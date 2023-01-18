<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {
    
    public $global = array();
    public $i_menu = '2050501';
    
    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $this->company      = $this->session->id_company;
        $this->username     = $this->session->username;
        $this->level        = $this->session->i_level;
        $this->departement  = $this->session->i_departement;
        
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    
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
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );
        
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
    
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
        
        echo $this->mmaster->data($this->global['folder'],$this->i_menu, $dfrom, $dto);
    }
    
    public function kelompok()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->kelompok($this->input->get('q'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Kategori',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,  
                    'text' => ucwords(strtolower($key->e_nama_kelompok)),
                );
            }
        }else{    
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Pembuat Tidak Boleh Kosong',
            );        
        }
        echo json_encode($filter);
    }
    
    public function jenis()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->jenis($this->input->get('q'),$this->input->get('ikategori'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Jenis',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_type_code,  
                    'text' => ucwords(strtolower($key->e_type_name)),
                );
            }          
        }else{            
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Pembuat Tidak Boleh Kosong',
            );   
        }
        echo json_encode($filter);
    }
    
    /*----------  CARI BARANG  ----------*/
    
    public function material()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->material(str_replace("'","",$this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ijenis'),$this->input->get('ibagian'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->i_material.' - '.$row->e_material_name,
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => 'Tidak Ada Data',
                );   
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Pembuat Tidak Boleh Kosong',
            );   
        }
        echo json_encode($filter);
    }
    
    public function tambah()
    {
        
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "PPS-".date('ym')."-123456",
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
        );
        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    public function number() 
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }
    
    public function cekkode() 
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $e_remark    = $this->input->post('eremark[]', true);
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $cekdata = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                    'ada'    => true,
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->mmaster->simpan($id,$idocument,$ddocument,$ibagian,$eremarkh);
                $no = 0;
                foreach ($this->input->post('idproduct[]', true) as $idproduct) {
                    $jumlah     = str_replace(',','',$this->input->post('njumlah[]', true))[$no];
                    $iventaris  = $this->input->post('noinventaris[]', true)[$no];
                    $tujuan     = $this->input->post('tujuan[]', true)[$no];
                    if (($idproduct != '' || $idproduct != null) && $jumlah > 0) {
                        $this->mmaster->simpandetail($id,$idproduct,$jumlah,$iventaris,$tujuan);
                    }
                    $no++;
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'ada'    => faLse,
                    'kode'   => $idocument,
                    'id'     => '',
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'ada'    => false,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
                'ada'    => faLse,
                'kode'   => $idocument,
                'id'     => '',
            );
        }
        echo json_encode($data);
    }    
    
    /*----------  MEMBUKA MENU EDIT  ----------*/
    
    public function edit()
    {
        
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "PPS-".date('ym')."-123456",
        );
        
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
    
    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ibagianold     = $this->input->post('ibagianold', TRUE);
        $id             = $this->input->post('id', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $e_remark    = $this->input->post('eremark[]', true);
            $this->db->trans_begin();
            $cekdata = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                    'ada'    => true,
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$eremarkh);
                $this->mmaster->deletedetail($id);
                $no = 0;
                foreach ($this->input->post('idproduct[]', true) as $idproduct) {
                    $jumlah     = str_replace(',','',$this->input->post('njumlah[]', true))[$no];
                    $iventaris  = $this->input->post('noinventaris[]', true)[$no];
                    $tujuan     = $this->input->post('tujuan[]', true)[$no];
                    if (($idproduct != '' || $idproduct != null) && $jumlah > 0) {
                        $this->mmaster->simpandetail($id,$idproduct,$jumlah,$iventaris,$tujuan);
                    }
                    $no++;
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'ada'    => faLse,
                    'kode'   => $idocument,
                    'id'     => '',
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'ada'    => false,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
                'ada'    => faLse,
                'kode'   => $idocument,
                'id'     => '',
            );
        }
        echo json_encode($data);
    }
    
    
    
    public function changestatus() {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
    }  
    
    
    /*----------  MEMBUKA MENU Approve  ----------*/
    
    public function approval()
    {
        
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "ADJ-".date('ym')."-123456",
        );
        
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
    
    
    
    public function view(){
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Detail ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "ADJ-".date('ym')."-123456",
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */
