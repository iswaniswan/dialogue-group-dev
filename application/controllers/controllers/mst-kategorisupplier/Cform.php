<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010801';

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
		echo $this->mmaster->data($this->i_menu, $this->global['folder']);
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],     
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_data($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isuppliergroup         = $this->input->post('isuppliergroup', TRUE);
        $isuppliergroupname     = $this->input->post('isuppliergroupname', TRUE); 
        $id                     = $this->mmaster->runningid();

        if ($isuppliergroup != ''){
                $cekada = $this->mmaster->cek_data($isuppliergroup);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false,
                        'kode' => "Kode Group Sudah Ada"
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isuppliergroup);
                    $this->mmaster->insert($id, $isuppliergroup, $isuppliergroupname);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isuppliergroup,
                        'id'        => $id,
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                    'kode' => "Kode Group masih kosong"
                );
        }
        $this->load->view('pesan2', $data);  
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_data_edit($this->input->post('kodeold',TRUE), $this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek($id)->row(),
          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id                     = $this->input->post('id', TRUE);
        $isuppliergroup         = $this->input->post('isuppliergroup', TRUE);
        $oldisuppliergroup      = $this->input->post('oldisuppliergroup', TRUE);
        $isuppliergroupname     = $this->input->post('isuppliergroupname', TRUE);  
        $idcompany              = $this->session->userdata('id_company');      

        if ($isuppliergroup != '' && $isuppliergroupname != ''){
            $cekada = $this->mmaster->cek_data_edit($id, $oldisuppliergroup, $isuppliergroup, $idcompany);
            if($cekada->num_rows() == 0){                 
                $this->mmaster->update($id, $oldisuppliergroup, $isuppliergroup, $isuppliergroupname, $idcompany);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isuppliergroup,
                    'id'        => $id,
                );
            }else{
                $data = array(
                    'sukses' => false,
                    'kode' => "Kode Group Sudah Ada"
                );
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode' => "Nama Harus Di isi"
            );
        }
        $this->load->view('pesan2', $data);  
    }


    public function view(){

        $id= $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek($id)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */