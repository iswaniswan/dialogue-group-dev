<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($i_menu, $folder, $dfrom, $dto)
    {
        $idcompany = $this->session->userdata('id_company');

        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_op BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "
                              SELECT DISTINCT ON (a.i_op)
                                 0 as nomor,
                                 a.id,  
                                 a.i_op,
                                 to_char(a.d_op, 'dd-mm-yyyy') as d_op, 
                                 g.i_pp,
                                 b.id_pp,
                                 c.e_bagian_name,
                                 d.e_supplier_name,
                                 upper(a.jenis_pembelian) AS jenis_pembelian,
                                 a.e_remark || '+' || e.e_status_op as keterangan,
                                 a.i_status,
                                 f.e_status_name,
                                 a.f_op_close,
                                 a.f_op_faktur,
                                 b.f_btb_complete,
                                 a.id_company,
                                 f.label_color as label,
                                 '$i_menu' as i_menu,
                                 '$folder' as folder,
                                 '$dfrom' as dfrom,
                                 '$dto' as dto
                              FROM 
                                   tm_opbb a
                                   LEFT JOIN tm_opbb_item b
                                 ON (a.id = b.id_op)
                                 LEFT JOIN tm_pp g
                                 ON (b.id_pp = g.id and a.id_company = g.id_company) 
                                   LEFT JOIN tr_bagian c
                                   ON (g.i_bagian = c.i_bagian and a.id_company = c.id_company)
                                   LEFT JOIN tr_supplier d
                                   ON (a.i_supplier = d.i_supplier and a.id_company = d.id_company)
                                   LEFT JOIN tr_status_op e
                                   ON (a.i_status_op = e.i_status_op)
                                   LEFT JOIN tr_status_document f
                                 ON (a.i_status = f.i_status)
                              WHERE 
                                 a.i_status != '5'
                              AND
                                 a.id_company = '$idcompany'
                                   $where
                              ORDER BY
                                   a.i_op DESC
                          ",
            FALSE
        );

        $datatables->edit(
            'e_status_name',
            function ($data) {
                $f_op_close = trim($data['f_op_close']);
                $f_complete = trim($data['f_btb_complete']);
                $e_status   = trim($data['e_status_name']);

                if ($f_op_close == 't') {
                    return '<span class="label label-success label-rouded">Final</span>';
                } else if ($f_complete == 't') {
                    return '<span class="label label-success label-rouded">Final Approve</span>';
                } else {
                    return '<span class="label label-' . $data['label'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
                    return $e_status;
                }
            }
        );

        $datatables->add(
            'action',
            function ($data) {
                $iop         = $data['i_op'];
                $i_menu      = $data['i_menu'];
                $idop        = $data['id'];
                $idpp        = $data['id_pp'];
                $f_op_faktur = $data['f_op_faktur'];
                $folder      = $data['folder'];
                $f_op_faktur = $data['f_op_faktur'];
                $i_status    = $data['i_status'];
                $dfrom       = $data['dfrom'];
                $dto         = $data['dto'];
                $jenis       = $data['jenis_pembelian'];
                $data        = '';

                if (check_role($i_menu, 2)) {
                    $data .= "<a href=\"#\" title='Lihat Detail' onclick='show(\"$folder/cform/view/$iop/$idpp/$idop/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
                }

                if ($jenis=='CREDIT') {
                    if (check_role($i_menu, 3) && ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') && $f_op_faktur != 't') {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$iop/$idpp/$idop/$dfrom/$dto/$jenis\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
                    }
                    if (check_role($i_menu, 5) && $i_status == '6') {
                        $data .= "<a href=\"#\" title='Print Harga' onclick='printx(\"$iop\",\"$idpp\",\"$idop\",\"#main\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;";
                        $data .= "<a href=\"#\" title='Print Non Harga' onclick='printnonharga(\"$iop\",\"$idpp\",\"$idop\",\"#main\"); return false;'><i class='ti-printer text-success'></i></a>&nbsp;&nbsp;";
                    }
                    if (check_role($i_menu, 7) && ($i_status == '2')) {
                        $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$iop/$idpp/$idop/$dfrom/$dto/$jenis\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }else{
                    if (check_role($i_menu, 3) && ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') && $f_op_faktur != 't') {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$iop/$idpp/$idop/$dfrom/$dto/$jenis\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
                    }elseif (check_role($i_menu, 3) && ($i_status == '14' || $i_status == '8') && $f_op_faktur != 't') {
                        $data .= "<a href=\"#\" title='Edit Harga' onclick='show(\"$folder/cform/editharga/$iop/$idpp/$idop/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil text-success'></i></a>&nbsp;&nbsp;";
                    }
                    if (check_role($i_menu, 5) && ($i_status == '6' || $i_status == '14')) {
                        $data .= "<a href=\"#\" title='Print Harga' onclick='printx(\"$iop\",\"$idpp\",\"$idop\",\"#main\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;";
                        $data .= "<a href=\"#\" title='Print Non Harga' onclick='printnonharga(\"$iop\",\"$idpp\",\"$idop\",\"#main\"); return false;'><i class='ti-printer text-success'></i></a>&nbsp;&nbsp;";
                    }
                    if (check_role($i_menu, 7) && ($i_status == '2')) {
                        $data .= "<a href=\"#\" title='Approve 1' onclick='show(\"$folder/cform/approval/$iop/$idpp/$idop/$dfrom/$dto/$jenis\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    }elseif (check_role($i_menu, 7) && ($i_status == '8')) {
                        $data .= "<a href=\"#\" title='Approve 2' onclick='show(\"$folder/cform/approvalharga/$iop/$idpp/$idop/$dfrom/$dto/$jenis\",\"#main\"); return false;'><i class='ti-check text-success'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }

                if (check_role($i_menu, 4) && ($i_status == '1')) {
                    $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$idop\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
                }

                return $data;
            }
        );
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('f_op_faktur');
        $datatables->hide('f_btb_complete');
        $datatables->hide('f_op_close');
        $datatables->hide('label');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_status');
        $datatables->hide('id');
        $datatables->hide('id_pp');
        $datatables->hide('id_company');

        return $datatables->generate();
    }

    public function data_pp($folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_pp BETWEEN '$dfrom' AND '$dto' AND a.i_status='6' AND c.n_sisa > 0";
        } else {
            $where = "AND a.i_status = '6' AND c.n_sisa > 0";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "
                          WITH CTE AS (
                            SELECT
                                0 as nomor,
                                ROW_NUMBER() OVER (ORDER BY c.id_pp, d.id) AS i,
                                a.i_bagian,
                                c.id as id_pp_item,
                                b.e_bagian_name,
                                a.i_pp,
                                to_char(a.d_pp, 'dd-mm-yyyy') as d_pp,
                                a.i_status,
                                c.id_pp,
                                d.id as id_material,
                                d.i_material || ' - ' || d.e_material_name  as nama,
                                c.n_sisa,
                               '$folder' as folder
                             FROM 
                             tm_pp a
                             LEFT JOIN tr_bagian b ON (a.i_bagian = b.i_bagian and a.id_company = b.id_company)
                             LEFT JOIN tm_pp_item c ON (a.id = c.id_pp)
                             INNER JOIN tr_material d on (c.i_material = d.i_material and d.id_company = c.id_company)
                             WHERE a.id_company = '" . $this->session->userdata('id_company') . "' $where
                             ORDER BY
                             a.i_pp,
                             a.d_pp,
                             d.e_material_name   
                          )
                          
                          select nomor, i, i_bagian, e_bagian_name, i_pp, d_pp, i_status, id_pp, id_material, nama,n_sisa, id_pp_item, folder, (select count(i) as jml from CTE) As jml from CTE                   
                        ",
            FALSE
        );

        $datatables->add(
            'action',
            function ($data) {
                $i = $data['i'];
                $jml = $data['jml'];
                $ipp         = $data['i_pp'];
                $ibagian     = $data['i_bagian'];
                $folder      = $data['folder'];
                $i_status    = $data['i_status'];
                $id_material       = $data['id_material'];
                $id_pp       = $data['id_pp'];
                $id_pp_item  = $data['id_pp_item'];
                $data = '';

                $data  .= "
                <label class=\"custom-control custom-checkbox\"> 
                <input type=\"checkbox\" id=\"chk" . $i . "\" name=\"chk" . $i . "\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input id=\"id_pp" . $i . "\" name=\"id_pp" . $i . "\" value=\"" . $id_pp . "\" type=\"hidden\">
                <input id=\"id_pp_item" . $i . "\" name=\"id_pp_item" . $i . "\" value=\"" . $id_pp_item . "\" type=\"hidden\">
                <input id=\"jml\" name=\"jml\" value=\"" . $jml . "\" type=\"hidden\">
                <input id=\"id_material" . $i . "\" name=\"id_material" . $i . "\" value=\"" . $id_material . "\" type=\"hidden\">";
                return $data;
            }
        );
        $datatables->hide('folder');
        $datatables->hide('i_bagian');
        $datatables->hide('i_status');
        $datatables->hide('id_pp');
        $datatables->hide('id_pp_item');
        $datatables->hide('id_material');
        $datatables->hide('jml');
        $datatables->hide('i');
        return $datatables->generate();
    }

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode)
    {
        $this->db->select('i_op');
        $this->db->from('tm_opbb');
        $this->db->where('i_op', $kode);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun)
    {
        $cek = $this->db->query(
            "
            SELECT 
                substring(i_op, 1, 2) AS kode 
            FROM tm_opbb 
            WHERE i_status <> '5'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC"
        );
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'OP';
        }
        $query = $this->db->query(
            "
            SELECT
                max(substring(i_op, 9, 6)) AS max
            FROM
                tm_opbb
            WHERE to_char (d_op, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            AND substring(i_op, 1, 2) = '$kode'
            AND substring(i_op, 4, 2) = substring('$thbl',1,2)
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

    /*public function gudang($cari, $idepart)
    {
        return $this->db->query(
            "SELECT * FROM tr_bagian WHERE (UPPER(i_bagian) LIKE '%$cari%' OR UPPER(e_bagian_name) LIKE '%$cari%') AND id_company = '" . $this->session->userdata("id_company") . "' ORDER BY e_bagian_name",
            FALSE
        );
    }*/

    /*public function getpp($dfrom1, $dto1, $igudang)
    {
        return $this->db->query(
            "SELECT i_pp FROM tm_pp WHERE d_pp >= '$dfrom1' AND d_pp <=  '$dto1' AND i_status = '6'  AND i_bagian = '$igudang' AND id_company = '" . $this->session->userdata("id_company") . "'",
            FALSE
        );
    }*/

    public function getsup($idmaterial)
    {
        $id_material = implode(",", $idmaterial);
        return $this->db->query(
            "
                              SELECT DISTINCT c.i_supplier, d.e_supplier_name, jenis_pembelian
                              FROM tr_supplier_materialprice c
                              LEFT JOIN tr_supplier d ON (c.i_supplier = d.i_supplier and c.id_company = d.id_company) 
                              INNER JOIN tr_material e on (c.i_material = e.i_material and e.id_company = c.id_company) 
                              WHERE
                                 c.id_company = '" . $this->session->userdata("id_company") . "'
                                 AND e.id IN ($id_material)
                              UNION ALL
                              SELECT
                                  i_supplier,
                                  e_supplier_name,
                                  jenis_pembelian
                              FROM
                                  tr_supplier
                              WHERE
                                  id_company = '" . $this->session->userdata("id_company") . "'
                                  AND jenis_pembelian = 'cash'
                              ORDER BY
                                  1,
                                  2
                              ",
            FALSE
        );
    }

    /*public function get_pp_item($dfrom, $dto, $igudang, $isupplier, $ipp)
    {
        if (!isset($dfrom)) $dfrom = date('Y-m-d');
        if (!isset($dto)) $dto = date('Y-m-d');

        return $this->db->query(
            "
                              SELECT
                                 x.*,
                                 (
                                    SELECT
                                       SUM(a.n_quantity) 
                                    FROM
                                       tm_opbb_item a,
                                       tm_opbb b 
                                    WHERE
                                       a.id_op = b.id 
                                       AND a.i_material = x.i_material 
                                       AND b.i_status != '9' 
                                 ) AS qty_op 
                              FROM
                                 (
                                    SELECT
                                       a.i_pp,
                                       a.i_bagian,
                                       f.e_bagian_name,
                                       b.i_material,
                                       d.e_material_name,
                                       b.i_satuan_code,
                                       e.e_satuan_name,
                                       b.n_quantity,
                                       g.i_supplier,
                                       b.n_sisa 
                                    FROM
                                       tm_pp a 
                                       LEFT JOIN
                                          tm_pp_item b 
                                          ON (a.i_pp = b.i_pp)
                                       LEFT JOIN
                                          tr_material d 
                                          ON (b.i_material = d.i_material and a.id_company = d.id_company)
                                       LEFT JOIN
                                          tr_satuan e 
                                          ON (b.i_satuan_code = e.i_satuan_code and a.id_company = e.id_company)
                                       LEFT JOIN
                                          tr_bagian f 
                                          ON (a.i_bagian = f.i_bagian and a.id_company = a.id_company = f.id_company)
                                       LEFT JOIN
                                          tr_supplier_materialprice g 
                                          ON (b.i_material = g.i_material AND g.i_supplier = '$isupplier' AND a.id_company = g.id_company)
                                    WHERE
                                       a.i_status != '9' 
                                       AND a.d_pp >= '$dfrom' 
                                       AND a.d_pp <= '$dto' 
                                       AND a.i_bagian = '$igudang' 
                                       AND a.i_pp = '$ipp' 
                                       AND a.id_company = '" . $this->session->userdata("id_company") . "'
                                       AND b.n_sisa NOT IN ('0.00')
                                    ORDER BY
                                       d.e_material_name,
                                       b.i_material,
                                       a.i_pp 
                                 ) AS x 
                              ORDER BY
                                 x.i_pp,
                                 x.e_bagian_name,
                                 x.e_material_name
                              ",
            false
        );
    }*/

    public function cek_data($idop)
    {
        return $this->db->query(
            "
                              SELECT DISTINCT a.id, a.i_op, a.i_status, to_char(a.d_op,'dd-mm-yyyy') AS d_op, a.i_bagian, 
                              f.e_bagian_name as bagian_pembuat, to_char(a.d_deliv,'dd-mm-yyyy') AS d_deliv, 
                              a.i_status_op, d.e_status_op,  array_agg(distinct(e.e_bagian_name)) AS e_bagian_name, 
                              a.i_supplier, a.e_supplier_name, g.e_supplier_address, a.e_remark, a.n_top, a.n_diskon, 
                              a.i_type_pajak, a.f_pkp, a.jenis_pembelian
                              FROM tm_opbb a 
                              LEFT JOIN tm_opbb_item b ON (a.id = b.id_op) 
                              LEFT JOIN tm_pp c ON (b.id_pp = c.id and a.id_company = c.id_company) 
                              LEFT JOIN tr_status_op d ON (a.i_status_op = d.i_status_op) 
                              LEFT JOIN tr_bagian e ON (c.i_bagian = e.i_bagian and a.id_company = e.id_company) 
                              LEFT JOIN tr_bagian f ON (a.i_bagian = f.i_bagian and a.id_company = f.id_company) 
                              LEFT JOIN tr_supplier g ON (g.i_supplier = a.i_supplier and a.id_company = g.id_company) 
                              WHERE b.id_op = '$idop' AND a.id_company = '" . $this->session->userdata("id_company") . "'
                              group by 1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18
                                
                              ",
            FALSE
        );
    }

    /*function bacadetail($isupplier)
    {
        $this->db->select(
            " * from duta_prod.tm_opbb_item a
                      join duta_prod.tm_opbb b on a.i_op=b.i_op
                      where i_supplier = '$isupplier' and f_op_cancel = 'f'",
            false
        );
        return $this->db->get();
    }

    public function cek_det($ipp, $imaterial, $isupplier)
    {
        $this->db->select(
            "x.*, 
                      (
                         select
                            sum(a.n_quantity) as op 
                         from
                            tm_opbb_item a,
                            tm_opbb b 
                         where
                            a.i_op = b.i_op 
                            and a.i_material = x.i_material 
                            and a.i_satuan = x.i_satuan 
                            and a.i_pp = x.i_pp 
                            and b.f_op_cancel = 'f' 
                      )
                      as op 
                      from
                         (
                            SELECT
                               a.i_pp,
                               a.i_material,
                               c.e_material_name,
                               a.i_satuan,
                               d.e_satuan,
                               a.n_quantity,
                               b.v_price,
                               a.v_price as hrgpp,
                               f.i_kode_master,
                               g.e_nama_master 
                            FROM
                               tm_pp_item a 
                               LEFT JOIN
                                  tr_supplier_materialprice b 
                                  ON a.i_material = b.i_material 
                                  and b.i_supplier = '$isupplier' 
                               JOIN
                                  tr_material c 
                                  ON a.i_material = c.i_material 
                               JOIN
                                  tr_satuan d 
                                  ON a.i_satuan = d.i_satuan 
                               JOIN
                                  tm_pp f 
                                  ON a.i_pp = f.i_pp 
                               JOIN
                                  tr_master_gudang g 
                                  ON f.i_kode_master = g.i_kode_master 
                            WHERE
                               a.i_material = '$imaterial' 
                               AND a.i_pp = '$ipp' 
                               AND f.id_company = '" . $this->session->userdata("id_company") . "'
                            GROUP BY
                               a.i_pp,
                               a.i_material,
                               c.e_material_name,
                               a.i_satuan,
                               d.e_satuan,
                               a.n_quantity,
                               b.v_price,
                               a.v_price,
                               f.i_kode_master,
                               g.e_nama_master,
                               b.i_price_no 
                            ORDER BY
                               b.i_price_no DESC 
                         )
                         as x",
            false
        );
//and a.i_satuan=b.i_satuan
        return $this->db->get();
    }*/

    public function get_item($idpp, $idop, $idcompany)
    {
        return $this->db->query(
            "
                              SELECT
                              a.id,
                              a.id_op,
                              a.id_pp,
                              a.i_material,
                              a.n_quantity,
                              a.v_price,
                              a.e_remark,
                              a.f_btb_complete,
                              a.f_op_faktur, 
                              b.*,
                              a.e_remark AS remark,
                              d.i_satuan_code,
                              d.i_pp,
                              d.n_sisa,
                              e.e_material_name,
                              f.e_satuan_name,
                              g.e_status_op,
                              d.id as id_pp_item,
                              to_char(c.d_pp, 'dd-mm-yyyy') as d_pp,
                              h.e_bagian_name
                              FROM 
                              tm_opbb_item a
                              LEFT JOIN 
                              tm_opbb b ON (a.id_op = b.id)
                              LEFT JOIN 
                              tm_pp c ON (a.id_pp = c.id and a.id_company = c.id_company)
                              LEFT JOIN 
                              tm_pp_item d ON (a.id_pp = d.id_pp and a.i_material = d.i_material and a.id_company = d.id_company)
                              LEFT JOIN 
                              tr_material e ON (a.i_material = e.i_material and a.id_company = e.id_company)
                              LEFT JOIN
                              tr_satuan f ON (d.i_satuan_code = f.i_satuan_code and a.id_company = f.id_company)
                              LEFT JOIN
                              tr_status_op g ON (b.i_status_op = g.i_status_op)
                              LEFT JOIN
                               tr_bagian h ON (c.i_bagian = h.i_bagian and c.id_company = h.id_company)
                              WHERE
                               a.id_op = '$idop' AND a.id_company = '$idcompany'
                              ",
            FALSE
        );
    }

    /*public function getPPitem($dfrom, $dto, $isupplier, $ipp)
    {
        //header("Content-Type: application/json", true);   
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $isupplier  = $this->input->post('isupplier');
        $igudang    = $this->input->post('igudang');
        $ipp        = $this->input->post('ipp');

        if (isset($dfrom)) {
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dfrom1 = $year . '-' . $month . '-' . $day;
        }
        if (isset($dto)) {
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dto1 = $year . '-' . $month . '-' . $day;
        }
        if (!isset($dfrom)) $dfrom = date('Y-m-d');
        if (!isset($dto)) $dto = date('Y-m-d');
        $this->db->select(
            "x.*,
                               (
                                  select
                                     sum(a.n_quantity) 
                                  from
                                     tm_opbb_item a,
                                     tm_opbb b 
                                  where
                                     a.i_op = b.i_op 
                                     and a.i_pp = x.i_pp 
                                     and a.i_material = x.i_material 
                                     and a.i_satuan = x.i_satuan 
                                     and b.f_op_cancel = 'f' 
                               )
                               as qty_op 
                            from
                               (
                                  SELECT
                                     a.i_pp,
                                     a.i_kode_master,
                                     f.e_nama_master,
                                     b.i_material,
                                     d.e_material_name,
                                     b.i_satuan,
                                     e.e_satuan,
                                     b.n_quantity,
                                     b.v_price,
                                     g.i_supplier,
                                     b.n_pemenuhan 
                                  FROM
                                     tm_pp a 
                                     JOIN
                                        tm_pp_item b 
                                        ON a.i_pp = b.i_pp 
                                        and b.f_op_complete = 'false' 
                                     JOIN
                                        tr_material d 
                                        ON b.i_material = d.i_material 
                                     JOIN
                                        tr_satuan e 
                                        ON b.i_satuan = e.i_satuan 
                                     JOIN
                                        tr_master_gudang f 
                                        ON a.i_kode_master = f.i_kode_master 
                                     JOIN
                                        tr_supplier_materialprice g 
                                        ON b.i_material = g.i_material 
                                        and g.i_supplier = '$isupplier' 
                                  WHERE
                                     f_pp_cancel = 'f' 
                                     and a.d_pp >= '$dfrom1' 
                                     and a.d_pp <= '$dto1' 
                                     and a.i_kode_master = '$igudang' 
                                     and a.i_pp = '$ipp' 
                                     and b.n_sisa not in 
                                     (
                                        '0.00'
                                     )
                                  ORDER BY
                                     d.e_material_name,
                                     b.i_material,
                                     a.i_pp 
                               )
                               as x 
                            order by
                               x.i_pp,
                               x.e_nama_master,
                               x.e_material_name",
            false
        );
        //and b.i_satuan=g.i_satuan
        return $this->db->get();
    }

    function get_harga($dfrom, $dto, $isupplier)
    {
      // $db_debug = $this->db->db_debug;
      // $this->db->db_debug = FALSE;
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $isupplier  = $this->input->post('isupplier');
        if (isset($dfrom)) {
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dfrom1 = $year . '-' . $month . '-' . $day;
        }
        if (isset($dto)) {
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dto1 = $year . '-' . $month . '-' . $day;
        }
        if (!isset($dfrom)) $dfrom = date('Y-m-d');
        if (!isset($dto)) $dto = date('Y-m-d');
        $this->db->select(
            "x.*, 
                          (
                             select
                                sum(a.n_quantity) as op 
                             from
                                tm_opbb_item a,
                                tm_opbb b 
                             where
                                a.i_op = b.i_op 
                                and a.i_material = x.i_material 
                                and a.i_satuan = x.i_satuan 
                                and a.i_pp = x.i_pp 
                                and b.f_op_cancel = 'f' 
                          )
                          as op 
                          from
                             (
                                SELECT
                                   a.i_pp,
                                   a.i_material,
                                   c.e_material_name,
                                   a.i_satuan,
                                   d.e_satuan,
                                   a.n_quantity,
                                   b.v_price,
                                   a.v_price as hrgpp,
                                   f.i_kode_master,
                                   g.e_nama_master 
                                FROM
                                   tm_pp_item a 
                                   LEFT JOIN
                                      tr_supplier_materialprice b 
                                      ON a.i_material = b.i_material 
                                      and b.i_supplier = '$isupplier' 
                                   JOIN
                                      tr_material c 
                                      ON a.i_material = c.i_material 
                                   JOIN
                                      tr_satuan d 
                                      ON a.i_satuan = d.i_satuan 
                                   JOIN
                                      tm_pp f 
                                      ON a.i_pp = f.i_pp 
                                   JOIN
                                      tr_master_gudang g 
                                      ON f.i_kode_master = g.i_kode_master 
                                where
                                   f.d_pp >= '$dfrom1' 
                                   and f.d_pp <= '$dto1' 
                                GROUP BY
                                   a.i_pp,
                                   a.i_material,
                                   c.e_material_name,
                                   a.i_satuan,
                                   d.e_satuan,
                                   a.n_quantity,
                                   b.v_price,
                                   a.v_price,
                                   f.i_kode_master,
                                   g.e_nama_master,
                                   b.i_price_no 
                                ORDER BY
                                   b.i_price_no DESC 
                             )
                             as x",
            false
        );
        return $this->db->get();
      // WHERE a.i_material = '$imaterial' AND a.i_pp = '$ipp
    }*/

    function get_head($id_pp_item, $isupplier)
    {
        $idcompany  = $this->session->userdata('id_company');
        $id_pp_item = implode(",", $id_pp_item);
        return $this->db->query(
            "
      SELECT 
         b.id_company,
         array_agg(distinct(f.e_bagian_name)) AS e_bagian_name,
         '$isupplier' as i_supplier,
         h.e_supplier_name,
         h.n_supplier_toplength,
         h.n_diskon,
         h.i_type_pajak,
         h.f_pkp
      FROM
         tm_pp b
         inner join tm_pp_item a on (b.id = a.id_pp)
         LEFT JOIN tr_supplier h ON (h.i_supplier = '$isupplier' AND b.id_company = h.id_company)
         LEFT JOIN tr_bagian f ON (b.i_bagian = f.i_bagian AND b.id_company = f.id_company)
      WHERE
         b.i_status = '6' AND b.id_company = '$idcompany' and a.id IN ($id_pp_item)
         group by 1, 3, 4, 5, 6, 7, 8
    ",
            FALSE
        );
    }

    function get_harga1($id_pp_item, $isupplier, $jenis)
    {
        if ($jenis == 'cash') {
            $where = '';
        } else {
            $where = "and c.v_harga_konversi is not null";
        }
        $idcompany  = $this->session->userdata('id_company');
        $id_pp_item = implode(",", $id_pp_item);
        return $this->db->query(
            "
                              SELECT 
                              x.*,
                               (
                                  SELECT 
                                     sum(a.n_quantity) as qty_op
                                  FROM
                                     tm_opbb_item a, 
                                     tm_opbb b
                                  WHERE
                                     a.id_op = b.id
                                     AND a.i_material = x.i_material
                                     AND a.id_pp = x.id_pp
                                     AND b.i_status = '6'
                                     AND b.id_company = '$idcompany'
                               ) as qty_op
                                  FROM
                                  (   
                                  SELECT 
                                     a.id_pp,
                                     a.i_pp,
                                     to_char(b.d_pp, 'dd-mm-yyyy') as d_pp,
                                     a.i_material,
                                     d.e_material_name,
                                     a.i_satuan_code,
                                     e.e_satuan_name,
                                     a.n_quantity,
                                     c.v_harga_konversi,
                                     c.v_price as hrgpp,
                                     b.i_bagian,
                                     f.e_bagian_name,
                                     c.i_supplier,
                                     h.e_supplier_name,
                                     a.n_sisa,
                                     h.n_supplier_toplength,
                                     h.n_diskon,
                                     h.i_type_pajak,
                                     h.f_pkp
                                  FROM
                                     tm_pp_item a
                                     LEFT JOIN 
                                  tm_pp b
                                  ON (a.id_pp = b.id)
                                     LEFT JOIN
                                  tr_supplier_materialprice c
                                  ON (a.i_material = c.i_material AND c.i_supplier = '$isupplier' and a.id_company = c.id_company)
                                     LEFT JOIN 
                                  tr_material d
                                  ON (a.i_material = d.i_material AND a.id_company = d.id_company)
                                     LEFT JOIN
                                  tr_satuan e
                                  ON (a.i_satuan_code = e.i_satuan_code AND a.id_company = e.id_company)
                                     LEFT JOIN
                                  tr_bagian f
                                  ON (b.i_bagian = f.i_bagian AND a.id_company = f.id_company)
                                     LEFT JOIN
                                  tr_supplier h
                                  ON (c.i_supplier = h.i_supplier AND a.id_company = h.id_company)
                                  WHERE
                                     b.i_status = '6'
                                     AND a.id_company = '$idcompany'
                                     AND (c.d_berlaku >= b.d_pp OR c.d_akhir isnull)
                                     $where
                                     AND a.n_sisa > 0.00
                                     and a.id IN ($id_pp_item)
                                  GROUP BY 
                                     a.id_pp,
                                     a.i_pp,
                                     b.d_pp,
                                     a.i_material,
                                     d.e_material_name,
                                     a.i_satuan_code,
                                     e.e_satuan_name,
                                     a.n_quantity,
                                     c.v_harga_konversi,
                                     c.v_price,
                                     b.i_bagian,
                                     f.e_bagian_name,
                                     c.i_supplier,
                                     h.e_supplier_name,
                                     a.n_sisa,
                                     h.n_supplier_toplength,
                                     h.n_diskon,
                                     h.i_type_pajak,
                                     h.f_pkp
                                  ORDER BY 
                                     a.i_material, 
                                     d.e_material_name
                                  ) as x  
                                  order by x.id_pp, x.e_material_name
                            ",
            false
        );
    }

    public function cek_sup($isupplier)
    {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_supplier WHERE i_supplier='$isupplier' AND id_company = '$idcompany'", FALSE);
    }

    public function importancestatus($cari)
    {
        return $this->db->query(
            "SELECT * FROM tr_status_op WHERE (UPPER(i_status_op) LIKE '%$cari%' OR UPPER(e_status_op) LIKE '%$cari%') ORDER BY i_status_op",
            FALSE
        );
    }

    public function paymenttype($cari)
    {
        return $this->db->query(
            "SELECT * FROM tr_payment_type WHERE (UPPER(i_payment_type) LIKE '%$cari%' OR UPPER(e_payment_type_name) LIKE '%$cari%') ORDER BY i_payment_type",
            FALSE
        );
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_opbb');
        return $this->db->get()->row()->id + 1;
    }

    /*public function cekstock($ipp, $imaterial)
    {
        return $this->db->query("SELECT n_sisa FROM tm_pp_item WHERE i_pp = '$ipp' AND i_material = '$imaterial'", FALSE);
    }*/

    public function changestatus($id, $istatus)
    {
        $idcompany = $this->session->userdata('id_company');
        if ($istatus == '6') {
            $query = $this->db->query(
                "
               SELECT 
                  id_pp, 
                  i_material, 
                  n_quantity 
               FROM 
                  tm_opbb_item
               WHERE 
                  id_op = '$id' ",
                FALSE
            );
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $key) {
                    $this->db->query(
                        "
                       UPDATE
                           tm_pp_item
                       SET
                           n_sisa = n_sisa - $key->n_quantity
                       WHERE
                           id_pp = '$key->id_pp'
                           AND i_material = '$key->i_material'
                           AND id_company = '$idcompany'
                   ",
                        FALSE
                    );
                }
            }
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_opbb', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    /*public function updatestock($ipp, $imaterial, $total)
    {
        $this->db->set(
            array(
                'n_sisa' => $total,
            )
        );
        $this->db->where('i_pp', $ipp);
        $this->db->where('i_material', $imaterial);
        $this->db->update('tm_pp_item');
    }*/

    public function updateharga($id,$imaterial,$nquantity,$vprice)
    {
        $this->db->set(
            array(
                'n_quantity' => $nquantity,
                'n_sisa'     => $nquantity,
                'v_price'    => $vprice,
            )
        );
        $this->db->where('id_op', $id);
        $this->db->where('i_material', $imaterial);
        $this->db->update('tm_opbb_item');
    }

    public function insertheader(
        $id,
        $iop,
        $dateop,
        $ibagian,
        $isupplier,
        $ntop,
        $ndiskon,
        $itypepajak,
        $importantstatus,
        $datedeliv,
        $eremark,
        $fpkp,
        $esuppliername,
        $jenis
    )
    {
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'id'               => $id,
            'i_op'             => $iop,
            'd_op'             => $dateop,
            'i_bagian'         => $ibagian,
            'i_supplier'       => $isupplier,
            'n_top'            => $ntop,
            'n_diskon'         => $ndiskon,
            'i_type_pajak'     => $itypepajak,
            'i_status_op'      => $importantstatus,
            'i_status'         => '1',
            'd_deliv'          => $datedeliv,
            'f_pkp'            => $fpkp,
            'e_supplier_name'  => $esuppliername,
            'e_remark'         => $eremark,
            'id_company'       => $idcompany,
            'd_entry'          => current_datetime(),
            'jenis_pembelian'  => $jenis,
        );

        $this->db->insert('tm_opbb', $data);
    }

    public function insertdetail($id, $idpp, $imaterial, $nquantity, $nsisa, $vprice, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_op'             => $id,
            'id_pp'             => $idpp,
            'i_material'        => $imaterial,
            'n_quantity'        => $nquantity,
            'n_sisa'            => $nsisa,
            'v_price'           => $vprice,
            'v_price_temporary' => $vprice,
            'e_remark'          => $eremark,
            'id_company'        => $idcompany,
        );

        $this->db->insert('tm_opbb_item', $data);
    }

    public function updatedetail($id, $idpp, $imaterial, $nquantity, $nsisa, $vprice, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_op'             => $id,
            'id_pp'             => $idpp,
            'i_material'        => $imaterial,
            'n_quantity'        => $nquantity,
            'n_sisa'            => $nsisa,
            'v_price'           => $vprice,
            'v_price_temporary' => $vprice,
            'e_remark'          => $eremark
        );
        $this->db->where('id_op', $id);
        $this->db->where('id_pp', $idpp);
        $this->db->where('i_material', $imaterial);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_opbb_item', $data);
    }

    /*public function send($kode)
    {
        $data = array(
            'e_approval' => '2'
        );

        $this->db->where('i_op', $kode);
        $this->db->update('tm_opbb', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_op', $id);
        $this->db->delete('tm_opbb_item');
    }*/

    public function update(
        $id,
        $iop,
        $dateop,
        $ibagian,
        $isupplier,
        $ntop,
        $ndiskon,
        $itypepajak,
        $importantstatus,
        $datedeliv,
        $eremark,
        $fpkp,
        $esuppliername
    )
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id'               => $id,
            'i_op'             => $iop,
            'd_op'             => $dateop,
            'i_bagian'         => $ibagian,
            'i_supplier'       => $isupplier,
            'n_top'            => $ntop,
            'n_diskon'         => $ndiskon,
            'i_type_pajak'     => $itypepajak,
            'i_status_op'      => $importantstatus,
            'd_deliv'          => $datedeliv,
            'e_remark'         => $eremark,
            'd_update'         => current_datetime(),
            'f_pkp'            => $fpkp,
            'e_supplier_name'  => $esuppliername
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_opbb', $data);
    }

    /*public function update2($iop, $imaterial, $ipp, $isatuan, $nquantity, $vprice, $eremark)
    {
        $data = array(
            'i_op'          => $iop,
            'i_material'    => $imaterial,
            'i_pp'          => $ipp,
            'i_satuan'      => $isatuan,
            'n_quantity'    => $nquantity,
            'v_price'       => $vprice,
            'e_remark'      => $eremark,
        );

        $this->db->where('i_material', $imaterial);
        $this->db->update('tm_opbb_item', $data);
    }

    public function approve($iop)
    {
        $data = array(
            'e_approval' => '5',
            'd_approve' => date("d F Y H:i:s"),
        );
        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    public function approvenext($iop)
    {
        $data = array(
            'e_approval' => '5',
            'd_approve' => date("d F Y H:i:s"),
        );
        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    public function cancel($iop)
    {
        $this->db->set(
            array(
                'e_approval'   => '9',
                'f_op_cancel'  => 't'
            )
        );
        $this->db->where('i_op', $iop);
        return $this->db->update('tm_opbb');
    }

    public function sendd($iop)
    {
        $data = array(
            'e_approval' => '2'
        );

        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    public function cancel_approve($iop)
    {
        $data = array(
            'e_approval' => '7',
        );
        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    public function change_approve($iop)
    {
        $data = array(
            'e_approval' => '3',
        );
        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    public function reject_approve($iop)
    {
        $data = array(
            'e_approval' => '4',
        );
        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    public function appr2($iop)
    {
        $data = array(
            'e_approval' => '6',
        );
        $this->db->where('i_op', $iop);
        $this->db->update('tm_opbb', $data);
    }

    function getdataheader($id)
    {
        $this->db->select(
            "distinct a.i_op, d.i_pp, a.i_payment_type, a.d_op, a.e_remark, a.f_op_cancel, b.i_supplier, b.e_supplier_name, d.e_approve, a.d_deliv, c.i_kode_master, e.e_nama_master, a.e_approval, d_pp, a.important_status, f.e_status
                        from tm_opbb a
                        join tr_supplier b on a.i_supplier = b.i_supplier
                        join tm_opbb_item c on a.i_op = c.i_op
                        join tm_pp d on c.i_pp = d.i_pp
                        join tr_master_gudang e on c.i_kode_master = e.i_kode_master
                        join tm_status_dokumen f on a.e_approval=f.i_status
                        where a.i_op='$id'",
            false
        );

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function getdatadetail($id)
    {
        $this->db->select(
            "a.i_op, a.i_pp, a.i_material, a.i_satuan,a.e_remark, a.i_kode_master, a.n_pemenuhan, b.e_material_name, c.e_satuan, d.i_kode_master,  b.e_material_name, c.e_satuan, d.i_kode_master, e.e_nama_master, sum(v_price) as total, po.d_op, a.n_quantity
                        from tm_opbb_item a
                        join tr_material b on a.i_material = b.i_material
                        join tr_satuan c on a.i_satuan = c.i_satuan
                        join tm_pp d on a.i_pp = d.i_pp
                        join tr_master_gudang e on d.i_kode_master = e.i_kode_master
                        join tm_opbb po on a.i_op =po.i_op
                        where a.i_op='$id'
                        group by a.i_op, a.i_pp, a.i_material, a.i_satuan,a.e_remark, a.i_kode_master, a.n_pemenuhan, b.e_material_name, c.e_satuan, d.i_kode_master, e.e_nama_master, po.d_op, a.n_quantity",
            false
        );

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }*/

    public function cetakop($idop)
    {
        return $this->db->query(
            "
                               SELECT DISTINCT a.id, a.i_op, a.i_status, to_char(a.d_op,'dd-mm-yyyy') AS d_op, a.i_bagian, 
                              f.e_bagian_name as bagian_pembuat, to_char(a.d_deliv,'dd-mm-yyyy') AS d_deliv, 
                              a.i_status_op, d.e_status_op,  array_agg(distinct(e.e_bagian_name)) AS e_bagian_name, 
                              a.i_supplier, a.e_supplier_name, g.e_supplier_address, a.e_remark, a.n_top, a.n_diskon, 
                              a.i_type_pajak, a.f_pkp , a.jenis_pembelian
                              FROM tm_opbb a 
                              LEFT JOIN tm_opbb_item b ON (a.id = b.id_op) 
                              LEFT JOIN tm_pp c ON (b.id_pp = c.id and a.id_company = c.id_company) 
                              LEFT JOIN tr_status_op d ON (a.i_status_op = d.i_status_op) 
                              LEFT JOIN tr_bagian e ON (c.i_bagian = e.i_bagian and a.id_company = e.id_company) 
                              LEFT JOIN tr_bagian f ON (a.i_bagian = f.i_bagian and a.id_company = f.id_company) 
                              LEFT JOIN tr_supplier g ON (g.i_supplier = a.i_supplier and a.id_company = g.id_company) 
                              WHERE b.id_op = '$idop' AND a.id_company = '" . $this->session->userdata("id_company") . "' and a.i_status IN ('6','14')
                              group by 1,2,3,4,5,6,7,8,9,11,12,13,14,15,16,17,18
                              ",
            FALSE
        );
    }

    public function get_cetakid(){
        $idcompany = $this->session->userdata("id_company");
        return $this->db->query( " SELECT * FROM tr_cetak WHERE id_company = '$idcompany'", FALSE);
    }
}
/* End of file Mmaster.php */