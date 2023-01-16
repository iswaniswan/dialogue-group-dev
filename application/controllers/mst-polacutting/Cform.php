<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;*/
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2010214';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'] . '/mmaster');
    }


    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);
    }

    public function status()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id == '') {
            $id = $this->uri->segment(4);
        }
        /* $iproductcolor = explode('|', $id);
        $iproduct = $iproductcolor[0];
        $icolor   = $iproductcolor[1]; */
        /*$id       = $iproductcolor[2];*/
        if ($id != '') {
            $this->db->trans_begin();
            $data = $this->mmaster->status(/* $iproduct, $icolor, */$id);
            if (($this->db->trans_status() === False)) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status ' . $this->global['title'] . ' ID : ' . $id);
                echo json_encode($data);
            }
        }
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'marker'     => $this->db->get_where('tr_marker',['f_status'=>'t']),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function productwip()
    {
        $filter = [];
        $data   = $this->mmaster->productwip(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->i_product_wip . '|' . $row->i_color . '|' . $row->id,
                    'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - [' . $row->e_color_name . ']',
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function productwipref()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $i_product_wip  = explode('|', $this->input->get('i_product_wip', TRUE))[0];
        $i_color        = explode('|', $this->input->get('i_product_wip', TRUE))[1];
        $id_marker      = $this->input->get('id_marker', TRUE);
        $data           = $this->mmaster->productwipref($cari, $i_product_wip, $i_color, $id_marker);
        if ($i_product_wip != '') {
            if ($data->num_rows() > 0) {
                foreach ($data->result() as  $row) {
                    $filter[] = array(
                        'id'   => $row->i_product_wip . '|' . $row->i_color,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - [' . $row->e_color_name . ']',
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data",
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih Product WIP",
            );
        }
        echo json_encode($filter);
    }

    public function get_bisbisan()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $i_material     = $this->input->get('i_material', false);
        $data           = $this->mmaster->get_bisbisan($cari, $i_material);
        if ($i_material != '') {
            if ($data->num_rows() > 0) {
                foreach ($data->result() as  $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->n_bisbisan . ' - ' . $row->e_jenis_potong,
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data",
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih Product Terlebih Dahulu",
            );
        }
        echo json_encode($filter);
    }

    public function get_type_makloon()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $data           = $this->mmaster->get_type_makloon($cari);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->name,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/

    public function getdetailref()
    {
        header("Content-Type: application/json", true);
        $i_product_wip  = explode('|', $this->input->post('i_product_wip', TRUE))[0];
        $i_color        = explode('|', $this->input->post('i_product_wip', TRUE))[1];
        $id_marker      = $this->input->post('id_marker', TRUE);
        $query  = array(
            'detail' => $this->mmaster->getdetailref($i_product_wip, $i_color, $id_marker)->result_array(),
        );
        echo json_encode($query);
    }

    /*----------  GET DETAIL MATERIAL  ----------*/

    public function getdetailmaterial()
    {
        header("Content-Type: application/json", true);
        $i_material  = $this->input->post('i_material', TRUE);
        $query  = array(
            'detail' => $this->mmaster->getdetailmaterial($i_material)->result_array(),
        );
        echo json_encode($query);
    }

    public function material()
    {
        $filter = [];
        $data   = $this->mmaster->material(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->i_material,
                    'text' => $row->i_material . ' - ' . $row->e_material_name . ' - ' . $row->e_satuan_name,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }


    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iproductcolor  = explode('|', $this->input->post('iproductwip', TRUE));
        $id_marker      = $this->input->post('id_marker', TRUE);
        $f_marker_utama = $this->input->post('f_marker_utama', TRUE);
        if ($f_marker_utama == 'on') {
            $f_marker_utama = 't';
        } else {
            $f_marker_utama = 'f';
        }
        $cek            = $this->input->post('cek', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if ($iproductcolor != '' && $jml > 0) {
            $this->db->trans_begin();
            $iproduct       = $iproductcolor[0];
            $icolor         = $iproductcolor[1];
            $this->mmaster->delete($iproduct, $icolor, $cek, $id_marker);
            // $this->mmaster->deletewip($iproduct, $icolor);
            for ($i = 1; $i <= $jml; $i++) {
                $imaterial  = $this->input->post('imaterial' . $i, TRUE);
                $vtoset     = $this->input->post('vtoset' . $i, TRUE);
                $vgelar     = $this->input->post('vgelar' . $i, TRUE);
                $vset       = $this->input->post('vset' . $i, TRUE);
                $bagian     = $this->input->post('bagian' . $i, TRUE);
                $bis3       = $this->input->post('bis3' . $i, TRUE);
                $bis4       = $this->input->post('bis4' . $i, TRUE);
                $id_bisbisan = $this->input->post('id_bisbisan' . $i, TRUE);
                $id_type_makloon = $this->input->post('id_type_makloon' . $i.'[]', TRUE);
                // $id_type_makloon = to_pg_array($id_type_makloon);
                $f_cutting  = $this->input->post('f_cutting' . $i, TRUE);
                $autocutter = $this->input->post('autocutter' . $i, TRUE);
                $badan      = $this->input->post('badan' . $i, TRUE);
                $print      = $this->input->post('print' . $i, TRUE);
                $bordir     = $this->input->post('bordir' . $i, TRUE);
                $quilting   = $this->input->post('quilting' . $i, TRUE);
                $f_kain_utama   = $this->input->post('f_kain_utama' . $i, TRUE);
                $f_budgeting   = $this->input->post('f_budgeting' . $i, TRUE);
                $f_jahit   = $this->input->post('f_jahit' . $i, TRUE);
                $f_packing   = $this->input->post('f_packing' . $i, TRUE);
                if ($f_kain_utama == 'on') {
                    $f_kain_utama = 't';
                } else {
                    $f_kain_utama = 'f';
                }
                if ($f_budgeting == 'on') {
                    $f_budgeting = 't';
                } else {
                    $f_budgeting = 'f';
                }
                if ($f_jahit == 'on') {
                    $f_jahit = 't';
                } else {
                    $f_jahit = 'f';
                }
                if ($f_packing == 'on') {
                    $f_packing = 't';
                } else {
                    $f_packing = 'f';
                }
                if ($f_cutting == 'on') {
                    $f_cutting = 't';
                } else {
                    $f_cutting = 'f';
                }
                if ($autocutter == 'on') {
                    $autocutter = 't';
                } else {
                    $autocutter = 'f';
                }
                if ($badan == 'on') {
                    $badan = 't';
                } else {
                    $badan = 'f';
                }
                if ($print == 'on') {
                    $print = 't';
                } else {
                    $print = 'f';
                }
                if ($bordir == 'on') {
                    $bordir = 't';
                } else {
                    $bordir = 'f';
                }
                if ($quilting == 'on') {
                    $quilting = 't';
                } else {
                    $quilting = 'f';
                }
                if ($id_bisbisan == '') $id_bisbisan = "NULL";
                $this->input->post('fbis' . $i, TRUE) == 'on' ? $fbis = 't' : $fbis = 'f';
                if ($fbis == 't') {
                    $n_bagibis = '1.00';
                } else {
                    $n_bagibis = '0.00';
                    $n_bagibis = '0.00';
                }
                /* var_dump($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproduct,$icolor,$n_bagibis,$bagian,$bis3,$bis4);
                if ($this->mmaster->cekdata($iproduct,$icolor,$imaterial)->num_rows()>0) {
                    $data = array(
                        'sukses' => false,
                    );
                }else{ */
                if ($vset == 0) {
                    $vset = 1;
                }
                if (($imaterial != '' || $imaterial != null) && ($vgelar > 0 || $bis3 > 0)) {
                    $this->mmaster->insertdetail($imaterial, $vgelar, $vset, $iproduct, $icolor, $bagian, $bis3, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting,$id_type_makloon, $f_kain_utama, $f_budgeting, $f_jahit, $f_packing, $id_marker,$f_marker_utama);
                    /* $n_quantity = ((1 / $vset) * $vgelar) + ($bis3 * (6/270)) + ($bis4 * (6/180)); */

                    /* $finalbis = 0;
                    if ($id_bisbisan = "NULL") {
                        $id_bisbisan = "NULL";
                    } else {
                        $v_panjang_bis = $this->db->query("SELECT v_panjang_bis FROM tr_material_bisbisan WHERE id = '$id_bisbisan' ", FALSE);
                        $finalbis = $bis3 / $v_panjang_bis->row()->v_panjang_bis;
                    }
                    $n_quantity = ((1 / $vset) * $vgelar) + $finalbis; */
                    // $this->mmaster->insertdetailwip($iproduct, $imaterial, $n_quantity, $bagian, $icolor, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting,$id_type_makloon, $f_kain_utama, $f_budgeting);
                }
                /* } */
            }
            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $iproduct
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode WIP : ' . $iproduct);
            }
        } else {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'marker'     => $this->db->get_where('tr_marker',['f_status'=>'t']),
            'data'       => $this->mmaster->datawip($this->uri->segment(4), $this->uri->segment(5), $this->uri->segment(6))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4), $this->uri->segment(5), $this->uri->segment(6))->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iproductcolorold   = explode('|', $this->input->post('iproductcolor', TRUE));
        $iproductcolor      = explode('|', $this->input->post('iproductwip', TRUE));
        $id_marker          = $this->input->post('id_marker', TRUE);
        $f_marker_utama     = $this->input->post('f_marker_utama', TRUE);
        if ($f_marker_utama == 'on') {
            $f_marker_utama = 't';
        } else {
            $f_marker_utama = 'f';
        }
        $cek               = $this->input->post('cek', TRUE);
        $jml               = $this->input->post('jml', TRUE);
        if ($iproductcolor != '' && $jml > 0) {
            $this->db->trans_begin();
            $iproductold    = $iproductcolorold[0];
            $icolorold      = $iproductcolorold[1];
            /* $this->mmaster->delete($iproductold,$icolorold); */
            $this->mmaster->delete($iproductold, $icolorold, $cek, $id_marker);
            // $this->mmaster->deletewip($iproductold, $icolorold);
            $iproduct       = $iproductcolor[0];
            $icolor         = $iproductcolor[1];
            /* for ($i=1; $i <= $jml; $i++) { 
                $imaterial  = $this->input->post('imaterial'.$i, TRUE);
                $vtoset     = $this->input->post('vtoset'.$i, TRUE);
                $vgelar     = $this->input->post('vgelar'.$i, TRUE);
                $vset       = $this->input->post('vset'.$i, TRUE);
                $this->input->post('fbis'.$i, TRUE) == 'on' ? $fbis = 't' : $fbis = 'f';
                if ($this->mmaster->cekdata($iproduct,$icolor,$imaterial)->num_rows()>0) {
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    if (($imaterial!='' || $imaterial != null) && $vtoset > 0 && $vgelar > 0 && $vset > 0) {
                        $this->mmaster->insertdetail($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproduct,$icolor);
                    }
                }
            } */
            for ($i = 1; $i <= $jml; $i++) {
                $imaterial  = $this->input->post('imaterial' . $i, TRUE);
                $vtoset     = $this->input->post('vtoset' . $i, TRUE);
                $vgelar     = $this->input->post('vgelar' . $i, TRUE);
                $vset       = $this->input->post('vset' . $i, TRUE);
                $bagian     = $this->input->post('bagian' . $i, TRUE);
                $bis3       = $this->input->post('bis3' . $i, TRUE);
                $bis4       = $this->input->post('bis4' . $i, TRUE);
                $id_bisbisan = $this->input->post('id_bisbisan' . $i, TRUE);
                $id_type_makloon = $this->input->post('id_type_makloon' . $i.'[]', TRUE);
                $f_cutting  = $this->input->post('f_cutting' . $i, TRUE);
                $autocutter = $this->input->post('autocutter' . $i, TRUE);
                $badan      = $this->input->post('badan' . $i, TRUE);
                $print      = $this->input->post('print' . $i, TRUE);
                $bordir     = $this->input->post('bordir' . $i, TRUE);
                $quilting   = $this->input->post('quilting' . $i, TRUE);
                $f_kain_utama   = $this->input->post('f_kain_utama' . $i, TRUE);
                $f_budgeting   = $this->input->post('f_budgeting' . $i, TRUE);
                $f_jahit   = $this->input->post('f_jahit' . $i, TRUE);
                $f_packing   = $this->input->post('f_packing' . $i, TRUE);
                if ($f_cutting == 'on') {
                    $f_cutting = 't';
                } else {
                    $f_cutting = 'f';
                }
                if ($f_kain_utama == 'on') {
                    $f_kain_utama = 't';
                } else {
                    $f_kain_utama = 'f';
                }
                if ($f_budgeting == 'on') {
                    $f_budgeting = 't';
                } else {
                    $f_budgeting = 'f';
                }
                if ($f_jahit == 'on') {
                    $f_jahit = 't';
                } else {
                    $f_jahit = 'f';
                }
                if ($f_packing == 'on') {
                    $f_packing = 't';
                } else {
                    $f_packing = 'f';
                }
                if ($id_bisbisan == '') $id_bisbisan = null;
                $this->input->post('fbis' . $i, TRUE) == 'on' ? $fbis = 't' : $fbis = 'f';
                if ($fbis == 't') {
                    $n_bagibis = '1.00';
                } else {
                    $n_bagibis = '0.00';
                    $n_bagibis = '0.00';
                }
                /* var_dump($imaterial,$vtoset,$vgelar,$vset,$fbis,$iproduct,$icolor,$n_bagibis,$bagian,$bis3,$bis4);
                if ($this->mmaster->cekdata($iproduct,$icolor,$imaterial)->num_rows()>0) {
                    $data = array(
                        'sukses' => false,
                    );
                }else{ */

                if ($vset == 0) {
                    $vset = 1;
                }
                if (($imaterial != '' || $imaterial != null) && ($vgelar > 0 || $bis3 > 0)) {
                    $finalbis = 0;
                    if ($id_bisbisan != null || $id_bisbisan != "") {
                        $v_panjang_bis = $this->db->query("SELECT v_panjang_bis FROM tr_material_bisbisan WHERE id = '$id_bisbisan' ", FALSE);
                        $finalbis = $bis3 / $v_panjang_bis->row()->v_panjang_bis;
                    }
                    $n_quantity = ((1 / $vset) * $vgelar) + $finalbis;
                    if ($cek != 'on') {
                        $this->mmaster->updatedetail($imaterial, $vtoset, $vgelar, $vset, $fbis, $iproduct, $icolor, $n_bagibis, $bagian, $bis3, $bis4, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting, $f_jahit, $f_packing, $id_marker,$f_marker_utama);
                        // $this->mmaster->insertdetailwip($iproduct, $imaterial, $n_quantity, $bagian, $icolor, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting);
                    } else {
                        $this->mmaster->updatedetailall($imaterial, $vtoset, $vgelar, $vset, $fbis, $iproduct, $icolor, $n_bagibis, $bagian, $bis3, $bis4, $id_bisbisan, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting, $f_jahit, $f_packing, $id_marker,$f_marker_utama);
                        // $this->mmaster->insertdetailwipall($iproduct, $imaterial, $n_quantity, $bagian, $f_cutting, $autocutter, $badan, $print, $bordir, $quilting, $id_type_makloon, $f_kain_utama, $f_budgeting);
                    }
                }
                /* } */
            }

            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $iproduct
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode WIP : ' . $iproduct);
            }
        } else {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->datawip($this->uri->segment(4), $this->uri->segment(5),$this->uri->segment(6))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4), $this->uri->segment(5),$this->uri->segment(6))->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function checkmarkerutama()
    {
        $iproductwip = explode('|',$this->input->post('iproductwip'));
        $res = $this->mmaster->checkmarkerutama($iproductwip[2])->num_rows();
        echo json_encode($res);
    }




    public function export_1()
    {
        /* $data = check_role($this->i_menu, 6);
        if(!$data){
            redirect(base_url(),'refresh');
        } */

        $query          = $this->mmaster->get_dataheader();
        $idmaterial     = $this->mmaster->get_dataheader()->result_array();
        $idmaterial     = array_column($idmaterial,"id");
        $idmaterial     = implode("','",$idmaterial);
        $detail         = $this->mmaster->get_datamaterial($idmaterial);
        $bisbisan       = $this->mmaster->get_databisbisan($idmaterial);
        $spreadsheet = new Spreadsheet();
        $sharedStyle1 = new Style();
        $sharedStyle11 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStylex = new Style();

        $sheet_produk = $spreadsheet;
        $sheet1 = $spreadsheet;
        $sheet2 = $spreadsheet;
        $sheet3 = $spreadsheet;
        $sheet_redaksi = $spreadsheet;
        $sheet_panel = $spreadsheet;
        $sheet_gabungan = $spreadsheet;
        
        $sharedStyle1->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "DFF1D0"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle11->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "f7a19a"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle2->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                /* 'top'    => ['borderStyle' => Border::BORDER_THIN],
                 'bottom' => ['borderStyle' => Border::BORDER_THIN], */
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStylex->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                /* 'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN] */
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStyle3->applyFromArray([
            "font" => [
                "name" => "Times New Roman",
                "bold" => true,
                "italic" => false,
                "size" => 12,
            ],
            "alignment" => [
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);


        // START OF PRODUK

        $query_produk = $this->mmaster->data_export();
        $sheet_produk
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        // foreach (range("A", "L") as $columnID) {
        //     $sheet_produk
        //         ->getActiveSheet()
        //         ->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }
        $sheet_produk->getActiveSheet()->setTitle("Master Produk");
        $sheet_produk->getActiveSheet()->mergeCells("A1:K3");
        /* $sheet_produk->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:K3");
        /* $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet_produk
            ->setActiveSheetIndex(0)
            ->setCellValue("A1", "MASTER PRODUK")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "ID")
            ->setCellValue("C5", "Kode Barang")
            ->setCellValue("D5", "Nama Barang")
            ->setCellValue("E5", "Warna")
            ->setCellValue("F5", "Divisi")
            ->setCellValue("G5", "Kategori")
            ->setCellValue("H5", "Sub Kategori")
            ->setCellValue("I5", "Brand")
            ->setCellValue("J5", "Series")
            ->setCellValue("K5", "Kategori Penjualan")
            /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:K5");
        /* $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        $sheet_produk
            ->setActiveSheetIndex(0)
            ->setCellValue("K2", "KATEGORI PENJUALAN")
            ->setCellValue("K5", "ID Kategori")
            ->setCellValue("L5", "Nama Kategori");
        $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */

        

        $kolom = 6;
        $no = 1;
        if ($query_produk->num_rows() > 0) {
            foreach ($query_produk->result() as $row) {
                $data = $this->db
                    ->query(
                        "SELECT string_agg(e_class_name,', ') AS name FROM tr_class_product"
                    )
                    ->row()->name;
                $sheet = $sheet_produk->getActiveSheet();
                $validation = $sheet->getCell("K".$kolom)->getDataValidation();
                $validation
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setFormula1('"' . $data . '"')
                    ->setAllowBlank(false)
                    ->setShowDropDown(true)
                    ->setShowInputMessage(true)
                    ->setPromptTitle("Note")
                    ->setPrompt("Must select one from the drop down options.")
                    ->setShowErrorMessage(true)
                    ->setErrorStyle(
                        \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
                    )
                    ->setErrorTitle("Invalid option")
                    ->setError("Select one from the drop down list.");

                $sheet_produk
                    ->setActiveSheetIndex(0)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->id)
                    ->setCellValue("C" . $kolom, $row->i_product_base)
                    ->setCellValue("D" . $kolom, $row->e_product_basename)
                    ->setCellValue("E" . $kolom, $row->e_color_name)
                    ->setCellValue("F" . $kolom, $row->e_nama_divisi)
                    ->setCellValue("G" . $kolom, $row->e_nama_kelompok)
                    ->setCellValue("H" . $kolom, $row->e_type_name)
                    ->setCellValue("I" . $kolom, $row->brand)
                    ->setCellValue("J" . $kolom, $row->series)
                    ->setCellValue("K" . $kolom, $row->e_class_name)
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet_produk
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":K" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        $sheet->setDataValidation("K6:K" . $kolom, $validation);
        $sheet_produk
            ->getActiveSheet()
            ->duplicateStyle($sharedStylex, "A" . $kolom . ":K" . $kolom);
        // END OF PRODUK

        // ->getActiveSheet()
        $sheet1
        ->createSheet()
        ->setTitle("Master Material & Spare part")
        ->getStyle("B2")
        ->getAlignment()
        ->applyFromArray([
            "horizontal" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            "vertical" =>
                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            "textRotation" => 0,
            "wrapText" => true,
        ]);

        $sheet1
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        // foreach (range("A", "J") as $columnID) {
        //     $sheet1
        //         ->getActiveSheet()
        //         ->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }
        $sheet1->getActiveSheet()->mergeCells("A1:I3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet1->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:I3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet1
            ->setActiveSheetIndex(1)
            ->setCellValue("A1", "MATERIAL & SPARE PART")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Barang")
            ->setCellValue("C5", "Nama Barang")
            ->setCellValue("D5", "Satuan")
            ->setCellValue("E5", "Sub Kategori")
            ->setCellValue("F5", "Kategori")
            ->setCellValue("G5", "Grup Barang")
            ->setCellValue("H5", "Supplier Utama")
            ->setCellValue("I5", "Tanggal Daftar")
            /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        $sheet1->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:I5");

        $kolom = 6;
        $no = 1;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {

                $sheet1
                    ->setActiveSheetIndex(1)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_material)
                    ->setCellValue("C" . $kolom, $row->e_material_name)
                    ->setCellValue("D" . $kolom, $row->e_satuan_name)
                    ->setCellValue("E" . $kolom, $row->e_type_name)
                    ->setCellValue("F" . $kolom, $row->e_nama_kelompok)
                    ->setCellValue("G" . $kolom, $row->e_nama_group_barang)
                    ->setCellValue("H" . $kolom, $row->e_supplier_name)
                    ->setCellValue("I" . $kolom, $row->d_register)
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet1
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":I" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        // check point sheet1

        $sheet2
            ->createSheet()
            ->setTitle("Master Bisbisan")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sheet2
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        // foreach (range("A", "M") as $columnID) {
        //     $sheet2
        //         ->getActiveSheet()
        //         ->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }
        $sheet2->getActiveSheet()->mergeCells("A1:J3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet2->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:L3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet2
            ->setActiveSheetIndex(2)
            ->setCellValue("A1", "BISBISAN")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Barang")
            ->setCellValue("C5", "Nama Barang")
            ->setCellValue("D5", "Ukuran Bisbisan")
            ->setCellValue("E5", "Lebar Kain")
            ->setCellValue("F5", "Jenis Potong")
            ->setCellValue("G5", "% Hilang Lebar Kain")
            ->setCellValue("H5", "Lebar Kain Jadi")
            ->setCellValue("I5", "Jml Roll")
            ->setCellValue("J5", "% Tambah Panjang Kain")
            ->setCellValue("K5", "Panjang Bisbisan")
            ->setCellValue("L5", "Panjang Bisbisan per 1m")
            /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        $sheet2->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:L5");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        $spreadsheet
            ->setActiveSheetIndex(0)
            ->setCellValue("K2", "KATEGORI PENJUALAN")
            ->setCellValue("K5", "ID Kategori")
            ->setCellValue("L5", "Nama Kategori");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */

        

        $kolom = 6;
        $no = 1;
        if ($bisbisan->num_rows() > 0) {
            foreach ($bisbisan->result() as $row) {

                $sheet2
                    ->setActiveSheetIndex(2)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_material)
                    ->setCellValue("C" . $kolom, $row->e_material_name)
                    ->setCellValue("D" . $kolom, $row->n_bisbisan)
                    ->setCellValue("E" . $kolom, $row->v_lebar_kain_awal)
                    ->setCellValue("F" . $kolom, $row->e_jenis_potong)
                    ->setCellValue("G" . $kolom, $row->n_hilang_lebar)
                    ->setCellValue("H" . $kolom, $row->v_lebar_kain_akhir)
                    ->setCellValue("I" . $kolom, number_format($row->v_jumlah_roll,4))
                    ->setCellValue("J" . $kolom, $row->n_tambah_panjang)
                    ->setCellValue("K" . $kolom, $row->n_panjang_bis)
                    ->setCellValue("L" . $kolom, number_format($row->v_panjang_bis,4))
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet2
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":L" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        // check point sheet2

        $sheet3
            ->createSheet()
            ->setTitle("Konversi Material")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sheet3
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        // foreach (range("A", "G") as $columnID) {
        //     $sheet3
        //         ->getActiveSheet()
        //         ->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }
        $sheet3->getActiveSheet()->mergeCells("A1:H3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet3->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:H3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet3
            ->setActiveSheetIndex(3)
            ->setCellValue("A1", "KONVERSI MATERIAL")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Barang")
            ->setCellValue("C5", "Nama Barang")
            ->setCellValue("D5", "Satuan Produksi")
            ->setCellValue("E5", "Operator")
            ->setCellValue("F5", "Faktor")
            ->setCellValue("G5", "Satuan Pembelian")
            ->setCellValue("H5", "Di Pakai Pembelian");
        $sheet3->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:H5");

        $kolom = 6;
        $no = 1;
        if ($detail->num_rows() > 0) {
            foreach ($detail->result() as $row) {

                $sheet3
                    ->setActiveSheetIndex(3)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_material)
                    ->setCellValue("C" . $kolom, $row->e_material_name)
                    ->setCellValue("D" . $kolom, $row->e_satuan_name)
                    ->setCellValue("E" . $kolom, $row->e_operator)
                    ->setCellValue("F" . $kolom, number_format($row->n_faktor,4))
                    ->setCellValue("G" . $kolom, $row->e_satuan_name_konversi)
                    ->setCellValue("H" . $kolom, $row->f_default)
                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet3
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":H" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        // START OF REDAKSI
        $query_redaksi = $this->mmaster->data_export_redaksi();
        $sheet_redaksi
            ->createSheet()
            ->setTitle("Marker")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sheet_redaksi
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);
        // foreach (range("A", "O") as $columnID) {
        //     $sheet_redaksi
        //         ->getActiveSheet()
        //         ->getColumnDimension($columnID)
        //         ->setAutoSize(true);
        // }
        $sheet_redaksi->getActiveSheet()->mergeCells("A1:S3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet_redaksi->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:S3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        $sheet_redaksi
            ->setActiveSheetIndex(4)
            ->setCellValue("A1", "MARKER")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Produk")
            ->setCellValue("C5", "Nama Produk")
            ->setCellValue("D5", "Warna")
            ->setCellValue("E5", "Kode Material")
            ->setCellValue("F5", "Nama Material")
            ->setCellValue("G5", "Group")
            ->setCellValue("H5", "Bagian")
            ->setCellValue("I5", "Gelar")
            ->setCellValue("J5", "Set")
            ->setCellValue("K5", "Ukuran Bisbisan")
            ->setCellValue("L5", "Kebutuhan Bisbisan (Per Satuan Bisbisan)")
            ->setCellValue("M5", "Jenis Potong")
            ->setCellValue("N5", "Lebar Kain Awal")
            ->setCellValue("O5", "Kebutuhan Bisbisan (Per 1 Meter Bahan)")
            ->setCellValue("P5", "Kebutuhan Bisbisan (Per 1 PCS Produk)")
            ->setCellValue("Q5", "Jasa Proses")
            ->setCellValue("R5", "Kebutuhan Dalam 1 PCS Produk")
            ->setCellValue("S5", "Satuan");

        //$sheet_redaksi->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHeight(30); 
        
        $sheet_redaksi->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:S5");
        $sheet_redaksi->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        $sheet_redaksi->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setWrapText(true); 

        $kolom = 6;
        $no = 1;
        if ($query_redaksi->num_rows() > 0) {
            foreach ($query_redaksi->result() as $row) {

                $sheet_redaksi
                    ->setActiveSheetIndex(4)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_product_wip)
                    ->setCellValue("C" . $kolom, $row->e_product_wipname)
                    ->setCellValue("D" . $kolom, $row->e_color_name)
                    ->setCellValue("E" . $kolom, $row->i_material)
                    ->setCellValue("F" . $kolom, $row->e_material_name)
                    ->setCellValue("G" . $kolom, $row->e_nama_group_barang)
                    ->setCellValue("H" . $kolom, $row->e_bagian)
                    ->setCellValue("I" . $kolom, $row->v_gelar)
                    ->setCellValue("J" . $kolom, $row->v_set)
                    ->setCellValue("K" . $kolom, $row->n_bisbisan)
                    ->setCellValue("L" . $kolom, $row->v_bisbisan)
                    ->setCellValue("M" . $kolom, $row->jenis_potong)
                    ->setCellValue("N" . $kolom, $row->v_lebar_kain_awal)
                    ->setCellValue("O" . $kolom, $row->v_panjang_bis_bahan)
                    ->setCellValue("P" . $kolom, $row->v_panjang_bis_produk)
                    ->setCellValue("Q" . $kolom, $row->e_type_makloon_name)
                    ->setCellValue("R" . $kolom, $row->total)
                    ->setCellValue("S" . $kolom, $row->e_satuan_name)

                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet_redaksi
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":S" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }
        // END OF REDAKSI



        // START OF PANEL
        $query_panel = $this->mmaster->data_export_panel();
        $sheet_panel
            ->createSheet()
            ->setTitle("Panel")
            ->getStyle("B2")
            ->getAlignment()
            ->applyFromArray([
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "textRotation" => 0,
                "wrapText" => true,
            ]);

        $sheet_panel
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);

        $sheet_panel->getActiveSheet()->mergeCells("A1:R3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet_panel->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:R3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */

        $sheet_panel
            ->setActiveSheetIndex(5)
            ->setCellValue("A1", "PANEL")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Produk")
            ->setCellValue("C5", "Nama Produk")
            ->setCellValue("D5", "Warna")
            ->setCellValue("E5", "Series")
            ->setCellValue("F5", "Kode Material")
            ->setCellValue("G5", "Nama Material")
            ->setCellValue("H5", "Kode Panel")
            ->setCellValue("I5", "Bagian")
            ->setCellValue("J5", "Qty Penyusun")
            ->setCellValue("K5", "Panjang (CM)")
            ->setCellValue("L5", "Lebar (CM)")
            ->setCellValue("M5", "Panjang Gelaran (CM)")
            ->setCellValue("N5", "Lebar Gelaran (CM)")
            ->setCellValue("O5", "Hasil Gelaran (Set)")
            ->setCellValue("P5", "Efficiency Marker")
            ->setCellValue("Q5", "Print")
            ->setCellValue("R5", "Bordir");

        //$sheet_panel->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHeight(30); 
        
        $sheet_panel->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:R5");
        $sheet_panel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        $sheet_panel->getActiveSheet()->getStyle('A1:R5')->getAlignment()->setWrapText(true); 


        $kolom = 6;
        $no = 1;
        if ($query_panel->num_rows() > 0) {
            foreach ($query_panel->result() as $row) {

                $sheet_panel
                    ->setActiveSheetIndex(5)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_product_wip)
                    ->setCellValue("C" . $kolom, $row->e_product_wipname)
                    ->setCellValue("D" . $kolom, $row->e_color_name)
                    ->setCellValue("E" . $kolom, $row->e_series_name)
                    ->setCellValue("F" . $kolom, $row->i_material)
                    ->setCellValue("G" . $kolom, $row->e_material_name)
                    ->setCellValue("H" . $kolom, $row->i_panel)
                    ->setCellValue("I" . $kolom, $row->bagian)
                    ->setCellValue("J" . $kolom, $row->n_qty_penyusun)
                    ->setCellValue("K" . $kolom, $row->n_panjang_cm)
                    ->setCellValue("L" . $kolom, $row->n_lebar_cm)
                    ->setCellValue("M" . $kolom, $row->n_panjang_gelar)
                    ->setCellValue("N" . $kolom, $row->n_lebar_gelar)
                    ->setCellValue("O" . $kolom, $row->n_hasil_gelar)
                    ->setCellValue("P" . $kolom, $row->n_efficiency)
                    ->setCellValue("Q" . $kolom, $row->print)
                    ->setCellValue("R" . $kolom, $row->bordir)

                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
                $sheet_panel
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A" . $kolom . ":R" . $kolom
                    );
                $kolom++;
                $no++;
            }
        }

        // END OF PANEL



        // START OF Gabungan
        // $query_gabungan = $this->mmaster->data_export_gabungan();
        // $sheet_gabungan
        //     ->createSheet()
        //     ->setTitle("Gabungan")
        //     ->getStyle("B2")
        //     ->getAlignment()
        //     ->applyFromArray([
        //         "horizontal" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         "vertical" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         "textRotation" => 0,
        //         "wrapText" => true,
        //     ]);

        // $sheet_gabungan
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);

        // $sheet_gabungan->getActiveSheet()->mergeCells("A1:P3");
        // /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet_gabungan->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:P3");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */

        // $sheet_gabungan
        //     ->setActiveSheetIndex(6)
        //     ->setCellValue("A1", "GABUNGAN MARKER & PANEL")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "Kode Produk")
        //     ->setCellValue("C5", "Nama Produk")
        //     ->setCellValue("D5", "Warna")
        //     ->setCellValue("E5", "Series")
        //     ->setCellValue("F5", "Kode Material")
        //     ->setCellValue("G5", "Nama Material")
        //     ->setCellValue("H5", "Gelar (CM)")
        //     ->setCellValue("I5", "Set")
        //     ->setCellValue("J5", "Kode Panel")
        //     ->setCellValue("K5", "Bagian")
        //     ->setCellValue("L5", "QTY Penyusun")
        //     ->setCellValue("M5", "Panjang (CM)")
        //     ->setCellValue("N5", "Lebar (CM)")
        //     ->setCellValue("O5", "Panjang Gelaran (CM)")
        //     ->setCellValue("P5", "Hasil Gelaran (Set)");

        // //$sheet_panel->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHeight(30); 
        
        // $sheet_gabungan->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:P5");
        // $sheet_gabungan->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        // $sheet_gabungan->getActiveSheet()->getStyle('A1:P5')->getAlignment()->setWrapText(true); 


        // $kolom = 6;
        // $no = 1;
        // if ($query_gabungan->num_rows() > 0) {
        //     foreach ($query_gabungan->result() as $row) {

        //         $sheet_gabungan
        //             ->setActiveSheetIndex(6)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->i_product_wip)
        //             ->setCellValue("C" . $kolom, $row->e_product_wipname)
        //             ->setCellValue("D" . $kolom, $row->e_color_name)
        //             ->setCellValue("E" . $kolom, $row->e_style_name)
        //             ->setCellValue("F" . $kolom, $row->i_material)
        //             ->setCellValue("G" . $kolom, $row->e_material_name)
        //             ->setCellValue("H" . $kolom, $row->v_gelar)
        //             ->setCellValue("I" . $kolom, $row->v_set)
        //             ->setCellValue("J" . $kolom, $row->i_panel)
        //             ->setCellValue("K" . $kolom, $row->bagian)
        //             ->setCellValue("L" . $kolom, $row->n_qty_penyusun)
        //             ->setCellValue("M" . $kolom, $row->n_panjang_cm)
        //             ->setCellValue("N" . $kolom, $row->n_lebar_cm)
        //             ->setCellValue("O" . $kolom, $row->n_panjang_gelar)
        //             ->setCellValue("P" . $kolom, $row->n_hasil_gelar)

        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
               
        //         $kolom++;
        //         $no++;
        //     }
        // }
        // $sheet_gabungan
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A6".":P" . ($kolom-1)
        //             );

        // END OF GABUNGAN
       
        $writer = new Xls($spreadsheet);
        $nama_file = "Master_Data_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $nama_file . "");
        header("Cache-Control: max-age=0");
        ob_end_clean();
        ob_start();
        $writer->save("php://output");
        /* }else{
            echo "<center><h1> Tidak Ada Data :(</h1></center>";
        } */
    }

    public function export_2()
    {
        /* $data = check_role($this->i_menu, 6);
        if(!$data){
            redirect(base_url(),'refresh');
        } */

        $query          = $this->mmaster->get_dataheader();
        $idmaterial     = $this->mmaster->get_dataheader()->result_array();
        $idmaterial     = array_column($idmaterial,"id");
        $idmaterial     = implode("','",$idmaterial);
        $detail         = $this->mmaster->get_datamaterial($idmaterial);
        $bisbisan       = $this->mmaster->get_databisbisan($idmaterial);
        $spreadsheet = new Spreadsheet();
        $sharedStyle1 = new Style();
        $sharedStyle11 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStylex = new Style();

        $sheet_produk = $spreadsheet;
        $sheet1 = $spreadsheet;
        $sheet2 = $spreadsheet;
        $sheet3 = $spreadsheet;
        $sheet_redaksi = $spreadsheet;
        $sheet_panel = $spreadsheet;
        $sheet_gabungan = $spreadsheet;
        
        $sharedStyle1->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "DFF1D0"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle11->applyFromArray([
            "fill" => [
                "fillType" => Fill::FILL_SOLID,
                "color" => ["rgb" => "f7a19a"],
            ],
            "font" => [
                "name" => "Arial",
                "bold" => true,
                "italic" => false,
                "size" => 10,
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                "bottom" => ["borderStyle" => Border::BORDER_THIN],
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
        ]);

        $sharedStyle2->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                /* 'top'    => ['borderStyle' => Border::BORDER_THIN],
                 'bottom' => ['borderStyle' => Border::BORDER_THIN], */
                "left" => ["borderStyle" => Border::BORDER_THIN],
                "right" => ["borderStyle" => Border::BORDER_THIN],
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStylex->applyFromArray([
            "font" => [
                "name" => "Arial",
                "bold" => false,
                "italic" => false,
                "size" => 10,
            ],
            "borders" => [
                "top" => ["borderStyle" => Border::BORDER_THIN],
                /* 'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN] */
            ],
            "alignment" => [
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sharedStyle3->applyFromArray([
            "font" => [
                "name" => "Times New Roman",
                "bold" => true,
                "italic" => false,
                "size" => 12,
            ],
            "alignment" => [
                "horizontal" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                "vertical" =>
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);


        // START OF PRODUK

        // $query_produk = $this->mmaster->data_export();
        // $sheet_produk
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);
        // // foreach (range("A", "L") as $columnID) {
        // //     $sheet_produk
        // //         ->getActiveSheet()
        // //         ->getColumnDimension($columnID)
        // //         ->setAutoSize(true);
        // // }
        // $sheet_produk->getActiveSheet()->setTitle("Master Produk");
        // $sheet_produk->getActiveSheet()->mergeCells("A1:K3");
        // /* $sheet_produk->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:K3");
        // /* $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        // $sheet_produk
        //     ->setActiveSheetIndex(0)
        //     ->setCellValue("A1", "MASTER PRODUK")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "ID")
        //     ->setCellValue("C5", "Kode Barang")
        //     ->setCellValue("D5", "Nama Barang")
        //     ->setCellValue("E5", "Warna")
        //     ->setCellValue("F5", "Divisi")
        //     ->setCellValue("G5", "Kategori")
        //     ->setCellValue("H5", "Sub Kategori")
        //     ->setCellValue("I5", "Brand")
        //     ->setCellValue("J5", "Series")
        //     ->setCellValue("K5", "Kategori Penjualan")
        //     /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        // $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:K5");
        // /* $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        // $sheet_produk
        //     ->setActiveSheetIndex(0)
        //     ->setCellValue("K2", "KATEGORI PENJUALAN")
        //     ->setCellValue("K5", "ID Kategori")
        //     ->setCellValue("L5", "Nama Kategori");
        // $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        // $sheet_produk->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */

        

        // $kolom = 6;
        // $no = 1;
        // if ($query_produk->num_rows() > 0) {
        //     foreach ($query_produk->result() as $row) {
        //         $data = $this->db
        //             ->query(
        //                 "SELECT string_agg(e_class_name,', ') AS name FROM tr_class_product"
        //             )
        //             ->row()->name;
        //         $sheet = $sheet_produk->getActiveSheet();
        //         $validation = $sheet->getCell("K".$kolom)->getDataValidation();
        //         $validation
        //             ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
        //             ->setFormula1('"' . $data . '"')
        //             ->setAllowBlank(false)
        //             ->setShowDropDown(true)
        //             ->setShowInputMessage(true)
        //             ->setPromptTitle("Note")
        //             ->setPrompt("Must select one from the drop down options.")
        //             ->setShowErrorMessage(true)
        //             ->setErrorStyle(
        //                 \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
        //             )
        //             ->setErrorTitle("Invalid option")
        //             ->setError("Select one from the drop down list.");

        //         $sheet_produk
        //             ->setActiveSheetIndex(0)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->id)
        //             ->setCellValue("C" . $kolom, $row->i_product_base)
        //             ->setCellValue("D" . $kolom, $row->e_product_basename)
        //             ->setCellValue("E" . $kolom, $row->e_color_name)
        //             ->setCellValue("F" . $kolom, $row->e_nama_divisi)
        //             ->setCellValue("G" . $kolom, $row->e_nama_kelompok)
        //             ->setCellValue("H" . $kolom, $row->e_type_name)
        //             ->setCellValue("I" . $kolom, $row->brand)
        //             ->setCellValue("J" . $kolom, $row->series)
        //             ->setCellValue("K" . $kolom, $row->e_class_name)
        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
        //         $sheet_produk
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A" . $kolom . ":K" . $kolom
        //             );
        //         $kolom++;
        //         $no++;
        //     }
        // }

        // $sheet->setDataValidation("K6:K" . $kolom, $validation);
        // $sheet_produk
        //     ->getActiveSheet()
        //     ->duplicateStyle($sharedStylex, "A" . $kolom . ":K" . $kolom);
        // // END OF PRODUK

        // // ->getActiveSheet()
        // $sheet1
        // ->createSheet()
        // ->setTitle("Master Material & Spare part")
        // ->getStyle("B2")
        // ->getAlignment()
        // ->applyFromArray([
        //     "horizontal" =>
        //         \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //     "vertical" =>
        //         \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //     "textRotation" => 0,
        //     "wrapText" => true,
        // ]);

        // $sheet1
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);
        // // foreach (range("A", "J") as $columnID) {
        // //     $sheet1
        // //         ->getActiveSheet()
        // //         ->getColumnDimension($columnID)
        // //         ->setAutoSize(true);
        // // }
        // $sheet1->getActiveSheet()->mergeCells("A1:I3");
        // /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet1->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:I3");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        // $sheet1
        //     ->setActiveSheetIndex(1)
        //     ->setCellValue("A1", "MATERIAL & SPARE PART")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "Kode Barang")
        //     ->setCellValue("C5", "Nama Barang")
        //     ->setCellValue("D5", "Satuan")
        //     ->setCellValue("E5", "Sub Kategori")
        //     ->setCellValue("F5", "Kategori")
        //     ->setCellValue("G5", "Grup Barang")
        //     ->setCellValue("H5", "Supplier Utama")
        //     ->setCellValue("I5", "Tanggal Daftar")
        //     /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        // $sheet1->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:I5");

        // $kolom = 6;
        // $no = 1;
        // if ($query->num_rows() > 0) {
        //     foreach ($query->result() as $row) {

        //         $sheet1
        //             ->setActiveSheetIndex(1)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->i_material)
        //             ->setCellValue("C" . $kolom, $row->e_material_name)
        //             ->setCellValue("D" . $kolom, $row->e_satuan_name)
        //             ->setCellValue("E" . $kolom, $row->e_type_name)
        //             ->setCellValue("F" . $kolom, $row->e_nama_kelompok)
        //             ->setCellValue("G" . $kolom, $row->e_nama_group_barang)
        //             ->setCellValue("H" . $kolom, $row->e_supplier_name)
        //             ->setCellValue("I" . $kolom, $row->d_register)
        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
        //         $sheet1
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A" . $kolom . ":I" . $kolom
        //             );
        //         $kolom++;
        //         $no++;
        //     }
        // }

        // // check point sheet1

        // $sheet2
        //     ->createSheet()
        //     ->setTitle("Master Bisbisan")
        //     ->getStyle("B2")
        //     ->getAlignment()
        //     ->applyFromArray([
        //         "horizontal" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         "vertical" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         "textRotation" => 0,
        //         "wrapText" => true,
        //     ]);

        // $sheet2
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);
        // // foreach (range("A", "M") as $columnID) {
        // //     $sheet2
        // //         ->getActiveSheet()
        // //         ->getColumnDimension($columnID)
        // //         ->setAutoSize(true);
        // // }
        // $sheet2->getActiveSheet()->mergeCells("A1:J3");
        // /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet2->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:L3");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        // $sheet2
        //     ->setActiveSheetIndex(2)
        //     ->setCellValue("A1", "BISBISAN")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "Kode Barang")
        //     ->setCellValue("C5", "Nama Barang")
        //     ->setCellValue("D5", "Ukuran Bisbisan")
        //     ->setCellValue("E5", "Lebar Kain")
        //     ->setCellValue("F5", "Jenis Potong")
        //     ->setCellValue("G5", "% Hilang Lebar Kain")
        //     ->setCellValue("H5", "Lebar Kain Jadi")
        //     ->setCellValue("I5", "Jml Roll")
        //     ->setCellValue("J5", "% Tambah Panjang Kain")
        //     ->setCellValue("K5", "Panjang Bisbisan")
        //     ->setCellValue("L5", "Panjang Bisbisan per 1m")
        //     /* ->setCellValue("I5", "ID Kategori Penjualan") */;
        // $sheet2->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:L5");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "I5");
        // $spreadsheet
        //     ->setActiveSheetIndex(0)
        //     ->setCellValue("K2", "KATEGORI PENJUALAN")
        //     ->setCellValue("K5", "ID Kategori")
        //     ->setCellValue("L5", "Nama Kategori");
        // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "L5");
        // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle11, "K5"); */

        

        // $kolom = 6;
        // $no = 1;
        // if ($bisbisan->num_rows() > 0) {
        //     foreach ($bisbisan->result() as $row) {

        //         $sheet2
        //             ->setActiveSheetIndex(2)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->i_material)
        //             ->setCellValue("C" . $kolom, $row->e_material_name)
        //             ->setCellValue("D" . $kolom, $row->n_bisbisan)
        //             ->setCellValue("E" . $kolom, $row->v_lebar_kain_awal)
        //             ->setCellValue("F" . $kolom, $row->e_jenis_potong)
        //             ->setCellValue("G" . $kolom, $row->n_hilang_lebar)
        //             ->setCellValue("H" . $kolom, $row->v_lebar_kain_akhir)
        //             ->setCellValue("I" . $kolom, number_format($row->v_jumlah_roll,4))
        //             ->setCellValue("J" . $kolom, $row->n_tambah_panjang)
        //             ->setCellValue("K" . $kolom, $row->n_panjang_bis)
        //             ->setCellValue("L" . $kolom, number_format($row->v_panjang_bis,4))
        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
        //         $sheet2
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A" . $kolom . ":L" . $kolom
        //             );
        //         $kolom++;
        //         $no++;
        //     }
        // }

        // // check point sheet2

        // $sheet3
        //     ->createSheet()
        //     ->setTitle("Konversi Material")
        //     ->getStyle("B2")
        //     ->getAlignment()
        //     ->applyFromArray([
        //         "horizontal" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         "vertical" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         "textRotation" => 0,
        //         "wrapText" => true,
        //     ]);

        // $sheet3
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);
        // // foreach (range("A", "G") as $columnID) {
        // //     $sheet3
        // //         ->getActiveSheet()
        // //         ->getColumnDimension($columnID)
        // //         ->setAutoSize(true);
        // // }
        // $sheet3->getActiveSheet()->mergeCells("A1:H3");
        // /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet3->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:H3");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        // $sheet3
        //     ->setActiveSheetIndex(3)
        //     ->setCellValue("A1", "KONVERSI MATERIAL")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "Kode Barang")
        //     ->setCellValue("C5", "Nama Barang")
        //     ->setCellValue("D5", "Satuan Produksi")
        //     ->setCellValue("E5", "Operator")
        //     ->setCellValue("F5", "Faktor")
        //     ->setCellValue("G5", "Satuan Pembelian")
        //     ->setCellValue("H5", "Di Pakai Pembelian");
        // $sheet3->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:H5");

        // $kolom = 6;
        // $no = 1;
        // if ($detail->num_rows() > 0) {
        //     foreach ($detail->result() as $row) {

        //         $sheet3
        //             ->setActiveSheetIndex(3)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->i_material)
        //             ->setCellValue("C" . $kolom, $row->e_material_name)
        //             ->setCellValue("D" . $kolom, $row->e_satuan_name)
        //             ->setCellValue("E" . $kolom, $row->e_operator)
        //             ->setCellValue("F" . $kolom, number_format($row->n_faktor,4))
        //             ->setCellValue("G" . $kolom, $row->e_satuan_name_konversi)
        //             ->setCellValue("H" . $kolom, $row->f_default)
        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
        //         $sheet3
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A" . $kolom . ":H" . $kolom
        //             );
        //         $kolom++;
        //         $no++;
        //     }
        // }

        // // START OF REDAKSI
        // $query_redaksi = $this->mmaster->data_export_redaksi();
        // $sheet_redaksi
        //     ->createSheet()
        //     ->setTitle("Marker")
        //     ->getStyle("B2")
        //     ->getAlignment()
        //     ->applyFromArray([
        //         "horizontal" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         "vertical" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         "textRotation" => 0,
        //         "wrapText" => true,
        //     ]);

        // $sheet_redaksi
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);
        // // foreach (range("A", "O") as $columnID) {
        // //     $sheet_redaksi
        // //         ->getActiveSheet()
        // //         ->getColumnDimension($columnID)
        // //         ->setAutoSize(true);
        // // }
        // $sheet_redaksi->getActiveSheet()->mergeCells("A1:S3");
        // /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet_redaksi->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:S3");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */
        // $sheet_redaksi
        //     ->setActiveSheetIndex(4)
        //     ->setCellValue("A1", "MARKER")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "Kode Produk")
        //     ->setCellValue("C5", "Nama Produk")
        //     ->setCellValue("D5", "Warna")
        //     ->setCellValue("E5", "Kode Material")
        //     ->setCellValue("F5", "Nama Material")
        //     ->setCellValue("G5", "Group")
        //     ->setCellValue("H5", "Bagian")
        //     ->setCellValue("I5", "Gelar")
        //     ->setCellValue("J5", "Set")
        //     ->setCellValue("K5", "Ukuran Bisbisan")
        //     ->setCellValue("L5", "Kebutuhan Bisbisan (Per Satuan Bisbisan)")
        //     ->setCellValue("M5", "Jenis Potong")
        //     ->setCellValue("N5", "Lebar Kain Awal")
        //     ->setCellValue("O5", "Kebutuhan Bisbisan (Per 1 Meter Bahan)")
        //     ->setCellValue("P5", "Kebutuhan Bisbisan (Per 1 PCS Produk)")
        //     ->setCellValue("Q5", "Jasa Proses")
        //     ->setCellValue("R5", "Kebutuhan Dalam 1 PCS Produk")
        //     ->setCellValue("S5", "Satuan");

        // //$sheet_redaksi->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHeight(30); 
        
        // $sheet_redaksi->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:S5");
        // $sheet_redaksi->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        // $sheet_redaksi->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setWrapText(true); 

        // $kolom = 6;
        // $no = 1;
        // if ($query_redaksi->num_rows() > 0) {
        //     foreach ($query_redaksi->result() as $row) {

        //         $sheet_redaksi
        //             ->setActiveSheetIndex(4)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->i_product_wip)
        //             ->setCellValue("C" . $kolom, $row->e_product_wipname)
        //             ->setCellValue("D" . $kolom, $row->e_color_name)
        //             ->setCellValue("E" . $kolom, $row->i_material)
        //             ->setCellValue("F" . $kolom, $row->e_material_name)
        //             ->setCellValue("G" . $kolom, $row->e_nama_group_barang)
        //             ->setCellValue("H" . $kolom, $row->e_bagian)
        //             ->setCellValue("I" . $kolom, $row->v_gelar)
        //             ->setCellValue("J" . $kolom, $row->v_set)
        //             ->setCellValue("K" . $kolom, $row->n_bisbisan)
        //             ->setCellValue("L" . $kolom, $row->v_bisbisan)
        //             ->setCellValue("M" . $kolom, $row->jenis_potong)
        //             ->setCellValue("N" . $kolom, $row->v_lebar_kain_awal)
        //             ->setCellValue("O" . $kolom, $row->v_panjang_bis_bahan)
        //             ->setCellValue("P" . $kolom, $row->v_panjang_bis_produk)
        //             ->setCellValue("Q" . $kolom, $row->e_type_makloon_name)
        //             ->setCellValue("R" . $kolom, $row->total)
        //             ->setCellValue("S" . $kolom, $row->e_satuan_name)

        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
        //         $sheet_redaksi
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A" . $kolom . ":S" . $kolom
        //             );
        //         $kolom++;
        //         $no++;
        //     }
        // }
        // // END OF REDAKSI



        // // START OF PANEL
        // $query_panel = $this->mmaster->data_export_panel();
        // $sheet_panel
        //     ->createSheet()
        //     ->setTitle("Panel")
        //     ->getStyle("B2")
        //     ->getAlignment()
        //     ->applyFromArray([
        //         "horizontal" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         "vertical" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         "textRotation" => 0,
        //         "wrapText" => true,
        //     ]);

        // $sheet_panel
        //     ->getDefaultStyle()
        //     ->getFont()
        //     ->setName("Calibri")
        //     ->setSize(9);

        // $sheet_panel->getActiveSheet()->mergeCells("A1:R3");
        // /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        // $sheet_panel->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:R3");
        // /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */

        // $sheet_panel
        //     ->setActiveSheetIndex(5)
        //     ->setCellValue("A1", "PANEL")
        //     ->setCellValue("A5", "No")
        //     ->setCellValue("B5", "Kode Produk")
        //     ->setCellValue("C5", "Nama Produk")
        //     ->setCellValue("D5", "Warna")
        //     ->setCellValue("E5", "Series")
        //     ->setCellValue("F5", "Kode Material")
        //     ->setCellValue("G5", "Nama Material")
        //     ->setCellValue("H5", "Kode Panel")
        //     ->setCellValue("I5", "Bagian")
        //     ->setCellValue("J5", "Qty Penyusun")
        //     ->setCellValue("K5", "Panjang (CM)")
        //     ->setCellValue("L5", "Lebar (CM)")
        //     ->setCellValue("M5", "Panjang Gelaran (CM)")
        //     ->setCellValue("N5", "Lebar Gelaran (CM)")
        //     ->setCellValue("O5", "Hasil Gelaran (Set)")
        //     ->setCellValue("P5", "Efficiency Marker")
        //     ->setCellValue("Q5", "Print")
        //     ->setCellValue("R5", "Bordir");

        // //$sheet_panel->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHeight(30); 
        
        // $sheet_panel->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:R5");
        // $sheet_panel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        // $sheet_panel->getActiveSheet()->getStyle('A1:R5')->getAlignment()->setWrapText(true); 


        // $kolom = 6;
        // $no = 1;
        // if ($query_panel->num_rows() > 0) {
        //     foreach ($query_panel->result() as $row) {

        //         $sheet_panel
        //             ->setActiveSheetIndex(5)
        //             ->setCellValue("A" . $kolom, $no)
        //             ->setCellValue("B" . $kolom, $row->i_product_wip)
        //             ->setCellValue("C" . $kolom, $row->e_product_wipname)
        //             ->setCellValue("D" . $kolom, $row->e_color_name)
        //             ->setCellValue("E" . $kolom, $row->e_series_name)
        //             ->setCellValue("F" . $kolom, $row->i_material)
        //             ->setCellValue("G" . $kolom, $row->e_material_name)
        //             ->setCellValue("H" . $kolom, $row->i_panel)
        //             ->setCellValue("I" . $kolom, $row->bagian)
        //             ->setCellValue("J" . $kolom, $row->n_qty_penyusun)
        //             ->setCellValue("K" . $kolom, $row->n_panjang_cm)
        //             ->setCellValue("L" . $kolom, $row->n_lebar_cm)
        //             ->setCellValue("M" . $kolom, $row->n_panjang_gelar)
        //             ->setCellValue("N" . $kolom, $row->n_lebar_gelar)
        //             ->setCellValue("O" . $kolom, $row->n_hasil_gelar)
        //             ->setCellValue("P" . $kolom, $row->n_efficiency)
        //             ->setCellValue("Q" . $kolom, $row->print)
        //             ->setCellValue("R" . $kolom, $row->bordir)

        //             /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
        //         $sheet_panel
        //             ->getActiveSheet()
        //             ->duplicateStyle(
        //                 $sharedStyle2,
        //                 "A" . $kolom . ":R" . $kolom
        //             );
        //         $kolom++;
        //         $no++;
        //     }
        // }

        // END OF PANEL



        // START OF Gabungan
        $query_gabungan = $this->mmaster->data_export_gabungan();
        $sheet_produk->getActiveSheet()->setTitle("Gabungan");
        // $sheet_gabungan
        //     ->createSheet()
        //     ->setTitle("Gabungan")
        //     ->getStyle("B2")
        //     ->getAlignment()
        //     ->applyFromArray([
        //         "horizontal" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         "vertical" =>
        //             \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         "textRotation" => 0,
        //         "wrapText" => true,
        //     ]);

        $sheet_gabungan
            ->getDefaultStyle()
            ->getFont()
            ->setName("Calibri")
            ->setSize(9);

        $sheet_gabungan->getActiveSheet()->mergeCells("A1:P3");
        /* $spreadsheet->getActiveSheet()->mergeCells("K2:L3"); */
        $sheet_gabungan->getActiveSheet()->duplicateStyle($sharedStyle3, "A1:P3");
        /* $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "K2:L3"); */

        $sheet_gabungan
            ->setActiveSheetIndex(0)
            ->setCellValue("A1", "GABUNGAN MARKER & PANEL")
            ->setCellValue("A5", "No")
            ->setCellValue("B5", "Kode Produk")
            ->setCellValue("C5", "Nama Produk")
            ->setCellValue("D5", "Warna")
            ->setCellValue("E5", "Series")
            ->setCellValue("F5", "Kode Material")
            ->setCellValue("G5", "Nama Material")
            ->setCellValue("H5", "Gelar (CM)")
            ->setCellValue("I5", "Set")
            ->setCellValue("J5", "Kode Panel")
            ->setCellValue("K5", "Bagian")
            ->setCellValue("L5", "QTY Penyusun")
            ->setCellValue("M5", "Panjang (CM)")
            ->setCellValue("N5", "Lebar (CM)")
            ->setCellValue("O5", "Panjang Gelaran (CM)")
            ->setCellValue("P5", "Hasil Gelaran (Set)");

        //$sheet_panel->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setHeight(30); 
        
        $sheet_gabungan->getActiveSheet()->duplicateStyle($sharedStyle1, "A5:P5");
        $sheet_gabungan->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        $sheet_gabungan->getActiveSheet()->getStyle('A1:P5')->getAlignment()->setWrapText(true); 


        $kolom = 6;
        $no = 1;
        if ($query_gabungan->num_rows() > 0) {
            foreach ($query_gabungan->result() as $row) {

                $sheet_gabungan
                    ->setActiveSheetIndex(0)
                    ->setCellValue("A" . $kolom, $no)
                    ->setCellValue("B" . $kolom, $row->i_product_wip)
                    ->setCellValue("C" . $kolom, $row->e_product_wipname)
                    ->setCellValue("D" . $kolom, $row->e_color_name)
                    ->setCellValue("E" . $kolom, $row->e_style_name)
                    ->setCellValue("F" . $kolom, $row->i_material)
                    ->setCellValue("G" . $kolom, $row->e_material_name)
                    ->setCellValue("H" . $kolom, $row->v_gelar)
                    ->setCellValue("I" . $kolom, $row->v_set)
                    ->setCellValue("J" . $kolom, $row->i_panel)
                    ->setCellValue("K" . $kolom, $row->bagian)
                    ->setCellValue("L" . $kolom, $row->n_qty_penyusun)
                    ->setCellValue("M" . $kolom, $row->n_panjang_cm)
                    ->setCellValue("N" . $kolom, $row->n_lebar_cm)
                    ->setCellValue("O" . $kolom, $row->n_panjang_gelar)
                    ->setCellValue("P" . $kolom, $row->n_hasil_gelar)

                    /* ->setCellValue("I" . $kolom, $row->id_class_product) */;
               
                $kolom++;
                $no++;
            }
        }
        $sheet_gabungan
                    ->getActiveSheet()
                    ->duplicateStyle(
                        $sharedStyle2,
                        "A6".":P" . ($kolom-1)
                    );

        // END OF GABUNGAN
       
        $writer = new Xls($spreadsheet);
        $nama_file = "Master_Data_Gabungan_" . date('Ymd_His') . ".xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;filename=" . $nama_file . "");
        header("Cache-Control: max-age=0");
        ob_end_clean();
        ob_start();
        $writer->save("php://output");
        /* }else{
            echo "<center><h1> Tidak Ada Data :(</h1></center>";
        } */
    }


}
/* End of file Cform.php */
