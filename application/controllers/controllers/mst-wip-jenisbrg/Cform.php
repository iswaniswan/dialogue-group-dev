<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011302';

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
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'wip_jenis'  => $this->mmaster->get_wipjenis()->result(),
            'wip_barang' => $this->mmaster->get_wipbarang()->result(),   
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ijenisbrgwip   = $this->input->post('ijenisbrgwip', TRUE);
        $ikelbrgwip     = $this->input->post('ikelbrgwip', TRUE);
        $enamajenis     = $this->input->post('enamajenis', TRUE);           

        if ($ijenisbrgwip != '' && $enamajenis != ''){
                $cekada = $this->mmaster->cek_data($ijenisbrgwip);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ijenisbrgwip);
                    $this->mmaster->insert($ijenisbrgwip, $enamajenis, $ikelbrgwip);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ijenisbrgwip
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ijenisbrgwip = $this->input->post('i_jenisbrgwip', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ijenisbrgwip);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Jenis WIP ' . $ijenisbrgwip);
            echo json_encode($data);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ijenisbrgwip = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_data($ijenisbrgwip)->row(),
            'wip_jenis'  => $this->mmaster->get_wipjenis()->result(),
            'wip_barang' => $this->mmaster->get_wipbarang()->result(),          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ijenisbrgwip   = $this->input->post('ijenisbrgwip', TRUE);
        $ikelbrgwip     = $this->input->post('ikelbrgwip', TRUE);
        $enamajenis     = $this->input->post('enamajenis', TRUE); 
        
        if ($ijenisbrgwip != '' && $enamajenis != ''){
            $cekada = $this->mmaster->cek_data($ijenisbrgwip);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($ijenisbrgwip, $enamajenis, $ikelbrgwip);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ijenisbrgwip
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

        $ijenisbrgwip= $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_data($ijenisbrgwip)->row(),
            'wip_jenis'  => $this->mmaster->get_wipjenis()->result(),
            'wip_barang' => $this->mmaster->get_wipbarang()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */