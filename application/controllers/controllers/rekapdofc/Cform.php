<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020214';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);

    }

    function datasupplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $data = $this->db->query("select i_supplier, e_supplier_name from tr_supplier 
                              where upper(e_supplier_name) like '%$cari%' or upper(i_supplier) like '%$cari%' order by i_supplier",false);
            foreach($data->result() as  $supplier){
                    $filter[] = array(
                    'id' => $supplier->i_supplier,  
                    'text' => $supplier->i_supplier.'-'.$supplier->e_supplier_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
      }

    function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $count      = $this->mmaster->total();
        $total      = $count->num_rows();

        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$yy.'-'.$mm.'-'.$dd;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$yy.'-'.$mm.'-'.$dd;
        
        echo $this->mmaster->data($dfrom,$dto,$isupplier,$total);
    }

    public function view(){
    	$dfrom = $this->input->post('dfrom');
        $dto   = $this->input->post('dto');
        $supplier = $this->input->post('isupplier');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'isupplier' => $supplier
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function rekap(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $jml  = $this->input->post('jml', TRUE);
        for($i=1;$i<=$jml;$i++){		
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $this->db->trans_begin();
                $i_supplier = $this->input->post('i_supplier'.$i, TRUE);
                $i_area = $this->input->post('i_area'.$i, TRUE);
                $i_spb = $this->input->post('i_spb'.$i, TRUE);
                $i_spmb = $this->input->post('i_spmb'.$i, TRUE);
                $d_do = $this->input->post('d_do'.$i, TRUE);
                $i_do = $this->input->post('i_do'.$i, TRUE);
                $i_op = $this->input->post('i_op'.$i, TRUE);
                $i_store = $this->input->post('i_area'.$i, TRUE);
                $i_store_location = $this->input->post('i_store_location'.$i, TRUE);
                $i_store_locationbin = '00';
                $d_spmb = $this->input->post('d_spmb'.$i, TRUE);
                $i_spmb_old = $this->input->post('i_spmb_old'.$i, TRUE);
                $detail = $this->mmaster->bacadetail3($i_do, $i_supplier);
                $d_sj = date('Y-m-d');
                $thbl = date('Ym');
                $vspbnetto = 0;
                $v_sjp = 0;
                $isjold = '';
                $isj	= $this->mmaster->runningnumbersj($i_area,$thbl);
    
                $this->mmaster->insertsjheader($i_spmb,$d_spmb,$isj,$d_sj,$i_area,$vspbnetto,$isjold);
                $a = 0;
    
                foreach ($detail as $row) {
                    $v_sjp = $v_sjp + ($row->n_deliver * $row->v_product_mill);
                    $a++;
                    $iproduct		= $row->i_product;
                    $iproductgrade	= $row->i_product_grade;
                    $iproductmotif  = $row->i_product_motif;
                    $eproductname	= $row->e_product_name;
                    $vunitprice		= $row->v_product_mill;
                    $ndeliver		= $row->n_deliver;
                    $norder		  	= $row->n_deliver;
                    $eremark  		= 'Dari DO FC '.$i_do;
                    if($eremark=='')$eremark=null;
                    if($norder>0){
                        $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,
						$vunitprice,$i_spmb,$d_spmb,$isj,$d_sj,$i_area,$i_store,$i_store_location,
						$i_store_location,$eremark,$a,$a);
					    $this->mmaster->updatespmbitem($i_spmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$i_area);
                        $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
                        if(isset($trans)){
                            foreach($trans as $itrans){
                                $q_aw =$itrans->n_quantity_stock;
                                $q_ak =$itrans->n_quantity_stock;
                                $q_in =0;
                                $q_out=0;
                                break;
                            }
                        }else{
                            $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00');
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
                        $this->mmaster->inserttrans($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$isj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                        $th=substr($d_sj,0,4);
                        $bl=substr($d_sj,5,2);
                        $emutasiperiode=$thbl;
                        $ada=$this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$emutasiperiode);
                        if($ada=='ada')
                        {
                            $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$emutasiperiode);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,'AA','01','00'))
                        {
                            $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$ndeliver,$q_ak);
                        }else{
                            $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,'AA','01','00',$eproductname,$ndeliver);
                        }
                    }
                }
        
                $this->db->query("update tm_sjp set v_sjp = '$v_sjp' where i_sjp = '$isj'");
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Rekap DO FC'.$this->global['title'].' Kode : '.$isj);
        
                    $data = array(
                        'sukses'    => true,
                        'kode'      => 'Rekap DO FC '.$isj
                    );
                }
                // END FOREACH
            }
            // end if ch on
        }
        // END FOR JML
        $this->load->view('pesan', $data);
    }	
  }
/* End of file Cform.php */
