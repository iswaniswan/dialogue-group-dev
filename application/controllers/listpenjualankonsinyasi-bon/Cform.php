<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070402';

    public function __construct(){
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
    

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->session->userdata('i_area');
        $ispg      = strtoupper($this->session->userdata("username"));
        $icustomer = $this->mmaster->bacacustomer($iarea,$ispg);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Info ".$this->global['title'],
            'ispg'          => $ispg,
            'iarea'         => $iarea,
            'icustomer'     => $icustomer
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $ispg   = strtoupper($this->session->userdata("username"));
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        echo $this->mmaster->data($dfrom,$dto,$ispg,$this->i_menu);
    }
    
    public function view(){
    	$ispg      = strtoupper($this->session->userdata("username"));
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'ispg'          => $ispg
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
            $inotapb    = $this->uri->segment(4);
            $inotapb    = str_replace("%20","",$inotapb);
			$icustomer  = $this->uri->segment(5);
			$dfrom      = $this->uri->segment(6);
			$dto 	    = $this->uri->segment(7);
            $iarea      = $this->session->userdata("i_area");
            $query      = $this->db->query("select i_product from tm_notapb_item where i_notapb='$inotapb' and i_customer='$icustomer'");
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'jmlitem'    => $query->num_rows(),
                'inotapb'    => $inotapb,
                'icustomer'  => $icustomer,
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'iarea'      => $iarea,
                'isi'        => $this->mmaster->baca($inotapb,$icustomer),
                'detail'     => $this->mmaster->bacadetail($inotapb,$icustomer),
                'periode'    => $this->db->query("select * from tm_periode")->row()

            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
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

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $xinotapb		= $this->input->post('xinotapb', TRUE);
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
        $iarea	        = $this->input->post('iarea', TRUE);
        $ispg           = $this->input->post('ispg', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $nnotapbdiscount= $this->input->post('nnotapbdiscount', TRUE);
        $nnotapbdiscount= str_replace(',','',$nnotapbdiscount);
        $vnotapbdiscount= $this->input->post('vnotapbdiscount', TRUE);
        $vnotapbdiscount= str_replace(',','',$vnotapbdiscount);
        $vnotapbgross= $this->input->post('vnotapbgross', TRUE);
        $vnotapbgross	  	= str_replace(',','',$vnotapbgross);
        $jml		= $this->input->post('jml', TRUE);
        if($dnotapb!='' && $inotapb!='' && $jml>0){
            $this->db->trans_begin();
            $inotapb = 'FB-'.$thbl.'-'.$inotapb;
            $this->mmaster->deleteheader($xinotapb, $iarea, $icustomer);
            $this->mmaster->insertheader($inotapb, $dnotapb, $iarea, $ispg, $icustomer, $nnotapbdiscount, $vnotapbdiscount, $vnotapbgross);
            for($i=1;$i<=$jml;$i++){
                $iproduct			  = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade	= 'A';
                $iproductmotif	= $this->input->post('motif'.$i, TRUE);
                $eproductname		= $this->input->post('eproductname'.$i, TRUE);
                $vunitprice		  = $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice	  	= str_replace(',','',$vunitprice);
                $nquantity   		= $this->input->post('nquantity'.$i, TRUE);
                $nquantity	  	= str_replace(',','',$nquantity);
                $ipricegroupco  = $this->input->post('ipricegroupco'.$i, TRUE);
                $eremark        = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($iproduct, $iproductgrade, $xinotapb, $iarea, $icustomer, $iproductmotif, $vunitprice);
                if($nquantity>0){
                  $this->mmaster->insertdetail( $inotapb,$iarea,$icustomer,$dnotapb,$iproduct,$iproductmotif,$iproductgrade,$nquantity,$vunitprice,$i,$eproductname,$ipricegroupco,$eremark);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Penjualan Konsinyasi :'.$this->global['title'].' Kode : '.$inotapb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update Penjualan Konsinyasi '.$inotapb
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

        $inotapb        = $this->input->post('inotapb', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $this->db->trans_begin();
        $this->mmaster->delete($inotapb,$icustomer);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Penjualan Konsinyasi '.$inotapb);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
