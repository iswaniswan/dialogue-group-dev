<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010202';
   
    public function __construct(){
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
    

    public function index(){
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

    public function cekkode(){
        $kode = $this->input->post('kode');
        $query = $this->mmaster->cekkode($kode);
        if($query->num_rows() > 0){
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'             => $this->global['folder'],
            'title'              => "Tambah ".$this->global['title'],
            'title_list'         => 'List '.$this->global['title'],
            'groupbarang'        => $this->mmaster->cek_group_barang()->result(),   
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function coa(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->coa($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_coa,  
                'text' => $key->i_coa." || ".$key->e_coa_name." || ".$key->e_coa_ledger_name,
            );
        }          
        echo json_encode($filter);
    }

    public function divisi(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->divisi($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->i_kode_divisi." || ".$key->e_nama_divisi,
            );
        }          
        echo json_encode($filter);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikelompok          = $this->input->post('ikelompok', TRUE);      
        $enama              = $this->input->post('enama', TRUE);
        $igroupbarang       = $this->input->post('igroupbrg', TRUE);
        $icoa               = $this->input->post('icoa', TRUE);
        $idivisi            = $this->input->post('idivisi', TRUE);
        $idcompany          = $this->session->userdata('id_company');
        $id                 = $this->mmaster->runningid();

        if ($ikelompok != ''){
            $cekada = $this->mmaster->cek_data($id, $idcompany);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikelompok);
                $this->mmaster->insert($id, $ikelompok, $enama, $igroupbarang, $idcompany, $icoa, $idivisi);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikelompok
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

        $id = $this->uri->segment('4');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($id, $idcompany)->row(),
            'groupbarang'       => $this->mmaster->cek_group_barang()->result(),     
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id           = $this->input->post('id', TRUE);
        $ikelompok    = $this->input->post('ikelompok', TRUE);  
        $enama        = $this->input->post('enama', TRUE);
        $icoa         = $this->input->post('icoa', TRUE);
        $idivisi         = $this->input->post('idivisi', TRUE);
        $igroupbarang = $this->input->post('igroupbrg', TRUE);

        if ($ikelompok != ''){
                $this->mmaster->update($id, $ikelompok, $enama, $igroupbarang, $icoa, $idivisi);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikelompok
                );
            }else{
                $data = array(
                    'sukses' => false
                );
            }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $id = $this->uri->segment('4');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_data($id, $idcompany)->row(),
            'groupbarang'=> $this->mmaster->cek_group_barang()->result(),  
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */