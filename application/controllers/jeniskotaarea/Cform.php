<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010313';

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
        $data['citytype'] = $this->mmaster->bacajeniskota();
        $data['area'] = $this->mmaster->bacaarea();

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea 			= $this->input->post('iarea', TRUE);
        $eareaname = $this->input->post('eareaname', TRUE);
        $iareatype = $this->input->post('iareatype', TRUE);
        $istore = $this->input->post('istore', TRUE);
        $eareaaddress = $this->input->post('eareaaddress', TRUE);
        $eareacity = $this->input->post('eareacity', TRUE);
        $eareashortname = $this->input->post('eareashortname', TRUE);
        $eareaphone = $this->input->post('eareaphone', TRUE);
        $nareatoleransi = $this->input->post('nareatoleransi', TRUE);
        $earearemark = $this->input->post('earearemark', TRUE);


        if ($iarea != ''){
                $cekada = $this->mmaster->cek_data($iarea);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iarea);
                    $this->mmaster->insert($iarea,$eareaname,$iareatype,$istore,$eareaaddress,$eareacity,$eareashortname,$eareaphone,$nareatoleransi,$earearemark);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iarea
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

        $iarea = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iarea)->row()
        );

        $data['citytype'] = $this->mmaster->bacajeniskota();
        $data['area'] = $this->mmaster->bacaarea();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iarea 			= $this->input->post('isuppliergroup', TRUE);
        $esuppliergroupname 		= $this->input->post('esuppliergroupname', TRUE);
        $esuppliergroupnameprint1	= $this->input->post('esuppliergroupnameprint1', TRUE);
        $esuppliergroupnameprint2	= $this->input->post('esuppliergroupnameprint2', TRUE);


        if ($iarea != '' && $esuppliergroupname != ''){
            $cekada = $this->mmaster->cek_data($iarea);
            if($cekada->num_rows() > 0){ 
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iarea);
                $this->mmaster->update($iarea,$esuppliergroupname,$esuppliergroupnameprint1,$esuppliergroupnameprint2);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iarea
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

        $iarea = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iarea)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
