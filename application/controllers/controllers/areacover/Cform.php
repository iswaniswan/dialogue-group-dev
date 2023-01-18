<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010303';

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

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea 			= $this->input->post('iarea', TRUE);
        $iareacover = $this->input->post('iareacover', TRUE);
        $eareacovername = $this->input->post('eareacovername', TRUE);

        if ($iarea != '' && $iareacover != ''){
            $cekada = $this->mmaster->cek_data($iareacover);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->insert($iarea,$iareacover,$eareacovername);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iareacover);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iareacover,
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
}

/* End of file Cform.php */
