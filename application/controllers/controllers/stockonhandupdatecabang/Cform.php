<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070306';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function datastore(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacastore($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_store,  
                'text'  => $row->e_store_name.' - '.$row->e_store_locationname
            );
        }
        echo json_encode($filter);
    }

    public function detailstore(){
        header("Content-Type: application/json", true);
        $istore = $this->input->post('istore', FALSE);
        $data   = $this->mmaster->detailstore($istore);
        echo json_encode($data->result_array());  
    } 

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iperiode       = $this->input->post('tahun').$this->input->post('bulan');
        $store          = $this->input->post('istore');
        $istorelocation = $this->input->post('istorelocation');
        $this->db->trans_begin();
        $this->mmaster->updateic($store,$istorelocation);
        if ($store=='AA') {
            $query = $this->mmaster->bacasaldopusat($iperiode,$store,$istorelocation);
        }else{
            $query = $this->mmaster->bacasaldodaerah($iperiode,$store,$istorelocation);
        }
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                $this->mmaster->updatesstokic($key->i_product,$key->i_store,$key->i_product_grade,$key->i_store_location,$key->i_product_motif,$key->saldo_akhir);
                $data = '1';
            }
        }else{
            $data = '0';
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write("Update Data Stok On Hand");
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
