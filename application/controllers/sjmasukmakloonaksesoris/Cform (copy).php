<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2051207';

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
            'kodemaster'    => $this->mmaster->bacagudang(),
            // 'jnskeluar'=> $this->mmaster->bacajenis(),
            // 'tujuan'=> $this->mmaster->bacatujuan(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function getsjkm(){
        //var_dump($gudang);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang = $this->input->get('gudang', FALSE);
            $data = $this->mmaster->sjkm($cari,$gudang);
            foreach($data->result() as  $sjkm){       
                $filter[] = array(
                    'id' => $sjkm->i_sj,  
                    'text' => $sjkm->i_sj.' || '.$sjkm->d_sj
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    } 

    function datamaterial(){
      
        //var_dump($gudang);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang =  $this->uri->segment(4);
            // $this->db->select("a.*,b.e_satuan");
            // $this->db->from("tr_material a");
            // $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            //$this->db->where("a.i_store", $gudang);
            // $this->db->order_by('a.i_material', 'ASC');
            $data = $this->mmaster->product($cari,$gudang);
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id' => $material->i_material,  
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    function datamaterialsj(){
      
        //var_dump($gudang);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang =  $this->uri->segment(4);
            $sjkm =  $this->uri->segment(5);
            $data = $this->mmaster->productsj($cari,$gudang,$sjkm);
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id' => $material->i_material,  
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    function getmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->where("UPPER(i_material)", $imaterial);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getmaterialsj(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $isj = $this->input->post('i_sj');
        $gudang = $this->input->post('i_kode_master');
        $this->db->select("a.e_material_name, a.i_material, c.n_qty, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->join("tm_sjkeluarmakloonaksesoris_detail c","a.i_material = c.i_material");
            $this->db->where("UPPER(c.i_material)", $imaterial);
            $this->db->where("UPPER(c.i_sj)", $isj);   
            $this->db->where("UPPER(c.i_kode_master)", $gudang);          
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

      public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsjk = $this->input->post("dsjk",true);
        $tmp = explode('-', $dsjk);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = substr($tmp[2],2,2);
        $dsjk = $tmp[2].'-'.$bl.'-'.$hr;
        $istore             = $this->input->post('istore', TRUE);
        $jnskeluar          = $this->input->post('jnskeluar', TRUE);
        $supplier           = $this->input->post('supplier', TRUE);
        $remark             = $this->input->post('eremark', TRUE);
        $nosjkeluar         = $this->input->post('isjkm', TRUE);
        $nosjmasuk          = $this->mmaster->runningnumbermasukm($th,$bl,$istore);
        $jml                = $this->input->post('jml', TRUE); 
        $cancel             = 'f';
        
        //var_dump($istore, $jnskeluar, $supplier, $remark , $nosjkeluar, $nosjmasuk, $jml );
        $this->db->trans_begin();
        $this->mmaster->insertheader($nosjmasuk, $dsjk,$nosjkeluar,$istore,$supplier, $remark);
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
                if($cek=='on'){
                    $imaterial_reff      = $this->input->post('i_material'.$i, TRUE);
                    $imaterial      = $this->input->post('i_2material'.$i, TRUE);
                    $nquantity      = $this->input->post('n_2qty'.$i, TRUE);
                    $isatuan        = $this->input->post('i_2satuan'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    if ($imaterial_reff != "" && $imaterial != "") {
                        $this->mmaster->insertdetail($nosjmasuk,$istore,$nosjkeluar, $imaterial_reff, $imaterial, $nquantity,$isatuan, $edesc, $i);
                        //var_dump($nosjmasuk,$istore,$nosjkeluar, $imaterial_reff, $imaterial, $nquantity,$isatuan, $edesc, $i);
                    }   
                    //
                }
            
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,         
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nosjmasuk. ' Gudang : '.$istore);
            $data = array(
                'sukses' => true,
                'kode'      => $nosjmasuk,
            );
        }
        $this->load->view('pesan', $data);      

    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $sj = $this->uri->segment('4');
        $gudang = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'kodemaster'=> $this->mmaster->bacagudang(),
            // 'jnskeluar'=> $this->mmaster->bacajenis(),
            // 'supplier'=> $this->mmaster->bacatujuan(),
            'head' => $this->mmaster->baca_header($sj, $gudang)->row(),
            //'detail' => $this->mmaster->baca_detail($sj, $gudang)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function getdetailsjmm(){
        header("Content-Type: application/json", true);
        $isjkm  = $this->input->post('isjkm', FALSE);
        $isjmm  = $this->input->post('isjmm', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        //var_dump($isjkm, $isjmm, $gudang);
        $query  = array(
            'detail' => $this->mmaster->getsjmm_detail($isjkm, $isjmm, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsjk = $this->input->post("dsjk",true);
        $tmp = explode('-', $dsjk);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = substr($tmp[2],2,2);
        $dsjk = $tmp[2].'-'.$bl.'-'.$hr;
        $istore            = $this->input->post('istore', TRUE);
        $supplier           = $this->input->post('supplier', TRUE);
        $remark             = $this->input->post('eremark', TRUE);
        $nosjkeluar         = $this->input->post('isjkm', TRUE);
        $nosjmasuk          = $this->input->post('isjmm', TRUE);
        $jml                = $this->input->post('jml', TRUE); 
        $cancel             = 'f';
        $query      = $this->db->query("SELECT current_timestamp as c");
        $row        = $query->row();
        $now        = $row->c;
        //var_dump($nosjkeluar, $dsjk,$nosjmasuk, $istore,$supplier, $remark, $now);
        $this->db->trans_begin();
        $this->mmaster->updateheader($nosjkeluar, $dsjk,$nosjmasuk, $istore,$supplier, $remark, $now);
        $this->mmaster->deletedetail($nosjkeluar, $istore, $nosjmasuk);
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                    $imaterial_reff      = $this->input->post('i_material'.$i, TRUE);
                    $imaterial      = $this->input->post('i_2material'.$i, TRUE);
                    $nquantity      = $this->input->post('n_2qty'.$i, TRUE);
                    $isatuan        = $this->input->post('i_2satuan'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    if ($imaterial_reff != "" && $imaterial != "") {
                        $this->mmaster->insertdetail($nosjmasuk,$istore,$nosjkeluar, $imaterial_reff, $imaterial, $nquantity,$isatuan, $edesc, $i);
                        //var_dump($nosjmasuk,$istore,$nosjkeluar, $imaterial_reff, $imaterial, $nquantity,$isatuan, $edesc, $i);
                    }   
            }
            
        }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Uddate Data '.$this->global['title'].' No SJ : '.$nosjmasuk.' Gudang :'.$istore);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nosjkeluar,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $sj   = $this->input->post('sj');
        $gudang  = $this->input->post('gudang');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($sj, $gudang);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJ Keluar Makloon '.$sj.' Gudang:'.$gudang);
            echo json_encode($data);
        }
    }

    // SJ Masuk Makloon

    public function getdetailsjkm(){
        header("Content-Type: application/json", true);
        $isjkm  = $this->input->post('isjkm', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->getsjkm($isjkm, $gudang)->row(),
            'detail' => $this->mmaster->getsjkm_detail($isjkm, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

     public function view(){

        $sj = $this->uri->segment('4');
        $gudang = $this->uri->segment('5');

        $data = array(
             'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'kodemaster'=> $this->mmaster->bacagudang(),
            'head' => $this->mmaster->baca_header($sj, $gudang)->row(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */
