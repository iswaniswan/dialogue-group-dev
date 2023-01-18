<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020502';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
            'kirim'     => $this->mmaster->bacadkb($username,$idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function datasj(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter  = [];
            $tgl1    = date('Y-m-d'); /*pendefinisian tanggal awal*/
            $tgl2    = date('Y-m-d', strtotime('-3 month', strtotime($tgl1))); /*operasi penjumlahan tanggal sebanyak 6 bulan*/
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = $this->input->get('iarea', FALSE);
            $data    = $this->mmaster->bacasj($cari,$iarea,$tgl2);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sjp,  
                    'text'  => $kuy->i_sjp
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsj(){
        header("Content-Type: application/json", true);
        $tgl1    = date('Y-m-d'); /*pendefinisian tanggal awal*/
        $tgl2    = date('Y-m-d', strtotime('-3 month', strtotime($tgl1))); /*operasi penjumlahan tanggal sebanyak 6 bulan*/
        $isj     = $this->input->post('isj', FALSE);
        $iarea   = $this->input->post('iarea', FALSE);
        $data    = $this->mmaster->bacasjx($iarea,$isj,$tgl2);
        echo json_encode($data->result_array());  
    }

    public function dataex(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->bacaex($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_ekspedisi,  
                    'text'  => $kuy->i_ekspedisi." - ".$kuy->e_ekspedisi
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailex(){
        header("Content-Type: application/json", true);
        $iekspedisi = $this->input->post('iekspedisi', FALSE);
        $data = $this->mmaster->bacaexx($iekspedisi);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dbapb  = $this->input->post('dbapb', TRUE);
        if($dbapb!=''){
            $dbapb = $dbapb;
        }else{
            $dbapb = date('Y-m-d');
        }
        $tmp=explode("-",$dbapb);
        $th=$tmp[2];
        $bl=$tmp[1];
        $hr=$tmp[0];
        $dbapb=$th."-".$bl."-".$hr;
        $thbl=substr($th,2,2).$bl;
        $ibapbold       = $this->input->post('ibapbold', TRUE);
        $ibapb          = $this->input->post('ibapb', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $idkbkirim      = $this->input->post('idkbkirim', TRUE);
        $nbal           = $this->input->post('nbal', TRUE);
        $nbal           = str_replace(',','',$nbal);
        $jml            = $this->input->post('jml', TRUE);
        $jmlx           = $this->input->post('jmlx', TRUE);
        $vbapb          = $this->input->post('vbapb', TRUE);
        $vbapb          = str_replace(',','',$vbapb);
        $vkirim         = $this->input->post('vkirim', TRUE);
        $vkirim         = str_replace(',','',$vkirim);
        if($dbapb!='' && $iarea!='' && $idkbkirim!='' && $jml!='0' && $jmlx!='0' && $vbapb!='0' ){
            $this->db->trans_begin();
            $ibapb = $this->mmaster->runningnumber($iarea, $thbl);
            $this->mmaster->insertheader($ibapb, $dbapb, $iarea, $idkbkirim, $nbal, $ibapbold, $vbapb, $vkirim);
            for($i=1;$i<=$jml;$i++){              
                $isj     = $this->input->post('isj'.$i, TRUE);
                $dsj     = $this->input->post('dsj'.$i, TRUE);
                $vsj     = $this->input->post('vsj'.$i, TRUE);
                $vsj     = str_replace(',','',$vsj);
                $eremark = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$vsj);
                $this->mmaster->updatesj($ibapb,$isj,$iarea,$dbapb);
            }
            for($i=1;$i<=$jmlx;$i++){
                $iekspedisi = $this->input->post('iekspedisi'.$i, TRUE);
                $eremark    = $this->input->post('eremarkx'.$i, TRUE);
                $this->mmaster->insertdetailekspedisi($ibapb,$iarea,$iekspedisi,$dbapb,$eremark);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input BAPB-SJP No:'.$ibapb.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibapb
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
