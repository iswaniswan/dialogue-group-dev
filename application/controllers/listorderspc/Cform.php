<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070407';

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

    public function view(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => 'List '.$this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );
        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $iarea      = $this->mmaster->getarea();
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto,$iarea);
    } 

    public function edit(){
        $iorderpb   = $this->uri->segment(4);
        $icustomer  = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iorderpb'  => $iorderpb,
            'icustomer' => $icustomer,
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'isi'       => $this->mmaster->baca($iorderpb,$icustomer),
            'detail'    => $this->mmaster->bacadetail($iorderpb,$icustomer),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function databarang(){
        $filter = [];
        $icustomer = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name, c.v_product_retail
                from tr_product_motif a,tr_product c
                where a.i_product=c.i_product
                and (upper(a.i_product) like '%$cari%' 
                or upper(c.e_product_name) like '%$cari%')
                order by a.e_product_motifname asc",false);
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
        $iproduct = $this->input->post('i_product');
        //$icustomer = $this->uri->segment('4');
        $data=$this->db->query("select a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name,c.v_product_retail
            from tr_product_motif a,tr_product c
            where a.i_product=c.i_product
            and upper(a.i_product) = '$iproduct'
            order by a.e_product_motifname asc");
        //$data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $xiorderpb          = $this->input->post('xiorderpb', TRUE);
        $iorderpb           = $this->input->post('iorderpb', TRUE);
        $dorderpb       = $this->input->post('dorderpb', TRUE);
        if($dorderpb!=''){
            $thbl     = date('ym', strtotime($dorderpb));
            $dorderpb = date('Y-m-d', strtotime($dorderpb));
        }
        $iarea              = $this->input->post('iarea', TRUE);
        $ispg               = $this->input->post('ispg', TRUE);
        $icustomer          = $this->input->post('icustomer', TRUE);
        $norderpbdiscount   = $this->input->post('norderpbdiscount', TRUE);
        $norderpbdiscount   = str_replace(',','',$norderpbdiscount);
        $vorderpbdiscount   = $this->input->post('vorderpbdiscount', TRUE);
        $vorderpbdiscount   = str_replace(',','',$vorderpbdiscount);
        $vorderpbgross      = $this->input->post('vorderpbgross', TRUE);
        $vorderpbgross      = str_replace(',','',$vorderpbgross);
        $jml                = $this->input->post('jml', TRUE);
        if($dorderpb!='' && $iorderpb!='' && $jml>0){
            $this->db->trans_begin();
            $this->mmaster->deleteheader($xiorderpb, $iarea, $icustomer);
            $this->mmaster->insertheader($iorderpb, $dorderpb, $iarea, $ispg, $icustomer, $norderpbdiscount, $vorderpbdiscount, $vorderpbgross);
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade  = 'A';
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice     = $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice     = str_replace(',','',$vunitprice);
                $nquantity      = $this->input->post('nquantityorder'.$i, TRUE);
                $nquantity      = str_replace(',','',$nquantity);
                $nstock         = $this->input->post('nquantitystock'.$i, TRUE);
                $nstock         = str_replace(',','',$nstock);
                $ipricegroupco  = $this->input->post('ipricegroupco'.$i, TRUE);
                $eremark        = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($iproduct, $iproductgrade, $xiorderpb, $iarea, $icustomer, $iproductmotif);
                if($nquantity>0){
                    $this->mmaster->insertdetail($iorderpb,$iarea,$icustomer,$dorderpb,$iproduct,$eproductname,$iproductmotif,$iproductgrade,$nquantity,$nstock,$eremark,$i);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Order Konsinyasi Kode : '.$iorderpb.' Customer : '.$icustomer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iorderpb
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }

    public function cancel(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iorderpb  = $this->input->post('iorderpb');
        $icustomer = $this->input->post('icustomer');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iorderpb,$icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus OPB :'.$iorderpb.' Customer '.$icustomer);
            echo json_encode($data);
        }
    }

}
/* End of file Cform.php */
