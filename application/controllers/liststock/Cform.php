<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070322';

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
        $istore             = $this->input->post('istore');
        $istorelocation     = $this->input->post('istorelocation');
        $iarea              = $this->input->post('iarea');
        if($istore==''){
            $istore=$this->uri->segment(4);
        } 
        if($istorelocation==''){
            $istorelocation=$this->uri->segment(5);
        } 
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        } 
        echo $this->mmaster->data($istore,$istorelocation,$this->global['folder']);
    }
    
    public function view(){
    	$istore             = $this->input->post('istore');
        $istorelocation     = $this->input->post('istorelocation');
        $iarea              = $this->input->post('iarea');
        if($istore==''){
            $istore=$this->uri->segment(4);
        } 
        if($istorelocation==''){
            $istorelocation=$this->uri->segment(5);
        } 
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        } 
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iarea'         => $iarea,
            'istore'        => $istore,
            'istorelocation'=> $istorelocation
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function getstore(){
        header("Content-Type: application/json", true);
        $istore  = $this->input->post('istore');
        $query  = array(
            'isi' => $this->mmaster->bacastore($istore)->row(),
        );
        echo json_encode($query);  
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if($this->uri->segment(4)!=''){
            $isj        = $this->uri->segment(4);
			$iarea      = $this->uri->segment(5);
			$dfrom      = $this->uri->segment(6);
            $dto        = $this->uri->segment(7);
            $ispb       = $this->uri->segment(8);
            $bisaedit   = $this->uri->segment(9);
            $username   = $this->session->userdata('username');
            $idcompany  = $this->session->userdata('id_company');
            $iareasj    = substr($isj,8,2);
            $query      = $this->db->query("select i_sj from tm_nota_item where i_sj='$isj'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'isj'            => $isj,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'iarea'          => $iarea,
                'iareasj'        => $iareasj,
                'bisaedit'       => $bisaedit,
                'isi'            => $this->mmaster->baca($isj,$iarea),
                'departemen'     => $this->mmaster->cekdepartemen($username,$idcompany),
                'stockdaerah'    => $this->mmaster->stockdaerah($ispb,$iarea),
                'cquery'         => $this->mmaster->bacadetailspb($ispb,$isj,$iarea)->result(),
                'detail'         => $this->mmaster->bacadetail($isj,$iarea)
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

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj	  = $this->input->post('isj', TRUE);
		$isjold	= $this->input->post('isjold', TRUE);
		$dsj   	= $this->input->post('dsj', TRUE);
		$iarea	= $this->input->post('iarea', TRUE);
		$ispb 	= $this->input->post('ispb', TRUE);
		$dspb	  = $this->input->post('dspb', TRUE);
		if($dsj!=''){
            $tmp	= explode("-",$dsj);
            $th	= $tmp[2];
            $bl	= $tmp[1];
            $hr	= $tmp[0];
            $dsj	= $th."-".$bl."-".$hr;
            $thbl	= substr($th,2,2).$bl;
            $tmpsj	= explode("-",$isj);
            $firstsj= $tmpsj[0];
            $lastsj	= $tmpsj[2];
            $newsj	= $firstsj."-".$thbl."-".$lastsj;
        }
        if($dspb!=''){
            $tmp=explode("-",$dspb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspb=$th."-".$bl."-".$hr;
        }
        $eareaname	        = $this->input->post('eareaname', TRUE);
		$isalesman		    = $this->input->post('isalesman',TRUE);
		$icustomer		    = $this->input->post('icustomer',TRUE);
		$nsjdiscount1	    = $this->input->post('nsjdiscount1',TRUE);
		$nsjdiscount1	    = str_replace(',','',$nsjdiscount1);
		$nsjdiscount2	    = $this->input->post('nsjdiscount2',TRUE);
		$nsjdiscount2	    = str_replace(',','',$nsjdiscount2);
		$nsjdiscount3	    = $this->input->post('nsjdiscount3',TRUE);
		$nsjdiscount3	    = str_replace(',','',$nsjdiscount3);
		$vsjdiscount1	    = $this->input->post('vsjdiscount1',TRUE);
		$vsjdiscount1	    = str_replace(',','',$vsjdiscount1);
		$vsjdiscount2	    = $this->input->post('vsjdiscount2',TRUE);
		$vsjdiscount2	    = str_replace(',','',$vsjdiscount2);
		$vsjdiscount3	    = $this->input->post('vsjdiscount3',TRUE);
		$vsjdiscount3	    = str_replace(',','',$vsjdiscount3);
		$vsjdiscounttotal	= $this->input->post('vsjdiscounttotal',TRUE);
		$vsjdiscounttotal	= str_replace(',','',$vsjdiscounttotal);
		$vsjgross	        = $this->input->post('vsjgross',TRUE);
		$vsjgross	        = str_replace(',','',$vsjgross);
		$vsjnetto	        = $this->input->post('vsjnetto',TRUE);
		$vsjnetto	        = str_replace(',','',$vsjnetto);
		$jml	            = $this->input->post('jml', TRUE);
		$cek_nota           = $this->db->query("select i_nota from tm_nota where i_sj = '$isj'");
        if(($cek_nota->row()->i_nota != '') || ($cek_nota->row()->i_nota != null)){
            echo "Sudah Jadi Nota !";
            die;
        }
        if($isj!='' && $dsj!='' && $eareaname!=''){
            $this->db->trans_begin();
			$gaono=true;
			for($i=1;$i<=$jml;$i++){
				$cek=$this->input->post('chk'.$i, TRUE);
				if($cek=='on'){
					$gaono=false;
				}
				if(!$gaono) break;
			}
			if(!$gaono){
				$istore	= $this->input->post('istore', TRUE);
				$kons		= $this->mmaster->cekkons($ispb,$iarea);
				if($istore=='AA'){
					$istorelocation		= '01';
				}else{
					if($kons=='t'){
						if($istore=='PB'){
							$istorelocation		= '00';
						}else{
							$istorelocation		= 'PB';
						}
					}else{
						$istorelocation		= '00';
					}
				}
				$istorelocationbin	= '00';
				$eremark		= 'SPB';
				$this->mmaster->updatesjheader($ispb,$dspb,$isj,$dsj,$iarea,$isalesman,$icustomer,
					$nsjdiscount1,$nsjdiscount2,$nsjdiscount3,$vsjdiscount1, 
					$vsjdiscount2,$vsjdiscount3,$vsjdiscounttotal,$vsjgross,$vsjnetto,$isjold);
				
				$this->mmaster->updatespb($ispb,$iarea,$isj,$dsj);
				$this->mmaster->updatedkb($vsjnetto,$isj,$iarea);
				for($i=1;$i<=$jml;$i++){
					$cek=$this->input->post('chk'.$i, TRUE);
					$vunitprice	= $this->input->post('vproductmill'.$i, TRUE);
					$vunitprice	= str_replace(',','',$vunitprice);
					$iproduct	= $this->input->post('iproduct'.$i, TRUE);
					$eproductname	= $this->input->post('eproductname'.$i, TRUE);
					$iproductgrade= 'A';
					$iproductmotif= $this->input->post('motif'.$i, TRUE);
					$ntmp		= $this->input->post('ntmp'.$i, TRUE);
					$ntmp		= str_replace(',','',$ntmp);
					$ndeliver		= $this->input->post('ndeliver'.$i, TRUE);
					$ndeliver		= str_replace(',','',$ndeliver);
					if($ntmp!=$ndeliver){
						$this->mmaster->deletesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$iarea);
						$th=substr($dsj,0,4);
						$bl=substr($dsj,5,2);
						$emutasiperiode=$th.$bl;
#              if( ($ntmp!='') && ($ntmp!=0) ){
						if( ($ntmp>0) ){
							$tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname);
							$this->mmaster->updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode);
							$this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp);
						}
###############2013 09 26
#					    $vunitprice	= $this->input->post('vproductmill'.$i, TRUE);
#					    $vunitprice	= str_replace(',','',$vunitprice);
						if($cek=='on'){
							$ndeliver		= $this->input->post('ndeliver'.$i, TRUE);
							$ndeliver		= str_replace(',','',$ndeliver);
							$eproductname	= $this->input->post('eproductname'.$i, TRUE);
							$norder		  = $this->input->post('norder'.$i, TRUE);
							$norderx		= str_replace(',','',$norder);
							if($ndeliver>0){	
								$this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vunitprice,$isj,$iarea,$i);
#                  $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
								$trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
								if(isset($trans)){
										foreach($trans as $itrans){
											$q_aw =$itrans->n_quantity_stock;
											$q_ak =$itrans->n_quantity_stock;
#                      $q_in =$itrans->n_quantity_in;
#                      $q_out=$itrans->n_quantity_out;
											$q_in =0;
											$q_out=0;
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
								$this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak,$trans);
								if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
									$this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
								}else{
									$this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode,$q_aw);
								}
								if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
									$this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$q_ak);
								}else{
									$this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver,$q_aw);
								}
								$this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea,$vunitprice);
								}else{
									$this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,0,$iarea,$vunitprice);
								}
						}else{
							$this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,0,$iarea,$vunitprice);
						}
###############              
					}elseif($ntmp==$ndeliver){
						if($cek!='on'){
							$this->mmaster->deletesjdetail($iproduct,$iproductgrade,$iproductmotif,$isj,$iarea);
							$th=substr($dsj,0,4);
							$bl=substr($dsj,5,2);
							$emutasiperiode=$th.$bl;
							if( ($ntmp!='') && ($ntmp!=0) ){
								$tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname);
								$this->mmaster->updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode);
								$this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp);
							}
							$this->mmaster->updatespbitem($ispb,$iproduct,$iproductgrade,$iproductmotif,0,$iarea,$vunitprice);
						}
					}
				}
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SJ :'.$this->global['title'].' Kode : '.$isj);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update SJ '.$isj
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

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj    = $this->input->post('isj');
        $iarea  =  $this->input->post('iarea');
        $this->db->trans_begin();
        $this->mmaster->delete($isj,$iarea);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJ '.$isj);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
