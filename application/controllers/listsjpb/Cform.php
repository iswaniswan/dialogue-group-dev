<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070406';

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
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $query      = $this->db->query("select * from tr_area where i_area in (select i_area from public.tm_user_area where username = '$username' and id_company = '$idcompany')");
        $hasil      = $query->row();
        $iarea      = $hasil->i_area;
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Info ".$this->global['title'],
            'customer'      => $this->mmaster->bacacustomer($iarea)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $icustomer  = $this->input->post('icustomer');
        $iarea  = $this->input->post('iarea');
        $dfrom	    = $this->input->post('dfrom');
        $dto	    = $this->input->post('dto');

        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        if($icustomer==''){
            $icustomer=$this->uri->segment(6);
        } 
        if($iarea==''){
            $iarea=$this->uri->segment(7);
        } 

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $query      = $this->db->query("select * from tr_area where i_area in (select i_area from public.tm_user_area where username = '$username' and id_company = '$idcompany')");
        $hasil      = $query->row();
        $iarea      = $hasil->i_area;

        echo $this->mmaster->data($dfrom,$dto,$icustomer, $iarea, $this->i_menu);
    }
    
    public function view(){
        $icustomer  = $this->input->post('icustomer');
        $iarea  = $this->input->post('iarea');
        $dfrom	    = $this->input->post('dfrom');
        $dto	    = $this->input->post('dto');

        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        if($icustomer==''){
            $icustomer=$this->uri->segment(6);
        } 
        if($iarea==''){
            $iarea=$this->uri->segment(7);
        } 

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $query      = $this->db->query("select * from tr_area where i_area in (select i_area from public.tm_user_area where username = '$username' and id_company = '$idcompany')");
        $hasil      = $query->row();
        $iarea      = $hasil->i_area;

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'icustomer'  => $icustomer,
            'iarea'      => $iarea
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
            $isj 	    = $this->uri->segment(4);
			$icustomer  = $this->uri->segment(5);
			$dfrom	    = $this->uri->segment(6);
			$dto 	    = $this->uri->segment(7);
            $query        = $this->db->query(" select a.i_product as kode
                                               from tr_product_motif a,tr_product c, tm_sjpb_item b
                                               where a.i_product=c.i_product 
                                               and b.i_product_motif=a.i_product_motif
                                               and c.i_product=b.i_product
                                               and b.i_sjpb='$isj'",false);
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'jmlitem'    => $query->num_rows(),
                'isj'        => $isj,
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'icustomer'  => $icustomer,
                'isi'        => $this->mmaster->baca($isj,$icustomer)->row(),
                'detail'     => $this->mmaster->bacadetail($isj,$icustomer)
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformupdate', $data);
        }
    }

    function databarang(){
        $filter = [];
        $istore    = $this->uri->segment('5');
        $icustomer = $this->uri->segment('6');
      
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select e.i_product, e.i_product_grade, a.i_product_motif, b.e_product_motifname, e.v_product_retail, 
                                    a.i_product_grade, a.i_store, a.i_store_location, 
                                    a.i_store_locationbin, a.e_product_name, a.n_quantity_stock, a.f_product_active
                                    FROM tm_ic a, tr_product_motif b, tr_product c, tr_customer_consigment d, tr_product_priceco e
                                    where (upper(a.i_product) like '%$cari%' or upper(a.e_product_name) like '%$cari%') and b.i_product_motif='00' 
                                    and a.i_store_location='00' and d.i_customer='$icustomer' and e.i_price_groupco=d.i_price_groupco 
                                    and c.i_product=e.i_product and a.i_product=e.i_product and
                                    a.i_product = b.i_product and a.i_product = c.i_product and i_store ='$istore'
                                    group by e.i_product, e.i_product_grade, a.i_product_motif, b.e_product_motifname, e.v_product_retail, 
                                    a.i_product_grade, a.i_store, a.i_store_location, 
                                    a.i_store_locationbin, a.e_product_name, a.n_quantity_stock, a.f_product_active order by e.i_product",false);
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
        $istore    = $this->uri->segment('5');
        $icustomer = $this->uri->segment('6');
        $data=$this->db->query(" select a.i_product, a.i_product_grade, a.i_product_motif, b.e_product_motifname, c.v_product_retail, 
                                a.i_product_grade, a.i_store, a.i_store_location, 
                                a.i_store_locationbin, a.e_product_name, a.n_quantity_stock, a.f_product_active
                                FROM tm_ic a, tr_product_motif b, tr_product c
                                where b.i_product_motif='00' and a.i_store_location='00' and 
                                a.i_product = b.i_product and a.i_product = c.i_product and i_store ='$istore'");
        //$data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isjpb      = $this->input->post('isj', TRUE);
		$iarea      = $this->input->post('iarea', TRUE);
		$icustomer	= $this->input->post('icustomer',TRUE);
		$jml	    = $this->input->post('jml', TRUE);
      	$ispg	    = $this->input->post('ispg',TRUE);
      	$tglsj      = $this->input->post('tglsj2', TRUE);
      	$tmp	    = explode("-",$tglsj);
		$th	        = $tmp[2];
		$bl	        = $tmp[1];
		$hr	        = $tmp[0];
		$tglsj      = $th."-".$bl."-".$hr;
      	$vsjpb      = $this->input->post('vsjpb',TRUE);
      	$vsjpb	    = str_replace(',','',$vsjpb);
      	$query      = $this->db->query("SELECT current_timestamp as c");
      	$row        = $query->row();
      	$tglupdate  =  $row->c;
        
        $this->db->trans_begin();
        $this->mmaster->updateheader2($isjpb, $iarea, $icustomer, $ispg, $tglsj, $vsjpb, $tglupdate);
        for($i=1;$i<=$jml;$i++){
            $iproduct		  = substr($this->input->post('iproduct'.$i, TRUE),0,7);
            $iproductgrade  = 'A';
            $ndeliver	  	  = $this->input->post('ndeliver'.$i, TRUE);
            $ndeliver		  = str_replace(',','',$ndeliver);
            $vproductmill = $this->input->post('vproductmill'.$i, TRUE);
            $this->mmaster->updatedetail2($isjpb, $iarea, $iproduct, $iproductgrade, $ndeliver, $vproductmill);
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update SJPB :'.$this->global['title'].' Kode : '.$isjpb);
            $data = array(
                'sukses'    => true,
                'kode'      => 'Update SJPB Receive '.$isjpb
            );
        }

        $this->load->view('pesan', $data);  
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isjpb      = $this->input->post('isjpb');

       // var_dump($isjpb);

        $icustomer	= $this->input->post('icustomer');
             //   var_dump($icustomer);
		$dfrom	    = $this->uri->segment(6);
		$dto	    = $this->uri->segment(7);
        $this->db->trans_begin();
        $this->mmaster->delete($isjpb,$icustomer);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJPB Receipt '.$isjpb);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
