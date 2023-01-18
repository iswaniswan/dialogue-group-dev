<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010101';

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
		echo $this->mmaster->data($this->i_menu,$this->global['folder']);
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

    public function jeniskas(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->jeniskas($cari);
        foreach($data->result() as $key){
            $filter[] = array(
                'id'    => $key->i_kas_type,
                'text'  => $key->e_kas_type_name
            );
        }
        echo json_encode($filter);
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title']
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function coa() {
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->coa($cari,$this->input->get('kas'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->i_coa,  
                'text' => $key->i_coa. " - ".$key->e_coa_name,
            );
        }          
        echo json_encode($filter);
    }

    public function jenisbank() {
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->jenisbank($cari);
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->i_bank,  
                'text' => $key->i_bank. " - ".$key->e_bank_name,
            );
        }          
        echo json_encode($filter);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ijenis           = $this->input->post('ijenis', TRUE);
        $ejenisvoucher    = $this->input->post('ejenisvoucher', TRUE); 
        $jeniskas         = $this->input->post('jeniskas', TRUE); 
        $jenisbank        = $this->input->post('jenisbank', TRUE); 
        $norek            = $this->input->post('norek', TRUE); 
        $namarek          = $this->input->post('namarek', TRUE);
        $coa              = $this->input->post('coa', TRUE);    

        if ($ijenis != '' && $ejenisvoucher != ''){
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ijenis);
            $this->mmaster->insert($ijenis, $ejenisvoucher,  $jeniskas, $jenisbank, $norek, $namarek, $coa);
            $data = array(
                'sukses'    => true,
                'kode'      => $ijenis
            );
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

        $ijenis = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->get_header($ijenis)->row()
          
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
        $ijenis         = $this->input->post('ijenis', TRUE);
        $ejenisvoucher  = $this->input->post('ejenisvoucher', TRUE);        
        $jeniskas       = $this->input->post('jeniskas', TRUE); 
        $jenisbank      = $this->input->post('jenisbank', TRUE); 
        $norek          = $this->input->post('norek', TRUE); 
        $namarek        = $this->input->post('namarek', TRUE);
        $coa            = $this->input->post('coa', TRUE);    

        if ($ijenis != '' && $ejenisvoucher != ''){      
            $this->mmaster->update($id, $ijenis, $ejenisvoucher, $jeniskas, $jenisbank, $norek, $namarek, $coa);
            $data = array(
                'sukses'    => true,
                'kode'      => $ijenis
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $ijenis= $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->get_header($ijenis)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */