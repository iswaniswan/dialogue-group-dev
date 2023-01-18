<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10801';

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
            
    	echo $this->mmaster->data($from,$to, $this->i_menu);
    }
    
    public function view(){
    	$dfrom = $this->input->post('dfrom');
    	$dto   = $this->input->post('dto');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dto    = $this->uri->segment(8);
		$dfrom  = $this->uri->segment(7);
		$area   = $this->uri->segment(6);
		$ispb   = $this->uri->segment(5);
        $inota  = $this->uri->segment(4);
        $query  = $this->db->query("select * from tm_nota_item where i_nota = '$inota' and i_area='$area'");
        $data   = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'jmlitem'       => $query->num_rows(),
            'ispb'          => $ispb,
            'inota'         => $inota,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area,
            'isi'           => $this->mmaster->bacanota($inota, $ispb, $area),
            'detail'        => $this->mmaster->bacadetailnota($inota,$area)
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
        $inota 		= $this->input->post('inota', TRUE);
		$iarea 		= $this->input->post('iarea', TRUE);
        $dnota 		= $this->input->post('dnota', TRUE);
        if($dnota!=''){
            $tmp=explode("-",$dnota);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dnota=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
            $tbl=substr($th,2,2).$bl;
        }
        $tmp="FK-".$tbl."-";
        $ifakturkomersial	= "FK-".$tbl."-".$this->input->post('ifakturkomersial', TRUE);
        
        if(strlen($ifakturkomersial)==14){
            $this->mmaster->updatenota($inota,$iarea,$ifakturkomersial);
        }

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
