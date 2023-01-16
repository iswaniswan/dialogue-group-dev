<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090602';

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
            'title'     => "Cetak ".$this->global['title'],
            'i_area'    => $this->mmaster->cekarea(),
            'area'      => $this->mmaster->bacaarea($username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        // $iarea  = $this->input->post('iarea');
      	$iarea  = $this->session->userdata('i_area');
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
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu,$status);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
      $dfrom	= $this->input->post('dfrom');
      $dto	= $this->input->post('dto');
      if($dfrom==''){
          $dfrom=$this->uri->segment(5);
      }
      if($dto==''){
          $dto=$this->uri->segment(6);
      }
      if($area==''){
          $area=$this->uri->segment(4);
      } 

      $data = array(
          'folder'        => $this->global['folder'],
          'title'         => "View ".$this->global['title'],
          'title_list'    => 'List '.$this->global['title'],
          'dfrom'         => $dfrom,
          'dto'           => $dto,
          'iarea'         => $area,
          // 'total'         => $this->mmaster->total($dfrom,$dto,$area)->row()
      );

      $this->Logger->write('Membuka Menu View '.$this->global['title']);

      $this->load->view($this->global['folder'].'/vformview', $data);
    }
    function cetak()
  {
      $isj  = $this->uri->segment(4);
      $area = substr($isj,8,2);
      $this->load->model('printsj/mmaster');
      $data['isj']=$isj;
      $data['page_title'] = $this->lang->line('printsj');
      $data['isi']=$this->mmaster->baca($isj,$area);
      $data['detail'] = $this->mmaster->bacadetail($isj,$area);
          $this->mmaster->updatesj($isj,$area);
      $data['host']=$_SERVER['REMOTE_ADDR'];
      $data['uri']  = $this->session->userdata('uri');
#     $data['isi']=$this->mmaster->baca($ispb,$iarea);
#      $data['detail']=$this->mmaster->bacadetail($ispb,$iarea);

      $data['iarea']  = $area;
      $this->Logger->write('Cetak SJ Area:'.$area.' No:'.$isj);
      if($area=='00')
      {
        $this->load->view('printsj/vformrpt',$data);
      }else{
        $this->load->view('printsj/vformrptcab',$data);
      }
#      $this->mmaster->close($iarea,$ispb);
  }
    function cetakinclude()
  {
      $isj  = $this->uri->segment(4);
      $area = substr($isj,8,2);
      $this->load->model('printsj/mmaster');
      $data['isj']=$isj;
      $data['page_title'] = $this->lang->line('printsj');
      $data['isi']=$this->mmaster->baca($isj,$area);
      $data['detail'] = $this->mmaster->bacadetail($isj,$area);
          $this->mmaster->updatesj($isj,$area);
      $data['host']=$_SERVER['REMOTE_ADDR'];
      $data['uri']  = $this->session->userdata('uri');
#     $data['isi']=$this->mmaster->baca($ispb,$iarea);
#      $data['detail']=$this->mmaster->bacadetail($ispb,$iarea);

      $data['iarea']  = $area;
      $this->Logger->write('Cetak SJ Area:'.$area.' No:'.$isj);
      if($area=='00')
      {
        $this->load->view('printsj/vformrptinc',$data);
      }else{
        $this->load->view('printsj/vformrptcabinc',$data);
      }
#      $this->mmaster->close($iarea,$ispb);
  }
}

/* End of file Cform.php */
