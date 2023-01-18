<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090104';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => 'Cetak '.$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getarea(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getarea($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_area,  
                    'text'  => $kuy->i_area." - ".$kuy->e_area_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getspb(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('dfrom') !='' && $this->input->get('dto') !='' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom   = $this->input->get('dfrom', FALSE);
            $dto   = $this->input->get('dto', FALSE);
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getspb($cari,$dfrom,$dto,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_spb,  
                    'text'  => $kuy->i_spb." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function getspbto(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('dfrom') !='' && $this->input->get('dto') !='' && $this->input->get('iarea') !='' && $this->input->get('spbfrom') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom   = $this->input->get('dfrom', FALSE);
            $dto   = $this->input->get('dto', FALSE);
            $iarea      = $this->input->get('iarea', FALSE);
            $spbfrom      = $this->input->get('spbfrom', FALSE);
            $data       = $this->mmaster->getspbto($cari,$dfrom,$dto,$iarea,$spbfrom);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_spb,  
                    'text'  => $kuy->i_spb." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    function cetak()
    {
            $area   = $this->input->post('iarea');
            $spbfrom= $this->input->post('spbfrom');
            $spbto  = $this->input->post('spbto');
            $id_company = $this->session->userdata('id_company');
            $this->load->model('printspbkelompok/mmaster');
            $data['page_title'] = $this->lang->line('listspb');
            $data['master']=$this->mmaster->bacamaster($area,$spbfrom,$spbto);
#      $this->mmaster->close($area,$spbfrom,$spbto);
            $data['area']=$area;

            $data['user']   = $this->session->userdata('user_id');
#           $data['host']   = $this->session->userdata('printerhost');
            $data['host']=$_SERVER['REMOTE_ADDR'];
            $data['company']= $this->mmaster->company($id_company)->row();
            $data['uri']  = $this->session->userdata('uri');
            $this->load->model('logger');
            $this->Logger->write('Cetak SPB Area:'.$area.' No:'.$spbfrom.' s/d No:'.$spbto);
            $this->load->view('printspbkelompok/vformrpt',$data);
    }

    
}
/* End of file Cform.php */
