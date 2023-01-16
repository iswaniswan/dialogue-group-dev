<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010203';
   
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
    
    function getkode(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('a.i_product_base, a.e_product_basename');
            $this->db->from('tr_product_base a');
            $this->db->like("UPPER(i_product_base)", $cari);
            $this->db->or_like("UPPER(e_product_basename)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_product_base,  
                    'text' => $iproduct->i_product_base
                );
            }      
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
   
    function getkodebarang(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
            $this->db->select('a.i_product_base, a.e_product_basename');
            $this->db->from('tr_product_base a');
            $this->db->where("UPPER(i_product_base)", $iproduct);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getkodemotifwarna(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('a.i_color, a.e_color_name');
            $this->db->from('tr_color a');
            $this->db->like("UPPER(e_color_name)", $cari);
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id' => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->e_color_name
                );
            }      
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }   
    }

    function getkodemotif(){
        header("Content-Type: application/json", true);
        $icolor   = $this->input->post('icolor', TRUE);            
            $this->db->select('a.i_color, a.e_color_name');
            $this->db->from('tr_color a');
            $this->db->where("UPPER(i_color)", $icolor);
            $data = $this->db->get();
        echo json_encode($data->result_array());  
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
            'product'       => $this->mmaster->get_product()->result(),
            'warna'         => $this->mmaster->get_warna()->result(),
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproduct            = $this->input->post('iproduct', TRUE);
        $eproductmotifname   = $this->input->post('eproductmotifname', TRUE);
        $nquantity           = $this->input->post('nquantity', TRUE);
        $icolor              = $this->input->post('icolor', TRUE);
        $iproductmotif       = $iproduct.$icolor;

        if ($iproductmotif != ''){
                $cekada = $this->mmaster->cek_data($iproductmotif);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproductmotif);
                    $this->mmaster->insert($iproductmotif, $iproduct, $eproductmotifname, $nquantity, $icolor);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iproductmotif
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

        $iproductmotif = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductmotif)->row(),
            'product'       => $this->mmaster->get_product()->result(),
            'warna'         => $this->mmaster->get_warna()->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproductmotif       = $this->input->post('iproductmotif', TRUE);
        $iproduct            = $this->input->post('iproduct', TRUE);
        $eproductmotifname   = $this->input->post('eproductmotifname', TRUE);
        $nquantity           = $this->input->post('nquantity', TRUE);
        $icolor              = $this->input->post('icolor', TRUE); 

        if ($iproductmotif != ''){
            $cekada = $this->mmaster->cek_data($iproductmotif);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($iproductmotif, $iproduct, $eproductmotifname, $nquantity, $icolor);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproductmotif
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

        $iproductmotif= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductmotif)->row(),
            'product'       => $this->mmaster->get_product()->result(),
            'warna'         => $this->mmaster->get_warna()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */