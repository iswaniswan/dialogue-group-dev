<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function gudang()
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
        $this->db->from('tm_masuk_cutting');
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
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_cutting 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBM';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_masuk_cutting
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

    public function changestatus($id,$istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_masuk_cutting a
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
                        UPDATE tm_stb_cutting_item a
                        SET n_sisa_material = n_sisa_material - b.n_quantity_material
                        FROM (select id_reff_item, n_quantity as n_quantity_material from tm_masuk_cutting_item where id_document = '$id') AS b
                        WHERE a.id=b.id_reff_item
                    ", FALSE);
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_cutting');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_cutting', $data);
    }

    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "
            SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                d.i_document AS idocument,
                e_bagian_name,
                a.e_remark,
                a.i_status,
                e_status_name,
                label_color,
                f.i_level,
                l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_masuk_cutting a
            INNER JOIN tr_status_document c ON (c.i_status = a.i_status)
            LEFT JOIN tm_stb_cutting d on (a.id_reff = d.id)
            LEFT JOIN tr_bagian b ON (b.i_bagian = a.i_bagian_pengirim AND a.id_company = b.id_company)
            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
            ORDER BY
                a.id
            ", FALSE
        );
        
        $datatables->edit('i_status', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
            $i_status = trim($data['i_status']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
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
            // if (check_role($i_menu, 4) && ($i_status=='1')) {
            //     $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            // }
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

    public function datareferensi($cari,$ibagian,$ymperiode)
    {
        $iperdiode = date("Ym", strtotime('-6 month', strtotime($ymperiode)));
        return $this->db->query(
            "
            select id, i_document from tm_masuk_cutting where 
            i_status = '6' and id_company= '$this->id_company'
            and id not in (
                select id_reff from tm_masuk_cutting where id_company= '$this->id_company'
                and i_status in ('1','2','3','6')
            )
            ",
            FALSE
        );
    }

    public function dataheader($id)
    {
        return $this->db->query(
            "
            SELECT
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                e_bagian_name
            FROM
                tm_masuk_cutting a
            INNER JOIN tr_bagian g ON (g.i_bagian = a.i_bagian
                AND a.id_company = a.id_company)
            WHERE
                a.i_status = '6'
                AND a.id_company = '$this->id_company'
                AND a.id = '$id'
            ORDER BY
                a.id
            ",FALSE
        );
    }

    public function datadetail($id)
    {
        return $this->db->query("
            select
                a.id,
                a.id_material,
                i_material,
                e_material_name,
                a.id_product_wip,
                i_product_wip,
                b.e_product_wipname,
                c.e_color_name,
                e_satuan_name,
                a.n_sisa_material - coalesce(x.os,0) as n_quantity_sisa,
                a.n_quantity_material as n_quantity, 
                to_char(g.d_schedule, 'dd-mm-yyyy') as d_schedule 
            FROM
                tm_stb_cutting_item a
            INNER JOIN tr_product_wip b on (b.id = a.id_product_wip)
            INNER JOIN tr_color c on (c.i_color = b.i_color AND c.id_company = b.id_company)
            INNER JOIN tr_material d on (d.id = a.id_material AND d.id_company = a.id_company)
            INNER JOIN tr_satuan f on (f.i_satuan_code = d.i_satuan_code AND d.id_company = f.id_company)
            INNER JOIN tm_schedule_item g on (a.id_schedule_item = g.id)
            left join (
                select id_reff_item, sum(n_quantity) as os from tm_masuk_cutting_item a
                inner join tm_masuk_cutting b on (a.id_document = b.id) 
                inner join tm_stb_cutting_item c on (c.id_document = b.id_reff and a.id_reff_item = c.id)
                where c.id_document = '$id' and b.i_status in ('1', '2', '3', '6') group by 1
            ) as x on (a.id = x.id_reff_item)
            WHERE
                a.id_document = '$id'
            ORDER BY
                a.id_product_wip, a.id_material
        ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_cutting');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id,$idocument,$ddocument,$ibagian,$ireff,$eremark,$ipengirim)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_reff'           => $ireff,
            'e_remark'          => $eremark,
            'i_bagian_pengirim' => $ipengirim,
            'd_entry'           => current_datetime(),

        );
        $this->db->insert('tm_masuk_cutting', $data);
    }

    public function insertdetail($id,$id_reff_item,$idproductwip,$idmaterial,$nquantity,$eremark)
    {
        $data = array(
            'id_document'    => $id,
            'id_reff_item'        => $id_reff_item,
            'id_product_wip' => $idproductwip,
            'id_material'    => $idmaterial,
            'n_quantity'     => $nquantity,
            'e_remark'       => $eremark,
            'id_company'     => $this->session->userdata('id_company'),
        );
        $this->db->insert('tm_masuk_cutting_item', $data);
    }

    public function data_header($id)
    {
        return $this->db->query("
            SELECT
                a.i_bagian,
                d.e_bagian_name AS e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                i_bagian_pengirim,
                b.e_bagian_name as e_bagian_name_pengirim,
                a.id_reff,
                c.i_document as i_referensi,
                to_char(c.d_document, 'dd-mm-yyyy') AS d_referensi,
                a.e_remark,
                a.i_status
            FROM
                tm_masuk_cutting a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian_pengirim AND a.id_company = b.id_company)
            INNER JOIN tm_stb_cutting c ON (c.id = a.id_reff)
            inner JOIN tr_bagian d ON (d.i_bagian = a.i_bagian AND a.id_company = d.id_company)
            WHERE a.id = '$id'
        ", FALSE);
    }

    public function data_detail($id)
    {
        return $this->db->query("
            SELECT
                    a.id_reff_item,
                    a.id_material,
                    i_material,
                    e_material_name,
                    a.id_product_wip,
                    i_product_wip,
                    b.e_product_wipname,
                    c.e_color_name,
                    e_satuan_name,
                    g.n_sisa_material as n_quantity_sisa,
                    a.n_quantity as n_quantity, 
                    to_char(h.d_schedule, 'dd-mm-yyyy') as d_schedule ,
                    g.n_quantity_material as n_quantity_kirim,
                    a.e_remark
            FROM
                tm_masuk_cutting_item a
            INNER JOIN tr_product_wip b on (b.id = a.id_product_wip)
            INNER JOIN tr_color c on (c.i_color = b.i_color AND c.id_company = b.id_company)
            INNER JOIN tr_material d on (d.id = a.id_material AND d.id_company = a.id_company)
            INNER JOIN tr_satuan f on (f.i_satuan_code = d.i_satuan_code AND d.id_company = f.id_company)
            inner join tm_stb_cutting_item g on (a.id_reff_item = g.id)
            INNER JOIN tm_schedule_item h on (g.id_schedule_item = h.id)
            where a.id_document = '$id'
            ORDER by a.id_product_wip, a.id_material
        ", FALSE);
    }


    public function updateheader($id,$idocument,$ddocument,$ibagian,$ireff,$eremark,$ipengirim)
    {
        $data = array(
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_reff'           => $ireff,
            'e_remark'          => $eremark,
            'i_bagian_pengirim' => $ipengirim,
            'd_update'           => current_datetime(),

        );
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_cutting', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_cutting_item');
    }

}
/* End of file Mmaster.php */