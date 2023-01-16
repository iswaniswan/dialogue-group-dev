<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '103085';

    public function __construct()
    {
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
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function datasjpb(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query(" select a.i_sjpb, a.i_customer,b.e_customer_name, a.d_sjpb, a.d_sjpb_receive, a.i_spb  
                                from tm_sjpb  a, tr_customer b
                                where a.i_customer = b.i_customer
                                and b.e_customer_name like 'CLANDY%'
                                and not a.d_sjpb_receive isnull
                                and a.d_sjpb >= '2019-05-01'
                                and (a.i_sjpb like '%$cari%' or a.i_customer like '%$cari%' or b.e_customer_name like '%$cari%')
                                --and a.i_spb isnull
                                order by a.d_sjpb_receive desc");
            foreach($data->result() as  $sjpb){
                    $filter[] = array(
                    'id' => $sjpb->i_sjpb,  
                    'text' => $sjpb->i_sjpb
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datakodeharga(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query(" select i_price_group from tr_product_priceco
                                where i_price_group like '%$cari%'
                                group by i_price_group
                                order by i_price_group asc");
            foreach($data->result() as  $kodeharga){
                    $filter[] = array(
                    'id' => $kodeharga->i_price_group,  
                    'text' => $kodeharga->i_price_group
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function view(){
    	$i_sjpb = $this->input->post('i_sjpb', TRUE);
		$i_kode_harga = $this->input->post('i_kode_harga', TRUE);
		$pilihan = $this->input->post('pilihan', TRUE);
        $nilaikotor = $this->mmaster->carinilaikotor($i_sjpb, $i_kode_harga,$pilihan);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'nilaikotor'    => $nilaikotor,
			'i_kode_harga'  => $i_kode_harga,
			'i_sjpb'        => $i_sjpb,
			'i_kode_harga'  => $i_kode_harga,
			'pilihan'       => $pilihan,
            'header'        => $this->mmaster->data_sjpb_header($i_sjpb)->row(),
			'detail'        => $this->mmaster->data_sjpb_item($i_sjpb, $i_kode_harga),
			'sj_nota'       => $this->mmaster->cek_sj_nota($i_sjpb)
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $icustomer     = $this->input->post('icustomer', TRUE);
		$dspb    = $this->input->post('dspb', TRUE);
		$i_sjpb = $this->input->post('i_sjpb', TRUE);
		$i_kode_harga = $this->input->post('i_kode_harga', TRUE);
		$pilihan = $this->input->post('pilihan', TRUE);
        if($dspb!=''){
            $tmp=explode("-",$dspb);
            $th=$tmp[0];
            $bl=$tmp[1];
            $hr=$tmp[2];
            $dspb=$th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
            $thblsj = $th.$bl;
        }
        $ispb    = $this->mmaster->runningnumberspb($thblsj);
        $this->mmaster->insert_spb_header($ispb, $icustomer,$dspb);
        $this->mmaster->update_sjpb($i_sjpb, $icustomer, $ispb);
        $detail = $this->mmaster->data_sjpb_item($i_sjpb, $i_kode_harga);
    
        $nitemno = 1;
        
        foreach ($detail as $row) {
            $iproduct = $row->i_product;
			$iproductgrade = $row->i_product_grade;
			$iproductmotif = $row->i_product_motif;
			$norder = $row->n_receive;
			if($pilihan == 'biasa'){                               
		    $vunitprice = $row->bersih;
		    }else{
		    	$vunitprice = $row->v_product_retail;
		    }
		    $eproductname = $row->e_product_name;
		    $this->mmaster->insert_spb_item($ispb, $iproduct, $iproductgrade, $iproductmotif, $norder, $vunitprice, $eproductname, $nitemno);
            $nitemno++;
        }
        $nilaikotor = $this->mmaster->carinilaikotor($i_sjpb, $i_kode_harga, $pilihan);
		if($pilihan == 'biasa'){                               
			$diskon1persen = ($nilaikotor->nilaikotor * 0.01);
			$v_spb_after = ($nilaikotor->nilaikotor - $diskon1persen);
		}else{
			$diskon1persen = 0;
			$v_spb_after = $nilaikotor->nilaikotor;
		}
        $this->mmaster->update_spb_header($ispb, $diskon1persen, $nilaikotor->nilaikotor, $v_spb_after, $pilihan);
		$isj = $this->mmaster->runningnumbersj($thblsj);
		$this->mmaster->insert_sj_header($isj, $ispb, $dspb, $icustomer, $diskon1persen, $nilaikotor->nilaikotor, $v_spb_after, $pilihan);
		$this->mmaster->updatespbsj($ispb,$isj,$dspb);
		$istore = 'PB';
		$istorelocation = '00';
		$istorelocationbin = '00';
		$nitemno = 1;
		foreach ($detail as $row){
            $iproduct = $row->i_product;
			$iproductgrade = $row->i_product_grade;
			$iproductmotif = $row->i_product_motif;
			$norder = $row->n_receive;
			if($pilihan == 'biasa'){                               
				$vunitprice = $row->bersih;
			}else{
				$vunitprice = $row->v_product_retail;
			}
			$eproductname = $row->e_product_name;
			$ndeliver = $norder;
			$this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,
			$vunitprice,$isj,$nitemno);
            $nitemno++;
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
                $this->mmaster->inserttrans04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
			    $th=substr($dspb,0,4);
			    $bl=substr($dspb,5,2);
			    $emutasiperiode=$th.$bl;
	 
			    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
				    $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
			    }else{
				    $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode,$q_aw);
			    }
			    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
				    $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$q_ak);
			    }else{
				    $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver,$q_aw);
			    }
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Input SJPB ke SJ SPB '.$this->global['title'].' Kode : '.$ispb.' SJ : '.$isj);
            
            $data = array(
                'sukses'    => true,
                'kode'      => 'Input SJPB ke SJ SPB : '.$ispb.' & '.$isj
            );
        }
        $this->load->view('pesan', $data);
    }  
}

/* End of file Cform.php */
