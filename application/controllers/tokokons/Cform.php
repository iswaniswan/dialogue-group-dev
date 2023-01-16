<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010702';

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

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icustomer 			= $this->input->post('icustomer', TRUE);
        $ecustomername 		= $this->input->post('ecustomername', TRUE);

        if ($icustomer != '' && $ecustomername != ''){
                $cekada = $this->mmaster->cek_data($icustomer);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{

                    $this->db->trans_begin();
                    $this->mmaster->insert($icustomer,$ecustomername);
                    
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();

                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$icustomer);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $icustomer
                        );
                    }
                    
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    function data_pelanggan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_customer, e_customer_name");
            $this->db->from("tr_customer");
            $this->db->like("UPPER(i_customer)", $cari);
            $this->db->or_like("UPPER(e_customer_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->i_customer,  
                    'text' => $product->i_customer.'-'.$product->e_customer_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

}

/* End of file Cform.php */
