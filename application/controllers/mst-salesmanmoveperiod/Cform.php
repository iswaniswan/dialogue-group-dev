<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010304';

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

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function data()
    {
		echo $this->mmaster->data($this->i_menu);
    }

    public function pindah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $blama 	        = $this->input->post('blama', TRUE);
        $tlama 		    = $this->input->post('tlama', TRUE);
        $periodelama    = $tlama.$blama;
        $bbaru	        = $this->input->post('bbaru', TRUE);
        $tbaru	        = $this->input->post('tbaru', TRUE);
        $periodebaru    = $tbaru.$bbaru;

         if ($blama != '' && $tlama != '' && $bbaru != '' && $tbaru){            
            $this->db->trans_begin();       
            $this->mmaster->update($periodelama, $periodebaru);
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$periodebaru);
                $data = array(
                    'sukses' => true,
                    'kode'   => $periodebaru
                );
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