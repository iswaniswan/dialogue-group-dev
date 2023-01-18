<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10305';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->model($this->global['folder'].'/mmaster');
        require_once("php/fungsi.php");
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $dfrom = $this->input->post('dfrom', TRUE);
        $dto   = $this->input->post('dto', TRUE);
        $nlama = $this->input->post('nlamaorder', TRUE);
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom=$th."-".$bl."-".$hr;
        }
        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto=$th."-".$bl."-".$hr;
        }
        $tmp=explode("-",$dfrom);
        $th1=$tmp[0];
        $bl1=$tmp[1];
        $hr1=$tmp[2];
        $tmp=explode("-",$dto);
        $th2=$tmp[0];
        $bl2=$tmp[1];
        $hr2=$tmp[2];
        if(($th1==$th2)&&($bl1==$bl2)&&($hr1=='01')&&($hr2=='28'||$hr2=='29'||$hr2=='30'||$hr2=='31')){
            $this->mmaster->simpan($nlama,$dfrom,$dto);
        }
        $this->Logger->write('Proses Update kunjungan Periode:'.$dfrom.' s/d '.$dto);
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
            'dfrom'  => $dfrom,
            'dto'    => $dto,
            'nlama'  => $nlama,
            'data'   => $this->mmaster->baca($nlama,$dfrom,$dto)
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function kunjungan(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $isalesman  = $this->uri->segment(6);
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom=$th."-".$bl."-".$hr;
        }
        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto=$th."-".$bl."-".$hr;
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'isalesman' => $isalesman,
            'data'      => $this->mmaster->bacakunjungan($isalesman,$dfrom,$dto)
        );
        $this->load->view($this->global['folder'].'/vformviewkunjungan', $data);
    }

    public function order(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $isalesman  = $this->uri->segment(6);
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom=$th."-".$bl."-".$hr;
        }
        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto=$th."-".$bl."-".$hr;
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'isalesman' => $isalesman,
            'data'      => $this->mmaster->bacaorder($isalesman,$dfrom,$dto)
        );
        $this->load->view($this->global['folder'].'/vformvieworder', $data);
    }
}
/* End of file Cform.php */
