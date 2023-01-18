<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070404';

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
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->i_menu);
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
            $isjpb  = $this->uri->segment(4);
			$iarea  = $this->uri->segment(5);
			$dfrom  = $this->uri->segment(6);
            $dto    = $this->uri->segment(7);
            $username = $this->session->userdata('username');
            $idcompany = $this->session->userdata('id_company');
            $query 	= $this->db->query("select i_sjpb from tm_sjpb_item where i_sjpb = '$isjpb' and i_area='$iarea'");
            $area   = $this->db->query("select * from tr_area where i_area in (select i_area from public.tm_user_area where username = '$username' and id_company = '$idcompany')");
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'jmlitem'    => $query->num_rows(),
                'isjpb'      => $isjpb,
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'iarea'      => $iarea,
                'pst'        => $area->result_array(),
                'isi'        => $this->mmaster->baca($isjpb,$iarea),
                'detail'     => $this->mmaster->bacadetail($isjpb,$iarea)
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
    }

    function databarang(){
        $filter = [];
        $icustomer = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select b.i_product, b.i_price_group, a.i_product_motif, a.e_product_motifname, b.v_product_retail, c.e_product_name
                                    from tr_product_motif a,tr_product c, tr_product_priceco b, tr_customer_consigment d
                                    where a.i_product=c.i_product and a.i_product=b.i_product 
                                    and (upper(a.i_product) like '%$cari%' or upper(c.e_product_name) like '%$cari%')
                                    and d.i_customer='$icustomer' and d.i_price_groupco=b.i_price_groupco and a.i_product_motif='00'
                                    order by c.i_product, a.e_product_motifname, b.i_price_group",false);
            foreach($query->result() as  $produk){
                    $filter[] = array(
                    'id' => $produk->i_product,  
                    'text' => $produk->i_product.'-'.$produk->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $icustomer = $this->uri->segment('4');
        $data=$this->db->query("select b.i_product, b.i_price_groupco, a.i_product_motif, a.e_product_motifname, b.v_product_retail, c.e_product_name
                          from tr_product_motif a,tr_product c, tr_product_priceco b, tr_customer_consigment d
                          where a.i_product=c.i_product and a.i_product=b.i_product 
                          and d.i_customer='$icustomer' and a.i_product='$iproduct' and d.i_price_groupco=b.i_price_groupco and a.i_product_motif='00'
                          order by c.i_product, a.e_product_motifname, b.i_price_group");
        //$data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isjpb      = $this->input->post('isj', TRUE);
		$iarea      = $this->input->post('iarea', TRUE);
		$dsjreceive = $this->input->post('dreceive', TRUE);
		if($dsjreceive!=''){
			$tmp    =explode("-",$dsjreceive);
			$th     =$tmp[2];
			$bl     =$tmp[1];
			$hr     =$tmp[0];
			$dsjreceive=$th."-".$bl."-".$hr;
			$thbl	  = substr($th,2,2).$bl;
			$tmpsj	= explode("-",$isjpb);
			$firstsj= $tmpsj[0];
			$lastsj	= $tmpsj[2];
			$newsj	= $firstsj."-".$thbl."-".$lastsj;				
		}
        $isjp		= $this->input->post('isjp', TRUE);
        $dsjp = $this->input->post('dsjp', TRUE);
        if($dsjp!=''){
            $tmp=explode("-",$dsjp);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjp=$th."-".$bl."-".$hr;
        }
        $dsjpb = $this->input->post('dsj', TRUE);
        if($dsjpb!=''){
                  $tmp=explode("-",$dsjpb);
                  $th=$tmp[2];
                  $bl=$tmp[1];
                  $hr=$tmp[0];
                  $dsjpb=$th."-".$bl."-".$hr;
        }
        $icustomer  = $this->input->post('icustomer', TRUE);
        $vsjpb      = $this->input->post('vsjpb', TRUE);
        $vsjpb      = str_replace(',','',$vsjpb);
        $vsjpbrec   = $this->input->post('vsjpbrec', TRUE);
        $vsjpbrec   = str_replace(',','',$vsjpbrec);
        $istore           = $iarea;
        $istorelocation   = 'PB';
        $istorelocationbin= '00';
        $jml	    = $this->input->post('jml', TRUE);
        $gaono=true;
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $gaono=false;
            }
            if(!$gaono) break;
        }
        if( (!$gaono)&&($dsjreceive!='') ){
            $this->db->trans_begin();
            $this->mmaster->updatesjheader($isjpb,$iarea,$dsjreceive,$vsjpb,$vsjpbrec);
			for($i=1;$i<=$jml;$i++){
				$cek=$this->input->post('chk'.$i, TRUE);
				$iproduct		= $this->input->post('iproduct'.$i, TRUE);
				$iproductgrade	= 'A';
				$iproductmotif	= $this->input->post('motif'.$i, TRUE);
				$ndeliver		= $this->input->post('ndeliver'.$i, TRUE);
				$ndeliver		= str_replace(',','',$ndeliver);
				$nreceive		= $this->input->post('nreceive'.$i, TRUE);
				$nreceive		= str_replace(',','',$nreceive);
				$ntmp		    = $this->input->post('ntmp'.$i, TRUE);
				$ntmp   		= str_replace(',','',$ntmp);
				$this->mmaster->deletesjdetail( $isjp, $isjpb, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver);
				$th=substr($dsjreceive,0,4);
				$bl=substr($dsjreceive,5,2);
				$emutasiperiode=$th.$bl;
                $thsj=substr($dsjpb,0,4);
				$blsj=substr($dsjpb,5,2);
                $emutasiperiodesj=$thsj.$blsj;
                
                if( ($ntmp!='') && ($ntmp!=0) ){
                    $this->mmaster->updatemutasi04($icustomer,$iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode,$emutasiperiodesj);
                    $this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$icustomer,$ntmp);
                }
                if($cek=='on'){
                    $eproductname	= $this->input->post('eproductname'.$i, TRUE);
					$vunitprice		= $this->input->post('vunitprice'.$i, TRUE);
					$vunitprice		= str_replace(',','',$vunitprice);
					$this->mmaster->insertsjpbdetail( $iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive);
					if($ndeliver>0){
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
                        
                        $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode);
						if($ada=='ada'){
						    $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nreceive,$emutasiperiode,$emutasiperiodesj,$iarea);
						}else{
						    $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nreceive,$emutasiperiode,$emutasiperiodesj,$iarea);
						}
						if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)){
						    $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nreceive,$q_ak);
						}else{
						    $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$nreceive);
						}
                    }
                }else{
                    $eproductname	= $this->input->post('eproductname'.$i, TRUE);
					$vunitprice		= $this->input->post('vproductmill'.$i, TRUE);
					$vunitprice		= str_replace(',','',$vunitprice);
					$eremark  		= $this->input->post('eremark'.$i, TRUE);
					if($eremark==''){
                        $eremark=null;
                    }
					$this->mmaster->insertsjpbdetail( $iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,
                                    			      $vunitprice,$isjpb,$iarea,$i,$dsjpb,$nreceive);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SJPB Receive :'.$this->global['title'].' Kode : '.$isjpb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update SJPB Receive '.$isjpb
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

        $isjp   = $this->uri->segment(4);
		$iarea	= $this->uri->segment(5);
		$dfrom	= $this->uri->segment(6);
		$dto	= $this->uri->segment(7);
        $this->db->trans_begin();
        $this->mmaster->delete($isjp,$iarea);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJPB Receipt '.$isjp);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
