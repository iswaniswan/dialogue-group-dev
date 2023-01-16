<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DEKLARASI SESSION  ----------*/
    
    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    /*----------  DAFTAR SUPPLIER YANG PKP DAN ADA DI FAKTUR PEMBELIAN  ----------*/
    
    public function cek_supplier($dfrom,$dto){
        return $this->db->query("
            SELECT
                DISTINCT(a.i_supplier),
                a.e_supplier_name
            FROM
                tr_supplier a
            JOIN tm_notabtb b ON
                (a.i_supplier = b.i_supplier 
                AND b.id_company = a.id_company)
            WHERE
                i_status IN ('11','12','13')
                AND b.d_nota >= '".date('Y-m-d', strtotime($dfrom))."'
                AND b.d_nota <= '".date('Y-m-d', strtotime($dto))."'
            ORDER BY 2
        ", FALSE);
    }

    /*----------  DAFTAR DATA FAKTUR PEMBELIAN  ----------*/
    
    public function data($isupplier,$dfrom,$dto,$i_menu,$folder)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_nota BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        if ($isupplier == 'SP') {
            $supplier = "";            
        }else{
            $supplier = "AND a.i_supplier = '$isupplier'";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS no,
                i_nota,
                to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                i_pajak,
                to_char(d_pajak, 'dd-mm-yyyy') AS tanggal_faktur,
                e_npwp_name,
                v_dpp AS jumlah_dpp,
                v_ppn AS jumlah_ppn,
                v_dpp + v_ppn AS jumlah_total
            FROM
                tm_notabtb a
            INNER JOIN tr_supplier b ON
                (b.i_supplier = a.i_supplier
                AND a.id_company = b.id_company)
            WHERE 
                a.i_status IN ('11','12','13')
                AND a.id_company = $this->company
                $where
                $supplier
            ORDER BY
                1,2
        ", FALSE);
        $datatables->edit('jumlah_dpp', function ($data) {
            return number_format($data['jumlah_dpp']);
        });
        $datatables->edit('jumlah_ppn', function ($data) {
            return number_format($data['jumlah_ppn']);
        });
        $datatables->edit('jumlah_total', function ($data) {
            return number_format($data['jumlah_total']);
        });
        return $datatables->generate();
    }

    /*----------  DAFTAR DATA FAKTUR PEMBELIAN  ----------*/    

    public function exportdata($isupplier,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_nota BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        if ($isupplier == 'SP') {
            $supplier = "";            
        }else{
            $supplier = "AND a.i_supplier = '$isupplier'";
        }
        return $this->db->query("
            SELECT
                'FM' AS fm,
                '01' AS kode_jt,
                '0' AS fg,
                i_pajak,
                to_char(d_pajak, 'mm') AS masa_pajak,
                to_char(d_pajak, 'yyyy') AS tahun_pajak,
                to_char(d_pajak, 'dd-mm-yyyy') AS tanggal_faktur,
                CASE
                    WHEN (i_supplier_npwp ISNULL OR i_supplier_npwp ='')
                    THEN '00.000.000.0.000.000'
                    ELSE i_supplier_npwp 
                END AS npwp,
                CASE
                    WHEN (e_npwp_name ISNULL OR e_npwp_name ='')
                    THEN a.e_supplier_name
                    ELSE e_npwp_name 
                END AS e_npwp_name,
                CASE
                    WHEN (e_npwp_address ISNULL OR e_npwp_address ='')
                    THEN e_supplier_address
                    ELSE e_npwp_address 
                END AS e_npwp_address,
                v_dpp AS jumlah_dpp,
                v_ppn AS jumlah_ppn,
                '0' AS jumlah_ppnbm,
                '1' AS is_creditable
            FROM
                tm_notabtb a
            INNER JOIN tr_supplier b ON
                (b.i_supplier = a.i_supplier
                AND a.id_company = b.id_company)
            WHERE 
                a.i_status IN ('11','12','13')
                AND a.id_company = $this->company
                $where
                $supplier
            ORDER BY
                7,8
        ", FALSE);
    }
}
/* End of file Mmaster.php */ 