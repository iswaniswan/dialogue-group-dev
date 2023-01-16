<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '103084';

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
        $iuser = $this->session->userdata('username');
        $iarea = $this->input->post('iarea');
        $query=$this->db->query('select * from tm_periode');
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'ispg'              => '',
            'iarea'             => '',
            'icustomer'         => '',
            'eareaname'         => '',
            'espgname'          => '',
            'ecustomername'     => '',
            'inotapb'           => '',
            'jmlitem'           => '',
            'detail'            => '',
            'tgl'               => date('d-m-Y'),
            'periode'           => $query,
            'isi'               => '',
            'area'              => $this->mmaster->bacaarea($iuser)->result(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getspg(){
        $iarea = $this->input->post('iarea');
        $query = $this->mmaster->getpg($iarea);
        if($query->num_rows()>0) {
            $c  = "";
            $spg = $query->result();
            foreach($spg as $row) {
                $c.="<option value=".$row->i_spg." >".$row->i_spg." - ".$row->e_spg_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih SPG -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak SPG</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function getcustomer(){
        header("Content-Type: application/json", true);
        /*$iarea = $this->input->post('i_area');*/
        $ispg = $this->input->post('i_spg');
        $data=$this->db->query("select a.i_spg, a.i_area, b.i_customer, b.e_customer_name
                                from tr_spg a, tr_customer b
                                where a.i_customer=b.i_customer 
                                and a.i_area=b.i_area
                                and a.i_spg='$ispg'
                                order by a.i_spg");
        echo json_encode($data->result_array());
    }


    function databarang(){
        $filter = [];
        $icustomer = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select b.i_product, b.i_price_group, a.i_product_motif, a.e_product_motifname, b.v_product_retail, c.e_product_name
                                    from tr_product_motif a,tr_product c, tr_product_priceco b, tr_customer_consigment d
                                    where a.i_product=c.i_product and a.i_product=b.i_product 
                                    and (upper(a.i_product) like '%$cari%' or upper(c.e_product_name) like '%$cari%')
                                    and d.i_customer='$icustomer' and d.i_price_groupco=b.i_price_groupco and a.i_product_motif='00'
                                    order by c.i_product, a.e_product_motifname, b.i_price_group",false);
            foreach($query->result() as  $produk){
                    $filter[] = array(
                    'id' => $produk->i_product,  
                    'text' => $produk->i_product.'-'.$produk->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $icustomer = $this->uri->segment('4');
        $data=$this->db->query("select b.i_product, b.i_price_groupco, a.i_product_motif, a.e_product_motifname, b.v_product_retail, c.e_product_name
                          from tr_product_motif a,tr_product c, tr_product_priceco b, tr_customer_consigment d
                          where a.i_product=c.i_product and a.i_product=b.i_product 
                          and d.i_customer='$icustomer' and a.i_product='$iproduct' and d.i_price_groupco=b.i_price_groupco and a.i_product_motif='00'
                          order by c.i_product, a.e_product_motifname, b.i_price_group");
        //$data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $inotapb 		= $this->input->post('inotapb', TRUE);
		$dnotapb 		= $this->input->post('dnotapb', TRUE);
		if($dnotapb!=''){
			$tmp=explode("-",$dnotapb);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dnotapb=$th."-".$bl."-".$hr;
			$thbl=substr($th,2,2).$bl;
		}
        $iarea	            = $this->input->post('iarea', TRUE);
		$ispg               = $this->input->post('ispg', TRUE);
		$icustomer          = $this->input->post('icustomer', TRUE);
		$nnotapbdiscount    = $this->input->post('nnotapbdiscount', TRUE);
		$nnotapbdiscount    = str_replace(',','',$nnotapbdiscount);
		$vnotapbdiscount    = $this->input->post('vnotapbdiscount', TRUE);
		$vnotapbdiscount    = str_replace(',','',$vnotapbdiscount);
		$vnotapbgross       = $this->input->post('vnotapbgross', TRUE);
		$vnotapbgross	    = str_replace(',','',$vnotapbgross);
		$jml		        = $this->input->post('jml', TRUE);
        if($dnotapb!='' && $inotapb!='' && $jml>0){
            $this->db->trans_begin();
			settype($inotapb,"string");
            $a=strlen($inotapb);
            while($a<7){
                $inotapb="0".$inotapb;
                $a=strlen($inotapb);
            }
            $inotapb = 'FB-'.$thbl.'-'.$inotapb;
			$cek_data = $this->mmaster->cek_notapb($inotapb, $iarea, $icustomer);
			if($cek_data->num_rows() > 0 ){
				echo "No Nota ".$inotapb." Untuk Pelanggan ".$icustomer." Sudah Ada !";
				die;
            }
            $this->mmaster->insertheader($inotapb, $dnotapb, $iarea, $ispg, $icustomer, $nnotapbdiscount, $vnotapbdiscount, $vnotapbgross);
			for($i=1;$i<=$jml;$i++){
                $iproduct			  = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade	= 'A';
                $iproductmotif	= $this->input->post('motif'.$i, TRUE);
                $eproductname	= $this->input->post('eproductname'.$i, TRUE);
                $vunitprice		= $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice	  	= str_replace(',','',$vunitprice);
                $nquantity   	= $this->input->post('nquantity'.$i, TRUE);
                $nquantity	  	= str_replace(',','',$nquantity);
                $ipricegroupco  = $this->input->post('ipricegroupco'.$i, TRUE);
                $eremark        = $this->input->post('eremark'.$i, TRUE);
                if($nquantity>0){
                    $this->mmaster->insertdetail( $inotapb,$iarea,$icustomer,$dnotapb,$iproduct,$iproductmotif,$iproductgrade,$nquantity,$vunitprice,$i,$eproductname,$ipricegroupco,$eremark);
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
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
                    $th=substr($dnotapb,0,4);
                    $bl=substr($dnotapb,5,2);
                    $emutasiperiode=$th.$bl;
                    $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode);
                    if($ada=='ada'){
                      $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nquantity,$emutasiperiode);
                    }else{
                      $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nquantity,$emutasiperiode,$q_aw,$q_ak);
                    }
                    if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$icustomer)){
                      $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nquantity,$q_ak);
                    }else{
                      $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$icustomer,$eproductname,$nquantity);
                    }
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Bon Konsinyasi :'.$this->global['title'].' Kode : '.$inotapb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Tambah Bon Konsinyasi '.$inotapb
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
