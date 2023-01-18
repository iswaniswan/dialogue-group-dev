<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090504';

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
    	// $dfrom = $this->uri->segment('4');
        // $dto = $this->uri->segment('5');
        
        // $tmp=explode('-',$dfrom);
        // $dd=$tmp[0];
        // $mm=$tmp[1];
        // $yy=$tmp[2];
        // $from=$yy.'-'.$mm.'-'.$dd;

        // $tmp=explode('-',$dto);
        // $dd=$tmp[0];
        // $mm=$tmp[1];
        // $yy=$tmp[2];
        // $to=$yy.'-'.$mm.'-'.$dd;
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
            
    	echo $this->mmaster->data($this->i_menu, $username, $idcompany, $idepartemen, $ilevel);
    }
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'gudang'=> $this->mmaster->bacagudang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    // public function view(){
    //     $dfrom = $this->input->post('dfrom',true);
    //     if($dfrom == ''){
    //         $dfrom = $this->uri->segment(4);
    //     }
    //     $dto = $this->input->post('dto',true);
    //     if($dto == ''){
    //         $dto = $this->uri->segment(5);
    //     }      

    //     $data = array(
    //         'folder'     => $this->global['folder'],
    //         'title'      => "List ".$this->global['title'],
    //         'title_list' => 'Tambah '.$this->global['title'],
    //         'dfrom'      => $dfrom,
    //         'dto'        => $dto,
    //     );

    //     $this->Logger->write('Membuka Menu List '.$this->global['title']);

    //     $this->load->view($this->global['folder'].'/vformlist', $data);
    // }

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

    public function jenistujuan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_jenis_kirimqc");
            $this->db->like("UPPER(i_tujuan)", $cari);
            $this->db->or_like("UPPER(e_tujuan)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $ijenistujuan){
                    $filter[] = array(
                    'id'   => $ijenistujuan->i_tujuan,  
                    'text' => $ijenistujuan->i_tujuan.'-'.$ijenistujuan->e_tujuan,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function jenistujuankirim(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_supplier");
            $this->db->like("UPPER(i_supplier)", $cari);
            $this->db->or_like("UPPER(e_supplier_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $ijenistujuan){
                    $filter[] = array(
                    'id'   => $ijenistujuan->i_supplier,  
                    'text' => $ijenistujuan->i_supplier.'-'.$ijenistujuan->e_supplier_name,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function gudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_sub_bagian, e_sub_bagian from tm_sub_bagian where i_sub_bagian='SDP0009'");
           
            $data = $this->db->get();
            foreach($data->result() as  $ijenistujuan){
                    $filter[] = array(
                    'id'   => $ijenistujuan->i_sub_bagian,  
                    'text' => $ijenistujuan->i_sub_bagian.'-'.$ijenistujuan->e_sub_bagian,
                );
            }          
            echo json_encode($filter);
        }else {
            echo json_encode($filter);
        }
    }

    public function gettujuan(){
        $itujuan = $this->input->post('itujuan');
        $query = $this->mmaster->gettujuann($itujuan);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                 $c.="<option value=".$row->kode." >".$row->kode." - ".$row->nama."</option>";
            }
            $kop  = "<option value=\"\">Pilih Tujuan Kirim".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada Tujuan Kirim</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function tujuankirim(){
        $filter = [];
        $itujuan = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" * from (
                            SELECT b.i_tujuan as tujuan,a.i_kode_master as kode , a.e_nama_master as nama FROM ttr_master_gudang a , tr_jenis_kirimqc b
                            where i_tujuan='GU'
                            union all
                            SELECT b.i_tujuan as tujuan ,a.i_unit_packing as kode ,a.e_nama_packing as nama FROM tr_unit_packing a,tr_jenis_kirimqc b
                            where i_tujuan='UP'
                            union all
                            SELECT b.i_tujuan as tujuan ,a.i_unit_jahit as kode , a.e_unitjahit_name as nama FROM tr_unit_jahit a,tr_jenis_kirimqc b
                            where i_tujuan='UJ'
                            ) as a 
                            where tujuan ='$itujuan'
                            order by tujuan, kode", false);
            $data = $this->db->get();
            foreach($data->result() as  $nota){       
                    $filter[] = array(
                    'id' => $nota->i_nota,  
                    'text' => $nota->i_nota//.' - '.$nota->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    function dataproduct(){
        $filter = [];
        $iunit = $this->uri->segment(4);
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct(a.i_product) , b.e_product_namewip, a.i_color , c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b","a.i_product=b.i_product_wip");
            $this->db->join("tr_color c","a.i_color=c.i_color ");
            $this->db->order_by('a.i_product', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    // 'id' => $product->i_product,  
                    // 'text' => $product->i_product.' - '.$product->e_product_namewip.' - '.$product->e_color_name
                    'id' => $product->i_product,  
                    'name' => $product->e_product_namewip, 
                    'text' => $product->i_product.' - '.$product->e_product_namewip.' - '.$product->e_color_name
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
        $this->db->select("distinct(a.i_product) , b.e_product_namewip, a.i_color , c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b","a.i_product=b.i_product_wip");
            $this->db->join("tr_color c","a.i_color=c.i_color ");
            $this->db->where("UPPER(a.i_product)", $iproduct);          
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

        $igudangqc  = $this->input->post('igudangqc', TRUE);
        // var_dump($igudangqc);
        // die();
        // $iperiode  = $this->input->post('thnforecast',TRUE).$this->input->post('blnforecast',TRUE);
        $iforcast    = $this->input->post('forcast', TRUE);
        $itujuan     = $this->input->post('itujuan', TRUE);
        // $igudangqc   = $this->input->post('igudang', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $itujuankirim= $this->input->post('itujuankirim', TRUE);
        $jml         = $this->input->post('jml', TRUE);
            
        $this->db->trans_begin();
        $isj       =$this->mmaster->runningnumber($thbl);
        $this->mmaster->insertheader($isj, $datesj, $iforcast, $eremark, $itujuan, $igudangqc, $itujuankirim);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);

        for($i=1;$i<=$jml;$i++){
              $iproduct         = $this->input->post('iproduct'.$i, TRUE);
              $icolor           = $this->input->post('icolor'.$i, TRUE);
              $eproductname     = $this->input->post('eproduct'.$i, TRUE);
              $nquantity        = $this->input->post('nquantity'.$i, TRUE);
              $eremarkh         = $this->input->post('eremarkh'.$i, TRUE);
              $nitemno          = $i;
            if(($nquantity == 0)||($nquantity == '')){
                exit;
            }else{
                $this->mmaster->insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $eremarkh, $nitemno);
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

    public function view(){
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
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){
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
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->change($kode);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->reject($kode);
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $i_bonk   = $this->input->post('isj');
        $this->db->trans_begin();
        $this->mmaster->approve($i_bonk);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $i_bonk,
            );
        }
        $this->load->view('pesan', $data);
    }

    function update(){
        $isj         = $this->input->post('isj', TRUE);
        // $dsj         = $this->input->post('dsj', TRUE);
        $igudangqc   = $this->input->post('igudangqc',TRUE);
        $ijenis      = $this->input->post('ijenis',TRUE);
        $iperiode    = $this->input->post('forcast',TRUE);
        $eremark     = $this->input->post('eremark',TRUE);
        $jml         = $this->input->post('jml', TRUE);

        $datesj      = $this->input->post('dsj', TRUE);
        if($datesj!=''){
            $tmp=explode("-",$datesj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }

            $this->db->trans_begin();
            $this->mmaster->updateheader($isj, $dsj, $igudangqc, $ijenis, $iperiode, $eremark);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isj);
            $this->mmaster->deletedetail($isj);
            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $icolor           = $this->input->post('icolor'.$i, TRUE);
                $eproductname     = $this->input->post('eproduct'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                $eremarkh         = $this->input->post('eremarkh'.$i, TRUE);
                $nitemno          = $i;
                if(($nquantity == 0)||($nquantity == '')){
                    exit;
                }else{
                    $this->mmaster->deletedetail($isj, $iproduct, $icolor);
                    $this->mmaster->insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $eremarkh, $nitemno);
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
