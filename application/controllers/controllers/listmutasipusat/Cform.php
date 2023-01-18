<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070325';

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
            'area'      => $this->mmaster->bacaarea(),
            'group'     => $this->mmaster->bacagroup(),
            'status'    => $this->mmaster->bacastatus(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);
    }
    
    public function view(){
        $iperiode           = $this->input->post('tahun').$this->input->post('bulan');
        $store              = $this->input->post('istore');
        $istorelocation     = $this->input->post('istorelocation');
        $iarea              = $this->input->post('iarea');
        $iproductgroup      = $this->input->post('iproductgroup');
        $iproductstatus     = $this->input->post('iproductstatus');
        if ($iarea=='AA') {
            $store = 'AA';
        }
        if($store==''){
            $query = $this->db->query("
                SELECT
                    i_store
                FROM
                    tr_area
                WHERE
                    i_area = '$iarea'
            ");
            if($query->num_rows() > 0){
                $st=$query->row();
                $store=$st->i_store;
            }
        }
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'istore'            => $store,
            'istorelocation'    => $istorelocation,
            'iperiode'          => $iperiode,
            'iarea'             => $iarea,
            'iproductgroup'     => $iproductgroup,
            'iproductstatus'    => $iproductstatus,
            'isi'               => $this->mmaster->baca($istorelocation,$iperiode,$store,$iproductgroup,$iproductstatus),
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
        echo json_encode($query);  
    }

    public function detail(){
        $iperiode       = $this->uri->segment(4);
        $iarea          = $this->uri->segment(5);
        $iproduct       = $this->uri->segment(6);
        $saldo          = $this->uri->segment(7);
        $istorelocation = $this->uri->segment(8);
        $iproductgrade  = $this->uri->segment(9);
        if($iproductgrade=='A'){
            $detail = $this->mmaster->detaila($istorelocation,$iperiode,$iarea,$iproduct,$iproductgrade);
        }else{
            $detail = $this->mmaster->detailb($istorelocation,$iperiode,$iarea,$iproduct,$iproductgrade);
        }
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'iperiode'          => $iperiode,
            'iarea'             => $iarea,
            'iproduct'          => $iproduct,
            'saldo'             => $saldo,
            'istorelocation'    => $istorelocation,
            'detail'            => $detail,
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
