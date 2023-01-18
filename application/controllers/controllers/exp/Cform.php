<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010405';

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
        );

        $data['area'] = $this->mmaster->bacaarea();

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iekspedisi 			= $this->input->post('iekspedisi', TRUE);
        $eekspedisi 		    = $this->input->post('eekspedisi', TRUE);
        $iarea          	    = $this->input->post('iarea', TRUE);
        $eekspedisiaddress	    = $this->input->post('eekspedisiaddress', TRUE);
        $eekspedisicity	        = $this->input->post('eekspedisicity', TRUE);
        $eekspedisiphone	    = $this->input->post('eekspedisiphone', TRUE);
        $eekspedisifax	        = $this->input->post('eekspedisifax', TRUE);

        if ($iekspedisi != '' && $eekspedisi != '' && $iarea != ''){
                $cekada = $this->mmaster->cek_data($iekspedisi);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    /*$this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iekspedisi);
                    $this->mmaster->insert($iekspedisi,$eekspedisi,$iarea,$eekspedisiaddress,$eekspedisicity,$eekspedisiphone,$eekspedisifax);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iekspedisi
                    );*/
                    $this->db->trans_begin();
                    $this->mmaster->insert($iekspedisi,$eekspedisi,$iarea,$eekspedisiaddress,$eekspedisicity,$eekspedisiphone,$eekspedisifax);
                    
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();

                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iekspedisi);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $iekspedisi
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

        $iekspedisi = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iekspedisi)->row()
        );

        $data['area'] = $this->mmaster->bacaarea();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iekspedisi 			= $this->input->post('iekspedisi', TRUE);
        $eekspedisi 		    = $this->input->post('eekspedisi', TRUE);
        $iarea          	    = $this->input->post('iarea', TRUE);
        $eekspedisiaddress	    = $this->input->post('eekspedisiaddress', TRUE);
        $eekspedisicity	        = $this->input->post('eekspedisicity', TRUE);
        $eekspedisiphone	    = $this->input->post('eekspedisiphone', TRUE);
        $eekspedisifax	        = $this->input->post('eekspedisifax', TRUE);


        if ($iekspedisi != '' && $eekspedisi != '' && $iarea != ''){
            $cekada = $this->mmaster->cek_data($iekspedisi);
            if($cekada->num_rows() > 0){ 
                /*$this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iekspedisi);
                $this->mmaster->update($iekspedisi,$eekspedisi,$iarea,$eekspedisiaddress,$eekspedisicity,$eekspedisiphone,$eekspedisifax);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iekspedisi
                );*/
                $this->db->trans_begin();
                $this->mmaster->update($iekspedisi,$eekspedisi,$iarea,$eekspedisiaddress,$eekspedisicity,$eekspedisiphone,$eekspedisifax);
                    
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();

                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iekspedisi);
                    $data = array(
                                  'sukses'    => true,
                                  'kode'      => $iekspedisi
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
        $iekspedisi = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iekspedisi)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    function data_area(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_area, e_area_name");
            $this->db->from("tr_area");
            $this->db->like("UPPER(i_area)", $cari);
            $this->db->or_like("UPPER(e_area_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $area){
                    $filter[] = array(
                    'id' => $area->i_area,  
                    'text' => $area->i_area.'-'.$area->e_area_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
}

/* End of file Cform.php */
