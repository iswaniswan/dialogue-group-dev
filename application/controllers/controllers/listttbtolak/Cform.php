<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070320';

    public function __construct(){
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
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'i_area'    => $this->mmaster->cekarea(),
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
        $xarea = $this->mmaster->cekarea();
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu,$xarea);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');

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
            'iarea'         => $area,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ittb   = $this->input->post('ittb');
        $iarea  = $this->input->post('iarea');
        $tahun  = $this->input->post('tahun');
        $inota  = $this->input->post('inota');

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ittb, $iarea, $tahun, $inota);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus TTB Tolak Area '.$iarea.' No:'.$ittb);
            echo json_encode($data);
        }
    }

    public function customer(){
        $filter = [];
        if ($this->input->get('iarea')!='') {
            $data   = $this->mmaster->bacacustomer(strtoupper($this->input->get('q')),$this->input->get('iarea', TRUE));
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_customer,  
                    'text'  => $row->e_customer_name
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function detailcustomer(){
        header("Content-Type: application/json", true);
        $data     = $this->mmaster->detailcustomer($this->input->post('icustomer', TRUE),$this->input->post('iarea', TRUE));
        echo json_encode($data->result_array());  
    } 

    public function salesman(){
        $filter = [];
        if ($this->input->get('iarea')!='' && $this->input->get('dttb')!='') {
            $dttb   = $this->input->get('dttb');
            $per    = "";
            if($dttb!=''){
                $tmp = explode('-',$dttb);
                $yy  = $tmp[2];
                $bl  = $tmp[1];
                $per = $yy.$bl;
            }
            $data   = $this->mmaster->bacasalesman(strtoupper($this->input->get('q')),$this->input->get('iarea', TRUE),$per);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_salesman,  
                    'text'  => $row->e_salesman_name
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function alasan(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacaalasan($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_alasan_retur,  
                'text'  => $row->e_alasan_returname
            );
        }
        echo json_encode($filter);
    }

    public function edit(){
        /*$data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }*/
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ittb           = $this->uri->segment(4);
            $iarea          = $this->uri->segment(5);
            $tahun          = $this->uri->segment(6);
            $fnotakoreksi   = $this->uri->segment(7);
            $dfrom          = $this->uri->segment(8);
            $dto            = $this->uri->segment(9);
            $xarea          = $this->uri->segment(10);
            
            $query 				= $this->db->query("select i_nota, i_area from tm_ttbtolak 
													where i_ttb = '$ittb' and i_area = '$iarea' and n_ttb_year=$tahun");
            if($query->num_rows()>0){
                foreach($query->result() as $row){
                    $nota=$row->i_nota;
                    $area=$row->i_area;
                }

                if($fnotakoreksi=='t'){
                    $query 			= $this->db->query("select i_product from tm_notakoreksi_item 
                                                        where i_nota = '$nota' and i_area = '$area'");
                }else{
                    $query 			= $this->db->query("select i_product from tm_nota_item 
                                                        where i_nota = '$nota' and i_area = '$area'");
                }
            }
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'ittb'          => $ittb,
                'iarea'         => $iarea,
                'tahun'         => $tahun,
                'fnotakoreksi'  => $fnotakoreksi,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'xarea'         => $xarea,
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($iarea,$ittb,$tahun),
                'detail'        => $this->mmaster->bacadetail($iarea,$ittb,$tahun,$fnotakoreksi,$nota),
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea			= $this->input->post('iarea', TRUE);
			$nttbdiscount1	= $this->input->post('ncustomerdiscount1',TRUE);
			$nttbdiscount2	= $this->input->post('ncustomerdiscount2',TRUE);
			$nttbdiscount3	= $this->input->post('ncustomerdiscount3',TRUE);
			$vttbdiscount1	= $this->input->post('vcustomerdiscount1',TRUE);
			$vttbdiscount2	= $this->input->post('vcustomerdiscount2',TRUE);
			$vttbdiscount3	= $this->input->post('vcustomerdiscount3',TRUE);
			$vttbdiscounttotal	= $this->input->post('vttbdiscounttotal',TRUE);
			$vttbdiscounttotal	= str_replace(',','',$vttbdiscounttotal);
			$vttbnetto			= $this->input->post('vttbnetto',TRUE);
			$vttbnetto			= str_replace(',','',$vttbnetto);
			$vttbgross			= $vttbnetto+$vttbdiscounttotal;
			$jml				= $this->input->post('jml', TRUE);
			$ittb 				= $this->input->post('ittb', TRUE);
			$dttb 				= $this->input->post('dttb', TRUE);
			if($dttb!=''){
				$tmp=explode("-",$dttb);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dttb=$th."-".$bl."-".$hr;
			}
			$tahun	= $this->input->post('nttbyear', TRUE);
			$dreceive1 	= $this->input->post('dreceive1', TRUE);
			if($dreceive1!=''){
				$tmp=explode("-",$dreceive1);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dreceive1=$th."-".$bl."-".$hr;
			}else{
        $dreceive1=null;
      }
			$ettbremark 	= $this->input->post('eremark', TRUE);
			if($ettbremark=='') $ettbremark=null;
			$istore				= 'AA';
			$istorelocation		= '01';
			$istorelocationbin	= '00';
			$eremark			= 'TTB Tolakan';
			$ibbktype			= '01';
			$ibbmtype			= '05';
			$ibbm				= $this->input->post('ibbm', TRUE);
			$dbbm				= $dttb;
			if(($dttb!='') && ($ittb!='')){
				$this->db->trans_begin();
				$this->mmaster->updateheader(	$ittb,$iarea,$tahun,$dttb,$dreceive1,$ettbremark,
												$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,
												$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,
												$vttbgross	);
				$this->mmaster->updatebbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea);
        $x=0;
				for($i=1;$i<=$jml;$i++){
				  $iproduct					= $this->input->post('iproduct'.$i, TRUE);
				  $iproductgrade			= 'A';
				  $iproductmotif			= $this->input->post('motif'.$i, TRUE);
				  $eproductname				= $this->input->post('eproductname'.$i, TRUE);
				  $vunitprice				= $this->input->post('vproductretail'.$i, TRUE);
				  $vunitprice				= str_replace(',','',$vunitprice);
				  $ndeliver					= $this->input->post('ndeliver'.$i, TRUE);
				  $nquantity				= $this->input->post('nquantity'.$i, TRUE);
				  $nasal    				= $this->input->post('nasal'.$i, TRUE);
				  $ettbremark				= $this->input->post('eremark'.$i, TRUE);
				  if($ettbremark=='')
					$ettbremark=null;
				  $this->mmaster->deletedetail(	$iproduct, $iproductgrade, $ittb, $iproductmotif,$nquantity,$istore,
							 					$istorelocation,$istorelocationbin,$tahun,$iarea);
#####
          $th=substr($dttb,0,4);
					$bl=substr($dttb,5,2);
					$emutasiperiode=$th.$bl;
					$tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ibbm);
				  $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$nasal,$emutasiperiode);
				  $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$nasal);
#####
				  if($nquantity>0){
            $x++;
				 	  $this->mmaster->insertdetail(	$iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,
													$nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver,$x);
					  $this->mmaster->insertbbmdetail(	$iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,
														$vunitprice,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$x);
#####
            $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
            if(isset($trans)){
              foreach($trans as $itrans)
              {
                $q_aw =$itrans->n_quantity_awal;
                $q_ak =$itrans->n_quantity_akhir;
                $q_in =$itrans->n_quantity_in;
                $q_out=$itrans->n_quantity_out;
                break;
              }
            }else{
              $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
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
            }
            
            $this->mmaster->inserttrans44($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$ibbm,$q_in,$q_out,$nquantity,$q_aw,$q_ak,$tra);
            $ada=$this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$emutasiperiode);
            if($ada=='ada')
            {
              $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$nquantity,$emutasiperiode);
            }else{
              $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$nquantity,$emutasiperiode);
            }
            if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00'))
            {
              $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$nquantity,$q_ak);
            }else{
              $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$nquantity);
            }
#####
				  }
				}
			
                if(($this->db->trans_status()=== False)){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update TTB Tolak Area '.$iarea.' No:'.$ittb);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ittb
                    );
                }

			}//end transbegin
        else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}

/* End of file Cform.php */
