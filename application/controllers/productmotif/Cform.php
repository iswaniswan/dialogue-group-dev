<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010208';

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
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
        );
        
        $data['iproduct'] = $this->mmaster->bacaproduct();

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct 			= $this->input->post('iproduct', TRUE);
        $iproductmotif 		= $this->input->post('iproductmotif', TRUE);
        $eproductmotifname	= $this->input->post('eproductmotifname', TRUE);

        if ($iproduct != '' && $iproductmotif != ''){
                $cekada = $this->mmaster->cek_data($iproduct);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproduct);
                    $this->mmaster->insert($iproduct,$iproductmotif,$eproductmotifname);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iproduct
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

        $iproduct = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iproduct)->row()
        );
        $data['iproduct'] = $this->mmaster->bacaproduct();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iproduct 			= $this->input->post('iproduct', TRUE);
        $iproductmotif 		= $this->input->post('iproductmotif', TRUE);
        $eproductmotifname	= $this->input->post('eproductmotifname', TRUE);


        if ($iproduct != '' && $iproductmotif != ''){
            $cekada = $this->mmaster->cek_data($iproduct);
            if($cekada->num_rows() > 0){ 
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iproduct);
                $this->mmaster->update($iproduct,$iproductmotif,$eproductmotifname);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
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

        $iproduct = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iproduct)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
