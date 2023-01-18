<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070416';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        echo $this->mmaster->data($dfrom,$dto,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iorderpb     = $this->input->post('iorderpb');
        $icustomer    = $this->input->post('icustomer');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iorderpb, $icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel OPB vs SJ : '.$icustomer.' No:'.$iorderpb);
            echo json_encode($data);
        }
    }

    public function edit(){
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='') && ($this->uri->segment(6)!='') && ($this->uri->segment(7)!='')){
            $iorderpb   = $this->uri->segment(4);
			$dfrom      = $this->uri->segment(5);
			$dto 	    = $this->uri->segment(6);
			// $tgl        = $this->uri->segment(7);
            $icustomer  = $this->uri->segment(7);
           // $iarea      = $this->mmaster->bacaarea();
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'iorderpb'      => $iorderpb,
                //'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'icustomer'     =>  $icustomer,
                'dto'           => $dto,
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($iorderpb,$icustomer)->row(),
                'detail'        => $this->mmaster->bacadetail($iorderpb,$icustomer)->result()
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }
}
/* End of file Cform.php */
