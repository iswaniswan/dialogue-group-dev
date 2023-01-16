<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050502';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekuser($username,$idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea($username,$idcompany),
            'iarea'     => $iarea
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder']);
    }

    public function view(){
        $dfrom     = $this->input->post('dfrom', TRUE);
        if ($dfrom =='') {
            $dfrom = $this->uri->segment(4);
        }
        $dto       = $this->input->post('dto', TRUE);
        if ($dto   =='') {
            $dto   = $this->uri->segment(5);
        }
        $iarea     = $this->input->post('iarea', TRUE);
        if ($iarea =='') {
            $iarea = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){
        $igiro  = $this->uri->segment(4);
        $igiro  = str_replace('%20',' ',$igiro);
        $iarea  = $this->uri->segment(5);
        $dfrom  = $this->uri->segment(6);
        $dto    = $this->uri->segment(7);
        $ipl    = $this->uri->segment(8);
        $idt    = $this->uri->segment(9);
        $xarea  = $this->uri->segment(10);
        if($ipl!=0 && $idt!=0) {
            $detail = $this->mmaster->bacadetail($iarea,$ipl,$idt);
        }else{
            $detail = '';
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'xarea'     => $xarea,
            'igiro'     => $igiro,
            'bank'      => $this->mmaster->bacabank(),
            'area'      => $this->mmaster->area(),
            'isi'       => $this->mmaster->baca($igiro,$iarea),
            'detail'    => $detail 
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
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

    public function getdetailcustomer(){
        header("Content-Type: application/json", true);
        $iarea     = $this->input->post('iarea');
        $icustomer = $this->input->post('icustomer');
        $data      = $this->mmaster->getdetailcustomer($iarea, $icustomer);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $igiro          = $this->input->post('igiro', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $ibank          = $this->input->post('ibank', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $dgiro          = $this->input->post('dgiro', TRUE);
        $dgirocair      = $this->input->post('dgirocair', TRUE);
        if($dgirocair!=''){
            $fgirocair  = 't';
            $tmp=explode("-",$dgirocair);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dgirocair=$th."-".$bl."-".$hr;
        }else{
            $fgirocair  = 'f';
            $dgirocair  = null;
        }
        if ((isset($igiro) && $igiro != '') && (isset($iarea) && $iarea != '') && (isset($icustomer) && $icustomer != '') && (isset($dgiro) && $dgiro != '') && (isset($ibank) && $ibank != '') && (isset($dgirocair) && $dgirocair != '')){
            $this->db->trans_begin();
            $this->mmaster->update($igiro,$iarea,$dgirocair,$fgirocair,$ibank);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update Giro Cair '.$igiro.' Pelanggan:'.$icustomer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $igiro
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
