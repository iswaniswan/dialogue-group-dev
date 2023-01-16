<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010808';
   
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
		echo $this->mmaster->data($this->global['folder'],$this->i_menu);
    }

    public function status(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id_company = $this->session->userdata('id_company');

        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Tambah ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'], 
            'company'        => $this->mmaster->cek_company($id_company),  
            'dept'           => $this->db->query('SELECT * FROM public.tr_departement ORDER BY e_departement_name'),
            'level'          => $this->db->query('SELECT * FROM public.tr_level ORDER BY e_level_name')
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ektp           = $this->input->post('ektp', TRUE);
        $enamakaryawan  = $this->input->post('enamakaryawan', TRUE);
        $etelp          = $this->input->post('etelp', TRUE);
        $ekota          = $this->input->post('ekota', TRUE);
        $ealamat        = $this->input->post('ealamat', TRUE);
        $enik           = $this->input->post('enik', TRUE);
        $company        = $this->input->post('company', TRUE);
        $departement    = $this->input->post('departement', TRUE);
        $ilevel          = $this->input->post('elevel', TRUE);
        $id             = $this->mmaster->runningid();

        if (($enik != '') && ($enamakaryawan != '') && ($etelp != '') && ($ealamat != '') && ($company != '')  && ($departement != '')){
                $this->db->trans_begin();

                $this->mmaster->insert($id, $ektp, $enamakaryawan, $etelp, $ekota, $ealamat, $enik, $company, $departement, $ilevel);
                
                if(($this->db->trans_status()=== False)){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Karyawan : '.$enik."-".$enamakaryawan);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $enik."-".$enamakaryawan,
                        'id'        => $id,
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan2', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $id_company = $this->session->userdata('id_company');

        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'dept'           => $this->db->query('SELECT * FROM public.tr_departement ORDER BY e_departement_name'),
            'company'        => $this->mmaster->cek_company($id_company),
            'isi'            => $this->mmaster->cek_data($id)->row(),
            'level'          => $this->db->query('SELECT * FROM public.tr_level ORDER BY e_level_name')
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id             = $this->input->post('id', TRUE);
        $ektp           = $this->input->post('ektp', TRUE);
        $enamakaryawan  = $this->input->post('enamakaryawan', TRUE);
        $etelp          = $this->input->post('etelp', TRUE);
        $ekota          = $this->input->post('ekota', TRUE);
        $ealamat        = $this->input->post('ealamat', TRUE);
        $enik           = $this->input->post('enik', TRUE);
        $company        = $this->input->post('company', TRUE);
        $departement    = $this->input->post('departement', TRUE);
        $ilevel          = $this->input->post('level', TRUE);

        if (($enik != '') && ($etelp != '') && ($ealamat != '')){
            $this->db->trans_begin();

            $this->mmaster->update($id, $ektp, $enamakaryawan, $etelp, $ekota, $ealamat, $enik, $company, $departement, $ilevel);
            
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Karyawan : '.$enik."-".$enamakaryawan);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $enik."-".$enamakaryawan,
                    'id'        => $id
                );
            }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan2', $data);  
    }

    public function view(){

        $id         = $this->uri->segment('4');
        $id_company = $this->session->userdata('id_company');
        
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'dept'              => $this->db->query('SELECT * FROM public.tr_departement ORDER BY e_departement_name'),
            'company'           => $this->mmaster->cek_company($id_company),
            'isi'               => $this->mmaster->cek_data($id)->row(),
            'level'          => $this->db->query('SELECT * FROM public.tr_level ORDER BY e_level_name')
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */