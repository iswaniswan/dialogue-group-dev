<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010809';

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


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $eunitjahitname         = $this->input->post('eunitjahitname', TRUE);      
        $eunitjahitaddress      = $this->input->post('eunitjahitaddress', TRUE); 
        $eperusahaanname        = $this->input->post('eperusahaanname', TRUE);
        $epenanggungjawabname   = $this->input->post('epenanggungjawabname', TRUE); 
        $eadminname             = $this->input->post('eadminname', TRUE);
        $ikategori              = $this->input->post('ikategori', TRUE);    

                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$eunitjahitname);
                $this->db->trans_begin();
                $query = $this->mmaster->insert($ikategori, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname);
                if($this->db->trans_status() === False){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        'sukses'  => true,
                        'kode'    => $eunitjahitname
                    );
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode Dokumen : '.$eunitjahitname);
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
            'data' => $this->mmaster->cek_data($iunitjahit)->row()
          
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
        
        $id                     = $this->input->post('id', TRUE);
        $ikategori              = $this->input->post('ikategori', TRUE);
        $eunitjahitname         = $this->input->post('eunitjahitname', TRUE);      
        $eunitjahitaddress      = $this->input->post('eunitjahitaddress', TRUE); 
        $eperusahaanname        = $this->input->post('eperusahaanname', TRUE);
        $epenanggungjawabname   = $this->input->post('epenanggungjawabname', TRUE); 
        $eadminname             = $this->input->post('eadminname', TRUE);       
        
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$eunitjahitname);
                $this->db->trans_begin();
                $query = $this->mmaster->update($id, $ikategori, $eunitjahitname, $eunitjahitaddress, $eperusahaanname, $epenanggungjawabname, $eadminname);
                if($this->db->trans_status() === False){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        'sukses'  => true,
                        'kode'    => $eunitjahitname
                    );
                    $this->Logger->write('Ubah Data '.$this->global['title'].' Kode Dokumen : '.$eunitjahitname);
                }
        $this->load->view('pesan', $data);
    }


    public function view(){

        $id= $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($id)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function getkategori()
    {
        $filter = [];
        $data   = $this->mmaster->getkategori(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->id,  
                    'text' => $row->e_nama_kategori,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), "refresh");
        }

        $id = $this->input->post("id", true);
        if ($id == "") {
            $id = $this->uri->segment(4);
        }
        if ($id != "") {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write(
                    "Update status " . $this->global["title"] . " Id : " . $id
                );
                echo json_encode($data);
            }
        }
    }


}

/* End of file Cform.php */