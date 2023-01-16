<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }    

    public function bacakunjungan(){
        return $this->db->order_by('i_kunjungan_type','ASC')->get('tr_kunjungan_type')->result();
    }    

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

    public function kodearea()
    {
        return $this->db->query("SELECT DISTINCT id, i_area, e_area FROM tr_area WHERE f_status = true
        ", false);
    }

    public function datarencana($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT  
                id_rencana,
                nama_rencana
            FROM
            tr_rencana 
            WHERE (upper(nama_rencana) LIKE '%$cari%') ", FALSE);
    }

    public function datacustomer($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT  
                a.id as id_customer,
                a.i_customer,
                a.e_customer_name,
                a.id_area,
                b.e_area as area
            FROM
            tr_customer a
            INNER JOIN tr_area b on (b.id = a.id_area)
            WHERE a.id_company = '$idcompany' and a.f_status = 't'
            AND (upper(a.i_customer) LIKE '%$cari%'
                OR upper(a.e_customer_name) LIKE '%$cari%') ", FALSE);
    }

    public function getcustomer($ecust){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                a.id as id_customer,
                a.i_customer,
                a.e_customer_name,
                a.id_area,
                b.e_area as area
            FROM
            tr_customer a
            INNER JOIN tr_area b on (b.id = a.id_area)
            WHERE a.id_company = '$idcompany' AND a.id = '$ecust'
        ", FALSE);
    }
    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_rrkh');
        return $this->db->get()->row()->id+1;
    }
      
    public function insertheader($id, $dok_rrkh, $ibagian, $drrkh, $kode_area, $kode_salesman){	
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                        'id_company'    => $idcompany,
                        'id'            => $id,
                        'i_document'    => $dok_rrkh,
                        'd_document'    => $drrkh,
                        'i_bagian'      => $ibagian,
                        'id_area'       => $kode_area,
                        'id_salesman'   => $kode_salesman,
                        'd_entry'       => current_datetime(),
        );
        $this->db->insert('tm_rrkh', $data);
    }

    // public function insertdetaidl($id, $iproduct, $icolor, $nqtyproduct, $edesc){}
    public function insertdetail($id, $idcust, $waktu, $idrencana, $real, $bukti, $eremark){
        $idcompany  = $this->session->userdata('id_company');
    	$data = array(
                        'id_company'    => $idcompany,
                        'id_rrkh'       => $id,
                        'id_customer'   => $idcust,
                        'waktu'         => $waktu,
                        'id_rencana'    => $idrencana,
                        'f_real'        => $real,
                        'f_bukti'       => $bukti,
                        'keterangan'    => $eremark,
        );
    	$this->db->insert('tm_rrkh_item', $data);
    } 

    public function cek_data($id, $idcompany){
        return $this->db->query(" SELECT
                a.id,
                a.id_company,
                a.i_bagian,
                b.e_bagian_name,
                a.i_document,
                a.d_document,
                a.id_area,
                a.id_salesman,
                a.i_status,
                a.i_approve,
                a.d_approve,
                d.id as id_area,
                d.i_area,
                d.e_area,
                e.id as id_sales,
                e.i_sales,
                e.e_sales
            FROM
                tm_rrkh a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_status_document c ON (c.i_status = a.i_status)
            INNER JOIN tr_area d ON (d.i_area = a.id_area)
            INNER JOIN tr_salesman e ON (e.i_sales = a.id_salesman AND a.id_company = e.id_company)
            WHERE                                        
                a.id = '$id'
            AND
                a.id_company = '$idcompany' ", FALSE);
    }

    public function cek_datadetail($id, $idcompany){
        return $this->db->query("SELECT
                a.id_ri,
                a.id_company,
                a.id_rrkh,
                a.id_customer,
                a.waktu,
                a.id_rencana,
                a.f_real,
                a.f_bukti,
                a.keterangan,
                b.id as id_customer,
                b.i_customer,
                b.e_customer_name,
                d.id as id_area,
                d.i_area,
                d.e_area,
                c.nama_rencana
            FROM
                tm_rrkh_item a 
                inner join tr_customer b ON (a.id_customer = b.id AND a.id_company = b.id_company)
                INNER JOIN tr_rencana c on (a.id_rencana = c.id_rencana)
                INNER JOIN tr_area d on (d.id = b.id_area)
            WHERE a.id_rrkh = '$id' 
            ", FALSE);
    }

    public function kodesalesman()
    {
        return $this->db->query("SELECT DISTINCT id, i_sales, e_sales FROM tr_salesman WHERE f_status = true
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

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("SELECT 
                substring(i_document, 1, 4) AS kode 
            FROM tm_rrkh 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'RRKH';
        }
        $query  = $this->db->query("SELECT
                max(substring(i_document, 11, 6)) AS max
            FROM
                tm_rrkh
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 4) = '$kode'
            AND substring(i_document, 6, 2) = substring('$thbl',1,2)
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
                tm_rrkh
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
                d.e_area,
                e.e_sales,
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
                tm_rrkh a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            INNER JOIN tr_status_document c ON (c.i_status = a.i_status)
            INNER JOIN tr_area d ON (d.i_area = a.id_area)
            INNER JOIN tr_salesman e ON (e.i_sales = a.id_salesman AND a.id_company = e.id_company)
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

    public function getsalesman($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT(i_salesman),
                e_salesman_name
            FROM
                tr_customer_salesman
            WHERE
                i_area = '$iarea'
                AND (UPPER(e_salesman_name) LIKE '%$cari%'
                OR UPPER(i_salesman) LIKE '%$cari%')
            ORDER BY
                i_salesman", 
        FALSE);
    } 
/* 
    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_customer,
                a.e_customer_name
            FROM
                (
                SELECT
                    a.i_customer,
                    a.e_customer_name,
                    b.i_city,
                    b.e_city_name
                FROM
                    tr_customer a,
                    tr_city b
                WHERE
                    a.i_area = '$iarea'
                    AND a.i_area = b.i_area
                    AND a.i_city = b.i_city
                    AND (UPPER(a.i_customer) LIKE '%$cari%'
                    OR UPPER(a.e_customer_name) LIKE '%$cari%')
            UNION ALL
                SELECT
                    a.i_customer,
                    a.e_customer_name,
                    b.i_city,
                    b.e_city_name
                FROM
                    tr_customer_tmp a
                LEFT JOIN tr_city b ON
                    (a.i_area = b.i_area
                    AND a.i_city = b.i_city)
                WHERE
                    a.i_area = '$iarea'
                    AND (a.i_customer LIKE '%000'
                    OR UPPER(a.e_customer_name) LIKE '%$cari%') ) AS a
            ORDER BY
                a.i_customer DESC", 
        FALSE);
    } */

    public function getcity($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_city,
                e_city_name
            FROM
                tr_city
            WHERE
                i_area = '$iarea'
                AND (UPPER(e_city_name) LIKE '%$cari%'
                OR UPPER(i_city) LIKE '%$cari%')
            ORDER BY
                e_city_name", 
        FALSE);
    }

    public function cekdata($isalesman, $drrkh, $iarea){
        $this->db->select('*');
        $this->db->from('tm_rrkh');
        $this->db->where('i_salesman',$isalesman);
        $this->db->where('d_rrkh',$drrkh);
        $this->db->where('i_area',$iarea);
        return $this->db->get();
    }

    // public function insertheader($isalesman, $drrkh, $iarea,$drec1){
    //     $dentry = current_datetime();
    //     $userid = $this->session->userdata("username");
    //     if($drec1!=''){
    //         $this->db->set(
    //             array(
    //                 'i_salesman'  => $isalesman,
    //                 'd_rrkh'      => $drrkh,
    //                 'i_area'      => $iarea,
    //                 'd_entry'     => $dentry,
    //                 'i_entry'     => $userid,
    //                 'd_receive1'  => $drec1));
    //         $this->db->insert('tm_rrkh');
    //     }else{
    //         $this->db->set(
    //             array(
    //                 'i_salesman'  => $isalesman,
    //                 'd_rrkh'      => $drrkh,
    //                 'i_area'      => $iarea,
    //                 'd_entry'     => $dentry,
    //                 'i_entry'     => $userid));
    //         $this->db->insert('tm_rrkh');
    //     }
    // }

    // public function insertdetail($isalesman,$drrkh,$iarea,$icustomer,$ikunjungantype,$icity,$fkunjunganrealisasi,$fkunjunganvalid,$eremark,$i){
    //     $dentry = current_datetime();
    //     $userid = $this->session->userdata("username");
    //     if($eremark=='') {
    //         $eremark=null;
    //     }
    //     $this->db->set(
    //         array(
    //             'd_rrkh'                => $drrkh,
    //             'i_salesman'            => $isalesman,
    //             'i_area'                => $iarea,
    //             'i_customer'            => $icustomer,
    //             'i_kunjungan_type'      => $ikunjungantype,
    //             'i_city'                => $icity,
    //             'f_kunjungan_realisasi' => $fkunjunganrealisasi,
    //             'f_kunjungan_valid'     => $fkunjunganvalid,
    //             'e_remark'              => $eremark,
    //             'd_entry'               => $dentry,
    //             'i_entry'               => $userid,
    //             'n_item_no'             => $i
    //         )
    //     );
    //     $this->db->insert('tm_rrkh_item');
    // }
}

/* End of file Mmaster.php */