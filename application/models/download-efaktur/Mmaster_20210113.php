<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    /*----------  DEKLARASI SESSION  ----------*/
    
    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    public function bacasupplier()
    {
        return $this->db->query("
            SELECT
                id,
                i_supplier,
                e_supplier_name
            FROM
                tr_supplier
            WHERE
                f_status = 't'
                AND f_pkp = 't'
                AND id_company = $this->company
            ORDER BY
                3
        ",FALSE)->result();
    }

    public function supplier($kode)
    {
        return $this->db->query("select i_supplier, e_supplier_name from tr_supplier where i_supplier='$kode'", false);
    }

    function data($dfrom, $dto, $isupplier, $folder, $i_menu)
    {
        $datatables = new Datatables(new CodeigniterAdapter);
        if ($isupplier == 'NA') {
            $datatables->query(
                "
                                 select distinct 
                                    a.i_pajak,
                                    '01' as fk,
                                    '0' as faktur_pengganti,
                                    to_char(a.d_pajak, 'mm') as masa_pajak,
                                    to_char(a.d_pajak, 'yyyy') as tahun_pajak,
                                    to_char(a.d_pajak, 'dd/mm/yyyy') as tgl_pajak,
                                    d.i_supplier_npwp,
                                    d.e_supplier_name,
                                    d.e_supplier_address,
                                    floor(a.v_dpp) as dpp,
                                    floor(a.v_ppn) as ppn,
                                    '0' as ppnbm,
                                    a.i_supplier as isupplier,
                                    '$dfrom' as dfrom,
                                    '$dto' as dto,
                                    '$isupplier' as supplier,
                                    '$folder' as folder,
                                    '$i_menu' as imenu
                                 from
                                    tm_notabtb a,
                                    tm_notabtb_item b,
                                    tr_supplier d
                                 where
                                    a.i_supplier = d.i_supplier
                                    and d.f_supplier_pkp = 't'
                                    and a.f_nota_cancel = 'false' 
                                    and a.d_pajak >= to_date('$dfrom', 'dd-mm-yyyy') 
                                    and a.d_pajak <= to_date('$dto', 'dd-mm-yyyy') 
                                    and a.i_nota = b.i_nota 
                                 order by
                                    a.i_pajak
                                 ",
                false
            );
        } else {
            $datatables->query(
                "
                              select distinct
                                 a.i_pajak,
                                 '01' as fk,
                                 '0' as faktur_pengganti,
                                 to_char(a.d_pajak, 'mm') as masa_pajak,
                                 to_char(a.d_pajak, 'yyyy') as tahun_pajak,
                                 to_char(a.d_pajak, 'dd/mm/yyyy') as tgl_pajak,
                                 d.i_supplier_npwp,
                                 d.e_supplier_name,
                                 d.e_supplier_address,
                                 floor(a.v_dpp) as dpp,
                                 floor(a.v_ppn) as ppn,
                                 '0' as ppnbm,
                                 a.i_supplier as isupplier,
                                 '$dfrom' as dfrom,
                                 '$dto' as dto,
                                 '$isupplier' as supplier,
                                 '$folder' as folder,
                                 '$i_menu' as imenu
                              from
                                 tm_notabtb a,
                                 tm_notabtb_item b,
                                 tr_supplier d
                              where
                                 a.i_supplier = d.i_supplier
                                 and d.f_supplier_pkp = 't'
                                 and a.f_nota_cancel = 'false' 
                                 and a.d_pajak >= to_date('$dfrom', 'dd-mm-yyyy') 
                                 and a.d_pajak <= to_date('$dto', 'dd-mm-yyyy') 
                                 and a.i_nota = b.i_nota 
                                 and a.i_supplier = '$isupplier'
                              order by
                                 a.i_pajak
                              ",
                false
            );
        }
        $datatables->add(
            'action',
            function($data) {
                $ifaktur    = trim($data['i_pajak']);
                $folder     = trim($data['folder']);
                $dfrom      = trim($data['dfrom']);
                $dto        = trim($data['dto']);
                $isupplier  = trim($data['isupplier']);
                $i_menu     = trim($data['imenu']);
                $data       = '';
                if (check_role($i_menu, 3)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/detail/$ifaktur/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            //$data .= "&nbsp;&nbsp;<a href=\"#\" id=\"href\" onclick='downloadfaktur(\"$ifaktur\",\"$isupplier\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-eye'></i></a>";
                }
                return $data;
            }
        );

        $datatables->edit(
            'dpp',
            function ($data) {
                return number_format($data['dpp']);
            }
        );
        $datatables->edit(
            'ppn',
            function($data) {
                return number_format($data['ppn']);
            }
        );

        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('isupplier');
        $datatables->hide('imenu');
        $datatables->hide('supplier');

        return $datatables->generate();
    }

    public function getAll($dfrom, $dto)
    {
        return $this->db->query(
            "
                              select distinct
                                 a.i_pajak,
                                 '01' as fk,
                                 '0' as faktur_pengganti,
                                 to_char(a.d_pajak, 'mm') as masa_pajak,
                                 to_char(a.d_pajak, 'yyyy') as tahun_pajak,
                                 to_char(a.d_pajak, 'dd/mm/yyyy') as tgl_pajak,
                                 d.i_supplier_npwp,
                                 d.e_supplier_name,
                                 d.e_supplier_address,
                                 floor(a.v_dpp) as dpp,
                                 floor(a.v_ppn) as ppn,
                                 '0' as ppnbm
                              from
                                 tm_notabtb a,
                                 tm_notabtb_item b,
                                 tr_supplier d
                              where
                                 a.i_supplier = d.i_supplier
                                 and a.f_nota_cancel = 'false' 
                                 and a.d_pajak >= to_date('$dfrom', 'dd-mm-yyyy') 
                                 and a.d_pajak <= to_date('$dto', 'dd-mm-yyyy') 
                                 and a.i_nota = b.i_nota 
                              order by
                                 a.i_pajak",
            false
        );
    }

    public function getdetail($ifaktur, $isupplier)
    {
        return $this->db->query(
            "
                              select distinct
                                 a.i_pajak,
                                 a.d_pajak,
                                 d.i_supplier_npwp,
                                 d.e_supplier_name,
                                 d.e_supplier_address,
                                 a.i_nota,
                                 a.d_nota,
                                 b.i_sj,
                                 b.d_sj,
                                 b.i_btb,
                                 a.v_total_diskon,
                                 floor(a.v_dpp) as dpp,
                                 floor(a.v_ppn) as ppn,
                                 '0' as ppnbm,
                                 b.i_material,
                                 c.e_material_name,
                                 b.n_quantity,
                                 b.v_price,
                                 b.v_tot_sj,
                                 b.v_dpp,
                                 b.v_ppn
                              from
                                 tm_notabtb a,
                                 tm_notabtb_item b,
                                 tr_material c,
                                 tr_supplier d
                              where
                                 b.i_material = c.i_material
                                 and a.i_supplier = d.i_supplier
                                 and a.f_nota_cancel = 'false' 
                                 and a.i_nota = b.i_nota 
                                 and a.i_pajak = '$ifaktur'
                                 and a.i_supplier = '$isupplier'",
            false
        );
    }
}

/* End of file Mmaster.php */