<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020102';

    public function __construct()
    {
        
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
            $dttb       = date('Y/m/d');
      		$now    = substr($dttb,2,2).substr($dttb,5,2);
			$dudet	= $this->fungsi->dateAdd("m",-1,$dttb);
			$dudet 	= explode("-", $dudet);
			$mon	  = $dudet[1];
			$yir 	  = substr($dudet[0],2,2);
            $dudet	= $yir.$mon;
        // $now = $this->uri->segment('4');
		// $dudet = $this->uri->segment('5');
		echo $this->mmaster->data($this->i_menu,$now,$dudet);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'productgrade'     => $this->mmaster->get_productgrade()->result() 
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tr_product");
            $this->db->like("UPPER(i_product)", $cari);
            $this->db->or_like("UPPER(e_product_name)", $cari);
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

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct 			= $this->input->post('iproduct', TRUE);
        $eproductname 		= $this->input->post('eproductname', TRUE);
        $iproductgrade	    = $this->input->post('iproductgrade', TRUE);
        $nproductmargin	    = $this->input->post('nproductmargin', TRUE);
        $vproductmill	    = $this->input->post('vproductmill', TRUE);

        if ($iproduct != '' && $eproductname != ''){
                $cekada = $this->mmaster->cek_data($iproduct);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproduct);
                    $this->mmaster->insert($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iproduct
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($isj)->row(),
            'data2' => $this->mmaster->cek_data2($isj)
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
    public function update()
        {   
            $data = check_role($this->i_menu, 3);
            if(!$data){
                redirect(base_url(),'refresh');
            }
                $inota 	= $this->input->post('inota', TRUE);
                $dnota 	= $this->input->post('dnota', TRUE);
                if($dnota!=''){
                    $tmp=explode("-",$dnota);
                    $th=$tmp[2];
                    $bl=$tmp[1];
                    $hr=$tmp[0];
                    $dnota=$th."-".$bl."-".$hr;
                }else{
            $dnota=null;
          }
                $icustomer		= $this->input->post('icustomer', TRUE);
                $ecustomername	= $this->input->post('ecustomername', TRUE);
                 $iarea			= $this->input->post('iarea', TRUE);
                // $iarea			= "01";
                // $icustomer		= "11017";
                // $isalesman		= "50";
                $isj        = $this->input->post('isj', TRUE);
                $eareaname		= $this->input->post('eareaname', TRUE);
                $isalesman		= $this->input->post('isalesman',TRUE);
                $esalesmanname	= $this->input->post('esalesmanname',TRUE);
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
                $fttbplusppn		= $this->input->post('fttbplusppn',TRUE);
                $fttbplusdiscount	= $this->input->post('fttbplusdiscount',TRUE);
                $jml				= $this->input->post('jml', TRUE);
                $ittb 				= $this->input->post('ittb', TRUE);
                $dttb 				= $this->input->post('dttb', TRUE);
                if($dttb!=''){
                    $tmp=explode("-",$dttb);
                    $th=$tmp[2];
                    $bl=$tmp[1];
                    $hr=$tmp[0];
                    $dttb=$th."-".$bl."-".$hr;
    #        $thbl=substr($th,2,2).$bl;
            $thbl=$th.$bl;
                }
                $tahun	= $th;
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
                if($ettbremark=='')
                    $ettbremark=null;
                $ecustomerpkpnpwp 	= $this->input->post('ecustomerpkpnpwp', TRUE);
                if($ecustomerpkpnpwp=='')
                    $fttbpkp	= 'f';
                else
                    $fttbpkp	= 't';
                $fttbcancel='f';
                if(($dttb!='') && ($ittb!='')){
                    $this->db->trans_begin();
                    $ibbm				= $this->mmaster->runningnumberbbm($thbl);
                    $dbbm				= $dttb;
                    $istore				= 'AA';
                    $istorelocation		= '01';
                    $istorelocationbin	= '00';
                    $eremark			= 'TTB Tolakan';
                    $ibbktype			= '01';
                    $ibbmtype			= '05';
                    $this->mmaster->insertheader(	$iarea,$ittb,$dttb,$icustomer,$isalesman,$inota,$dnota,$nttbdiscount1,$nttbdiscount2,
                                                    $nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
                                                    $fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
                                                    $dreceive1,$tahun,$isj);
                    $this->mmaster->insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman);
            $x=0;
                    for($i=1;$i<=$jml;$i++){
                      $iproduct					= $this->input->post('iproduct'.$i, TRUE);
                      $iproductgrade			= 'A';
                      $iproductmotif			= '00';
                      $eproductname				= $this->input->post('eproductname'.$i, TRUE);
                      $vunitprice				= $this->input->post('vproductretail'.$i, TRUE);
                      $vunitprice				= str_replace(',','',$vunitprice);
                      $ndeliver					= $this->input->post('ndeliver'.$i, TRUE);
                      $nquantity				= $this->input->post('nquantity'.$i, TRUE);
                      $ettbremark				= $this->input->post('eremark'.$i, TRUE);
                      if($ettbremark=='')
                        $ettbremark=null;
                      if($nquantity>0){
                $x++;
                          $this->mmaster->insertdetail(	$iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,
                                                        $nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver,$x,$isj);
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
                $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$ibbm,$q_in,$q_out,$nquantity,$q_aw,$q_ak);
                $th=substr($dttb,0,4);
                $bl=substr($dttb,5,2);
                $emutasiperiode=$th.$bl;
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
                }
                if ($this->db->trans_status() === FALSE)
                {
                  $this->db->trans_rollback();
                }else{
    #        $this->db->trans_rollback();
                    $this->db->trans_commit();
    
                        $sess=$this->session->userdata('session_id');
                        $id=$this->session->userdata('user_id');
                        $sql	= "select * from dgu_session where session_id='$sess' and not user_data isnull";
                        $rs		= pg_query($sql);
                        if(pg_num_rows($rs)>0){
                            while($row=pg_fetch_assoc($rs)){
                                $ip_address	  = $row['ip_address'];
                                break;
                            }
                        }else{
                            $ip_address='kosong';
                        }
                        $query 	= pg_query("SELECT current_timestamp as c");
                        while($row=pg_fetch_assoc($query)){
                            $now	  = $row['c'];
                        }
                        $pesan='Update TTB Tolak Area '.$iarea.' No:'.$ittb;
                        $this->load->model('logger');
                        $this->logger->write($id, $ip_address, $now , $pesan );
    
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $iarea.$ittb
                        );
                    // $data['inomor']	= $iarea.$ittb;
                    $this->load->view('pesan',$data);
                }
            }
        
    public function update2(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproduct 			= $this->input->post('iproduct', TRUE);
        $eproductname 		= $this->input->post('eproductname', TRUE);
        $iproductgrade	    = $this->input->post('iproductgrade', TRUE);
        $nproductmargin	    = $this->input->post('nproductmargin', TRUE);
        $vproductmill	    = $this->input->post('vproductmill', TRUE);


        if ($iproduct != '' && $eproductname != ''){
            $cekada = $this->mmaster->cek_data($iproduct);
            if($cekada->num_rows() > 0){ 
                $this->mmaster->update($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill);
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iproduct);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
                );
            }else{
                $data = array(
                    'sukses' => false
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $iproduct = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iproduct)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
