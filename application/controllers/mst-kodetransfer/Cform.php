<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010404';

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
		echo $this->mmaster->data($this->i_menu);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kode_transfer' => $this->mmaster->get_kodetransfer()->result(),
            'pelanggan'     => $this->mmaster->get_pelanggan()->result(),  
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icustomer          = $this->input->post('icustomer', TRUE); 
        $icustomertransfer  = $this->input->post('icustomertransfer', TRUE);

        if ($icustomertransfer != ''){
                $cekada = $this->mmaster->cek_data($icustomertransfer);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$icustomertransfer);
                    $this->mmaster->insert($icustomertransfer, $icustomer);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $icustomertransfer
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

        $icustomertransfer = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($icustomertransfer)->row(),
            'kode_transfer' => $this->mmaster->get_kodetransfer()->result(),
            'pelanggan'     => $this->mmaster->get_pelanggan()->result(),  
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $icustomer          = $this->input->post('icustomer', TRUE); 
        $icustomertransfer  = $this->input->post('icustomertransfer', TRUE);

        if ($icustomertransfer != ''){
            $cekada = $this->mmaster->cek_data($icustomertransfer);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($icustomertransfer, $icustomer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $icustomertransfer
                );
            }else{
                $data = array(
                    'sukses' => false
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $icustomertransfer= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($icustomertransfer)->row(),
            'kode_transfer' => $this->mmaster->get_kodetransfer()->result(),
            'pelanggan'     => $this->mmaster->get_pelanggan()->result(),  
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */