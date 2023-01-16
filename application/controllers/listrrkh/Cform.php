<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070210';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        require('php/fungsi.php');
        $this->load->model($this->global['folder'].'/mmaster');
    }

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
            'iarea'  => $iarea,
            'area'   => $this->mmaster->getarea($username, $idcompany, $iarea)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }  

    public function view(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $iarea      = $this->input->post('iarea');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        if($iarea=='') {
            $iarea   = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => 'List '.$this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
        );
        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $iarea      = $this->input->post('iarea');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        if($iarea=='') {
            $iarea   = $this->uri->segment(6);
        }
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto,$iarea);
    }

    public function edit(){
        $drrkh      = date('Y-m-d', strtotime($this->uri->segment(4)));
        $isalesman  = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $tmp =explode("-",$drrkh);
        $th=$tmp[0];
        $bl=$tmp[1];
        $hr=$tmp[2];
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => 'Update '.$this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'drrkh'     => date('d-m-Y', strtotime($drrkh)),
            'hari'      => dinten($hr,$bl,$th),
            'area'      => $this->mmaster->bacaarea(),
            'kunjungan' => $this->mmaster->bacakunjungan(),
            'isi'       => $this->mmaster->baca($drrkh,$isalesman,$iarea),
            'detail'    => $this->mmaster->bacadetail($drrkh,$isalesman,$iarea),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getsalesman($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_salesman,  
                    'text'  => $kuy->i_salesman." - ".$kuy->e_salesman_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcustomer($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcity(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcity($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_city,  
                    'text'  => $kuy->i_city." - ".$kuy->e_city_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $folder     = $this->global['folder'];
        $isalesman  = $this->input->post('isalesman', TRUE);
        $drrkh      = $this->input->post('drrkh', TRUE);
        $drrkhasal  = $this->input->post('drrkhasal', TRUE);
        $dreceive1  = $this->input->post('dreceive1', TRUE);
        if($drrkh!=''){
            $drrkh  = date('Y-m-d', strtotime($drrkh));
        }
        if($dreceive1!=''){
            $drec1  = date('Y-m-d', strtotime($dreceive1));
        }else{
            $drec1='';
        }
        $iarea      = $this->input->post('iarea', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if($drrkh!='' && $isalesman!='' && $iarea!=''){
            $this->db->trans_begin();
            $this->mmaster->updateheader($isalesman,$drrkh,$drrkhasal,$iarea,$drec1);
            for($i=1;$i<=$jml;$i++){
                $icustomer            = $this->input->post('icustomer'.$i, TRUE);
                $ikunjungantype       = $this->input->post('ikunjungantype'.$i, TRUE);
                $icity                = $this->input->post('icity'.$i, TRUE);
                $fkunjunganrealisasi  = $this->input->post('fkunjunganrealisasi'.$i, TRUE);
                if($fkunjunganrealisasi == 'on') {
                    $fkunjunganrealisasi ='t'; 
                }else{
                    $fkunjunganrealisasi ='f';
                }
                $fkunjunganvalid      = $this->input->post('fkunjunganvalid'.$i, TRUE);
                if($fkunjunganvalid == 'on'){
                    $fkunjunganvalid = 't';
                }else{
                    $fkunjunganvalid = 'f';
                }
                $eremark              = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($drrkh,$isalesman,$iarea,$icustomer);
                $this->mmaster->insertdetail($isalesman,$drrkh,$iarea,$icustomer,$ikunjungantype,$icity,$fkunjunganrealisasi,$fkunjunganvalid,$eremark,$i);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update RRKH Salesman:'.$isalesman.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => "Tanggal ".$drrkh." / Area ".$iarea." / Salesman ".$isalesman
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }

    public function cancel(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isalesman  = $this->input->post('isalesman');
        $drrkh      = $this->input->post('drrkh');
        $iarea      = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isalesman,$drrkh,$iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel RRKH Tanggal : '.$drrkh.' Salesman : '.$isalesman.' Area : '.$iarea);
            echo json_encode($data);
        }
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isalesman  = $this->input->post('isalesman');
        $drrkh      = $this->input->post('drrkh');
        $iarea      = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->approve($isalesman,$drrkh,$iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Approve RRKH Tanggal : '.$drrkh.' Salesman : '.$isalesman.' Area : '.$iarea);
            echo json_encode($data);
        }
    }

    public function batalapprove(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isalesman  = $this->input->post('isalesman');
        $drrkh      = $this->input->post('drrkh');
        $iarea      = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->batalapprove($isalesman,$drrkh,$iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Batal Approve RRKH Tanggal : '.$drrkh.' Salesman : '.$isalesman.' Area : '.$iarea);
            echo json_encode($data);
        }
    }

    public function deleteitem(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $drrkh      = date('Y-m-d', strtotime($this->input->post('drrkh', TRUE)));
        $isalesman  = $this->input->post('isalesman', TRUE);
        $iarea      = $this->input->post('iarea', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetail($drrkh,$isalesman,$iarea,$icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Menghapus Detail Item RRKH Tanggal : '.$drrkh.' Salesman : '.$isalesman.' Area : '.$iarea.' Kodelang : '.$icustomer);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */
