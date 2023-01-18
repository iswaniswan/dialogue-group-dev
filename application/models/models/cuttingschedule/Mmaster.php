<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = " AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }


        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_schedule
            WHERE
                i_status <> '5'
                AND id_company = '".$this->session->userdata('id_company')."'
                $where
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')

        ", FALSE);
        if ($this->session->userdata('i_departement')=='4' || $this->session->userdata('i_departement')=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = " AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = " AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 as no,
                a.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                e_remark,
                a.i_status,
                e_status_name,
                label_color,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                f.i_level,
                l.e_level_name
            FROM
                tm_schedule a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
            $where
            $bagian
            ORDER BY a.id ASC
        ", FALSE);

        $datatables->edit('i_status', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
            // return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = $data['id'];
            $i_status      = trim($data['i_status']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $dfrom         = $data['dfrom'];
            $dto           = $data['dto'];
            $data          = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function changestatus($id,$istatus)
    {
        $now = date('Y-m-d');
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("
                SELECT b.i_menu , a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut from tm_schedule a
                inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
                where a.id = '$id'
                group by 1,2", FALSE)->row();

            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0 ) {
                    $data = array(
                        'i_status'  => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("delete from tm_menu_approve where i_menu = '$this->i_menu' and i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
                if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $this->db->query("
                    INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                     ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_schedule');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_schedule', $data);
    }

    public function bagian()
    {
        // $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        // $this->db->from('tr_bagian a');
        // $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        // $this->db->where('i_departement', $this->session->userdata('i_departement'));
        // $this->db->where('i_level', $this->session->userdata('i_level'));
        // $this->db->where('username', $this->session->userdata('username'));
        // $this->db->where('a.id_company', $this->session->userdata('id_company'));        
        // $this->db->order_by('e_bagian_name');
        // return $this->db->get();
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            LEFT JOIN tr_type c on (a.i_type = c.i_type)
            LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_schedule');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_schedule 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SC';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_schedule
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function jenisbarang()
    {
        return $this->db->query("
            SELECT
                i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                i_kode_group_barang = 'GRB0002'
                AND f_status = 't'
                AND id_company = '".$this->session->userdata('id_company')."'
        ", FALSE);
    }

    public function product($cari,$ijenis)
    {
        if ($ijenis!='all' && $ijenis!='') {
            $and = "AND a.i_type_code = '$ijenis' ";
        }else{
            $and = "";
        }
        return $this->db->query("            
            SELECT
                DISTINCT a.i_product_wip,
                e_product_wipname,
                a.i_color,
                e_color_name
            FROM
                tr_product_wip a
            INNER JOIN tr_polacutting_new b on (b.id_product_wip = a.id AND b.id_company = a.id_company)
            INNER JOIN tr_color c on (c.i_color = a.i_color AND a.id_company = c.id_company)
            where a.f_status = 't'
                AND (a.i_product_wip ILIKE '%$cari%'
                OR e_product_wipname ILIKE '%$cari%')
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
            ORDER BY
                a.i_product_wip
        ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_schedule');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id,$ibagian,$ischedule,$ddocument,$eremarkh)
    {
        $data = array(
            'id'          => $id,
            'id_company'  => $this->session->userdata('id_company'),
            'i_document'  => $ischedule,
            'd_document'  => $ddocument,
            'i_bagian'    => $ibagian,
            'e_remark'    => $eremarkh,
            'd_entry'     => current_datetime(),
        );
        $this->db->insert('tm_schedule', $data);
    }

    public function insertdetail($id,$dschdetail,$iproduct,$icolor,$nquantity,$eremark)
    {
        $data = array(
            'id_schedule'         => $id,
            'd_schedule'          => $dschdetail,
            'i_product_wip'       => $iproduct,
            'i_color'             => $icolor,
            'e_remark'            => $eremark,
            'n_quantity'          => $nquantity,
            'n_quantity_sisa'     => $nquantity,
            'n_quantity_sisa_bon' => $nquantity,
            'id_company'          => $this->session->userdata('id_company'),
        );
        $this->db->insert('tm_schedule_item', $data);
    }

    public function insertitemdetail($id,$iproduct,$icolor,$nquantity)
    {
        $company = $this->session->id_company;
        $this->db->query("
            INSERT
                INTO
                tm_schedule_item_detail (
                id_schedule,
                id_company,
                i_product_wip,
                i_color,
                n_quantity,
                n_quantity_sisa,
                id_material,
                n_set,
                n_gelar,
                n_jumlah_gelar,
                id_product_wip,
                id_pola_cutting)
            SELECT
                $id AS id_schedule,
                $company AS id_company,
                '$iproduct' AS i_product_wip,
                '$icolor' AS i_color,
                $nquantity AS n_quantity,
                $nquantity AS n_quantity_sisa,
                b.id AS id_material,
                v_set AS n_set,
                v_gelar AS n_gelar,
                $nquantity / v_set AS n_jumlah_gelar,
                a.id_product_wip, a.id
            FROM
                tr_polacutting_new a
            INNER JOIN tr_material b ON (b.id = a.id_material AND a.id_company = b.id_company)
            INNER JOIN tr_product_wip c ON (a.id_product_wip = c.id)
            WHERE
                a.f_status = 't'
                AND c.i_product_wip = '$iproduct'
                AND c.i_color = '$icolor'
                AND a.id_company = $company
        ", FALSE);
    }

    public function cek_data($id)
    {
        $this->db->select('a.*, e_bagian_name');
        $this->db->from('tm_schedule a');
        $this->db->join('tr_bagian b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company');
        $this->db->where('a.id', $id);
        return $this->db->get();
    }

    public function cek_datadetail($idschedule)
    {
        return $this->db->query(
            "
            SELECT
                DISTINCT a.*,
                b.e_product_wipname,
                c.e_color_name
            FROM
                tm_schedule_item a
            INNER JOIN tr_product_wip b ON
                (b.i_product_wip = a.i_product_wip 
                AND a.id_company = b.id_company)
            INNER JOIN tr_color c ON
                (c.i_color = a.i_color
                AND a.id_company = c.id_company)
            WHERE a.id_schedule = '$idschedule'
            ORDER BY a.id
            ",
            false
        );
    }

    public function update($id,$ibagian,$ischedule,$ddocument,$eremarkh)
    {
        $data = array(
            'id_company'  => $this->session->userdata('id_company'),
            'i_document'  => $ischedule,
            'd_document'  => $ddocument,
            'i_bagian'    => $ibagian,
            'i_status'    => '1',
            'e_remark'    => $eremarkh,
            'd_update'    => current_datetime(),
        );

        $this->db->where('id', $id);
        $this->db->update('tm_schedule', $data);
    }

    public function deletedetail($id)
    {
        $this->db->query("DELETE FROM tm_schedule_item WHERE id_schedule='$id'");
        $this->db->query("DELETE FROM tm_schedule_item_detail WHERE id_schedule='$id'");
    }
}
/* End of file Mmaster.php */