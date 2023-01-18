<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020207';

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

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isjr = $this->uri->segment('4');
        $iarea = $this->uri->segment('5');

        $query 	= $this->db->query("select * from tm_sjr_item where i_sjr = '$isjr' and i_area='$iarea'");

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'jmlitem'       => $query->num_rows(),
            'isi'           => $this->mmaster->baca($isjr,$iarea),
            'detail'          => $this->mmaster->bacadetail($isjr,$iarea)
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isj	      = $this->input->post('isj', TRUE);
        $dsjreceive = $this->input->post('dreceive', TRUE);
        if($dsjreceive!=''){
            $tmp=explode("-",$dsjreceive);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjreceive=$th."-".$bl."-".$hr;
            $thbl	  = substr($th,2,2).$bl;
            $tmpsj	= explode("-",$isj);
            $firstsj= $tmpsj[0];
            $lastsj	= $tmpsj[2];
            $newsj	= $firstsj."-".$thbl."-".$lastsj;				
        }
        $dsj = $this->input->post('dsj', TRUE);
        if($dsj!=''){
	    	$tmp=explode("-",$dsj);
	    	$th=$tmp[2];
	    	$bl=$tmp[1];
	    	$hr=$tmp[0];
	    	$dsj=$th."-".$bl."-".$hr;
        }
        $iarea		= $this->input->post('iarea', TRUE);
		$vspbnetto= $this->input->post('vsj', TRUE);
		$vspbnetto= str_replace(',','',$vspbnetto);
		$vsjrec   = $this->input->post('vsjrec', TRUE);
		$vsjrec   = str_replace(',','',$vsjrec);
		$jml	    = $this->input->post('jml', TRUE);
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
            $istore	  			= $this->input->post('istore', TRUE);
			if($istore=='AA'){
				$istorelocation	= '01';
			}else{
				$istorelocation	= '00';
			}
			$istorelocationbin	= '00';
            $this->mmaster->updatesjheader($isj,$iarea,$dsjreceive,$vspbnetto,$vsjrec);
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
				$iproduct		= $this->input->post('iproduct'.$i, TRUE);
				$iproductgrade	= 'A';
				$iproductmotif	= $this->input->post('motif'.$i, TRUE);
				$nretur 		= $this->input->post('nretur'.$i, TRUE);
				$nretur 		= str_replace(',','',$nretur);
				$nreceive		= $this->input->post('nreceive'.$i, TRUE);
				$nreceive		= str_replace(',','',$nreceive);
                $ntmp		    = $this->input->post('ntmp'.$i, TRUE);
                $ntmp   		= str_replace(',','',$ntmp);
                if($nretur=='')
                    $nretur=$nreceive;
                $th=substr($dsjreceive,0,4);
                $bl=substr($dsjreceive,5,2);
                $emutasiperiode=$th.$bl;
                $th=substr($dsj,0,4);
                $bl=substr($dsj,5,2);
                $emutasiperiodesj=$th.$bl;
                if($cek=='on'){
                    $eproductname	= $this->input->post('eproductname'.$i, TRUE);
					$vunitprice		= $this->input->post('vproductmill'.$i, TRUE);
					$vunitprice		= str_replace(',','',$vunitprice);
					$eremark  		= $this->input->post('eremark'.$i, TRUE);
                    if($eremark=='')$eremark=null;
                    $this->mmaster->updatesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$dsj,$iarea,$nreceive,$ntmp);
                    if( ($ntmp!='') && ($ntmp!=0) ){
  						  $this->mmaster->deletetrans( $iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname);
					      $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode);
					      $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp);
                    }
                    if($nreceive>0){
                        $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
						if(isset($trans)){
						  foreach($trans as $itrans){
						    $q_aw =$itrans->n_quantity_awal;
						    $q_ak =$itrans->n_quantity_akhir;
						    $q_in =$itrans->n_quantity_in;
						    $q_out=$itrans->n_quantity_out;
						    break;
						  }
						}else{
						    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
						    if(isset($trans)){
						      foreach($trans as $itrans){
						    	    $q_aw =$itrans->n_quantity_stock;
						    	    $q_ak =$itrans->n_quantity_stock;
						    	    $q_in =0;
						    	    $q_out=0;
						    	    break;
						      }
						    }else{
						      $q_aw=0;
						      $q_ak=0;
						      $q_in=0;
						      $q_out=0;
						    }
                        }
                        $this->mmaster->inserttrans1($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$isj,$q_in,$q_out,$nreceive,$q_aw,$q_ak);
						$ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$emutasiperiode);
						if($ada=='ada'){
						  $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode,$emutasiperiodesj);
						}else{
						  $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode,$emutasiperiodesj);
						}
						if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00')){
						  $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$q_ak);
						}else{
						  $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nreceive);
						}
                    }
                }
            }
            $sjnew=0;
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isj);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isj
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
