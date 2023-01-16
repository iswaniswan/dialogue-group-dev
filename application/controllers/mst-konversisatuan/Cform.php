<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010208';

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
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu,$this->global['folder']);
    }

    public function status(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function cekkode(){
        $kode = $this->input->post('kode');
        $query = $this->mmaster->cekkode($kode);
        if($query->num_rows() > 0){
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'satuan'            => $this->mmaster->get_satuan()->result(),     
            'rumuskonversi'     => $this->mmaster->getkonversi()->result()   
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $kodekonversi    = $this->input->post('kodekonversi', TRUE);
        $isatuanawal     = $this->input->post('isatuanawal', TRUE); 
        $isatuankonversi = $this->input->post('isatuankonversi', TRUE);
        $nfaktorkonversi = $this->input->post('nfaktorkonversi', TRUE);
        $irumuskonversi  = $this->input->post('irumuskonversi', TRUE);
        $idcompany       = $this->session->userdata('id_company');

        if ($kodekonversi != '' && $isatuanawal != '' && $isatuankonversi != '' && $irumuskonversi != ''){
                $cekada = $this->mmaster->cek_data($kodekonversi, $idcompany);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$kodekonversi);
                    $this->mmaster->insert($kodekonversi, $isatuanawal, $isatuankonversi, $nfaktorkonversi, $irumuskonversi);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $kodekonversi
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $kodekonversi = $this->uri->segment(4);
        $idcompany    = $this->session->userdata('id_company');

        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'data'           => $this->mmaster->cek_data($kodekonversi, $idcompany)->row(),
            'satuan'         => $this->mmaster->get_satuan()->result(),     
            'rumuskonversi'  => $this->mmaster->getkonversi()->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id              = $this->input->post('id', TRUE);
        $kodekonversi    = $this->input->post('kodekonversi', TRUE);
        $isatuanawal     = $this->input->post('isatuanawal', TRUE); 
        $isatuankonversi = $this->input->post('isatuankonversi', TRUE);
        $nfaktorkonversi = $this->input->post('nfaktorkonversi', TRUE);
        $irumuskonversi  = $this->input->post('irumuskonversi', TRUE);
        $idcompany       = $this->session->userdata('id_company');

        if ($kodekonversi != '' && $isatuanawal != '' && $isatuankonversi != '' && $irumuskonversi != ''){             
            $this->mmaster->update($id, $kodekonversi, $isatuanawal, $isatuankonversi, $nfaktorkonversi, $irumuskonversi, $idcompany);
            $data = array(
                'sukses'    => true,
                'kode'      => $kodekonversi
            );
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $kodekonversi = $this->uri->segment(4);
        $idcompany    = $this->session->userdata('id_company');

        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'data'           => $this->mmaster->cek_data($kodekonversi, $idcompany)->row(),
            'satuan'         => $this->mmaster->get_satuan()->result(),     
            'rumuskonversi'  => $this->mmaster->getkonversi()->result()
            
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */