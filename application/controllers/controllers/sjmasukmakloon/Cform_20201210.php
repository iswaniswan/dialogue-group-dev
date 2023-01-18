<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050203';

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
    

    public function index(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $kelompokbrg= $this->session->userdata('kelompok_barang');
        $ibagian    = $this->session->userdata('i_bagian');
//var_dump($ibagian);
//die();
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany)->result(),
            'kelompokbrg'   => $kelompokbrg,
            'supplier'      => $this->mmaster->bacasupplier()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $i_material = $this->uri->segment(4);
            $ireferensi = $this->uri->segment(5);
            $kelompokbrg= $this->uri->segment(6);
            $data = $this->mmaster->product($cari,$i_material, $ireferensi, $kelompokbrg);
            foreach($data->result() as  $material){       
                $filter[] = 
                    array(
                        'id' => $material->i_material2,  
                        'text' => $material->i_material2.' - '.$material->e_material_name
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

      public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsjk = $this->input->post("dsjk",true);
        if ($dsjk) {
            $tmp    = explode('-', $dsjk);
            $day    = $tmp[0];
            $month  = $tmp[1];
            $year   = $tmp[2];
            $yearmonth = $year . $month;
            $datesjk = $year . '-' . $month . '-' . $day;
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $supplier           = $this->input->post('supplier', TRUE);
        $itypemakloon       = $this->input->post('itypemakloon', TRUE);
        $remark             = $this->input->post('eremark', TRUE);
        $inodoksup          = $this->input->post('ireff', TRUE);
        $nosjmasuk          = $this->mmaster->runningnumbermasukm($yearmonth,$ibagian);
        $jml                = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
        $this->mmaster->insertheader($nosjmasuk, $datesjk, $ibagian, $supplier, $itypemakloon, $remark, $inodoksup);
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
                if($cek=='on'){
                    $nosjkeluar     = $this->input->post('ireferensi'.$i, TRUE);
                    $imaterial_reff = $this->input->post('i_material'.$i, TRUE);
                    $imaterial      = $this->input->post('i_2material'.$i, TRUE);
                    $nquantity      = $this->input->post('n_qty'.$i, TRUE);
                    $nquantityy     = $this->input->post('n_2qty'.$i, TRUE);
                    $isatuan        = $this->input->post('i_satuan'.$i, TRUE);  
                    $isatuann       = $this->input->post('i_2satuan'.$i, TRUE);
                    $vprice         = $this->input->post('v_price'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);

                    $sisa           = $nquantityy - $nquantityy;
                    $q_uantity      = $this->mmaster->ceksjkeluar2($nosjkeluar, $imaterial_reff, $imaterial); 
                    $sisaa          = $q_uantity - $nquantity;
                    
                    $this->mmaster->insertdetail($nosjmasuk, $ibagian, $nosjkeluar, $imaterial_reff, $imaterial, $nquantity, $nquantityy, $isatuan, $isatuann, $edesc, $i, $vprice);
                    $this->mmaster->updatesjkeluar($nosjkeluar, $imaterial_reff, $imaterial, $sisa, $sisaa);
                }
            
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,         
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nosjmasuk);
            $data = array(
                'sukses' => true,
                'kode'      => $nosjmasuk,
            );
        }
        $this->load->view('pesan', $data);      
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $sj        = $this->uri->segment('4');
        $isupplier = $this->uri->segment('5');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'departement'   => $idepart,
            'data'          => $this->mmaster->baca($sj,$isupplier)->row(),
            'detail'        => $this->mmaster->bacadetail($sj,$isupplier)->result(),
            'ireferensi'    => $this->mmaster->bacareferensi($sj)
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $nosjmasuk    = $this->input->post('isjkm', TRUE);
        $dsjk         = $this->input->post("dsjk",true);
        if ($dsjk) {
            $tmp    = explode('-', $dsjk);
            $day    = $tmp[0];
            $month  = $tmp[1];
            $year   = $tmp[2];
            $yearmonth = $year . $month;
            $datesjk = $year . '-' . $month . '-' . $day;
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $remark       = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE); 

        //var_dump($nosjkeluar, $dsjk,$nosjmasuk, $istore,$supplier, $remark, $now);
        $this->db->trans_begin();
        $this->mmaster->updateheader($nosjmasuk, $datesjk, $ibagian, $remark);
        //$this->mmaster->deletedetail($istore, $nosjmasuk);
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
                if($cek=='on'){
                    $nosjkeluar     = $this->input->post('ireferensi'.$i, TRUE);
                    $imaterial_reff = $this->input->post('imaterial1'.$i, TRUE);
                    $imaterial      = $this->input->post('imaterial2'.$i, TRUE);
                    $nquantity      = $this->input->post('qty2'.$i, TRUE);
                    $isatuan        = $this->input->post('isatuan2'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                   // $this->mmaster->insertdetail($nosjmasuk,$istore,$nosjkeluar, $imaterial_reff, $imaterial, $nquantity,$isatuan, $edesc, $i);
                    $this->mmaster->updatedetail($nosjmasuk, $imaterial_reff, $imaterial, $nquantity, $edesc, $i);
                }
            
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Uddate Data '.$this->global['title'].' No SJ : '.$nosjmasuk);
            $data = array(
                'sukses'    => true,
                'kode'      => $nosjmasuk,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $isjkm = $this->input->post('isjkm');
        $this->mmaster->sendd($isjkm);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $isjkm = $this->input->post('isjkm');
        $this->mmaster->cancel_approve($isjkm);
    }

    // SJ Masuk Makloon
    public function getsjkm(){
        $isupplier = $this->input->post('isupplier');
        $query = $this->mmaster->getrefferensi($isupplier);
        if($query->num_rows()>0) {
            $c         = "";
            $reff  = $query->result();
            foreach($reff as $row) {
                $c.="<option value=".$row->i_sj." >".$row->i_sj." || ".$row->d_sj."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih No Refferensi -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">No Referensi Tidak Ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getdetailsjkm(){
        header("Content-Type: application/json", true);
        $isjkm  = $this->input->post('isjkm', FALSE);
        $gudang = $this->input->post('gudang', FALSE);
        $query  = array(
            'head'   => $this->mmaster->getsjkm($isjkm, $gudang)->row(),
            'detail' => $this->mmaster->getsjkm_detail($isjkm, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

    public function getdetailsjmm(){
        header("Content-Type: application/json", true);
        $isjkm  = $this->input->post('isjkm', FALSE);
        $isjmm  = $this->input->post('isjmm', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'detail' => $this->mmaster->getsjmm_detail($isjkm, $isjmm, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

     public function view(){

        $sj        = $this->uri->segment('4');
        $isupplier = $this->uri->segment('5');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'departement'   => $idepart,
            'data'          => $this->mmaster->baca($sj,$isupplier)->row(),
            'detail'        => $this->mmaster->bacadetail($sj,$isupplier)->result(),
            'ireferensi'    => $this->mmaster->bacareferensi($sj)
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approve(){

        $sj        = $this->uri->segment('4');
        $isupplier = $this->uri->segment('5');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'departement'   => $idepart,
            'data'          => $this->mmaster->baca($sj,$isupplier)->row(),
            'detail'        => $this->mmaster->bacadetail($sj,$isupplier)->result(),
            'ireferensi'    => $this->mmaster->bacareferensi($sj)
        );
        
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

     public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isjkm = $this->input->post('isjkm');
        
        $this->mmaster->approve($isjkm);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isjkm
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function change(){
        header("Content-Type: application/json", true);
        $isjkm = $this->input->post('isjkm');
        $this->mmaster->change_approve($isjkm);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $isjkm = $this->input->post('isjkm');
        $this->mmaster->reject_approve($isjkm);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isjkm = $this->input->post('isjkm', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isjkm);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Surat Keluar Makloon Bahan Baku' . $isjkm);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */
