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
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
                                    SELECT
                                        i_bagian
                                    FROM
                                        tm_kas_masuk_piutang
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
                                                AND id_company = $this->company)

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
// --string_agg(DISTINCT f.e_customer_name, ', ') AS f_e_customer_name, 
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                                SELECT
                                    DISTINCT
                                    0 AS NO,
                                    a.id,
                                    a.id_company,
                                    a.i_document,
                                    to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                    a.i_bagian,
                                    a.id_kas_bank,
                                    e.e_kas_name,  
                                    string_agg(DISTINCT  
                                    CASE
                                        WHEN b.group_partner = 'customer' THEN f.e_customer_name 
                                        WHEN b.group_partner = 'supplier' THEN g.e_supplier_name 
                                        WHEN b.group_partner = 'karyawan' THEN h.e_nama_karyawan 
                                        WHEN b.group_partner = 'bagian' THEN i.e_bagian_name 
                                    END, ', ') AS e_partner,   
                                    a.i_status,
                                    a.e_remark,
                                    c.e_status_name,
                                    c.label_color,
                                    '$i_menu' AS i_menu,
                                    '$folder' AS folder,
                                    '$dfrom' AS dfrom,
                                    '$dto' AS dto
                                FROM
                                    tm_kas_masuk_piutang a
                                JOIN tm_kas_masuk_piutang_item b ON
                                    a.id = b.id_document
                                    AND a.id_company = b.id_company
                                JOIN tr_status_document c ON
                                    c.i_status = a.i_status
                                JOIN tr_kas_bank e ON
                                    a.id_kas_bank = e.id
                                    AND a.id_company = e.id_company
                                LEFT JOIN tr_customer f ON
                                    f.id = b.id_partner
                                    AND f.id_company = b.id_company
                                LEFT JOIN tr_supplier g ON
                                    g.id = b.id_partner
                                    AND g.id_company = b.id_company
                                LEFT JOIN tr_karyawan h ON 
                                    h.id = b.id_partner 
                                    AND h.id_company = b.id_company
                                LEFT JOIN tr_bagian i ON 
                                    i.id = b.id_partner 
                                    AND i.id_company = b.id_company
                                WHERE
                                    a.i_status <> '5'
                                    AND a.id_company = $this->company
                                    $and
                                    $bagian
                                GROUP BY
                                    a.id,
                                    a.i_document,
                                    d_document,
                                    e.e_kas_name,
                                    a.i_status,
                                    c.e_status_name,
                                    c.label_color
                                ORDER BY
                                    i_document ASC
                            ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('e_partner', function ($data) {
            return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['e_partner']))).'</span>';
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
        $datatables->hide('id_company');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('id_kas_bank');

        return $datatables->generate();
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
                tm_kas_masuk_piutang
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = '$this->company'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'KMP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_kas_masuk_piutang
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
        $this->db->from('tm_kas_masuk_piutang');
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
        $this->db->from('tm_kas_masuk_piutang');
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
                                        (i_bank ILIKE '%$cari%'
                                        OR e_bank_name ILIKE '%$cari%')
                                        AND id_company = '$this->company'
                                        AND i_bank = '$ibank'
                                    ORDER BY
                                        3
                                ", FALSE);
    }

    /*----------  CARI CUSTOMER YANG MASIH PUNYA HUTANG  ----------*/

    public function customer($cari)
    {
        return $this->db->query("
                                    SELECT 
                                        z.id_partner,
                                        z.i_partner,
                                        z.e_partner,
                                        z.jenis_faktur,
                                        z.group_partner,
                                        z.id_company
                                    FROM(
                                            SELECT
                                                DISTINCT a.id as id_partner,
                                                a.i_customer as i_partner,
                                                a.e_customer_name as e_partner,
                                                'faktur_barangjadi' as jenis_faktur,
                                                'customer' as group_partner,
                                                b.id_company
                                                FROM 
                                                    tr_customer a
                                                JOIN tm_nota_penjualan b ON
                                                    (b.id_customer = a.id
                                                    AND a.id_company = b.id_company)
                                                    WHERE b.i_status ='6'
                                                    AND b.v_sisa > 0
                                              UNION ALL
                                            SELECT 
                                                DISTINCT a.id_partner,
                                                CASE 
                                                   WHEN e_partner_type = 'customer' THEN b.i_customer
                                                   WHEN e_partner_type = 'bagian' THEN c.i_bagian
                                                   WHEN e_partner_type = 'supplier' THEN d.i_supplier
                                                   WHEN e_partner_type = 'karyawan' THEN e.e_nik
                                                END AS i_partner,
                                                a.e_partner_name as e_partner,
                                                'faktur_bahanbaku' as jenis_faktur,
                                                a.e_partner_type as group_partner,
                                                a.id_company
                                            FROM 
                                            tm_nota_penjualan_bb a
                                            LEFT JOIN tr_customer b ON a.id_partner = b.id AND a.id_company = b.id_company
                                            LEFT JOIN tr_bagian c ON a.id_partner = c.id AND a.id_company = c.id_company
                                            LEFT JOIN tr_supplier d ON a.id_partner = d.id AND a.id_company = d.id_company
                                            LEFT JOIN tr_karyawan e ON a.id_partner = e.id AND a.id_company = e.id_company
                                            WHERE a.i_status ='6'
                                            AND a.v_sisa > 0
                                        )as z
                                        WHERE
                                            (z.e_partner ILIKE '%$cari%'
                                            OR z.i_partner ILIKE '%$cari%')
                                            AND z.id_company = '$this->company'
                                        ORDER BY
                                            e_partner ASC         
                                ", FALSE);
    }
    
    /*----------  GET DETAIL CUSTOMER  ----------*/
    
    public function getcustomer($idpartner)
    {

        // $in_str = "'".implode("', '", $idpartner)."'";
        // $where = " ";
        return $this->db->query("
                                    SELECT 
                                        z.id_partner,
                                        z.i_partner,
                                        z.e_partner,
                                        z.jenis_faktur,
                                        z.group_partner,
                                        z.id_company
                                    FROM(
                                            SELECT
                                                DISTINCT a.id as id_partner,
                                                a.i_customer as i_partner,
                                                a.e_customer_name as e_partner,
                                                'faktur_barangjadi' as jenis_faktur,
                                                'customer' as group_partner,
                                                b.id_company
                                                FROM 
                                                    tr_customer a
                                                JOIN tm_nota_penjualan b ON
                                                    (b.id_customer = a.id
                                                    AND a.id_company = b.id_company)
                                                    WHERE b.i_status ='6'
                                                    AND b.v_sisa > 0
                                              UNION ALL
                                            SELECT 
                                                DISTINCT a.id_partner,
                                                CASE 
                                                   WHEN e_partner_type = 'customer' THEN b.i_customer
                                                   WHEN e_partner_type = 'bagian' THEN c.i_bagian
                                                   WHEN e_partner_type = 'supplier' THEN d.i_supplier
                                                   WHEN e_partner_type = 'karyawan' THEN e.e_nik
                                                END AS i_partner,
                                                a.e_partner_name as e_partner,
                                                'faktur_bahanbaku' as jenis_faktur,
                                                a.e_partner_type as group_partner,
                                                a.id_company
                                            FROM 
                                            tm_nota_penjualan_bb a
                                            LEFT JOIN tr_customer b ON a.id_partner = b.id AND a.id_company = b.id_company
                                            LEFT JOIN tr_bagian c ON a.id_partner = c.id AND a.id_company = c.id_company
                                            LEFT JOIN tr_supplier d ON a.id_partner = d.id AND a.id_company = d.id_company
                                            LEFT JOIN tr_karyawan e ON a.id_partner = e.id AND a.id_company = e.id_company
                                            WHERE a.i_status ='6'
                                            AND a.v_sisa > 0
                                        )as z
                                    WHERE
                                        z.id_company = '$this->company'
                                        AND z.id_partner||z.group_partner||z.jenis_faktur IN ($idpartner)
                                    ORDER BY
                                        e_partner ASC   
                                ", FALSE);
    }

    /*----------  RUNNING ID  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_kas_masuk_piutang');
        return $this->db->get()->row()->id+1;
    }

    /*----------  SIMPAN DATA HEADER  ----------*/    

    public function insertheader($id,$idocument,$datedocument,$ibagian,$idkasbank,$ibank,$vnilai,$eremark)
    {
        $data = array(
                        'id'                 => $id,
                        'id_company'         => $this->company,
                        'i_document'         => $idocument,
                        'd_document'         => $datedocument,
                        'i_bagian'           => $ibagian,
                        'id_kas_bank'        => $idkasbank,
                        'id_bank'            => $ibank,
                        'n_nilai'            => $vnilai,
                        'n_sisa'             => $vnilai,
                        'e_remark'           => $eremark,
        );
        $this->db->insert('tm_kas_masuk_piutang', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/    

    public function insertdetail($id,$idpartner,$jenisfaktur,$grouppartner,$vvalue,$edesc)
    {
        $data = array(
                        'id_company'          => $this->company,
                        'id_document'         => $id,
                        'id_partner'          => $idpartner,
                        'group_partner'       => $grouppartner,
                        'jenis_faktur'        => $jenisfaktur,
                        'n_nilai'             => $vvalue,
                        'n_sisa'              => $vvalue,
                        'e_remark'            => $edesc,
        );
        $this->db->insert('tm_kas_masuk_piutang_item', $data);
    }     

    /*----------  GET CUSTOMER EDIT  ----------*/
    
    public function cek_customer($id)
    {
        return $this->db->query("
                                    SELECT
                                        a.id_partner
                                    FROM
                                        tm_kas_masuk_piutang_item a
                                    WHERE
                                        a.id_document = '$id'
                                        AND a.id_company = '$this->company'
                                ", FALSE);
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
                                        bb.id_partner,
                                        CASE
                                            WHEN bb.group_partner = 'customer' THEN f.e_customer_name 
                                            WHEN bb.group_partner = 'supplier' THEN g.e_supplier_name 
                                            WHEN bb.group_partner = 'karyawan' THEN h.e_nama_karyawan 
                                            WHEN bb.group_partner = 'bagian' THEN i.e_bagian_name 
                                        END AS e_partner, 
                                        CASE
                                            WHEN bb.group_partner = 'customer' THEN f.i_customer 
                                            WHEN bb.group_partner = 'supplier' THEN g.i_supplier 
                                            WHEN bb.group_partner = 'karyawan' THEN h.e_nik 
                                            WHEN bb.group_partner = 'bagian' THEN i.i_bagian 
                                        END AS i_partner, 
                                        bb.group_partner,
                                        bb.jenis_faktur,
                                        c.i_bank,
                                        a.id_bank,
                                        d.e_bank_name,
                                        a.n_nilai,
                                        a.e_remark,
                                        a.i_status
                                    FROM
                                        tm_kas_masuk_piutang a
                                    INNER JOIN tm_kas_masuk_piutang_item bb ON
                                        (a.id = bb.id_document 
                                        AND a.id_company = bb.id_company)
                                    INNER JOIN tr_bagian b ON
                                        (a.i_bagian = b.i_bagian
                                        AND a.id_company = b.id_company)
                                    INNER JOIN tr_kas_bank c ON
                                        (a.id_kas_bank = c.id
                                        AND a.id_company = c.id_company)
                                    LEFT JOIN tr_bank d ON
                                        (a.id_bank = d.id
                                        AND a.id_company = d.id_company)
                                    LEFT JOIN tr_customer f ON
                                        f.id = bb.id_partner
                                        AND f.id_company = bb.id_company
                                    LEFT JOIN tr_supplier g ON
                                        g.id = bb.id_partner
                                        AND g.id_company = bb.id_company
                                    LEFT JOIN tr_karyawan h ON 
                                        h.id = bb.id_partner 
                                        AND h.id_company = bb.id_company
                                    LEFT JOIN tr_bagian i ON 
                                        i.id = bb.id_partner 
                                        AND i.id_company = bb.id_company
                                    WHERE
                                        a.id = '$id'
                                        AND a.id_company = '$this->company'
                                    GROUP BY 
                                        a.id,
                                        a.i_document,
                                        d_document,
                                        a.i_bagian,
                                        b.e_bagian_name,
                                        a.id_kas_bank,
                                        c.e_kas_name,
                                        bb.id_partner,
                                        i_partner,
                                        e_partner, 
                                        bb.group_partner,
                                        bb.jenis_faktur,
                                        c.i_bank,
                                        a.id_bank,
                                        d.e_bank_name,
                                        a.n_nilai,
                                        a.e_remark,
                                        a.i_status  
                                ", FALSE);
    }

    /*----------  GET EDIT DATA ITEM  ----------*/

    public function cek_datadetail($id)
    {
        return $this->db->query("
                                    SELECT
                                        a.id,
                                        a.id_document,
                                        a.id_partner,
                                        CASE
                                            WHEN a.group_partner = 'customer' THEN f.e_customer_name 
                                            WHEN a.group_partner = 'supplier' THEN g.e_supplier_name 
                                            WHEN a.group_partner = 'karyawan' THEN h.e_nama_karyawan 
                                            WHEN a.group_partner = 'bagian' THEN i.e_bagian_name 
                                        END AS e_partner, 
                                        CASE
                                            WHEN a.group_partner = 'customer' THEN f.i_customer 
                                            WHEN a.group_partner = 'supplier' THEN g.i_supplier 
                                            WHEN a.group_partner = 'karyawan' THEN h.e_nik 
                                            WHEN a.group_partner = 'bagian' THEN i.i_bagian 
                                        END AS i_partner, 
                                        a.group_partner,
                                        a.jenis_faktur,
                                        a.n_nilai,
                                        a.e_remark
                                    FROM
                                        tm_kas_masuk_piutang_item a
                                    INNER JOIN tm_kas_masuk_piutang b ON
                                        (a.id_document = b.id
                                        AND a.id_company = b.id_company)
                                    LEFT JOIN tr_customer f ON
                                        f.id = a.id_partner
                                        AND f.id_company = a.id_company
                                    LEFT JOIN tr_supplier g ON
                                        g.id = a.id_partner
                                        AND g.id_company = a.id_company
                                    LEFT JOIN tr_karyawan h ON 
                                        h.id = a.id_partner 
                                        AND h.id_company = a.id_company
                                    LEFT JOIN tr_bagian i ON 
                                        i.id = a.id_partner 
                                        AND i.id_company = a.id_company
                                    WHERE
                                        b.id = '$id'
                                        AND b.id_company = '$this->company'
                                    ORDER BY
                                        a.id_partner ASC
                                ", FALSE);
    }

    /*----------  UPDATE DATA HEADER  ----------*/    

    public function updateheader($id,$idocument,$datedocument,$ibagian,$idkasbank,$ibank,$vnilai,$eremark)
    {
        $data = array(
                        'id_company'         => $this->company,
                        'i_document'         => $idocument,
                        'd_document'         => $datedocument,
                        'i_bagian'           => $ibagian,
                        'id_kas_bank'        => $idkasbank,
                        'id_bank'            => $ibank,
                        'n_nilai'            => $vnilai,
                        'n_sisa'             => $vnilai,
                        'e_remark'           => $eremark,
                        'd_update'           => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_kas_masuk_piutang', $data);
    }

    /*----------  DELETE ITEM SAAT EDIT  ----------*/
    
    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_kas_masuk_piutang_item');
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
        $this->db->update('tm_kas_masuk_piutang', $data);
    }
}
/* End of file Mmaster.php */