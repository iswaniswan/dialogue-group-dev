<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070316';

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
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
            'supplier'  => $this->mmaster->bacasupplier($username, $idcompany)->result(),
            'i_area'    => $this->mmaster->cekarea(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $isupplier  = $this->input->post('isupplier');
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($isupplier==''){
            $isupplier=$this->uri->segment(6);
        }

        echo $this->mmaster->data($dfrom,$dto,$isupplier,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
    	$isupplier	= $this->input->post('isupplier');
        $dfrom	    = $this->input->post('dfrom');
        $dto	    = $this->input->post('dto');

        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($isupplier==''){
            $isupplier=$this->uri->segment(6);
        } 
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'isupplier'     => $isupplier,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iop		= $this->input->post('iop');
        $iap		= $this->input->post('iap');
        $isupplier	= $this->input->post('isupplier');
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iap,$isupplier,$iop);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel BBM-AP No '.$iap);
            echo json_encode($data);
        }
    }

    public function edit(){
        if(($this->uri->segment(5)) && ($this->uri->segment(6))){
            $inota  = $this->uri->segment(4);
            $iap    = $this->uri->segment(5);
            $isupp  = $this->uri->segment(6);
            $dfrom  = $this->uri->segment(7);
            $dto    = $this->uri->segment(8);
            $allsupp= $this->uri->segment(9);
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'inota'         => $inota,
                'iap'           => $iap,
                'isupp'         => $isupp,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'allsupp'       => $allsupp,
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($iap,$isupp),
                'detail'        => $this->mmaster->bacadetail($iap),
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function deleteitem(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iproduct           = $this->input->post('i_product');
        $iproductmotif      = $this->input->post('i_product_motif');
        $vproductmill       = $this->input->post('v_product_mill');
        $iproductgrade      = $this->input->post('i_product_grade');
        $nreceive           = $this->input->post('n_receive');
        $iap                = $this->input->post('i_ap');
        /* $istore             = 'AA';
        $istorelocation     = '01';
        $istorelocationbin  = '00'; */
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetail($iproduct,$iproductmotif,$iproductgrade,$iap);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Menghapus Detail Item BBM-AP No : '.$iap.' Kode Barang : '.$iproduct);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iap    = $this->input->post('iap', TRUE);
        $iapold = $this->input->post('iapold', TRUE);
        $dap    = $this->input->post('dap', TRUE);
        
        if($dap!=''){
            $tmp=explode("-",$dap);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dap=$th."-".$bl."-".$hr;
            $tahun=$th;
        }

        $isupplier		= $this->input->post('isupplier', TRUE);
        $iarea			= $this->input->post('iarea', TRUE);
        $iop			= $this->input->post('iop', TRUE);
        $vapgross		= $this->input->post('vapgross',TRUE);
        $vapgross		= str_replace(',','',$vapgross);
        $jml			= $this->input->post('jml', TRUE);
       
        if(($iap!='') && ($isupplier!='') && (($vapgross!='') || ($vapgross!='0'))
            && ($iop!='') && ($dap!='')
          )
        {
            $this->db->trans_begin();
            $istore				= 'AA';
            $istorelocation		= '01';
            $istorelocationbin	= '00';
            $eremark			= 'BBM AP';
            $ibbmtype			= '04';
            $query 				= $this->db->query("select i_bbm from tm_bbm where i_refference_document = '$iap' and i_bbm_type='04' ");
            foreach($query->result() as $t){
                $ibbm			= $t->i_bbm;
            }
            $dbbm				= $dap;
            $this->mmaster->updateheader($iap,$isupplier,$iop,$iarea,$dap,$vapgross,$iapold);
/*===================================================================================================================================================*/
            $this->mmaster->updatebbmheader($iap,$dap,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea);
/*===================================================================================================================================================*/
            for($i=1;$i<=$jml;$i++){
                $iproduct					= $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade		= 'A';
                $iproductmotif		= $this->input->post('motif'.$i, TRUE);
                $eproductname			= $this->input->post('eproductname'.$i, TRUE);
                $vproductmill			= $this->input->post('vproductmill'.$i, TRUE);
                $vproductmill			= str_replace(',','',$vproductmill);
                $nreceive					= $this->input->post('nreceive'.$i, TRUE);
                $nreceive         = str_replace(',','',$nreceive);
      $ntmp		          = $this->input->post('ntmp'.$i, TRUE);
                $ntmp		          = str_replace(',','',$ntmp);
                $this->mmaster->deletedetail2($iproduct, $iproductgrade, $iap, $isupplier, $iproductmotif, $tahun);

                $query=$this->db->query(" select i_area, i_reff from tm_op where i_area='$iarea' and i_op='$iop' ",false);
                if ($query->num_rows() > 0){
                    foreach($query->result() as $row){
                        if(substr($row->i_reff,0,3)=='SPB'){
                            $ispb=$row->i_reff;
                            $this->mmaster->updatespbx($iproduct,$iproductgrade,$iproductmotif,$ntmp,$ispb,$iarea);
                        }else if(substr($row->i_reff,0,4)=='SPMB'){
                            $ispmb=$row->i_reff;
                            $this->mmaster->updatespmbx($iproduct,$iproductgrade,$iproductmotif,$ntmp,$ispmb,$iarea);
                        }
                    }
                }		
      $th=substr($dap,0,4);
              $bl=substr($dap,5,2);
              $emutasiperiode=$th.$bl;
              $tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$iap,$ntmp,$eproductname);
      if( ($ntmp!='') && ($ntmp!=0) ){
              $this->mmaster->updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode);
              $this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp);
      }

                $this->mmaster->insertdetail($iap,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,
                                             $nreceive,$vproductmill,$dap,$iop,$i);
##########
      $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
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
      }
      $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iap,$q_in,$q_out,$nreceive,$q_aw,$q_ak);
      $th=substr($dap,0,4);
      $bl=substr($dap,5,2);
      $emutasiperiode=$th.$bl;
      if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode))
      {
        $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode);
      }else{
        $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode);
      }
      if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin))
      {
        $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$q_ak);
      }else{
        $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nreceive);
      }
##########

                $this->mmaster->insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nreceive,
                                                $vproductmill,$iap,$ibbm,$eremark,$dap);
                $query=$this->db->query(" 	select i_area, i_reff from tm_op
                                            where i_area='$iarea' and i_op='$iop' ",false);
                if ($query->num_rows() > 0){
                    foreach($query->result() as $row){
                        if(substr($row->i_reff,0,3)=='SPB'){
                            $ispb=$row->i_reff;
                            $this->mmaster->updatespb($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispb,$iarea);
                        }else if(substr($row->i_reff,0,4)=='SPMB'){
                            $ispmb=$row->i_reff;
                            $this->mmaster->updatespmb($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispmb,$iarea);
                        }
                    }
                }		
            }
            if ( ($this->db->trans_status() === FALSE) )
            {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                    $this->Logger->write('Update BBM-AP Area : '.$iarea.' No:'.$iap);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iap
                    );
            }
        }
        $this->load->view('pesan',$data);
    }
}

/* End of file Cform.php */