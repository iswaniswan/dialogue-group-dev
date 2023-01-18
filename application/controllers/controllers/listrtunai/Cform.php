<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070116';
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
            'iarea'     => $this->mmaster->bacaarea($idcompany)
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
        $idcompany  = $this->session->userdata('id_company');
        $username   = $this->session->userdata('username');
        $iperiode   = $this->mmaster->cekperiode();
        $area       = $this->mmaster->area($idcompany,$username);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $iperiode, $area, $this->global['folder']);
    }

    public function edit(){
        $irtunai   = $this->uri->segment(4);
        $iarea     = $this->uri->segment(5);
        $dfrom     = $this->uri->segment(6);
        $dto       = $this->uri->segment(7);
        $idcompany = $this->session->userdata('id_company');
        $query     = $this->mmaster->baca($iarea,$irtunai);
        $drtunai    = substr($query->d_rtunai,0,4).substr($query->d_rtunai,5,2);
        $query1    = $this->db->query("select i_periode from tm_periode",false);
        if($query1->num_rows() > 0){
            foreach($query1->result() as $row){
                $iperiode=$row->i_periode;
            }
        }
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'irtunai'    => $irtunai,
            'area'       => $this->mmaster->bacaarea($idcompany),
            'iarea'      => $iarea,
            'icustomer'  => $this->mmaster->bacabank(),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'iperiode'   => $iperiode,
            'isi'        => $this->mmaster->baca($iarea,$irtunai),
            'detail'     => $this->mmaster->bacadetail($iarea,$irtunai)
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function gettunai(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $drtunai     = $this->input->get('drtunai', FALSE);
            $tmp=explode("-",$drtunai);
            $dd=$tmp[0];
            $mm=$tmp[1];
            $yy=$tmp[2];
            $drtunaix=$yy.'-'.$mm.'-'.$dd;
            $data       = $this->mmaster->gettunai($cari,$drtunaix,$iarea);
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
        $data  = $this->mmaster->getdetailtunai($itunai,$iarea);
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $irtunai  = $this->input->post('irtunai', TRUE);
		$drtunai  = $this->input->post('drtunai', TRUE);
		if($drtunai!=''){
			$tmp=explode("-",$drtunai);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$drtunai=$th."-".$bl."-".$hr;
			$thbl=$th.$bl;
			$tahun=$th;
		}
        $iarea       = $this->input->post('iarea', TRUE);
        $ibank       = $this->input->post('ibank', TRUE);
        $xiarea      = $this->input->post('xiarea', TRUE);
		$eremark     = $this->input->post('eremark', TRUE);
        $vjumlah     = $this->input->post('vjumlah', TRUE);
        $vjumlah     = str_replace(',','',$vjumlah);
        $jml         = $this->input->post('jml', TRUE);
        
        if (($drtunai != '') && ($iarea!='') && (($vjumlah!='') || ($vjumlah != 0)) && $ibank!=''){
            $this->db->trans_begin();
            $this->mmaster->update($irtunai,$drtunai,$iarea,$xiarea,$eremark,$vjumlah,$ibank);
            $this->mmaster->deletedetail($irtunai,$iarea);
			for($i=1;$i<=$jml;$i++){
                $itunai			    = $this->input->post('itunai'.$i, TRUE);
                var_dump($itunai);
                $iareatunai	        = $this->input->post('iarea'.$i, TRUE);
                var_dump($iareatunai);
				$vjumlah		    = $this->input->post('vjumlahx'.$i, TRUE);
                $vjumlah		    = str_replace(',','',$vjumlah);
                var_dump($vjumlah);
				$vjumlahasallagi	= $this->input->post('vjumlahasallagi'.$i, TRUE);
                $vjumlahasallagi	= str_replace(',','',$vjumlahasallagi);
                if($itunai<>''){
                    $this->mmaster->updatedetail($irtunai,$iarea,$xiarea,$itunai,$iareatunai,$vjumlah,$i);
                    $this->mmaster->updatetunai($irtunai,$iarea,$itunai,$iareatunai,$vjumlah);
                }
            }		
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Setor Tunai Area:'.$iarea.' No:'.$irtunai);
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

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $irtunai = $this->input->post('irtunai', TRUE);
        $iarea   = $this->input->post('iarea', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($irtunai, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel Tunai Item Area '.$iarea.' No:'.$irtunai);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */
