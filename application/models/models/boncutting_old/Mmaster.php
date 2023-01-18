<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function bagian()
    {
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
        $this->db->from('tm_keluar_cutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_cutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_cutting 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'STB';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_keluar_cutting
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
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

    public function material($cari,$ibagian)
    {
        return $this->db->query("
            SELECT a.id, a.id_company, to_char(a.d_schedule, 'dd-mm-yyyy') as d_schedule,
            id_material, n_sisa_material_keluar AS n_sisa_material, c.i_material , c.e_material_name , initcap(d.e_satuan_name)  as e_satuan_name
            from tm_schedule_item a
            inner join tm_schedule b on (a.id_schedule = b.id)
            inner join tr_material c on (a.id_material = c.id)
            inner join tr_satuan d on (c.i_satuan_code = d.i_satuan_code and c.id_company = d.id_company)
            where b.i_status = '6' and a.n_sisa_material_keluar > 0  and a.id_company= '$this->id_company'
            and (c.e_material_name ilike '%$cari%' or c.i_material ilike '%$cari%' or to_char(a.d_schedule, 'dd-mm-yyyy') ilike '%$cari%')
            order by d_schedule asc
        ", FALSE);
    }

    public function getmaterial($id_schedule_item)
    {
        return $this->db->query("SELECT
                to_char(a.d_schedule, 'dd-mm-yyyy') AS d_schedule,
                n_sisa_material_keluar AS n_quantity,
                d.i_product_wip,
                initcap(d.e_product_wipname) AS e_product_wipname,
                a.*
            FROM
                tm_schedule_item a
            INNER JOIN tr_material c ON
                (a.id_material = c.id)
            INNER JOIN tr_product_wip d ON
                (d.id = a.id_product_wip)
            WHERE a.id = '$id_schedule_item'
        ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_cutting');
        return $this->db->get()->row()->id+1;
    }


    public function insertheader($id,$ibagian,$istb_cutting,$dstb_cutting,$remark)
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'                => $id,
            'id_company'        => $idcompany,  
            'i_document'        => $istb_cutting,
            'd_document'        => $dstb_cutting,
            'i_bagian'          => $ibagian,
            /* 'i_bagian_tujuan'   => $i_bagian_tujuan, */
            'e_remark'          => $remark,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_keluar_cutting', $data);
    }

    public function insertdetail($id,$idscheduleitem, $nquantity,$eremark)
    {
        /* $idcompany  = $this->session->userdata('id_company'); */
        $this->db->query("INSERT INTO tm_keluar_cutting_item 
            (id_document, id_reff, id_product_wip, n_quantity_wip, n_quantity_wip_sisa, id_material, n_quantity, n_quantity_sisa, e_remark, id_company)
            SELECT '$id', id, id_product_wip, n_quantity_wip, n_quantity_wip, id_material , '$nquantity', '$nquantity', '$eremark', id_company 
            FROM tm_schedule_item WHERE id = '$idscheduleitem'
        ", FALSE);
    }


    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }


    public function changestatus($id,$istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_keluar_cutting a
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
                if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    $this->db->query("
                        UPDATE tm_schedule_item a
                        SET n_sisa_material_keluar = n_sisa_material_keluar - b.n_quantity
                        FROM (select id_reff, n_quantity from tm_keluar_cutting_item where id_document = '$id') AS b
                        WHERE a.id=b.id_reff
                    ", FALSE);
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'i_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_cutting');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_cutting', $data);
    }

	public function data($i_menu,$folder,$dfrom,$dto){
        $idcompany  = $this->session->userdata('id_company');

        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_keluar_cutting
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
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN (SELECT
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
        $datatables->query("SELECT
                0 AS NO,
                a.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                e_bagian_name,
                e_remark,
                a.i_status,
                e_status_name,
                label_color,
                f.i_level,
			    l.e_level_name,
                a.id_company,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_cutting a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_status_document c ON (c.i_status = a.i_status)
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE a.i_status <> '5'
            AND 
                a.id_company = '$idcompany'
                $where
                $bagian
            ORDER BY
                a.id DESC 
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });
        
        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $i_menu  = $data['i_menu'];
            $i_status= $data['i_status'];
            $i_level = $data['i_level'];
            $folder  = $data['folder'];
            $dfrom   = $data['dfrom'];
            $dto     = $data['dto'];
            $data    = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            // if (check_role($i_menu, 5)) {
            //     if ($i_status == '6') {
            //         $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
            //     }
            // }
            // if (check_role($i_menu, 4) && ($i_status=='1')) {
            //     $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            // }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('id_company');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        
        return $datatables->generate();
    }

    public function dataheader($id)
    {
        return $this->db->query("
            SELECT a.*, b.e_bagian_name from tm_keluar_cutting a
            inner join tr_bagian b on (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            where a.id = '$id'
        ", FALSE);
    }

    public function datadetail($id)
    {
        return $this->db->query("
            SELECT a.*, b.i_material, b.e_material_name, c.e_satuan_name, to_char(d.d_schedule, 'dd-mm-yyyy') as d_schedule, 
            d.n_sisa_material_keluar as n_sisa_material_schedule, d.n_quantity_material,
            e.i_product_wip,
                initcap(e.e_product_wipname) AS e_product_wipname
            from tm_keluar_cutting_item a
            inner join tr_material b on (b.id = a.id_material)
            inner join tr_satuan c on (c.i_satuan_code = b.i_satuan_code AND b.id_company = c.id_company)
            inner join tm_schedule_item d on (a.id_reff = d.id)            
            INNER JOIN tr_product_wip e ON
                (e.id = a.id_product_wip)
            where a.id_document = '$id'
        ", FALSE);
    }

    public function updateheader($id,$ibagian,$istb_cutting,$dstb_cutting,$remark)
    {
         $data = array(
            'id'                => $id,
            'i_bagian'          => $ibagian,
            'i_document'        => $istb_cutting,
            'd_document'        => $dstb_cutting,
            'e_remark'          => $remark,
            'i_status'          => '1',
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_keluar_cutting', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_cutting_item');
    }   
}
/* End of file Mmaster.php */
