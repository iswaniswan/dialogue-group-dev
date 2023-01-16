<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050403';

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
            'title'     => $this->global['title'],
            'dfrom'     => '',
            'dto'       => '',
            'ipl'       => '',
            'idt'       => '',
            'iarea'     => '',
            'status'    => 'awal',
            'isi'       => '',
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
    	$dfrom = $this->uri->segment('4');
        $dto = $this->uri->segment('5');
        $iarea = $this->uri->segment('6');
        
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
            
    	echo $this->mmaster->data($from,$to, $iarea, $this->i_menu);
    }

    function dataarea(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $this->db->select(" i_area, e_area_name from tr_area where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%')
                                and (i_area in ( select i_area from public.tm_user_area where username='$username') ) order by i_area ",false);
            $data = $this->db->get();
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
    
    public function view(){
    	$dfrom		= $this->input->post('dfrom');
	    $dto		= $this->input->post('dto');
        $iarea		= $this->input->post('iarea');
        
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        } 
		if($dto==''){
            $dto=$this->uri->segment(5);
        } 
		if($iarea==''){
            $iarea	= $this->uri->segment(6);
        } 

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'iarea' => $iarea
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikum = $this->uri->segment(4);
	    $iarea= $this->uri->segment(5);
	    $dfrom= $this->uri->segment(6);
	    $dto 	= $this->uri->segment(7);
	    $tmp=explode("-",$dfrom);
		$ikum = str_replace("spasi"," ",$ikum);
		$th=$tmp[0];
		$bl=$tmp[1];
		$hr=$tmp[2];
		$y_kum=$th;
        $query  = $this->db->query("select * from tm_kum 
                                    where i_kum = '$ikum' 
                                    and i_area = '$iarea' 
                                    and d_kum >= to_date('$dfrom','yyyy-mm-dd') 
                                    and d_kum <= to_date('$dto','yyyy-mm-dd')
                                    and f_kum_cancel='f' 
                                    and n_kum_year='$y_kum'");
        $data   = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'jmlitem'       => $query->num_rows(),
            'ikum'          => $ikum,
            'iarea'         => $iarea,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'row'           => $this->mmaster->bacakum($iarea,$ikum,$y_kum)->row(),
        );   

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $ikum 	= $this->input->post('ikum', TRUE);
		$dkum 	= $this->input->post('dkum', TRUE);
		$iarea	= $this->input->post('iarea', TRUE);
		$ecek	= $this->input->post('ecek',TRUE);
		if($ecek=='')
			$ecek=null;
		$user		=$this->session->userdata('username');
		$this->mmaster->updatecekku($ecek,$user,$ikum,$dkum,$iarea);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cek KU Masuk'.$this->global['title'].' No : '.$ikum.' Area : '.$iarea);
            
            $data = array(
                'sukses'    => true,
                'kode'      => 'Cek KU Masuk No: '.$ikum
            );
        }
        $this->load->view('pesan', $data);
    }  
}

/* End of file Cform.php */
