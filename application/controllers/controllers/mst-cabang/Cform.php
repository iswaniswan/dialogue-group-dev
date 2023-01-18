<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010403';
   
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
    
    function getkodepelanggan(){
        header("Content-Type: application/json", true);
        $icustomer = $this->input->post('icustomer');
            $this->db->select('i_customer, e_customer_name');
            $this->db->from('tr_customer');
            $this->db->where("UPPER(i_customer)", $icustomer);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getkode(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('a.i_customer, a.e_customer_name');
            $this->db->from('tr_customer a');
            $this->db->like("UPPER(i_customer)", $cari);
            $this->db->or_like("UPPER(e_customer_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_customer,  
                    'text' => $iproduct->i_customer.'-'.$iproduct->e_customer_name
                );
            }      
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
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
            'pelanggan_cabang'  => $this->mmaster->get_cabang()->result(),  
            'pelanggan'         => $this->mmaster->get_pelanggan()->result(),    
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibranch          = $this->input->post('ibranch', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $ibranch          = $this->input->post('ibranch', TRUE);    
        $einitial         = $this->input->post('einitial', TRUE);            
        $ebranchname      = $this->input->post('ebranchname', TRUE); 
        $ecity            = $this->input->post('ecity', TRUE); 
        $ecodearea        = $this->input->post('ecodearea', TRUE);
        $ebranchaddress   = $this->input->post('ebranchaddress', TRUE);
        $ncustomerdiscount1          = $this->input->post('ncustomerdiscount1', TRUE);
        $ncustomerdiscount2          = $this->input->post('ncustomerdiscount2', TRUE);
        $ncustomerdiscount3          = $this->input->post('ncustomerdiscount3', TRUE);

        if ($ibranch != ''){
                $cekada = $this->mmaster->cek_data($ibranch);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibranch);
                    $this->mmaster->insert($ibranch, $icustomer, $einitial, $ebranchname, $ecity, $ecodearea, $ebranchaddress);
                    $this->mmaster->insertdiscount($icustomer,$ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ibranch
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

        $ibranch = $this->uri->segment('4');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($ibranch)->row(),
            'pelanggan_cabang'  => $this->mmaster->get_cabang()->result(),
            'pelanggan'         => $this->mmaster->get_pelanggan()->result(), 
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibranch          = $this->input->post('ibranch', TRUE);  
        $icustomer        = $this->input->post('icustomer', TRUE);              
        $einitial         = $this->input->post('einitial', TRUE);            
        $ebranchname      = $this->input->post('ebranchname', TRUE); 
        $ecity            = $this->input->post('ecity', TRUE); 
        $ecodearea        = $this->input->post('ecodearea', TRUE);
        $ebranchaddress   = $this->input->post('ebranchaddress', TRUE);
        $ncustomerdiscount1          = $this->input->post('ncustomerdiscount1', TRUE);
        $ncustomerdiscount2          = $this->input->post('ncustomerdiscount2', TRUE);
        $ncustomerdiscount3          = $this->input->post('ncustomerdiscount3', TRUE);       

        if ($ibranch != ''){
            $cekada = $this->mmaster->cek_data($ibranch);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($ibranch, $icustomer, $einitial, $ebranchname, $ecity, $ecodearea, $ebranchaddress);
                $this->mmaster->updatediscount($icustomer, $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibranch
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

        $ibranch= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ibranch)->row(),
            'pelanggan'     => $this->mmaster->get_pelanggan()->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */