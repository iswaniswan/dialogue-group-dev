<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '101050109';

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
		echo $this->mmaster->data($this->i_menu);
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
            'plugroup'=> $this->mmaster->get_plu()->result()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
	}
	
	public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icustomerplugroup 	= $this->input->post('icustomerplugroup', TRUE);
        $icustomerplu       = $this->input->post('icustomerplu', TRUE);
        $iproduct           = $this->input->post('iproduct', TRUE);

        if ($icustomerplugroup != '' || $icustomerplu != '' || $iproduct != ''){
                $cekada = $this->mmaster->cek_data($icustomerplu, $icustomerplugroup);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{

                    $this->db->trans_begin();
                    $this->mmaster->insert($icustomerplugroup, $icustomerplu, $iproduct);
                    
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();

                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$icustomerplu);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $icustomerplu
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icustomerplugroup      = $this->uri->segment('4');
        $icustomerplu           = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($icustomerplu, $icustomerplugroup)->row(),
            'plugroup'=> $this->mmaster->get_plu()->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $icustomerplugroup 	= $this->input->post('icustomerplugroup', TRUE);
        $icustomerplu       = $this->input->post('icustomerplu', TRUE);
        $iproduct           = $this->input->post('iproduct', TRUE);
        $fcustomerpluaktif  = $this->input->post('fcustomerpluaktif', TRUE);

        if ($icustomerplugroup != '' || $icustomerplu != '' || $iproduct != ''){
            $cekada = $this->mmaster->cek_data($icustomerplu, $icustomerplugroup);
            if($cekada->num_rows() > 0){ 
                $this->db->trans_begin();

                $this->mmaster->update($icustomerplugroup, $icustomerplu, $iproduct,$fcustomerpluaktif);
                
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$icustomerplu);
                    
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $icustomerplu
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
	
	public function view(){

        $icustomerplugroup      = $this->uri->segment('4');
        $icustomerplu           = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($icustomerplu, $icustomerplugroup)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    function data_product(){
        $filter = [];

        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tr_product");
            $this->db->like("i_product", $cari);
            $this->db->or_like("UPPER(e_product_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as $row){
                    $filter[] = array(
                    'id'    => $row->i_product,  
                    'text'  => $row->i_product.'-'.$row->e_product_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
}
?>
