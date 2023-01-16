<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DAFTAR DATA SPB  ----------*/    

    public function data($folder,$i_menu,$dfrom,$dto)
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
                tm_retur_penjualan a
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
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_customer_name,
                array_agg (distinct(f.i_document)) AS i_referensi,
                g.e_alasan_name,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_retur_penjualan a
            INNER JOIN tr_customer b ON
                (a.id_customer = b.id)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tm_retur_penjualan_item e ON
                (e.id_document = a.id)
            INNER JOIN tm_nota_penjualan f ON
                (f.id = e.id_referensi
                AND e.id_company = f.id_company)
            INNER JOIN tr_alasan_retur g ON
                (g.id = a.id_alasan_retur)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company
                $and
                $bagian
            GROUP BY
                a.id,
                b.e_customer_name,
                d.e_status_name,
                d.label_color,
                g.e_alasan_name
            ORDER BY
                a.id DESC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('i_referensi', function ($data) {
            return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['i_referensi']))).'</span>';
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
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status=='2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
            }   

            if (check_role($i_menu, 5)  && ($i_status=='6')) {
                $data .= "<a href=\"#\" title='Cetak SPB' onclick='cetak(\"$id\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-print'></i></a>";
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

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/    

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
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
            FROM tm_retur_penjualan
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'TTB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_retur_penjualan
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
        $this->db->from('tm_retur_penjualan');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI PELANGGAN  ----------*/
    
    public function customer($cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT
                a.id,
                i_customer,
                a.e_customer_name
            FROM
                tr_customer a
            INNER JOIN tm_nota_penjualan b ON
                (b.id_customer = a.id
                AND a.id_company = b.id_company)
            WHERE
                b.i_status = '6'
                AND a.id_company = $this->company
                AND (i_customer ILIKE '%$cari%'
                OR a.e_customer_name ILIKE '%$cari%')
                AND a.f_status = 't'
            ORDER BY
                3
            ", FALSE);
    }

    /*----------  CARI REFERENSI  ----------*/
    
    public function referensi($cari,$icustomer,$periode)
    {
        if ($periode!='' || $periode!=null) {
            $yearmonth = date('Ym', strtotime('01-'.$periode));
            $and = "AND to_char(a.d_document, 'yyyymm') = '$yearmonth'";
        }else{
            $and = "";
        }
        return $this->db->query("
            SELECT
                DISTINCT a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_nota_penjualan a,
                tm_nota_penjualan_item b
            WHERE
                a.id = b.id_document 
                AND a.id_company = b.id_company
                AND id_customer = '$icustomer'
                AND i_status = '6'
                AND b.n_quantity_sisa > 0
                AND (a.i_document ILIKE '%$cari%')
                AND a.id_company = '$this->company'
                $and
            ORDER BY
                3,
                2
            ", FALSE);
    }

    /*----------  GET DETAIL BARANG JADI REFERENSI  ----------*/    

    public function getdetailref($idnota)
    {
        $in_str = "'".implode("', '", $idnota)."'";
        $and  = "AND id_document IN (".$in_str.")";
        return $this->db->query("
            SELECT
                c.i_document,
                d.i_document AS i_sj,
                to_char(c.d_document, 'dd-mm-yyyy') AS d_document,
                b.i_product_base AS i_product,
                b.e_product_basename AS e_product,
                a.*
            FROM
                tm_nota_penjualan_item a
            INNER JOIN tm_nota_penjualan c ON
                (c.id = a.id_document)
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product
                AND a.id_company = b.id_company)
            INNER JOIN tm_sj d ON 
                (d.id = a.id_document_reff 
                AND a.id_company = d.id_company)
            WHERE
                a.id_company = '$this->company'
                AND a.n_quantity_sisa > 0
                $and
            ORDER BY
                id_document,
                id_document_reff
            ", FALSE);
    }

    /*----------  DATA MASTER RETUR  ----------*/
    
    public function retur(){
        return $this->db->query("
            SELECT
                id,
                e_alasan_name
            FROM
                tr_alasan_retur
            ORDER BY
                2
        ", FALSE);
    }

    /*----------  RUNNING ID SPBD  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_penjualan');
        return $this->db->get()->row()->id+1;
    }

    /*----------  NOT RUNNING ID SPBD  ----------*/
    
    public function notrunningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_retur_penjualan');
        return $this->db->get()->row()->id;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ialasan,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp)
    {
        $data = array(
            'id'                => $id,
            'id_company'        => $this->company,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_customer'       => $idcustomer,
            'e_customer_name'   => $this->db->query("SELECT e_customer_name FROM tr_customer WHERE id = '$idcustomer' AND id_company = '$this->company' ")->row()->e_customer_name,
            'id_alasan_retur'   => $ialasan,
            'v_diskon'          => $vdiskon,
            'v_kotor'           => $vkotor,
            'v_ppn'             => $vppn,
            'v_bersih'          => $vbersih,
            'v_dpp'             => $vdpp,
            'e_remark'          => $eremarkh,
            'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_retur_penjualan', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/
    
    public function insertdetail($id,$iddocument,$iddocumentdetail,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark)
    {
        $data = array(
            'id_company'            => $this->company,
            'id_document'           => $id,
            'id_referensi'          => $iddocument,
            'id_referensi_detail'   => $iddocumentdetail,
            'id_product'            => $idproduct,
            'n_quantity'            => $nquantity,
            'n_quantity_sisa'       => $nquantity,
            'v_price'               => $vprice,
            'n_diskon1'             => $ndiskon1,
            'n_diskon2'             => $ndiskon2,
            'n_diskon3'             => $ndiskon3,
            'v_diskon1'             => $vdiskon1,
            'v_diskon2'             => $vdiskon2,
            'v_diskon3'             => $vdiskon3,
            'v_diskon_tambahan'     => $vdiskonplus,
            'v_diskon_total'        => $vtotaldiskon,
            'v_total'               => $vtotal,
            'e_remark'              => $eremark,
        );
        $this->db->insert('tm_retur_penjualan_item', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/
    
    public function editheader($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                e_bagian_name,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                id_customer,
                i_customer,
                e.e_customer_name,
                a.id_alasan_retur,
                e_alasan_name,
                e_remark,
                i_status,
                v_bersih,
                v_diskon,
                v_dpp,
                v_ppn,
                v_kotor
            FROM
                tm_retur_penjualan a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_alasan_retur c ON
                (c.id = a.id_alasan_retur)
            INNER JOIN tr_customer e ON
                (e.id = a.id_customer)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  GET NO REFERENSI VIEW, EDIT & APPROVE HEADER  ----------*/
    
    public function noreferensi($id)
    {
        return $this->db->query("
            SELECT DISTINCT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_nota_penjualan a
            INNER JOIN tm_retur_penjualan_item b ON
                (b.id_referensi = a.id
                AND a.id_company = b.id_company)
            WHERE
                b.id_document = $id
            ORDER BY 
                a.id
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/
    
    public function edititem($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.*,
                b.i_product_base AS i_product,
                b.e_product_basename AS e_product,
                to_char(e.d_document, 'dd-mm-yyyy') AS d_document,
                e.i_document,
                f.i_document AS i_sj,
                d.id_document_reff,
                d.n_quantity_sisa AS n_quantity_sisa_reff
            FROM
                tm_retur_penjualan_item a
            INNER JOIN tr_product_base b ON
                (b.id = a.id_product)
            INNER JOIN tm_nota_penjualan_item d ON
                (d.id_document = a.id_referensi
                AND a.id_product = d.id_product
                AND d.id = a.id_referensi_detail)
            INNER JOIN tm_nota_penjualan e ON
                (e.id = d.id_document)
            INNER JOIN tm_sj f ON
                (f.id = d.id_document_reff
                AND d.id_company = f.id_company)
            WHERE
                a.id_document = $id
            ORDER BY
                a.id_referensi,
                d.id_document_reff
        ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_retur_penjualan');
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
        $this->db->delete('tm_retur_penjualan_item');
    }

    /*----------  UPDATE HEADER  ----------*/
    
    public function updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ialasan,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp)
    {
        $data = array(
            'id_company'        => $this->company,
            'i_document'        => $idocument,
            'd_document'        => $ddocument,
            'i_bagian'          => $ibagian,
            'id_customer'       => $idcustomer,
            'e_customer_name'   => $this->db->query("SELECT e_customer_name FROM tr_customer WHERE id = '$idcustomer' AND id_company = '$this->company' ")->row()->e_customer_name,
            'id_alasan_retur'   => $ialasan,
            'v_diskon'          => $vdiskon,
            'v_kotor'           => $vkotor,
            'v_ppn'             => $vppn,
            'v_bersih'          => $vbersih,
            'v_dpp'             => $vdpp,
            'e_remark'          => $eremarkh,
            'd_update'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_retur_penjualan', $data);
    }

    /*----------  UPDATE SISA NOTA  ----------*/

    public function updatesisa($id)
    {
        $query = $this->db->query("
            SELECT 
                id_product, 
                id_referensi,
                id_referensi_detail,
                n_quantity
            FROM tm_retur_penjualan_item
            WHERE 
                id_document = '$id'
                AND id_company = '$this->company'
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $nsisa = $this->db->query("
                    SELECT
                        n_quantity_sisa
                    FROM
                        tm_nota_penjualan_item
                    WHERE
                        id              = '$key->id_referensi_detail'
                        AND id_document = '$key->id_referensi'
                        AND id_product  = '$key->id_product'
                        AND id_company  = '$this->company'
                        AND n_quantity_sisa >= '$key->n_quantity'
                ", FALSE);

                if ($nsisa->num_rows() > 0) {
                    $this->db->query("
                    UPDATE
                        tm_nota_penjualan_item
                    SET
                        n_quantity_sisa = n_quantity_sisa - $key->n_quantity
                    WHERE
                        id              = '$key->id_referensi_detail'
                        AND id_document = '$key->id_referensi'
                        AND id_product  = '$key->id_product'
                        AND id_company  = '$this->company'
                        AND n_quantity_sisa >= '$key->n_quantity'
                    ", FALSE);
                } else {
                    die();
                }
            }
        }
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
        $this->db->update('tm_retur_penjualan', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/
}
/* End of file Mmaster.php */