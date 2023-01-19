<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010303';

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
		echo $this->mmaster->data($this->i_menu, $this->global['folder']);
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
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
	}

    public function area(){
        $filter = [];
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->area($cari);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_area,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function customer(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->customer($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_customer_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function salesman(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->salesman($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_sales,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function brand(){
        $filter = [];
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->brand($cari, $idcompany);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_brand_name,                    
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }
	
	public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea 		    = $this->input->post('iarea', TRUE);
        $icustomer 	    = $this->input->post('icustomer[]', TRUE);
        $isalesman 	    = $this->input->post('isalesman', TRUE);
        $ibrand 		= $this->input->post('ibrand', TRUE);
        $bl             = $this->input->post('bulan', TRUE);
        $th             = $this->input->post('tahun', TRUE);
        $iperiode       = $th.$bl;
        $idcompany      = $this->session->userdata('id_company');
        $id             = '';
        if ($iarea != '' && $icustomer != '' && $isalesman != '' && $iperiode != ''){
            $this->db->trans_begin();

            if (is_array($icustomer) || is_object($icustomer)) {
                foreach($icustomer AS $customer){
                    $cekada = $this->mmaster->cek_data($iarea, $customer, $isalesman, $ibrand, $iperiode, $idcompany);
                    if($cekada->num_rows() > 0){
                        $data = array(
                            'sukses' => false
                        );
                    }else{
                        $id = $this->mmaster->runningid();
                        $this->mmaster->insert($id, $iarea, $customer, $isalesman, $ibrand, $iperiode, $idcompany);
                    }
                }
            }
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();

                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$id);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $id
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

        $id          = $this->uri->segment('4');
        $epriode     = $this->uri->segment('5');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->get_data($id, $idcompany)->row(),
            'periode'       => $epriode,
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $isalesman      = $this->input->post('isalesman', TRUE);
        $ibrand         = $this->input->post('ibrand', TRUE);
        $bl             = $this->input->post('bulan', TRUE);
        $th             = $this->input->post('tahun', TRUE);
        $iperiode       = $th.$bl;
        $idcompany      = $this->session->userdata('id_company');

        if ($iarea != '' && $icustomer != '' && $isalesman != '' && $iperiode != ''){
            $this->db->trans_begin();

            $this->mmaster->update($id, $iarea, $icustomer, $isalesman, $ibrand, $iperiode, $idcompany);
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$id);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => $id
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

        $id          = $this->uri->segment('4');
        $epriode     = $this->uri->segment('5');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->get_data($id, $idcompany)->row(),
            'periode'       => $epriode,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function status()
    {
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

}