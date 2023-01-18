<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107011802';
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
            'iarea'     => $this->mmaster->bacaarea()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

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
        $iarea     = $this->input->post('iarea', TRUE);
        if ($iarea =='') {
            $iarea = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'], $this->i_menu);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikum      = $this->input->post('ikum', TRUE);
        $nkumyear  = $this->input->post('nkumyear', TRUE);
        $iarea     = $this->input->post('iarea', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ikum, $nkumyear, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Menghapus Transfer Uang Masuk Area : '.$iarea.' No : '.$ikum);
            echo json_encode($data);
        }
    }

    public function edit(){
        $ikum       = $this->uri->segment(4);
        $nkumyear   = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $dkum       = $this->uri->segment(9);

        $tmp 	    = explode("-", $dkum);
        $yir	    = $tmp[0];
        $mon	    = $tmp[1];
        $periode	= $yir.$mon;

        $ikum       = str_replace('|','/',$this->uri->segment(4));
        $ikum       = str_replace('%20',' ',$ikum);
        $idcompany  = $this->session->userdata('id_company');
        $username   = $this->session->userdata('username');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'ikum'       => $ikum,
            'iarea'      => $iarea,
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
            'nkumyear'   => $nkumyear,
            'i_menu'     => $this->i_menu,
            'isi'        => $this->mmaster->baca($iarea,$ikum,$nkumyear),
            'pst'        => $this->mmaster->bacaareauser($idcompany,$username),
            'area'       => $this->mmaster->getarea(),
            'bank'       => $this->mmaster->bacabank(),
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

        $ikum   = $this->input->post('ikum', TRUE);
        $xkum   = $this->input->post('xkum', TRUE);
        $xdkum  = $this->input->post('xdkum', TRUE);
        if($xdkum!=''){
            $tmp=explode("-",$xdkum);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $xdkum=$th."-".$bl."-".$hr;
            $xtahun=$th;
        }
        $dkum   = $this->input->post('dkum', TRUE);
        if($dkum!=''){
            $tmp=explode("-",$dkum);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dkum=$th."-".$bl."-".$hr;
            $tahun=$th;
        }
        $ibank            = $this->input->post('ibank', TRUE);
        $ebankname        = $this->input->post('ebankname', TRUE);
        $iarea            = $this->input->post('iarea', TRUE);
        $iareaasal        = $this->input->post('iareaasal', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $icustomergroupar = $this->input->post('icustomergroupar', TRUE);
        $isalesman        = $this->input->post('isalesman', TRUE);
        $esalesmanname    = $this->input->post('esalesmanname', TRUE);
        $eremark          = $this->input->post('eremark', TRUE);
        $vjumlah          = $this->input->post('vjumlah', TRUE);
        $vjumlah          = str_replace(',','',$vjumlah);
        $vsisa            = $this->input->post('vsisa', TRUE);
        $vsisa            = str_replace(',','',$vsisa);
        if (($ikum != '') && ($iarea!='') && ($tahun!='') && ($ibank!='')){
            $this->db->trans_begin();
            $this->mmaster->update($ikum,$xkum,$dkum,$xtahun,$tahun,$ebankname,$iarea,$icustomer,$icustomergroupar,$isalesman,$eremark,$vjumlah,$vsisa,$iareaasal);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Transfer Uang Masuk Area : '.$iarea.' No : '.$ikum);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikum
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
