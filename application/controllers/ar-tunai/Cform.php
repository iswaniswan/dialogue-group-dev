<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '204021302';

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

        $data = array(
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
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

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
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
            'number' => "DT-" . date('ym') . "-0001",
            'dfrom' => $this->uri->segment(4),
            'dto' => $this->uri->segment(5),
            'all_area' => $this->mmaster->area()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE), $this->input->post('i_area', TRUE), $this->input->post('id', TRUE));
        }
        echo json_encode($number);
    }

    public function generate_nomor_dokumen()
    {
        $number = "";

        if ($this->input->post('tgl', TRUE) != '') {
            $id_bagian = $this->input->post('ibagian');
            $number = $this->mmaster->generate_nomor_dokumen($id_bagian);
        }

        echo json_encode($number);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function cekkodeedit()
    {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode', TRUE), $this->input->post('kodeold', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  CARI BARANG  ----------*/

    public function nota()
    {
        $filter = [];
        $data = $this->mmaster->nota(str_replace("'", "", $this->input->get('q')), $this->input->get('i_area'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->i_document . ' - ' . $row->e_customer_name
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

    /*----------  GET DETAIL NOTA  ----------*/

    public function detailnota()
    {
        header("Content-Type: application/json", true);
        $query = array(
            'detail' => $this->mmaster->detailnota($this->input->post('id'))->result_array()
        );
        echo json_encode($query);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian = $this->input->post('ibagian');
        $i_dt_id = $this->input->post('i_dt_id');
        $d_dt = $this->input->post('d_dt');
        if (strlen($d_dt) > 0) {
            $d_dt = formatYmd($d_dt);
        } else {
            $d_dt = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $v_jumlah = $this->input->post('v_jumlah');
        $jml = $this->input->post('jml');

        if ($ibagian != '' && $d_dt != '' && $ibagian != '' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->create_header($id, $i_dt_id, $ibagian, $i_area, $d_dt, $v_jumlah);
            $no = 0;
            for ($x = 1; $x <= $jml; $x++) {
                $i_nota = $this->input->post('i_nota' . $x);

                if ($i_nota != "" || $i_nota != NULL || strlen($i_nota) > 0) {
                    $no++;
                    $d_nota = $this->input->post('d_nota_' . $x);
                    $v_bayar = str_replace(",", ".", $this->input->post('v_nota_' . $x));
                    $v_sisa = str_replace(",", ".", $this->input->post('v_sisa_' . $x));
                    $this->mmaster->create_detail($id, $i_nota, $d_nota, $v_sisa, $v_bayar, $no);
                }
            }
            //die();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $i_dt_id,
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

    public function simpan2()
    {
        $id_bagian;
        $id_area;
        $id_daftar_tagihan;

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
            'doc' => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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
        $ibagian = $this->input->post('ibagian');
        $i_dt_id = $this->input->post('i_dt_id');
        $d_dt = $this->input->post('d_dt');
        if (strlen($d_dt) > 0) {
            $d_dt = formatYmd($d_dt);
        } else {
            $d_dt = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $v_jumlah = $this->input->post('v_jumlah');
        $jml = $this->input->post('jml');

        if ($ibagian != '' && $d_dt != '' && $ibagian != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update_header($id, $i_dt_id, $ibagian, $i_area, $d_dt, $v_jumlah);
            $this->mmaster->delete($id);
            $no = 0;
            for ($x = 1; $x <= $jml; $x++) {
                $i_nota = $this->input->post('i_nota' . $x);
                if ($i_nota != "" || $i_nota != NULL || strlen($i_nota) > 0) {
                    $no++;
                    $d_nota = $this->input->post('d_nota_' . $x);
                    $v_bayar = str_replace(",", ".", $this->input->post('v_nota_' . $x));
                    $v_sisa = str_replace(",", ".", $this->input->post('v_sisa_' . $x));
                    $this->mmaster->create_detail($id, $i_nota, $d_nota, $v_sisa, $v_bayar, $no);
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
                    'kode' => $i_dt_id,
                    'id' => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
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
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'area'          => $this->mmaster->area()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'area'          => $this->mmaster->area()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $id,
            'company'       => $this->db->get_where('public.company',['id'=>$this->id_company])->row(),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($id)->row(),
            'detail'        => $this->mmaster->dataeditdetail($id)->result(),
        );

        $this->Logger->write('Cetak Data ' . $this->global['title'] . ' Id : ' . $id);

        $this->load->view($this->global['folder'] . '/print', $data);
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

    public function get_all_customer()
    {
        $q = $this->input->get('q');
        $id_area = $this->input->get('id_area');

        $data = [];

        $query = $this->mmaster->get_all_customer(str_replace("'", "", $q), $id_area);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->e_customer_name
            );
        }
        echo json_encode($data);
    }

    public function get_all_sales()
    {
        $q = $this->input->get('q');
        // $id_area = $this->input->get('id_customer');

        $data = [];

        $query = $this->mmaster->get_all_sales(str_replace("'", "", $q), null);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->e_sales
            );
        }
        echo json_encode($data);
    }

    public function get_all_daftar_tagihan()
    {
        $q = $this->input->get('q');
        // $id_area = $this->input->get('id_customer');

        $data = [];

        $query = $this->mmaster->get_all_daftar_tagihan(str_replace("'", "", $q), null);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->i_dt,
                'text' => $result->i_dt_id
            );
        }
        echo json_encode($data);
    }

    public function get_all_nota_penjualan()
    {
        $q = $this->input->get('q');
        // $id_area = $this->input->get('id_customer');

        $data = [];

        $query = $this->mmaster->get_all_nota_penjualan(str_replace("'", "", $q), null);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->i_document
            );
        }
        echo json_encode($data);
    }
}
/* End of file Cform.php */