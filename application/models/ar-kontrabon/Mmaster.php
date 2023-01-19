<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($folder,$i_menu,$dfrom,$dto,$ipartner,$idpartner,$epartnertype){
        // $dfrom = date('Y-m-d', strtotime($dfrom));
        // $dto   = date('Y-m-d', strtotime($dto));

        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_kontra_bon
            WHERE
                i_status <> '5'
                AND d_document between to_date('$dfrom','dd-mm-yyyy') AND to_date('$dto','dd-mm-yyyy') AND  id_company = '$id_company'
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

        if ($ipartner == 'ALL' || $idpartner == 'ALL') {
            $where = "";
        } else {
            if($ipartner != 'ALL' || $idpartner != 'ALL'){
                $a = explode("-", $ipartner);
                $idpartner = $a[0];
                $epartnertype = $a[1];
            $where = "AND a.id_partner = '$idpartner'
                      AND e_partner_type = '$epartnertype'";
            }else{
                $where="";
            }
        }
        
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT DISTINCT
                               0 as no,
                               a.id,
                               d.e_bagian_name,
                               a.i_document,
                               to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                              CASE
                                 WHEN e_partner_type = 'supplier' THEN f.e_supplier_name
                                 WHEN e_partner_type = 'customer' THEN g.e_customer_name
                                 WHEN e_partner_type = 'karyawan' THEN h.e_nama_karyawan
                                 WHEN e_partner_type = 'bagian' THEN i.e_bagian_name
                              END AS e_partner_name,
                               a.e_partner_type,
                               a.v_total,
                               a.e_remark,
                               a.i_status,
                               c.e_status_name,
                               '$i_menu' as i_menu,
                               '$folder' as folder,
                               '$dfrom' AS dfrom,
                               '$dto' AS dto,
                               c. label_color,
                               e.i_jenis_faktur
                            FROM
                                tm_kontra_bon a 
                                INNER JOIN
                                   tr_status_document c 
                                   ON (c.i_status = a.i_status) 
                                INNER JOIN
                                   tr_bagian d 
                                   ON (a.i_bagian = d.i_bagian 
                                   AND a.id_company = d.id_company) 
                                INNER JOIN
                                   tm_kontra_bon_item e 
                                   ON (a.id = e.id_document 
                                   AND a.id_company = e.id_company) 
                                LEFT JOIN tr_supplier f 
                                    ON
                                    (f.id = a.id_partner
                                    AND f.id_company = a.id_company)
                                LEFT JOIN tr_customer g 
                                    ON
                                    (g.id = a.id_partner
                                    AND g.id_company = a.id_company)
                                LEFT JOIN tr_karyawan h 
                                    ON
                                    (h.id = a.id_partner 
                                    AND h.id_company = a.id_company)
                                LEFT JOIN tr_bagian i 
                                    ON
                                    (i.id = a.id_partner
                                    AND i.id_company = a.id_company)
                            WHERE
                               a.d_document 
                               BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                               AND a.id_company = '$id_company' 
                               $bagian 
                               $where
                            ORDER BY 
                               a.i_document desc
        ", FALSE);


        $datatables->edit('v_total', function ($data) {
            $data = "Rp. ".number_format($data['v_total']);
            return $data;
        });
          
        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = trim($data['id']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $i_status      = $data['i_status'];
            $dfrom         = $data['dfrom'];
            $dto           = $data['dto'];
            $ijenis        = $data['i_jenis_faktur'];
            $epartnertype  = $data['e_partner_type'];
            $data          = '';

            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$ijenis/$epartnertype/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$ijenis/$epartnertype/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$ijenis/$epartnertype/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
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
        $datatables->hide('e_bagian_name');
        $datatables->hide('label_color');
        $datatables->hide('i_jenis_faktur');
        $datatables->hide('e_partner_type');
        return $datatables->generate();
    }
    
    public function bagian(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function getpartner(){
        $cari = str_replace("'","",$this->input->get('q'));
        return $this->db->query("
                                    SELECT
                                       a.id,
                                       a.nama,
                                       a.grouppartner 
                                    FROM
                                       (
                                          SELECT
                                             id,
                                             e_bagian_name as nama,
                                             'bagian' as grouppartner,
                                             id_company 
                                          FROM
                                             tr_bagian 
                                          UNION ALL
                                          SELECT
                                             id,
                                             e_nama_karyawan as nama,
                                             'karyawan' as grouppartner,
                                             id_company 
                                          FROM
                                             tr_karyawan 
                                          UNION ALL
                                          SELECT
                                             id,
                                             e_supplier_name as nama,
                                             'supplier' as grouppartner,
                                             id_company 
                                          FROM
                                             tr_supplier 
                                          UNION ALL
                                          SELECT
                                             id,
                                             e_customer_name as nama,
                                             'customer' as grouppartner,
                                             id_company 
                                          FROM
                                             tr_customer 
                                       )
                                       as a 
                                       JOIN
                                          tm_kontra_bon b 
                                          on a.id = id_partner 
                                          AND a.grouppartner = b.e_partner_type 
                                          AND a.id_company = b.id_company
                                      WHERE a.nama ILIKE '%$cari%' 
                                ", FALSE);
    }

    public function partner($ijenis){
        $cari = str_replace("'","",$this->input->get('q'));
        if ($ijenis == 9) {
            return $this->db->query("
                SELECT
                    DISTINCT a.id,
                    a.e_supplier_name AS e_name,
                    a.i_supplier AS kode,
                    'supplier' AS grouppartner
                FROM
                    tr_supplier a
                JOIN 
                    tm_nota_penjualan b
                    ON (a.id = b.id_customer
                    AND a.id_company = b.id_company)
                WHERE
                    a.f_status = 't'
                    AND (e_supplier_name ILIKE '%$cari%')
                    AND a.id_company = '".$this->session->userdata('id_company')."'
                ORDER BY 
                    2
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT DISTINCT
                    q.id,
                    q.e_name,
                    q.kode,
                    q.grouppartner,
                    q.f_status,
                    q.id_company
                FROM(
                        SELECT
                            id,
                            e_nama_karyawan AS e_name,
                            e_nik AS kode,
                            'karyawan' AS grouppartner,
                            f_status,
                            id_company
                        FROM
                            tr_karyawan

                        UNION ALL
                        SELECT
                            id,
                            e_bagian_name AS e_name,
                            i_bagian AS kode,
                            'bagian' AS grouppartner,
                            f_status,
                            id_company
                        FROM
                            tr_bagian

                        UNION ALL 
                        SELECT
                            id,
                            e_supplier_name AS e_name,
                            i_supplier AS kode,
                            'supplier' AS grouppartner,
                            f_status,
                            id_company
                        FROM
                            tr_supplier

                        UNION ALL
                        SELECT
                            id,
                            e_customer_name AS e_name,
                            i_customer AS kode,
                            'customer' AS grouppartner,
                            f_status,
                            id_company
                        FROM
                            tr_customer
                    ) AS q
                    JOIN 
                      tm_nota_penjualan_bb c 
                      ON q.id = id_partner
                      AND q.id_company = c.id_company
                WHERE
                    q.f_status = 't'
                    AND (q.e_name ILIKE '%$cari%')
                    AND q.id_company = '".$this->session->userdata('id_company')."'
                ORDER BY
                    2
            ", FALSE);
            
        }
    }

//---
    public function cek_partner($ijenis, $ipartner){
       
        if ($ipartner == 'ALL') {
            $where = "";
        } else {
            if ($ipartner) {
                $tmp = explode('-', $ipartner);
                $idpartner      = $tmp[0];
                $epartnertype   = $tmp[1];
            }
            $where = "AND id = '$idpartner'";
        }
        $ecustomer = '';
        $query = $this->db->query("
                                    SELECT 
                                        e_customer_name 
                                    FROM 
                                        tr_customer 

                                    WHERE 
                                       id_company = '".$this->session->userdata('id_company')."'
                                       $where
                                 ", FALSE);
        foreach($query->result() as $key){
            $ecustomer = $key->e_customer_name;
        }
        return $ecustomer;
    }

    public function jenis() {
        return $this->db->query("                                    
                                    SELECT
                                       i_jenis_faktur,
                                       e_jenis_faktur_name 
                                    FROM
                                       tr_jenis_faktur 
                                    WHERE 
                                        i_type = '2'
                                    AND 
                                        f_status = 't'
                                    ORDER BY
                                       i_jenis_faktur
                                ", FALSE);
    }

    public function runningnumber($thbl,$tahun,$ibagian) {
        $cek = $this->db->query("
                SELECT 
                  substring(i_document, 1, 3) AS kode 
                FROM tm_kontra_bon
                WHERE i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '".$this->session->userdata("id_company")."'
                ORDER BY id DESC");
        if ($cek->num_rows()>0) {
          $kode = $cek->row()->kode;
        }else{
          $kode = 'KNB';
        }
        $query  = $this->db->query("
              SELECT
                  max(substring(i_document, 10, 6)) AS max
              FROM
                tm_kontra_bon
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

    public function cek_kode($kode,$ibagian) {
        $this->db->select('i_document');
        $this->db->from('tm_kontra_bon');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold, $ibagian) {
        $this->db->select('i_document');
        $this->db->from('tm_kontra_bon');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function getdetail($idpartner, $epartnertype, $ijenis, $jtawal, $jtakhir){
        //faktur penjualan barang jadi
        if($ijenis == '9'){      
            return $this->db->query("                       
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                           a.i_pajak,
                                           a.d_pajak,
                                           to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                           a.v_bersih,
                                           a.v_sisa 
                                        FROM
                                           tm_nota_penjualan a 
                                        WHERE
                                           a.id_customer = '$idpartner' 
                                           AND d_jatuh_tempo BETWEEN to_date('$jtawal', 'dd-mm-yyyy') AND to_date('$jtakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."'
                                    ", FALSE);
        }
        //faktur penjualan bahan baku
        else if($ijenis == '10'){
            return $this->db->query("
                                        SELECT
                                           a.id,
                                           a.e_partner_type,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                           a.i_pajak,
                                           a.d_pajak,
                                           to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                           a.v_bersih,
                                           a.v_sisa 
                                        FROM
                                           tm_nota_penjualan_bb a 
                                        WHERE
                                           a.id_partner = '$idpartner' 
                                           AND a.e_partner_type = '$epartnertype'
                                           AND d_jatuh_tempo BETWEEN to_date('$jtawal', 'dd-mm-yyyy') AND to_date('$jtakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."' 
                                    ", FALSE);
        }
    }

    public function estatus($istatus) {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id,$istatus) {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_kontra_bon', $data);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_kontra_bon');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id_company, $id, $ibagian, $idocument, $ddocument, $ijenis, $idpartner, $epartnertype, $jumlah, $sisa, $remark) {
        $data = array(
                        'id_company'       => $id_company,
                        'id'               => $id,
                        'i_bagian'         => $ibagian,
                        'i_document'       => $idocument,
                        'd_document'       => $ddocument,
                        'id_partner'       => $idpartner,
                        'e_partner_type'   => $epartnertype,
                        'v_total'          => $jumlah,
                        'v_sisa'           => $sisa,
                        'e_remark'         => $remark,
        );
        $this->db->insert('tm_kontra_bon', $data);
    }

    public function insertdetail($id, $idfaktur, $ijenis, $vtotal, $vsisa, $edesc, $id_company) {
        $data = array(
                        'id_company'       => $id_company,
                        'id_document'      => $id,
                        'id_faktur'        => $idfaktur,
                        'i_jenis_faktur'   => $ijenis,
                        'v_total'          => $vtotal,
                        'v_sisa'           => $vsisa,
                        'e_remark'         => $edesc,
        );
        $this->db->insert('tm_kontra_bon_item', $data);
    }

    public function cek_data($id, $epartnertype, $idcompany){
        return $this->db->query("
                                    SELECT
                                       a.id,
                                       a.i_bagian,
                                       b.e_bagian_name,
                                       a.i_document,
                                       to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                       a.id_partner,
                                       a.e_partner_type,
                                       a.v_total,
                                       a.v_sisa,
                                       c.i_jenis_faktur,
                                       d.e_jenis_faktur_name,
                                       a.i_status,
                                       a.e_remark 
                                    FROM
                                       tm_kontra_bon a 
                                       JOIN
                                          tr_bagian b 
                                          ON a.i_bagian = b.i_bagian 
                                          AND a.id_company = b.id_company
                                       JOIN 
                                          tm_kontra_bon_item c 
                                          ON a.id = c.id_document 
                                          AND a.id_company = c.id_company
                                       JOIN
                                          tr_jenis_faktur d 
                                          ON c.i_jenis_faktur = d.i_jenis_faktur 
                                        WHERE
                                           a.id = '$id' 
                                           AND a.e_partner_type = '$epartnertype'
                                           AND a.id_company = '$idcompany' 
                                    ", FALSE);
    }

    public function cek_datadetail($id, $ijenis){
        if($ijenis == '9'){
            return $this->db->query("
                                        SELECT DISTINCT
                                            a.id_document,
                                            a.id_faktur,
                                            a.i_jenis_faktur,
                                            b.i_document as i_faktur,
                                            to_char(b.d_document, 'dd-mm-yyyy') as d_faktur,
                                            b.i_pajak,
                                            to_char(b.d_pajak, 'dd-mm-yyyy') as d_pajak,
                                            to_char(b.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                            a.v_total,
                                            a.v_sisa,
                                            a.e_remark 
                                            FROM tm_kontra_bon_item a
                                            JOIN tm_nota_penjualan b 
                                              ON a.id_faktur = b.id 
                                              AND a.id_company = b.id_company
                                            WHERE a.id_document = '$id'
                                    ", FALSE);
        }else if($ijenis == '10'){
            return $this->db->query("
                                        SELECT DISTINCT
                                            a.id_document,
                                            a.id_faktur,
                                            a.i_jenis_faktur,
                                            b.i_document as i_faktur,
                                            to_char(b.d_document, 'dd-mm-yyyy') as d_faktur,
                                            b.i_pajak,
                                            to_char(b.d_pajak, 'dd-mm-yyyy') as d_pajak,
                                            to_char(b.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                            a.v_total,
                                            a.v_sisa,
                                            a.e_remark 
                                            FROM tm_kontra_bon_item a
                                            JOIN tm_nota_penjualan_bb b 
                                              ON a.id_faktur = b.id 
                                              AND a.id_company = b.id_company
                                            WHERE a.id_document = '$id'
                                    ", FALSE);
        }
    }

    public function updateheader($id_company, $id, $ibagian, $idocument, $ddocument, $ijenis, $idpartner, $epartnertype, $jumlah, $sisa, $remark) {
        $data = array(
                        'id_company'       => $id_company,
                        'i_bagian'         => $ibagian,
                        'i_document'       => $idocument,
                        'd_document'       => $ddocument,
                        'id_partner'       => $idpartner,
                        'e_partner_type'   => $epartnertype,
                        'v_total'          => $jumlah,
                        'v_sisa'           => $sisa,
                        'e_remark'         => $remark,
                        'd_update'         => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_kontra_bon', $data);
    }

    function deletedetail($id) {
         $this->db->query("DELETE FROM tm_kontra_bon_item WHERE id_document='$id'");
    }
}
/* End of file Mmaster.php */