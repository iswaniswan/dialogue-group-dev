<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10207';

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
            'ibbm'      => '',
            'detail'    => '',
            'jmlitem'   => ''
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data); 
    }

    function data_ttb(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*, b.e_customer_name, c.e_area_name, d.e_salesman_name 
                                from tm_ttbretur a, tr_customer b, tr_area c, tr_salesman d
                                where a.i_customer=b.i_customer and (a.i_ttb like '%$cari%') and a.i_area=c.i_area and b.i_area=c.i_area
                                and not a.d_receive1 is NULL
                                and a.i_salesman=d.i_salesman and a.f_ttb_cancel='f' and a.i_bbm isnull",false);
            $data = $this->db->get();
            foreach($data->result() as  $ittb){
                    $filter[] = array(
                    'id' => $ittb->i_ttb,  
                    'text' => $ittb->i_ttb
                    );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function get_ttb(){
        header("Content-Type: application/json", true);
        $ittb = $this->input->post('i_ttb');
        $ittb = trim($ittb);
        $this->db->select("a.*, c.e_area_name, b.e_customer_name, d.e_salesman_name 
                            from tm_ttbretur a, tr_customer b, tr_area c, tr_salesman d 
                            where a.i_customer=b.i_customer and UPPER(a.i_ttb) = '$ittb' and a.i_area=c.i_area
                            and b.i_area=c.i_area and not a.d_receive1 is NULL and
                            a.i_salesman=d.i_salesman and a.f_ttb_cancel='f' and a.i_bbm isnull",false);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function view(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ittb           = $this->input->post('ittb', TRUE);
        $ittb           = trim($ittb);
        $iarea          = $this->input->post('iarea', TRUE);
        $eareaname      = $this->input->post('eareaname', TRUE); 
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomername', TRUE);
        $isalesman      = $this->input->post('isalesman', TRUE);
        $esalesmanname  = $this->input->post('esalesmanname', TRUE);
        $dttb           = $this->input->post('dttb', TRUE);
        $thn            = substr($dttb,0,4);

        $query   = $this->db->query("select a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name, b.*
                                    from tr_product_motif a,tr_product c, tm_ttbretur_item b
                                    where trim(b.i_ttb)='$ittb' and b.i_area='$iarea' and n_ttb_year='$thn'
                                    and a.i_product=c.i_product and b.i_product1=a.i_product and b.i_product1_motif=a.i_product_motif");

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'data'       => $this->mmaster->bacattb($ittb)->row(),
            'data1'      => $this->mmaster->bacattbdetail($ittb, $iarea, $thn)->result(),
            'jmlitem'    => $query->num_rows()
        );
        $this->Logger->write('Membuka Menu Input Item '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vforminput', $data);
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
        $this->db->select("a.e_product_name, b.e_product_motifname, b.i_product_motif, c.i_product_grade, c.v_product_retail");
        $this->db->from("tr_product a");
        $this->db->join("tr_product_motif b","a.i_product = b.i_product");
        $this->db->join("tr_product_price c","a.i_product = c.i_product");
        $this->db->where("UPPER(a.i_product)", $iproduk);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function simpan(){
        $data = check_role($this->i_menu, 1);
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
            $thbl=$th.$bl;
        }
        $dttb 	= $this->input->post('dttb', TRUE);
        if($dttb!=''){
            $tmp=explode("-",$dttb);
            $thttb=$tmp[0];
            $bl=$tmp[1];
            $hr=$tmp[2];
            $dttb=$thttb."-".$bl."-".$hr;
        }
        $icustomer	= $this->input->post('icustomer', TRUE);
		$iarea		= $this->input->post('iarea', TRUE);
        $ittb		= $this->input->post('ittb', TRUE);
        $ittb       = trim($ittb);
		$isalesman	= $this->input->post('isalesman',TRUE);
        $jml		= $this->input->post('jml', TRUE);

        if(($ibbm=='') && ($icustomer!='') && ($dbbm!='')  && ($ittb!='') && ($dttb!='') && ($isalesman!='') && (($jml!='')&&($jml!='0'))){
            $this->db->trans_begin();
			$ibbm				= $this->mmaster->runningnumberbbm($thbl,$iarea);
			$istore				= 'AA';
			$istorelocation		= '01';
			$istorelocationbin	= '00';
			$eremark			= 'TTB Retur';
            $ibbmtype			= '05';
            $this->mmaster->insertbbmheader($ittb,$dttb,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea,$isalesman);
			$this->mmaster->updatettbheader($ittb,$thttb,$iarea,$ibbm,$dbbm);
				for($i=1;$i<=$jml;$i++){
                    $iproduct1				= $this->input->post('iproduct'.$i, TRUE);
					$iproductgrade1			= $this->input->post('iproductgrade'.$i, TRUE);
					$iproductmotif1			= $this->input->post('iproductmotif'.$i, TRUE);
					$eproductname1			= $this->input->post('eproductname'.$i, TRUE);
					$vunitprice1			= $this->input->post('vunitprice'.$i, TRUE);
					$vunitprice1			= str_replace(',','',$vunitprice1);
					$nttb					= $this->input->post('nttb'.$i, TRUE);
				  	$iproduct2				= $this->input->post('iproductx'.$i, TRUE);
					$iproductgrade2			= $this->input->post('iproductgradex'.$i, TRUE);
					$iproductmotif2			= $this->input->post('iproductmotifx'.$i, TRUE);
					$eproductname2			= $this->input->post('eproductnamex'.$i, TRUE);
					$vunitprice2			= $this->input->post('vunitpricex'.$i, TRUE);
					$vunitprice2			= str_replace(',','',$vunitprice2);
					$nbbm					= $this->input->post('nbbm'.$i, TRUE);
					$inota					= $this->input->post('inota'.$i, TRUE);
					$this->mmaster->updatettbdetail($iproduct1,$iproductgrade1,$iproductmotif1,$nttb,
													$iproduct2,$iproductgrade2,$iproductmotif2,$nbbm,
                                                    $ittb,$thttb,$iarea,$inota);
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
                    $this->mmaster->insertbbmdetail($iproduct2,$iproductgrade2,$eproductname2,$iproductmotif2,$nbbm,$vunitprice2,$ittb,$ibbm,$eremark,$dttb,$ibbmtype,$i,$dbbm);
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Nomor BBM'.$this->global['title'].' Kode : '.$ibbm);

                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ibbm
                    );
                }
                
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */
