<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DAFTAR SJ MAKLOON  ----------=*/    
    
    function data($i_menu,$folder,$dfrom,$dto){
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_keluar_gudang_jadi
            WHERE
                i_status <> '5'
                AND id_company = '".$this->session->userdata('id_company')."'
                $and
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
                        AND id_company = '".$this->session->userdata('id_company')."'
                        AND username = '".$this->session->userdata('username')."')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                b.e_bagian_name,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_gudang_jadi a
            INNER JOIN tr_bagian b ON
                (b.id = a.id_bagian_tujuan)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
                $bagian
            ORDER BY
                a.id", 
        FALSE);
            
        $datatables->edit('i_status', function ($data) {
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
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  CEK KODE  ----------*/

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_gudang_jadi');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  RUNNING NO DOK  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_gudang_jadi 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBK';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_keluar_gudang_jadi
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
    
    /*----------  BACA PARTNER (SUPPLIER)  ----------*/

    public function partner($cari,$ibagian)
    {
        return $this->db->query("
            SELECT
                id,
                e_bagian_name AS e_name
            FROM
                tr_bagian tb
            WHERE
                i_type = (
                SELECT
                    i_type
                FROM
                    tr_bagian
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = '".$this->session->userdata('id_company')."')
                AND i_bagian <> '$ibagian'
            ORDER BY
                e_bagian_name ASC
        ", FALSE);
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari)
    {
        return $this->db->query("            
            SELECT
                a.id,
                i_product_base,
                e_product_basename,
                e_color_name
            FROM
                tr_product_base a
            INNER JOIN tr_color b ON
                (b.i_color = a.i_color
                AND a.id_company = b.id_company)
            WHERE
                a.f_status = 't'
                AND (i_product_base ILIKE '%$cari%' 
                OR e_product_basename ILIKE '%$cari%')
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                2 ASC
        ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_gudang_jadi');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$ipartner,$eremarkh)
    {
        $data = array(
            'id'              => $id,
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'id_bagian_tujuan'=> $ipartner,
            'e_remark'        => $eremarkh,
            'd_entry'         => current_datetime(),
        );
        $this->db->insert('tm_keluar_gudang_jadi', $data);
    }

    public function simpandetail($id,$idproduct,$nquantity,$eremark)
    {
        $data = array(
            'id_company'      => $this->session->userdata('id_company'),
            'id_document'     => $id,
            'id_product_base' => $idproduct,
            'n_quantity'      => $nquantity,
            'n_quantity_sisa' => $nquantity,
            'e_remark'        => $eremark,
        );
        $this->db->insert('tm_keluar_gudang_jadi_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                a.id_bagian_tujuan,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                c.e_bagian_name,
                a.e_remark,
                b.e_bagian_name,
                c.e_bagian_name AS e_bagian_tujuan,
                a.i_status
            FROM
                tm_keluar_gudang_jadi a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_bagian c ON
                (c.id = a.id_bagian_tujuan)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT
                id_product_base,
                i_product_base,
                e_product_basename,
                e_color_name,
                n_quantity,
                a.e_remark
            FROM
                tm_keluar_gudang_jadi_item a
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product_base)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color
                AND b.id_company = c.id_company)
            WHERE
                a.id_document = '$id'
            ORDER BY
                a.id ASC
        ", FALSE);
    }

    /*----------  UPDATE DATA  ----------*/    

    public function update($id,$idocument,$ddocument,$ibagian,$ipartner,$eremarkh)
    {
        $data = array(
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'id_bagian_tujuan'=> $ipartner,
            'e_remark'        => $eremarkh,
            'd_update'        => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_gudang_jadi', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_gudang_jadi_item');
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_gudang_jadi', $data);
    }
    

}
/* End of file Mmaster.php */
