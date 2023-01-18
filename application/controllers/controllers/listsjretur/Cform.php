<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107030201';

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
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu);
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
            $isjr	= $this->uri->segment(4);
			$iarea  = $this->uri->segment(5);
			$dfrom  = $this->uri->segment(6);
			$dto 	= $this->uri->segment(7);
            $query 	= $this->db->query("select * from tm_sjr_item where i_sjr = '$isjr' and i_area='$iarea'");
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'jmlitem'    => $query->num_rows(),
                'isjr'       => $isjr,
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'iarea'      => $iarea,
                'isi'        => $this->mmaster->baca($isjr,$iarea),
                'detail'     => $this->mmaster->bacadetail($isjr,$iarea)
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
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

    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $icustomer = $this->uri->segment('4');
        $data=$this->db->query("select b.i_product, b.i_price_groupco, a.i_product_motif, a.e_product_motifname, b.v_product_retail, c.e_product_name
                          from tr_product_motif a,tr_product c, tr_product_priceco b, tr_customer_consigment d
                          where a.i_product=c.i_product and a.i_product=b.i_product 
                          and d.i_customer='$icustomer' and a.i_product='$iproduct' and d.i_price_groupco=b.i_price_groupco and a.i_product_motif='00'
                          order by c.i_product, a.e_product_motifname, b.i_price_group");
        echo json_encode($data->result_array());
    }

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj	= $this->input->post('isj', TRUE);
		$isjold	= $this->input->post('isjold', TRUE);
		$dsj 	= $this->input->post('dsj', TRUE);
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
        $gablas=true;
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
            $this->mmaster->updatesjheader($isj,$iarea,$isjold,$dsj,$vspbnetto);
           
			for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i);
				$iproduct		= $this->input->post('iproduct'.$i, TRUE);
				$eproductname	= $this->input->post('eproductname'.$i, TRUE);
				$iproductgrade	= 'A';
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
                $this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$nasal,$eproductname);
			    $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nasal,$emutasiperiode);
                $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nasal);
                if($cek=='on'){
                   
					$eproductname	= $this->input->post('eproductname'.$i, TRUE);
					$vunitprice		= $this->input->post('vproductmill'.$i, TRUE);
					$vunitprice		= str_replace(',','',$vunitprice);
					$nreceive		= $this->input->post('nreceive'.$i, TRUE);
					$nreceive		= str_replace(',','',$nreceive);
					$nretur		  	= $this->input->post('nretur'.$i, TRUE);
                    $nretur			= str_replace(',','',$nretur);
                    $eremark  		= $this->input->post('eremark'.$i, TRUE);
					if($eremark=='')$eremark=null;
					if($nretur>0){
                        $gablas=false;
					    $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,
                                                 $vunitprice,$isj,$dsj,$iarea,$istore,$istorelocation,
                                                 $istorelocationbin,$eremark,$i);                      
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
					    }
					    $this->mmaster->inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$nretur,$q_aw,$q_ak);
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
                $this->Logger->write('Update SJ Retur :'.$this->global['title'].' Kode : '.$isj);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update SJ Retur '.$isj
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

        $isjr   = $this->input->post('isjr');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $this->mmaster->delete($isjr,$iarea);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJ Retur: '.$isjr);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
