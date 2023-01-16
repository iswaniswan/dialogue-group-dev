<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1090401';

    public function __construct(){
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
            'title'     => "Cetak ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function dataarea(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacaarea($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_area,  
                'text'  => $row->e_area_name
            );
        }
        echo json_encode($filter);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "List ".$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function cetak(){
        $id = $this->uri->segment(4);
        $id_company = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Print ".$this->global['title'],
            'id'            => $id,
            'company'       => $this->mmaster->company($id_company)->row(),
            'isi'           => $this->mmaster->baca($id),
            'detail'        => $this->mmaster->bacadetail($id),
        );

        $this->Logger->write('Cetak '.$this->global['title'].' No Nota : '.$id);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }

    public function cetakinclude(){
        $id = $this->uri->segment(4);
        $id_company = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Print ".$this->global['title'],
            'id'            => $id,
            'company'       => $this->mmaster->company($id_company)->row(),
            'isi'           => $this->mmaster->baca($id),
            'detail'        => $this->mmaster->bacadetail($id),
        );

        $this->Logger->write('Cetak '.$this->global['title'].' No Nota : '.$id);

        $this->load->view($this->global['folder'].'/vformrptinc', $data);
    }

    public function update(){
        $id     = $this->input->post('id');
        $this->db->trans_begin();
        $data = $this->mmaster->close($id);
        if($this->db->trans_status()===FALSE){
            $this->db->trans_rollback();
            echo 'fail';
        } else {
            $this->db->trans_commit();
            echo $id;
        }
    }
}

/* End of file Cform.php */
