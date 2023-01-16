<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090503';

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

    public function getspmbfrom(){
        $filter = [];
        if($this->input->get('q') != ''&& $this->input->get('dfrom') !='' && $this->input->get('dto') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom   = $this->input->get('dfrom', FALSE);
            $dto   = $this->input->get('dto', FALSE);
            $iarea      = $this->session->userdata('i_area');
            $data       = $this->mmaster->getspmbfrom($cari,$dfrom,$dto,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_spmb,  
                    'text'  => $kuy->i_spmb.' - '.$kuy->i_area
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function getspmbto(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom   = $this->input->get('dfrom', FALSE);
            $dto   = $this->input->get('dto', FALSE);
            $iarea      = $this->session->userdata('i_area');
            $spmbfrom      = $this->input->get('spmbfrom', FALSE);
            $data       = $this->mmaster->getspmbto($cari,$dfrom,$dto,$iarea,$spmbfrom);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_spmb,  
                    'text'  => $kuy->i_spmb.' - '.$kuy->i_area
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    function cetak()
    {
            $spmbfrom= $this->input->post('spmbfrom');
            $spmbto  = $this->input->post('spmbto');
            $area      = $this->session->userdata('i_area');
            $id_company = $this->session->userdata('id_company');
            $this->load->model('printspmbkelompok/mmaster');
            $data['page_title'] = $this->lang->line('list_nota');
            $data['master']=$this->mmaster->bacamaster($area,$spmbfrom,$spmbto);
#      $this->mmaster->close($area,$spmbfrom,$spmbto);
            $data['company']=$this->mmaster->company($id_company)->row();
            $data['area']=$area;
            $data['user']   = $this->session->userdata('user_id');
#           $data['host']   = $this->session->userdata('printerhost');
            $data['host']=$_SERVER['REMOTE_ADDR'];
            $data['uri']  = $this->session->userdata('uri');
            $this->load->model('logger');
            $this->Logger->write('Cetak SPMB Area:'.$area.' No:'.$spmbfrom.' s/d No:'.$spmbto);
            $this->load->view('printspmbkelompok/vformrpt',$data);
    }

    
}
/* End of file Cform.php */
