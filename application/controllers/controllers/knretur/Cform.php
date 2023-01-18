<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050801';

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

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getreferensi(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $iarea  = $this->input->get('iarea', FALSE);
            $data   = $this->mmaster->getreferensi($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_bbm,  
                    'text'  => $kuy->i_bbm." - ".$kuy->d_bbm." - ".$kuy->i_ttb
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailref(){
        header("Content-Type: application/json", true);
        $ibbm   = $this->input->post('ibbm', FALSE);
        $iarea  = $this->input->post('iarea', FALSE);
        $data   = $this->mmaster->getdetailref($ibbm, $iarea);
        $query  = array(
            'data'   => $data->result_array(),
            'jml'    => $this->mmaster->jmldetail($ibbm),
            'detail' => $this->mmaster->getdetailbbm($ibbm)->result_array()
        );
        echo json_encode($query);  
    }

    public function getpajak(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('icustomer') !='' && $this->input->get('iproduct') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $icustomer  = $this->input->get('icustomer', FALSE);
            $iproduct   = $this->input->get('iproduct', FALSE);
            $data       = $this->mmaster->getpajak($cari,$icustomer,$iproduct);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota." - ".$kuy->i_seri_pajak
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailpajak(){
        header("Content-Type: application/json", true);
        $inota      = $this->input->post('inota', FALSE);
        $iproduct   = $this->input->post('iproduct', FALSE);
        $icustomer  = $this->input->post('icustomer', FALSE);
        $data       = $this->mmaster->getdetailpajak($inota, $icustomer, $iproduct);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea              = $this->input->post('iarea', TRUE);
        $ikn                = $this->input->post('ikn', TRUE);
        $icustomer          = $this->input->post('icustomer', TRUE);
        $irefference        = $this->input->post('irefference', TRUE);
        $icustomergroupar   = $this->input->post('icustomergroupar', TRUE);
        $isalesman          = $this->input->post('isalesman', TRUE);
        $ikntype            = '01';
        $drefference        = $this->input->post('drefference', TRUE);
        if($drefference!=''){
            $tmp=explode("-",$drefference);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $drefference=$th."-".$bl."-".$hr;
        }
        $dkn                = $this->input->post('dkn', TRUE);
        if($dkn!=''){
            $tmp=explode("-",$dkn);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dkn=$th."-".$bl."-".$hr;
            $nknyear=$th;
        }
        $ipajak             = $this->input->post('ipajak', TRUE);
        $dpajak             = $this->input->post('dpajak', TRUE);
        if($dpajak!=''){
            $tmp=explode("-",$dpajak);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dpajak=$th."-".$bl."-".$hr;
        }
        $fcetak             = 'f';
        $fmasalah           = $this->input->post('fmasalah', TRUE);
        if($fmasalah==''){
            $fmasalah='f';
        }else{
            $fmasalah='t';
        }
        $finsentif  = $this->input->post('finsentif', TRUE);
        if($finsentif==''){
            $finsentif='f';
        }else{
            $finsentif='t';
        }
        $vnetto         = $this->input->post('vnetto', TRUE);
        $vnetto         = str_replace(',','',$vnetto);
        $vsisa          = $vnetto;
        $vgross         = $this->input->post('vgross', TRUE);
        $vgross         = str_replace(',','',$vgross);
        $vdiscount      = $this->input->post('vdiscount', TRUE);
        $vdiscount      = str_replace(',','',$vdiscount);
        if($vdiscount=='') {
            $vdiscount=0;
        }
        $eremark    = $this->input->post('eremark', TRUE);
        if ((isset($irefference) && $irefference != '') && (isset($iarea) && $iarea != '') && (isset($icustomer) && $icustomer != '') && ($finsentif != 'f')){
            $this->db->trans_begin();
            $ikn = $this->mmaster->runningnumberkn($nknyear,$iarea);
            $this->mmaster->insert($iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah,$finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference,$ipajak,$dpajak);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input KN Retur Area '.$iarea.' No:'.$ikn);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikn
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
