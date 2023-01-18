<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107012001';
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
        //$idcompany  = $this->session->userdata('id_company');
        //$username   = $this->session->userdata('username');
        //$iperiode   = $this->mmaster->cekperiode();
        //$status     = $this->mmaster->cekstatus($idcompany,$username);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder']);
    }

    public function edit(){
        $iikhp          = $this->uri->segment(4);
        $iikhpkeluar    = $this->uri->segment(4);
        $iarea          = $this->uri->segment(5);
        $dfrom          = $this->uri->segment(6);
        $dto            = $this->uri->segment(7);
        $idcompany      = $this->session->userdata('id_company');
        $username       = $this->session->userdata('username');
        $periode        = $this->mmaster->cekperiode();
    
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    =>'List '.$this->global['title'],
            'iikhp'         => $iikhp,
            'iikhpkeluar'   => $iikhpkeluar,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $iarea,
            'periode'       => $periode,
            'area'          => $this->mmaster->bacaarea($idcompany),
            'iikhptype'     => $this->mmaster->bacaikhptype(),
            'isi'           => $this->mmaster->baca($iikhpkeluar)
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iikhp	 			= $this->input->post('iikhp', TRUE);
		$iarea	 			= $this->input->post('iarea', TRUE);
		$dbukti 			= $this->input->post('dbukti', TRUE);
		$dtmp					= $dbukti;
		if($dbukti!=''){
			$tmp=explode("-",$dbukti);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dbukti=$th."-".$bl."-".$hr;
		}
		$ibukti				= $this->input->post('ibukti', TRUE);
		if($ibukti=='') $ibukti=null;
		$eikhptypename= $this->input->post('eikhptypename', TRUE);
		$icoa					= $this->input->post('icoa', TRUE);
		$ecoaname 		= $this->input->post('ecoaname', TRUE);
		$iikhptype		= $this->input->post('iikhptype', TRUE);
		$vterimatunai = $this->input->post('vterimatunai', TRUE);
		if($vterimatunai=='') $vterimatunai=0;
		$vterimatunai		= str_replace(',','',$vterimatunai);
		$vterimagiro	= $this->input->post('vterimagiro', TRUE);
		if($vterimagiro=='') $vterimagiro=0;
		$vterimagiro		= str_replace(',','',$vterimagiro);
		$vkeluartunai = $this->input->post('vkeluartunai', TRUE);
		if($vkeluartunai=='') $vkeluartunai=0;
		$vkeluartunai		= str_replace(',','',$vkeluartunai);
		$vkeluargiro	= $this->input->post('vkeluargiro', TRUE);
		if($vkeluargiro=='') $vkeluargiro=0;
		$vkeluargiro		= str_replace(',','',$vkeluargiro);
        if (($dbukti != '') && ($eikhptypename != '') && ($iarea != '') &&
        ( ($vterimatunai!='') || ($vterimagiro!='') || ($vkeluartunai!='') || ($vkeluargiro!='') )){
            $this->db->trans_begin();
            $this->mmaster->update($iikhp,$iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro);
			$nomor=$dtmp." - ".$eikhptypename;
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update IKHP Pengeluaran Area:'.$iarea.' No:'.$iikhp);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iikhp
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
