<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010210';

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
            'productgrade'     => $this->mmaster->get_productgrade()->result() 
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tr_product");
            $this->db->like("UPPER(i_product)", $cari);
            $this->db->or_like("UPPER(e_product_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->i_product,  
                    'text' => $product->i_product.' - '.$product->e_product_name
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

        $iproduct 			= $this->input->post('iproduct', TRUE);
        $eproductname 		= $this->input->post('eproductname', TRUE);
        $iproductgrade	    = $this->input->post('iproductgrade', TRUE);
        $nproductmargin	    = $this->input->post('nproductmargin', TRUE);
        $vproductmill	    = $this->input->post('vproductmill', TRUE);

        if ($iproduct != '' && $eproductname != ''){
                $cekada = $this->mmaster->cek_data($iproduct);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproduct);
                    $this->mmaster->insert($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill);
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

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproduct 			= $this->input->post('iproduct', TRUE);
        $eproductname 		= $this->input->post('eproductname', TRUE);
        $iproductgrade	    = $this->input->post('iproductgrade', TRUE);
        $nproductmargin	    = $this->input->post('nproductmargin', TRUE);
        $vproductmill	    = $this->input->post('vproductmill', TRUE);


        if ($iproduct != '' && $eproductname != ''){
            $cekada = $this->mmaster->cek_data($iproduct);
            if($cekada->num_rows() > 0){ 
                $this->mmaster->update($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill);
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iproduct);
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
