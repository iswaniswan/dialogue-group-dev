<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070410';

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
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function dataarea(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacaarea($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_area,  
                'text'  => $row->e_area_name
            );
        }
        echo json_encode($filter);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        // $areasj = $this->mmaster->bacaareasj($iarea);
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $area   = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        // $areasj = $this->mmaster->bacaareasj($area);
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area
            // 'areasj'        => $areasj
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id     = $this->input->post('id');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($id, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus BAPB SJP Area '.$iarea.' No:'.$id);
            echo json_encode($data);
        }
    }

    public function edit(){
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $id     = $this->uri->segment(4);
			$iarea  = $this->uri->segment(5);
			$dfrom  = $this->uri->segment(6);
			$dto    = $this->uri->segment(7);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($id,$iarea),
                'detail'        => $this->mmaster->bacadetail($id,$iarea)
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getproduct($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->kode,  
                    'text'  => $kuy->kode." - ".$kuy->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $data     = $this->mmaster->getdetailproduct($iproduct);      
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj 	= $this->input->post('isj', TRUE);
        $dsj 	= $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea		= $this->input->post('iarea', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        $ispg       = $this->input->post('ispg', TRUE);
        $vsjpbr     =$this->input->post('vsjpbr', TRUE);
        $vsjpbr     = str_replace(',','',$vsjpbr);
        $jml	    = $this->input->post('jml', TRUE);
        if($dsj!='' && $iarea!=''){
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
                $this->mmaster->updatesjheader($isj,$dsj,$iarea,$vsjpbr,$icustomer,$ispg);
					for($i=1;$i<=$jml;$i++){
                        $cek=$this->input->post('chk'.$i, TRUE);
					    $iproduct			= $this->input->post('iproduct'.$i, TRUE);
					    $iproductgrade      = 'A';
					    $iproductmotif      = $this->input->post('motif'.$i, TRUE);
					    $eproductname	    = $this->input->post('eproductname'.$i, TRUE);
					    $vunitprice		    = $this->input->post('vproductmill'.$i, TRUE);
					    $vunitprice		    = str_replace(',','',$vunitprice);
					    $nretur 			= $this->input->post('nretur'.$i, TRUE);
					    $nretur		  	    = str_replace(',','',$nretur);
					    $nasal   			= $this->input->post('nasal'.$i, TRUE);
					    $nasal		  	    = str_replace(',','',$nasal);
					    $nreceive	  	    = $this->input->post('nreceive'.$i, TRUE);
					    $nreceive		    = str_replace(',','',$nreceive);
					    $eremark  		    = $this->input->post('eremark'.$i, TRUE);
                        if($eremark=='')$eremark=null;
                        $this->mmaster->deletesjdetail( $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif);
						$th=substr($dsj,0,4);
						$bl=substr($dsj,5,2);
						$emutasiperiode=$th.$bl;
                        if( ($nasal!='') && ($nasal!=0) ){
					        $this->mmaster->updatemutasi04($icustomer,$iproduct,$iproductgrade,$iproductmotif,$nasal,$emutasiperiode);
					        $this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nasal);
                        }
                        if($cek=='on'){
                            if($nretur>0){
                                $this->mmaster->insertsjdetail( $iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,$vunitprice,$isj,$dsj,$iarea,$eremark,$i);
                                $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
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
                                $th=substr($dsj,0,4);
                                $bl=substr($dsj,5,2);
                                $emutasiperiode=$th.$bl;
                                $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode);
                                if($ada=='ada'){
                                  $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nretur,$emutasiperiode);
                                }else{
                                  $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nretur,$emutasiperiode,$q_aw,$q_ak);
                                }
                                if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)){
                                  $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nretur,$q_ak);
                                }else{
                                  $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$nretur);
                                }
                            }
                        }
                    }
                    if(($this->db->trans_status()=== False)){
                        $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false
                        );
                    } else {
                        $this->db->trans_commit();
                        $this->Logger->write('Update SJPB Retur No:'.$isj.' Area:'.$iarea);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $isj
                        );
                    }
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
