<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2080101';

    public function __construct(){
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

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function unitpacking(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_unit_packing");
            $this->db->like("UPPER(i_unit_packing)", $cari);
            $this->db->or_like("UPPER(e_nama_packing)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $isupplier){
                    $filter[] = array(
                    'id'   => $isupplier->i_unit_packing,  
                    'text' => $isupplier->i_unit_packing.'-'.$isupplier->e_nama_packing,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function gudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_master_gudang");
            $this->db->like("UPPER(i_kode_master)", $cari);
            $this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $isupplier){
                    $filter[] = array(
                    'id'   => $isupplier->i_kode_master,  
                    'text' => $isupplier->i_kode_master.'-'.$isupplier->e_nama_master,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    
    public function view(){
        $dso          = $this->input->post('dso', TRUE);  
        if($dso!=''){
            $tmp=explode("-",$dso);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dateso=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }     
        $iunitpacking = $this->input->post('iunitpacking', TRUE);       
        $igudang      = $this->input->post('igudang', TRUE);

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'title_list'=> $this->global['title'],
            'dso'       => $dso,
            'packing'   => $this->mmaster->packing($iunitpacking)->row(),
            'gudang'    => $this->mmaster->gudang($igudang)->row(),
            'data'      => $this->mmaster->getso($dso, $iunitpacking, $igudang)->result(), 
        );
        $this->Logger->write('Membuka Menu'.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dso    = $this->input->post('dso', TRUE);
        if($dso!=''){
            $tmp=explode("-",$dso);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dateso=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iunitpacking  = $this->input->post('iunitpacking', TRUE);
        $igudang       = $this->input->post('igudang', TRUE);
      
        $jml           = $this->input->post('jml', TRUE);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$dso);

        for($i=1;$i<=$jml;$i++){
            $iproduct       = $this->input->post('iproduct'.$i, TRUE);              
            $eproduct       = $this->input->post('eproduct'.$i, TRUE);
            $saldoawal      = $this->input->post('saldoawal'.$i, TRUE);
            $masukpacking   = $this->input->post('masukpacking'.$i, TRUE);
            $keluarpacking  = $this->input->post('keluarpacking'.$i, TRUE);
            $salhir         = $this->input->post('salhir'.$i, TRUE); 
            $nitemno        = $i;
            $this->mmaster->insertdetail($thbl, $saldoawal, $iproduct, $eproduct, $salhir, $nitemno);
        }
        $this->mmaster->insertheader($dateso, $thbl, $iunitpacking, $igudang);
           
        if ($this->db->trans_status() === FALSE){
        $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
                
            );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $dso,
            );
        }  
        $this->load->view('pesan', $data);  
    }
}
/* End of file Cform.php */