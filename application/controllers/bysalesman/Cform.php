<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020408';

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
        if($dfrom!=''){
            $tmp = explode("-", $dfrom);
            $hr=$tmp[0]; 
            $bl=$tmp[1];
            $th=$tmp[2];
            $prevth  =$th-1;
        }
        if($bl <9 ){
            $bln = $bl+1;
            $bln = '0'.$bln;
            $akhir = $th.'-'.$bln.'-01';
            $prevakhir = $prevth.'-'.$bln.'-01';
        }elseif($bl >=9 && $bl <12){
            $bln = $bl+1;
            $akhir = $th.'-'.$bln.'-01';
            $prevakhir = $prevth.'-'.$bln.'-01';
        }elseif($bl ==12){
            $thn = $th+1;
            $akhir = $thn.'-01-01';
            $prevakhir = $prevth.'-01-01';
        }

        if($dto!=''){
            $tmpo = explode("-", $dto);
            $hari=$tmpo[0]; 
            $bulan=$tmpo[1];
            $tahun=$tmpo[2];
            $prevtahun  =$tahun-1;
        }

        if($bulan <9 ){
            $month = $bulan+1;
            $month = '0'.$month;
            $last = $tahun.'-'.$month.'-01';
            $prevlast = $prevtahun.'-'.$month.'-01';
        }elseif($bulan >=9 && $bulan <12){
            $month = $bulan+1;
            $last = $tahun.'-'.$month.'-01';
            $prevlast = $prevtahun.'-'.$month.'-01';
        }elseif($bulan ==12){
            $year = $tahun+1;
            $last = $year.'-01-01';
            $prevlast = $prevtahun.'-01-01';
        }
        $iuser = $this->session->userdata('username');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'tahun'         => $tahun,
            'th'            => $th,
            'prevth'        => $prevth,
            'bl'            => $bl,            
            'isi'           => $this->mmaster->baca($dfrom,$dto,$th,$prevth,$bl,$bulan,$tahun,$iuser,$akhir,$prevakhir,$last)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']." Periode : ".$tahun);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}
/* End of file Cform.php */
