<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global  = array();
    public $i_menu  = '2090113';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->company = $this->session->id_company;

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];


        $this->load->model($this->global['folder'] . '/mmaster');
    }

    /*----------  DEFAULT CONTROLLERS  ----------*/

    public function index()
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

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/

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
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  MEMBUKA FORM TAMBAH DATA  ----------*/

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "BBM-" . date('ym') . "-1234",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('i_bagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK NO DOKUMEN  ----------*/

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  DATA REFERENSI SESUAI SESUAI PENGIRIM  ----------*/

    public function referensi()
    {
        $filter = [];
        $data = $this->mmaster->data_referensi(str_replace("'", "", $this->input->get('q')), $this->input->get('i_bagian'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => $key->i_document
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data."
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL ITEM REFERENSI  ----------*/

    public function detail_referensi()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detail_referensi($this->input->post('id', TRUE), $this->input->post('i_bagian'))->result_array()
        );
        echo json_encode($query);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_document    = $this->input->post('i_document', TRUE);
        $d_document    = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian      = $this->input->post('i_bagian', TRUE);
        $i_referensi   = $this->input->post('i_referensi', TRUE);
        $e_remark      = $this->input->post('e_remark', TRUE);
        $jml           = $this->input->post('jml', TRUE);
        $id            = $this->mmaster->runningid();
        if ($id != '' && $i_document != '' && $d_document != '' && $i_referensi != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->simpan($id, $i_document, $d_document, $i_bagian, $i_referensi, $e_remark);
            for ($i = 0; $i < $jml; $i++) {
                $id_material = $this->input->post('id_material' . $i, TRUE);
                $id_product_wip = $this->input->post('id_product_wip' . $i, TRUE);
                $n_quantity = str_replace(",", "", $this->input->post('n_quantity' . $i, TRUE));
                $n_quantity_gelar = str_replace(",", "", $this->input->post('n_quantity_gelar' . $i, TRUE));
                $v_gelar = str_replace(",", "", $this->input->post('v_gelar' . $i, TRUE));
                $v_set = str_replace(",", "", $this->input->post('v_set' . $i, TRUE));
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                $check = $this->input->post('check' . $i, TRUE);
                $id_item_keluar = $this->input->post('id_item_keluar' . $i, TRUE);
                
                if (($id_material != '' || $id_material != null) && $n_quantity > 0 && $check == 'on') {
                    $this->mmaster->simpandetail($id, $id_material, $id_product_wip, $n_quantity, $e_remark_item, $n_quantity_gelar, $v_gelar, $v_set, $id_item_keluar);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $i_document,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'kode'   => $i_document,
                    'sukses' => true,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
        } else {
            $data = array(
                'kode'      => $i_document,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'number'     => "BBM-" . date('ym') . "-1234",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
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

        $id            = $this->input->post('id', TRUE);
        $i_document    = $this->input->post('i_document', TRUE);
        $d_document    = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian      = $this->input->post('i_bagian', TRUE);
        $i_referensi   = $this->input->post('i_referensi', TRUE);
        $e_remark      = $this->input->post('e_remark', TRUE);
        $jml           = $this->input->post('jml', TRUE);
        if ($id != '' && $i_document != '' && $d_document != '' && $i_referensi != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id, $i_document, $d_document, $i_bagian, $i_referensi, $e_remark);
            $this->mmaster->delete($id);
            for ($i = 0; $i < $jml; $i++) {
                $id_material = $this->input->post('id_material' . $i, TRUE);
                $id_product_wip = $this->input->post('id_product_wip' . $i, TRUE);
                $n_quantity = str_replace(",", "", $this->input->post('n_quantity' . $i, TRUE));
                $n_quantity_gelar = str_replace(",", "", $this->input->post('n_quantity_gelar' . $i, TRUE));
                $v_gelar = str_replace(",", "", $this->input->post('v_gelar' . $i, TRUE));
                $v_set = str_replace(",", "", $this->input->post('v_set' . $i, TRUE));
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                 $id_item_keluar = $this->input->post('id_item_keluar' . $i, TRUE);
                if (($id_material != '' || $id_material != null) && $n_quantity > 0) {
                    $this->mmaster->simpandetail($id, $id_material, $id_product_wip, $n_quantity, $e_remark_item, $n_quantity_gelar, $v_gelar, $v_set, $id_item_keluar);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => $i_document,
                    'id'     => $id
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $i_document,
                    'id'     => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
        } else {
            $data = array(
                'sukses'    => false,
                'kode'      => $i_document,
                'id'        => $id,
            );
        }
        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU FORM VIEW  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  MEMBUKA MENU FORM APPROVE  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        if ($istatus == '6') {
            $update = $this->mmaster->updatesisa($id);
            if ($update == "gagal") {
                $this->db->trans_rollback();
                echo json_encode(false);
                die;
            } else {
                $this->mmaster->changestatus($id, $istatus);
            }
        } else {
            $this->mmaster->changestatus($id, $istatus);
        }
        // $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    /*----------  MEMBUKA FORM TAMBAH DATA TANPA REFERENSI  ----------*/

    public function tambahmanual()
    {
        $data = check_role($this->i_menu1, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "BBM-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title'] . ' Manual');

        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }



    /*----------  SIMPAN DATA MANUAL ----------*/

    public function simpanmanual()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ipengirim    = $this->input->post('ipengirim', TRUE);
        $ireff        = 0;
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($ibagian != '' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $getidpengirim = $this->mmaster->idbagian($ibagian);
            if ($getidpengirim->num_rows() > 0) {
                $ipengirim = $getidpengirim->row()->id;
            } else {
                $ipengirim = 0;
            }
            $this->mmaster->simpan($id, $idocument, $ddocument, $ibagian, $ipengirim, $ireff, $eremark);
            for ($i = 1; $i <= $jml; $i++) {
                $idproduct     = $this->input->post('idproduct' . $i, TRUE);
                $nquantityreff = $this->input->post('npemenuhan' . $i, TRUE);
                $nquantity     = $this->input->post('npemenuhan' . $i, TRUE);
                $eremark       = $this->input->post('eremark' . $i, TRUE);
                if (($idproduct != '' || $idproduct != null) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id, $ireff, $idproduct, $nquantity, $nquantityreff, $eremark);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ' Manual, Kode : ' . $idocument);
            }
        } else {
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    /*----------  UPDATE DATA MANUAL  ----------*/

    public function updatemanual()
    {
        $data = check_role($this->i_menu1, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ipengirim    = $this->input->post('ipengirim', TRUE);
        $ireff        = 0;
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($id != '' && $ibagian != '' && $ipengirim != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id, $idocument, $ddocument, $ibagian, $ipengirim, $ireff, $eremark);
            $this->mmaster->delete($id);
            for ($i = 1; $i <= $jml; $i++) {
                $idproduct     = $this->input->post('idproduct' . $i, TRUE);
                $nquantityreff = $this->input->post('npemenuhan' . $i, TRUE);
                $nquantity     = $this->input->post('npemenuhan' . $i, TRUE);
                $eremark       = $this->input->post('eremark' . $i, TRUE);
                if (($idproduct != '' || $idproduct != null) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id, $ireff, $idproduct, $nquantity, $nquantityreff, $eremark);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $idocument);
            }
        } else {
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }
}

/* End of file Cform.php */