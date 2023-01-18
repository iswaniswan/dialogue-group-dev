<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070411';

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
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->bacaarea($username,$idcompany);
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        $query = $this->db->query("
                              select
                                 a.i_customer,
                                 a.i_area,
                                 a.e_spg_name,
                                 a.i_user,
                                 b.e_area_name,
                                 c.e_customer_name 
                              from
                                 tr_spg a,
                                 tr_area b,
                                 tr_customer c 
                              where
                                 upper(a.i_spg) = '$username' 
                                 and a.i_area = '$iarea' 
                                 and a.i_area = b.i_area 
                                 and a.i_customer = c.i_customer
                              ", FALSE);
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $icustomer = $kuy->i_customer; 
            $iuser     = $kuy->i_user;
        }else{
          $icustomer = '';
          $iuser     = '';
        }
        //$icustomer = $this->mmaster->bacacustomer($username,$iarea);
        echo $this->mmaster->data($iuser,$dfrom,$dto,$iarea,$icustomer,$this->global['folder']);
    }
    
    public function view(){
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea = $this->mmaster->bacaarea($username,$idcompany);

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
            $isopb		= $this->uri->segment(4);
            $icustomer  = $this->uri->segment(5);
            $dfrom	 	= $this->uri->segment(6);
            $dto		= $this->uri->segment(7);
            $query = $this->db->query("	select * from tm_sopb_item where i_sopb = '$isopb' and i_customer = '$icustomer'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'isopb'          => $isopb,
                'icustomer'      => $icustomer,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'isi'            => $this->mmaster->baca($isopb,$icustomer)->row(),
                'detail'         => $this->mmaster->bacadetail($isopb,$icustomer)->result()
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
        $istockopname 	= $this->input->post('isopb', TRUE);
        $dstockopname 	= $this->input->post('dsopb', TRUE);
        if($dstockopname!=''){
            $tmp=explode("-",$dstockopname);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dstockopname=$th."-".$bl."-".$hr;
        }
        $icustomer	= $this->input->post('icustomer');
        $iarea			= $this->input->post('iarea');
        $ispg 			= $this->input->post('ispg');
        $jml	  		= $this->input->post('jml');
        if ((isset($dstockopname) && $dstockopname != '') && (isset($icustomer) && $icustomer != '') && (isset($ispg) && $ispg != '')){
            $this->db->trans_begin();
            $this->mmaster->updateheader($istockopname,$dstockopname,$icustomer);
			for($i=1;$i<=$jml;$i++){
				$iproduct			  = $this->input->post('iproduct'.$i, TRUE);
				$iproductgrade	= $this->input->post('iproductgrade'.$i, TRUE);
				$iproductmotif	= $this->input->post('iproductmotif'.$i, TRUE);
				$eproductname		= $this->input->post('eproductname'.$i, TRUE);
				$nstockopname		= $this->input->post('nstockopname'.$i, TRUE);
				$nstockopname	  = str_replace(',','',$nstockopname);
				$this->mmaster->deletedetail($iproduct, $iproductgrade, $istockopname, $icustomer, $iproductmotif);
                if($nstockopname>0){
				    $this->mmaster->insertdetail($iproduct, $iproductgrade, $eproductname, $nstockopname,$istockopname, $icustomer,$iproductmotif,$dstockopname,$iarea,$i);
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
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
                    $emutasiperiode='20'.substr($istockopname,3,4);
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode)){
                      $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nstockopname,$emutasiperiode);
                    }else{
                      $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nstockopname,$emutasiperiode);
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
        $isopb      = $this->input->post('isopb', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->delete($isopb, $icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Stock Opname :'.$isopb.' No:'.$icustomer);
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
