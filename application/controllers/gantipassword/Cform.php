<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010801';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $idcompany        = $this->session->userdata('id_company');
        $username 		  = $this->session->userdata('username');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'data'      => $this->mmaster->cek_data($idcompany,$username)->row()
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idcompany        = $this->session->userdata('id_company');
        $username 		  = $this->session->userdata('username');
        $epasswordold     = $this->input->post('epasswordold', TRUE);
        $epasswordnew1	  = $this->input->post('epasswordnew1', TRUE);
        $epasswordnew2    = $this->input->post('epasswordnew2', TRUE);
        $ename            = $this->input->post('ename', TRUE);

        if ($idcompany != '' && $username != '' && $epasswordold != '' && $epasswordnew1 != '' && $epasswordnew2 != '' && $ename != '' && $epasswordnew2==$epasswordnew1){
            $cekada = $this->mmaster->cek_data($idcompany,$username);
            if($cekada->num_rows() > 0){ 
                $this->db->trans_begin();
                $idcompany = $this->session->userdata('id_company');
                $username  = $this->session->userdata('username');
                $epass     = $cekada->row()->e_password;
                if(md5(md5($epasswordold)) == $epass){
                   $this->mmaster->update($idcompany,$username,md5(md5($epasswordnew1)),$ename);
                }

                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$username);
                    
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $username
                    );
                }
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

        $idcompany = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($idcompany)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */
