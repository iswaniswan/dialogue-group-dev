<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070207';

    public function __construct()
    {
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
    	$dfrom = $this->uri->segment('4');
        $dto = $this->uri->segment('5');
        $iarea = $this->uri->segment('6');
        $icity = $this->uri->segment('7');
            
    	echo $this->mmaster->data($dfrom,$dto,$iarea,$icity);
    }
    
    public function view(){
    	$dfrom = $this->input->post('dfrom');
        $dto   = $this->input->post('dto');
        $iarea = $this->input->post('iarea');
        $icity   = $this->input->post('icity');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'iarea' => $iarea,
            'icity' => $icity
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function bacaarea(){
        $iuser = $this->session->userdata('username');
        $filter = [];
		$query = $this->db->query("select * from tr_area where (i_area in ( select i_area from public.tm_user_area where username='$iuser') ) order by i_area",false);
        foreach($query->result() as  $iarea){
                $filter[] = array(
                'id' => $iarea->i_area,  
                'text' => $iarea->i_area."-".$iarea->e_area_name
            );
        }
        echo json_encode($filter);
    }

    function bacakota(){
        $iarea   = $this->input->get('iarea');
        $filter = [];
        $query = $this->db->query(" select i_city, e_city_name from tr_city where i_area = '$iarea' order by i_city", false);
        foreach($query->result() as  $icity){
                $filter[] = array(
                'id' => $icity->i_city,  
                'text' => $icity->i_city."-".$icity->e_city_name
            );
        }
        echo json_encode($filter);
    }
}

/* End of file Cform.php */
