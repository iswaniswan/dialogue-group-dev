<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070405';

    public function __construct(){
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
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    } 

    public function customer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $iarea  = $this->mmaster->getarea();
            $query  = $this->mmaster->customer($cari,$iarea);
            foreach($query->result() as $key){
                $filter[] = array(
                    'id'    => $key->i_customer,  
                    'text'  => $key->i_customer.' - '.$key->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    } 

    public function view(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $icustomer  = $this->input->post('icustomer');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        if($icustomer=='') {
            $icustomer   = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => 'List '.$this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'icustomer' => $icustomer,
        );
        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $icustomer  = $this->input->post('icustomer');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        if($icustomer=='') {
            $icustomer   = $this->uri->segment(6);
        }
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto,$icustomer);
    }

    public function edit(){
        $inotapb    = $this->uri->segment(4);
        $inotapb    = str_replace("%20","",$inotapb);
        $icustomer  = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $dnotapb    = $this->uri->segment(8);
        $iuser      = $this->session->userdata('username');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'inotapb'   => $inotapb,
            'icustomer' => $icustomer,
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'periode'   => $this->mmaster->periode()->row(),
            'isi'       => $this->mmaster->bacaisi($inotapb,$icustomer),
            'detail'    => $this->mmaster->bacadetail($inotapb,$icustomer),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function batalcek(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $i_notapb  = $this->input->post('i_notapb', TRUE);
        $icustomer = $this->input->post('icustomer', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->batalcek($i_notapb,$icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Batal Cek Bon : '.$i_notapb.' Customer : '.$icustomer);
            echo json_encode($data);
        }
    }

    PUBLIC function databarang(){
        $filter = [];
        $icustomer = $this->uri->segment(4);
        if($this->input->get('q') != '' && $icustomer!='') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $query  = $this->mmaster->getbarang($cari,$icustomer);
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

    public function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct   = $this->input->post('i_product');
        $icustomer  = $this->uri->segment(4);
        $data       = $this->mmaster->caribarang($iproduct,$icustomer);
        echo json_encode($data->result_array());
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $xinotapb       = $this->input->post('xinotapb', TRUE);
        $inotapb        = $this->input->post('inotapb', TRUE);
        $dnotapb        = $this->input->post('dnotapb', TRUE);
        if($dnotapb!=''){
            $thbl    = date('ym', strtotime($dnotapb));
            $dnotapb = date('Y-m-d', strtotime($dnotapb));
        }
        $iarea          = $this->input->post('iarea', TRUE);
        $ispg           = $this->input->post('ispg', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $nnotapbdiscount= $this->input->post('nnotapbdiscount', TRUE);
        $nnotapbdiscount= str_replace(',','',$nnotapbdiscount);
        $vnotapbdiscount= $this->input->post('vnotapbdiscount', TRUE);
        $vnotapbdiscount= str_replace(',','',$vnotapbdiscount);
        $vnotapbgross   = $this->input->post('vnotapbgross', TRUE);
        $vnotapbgross   = str_replace(',','',$vnotapbgross);
        $jml            = $this->input->post('jml', TRUE);
        if($dnotapb!='' && $inotapb!='' && $jml>0){
            $this->db->trans_begin();
            settype($inotapb,"string");
            /*$inotapb = 'FB-'.$thbl.'-'.$inotapb;*/
            $this->mmaster->deleteheader($xinotapb, $iarea, $icustomer);
            $this->mmaster->insertheader($inotapb, $dnotapb, $iarea, $ispg, $icustomer, $nnotapbdiscount, $vnotapbdiscount, $vnotapbgross);
            for($i=1;$i<=$jml;$i++){
                $iproduct        = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade   = 'A';
                $iproductmotif   = $this->input->post('motif'.$i, TRUE);
                $eproductname    = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice      = $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice      = str_replace(',','',$vunitprice);
                $nquantity       = $this->input->post('nquantity'.$i, TRUE);
                $nquantity       = str_replace(',','',$nquantity);
                $ipricegroupco   = $this->input->post('ipricegroupco'.$i, TRUE);
                $eremark         = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($iproduct, $iproductgrade, $xinotapb, $iarea, $icustomer, $iproductmotif, $vunitprice);
                if($nquantity>0){
                    $this->mmaster->insertdetail($inotapb,$iarea,$icustomer,$dnotapb,$iproduct,$iproductmotif,$iproductgrade,$nquantity,$vunitprice,$i,$eproductname,$ipricegroupco,$eremark);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Penjualan Konsinyasi No:'.$inotapb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $inotapb
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
