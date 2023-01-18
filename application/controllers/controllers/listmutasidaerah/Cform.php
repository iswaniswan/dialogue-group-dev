<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070309';

    public function __construct(){
        parent::__construct();
        cek_session();
        require('php/fungsi.php');
        
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'area'      => $this->mmaster->bacaarea()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }
    
    public function view(){
        $bulan              = $this->input->post('bulan');
        $tahun              = $this->input->post('tahun');
        $istore             = $this->input->post('istore');
        $istorelocation     = $this->input->post('istorelocation');
        $iarea              = $this->input->post('iarea');
        
        if($bulan == ''){
            $bulan = $this->uri->segment(4);
        }

        if($tahun == ''){
            $tahun = $this->uri->segment(5);
        }

        if($istore == ''){
            $istore = $this->uri->segment(6);
        }

        if($istorelocation == ''){
            $istorelocation = $this->uri->segment(7);
        }

        $iperiode = $tahun.$bulan;
        // var_dump($iperiode);
        // var_dump($istore);
        // var_dump($istorelocation);
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'istore'            => $istore,
            'istorelocation'    => $istorelocation,
            'iperiode'          => $iperiode,
            'iarea'             => $iarea,
            'isi'               => $this->mmaster->baca($istore,$iperiode,$istorelocation),
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$iperiode);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function getstore(){
        header("Content-Type: application/json", true);
        $istore  = $this->input->post('istore');
        $query  = array(
            'isi' => $this->mmaster->bacastore($istore)->row(),
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function detail(){
        $iperiode       = $this->uri->segment(4);
        $iarea          = $this->uri->segment(5);
        $iproduct       = $this->uri->segment(6);
        $saldo          = $this->uri->segment(7);
        $istorelocation = $this->uri->segment(8);
        $eproductname   = $this->uri->segment(9);
        $istore         = $this->uri->segment(10);
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        =>'List '.$this->global['title'],
            'iperiode'          => $iperiode,
            'iarea'             => $iarea,
            'iproduct'          => $iproduct,
            'saldo'             => $saldo,
            'istorelocation'    => $istorelocation,
            'eproductname'      => $eproductname,
            'istore'            => $istore,
            'detail'            => $this->mmaster->detail($istorelocation,$iperiode,$iarea,$iproduct)
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function cetakdetail(){
        $iperiode = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $iproduct = $this->uri->segment(6);
        $saldo    = $this->uri->segment(7);
        $istorelocation  = $this->uri->segment(8);

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        =>'List '.$this->global['title'],
            'iperiode'          => $iperiode,
            'iarea'             => $iarea,
            'iproduct'          => $iproduct,
            'saldo'             => $saldo,
            'istorelocation'    => $istorelocation,
            'detail'            => $this->mmaster->detail($istorelocation,$iperiode,$iarea,$iproduct)
        );
        $this->load->view($this->global['folder'].'/vformprintdetail', $data);
    }
}

/* End of file Cform.php */
