<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090406';

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

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
    	echo $this->mmaster->data($this->i_menu);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function gudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_sub_bagian");
            $this->db->where("i_sub_bagian", "SDP0008");
            $data = $this->db->get();
            foreach($data->result() as  $idept){
                    $filter[] = array(
                    'id'   => $idept->i_sub_bagian,  
                    'text' => $idept->e_sub_bagian,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function tujuan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_supplier");
            $this->db->where("i_supplier_group", "KTG0002");
            $data = $this->db->get();
            foreach($data->result() as  $idept){
                    $filter[] = array(
                    'id'   => $idept->i_supplier,  
                    'text' => $idept->e_supplier_name,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getreferensi(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_sj_masuk_makloonunitjahit");
            $data = $this->db->get();
            foreach($data->result() as  $sj){
                    $filter[] = array(
                    'id'   => $sj->i_sj,  
                    'text' => $sj->i_sj.'||'.$sj->d_sj,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function dataproduct(){
        $filter = [];
        $iproduct = $this->uri->segment(4);
        if($this->input->get('q') != ''){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name
                                from tr_polacutting a, tr_product_wip b, tr_color c
                                where a.i_product = b.i_product_wip
                                and a.i_color = c.i_color
                                order by a.i_product", false); 
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id'    => $product->i_product,
                    'name'  => $product->e_product_namewip,
                    'text'  => $product->i_product.' - '.$product->e_product_namewip.'-'.$product->e_color_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getproduct(){
        header("Content-Type: application/json", true);
        $eproduct = $this->input->post('eproduct');
        $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
            $this->db->join("tr_color c", "a.i_color = c.i_color");
            $this->db->where("a.i_product", $eproduct);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function datamaterial(){
        $filter = [];
        $eproduct = $this->uri->segment(4);
        if($this->input->get('q') != ''){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" a.i_product, a.i_material, b.e_material_name 
                                from tr_polacutting a
                                join tr_material b on a.i_material=b.i_material
                                where a.i_product='$eproduct'", false); 
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id'    => $product->i_material,
                    'name'  => $product->e_material_name,
                    'text'  => $product->i_material.' - '.$product->e_material_name,
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerial= $this->input->post('ematerial');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
        $this->db->from("tr_material a");
        $this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        $this->db->where("UPPER(i_material)", $ematerial);
        $this->db->order_by('a.i_material', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getdataitem(){
        header("Content-Type: application/json", true);
        $referensi        = $this->input->post('referensi');

        $this->db->select("* from tm_sj_masuk_makloonunitjahit a where a.i_sj = '$referensi'");
        $data = $this->db->get();

        $query   = $this->mmaster->getdataitem($referensi);

        $dataa = array(
            'data'       => $data->result_array(),
            'jmlitem'    => $query->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($referensi)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dretur         = $this->input->post('dretur', TRUE);
        if($dretur!=''){
            $tmp=explode("-",$dretur);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dateretur=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $ibagian        = $this->input->post('ibagian',TRUE);
        $itujuan        = $this->input->post('itujuan',TRUE);
        $noreff         = $this->input->post('noreff',TRUE);
        // if($noreff == '1'){
        $ireferensi = $this->input->post('ireffo',TRUE);
        // }else{
        //     $ireferensi = $this->input->post('ireffm',TRUE);
        // }
        $eremark     = $this->input->post('eremark',TRUE);
        $jml         = $this->input->post('jml', TRUE);
           
        $iretur      = $this->mmaster->runningnumber($thbl, $ibagian);
        $this->mmaster->insertheader($iretur, $dateretur, $ibagian, $itujuan, $ireferensi, $eremark);

        for($i=1;$i<=$jml;$i++){
            if($this->input->post('cek'.$i)=='cek'){  
              $iproduct         = $this->input->post('iproductwip'.$i, TRUE);
              $icolor           = $this->input->post('icolor'.$i, TRUE);
              $brgjadi          = $this->input->post('brgjadi'.$i, TRUE);
              $nquantity        = $this->input->post('nquantity'.$i, TRUE);
              $edesc            = $this->input->post('edesc'.$i, TRUE);
              $inoitem          = $i;

              $this->mmaster->insertdetail($iretur, $iproduct, $icolor, $brgjadi, $nquantity, $edesc, $inoitem);
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
                'sukses'    => true,
                'kode'      => $iretur,
            );
        }
        $this->load->view('pesan', $data);    
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iretur = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iretur)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($iretur)->result(),
            'bagian'        => $this->mmaster->baca_bagian()->result(),
            'tujuan'        => $this->mmaster->baca_tujuan()->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iretur         = $this->input->post('iretur', TRUE);
        $dretur         = $this->input->post('dretur', TRUE);
        if($dretur!=''){
            $tmp=explode("-",$dretur);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dateretur=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $ibagian     = $this->input->post('ibagian',TRUE);
        $itujuan     = $this->input->post('itujuan',TRUE);
        $ireff       = $this->input->post('ireff',TRUE);
        $eremark     = $this->input->post('eremark',TRUE);
        $jml         = $this->input->post('jml', TRUE);
            
        $this->db->trans_begin(); 
        $this->mmaster->updateheader($iretur, $dateretur, $ibagian, $itujuan, $ireff, $eremark);
        $this->mmaster->deletedetail($iretur);

        for($i=1;$i<=$jml;$i++){
          $iproduct         = $this->input->post('iproduct'.$i, TRUE);
          $icolor           = $this->input->post('icolorproduct'.$i, TRUE);
          $brgjadi          = $this->input->post('brgjadi'.$i, TRUE);
          $nquantity        = $this->input->post('nquantity'.$i, TRUE);
          $edesc            = $this->input->post('edesc'.$i, TRUE);
          $inoitem          = $i;

          $this->mmaster->insertdetail($iretur, $iproduct, $icolor, $brgjadi, $nquantity, $edesc, $inoitem);
        }        
        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iretur);

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iretur
                );
            }
        $this->load->view('pesan', $data);  
    }

    public function detail(){
        $iretur = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iretur)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($iretur)->result(),
            'bagian'        => $this->mmaster->baca_bagian()->result(),
            'tujuan'        => $this->mmaster->baca_tujuan()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformdetail', $data);
        
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iretur = $this->input->post('iretur', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iretur);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Retur Barang Tolakan' . $iretur);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */