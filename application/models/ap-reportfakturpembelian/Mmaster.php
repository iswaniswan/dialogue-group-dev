<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function typemakloon(){
        return $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tm_type_makloon
                                WHERE
                                    f_status_aktif = 't'
                                ORDER BY
                                    i_type_makloon
                                ", FALSE)->result();
    }

    public function getmakloon($itypemakloon){
        return $this->db->query("
                                SELECT
                                    e_type_makloon
                                FROM
                                    tm_type_makloon
                                WHERE
                                    f_status_aktif = 't'
                                    AND i_type_makloon = '$itypemakloon'
                                ORDER BY
                                    i_type_makloon
                                ", FALSE);
    }

    public function getsupplier($id){
        if($id == 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        'ALL' as i_supplier,
                                        'Semua Supplier' as e_supplier_name
                                    FROM
                                        tr_supplier
                                    ",FALSE);
        }else{
            return $this->db->query("
                                SELECT
                                    i_supplier,
                                    e_supplier_name
                                FROM
                                    tr_supplier
                                WHERE
                                    i_type_makloon = '$id'
                                ", FALSE);
        }
    }

    public function getsuppliername($isupplier){
        return $this->db->query("
                                SELECT
                                    e_supplier_name
                                FROM
                                    tr_supplier
                                WHERE
                                    i_supplier = '$isupplier'
                                ", FALSE);
    }

    #public function getfaktur($periode, $isupplier){
    public function getfaktur($periode, $isupplier){
        if($isupplier == 'ALL'){
            return $this->db->query(" 
                                    SELECT 
                                        a.no_faktur,
                                        a.nama_supplier
                                    FROM
                                        f_report_faktur_jasamakloon('$periode') a
                                ", FALSE);
        }else{
            return $this->db->query(" 
                                    SELECT 
                                        a.no_faktur,
                                        a.nama_supplier
                                    FROM
                                        f_report_faktur_jasamakloon('$periode') a
                                    WHERE 
                                        a.kode_supplier = '$isupplier'
                                ", FALSE);
        }
        
    }

    #public function data($bulan, $tahun, $isupplier, $ifaktur, $i_menu, $folder){
    public function data($bulan, $tahun, $isupplier, $i_menu, $folder){
        $periode = $tahun.$bulan;
        $isupplierx = '';

        if($isupplier != ''){
            $where = "AND a.i_supplier = '$isupplier' ";
        }else{
            $where = "";
            $isupplierx = "ALL";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        
            $datatables->query(" SELECT 
                                    e_supplier_name,
                                    i_nota,
                                    to_char(d_nota,'dd-mm-yyyy') as d_nota,
                                    v_total_bruto,
                                    v_total_diskon,
                                    v_dpp,
                                    v_ppn,
                                    v_total_net,
                                    a.i_supplier,
                                    a.i_payment_type,
                                    '$i_menu' as imenu,
                                    '$folder' as folder,
                                    '$bulan' as bulan,
                                    '$tahun' as tahun,
                                    '$isupplierx' as isupplierx
                                FROM
                                    tm_notabtb a
                                INNER JOIN tr_supplier b ON
	                                (a.i_supplier = b.i_supplier)
                                WHERE 
                                    to_char(d_nota,'yyyymm') = '$periode'
                                    $where ", FALSE);
        
        $datatables->add('action', function ($data) {
            $inota          = trim($data['i_nota']);
            $isupplier      = trim($data['i_supplier']);
            $ipayment       = trim($data['i_payment_type']);
            $folder         = trim($data['folder']);
            $imenu          = trim($data['imenu']);
            $bulan          = trim($data['bulan']);
            $tahun          = trim($data['tahun']);
            $isupplierx     = trim($data['isupplierx']);

            $data = '';
            if(check_role($imenu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"ap-reportfakturpembelian/cform/view/$inota/$isupplier/$ipayment/$bulan/$tahun/$isupplierx/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
			 return $data;
        });

        $datatables->edit('v_total_bruto', function ($data) {
            return number_format($data['v_total_bruto']);
        });

        $datatables->edit('v_total_diskon', function ($data) {
            return number_format($data['v_total_diskon']);
        });

        $datatables->edit('v_dpp', function ($data) {
            return number_format($data['v_dpp']);
        });
        
        $datatables->edit('v_ppn', function ($data) {
            return number_format($data['v_ppn']);
        });

        $datatables->edit('v_total_net', function ($data) {
            return number_format($data['v_total_net']);
        });

        $datatables->hide('imenu');
        $datatables->hide('folder');
        $datatables->hide('bulan');
        $datatables->hide('tahun');
        $datatables->hide('i_supplier');
        $datatables->hide('isupplierx');
        $datatables->hide('i_payment_type');

        return $datatables->generate();
    }

    function cek_data($id){
        $this->db->select("a.*, b.e_supplier_name, b.f_supplier_pkp");
        $this->db->from("tm_notabtb a");
        $this->db->join("tr_supplier b","a.i_supplier = b.i_supplier");
        $this->db->where("i_nota",$id);
        //$this->db->order_by('a.i_nota', $id);
        return $this->db->get();
    }

    function get_itemm($inota, $isupplier){
        $this->db->select(" a.*, b.*, c.e_supplier_name, c.f_supplier_pkp, f.i_material, e.e_material_name, e.i_satuan_code, g.e_satuan, f.n_qty_eks, f.i_satuan_eks, h.e_satuan as satuaneks  
                      from tm_notabtb a
                      join tm_notabtb_item b on a.i_nota = b.i_nota
                      join tr_supplier c on a.i_supplier = c.i_supplier
                      join tm_sj_pembelian d on b.i_sj = d.i_sj
                      join tm_sj_pembelian_detail f on d.i_sj = f.i_sj
                      left join tr_material e on f.i_material = e.i_material
                      join tr_satuan g on g.i_satuan_code=e.i_satuan_code
                      join tr_satuan h on h.i_satuan=f.i_satuan_eks 
                      where a.i_nota='$inota' and a.i_supplier='$isupplier'", false);
        return $this->db->get();
    }

    function get_supplier(){
        $this->db->select('*');
        $this->db->from('tr_supplier');
        $this->db->order_by('i_supplier');
    return $this->db->get();
    }

    public function bacaexport($bulan, $tahun, $isupplier){
        $periode = $tahun.$bulan;

        if($isupplier=="ALL"){
            $where = "";
        }else{
            $where = "AND a.i_supplier = '$isupplier'";
        }
        
            return $this->db->query("   SELECT
                                            a.i_nota,
                                            to_char(d_nota, 'dd-mm-yyyy') AS d_nota,
                                            a.e_remark,
                                            c.e_supplier_name,
                                            --a.*,
                                            --b.*,
                                            b.i_btb,
                                            --c.f_supplier_pkp,
                                            b.i_material,
                                            e.e_material_name,
                                            f.n_qty_eks,
                                            h.e_satuan AS satuaneks,
                                            b.n_quantity AS qtyin,
                                            g.e_satuan,
                                            b.v_price,
                                            b.v_dpp,
                                            b.v_ppn,
                                            a.v_total
                                        FROM
                                            tm_notabtb a
                                        JOIN tm_notabtb_item b ON
                                            a.i_nota = b.i_nota
                                        JOIN tr_supplier c ON
                                            a.i_supplier = c.i_supplier
                                        JOIN tm_sj_pembelian d ON
                                            b.i_sj = d.i_sj
                                        JOIN tm_sj_pembelian_detail f ON
                                            d.i_sj = f.i_sj
                                        LEFT JOIN tr_material e ON
                                            f.i_material = e.i_material
                                        JOIN tr_satuan g ON
                                            g.i_satuan_code = e.i_satuan_code
                                        JOIN tr_satuan h ON
                                            h.i_satuan = f.i_satuan_eks	
                                        WHERE
                                            to_char(a.d_nota,'yyyymm') = '$periode'
                                            $where ");
    }

    public function getnofaktur($periode, $ifaktur){
        return $this->db->query("
                                SELECT 
                                    a.no_faktur
                                FROM
                                    f_report_faktur_jasamakloon('$periode') a
                                WHERE
                                    a.no_faktur = '$ifaktur'
                                ",FALSE);
    }

    public function bacaheader($bulan, $tahun, $isupplier){
        $periode = $tahun.$bulan;
        
        if($isupplier == 'ALL' && $itypemakloon == 'ALL' && $ifaktur == 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                    ) AS a
                                    ");
        }else if($isupplier != 'ALL' && $itypemakloon != 'ALL' && $ifaktur == 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                        WHERE
                                            kode_supplier = '$isupplier'
                                    ) AS a
                                    ");
        }else if($isupplier == 'ALL' && $itypemakloon == 'ALL' && $ifaktur != 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                        WHERE
                                            no_faktur = '$ifaktur'
                                    ) AS a
                                    ");
        }else if($isupplier != 'ALL' && $itypemakloon != 'ALL' && $ifaktur != 'ALL'){
            return $this->db->query("
                                    SELECT 
                                        SUM(a.nilai_gross) AS total_gross,
                                        SUM(a.nilai_discount) AS total_discount,
                                        SUM(a.nilai_netto) AS total_netto,
                                        SUM(a.dpp) AS total_dpp,
                                        SUM(a.ppn) AS total_ppn 
                                    FROM (
                                        SELECT DISTINCT
                                            no_faktur,
                                            nilai_gross,
                                            nilai_discount,
                                            nilai_netto,
                                            dpp,
                                            ppn
                                        FROM
                                            f_report_faktur_jasamakloon_item('$periode')
                                        WHERE
                                            no_faktur = '$ifaktur'
                                            AND kode_supplier = '$isupplier'
                                    ) AS a
                                    ");
        }

    }

}

/* End of file Mmaster.php */