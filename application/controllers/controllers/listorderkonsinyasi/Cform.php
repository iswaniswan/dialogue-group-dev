<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070403';

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
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto);
    } 

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iorderpb   = $this->input->post('iorderpb');
        $icustomer  = $this->input->post('icustomer');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iorderpb,$icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Menghapus Data Order Konsinyasi Customer '.$icustomer.' No : '.$iorderpb);
            echo json_encode($data);
        }
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
            'i_menu'    => $this->i_menu,
            'isi'       => $this->mmaster->baca($iorderpb,$icustomer),
            'detail'    => $this->mmaster->bacadetail($iorderpb,$icustomer),
            'periode'   => $this->db->query('SELECT * FROM tm_periode')->row(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function databarang(){
        $filter = [];
        $icustomer = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("
                SELECT
                    a.i_product,
                    a.i_product_motif,
                    a.e_product_motifname,
                    c.e_product_name,
                    c.v_product_retail
                FROM
                    tr_product_motif a,
                    tr_product c
                WHERE
                    a.i_product = c.i_product
                    AND (upper(a.i_product) LIKE '%$cari%'
                    OR upper(c.e_product_name) LIKE '%$cari%')
                ORDER BY
                    a.e_product_motifname ASC
            ",false);
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
        $data=$this->db->query("
            SELECT
                a.i_product,
                a.i_product_motif,
                a.e_product_motifname,
                c.e_product_name,
                c.v_product_retail
            FROM
                tr_product_motif a,
                tr_product c
            WHERE
                a.i_product = c.i_product
                AND upper(a.i_product) = '$iproduct'
            ORDER BY
                a.e_product_motifname ASC
        ");
        echo json_encode($data->result_array());
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $xiorderpb      = $this->input->post('xiorderpb', TRUE);
        $iorderpb       = $this->input->post('iorderpb', TRUE);
        $dorderpb       = $this->input->post('dorderpb', TRUE);
        if($dorderpb!=''){
            $tmp=explode("-",$dorderpb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dorderpb=$th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
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
            $this->mmaster->deleteheader($xiorderpb, $iarea, $icustomer);
            $this->mmaster->insertheader($iorderpb, $dorderpb, $iarea, $ispg, $icustomer, $norderpbdiscount, $vorderpbdiscount, $vorderpbgross);
            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade    = 'A';
                $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice       = $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice       = str_replace(',','',$vunitprice);
                $nquantityorder   = $this->input->post('nquantityorder'.$i, TRUE);
                $nquantityorder   = str_replace(',','',$nquantityorder);
                $nquantitystock   = $this->input->post('nquantitystock'.$i, TRUE);
                $nquantitystock   = str_replace(',','',$nquantitystock);
                $ipricegroupco    = $this->input->post('ipricegroupco'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($iproduct, $iproductgrade, $iorderpb, $iarea, $icustomer, $iproductmotif,$i);
                if($nquantityorder>0){
                    $this->mmaster->insertdetail($iorderpb,$iarea,$icustomer,$dorderpb,$iproduct,$iproductmotif,$iproductgrade,$nquantityorder,$nquantitystock,$i,$eproductname,$eremark);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update '.$this->global['title'].' Kode : '.$iorderpb.' SPG : '.$ispg);
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

}
/* End of file Cform.php */
