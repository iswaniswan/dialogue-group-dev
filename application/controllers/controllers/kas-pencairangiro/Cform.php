<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040307';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
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
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
        );


        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
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

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "GRC-".date('ym')."-123456"            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function kasbank(){
        $filter = [];
        $data   = $this->mmaster->kasbank(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->e_kas_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function customer(){
        $filter = [];
        $data   = $this->mmaster->customer(strtoupper($this->input->get('q')), $this->input->get('ikasbank'));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id_customer,
                    'text'  => $row->e_customer_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

     public function kliring(){
        $filter = [];
        $data   = $this->mmaster->kliring(strtoupper($this->input->get('q')), $this->input->get('ikasbank'), $this->input->get('icustomer'));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->i_document,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function getgiro(){
        $filter = [];
        $data   = $this->mmaster->getgiro(strtoupper($this->input->get('q')), $this->input->get('ikasbank'), $this->input->get('icustomer'), $this->input->get('ikriling'));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id, 
                    'text'  => $row->i_giro,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    function getitemgiro(){
        header("Content-Type: application/json", true);
        $ireferensigiro  = $this->input->post('ireferensigiro');
        $ikriling        = $this->input->post('ikriling');      
        $ikasbank        = $this->input->post('ikasbank');
        $icustomer       = $this->input->post('icustomer');    

        $data = $this->mmaster->getitemgiro($ireferensigiro, $ikriling, $ikasbank, $icustomer);
        $dataa = array(
            'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getitemgiro($ireferensigiro, $ikriling, $ikasbank, $icustomer)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument      = $this->input->post('idocument', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikasbank       = $this->input->post('ikasbank', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ikriling       = $this->input->post('ikriling', TRUE);
        $ireferensigiro = $this->input->post('ireferensigiro', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
        $id = $this->mmaster->runningid();
        $this->mmaster->insertheader($id, $idocument, $ibagian, $datedocument, $ikasbank, $icustomer, $ikriling, $ireferensigiro, $eremark);

        for($i=1;$i<=$jml;$i++){ 
            $idkliring      = $this->input->post('idkliring'.$i, TRUE);
            $idbank         = $this->input->post('idbank'.$i, TRUE);
            $idpenyetor     = $this->input->post('idpenyetor'.$i, TRUE);
            $jumlah         = str_replace(',','',$this->input->post('jumlah'.$i,TRUE));

            $this->mmaster->insertdetail($id, $idkliring, $idbank, $idpenyetor, $jumlah);
            
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
                'kode'   => $idocument,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');   

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'id'            => $id,
            'number'        => "GRC-".date('ym')."-123456",   
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('idocument', TRUE);
        $ibagian      = $this->input->post('ibagian', TRUE);
       $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikasbank       = $this->input->post('ikasbank', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ikriling       = $this->input->post('ikriling', TRUE);
        $ireferensigiro = $this->input->post('ireferensigiro', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
        $this->mmaster->updateheader($id, $idocument, $ibagian, $datedocument, $ikasbank, $icustomer, $ikriling, $ireferensigiro, $eremark);
        $this->mmaster->deletedetail($id);

        for($i=1;$i<=$jml;$i++){ 
            $idkliring      = $this->input->post('idkliring'.$i, TRUE);
            $idbank         = $this->input->post('idbank'.$i, TRUE);
            $idpenyetor     = $this->input->post('idpenyetor'.$i, TRUE);
            $jumlah         = str_replace(',','',$this->input->post('jumlah'.$i,TRUE));

            $this->mmaster->insertdetail($id, $idkliring, $idbank, $idpenyetor, $jumlah);
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
                'kode'   => $idocument,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function changestatus(){
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');   

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
        );
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');   

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */