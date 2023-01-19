<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  LIST DATA  ----------*/
    
    public function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_kasbank_keluarnonap a
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                $and
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')

        ", FALSE);
        if ($this->departement=='1') {
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
                        i_departement = '$this->departement'
                        AND username  = '$this->username'
                        AND id_company = '$this->company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                e.e_kas_name,
                e_jenis_name,
                CASE 
                    WHEN id_jenis = 1 THEN f.i_document
                    WHEN id_jenis = 2 THEN g.i_document
                END AS i_referensi,
                a.n_nilai,
                a.i_status,
                c.e_status_name,
                c.label_color,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_kasbank_keluarnonap a
            JOIN tr_jenis_kas_bon b ON
                (b.id = a.id_jenis)
            JOIN tr_status_document c ON
                (c.i_status = a.i_status)
            JOIN tr_kas_bank e ON
                (a.id_kas_bank = e.id
                AND a.id_company = e.id_company)
            LEFT JOIN tm_permintaan_kas_keluar f ON
                (f.id = a.id_document_reff 
                AND a.id_company = f.id_company)
            LEFT JOIN tm_kas_bon_karyawan g ON
                (g.id = a.id_document_reff
                AND a.id_company = g.id_company)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company'
                $and
                $bagian
            ORDER BY
                2
        ", FALSE);

        $datatables->edit('n_nilai', function ($data) {
            return 'Rp. '.number_format($data['n_nilai']);
        });

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id        = trim($data['id']);
            $i_menu    = $data['i_menu'];
            $folder    = $data['folder'];
            $i_status  = $data['i_status'];
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $data      = '';

            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');       
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_status');

        return $datatables->generate();
    }

    /*----------  JENIS KAS / BON  ----------*/
    
    public function jenis()
    {
        $this->db->select('*');
        $this->db->from('tr_jenis_kas_bon');
        $this->db->order_by('id');
        return $this->db->get();
    }

    /*----------  BAGIAN PEMBUAT DOKUMEN  ----------*/
    
    public function bagianpembuat()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/
    
    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM    
                tm_kasbank_keluarnonap
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->company'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'KNA';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_kasbank_keluarnonap
            WHERE 
                to_char (d_document, 'yyyy') >= '$tahun'
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND id_company = '$this->company'
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

    /*----------  CEK NO DOKUMEN  ----------*/
    
    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_kasbank_keluarnonap');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_kasbank_keluarnonap');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI KAS/BANK  ----------*/

    public function kasbank($cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT id,
                i_kode_kas,
                e_kas_name,
                i_bank
            FROM
                tr_kas_bank
            WHERE
                (i_kode_kas ILIKE '%$cari%'
                OR e_kas_name ILIKE '%$cari%')
                AND id_company = '$this->company'
                AND f_status = 't'
            ORDER BY
                3
            ", FALSE);
    }
    
    /*----------  CARI DATA BANK  ----------*/

    public function bank($cari,$ibank)
    {
        return $this->db->query("
            SELECT
                DISTINCT id,
                i_bank,
                e_bank_name
            FROM
                tr_bank
            WHERE
                (i_bank LIKE '%$cari%'
                OR e_bank_name LIKE '%$cari%')
                AND id_company = '$this->company'
                AND i_bank = '$ibank'
            ORDER BY
                3
            ", FALSE);
    }

    /*----------  CARI REFERENSI YANG MASIH ADA SISA  ----------*/
    /**
     * Jika Ijenis = 1, Maka Permintaan Kas Keluar (tm_permintaan_kas_keluar, tm_permintaan_kas_keluar_item)
     * Jika Ijenis = 2, Maka Kas Bon (tm_kas_bon_karyawan, tm_kas_bon_karyawan_item)
     */

    public function referensi($cari,$ijenis)
    {
        if ($ijenis==1) {
            return $this->db->query("
                SELECT
                    DISTINCT a.id,
                    a.i_document
                FROM
                    tm_permintaan_kas_keluar a
                JOIN tm_permintaan_kas_keluar_item b ON
                    (b.id_document = a.id
                    AND a.id_company = b.id_company)
                WHERE
                    (a.i_document ILIKE '%$cari%')
                    AND a.id_company = '$this->company'
                    /*AND i_status = '6'*/
                    AND a.n_sisa > 0
                ORDER BY
                    2
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    DISTINCT id,
                    i_document
                FROM
                    tm_kas_bon_karyawan
                WHERE
                    (i_document ILIKE '%$cari%')
                    AND id_company = '$this->company'
                    /*AND i_status = '6'*/
                    AND v_sisa > 0
                ORDER BY
                    2
            ", FALSE);
        }
    }
    
    /*----------  GET DETAIL CUSTOMER  ----------*/
    
    public function getreferensi($idreferensi,$ijenis)
    {
        if ($ijenis==1) {
            return $this->db->query("
                SELECT
                    DISTINCT id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    n_sisa
                FROM
                    tm_permintaan_kas_keluar
                WHERE
                    id = $idreferensi
                    AND id_company = $this->company
                    /*AND i_status = '6'*/
                    AND n_sisa > 0
            ", FALSE);
        }else{
            return $this->db->query("
                SELECT
                    DISTINCT id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    v_sisa AS n_sisa
                FROM
                    tm_kas_bon_karyawan
                WHERE
                    id = $idreferensi
                    AND id_company = $this->company
                    /*AND i_status = '6'*/
                    AND v_sisa > 0
            ", FALSE);
        }
    }

    /*----------  RUNNING ID  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_kasbank_keluarnonap');
        return $this->db->get()->row()->id+1;
    }

    /*----------  SIMPAN DATA HEADER  ----------*/    

    public function insertheader($id,$idocument,$datedocument,$ibagian,$idkasbank,$ibank,$ijenis,$ireferensi,$vnilai,$eremark)
    {
        $data = array(
            'id'                 => $id,
            'id_company'         => $this->company,
            'i_document'         => $idocument,
            'd_document'         => $datedocument,
            'i_bagian'           => $ibagian,
            'id_kas_bank'        => $idkasbank,
            'id_bank'            => $ibank,
            'id_jenis'           => $ijenis,
            'id_document_reff'   => $ireferensi,
            'n_nilai'            => $vnilai,
            'n_sisa'             => $vnilai,
            'e_remark'           => $eremark,
        );
        $this->db->insert('tm_kasbank_keluarnonap', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/    

    public function insertdetail($id,$idreferensi,$vvalue,$edesc)
    {
        $data = array(
            'id_company'          => $this->company,
            'id_document'         => $id,
            'id_document_reff'    => $idreferensi,
            'n_nilai'             => $vvalue,
            'e_remark'            => $edesc,
        );
        $this->db->insert('tm_kasbank_keluarnonap_item', $data);
    }
    
    /*----------  GET EDIT DATA HEADER  ----------*/

    public function cek_data($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.i_bagian,
                b.e_bagian_name,
                a.id_kas_bank,
                c.e_kas_name,
                c.i_bank,
                a.id_bank,
                d.e_bank_name,
                a.n_nilai,
                a.e_remark,
                a.i_status,
                a.id_jenis,
                e_jenis_name,
                a.id_document_reff,
                CASE 
                    WHEN a.id_jenis = 1 THEN f.i_document
                    WHEN a.id_jenis = 2 THEN g.i_document
                END AS i_referensi
            FROM
                tm_kasbank_keluarnonap a
            INNER JOIN tr_bagian b ON
                (a.i_bagian = b.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_kas_bank c ON
                (a.id_kas_bank = c.id
                AND a.id_company = c.id_company)
            LEFT JOIN tr_bank d ON
                (a.id_bank = d.id
                AND a.id_company = d.id_company)
            INNER JOIN tr_jenis_kas_bon e ON
                (e.id = a.id_jenis)
            LEFT JOIN tm_permintaan_kas_keluar f ON
                (f.id = a.id_document_reff 
                AND a.id_company = f.id_company)
            LEFT JOIN tm_kas_bon_karyawan g ON
                (g.id = a.id_document_reff
                AND a.id_company = g.id_company)
            WHERE
                a.id = '$id'
                AND a.id_company = '$this->company'
            ", FALSE);
    }

    /*----------  GET EDIT DATA ITEM  ----------*/

    public function cek_datadetail($id)
    {
        return $this->db->query("
            SELECT
                a.*,
                CASE 
                    WHEN id_jenis = 1 THEN f.i_document
                    WHEN id_jenis = 2 THEN g.i_document
                END AS i_referensi,
                CASE 
                    WHEN id_jenis = 1 THEN to_char(f.d_document, 'dd-mm-yyyy')
                    WHEN id_jenis = 2 THEN to_char(g.d_document, 'dd-mm-yyyy')
                END AS d_referensi,
                CASE 
                    WHEN id_jenis = 1 THEN f.n_sisa
                    WHEN id_jenis = 2 THEN g.v_sisa
                END AS n_sisa
            FROM
                tm_kasbank_keluarnonap_item a
            INNER JOIN tm_kasbank_keluarnonap b ON
                (a.id_document = b.id
                AND a.id_company = b.id_company)
            LEFT JOIN tm_permintaan_kas_keluar f ON
                (f.id = a.id_document_reff 
                AND a.id_company = f.id_company)
            LEFT JOIN tm_kas_bon_karyawan g ON
                (g.id = a.id_document_reff
                AND a.id_company = g.id_company)
            WHERE
                a.id_document = '$id'
                AND a.id_company = '$this->company'
            ", FALSE);
    }

    /*----------  UPDATE DATA HEADER  ----------*/    

    public function updateheader($id,$idocument,$datedocument,$ibagian,$idkasbank,$ibank,$ijenis,$ireferensi,$vnilai,$eremark)
    {
        $data = array(
            'id_company'         => $this->company,
            'i_document'         => $idocument,
            'd_document'         => $datedocument,
            'i_bagian'           => $ibagian,
            'id_kas_bank'        => $idkasbank,
            'id_bank'            => $ibank,
            'id_jenis'           => $ijenis,
            'id_document_reff'   => $ireferensi,
            'n_nilai'            => $vnilai,
            'e_remark'           => $eremark,
            'd_update'           => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_kasbank_keluarnonap', $data);
    }

    /*----------  DELETE ITEM SAAT EDIT  ----------*/
    
    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_kasbank_keluarnonap_item');
    }

    /*----------  NAMA STATUS  ----------*/
    
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    
    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->company);
        $this->db->update('tm_kasbank_keluarnonap', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {
        $idjenis = $this->db->query("
            SELECT 
                id_jenis 
            FROM 
                tm_kasbank_keluarnonap
            WHERE 
                id = $id
            ", FALSE)->row()->id_jenis;
        $query = $this->db->query("
            SELECT 
                id_document_reff,
                n_nilai
            FROM 
                tm_kasbank_keluarnonap_item
            WHERE 
                id_document = '$id'
            ", FALSE);
        if ($idjenis == 1) {
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("
                        SELECT
                            n_sisa
                        FROM
                            tm_permintaan_kas_keluar
                        WHERE
                            id = '$key->id_document_reff'
                            AND id_company  = '$this->company'
                            AND n_sisa >= '$key->n_nilai'
                    ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                        UPDATE
                            tm_permintaan_kas_keluar
                        SET
                            n_sisa = n_sisa - $key->n_nilai
                        WHERE
                            id = '$key->id_document_reff'
                            AND id_company  = '$this->company'
                            AND n_sisa >= '$key->n_nilai'
                        ", FALSE);
                    } else {
                        die();
                    }
                }
            }
        } else if ($idjenis == 2) {
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $nsisa = $this->db->query("
                        SELECT
                            v_sisa
                        FROM
                            tm_kas_bon_karyawan
                        WHERE
                            id = '$key->id_document_reff'
                            AND id_company  = '$this->company'
                            AND v_sisa >= '$key->n_nilai'
                    ", FALSE);

                    if ($nsisa->num_rows() > 0) {
                        $this->db->query("
                        UPDATE
                            tm_kas_bon_karyawan
                        SET
                            v_sisa = v_sisa - $key->n_nilai
                        WHERE
                            id = '$key->id_document_reff'
                            AND id_company  = '$this->company'
                            AND v_sisa >= '$key->n_nilai'
                        ", FALSE);
                    } else {
                        die();
                    }
                }
            }
        }
    }    
}
/* End of file Mmaster.php */