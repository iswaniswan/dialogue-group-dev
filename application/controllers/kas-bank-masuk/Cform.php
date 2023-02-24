<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2040316';

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
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'] . '/mmaster');
    }


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
        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(6);
            if ($i_area == '') {
                $i_area = 'all';
            }
        }
        $i_rv_type = $this->input->post('i_rv_type', TRUE);
        if ($i_rv_type == '') {
            $i_rv_type = $this->uri->segment(6);
            if ($i_rv_type == '') {
                $i_rv_type = 'all';
            }
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'i_area' => $i_area,
            'area' => $this->mmaster->area()->result(),
            'i_rv_type' => $i_rv_type,
            'rvtype' => $this->mmaster->rvtype()->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        $i_area = $this->input->post('i_area', TRUE);
        if ($i_area == '') {
            $i_area = $this->uri->segment(6);
            if ($i_area == '') {
                $i_area = 'all';
            }
        }
        $i_rv_type = $this->input->post('i_rv_type', TRUE);
        if ($i_rv_type == '') {
            $i_rv_type = $this->uri->segment(7);
            if ($i_rv_type == '') {
                $i_rv_type = 'all';
            }
        }

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto, $i_area, $i_rv_type);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'bagian' => $this->mmaster->bagian()->result(),
            'number' => "RV-" . date('ym') . "-0001",
            'dfrom' => $this->uri->segment(4),
            'dto' => $this->uri->segment(5),
            'area' => $this->mmaster->area()->result(),
            'rvtype' => $this->mmaster->rvtype()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        $d_rv = $this->input->post('d_rv');
        $i_bagian = $this->input->post('i_bagian');
        $i_area = $this->input->post('i_area');
        $i_rv_type = $this->input->post('i_rv_type');
        $i_coa = $this->input->post('i_coa');
        $id = $this->input->post('id');
        if (strlen($d_rv) > 0) {
            $number = $this->mmaster->runningnumber(format_ym($d_rv), format_Y($d_rv), $i_bagian, $i_area, $i_rv_type, $i_coa, $id);
        }
        echo json_encode($number);
    }

    /*----------  CARI BARANG  ----------*/

    public function coa_type()
    {
        $filter = [];
        $data = $this->mmaster->coa_type(str_replace("'", "", $this->input->get('q')), $this->input->get('i_rv_type'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->i_coa,
                    'text' => $row->i_coa_id . ' - ' . $row->e_coa_name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI AREA  ----------*/

    public function area()
    {
        $filter = [];
        $data = $this->mmaster->get_area(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id' => $row->id,
                'text' => $row->i_area . ' - ' . $row->e_area,
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI COA  ----------*/

    public function coa()
    {
        $filter = [];
        $i_rv_type = $this->input->get('i_rv_type');
        if ($i_rv_type != '') {
            $data = $this->mmaster->coa(str_replace("'", "", $this->input->get('q')), $i_rv_type);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->i_coa,
                    'text' => $row->i_coa_id . ' - ' . $row->e_coa_name,
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => 'Pilih Jenis',
            );
        }
        echo json_encode($filter);
    }

    public function referensi_type()
    {
        $filter = [];
        $data = $this->mmaster->reference_type(str_replace("'", "", $this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id' => $row->i_rv_refference_type,
                'text' => $row->e_rv_refference_type_name,
            );
        }
        echo json_encode($filter);
    }

    public function referensi()
    {
        $filter = [];
        $i_rv_refference_type = $this->input->get('i_rv_refference_type');
        $i_area = $this->input->get('i_area');
        if ($i_rv_refference_type != '') {
            $data = $this->mmaster->referensi(str_replace("'", "", $this->input->get('q')), $i_area, $i_rv_refference_type);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->kode . ' - ' . $row->v_jumlah,
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => 'Pilih Tunai/Giro/Transfer',
            );
        }
        echo json_encode($filter);
    }

    public function get_detail_referensi()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id', true);
        $i_rv_refference_type = $this->input->post('i_rv_refference_type', true);
        $data = '';
        if (strlen($id) > 0 && strlen($i_rv_refference_type) > 0) {
            $data = array(
                'detail' => $this->mmaster->get_detail_referensi($id, $i_rv_refference_type)->result_array()
            );
        }
        echo json_encode($data);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_bagian = $this->input->post('i_bagian');
        $i_rv_id = $this->input->post('i_rv_id');
        $d_rv = $this->input->post('d_rv');
        if (strlen($d_rv) > 0) {
            $d_rv = formatYmd($d_rv);
        } else {
            $d_rv = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $i_rv_type = $this->input->post('i_rv_type');
        $i_coa = $this->input->post('i_coa');
        $e_remark = $this->input->post('e_remark');
        $v_rv = str_replace(",", "", $this->input->post('v_rv'));
        $jml = $this->input->post('jml');
        if ($i_bagian != '' && $d_rv != '' && $i_rv_id != '' && $i_rv_type != '' && $i_coa != '' && $v_rv > 0 && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->create_header($id, $i_bagian, $i_rv_id, $i_rv_type, $i_area, $i_coa, $d_rv, $v_rv, $e_remark);
            $no = 0;
            for ($x = 1; $x <= $jml; $x++) {
                $i_coa_item = $this->input->post('i_coa_item_' . $x);
                $d_bukti = $this->input->post('d_bukti_' . $x);
                if (strlen($d_bukti) > 0) {
                    $d_bukti = formatYmd($d_bukti);
                } else {
                    $d_bukti = date('Y-m-d');
                }
                $i_area_item = $this->input->post('i_area_item_' . $x);
                $i_rv_refference_type = $this->input->post('i_rv_refference_type_' . $x);
                $i_rv_refference_type = (strlen($i_rv_refference_type) > 0) ? $i_rv_refference_type : NULL ;
                $i_rv_refference = $this->input->post('i_rv_refference_' . $x);
                $i_rv_refference = (strlen($i_rv_refference) > 0) ? $i_rv_refference : NULL ;
                $e_remark_item = $this->input->post('e_remark_item_' . $x);
                $v_rv_item = str_replace(",", "", $this->input->post('v_rv_item_' . $x));
                $e_coa_name = $this->db->query("SELECT e_coa_name FROM tr_coa WHERE id = '$i_coa' ")->row()->e_coa_name;
                if ($i_coa_item != "" && $d_bukti != "" && $i_area_item != "" && $v_rv_item > 0) {
                    $no++;
                    $this->mmaster->create_detail($id, $i_area_item, $i_coa_item, $d_bukti, $e_coa_name, $v_rv_item, $e_remark_item, $no, $i_rv_refference_type, $i_rv_refference);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $i_rv_id,
                    'id' => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'area' => $this->mmaster->area()->result(),
            'rvtype' => $this->mmaster->rvtype()->result(),
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

        $id = $this->input->post('id', TRUE);
        $i_bagian = $this->input->post('i_bagian');
        $i_rv_id = $this->input->post('i_rv_id');
        $d_rv = $this->input->post('d_rv');
        if (strlen($d_rv) > 0) {
            $d_rv = formatYmd($d_rv);
        } else {
            $d_rv = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $i_rv_type = $this->input->post('i_rv_type');
        $i_coa = $this->input->post('i_coa');
        $e_remark = $this->input->post('e_remark');
        $v_rv = str_replace(",", "", $this->input->post('v_rv'));
        $jml = $this->input->post('jml');
        if ($i_bagian != '' && $d_rv != '' && $i_rv_id != '' && $i_rv_type != '' && $i_coa != '' && $v_rv > 0 && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update_header($id, $i_bagian, $i_rv_id, $i_rv_type, $i_area, $i_coa, $d_rv, $v_rv, $e_remark);
            $this->mmaster->delete($id);
            $no = 0;
            for ($x = 1; $x <= $jml; $x++) {
                $i_coa_item = $this->input->post('i_coa_item_' . $x);
                $d_bukti = $this->input->post('d_bukti_' . $x);
                if (strlen($d_bukti) > 0) {
                    $d_bukti = formatYmd($d_bukti);
                } else {
                    $d_bukti = date('Y-m-d');
                }
                $i_area_item = $this->input->post('i_area_item_' . $x);
                $i_rv_refference_type = $this->input->post('i_rv_refference_type_' . $x);
                $i_rv_refference_type = (strlen($i_rv_refference_type) > 0) ? $i_rv_refference_type : NULL ;
                $i_rv_refference = $this->input->post('i_rv_refference_' . $x);
                $i_rv_refference = (strlen($i_rv_refference) > 0) ? $i_rv_refference : NULL ;
                $e_remark_item = $this->input->post('e_remark_item_' . $x);
                $v_rv_item = str_replace(",", "", $this->input->post('v_rv_item_' . $x));
                $e_coa_name = $this->db->query("SELECT e_coa_name FROM tr_coa WHERE id = '$i_coa' ")->row()->e_coa_name;
                if ($i_coa_item != "" && $d_bukti != "" && $i_area_item != "" && $v_rv_item > 0) {
                    $no++;
                    $this->mmaster->create_detail($id, $i_area_item, $i_coa_item, $d_bukti, $e_coa_name, $v_rv_item, $e_remark_item, $no, $i_rv_refference_type, $i_rv_refference);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $i_rv_id,
                    'id' => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
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

    /*----------  MEMBUKA MENU Approve  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'area' => $this->mmaster->area()->result(),
            'rvtype' => $this->mmaster->rvtype()->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'area' => $this->mmaster->area()->result(),
            'rvtype' => $this->mmaster->rvtype()->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function cetak()
    {
        $data = check_role($this->i_menu, 5);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $id = decrypt_url($this->uri->segment(4));
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Cetak " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $id,
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'data' => $this->mmaster->dataedit($id)->row(),
            'detail' => $this->mmaster->dataeditdetail($id)->result(),
            'company' => $this->db->get_where('public.company', ['id' => $this->id_company])->row(),
        );

        $this->Logger->write('Cetak Data ' . $this->global['title'] . ' Id : ' . $id);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function updateprint()
    {

        $id = $this->input->post('id', true);
        $this->db->trans_begin();
        $this->mmaster->updateprint($id);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo 'fail';
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Print ' . $this->global['folder'] . ' Id : ' . $id);
            echo $id;
        }
    }
}
/* End of file Cform.php */