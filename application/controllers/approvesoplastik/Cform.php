<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2051312';

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
    }

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function gudang(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_master_gudang');
            $this->db->where('i_kode_master','GD10003');
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_kode_master,
                    'text' => $itype->e_nama_master,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom          = $this->input->post("dfrom",true);
        $dto            = $this->input->post("dto",true);
        $ikodemaster    = $this->input->post("ikodemaster",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => $this->global['title'],
            'gudang'        => $this->mmaster->bacagudang($ikodemaster)->row(),
            'kodemaster'    => $ikodemaster,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'data'          => $this->mmaster->cek_data($dfrom, $dto, $ikodemaster)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($dfrom, $dto, $ikodemaster)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }   
            $ikodeso        = $this->input->post('ikodeso', TRUE);
            $ikodemaster    = $this->input->post('ikodemaster', TRUE);
            $dfrom          = $this->input->post('dfrom', TRUE);
            if($dfrom!=''){
                $tmp=explode("-",$dfrom);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dateso=$th."-".$bl."-".$hr;
            }

            $jml            = $this->input->post('jml', TRUE);

            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikodeso);
            $this->mmaster->updateheader($ikodeso, $ikodemaster, $bl, $th);
                
            for($i=1;$i<=$jml;$i++){  
                    //$ikodeso    = $this->input->post('ikodeso'.$i, TRUE);
                    $imaterial  = $this->input->post('imaterial'.$i, TRUE);
                    $this->mmaster->updatedetail($ikodeso, $imaterial);
            }
        
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodeso,
                );
            }
    $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */