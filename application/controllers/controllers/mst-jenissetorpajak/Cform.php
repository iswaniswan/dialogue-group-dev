<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011002';
   
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
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'setoran_pajak'     => $this->mmaster->get_setoranpajak()->result(),
            'akun_pajak'        => $this->mmaster->get_akunpajak()->result(),         
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isetoran       = $this->input->post('isetoran', TRUE);
        $iakunpajak     = $this->input->post('iakunpajak', TRUE);
        $ijsetorpajak   = $this->input->post('ijsetorpajak', TRUE);
        $ejsetorpajak   = $this->input->post('ejsetorpajak', TRUE);
        $eketerangan    = $this->input->post('eketerangan', TRUE);
                   
        
        if ($iakunpajak != ''){
                $cekada = $this->mmaster->cek_data($isetoran);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isetoran);
                    $this->mmaster->insert($isetoran, $iakunpajak, $ijsetorpajak, $ejsetorpajak, $eketerangan);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isetoran
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

        $isetoran = $this->uri->segment('4');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($isetoran)->row(),
            'setoran_pajak'     => $this->mmaster->get_setoranpajak()->result(),
            'akun_pajak'        => $this->mmaster->get_akunpajak()->result(),            
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isetoran       = $this->input->post('isetoran', TRUE);
        $iakunpajak     = $this->input->post('iakunpajak', TRUE);
        $ijsetorpajak   = $this->input->post('ijsetorpajak', TRUE);
        $ejsetorpajak   = $this->input->post('ejsetorpajak', TRUE);
        $eketerangan    = $this->input->post('eketerangan', TRUE);
        
        if ($isetoran != '' && $iakunpajak != ''){
            $cekada = $this->mmaster->cek_data($isetoran);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($isetoran, $iakunpajak, $ijsetorpajak, $ejsetorpajak, $eketerangan);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isetoran
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

        $isetoran= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($isetoran)->row(),
            'setoran_pajak' => $this->mmaster->get_setoranpajak()->result(),
            'akun_pajak'    => $this->mmaster->get_akunpajak()->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */