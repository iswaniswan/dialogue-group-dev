<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  DAFTAR DATA MASUK GUDANG JADI SESUAI GUDANG PEMBUAT  ----------*/
    
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
                tm_keluar_makloon_bb a
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
                DISTINCT 0 AS NO,
                a.id AS id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(a.d_estimate, 'dd-mm-yyyy') AS d_estimate,
                d.e_supplier_name,
                e.i_document AS i_referensi,
                e_status_name AS e_status,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_makloon_bb a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            INNER JOIN tr_supplier d ON
                (d.id = a.id_supplier)
            INNER JOIN tm_memo_bb e ON
                (e.id = a.id_document_reff)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
                $bagian
            ORDER BY
                a.id ASC
            ", FALSE
        );

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
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
        $datatables->hide('e_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /*----------  TYPE MAKLOON  ----------*/    

    public function type($i_menu)
    {
        $this->db->select('b.id, e_type_makloon_name AS e_name')->distinct();
        $this->db->from('tr_makloon_menu a');
        $this->db->join('tr_type_makloon b','b.id = a.id_makloon AND a.id_company = b.id_company','inner');
        $this->db->where('b.f_status', 't');
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_menu', $i_menu);
        $this->db->order_by('e_type_makloon_name');
        return $this->db->get();
    }

    /*----------  BACA BAGIAN PEMBUAT  ----------*/    

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('a.f_status', 't');
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  BACA PARTNER  ----------*/    

    public function partner($idtype,$cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT id_partner AS id,
                b.e_supplier_name AS e_name
            FROM
                tm_memo_bb a
            INNER JOIN tr_supplier b ON
                (b.id = a.id_partner)
            INNER JOIN tr_supplier_makloon c ON
                (c.i_supplier = b.i_supplier
                AND b.id_company = c.id_company)
            INNER JOIN tr_type_makloon e ON
                (e.i_type_makloon = c.i_type_makloon
                AND c.id_company = e.id_company)
            INNER JOIN tm_memo_bb_item d ON
                (d.id_document = a.id)
            WHERE
                a.e_partner_type = 'supplier'
                AND d.n_quantity_sisa > 0
                AND d.n_quantity_list_sisa > 0
                AND a.id_jenis = 1
                AND e.id = $idtype
                AND b.e_supplier_name ILIKE '%$cari%'
                AND a.i_status = '6'
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                2
        ", FALSE);
    }  

    /*----------  DETAIL PARTNER  ----------*/
    
    public function detailpartner($idsupplier)
    {
        return $this->db->query("            
            SELECT 
                i_type_pajak,
                n_diskon
            FROM
                tr_supplier
            WHERE 
                id = $idsupplier
        ", FALSE);
    }  

    /*----------  RUNNING NO DOKUMEN  ----------*/    

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_keluar_makloon_bb 
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
                tm_keluar_makloon_bb
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

    /*----------  CEK NO DOKUMEN  ----------*/
    
    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_makloon_bb');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI DATA REFERENSI  ----------*/
    
    public function datareferensi($cari,$idpartner)
    {
        return $this->db->query("            
            SELECT DISTINCT
                a.id,
                i_document
            FROM
                tm_memo_bb a
            INNER JOIN tm_memo_bb_item b ON
                (a.id = b.id_document)
            WHERE 
                id_partner = '$idpartner'
                AND e_partner_type = 'supplier'
                AND id_jenis = '1'
                AND i_status = '6'
                AND n_quantity_sisa > 0
                AND n_quantity_list_sisa > 0
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY 2
        ", FALSE);
    }

    /*----------  REFERENSI HEADER  ----------*/
    
    public function ref($id)
    {
        return $this->db->query("            
            SELECT 
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_memo_bb
            WHERE 
                id = $id
        ", FALSE);
    }

    /*----------  DETAIL DATA REFERENSI  ----------*/    

    public function detailreferensi($id,$tgl)
    {
        $idcompany = $this->session->userdata('id_company');
        $dberlaku  = date('Y-m-d', strtotime($tgl));
        $dakhir    = date('Y-m-d', strtotime('+1 year', strtotime($tgl))); /*tamabah tanggal sebanyak 1 tahun*/
        return $this->db->query("
            SELECT
                id_material,
                b.i_material,
                b.e_material_name,
                bb.e_satuan_name,
                n_quantity,
                n_quantity_sisa,
                id_material_list,
                c.i_material AS i_material_list,
                c.e_material_name AS e_material_list,
                cc.e_satuan_name AS e_satuan_list,
                n_quantity_list,
                n_quantity_list_sisa,
                y.v_unitprice,
                x.v_unitprice AS v_unitprice_list
            FROM
                tm_memo_bb_item a
            INNER JOIN tm_memo_bb aa ON
                (aa.id = a.id_document)
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_satuan bb ON
                (bb.i_satuan_code = b.i_satuan_code
                AND b.id_company = bb.id_company)
            INNER JOIN tr_material c ON
                (c.id = a.id_material_list)
            INNER JOIN tr_satuan cc ON
                (cc.i_satuan_code = c.i_satuan_code
                AND c.id_company = cc.id_company)
            INNER JOIN (
                SELECT
                    *
                FROM
                    (
                    SELECT
                        a.id_supplier,
                        /*a.id_type_makloon,*/
                        b.v_price_int AS v_unitprice,
                        b.id_product,
                        d_berlaku,
                        CASE
                            WHEN d_akhir ISNULL THEN '$dakhir'
                            ELSE d_akhir
                        END AS d_akhir
                    FROM
                        tr_harga_makloon_supplier a
                    INNER JOIN tr_harga_makloon_supplier_item b ON
                        (b.id_harga = a.id)
                    WHERE
                        a.f_status = 't'
                        AND a.id_company = '$idcompany' ) AS x
                WHERE
                    x.d_berlaku <= '$dberlaku'
                    AND x.d_akhir >= '$dberlaku' ) x ON
                (x.id_supplier = aa.id_partner 
                /*AND aa.id_type_makloon = x.id_type_makloon*/
                AND x.id_product = a.id_material_list)
            INNER JOIN (
                SELECT
                    *
                FROM
                    (
                    SELECT
                        a.id_supplier,
                        /*a.id_type_makloon,*/
                        b.v_price_int AS v_unitprice,
                        b.id_product,
                        d_berlaku,
                        CASE
                            WHEN d_akhir ISNULL THEN '$dakhir'
                            ELSE d_akhir
                        END AS d_akhir
                    FROM
                        tr_harga_makloon_supplier a
                    INNER JOIN tr_harga_makloon_supplier_item b ON
                        (b.id_harga = a.id)
                    WHERE
                        a.f_status = 't'
                        AND a.id_company = '$idcompany' ) AS y
                WHERE
                    y.d_berlaku <= '$dberlaku'
                    AND y.d_akhir >= '$dberlaku' ) y ON
                (y.id_supplier = aa.id_partner
                /*AND aa.id_type_makloon = y.id_type_makloon*/
                AND y.id_product = a.id_material)
            WHERE
                id_document = $id
            ORDER BY
                1,
                2
            ",
            FALSE
        );
    }

    /*----------  SIMPAN DATA HEADER DAN DETAIL  ----------*/    

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_makloon_bb');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$destimate,$ibagian,$idtype,$idpartner,$idreff,$eremark,$itypepajak,$ndiskon)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'd_estimate'        => $destimate,
            'i_bagian'          => $ibagian,
            'id_type_makloon'   => $idtype,
            'id_supplier'       => $idpartner,
            'id_document_reff'  => $idreff,
            'e_remark'          => $eremark,
            'i_type_pajak'      => $itypepajak,
            'n_diskon'          => $ndiskon,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_keluar_makloon_bb', $data);
    }

    public function simpandetail($id,$idreff,$idmaterial,$nqty,$idmateriallist,$nqtylist,$eremark,$vunitprice,$vunitpricelist)
    {
        $data = array(
            'id_company'            => $this->session->userdata('id_company'),
            'id_document'           => $id,
            'id_material'           => $idmaterial,
            'id_document_reff'      => $idreff,
            'n_quantity'            => $nqty,
            'n_quantity_sisa'       => $nqty,
            'id_material_list'      => $idmateriallist,
            'n_quantity_list'       => $nqtylist,
            'n_quantity_list_sisa'  => $nqtylist,
            'e_remark'              => $eremark,
            'v_unitprice'           => $vunitprice,
            'v_unitprice_list'      => $vunitpricelist,
        );
        $this->db->insert('tm_keluar_makloon_bb_item', $data);
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE  ----------*/
    
    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                a.i_bagian,
                b.e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                to_char(a.d_estimate, 'dd-mm-yyyy') AS d_estimate,
                a.id_type_makloon,
                c.e_type_makloon_name,
                a.id_supplier,
                d.e_supplier_name,
                a.id_document_reff,
                e.i_document AS i_referensi,
                to_char(e.d_document, 'dd-mm-yyyy') AS d_referensi,
                a.e_remark,
                a.i_status,
                a.i_type_pajak,
                a.n_diskon
            FROM
                tm_keluar_makloon_bb a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_type_makloon c ON
                (c.id = a.id_type_makloon)
            INNER JOIN tr_supplier d ON
                (d.id = a.id_supplier)
            INNER JOIN tm_memo_bb e ON
                (e.id = a.id_document_reff)
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
            SELECT DISTINCT
                a.id_material,
                b.i_material,
                b.e_material_name,
                bb.e_satuan_name,
                d.n_quantity AS n_quantity_reff,
                d.n_quantity_sisa AS n_quantity_sisa_reff,
                a.n_quantity,
                a.id_material_list,
                c.i_material AS i_material_list,
                c.e_material_name AS e_material_list,
                cc.e_satuan_name AS e_satuan_list,
                a.n_quantity_list,
                d.n_quantity_list AS n_quantity_list_reff,
                d.n_quantity_list_sisa AS n_quantity_list_sisa_reff,
                a.e_remark,
                a.v_unitprice,
                a.v_unitprice_list
            FROM
                tm_keluar_makloon_bb_item a
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_satuan bb ON
                (bb.i_satuan_code = b.i_satuan_code
                AND b.id_company = bb.id_company)
            INNER JOIN tr_material c ON
                (c.id = a.id_material_list)
            INNER JOIN tr_satuan cc ON
                (cc.i_satuan_code = c.i_satuan_code
                AND c.id_company = cc.id_company)
            INNER JOIN tm_memo_bb_item d ON
                (d.id_document = a.id_document_reff 
                AND a.id_material = d.id_material
                AND a.id_material_list=d.id_material_list)
            WHERE
                a.id_document = $id
            ORDER BY
                1,2
            ",
            FALSE
        );
    }

    /*----------  UPDATE DATA  ----------*/   

    public function update($id,$idocument,$ddocument,$destimate,$ibagian,$idtype,$idpartner,$idreff,$eremark,$itypepajak,$ndiskon)
    {
        $data = array(
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'd_estimate'        => $destimate,
            'i_bagian'          => $ibagian,
            'id_type_makloon'   => $idtype,
            'id_supplier'       => $idpartner,
            'id_document_reff'  => $idreff,
            'e_remark'          => $eremark,
            'i_type_pajak'      => $itypepajak,
            'n_diskon'          => $ndiskon,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_keluar_makloon_bb', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (UPDATE)  ----------*/    

    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_makloon_bb_item');
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
        $this->db->update('tm_keluar_makloon_bb', $data);
    }

    /*----------  UPDATE SISA REFERENSI  ----------*/

    public function updatesisa($id)
    {

        /*----------  Cek ada data atau tidak  ----------*/
        
        $query = $this->db->query("
            SELECT 
                id_document_reff,
                id_material,
                id_material_list,
                n_quantity,
                n_quantity_list
            FROM 
                tm_keluar_makloon_bb_item
            WHERE id_document = $id
        ", FALSE);

        /*----------  Jika Data Ada  ----------*/
        
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/
                
                $ceksisa1 = $this->db->query("
                    SELECT 
                        n_quantity_sisa, n_quantity_list_sisa
                    FROM 
                        tm_memo_bb_item
                    WHERE 
                        id_document = $key->id_document_reff
                        AND id_material = $key->id_material
                        AND id_material_list = $key->id_material_list
                        AND n_quantity_sisa >= $key->n_quantity
                        AND n_quantity_list_sisa >= $key->n_quantity_list
                ", FALSE);
                if ($ceksisa1->num_rows()>0) {

                    /*----------  Update Sisa Di Packing  ----------*/
                    
                    $this->db->query("
                        UPDATE 
                            tm_memo_bb_item
                        SET 
                            n_quantity_sisa = n_quantity_sisa - $key->n_quantity,
                            n_quantity_list_sisa = n_quantity_list_sisa - $key->n_quantity_list
                        WHERE 
                            id_document = $key->id_document_reff
                            AND id_material = $key->id_material
                        AND id_material_list = $key->id_material_list
                        AND n_quantity_sisa >= $key->n_quantity
                        AND n_quantity_list_sisa >= $key->n_quantity_list
                    ", FALSE);
                }else{
                    die();
                }
            }
        }
    }

    /*----------  SIMPAN KE JURNAL  ----------*/

    public function simpanjurnal($id,$title)
    {
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_dokumen (id_company,
                id_document,
                i_document,
                i_periode,
                id_material,
                id_product_wip,
                id_product_base,
                i_coa,
                e_coa,
                id_payment_type,
                v_price,
                n_quantity_material,
                n_quantity_wip,
                n_quantity_base,
                n_total,
                title)
            SELECT
                a.id_company,
                b.id_document,
                a.i_document,
                to_char(a.d_document, 'yyyymm') AS i_periode,
                b.id_material_list AS id_material,
                NULL AS id_product_wip,
                NULL AS id_product_base,
                '110-81000' AS i_coa,
                'BAHAN BAKU (BENANG/KAIN, QUILTING, EMBOSS)' AS e_coa,
                NULL AS id_payment_type,
                b.v_unitprice_list AS v_price,
                b.n_quantity_list AS n_quantity_material,
                NULL AS n_quatity_wip,
                NULL AS n_quatity_base,
                b.v_unitprice_list * b.n_quantity_list AS total,
                '$title' AS title
            FROM
                tm_keluar_makloon_bb a
            INNER JOIN tm_keluar_makloon_bb_item b ON
                (b.id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
    }    
}
/* End of file Mmaster.php */