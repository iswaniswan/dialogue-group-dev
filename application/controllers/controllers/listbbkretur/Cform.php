<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070324';

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
        $dfrom	    = $this->input->post('dfrom');
        $dto	    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $departemen  = $this->mmaster->cekdepartemen($username,$idcompany);
        echo $this->mmaster->data($dfrom,$dto,$departemen,$this->global['folder']);
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibbk   = $this->uri->segment(4);
		$dfrom  = $this->uri->segment(5);
		$dto    = $this->uri->segment(6);
        
        $query  = $this->mmaster->jumlah($ibbk);
        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'jmlitem'        => $query->num_rows(),
            'ibbk'           => $ibbk,
            'dfrom'          => $dfrom,
            'dto'            => $dto,
            'isi'            => $this->mmaster->baca($ibbk)->row(),
            'detail'         => $this->mmaster->bacadetail($ibbk)->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
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

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibbkretur = $this->input->post('ibbkretur', TRUE);
		$dbbkretur = $this->input->post('dbbkretur', TRUE);
		if($dbbkretur!=''){
			$tmp=explode("-",$dbbkretur);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dbbkretur=$th."-".$bl."-".$hr;
			$thbl=$th.$bl;
		}
		$isupplier		= $this->input->post('isupplier', TRUE);
		$esuppliername  = $this->input->post('esuppliername', TRUE);
		$eremark	    = $this->input->post('eremark', TRUE);
		$vbbkretur	    = $this->input->post('vtotal', TRUE);
		$vbbkretur  	= str_replace(',','',$vbbkretur);
  		$jml		    = $this->input->post('jml', TRUE);
        if($dbbkretur!='' && $esuppliername!='' && $jml!='' && $jml!='0'){
            $this->db->trans_begin();
			$istore			    = 'AA';
			$istorelocation		= '01';
			$istorelocationbin  = '00';
			$this->mmaster->updateheader($ibbkretur, $dbbkretur, $isupplier, $eremark, $vbbkretur);
			for($i=1;$i<=$jml;$i++){
                $iproduct	    = $this->input->post('iproduct'.$i, TRUE);
				$iproductgrade	= 'A';
				$iproductmotif	= $this->input->post('motif'.$i, TRUE);
				$eproductname   = $this->input->post('eproductname'.$i, TRUE);
				$vunitprice		= $this->input->post('vunitprice'.$i, TRUE);
				$vunitprice	  	= str_replace(',','',$vunitprice);
				$nquantity      = $this->input->post('nquantity'.$i, TRUE);
				$xnquantity     = $this->input->post('xnquantity'.$i, TRUE);
				$eremark		= $this->input->post('eremark'.$i, TRUE);
				if($xnquantity>0){
  				    $this->mmaster->deletedetail($ibbkretur, $iproduct, $iproductmotif, $iproductgrade);
                    $th=substr($dbbkretur,0,4);
				    $bl=substr($dbbkretur,5,2);
				    $emutasiperiode=$th.$bl;
				    $tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ibbkretur,$xnquantity,$eproductname);
		            $this->mmaster->updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$xnquantity,$emutasiperiode);
		            $this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$xnquantity);
                }

                $this->mmaster->insertdetail($ibbkretur,$isupplier,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$vunitprice,$eremark,$i,$thbl);
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
                $this->mmaster->inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbkretur,$q_in,$q_out,$nquantity,$q_aw,$q_ak);
                $th=substr($dbbkretur,0,4);
                $bl=substr($dbbkretur,5,2);
                $emutasiperiode=$th.$bl;
                if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode))
                {
                  $this->mmaster->updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                }else{
                  $this->mmaster->insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                }
                if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin))
                {
                  $this->mmaster->updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$q_ak);
                }else{
                  $this->mmaster->inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nquantity);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update BBK Retur :'.$this->global['title'].' Kode : '.$ibbkretur);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update BBK Retur : '.$ibbkretur
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

        $ibbkretur	= $this->input->post('ibbk');
        $this->db->trans_begin();
        $this->mmaster->delete($ibbkretur);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel BBK Retur '.$ibbkretur);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
