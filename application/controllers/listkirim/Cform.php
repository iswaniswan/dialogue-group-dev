<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107030501';

    public function __construct(){
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Info ".$this->global['title'],
            'area'          => $this->mmaster->bacaarea($username,$idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        } 
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder']);
    }
    
    public function view(){
    	$iarea  = $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $iarea
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if($this->uri->segment(4)!=''){
            $isjrt	= $this->uri->segment(4);
			$iarea= $this->uri->segment(5);
			$dfrom= $this->uri->segment(6);
			$dto 	= $this->uri->segment(7);
			$ittb= $this->uri->segment(8);
            $query 	= $this->db->query("select * from tm_sjrt_item where i_sjr = '$isjrt' and i_area='$iarea'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'isjrt'          => $isjrt,
                'ittb'           => $ittb,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'iarea'          => $iarea,
                'isi'            => $this->mmaster->baca($isjrt,$iarea),
                'cquery'         => $this->mmaster->bacadetailspb($isjrt,$ittb,$iarea)->result(),
                'detail'         => $this->mmaster->bacadetail($isjrt,$iarea,$ittb)
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
    }

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isjr	= $this->input->post('isjr', TRUE);
		$dsjr 	= $this->input->post('dsjr', TRUE);
		if($dsjr!=''){
			$tmp=explode("-",$dsjr);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dsjr=$th."-".$bl."-".$hr;
			$thbl	= substr($th,2,2).$bl;
			$tmpsj	= explode("-",$isjr);
			$firstsj= $tmpsj[0];
			$lastsj	= $tmpsj[2];
			$newsj	= $firstsj."-".$thbl."-".$lastsj;				
		}
		$iarea		= $this->input->post('iarea', TRUE); // area_to
		$ittb		= $this->input->post('ittb', TRUE);
		$dttb	 	= $this->input->post('dttb', TRUE);
		if($dttb!=''){
			$tmp=explode("-",$dttb);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dttb=$th."-".$bl."-".$hr;
		}
		$eareaname	= $this->input->post('eareaname', TRUE);
		$vspbnetto= $this->input->post('vsj', TRUE);
		$vspbnetto= str_replace(',','',$vspbnetto);
		$jml	  = $this->input->post('jml', TRUE);
		$gaono=true;
		for($i=1;$i<=$jml;$i++){
			$cek=$this->input->post('chk'.$i, TRUE);
			if($cek=='on'){
				$gaono=false;
			}
			if(!$gaono) break;
		}
        if(!$gaono){
            $this->db->trans_begin();
            $this->mmaster->updatesjheader($isjr,$iarea,$dsjr,$vspbnetto);
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
                $iproduct		= $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade	= 'A';
                $iproductmotif	= $this->input->post('motif'.$i, TRUE);
                $ndeliver		= $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver		= str_replace(',','',$ndeliver);
                $this->mmaster->deletesjdetail($isjr, $iarea, $iproduct, $iproductgrade, $iproductmotif);
                if($cek=='on'){
                  $eproductname	= $this->input->post('eproductname'.$i, TRUE);
                  $vunitprice		= $this->input->post('vproductmill'.$i, TRUE);
                  $vunitprice		= str_replace(',','',$vunitprice);
                  $eremark  		= $this->input->post('eremark'.$i, TRUE);
                  if($eremark=='')
                    $eremark=null;
                  if($ndeliver>0){
                      $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,
                                         $vunitprice,$ittb,$dttb,$isjr,$dsjr,$iarea,$eremark,$i);                      
                  }
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SJR Toko :'.$this->global['title'].' Kode : '.$isjr);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update SJPB Receive '.$isjr
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
        $isjr = $this->input->post('isjr', TRUE);
        $iarea   = $this->input->post('iarea', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isjr, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel Tunai Item Area '.$iarea.' No:'.$isjr);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
