<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090101';

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

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->doc_qe = $data[0]['doc_qe'];

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
            'title'     => 'List Penerimaan Cutting',
            'title_list'=> 'List ' . $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist_penerimaan', $data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/

    public function proses()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom = $this->input->post('d_from', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('d_to', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $jml = $this->input->post('jml');
        $material = '';
        $wip = '';
        $periode = '';
        $id = '';
        for ($i=1; $i <= $jml; $i++) { 
            $check = $this->input->post("chk$i");
            $id_material = $this->input->post("id_material$i");
            $id_product_wip = $this->input->post("id_product_wip$i");
            $id_penerimaan = $this->input->post("id$i");
            if ($check=='on') {
                $material .= $id_material.',';
                $wip .= $id_product_wip.',';
                $id .= $id_penerimaan."','";
                // $periode .= $i_periode."','";
            }
        }
        $id_material = substr($material,0,-1);
        $id_product_wip = substr($wip,0,-1);
        $id = "'".substr($id,0,-2);
        // $i_periode = "'".substr($periode,0,-2);
        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'number'     => "SJ-" . date('ym') . "-1234",
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian(),
            'datadetail' => $this->mmaster->dataeditdetail_penerimaan($id_material,$id_product_wip,$id)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah Dari Penerimaan Cutting ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/

    public function data_penerimaan()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data_penerimaan($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        $date = $this->input->post('tgl', TRUE);
        if ($date != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($date)), $this->input->post('i_bagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function get_pic_cutting()
    {
        $filter = [];
        $f_cutting = 't';
        $f_gelar = 'f';
        $cari = str_replace("'", "", $this->input->get('q'));
        $data = $this->mmaster->get_pic($cari, $f_cutting, $f_gelar);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => $key->e_pic_name
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

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function get_pic_gelar()
    {
        $filter = [];
        $f_cutting = 'f';
        $f_gelar = 't';
        $cari = str_replace("'", "", $this->input->get('q'));
        $data = $this->mmaster->get_pic($cari, $f_cutting, $f_gelar);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => $key->e_pic_name
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

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_document = $this->input->post('i_document', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian = $this->input->post('i_bagian', TRUE);
        $e_remark = $this->input->post('e_remark', TRUE);
        $jml = $this->input->post('jml', TRUE);
        $id = $this->mmaster->runningid();
        if ($i_bagian != '' && $i_document != ''  && $d_document != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->simpan($id, $i_document, $d_document, $i_bagian, $e_remark);
            for ($i = 1; $i <= $jml; $i++) {
                $id_company_referensi = $this->input->post('id_company_referensi' . $i, TRUE);
                $d_schedule = $this->input->post('d_schedule' . $i, TRUE);
                $jam = $this->input->post('jam' . $i, TRUE);
                $id_referensi = $this->input->post('id_referensi' . $i, TRUE);
                $id_material = $this->input->post('id_material' . $i, TRUE);
                $id_product_wip = $this->input->post('id_product_wip' . $i, TRUE);
                $n_quantity = str_replace(",","",$this->input->post('n_quantity' . $i, TRUE));
                $n_quantity_product = str_replace(",","",$this->input->post('n_quantity_product' . $i, TRUE));
                $n_jumlah_gelar = str_replace(",","",$this->input->post('n_jumlah_gelar' . $i, TRUE));
                $v_set = str_replace(",","",$this->input->post('v_set' . $i, TRUE));
                $v_gelar = str_replace(",","",$this->input->post('v_gelar' . $i, TRUE));
                $id_pic_cutting = $this->input->post('id_pic_cutting' . $i, TRUE);
                $id_pic_gelar = $this->input->post('id_pic_gelar' . $i, TRUE);
                $d_cutting = $this->input->post('d_cutting' . $i, TRUE);
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                if (($id_material != '' || $id_material != null) && ($id_product_wip != '' || $id_product_wip != null) && $n_quantity > 0) {
                    $this->mmaster->simpandetail($id,$d_schedule,$jam,$id_referensi,$id_material,$id_product_wip,$n_quantity,$id_pic_cutting,$id_pic_gelar,$d_cutting,$e_remark_item,$id_company_referensi,$n_quantity_product,$n_jumlah_gelar,$v_set,$v_gelar);
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
                    'sukses' => true,
                    'kode'   => $i_document,
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
            'number'     => "SJ-" . date('ym') . "-1234",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
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

        $id         = $this->input->post('id', TRUE);
        $i_document = $this->input->post('i_document', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian = $this->input->post('i_bagian', TRUE);
        $e_remark = $this->input->post('e_remark', TRUE);
        $jml = $this->input->post('jml', TRUE);
        if ($id != '' && $i_bagian != '' && $i_document != ''  && $d_document != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id, $i_document, $d_document, $i_bagian, $e_remark);
            $this->mmaster->delete($id);
            for ($i = 1; $i <= $jml; $i++) {
                $id_company_referensi = $this->input->post('id_company_referensi' . $i, TRUE);
                $d_schedule = $this->input->post('d_schedule' . $i, TRUE);
                $jam = $this->input->post('jam' . $i, TRUE);
                $id_referensi = $this->input->post('id_referensi' . $i, TRUE);
                $id_material = $this->input->post('id_material' . $i, TRUE);
                $id_product_wip = $this->input->post('id_product_wip' . $i, TRUE);
                $n_quantity = str_replace(",","",$this->input->post('n_quantity' . $i, TRUE));
                $n_quantity_product = str_replace(",","",$this->input->post('n_quantity_product' . $i, TRUE));
                $n_jumlah_gelar = str_replace(",","",$this->input->post('n_jumlah_gelar' . $i, TRUE));
                $v_set = str_replace(",","",$this->input->post('v_set' . $i, TRUE));
                $v_gelar = str_replace(",","",$this->input->post('v_gelar' . $i, TRUE));
                $id_pic_cutting = $this->input->post('id_pic_cutting' . $i, TRUE);
                $id_pic_gelar = $this->input->post('id_pic_gelar' . $i, TRUE);
                $d_cutting = $this->input->post('d_cutting' . $i, TRUE);
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                if (($id_material != '' || $id_material != null) && ($id_product_wip != '' || $id_product_wip != null) && $n_quantity > 0) {
                    $this->mmaster->simpandetail($id,$d_schedule,$jam,$id_referensi,$id_material,$id_product_wip,$n_quantity,$id_pic_cutting,$id_pic_gelar,$d_cutting,$e_remark_item,$id_company_referensi,$n_quantity_product,$n_jumlah_gelar,$v_set,$v_gelar);
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
                    'sukses' => true,
                    'kode'   => $i_document,
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
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
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
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
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
        /* if ($istatus == '6') {
            $this->mmaster->updatesisa($id);
            $this->mmaster->simpanjurnal($id, $this->global['title']);
        } */
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    public function realisasi()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'number'     => "SJ-" . date('ym') . "-1234",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformrealisasi', $data);
    }

    public function update_realisasi()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_document         = $this->input->post('i_document', TRUE);
        $id         = $this->input->post('id', TRUE);
        $jml = $this->input->post('jml', TRUE);
        if ($id != '' && $jml > 0) {
            $this->db->trans_begin();
            // $this->mmaster->update($id, $i_document, $d_document, $i_bagian, $e_remark);
            // $this->mmaster->delete($id);
            for ($i = 1; $i <= $jml; $i++) {
                $id_item = $this->input->post('id_item' . $i, TRUE);
                $d_schedule_real = $this->input->post('d_schedule_real' . $i, TRUE);
                $jam_real = $this->input->post('jam_real' . $i, TRUE);
                $id_pic_cutting = $this->input->post('id_pic_cutting' . $i, TRUE);
                $id_pic_gelar = $this->input->post('id_pic_gelar' . $i, TRUE);
                $e_pic_cutting = $this->input->post('e_pic_cutting' . $i, TRUE);
                $e_pic_gelar = $this->input->post('e_pic_gelar' . $i, TRUE);
                $n_realisasi_gelar = $this->input->post('n_realisasi_gelar' . $i, TRUE);
                $n_realisasi_product = $this->input->post('n_realisasi_product' . $i, TRUE);
                $this->mmaster->update_realisasi($id_item, $d_schedule_real, $jam_real, $id_pic_cutting, $id_pic_gelar, $n_realisasi_gelar, $n_realisasi_product, $e_pic_cutting, $e_pic_gelar );
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
                    'sukses' => true,
                    'kode'   => $i_document,
                    'id'     => $id
                );
                $this->Logger->write('Realisasi Schedule Cutting ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
            echo json_encode($data);
        }
    } 

}

/* End of file Cform.php */