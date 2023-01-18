<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder, $dfrom, $dto){

        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
		$datatables = new Datatables(new CodeigniterAdapter);
        $idcompany  = $this->session->userdata('id_company');
        $datatables->query("SELECT
                DISTINCT
                0 AS NO,
                a.id,
                a.i_document,
                a.d_document,
                a.i_periode,
                a.i_bagian,
                b.e_bagian_name,
                a.i_status,
                d.e_status_name,
                d.label_color,
                e.i_level,
                f.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' as dfrom,
                '$dto' as dto
            FROM
                tm_schedule_new a
                LEFT JOIN tr_bagian b
                ON (b.i_bagian = a.i_bagian)
                LEFT JOIN tr_status_document c
                ON (c.i_status = a.i_status)
                INNER JOIN tr_status_document d 
                ON (a.i_status = d.i_status)
                LEFT JOIN tr_menu_approve e 
                ON (a.i_approve_urutan = e.n_urut AND e.i_menu = '$i_menu')
                LEFT JOIN public.tr_level f 
                ON (e.i_level = f.i_level)
            $where
            ", FALSE);
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id      = trim($data['id']);
            $idocument  = trim($data['i_document']);
            $i_status   = trim($data['i_status']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data    = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$idocument\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$idocument/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"".base_url($folder.'/cform/download/'.$id.'/'.$idocument)."\" title='Export'><i class='ti-download text-success'></i></a>&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($this->i_level == 1)) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$idocument/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_bagian');
        $datatables->hide('i_status');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');

        return $datatables->generate();
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus == '3' || $istatus == '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_schedule_new a
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
                        'i_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_schedule');", FALSE);
            }
        } else {
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_schedule_new', $data);
    }

    public function productwip($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT
                a.id_product_wip, 
                c.i_product_wip,
                c.e_product_wipname,
                d.i_color,
                d.e_color_name,
                CASE 
                            WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Print, Bordir, Quilting'
                            WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Print, Bordir'
                            WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Bordir'
                            WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN ''
                            WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Print, Quilting'
                            WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Quilting'
                            WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Print'
                            WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Bordir, Quilting'
                END progress,
                COALESCE(sum(a.n_fc_cutting), 0) AS n_fc_cutting,
                COALESCE(sum(a.n_fc_perhitungan), 0) AS n_fc_perhitungan,
                COALESCE(sum(a.n_kondisi_stock), 0) AS n_kondisi_stock
                FROM tm_fccutting_item_new a
                INNER JOIN tm_fccutting b ON
                (a.id_forecast = b.id AND b.i_periode = '202202')
                LEFT JOIN tr_product_wip c
                ON (c.id = a.id_product_wip)
                LEFT JOIN tr_color d
                ON (d.i_color = c.i_color AND d.id_company = 4)
                INNER JOIN tr_polacutting_new e
                ON (e.id_product_wip = a.id_product_wip)
                AND (c.i_product_wip ILIKE '%$cari%' 
                    OR c.e_product_wipname ILIKE '%$cari%' 
                    OR d.e_color_name ILIKE '%$cari%')
                AND e.f_marker_utama = 't'
                GROUP BY a.id_company, a.id_product_wip, c.i_product_wip, c.e_product_wipname, d.i_color, d.e_color_name
        ", FALSE);
    }

    public function get_bisbisan($cari,$i_material)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                a.id,
                n_bisbisan,
                b.e_jenis_potong
            FROM
                tr_material_bisbisan a
            INNER JOIN tr_jenis_potong b ON
                (b.id = a.id_jenis_potong)
            INNER JOIN tr_material c ON 
                (c.id = a.id_material)
            WHERE
                c.i_material = '$i_material'
                AND c.id_company = '$idcompany'
                AND (b.e_jenis_potong ILIKE '%$cari%')
                AND a.f_status = 't'
            ORDER BY 3,2
        ", FALSE);
    }

    public function productwipref($cari,$i_product_wip, $i_color)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                DISTINCT 
                a.i_product_wip,
                initcap(e_product_wipname) AS e_product_wipname,
                a.i_color,
                initcap(e_color_name) AS e_color_name
            FROM
                tr_product_wip a
            INNER JOIN tr_polacutting_new b ON
                (b.id_product_wip = a.id)
            INNER JOIN tr_color c ON
                (c.i_color = a.i_color
                    AND a.id_company = c.id_company)
            WHERE
                a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND a.i_product_wip = '$i_product_wip'
                AND a.i_color <> '$i_color'
                AND (a.i_product_wip ILIKE '%$cari%'
                    OR e_product_wipname ILIKE '%$cari%'
                    OR e_color_name ILIKE '%$cari%')
                AND b.f_marker_utama = 't'
            ORDER BY
                i_product_wip
        ", FALSE);
    }

    public function getdetailref($i_product_wip, $i_color)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                DISTINCT 
                a.*,
                e.i_material,
                e.e_material_name,
                v_bisbisan,
                n_bisbisan,
                c.e_jenis_potong,
                a.e_bagian AS bagian,
                string_agg(trim(e_bagian_name),', ') AS gudang
            FROM
                tr_polacutting_new a
            LEFT JOIN tr_material_bisbisan b ON
                (b.id_material = a.id_material
                    AND a.id_bisbisan = b.id)
            LEFT JOIN tr_material e ON 
                (e.id = a.id_material)
            LEFT JOIN tr_jenis_potong c ON
                (c.id = b.id_jenis_potong)
            LEFT JOIN tr_product_wip d ON
                (d.id = a.id_product_wip)
            LEFT JOIN tr_bagian_kelompokbarang h ON (h.i_kode_kelompok = e.i_kode_kelompok AND e.id_company = h.id_company)
            LEFT JOIN tr_bagian i ON (i.i_bagian = h.i_bagian AND h.id_company = i.id_company)
            WHERE
                a.f_status = 't'
                AND a.id_company = '$idcompany'
                AND d.i_product_wip = '$i_product_wip'
                AND d.i_color = '$i_color'
            GROUP BY 1,e.i_material,e.e_material_name,n_bisbisan,c.e_jenis_potong,a.e_bagian
            ORDER BY
                e.e_material_name ASC
        ", FALSE);
    }

    public function material($cari)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT 
                i_material,
                e_material_name,
                b.e_satuan_name
            FROM tr_material a
            INNER JOIN tr_satuan b ON (b.i_satuan_code = a.i_satuan_code AND a.id_company = b.id_company)
            WHERE 
                a.id_company = '$idcompany'
                AND
                a.f_status = 't'
                AND (i_material ILIKE '%$cari%' 
                     OR e_material_name ILIKE '%$cari%')
                AND i_kode_group_barang NOT IN ('GRB0003')
            ORDER BY i_material
        ", FALSE);
    }

    public function getkategori()
    {
        return $this->db->query("SELECT id,e_nama_kategori FROM tr_kategori_jahit WHERE f_status = 't'
        ", false);
    }

    public function getunit($cari,$kategori)
    {
        return $this->db->query("SELECT id,e_nama_unit FROM tr_unit_jahit WHERE id_kategori_jahit = '$kategori' AND e_nama_unit ILIKE '%$cari%'  AND f_status = 't'
        ", false);
    }

    public function getdetailmaterial($i_material)
    {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT DISTINCT i_material, string_agg(trim(e_bagian_name),', ') AS e_bagian_name  FROM tr_material a
            LEFT JOIN tr_bagian_kelompokbarang b ON (b.i_kode_kelompok = a.i_kode_kelompok AND a.id_company = b.id_company)
            LEFT JOIN tr_bagian c ON (c.i_bagian = b.i_bagian AND b.id_company = c.id_company)
            WHERE i_material = '$i_material' AND a.f_status = 't'
            AND a.id_company = '$idcompany'
            GROUP BY 1
        ", FALSE);
    }

    public function cekdata($iproduct,$icolor,$imaterial)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->select('i_product_wip, i_material, i_color');
        $this->db->from('tr_polacutting');
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_material', $imaterial);
        $this->db->where('i_color', $icolor);
        return $this->db->get();
    }

    /* public function insertdetail($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproductwip,$icolor,$n_bagibis,$bagian,$bis3,$bis4){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'i_product_wip' => $iproductwip,
            'i_material'    => $imaterial,
            'i_color'       => $icolor,
            'v_gelar'       => $vgelar,
            'v_set'         => $vset,
            'f_bisbisan'    => $fbis,
            'v_toset'       => $vtoset,
            'n_bagibis'     => $n_bagibis,
            'id_company'    => $idcompany,
            'd_entry'       => current_datetime(),
            'bagian'        => $bagian,
            'n_bis3'        => $bis3,
            'n_bis4_5'      => $bis4,
        );
        $this->db->insert('tr_polacutting', $data);
    } */

    public function insertheader($idocument, $ddocument, $ibagian, $keterangan, $iperiode){
        $tgl = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);

        $this->db->query("INSERT 
        INTO tm_schedule_new(id_company, i_document, d_document, i_bagian, e_remark, d_entry, i_periode) 
        VALUES ('$this->id_company', '$idocument', '$ddocument', '$ibagian', '$keterangan', '$tgl', '$iperiode')
        ");
    }


    public function insertdetail($idocument, $tanggal, $ibarang, $progress, $nfccutting, $nfcproduksi, $nkondisi, $eremark){
        $this->db->query("INSERT 
        INTO tm_schedule_item_new(i_document, d_schedule, id_product_wip, e_progress, n_fc_cutting, n_fc_perhitungan, n_kondisi_stock, e_remark) 
        VALUES ('$idocument', '$tanggal', '$ibarang', '$progress', '$nfccutting', '$nfcproduksi', '$nkondisi', '$eremark')
        ");
    }

    public function updateheader($idocument, $ddocument, $ibagian, $keterangan){
        $tgl = date("Y-m-d H:i:s") . substr((string)microtime(), 1, 4);

        $this->db->query("UPDATE tm_schedule_new
        SET d_document = '$ddocument', i_bagian = '$ibagian', e_remark = '$keterangan', d_update = '$tgl'
        WHERE (i_document = '$idocument')
        ");
    }

    public function updatedetail($idocument, $tanggal, $ibarang, $progress, $nfccutting, $nfcproduksi, $nkondisi, $eremark){
        $this->db->query("INSERT 
        INTO tm_schedule_item_new(i_document, d_schedule, id_product_wip, e_progress, n_fc_cutting, n_fc_perhitungan, n_kondisi_stock, e_remark) 
        VALUES ('$idocument', '$tanggal', '$ibarang', '$progress', '$nfccutting', '$nfcproduksi', '$nkondisi', '$eremark')
        ");
    }

    public function updatenourut($id,$urut){

        $this->db->set('n_urut_stock', $urut);
        $this->db->where('id', $id);
        $this->db->update('tm_schedule_item_new');
    }

    public function insertdetailall($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproductwip,$icolor,$n_bagibis,$bagian,$bis3,$bis4,$id_bisbisan,$f_cutting,$autocutter,$badan,$print,$bordir,$quilting){
        if ($bis3 == '') {
            $bis3 = 0;
        }
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND id_company = '$idcompany' ", FALSE);
        if($query->num_rows()>0){
            foreach($query->result() AS $key){
                $data = array(
                    'id_company'        => $idcompany,
                    'id_product_wip'    => $key->id,
                    'id_material'       => $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id,
                    'e_bagian'          => $bagian,
                    'v_gelar'           => $vgelar,
                    'v_set'             => $vset,
                    'v_bisbisan'        => $bis3,
                    'id_bisbisan'       => $id_bisbisan,
                    'f_cutting'         => $f_cutting,
                    'f_autocutter'        => $autocutter,
                    'f_badan'             => $badan,
                    'f_print'             => $print,
                    'f_bordir'            => $bordir,
                    'f_quilting'          => $quilting,
                );
                $this->db->insert('tr_polacutting_new', $data);
            }
        }
    }

    public function updatedetailall($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproductwip,$icolor,$n_bagibis,$bagian,$bis3,$bis4,$id_bisbisan,$f_cutting,$autocutter,$badan,$print,$bordir,$quilting){
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND id_company = '$idcompany' ", FALSE);
        if($query->num_rows()>0){
            foreach($query->result() AS $key){
                $data = array(
                    'id_company'        => $idcompany,
                    'id_product_wip'    => $key->id,
                    'id_material'       => $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany' ", FALSE)->row()->id,
                    'e_bagian'          => $bagian,
                    'v_gelar'           => $vgelar,
                    'v_set'             => $vset,
                    'v_bisbisan'        => $bis3,
                    'id_bisbisan'       => $id_bisbisan,
                    'f_cutting'         => $f_cutting,
                    'f_autocutter'        => $autocutter,
                    'f_badan'             => $badan,
                    'f_print'             => $print,
                    'f_bordir'            => $bordir,
                    'f_quilting'          => $quilting,
                    'd_update'          => current_datetime(),
                );
                $this->db->insert('tr_polacutting_new', $data);
            }
        }
    }

    public function insertdetailwip($iproductwip, $imaterial, $n_quantity,$bagian,$icolor,$f_cutting,$autocutter,$badan,$print,$bordir,$quilting){
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        if($query->num_rows()>0){
            $sql = $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany'", FALSE);
            if($sql->num_rows()>0){
                $data = array(
                    'id_company'     => $idcompany,
                    'id_product_wip' => $query->row()->id,
                    'id_material'    => $sql->row()->id,
                    'n_quantity'     => $n_quantity,
                    'bagian'         => $bagian,
                    'f_cutting'      => $f_cutting,
                    'f_autocutter'     => $autocutter,
                    'f_badan'          => $badan,
                    'f_print'          => $print,
                    'f_bordir'         => $bordir,
                    'f_quilting'       => $quilting,
                );
                $this->db->insert('tr_polacutting_new', $data);
            }
        }
    }

    public function insertdetailwipall($iproductwip, $imaterial, $n_quantity,$bagian,$f_cutting,$autocutter,$badan,$print,$bordir,$quilting){
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproductwip' AND id_company = '$idcompany' ", FALSE);
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $sql = $this->db->query("SELECT id FROM tr_material WHERE i_material = '$imaterial' AND id_company = '$idcompany'", FALSE);
                if($sql->num_rows()>0){
                    $data = array(
                        'id_company'     => $idcompany,
                        'id_product_wip' => $row->id,
                        'id_material'    => $sql->row()->id,
                        'n_quantity'     => $n_quantity,
                        'e_bagian'         => $bagian,
                        'f_cutting'      => $f_cutting,
                        'f_autocutter'     => $autocutter,
                        'f_badan'          => $badan,
                        'f_print'          => $print,
                        'f_bordir'         => $bordir,
                        'f_quilting'       => $quilting,
                    );
                    $this->db->insert('tr_polacutting_new', $data);
                }
            }
        }
    }

    public function dataheader($idocument)
    {
        // $this->db->select('a.*')->distinct();
        return $this->db->query("SELECT 
        a.id,
        a.i_document,
        a.d_document,
        a.i_bagian,
        a.i_status,
        b.e_bagian_name,
        a.e_remark,
        a.i_periode
        FROM tm_schedule_new a
        LEFT JOIN tr_bagian b
        ON (b.i_bagian = a.i_bagian)
        WHERE i_document = '$idocument'" );
    }

    public function cekdataheader($id)
    {
        // $this->db->select('a.*')->distinct();
        return $this->db->query("SELECT 
        a.id,
        a.i_document,
        a.d_document,
        a.i_bagian,
        a.i_status,
        b.e_bagian_name,
        a.e_remark
        FROM tm_schedule_new a
        LEFT JOIN tr_bagian b
        ON (b.i_bagian = a.i_bagian)
        WHERE a.id = '$id'" );
    }

    public function detail($idocument)
    {
        return $this->db->query("SELECT DISTINCT
        a.i_document,
        a.d_schedule,
        a.id_product_wip, 
        b.i_product_wip,
        b.e_product_wipname,
        c.i_color,
        c.e_color_name,
        a.e_progress,
        a.n_fc_cutting,
        a.n_fc_perhitungan,
        a.n_kondisi_stock,
        a.e_remark,
        a.n_urut_stock
        FROM tm_schedule_item_new a
        INNER JOIN tr_product_wip b
        ON (b.id = a.id_product_wip)
        INNER JOIN tr_color c
        ON (c.i_color = b.i_color AND c.id_company = '$this->id_company')
        INNER JOIN tr_polacutting_new e
        ON (e.id_product_wip = a.id_product_wip )
        WHERE a.i_document = '$idocument' AND e.f_marker_utama = 't'
        GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13
        ORDER BY 9 DESC,2 ASC,13 DESC
        ");
        
    }

    public function detailurut($idocument)
    {
        return $this->db->query("SELECT DISTINCT
        a.i_document,
        a.d_schedule,
        a.id_product_wip, 
        b.i_product_wip,
        b.e_product_wipname,
        c.i_color,
        c.e_color_name,
        a.e_progress,
        a.n_fc_cutting,
        a.n_fc_perhitungan,
        a.n_kondisi_stock,
        a.e_remark,
        a.n_urut_stock
        FROM tm_schedule_item_new a
        INNER JOIN tr_product_wip b
        ON (b.id = a.id_product_wip)
        INNER JOIN tr_color c
        ON (c.i_color = b.i_color AND c.id_company = '$this->id_company')
        INNER JOIN tr_polacutting_new e
        ON (e.id_product_wip = a.id_product_wip )
        WHERE a.i_document = '$idocument' AND e.f_marker_utama = 't'
        GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13
        ORDER BY 9 DESC,2 ASC,13 DESC
        ");
        
    }

    public function cekdetail($idocument)
    {
        return $this->db->query("SELECT DISTINCT
        a.id,
        a.i_document,
        a.d_schedule,
        a.id_product_wip, 
        b.i_product_wip,
        b.e_product_wipname,
        c.i_color,
        c.e_color_name,
        a.e_progress,
        a.n_fc_cutting,
        a.n_fc_perhitungan,
        a.n_kondisi_stock,
        a.e_remark,
        CASE 
            WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 7
                    WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 2
                    WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 3
                    WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN NULL
                    WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 4
                    WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 6
                    WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 1
                    WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 5
        END n_urut_stock
        FROM tm_schedule_item_new a
        INNER JOIN tr_product_wip b
        ON (b.id = a.id_product_wip)
        INNER JOIN tr_color c
        ON (c.i_color = b.i_color AND c.id_company = '$this->id_company')
        INNER JOIN tr_polacutting_new e
        ON (e.id_product_wip = a.id_product_wip )
        WHERE a.i_document = '$idocument' AND e.f_marker_utama = 't'
        GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13
        ORDER BY 10 DESC, 3 ASC
        ");
    }

    public function cek_data($id, $ibagian)
    {
        return $this->db->query("SELECT 
                a.id,
                a.i_document, 
                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                a.i_bagian,
                b.e_bagian_name,
                a.e_remark,
                a.i_status
            FROM tm_schedule_new a
            INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
            WHERE a.id  = '$id'
            AND a.i_bagian = '$ibagian'
            AND a.id_company = '$this->idcompany'
            ", FALSE);
    }

    public function cek_datadetail($id)
    {
        return $this->db->query("SELECT
                a.*,
                c.i_product_wip,
                c.e_product_wipname,
                c.i_color,
                e.id AS id_color,
                e.e_color_name,
                d.i_material,
                d.e_material_name,
                a.e_remark,
                f.n_fc_cutting AS n_quantity_wip_sisa
            FROM
                tm_ipcutting_item a
            INNER JOIN tr_product_wip c ON
                (a.id_product_wip = c.id
                    AND a.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (a.id_material = d.id
                    AND a.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (c.i_color = e.i_color
                    AND c.id_company = e.id_company)
            LEFT JOIN tm_fccutting_item_new f ON
                (f.id = a.id_fccutting_item)
            WHERE
                a.id_document = '$id'
                AND a.id_company = '$this->idcompany'
            ORDER BY c.i_product_wip, a.id ", FALSE);
    }

    public function getperiode(){
        return $this->db->query("SELECT i_periode FROM tm_fccutting WHERE i_status = '6' AND i_periode NOT IN (SELECT i_periode FROM tm_schedule_new WHERE i_status NOT IN ('9','4','7'))");
    }

    public function getdataitem($periode)
        {
            return $this->db->query("SELECT DISTINCT
            a.id_company,
            a.id_product_wip, 
            c.i_product_wip, 
            c.e_product_wipname,
            d.e_color_name,
            CASE 
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Print, Bordir, Quilting'
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Print, Bordir'
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Bordir'
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) < 1 THEN ''
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Print, Quilting'
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Quilting'
                        WHEN sum(cast(e.f_bordir as int)) < 1 AND sum(cast(e.f_print as int)) >= 1 AND sum(cast(e.f_quilting as int)) < 1 THEN 'Print'
                        WHEN sum(cast(e.f_bordir as int)) >= 1 AND sum(cast(e.f_print as int)) < 1 AND sum(cast(e.f_quilting as int)) >= 1 THEN 'Bordir, Quilting'
            END progress,
            COALESCE(sum(a.n_fc_cutting), 0) AS n_fc_cutting,
            COALESCE(sum(a.n_fc_perhitungan), 0) AS n_fc_perhitungan,
            COALESCE(sum(a.n_kondisi_stock), 0) AS n_kondisi_stock
            FROM tm_fccutting_item_new a
            INNER JOIN tm_fccutting b ON
            (a.id_forecast = b.id AND b.i_periode = '$periode')
            LEFT JOIN tr_product_wip c
            ON (c.id = a.id_product_wip)
            LEFT JOIN tr_color d
            ON (d.i_color = c.i_color AND d.id_company = '$this->id_company')
            INNER JOIN tr_polacutting_new e
            ON (e.id_product_wip = a.id_product_wip)
            WHERE e.f_marker_utama = 't'
            GROUP BY a.id_company ,a.id_product_wip,c.i_product_wip ,c.e_product_wipname, d.e_color_name
            ORDER BY 7 DESC");
        }

    public function delete($iproduct,$icolor,$cek)
    {
        $idcompany  = $this->session->userdata('id_company');
        if($cek!='on'){
            $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        }else{
            $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' /* AND i_color = '$icolor' */ AND id_company = '$idcompany' ", FALSE);
        }
        if($query->num_rows()>0){
            foreach($query->result() AS $row){
                $this->db->where('id_product_wip', $row->id);
                $this->db->delete('tr_polacutting_new');
            }
        }
        /* if($cek!='on'){

            $this->db->where('id_product_wip', $iproduct);
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        }else{
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        } */
    }

    /* public function delete($iproduct,$icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $this->db->where('i_product_wip', $iproduct);
        $this->db->where('i_color', $icolor);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tr_polacutting');
    } */

    public function deletewip($iproduct,$icolor)
    {
        $idcompany  = $this->session->userdata('id_company');
        $query = $this->db->query("SELECT id FROM tr_product_wip WHERE i_product_wip = '$iproduct' AND i_color = '$icolor' AND id_company = '$idcompany' ", FALSE);
        if($query->num_rows()>0){
            $this->db->where('id_product_wip', $query->row()->id);
            /* $this->db->where('i_color', $icolor); */
            $this->db->where('id_company', $idcompany);
            $this->db->delete('tr_polacutting_new');
        }
    }

    public function deletedetail($idocument)
    {
        $this->db->where('i_document', $idocument);
        $this->db->delete('tm_schedule_item_new');
    }

    public function getformat()
    {
        return $this->db->query("SELECT i_document FROM tm_schedule_new ORDER BY i_document DESC LIMIT 1");
    }

    public function bagian() {
        return $this->db->query("
				SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
				INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
				left join tr_type c on (a.i_type = c.i_type)
				left join public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
				WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
				ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function bagianpembuat()
    {
        return $this->db->query("
                SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
                INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
                LEFT JOIN tr_type c on (a.i_type = c.i_type)
                LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
                WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
                ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function cekstatus($idocument)
    {
        $this->db->select('i_status');
        $this->db->from('tm_schedule_new');
        $this->db->where('i_document', $idocument);
        return $this->db->get()->row()->i_status;
    }

    public function getidocument($id)
    {
        $this->db->select('i_document');
        $this->db->from('tm_schedule_new');
        $this->db->where('id', $id);
        return $this->db->get()->row()->i_document;
    }

    public function getlateperiode()
    {
        $query = $this->db->query("SELECT i_periode FROM tm_schedule_new ORDER BY i_periode DESC LIMIT 1");
        return $query->row()->i_periode;
    }
}
/* End of file Mmaster.php */