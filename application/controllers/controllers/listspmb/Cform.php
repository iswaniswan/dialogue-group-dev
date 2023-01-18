<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070312';

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
            'gudang'         => $this->mmaster->bacastore($username,$idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $istore     = $this->input->post('istore');
        $dfrom	    = $this->input->post('dfrom');
        $dto	    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        if($istore==''){
            $istore=$this->uri->segment(6);
        } 
        // $ispb = $this->mmaster->bacaspb($dfrom,$dto,$istore);
        // $spb=json_encode($ispb); 
        echo $this->mmaster->data($dfrom,$dto,$istore,$this->global['folder']);
    }
    
    public function view(){
    	$istore  = $this->input->post('istore');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        if($istore==''){
            $istore=$this->uri->segment(6);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bisaedit'      => false,
            'istore'         => $istore
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
            $ispmb  = $this->uri->segment(4);
			$iarea  = $this->uri->segment(5);
			$dfrom  = $this->uri->segment(6);
			$dto 	= $this->uri->segment(7);
			$peraw 	= $this->uri->segment(8);
            $perak 	= $this->uri->segment(9);
            $username  = $this->session->userdata('username');
            $idcompany = $this->session->userdata('id_company'); 
            $query  = $this->db->query("select i_product from tm_spmb_item where i_spmb='$ispmb'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'ispmb'          => $ispmb,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'iarea'          => $iarea,
                'peraw'          => $peraw,
                'perak'          => $perak,
                'area'           => $this->mmaster->bacaarea($username,$idcompany),
                'isi'            => $this->mmaster->baca($ispmb)->row(),
                'detail'         => $this->mmaster->bacadetail($ispmb)->result()
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter         = [];
            $cari           = strtoupper($this->input->get('q'));
            $data           = $this->mmaster->getproduct($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name." - ".$kuy->i_product_motif
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

    public function getdetailrata(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $iproductmotif = $this->input->post('iproductmotif');
        $peraw         = $this->input->post('peraw');
        $perak         = $this->input->post('perak');
        $iarea         = $this->input->post('iarea');
        $fperaw        = 'FP-'.$peraw;
        $fperak        = 'FP-'.$perak;
        $data  = $this->mmaster->getdetailrata($iproduct,$iproductmotif,$iarea,$fperaw,$fperak);
        echo json_encode($data->result_array());
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ispmb 		= $this->input->post('ispmb', TRUE);
		$ispmbold 	= $this->input->post('ispmbold', TRUE);
		$dspmb 		= $this->input->post('dspmb', TRUE);
      	$eremark	= $this->input->post('eremark', TRUE);
        if($dspmb!=''){
            $tmp=explode("-",$dspmb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspmb=$th."-".$bl."-".$hr;
        }
        $iarea		= $this->input->post('iarea', TRUE);
        $eareaname	= $this->input->post('eareaname', TRUE);
        $jml		= $this->input->post('jml', TRUE);
        $fop		= 'f';
        $nprint		= 0;
        if($dspmb!='' && $eareaname!=''){
            $this->mmaster->updateheader($ispmb, $dspmb, $iarea, $ispmbold, $eremark);
			for($i=1;$i<=$jml;$i++){
			    $iproduct		  	= $this->input->post('iproduct'.$i, TRUE);
			    $iproductgrade	    = 'A';
			    $iproductmotif	    = $this->input->post('iproductmotif'.$i, TRUE);
			    $eproductname		= $this->input->post('eproductname'.$i, TRUE);
			    $vunitprice	  	    = $this->input->post('vproductmill'.$i, TRUE);
			    $vunitprice 		= str_replace(',','',$vunitprice);
			    $norder   			= $this->input->post('norder'.$i, TRUE);
                $nacc 	    		= $this->input->post('nacc'.$i, TRUE);
				$eremark		  	= $this->input->post('eremark'.$i, TRUE);
				$this->mmaster->deletedetail( $iproduct,$iproductgrade,$ispmb,$iproductmotif);
				if($norder>0){
				  $this->mmaster->insertdetail( $ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$nacc,$vunitprice,$iproductmotif,$eremark,$iarea,$i);
				}
			}
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update SPMB :'.$this->global['title'].' Kode : '.$ispmb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update SPMB :  '.$ispmb
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

        $ispmb	= $this->input->post('ispmb');
        $this->db->trans_begin();
        $this->mmaster->delete($ispmb);
        $this->mmaster->deleterekap($ispmb);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SPMB '.$ispmb);
            echo json_encode($data);
        }
    }

    public function deletedetail(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispmb			= $this->input->post('ispmb', TRUE);
		$iproduct		= $this->input->post('iproduct', TRUE);
		$iproductgrade	= $this->input->post('iproductgrade', TRUE);
		$iproductmotif	= $this->input->post('iproductmotif', TRUE);

        $this->db->trans_begin();
        $this->mmaster->deletedetail($iproduct, $iproductgrade, $ispmb, $iproductmotif);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Item SPMB '.$ispmb);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
