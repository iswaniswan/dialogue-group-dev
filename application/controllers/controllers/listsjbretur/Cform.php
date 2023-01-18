<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107030211';

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

    public function product(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $isjbr   = $this->uri->segment(4);
            $iarea   = $this->uri->segment(5);
            $data    = $this->mmaster->product($isjbr,$iarea,$cari);
            foreach($data->result() as  $product){
                $filter[] = array(
                    'id'    => $product->kode,  
                    'text'  => $product->kode.' - '.$product->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function detailproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct', FALSE);
        $data     = $this->mmaster->detailproduct($iproduct);
        echo json_encode($data->result_array());  
    } 

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if($this->uri->segment(4)!=''){
            $isjbr	= $this->uri->segment(4);
			$iarea  = $this->uri->segment(5);
			$dfrom  = $this->uri->segment(6);
			$dto 	= $this->uri->segment(7);
            $query 	= $this->db->query("select * from tm_sjbr_item where i_sjbr = '$isjbr' and i_area='$iarea'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'isjbr'          => $isjbr,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'iarea'          => $iarea,
                'isi'            => $this->mmaster->baca($isjbr,$iarea),
                'detail'         => $this->mmaster->bacadetail($isjbr,$iarea)
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
        $isj	= $this->input->post('isjbr', TRUE);
		$dsj 	= $this->input->post('dsjbr', TRUE);
		if($dsj!=''){
			$tmp=explode("-",$dsj);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dsj=$th."-".$bl."-".$hr;
			$thbl	= substr($th,2,2).$bl;
			$tmpsj	= explode("-",$isj);
			$firstsj= $tmpsj[0];
			$lastsj	= $tmpsj[2];
			$newsj	= $firstsj."-".$thbl."-".$lastsj;				
		}
		$iarea		= $this->input->post('iarea', TRUE);
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
            $this->load->model('sjbretur/mmaster');
            $istore	  			= $this->input->post('istore', TRUE);
            if($istore=='AA'){
                $istorelocation		= '01';
            }elseif($istore=='PB'){
                $istorelocation		= '00';
            }else{
                $istorelocation		= 'PB';		
            }
            $istorelocationbin	= '00';
#				$Qseachsjdaer	= $this->mmaster->searchsjheader($isj,$iarea);
#				$nserachsjdaer	= $Qseachsjdaer->num_rows();
            $this->mmaster->updatesjheader($isj,$iarea,$dsj,$vspbnetto);
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
                $iproduct		= $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade	= 'A';
                $eproductname	= $this->input->post('eproductname'.$i, TRUE);
                $iproductmotif	= $this->input->post('motif'.$i, TRUE);
                $nretur		    = $this->input->post('nretur'.$i, TRUE);
                $nretur		    = str_replace(',','',$nretur);
                $nreceive	    = $this->input->post('nreceive'.$i, TRUE);
                $nreceive	    = str_replace(',','',$nreceive);
                $nasal  	    = $this->input->post('nasal'.$i, TRUE);
                $nasal  	    = str_replace(',','',$nasal);

                $this->mmaster->deletesjdetail( $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif);

                $th=substr($dsj,0,4);
                $bl=substr($dsj,5,2);
                $emutasiperiode=$th.$bl;
                $tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$istore,$istorelocation,$istorelocationbin,$isj,$nretur,$eproductname);
                $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nasal,$emutasiperiode);
                $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nasal);
                if($cek=='on'){
                  $eproductname	= $this->input->post('eproductname'.$i, TRUE);
                  $vunitprice		= $this->input->post('vproductmill'.$i, TRUE);
                  $vunitprice		= str_replace(',','',$vunitprice);
                  $nreceive		 	= $this->input->post('nreceive'.$i, TRUE);
                  $nreceive		  = str_replace(',','',$nreceive);
                  $nretur		  	= $this->input->post('nretur'.$i, TRUE);
                  $nretur			  = str_replace(',','',$nretur);
                  $eremark  		= $this->input->post('eremark'.$i, TRUE);
                  if($eremark=='')$eremark=null;
                  if($nretur>0){
                      $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,
                                         $vunitprice,$isj,$dsj,$iarea,$istore,$istorelocation,
                                         $istorelocationbin,$eremark,$i);                      
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                      foreach($trans as $itrans)
                      {
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
                   // $this->mmaster->inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$nretur,$q_aw,$q_ak,$tra);
                    $th=substr($dsj,0,4);
                    $bl=substr($dsj,5,2);
                    $emutasiperiode=$th.$bl;
                    $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode);
                    if($ada=='ada'){
                        $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nretur,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nretur,$emutasiperiode,$q_aw,$q_ak);
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                        $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nretur,$q_ak);
                    }else{
                        $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nretur);
                    }
                  }
                }
            }			
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SJR Toko :'.$this->global['title'].' Kode : '.$isjbr);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update SJPB Receive '.$isjbr
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
        $isjbr = $this->input->post('isjbr', TRUE);
        $iarea   = $this->input->post('iarea', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isjbr, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel Tunai Item Area '.$iarea.' No:'.$isjbr);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
