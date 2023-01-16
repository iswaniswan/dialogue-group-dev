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
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_spb
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
        $datatables->query("SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_customer,
                b.e_customer_name,
                a.i_referensi,
                e_remark,
                a.i_status,
                a.e_jenis_spb,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_spb a
            INNER JOIN tr_customer b ON
                (a.id_customer = b.id)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            WHERE
                a.i_status = '6'
                AND a.id_company = $this->company 
                AND a.i_promo ISNULL
                AND a.d_approve_keuangan ISNULL 
                AND a.d_not_approve_keuangan ISNULL
                $and 
                $bagian
            ORDER BY
                a.id ASC
        ", FALSE);

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $idcustomer = trim($data['id_customer']);
            $ddocument  = $data['d_document'];
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $i_status   = $data['i_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $jenis      = $data['e_jenis_spb'];
            $data       = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }

            if (check_role($i_menu, 7) && $i_status == '6') {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/$jenis/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_customer');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('e_jenis_spb');
        return $datatables->generate();
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
                a.id_harga_kode,
                i_harga,
                e_harga,
                a.id_area,
                a.id_salesman,
                e_area,
                i_area,
                i_referensi,
                e_remark,
                i_status,
                v_netto AS v_bersih,
                v_discount AS v_diskon,
                v_dpp,
                v_ppn,
                v_bruto AS v_kotor,
                e.v_customer_discount,
                e.v_customer_discount2,
                e.v_customer_discount3,
                e_customer_address,
                e_city_name,
                e_sales,
                to_char(e.d_join, 'dd-mm-yyyy') AS d_join
            FROM
                tm_spb a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_harga_kode c ON
                (c.id = a.id_harga_kode)
            INNER JOIN tr_area d ON
                (d.id = a.id_area)
            INNER JOIN tr_customer e ON
                (e.id = a.id_customer)
            INNER JOIN tr_city f ON 
                (f.id = e.id_city)
            INNER JOIN tr_salesman g ON 
                (g.id = a.id_salesman)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/
    
    public function edititem($id,$jenis)
    {
        if($jenis == "Manual"){
            return $this->db->query("
                SELECT DISTINCT
                    a.*,
                    b.i_product_base,
                    b.e_product_basename,
                    c.id_customer,
                    to_char(c.d_document, 'yyyymm'),
                    a.n_quantity_sisa as fc, 
                    e_satuan_name 
                FROM
                   tm_spb_item a 
                   INNER JOIN
                      tr_product_base b 
                      ON (b.id = a.id_product) 
                   INNER JOIN
                      tr_satuan s 
                      ON (s.i_satuan_code = b.i_satuan_code 
                      AND b.id_company = s.id_company) 
                   INNER JOIN
                      tm_spb c 
                      ON (a.id_document = c.id) 
                WHERE
                   a.id_document = '$id'
                ORDER BY
                   a.id
            ", FALSE);
        }else if($jenis == "FC"){
            return $this->db->query("
                SELECT DISTINCT
                    a.*,
                    b.i_product_base,
                    b.e_product_basename,
                    c.id_customer, 
                    to_char(c.d_document, 'yyyymm'),
                    case when e.id is not null then coalesce(d.n_quantity_fc, 0) else 9999 end as fc,
                    e_satuan_name
                FROM
                    tm_spb_item a
                    INNER JOIN tr_product_base b ON (b.id = a.id_product)
                    INNER JOIN tr_satuan s ON (s.i_satuan_code = b.i_satuan_code AND b.id_company = s.id_company)
                    INNER JOIN tm_spb c ON (a.id_document = c.id)
                    LEFT JOIN f_get_forecast_distributor($this->company,to_char(d_document, 'yyyymm'), c.id_customer) d 
                        ON (d.id_company = a.id_company and d.periode = to_char(c.d_document, 'yyyymm')  and c.id_customer = d.id_customer and a.id_product = d.id_product)
                    LEFT JOIN tr_customer_transfer e on (a.id_company = e.id_company and c.id_customer = e.id_customer)
                WHERE
                    a.id_document = $id
                ORDER BY
                    a.id
            ", FALSE);
        }else if($jenis == "Transfer"){
            return $this->db->query("
                SELECT DISTINCT
                    a.*,
                    b.i_product_base,
                    b.e_product_basename,
                    c.id_customer, 
                    to_char(c.d_document, 'yyyymm'),
                    a.n_quantity_sisa as fc,
                    e_satuan_name
                FROM
                    tm_spb_item a
                    INNER JOIN tr_product_base b ON (b.id = a.id_product)
                    INNER JOIN tr_satuan s ON (s.i_satuan_code = b.i_satuan_code AND b.id_company = s.id_company)
                    INNER JOIN tm_spb c ON (a.id_document = c.id)
                    LEFT JOIN tr_customer_transfer e on (a.id_company = e.id_company and c.id_customer = e.id_customer)
                WHERE
                    a.id_document = $id
                ORDER BY
                    a.id
            ", FALSE);
        }
    }

    /*----------  APPROVE  ----------*/   
    
    public function approve($id, $istatus, $note)
    {
        if($istatus=='6'){
            $data = array(
                'i_approve_keuangan'     => $this->username,
                'd_approve_keuangan'     => date('Y-m-d'),
                'e_approve_keuangan'     => $note,
            );
            $this->db->where('id', $id);
            $this->db->update('tm_spb', $data);
            $this->Logger->write('Approve SPB bagian Keuangan : '.$this->username);
        }else{
            $data = array(
                'i_not_approve_keuangan' => $this->username,
                'd_not_approve_keuangan' => date('Y-m-d'),
                'e_not_approve_keuangan' => $note,
            );
            $this->db->where('id', $id);
            $this->db->update('tm_spb', $data);
            $this->Logger->write('Not Approve SPB bagian Keuangan : '.$this->username);
        }
    }

    /*----------  END APPROVE  ----------*/   
    
}
/* End of file Mmaster.php */