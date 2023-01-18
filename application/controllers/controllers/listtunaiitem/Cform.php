<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070115';
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
        $itunai   = $this->uri->segment(4);
        $iarea    = $this->uri->segment(5);
        $dfrom    = $this->uri->segment(6);
        $dto      = $this->uri->segment(7);
        $idcompany = $this->session->userdata('id_company');
        $query    = $this->mmaster->baca($iarea,$itunai);
        $dtunai   = substr($query->d_tunai,0,4).substr($query->d_tunai,5,2);
        $query1   = $this->db->query("select i_periode from tm_periode",false);
        if($query1->num_rows() > 0){
            foreach($query1->result() as $row){
                $iperiode=$row->i_periode;
            }
        }
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'itunai'     => $itunai,
            'area'       => $this->mmaster->bacaarea($idcompany),
            'iarea'      => $iarea,
            'icustomer'  => $this->mmaster->bacacustomer($iarea,$iperiode),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bisaedit'   => false,
            'iperiode'   => $iperiode,
            'dtunai'     => $dtunai,
            'pst'        => $this->session->userdata('i_area'),
            'isi'        => $this->mmaster->baca($iarea,$itunai),
            'detail'     => $this->mmaster->bacadetail($iarea,$itunai)
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function getnota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='' && $this->input->get('icustomer')) {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $icustomer  = $this->input->get('icustomer', FALSE);
            $dtunai     = $this->input->get('dtunai', FALSE);
            $tmp=explode("-",$dtunai);
            $dd=$tmp[0];
            $mm=$tmp[1];
            $yy=$tmp[2];
            $dtunaix=$yy.'-'.$mm.'-'.$dd;
            $data       = $this->mmaster->getnota($cari,$dtunaix,$icustomer,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailnota(){
        header("Content-Type: application/json", true);
        $inota      = $this->input->post('inota');
        $iarea      = $this->input->post('iarea');
        $data  = $this->mmaster->getdetailnota($inota,$iarea);
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $itunai  = $this->input->post('itunai', TRUE);
		$dtunai	= $this->input->post('dtunai', TRUE);
		if($dtunai!=''){
			$tmp=explode("-",$dtunai);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dtunai=$th."-".$bl."-".$hr;
			$thbl=$th.$bl;
			$tahun=$th;
		}
		$iarea       = $this->input->post('iarea', TRUE);
		$eremark     = $this->input->post('eremark', TRUE);
        $vjumlah     = $this->input->post('vjumlah', TRUE);
        $vjumlah     = str_replace(',','',$vjumlah);
		$jml         = $this->input->post('jml', TRUE);
		$lebihbayar  = $this->input->post('lebihbayar',TRUE);
         if($lebihbayar!=''){
            $lebihbayar= 't';
         }else{
            $lebihbayar= 'f';
         }
        if (($itunai != '') && ($dtunai != '') && ($iarea!='') && (($vjumlah!='') || ($vjumlah != 0))){
            $this->db->trans_begin();
            $this->mmaster->update($itunai,$dtunai,$iarea,$eremark,$vjumlah,$lebihbayar);
            $this->mmaster->deletedetail($itunai,$iarea);
			for($i=1;$i<=$jml;$i++){
				$iarea		= $this->input->post('iarea'.$i, TRUE);
				$inota      = $this->input->post('inota'.$i, TRUE);
				$vjumlah	= $this->input->post('vsisa'.$i, TRUE);
                $vjumlah	= str_replace(',','',$vjumlah);
				if($inota<>''){
  				    $this->mmaster->insertdetail($itunai,$iarea,$inota,$vjumlah,$i);
                }
            }		
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Tunai Area:'.$iarea.' No:'.$itunai);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $itunai
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
        $itunai  = $this->input->post('itunai', TRUE);
        $iarea   = $this->input->post('iarea', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($itunai, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel Tunai Item Area '.$iarea.' No:'.$itunai);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */
