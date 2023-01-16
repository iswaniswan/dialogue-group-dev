<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040303';

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
            'number'        => "GRP-".date('ym')."-123456",            
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

    public function area(){
        $filter = [];
        $data   = $this->mmaster->area(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->e_area,
            );
        }
        echo json_encode($filter);
    }

    public function customer(){
        $filter = [];
        $data   = $this->mmaster->customer(strtoupper($this->input->get('q')),$this->input->get('iarea'));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->e_customer_name,
            );
        }
        echo json_encode($filter);
    }

    public function karyawan(){
        $filter = [];
        $data   = $this->mmaster->karyawan(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->e_nama_karyawan,
            );
        }
        echo json_encode($filter);
    }

    public function bank(){
        $filter = [];
        $data   = $this->mmaster->bank(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->e_bank_name,
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
        $igiro          = $this->input->post('igiro', TRUE);
        $dgiro          = $this->input->post('dgiro', TRUE);
        if ($dgiro) {
            $tmp   = explode('-', $dgiro);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dategiro = $year . '-' . $month . '-' . $day;
        } 
        $dgiroduedate   = $this->input->post('dgiroduedate', TRUE);
        if ($dgiroduedate) {
            $tmp   = explode('-', $dgiroduedate);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dategiroduedate = $year . '-' . $month . '-' . $day;
        }
        $dsetor         = $this->input->post('dsetor', TRUE);
        if ($dsetor) {
            $tmp   = explode('-', $dsetor);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datesetor = $year . '-' . $month . '-' . $day;
        }
        $dgiroterima    = $this->input->post('dgiroterima', TRUE);
        if ($dgiroterima) {
            $tmp   = explode('-', $dgiroterima);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dategiroterima = $year . '-' . $month . '-' . $day;
        }
        $ikaryawan      = $this->input->post('ikaryawan', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ibank          = $this->input->post('ibank', TRUE);
        $vjumlah        = $this->input->post('vjumlah', TRUE);
        $vjumlah        = str_replace(',','',$vjumlah);
        $eremarkh       = $this->input->post('eremarkh', TRUE);

        if ((isset($ibagian) && $ibagian != '') && (isset($igiro) && $igiro != '') && (isset($icustomer) && $icustomer != '') && (isset($ikaryawan) && $ikaryawan != '') && (isset($iarea) && $iarea != '') && (isset($ibank) && $ibank != '')){
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();

            $this->mmaster->insert($id, $ibagian, $idocument, $datedocument, $igiro, $dategiro, $dategiroduedate, $datesetor, $dategiroterima, $ikaryawan, $iarea, $icustomer, $ibank, $vjumlah, $eremarkh);

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
                'number'        => "GRP-".date('ym')."-123456",               
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
        $igiro          = $this->input->post('igiro', TRUE);
        $dgiro          = $this->input->post('dgiro', TRUE);
        if ($dgiro) {
            $tmp   = explode('-', $dgiro);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dategiro = $year . '-' . $month . '-' . $day;
        } 
        $dgiroduedate   = $this->input->post('dgiroduedate', TRUE);
        if ($dgiroduedate) {
            $tmp   = explode('-', $dgiroduedate);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dategiroduedate = $year . '-' . $month . '-' . $day;
        }
        $dsetor         = $this->input->post('dsetor', TRUE);
        if ($dsetor) {
            $tmp   = explode('-', $dsetor);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datesetor = $year . '-' . $month . '-' . $day;
        }
        $dgiroterima    = $this->input->post('dgiroterima', TRUE);
        if ($dgiroterima) {
            $tmp   = explode('-', $dgiroterima);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dategiroterima = $year . '-' . $month . '-' . $day;
        }
        $ikaryawan      = $this->input->post('ikaryawan', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ibank          = $this->input->post('ibank', TRUE);
        $vjumlah        = $this->input->post('vjumlah', TRUE);
        $vjumlah        = str_replace(',','',$vjumlah);
        $eremarkh       = $this->input->post('eremarkh', TRUE);

         if ((isset($ibagian) && $ibagian != '') && (isset($igiro) && $igiro != '') && (isset($icustomer) && $icustomer != '') && (isset($ikaryawan) && $ikaryawan != '') && (isset($iarea) && $iarea != '') && (isset($ibank) && $ibank != '')){
            $this->db->trans_begin();

            $this->mmaster->update($id, $ibagian, $idocument, $datedocument, $igiro, $dategiro, $dategiroduedate, $datesetor, $dategiroterima, $ikaryawan, $iarea, $icustomer, $ibank, $vjumlah, $eremarkh);

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
                'title'         => "Approve ".$this->global['title'],
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