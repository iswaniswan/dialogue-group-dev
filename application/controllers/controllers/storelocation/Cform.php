<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010402';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

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

        $istorelocation 			= $this->input->post('istorelocation', TRUE);
        $estorelocationname 		= $this->input->post('estorelocationname', TRUE);
        $istore	                    = $this->input->post('istore', TRUE);
        $istorelocationbin          = $this->input->post('istorelocationbin', TRUE);

        if ($istorelocation != '' && $estorelocationname != '' && $istore != '' && $istorelocationbin != ''){
                $cekada = $this->mmaster->cek_data($istorelocation,$istore,$istorelocationbin);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->db->trans_begin();
                    $this->mmaster->insert($istorelocation,$estorelocationname,$istore,$istorelocationbin);
                    
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();

                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istorelocation);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $istorelocation
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

        $istorelocation     = $this->uri->segment('4');
        $istore             = $this->uri->segment('5');
        $istorelocationbin  = $this->uri->segment('6');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($istorelocation, $istore, $istorelocationbin)->row()
        );
        
        $data['store'] = $this->mmaster->bacagudang();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 3);

        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $istorelocation 			= $this->input->post('istorelocation', TRUE);
        $estorelocationname 		= $this->input->post('estorelocationname', TRUE);
        $istore                 	= $this->input->post('istore', TRUE);
        $istorelocationbin      	= $this->input->post('istorelocationbin', TRUE);
        $estorename                 = $this->input->post('estorename', TRUE);

        if ($istorelocation != '' && $estorelocationname != '' && $istore != '' && $istorelocationbin != ''){
            $cekada = $this->mmaster->cek_data($istorelocation, $istore, $istorelocationbin);
            if($cekada->num_rows() > 0){ 

                $this->db->trans_begin();

                $this->mmaster->update($istorelocation,$estorelocationname,$istore,$istorelocationbin);
                
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$istorelocation);
                    
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $istorelocation
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

    public function view()
    {
        $istorelocation       = $this->uri->segment('4');
        $istore               = $this->uri->segment('5');
        $istorelocationbin    = $this->uri->segment('6');
       
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($istorelocation,$istore,$istorelocationbin)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    function data_store(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_store, e_store_name");
            $this->db->from("tr_store");
            $this->db->like("UPPER(i_store)", $cari);
            $this->db->or_like("UPPER(e_store_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $store){
                    $filter[] = array(
                    'id' => $store->i_store,  
                    'text' => $store->i_store.'-'.$store->e_store_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

}

/* End of file Cform.php */
