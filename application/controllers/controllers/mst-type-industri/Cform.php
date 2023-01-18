<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010802';
   
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

    public function cekkode()
    {
        $data = $this->mmaster->cek_data($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_data_edit($this->input->post('kodeold',TRUE), $this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
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
            'folder'         => $this->global['folder'],
            'title'          => "Tambah ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function kategoripartner(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
         $data = $this->db->query("select a.*
                        from tr_supplier_group a 
                        where a.f_status_aktif='t'
                        and (a.i_supplier_group like '%$cari%' or a.e_supplier_groupname like '%$cari%') 
                        order by a.i_supplier_group");
        foreach($data->result() as  $kode){
                $filter[] = array(
                'id'   => $kode->i_supplier_group,  
                'text' => $kode->e_supplier_groupname,
            );
        }          
        echo json_encode($filter);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $itype      = $this->input->post('itype', TRUE);
        $etype      = $this->input->post('etype', TRUE); 
        //$i_kategori  = $this->input->post('ikategori[]', TRUE); 
        
        if ($etype != ''){
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$itype);
            $this->mmaster->insert($itype, $etype);
            $data = array(
                'sukses'    => true,
                'kode'      => $itype
                    );  
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $itype = $this->input->post('i_type', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($itype);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Jenis Partner ' . $itype);
            echo json_encode($data);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $itype = $this->uri->segment('4');

        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            // 'ikategori'      => $this->mmaster->bacakategori($itype),
            // 'i_kategori'     => $this->mmaster->baca_kategori($itype),
            'data'           => $this->mmaster->cek_data($itype)->row(),          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $itype      = $this->input->post('itype', TRUE);
        $olditype      = $this->input->post('olditype', TRUE);
        $etype      = $this->input->post('etype', TRUE);  
             
        $this->mmaster->update($olditype,$itype, $etype);
        $data = array(
            'sukses'    => true,
            'kode'      => $itype
        );
         
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $itype= $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            // 'ikategori'  => $this->mmaster->bacakategori($itype),
            // 'i_kategori' => $this->mmaster->baca_kategori($itype),
            'data'       => $this->mmaster->cek_data($itype)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

}

/* End of file Cform.php */