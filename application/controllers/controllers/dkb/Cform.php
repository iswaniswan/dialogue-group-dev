<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10204';

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
            'via'       => $this->mmaster->bacavia(),
            'kirim'     => $this->mmaster->bacadkb($username,$idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function datasj(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = strtoupper($this->input->get('iarea', FALSE));
            $ddkb    = $this->input->get('ddkb', FALSE);
            if($ddkb){              
                $tmp=explode("-",$ddkb);              
                $dd=$tmp[0];
                $mm=$tmp[1];
                $yy=$tmp[2];
                $ddkbx=$yy.'-'.$mm.'-'.$dd;
            }
            $data    = $this->mmaster->bacasj($cari,$iarea,$ddkbx);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sj,  
                    'text'  => $kuy->i_sj
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsj(){
        header("Content-Type: application/json", true);
        $isj   = strtoupper($this->input->post('isj', FALSE));
        $iarea = strtoupper($this->input->post('iarea', FALSE));
        $ddkb  = $this->input->post('ddkb', FALSE);
        if($ddkb){              
            $tmp=explode("-",$ddkb);              
            $dd=$tmp[0];
            $mm=$tmp[1];
            $yy=$tmp[2];
            $ddkbx=$yy.'-'.$mm.'-'.$dd;
        }
        $data = $this->mmaster->bacasjx($iarea,$ddkbx,$isj);
        echo json_encode($data->result_array());  
    }

    public function dataex(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea')!='') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = strtoupper($this->input->get('iarea', FALSE));
            $data    = $this->mmaster->bacaex($cari,$iarea);
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
        $iekspedisi = strtoupper($this->input->post('iekspedisi', FALSE));
        $iarea      = strtoupper($this->input->post('iarea', FALSE));
        $data = $this->mmaster->bacaexx($iarea,$iekspedisi);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iarea      = $this->mmaster->areanya();
        $ddkb       = $this->input->post('ddkb', TRUE);
        if($ddkb!=''){
            $tmp=explode("-",$ddkb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $ddkb=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }else{
            $ddkb = date('Y-m-d');
        }
        $idkbold    = $this->input->post('idkbold', TRUE);
        $idkb       = $this->input->post('idkb', TRUE);
        $iareasj    = $this->input->post('iarea', TRUE);
        $edkbkirim  = $this->input->post('edkbkirim', TRUE);
        $idkbkirim  = $this->input->post('idkbkirim', TRUE);
        $edkbvia    = $this->input->post('edkbvia', TRUE);
        $idkbvia    = $this->input->post('idkbvia', TRUE);
        $eekspedisi = $this->input->post('eekspedisi', TRUE);
        $esupirname = $this->input->post('esupirname', TRUE);
        $ikendaraan = $this->input->post('ikendaraan', TRUE);       
        $vdkb       = $this->input->post('vdkb', TRUE);       
        $vdkb       = str_replace(',','',$vdkb);
        $jml        = $this->input->post('jml', TRUE);
        $jmlx       = $this->input->post('jmlx', TRUE);
        if($iarea=='00') {
            $daer='f';
        }else{
            $daer='t';
        }
        if($ddkb!='' && $iareasj!='' && $idkbkirim!='' && $idkbvia!='' && $jml!='' && $jml!=0){
            $bener = "false";
            $this->db->trans_begin();
            $idkb =$this->mmaster->runningnumber($iarea, $thbl);
            $this->mmaster->insertheader($idkb, $ddkb, $iareasj, $idkbkirim, $idkbvia, $ikendaraan, $esupirname, $vdkb, $idkbold);
            for($i=1;$i<=$jml;$i++){              
                $isj          = $this->input->post('isj'.$i, TRUE);
                $dsj          = $this->input->post('dsj'.$i, TRUE);
                $vjumlah      = $this->input->post('vsjnetto'.$i, TRUE);
                $vjumlah      = str_replace(',','',$vjumlah);
                $eremark      = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertdetail($idkb,$iareasj,$isj,$ddkb,$dsj,$vjumlah,$eremark,$i);
                $this->mmaster->updatesj($idkb,$isj,$iareasj,$ddkb);
            }
            if($jmlx>0 && $idkbvia!=2){
                for($i=1;$i<=$jmlx;$i++){
                    $iekspedisi = $this->input->post('iekspedisi'.$i, TRUE);
                    $eremark    = $this->input->post('eremarkx'.$i, TRUE);
                    $this->mmaster->insertdetailekspedisi($idkb,$iareasj,$iekspedisi,$ddkb,$eremark,$i);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input DKB Area '.$iareasj.' No:'.$idkb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idkb
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
