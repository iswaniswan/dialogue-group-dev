<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070327';

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
            'area'         => $this->mmaster->bacaarea($username,$idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iarea     = $this->input->post('iarea');
        $dfrom	    = $this->input->post('dfrom');
        $dto	    = $this->input->post('dto');
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
        $iadj    = $this->uri->segment(4);
		$iarea   = $this->uri->segment(5);
		$dfrom   = $this->uri->segment(6);
		$dto 	 = $this->uri->segment(7);
        
        $query  = $this->mmaster->jumlah($iadj,$iarea);
        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'jmlitem'        => $query->num_rows(),
            'iadj'           => $iadj,
            'dfrom'          => $dfrom,
            'dto'            => $dto,
            'iarea'          => $iarea,
            'isi'            => $this->mmaster->baca($iadj,$iarea)->row(),
            'detail'         => $this->mmaster->bacadetail($iadj,$iarea)->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter         = [];
            $cari           = strtoupper($this->input->get('q'));
            $istore         = $this->uri->segment(4);
            $loc            = $this->uri->segment(5);
            $cari           = strtoupper($this->input->get('q'));
            $data           = $this->mmaster->getproduct($istore,$loc,$cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name." - ".$kuy->i_product_grade
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
        $iproductgrade = $this->input->post('iproductgrade');
        $data  = $this->mmaster->getdetailproduct($iproduct,$iproductgrade);
        echo json_encode($data->result_array());  
    }

    public function getdetailproductgrade(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $data  = $this->mmaster->getdetailproductgrade($iproduct);
        echo json_encode($data->result_array());  
    }

    

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iadj 		= $this->input->post('iadj', TRUE);
		$dadj 		= $this->input->post('dadj', TRUE);
        if($dadj!=''){
            $tmp=explode("-",$dadj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dadj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea		    = $this->input->post('iarea', TRUE);
        $istore		    = $this->input->post('istore', TRUE);
        $istorelocation = $this->input->post('istorelocation', TRUE);
        $eremark	    = $this->input->post('eremark', TRUE);
        $istockopname	= $this->input->post('istockopname', TRUE);
        $jml		      = $this->input->post('jml', TRUE);
        if($dadj!='' && $istockopname!='' && $eremark!='' && $iarea!=''){
            $this->db->trans_begin();
			$this->mmaster->updateheader($iadj, $iarea, $dadj, $istockopname, $eremark, $istore, $istorelocation);
			for($i=1;$i<=$jml;$i++){
			    $iproduct			  = $this->input->post('iproduct'.$i, TRUE);
			    $iproductgrade	= $this->input->post('grade'.$i, TRUE);
			    $iproductmotif	= $this->input->post('motif'.$i, TRUE);
			    $eproductname		= $this->input->post('eproductname'.$i, TRUE);
			    $nquantity   		= $this->input->post('nquantity'.$i, TRUE);
			    $eremark		  	= $this->input->post('eremark'.$i, TRUE);
			    if($nquantity!=0){
                    $query = $this->mmaster->bacaitem($iadj,$iarea,$iproduct,$iproductgrade,$iproductmotif);
     			    // $query = $this->db->query("select * from tm_adj_item where i_adj='$iadj' and i_area='$iarea' and i_product='$iproduct'
     			    //                        and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
                    if($query->num_rows()>0){
				      $this->mmaster->updatedetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i);
				    }else{
				      $this->mmaster->insertdetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i);
    				}
				}else{
				  $this->mmaster->deletedetail($iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade);				  
				}
			}
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Adjustment :'.$this->global['title'].' Kode : '.$iadj);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update Adjustment '.$iadj
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

        $iadj	= $this->input->post('iadj');
        $iarea	= $this->input->post('iarea');
        $this->db->trans_begin();
        $this->mmaster->delete($iadj,$iarea);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Adjustment '.$iadj);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
