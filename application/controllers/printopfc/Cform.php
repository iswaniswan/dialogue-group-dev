<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090701';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Cetak ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
      	// $iarea  = $this->session->userdata('i_area');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        // if($iarea==''){
        //     $iarea=$this->uri->segment(6);
        // }
        $idcompany  = $this->session->userdata('id_company');
        $username   = $this->session->userdata('username');
        //$iperiode   = $this->mmaster->cekperiode();
        $status     = $this->mmaster->cekstatus($idcompany,$username);
        echo $this->mmaster->data($dfrom,$dto,$this->global['folder'],$this->i_menu,$status);
    }
    
    public function view(){
      $dfrom	= $this->input->post('dfrom');
      $dto	= $this->input->post('dto');
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
          'dto'           => $dto,
          // 'total'         => $this->mmaster->total($dfrom,$dto,$area)->row()
      );

      $this->Logger->write('Membuka Menu View '.$this->global['title']);

      $this->load->view($this->global['folder'].'/vformview', $data);
    }
    function cetak()
  {
      $id_company = $this->session->userdata('id_company');
      $iop = $this->uri->segment(4);
      $this->load->model('printoprekap/mmaster');
      $data['iop']=$iop;
      $data['page_title'] = $this->lang->line('printop');
      $data['isi']=$this->mmaster->baca($iop);
      $data['detail']=$this->mmaster->bacadetail($iop);
      $data['company']=$this->mmaster->company($id_company)->row();
      $data['host']=$_SERVER['REMOTE_ADDR'];
      $data['uri']  = $this->session->userdata('uri');
#     $data['isi']=$this->mmaster->baca($ispb,$iarea);
#      $data['detail']=$this->mmaster->bacadetail($ispb,$iarea);
      $this->Logger->write('Cetak OP No:'.$iop);
      $this->load->view('printopfc/vformrpt',$data);
  }
}

/* End of file Cform.php */
