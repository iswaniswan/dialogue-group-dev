<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010401';

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

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $istore 			= $this->input->post('istore', TRUE);
        $estorename 		= $this->input->post('estorename', TRUE);
        $estoreshortname    = $this->input->post('estoreshortname', TRUE);
        $dstoreregister 	= $this->input->post('dstoreregister', TRUE);
        if($dstoreregister!=''){
            $tmp            =explode("-",$dstoreregister);
            $th             =$tmp[2];
            $bl             =$tmp[1];
            $hr             =$tmp[0];
            $dstoreregister =$th."-".$bl."-".$hr;
        }

        if ($istore != '' && $estorename != ''){
                $cekada = $this->mmaster->cek_data($istore);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{ 
                    /*$this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istore);
                    $this->mmaster->insert($istore,$estorename,$estoreshortname,$dstoreregister);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $istore
                    );*/
                    $this->db->trans_begin();
                    $this->mmaster->insert($istore,$estorename,$estoreshortname,$dstoreregister);
                    
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();

                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istore);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $istore
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

        $i_store = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($i_store)->row()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $istore 			= $this->input->post('istore', TRUE);
        $estorename 		= $this->input->post('estorename', TRUE);
        $estoreshortname    = $this->input->post('estoreshortname', TRUE);
        $dstoreregister	    = $this->input->post('dstoreregister', TRUE);
        if($dstoreregister!=''){
            $tmp            =explode("-",$dstoreregister);
            $th             =$tmp[2];
            $bl             =$tmp[1];
            $hr             =$tmp[0];
            $dstoreregister =$th."-".$bl."-".$hr;
        }

        if ($istore != '' && $estorename != ''){
            $cekada = $this->mmaster->cek_data($istore);
            if($cekada->num_rows() > 0){ 
                $this->db->trans_begin();

                $this->mmaster->update($istore,$estorename,$estoreshortname,$dstoreregister);
                
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$istore);
                    
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $istore
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

        $istore = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($istore)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
