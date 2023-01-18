<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107011803';
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
            'title'     => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function getsupplier(){
        $filter = [];
        /*if($this->input->get('q') != '' && $this->input->get('iarea') !='') {*/
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getsupplier($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_supplier,  
                    'text'  => $kuy->i_supplier." - ".$kuy->e_supplier_name
                );
            }
            echo json_encode($filter);
        /*} else {
            echo json_encode($filter);
        }*/
    }

    public function view(){
        $dfrom     = $this->input->post('dfrom', TRUE);
        if ($dfrom =='') {
            $dfrom = $this->uri->segment(4);
        }
        $dto       = $this->input->post('dto', TRUE);
        if ($dto   =='') {
            $dto   = $this->uri->segment(5);
        }
        $isupplier     = $this->input->post('isupplier', TRUE);
        if ($isupplier =='') {
            $isupplier = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'isupplier' => $isupplier
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $isupplier, $this->global['folder'], $this->i_menu);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikuk      = $this->input->post('ikuk', TRUE);
        $nkukyear  = $this->input->post('nkukyear', TRUE);
        $isupplier = $this->input->post('isupplier', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ikuk, $nkukyear, $isupplier);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Menghapus Transfer Uang Keluar Supplier : '.$isupplier.' No : '.$ikuk);
            echo json_encode($data);
        }
    }

    public function edit(){
        $ikuk       = $this->uri->segment(4);
        $nkukyear   = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $ikuk       = str_replace('|','/',$this->uri->segment(4));
        $ikuk       = str_replace('%20',' ',$ikuk);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'ikuk'       => $ikuk,
            'nkukyear'   => $nkukyear,
            'isupplier'  => $isupplier,
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'i_menu'     => $this->i_menu,
            'isi'        => $this->mmaster->baca($ikuk,$nkukyear),
        );

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcustomer($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailcustomer(){
        header("Content-Type: application/json", true);
        $iarea     = $this->input->post('iarea');
        $icustomer = $this->input->post('icustomer');
        $data      = $this->mmaster->getdetailcustomer($iarea, $icustomer);      
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikuk   = $this->input->post('ikuk', TRUE);
        $ikuk   = str_replace('%20','',$ikuk);
        $dkuk   = $this->input->post('dkuk', TRUE);
        if($dkuk!=''){
            $tmp=explode("-",$dkuk);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dkuk=$th."-".$bl."-".$hr;
            $tahun=$th;
        }
        $ebankname          = $this->input->post('ebankname', TRUE);
        $eareaname          = $this->input->post('eareaname', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE);
        $esuppliername      = $this->input->post('esuppliername', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $vjumlah            = $this->input->post('vjumlah', TRUE);
        $vjumlah            = str_replace(',','',$vjumlah);
        $vsisa              = $this->input->post('vsisa', TRUE);
        $vsisa              = str_replace(',','',$vsisa);
        if (($ikuk != '') && ($tahun!='')){
            $this->db->trans_begin();
            $this->mmaster->update($ikuk,$dkuk,$tahun,$ebankname,$isupplier,$eremark,$vjumlah,$vsisa);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Transfer Uang Keluar Supplier : '.$isupplier.' No : '.$ikuk);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikuk
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
