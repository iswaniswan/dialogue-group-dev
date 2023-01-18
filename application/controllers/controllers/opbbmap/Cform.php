<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040103';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }
    
   public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if($this->uri->segment(5)){
            $ispb 			    = $this->uri->segment(4);
			$iarea			    = $this->uri->segment(5);
			$tmp                = explode('-',$ispb);
            if($tmp[0]=='SPB'){
                $query 	= $this->db->query("select a.* from tm_spb_item a, tr_product b
                                            where a.i_spb = '$ispb'
                                            and a.i_area='$iarea'
                                            and a.i_product=b.i_product
                                            and a.n_deliver<a.n_order
                                            and a.i_op isnull");
            }else if($tmp[0]=='SPMB'){
                $query 	= $this->db->query("select a.* from tm_spmb_item a, tr_product b
                                            where a.i_spmb = '$ispb'
                                            and a.i_product=b.i_product
                                            and a.n_deliver<a.n_order
                                            and a.i_op isnull");
                $data['ispmbold'] = $ispb;
            }

            $data = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'jmlitem'       => $query->num_rows(),
                'iop'           => '',
                'ispb'          => $ispb,
                'isi'           => $this->mmaster->baca($ispb,$iarea),
                'detail'        => $this->mmaster->bacadetail($ispb,$iarea),
                'opstatus'      => $this->mmaster->getop()->result(),
                'supplier'      => $this->mmaster->getsupplier()->result()
            );
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
            $this->load->view('opbbmap/vformedit',$data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iop		= $this->input->post('iop', TRUE);
        $iopold		= $this->input->post('iopold', TRUE);
        $isupplier 	= $this->input->post('isupplier', TRUE);
        $iarea 		= $this->input->post('iarea', TRUE);
        $iopstatus 	= $this->input->post('iopstatus', TRUE);
        $ireff		= $this->input->post('ispb', TRUE);
        $dop		= $this->input->post('dop', TRUE);
        $old		= $this->input->post('asal', TRUE);

        if($dop!=''){
            $tmp=explode("-",$dop);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dop=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
		}
        $dreff		= $this->input->post('dspb', TRUE);
        if($dreff!=''){
            $tmp=explode("-",$dreff);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dreff=$th."-".$bl."-".$hr;
        }

        $eopremark		= $this->input->post('eopremark', TRUE);
        if($eopremark=='')
            $eopremark=null;
            $ndeliverylimit	= $this->input->post('ndeliverylimit', TRUE);
            $ntoplength		= $this->input->post('nsuppliertoplength', TRUE);
            $jml			= $this->input->post('jml', TRUE);
            if(($isupplier!='') && ($iopstatus!='') && ($dop!='')){
				$iop	= $this->mmaster->runningnumber($thbl);
				$this->mmaster->insertheader($iop, $dop, $isupplier, $iarea, $iopstatus, $ireff, 
							     $eopremark, $ndeliverylimit, $ntoplength, $dreff, $old, $iopold);
				for($i=1;$i<=$jml;$i++){
                  $norder				= $this->input->post('norder'.$i, TRUE);
                  $iproduct			= $this->input->post('iproduct'.$i, TRUE);
				  if($norder!='0'){
					$iproductgrade		= 'A';
					$iproductmotif		= $this->input->post('motif'.$i, TRUE);
					$eproductname		= $this->input->post('eproductname'.$i, TRUE);
					$vproductmill		= $this->input->post('vproductmill'.$i, TRUE);
					$vproductmill		= str_replace(',','',$vproductmill);
					$norder			    = $this->input->post('norder'.$i, TRUE);
					$nquantitystock		= $this->input->post('nquantitystock'.$i, TRUE);
					$this->mmaster->insertdetail( $iop,$iproduct,$iproductgrade,$eproductname,$norder,
												$vproductmill,$iproductmotif,$i);
					$this->mmaster->updatespb($ireff,$iop,$iproduct,$iproductgrade,$iproductmotif,$nquantitystock,$iarea);
				  }
				}
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
				}else{
					$this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iop);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iop
                    );
                }
            }else{
                $data = array(
                    'sukses' => false
                );
            }
            $this->load->view('pesan', $data); 
    }

    /*function editop(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');

            if(($this->uri->segment(4)) && ($this->uri->segment(5))){
                $ispb       = $this->uri->segment(4);
                $iop        = $this->uri->segment(5);
                $isupplier  = $this->uri->segment(6);
                $area       = $this->uri->segment(7);
                $dfrom      = $this->uri->segment(8);
                $dto        = $this->uri->segment(9);
                $query      = $this->db->query("select * from tm_op_item where i_op = '$iop'");
                
                $data = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Edit ".$this->global['title'],
                    'title_list'    => 'List '.$this->global['title'],
                    'jmlitem'       => $query->num_rows(),
                    'iop'           => $iop,
                    'ispb'          => $ispb,
                    'supplier'      => $isupplier,
                    'isi'           => $this->mmaster->bacaop($iop,$iarea),
                    'detail'        => $this->mmaster->bacadetailop($iop,$iarea),
                    'opstatus'      => $this->mmaster->getop()->result(),
                    'dfrom'         => $dfrom,
                    'dto'           => $dto
                );
                $this->load->view('opnew/vformupdate',$data);
            }
        }
        
    }

    function updateop(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');

            $iop        = $this->input->post('iop', TRUE);
            $iopold     = $this->input->post('iopold', TRUE);
            $isupplier  = $this->input->post('isupplier', TRUE);
            $iarea      = $this->input->post('iarea', TRUE);
            $iopstatus  = $this->input->post('iopstatus', TRUE);
            $ireff      = $this->input->post('ispb', TRUE);
            $dop        = $this->input->post('dop', TRUE);
            $old        = $this->input->post('asal', TRUE);

            if($dop!=''){
                $tmp=explode("-",$dop);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dop=$th."-".$bl."-".$hr;
            }
            $dreff      = $this->input->post('dspb', TRUE);
            if($dreff!=''){
                $tmp=explode("-",$dreff);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dreff=$th."-".$bl."-".$hr;
            }

            $eopremark     = $this->input->post('eopremark', TRUE);
            if($eopremark=='')
            $eopremark=null;
            $ndeliverylimit = $this->input->post('ndeliverylimit', TRUE);
            $ntoplength     = $this->input->post('ntoplength', TRUE);
            $jml            = $this->input->post('jml', TRUE);

            if(($isupplier!='') && ($iopstatus!='') && ($dop!='')){
                $benar      = 'false';
                $this->mmaster->updateheader($iop, $dop, $isupplier, $iarea, $iopstatus, $ireff,
                $eopremark, $ndeliverylimit, $ntoplength, $dreff, $old, $iopold);
                for($i=1;$i<=$jml;$i++){
                    $norder                 =$this->input->post('norder'.$i, TRUE);
                    if($norder!='0'){
                     $iproduct              =$this->input->post('iproduct'.$i, TRUE);
                     $iproductgrade         ='A';
                     $iproductmotif         =$this->input->post('motif'.$i, TRUE);
                     $eproductname          =$this->input->post('eproductname'.$i, TRUE);
                     $vproductmill          =$this->input->post('vproductmill'.$i, TRUE);
                     $vproductmill          =str_replace(',','',$vproductmill);
                     $norder                =$this->input->post('norder'.$i, TRUE);
                     $data = array(
                        'iproduct'      => $iproduct,
                        'iproductgrade' => $iproductgrade,
                        'iproductmotif' => $iproductmotif,
                        'eproductname'  => $eproductname,
                        'vproductmill'  => $vproductmill,
                        'norder'        => $norder
                     );
                     $this->mmaster->deletedetail($iproduct, $iproductgrade, $iop, $iproductmotif);
                     $this->mmaster->insertdetail( $iop,$iproduct,$iproductgrade,$eproductname,$norder,$vproductmill,$iproductmotif,$i);
                     $this->mmaster->updatespb($ireff,$iop,$iproduct,$iproductgrade,$iproductmotif,$iarea,$norder);
                    }
                }
            }
            $benar='true';
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iop);

                $data = array(
                    'sukses'    => true,
                    'kode'      => $iop
                );
            }
        }
    }*/

    public function getopstatus(){
        $iopstatus = $this->input->post('iopstatus');
        $query = $this->mmaster->getop($iopstatus);
        if($query->num_rows()>0) {
            $c  = "";
            $opstatus = $query->result();
            foreach($opstatus as $row) {
                $iopstatus      = $row->i_op_status;
                $eopstatusname  = $row->e_op_statusname;
            }
            echo json_encode(array(
                'iopstatus'        => $iopstatus,
                'eopstatusname'    => $eopstatusname
            ));
        }
    }

    public function getsupplier(){
        header("Content-Type: application/json", true);
        $isupplier = $this->input->post('i_supplier');
        $this->db->select("i_supplier, e_supplier_name, i_supplier_group, n_supplier_toplength");
        $this->db->from("tr_supplier");
        $this->db->where("UPPER(i_supplier)", $isupplier);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }
}
/* End of file Cform.php */
