<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020503';

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
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function datasj(){
        $filter = [];
        if($this->input->get('q') != '') {
            $tgl1    = date('Y-m-d'); /*pendefinisian tanggal awal*/
            $tgl2    = date('Y-m-d', strtotime('-3 month', strtotime($tgl1))); /*operasi penjumlahan tanggal sebanyak 6 bulan*/
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->bacasj($cari,$tgl2);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sjpb,  
                    'text'  => $kuy->i_sjpb
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsj(){
        header("Content-Type: application/json", true);
        $isj   = $this->input->post('isj', FALSE);
        $tgl1  = date('Y-m-d'); /*pendefinisian tanggal awal*/
        $tgl2  = date('Y-m-d', strtotime('-3 month', strtotime($tgl1))); /*operasi penjumlahan tanggal sebanyak 6 bulan*/
        $data  = $this->mmaster->bacasjx($tgl2,$isj);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iarea  = 'PB';
        $vbapb  = $this->input->post('vbapb', TRUE);
        $vbapb  = str_replace(',','',$vbapb);
        $jml    = $this->input->post('jml', TRUE);
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

        if($dbapb!='' &&  $vbapb!='0'){
            $this->db->trans_begin();
            $ibapb = $this->mmaster->runningnumber($iarea, $thbl);
            $this->mmaster->insertheader($ibapb, $dbapb, $iarea, $vbapb);
            $nilaitotal = 0;
            for($i=1;$i<=$jml;$i++){              
                $isj     = $this->input->post('isj'.$i, TRUE);
                $dsj     = $this->input->post('dsj'.$i, TRUE);
                $vsj     = $this->input->post('vsj'.$i, TRUE);
                $vsj     = str_replace(',','',$vsj);
                $eremark = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$vsj);
                $this->mmaster->updatesj($ibapb,$isj,$iarea,$dbapb);
                $nilaitotal = $nilaitotal + $vsj;
            }
            $this->mmaster->updatesjb($ibapb,$iarea,$nilaitotal);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input BAPB-SJPB No:'.$ibapb.' Area:'.$iarea);
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
