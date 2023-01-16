<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040204';

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
    
    public function load(){
    	$dfrom = $this->input->post('dfrom');
    	$dto   = $dfrom;

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

    public function transfer(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $jml= $this->input->post('jml', TRUE);
		$idox			    = '';
		$isupplierx		    = '';
		$data['jml']	    = $jml;
		$data['kosong']	    = 0;
		$tmp[0]			    = null;
		$istore			    = 'AA';
		$istorelocation		= '01';
		$istorelocationbin  = '00';
        $eremark			= 'DO Transfer';
        for($i=0;$i<$jml;$i++){
            $cek = $this->input->post('chk'.$i, TRUE);
			if($cek!=''){
				$ido					= $this->input->post('nodok'.$i, TRUE);
				$iop					= $this->input->post('noop'.$i, TRUE);
				$iarea				= $this->input->post('wila'.$i, TRUE);
				$ddo					= $this->input->post('tgldok'.$i, TRUE);
				if($ddo!=''){
					$tmp=explode("-",$ddo);
					$th=$tmp[2];
					$bl=$tmp[1];
					$hr=$tmp[0];
					$ddo=$th."-".$bl."-".$hr;
					$thbl=substr($th,2,2).$bl;
				}
				$ido='DO-'.$thbl.'-DT'.substr($ido,2,4);
				$dop					= $this->input->post('tglop'.$i, TRUE);
                if($dop!=''){
					$tmp=explode("-",$dop);
					$th=$tmp[2];
					$bl=$tmp[1];
					$hr=$tmp[0];
					$dop=$th."-".$bl."-".$hr;
					$thbl=substr($th,2,2).$bl;
                }
                $iop='OP-'.$thbl.'-'.$iop;
				$iproduct		= $this->input->post('kodeprod'.$i, TRUE);
				$iproduct		= substr($iproduct,0,7);
				$iproductgrade	= 'A';
				$iproductmotif	= $this->input->post('kodeprod'.$i, TRUE);
				$iproductmotif	= substr($iproductmotif,7,2);
				$isupplier		= $this->input->post('kodelang'.$i, TRUE);
				$vproductmill	= $this->input->post('hargasat'.$i, TRUE);
				$vproductmill	= str_replace(',','',$vproductmill);
				$jumlah			= $this->input->post('jumlah'.$i, TRUE);
				$jumlah			= str_replace(',','',$jumlah);
				$eproductname	= null;
				$this->mmaster->inserttmp( $ido,$iproduct,$iproductgrade,$eproductname,$jumlah,
											$vproductmill,$iproductmotif,$isupplier,$ddo,$iarea,$iop,$dop);
            }
        }
        
        $dotmp	= '';
		$xx		= 0;
        $xxx  = array();
		$yy		= 0;
		for($i=0;$i<$jml;$i++){
            $cek					=$this->input->post('chk'.$i, TRUE);
			if($cek!=''){
                $nodo         = $this->input->post('nodo'.$i,TRUE);
				$xop          = $this->input->post('xop'.$i,TRUE);
				$ido		  = $this->input->post('nodok'.$i, TRUE);
				$iop		  = $this->input->post('noop'.$i, TRUE);
				$iarea		  = $this->input->post('wila'.$i, TRUE);
				$ddo		  = $this->input->post('tgldok'.$i, TRUE);
				if($ddo!=''){
					$tmp=explode("-",$ddo);
					$th=$tmp[2];
					$bl=$tmp[1];
					$hr=$tmp[0];
					$ddo=$th."-".$bl."-".$hr;
                    $emutasiperiode=$th.$bl;
                }
                $dop					= $this->input->post('tglop'.$i, TRUE);
				if($dop!=''){
                    $tmp=explode("-",$dop);
					$th=$tmp[2];
					$bl=$tmp[1];
					$hr=$tmp[0];
					$dop=$th."-".$bl."-".$hr;					
                    settype($iop,"string");
                    $a=strlen($iop);
                    while($a<6){
                      $iop="0".$iop;
                      $a=strlen($iop);
                    }			
                    $iop='OP-'.substr($th,2,2).$bl.'-'.$iop;
                }
                $thbl=substr($th,2,2).$bl;
				$ido            ='DO-'.$thbl.'-DT'.substr($ido,2,4);
				$iproduct		= $this->input->post('kodeprod'.$i, TRUE);
				$iproduct		= substr($iproduct,0,7);
				$iproductgrade	= 'A';
				$iproductmotif	= $this->input->post('kodeprod'.$i, TRUE);
				$iproductmotif	= substr($iproductmotif,7,2);
				$isupplier		= $this->input->post('kodelang'.$i, TRUE);
				$vproductmill	= $this->input->post('hargasat'.$i, TRUE);
				$vproductmill	= str_replace(',','',$vproductmill);
				$jumlah			= $this->input->post('jumlah'.$i, TRUE);
                $jumlah			= str_replace(',','',$jumlah);
				$query= $this->db->query("select e_product_name from tr_product where i_product='$iproduct'");
				foreach($query->result() as $riw){
					$eproductname	=$riw->e_product_name;
                }
                $adaop	= $this->mmaster->cekadaop($iop,$iproduct,$iproductmotif,$iproductgrade,$iarea);
                //var_dump($adaop);
                //die();
				$adaitem=1;
				if($adaop!=0){
                    $adaitem= $this->mmaster->cekdataitem($ido,$isupplier,$iproduct,$iproductmotif,$iproductgrade,$thbl);
					if($adaitem==0){
						$sudahada=false;
                        $ono= $this->mmaster->cekdata($ido,$isupplier,$thbl);
                        if( ($dotmp!= $ido) && ($ono==0) ){
                            $dotmp= $ido;
                            $query= $this->db->query("select sum(v_product_mill) as v_product_mill, i_do, i_supplier, d_do, i_area, i_op
                                                        from tt_dofc where i_do='$ido' and i_supplier='$isupplier'
                                                        group by i_do, i_supplier, d_do, i_area, i_op", false);
                            foreach($query->result() as $row){
                                $row->i_op=trim($row->i_op);
                                $qq= $this->db->query("select i_op from tm_opfc where i_op like '%$row->i_op%' and i_area='$iarea'", false);
								foreach($qq->result() as $rr){
                                    $re= $this->db->query("select i_product from tm_opfc_item where i_op='$rr->i_op' and i_product='$iproduct'", false);
										if($re->num_rows() >0){
											$ada	= $this->mmaster->cekdata($row->i_do,$row->i_supplier,$thbl);
											$siop	= 0;
											$saldo	= $jumlah;
                                            $qy		= $this->db->query("select i_op from tm_opfc where i_op like '%$row->i_op%' and i_area='$iarea'", false);
                                            foreach($qy->result() as $rw){
                                                if($ada==0){
                                                    if($siop==1){
                                                        $row->i_do=trim($row->i_do).'-A';
                                                    }elseif($siop==2){
                                                        $row->i_do=trim($row->i_do).'-B';
                                                    }elseif($siop==0){
                                                        $this->mmaster->insertheader($row->i_do,$row->i_supplier,$rw->i_op,$row->i_area,$row->d_do,
                                                                                     $row->v_product_mill);
                                                    }
                                                }else{
                                                    if($siop==1){
                                                        $qy= $this->db->query("select i_product from tm_opfc_item where i_op='$rw->i_op' and i_product='$iproduct'", false);
                                                        if($qy->num_rows() >0){
                                                        }
                                                    }elseif($siop==2){
                                                        $qy= $this->db->query("select i_product from tm_opfc_item where i_op='$rw->i_op' and i_product='$iproduct'", false);
                                                        if($qy->num_rows() >0){
                                                        }
                                                    }else{
                                                        $this->mmaster->updateheader($row->i_do,$row->i_supplier,$rw->i_op,$row->i_area,$row->d_do,
                                                                                     $row->v_product_mill);
                                                    }
                                                }
                                                $siop++;
                                                $qy= $this->db->query("select i_product, n_order, n_item_no from tm_opfc_item where i_op='$iop' and i_product='$iproduct'", false);
												if($qy->num_rows() >0){
                                                    foreach($qy->result() as $rwz){
                                                        $saldo=$saldo-$rwz->n_order;
                                                        if($saldo<=0){
                                                            $this->mmaster->insertdetail($iop,$ido,$iproduct,$iproductgrade,$eproductname,$jumlah,
																		                $vproductmill,$iproductmotif,$isupplier,$rwz->n_item_no,$ddo,$emutasiperiode);
                                                            $this->mmaster->updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$jumlah);
                                                            $this->mmaster->updatesaldofc($iproduct,$jumlah,$emutasiperiode);
                                                            $iproductmotif='00';
                                                        }
                                                        $sudahada=true;
                                                    }
                                                }
                                            }
                                        }
                                }
                            }
                        }
                        if(!$sudahada){
                            if($ono>0){
                                $saldo=$jumlah;
                                $qy= $this->db->query("select i_product, n_order, n_item_no from tm_opfc_item where i_op='$iop' and i_product='$iproduct'", false);
								if($qy->num_rows() >0){
                                    foreach($qy->result() as $rwz){
                                        $saldo=$saldo-$rwz->n_order;
                                        $this->mmaster->insertdetail($iop,$ido,$iproduct,$iproductgrade,$eproductname,$jumlah,$vproductmill,$iproductmotif,$isupplier,$rwz->n_item_no,$ddo,$emutasiperiode);
                                        $this->mmaster->updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$jumlah);
                                    }
                                }
                            }else{
                                $this->mmaster->insertdetail($iop,$ido,$iproduct,$iproductgrade,$eproductname,$jumlah,$vproductmill,$iproductmotif,$isupplier,$i,$ddo,$emutasiperiode);
                                $this->mmaster->updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$jumlah);
                            }
                            $sudahada=false;
                        }
                    }
                }else{
                    $yy++;
				    $xx++;
				    $xxx[$yy]		= $iop.' - '.$iproduct.$iproductmotif.' - '.$eproductname.'  -  Tidak Ada di OP';
				    $data['kosong']	= $xx;
                }
            }
        }
        $data['error']=$xxx;
  		$this->db->query("delete from tt_dofc");
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('DO Transfer ke DSG '.$this->global['title'].' Kode : '.$ido);
            $konek 	= "host=192.168.0.93 user=dedy dbname=distributor port=5432 password=g#>m[J2P^^";
            $db    	= pg_connect($konek);    	
            for($i=0;$i<$jml;$i++){
                    $cek			=$this->input->post('chk'.$i, TRUE);
                    if($cek!=''){
                      $nodo         = $this->input->post('nodo'.$i,TRUE);
                      $xop          = $this->input->post('xop'.$i,TRUE);
                $iproduct			= $this->input->post('kodeprod'.$i, TRUE);
                $sql	= " update duta_prod.tm_trans_dofc set f_transfer='t'
                                    where i_do_code='$nodo' and i_op_code='$xop' and i_product='$iproduct'";
                pg_query($sql);
              }
            }
            $data = array(
                'sukses'    => true,
                'kode'      => 'Transfer DO ke DSG '.$ido. ' OP : '.$iop
            );
            pg_close($db);
            $konek 	= "host=192.168.0.93 user=dedy dbname=distributor port=5432 password=g#>m[J2P^^";
    	    $db    	= pg_connect($konek);
        }
        $this->load->view('pesan', $data);
    }  
}

/* End of file Cform.php */
