<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011201';
   
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
    
    function getkodeunit(){
        header("Content-Type: application/json", true);
        $iunitpacking = $this->input->post('iunitpacking');
            $this->db->select('*');
            $this->db->from('tr_unit_packing');
            $this->db->where("UPPER(i_unit_packing)", $iunitpacking);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getkodepacking(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_unit_packing');
            $this->db->like("UPPER(i_unit_packing)", $cari);
            $this->db->or_like("UPPER(i_unit_packing)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iunitpacking){
                    $filter[] = array(
                    'id' => $iunitpacking->i_unit_packing,  
                    'text' => $iunitpacking->i_unit_packing,
                );
            }      
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getkodejahit(){
       $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_unit_jahit');
            $this->db->like("UPPER(i_unit_jahit)", $cari);
            $this->db->or_like("UPPER(i_unit_jahit)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iunitjahit){
                    $filter[] = array(
                    'id' => $iunitjahit->i_unit_jahit,  
                    'text' => $iunitjahit->i_unit_jahit,
                );
            }      
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        } 
    }

    function getkodejahit2(){
        header("Content-Type: application/json", true);
        $iunitjahit = $this->input->post('iunitjahit');
            $this->db->select('*');
            $this->db->from('tr_unit_jahit');
            $this->db->where("UPPER(i_unit_jahit)", $iunitjahit);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'                => $this->global['folder'],
            'title'                 => "Tambah ".$this->global['title'],
            'title_list'            => 'List '.$this->global['title'],
            'kelompok_jahitpacking' => $this->mmaster->get_kelompokjahitpacking()->result(), 
            'unit_jahit'            => $this->mmaster->get_unitjahit()->result(), 
            'unit_packing'          => $this->mmaster->get_unitpacking()->result(), 
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ijahitpacking      = $this->input->post('ijahitpacking', TRUE);
        $iunitjahit         = $this->input->post('iunitjahit', TRUE);
        $iunitpacking       = $this->input->post('iunitpacking', TRUE);
        $enamajahitpacking  = $this->input->post('enamajahitpacking', TRUE);
        $eunitjahitname     = $this->input->post('eunitjahitname', TRUE);
        $enamapacking       = $this->input->post('enamapacking', TRUE);

        if ($enamajahitpacking != ''){
                $cekada = $this->mmaster->cek_data($ijahitpacking);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ijahitpacking);
                    $this->mmaster->insert($ijahitpacking, $iunitjahit, $iunitpacking, $enamajahitpacking, $eunitjahitname, $enamapacking);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ijahitpacking
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

        $ijahitpacking = $this->uri->segment('4');

        $data = array(
            'folder'                => $this->global['folder'],
            'title'                 => "Edit ".$this->global['title'],
            'title_list'            => 'List '.$this->global['title'],
            'data'                  => $this->mmaster->cek_data($ijahitpacking)->row(),
            'kelompok_jahitpacking' => $this->mmaster->get_kelompokjahitpacking()->result(),
            'unit_jahit'            => $this->mmaster->get_unitjahit()->result(), 
            'unit_packing'          => $this->mmaster->get_unitpacking()->result(), 
          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ijahitpacking      = $this->input->post('ijahitpacking', TRUE);
        $iunitjahit         = $this->input->post('iunitjahit', TRUE);
        $iunitpacking       = $this->input->post('iunitpacking', TRUE);
        $enamajahitpacking  = $this->input->post('enamajahitpacking', TRUE);
        $eunitjahitname     = $this->input->post('eunitjahitname', TRUE);
        $enamapacking       = $this->input->post('enamapacking', TRUE);   

        if ($enamajahitpacking != ''){
            $cekada = $this->mmaster->cek_data($ijahitpacking);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($ijahitpacking, $iunitjahit, $iunitpacking, $enamajahitpacking, $eunitjahitname, $enamapacking);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ijahitpacking
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

        $ijahitpacking= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ijahitpacking)->row(),
            'kelompok_jahitpacking' => $this->mmaster->get_kelompokjahitpacking()->result(),
            'unit_jahit'            => $this->mmaster->get_unitjahit()->result(), 
            'unit_packing'          => $this->mmaster->get_unitpacking()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */