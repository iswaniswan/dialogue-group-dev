<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    /*----------  DAFTAR DATA SESUAI BAGIAN PEMBUAT  ----------*/
    
    function data($i_menu,$folder,$dfrom,$dto)
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
                tm_konversi_pinjaman_bb a
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
                        AND id_company = $this->company
                        AND username = '$this->username')

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
                        AND id_company = $this->company
                        AND username = '$this->username')";
            }
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                CASE
                    WHEN e_type_partner = 'supplier' THEN e_supplier_name
                    WHEN e_type_partner = 'customer' THEN e_customer_name
                    WHEN e_type_partner = 'karyawan' THEN e_nama_karyawan
                    WHEN e_type_partner = 'bagian' THEN e_bagian_name
                END AS e_partner_name,
                string_agg(DISTINCT c.i_document, ', ') AS i_referensi,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_konversi_pinjaman_bb a
            INNER JOIN tm_konversi_pinjaman_bb_item b ON
                (b.id_document = a.id)
            INNER JOIN tm_keluar_pinjamanbb c ON
                (c.id = b.id_document_reff)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            LEFT JOIN tr_supplier e ON
                (e.id = a.id_partner)
            LEFT JOIN tr_customer f ON
                (f.id = a.id_partner)
            LEFT JOIN tr_karyawan g ON
                (g.id = a.id_partner)
            LEFT JOIN tr_bagian h ON
                (h.id = a.id_partner)
            WHERE
                a.i_status <> '5' 
                AND a.id_company = $this->company
                $and
                $bagian
            GROUP BY
                a.id,
                a.i_document,
                a.d_document,
                e_supplier_name,
                e_customer_name,
                e_nama_karyawan,
                e_bagian_name,
                e_status_name,
                label_color,
                a.i_status
            ORDER BY
                a.id
            ", FALSE
        );

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('i_referensi', function ($data) {
            return '<span>'.str_replace(",", "<br>", $data['i_referensi']).'</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = $data['id'];
            $i_status   = trim($data['i_status']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
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

    /*----------  BACA BAGIAN PEMBUAT  ----------*/    

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('a.f_status', 't');
        $this->db->where('i_level', $this->level);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  BACA PARTNER  ----------*/    

    public function partner($cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.id_partner AS id,
                b.i_partner,
                b.e_partner_name AS e_name,
                grouppartner
            FROM
                tm_keluar_pinjamanbb a
            INNER JOIN (
                SELECT
                    id AS id_partner,
                    i_bagian AS i_partner,
                    e_bagian_name AS e_partner_name,
                    id_company,
                    'bagian' AS grouppartner
                FROM
                    tr_bagian
                WHERE
                    id_company = $this->company
                    AND f_status = 't'
            UNION ALL
                SELECT
                    id AS id_partner,
                    i_supplier AS i_partner,
                    e_supplier_name AS e_partner_name,
                    id_company,
                    'supplier' AS grouppartner
                FROM
                    tr_supplier
                WHERE
                    id_company = $this->company
                    AND f_status = 't'
            UNION ALL
                SELECT
                    id AS id_partner,
                    i_customer AS i_partner,
                    e_customer_name AS e_partner_name,
                    id_company,
                    'customer' AS grouppartner
                FROM
                    tr_customer
                WHERE
                    id_company = $this->company
                    AND f_status = 't'
            UNION ALL
                SELECT
                    id AS id_partner,
                    e_nik AS i_partner,
                    e_nama_karyawan AS e_partner_name,
                    id_company,
                    'karyawan' AS grouppartner
                FROM
                    tr_karyawan
                WHERE
                    id_company = $this->company
                    AND f_status = 't' ) b ON
                (a.id_partner = b.id_partner
                AND a.id_company = b.id_company
                AND a.i_partner_group = b.grouppartner)
            LEFT JOIN tm_keluar_pinjamanbb_item c ON
                (a.id = c.id_document
                AND a.id_company = c.id_company)
            WHERE
                a.id_company = $this->company
                AND a.i_status = '6'
                AND c.n_quantity_sisa > 0
            ORDER BY
                4, 3
        ", FALSE);
    }    

    /*----------  RUNNING NO DOKUMEN  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM 
                tm_konversi_pinjaman_bb 
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'KPP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_konversi_pinjaman_bb
            WHERE 
                id_company = $this->company
                AND i_status <> '5'
                AND i_bagian = '$ibagian'
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
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
        $this->db->from('tm_konversi_pinjaman_bb');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/
    
    public function datareferensi($cari,$idpartner,$grouppartner)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_keluar_pinjamanbb a
            INNER JOIN tm_keluar_pinjamanbb_item b ON
                (a.id = b.id_document
                AND a.id_company = b.id_company)
            WHERE
                a.id_company = $this->company
                AND i_status = '6'
                AND id_partner = $idpartner
                AND i_partner_group = '$grouppartner'
                AND (i_document ILIKE '%$cari%')
                AND n_quantity_sisa > 0
            ORDER BY
                2
        ", FALSE);
    }

    /*----------  REFERENSI HEADER  ----------*/
    
    public function tanggal($id)
    {
        $in_str = "'".implode("', '", $id)."'";
        $where  = "WHERE id IN (".$in_str.")";
        return $this->db->query("            
            SELECT 
                max(to_char(d_document,'dd-mm-yyyy')) AS d_date,
                string_agg(to_char(d_document, 'dd-mm-yyyy'),', ') AS d_document
            FROM
                tm_keluar_pinjamanbb
            $where
        ", FALSE);
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/    

    public function detailreferensi($id)
    {
        $in_str = "'".implode("', '", $id)."'";
        $where  = "WHERE id_document IN (".$in_str.")";
        return $this->db->query("
            SELECT
                a.id,
                i_document,
                id_document,
                id_material,
                i_material,
                e_material_name,
                n_quantity,
                n_quantity_sisa,
                e_satuan_name
            FROM
                tm_keluar_pinjamanbb a
            INNER JOIN tm_keluar_pinjamanbb_item b ON
                (a.id = b.id_document)
            INNER JOIN tr_material c ON
                (b.id_material = c.id)
            INNER JOIN tr_satuan d ON
                (d.i_satuan_code = c.i_satuan_code
                AND c.id_company = d.id_company)
            $where
            AND b.n_quantity_sisa > 0
            ORDER BY 2, 4,2
            ",
            FALSE
        );
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/    

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_konversi_pinjaman_bb');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$ipartner,$etypepartner,$ireff,$eremark)
    {
        $data = array(
            'id'               => $id,
            'id_company'       => $this->company,
            'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_partner'       => $ipartner,
            'e_type_partner'   => $etypepartner,
            'id_document_reff' => $ireff,
            'e_remark'         => $eremark,
            'd_entry'          => current_datetime(),
        );
        $this->db->insert('tm_konversi_pinjaman_bb', $data);
    }

    public function simpandetail($id,$iddocument,$idmaterial,$nquantity,$eremark)
    {
        $data = array(
            'id_company'        => $this->company,
            'id_document'       => $id,
            'id_document_reff'  => $iddocument,
            'id_material'       => $idmaterial,
            'n_quantity'        => $nquantity,
            'n_quantity_sisa'   => $nquantity,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_konversi_pinjaman_bb_item', $data);
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE MULTIPLE  ----------*/
    
    public function dataeditreferensi($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT b.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_konversi_pinjaman_bb_item a
            INNER JOIN tm_keluar_pinjamanbb b ON
                (b.id = a.id_document_reff)
            WHERE
                a.id_document = $id
            ORDER BY
                2
            ",
            FALSE
        );
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE TANGGAL REFERENSI  ----------*/
    
    public function tanggalreferensi($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT 
                max(to_char(d_document, 'dd-mm-yyyy')) AS d_document
            FROM
                tm_konversi_pinjaman_bb_item a
            INNER JOIN tm_keluar_pinjamanbb b ON
                (b.id = a.id_document_reff)
            WHERE
                a.id_document = $id
            ",
            FALSE
        )->row()->d_document;
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/
    
    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                b.e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                id_partner,
                e_type_partner,
                CASE
                    WHEN e_type_partner = 'supplier' THEN e_supplier_name
                    WHEN e_type_partner = 'customer' THEN e_customer_name
                    WHEN e_type_partner = 'karyawan' THEN e_nama_karyawan
                    WHEN e_type_partner = 'bagian' THEN h.e_bagian_name
                END AS e_partner_name,
                a.e_remark,
                i_status
            FROM
                tm_konversi_pinjaman_bb a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            LEFT JOIN tr_supplier e ON
                (e.id = a.id_partner)
            LEFT JOIN tr_customer f ON
                (f.id = a.id_partner)
            LEFT JOIN tr_karyawan g ON
                (g.id = a.id_partner)
            LEFT JOIN tr_bagian h ON
                (h.id = a.id_partner)
            WHERE
                a.id = $id
            ",
            FALSE
        );
    }

    /*----------  GET DATA DETAIL EDIT, VIEW DAN APPROVE  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT
                a.id_document,
                a.id_document_reff,
                i_document,
                a.id_material,
                i_material,
                e_material_name,
                e_satuan_name,
                a.n_quantity,
                c.n_quantity AS n_quantity_reff,
                c.n_quantity_sisa AS n_quantity_sisa_reff,
                a.e_remark
            FROM
                tm_konversi_pinjaman_bb_item a
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tm_keluar_pinjamanbb_item c ON
                (c.id_document = a.id_document_reff
                AND a.id_material = c.id_material)
            INNER JOIN tm_keluar_pinjamanbb d ON
                (d.id = c.id_document)
            INNER JOIN tr_satuan e ON
                (e.i_satuan_code = b.i_satuan_code
                AND b.id_company = e.id_company)
            WHERE
                a.id_document = $id
            ORDER BY
                1, 2, 5, 4
            ",
            FALSE
        );
    }

    /*----------  UPDATE DATA  ----------*/   

    public function update($id,$idocument,$ddocument,$ibagian,$ipartner,$etypepartner,$ireff,$eremark)
    {
        $data = array(
            'id'               => $id,
            'id_company'       => $this->company,
            'i_document'       => $idocument,
            'd_document'       => $ddocument,
            'i_bagian'         => $ibagian,
            'id_partner'       => $ipartner,
            'e_type_partner'   => $etypepartner,
            'id_document_reff' => $ireff,
            'e_remark'         => $eremark,
            'd_entry'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_konversi_pinjaman_bb', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/    

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_konversi_pinjaman_bb_item');
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
        $this->db->update('tm_konversi_pinjaman_bb', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {

        /*----------  Cek ada data atau tidak  ----------*/
        
        $query = $this->db->query("
            SELECT 
                id_document_reff,
                id_material,
                n_quantity
            FROM 
                tm_konversi_pinjaman_bb_item
            WHERE 
                id_document = $id
        ", FALSE);

        /*----------  Jika Data Ada  ----------*/
        
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/
                
                $ceksisa1 = $this->db->query("
                    SELECT 
                        n_quantity_sisa
                    FROM 
                        tm_keluar_pinjamanbb_item
                    WHERE 
                        id_document = $key->id_document_reff
                        AND id_material = $key->id_material
                        AND n_quantity_sisa >= $key->n_quantity
                ", FALSE);
                if ($ceksisa1->num_rows()>0) {

                    /*----------  Update Sisa Di Packing  ----------*/
                    
                    $this->db->query("
                        UPDATE 
                            tm_keluar_pinjamanbb_item
                        SET 
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_material = $key->id_material
                            AND n_quantity_sisa >= $key->n_quantity
                    ", FALSE);
                }else{
                    die();
                }
            }
        }
    }
    
}
/* End of file Mmaster.php */