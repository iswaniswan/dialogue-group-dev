<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010203';
   
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

        $ikode = '';
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'kelompok'          => $this->mmaster->get_kelompok($idcompany)->result(), 
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getkelompok(){
        $ikelompokbrg = $this->input->post('ikelompokbrg');
        $query = $this->mmaster->getkelompoknya($ikelompokbrg);
        if($query->num_rows()>0) {
            $c  = "";
            $ikelompok = $query->result();
            foreach($ikelompok as $row) {
                $egroupname  = $row->e_nama_group_barang;
                $igroup  = $row->i_kode_group_barang;
            }
            echo json_encode(array(
                'igroup'        => $igroup,
                'egroupname'    => $egroupname
            ));
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $itypecode      = $this->input->post('itypecode', TRUE);
        $ikelompok      = $this->input->post('ikelompok', TRUE);
        $etypename      = $this->input->post('etypename', TRUE); 
        $igroupbrg      = $this->input->post('igroupbrg', TRUE);       
        $idcompany      = $this->session->userdata('id_company');

        if ($itypecode != '' && $ikelompok != ''){
                $cekada = $this->mmaster->cek_data($itypecode, $idcompany);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$itypecode);
                    $this->mmaster->insert($itypecode, $ikelompok, $etypename, $igroupbrg, $idcompany);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $itypecode
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

        $itypecode = $this->uri->segment('4');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($itypecode, $idcompany)->row(),
            'groupbarang'       => $this->mmaster->get_group($idcompany)->result(),
            'jenis_bahanbaku'   => $this->mmaster->get_jenis($idcompany)->result(),
            'kelompok'          => $this->mmaster->get_kelompok($idcompany)->result(),           
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->input->post('id', TRUE);
        $itypecode  = $this->input->post('itypecode', TRUE);
        $ikelompok  = $this->input->post('ikelompok', TRUE);
        $etypename  = $this->input->post('etypename', TRUE);  
        $igroupbrg  = $this->input->post('igroupbrg', TRUE);
        $idcompany = $this->session->userdata('id_company');

        if ($itypecode != '' && $ikelompok != ''){               
                $this->mmaster->update($id, $itypecode, $ikelompok, $etypename, $igroupbrg, $idcompany);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $itypecode
                );
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $itypecode= $this->uri->segment('4');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($itypecode, $idcompany)->row(),
            'groupbarang'       => $this->mmaster->get_group($idcompany)->result(),
            'jenis_bahanbaku'   => $this->mmaster->get_jenis($idcompany)->result(),
            'kelompok'          => $this->mmaster->get_kelompok($idcompany)->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */