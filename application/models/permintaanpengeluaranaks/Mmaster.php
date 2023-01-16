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
                tm_memo_ak
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
            SELECT DISTINCT
                0 AS NO,
                a.id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                e_jenis_name,
                CASE
                     WHEN e_partner_type = 'supplier' THEN b.e_supplier_name
                     WHEN e_partner_type = 'customer' THEN e_customer_name
                     WHEN e_partner_type = 'karyawan' THEN e_nama_karyawan
                     WHEN e_partner_type = 'bagian' THEN e_bagian_name
                END AS e_partner_name,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_memo_ak a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tr_jenis_pengeluaran c ON
                (c.id = a.id_jenis)
            LEFT JOIN tr_supplier b ON
                (b.id = a.id_partner)
            LEFT JOIN tr_customer e ON
                (e.id = a.id_partner)
            LEFT JOIN tr_karyawan f ON
                (f.id = a.id_partner)
            LEFT JOIN tr_bagian g ON
                (g.id = a.id_partner)
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
    
    /*----------  DATA BAGIAN PEMBUAT DOKUMEN  ----------*/
    
    public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
         $this->db->where('a.i_type', '02');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
      }
    
    /*----------  DATA TUJUAN INTERNAL EKSTERNAL  ----------*/    

    public function tujuan()
    {
        $this->db->select('*');
        $this->db->from('tr_tujuan');
        return $this->db->get();
    }
    
    /*----------  DATA JENIS PENGELUARAN  ----------*/
    /**
     *
     * Jika id tujuan 1 = Eksternal
     * Jika id tujuan 2 = Internal
     *
     */

    public function jenis($cari,$idtujuan)
    {
        return $this->db->query("
            SELECT 
                id,
                e_jenis_name
            FROM
                tr_jenis_pengeluaran tjp
            WHERE
                i_tujuan ILIKE (
                SELECT
                    '%' || i_tujuan || '%'
                FROM
                    tr_tujuan
                WHERE
                    id = $idtujuan)
                AND e_jenis_name ILIKE '%$cari%'
                AND id <> 5
            ORDER BY e_jenis_name ASC
        ", fALSE);
    }

    /*----------  CEK KODE  ----------*/

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_memo_ak');
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
                substring(i_document, 1, 2) AS kode 
            FROM tm_memo_ak 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
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
                tm_memo_ak
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
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
    
    /*----------  BACA KARYAWAN (PIC)  ----------*/

    public function pic($cari)
    {
        return $this->db->query("
            SELECT
                id,
                e_nama_karyawan
            FROM
                tr_karyawan
            WHERE
                f_status = 't'
                AND (e_nama_karyawan ILIKE '%$cari%')
                AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                e_nama_karyawan
        ", FALSE);
    }
    
    /*----------  BACA PARTNER (SUPPLIER, CUSTOMER, KARYAWAN)  ----------*/
    /**
     * 
     * DI TANAM DIPROGRAM
     *
     * tabel tr_tujuan
     * 
     * id tujuan 1 = Eksternal
     * id tujuan 2 = Internal
     *
     * table tr_jenis pengeluaran
     * 
     * id jenis 1 = Pengeluaran Makloon
     * id jenis 2 = Pengeluaran Penjualan
     * id jenis 3 = Pengeluaran Pinjaman
     * id jenis 4 = Pengeluaran Produksi
     *
     */
    
    public function partner($cari,$idtujuan,$idjenis)
    {
        if ($idtujuan == 1 && $idjenis == 1) {
            return $this->db->query("
                SELECT
                    DISTINCT b.id,
                    e_supplier_name AS e_name,
                    b.i_supplier AS kode,
                    'supplier' AS grouppartner
                FROM
                    tr_supplier_makloon a
                INNER JOIN tr_supplier b ON
                    (b.i_supplier = a.i_supplier
                    AND a.id_company = b.id_company)
                WHERE
                    b.f_status = 't'
                    AND (e_supplier_name ILIKE '%$cari%')
                    AND a.id_company = '".$this->session->userdata('id_company')."'
                ORDER BY 
                    2
            ", FALSE);
        } elseif ($idtujuan == 2 && $idjenis == 2) {
            return $this->db->query("
                SELECT
                    id,
                    e_nama_karyawan AS e_name,
                    e_nik AS kode,
                    'karyawan' AS grouppartner
                FROM
                    tr_karyawan
                WHERE
                    f_status = 't'
                    AND (e_nama_karyawan ILIKE '%$cari%')
                    AND id_company = '".$this->session->userdata('id_company')."'
                ORDER BY
                    2
            ", FALSE);
        } elseif ($idtujuan == 1 && $idjenis == 2) {
            return $this->db->query("
                SELECT
                    id,
                    e_customer_name AS e_name,
                    i_customer AS kode,
                    'customer' AS grouppartner
                FROM
                    tr_customer
                WHERE
                    f_status = 't'
                    AND (e_customer_name ILIKE '%$cari%')
                    AND id_company = '".$this->session->userdata('id_company')."'
                ORDER BY
                    2
            ", FALSE);
        } elseif ($idtujuan == 2 && $idjenis == 3) {
            return $this->db->query("
                SELECT 
                    * 
                FROM (
                    SELECT
                        id,
                        e_nama_karyawan AS e_name,
                        e_nik AS kode,
                        'karyawan' AS grouppartner
                    FROM
                        tr_karyawan
                    WHERE
                        f_status = 't'
                        AND (e_nama_karyawan ILIKE '%$cari%')
                        AND id_company = '".$this->session->userdata('id_company')."'
                    UNION ALL
                    SELECT
                        id,
                        e_bagian_name AS e_name,
                        i_bagian AS kode,
                        'bagian' AS grouppartner
                    FROM
                        tr_bagian
                    WHERE
                        f_status = 't'
                        AND (e_bagian_name ILIKE '%$cari%')
                        AND id_company = '".$this->session->userdata('id_company')."'
                ) AS x
                ORDER BY
                    2
            ", FALSE);
        } elseif ($idtujuan == 1 && $idjenis == 3) {
            return $this->db->query("
                SELECT 
                    * 
                FROM (
                    SELECT
                        DISTINCT b.id,
                        e_supplier_name AS e_name,
                        b.i_supplier AS kode,
                        'supplier' AS grouppartner
                    FROM
                        tr_supplier_makloon a
                    INNER JOIN tr_supplier b ON
                        (b.i_supplier = a.i_supplier
                        AND a.id_company = b.id_company)
                    WHERE
                        b.f_status = 't'
                        AND (e_supplier_name ILIKE '%$cari%')
                        AND a.id_company = '".$this->session->userdata('id_company')."'
                    UNION ALL
                    SELECT
                        id,
                        e_customer_name AS e_name,
                        i_customer AS kode,
                        'customer' AS grouppartner
                    FROM
                        tr_customer
                    WHERE
                        f_status = 't'
                        AND (e_customer_name ILIKE '%$cari%')
                        AND id_company = '".$this->session->userdata('id_company')."'
                ) AS x
                ORDER BY
                    2
            ", FALSE);
        } else {
            return $this->db->query("
                SELECT
                    id,
                    e_bagian_name AS e_name,
                    i_bagian AS kode,
                    'bagian' AS grouppartner
                FROM
                    tr_bagian
                WHERE
                    f_status = 't'
                    AND (e_bagian_name ILIKE '%$cari%')
                    AND id_company = '".$this->session->userdata('id_company')."'
                ORDER BY
                    2
            ", FALSE);
        }
    }

    /*----------  CARI BARANG  ----------*/

    public function product($cari,$ibagian)
    {
        return $this->db->query("            
            SELECT
                a.id,
                i_material,
                e_material_name,
                e_satuan_name
            FROM
                tr_material a
            INNER JOIN tr_satuan b ON
                (b.i_satuan_code = a.i_satuan_code
                AND a.id_company = b.id_company)
            WHERE
                a.f_status = 't'
                AND (i_material ILIKE '%$cari%' 
                OR e_material_name ILIKE '%$cari%')
                AND a.id_company = '".$this->session->userdata('id_company')."'
                AND a.i_kode_kelompok IN (
                SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = '".$this->session->userdata('id_company')."')
            ORDER BY
                2, 3 ASC
        ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_memo_ak');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$itujuan,$ijeniskeluar,$ipartner,$typepartner,$picinternal,$piceksternal,$imemo,$dmemo,$eremarkh)
    {
        $data = array(
            'id'             => $id,
            'id_company'     => $this->session->userdata('id_company'),
            'i_document'     => $idocument,
            'd_document'     => $ddocument,
            'i_bagian'       => $ibagian,
            'id_tujuan'      => $itujuan,
            'id_jenis'       => $ijeniskeluar,
            'id_partner'     => $ipartner,
            'id_pic_int'     => $picinternal,
            'e_pic_eks'      => $piceksternal,
            'i_memo'         => $imemo,
            'd_memo'         => $dmemo,
            'e_remark'       => $eremarkh,
            'e_partner_type' => $typepartner,
            'd_entry'        => current_datetime(),
        );
        $this->db->insert('tm_memo_ak', $data);
    }

    public function simpandetail($id,$idmaterial,$nquantity,$idmateriallist,$nquantitylist,$eremark)
    {
        if ($idmateriallist=='' || $idmateriallist==null) {
            $idmateriallist = $idmaterial;
        }
        if ($nquantitylist=='' || $nquantitylist==null) {
            $nquantitylist = $nquantity;
        }
        $data = array(
            'id_company'            => $this->session->userdata('id_company'),
            'id_document'           => $id,
            'id_material'           => $idmaterial,
            'n_quantity'            => $nquantity,
            'n_quantity_sisa'       => $nquantity,
            'id_material_list'      => $idmateriallist,
            'n_quantity_list'       => $nquantitylist,
            'n_quantity_list_sisa'  => $nquantitylist,
            'e_remark'              => $eremark,
        );
        $this->db->insert('tm_memo_ak_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                b.e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_tujuan,
                c.e_tujuan_name,
                a.id_jenis,
                d.e_jenis_name,
                a.i_memo,
                to_char(a.d_memo, 'dd-mm-yyyy') AS d_memo,
                a.id_partner,
                CASE
                    WHEN e_partner_type = 'supplier' THEN e_supplier_name
                    WHEN e_partner_type = 'customer' THEN e_customer_name
                    WHEN e_partner_type = 'karyawan' THEN h.e_nama_karyawan
                    WHEN e_partner_type = 'bagian' THEN i.e_bagian_name
                END AS e_partner_name,
                a.id_pic_int,
                e.e_nama_karyawan,
                a.e_remark,
                a.e_pic_eks,
                e_partner_type,
                a.i_status
            FROM
                tm_memo_ak a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_tujuan c ON
                (c.id = a.id_tujuan)
            INNER JOIN tr_jenis_pengeluaran d ON
                (d.id = a.id_jenis)
            INNER JOIN tr_karyawan e ON
                (e.id = a.id_pic_int)
            LEFT JOIN tr_supplier f ON
                (f.id = a.id_partner)
            LEFT JOIN tr_customer g ON
                (g.id = a.id_partner)
            LEFT JOIN tr_karyawan h ON
                (h.id = a.id_partner)
            LEFT JOIN tr_bagian i ON
                (i.id = a.id_partner)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT
                a.id_material,
                b.i_material,
                b.e_material_name,
                c.e_satuan_name,
                a.n_quantity,
                a.id_material_list,
                d.i_material AS i_material_list,
                d.e_material_name AS e_material_list,
                f.e_satuan_name AS e_status_list,
                a.n_quantity_list,
                a.e_remark
            FROM
                tm_memo_ak_item a
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.id = a.id_material_list)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = d.i_satuan_code
                AND d.id_company = f.id_company)
            WHERE
                a.id_document = '$id'
            ORDER BY
                1,2
        ", FALSE);
    }

    /*----------  UPDATE DATA  ----------*/    

    public function update($id,$idocument,$ddocument,$ibagian,$itujuan,$ijeniskeluar,$ipartner,$typepartner,$picinternal,$piceksternal,$imemo,$dmemo,$eremarkh)
    {
        $data = array(
            'id_company'     => $this->session->userdata('id_company'),
            'i_document'     => $idocument,
            'd_document'     => $ddocument,
            'i_bagian'       => $ibagian,
            'id_tujuan'      => $itujuan,
            'id_jenis'       => $ijeniskeluar,
            'id_partner'     => $ipartner,
            'id_pic_int'     => $picinternal,
            'e_pic_eks'      => $piceksternal,
            'i_memo'         => $imemo,
            'd_memo'         => $dmemo,
            'e_remark'       => $eremarkh,
            'e_partner_type' => $typepartner,
            'd_update'       => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_memo_ak', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_memo_ak_item');
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
        $this->db->update('tm_memo_ak', $data);
    }
}
/* End of file Mmaster.php */
