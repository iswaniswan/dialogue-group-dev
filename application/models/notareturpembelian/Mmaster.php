<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {


    /*----------  DATA HEADER KN  ----------*/
    /*
    * Bercocok Tanam Diprogram
    * Jenis Gudang Retur{
        Jika 1 = Gudang Bahan Baku
        Jika 2 = Gudang Aksesoris
        Jika 3 = Gudang Bahan Pembantu
        Jika 4 = Gudang Jadi
    }
    */

    /*----------  DAFTAR DATA SPB  ----------*/    

    public function data($folder,$i_menu,$dfrom,$dto)
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
                tm_nota_retur_beli
            WHERE
                i_status <> '5'
                AND id_company = $this->company
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
                        AND username = '$this->username'
                        AND id_company = '$this->company') ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                e_supplier_name,
                e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_nota_retur_beli a
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company'
                $and
                $bagian
            ORDER BY
                a.id DESC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
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
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status=='2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
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
        $datatables->hide('label_color');
        return $datatables->generate();
    }

    /*----------  DAFTAR DATA REFERENSI  ----------*/    

    public function datareferensi($folder,$i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_retur BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            WITH xx AS (
            SELECT
                0 AS NO,
                ROW_NUMBER() OVER (
                ORDER BY id, i_referensi) AS i,
                id,
                i_referensi,
                d_referensi,
                id_retur_gudang,
                e_bagian_name,
                i_supplier,
                e_supplier_name,
                e_remark,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                (

                /*----------  GUDANG BAHAN BAKU  ----------*/
                
                SELECT
                    a.id,
                    i_retur_beli AS i_referensi,
                    to_char(d_retur, 'dd-mm-yyyy') AS d_referensi,
                    1 AS id_retur_gudang,
                    b.e_bagian_name,
                    a.i_supplier,
                    e_supplier_name,
                    a.e_remark
                FROM
                    tm_retur_belibahanbaku a
                INNER JOIN tr_bagian b ON
                    (b.i_bagian = a.i_bagian
                    AND a.id_company = b.id_company)
                WHERE a.id_company = '$this->company'
                    AND a.i_status = '6'
                    $and

                /*----------  END GUDANG BAHAN BAKU  ----------*/
                
                UNION ALL 

                /*----------  GUDANG AKSESORIS  ----------*/
                
                SELECT
                    a.id,
                    i_retur_beli AS i_referensi,
                    to_char(d_retur, 'dd-mm-yyyy') AS d_referensi,
                    2 AS id_retur_gudang,
                    b.e_bagian_name,
                    a.i_supplier,
                    e_supplier_name,
                    a.e_remark
                FROM
                    tm_retur_beliaksesories a
                INNER JOIN tr_bagian b ON
                    (b.i_bagian = a.i_bagian
                    AND a.id_company = b.id_company)
                WHERE a.id_company = '$this->company'
                    AND a.i_status = '6'
                    $and

                /*----------  END GUDANG AKSESORIS  ----------*/                

                UNION ALL 

                /*----------  GUDANG BAHAN PEMBANTU  ----------*/
                
                SELECT
                    a.id,
                    i_retur_beli AS i_referensi,
                    to_char(d_retur, 'dd-mm-yyyy') AS d_referensi,
                    3 AS id_retur_gudang,
                    b.e_bagian_name,
                    a.i_supplier,
                    e_supplier_name,
                    a.e_remark
                FROM
                    tm_retur_belibahanpembantu a
                INNER JOIN tr_bagian b ON
                    (b.i_bagian = a.i_bagian
                    AND a.id_company = b.id_company)
                WHERE a.id_company = '$this->company'
                    AND a.i_status = '6'
                    $and

                /*----------  END GUDANG BAHAN PEMBANTU  ----------*/
                
                UNION ALL 

                /*----------  GUDANG JADI  ----------*/
                
                SELECT
                    a.id,
                    i_retur_beli AS i_referensi,
                    to_char(d_retur, 'dd-mm-yyyy') AS d_referensi,
                    4 AS id_retur_gudang,
                    b.e_bagian_name,
                    a.i_supplier,
                    e_supplier_name,
                    a.e_remark
                FROM
                    tm_retur_beligdjd a
                INNER JOIN tr_bagian b ON
                    (b.i_bagian = a.i_bagian
                    AND a.id_company = b.id_company)
                WHERE a.id_company = '$this->company'
                    AND a.i_status = '6'
                    $and

                /*----------  END GUDANG JADI  ----------*/
                    
                ) AS x
            )
            SELECT 
                NO,
                i,
                (
                SELECT
                    count(i) AS jml
                FROM
                    xx) AS jml,
                id,
                i_referensi,
                d_referensi,
                id_retur_gudang,
                e_bagian_name,
                i_supplier,
                e_supplier_name,
                e_remark,
                i_menu,
                folder,
                dfrom,
                dto
            FROM 
                xx
            ORDER BY
                6 DESC
        ", FALSE);

        $datatables->add('action', function ($data) {
            $i          = $data['i'];
            $jml        = $data['jml'];
            $id         = $data['id'];
            $isupplier  = trim($data['i_supplier']);
            $ireferensi = trim($data['i_referensi']);
            $idjenis    = $data['id_retur_gudang'];
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';            
            if(check_role($i_menu, 1)){
                $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"jml\" value=\"".$jml."\" type=\"hidden\">
                <input name=\"id".$i."\" value=\"".$id."\" type=\"hidden\">
                <input name=\"idjenis".$i."\" value=\"".$idjenis."\" type=\"hidden\">
                <input name=\"ireferensi".$i."\" value=\"".$ireferensi."\" type=\"hidden\">
                <input name=\"dfrom\" value=\"".$dfrom."\" type=\"hidden\">
                <input name=\"dto\" value=\"".$dto."\" type=\"hidden\">
                <input name=\"isupplier".$i."\" value=\"".$isupplier."\" type=\"hidden\">";
                /*$data .= "<a href=\"#\" title='Tambah Data Alokasi' onclick='show(\"$folder/cform/tambah/$id/$dfrom/$dto/$idjenis/\",\"#main\"); return false;'><i class='ti-new-window'></i></a>";*/
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i');
        $datatables->hide('jml');
        $datatables->hide('i_supplier');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_retur_gudang');
        return $datatables->generate();
    }

    /*----------  GET DATA HEADER SUPPLIER  ----------*/
    
    public function dataheader($isupplier)
    {
        return $this->db->query("
            SELECT
                id,
                e_supplier_name
            FROM
                tr_supplier
            WHERE
                id_company = $this->company
                AND f_status = 't'
                AND i_supplier = '$isupplier'
        ", FALSE);
    }

    /*----------  GET DATA DETAIL REFERENSI  ----------*/    
    /*
    * Bercocok Tanam Diprogram
    * Jenis Gudang Retur{
        Jika 1 = Gudang Bahan Baku
        Jika 2 = Gudang Aksesoris
        Jika 3 = Gudang Bahan Pembantu
        Jika 4 = Gudang Jadi
    }
    */

    public function datadetail($isupplier,$id,$ijenis,$referensi)
    {
        $and   = "AND a.id IN (".$id.")";
        $or    = "AND a.i_retur_beli IN (".$referensi.")";
        return $this->db->query("
            SELECT
                a.id,
                1 AS id_retur_gudang,
                a.i_retur_beli AS i_document,
                to_char(a.d_retur, 'dd-mm-yyyy') AS d_document,
                e.e_bagian_name,
                b.i_sj_supplier,
                d.id AS id_material,
                b.i_material,
                d.e_material_name,
                c.e_satuan_name,
                b.n_quantity,
                b.v_price
            FROM
                tm_retur_belibahanbaku a
            INNER JOIN tm_retur_belibahanbaku_item b ON
                (b.id_retur_beli = a.id
                AND a.id_company = b.id_company)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.i_material = b.i_material
                AND b.id_company = d.id_company)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = a.i_bagian
                AND a.id_company = e.id_company)
            WHERE
                a.id_company = '$this->company'
                $and
                $or
            UNION ALL
            SELECT
                a.id,
                2 AS id_retur_gudang,
                a.i_retur_beli AS i_document,
                to_char(a.d_retur, 'dd-mm-yyyy') AS d_document,
                e.e_bagian_name,
                b.i_sj_supplier,
                d.id AS id_material,
                b.i_material,
                d.e_material_name,
                c.e_satuan_name,
                b.n_quantity,
                b.v_price
            FROM
                tm_retur_beliaksesories a
            INNER JOIN tm_retur_beliaksesories_item b ON
                (b.id_retur_beli = a.id
                AND a.id_company = b.id_company)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.i_material = b.i_material
                AND b.id_company = d.id_company)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = a.i_bagian
                AND a.id_company = e.id_company)
            WHERE
                a.id_company = '$this->company'
                $and
                $or
            UNION ALL
            SELECT
                a.id,
                3 AS id_retur_gudang,
                a.i_retur_beli AS i_document,
                to_char(a.d_retur, 'dd-mm-yyyy') AS d_document,
                e.e_bagian_name,
                b.i_sj_supplier,
                d.id AS id_material,
                b.i_material,
                d.e_material_name,
                c.e_satuan_name,
                b.n_quantity,
                b.v_price
            FROM
                tm_retur_belibahanpembantu a
            INNER JOIN tm_retur_belibahanpembantu_item b ON
                (b.id_retur_beli = a.id
                AND a.id_company = b.id_company)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.i_material = b.i_material
                AND b.id_company = d.id_company)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = a.i_bagian
                AND a.id_company = e.id_company)
            WHERE
                a.id_company = '$this->company'
                $and
                $or
            UNION ALL
            SELECT
                a.id,
                4 AS id_retur_gudang,
                a.i_retur_beli AS i_document,
                to_char(a.d_retur, 'dd-mm-yyyy') AS d_document,
                e.e_bagian_name,
                b.i_sj_supplier,
                d.id AS id_material,
                b.i_material,
                d.e_material_name,
                c.e_satuan_name,
                b.n_quantity,
                b.v_price
            FROM
                tm_retur_beligdjd a
            INNER JOIN tm_retur_beligdjd_item b ON
                (b.id_retur_beli = a.id
                AND a.id_company = b.id_company)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.i_material = b.i_material
                AND b.id_company = d.id_company)
            INNER JOIN tr_bagian e ON
                (e.i_bagian = a.i_bagian
                AND a.id_company = e.id_company)
            WHERE
                a.id_company = '$this->company'
                $and
                $or
            ORDER BY 1
        ", FALSE);
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/    

    public function bagian()
    {
        // $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        // $this->db->from('tr_bagian a');
        // $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        // $this->db->where('a.f_status', 't');
        // $this->db->where('i_departement', $this->departement);
        // $this->db->where('username', $this->username);
        // $this->db->where('a.id_company', $this->company);
        // $this->db->order_by('e_bagian_name');
        // return $this->db->get();
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
          INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
          LEFT JOIN tr_type c on (a.i_type = c.i_type)
          LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
          WHERE a.f_status = 't' AND b.i_departement = '$this->departement' AND username = '$this->username' AND a.id_company = '$this->company' 
          ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/
    
    public function runningnumber($thbl,$tahun,$ibagian) 
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode
            FROM tm_nota_retur_beli
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'NRP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_nota_retur_beli
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
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

    /*----------  CEK DOKUMEN SUDAH ADA  ----------*/

    public function cek_kode($kode,$ibagian) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_nota_retur_beli');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  RUNNING ID  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_nota_retur_beli');
        return $this->db->get()->row()->id+1;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id,$idocument,$ddocument,$ibagian,$idsupplier,$esupplier,$eremarkh)
    {
        $data = array(
            'id'                  => $id,
            'id_company'          => $this->company,
            'i_document'          => $idocument,
            'd_document'          => $ddocument,
            'i_bagian'            => $ibagian,
            'id_supplier'         => $idsupplier,
            'e_supplier_name'     => $esupplier,
            'e_remark'            => $eremarkh,
        );
        $this->db->insert('tm_nota_retur_beli', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/
    
    public function insertdetail($id,$idreferensi,$idmaterial,$idreturgudang,$nqty,$nqtyretur,$vprice,$eremark)
    {
        $data = array(
            'id_company'      => $this->company,
            'id_document'     => $id,
            'id_material'     => $idmaterial,
            'id_referensi'    => $idreferensi,
            'id_retur_gudang' => $idreturgudang,
            'n_referensi'     => $nqty,
            'n_retur'         => $nqtyretur,
            'v_price'         => $vprice,
            'e_remark'        => $eremark,
        );
        $this->db->insert('tm_nota_retur_beli_item', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/
    
    public function editheader($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                id_supplier,
                e_supplier_name,
                a.e_remark,
                a.i_status
            FROM
                tm_nota_retur_beli a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/
    
    public function edititem($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.*,
                b.i_material,
                b.e_material_name,
                c.e_satuan_name,
                CASE
                    WHEN a.id_retur_gudang = 1 THEN e.i_retur_beli
                    WHEN a.id_retur_gudang = 2 THEN g.i_retur_beli
                    WHEN a.id_retur_gudang = 3 THEN i.i_retur_beli
                    WHEN a.id_retur_gudang = 4 THEN k.i_retur_beli
                END AS i_referensi,
                CASE
                    WHEN a.id_retur_gudang = 1 THEN to_char(e.d_retur, 'dd-mm-yyyy')
                    WHEN a.id_retur_gudang = 2 THEN to_char(g.d_retur, 'dd-mm-yyyy')
                    WHEN a.id_retur_gudang = 3 THEN to_char(i.d_retur, 'dd-mm-yyyy')
                    WHEN a.id_retur_gudang = 4 THEN to_char(k.d_retur, 'dd-mm-yyyy')
                END AS d_referensi,
                CASE
                    WHEN a.id_retur_gudang = 1 THEN ee.e_bagian_name
                    WHEN a.id_retur_gudang = 2 THEN gg.e_bagian_name
                    WHEN a.id_retur_gudang = 3 THEN ii.e_bagian_name
                    WHEN a.id_retur_gudang = 4 THEN kk.e_bagian_name
                END AS e_bagian_name,
                CASE
                    WHEN a.id_retur_gudang = 1 THEN d.i_sj_supplier
                    WHEN a.id_retur_gudang = 2 THEN f.i_sj_supplier
                    WHEN a.id_retur_gudang = 3 THEN h.i_sj_supplier
                    WHEN a.id_retur_gudang = 4 THEN j.i_sj_supplier
                END AS i_sj_supplier
            FROM
                tm_nota_retur_beli_item a
            INNER JOIN tr_material b ON
                (b.id = a.id_material
                AND a.id_company = b.id_company)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            LEFT JOIN tm_retur_belibahanbaku_item d ON
                (d.id_retur_beli = a.id_referensi
                AND a.id_company = d.id_company
                AND d.i_material = b.i_material)
            LEFT JOIN tm_retur_belibahanbaku e ON
                (e.id = d.id_retur_beli
                AND d.id_company = e.id_company)
            LEFT JOIN tr_bagian ee ON
                (ee.i_bagian = e.i_bagian
                AND e.id_company = ee.id_company)
            LEFT JOIN tm_retur_beliaksesories_item f ON
                (f.id_retur_beli = a.id_referensi
                AND a.id_company = f.id_company
                AND f.i_material = b.i_material)
            LEFT JOIN tm_retur_beliaksesories g ON
                (g.id = f.id_retur_beli
                AND f.id_company = g.id_company)
            LEFT JOIN tr_bagian gg ON
                (gg.i_bagian = g.i_bagian
                AND g.id_company = gg.id_company)
            LEFT JOIN tm_retur_belibahanpembantu_item h ON
                (h.id_retur_beli = a.id_referensi
                AND a.id_company = h.id_company
                AND h.i_material = b.i_material)
            LEFT JOIN tm_retur_belibahanpembantu i ON
                (i.id = h.id_retur_beli
                AND h.id_company = i.id_company)
            LEFT JOIN tr_bagian ii ON
                (ii.i_bagian = i.i_bagian
                AND i.id_company = ii.id_company)
            LEFT JOIN tm_retur_beligdjd_item j ON
                (j.id_retur_beli = a.id_referensi
                AND a.id_company = j.id_company
                AND j.i_material = b.i_material)
            LEFT JOIN tm_retur_beligdjd k ON
                (k.id = j.id_retur_beli
                AND j.id_company = k.id_company)
            LEFT JOIN tr_bagian kk ON
                (kk.i_bagian = k.i_bagian
                AND k.id_company = kk.id_company)
            WHERE
                a.id_document = $id
            ORDER BY
                a.id_referensi
        ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_nota_retur_beli');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  DELETE DETAIL PAS EDIT  ----------*/
    
    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_nota_retur_beli_item');
    }

    /*----------  UPDATE HEADER  ----------*/
    
    public function updateheader($id,$idocument,$ddocument,$ibagian,$idsupplier,$esupplier,$eremarkh)
    {
        $data = array(
            'id_company'          => $this->company,
            'i_document'          => $idocument,
            'd_document'          => $ddocument,
            'i_bagian'            => $ibagian,
            'id_supplier'         => $idsupplier,
            'e_supplier_name'     => $esupplier,
            'e_remark'            => $eremarkh,
            'd_update'            => current_datetime()
        );
        $this->db->where('id', $id);
        $this->db->update('tm_nota_retur_beli', $data);
    }

    /*----------  RUBAH STATUS  ----------*/
    
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
    
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
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_nota_retur_beli', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/
}
/* End of file Mmaster.php */