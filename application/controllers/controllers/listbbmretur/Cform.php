<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070317';

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
        // $ispb = $this->mmaster->bacaspb($dfrom,$dto,$istore);
        // $spb=json_encode($ispb); 
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
            // 'bisaedit'      => false,
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
        $ibbm       = $this->uri->segment(4);
		$ittb	    = $this->uri->segment(5);
		$nyear	    = $this->uri->segment(6);
        $iarea	    = $this->uri->segment(7);
        $area       = $this->uri->segment(8);
        $dfrom      = $this->uri->segment(9);
        $dto        = $this->uri->segment(10);
        
        $query  = $this->mmaster->jumlah($ibbm);
        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'jmlitem'        => $query->num_rows(),
            'ibbm'           => $ibbm,
            'dfrom'          => $dfrom,
            'dto'            => $dto,
            'iarea'          => $iarea,
            'nyear'          => $nyear,
            'ittb'           => $ittb,
            //'area'           => $this->mmaster->bacaarea($username,$idcompany),
            'isi'            => $this->mmaster->baca($ibbm)->row(),
            'detail'         => $this->mmaster->bacadetail($ibbm)->result(),
            'detail2'        => $this->mmaster->bacadetail2($ittb,$iarea,$nyear)->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product,e_product_name");
            $this->db->from("tr_product ");
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

    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduk = $this->input->post('i_product');
        $iproduk = trim($iproduk);
        $this->db->select("a.i_product,a.e_product_name, b.e_product_motifname, b.i_product_motif, c.i_product_grade, c.v_product_retail");
        $this->db->from("tr_product a");
        $this->db->join("tr_product_motif b","a.i_product = b.i_product");
        $this->db->join("tr_product_price c","a.i_product = c.i_product");
        $this->db->where("UPPER(a.i_product)", $iproduk);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibbm 	= $this->input->post('ibbm', TRUE);
        $dbbm 	= $this->input->post('dbbm', TRUE);
        if($dbbm!=''){
            $tmp=explode("-",$dbbm);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbbm=$th."-".$bl."-".$hr;
        }
        $dttb 	= $this->input->post('dttb', TRUE);
        if($dttb!=''){
            $tmp=explode("-",$dttb);
            $thttb=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dttb=$thttb."-".$bl."-".$hr;
        }
        $icustomer	= $this->input->post('icustomer', TRUE);
        $iarea		= $this->input->post('iarea', TRUE);
        $ittb		= $this->input->post('ittb', TRUE);
        $isalesman	= $this->input->post('isalesman',TRUE);
        $jml		= $this->input->post('jml', TRUE);
        if(($ibbm!='')
            && ($icustomer!='')
            && ($dbbm!='') 
            && ($ittb!='')
            && ($dttb!='')
            && ($isalesman!='')
            && (($jml!='')&&($jml!='0'))){
            $this->db->trans_begin();
			$istore						= 'AA';
			$istorelocation		= '01';
			$istorelocationbin= '00';
			$eremark					= 'TTB Retur';
			$ibbmtype					= '05';
			$this->mmaster->deletebbmheader($ibbm);
			$this->mmaster->insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman);
			$this->mmaster->updatettbheader($ittb,$thttb,$iarea,$ibbm,$dbbm);
			for($i=1;$i<=$jml;$i++){
                $iproduct1			= $this->input->post('iproduct'.$i, TRUE);
				$iproductgrade1		= $this->input->post('iproductgrade'.$i, TRUE);
				$iproductmotif1		= $this->input->post('iproductmotif'.$i, TRUE);
				$eproductname1		= $this->input->post('eproductname'.$i, TRUE);
				$vunitprice1		= $this->input->post('vunitprice'.$i, TRUE);
				$vunitprice1		= str_replace(',','',$vunitprice1);
				$nttb				= $this->input->post('nttb'.$i, TRUE);
				$iproduct2			= $this->input->post('iproductx'.$i, TRUE);
				$iproductgrade2		= $this->input->post('iproductgradex'.$i, TRUE);
				$iproductmotif2		= $this->input->post('iproductmotifx'.$i, TRUE);
				$eproductname2		= $this->input->post('eproductnamex'.$i, TRUE);
				$vunitprice2		= $this->input->post('vunitpricex'.$i, TRUE);
				$vunitprice2		= str_replace(',','',$vunitprice2);
				$nbbm				= $this->input->post('nbbm'.$i, TRUE);
                $nquantityx			= $this->input->post('nquantityx'.$i, TRUE);
				$inota				= $this->input->post('inota'.$i, TRUE);
			  	$iproductxxx		= $this->input->post('iproductxxx'.$i, TRUE);
				$iproductgradexxx	= $this->input->post('iproductgradexxx'.$i, TRUE);
                $iproductmotifxxx	= $this->input->post('iproductmotifxxx'.$i, TRUE);
                // var_dump($iproductxxx);
                // var_dump($iproductgradexxx);
                // var_dump($iproductmotifxxx);
				$this->mmaster->deletebbmdetail($iproductxxx,$iproductgradexxx,$iproductmotifxxx,$ibbm,$ibbmtype);
############
                $th=substr($dbbm,0,4);
				$bl=substr($dbbm,5,2);
				$emutasiperiode=$th.$bl;
				$tra=$this->mmaster->deletetrans($iproductxxx,$iproductgradexxx,$iproductmotifxxx,$istore,$istorelocation,$istorelocationbin,$ibbm,$nquantityx,$eproductname2);
                if( ($nquantityx!='') && ($nquantityx!=0) ){
			        $this->mmaster->updatemutasi04($iproductxxx,$iproductgradexxx,$iproductmotifxxx,$istore,$istorelocation,$istorelocationbin,$nquantityx,$emutasiperiode);
			        $this->mmaster->updateic04($iproductxxx,$iproductgradexxx,$iproductmotifxxx,$istore,$istorelocation,$istorelocationbin,$nquantityx);
                }

                $trans=$this->mmaster->lasttrans($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin);
                if(isset($trans)){
                    foreach($trans as $itrans){
                        $q_aw =$itrans->n_quantity_awal;
                        $q_ak =$itrans->n_quantity_akhir;
                        $q_in =$itrans->n_quantity_in;
                        $q_out=$itrans->n_quantity_out;
                        break;
                    }
                }else{
                    $trans=$this->mmaster->qic($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin);
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

                $this->mmaster->inserttrans4($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin,$eproductname2,$ibbm,$q_in,$q_out,$nbbm,$q_aw,$q_ak);
                $th=substr($dbbm,0,4);
                $bl=substr($dbbm,5,2);
                $emutasiperiode=$th.$bl;
                if($this->mmaster->cekmutasi($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                    $this->mmaster->updatemutasi4($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin,$nbbm,$emutasiperiode);
                }else{
                    $this->mmaster->insertmutasi4($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin,$nbbm,$emutasiperiode);
                }
                if($this->mmaster->cekic($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin)){
                    $this->mmaster->updateic4($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin,$nbbm,$q_ak);
                }else{
                    $this->mmaster->insertic4($iproduct2,$iproductgrade2,$iproductmotif2,$istore,$istorelocation,$istorelocationbin,$eproductname2,$nbbm);
                }
                $this->mmaster->insertbbmdetail($iproduct2,$iproductgrade2,$eproductname2,$iproductmotif2,$nbbm,
											    $vunitprice2,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$i,$dbbm);
				$this->mmaster->updatettbdetail($iproduct1,$iproductgrade1,$iproductmotif1,$nttb,
												$iproduct2,$iproductgrade2,$iproductmotif2,$nbbm,
												$ittb,$thttb,$iarea,$inota);
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update BBM Retur :'.$this->global['title'].' Kode : '.$ibbm);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update BBM Retur '.$ibbm
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

        $ibbm	= $this->input->post('ibbm');
        $ittb	= $this->input->post('ittb');
        $iarea	= $this->input->post('iarea');
        $tahun	= $this->input->post('tahun');
        $this->db->trans_begin();
        $this->mmaster->delete($ibbm,$ittb,$iarea,$tahun);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel BBM Retur '.$ibbm);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
