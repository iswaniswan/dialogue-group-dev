<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_op BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            WITH query AS (
            SELECT
                NO,
                ROW_NUMBER() OVER (
                ORDER BY a.id) AS i,
                id,
                i_op,
                d_op,
                e_supplier_name,
                i_sj_supplier,
                d_sj,
                i_btb,
                d_btb,
                f_op_close,
                i_menu
                FROM(
                    SELECT
                        DISTINCT ON
                        (i_op) 0 AS no,
                        a.id,
                        i_op,
                        to_char(d_op, 'dd-mm-yyyy') AS d_op,
                        a.e_supplier_name,
                        i_sj_supplier,
                        to_char(d_sj_supplier, 'dd-mm-yyyy') AS d_sj,
                        i_btb,
                        to_char(d_btb, 'dd-mm-yyyy') AS d_btb,
                        f_op_close,
                        '$i_menu' AS i_menu
                    FROM
                        tm_opbb a
                    INNER JOIN tm_opbb_item b ON
                        (b.id_op = a.id)
                    LEFT JOIN tm_btb_item c ON
                        (c.id_op = b.id_op
                        AND b.i_material = c.i_material)
                    LEFT JOIN tm_btb d ON
                        (d.id = c.id_btb)
                    WHERE
                        a.i_status = '6'
                        AND d.i_status = '6'
                        AND a.id_company = '".$this->session->userdata('id_company')."'
                        $and) AS a)
                SELECT
                    NO,
                    i,
                    id,
                    i_op,
                    d_op,
                    e_supplier_name,
                    i_sj_supplier,
                    d_sj,
                    i_btb,
                    d_btb,
                    f_op_close,
                    i_menu,
                    (
                    SELECT
                        count(i) AS jml
                    FROM
                        query) AS jml
                FROM
                    query
        ", FALSE);

        $datatables->edit('f_op_close', function ($data) {
            if($data['f_op_close'] == 'f'){
                return '<span class="label label-danger"><b>Belum</b></span>';
            }else{
                return '<span class="label label-success"><b>Sudah</b></span>';
            }
        });

        $datatables->add('action', function ($data) {
            $id     = $data['id'];
            $i      = $data['i'];
            $jml      = $data['jml'];
            $iop    = trim($data['i_op']);
            $close  = $data['f_op_close'];
            $data   = '';
            if($close == 'f'){
                $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"id".$i."\" value=\"".$id."\" type=\"hidden\">
                <input name=\"jml\" value=\"".$jml."\" type=\"hidden\">
                <input name=\"iop".$i."\" value=\"".$iop."\" type=\"hidden\">";
            }else{
                $data .= "<a href=\"#\" onclick='unclosing(\"$id\",\"$iop\"); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('id');
        $datatables->hide('i');
        $datatables->hide('jml');
        return $datatables->generate();
    }

    public function closing($id)
    {
        $data = array(
            'f_op_close' => 't', 
        );
        $this->db->where('id', $id);
        $this->db->update('tm_opbb', $data);
    }

    public function unclosing($id)
    {
        $data = array(
            'f_op_close' => 'f', 
        );
        $this->db->where('id', $id);
        return $this->db->update('tm_opbb', $data);
    }

    public function closingpembelianbarangjadi($id_company, $ibagian, $periodeclosing, $periodenext) {
        
        /*----------  Mutasi Pembelian Barang Jadi  ----------*/
        
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_base_material
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            insert into tm_mutasi_saldoawal_base_material(id_company, i_bagian, e_mutasi_periode, i_material, n_saldo_awal)
            select id_company,'$ibagian', '$periodenext', i_material, sum(n_saldo_awal+beli-returbeli) as saldo_akhir from (
                select id_company, i_material, n_saldo_awal, 0 as beli, 0 as returbeli from tm_mutasi_saldoawal_base_material where e_mutasi_periode = '$periodeclosing' and id_company = '$id_company' and i_bagian = '$ibagian'
                union all
                select a.id_company, b.i_material, 0 as n_saldo_awal, sum(b.n_quantity) as pembelian, 0 as returbeli from tm_btb a 
                inner join tm_btb_item b on (a.id = b.id_btb)
                inner join tr_material c on (b.i_material = c.i_material and a.id_company = c.id_company)
                where to_char(a.d_btb, 'yyyymm') = '$periodeclosing' and a.i_status = '6' and a.id_company = '$id_company' and a.i_bagian = '$ibagian' and c.i_kode_group_barang = 'GRB0003'
                group by a.id_company, b.i_material
                union all
                select a.id_company, b.i_material, 0 as n_saldo_awal, 0 as pembelian, sum(b.n_quantity) as returbeli from tm_retur_beligdjd a 
                inner join tm_retur_beligdjd_item b on (a.id = b.id_retur_beli)
                inner join tr_material c on (b.i_material = c.i_material and a.id_company = c.id_company)
                where to_char(a.d_retur, 'yyyymm') = '$periodeclosing' and a.i_status = '6' and a.id_company = '$id_company' and a.i_bagian = '$ibagian' and c.i_kode_group_barang = 'GRB0003'
                group by a.id_company, b.i_material
            ) as x
            GROUP BY 1,2,3,4
            /*ON CONFLICT (id_company, i_bagian, e_mutasi_periode, i_material) DO UPDATE SET n_saldo_awal = excluded.n_saldo_awal;*/
        ", FALSE);

        $x     = $periodeclosing.'01';
        $awal  = date('Y-m-d', strtotime($x));
        $akhir = date('Y-m-t', strtotime($x));

        /*----------  Mutasi Material Barang Jadi  ----------*/
        
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_base_material
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_base_material
                (id_company, i_bagian, e_mutasi_periode, i_material, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_material, 
                    saldo_akhir 
                FROM f_mutasi_saldoawal_bb ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_pinjaman_material
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_pinjaman_material
                (id_company, i_bagian, e_mutasi_periode, i_material, id_partner, i_partner, type_partner, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_material,
                    id_partner,
                    i_partner,
                    type_partner,
                    saldo_akhir
                FROM f_mutasi_saldoawal_bbpinjaman ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);

        /*----------  Mutasi Gudang Jadi  ----------*/
        
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_base_jadi
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_base_jadi
                (id_company, i_bagian, e_mutasi_periode, i_product_base, i_color, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_product_base, 
                    i_color, 
                    saldo_akhir 
                FROM f_mutasi_saldoawal_gdjadi ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
                WHERE jenis_barang = 'Barang Jadi'
        ", FALSE);
        $this->db->query("
            DELETE FROM
                tm_mutasi_saldoawal_pinjaman_jadi
                WHERE id_company = $this->company AND i_bagian = '$ibagian' AND e_mutasi_periode = '$periodenext';
            INSERT INTO 
                tm_mutasi_saldoawal_pinjaman_jadi
                (id_company, i_bagian, e_mutasi_periode, i_product_base, i_color, id_partner, i_partner, type_partner, n_saldo_awal)
                SELECT 
                    id_company, 
                    '$ibagian', 
                    '$periodenext', 
                    i_product_base, 
                    i_color,
                    id_partner,
                    i_partner,
                    type_partner,
                    saldo_akhir
                FROM f_mutasi_saldoawal_gdjadipinjaman ($this->company,'$periodeclosing','9999-12-01','9999-12-31','$awal','$akhir','$ibagian')
        ", FALSE);
    }

    public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_type', '04');    
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }
}
/* End of file Mmaster.php */