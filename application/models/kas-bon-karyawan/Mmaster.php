<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($folder, $i_menu, $dfrom, $dto){
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_kas_bon_karyawan
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
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')";
            }
        }
        $datatables->query("
                            SELECT
                                 0 AS NO,
                                 a.id, 
                                 a.i_document,
                                 a.i_bagian, 
                                 to_char(a.d_document, 'dd-mm-yyyy') as d_document, 
                                 a.id_karyawan,
                                 b.e_nama_karyawan,
                                 a.i_departement, 
                                 c.e_departement_name,
                                 a.v_jumlah,
                                 a.e_keperluan,
                                 a.e_remark,
                                 d.e_status_name as statusdok,
                                 d.label_color as labeldok,
                                 a.i_status,
                                 '$i_menu' AS i_menu,
                                 '$folder' AS folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto 
                              FROM
                                 tm_kas_bon_karyawan a 
                               INNER JOIN
                                  tr_karyawan b 
                                  ON (a.id_karyawan = b.id 
                                  AND a.id_company = b.id_company) 
                               INNER JOIN
                                public.tr_departement c 
                                  ON (a.i_departement = c.i_departement) 
                               INNER JOIN
                                  tr_status_document d 
                                  ON (d.i_status = a.i_status) 
                              WHERE
                                 a.i_status <> '5' 
                                 AND a.d_document BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company = '$id_company' $bagian 
                              GROUP BY
                                 a.id,
                                 a.i_document,
                                 a.d_document,
                                 b.e_nama_karyawan,
                                 c.e_departement_name,
                                 statusdok,
                                 labeldok,
                                 a.i_status",FALSE);

        $datatables->edit('v_jumlah', function ($data) {
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('statusdok', function ($data) {
            return '<span class="label label-'.$data['labeldok'].' label-rouded">'.$data['statusdok'].'</span>';
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
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('labeldok');
        $datatables->hide('i_bagian');
        $datatables->hide('id_karyawan');
        $datatables->hide('i_departement');
        return $datatables->generate();
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_giro');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian){
       $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_kas_bon_karyawan
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BON';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_kas_bon_karyawan
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
            AND id_company = '".$this->session->userdata("id_company")."'
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

    public function bagianpembuat(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_kas_bon_karyawan');
        return $this->db->get()->row()->id+1;
    }

    public function departement($cari){
        $idcompany = $this->session->userdata('id_company');

        return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_departement,
                                        a.e_departement_name
                                    FROM
                                        public.tr_departement a
                                    JOIN 
                                        tr_karyawan b 
                                    ON a.i_departement = b.i_departement
                                    WHERE
                                        a.f_status = 't'
                                    AND 
                                        a.e_departement_name ILIKE '%$cari%'
                                    ORDER BY a.e_departement_name
                                ", FALSE);
    }

    public function karyawan($cari, $idepartement){
        $idcompany = $this->session->userdata('id_company');

        return $this->db->query("
                                    SELECT DISTINCT
                                        a.id,
                                        a.e_nama_karyawan
                                    FROM
                                        tr_karyawan a
                                    WHERE
                                        a.f_status = 't'
                                    AND 
                                        a.id_company = '$idcompany'
                                    AND 
                                        a.e_nama_karyawan ILIKE '%$cari%'
                                    AND
                                        a.i_departement = '$idepartement'
                                ", FALSE);
    }

    public function insert($id, $ibagian, $idocument, $datedocument, $idepartement, $ikaryawan, $vjumlah, $ekeperluan, $eremark){
        $idcompany = $this->session->userdata('id_company');
        $data = array(
                        'id_company'        => $idcompany,
                        'id'                => $id,
                        'i_bagian'          => $ibagian,
                        'i_document'        => $idocument,
                        'd_document'        => $datedocument,
                        'i_departement'     => $idepartement,
                        'id_karyawan'       => $ikaryawan,
                        'e_keperluan'       => $ekeperluan,
                        'v_jumlah'          => $vjumlah,
                        'v_sisa'            => $vjumlah,
                        'e_remark'          => $eremark,
                        'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_kas_bon_karyawan', $data);
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id,$istatus){
        $iapprove = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $iapprove,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_kas_bon_karyawan', $data);
    }

    public function get_data($id){
        return $this->db->query("                                  
                                    SELECT
                                       a.id,
                                       a.i_bagian,
                                       b.e_bagian_name,
                                       a.i_document,
                                       to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                       a.i_departement,
                                       c.e_departement_name,
                                       a.id_karyawan,
                                       d.e_nama_karyawan,
                                       a.e_keperluan,
                                       a.v_jumlah,
                                       a.i_status,
                                       a.e_remark 
                                    FROM
                                       tm_kas_bon_karyawan a 
                                        JOIN
                                          tr_bagian b 
                                          ON a.i_bagian = b.i_bagian 
                                          AND a.id_company = b.id_company 
                                        JOIN
                                          public.tr_departement c 
                                          ON a.i_departement = c.i_departement 
                                        JOIN
                                          tr_karyawan d 
                                          ON a.id_karyawan = d.id 
                                          AND a.id_company = d.id_company
                                      WHERE a.id = '$id'
                                ", FALSE);
    }

    public function update($id, $ibagian, $idocument, $datedocument, $idepartement, $ikaryawan, $vjumlah, $ekeperluan, $eremark){
        $idcompany = $this->session->userdata('id_company');
        $data = array(
                        'i_bagian'          => $ibagian,
                        'i_document'        => $idocument,
                        'd_document'        => $datedocument,
                        'i_departement'     => $idepartement,
                        'id_karyawan'       => $ikaryawan,
                        'e_keperluan'       => $ekeperluan,
                        'v_jumlah'          => $vjumlah,
                        'v_sisa'            => $vjumlah,
                        'e_remark'          => $eremark,
                        'd_update'          => current_datetime(),
        );

    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tm_kas_bon_karyawan', $data);
    }   
}
/* End of file Mmaster.php */