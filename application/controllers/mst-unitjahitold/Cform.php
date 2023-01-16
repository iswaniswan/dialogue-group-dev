<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011203';

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
            'unitjahit'=> $this->mmaster->get_unitjahit()->result(),  
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iunitjahit             = $this->input->post('iunitjahit', TRUE);
        $eunitjahitname         = $this->input->post('eunitjahitname', TRUE);      
        $eunitjahitaddress      = $this->input->post('eunitjahitaddress', TRUE); 
        $eperusahaanname        = $this->input->post('eperusahaanname', TRUE);
        $epenanggungjawabname   = $this->input->post('epenanggungjawabname', TRUE); 
        $eadminname             = $this->input->post('eadminname', TRUE);    

        if ($iunitjahit != '' && $eunitjahitname != ''){
                $cekada = $this->mmaster->cek_data($iunitjahit);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iunitjahit);
                    $this->mmaster->insert($iunitjahit, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iunitjahit
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

        $iunitjahit = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iunitjahit)->row(),
            'unitjahit'=> $this->mmaster->get_unitjahit()->result(),
          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iunitjahit = $this->input->post('i_unit_jahit', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iunitjahit);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Permintaan Pembelian ' . $iunitjahit);
            echo json_encode($data);
        }
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iunitjahit             = $this->input->post('iunitjahit', TRUE);
        $eunitjahitname         = $this->input->post('eunitjahitname', TRUE);      
        $eunitjahitaddress      = $this->input->post('eunitjahitaddress', TRUE); 
        $eperusahaanname        = $this->input->post('eperusahaanname', TRUE);
        $epenanggungjawabname   = $this->input->post('epenanggungjawabname', TRUE); 
        $eadminname             = $this->input->post('eadminname', TRUE);       

        if ($iunitjahit != '' && $eunitjahitname != ''){
            $cekada = $this->mmaster->cek_data($iunitjahit);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($iunitjahit, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iunitjahit
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

        $iunitjahit= $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iunitjahit)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */