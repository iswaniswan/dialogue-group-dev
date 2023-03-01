<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010903';

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

    function data()
    {
        $idcompany = $this->session->userdata('id_company');
        echo $this->mmaster->data($this->i_menu, $idcompany, $this->global['folder']);
    }
    
    public function status()
    {
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

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dept'          => $this->mmaster->bacadept()->result(),
            'level'         => $this->mmaster->bacalevel()->result(),
            'userarea'      => $this->mmaster->bacauserarea()->result(),
            'usersalesman'  => $this->mmaster->bacausersalesman()->result(),
            'userkasrv'     => $this->mmaster->bacauserkasrv()->result(),
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idcompany      = $this->session->userdata('id_company');
        $ilokasi        = $this->input->post('ilokasi', TRUE); 
        $departement    = $this->input->post('idept', TRUE);  
        $Level          = $this->input->post('ilevel', TRUE); 
        $iuser          = $this->input->post('iuser', TRUE); 
        $password       = $this->input->post('epass', TRUE); 
        $eusername      = $this->input->post('eusername', TRUE); 
        
        $iuser_area     = $this->input->post('iuser_area[]', TRUE); 
        $iuser_salesman = $this->input->post('iuser_salesman[]', TRUE); 
        $iuser_kas_rv   = $this->input->post('iuser_kas_rv[]', TRUE); 
        if ($iuser != '' && $eusername != ''){
            $cekada = $this->mmaster->cek_data($iuser, $idcompany);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->insert($idcompany, $iuser, $eusername, md5(md5($password)));
                if ($departement != '' && $Level != ''){
                    $this->mmaster->insertdeproll($idcompany,$departement,$Level,$iuser);
                }
                if($iuser_area != null) {
                    foreach($iuser_area as $iarea) {
                        $iarea = explode('|', $iarea);
                        $i_area = $iarea[0];
                        $id_area = $iarea[1];
                        $this->mmaster->insertuserarea($idcompany, $iuser, $i_area, $id_area);
                    }
                }
                if($iuser_salesman != null) {
                    foreach($iuser_salesman as $id_salesman) {
                        $this->mmaster->insertusersalesman($idcompany, $iuser, $id_salesman);
                    }
                }
                if($iuser_kas_rv != null) {
                    foreach($iuser_kas_rv as $i_rv_type) {
                        $this->mmaster->insertuserkasrv($idcompany, $iuser, $i_rv_type);
                    }
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Username : '.$iuser);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $iuser,
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

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iuser = $this->uri->segment('4');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iuser, $idcompany)->row(),
            'depart'        => $this->mmaster->bacadept()->result(),
            'level'         => $this->mmaster->bacalevel()->result(),
            'userarea'      => $this->mmaster->bacauserarea()->result(),
            'usersalesman'  => $this->mmaster->bacausersalesman()->result(),
            'userkasrv'     => $this->mmaster->bacauserkasrv()->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idcompany      = $this->input->post('idcompany', TRUE);
        $iuser          = $this->input->post('iuser', TRUE); 
        $iuserold       = $this->input->post('iuserold', TRUE); 
        $eusername      = $this->input->post('eusername', TRUE);             
        $passwordnew    = $this->input->post('passwordnew', TRUE);
        $pasworold      = $this->input->post('pasworold', TRUE);
        $passwordoldd   = $this->input->post('passwordoldd', TRUE);         
        $passwordbaru   = md5(md5($passwordnew));

        $iuser_area     = $this->input->post('iuser_area[]', TRUE); 
        $iuser_salesman = $this->input->post('iuser_salesman[]', TRUE); 
        $iuser_kas_rv   = $this->input->post('iuser_kas_rv[]', TRUE);

        if ($iuser != '' && $eusername != ''){
            $cekada = $this->mmaster->cek_data($iuserold,$idcompany);
            if($cekada->num_rows() > 0){     
                $this->db->trans_begin();           
                $this->mmaster->update($iuser, $eusername, $idcompany, $iuserold);
                $this->mmaster->delete_user_area($idcompany,$iuserold);
                $this->mmaster->delete_user_salesman($idcompany,$iuserold);
                $this->mmaster->delete_user_kas_rv($idcompany,$iuserold);
                if ($pasworold != $passwordoldd && strlen($passwordoldd) > 0 && strlen($passwordbaru) > 0){
                    $this->mmaster->updatepass($idcompany, $passwordbaru, $iuserold);
                }
                if($iuser_area != null) {
                    foreach($iuser_area as $iarea) {
                        $iarea = explode('|', $iarea);
                        $i_area = $iarea[0];
                        $id_area = $iarea[1];
                        $this->mmaster->insertuserarea($idcompany, $iuser, $i_area, $id_area);
                    }
                }
                if($iuser_salesman != null) {
                    foreach($iuser_salesman as $id_salesman) {
                        $this->mmaster->insertusersalesman($idcompany, $iuser, $id_salesman);
                    }
                }
                if($iuser_kas_rv != null) {
                    foreach($iuser_kas_rv as $i_rv_type) {
                        $this->mmaster->insertuserkasrv($idcompany, $iuser, $i_rv_type);
                    }
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Username : '.$iuser);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $iuser,
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
}
/* End of file Cform.php */