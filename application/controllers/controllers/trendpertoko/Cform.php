<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020411';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
        require('php/fungsi.php');
    }  

    public function index(){
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function view(){
        $dfrom		= $this->input->post('dfrom');
        $dto  		= $this->input->post('dto');
        if($dfrom==''){
            $dfrom	= $this->uri->segment(4);
        } 
        if($dto==''){
            $dto	= $this->uri->segment(5);
        } 
        $tahun	= $this->uri->segment(6);
        if($dfrom!=''){
            $tmp=explode("-",$dfrom);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dfrom =$hr."-".$bl."-".$th;
            $thdfromkurang=$th-1;
            $dfromsebelumnya =$hr."-".$bl."-".$thdfromkurang;
            $thskrng=$th;
        }

        if($dto!=''){
            $tmp=explode("-",$dto);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dto =$hr."-".$bl."-".$th;
            $thdtokurang=$th-1;
            if((intval($thdtokurang)%4!=0)&&($bl=='02')&&($hr=='29')) $hr='28';
            $dtosebelumnya =$hr."-".$bl."-".$thdtokurang;
            $thnsebelumnya=$th-1;
        }
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'dfrom'             => $dfrom,
            'dto'               => $dto,
            'tahun'             => $tahun,
            'thskrng'           => $thskrng,
            'thnsebelumnya'     => $thnsebelumnya,
            'dfromsebelumnya'   => $dfromsebelumnya,  
            'dtosebelumnya'     => $dtosebelumnya,          
            'isi'               => $this->mmaster->baca($dfrom,$dto,$dfromsebelumnya,$dtosebelumnya)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']." Periode : ".$tahun);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}
/* End of file Cform.php */
