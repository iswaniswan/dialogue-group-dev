<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050104';

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

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
    	// $dfrom = $this->uri->segment('4');
     //    $dto = $this->uri->segment('5');
        
     //    $tmp=explode('-',$dfrom);
     //    $dd=$tmp[0];
     //    $mm=$tmp[1];
     //    $yy=$tmp[2];
     //    $from=$yy.'-'.$mm.'-'.$dd;

     //    $tmp=explode('-',$dto);
     //    $dd=$tmp[0];
     //    $mm=$tmp[1];
     //    $yy=$tmp[2];
     //    $to=$yy.'-'.$mm.'-'.$dd;
            
    	echo $this->mmaster->data($this->i_menu);
    }

    public function view(){
        $dfrom = $this->input->post('dfrom',true);
        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto',true);
        if($dto == ''){
            $dto = $this->uri->segment(5);
        }      

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "List ".$this->global['title'],
            'title_list' => 'Tambah '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
        );

        $this->Logger->write('Membuka Menu List '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isj        = $this->input->post('i_sj', TRUE);
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancelheader($isj);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Surat Jalan Keluar Packing'.$isj);
            echo json_encode($data);
        }
    }

    public function tambah(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'penerima'   => $this->mmaster->getkaryawan()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function jenistujuan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_jenis_keluargdjadi");
            //$this->db->like("UPPER(i_jenis)", $cari);
            $this->db->or_like("UPPER(e_jenis_keluar)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $ijenistujuan){
                    $filter[] = array(
                    'id'   => $ijenistujuan->i_jenis,  
                    'text' => $ijenistujuan->i_jenis.'-'.$ijenistujuan->e_jenis_keluar,
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function dataproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_product_wip, a.e_product_namewip, b.i_color, c.e_color_name");   
            $this->db->from("tr_product_wip a"); 
            $this->db->join("tr_product_wipcolor b","a.i_product_wip = b.i_product_wip");
            $this->db->join("tr_color c","b.i_color=c.i_color ");
            $this->db->where("a.f_product_active", 't'); 
            $this->db->order_by("a.i_product_wip"); 
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id' => $product->i_product_wip.' - '.$product->i_color,  
                    'text' => $product->i_product_wip.' - '.$product->e_product_namewip.' - '.$product->i_color.' - '.$product->e_color_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }
    
    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $kdproduct = $this->input->post('kdproduct');
        $color = $this->input->post('color');
        //$e_color_name = $this->input->post('e_color_name');  
            $this->db->select("distinct (a.i_product_wip), a.e_product_namewip, b.i_color, c.e_color_name, d.n_quantity_stock");   
            $this->db->from("tr_product_wip a"); 
            $this->db->join("tr_product_wipcolor b","a.i_product_wip = b.i_product_wip");
            $this->db->join("tr_color c","b.i_color=c.i_color ");
            //$this->db->where("UPPER(a.i_product_wip)", $iproduct);
            $this->db->join("tm_ic d","a.i_product_wip = d.i_product","left");
            $this->db->where("UPPER(a.i_product_wip)", trim($kdproduct));
            $this->db->where("b.i_color", trim($color));
            $this->db->where("a.f_product_active", 't'); 
            $this->db->order_by("a.i_product_wip");    
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsj      = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datesj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }

        $ijenis    = $this->input->post('ijenis', TRUE);
        $eremark   = $this->input->post('eremark', TRUE);
        $ipenerima = $this->input->post('ipenerima', TRUE);
        $jml       = $this->input->post('jml', TRUE);
            
        $this->db->trans_begin();
        $isj       =$this->mmaster->runningnumber($thbl);
        $this->mmaster->insertheader($isj, $datesj, $ijenis, $eremark, $ipenerima);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);

        for($i=1;$i<=$jml;$i++){
              $iproduct         = $this->input->post('iproductt'.$i, TRUE);
              $icolor           = $this->input->post('icolor'.$i, TRUE);
              $eproductname     = $this->input->post('eproduct'.$i, TRUE);
              $nquantity        = $this->input->post('nquantity'.$i, TRUE);
              $nitemno          = $i;

            if(($nquantity == 0)||($nquantity == '')){
                exit;
            }else{
                $this->mmaster->insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $nitemno);
            }
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,   
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }  

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_dataheader($isj)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($isj)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function update(){
        $isj         = $this->input->post('isj', TRUE);
        $dsj         = $this->input->post('dsj', TRUE);
        $ijenis      = $this->input->post('ijenis',TRUE);
        $eremark     = $this->input->post('eremark',TRUE);
        $jml         = $this->input->post('jml', TRUE);

            $this->db->trans_begin();
            $this->mmaster->updateheader($isj, $dsj, $ijenis, $eremark);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isj);

            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproductt'.$i, TRUE);
                $icolor           = $this->input->post('icolor'.$i, TRUE);
                $eproductname     = $this->input->post('eproduct'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                $nitemno          = $i;
                if(($nquantity == 0)||($nquantity == '')){
                    exit;
                }else{
                    $this->mmaster->deletedetail($isj, $iproduct, $icolor);
                    $this->mmaster->insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $nitemno);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,   
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
