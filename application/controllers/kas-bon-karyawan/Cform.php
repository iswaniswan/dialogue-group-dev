<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040301';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
       $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->data($this->global['folder'],$this->i_menu, $dfrom, $dto);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "BON-".date('ym')."-123456",            
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
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

    public function departement(){
        $filter = [];
        $data   = $this->mmaster->departement(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->i_departement, 
                    'text'  => $row->e_departement_name,
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

    public function karyawan(){
        $filter = [];
        $data   = $this->mmaster->karyawan(strtoupper($this->input->get('q')),$this->input->get('idepartement'));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id, 
                    'text'  => $row->e_nama_karyawan,
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

    public function simpan(){
       
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $idepartement   = $this->input->post('idepartement', TRUE);
        $ikaryawan      = $this->input->post('ikaryawan', TRUE);
        $vjumlah        = $this->input->post('vjumlah', TRUE);
        $vjumlah        = str_replace(',','',$vjumlah);
        $ekeperluan     = $this->input->post('ekeperluan', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);

        if ((isset($ibagian) && $ibagian != '') && (isset($idepartement) && $idepartement != '') && (isset($ikaryawan) && $ikaryawan != '') && (isset($vjumlah) && $vjumlah != '') && (isset($ekeperluan) && $ekeperluan != '')){
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();

            $this->mmaster->insert($id, $ibagian, $idocument, $datedocument, $idepartement, $ikaryawan, $vjumlah, $ekeperluan, $eremark);

            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Giro No:'.$idocument);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idocument,
                    'id'        => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false
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

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'number'        => "BON-".date('ym')."-123456",               
                'data'          => $this->mmaster->get_data($id)->row(),
            );              

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $idepartement   = $this->input->post('idepartement', TRUE);
        $ikaryawan      = $this->input->post('ikaryawan', TRUE);
        $vjumlah        = $this->input->post('vjumlah', TRUE);
        $vjumlah        = str_replace(',','',$vjumlah);
        $ekeperluan     = $this->input->post('ekeperluan', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);

         if ((isset($ibagian) && $ibagian != '') && (isset($idepartement) && $idepartement != '') && (isset($ikaryawan) && $ikaryawan != '') && (isset($vjumlah) && $vjumlah != '') && (isset($ekeperluan) && $ekeperluan != '')){
            $this->db->trans_begin();

            $this->mmaster->update($id, $ibagian, $idocument, $datedocument, $idepartement, $ikaryawan, $vjumlah, $ekeperluan, $eremark);

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$idocument);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idocument,
                    'id'        => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);  
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "View ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'dfrom'         => $dfrom,
                'dto'           => $dto,              
                'data'          => $this->mmaster->get_data($id)->row(),
            );              
        $this->Logger->write('Membuka Menu ' . $this->global['title']) . ' Tanggal : ' . $dfrom . ' S/d : ' . $dto;
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval(){
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Approval ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'dfrom'         => $dfrom,
                'dto'           => $dto,              
                'data'          => $this->mmaster->get_data($id)->row(),
            );              
        $this->Logger->write('Membuka Menu ' . $this->global['title']) . ' Tanggal : ' . $dfrom . ' S/d : ' . $dto;
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */