<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010906';

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
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);
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
        $str = explode('|', $id);
        $username    = $str[0];
        $depart      = $str[1];
        $ilevel      = $str[2];
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($username,$depart,$this->session->userdata('id_company'),$ilevel);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
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
            'level'         => $this->mmaster->bacalevel()->result(),
            'departement'   => $this->mmaster->bacadept()->result(),
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function user()
    {
        $filter = [];
        /*if ($this->input->get('q')!='') {*/
            $data = $this->mmaster->get_user($this->input->get('q'),$this->input->get('idept'),$this->input->get('ilevel'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->username,  
                    'text' => $key->username,
                );
            }          
            echo json_encode($filter);
        /*}else{            
            echo json_encode($filter);
        }*/
    }

    public function bagian()
    {
        $filter = [];
        if ($this->input->get('idept')!='') {
            $data = $this->mmaster->get_bagian($this->input->get('idept'),$this->input->get('q'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_bagian,  
                    'text' => $key->e_bagian_name,
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username = $this->input->post('iuser', TRUE); 
        $ilevel   = $this->input->post('ilevel', TRUE); 
        $idept    = $this->input->post('idept', TRUE);

        if ($username != '' && $ilevel != '' && $idept != '' && $this->input->post('ibagian', TRUE) != ''){
            $this->db->trans_begin();
            $this->mmaster->delete($username,$ilevel,$idept);
            foreach ($this->input->post('ibagian', TRUE) as $key) {
                $this->mmaster->insert($username,$ilevel,$idept,$key);
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Username : '.$username);
                $data = array(
                    'sukses'  => true,
                    'kode'    => $username
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

        $username       = $this->uri->segment(4);
        $idepartement   = $this->uri->segment(5);
        $ilevel         = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'level'         => $this->mmaster->bacalevel()->result(),
            'departement'   => $this->mmaster->bacadept()->result(),
            'data'          => $this->mmaster->cek_data($username,$idepartement,$ilevel)->row(),
            'detail'        => $this->mmaster->cek_datadetail($username,$idepartement,$ilevel)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->input->post('iuser', TRUE);
        $ilevel     = $this->input->post('ilevel', TRUE);
        $idept      = $this->input->post('idept', TRUE);

        $iuserold   = $this->input->post('iuserold', TRUE);
        $ilevelold  = $this->input->post('ilevelold', TRUE);
        $ideptold   = $this->input->post('ideptold', TRUE);

        if ($username != '' && $ilevel != '' && $idept != '' && $this->input->post('ibagian', TRUE) != ''){
            $this->db->trans_begin();
            $this->mmaster->delete($iuserold,$ilevelold,$ideptold);
            foreach ($this->input->post('ibagian', TRUE) as $key) {
                $this->mmaster->insert($username,$ilevel,$idept,$key);
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Username : '.$username);
                $data = array(
                    'sukses'  => true,
                    'kode'    => $username
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