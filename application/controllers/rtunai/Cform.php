<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10507';

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
        $iarea     = $this->mmaster->cekuser($username,$idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea($iarea, $username, $idcompany),
            'bank'      => $this->mmaster->bacabank()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function tunai(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '' && $this->input->get('drtunai') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea');
            $drtunai    = $this->input->get('drtunai');
            $tmp        = explode("-",$drtunai);
            $dd         = $tmp[0];
            $mm         = $tmp[1];
            $yy         = $tmp[2];
            $drtunaix   = $yy.'-'.$mm.'-'.$dd;
            $data       = $this->mmaster->tunai($cari,$iarea,$drtunaix);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_tunai,  
                    'text'  => $kuy->i_tunai
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailtunai(){
        header("Content-Type: application/json", true);
        $itunai     = $this->input->post('itunai');
        $iarea      = $this->input->post('iarea');
        $drtunai    = $this->input->post('drtunai');
        $tmp        = explode("-",$drtunai);
        $dd         = $tmp[0];
        $mm         = $tmp[1];
        $yy         = $tmp[2];
        $drtunaix   = $yy.'-'.$mm.'-'.$dd;
        $data = $this->mmaster->getdetailtunai($itunai,$iarea,$drtunaix);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $drtunai    = $this->input->post('drtunai', TRUE);
        if($drtunai!=''){
            $tmp=explode("-",$drtunai);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $drtunai=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
            $tahun=$th;
        }
        $iarea              = $this->input->post('iarea', TRUE);
        $ibank              = $this->input->post('ibank', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $vjumlah            = $this->input->post('vjumlah', TRUE);
        $vjumlah            = str_replace(',','',$vjumlah);
        $jml                = $this->input->post('jml', TRUE);
        $jml                = str_replace(',','',$jml);
        if (($drtunai != '') && ($iarea!='') && ($vjumlah!='') && ($vjumlah!='0') && ($ibank!='')){
            $this->db->trans_begin();
            $irtunai = $this->mmaster->runningnumber($iarea,$thbl);
            $this->mmaster->insert($irtunai,$drtunai,$iarea,$eremark,$vjumlah,$ibank);
            for($i=1;$i<=$jml;$i++){
                $itunai       = $this->input->post('itunai'.$i, TRUE);
                $iareatunai   = $this->input->post('iarea'.$i, TRUE);
                $vjumlah      = $this->input->post('vjumlah'.$i, TRUE);
                $vjumlah      = str_replace(',','',$vjumlah);
                $this->mmaster->insertdetail($irtunai,$iarea,$itunai,$iareatunai,$vjumlah,$i);
                $this->mmaster->updatetunai($irtunai,$iarea,$itunai,$iareatunai,$vjumlah);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Setor Tunai Area '.$iarea.' No:'.$irtunai);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $irtunai
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
