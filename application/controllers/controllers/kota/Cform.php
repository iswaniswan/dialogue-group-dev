<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010315';

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
        $data['jeniskota'] = $this->mmaster->bacajeniskota();
        $data['grupkota'] = $this->mmaster->bacagrupkota();
        $data['statuskota'] = $this->mmaster->bacastatuskota();

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icity 			= $this->input->post('icity', TRUE);
        $iarea = $this->input->post('iarea',TRUE);
        $icitytype = $this->input->post('icitytype',TRUE);
        $icitytypearea = $this->input->post('icitytypearea',TRUE);
        $icitygroup = $this->input->post('icitygroup',TRUE);
        $icitystatus = $this->input->post('icitystatus',TRUE);
        $ecityname = $this->input->post('ecityname',TRUE);
        $ntoleransipusat = $this->input->post('ntoleransipusat',TRUE);
        $ntoleransicabang = $this->input->post('ntoleransicabang',TRUE);


        if ($icity != ''){
                $cekada = $this->mmaster->cek_data($icity);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$icity);
                    $this->mmaster->insert($icity,$iarea,$icitytype,$icitytypearea,$icitygroup,$icitystatus,$ecityname,$ntoleransipusat,$ntoleransicabang);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $icity
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

        $icity = $this->uri->segment('4');
        $iarea = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($icity,$iarea)->row()
        );
        $data['area'] = $this->mmaster->bacaarea();
        $data['jeniskota'] = $this->mmaster->bacajeniskota();
        $data['grupkota'] = $this->mmaster->bacagrupkota();
        $data['statuskota'] = $this->mmaster->bacastatuskota();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $icity          = $this->input->post('icity', TRUE);
        $iarea = $this->input->post('iarea',TRUE);
        $icitytype = $this->input->post('icitytype',TRUE);
        $icitytypearea = $this->input->post('icitytypearea',TRUE);
        $icitygroup = $this->input->post('icitygroup',TRUE);
        $icitystatus = $this->input->post('icitystatus',TRUE);
        $ecityname = $this->input->post('ecityname',TRUE);
        $ntoleransipusat = $this->input->post('ntoleransipusat',TRUE);
        $ntoleransicabang = $this->input->post('ntoleransicabang',TRUE);


        if ($icity != ''){
            $cekada = $this->mmaster->cek_data($icity);
            if($cekada->num_rows() > 0){ 
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$icity);
                $this->mmaster->update($icity,$iarea,$icitytype,$icitytypearea,$icitygroup,$icitystatus,$ecityname,$ntoleransipusat,$ntoleransicabang);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $icity
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

        $icity = $this->uri->segment('4');
        $iarea = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($icity,$iarea)->row(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
