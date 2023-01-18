<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10808';

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
            
    	echo $this->mmaster->data($from,$to, $area, $this->i_menu);
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $inota    = $this->uri->segment(4);
		$iarea  = $this->uri->segment(5);
		$dfrom   = $this->uri->segment(6);
		$dto   = $this->uri->segment(7);
        $query  = $this->db->query("select i_sj from tm_nota_item where i_sj = '$isj' and i_area='$iarea'");
        $data   = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'jmlitem'       => $query->num_rows(),
            'isj'           => $isj,
            'iarea'         => $iarea,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'dsj'           => $dsj,
            'tgl'           => date('d-m-Y'),
            'isi'           => $this->mmaster->baca($isj,$iarea),
            'detail'        => $this->mmaster->bacadetail($isj,$iarea)
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
        $isj 	        = $this->input->post('isj', TRUE);
		$inota          = $this->input->post('inota', TRUE);
		$iarea			= $this->input->post('iarea', TRUE);
		$eremarkpajak 	= $this->input->post('eremarkpajak', TRUE);
		$dapprovepajak	= $this->input->post('dapprovepajak', TRUE);
        if($dapprovepajak!=''){
            $tmp=explode("-",$dapprovepajak);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dapprovepajak=$th."-".$bl."-".$hr;
        }
        $this->mmaster->updatenota($isj,$iarea,$eremarkpajak,$dapprovepajak);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$inota);
            
            $data = array(
                'sukses'    => true,
                'kode'      => $inota
            );
        }
        $this->load->view('pesan', $data);
    }  
}

/* End of file Cform.php */
