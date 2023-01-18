<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2090304';

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
            'dfrom'     => $dfrom,
            'dto'       => $dto,
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
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
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
            'gudang'        => $this->mmaster->gudang(),
            'no'            => $this->mmaster->runningnumberid(date('Ym')),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function getid()
    {
        $filter = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $filter = $this->mmaster->runningnumberid(date('Ym', strtotime($this->input->post('tgl', TRUE))));
        }
        echo json_encode($filter);
    }

    public function dataproduct()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->product($cari);
            foreach ($data->result() as $product) {
                $filter[] = array(
                    'id'    => $product->id,
                    'text'  => $product->nama . ' (' . $product->e_color_name . ')',
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getproduct()
    {
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('iproduct'));
        echo json_encode($data->result_array());
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $tgl         = $this->input->post('tgl', TRUE);
        $idepartemen = $this->input->post('idepartemen', TRUE);
        $pengirim    = $this->input->post('pengirim', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);
        if ($tgl!='') {
            $yearmonth = date('Ym', strtotime($tgl));
            $tanggal   = date('Y-m-d', strtotime($tgl));
        }else{
            $tanggal   = date('Y-m-d');
        }

        if ($tgl!='' && $idepartemen!='' && $pengirim!='' && $jml!='') {
            $this->db->trans_begin();
            $id = $this->mmaster->runningnumber($yearmonth);
            $this->mmaster->insertheader($id,$tanggal,$idepartemen,$pengirim,$eremark);
            $x = 0;
            for ($i = 1; $i <= $jml; $i++) {
                $iwip           = $this->input->post('iproduct'.$i, TRUE);
                $icolor         = $this->input->post('icolor'.$i, TRUE);
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $qty            = $this->input->post('nquantity'.$i, TRUE);
                $enote          = $this->input->post('edesc'.$i, TRUE);
                if ($qty > 0 && ($qty!= null || $qty != '')) {
                    $x++;
                    $this->mmaster->insertdetail($id,$iwip,$eproductname,$icolor,$enote,$qty,$qty,$x);
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
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $id),
                    'sukses' => true,
                    'kode'   => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function changestatus()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $data = $this->mmaster->changestatus($id,$istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status '.$this->global['folder'].' Menjadi : '.$istatus.' No : '.$id);
            echo json_encode($data);
        }
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
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->dataheader($id)->row(),
            'datadetail' => $this->mmaster->datadetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $id = $this->uri->segment(4);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->dataheader($id)->row(),
            'datadetail' => $this->mmaster->datadetail($id)->result(),
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
        $id          = $this->input->post('id', TRUE);
        $tgl         = $this->input->post('tgl', TRUE);
        $idepartemen = $this->input->post('idepartemen', TRUE);
        $pengirim    = $this->input->post('pengirim', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);
        if ($tgl!='') {
            $yearmonth = date('Ym', strtotime($tgl));
            $tanggal   = date('Y-m-d', strtotime($tgl));
        }else{
            $tanggal   = date('Y-m-d');
        }

        if ($id!='' && $tgl!='' && $idepartemen!='' && $pengirim!='' && $jml!='') {
            $this->db->trans_begin();
            $this->mmaster->updateheader($id,$tanggal,$idepartemen,$pengirim,$eremark);
            $this->mmaster->deletedetail($id);
            $x = 0;
            for ($i = 1; $i <= $jml; $i++) {
                $iwip           = $this->input->post('iproduct'.$i, TRUE);
                $icolor         = $this->input->post('icolor'.$i, TRUE);
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $qty            = $this->input->post('nquantity'.$i, TRUE);
                $enote          = $this->input->post('edesc'.$i, TRUE);
                if ($qty > 0 && ($qty!= null || $qty != '')) {
                    $x++;
                    $this->mmaster->insertdetail($id,$iwip,$eproductname,$icolor,$enote,$qty,$qty,$x);
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
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $id),
                    'sukses' => true,
                    'kode'   => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $id = $this->uri->segment(4);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->dataheader($id)->row(),
            'datadetail' => $this->mmaster->datadetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */
