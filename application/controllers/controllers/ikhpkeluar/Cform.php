<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10519';

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
            'ikhpkeluar'   => ''
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function dataarea(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $data = $this->db->query("select * from tr_area
                                where (upper(i_area) like '%$cari%' 
                                or upper(e_area_name) like '%$cari%' 
                                and i_area in ( select i_area from public.tm_user_area where username='$username') )",false);
            foreach($data->result() as  $area){
                    $filter[] = array(
                    'id' => $area->i_area,  
                    'text' => $area->i_area.'-'.$area->e_area_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datauraian(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select * from tr_ikhp_type
                                    where upper(e_ikhp_typename) like '%$cari%'",false);
            foreach($query->result() as  $ikhp){
                    $filter[] = array(
                    'id' => $ikhp->i_ikhp_type,  
                    'text' => $ikhp->i_ikhp_type.'-'.$ikhp->e_ikhp_typename
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getcoa(){
        header("Content-Type: application/json", true);
        $iikhp = $this->input->post('i_ikhp_type');
        $data=$this->db->query("select i_ikhp_type, e_ikhp_typename, i_coa, e_coa_name from tr_ikhp_type where i_ikhp_type='$iikhp'",false);
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
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
        if($ibukti==''){
            $ibukti=null;
        } 
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
        if(($dbukti != '') && ($eikhptypename != '') && ($iarea != '') && ( ($vterimatunai!='') || ($vterimagiro!='') || ($vkeluartunai!='') || ($vkeluargiro!='') )){
                $this->db->trans_begin();
                $this->db->select(" i_ikhp from tm_ikhp where i_bukti='$ibukti' and i_area='$iarea'");
		        $query = $this->db->get();
		        if ($query->num_rows() >0){
                    $nomor='Nomor bukti sudah ada !!!!!';
                }else{
  				    $this->mmaster->insert($iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro);
  				    $nomor=$dtmp." - ".$eikhptypename;
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Input IKHP Pengeluaran No Bukti :'.$this->global['title'].' Kode : '.$ibukti.' Area: '.$iarea);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => 'Input IKHP Pengeluaran No Bukti '.$ibukti
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
