<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2010404';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->id_company = $this->session->userdata('id_company');
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

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
        if ($id != '') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if (($this->db->trans_status() === False)) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status ' . $this->global['title'] . ' Id : ' . $id);
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
        );


        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $e_pic_name = $this->input->post('e_pic_name', TRUE);
        $f_cutting  = $this->input->post('f_cutting', TRUE);
        $f_cutting  = ($f_cutting == 'on') ? 't' : 'f';
        $f_gelar    = $this->input->post('f_gelar', TRUE);
        $f_gelar    = ($f_gelar == 'on') ? 't' : 'f';

        if ($e_pic_name != '' && $f_cutting != '' && $f_gelar != '') {
            $this->db->trans_begin();
            $this->mmaster->insert($e_pic_name, $f_cutting, $f_gelar);
            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $e_pic_name);
                $data = array(
                    'sukses'  => true,
                    'kode'    => $e_pic_name
                );
            }
        } else {
            $data = array(
                'sukses' => false,
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

        $id = $this->uri->segment(4);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'data'          => $this->mmaster->cek_data($id)->row(),
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

        $id         = $this->input->post('id', TRUE);
        $e_pic_name = $this->input->post('e_pic_name', TRUE);
        $f_cutting  = $this->input->post('f_cutting', TRUE);
        $f_cutting  = ($f_cutting == 'on') ? 't' : 'f';
        $f_gelar    = $this->input->post('f_gelar', TRUE);
        $f_gelar    = ($f_gelar == 'on') ? 't' : 'f';

        if ($id != '' && $e_pic_name != '' && $f_cutting != '' && $f_gelar != '') {
            $this->db->trans_begin();
            $this->mmaster->update($id, $e_pic_name, $f_cutting, $f_gelar);
            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $e_pic_name);
                $data = array(
                    'sukses'  => true,
                    'kode'    => $e_pic_name
                );
            }
        } else {
            $data = array(
                'sukses' => false,
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

        $id = $this->uri->segment(4);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->cek_data($id)->row()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}

/* End of file Cform.php */