<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070311';

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

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if($this->uri->segment(4)!=''){
            $istockopname		= $this->uri->segment(4);
		    $istore 			= $this->uri->segment(5);
		    $istorelocation     = $this->uri->segment(6);
		    $iarea 				= $this->uri->segment(7);
		    $dfrom	 			= $this->uri->segment(8);
		    $dto		        = $this->uri->segment(9);
            $query = $this->db->query("	select * from tm_stockopname_item where i_stockopname = '$istockopname' and i_store = '$istore' and i_store_location = '$istorelocation'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'istockopname'   => $istockopname,
                'istore'         => $istore,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'istorelocation' => $istorelocation,
                'iarea'          => $iarea,
                'bisaedit'       => $this->mmaster->bisaedit($istockopname,$istore,$istorelocation),
                'isi'            => $this->mmaster->baca($istockopname,$istore,$istorelocation)->row(),
                'detail'         => $this->mmaster->bacadetail($istockopname,$istore,$istorelocation)->result()
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vform', $data);
        }
    }

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $istockopname 	= $this->input->post('istockopname', TRUE);
		$dstockopname 	= $this->input->post('dstockopname', TRUE);
		/*if($dstockopname!=''){
			$tmp=explode("-",$dstockopname);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dstockopname=$th."-".$bl."-".$hr;
		}*/
		$iarea			 	= $this->input->post('iarea', TRUE);
		$istore				= $this->input->post('istore', TRUE);
		$istorelocation		= $this->input->post('istorelocation', TRUE);
		$istorelocationbin	= '00';
		$jml				= $this->input->post('jml', TRUE);
        if ((isset($dstockopname) && $dstockopname != '') && (isset($istore) && $istore != '') && (isset($istorelocation) && $istorelocation != '')){
            $this->db->trans_begin();
            $this->mmaster->updateheader($istockopname,$dstockopname,$istore,$istorelocation);
			for($i=1;$i<=$jml;$i++){
				$iproduct			= $this->input->post('iproduct'.$i, TRUE);
				$iproductgrade	= $this->input->post('iproductgrade'.$i, TRUE);
				$iproductmotif	= $this->input->post('iproductmotif'.$i, TRUE);
				$eproductname		= $this->input->post('eproductname'.$i, TRUE);
				$nstockopname		= $this->input->post('nstockopname'.$i, TRUE);
				$nstockopname	  	= str_replace(',','',$nstockopname);
				$this->mmaster->deletedetail($iproduct, $iproductgrade, $istockopname, $istore, $istorelocation, $istorelocationbin, $iproductmotif);
                if($nstockopname>0){
				    $this->mmaster->insertdetail($iproduct, $iproductgrade, $eproductname, $nstockopname,
                			         			$istockopname, $istore, $istorelocation, $istorelocationbin,$iproductmotif,$dstockopname,$iarea,$i);
            
                    $emutasiperiode='20'.substr($istockopname,3,4);
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                      $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nstockopname,$emutasiperiode);
                    }else{
                      $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nstockopname,$emutasiperiode);
                    }
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Stock Opname :'.$this->global['title'].' Kode : '.$istockopname);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update Stock Opname '.$istockopname
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
        $istockopname = $this->input->post('istockopname', TRUE);
        $istore   = $this->input->post('istore', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->delete($istockopname, $istore);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Stock Opname :'.$istore.' No:'.$istockopname);
            echo json_encode($data);
        }
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter         = [];
            $cari           = strtoupper($this->input->get('q'));
            $istore         = $this->uri->segment(4);
            $istorelocation = $this->uri->segment(5);
            $data           = $this->mmaster->getproduct($cari,$istore,$istorelocation);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name
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
        $data  = $this->mmaster->getdetailproduct($iproduct);
        echo json_encode($data->result_array());  
    }
}

/* End of file Cform.php */
