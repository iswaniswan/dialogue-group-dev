<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $this->global['title']  = $data[0]['e_menu'];

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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
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

    public function changestatus()
    {
        /*$data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }*/

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
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
            'jenis'         => $this->mmaster->jenisbarang(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SC-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('ijenis'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->i_product_wip.'|'.$row->i_color,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname.' - '.$row->e_color_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian  = $this->input->post('ibagian', TRUE);
        $ischedule  = $this->input->post('ischedule', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $eremarkh = $this->input->post('eremarkh', TRUE);
        $jml      = $this->input->post('jml', TRUE);
        if ($ischedule!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id,$ibagian,$ischedule,$ddocument,$eremarkh);
            for ($i = 1; $i <= $jml; $i++) {
                $dschdetail     = date('Y-m-d',strtotime($this->input->post('dschdetail' . $i, TRUE)));
                $iproductcolor  = explode("|", $this->input->post('iproductcolor' . $i, TRUE));
                $iproduct       = $iproductcolor[0];
                $icolor         = $iproductcolor[1];
                $nquantity      = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
                $eremark        = $this->input->post('eremark' . $i, TRUE);
                if ($nquantity>0 && ($iproduct!=null || $iproduct!='')) {
                    $this->mmaster->insertdetail($id,$dschdetail,$iproduct,$icolor,$nquantity,$eremark); 
                    $this->mmaster->insertitemdetail($id,$iproduct,$icolor,$nquantity);
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
                    'kode'   => $ischedule,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        echo json_encode($data);
        /*$this->load->view('pesan2', $data);*/
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
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'jenis'      => $this->mmaster->jenisbarang(),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result(),
            'number'     => "SC-".date('ym')."-123456",
            'bagian'     => $this->mmaster->bagian()->result(),
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

        $id     = $this->input->post('id', TRUE);
        $ibagian  = $this->input->post('ibagian', TRUE);
        $ischedule  = $this->input->post('ischedule', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        $ischeduleold   = $this->input->post('ischeduleold', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $eremarkh = $this->input->post('eremarkh', TRUE);
        $jml      = $this->input->post('jml', TRUE);
        if ($id!='' && $ischedule!='' && $ischeduleold!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$ibagian,$ischedule,$ddocument,$eremarkh);
            $this->mmaster->deletedetail($id);
            for ($i = 1; $i <= $jml; $i++) {
                $dschdetail     = date('Y-m-d',strtotime($this->input->post('dschdetail' . $i, TRUE)));
                $iproductcolor  = explode("|", $this->input->post('iproductcolor' . $i, TRUE));
                $iproduct       = $iproductcolor[0];
                $icolor         = $iproductcolor[1];
                $nquantity      = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
                $eremark        = $this->input->post('eremark' . $i, TRUE);
                if ($nquantity>0 && ($iproduct!=null || $iproduct!='')) {
                    $this->mmaster->insertdetail($id,$dschdetail,$iproduct,$icolor,$nquantity,$eremark);
                    $this->mmaster->insertitemdetail($id,$iproduct,$icolor,$nquantity);
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
                    'kode'   => $ischedule,
                    'id'     => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        echo json_encode($data);
        /*$this->load->view('pesan2', $data);*/
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result(),
            'bagian'     => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ischedule = $this->uri->segment(4);
        $dfrom     = $this->uri->segment(5);
        $dto       = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'jenis'      => $this->mmaster->jenisbarang(),
            'data'       => $this->mmaster->cek_data($ischedule)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ischedule)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}

/* End of file Cform.php */