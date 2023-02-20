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
            $where = "AND d_keluar_qc BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);

        $cek = $this->db->query("
                                    SELECT
                                        i_bagian
                                    FROM
                                        tm_keluar_qc
                                    WHERE
                                        i_status <> '5'
                                        AND id_company = '" . $this->session->userdata('id_company') . "'
                                        $where
                                        AND i_bagian IN (
                                            SELECT
                                                i_bagian
                                            FROM
                                                tr_departement_cover
                                            WHERE
                                                i_departement = '" . $this->session->userdata('i_departement') . "'
                                                AND id_company = '" . $this->session->userdata('id_company') . "'
                                                AND username = '" . $this->session->userdata('username') . "')
                                ", FALSE);
        if ($this->session->userdata('i_departement') == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (SELECT
                            i_bagian
                        FROM
                            tr_departement_cover
                        WHERE
                            i_departement = '" . $this->session->userdata('i_departement') . "'
                            AND id_company = '" . $this->session->userdata('id_company') . "'
                            AND username = '" . $this->session->userdata('username') . "')";
            }
        }

        $sql = "SELECT DISTINCT
                    0 as no,
                    a.id,
                    a.i_keluar_qc,
                    to_char(a.d_keluar_qc, 'dd-mm-yyyy') as d_keluar_qc,
                    a.i_tujuan,
                    ab.e_bagian_name,
                    concat (b.e_bagian_name, ' - ', c2.name) e_bagian_tujuan,
                    cc.e_jenis_name,
                    a.e_remark,
                    a.id_company,
                    a.i_status,
                    c.e_status_name,
                    a.i_bagian,
                    c.label_color,
                    f.i_level,
                    l.e_level_name,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto,
                    '$i_menu' as i_menu,
                    '$folder' AS folder
                FROM tm_keluar_qc a 
                JOIN tr_bagian b ON (b.i_bagian = a.i_tujuan AND b.id_company = a.id_company_tujuan) 
                JOIN tr_bagian ab ON (ab.i_bagian = a.i_bagian AND ab.id_company = a.id_company AND ab.i_type = '12') 
                JOIN tr_status_document c ON (a.i_status = c.i_status)   
                JOIN tr_jenis_barang_keluar cc ON (cc.id = a.id_jenis_barang_keluar)     
                LEFT JOIN public.company c2 ON c2.id = a.id_company_tujuan                    
                LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
                LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
                    WHERE a.id_company = '$idcompany'
                        AND a.i_status <> '5'
                    $where $bagian
                ORDER BY a.id DESC";

        // var_dump($sql); die();

        $datatables->query($sql, false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id           = trim($data['id']);
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];
            $i_level      = $data['i_level'];

            $data       = '';
            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye mr-2 fa-lg text-success'></i></a>";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt mr-2 fa-lg'></i></a>";
                }
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box mr-2 fa-lg text-primary'></i></a>";
                }
            }
            if (check_role($i_menu, 4) && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close fa-lg text-danger'></i></a>";
            }

            return $data;
        });

        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');

        return $datatables->generate();
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus_20211216($id, $istatus)
    {
        if ($istatus == '6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_qc', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_keluar_qc a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
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
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6') {
                if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_qc');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_qc', $data);
    }

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
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

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_keluar_qc');
        $this->db->from('tm_keluar_qc');
        $this->db->where('i_keluar_qc', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function jeniskeluar()
    {
        return $this->db->get_where("tr_jenis_barang_keluar", ["id" => "1"]);
    }

    public function runningnumber($thbl, $tahun, $ibagian, $itujuan)
    {
        //var_dump($thbl);
        $id_company = $this->id_company;
        // $cek = $this->db->query("
        // SELECT 
        //     a.i_bagian,
        //     b.e_no_doc as kode
        // FROM
        //     tr_tujuan_menu a
        // INNER JOIN
        //     tr_kategori_jahit b 
        //     ON (b.id = a.id_kategori)
        // WHERE
        //     id_company = '$id_company'
        //     AND a.i_menu = '$this->i_menu'
        //     AND i_bagian = '$itujuan'
        // ");

        $sql = "SELECT a.i_bagian, b.i_keluar_qc as kode
                FROM tr_tujuan_menu a
                INNER JOIN tm_keluar_qc b ON (b.id = a.id_kategori)
                WHERE id_company = '$id_company'
                    AND a.i_menu = '$this->i_menu'
                    AND i_bagian = '$itujuan'";

        $cek = $this->db->query($sql);

        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'STB';
        }
        $count = strlen($kode);
        $start = $count + 2;
        $sub   = $count + 7;
        $query  = $this->db->query("
            SELECT
                max(substring(i_keluar_qc, $sub, 4)) AS max
            FROM
                tm_keluar_qc
            WHERE to_char (d_keluar_qc, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND i_tujuan = '$itujuan'
            AND id_company = '$id_company'
            AND substring(i_keluar_qc, 1, $count) = '$kode'
            AND substring(i_keluar_qc, $start, 2) = substring('$thbl', 1, 2)
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 4) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "0001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_qc');
        return $this->db->get()->row()->id + 1;
    }

    public function runningiditem()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_qc_item');
        return $this->db->get()->row()->id;
    }

    public function tujuan($i_menu, $idcompany)
    {
        $sql = "SELECT a.*, b.id AS id_bagian, b.e_bagian_name, c.name, c.id AS id_company
                FROM tr_tujuan_menu a
                JOIN tr_bagian b ON a.i_bagian = b.i_bagian
                JOIN public.company c ON c.id = b.id_company
                WHERE a.i_menu = '$i_menu'
                    AND a.id_company = '$idcompany'";

        // var_dump($sql);

        return $this->db->query($sql);
    }

    public function dataproduct($cari, $itujuan=null)
    {
        $idcompany  = $this->session->userdata('id_company');

        if ($itujuan != null) {
            $query = $this->get_bagian_by_id($itujuan);
            $idcompany = $query->row()->id_company;
        }

        return $this->db->query("    
                                    SELECT  
                                        a.id,
                                        a.i_product_base,
                                        a.e_product_basename,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND
                                        (upper(a.i_product_base) LIKE '%$cari%'
                                        OR upper(a.e_product_basename) LIKE '%$cari%') 
                                ", FALSE);
    }

    public function getproduct($eproduct)
    {
        $idcompany  = $this->session->userdata('id_company');

        // return $this->db->query("            
        //                             SELECT 
        //                                 a.id as id_product, 
        //                                 a.i_product_base,
        //                                 a.e_product_basename,
        //                                 b.id as id_color,
        //                                 a.i_color,
        //                                 b.e_color_name
        //                             FROM
        //                                 tr_product_base a
        //                             INNER JOIN tr_color b ON
        //                                 (b.i_color = a.i_color AND a.id_company = b.id_company)
        //                             WHERE
        //                                 a.id_company = '$idcompany'
        //                             AND 
        //                                 a.id = '$eproduct'
        //                         ", FALSE);

        $sql = "SELECT tpb.id AS id_product, tpb.i_product_base, tpb.e_product_basename, tc.id AS id_color, tpb.i_color, tc.e_color_name
                FROM tr_product_base tpb
                INNER JOIN tr_color tc ON (
                                        tc.i_color = tpb.i_color AND tc.id_company = tpb.id_company
                                        )
                WHERE tpb.id = '$eproduct'";

        return $this->db->query($sql);                
    }

    // public function getstok($idproduct, $ibagian)
    // {
    //     $idcompany = $this->session->userdata('id_company');
    //     $today = date('Y-m-d');
    //     $jangkaawal = date('Y-m-01');
    //     $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
    //     $periode = date('Ym');

    //     $sql = "SELECT DISTINCT a.id,
    //                 CASE
    //                     WHEN c.n_saldo_akhir IS NULL THEN 0 
    //                     WHEN c.n_saldo_akhir < 0 THEN 0 ELSE c.n_saldo_akhir
    //                 END AS saldo_akhir
    //             FROM tr_product_base a
    //             INNER JOIN tr_color b ON (
    //                                     a.i_color = b.i_color AND a.id_company = b.id_company
    //                                     )
    //             LEFT JOIN (
    //                         SELECT * 
    //                         FROM produksi.f_mutasi_packing(
    //                                                     $idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian'
    //                                                     )
    //                     ) c ON (c.id_product_base = a.id AND c.id_company = '$idcompany')
    //             WHERE a.id = '$idproduct'
    //                 AND a.id_company = '$idcompany'
    //                 AND a.f_status = 't'
    //                 AND b.f_status = 't'
    //             ORDER BY a.id ASC";

    //     // var_dump($sql); die();

    //     return $this->db->query($sql,FALSE);
    // }

    public function getstok($idproduct, $ibagian, $itujuan=null)
    {
        $idcompany = $this->session->userdata('id_company');
        // if ($itujuan != null) {
        //     $query = $this->get_bagian_by_id($itujuan);
        //     $idcompany = $query->row()->id_company;
        //     $ibagian = $query->row()->i_bagian;
        // }

        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');

        $sql = "SELECT DISTINCT a.id,
                    CASE
                        WHEN c.n_saldo_akhir IS NULL THEN 0 
                        WHEN c.n_saldo_akhir < 0 THEN 0 ELSE c.n_saldo_akhir
                    END AS saldo_akhir
                FROM tr_product_base a
                INNER JOIN tr_color b ON (
                                        a.i_color = b.i_color AND a.id_company = b.id_company
                                        )
                LEFT JOIN (
                            SELECT * 
                            FROM produksi.f_mutasi_packing(
                                                        $idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '$ibagian'
                                                        )
                        ) c ON (c.id_product_base = a.id AND c.id_company = '$idcompany')
                WHERE a.id = '$idproduct'
                    -- AND a.id_company = '$idcompany'
                    AND a.f_status = 't'
                    AND b.f_status = 't'
                ORDER BY a.id ASC";

        // var_dump($sql); die();

        return $this->db->query($sql,FALSE);
    }

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $id_company_tujuan = $idcompany;

        if (intval($itujuan) >= 1) {
            $query = $this->get_bagian_by_id($itujuan);
            $itujuan = $query->row()->i_bagian;
            $id_company_tujuan = $query->row()->id_company;
        }

        $data = array(
            'id_company'        => $idcompany,
            'id'                => $id,
            'i_keluar_qc'       => $ibonk,
            'd_keluar_qc'       => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'e_remark'          => $eremark,
            'd_entry'           => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
            'id_company_tujuan' => $id_company_tujuan
        );
        $this->db->insert('tm_keluar_qc', $data);
    }

    public function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_keluar_qc'      => $id,
            'id_product'        => $iproduct,
            'id_color'          => $icolor,
            'n_quantity_product' => $nqtyproduct,
            'n_sisa'            => $nqtyproduct,
            'id_company'        => $idcompany,
            'e_remark'          => $edesc,
        );
        // var_dump($data); die();
        $this->db->insert('tm_keluar_qc_item', $data);
    }

    public function insertbundling($id, $iditem, $iproduct, $nqtyproduct)
    {
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'id_keluar_qc' => $id,
            'id_keluar_qc_item'      => $iditem,
            'id_product'        => $iproduct,
            'n_quantity_bundling' => $nqtyproduct,
            'id_company'        => $idcompany,
        );
        $this->db->insert('tm_keluar_qc_bundling', $data);
    }

    public function cek_data($id, $idcompany)
    {
        $sql = "SELECT
                    a.id,
                    a.i_keluar_qc,
                    to_char(a.d_keluar_qc, 'dd-mm-yyyy') as d_keluar_qc,
                    a.i_bagian,
                    tb.id AS i_tujuan,
                    a.i_status,
                    a.e_remark,
                    a.id_jenis_barang_keluar
                FROM tm_keluar_qc a
                LEFT JOIN tr_bagian tb ON tb.i_bagian = a.i_tujuan AND tb.id_company = a.id_company_tujuan
                WHERE a.id = '$id'
                AND a.id_company = '$idcompany' ";
        
        return $this->db->query($sql, FALSE);
    }

    public function cek_datadetail($id, $idcompany)
    {
        $idcompany = $this->session->userdata('id_company');
        $today = date('Y-m-d');
        $jangkaawal = date('Y-m-01');
        $jangkaakhir = date('Y-m-d', strtotime("-1 days"));
        $periode = date('Ym');
       
        $sql = "SELECT DISTINCT ON (a.id)
                a.id,
                a.id_keluar_qc,
                a.id_product,
                b.i_product_base,
                b.e_product_basename,
                a.id_color,
                c.i_color,
                c.e_color_name,
                a.n_quantity_product,
                a.e_remark,
                CASE 
                    WHEN d.i_status = '6' THEN (
                        CASE 
                            WHEN s.n_saldo_akhir IS NULL THEN 0 
                            WHEN (s.n_saldo_akhir + a.n_quantity_product) < 0 THEN 0 
                        ELSE (s.n_saldo_akhir + a.n_quantity_product)
                    END) 
                ELSE (
                    CASE
                        WHEN s.n_saldo_akhir IS NULL THEN 0 
                        WHEN s.n_saldo_akhir < 0 THEN 0 ELSE s.n_saldo_akhir
                    END) END AS saldo_akhir
            FROM tm_keluar_qc_item a 
                JOIN tm_keluar_qc d ON a.id_keluar_qc = d.id 
                JOIN tr_product_base b ON a.id_product = b.id
                JOIN tr_color c ON a.id_color = c.id
                LEFT JOIN (
                        SELECT * 
                        FROM produksi.f_mutasi_packing($idcompany, '$periode', '$jangkaawal', '$jangkaakhir', '$today', '$today', '')
                        ) s ON (
                            s.id_product_base = a.id_product AND s.id_company = '$idcompany'
                            )
            WHERE a.id_keluar_qc = '$id' 
                AND d.id_company = '$idcompany'";

        // var_dump($sql); 
        
        return $this->db->query($sql);
    }

    public function view_databundling($id, $company)
    {
        return $this->db->query("SELECT j.*, k.i_product_base, k.e_product_basename, l.e_color_name FROM tm_keluar_qc_bundling j
        INNER JOIN tm_keluar_qc_item a ON (a.id = j.id_keluar_qc_item)
        JOIN tr_product_base k ON (
            j.id_product = k.id AND j.id_company = k.id_company
        )
        JOIN tr_color l ON (
            k.i_color = l.i_color AND j.id_company = l.id_company
        )
        WHERE
            a.id_keluar_qc = '$id' 
            AND j.id_company = '$company'
        ORDER BY j.id_product", FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremark)
    {
        $idcompany  = $this->session->userdata('id_company');
        $id_company_tujuan = $idcompany;

        if (intval($itujuan) >= 1) {
            $query = $this->get_bagian_by_id($itujuan);
            $itujuan = $query->row()->i_bagian;
            $id_company_tujuan = $query->row()->id_company;
        }

        $data = array(
            'i_keluar_qc'       => $ibonk,
            'd_keluar_qc'       => $datebonk,
            'i_bagian'          => $ibagian,
            'i_tujuan'          => $itujuan,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime(),
            'id_jenis_barang_keluar' => $ijenis,
            'id_company_tujuan' => $id_company_tujuan
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_qc', $data);
    }

    public function deletedetail($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('id_keluar_qc', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_keluar_qc_item');
    }
    public function deletebundling($id)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('id_keluar_qc', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_keluar_qc_bundling');
    }

    public function doc($imenu)
    {
        $this->db->select('doc_qe');
        $this->db->from('public.tm_menu');
        $this->db->where('i_menu', $imenu);
        return $this->db->get();
    }

    public function get_bagian_by_id($id_bagian) {
        $this->db->select();
        $this->db->from('tr_bagian');
        $this->db->where('id', $id_bagian);
        return $this->db->get();
    }

    public function generate_nomor_dokumen($bagian, $itujuan) {
        $id_company = $this->id_company;
        $id_company_tujuan = $id_company;

        if (intval($itujuan) >= 1) {
            $query = $this->get_bagian_by_id($itujuan);
            $itujuan = $query->row()->i_bagian;
            $id_company_tujuan = $query->row()->id_company;
        }    

        $kode = 'STB';
        $where = "AND id_company_tujuan = '$id_company_tujuan'";

        if ($id_company_tujuan != $id_company) {
            $kode = 'SJ';
            $where = "AND NOT id_company_tujuan = '$id_company'";
        }

        $sql = "SELECT count(*) FROM tm_keluar_qc tkq
                    WHERE i_bagian = '$bagian'
                    AND id_company = '$id_company'
                    $where 
                    AND to_char(d_keluar_qc, 'yyyy-mm') = to_char(now(), 'yyyy-mm')
                    AND i_status <> '5'";

        $query = $this->db->query($sql);
        $result = $query->row()->count;
        $count = intval($result) + 1;
        $generated = $kode . '-' . date('ym') . '-' . sprintf('%04d', $count);

        return $generated;
    }
}
/* End of file Mmaster.php */