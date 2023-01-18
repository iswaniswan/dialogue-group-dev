<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1060108';

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
        $area = $this->uri->segment('6');
        
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
        echo $this->mmaster->data($from,$to, $area);
    }

    function dataarea(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $this->db->select(" * from tr_area where (upper(i_area) like '%$cari%' or upper(e_area_name) like '%$cari%') and (i_area in ( select i_area from tm_user_area where i_user='$username') )",false);
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
    	$dfrom = $this->input->post('dfrom');
        $dto   = $this->input->post('dto');
        $area  = $this->input->post('iarea');
        $query = $this->mmaster->bacaperiode($dfrom,$dto,$area);
        foreach($query->result() as  $row){
            if(!empty($row->i_cek)) {
                if(!empty($row->d_cek)) {
                    $tmpck=explode('-',$row->d_cek);	
                    $tglck=$tmpck[2];
                    $blnck=$tmpck[1];
                    $thnck=$tmpck[0];
                    $statusperiksa = (@$tmpck[2]!='' && !empty($row->d_cek) )?($tglck.'-'.$blnck.'-'.$thnck):('System');
                } else {
                    $statusperiksa = 'System';
                }
            } else {
                    $statusperiksa = 'Belum';
            }
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'area' => $area
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $dfrom		= $this->input->post('dfrom');
	    $dto		  = $this->input->post('dto');
	    $iarea		= $this->input->post('iarea');
        if($dfrom=='') 
            $dfrom=$this->uri->segment(4);
        if($dto=='') 
            $dto=$this->uri->segment(5);
        if($iarea=='') 
            $iarea	= $this->uri->segment(6);
        $user	= $this->session->userdata('username');
        
        $this->mmaster->update($iarea,$dfrom,$dto,$user);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data Cek Pelaporan Daerah (Kas Kecil) Dari Tanggal '.$dfrom.' Sampai '.$dto);
            $data = array(
                'sukses'    => true,
                'kode'      => ''
            );
        }
        $this->load->view('pesan', $data);
    }  
}

/* End of file Cform.php */
