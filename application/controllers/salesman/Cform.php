<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010307';

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

        $data['area'] = $this->mmaster->bacaarea();

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isalesman 			= $this->input->post('isalesman', TRUE);
        $iarea = $this->input->post('iarea', TRUE);
        $esalesmanname = $this->input->post('esalesmanname', TRUE);
        $esalesmanaddress = $this->input->post('esalesmanaddress', TRUE);
        $esalesmancity = $this->input->post('ecityname', TRUE);
        $esalesmanpostal = $this->input->post('esalesmanpostal', TRUE);
        $dsalesmanentry = date('Y-m-d');
        $fsalesmanaktif = 'TRUE';

        if ($isalesman != '' && $iarea != ''){
                $cekada = $this->mmaster->cek_data($isalesman);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isuppliergroup);
                    $this->mmaster->insert($isalesman,$iarea,$esalesmanname,$esalesmanaddress,$esalesmancity,$esalesmanpostal,$dsalesmanentry,$fsalesmanaktif);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isuppliergroup
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

        $isuppliergroup = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($isuppliergroup)->row()
        );

        $data['area'] = $this->mmaster->bacaarea();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isuppliergroup 			= $this->input->post('isuppliergroup', TRUE);
        $esuppliergroupname 		= $this->input->post('esuppliergroupname', TRUE);
        $esuppliergroupnameprint1	= $this->input->post('esuppliergroupnameprint1', TRUE);
        $esuppliergroupnameprint2	= $this->input->post('esuppliergroupnameprint2', TRUE);


        if ($isuppliergroup != '' && $esuppliergroupname != ''){
            $cekada = $this->mmaster->cek_data($isuppliergroup);
            if($cekada->num_rows() > 0){ 
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isuppliergroup);
                $this->mmaster->update($isuppliergroup,$esuppliergroupname,$esuppliergroupnameprint1,$esuppliergroupnameprint2);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isuppliergroup
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

        $isalesman = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($isalesman)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
