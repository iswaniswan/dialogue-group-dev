<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    public function data($i_menu,$folder,$dfrom,$dto)
    {

        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_masuk_makloonqcset
            WHERE
                i_status <> '5'
                and d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        /*AND i_level = '".$this->session->userdata('i_level')."'*/
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')

        ", FALSE);
        if ($this->session->userdata('i_departement')=='1') {
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
                        /*AND i_level = '".$this->session->userdata('i_level')."'*/
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_supplier_name,
                c.e_type_makloon_name,
                array_agg(distinct(dt.i_document)) AS i_reff,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                f.i_level,
			    l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM tm_masuk_makloonqcset a
            inner join tm_masuk_makloonqcset_item ai on (a.id = ai.id_document)
            inner join tm_keluar_makloon_qcset_new dt on (dt.id = ai.id_document_reff)
            INNER JOIN tr_supplier b ON (b.id = a.id_supplier)
            INNER JOIN tr_type_makloon c ON (c.id = a.id_type_makloon)
            INNER JOIN tr_status_document d ON (d.i_status = a.i_status)
            LEFT JOIN public.tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
            WHERE a.i_status <> '5' and a.d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and a.id_company = '$id_company' $bagian
            group by  a.id, a.i_document, a.d_document, b.e_supplier_name, c.e_type_makloon_name, e_status_name, label_color, a.i_status, f.i_level, l.e_level_name
        ", FALSE);

        $datatables->edit('i_status', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->edit('i_reff', function ($data) {
            return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['i_reff']))).'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            
            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box fa-lg'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close fa-lg'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('e_status_name');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

      /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
    
    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_type', '08');    
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }
    
    /*----------  DATA TYPE MAKLOON SESUAI MENU  ----------*/

    public function typemakloon($i_menu)
    {
        $this->db->select('b.id, b.e_type_makloon_name');
        $this->db->from('tr_makloon_menu a');
        $this->db->join('tr_type_makloon b','b.id = a.id_makloon AND a.id_company = b.id_company','inner');
        $this->db->where('i_menu', $i_menu);
        $this->db->where('b.f_status', 't');
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian,$itype)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_masuk_makloonqcset 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_type_makloon = '$itype'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJ';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_masuk_makloonqcset
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_type_makloon = '$itype'
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
            while($n < 4){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "0001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }
    
    /*----------  BACA PARTNER (SUPPLIER)  ----------*/

    public function partner($cari) {
        return $this->db->query("
            SELECT
                DISTINCT b.id,
                b.e_supplier_name
            FROM
                tr_supplier_makloon a
            INNER JOIN tr_supplier b ON
                (b.i_supplier = a.i_supplier
                AND a.id_company = b.id_company)
            INNER JOIN tr_type_makloon c ON
                (c.i_type_makloon = a.i_type_makloon
                AND a.id_company = c.id_company)
            WHERE
                b.f_status = 't'
                AND (e_supplier_name ILIKE '%$cari%')
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                b.e_supplier_name
        ", FALSE);
    }


   /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_makloonqcset');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$itype,$ipartner,$idocumentsup,$eremarkh,$dsup)
    {
        $data = array(
            'id'              => $id,
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'id_type_makloon' => $itype,
            'id_supplier'     => $ipartner,
            'i_document_supplier' => $idocumentsup,
            'e_remark'        => $eremarkh,
            'd_entry'         => current_datetime(),
            'd_supplier'      => $dsup
        );
        $this->db->insert('tm_masuk_makloonqcset', $data);
    }

    public function simpandetail($id, $id_reff, $idpanel, $qty, $eremark, $idmarker)
    {
        $data = array(
            'id_company'      => $this->session->userdata('id_company'),
            'id_document'     => $id,
            'id_document_reff'=> $id_reff,
            'id_panel_item'   => $idpanel,
            'n_quantity'      => $qty,
            'n_quantity_sisa' => $qty,
            'e_remark'        => $eremark,
            'id_marker'       => $idmarker
        );
        $this->db->insert('tm_masuk_makloonqcset_item', $data);
    }

    public function changestatus($id,$istatus) {
        // if ($istatus=='6') {
        //     $data = array(
        //         'i_status'  => $istatus,
        //         'e_approve' => $this->session->userdata('username'),
        //         'd_approve' => date('Y-m-d'),
        //     );
        // }else{
        //     $data = array(
        //         'i_status' => $istatus,
        //     );
        // }
        // $this->db->where('id', $id);
        // $this->db->update('tm_masuk_makloonqcset', $data);

        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tm_masuk_makloonqcset a
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
            		$data = array(
	                    'i_status' => $istatus,
	                    'i_approve_urutan' => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
                $now = date('Y-m-d');
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_makloon_qcset_new');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloonqcset', $data);
    }

     /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                a.id_type_makloon,
                a.id_supplier,
                b.e_supplier_name,
                a.i_document_supplier,
                a.d_supplier,
                a.i_status,
                a.e_remark,
                e.e_bagian_name,
                f.e_type_makloon_name
            FROM tm_masuk_makloonqcset a
            INNER JOIN tr_supplier b ON (b.id = a.id_supplier)
            INNER JOIN tr_status_document d ON (d.i_status = a.i_status)
            INNER JOIN tr_bagian e ON (e.i_bagian = a.i_bagian AND a.id_company = e.id_company)
            INNER JOIN tr_type_makloon f ON (f.id = a.id_type_makloon)
            WHERE a.id = '$id'
        ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT a.id, b.id_document_reff, k.i_document, c.id as id_product_wip, c.i_product_wip, c.e_product_wipname, d.e_color_name, ki.n_quantity as keluarfull, ki.n_quantity as keluar, COALESCE(b.n_quantity, 0) as masuk, b.e_remark, p.id as id_panel, p.bagian, p.i_panel, b.id_marker, m.e_marker_name
            FROM tm_masuk_makloonqcset a
            INNER JOIN tm_masuk_makloonqcset_item b ON (a.id = b.id_document)
            INNER JOIN tm_keluar_makloon_qcset_new k ON (k.id = b.id_document_reff)
            INNER JOIN tm_keluar_makloon_qcset_item_new ki ON (k.id = ki.id_document and b.id_panel_item = ki.id_panel_item)
            INNER JOIN tm_panel_item p ON (b.id_panel_item = p.id AND b.id_marker = p.id_marker)
            INNER JOIN tr_marker m ON (b.id_marker = m.id)
            inner join tr_product_wip c on (p.id_product_wip = c.id and c.id_company = $this->id_company)
            INNER JOIN tr_color d ON (c.i_color = d.i_color and c.id_company = d.id_company)
            WHERE a.id = '$id'
            ORDER BY a.i_document, c.e_product_wipname ASC
        ", FALSE);
    }


    public function update($id,$idocument,$ddocument,$ibagian,$itype,$ipartner,$idocumentsup,$eremarkh,$dsup)
    {
        $data = array(
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'id_type_makloon' => $itype,
            'id_supplier'     => $ipartner,
            'i_document_supplier' => $idocumentsup,
            'e_remark'        => $eremarkh,
            'd_update'        => current_datetime(),
            'd_supplier'      => $dsup
        );
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloonqcset', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id) {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_masuk_makloonqcset_item');
    }


    public function referensieks($cari,$partner, $itype)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT DISTINCT a.i_document, a.id, to_char(d_document, 'dd-mm-yyyy') as d_document
            FROM tm_keluar_makloon_qcset_new a
            INNER JOIN tm_keluar_makloon_qcset_item_new b ON (a.id = b.id_document)
            INNER JOIN tm_panel_item e ON (b.id_panel_item = e.id )
            inner join tr_product_wip c on (e.id_product_wip = c.id and c.id_company = $this->id_company)
            INNER JOIN tr_color d ON (c.i_color = d.i_color and c.id_company = d.id_company)
            left join tm_masuk_makloonqcset_item f ON (f.id_document_reff = b.id_document and f.id_document_reff = a.id)
            WHERE a.i_status = '6' AND COALESCE(b.n_quantity, 0) > 0 AND a.id_supplier = '$partner' and a.id_type_makloon = '$itype' AND (COALESCE(b.n_quantity_sisa, 0) > 0 OR f.id_document_reff IS NULL)
                AND (TRIM(a.i_document) ILIKE '$cari%')
        ", FALSE);
    }

    public function getdetailrefeks($id)
    {
        if(count($id) < 2){

        $and = "AND a.id IN (".$id[0].")";
        }
        else {
        $in_str = "'".implode("', '", $id)."'";
        $and   = "AND a.id IN (".$in_str.")";
        }
        return $this->db->query(
            "SELECT a.i_document, a.id, b.id_panel_item, c.id as id_product_wip, 
            c.i_product_wip, c.e_product_wipname, d.e_color_name, b.n_quantity,e.id as id_panel, 
            e.bagian, e.i_panel, b.id_marker, m.e_marker_name, b.e_remark
            FROM tm_keluar_makloon_qcset_new a
            INNER JOIN tm_keluar_makloon_qcset_item_new b ON (a.id = b.id_document)
            INNER JOIN tm_panel_item e ON (b.id_panel_item = e.id and b.id_marker = e.id_marker)
            inner join tr_product_wip c on (e.id_product_wip = c.id and c.id_company = $this->id_company)
            INNER JOIN tr_color d ON (c.i_color = d.i_color and c.id_company = d.id_company)
            INNER JOIN tr_marker m ON (b.id_marker = m.id)
            WHERE  COALESCE (b.n_quantity, 0) > 0 $and
            ORDER BY
                a.i_document, c.e_product_wipname ASC
        ", FALSE);
    }
    

    public function updatekeluar($id_document_reff, $idpanel, $nquantity)
    {
        $this->db->query("
            update tm_keluar_makloon_qcset_item_new set n_quantity_sisa = n_quantity - $nquantity where id_document = '$id_document_reff' and id_panel_item = '$idpanel'
        ", FALSE);
    }

      public function cek_kode($kode,$ibagian, $itype) {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_makloonqcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_type_makloon', $itype);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold,$ibagian, $itype, $itypeold) {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_makloonqcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_type_makloon', $itype);
        // $this->db->where('id_type_makloon <>', $itypeold);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }
}
/* End of file Mmaster.php */


