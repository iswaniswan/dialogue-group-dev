<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {
    
    public $global  = array();
    public $i_menu  = '2050102';
    public $i_menu1 = '2050123';
    
    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->company = $this->session->id_company;

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    /*----------  DEFAULT CONTROLLERS  ----------*/    
    
    public function index()
    {
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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );
        
        $this->Logger->write('Membuka Menu ' . $this->global['title']);
        
        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }
    
    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/    
    
    public function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }
    
    /*----------  MEMBUKA FORM TAMBAH DATA  ----------*/
    
    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'], 
            'bagian'        => $this->mmaster->bagian(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "BBM-".date('ym')."-1234",
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
        );
        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    /*----------  RUNNING NO DOKUMEN  ----------*/
    
    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }
    
    /*----------  CEK NO DOKUMEN  ----------*/    
    
    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    /*----------  DATA PENGIRIM SESUAI MENU DAN BAGIAN  ----------*/    
    
    public function pengirim()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $q = $this->input->get('q');
            $q = str_replace("'", "", $q);
            $ibagian = $this->input->get('ibagian');
            $i_menu = $this->i_menu;

            $data = $this->mmaster->pengirim($i_menu, $ibagian, $q);

            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->e_bagian_name,
                        'name' => $key->name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data."
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Bagian Pembuat tidak boleh kosong!"
            );
        }
        echo json_encode($filter);
    }
    
    /*----------  DATA REFERENSI SESUAI SESUAI PENGIRIM  ----------*/
    
    public function referensi()
    {
        $filter = [];
        if ($this->input->get('ipengirim')!='' && $this->input->get('ibagian')!='') {

            $q = str_replace("'", "", $this->input->get('q'));
            $ipengirim = $this->input->get('ipengirim');
            $ibagian = $this->input->get('ibagian');

            $data = $this->mmaster->datareferensi($q, $ipengirim, $ibagian);

            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->i_document
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data."
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Bagian Pengirim tidak boleh kosong!"
            );
        }
        echo json_encode($filter);
    }
    
    /*----------  DETAIL ITEM REFERENSI  ----------*/    
    
    public function detailreferensi()
    {
        $id = $this->input->post('id');
        $ipengirim = $this->input->post('ipengirim');
        $ibagian = $this->input->post('ibagian');

        header("Content-Type: application/json", true);

        $data = $this->mmaster->headerreferensi($id, $ipengirim, $ibagian)->result();
        $detail = $this->mmaster->detailreferensi($id, $ipengirim, $ibagian)->result_array();

        $result = [
            'data'   => $data,
            'detail' => $detail
        ];

        echo json_encode($result);  
    }
    
    /*----------  DATA REFERENSI SESUAI SESUAI PENGIRIM  ----------*/
    
    public function product()
    {
        $filter = [];
        if ($this->input->get('q')!='') {
            $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')));
            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->i_product_base.' - '.$key->e_product_basename.' '.$key->e_color_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data."
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Cari Berdasarkan Kode / Nama!"
            );
        }
        echo json_encode($filter);
    }
    
    /*----------  SIMPAN DATA  ----------*/
    
    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument= date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ipengirim    = $this->input->post('ipengirim', TRUE);
        // $ijenis       = $this->input->post('ijenis',TRUE);
        $ijenis       = $this->input->post('id_jenis_barang',TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $dspbb        = $this->input->post('dspbb', TRUE); 
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($ibagian!='' && $ipengirim!='' && $ireff!='' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id,$idocument,$ddocument,$ibagian,$ipengirim,$ijenis,$ireff,$eremark);
            for($i=0;$i<$jml;$i++){
                $idproduct     = $this->input->post('idproduct'.$i, TRUE);
                $nquantityreff = $this->input->post('nquantity'.$i, TRUE);
                $nquantity     = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark       = $this->input->post('eremark'.$i, TRUE);
                if (($idproduct!='' || $idproduct!=null) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id,$ireff,$idproduct,$nquantity,$nquantityreff,$eremark);
                }
            } 
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }
    
    /*----------  MEMBUKA FORM EDIT  ----------*/
    
    public function edit()
    {
        
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'number'     => "BBK-".date('ym')."-123456",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'pengirim' => $this->mmaster->pengirim(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
        );
        
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        if($this->uri->segment(7)==0){
            $this->load->view($this->global['folder'].'/vformupdate', $data);    
        }else{
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }

    }
    
    /*----------  UPDATE DATA  ----------*/
    
    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }        
        
        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument= date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ipengirim    = $this->input->post('ipengirim', TRUE);
        $ijenis       = $this->input->post('ijenis', TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $dspbb        = $this->input->post('dspbb', TRUE); 
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($id!='' && $ibagian!='' && $ipengirim!='' && $ireff!='' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$ddocument,$ibagian,$ipengirim,$ijenis,$ireff,$eremark);
            $this->mmaster->delete($id);
            for($i=0;$i<$jml;$i++){
                $idproduct     = $this->input->post('idproduct'.$i, TRUE);
                $nquantityreff = $this->input->post('nquantity'.$i, TRUE);
                $nquantity     = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark       = $this->input->post('eremark'.$i, TRUE);
                if (($idproduct!='' || $idproduct!=null) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id,$ireff,$idproduct,$nquantity,$nquantityreff,$eremark);
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
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Update Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }
    
    /*----------  MEMBUKA MENU FORM VIEW  ----------*/
    
    public function view()
    {
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
        );
        
        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
    
    /*----------  MEMBUKA MENU FORM APPROVE  ----------*/
    
    public function approval()
    {
        
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );
        
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
    
    /*----------  UPDATE STATUS DOKUMEN  ----------*/    
    
    public function changestatus()
    {
        
        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id,$istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
    }
    
    /*----------  MEMBUKA FORM TAMBAH DATA TANPA REFERENSI  ----------*/
    
    public function tambahmanual()
    {
        $data = check_role($this->i_menu1, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'], 
            'bagian'        => $this->mmaster->bagian(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "BBM-".date('ym')."-123456",
        );
        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title'].' Manual');
        
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }


    
    /*----------  SIMPAN DATA MANUAL ----------*/
    
    public function simpanmanual()
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument= date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ipengirim    = $this->input->post('ipengirim', TRUE);
        $ireff        = 0;
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($ibagian!='' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $getidpengirim = $this->mmaster->idbagian($ibagian);
            if($getidpengirim->num_rows()>0){
                $ipengirim = $getidpengirim->row()->id;
            }else{
                $ipengirim = 0;
            }
            $this->mmaster->simpan($id,$idocument,$ddocument,$ibagian,$ipengirim,$ireff,$eremark);
            for($i=1;$i<=$jml;$i++){
                $idproduct     = $this->input->post('idproduct'.$i, TRUE);
                $nquantityreff = $this->input->post('npemenuhan'.$i, TRUE);
                $nquantity     = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark       = $this->input->post('eremark'.$i, TRUE);
                if (($idproduct!='' || $idproduct!=null) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id,$ireff,$idproduct,$nquantity,$nquantityreff,$eremark);
                }
            } 
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Id : '.$id.' Manual, Kode : '.$idocument);
            }
        }else{
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    /*----------  UPDATE DATA MANUAL  ----------*/
    
    public function updatemanual()
    {
        $data = check_role($this->i_menu1, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }        
        
        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument= date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ipengirim    = $this->input->post('ipengirim', TRUE);
        $ireff        = 0; 
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($id!='' && $ibagian!='' && $ipengirim!='' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$ddocument,$ibagian,$ipengirim,$ireff,$eremark);
            $this->mmaster->delete($id);
            for($i=1;$i<=$jml;$i++){
                $idproduct     = $this->input->post('idproduct'.$i, TRUE);
                $nquantityreff = $this->input->post('npemenuhan'.$i, TRUE);
                $nquantity     = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark       = $this->input->post('eremark'.$i, TRUE);
                if (($idproduct!='' || $idproduct!=null) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id,$ireff,$idproduct,$nquantity,$nquantityreff,$eremark);
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
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Update Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }
}

/* End of file Cform.php */