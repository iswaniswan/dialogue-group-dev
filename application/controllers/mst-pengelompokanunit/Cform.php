<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011202';
   
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

    function unitjahit(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_unit_jahit, e_unitjahit_name");
            $this->db->from("tr_unit_jahit");
            $this->db->like("UPPER(i_unit_jahit)", $cari);
            $this->db->or_like("UPPER(e_unitjahit_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iunitjahit){
                    $filter[] = array(
                    'id' => $iunitjahit->i_unit_jahit,  
                    'text' => $iunitjahit->i_unit_jahit.' - '.$iunitjahit->e_unitjahit_name
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    /*function getjahit(){
        header("Content-Type: application/json", true);
        $iunitjahit = $this->input->post('iunitjahit');
        $this->db->select("i_unit_jahit, e_unitjahit_name");
            $this->db->from("tr_unit_jahit");
            $this->db->where("UPPER(i_unit_jahit)", $iunitjahit);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }*/


    function unitpacking(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_unit_packing, e_nama_packing");
            $this->db->from("tr_unit_packing");
            $this->db->like("UPPER(i_unit_packing)", $cari);
            $this->db->or_like("UPPER(e_nama_packing)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iunitpacking){
                    $filter[] = array(
                    'id' => $iunitpacking->i_unit_packing,  
                    'text' => $iunitpacking->i_unit_packing.' - '.$iunitpacking->e_nama_packing
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    /*function getpacking(){
        header("Content-Type: application/json", true);
        $iunitjahit = $this->input->post('iunitpacking');
        $this->db->select("i_unit_packing, e_nama_packing");
            $this->db->from("tr_unit_packing");
            $this->db->where("UPPER(i_unit_packing)", $iunitpacking);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }*/
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'kelompok_unit'     => $this->mmaster->get_kelompokunit()->result(),
            'unit_detail'       => $this->mmaster->get_unitdetail()->result(),
            'unit_jahit'        => $this->mmaster->get_unitjahit()->result(),
            'unit_packing'      => $this->mmaster->get_unitpacking()->result(),  
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $ikelompokunit    = $this->input->post('ikelompokunit', TRUE);
        $namakelompok     = $this->input->post('namakelompok', TRUE); 
        $jml              = $this->input->post('jml', TRUE);       
        
        /*if(($ikelompokunit != '') && ($namakelompok != '')){
            //var_dump($jml);

        for($i=1;$i<=$jml;$i++){  
            $iunitjahit       = $this->input->post('iunitjahit'.$i, TRUE);
            $iunitpacking     = $this->input->post('iunitpacking'.$i, TRUE);     
            $this->mmaster->insert($ikelompokunit, $namakelompok);
            $this->mmaster->insert2($ikelompokunit, $iunitjahit, $iunitpacking);                 
        }

        if ($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $data = array(
                'sukses' => false
                );
            }else{
                $this->db->trans_commit();

                $data = array(
                'sukses'    => true,
                'kode'      => $ikelompokunit
                );
            }
            $this->load->view('pesan', $data);
        }
    }*/    
        if ($ikelompokunit != ''){
            $cekada = $this->mmaster->cek_data($ikelompokunit);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                for($i=1;$i<=$jml;$i++){  
                    $iunitjahit   = $this->input->post('iunitjahit'.$i, TRUE);
                    $iunitpacking = $this->input->post('iunitpacking'.$i, TRUE);

                    $this->mmaster->insert2($ikelompokunit, $iunitjahit, $iunitpacking);                 
                }
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikelompokunit);
                $this->mmaster->insert($ikelompokunit, $namakelompok);
                 
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikelompokunit
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

        $ikelompokunit = $this->uri->segment('4');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($ikelompokunit)->row(),
            'data2'             => $this->mmaster->cek_data2($ikelompokunit),
            'kelompok_unit'     => $this->mmaster->get_kelompokunit()->result(),
            'unit_detail'       => $this->mmaster->get_unitdetail()->result(),
            'unit_jahit'        => $this->mmaster->get_unitjahit()->result(),
            'unit_packing'      => $this->mmaster->get_unitpacking()->result(),            
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ikelompokunit    = $this->input->post('ikelompokunit', TRUE);
        $namakelompok     = $this->input->post('namakelompok', TRUE); 
        //$id               = $this->input->post('id', TRUE); 
        $jml              = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikelompokunit);
        $this->mmaster->update($ikelompokunit, $namakelompok);

            for($i=1;$i<=$jml;$i++){                                        
                    $iunitjahit   = $this->input->post('iunitjahit'.$i, TRUE);
                    $iunitpacking = $this->input->post('iunitpacking'.$i, TRUE);

                    $this->mmaster->deletedetail($ikelompokunit, $iunitjahit, $iunitpacking);
                    $this->mmaster->insert2($ikelompokunit, $iunitjahit, $iunitpacking);
                    //$this->mmaster->updatenota($ipajak, $inota);
            }
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $ikelompokunit,
                );
        }
    $this->load->view('pesan', $data);  
    }

    public function view(){

        $ikelompokunit= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ikelompokunit)->row(),
            'data2'         => $this->mmaster->cek_data2($ikelompokunit),
            //'kelompok_unit'     => $this->mmaster->get_kelompokunit()->result(),
            //'unit_detail'       => $this->mmaster->get_unitdetail()->result(),
            //'unit_jahit'        => $this->mmaster->get_unitjahit()->result(),
            //'unit_packing'      => $this->mmaster->get_unitpacking()->result(),  
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */