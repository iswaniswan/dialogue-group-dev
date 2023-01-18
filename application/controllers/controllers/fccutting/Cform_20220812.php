<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
/* use PhpOffice\PhpSpreadsheet\Style\Fill; */
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment; */
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '2090100';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama / Judul Menu  ----------*/
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/
        $this->load->model($this->global['folder'] . '/mmaster');

        /*----------  Load Librabry  ----------*/
        $this->load->library('fungsi');

        /* var_dump($this->uri->segment(1)); */
    }

    /*----------  DEFAULT CONTROLLERS  ----------*/

    public function index()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-01-' . date('Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  DAFTAR DATA  ----------*/

    public function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    /*----------  REDIRECT LIST REFERENSI  ----------*/

    public function indexx()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlistreferensi', $data);
    }

    /*----------  DAFTAR DATA REFERENSI  ----------*/

    public function datareferensi()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->datareferensi($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    /*----------  PROSES DATA  ----------*/

    public function prosesdata()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $tahun = $this->uri->segment(7);
        $bulan = $this->uri->segment(8);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'tahun'      => $tahun,
            'bulan'      => $bulan,
            'bagian'     => $this->mmaster->bagian()->result(),
            'datadetail' => $this->mmaster->datadetail($tahun,$bulan)->result(),
            // 'datadetail' => $this->mmaster->datadetail($this->uri->segment(4))->result(),
            // 'datadetaill'=> $this->mmaster->datadetaill($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->datadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-" . date('ym') . "-123456",
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode()
    {
        if ($this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE))->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument          = $this->input->post('idocument', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $iperiode           = $this->input->post('iperiode', TRUE);

        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     != "") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);

        if ($idocument != '' && $ddocument != '' && $ibagian != '' && $idreferensi != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                /** Simpan Data Header */
                $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $iperiode, $eremark);

                /** Simpan Data Detail Yang Barang Jadi */
                // for ($i = 1; $i <= $jml; $i++) {
                //     $id_product_wip                 = $this->input->post('id_product_wip' . $i, TRUE);
                //     $n_fc_perhitungan               = str_replace(",", "", $this->input->post('fc_produksi' . $i, TRUE));
                //     //$n_schedule_jahit               = str_replace(",", "", $this->input->post('schedule_jahit' . $i, TRUE));
                //     $n_schedule_jahit               = NULL;
                //     //$n_bahan_baku                   = str_replace(",", "", $this->input->post('bahan_baku' . $i, TRUE));
                //     $n_bahan_baku                   = NULL;
                //     //$n_sisa_schedule                = str_replace(",", "", $this->input->post('sisa_scedule' . $i, TRUE));
                //     $n_sisa_schedule                = NULL;
                //     //$n_stock_pengadaan              = str_replace(",", "", $this->input->post('n_stock_pengadaan' . $i, TRUE));
                //     $n_stock_pengadaan              = NULL;
                //     $n_stock_pengesetan             = str_replace(",", "", $this->input->post('n_stock_pengesetan' . $i, TRUE));
                //     //$n_pendingan_permintaan_cutting = str_replace(",", "", $this->input->post('pendingan_bulan_sebelumnya' . $i, TRUE));
                //     $n_pendingan_permintaan_cutting = NULL;
                //     //$n_kondisi_stock                = str_replace(",", "", $this->input->post('kondisi_stock' . $i, TRUE));
                //     $n_kondisi_stock                = NULL;
                //     $n_permintaan_cutting           = str_replace(",", "", $this->input->post('permintaan_cutting' . $i, TRUE));
                //     //$n_up_cutting                   = str_replace(",", "", $this->input->post('up_cutting' . $i, TRUE));
                //     $n_up_cutting                   = NULL;
                //     $n_fc_cutting                   = str_replace(",", "", $this->input->post('fc_cutting' . $i, TRUE));
                //     $e_remark                       = $this->input->post('remark' . $i, TRUE);
                //     if ($n_fc_perhitungan > 0 && ($id_product_wip != null || $id_product_wip != '')) {
                //         $this->mmaster->insertdetailbase($id, $id_product_wip, $n_fc_perhitungan, $n_schedule_jahit, $n_bahan_baku, $n_sisa_schedule, $n_stock_pengadaan, $n_stock_pengesetan, $n_pendingan_permintaan_cutting, $n_kondisi_stock, $n_permintaan_cutting, $n_up_cutting, $n_fc_cutting, $e_remark);
                //     }
                // }

                /** Simpan Data Detail */
                for ($i = 1; $i <= $jml; $i++) {
                    $id_product_wip = $this->input->post('id_product_wip' . $i, TRUE);
                    $n_sisa_schedule_berjalan = str_replace(",", "", $this->input->post('n_sisa_schedule_berjalan' . $i, TRUE));
                    $n_schedule_jahit = str_replace(",", "", $this->input->post('n_schedule_jahit' . $i, TRUE));
                    $n_stb_pengadaan = str_replace(",", "", $this->input->post('n_stb_pengadaan' . $i, TRUE));
                    $n_stock_pengadaan = str_replace(",", "", $this->input->post('n_stock_pengadaan' . $i, TRUE));
                    $n_stock_pengesetan = str_replace(",", "", $this->input->post('n_stock_pengesetan' . $i, TRUE));
                    $n_sisa_permintaan_cutting = str_replace(",", "", $this->input->post('n_sisa_permintaan_cutting' . $i, TRUE));
                    $n_kondisi_stock = str_replace(",", "", $this->input->post('n_kondisi_stock' . $i, TRUE));
                    $n_fc_produksi_perhitungan = str_replace(",", "", $this->input->post('n_fc_produksi_perhitungan' . $i, TRUE));
                    $n_up_cutting = str_replace(",", "", $this->input->post('n_up_cutting' . $i, TRUE));
                    $n_fc_cutting = str_replace(",", "", $this->input->post('n_fc_cutting' . $i, TRUE));
                    $e_remark = $this->input->post('remark' . $i, TRUE);
                    if ($n_fc_cutting > 0 && ($id_product_wip != null || $id_product_wip != '')) {
                        $this->mmaster->insert_detail($id,$id_product_wip,$n_sisa_schedule_berjalan,$n_schedule_jahit,$n_stb_pengadaan,$n_stock_pengadaan,$n_stock_pengesetan,$n_sisa_permintaan_cutting,
                        $n_kondisi_stock,$n_fc_produksi_perhitungan,$n_up_cutting,$n_fc_cutting,$e_remark);
                    }
                }

                // /** Simpan Data Detail Yang Material */
                // for ($i = 1; $i <= $jml_item; $i++) {
                //     $id_material_item       = $this->input->post('id_material_item'.$i, TRUE);
                //     $i_satuan_konversi      = $this->input->post('i_satuan_konversi'.$i, TRUE);
                //     $nilai_kebutuhan_item   = str_replace(",","",$this->input->post('nilai_kebutuhan_item'.$i, TRUE));
                //     $nilai_mutasi           = str_replace(",","",$this->input->post('nilai_mutasi'.$i, TRUE));
                //     $nilai_budgeting        = str_replace(",","",$this->input->post('nilai_budgeting'.$i, TRUE));
                //     $nilai_estimasi         = str_replace(",","",$this->input->post('nilai_estimasi'.$i, TRUE));
                //     $nilai_op_sisa          = str_replace(",","",$this->input->post('nilai_op_sisa'.$i, TRUE));
                //     $ket                    = $this->input->post('ket'.$i, TRUE);
                //     $up                     = str_replace(",","",$this->input->post('up'.$i, TRUE));
                //     if ($nilai_budgeting > 0 && ($id_material_item!=null || $id_material_item!='')) {
                //         $this->mmaster->insertdetailmaterial($id,$idreferensi,$id_material_item,$nilai_kebutuhan_item,$nilai_mutasi,$nilai_budgeting,$i_satuan_konversi,$up,$ket,$nilai_estimasi,$nilai_op_sisa);
                //     }
                // }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
                }
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

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
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4),$this->uri->segment(7))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id                 = $this->input->post('id', TRUE);
        $idocumentold       = $this->input->post('idocumentold', TRUE);
        $ibagianold         = $this->input->post('ibagianold', TRUE);

        $idocument          = $this->input->post('idocument', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $iperiode           = $this->input->post('iperiode', TRUE);

        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     != "") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);


        if ($id != '' && $idocument != '' && $ddocument != '' && $ibagian != '' && $idreferensi != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument, $ibagian, $idocumentold, $ibagianold);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                /** Update Header */
                $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $iperiode, $eremark);

                /** Delete Item Sebelum Insert */
                //$this->mmaster->delete($id);

                /** Simpan Data Detail Yang Barang Jadi */
                /* for ($i = 1; $i <= $jml; $i++) {
                    $id_product_wip                 = $this->input->post('id_product_wip' . $i, TRUE);
                    $n_fc_perhitungan               = str_replace(",", "", $this->input->post('fc_produksi' . $i, TRUE));
                    $n_schedule_jahit               = str_replace(",", "", $this->input->post('schedule_jahit' . $i, TRUE));
                    $n_bahan_baku                   = str_replace(",", "", $this->input->post('bahan_baku' . $i, TRUE));
                    $n_sisa_schedule                = str_replace(",", "", $this->input->post('sisa_scedule' . $i, TRUE));
                    $n_stock_pengadaan              = str_replace(",", "", $this->input->post('n_stock_pengadaan' . $i, TRUE));
                    $n_stock_pengesetan             = str_replace(",", "", $this->input->post('n_stock_pengesetan' . $i, TRUE));
                    $n_pendingan_permintaan_cutting = str_replace(",", "", $this->input->post('pendingan_bulan_sebelumnya' . $i, TRUE));
                    $n_kondisi_stock                = str_replace(",", "", $this->input->post('kondisi_stock' . $i, TRUE));
                    $n_permintaan_cutting           = str_replace(",", "", $this->input->post('permintaan_cutting' . $i, TRUE));
                    $n_up_cutting                   = str_replace(",", "", $this->input->post('up_cutting' . $i, TRUE));
                    $n_fc_cutting                   = str_replace(",", "", $this->input->post('fc_cutting' . $i, TRUE));
                    $e_remark                       = $this->input->post('remark' . $i, TRUE);
                    if ($n_fc_perhitungan > 0 && ($id_product_wip != null || $id_product_wip != '')) {
                        $this->mmaster->updatedetailbase($id, $id_product_wip, $n_fc_perhitungan, $n_schedule_jahit, $n_bahan_baku, $n_sisa_schedule, $n_stock_pengadaan, $n_stock_pengesetan, $n_pendingan_permintaan_cutting, $n_kondisi_stock, $n_permintaan_cutting, $n_up_cutting, $n_fc_cutting, $e_remark);
                    }
                } */

                for ($i = 1; $i <= $jml; $i++) {
                    $id_product_wip = $this->input->post('id_product_wip' . $i, TRUE);
                    $n_sisa_schedule_berjalan = str_replace(",", "", $this->input->post('n_sisa_schedule_berjalan' . $i, TRUE));
                    $n_schedule_jahit = str_replace(",", "", $this->input->post('n_schedule_jahit' . $i, TRUE));
                    $n_stb_pengadaan = str_replace(",", "", $this->input->post('n_stb_pengadaan' . $i, TRUE));
                    $n_stock_pengadaan = str_replace(",", "", $this->input->post('n_stock_pengadaan' . $i, TRUE));
                    $n_stock_pengesetan = str_replace(",", "", $this->input->post('n_stock_pengesetan' . $i, TRUE));
                    $n_sisa_permintaan_cutting = str_replace(",", "", $this->input->post('n_sisa_permintaan_cutting' . $i, TRUE));
                    $n_kondisi_stock = str_replace(",", "", $this->input->post('n_kondisi_stock' . $i, TRUE));
                    $n_fc_produksi_perhitungan = str_replace(",", "", $this->input->post('n_fc_produksi_perhitungan' . $i, TRUE));
                    $n_up_cutting = str_replace(",", "", $this->input->post('n_up_cutting' . $i, TRUE));
                    $n_fc_cutting = str_replace(",", "", $this->input->post('n_fc_cutting' . $i, TRUE));
                    $e_remark = $this->input->post('remark' . $i, TRUE);
                    if ($n_fc_cutting > 0 && ($id_product_wip != null || $id_product_wip != '')) {
                        $this->mmaster->update_detail($id,$id_product_wip,$n_sisa_schedule_berjalan,$n_schedule_jahit,$n_stb_pengadaan,$n_stock_pengadaan,$n_stock_pengesetan,$n_sisa_permintaan_cutting,
                        $n_kondisi_stock,$n_fc_produksi_perhitungan,$n_up_cutting,$n_fc_cutting,$e_remark);
                    }
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
                }
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            // 'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4),$this->uri->segment(7))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            // 'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4),$this->uri->segment(7))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }
    
    public function export_excel()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);
        $id         = $this->input->post("id", true);
        $periode    = $this->input->post("periode", true);
        if ($id == "") $id = $this->uri->segment(4);
        if ($dfrom == "") $dfrom = $this->uri->segment(5);
        if ($dto == "") $dto = $this->uri->segment(6);
        if ($periode == "") $periode = $this->uri->segment(7);

        // $query = $this->mmaster->datadetail($this->company, $tahun . $bulan, $id);
        $query = $this->mmaster->edititembase($id, $periode);

        if ($query->num_rows()>0) {

            $spreadsheet = new Spreadsheet;
            $title = new Style();
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
                ]
            );

            $sharedStyle1->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10,
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                ]
            );

            $title->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => true,
                        'italic' => false,
                        'size'  => 12
                    ],
                ]
            );

            $sharedStyle2->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]
            );


            $sharedStyle3->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]
            );
            $spreadsheet->getDefaultStyle()
                ->getFont()
                ->setName('Calibri')
                ->setSize(9);
            /* foreach (range('A', 'R') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            } */
            
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Permintaan Cutting');
            $spreadsheet->getActiveSheet()->duplicateStyle($title, 'A1');
            $spreadsheet->getActiveSheet()->setTitle('Permintaan Cutting');
            $spreadsheet->getActiveSheet()->mergeCells("A1:O1");
            $spreadsheet->setActiveSheetIndex(0)

                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Kode Barang')
                ->setCellValue('C2', 'Nama Barang')
                ->setCellValue('D2', 'Warna')
                ->setCellValue('E2', 'Brand')
                ->setCellValue('F2', 'Series')
                ->setCellValue('G2', 'Sisa Schedule Berjalan')
                ->setCellValue('H2', 'Stock Pengadaan')
                ->setCellValue('I2', 'Stock Pengesetan')
                ->setCellValue('J2', 'Sisa Permintaan Cutting')
                ->setCellValue('K2', 'Kondisi Stock Persiapan Cutting')
                ->setCellValue('L2', 'FC Produksi yang Diperhitungkan')
                ->setCellValue('M2', 'Up Qty')
                ->setCellValue('N2', 'FC Cutting')
                ->setCellValue('O2', 'Keterangan');

            $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(40);
            foreach (range('G2', 'K2') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setWidth(15);
            }
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(40);
            foreach (range('A', 'F') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:O2');
            /* $spreadsheet->getActiveSheet()->getStyle('A2:R2')->getActiveSheet()->getHighestRow()->getAlignment()->setWrapText(true); */
            $spreadsheet->getActiveSheet()->getStyle('A2:O2')->getAlignment()->setWrapText(true); 
            $spreadsheet->getActiveSheet()->getStyle('G2:I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCFFFF');
            $spreadsheet->getActiveSheet()->getStyle('L2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CCFFFF');
            $spreadsheet->getActiveSheet()->getStyle('K2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('FFE5CC');
            $spreadsheet->getActiveSheet()->getStyle('J2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E5FFCC');
            $spreadsheet->getActiveSheet()->getStyle('M2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E5FFCC');
            $spreadsheet->getActiveSheet()->getStyle('O2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E5FFCC');
            $spreadsheet->getActiveSheet()->getStyle('N2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('404040');
            $spreadsheet->getActiveSheet()->getStyle('N2')->getFont()->getColor()->setRGB ('FFFFFF');

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_product_wip))
                    ->setCellValue('C' . $kolom, removeEmoji($row->e_product_wipname))
                    ->setCellValue('D' . $kolom, removeEmoji($row->e_color_name))
                    ->setCellValue('E' . $kolom, removeEmoji($row->e_brand_name))
                    ->setCellValue('F' . $kolom, removeEmoji($row->e_style_name))
                    ->setCellValue('G' . $kolom, $row->n_sisa_schedule_berjalan)
                    ->setCellValue('H' . $kolom, $row->n_stock_pengadaan)
                    ->setCellValue('I' . $kolom, $row->n_stock_pengesetan)
                    ->setCellValue('J' . $kolom, $row->n_sisa_permintaan_cutting)
                    ->setCellValue('K' . $kolom, $row->n_kondisi_stock)
                    ->setCellValue('L' . $kolom, $row->n_fc_produksi_perhitungan)
                    ->setCellValue('M' . $kolom, $row->n_up_cutting)
                    ->setCellValue('N' . $kolom, $row->n_fc_cutting)
                    ->setCellValue('O' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':O' . $kolom);

                $kolom++;
                $nomor++;
            }
            $writer = new Xls($spreadsheet);
            $nama_file = "Permintaan_Cutting.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }else{
            echo "<center><h1>Tidak Ada Data :)</h1></center>";
        }
    }
}
/* End of file Cform.php */