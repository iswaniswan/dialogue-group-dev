<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107011701';
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
        //$iperiode   = $this->mmaster->cekperiode();
        $status     = $this->mmaster->cekstatus($idcompany,$username);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $status, $this->global['folder']);
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

    public function edit(){
        $ikn        = $this->uri->segment(4);
        $nknyear    = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $ibbm       = $this->uri->segment(9);
        $icustomer  = $this->uri->segment(10);
        $idcompany = $this->session->userdata('id_company');
    
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'ikn'        => $ikn,
            'iarea'      => $iarea,
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'nknyear'    => $nknyear,
            'ibbm'       => $ibbm,
            'icustomer'  => $icustomer,
            'area'     => $this->mmaster->bacaarea($idcompany),
            'refference' => $this->mmaster->bacabbm($iarea),
            'pajak'      => $this->mmaster->bacapajak($icustomer),
            'isi'        => $this->mmaster->bacakn($ikn,$nknyear,$iarea)->row(),
            'detail'     => $this->mmaster->bacabbmdetail($ibbm),
            'jml'        => $this->mmaster->jmldetail($ibbm)
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
        $iarea 			= $this->input->post('iarea', TRUE);
		$ikn 			= $this->input->post('ikn', TRUE);
		$icustomer		= $this->input->post('icustomer', TRUE);
		$irefference	= $this->input->post('irefference', TRUE);
		$icustomergroupar= $this->input->post('icustomergroupar', TRUE);
		$isalesman		= $this->input->post('isalesman', TRUE);
		$ikntype		= '01';
		$drefference	= $this->input->post('drefference', TRUE);
		if($drefference!=''){
			$tmp=explode("-",$drefference);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$drefference=$th."-".$bl."-".$hr;
		}
		$dkn			= $this->input->post('dkn', TRUE);
		if($dkn!=''){
			$tmp=explode("-",$dkn);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dkn=$th."-".$bl."-".$hr;
			$nknyear=$th;
		}
		$ipajak 		= $this->input->post('ipajak', TRUE);
		$dpajak			= $this->input->post('dpajak', TRUE);
		if($dpajak!=''){
			$tmp=explode("-",$dpajak);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dpajak=$th."-".$bl."-".$hr;
        }
        $fcetak		= 'f';
		$fmasalah	= $this->input->post('fmasalah', TRUE);
		if($fmasalah=='')
			$fmasalah='f';
		else
			$fmasalah='t';
		$finsentif	= $this->input->post('finsentif', TRUE);
		if($finsentif=='')
			$finsentif='f';
		else
			$finsentif='t';
		$vnetto		= $this->input->post('vnetto', TRUE);
		$vnetto		= str_replace(',','',$vnetto);
		$vsisa		= $vnetto;
		$vgross		= $this->input->post('vgross', TRUE);
		$vgross		= str_replace(',','',$vgross);
		$vdiscount	= $this->input->post('vdiscount', TRUE);
		$vdiscount	= str_replace(',','',$vdiscount);
		$eremark	= $this->input->post('eremark', TRUE);
        if ((isset($irefference) && $irefference != '') && (isset($dkn) && $dkn != '') && (isset($iarea) && $iarea != '') && (isset($icustomer) && $icustomer != '') && ($finsentif != 'f')){
            $this->db->trans_begin();
            $this->mmaster->update(	$iarea,$ikn,$icustomer,$irefference,$icustomergroupar,$isalesman,$ikntype,$dkn,$nknyear,$fcetak,$fmasalah,
									$finsentif,$vnetto,$vsisa,$vgross,$vdiscount,$eremark,$drefference,$ipajak,$dpajak);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update KN Retur:'.$iarea.' No:'.$ikn);
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

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikn       = $this->input->post('ikn', TRUE);
        $nknyear   = $this->input->post('nknyear', TRUE);
        $iarea     = $this->input->post('iarea', TRUE);
        $dfrom     = $this->input->post('dfrom', TRUE);
        $dto       = $this->input->post('dto', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ikn, $nknyear, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel KN Retur Area '.$iarea.' No:'.$ikn);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */
