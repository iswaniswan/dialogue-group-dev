<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010402';

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

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data()
    {
        echo $this->mmaster->data($this->i_menu,$this->global['folder']);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function group()
    {
        $data = $this->mmaster->get_group($this->input->post('kode',TRUE),$this->input->post('head',TRUE));
        if ($data!='') {
            echo json_encode($data);
        }else{
            echo json_encode(null);
        }
    }

    public function bagian()
    {
        $filter = [];
        //$lokasi = $this->input->get('lokasi');
            $data = $this->mmaster->get_bagian($this->input->get('q'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->head,  
                    'text' => $key->head,
                );
            }
            echo json_encode($filter);
    }

    public function jenis()
    {
        $filter = [];
        $head = $this->input->get('head');
            $data = $this->mmaster->get_jenisgudang($this->input->get('q'),$head);
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_type,  
                    'text' => $key->e_type_name,
                );
            }
            echo json_encode($filter);
    }

    public function kelompok()
    {
        $filter = [];
        if ($this->input->get('igroup')!='') {
            $data = $this->mmaster->get_kelompok($this->input->get('q'),$this->input->get('igroup'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,  
                    'text' => $key->e_nama_kelompok,
                );
            }
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

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
            'lokasigudang'  => $this->mmaster->get_lokasigudang()->result(),
            'jahit'         => $this->db->get('tr_kategori_jahit'),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikode     = $this->input->post('ikode', TRUE);
        $enama     = $this->input->post('enama', TRUE);
        $ilokasi   = $this->input->post('ilokasi', TRUE);
        $ijenis    = $this->input->post('ijenis', TRUE);
        $jenis_bagian    = $this->input->post('jenis_bagian', TRUE);
        $inter_exter    = $this->input->post('inter_exter', TRUE);
        
        if ($ikode != '' && $enama != '' && $ilokasi != '' && $ijenis != ''){
            $cekada = $this->mmaster->cek_kode($ikode);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->insert($ikode,$enama,$ilokasi,$ijenis,$jenis_bagian,$inter_exter);
                if ($this->input->post('ikelompok', TRUE) != NULL) {
                    foreach ($this->input->post('ikelompok', TRUE) as $key) {
                        $ikelompok = $key;
                        $this->mmaster->insertdetail($ikode,$ikelompok);
                    }
                } 
                
                if($this->db->trans_status() === False){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikode);
                    $data = array(
                        'sukses'  => true,
                        'kode'    => $ikode
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $kode   = $this->uri->segment(5);

        $data   = $this->mmaster->cek_data($id)->row();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $data,
            'jenisgudang'   => $this->mmaster->get_jenis($data->i_type)->row_array(),
            'lokasigudang'  => $this->mmaster->get_lokasigudang()->result(),
            'detail'        => $this->mmaster->get_detailbagian($kode)->result(),
            'jahit'         => $this->db->get('tr_kategori_jahit'),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id        = $this->input->post('id', TRUE);
        $ikode     = $this->input->post('ikode', TRUE);
        $enama     = $this->input->post('enama', TRUE);       
        $ilokasi   = $this->input->post('ilokasi', TRUE);
        $ijenis    = $this->input->post('ijenis', TRUE);
        $jenis_bagian    = $this->input->post('jenis_bagian', TRUE);    
        $inter_exter    = $this->input->post('inter_exter', TRUE);
        $jml       = $this->input->post('jml', TRUE);    

        if ($id != '' && $ikode != '' && $enama != ''  && $ilokasi != '' && $ijenis != ''){
            $this->db->trans_begin();
            $this->mmaster->update($id,$ikode,$enama,$ilokasi,$ijenis,$jenis_bagian,$inter_exter);
            $this->mmaster->deletedetail($ikode);
            if ($this->input->post('ikelompok', TRUE) != NULL) {
                foreach ($this->input->post('ikelompok', TRUE) as $key) {
                    $ikelompok = $key;
                    $this->mmaster->insertdetail($ikode,$ikelompok);
                }
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikode);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ikode
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $kode   = $this->uri->segment(5);

        $data   = $this->mmaster->cek_data($id)->row();

        $query = $this->db->query("SELECT a.*, c.e_departement_name, b.e_type_name, d.e_lokasi_name FROM tr_bagian a
        INNER JOIN tr_type b ON (a.i_type = b.i_type)
        INNER JOIN public.tr_departement c ON (c.i_departement = b.i_departement)
        INNER JOIN tr_lokasi d ON (d.i_lokasi = a.i_lokasi AND a.id_company = d.id_company) WHERE a.id = '$id' ");

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $data,
            'jenisgudang'   => $this->mmaster->get_jenis($data->i_type)->row_array(),
            'lokasigudang'  => $this->mmaster->get_lokasigudang()->result(),
            'detail'        => $this->mmaster->get_detailbagian($kode)->result(),
            'jahit'         => $this->db->get('tr_kategori_jahit'),
            'department'    => $this->db->get_where('public.tr_departement', ['f_status'=>'t']),
            'isi'           => $query->row(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */