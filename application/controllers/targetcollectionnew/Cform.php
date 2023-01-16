<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1051401';
    #public $i_menu = '10080420';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'periode'   => '',
            'xy'        => ''

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformawal', $data);

    }

    /*function data(){
        $iperiode         = $this->uri->segment('4');
        $query = $this->mmaster->baca($iperiode);
        foreach($query->result() as  $row){
            if($row->realisasi==null || $row->realisasi=='')
            $row->realisasi=0;
            if($row->total!=0){
                $persen=number_format(($row->realisasi/$row->total)*100,2);
            }else{
                $persen='0.00';
            }
            if($row->realisasinon==null || $row->realisasinon=='')
                $row->realisasinon=0;
            if($row->totalnon!=0){
                $persennon=number_format(($row->realisasinon/$row->totalnon)*100,2);
            }else{
                $persennon='0.00';
            }
        }
        echo $this->mmaster->data($iperiode,$persen,$persennon);
    }*/

    function view(){
        $iperiode	= $this->input->post('iperiode');
        $this->db->trans_begin();
		if($iperiode==''){
      	    $iperiode=$this->uri->segment(4);
        }
        $bl=substr($iperiode,4,2);
        $th=substr($iperiode,0,4);
        if($bl=='12'){
          $th=$th+1;
          $bl='01';
        }else{
          $bl=$bl+1;
        }
        settype($th,'string');
        settype($bl,'string');
        if(strlen($bl)==1)
        $bl='0'.$bl;
        $batas=$th.'-'.$bl.'-01';
        $this->mmaster->simpan($iperiode,$batas);
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'iperiode'   => $iperiode,
            'isi'       => $this->mmaster->baca($iperiode)
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
}
/* End of file Cform.php */