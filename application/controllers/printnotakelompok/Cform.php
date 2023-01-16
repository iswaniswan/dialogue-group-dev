<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090403';

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

    public function getnotafrom(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('dfrom') !='' && $this->input->get('dto') !='' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom   = $this->input->get('dfrom', FALSE);
            $dto   = $this->input->get('dto', FALSE);
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getnotafrom($cari,$dfrom,$dto,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota." - ".$kuy->i_area
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function getnotato(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('dfrom') !='' && $this->input->get('dto') !='' && $this->input->get('iarea') !='' && $this->input->get('notafrom') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom   = $this->input->get('dfrom', FALSE);
            $dto   = $this->input->get('dto', FALSE);
            $iarea      = $this->input->get('iarea', FALSE);
            $notafrom      = $this->input->get('notafrom', FALSE);
            $data       = $this->mmaster->getnotato($cari,$dfrom,$dto,$iarea,$notafrom);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota." - ".$kuy->i_area
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
            $notafrom= $this->input->post('notafrom');
            $notato  = $this->input->post('notato');
            $id_company = $this->session->userdata('id_company');
            $this->load->model('printnotakelompok/mmaster');
            $data['page_title'] = $this->lang->line('list_nota');
            $data['master']=$this->mmaster->bacamaster($area,$notafrom,$notato);
#      $this->mmaster->close($area,$notafrom,$notato);
            $data['area']=$area;
            $data['company']=$this->mmaster->company($id_company)->row();

            $data['user']   = $this->session->userdata('user_id');
#           $data['host']   = $this->session->userdata('printerhost');
            $data['host']=$_SERVER['REMOTE_ADDR'];
            $data['uri']  = $this->session->userdata('uri');
            $this->load->model('logger');
            $this->Logger->write('Cetak Nota Area:'.$area.' No:'.$notafrom.' s/d No:'.$notato);
            $this->load->view('printnotakelompok/vformrpt',$data);
    }

    
}
/* End of file Cform.php */
