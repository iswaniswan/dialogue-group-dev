<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040107';

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
            'title_list'=> 'List '.$this->global['title'],
            'ispb'      => '',
            'iop'       => '',
            'isi'       => '',
            'jmlitem'   => 0,
            'detail'    => ''
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dsaldo        = $this->input->post('dop', TRUE);
        if($dsaldo!=''){
            $tmp=explode("-",$dsaldo);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsaldo=$th."-".$bl."-".$hr;
    #        $thbl=substr($th,2,2).$bl;
            $thbl=$th.$bl;
        }
        $jml  = $this->input->post('jml');
        $i=0;
        
        $iop='';
        for($i=1;$i<=$jml;$i++){
            $norder   = $this->input->post('norder'.$i, TRUE);
            $iproduct = $this->input->post('iproduct'.$i, TRUE);
            $rp=$this->mmaster->cekproduct($iproduct, $thbl);
            if( ($norder!='0') ){
                $iproductgrade = 'A';
                $iproductmotif = $this->input->post('motif'.$i, TRUE);
                $eproductname  = $this->input->post('eproductname'.$i, TRUE);
                $norder        = $this->input->post('norder'.$i, TRUE);

                $this->mmaster->insertdetail($thbl,$iproduct,$iproductgrade,$eproductname,$norder,$iproductmotif,$i);
            }
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('input Saldo Forecast Periode: '.$this->global['title'].' Kode : '.$thbl);
            $data = array(
                'sukses'    => true,
                'kode'      => $thbl
            );
        }
        $this->load->view('pesan', $data); 
    }

    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tr_product");
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

    function getmotif(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $this->db->select("a.i_product, a.e_product_name, b.i_product_motif, b.e_product_motifname");
        $this->db->from("tr_product a");
        $this->db->join("tr_product_motif b","a.i_product=b.i_product");
        $this->db->where("UPPER(a.i_product)", $iproduct);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }
}
/* End of file Cform.php */
