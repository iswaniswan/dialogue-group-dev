<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010403';
    
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
            'jenis_gudang'  => $this->mmaster->get_jenis_gudang()->result()  
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function groupbarang(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->db->query("select a.*
                        from tm_group_barang a 
                        where a.status='t'
                        and (a.i_kode_group_barang like '%$cari%' or a.e_nama_group_barang like '%$cari%') 
                        order by a.i_kode_group_barang");
        foreach ($data->result() as $ikode) {
            $filter[] = array(
                'id'    => $ikode->i_kode_group_barang,
                'text'  => $ikode->e_nama_group_barang,

            );
        }
        echo json_encode($filter);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodejenis     = $this->input->post('ikodejenis', TRUE);
        $enamajenis     = $this->input->post('enamajenis', TRUE);      
        $igroup         = $this->input->post('igroup', TRUE);     

        if ($ikodejenis != '' && $enamajenis != ''){
                $cekada = $this->mmaster->cek_data($ikodejenis);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikodejenis);
                    $this->mmaster->insert($ikodejenis, $enamajenis, $igroup);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ikodejenis
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

        $ikodejenis = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ikodejenis)->row(),
            'jenis_gudang'  => $this->mmaster->get_jenis_gudang()->result(),
            'group'         => $this->mmaster->cek_group()->result(),
          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ikodejenis     = $this->input->post('ikodejenis', TRUE);
        $enamajenis     = $this->input->post('enamajenis', TRUE);        
        $igroup         = $this->input->post('igroup', TRUE);

        if ($ikodejenis != '' && $enamajenis != ''){
            $cekada = $this->mmaster->cek_data($ikodejenis);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($ikodejenis, $enamajenis, $igroup);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodejenis
                );
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

        $ikodejenis= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ikodejenis)->row(),
            'group'         => $this->mmaster->cek_group()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */