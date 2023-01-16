<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '103083';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
    	$dfrom = $this->uri->segment('4');
        $dto = $this->uri->segment('5');
        $icustomer = $this->uri->segment('6');
        
        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$yy.'-'.$mm.'-'.$dd;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$yy.'-'.$mm.'-'.$dd;
            
    	echo $this->mmaster->data($from,$to, $icustomer, $this->i_menu);
    }

    function datacustomer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $data = $this->db->query(" select a.*, b.e_customer_name, c.e_area_name
                                from tr_spg a, tr_customer b, tr_area c
                                where (c.i_area in ( select i_area from tm_user_area where i_user='$username')
                                and a.i_customer=b.i_customer 
                                and a.i_area=b.i_area 
                                and a.i_area=c.i_area
                                and (upper(a.i_customer) like '%$cari%'
                                or upper(b.e_customer_name) like '%$cari%'))",false);
            foreach($data->result() as  $customer){
                    $filter[] = array(
                    'id' => $customer->i_customer,  
                    'text' => $customer->i_customer.'-'.$customer->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    
    public function view(){
    	$dfrom = $this->input->post('dfrom');
        $dto   = $this->input->post('dto');
        $icustomer  = $this->input->post('icustomer');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'icustomer' => $icustomer
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $inotapb    = str_replace('%20',' ',$this->uri->segment(4));
			$icustomer  = $this->uri->segment(5);
			$dfrom      = $this->uri->segment(6);
			$dto        = $this->uri->segment(7);
            $query      = $this->db->query("select * from tm_notapb_item where i_notapb = '$inotapb' and i_customer='$icustomer'");
            $data       = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'jmlitem'       => $query->num_rows(),
                'inotapb'       => $inotapb,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'icustomer'     => $icustomer,
                'isi'           => $this->mmaster->baca($inotapb,$icustomer)->row(),
                'detail'        => $this->mmaster->bacadetail($inotapb,$icustomer)->result()
            );   
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }  
    }   

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $inotapb 	  = $this->input->post('inotapb', TRUE);
	    $icustomer	= $this->input->post('icustomer',TRUE);
	    $ecek	      = $this->input->post('ecek',TRUE);
	    if($ecek=='')
	    	$ecek=null;
	    $user		=$this->session->userdata('username');
        $dnotapb 	= $this->input->post('dnotapb', TRUE);
		if($dnotapb!=''){
			$tmp=explode("-",$dnotapb);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dnotapb=$th."-".$bl."-".$hr;
		}
		$icustomer		= $this->input->post('icustomer', TRUE);
		$ecustomername	= $this->input->post('ecustomername', TRUE);
		$eareaname		= $this->input->post('eareaname', TRUE);
		$ispg		= $this->input->post('ispg',TRUE);
		$espgname	= $this->input->post('espgname',TRUE);
		$fnotapbcancel		= 'f';
		$nnotapbdiscount	= $this->input->post('nnotapbdiscount',TRUE);
		$vnotapbdiscount	= $this->input->post('vnotapbdiscount',TRUE);
		$vnotapbgross	= $this->input->post('vnotapbgross',TRUE);
		$nnotapbdiscount	= str_replace(',','',$nnotapbdiscount);
		$vnotapbdiscount	= str_replace(',','',$vnotapbdiscount);
		$vnotapbgross	= str_replace(',','',$vnotapbgross);		
        $jml		= $this->input->post('jml', TRUE);
        if(($icustomer!='') && ($inotapb!='')){
            $benar="false";
            $this->mmaster->updateheader($inotapb, $icustomer,$ecek,$user);
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Cek Penjualan Konsinyasi Customer'.$this->global['title'].' Kode : '.$icustomer.' No: '.$inotapb);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Cek Penjualan Konsinyasi Customer : '.$icustomer.' No Nota: '.$inotapb
                );
            }
        }
        $this->load->view('pesan', $data);
    }  
}

/* End of file Cform.php */
