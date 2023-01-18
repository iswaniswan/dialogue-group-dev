<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010207';

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
        $data['iproductgroup'] = $this->mmaster->bacagroup();
        $data['iproductseri'] = $this->mmaster->bacaseri();

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproducttype 			= $this->input->post('iproducttype', TRUE);
        $eproducttypename 		= $this->input->post('eproducttypename', TRUE);
        $eproducttypenameprint1	= $this->input->post('eproducttypenameprint1', TRUE);
        $eproducttypenameprint2	= $this->input->post('eproducttypenameprint2', TRUE);

        if ($iproducttype != '' && $eproducttypename != ''){
                $cekada = $this->mmaster->cek_data($iproducttype);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproducttype);
                    $this->mmaster->insert($iproducttype,$eproducttypename,$eproducttypenameprint1,$eproducttypenameprint2);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iproducttype
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

        $iproducttype = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iproducttype)->row()
        );
        $data['iproductgroup'] = $this->mmaster->bacagroup();
        $data['iproductseri'] = $this->mmaster->bacaseri();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproducttype 			= $this->input->post('iproducttype', TRUE);
        $eproducttypename 		= $this->input->post('eproducttypename', TRUE);
        $eproducttypenameprint1	= $this->input->post('eproducttypenameprint1', TRUE);
        $eproducttypenameprint2	= $this->input->post('eproducttypenameprint2', TRUE);


        if ($iproducttype != '' && $eproducttypename != ''){
            $cekada = $this->mmaster->cek_data($iproducttype);
            if($cekada->num_rows() > 0){ 
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iproducttype);
                $this->mmaster->update($iproducttype,$eproducttypename,$eproducttypenameprint1,$eproducttypenameprint2);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproducttype
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

        $iproducttype = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iproducttype)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
