<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_nota BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "
            SELECT no, id, i_nota, d_nota, d_terima_faktur, i_supplier, e_supplier_name , i_sj_supplier, i_pajak , i_faktur_supplier , v_total ,
            e_remark , i_status , id_company , e_status_name, label_color, i_level, e_level_name, i_menu, folder, dfrom, dto
            from (
              SELECT distinct 
                  0 AS NO,
                  a.id,
                  a.i_nota,
                  to_char(a.d_nota, 'dd-mm-yyyy') as d_nota,
                  to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur,
                  a.i_supplier,
                  a.e_supplier_name,
                  (SELECT string_agg(distinct bt.i_sj_supplier, ', ') FROM tm_notabtb nb INNER JOIN tm_notabtb_item nbi ON (nb.id = nbi.id_nota) INNER JOIN tm_btb bt ON (bt.id = nbi.id_btb) WHERE nb.id = a.id GROUP BY nb.id) as i_sj_supplier,
                  a.i_pajak,
                  a.i_faktur_supplier,
                  a.v_total,
                  a.e_remark,
                  a.i_status,
                  a.id_company,
                  e_status_name,
                  d.label_color,
                  f.i_level,
  			      l.e_level_name,
                  '$i_menu' AS i_menu,
                  '$folder' AS folder,
                  '$dfrom' AS dfrom,
                  '$dto' AS dto 
              FROM
                  tm_notabtb a 
                  JOIN
                  tr_supplier b 
                  ON (a.i_supplier = b.i_supplier AND a.id_company = b.id_company)
                  JOIN
                  tm_notabtb_item c 
                  ON (a.id = c.id_nota )
                  JOIN
                  tr_status_document d 
                  ON (a.i_status = d.i_status)
                  JOIN tr_departement_cover e 
                  ON (e.i_bagian = a.i_bagian AND a.id_company = e.id_company)
                  LEFT JOIN tr_menu_approve f ON
                  (a.i_approve_urutan = f.n_urut
                  AND f.i_menu = '$i_menu')
                  LEFT JOIN public.tr_level l ON
                  (f.i_level = l.i_level)
                  WHERE
                  a.i_status <> '5'
                  AND
                  a.id_company = '$idcompany'
                  AND 
                  e.i_departement = '$this->i_departement'
                  AND 
                  e.i_level = '$this->i_level'
                  AND 
                  username = '$this->username'
                  $where
            ) as x
            order by case i_status when '2' then 1 else 2 end, d_nota asc
                ",
            false
        );

        $datatables->edit('v_total', function ($data) {
            return number_format($data['v_total'],2);
        });

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add(
            'action',
            function ($data) {
                $id      = trim($data['id']);
                $i_menu  = $data['i_menu'];
                $i_status = $data['i_status'];
                $i_level = $data['i_level'];
                $isupplier = $data['i_supplier'];
                $folder  = $data['folder'];
                $dfrom   = $data['dfrom'];
                $dto     = $data['dto'];
                $data    = '';

                if (check_role($i_menu, 2)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$isupplier/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye fa-lg text-success'></i></a>&nbsp;&nbsp;";
                }
                if (check_role($i_menu, 3)) {
                    if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg'></i></a>&nbsp;&nbsp;";
                    }
                }
                if (check_role($i_menu, 3)) {
                    if ($i_status == '11' || $i_status == '12' || $i_status == '13') {
                        $data .= "<a href=\"#\" title='Edit No Pajak' onclick='show(\"$folder/cform/editpajak/$id/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil fa-lg'></i></a>&nbsp;&nbsp;";
                    }
                }
                if (check_role($i_menu, 7) && $i_status == '2') {
                    if (($i_level == $this->session->userdata('i_level') || $this->session->userdata('i_level') == 1)) {
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box fa-lg text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }
                if (check_role($i_menu, 4) && ($i_status == '1')) {
                    $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close fa-lg text-danger'></i></a>";
                }
                return $data;
            }
        );
        $datatables->hide('id');
        $datatables->hide('i_supplier');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('id_company');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');

        return $datatables->generate();
    }

    public function awalnext()
    {
        $idcompany  = $this->session->userdata('id_company');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "
                        WITH CTE as (
                          SELECT
                             0 AS NO,
                             ROW_NUMBER() OVER (ORDER BY a.id, btb.id) AS i,
                             a.id,
                             btb.id as btb,
                             a.i_supplier,
                             b.e_supplier_name,
                             a.i_op,
                             to_char(a.d_op, 'dd-mm-yyyy') as d_op,
                             btb.i_btb,
                             to_char(btb.d_btb, 'dd-mm-yyyy') as d_btb,
                             btb.i_sj_supplier
                          FROM tm_opbb a 
                          JOIN tm_opbb_item op ON a.id = op.id_op   
                          JOIN tr_supplier b ON a.i_supplier = b.i_supplier and a.id_company = b.id_company
                          JOIN tm_btb_item be ON a.id = be.id_op
                          join tm_btb btb on (be.id_btb = btb.id)
                          WHERE
                              a.i_status = '6' and btb.i_status = '6'
                          AND op.f_op_faktur = 'f'
                          AND be.f_btb_faktur = 'f'
                          AND a.id_company = '$idcompany'
                          group by btb.id, a.id, a.i_supplier, b.e_supplier_name, a.i_op, a.d_op, btb.i_btb, btb.d_btb, btb.i_sj_supplier
                          ORDER BY a.i_supplier, a.d_op, btb.d_btb DESC
                        )

                        select no, i, id, btb, i_supplier, e_supplier_name, i_op, d_op, i_btb, d_btb,  i_sj_supplier, ( SELECT count(i) AS jml FROM CTE) AS jml
                        FROM CTE
                          ",
            false
        );

        $datatables->add(
            'action',
            function ($data) {
                $id = $data['id'];
                $jml      = $data['jml'];
                $i = $data['i'];
                $isupplier = $data['i_supplier'];
                $btb = $data['btb'];
                $data = '';
                $data .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk" . $i . "\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"id" . $i . "\" value=\"" . $id . "\" type=\"hidden\">
                <input name=\"jml\" value=\"" . $jml . "\" type=\"hidden\">
                <input name=\"btb" . $i . "\" value=\"" . $btb . "\" type=\"hidden\">
                <input name=\"isupplier" . $i . "\" value=\"" . $isupplier . "\" type=\"hidden\">";
                //$data .= "<a href=\"#\" title='Edit' onclick='callswal(\"$id\",\"$isupplier\",\"$iop\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";

                return $data;
            }
        );
        $datatables->hide('id');
        $datatables->hide('btb');
        $datatables->hide('i');
        $datatables->hide('i_supplier');
        $datatables->hide('jml');

        return $datatables->generate();
    }

    public function bacasupplier($cari)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query(
            "
                              SELECT 
                                distinct a.i_supplier,
                                c.e_supplier_name
                              FROM
                                 tm_btb a 
                                 JOIN
                                    tm_btb_item b 
                                    ON (a.id = b.id) 
                                 JOIN
                                    tr_supplier c 
                                    ON (a.i_supplier = c.i_supplier and a.id_company = c.id_company) 
                              WHERE
                                 a.f_faktur_created = 'f' 
                                 AND a.f_status_lunas = 'f'
                                 AND a.i_status = '6'
                                 AND a.id_company = '$idcompany'
                                 AND 
                                  (
                                     upper(a.i_supplier) like '%$cari%' 
                                     or upper(c.e_supplier_name) like '%$cari%'
                                  )
                              ORDER BY
                                    a.i_supplier",
            false
        );
    }

    public function getiop($isupplier, $cari)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select(
            "
                          DISTINCT
                             c.i_op, a.id_op 
                          FROM
                             tm_btb_item a 
                             JOIN
                                tm_btb b 
                                ON (a.id = b.id) 
                             JOIN
                                tm_opbb c 
                                ON (a.id_op = c.id) 
                          WHERE
                             b.i_supplier = '$isupplier' 
                             AND b.i_status = '6' 
                             AND b.f_status_lunas = 'f' 
                             AND f_faktur_created = 'f' 
                             AND b.id_company = '$idcompany'
                             AND 
                             (
                                upper(c.i_op) like '%$cari%' 
                             )
                          ORDER BY
                             i_op",
            FALSE
        );
        return $this->db->get();
    }

    public function getibtb($id, $isupplier, $iop, $cari)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select(
            " 
                          DISTINCT
                             a.i_btb,
                             a.id 
                          FROM
                             tm_btb a 
                             JOIN
                                tm_btb_item b 
                                ON (a.id = b.id_btb) 
                          WHERE
                             a.i_supplier = '$isupplier' 
                             AND a.i_status = '6' 
                             AND f_faktur_created = 'f' 
                             AND a.f_status_lunas = 'f' 
                             AND b.id_op = '$id' 
                             AND a.id_company = '$idcompany'
                             AND 
                                  (
                                     upper(a.i_btb) like '%$cari%' 
                                  )
                          ORDER BY
                             a.i_btb",
            FALSE
        );
        return $this->db->get();
    }

    public function ibtb()
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select(
            "
                          DISTINCT
                             a.i_btb, a.id 
                          FROM
                             tm_btb a 
                             JOIN 
                                tm_btb_item b 
                                ON (a.id = b.id) 
                          WHERE
                             
                             AND a.i_status = '6' 
                             AND f_faktur_created = 'f' 
                             AND a.f_status_lunas = 'f'
                             AND a.id_company = '$idcompany'
                               
                          ORDER BY
                             a.i_btb",
            FALSE
        );
        return $this->db->get();
    }

    public function getidoksup($isupplier, $iop, $ibtb)
    {
        $idcompany = $this->session->userdata('id_company');
        $where = '';
        if ($iop != 'IOP') {
            $where .= "AND b.id_op = '$iop'";
        }
        $and = '';
        if ($ibtb != 'IBTB') {
            $and .= " AND a.id = '$ibtb'";
        }

        $this->db->select(
            "
                             a.i_sj_supplier 
                          FROM
                             tm_btb a 
                             JOIN
                                tm_btb_item b 
                                ON a.id = b.id 
                          WHERE
                             a.i_supplier = '$isupplier' 
                             AND a.i_status = '6' 
                             AND a.f_status_lunas = 'f' 
                             AND f_faktur_created = 'f' 
                             AND a.id_company = '$idcompany'
                          $where $and
                          ORDER BY
                             a.i_btb",
            FALSE
        );
        return $this->db->get();
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian and a.id_company = b.id_company', 'inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query(
            "
            SELECT 
                substring(i_nota, 1, 2) AS kode 
            FROM tm_notabtb 
            WHERE i_status <> '5'
            AND id_company = '" . $this->session->userdata("id_company") . "' and i_bagian = '$ibagian'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'FP';
        }
        $query = $this->db->query(
            "
            SELECT
                max(substring(i_nota, 9, 6)) AS max
            FROM
                tm_notabtb
            WHERE to_char (d_nota, 'yymm') = '$thbl'
            AND i_status <> '5'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            AND i_bagian = '$ibagian'
            AND substring(i_nota, 1, 2) = '$kode'
        ",
            false
        );
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_notabtb');
        return $this->db->get()->row()->id + 1;
    }

    public function cek_sup($isupplier)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select('*');
        $this->db->from('tr_supplier');
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_supplier', $isupplier);
        return $this->db->get();
    }

    function get_btbitem($isupplier, $idbtb)
    {
        $idcompany = $this->session->userdata('id_company');
        /* $this->db->select(
            "    DISTINCT ON (a.id)
                             a.*,
                             b.e_supplier_name,
                             b.f_pkp,
                             b.n_diskon,
                             op.n_top,
                             b.n_supplier_toplength as sup_top 
                          FROM
                             tm_btb a 
                          JOIN
                             tm_btb_item btb
                          ON a.id = btb.id_btb
                          JOIN
                              tm_opbb op 
                          ON btb.id_op = op.id
                          JOIN
                             tr_supplier b 
                          ON a.i_supplier = b.i_supplier and a.id_company = b.id_company
                          WHERE
                             a.i_supplier = '$isupplier' 
                          AND
                             a.i_status = '6'
                          AND
                             a.f_faktur_created = 'f'
                          AND
                             btb.n_quantity_sisa > 0
                          AND 
                             a.id_company = '$idcompany'
                          ORDER BY
                             a.id",
            FALSE
        );
        return $this->db->get(); */
        return $this->db->query(
            "SELECT
                DISTINCT 
                a.i_supplier,
                b.e_supplier_name,
                b.f_pkp,
                b.n_diskon,
                op.n_top,
                b.n_supplier_toplength AS sup_top,
                to_char(max(d_btb),'dd-mm-yyyy') AS d_btb
                /*(a.id) a.*,
                b.e_supplier_name,
                b.f_pkp,
                b.n_diskon,
                op.n_top,
                b.n_supplier_toplength AS sup_top*/
            FROM
                tm_btb a
            JOIN tm_btb_item btb ON
                a.id = btb.id_btb
            JOIN tm_opbb op ON
                btb.id_op = op.id
            JOIN tr_supplier b ON
                a.i_supplier = b.i_supplier
                AND a.id_company = b.id_company
            WHERE
                a.i_supplier = '$isupplier'
                AND a.i_status = '6'
                AND a.f_faktur_created = 'f'
                AND btb.n_quantity_sisa > 0
                AND a.id_company = '$idcompany'
                AND btb.id_btb IN (" . $idbtb . ")
            GROUP BY 1,2,3,4,5,6"
        );
    }

    function get_item2($isupplier, $ibtb)
    {
        $idcompany = $this->session->userdata('id_company');
        // $where = '';
        // if($iop != 'IOP'){
        //   $where .= "AND c.id_op = '$iop'";
        // }
        // $and='';
        // if($ibtb != 'IBTB'){
        //   $and .= "AND c.id_btb = '$ibtb'";
        // }
        // if($isj != 'ISJ'){
        //   $where .= "and a.i_sj_supplier = '$isj'";
        // }

        $and = "AND c.id_btb IN (" . $ibtb . ")";
        $this->db->select(
            "    
                                a.id,
                                a.i_btb,
                                a.d_btb,
                                a.i_supplier,
                                a.i_sj_supplier,
                                c.id_btb,
                                c.id_op,
                                ca.i_op,
                                c.i_material,
                                d.e_material_name,
                                c.n_quantity,
                                e.v_price,
                                d.i_satuan_code,
                                f.e_satuan_name,
                                b.f_pkp,
                                c.n_quantity_eks,
                                c.i_satuan_code_eks,
                                g.e_satuan_name as satuaneks,
                                ca.i_type_pajak as f_ppn,
                                c.id_pp ,
                                c.n_toleransi,
                                COALESCE (e.n_ppn,0) AS n_ppn
                            FROM
                                tm_btb a 
                            JOIN
                                tr_supplier b 
                                ON (a.i_supplier = b.i_supplier AND a.id_company = b.id_company) 
                            JOIN
                                tm_btb_item c 
                                ON (a.id = c.id_btb)                             
                            JOIN
                                tr_material d 
                                ON (c.i_material = d.i_material AND a.id_company = d.id_company) 
                            LEFT JOIN
                                tm_opbb_item e 
                                ON ((c.id_op = e.id_op AND c.i_material = e.i_material AND a.id_company = e.id_company) AND (c.id_pp = e.id_pp or c.id_pp isnull))
                            JOIN
                                tm_opbb ca 
                                ON (e.id_op = ca.id and ca.i_status = '6' and i_status_harga = '6') 
                            JOIN
                                tr_satuan f 
                                ON (c.i_satuan_code = f.i_satuan_code AND c.id_company = f.id_company) 
                            JOIN
                                tr_satuan g 
                                ON (c.i_satuan_code_eks = g.i_satuan_code AND a.id_company = g.id_company)                              
                            WHERE
                                a.i_supplier = '$isupplier' 
                            AND
                                a.i_status = '6'
                            AND 
                                c.f_btb_faktur = 'f'
                            AND 
                                a.id_company = '$idcompany'
                            $and
                            ORDER BY d.e_material_name ASC",
            FALSE
        );
        return $this->db->get();
    }

    public function cek_kode($kode)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select('i_nota');
        $this->db->from('tm_notabtb');
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_nota', $kode);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    function cek_data($id)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select("a.*, b.e_supplier_name, b.f_pkp, b.n_supplier_toplength as sup_top, b.n_diskon");
        $this->db->from("tm_notabtb a");
        $this->db->join("tr_supplier b", "a.i_supplier = b.i_supplier and a.id_company = b.id_company");
        $this->db->where("a.id_company", $idcompany);
        $this->db->where("a.id", $id);
        return $this->db->get();
    }

    function get_date($id)
    {
        return $this->db->query(
            "SELECT to_char(max(d_btb),'dd-mm-yyyy') as d_btb FROM tm_btb WHERE id IN (SELECT id_btb FROM tm_notabtb_item WHERE id_nota = '$id' )"
        );
    }

    function get_item($inota, $isupplier)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select(
            "(select a.i_sj from duta_prod.tm_notabtb_item a, duta_prod.tm_notabtb b where a.i_nota=b.i_nota and b.i_nota='$inota' and b.f_nota_cancel='f' and a.i_sj=x.i_sj) as sjnota,
                x.*
            from 
            (select a.*, b.e_supplier_name, b.i_jenis_pembelian, c.i_material, d.e_material_name, c.n_qty, c.v_unit_price, d.i_satuan_code, e.e_satuan, f.v_price 
            from duta_prod.tr_supplier b, duta_prod.tm_sj_pembelian a 
            join duta_prod.tm_sj_pembelian_detail c on c.i_sj=a.i_sj
            join duta_prod.tr_material d on c.i_material=d.i_material
            join duta_prod.tr_satuan e on e.i_satuan_code = d.i_satuan_code
            join duta_prod.tr_supplier_materialprice f on d.i_material = f.i_material
            where a.i_supplier=b.i_supplier and a.f_sj_cancel='f' and a.i_supplier='$isupplier'
            and a.i_makloon_type='0'
            order by a.i_sj ) as x",
            false
        );
        $data = $this->db->get();
        return $data;
    }

    function get_itemm($inota, $isupplier)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->select("a.*,
                    b.*,
                    b.v_pembulatan as v_pembulatan_item,
                    c.f_pkp,
                    e.e_material_name,
                    e.i_satuan_code,
                    g.e_satuan_name,
                    f.n_quantity_eks,
                    f.i_satuan_code_eks,
                    h.e_satuan_name as satuaneks,
                    d.i_btb,
                    ca.i_type_pajak as f_ppn   
                from
                    tm_notabtb a 
                    join
                        tm_notabtb_item b 
                        on a.id = b.id_nota 
                    join
                        tr_supplier c 
                        on a.i_supplier = c.i_supplier and a.id_company = c.id_company
                    join
                        tm_btb d 
                        on b.id_btb = d.id 
                    left join
                        tm_btb_item f 
                        on ((b.id_btb = f.id_btb and f.i_material=b.i_material and a.id_company = f.id_company) and (b.id_pp = f.id_pp or b.id_pp isnull))
                    left join
                        tr_material e 
                        on f.i_material = e.i_material and a.id_company = e.id_company
                    join
                        tr_satuan g 
                        on g.i_satuan_code = f.i_satuan_code and a.id_company = g.id_company
                    join
                        tr_satuan h 
                        on h.i_satuan_code = f.i_satuan_code_eks and a.id_company = h.id_company
                    join
                        tm_opbb ca 
                        on (f.id_op = ca.id and f.id_company = ca.id_company) 
                where
                    a.id = '$inota' 
                    and a.i_supplier = '$isupplier'
                    and a.id_company = '$idcompany'
                ORDER BY e.e_material_name ASC",
            false
        );
        return $this->db->get();
    }

    public function insert(
        $id,
        $inota,
        $datenota,
        $ipajak,
        $datepajak,
        $isupplier,
        $datereceivefaktur,
        $vdiskonx,
        $vtotaldpp,
        $vtotalppn,
        $vtotal,
        $eremark,
        $datefsupp,
        $vtotaldis,
        $vtotalbruto,
        $vtotalnet,
        $ibagian,
        $ntop,
        $isuppliername,
        $vsisa,
        $datejatuhtempo,
        $ifaktur,
        $vdiskontotal = 0,
        $vdiskonsup = 0,
        $vdiskon = 0,
        $v_pembulatan = 0
    ) {
        // $dentry = date("Y-m-d H:i:s");
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'                => $id,
            'i_nota'            => $inota,
            'd_nota'            => $datenota,
            'i_bagian'          => $ibagian,
            'i_pajak'           => $ipajak,
            'd_pajak'           => $datepajak,
            'i_supplier'        => $isupplier,
            'e_supplier_name'   => $isuppliername,
            'd_terima_faktur'   => $datereceivefaktur,
            //'i_payment_type'    => $ipaymenttype, 
            'v_dpp'             => $vtotaldpp,
            'v_ppn'             => $vtotalppn,
            'v_total'           => $vtotalnet,
            'v_sisa'            => $vtotalnet,
            'e_remark'          => $eremark,
            'i_status'          => '1',
            'd_faktur_supplier' => $datefsupp,
            'v_sub_diskon'      => $vdiskon,
            'v_diskon_lain'     => $vdiskonsup,
            'v_total_diskon'    => $vdiskontotal,
            'v_total_bruto'     => $vtotalbruto,
            'v_total_net'       => $vtotalnet,
            'v_pembulatan'      => $v_pembulatan,
            'n_top'             => $ntop,
            'd_jatuh_tempo'     => $datejatuhtempo,
            'id_company'        => $idcompany,
            'd_entry'           => current_datetime(),
            'i_faktur_supplier' => $ifaktur,
        );
        //v_diskon_lain numeric(10,0),

        $this->db->insert('tm_notabtb', $data);
    }

    public function send($kode)
    {
        $data = array(
            'i_status' => '11'
        );

        $this->db->where('id', $kode);
        $this->db->update('tm_notabtb', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '9' || $istatus == '5') {
            $query = $this->db->query("
                WITH a AS (SELECT id_op, id_btb, i_material FROM tm_notabtb_item WHERE id_nota = '$id')
                UPDATE tm_opbb_item SET f_op_faktur = 'f' WHERE id_op IN (SELECT id_op FROM a) AND i_material IN (SELECT i_material FROM a);
                WITH a AS (SELECT id_op, id_btb, i_material FROM tm_notabtb_item WHERE id_nota = '$id')
                UPDATE tm_btb_item SET f_btb_faktur = 'f' WHERE id_btb IN (SELECT id_btb FROM a) AND i_material IN (SELECT i_material FROM a);
                update tm_notabtb set i_status = '$istatus' where id = $id;
            ");
        } else {
            if ($istatus == '3' || $istatus == '11') {
                $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tm_notabtb a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();
                if ($istatus == '3') {
                    if ($awal->i_approve_urutan - 1 == 0) {
                        $data = array(
                            'i_status'  => $istatus,
                        );
                    } else {
                        $data = array(
                            'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                        );
                    }
                    $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
                } else if ($istatus == '11') {
                    if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                        $query = $this->db->query("SELECT
                            DISTINCT jenis_pembelian, a.id
                        FROM
                            tm_opbb a
                        INNER JOIN tm_notabtb_item b ON
                            (b.id_op = a.id
                            AND a.id_company = b.id_company)
                        WHERE
                            b.id_nota = '$id'
                    ", FALSE)->row();
                        $jenis = $query->jenis_pembelian;
                        $id_op = $query->id;
                        if ($jenis == 'cash') {
                            $data = array(
                                'i_status'  => $istatus,
                                'i_status'       => '12',
                                'v_sisa'         => '0',
                                'f_status_lunas' => 't',
                                'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                                'e_approve' => $this->username,
                                'd_approve' => date('Y-m-d'),
                            );
                        } else {
                            $data = array(
                                'i_status'  => $istatus,
                                'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                                'e_approve' => $this->username,
                                'd_approve' => date('Y-m-d'),
                            );
                        }
                        //$this->db->query("UPDATE tm_opbb SET f_op_faktur = 't' WHERE id = '$id_op'");
                    } else {
                        $data = array(
                            'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        );
                    }
                    $now = date('Y-m-d');
                    $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_notabtb');", FALSE);
                }
            } else {
                $data = array(
                    'i_status'  => $istatus,
                );
            }
            $this->db->where('id', $id);
            $this->db->update('tm_notabtb', $data);
        }
    }

    public function changestatus_20211203($id, $istatus)
    {
        if ($istatus == '5') {
            $this->db->query(
                "UPDATE 
                    tm_btb_item 
                SET 
                    f_btb_faktur = 'f' 
                WHERE 
                    id_btb in (select id_btb from tm_notabtb_item where id_nota= '$id')",
                false
            );
        }

        if ($istatus == '11') {
            $jenis = $this->db->query("
                SELECT
                    DISTINCT jenis_pembelian
                FROM
                    tm_opbb a
                INNER JOIN tm_notabtb_item b ON
                    (b.id_op = a.id
                    AND a.id_company = b.id_company)
                WHERE
                    b.id_nota = '$id'
            ", FALSE)->row()->jenis_pembelian;
            if ($jenis == 'cash') {
                $data = array(
                    'i_status'       => '12',
                    'v_sisa'         => '0',
                    'f_status_lunas' => 't'
                );
            } else {
                $data = array(
                    'i_status' => $istatus
                );
            }
        } else {
            $data = array(
                'i_status' => $istatus
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_notabtb', $data);
    }

    public function updatestatus($idbtb, $idiop, $imaterial, $idpp)
    {
        $data = array(
            'f_btb_faktur' => 't'
        );
        $this->db->where('id_btb', $idbtb);
        $this->db->where('id_op', $idiop);
        $this->db->where('id_pp', $idpp);
        $this->db->where('i_material', $imaterial);
        $this->db->update('tm_btb_item', $data);
    }

    public function updatestatusop($idiop, $imaterial)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'f_op_faktur' => 't',
        );
        $this->db->where('id_op', $idiop);
        $this->db->where('i_material', $imaterial);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_opbb_item', $data);
    }

    public function insertdetail($id, $isj, $dsj, $idbtb, $idiop, $imaterial, $nquantity, $vprice, $vprice_manual, $vdpp, $vppn, $vtotalsem, $itipe, $fpkp, $idpp, $f_toleransi, $nquantitybtb, $toleransi, $v_pembulatan_item)
    {
        //$dentry = date("Y-m-d");
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_nota' => $id,
            // 'i_sj'          => $isj, 
            // 'd_sj'          => $dsj,
            'id_btb'        => $idbtb,
            'id_op'         => $idiop,
            'i_material'    => $imaterial,
            'n_quantity'    => $nquantity,
            'n_sisa'        => $nquantity,
            //'i_satuan_code' => $isatuan,
            'v_price'       => $vprice,
            'v_price_manual' => $vprice_manual,
            'v_dpp'         => $vdpp,
            'v_ppn'         => $vppn,
            'v_total'       => $vtotalsem,
            'v_pembulatan'  => $v_pembulatan_item,
            'i_type_pajak'  => $itipe,
            'f_pkp'         => $fpkp,
            'id_company'    => $idcompany,
            'id_pp'         => $idpp,
            'd_entry'       => current_datetime(),
            'f_toleransi'   => $f_toleransi,
            'n_toleransi'   => $toleransi,
            'n_quantity_btb' => $nquantitybtb,
        );
        // i_type_pajak character(1),
        // f_pkp boolean,
        // n_diskon numeric(10,0),
        // v_total_diskon numeric(10,0),
        // v_total_bruto numeric(10,2),
        // v_total_net numeric(10,2),
        // e_remark text,
        $this->db->insert('tm_notabtb_item', $data);
    }

    public function update($id, $inota, $datenota, $ipajak, $isupplier, $vtotal, $vsisa, $datereceivefaktur, $datepajak, $vdiskonx, $eremark, $datejatuhtempo, $ifaktur, $vtotaldis, $vdiskontotal, $vdiskonsup, $vdiskon, $vtotaldpp, $vtotalppn, $vtotalbruto, $vtotalnet, $v_pembulatan)
    {
        //$dupdate = date("Y-m-d H:i:s");
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_nota'            => $inota,
            'd_nota'            => $datenota,
            'i_pajak'           => $ipajak,
            'i_supplier'        => $isupplier,
            //'f_pkp'           => $fpkp,
            //'i_payment_type'  => $ipaymenttype, 
            'v_dpp'             => $vtotaldpp,
            'v_ppn'             => $vtotalppn,
            'v_total'           => $vtotalnet,
            'v_sisa'            => $vtotalnet,
            'd_pajak'           => $datepajak,
            'd_terima_faktur'   => $datereceivefaktur,
            'd_jatuh_tempo'     => $datejatuhtempo,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime(),
            'i_faktur_supplier' => $ifaktur,
            /* 'v_sub_diskon'      => $vdiskon,
            'v_diskon_lain'     => $vdiskonsup,
            'v_total_diskon'    => $vdiskontotal, */
            'v_sub_diskon'      => $vdiskon,
            'v_diskon_lain'     => $vdiskonsup,
            'v_total_diskon'    => $vdiskontotal,
            'v_total_bruto'     => $vtotalbruto,
            'v_total_net'       => $vtotalnet,
            'v_pembulatan'      => $v_pembulatan,
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_notabtb', $data);
    }

    public function update1($inota, $isj, $vtotsj, $inoitem)
    {
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_nota'    => $inota,
            'i_sj'      => $isj,
            'v_tot_sj'  => $vtotsj,
            'i_no_item' => $inoitem,
            'd_update'  => $dupdate,
        );
        $this->db->where('i_nota', $inota);
        $this->db->update('tm_notabtb_item', $data);
    }

    public function sendd($inota)
    {
        $data = array(
            'e_status_dokumen' => 'HP02'
        );

        $this->db->where('i_nota', $inota);
        $this->db->update('tm_notabtb', $data);
    }

    function deletenotasj($inota)
    {
        //update f_faktur_created sj
        $this->db->where('i_nota', $inota);
        $query = $this->db->get('tm_notabtb_item');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $isj = trim($row->i_sj);
                //var_dump($isj);
                $this->db->set('f_faktur_created', 'f');
                $this->db->where('i_sj', $isj);
                $this->db->update('tm_sj_pembelian');
            }
        }
        //update tm_notabtb
        $qdelete = $this->db->where('i_nota', $inota)->delete("tm_notabtb_item");
        // $qupdate = $this->db->update('tm_notabtb');
        return $qdelete;
    }

    public function cancel($inota)
    {
        $this->db->set(
            array(
                'e_status_dokumen' => 'HP04',
                'f_nota_cancel' => 't'
            )
        );
        $this->db->where('i_nota', $inota);
        return $this->db->update('tm_notabtb');
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function get_ppn_op($idbtb)
    {
        return $this->db->query("SELECT max(n_ppn) AS ppn_op FROM tm_opbb_item WHERE id_op IN (SELECT id_op FROM tm_btb_item WHERE id_btb IN ($idbtb));");
    }

    public function get_ppn_op_edit($idnota)
    {
        return $this->db->query("SELECT max(n_ppn) AS ppn_op FROM tm_opbb_item WHERE id_op IN (SELECT id_op FROM tm_notabtb_item WHERE id_nota = '$idnota');");
    }

    public function update_pajak($id, $ipajak, $dpajak)
    {
        $data = array(
            'i_pajak'           => $ipajak,
            'd_pajak'           => $dpajak,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_notabtb', $data);
    }
}
/* End of file Mmaster.php */