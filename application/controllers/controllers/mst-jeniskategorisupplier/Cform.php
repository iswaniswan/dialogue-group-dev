<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010101';

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
            'supplier_group'=> $this->mmaster->get_supplier_type()->result()  
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function kategorisup(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_supplier_group");
            $this->db->like("UPPER(i_supplier_group)", $cari);
            $this->db->or_like("UPPER(e_supplier_groupname)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_supplier_group,  
                    'text' => $icolor->i_supplier_group.'-'.$icolor->e_supplier_groupname,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isuppliertype         = $this->input->post('isuppliertype', TRUE);
        $isuppliertypename     = $this->input->post('isuppliertypename', TRUE);
        $ikategorisupplier     = $this->input->post('ikategorisupplier', TRUE);  

        if ($isuppliertype != '' && $isuppliertypename != ''){
                $cekada = $this->mmaster->cek_data($isuppliertype);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isuppliertype);
                    $this->mmaster->insert($isuppliertype, $isuppliertypename, $ikategorisupplier);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isuppliertype
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

        $isuppliertype = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($isuppliertype)->row(),
            'supplier_group'=> $this->mmaster->get_supplier_group()->result(), 
            'supplier_type' => $this->mmaster->get_supplier_type()->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isuppliertype         = $this->input->post('isuppliertype', TRUE);
        $isuppliertypename     = $this->input->post('isuppliertypename', TRUE);
        $ikategorisupplier     = $this->input->post('ikategorisupplier', TRUE);

        if ($isuppliertype != '' && $isuppliertypename != ''){
            $cekada = $this->mmaster->cek_data($isuppliertype);
            if($cekada->num_rows() > 0){ 
               
                $this->mmaster->update($isuppliertype, $isuppliertypename, $ikategorisupplier);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isuppliertype
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

        $isuppliertype= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($isuppliertype)->row(),
            'supplier_group'=> $this->mmaster->get_supplier_group()->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */