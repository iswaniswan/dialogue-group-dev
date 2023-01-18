<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020206';

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
    

    public function index()
    {
        $iarea = $this->input->post('iarea');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
        
    }

    function data_area(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $user  = $this->session->userdata('username');
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" distinct (a.i_store), a.i_store, a.i_area, a.e_area_name, b.e_store_name, c.i_store_location, c.e_store_locationname
                            from tr_area a, tr_store b, tr_store_location c
                            where (upper(e_area_name) like '%$cari%' or upper(i_area) like '%$cari%') and i_area in 
                            ( select i_area from tm_user_area where i_user = '$user')
                            and a.i_store=b.i_store and b.i_store=c.i_store order by a.i_store ", FALSE);
            $query = $this->db->get();
            foreach($query->result() as  $store){
                    $filter[] = array(
                    'id' => $store->i_store,  
                    'text' => $store->i_store.'-'.$store->e_store_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getarea(){
        header("Content-Type: application/json", true);
        $istore = $this->input->post('i_store');
        $this->db->select("distinct (a.i_store), a.i_store, a.i_area, a.e_area_name, b.e_store_name, c.i_store_location, c.e_store_locationname
                            from tr_area a, tr_store b, tr_store_location c
                            where a.i_store=b.i_store and b.i_store=c.i_store and a.i_store='$istore' order by a.i_store ", FALSE);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $istore = $this->uri->segment('4');
            $istorelocation = $this->uri->segment('5');
            $this->db->select(" a.i_product, b.e_product_name, b.v_product_retail, a.n_quantity_stock,
                                a.i_product_motif, c.e_product_motifname
                                from tm_ic a, tr_product b, tr_product_motif c
                                where a.i_product=b.i_product and b.i_product=c.i_product and c.i_product_motif='00'
                                and a.i_product_motif=c.i_product_motif and i_store='$istore' and i_store_location='$istorelocation'
                                and (upper(a.i_product) like '%$cari%' or upper(b.e_product_name) like '%$cari%')
                                order by b.e_product_name",false);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->i_product,  
                    'text' => $product->i_product.' - '.$product->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailbar(){
        header("Content-Type: application/json", true);
        $istore   = strtoupper($this->input->post('istore', FALSE));
        $iproduct = $this->input->post('iproduct', FALSE);
        if ($fstock!='t') {
            $data = $this->mmaster->bacaproductx($iproduct);
        }else{
            $data = $this->mmaster->bacaproducticx($istore,$iproduct);
        }
        echo json_encode($data->result_array());  
    }

    function getharga(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $this->db->select("a.e_product_name, a.v_product_mill, b.n_quantity_stock, b.i_product_motif");
        $this->db->from("tr_product a");
        $this->db->join("tm_ic b","a.i_product=b.i_product");
        $this->db->where("UPPER(a.i_product)", $iproduct);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }
    
   public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isjold	= $this->input->post('isjold', TRUE);
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
		$eareaname	= $this->input->post('eareaname', TRUE);
		$vspbnetto=$this->input->post('vsj', TRUE);
		$vspbnetto= str_replace(',','',$vspbnetto);
        $jml	  = $this->input->post('jml', TRUE);
        if($dsj!='' && $eareaname!=''){
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
					$istorelocation		= '01';
				}else{
					$istorelocation		= '00';
				}
				$istorelocationbin	= '00';
				$isj		 		= $this->mmaster->runningnumbersj($iarea,$thbl);
				$this->mmaster->insertsjheader($isj,$dsj,$iarea,$vspbnetto,$isjold);
				for($i=1;$i<=$jml;$i++){
                    $cek=$this->input->post('chk'.$i, TRUE);
					if($cek=='on'){
                        $iproduct	 = $this->input->post('iproduct'.$i, TRUE);
					    $iproductgrade= 'A';
					    $iproductmotif= $this->input->post('motif'.$i, TRUE);
					    $eproductname	= $this->input->post('eproductname'.$i, TRUE);
					    $vunitprice		= $this->input->post('vproductmill'.$i, TRUE);
					    $vunitprice		= str_replace(',','',$vunitprice);
					    $nretur 			= $this->input->post('nretur'.$i, TRUE);
					    $nretur		  	= str_replace(',','',$nretur);
					    $nreceive	  	= $this->input->post('nreceive'.$i, TRUE);
					    $nreceive		  = str_replace(',','',$nreceive);
					    $eremark  		= $this->input->post('eremark'.$i, TRUE);
                        if($eremark==''){
                            $eremark=null;
                        }
                        if($nretur>0){
                            $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,
									                        $vunitprice,$isj,$dsj,$iarea,$istore,$istorelocation,$istorelocationbin,$eremark,$i);
                            $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                            if(isset($trans)){
                                foreach($trans as $itrans){
                                    $q_aw =$itrans->n_quantity_awal;
                                    $q_ak =$itrans->n_quantity_akhir;
                                    $q_in =$itrans->n_quantity_in;
                                    $q_out=$itrans->n_quantity_out;
                                    break;
                                }
                            }else{
                                $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
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
                                $this->mmaster->inserttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$nretur,$q_aw,$q_ak);
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
                }
                if(($this->db->trans_status()=== False)){
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Input SJ Retur : '.$isj);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isj
                    );
                }
            }
            $this->load->view('pesan', $data);
        }  
   }
}
/* End of file Cform.php */
