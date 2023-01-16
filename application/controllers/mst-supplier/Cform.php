<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010806';

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

    public function getsuppliergroup(){
        header("Content-Type: application/json", true);
        $id   = $this->input->post('id');

        $query  = array(
            'isi' => $this->mmaster->getsuppliergroup($id)->row(),
        );
        echo json_encode($query); 
    }

    public function getkategoriproduct(){
        $ijenismakloon = $this->input->post('ijenismakloon');
        $idcompany     = $this->session->userdata('id_company');

        $query = $this->mmaster->getkategoriproduct($ijenismakloon, $idcompany);
        if($query->num_rows()>0) {
            $c  = "";
            $kategoriproduct = $query->result();
            foreach($kategoriproduct as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->e_nama."</option>";
            }
            // $kop  = "<option value=\"all\">Semua Kategori Barang".$c."</option>";
            $kop = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getkategoriproduct2(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');

        $data = $this->mmaster->getkategoriproduct2($cari, $idcompany);
        foreach($data->result() as $ma){       
            $filter[] = array(
                    'id'    => $ma->i_kode_kelompok, 
                    'name'  => $ma->e_nama,  
                    'text'  => $ma->e_nama
            );
        }
        echo json_encode($filter);
    }

    public function getmakloon2(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
       
        $data = $this->mmaster->getmakloon2($cari);
        foreach($data->result() as $ma){       
            $filter[] = array(
                    'id'    => $ma->i_type_makloon, 
                    'name'  => $ma->e_nama,  
                    'text'  => $ma->e_nama
            );
        }
        echo json_encode($filter);
    }

    public function getjenisindustry(){
        $isuppliergroup = $this->input->post('isuppliergroup');
        $idcompany  = $this->session->userdata('id_company');

        $query = $this->mmaster->getjenisindustry($isuppliergroup, $idcompany);
        if($query->num_rows()>0) {
            $c  = "";
            $jenisindustry = $query->result();
            foreach($jenisindustry as $row) {
                $c.="<option value=".$row->i_type_industry." >".$row->e_type_industry_name."</option>";
            }
            $kop  = "<option value=\"\">Pilih Jenis Partner".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getkategoripembelian(){
        $query = $this->mmaster->getkategoripembelian();
        if($query->num_rows()>0) {
            $c  = "";
            $kategoriproduct = $query->result();
            foreach($kategoriproduct as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->e_nama."</option>";
            }
            // $kop  = "<option value=\"all\">Semua Kategori Barang".$c."</option>";
            $kop = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function data(){
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bank'          => $this->mmaster->get_bank($idcompany),
            'typepajak'     => $this->mmaster->get_type_pajak(),
            'suppliergroup' => $this->mmaster->getsupplier_group($idcompany),
            //'typeindustry'  => $this->mmaster->get_type_industry()->result(), 
            'levelcompany'  => $this->mmaster->get_level_company($idcompany)->result(),
            'jahit'         => $this->db->get('tr_kategori_jahit'),
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function status(){
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

    public function getjenissupplier(){
            $isuppliergroup = $this->input->post('isuppliergroup');
           
            $query = $this->mmaster->getjenissupplier($isuppliergroup);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_type." >".$row->e_type_name."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Jenis Barang Supplier -- ".$c."</option>";
                echo json_encode(array(
                    'kop'   => $kop
                ));
            }else{
                $kop  = "<option value=\"\">Data Kosong</option>";
                echo json_encode(array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                ));
            }
    }

    public function jenismakloon(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select("a.i_type_makloon, a.e_type_makloon_name");
        $this->db->from("tr_type_makloon a");
        $this->db->where("a.e_type_makloon_name ilike '%$cari%'");
        $this->db->where("a.id_company", $idcompany);
        $this->db->order_by("a.i_type_makloon");
        $data = $this->db->get();
        foreach ($data->result() as $itype) {
            $filter[] = array(
                'id' => $itype->i_type_makloon,
                'text' => $itype->e_type_makloon_name,
            );
        }
        echo json_encode($filter);
    }

    public function getpusat(){
        $isuppliergroup = $this->input->post('isuppliergroup');
        $ilevelcompany  = $this->input->post('ilevelcompany');
        $idcompany  = $this->session->userdata('id_company');

        if($ilevelcompany == 'PLV01'){
            $query = $this->mmaster->getpusat($isuppliergroup,'PLV00', $idcompany);
            if($query->num_rows()>0) {
                $c      = "";
                $jenis  = $query->result();
                foreach($jenis as $row) {
                    $c.="<option value=".$row->i_kepala_pusat." >".$row->e_pusat."</option>";
                }
                // $kop  = "<option value=\"\"> -- Pilih Kepala Group -- ".$c."</option>";
                $kop = $c;
                echo json_encode(array(
                    'kop'   => $kop
                ));
            }else{
                $kop  = "<option value=\"\">Kepala Group Kosong</option>";
                echo json_encode(array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                ));
            }
        }else{
            $kop  = "<option value=\"\"></option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_data($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_data_edit($this->input->post('kode',TRUE), $this->input->post('kodeold',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isupplier              = $this->input->post('isupplier', TRUE);
        $isuppliergroup         = $this->input->post('isuppliergroup', TRUE);
        $isuppliername          = $this->input->post('isuppliername', TRUE);
        $isupplieraddres        = $this->input->post('isupplieraddres', TRUE);       
        $isupplierpostalcode    = $this->input->post('isupplierpostalcode', TRUE);
        $isupplierphone         = $this->input->post('isupplierphone', TRUE);
        $isupplierfax           = $this->input->post('isupplierfax', TRUE);               
        $isuppliernpwp          = $this->input->post('isuppliernpwp', TRUE);
        $esuppliernpwpname      = $this->input->post('esuppliernpwpname', TRUE);
        $esupplierownername     = $this->input->post('esupplierownername', TRUE);
        $ftipepajak             = $this->input->post('ftipepajak', TRUE);
        $isuppliertoplength     = $this->input->post('isuppliertoplength', TRUE);
        $ijenispembelian        = $this->input->post('ijenispembelian', TRUE); 
        $isupplierpkp           = $this->input->post('isupplierpkp', TRUE);
        if (isset($isupplierpkp)){ 
            $pkp='t';
        } else { 
            $pkp='f';
        } 
        $isuppliercity          = $this->input->post('isuppliercity', TRUE); 
        $isuppliernpwpaddress   = $this->input->post('isuppliernpwpaddress', TRUE);
        $itypeindustry          = $this->input->post('itypeindustry', TRUE);
        $ilevelcompany          = $this->input->post('ilevelcompany', TRUE);
        $i_kategori_produk      = $this->input->post('ikategoriproduk[]', TRUE);
        $i_jenis_makloon        = $this->input->post('ijenismakloon[]', TRUE);
        $inorekening            = $this->input->post('inorekening', TRUE);
        $enamarekening          = $this->input->post('enamarekening', TRUE);
        $enamabank              = $this->input->post('ibank', TRUE);
        $isupplierdiskon        = $this->input->post('isupplierdiskon', TRUE);
        $ikepalapusat           = $this->input->post('ikepalapusat', TRUE);
        $inter_exter            = $this->input->post('inter_exter', TRUE);
        $idcompany              = $this->session->userdata('id_company');
        $jml                    = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->mmaster->insert($isupplier, $isuppliername, $isupplieraddres, $esupplierownername, $isupplierphone, $isupplierfax, $isupplierpostalcode, $isuppliercity, $pkp, $ftipepajak,$isuppliernpwp, $esuppliernpwpname, $isuppliernpwpaddress, $isuppliertoplength, $isuppliergroup, $itypeindustry, $ilevelcompany, $enamabank, $inorekening, $enamarekening, $isupplierdiskon, $ikepalapusat, $idcompany, $this->input->post('jenis_pembelian', TRUE), $inter_exter);

        if ($i_kategori_produk) {
            foreach($i_kategori_produk as $ikategoriproduk){
                    $ikategoriproduk = $ikategoriproduk;
                    $this->mmaster->insert_kelompokbarang($isupplier, $ikategoriproduk, $idcompany);
            }
        }
        
        if ($isuppliergroup == 'KTG02') {
            foreach($i_jenis_makloon as $ijenismakloon){
                $ijenismakloon = $ijenismakloon;
                $this->mmaster->insert_makloon($isupplier, $ijenismakloon, $idcompany);
            }
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,         
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isupplier);
            $data = array(
                'sukses' => true,
                'kode'   => $isupplier,
            );
        }

        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id             = $this->uri->segment(4);
        $isupplier      = $this->uri->segment(5);
        $isuppliergroup = $this->uri->segment(6);
        $ipusat         = $this->uri->segment(7);
        $ilevelcompany  = $this->uri->segment(8);
        $idcompany      = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->get_data($id)->row(),
            'typeindustry'  => $this->mmaster->get_type_industry($idcompany)->result(), 
            'levelcompany'  => $this->mmaster->get_level_company($idcompany)->result(), 
            'bank'          => $this->mmaster->get_bank($idcompany),
            'typepajak'     => $this->mmaster->get_type_pajak(),
            'suppliergroup' => $this->mmaster->getsupplier_group($idcompany),
            'makloon'       => $this->mmaster->getmakloon($isupplier, $idcompany)->result(),
            'kategori'      => $this->mmaster->getbarang($isupplier, $idcompany)->result(),
            'kepalapusat'   => $this->mmaster->getpusat($isuppliergroup,$ilevelcompany, $idcompany)->result(),
            'jahit'         => $this->db->get('tr_kategori_jahit'),
        );

        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function getbarangsupplier(){
        header("Content-Type: application/json", true);
        $isupplier     = $this->input->post('isupplier', FALSE);
        $query  = array(
            'detail' => $this->mmaster->getbarangsupplier($isupplier)->result_array()
        );
        echo json_encode($query);  
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isupplier = $this->input->post('i_supplier', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isupplier);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Supplier ' . $isupplier);
            echo json_encode($data);
        }
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id                     = $this->input->post('id', TRUE);
        $isupplierold           = $this->input->post('isupplierold', TRUE);
        $isupplier              = $this->input->post('isupplier', TRUE);
        $isuppliergroup         = $this->input->post('isuppliergroup', TRUE);
        $isuppliername          = $this->input->post('isuppliername', TRUE);
        $isupplieraddres        = $this->input->post('isupplieraddres', TRUE);       
        $isupplierpostalcode    = $this->input->post('isupplierpostalcode', TRUE);
        $isupplierphone         = $this->input->post('isupplierphone', TRUE);
        $isupplierfax           = $this->input->post('isupplierfax', TRUE);               
        $isuppliernpwp          = $this->input->post('isuppliernpwp', TRUE);
        $esuppliernpwpname      = $this->input->post('esuppliernpwpname', TRUE);
        $esupplierownername     = $this->input->post('esupplierownername', TRUE);
        $ftipepajak             = $this->input->post('ftipepajak', TRUE);
        $isuppliertoplength     = $this->input->post('isuppliertoplength', TRUE);
        $ijenispembelian        = $this->input->post('ijenispembelian', TRUE); 
        $isupplierpkp           = $this->input->post('isupplierpkp', TRUE);
        if (isset($isupplierpkp)){ 
            $pkp='t';
        } else { 
            $pkp='f';
        } 
        $isuppliercity          = $this->input->post('isuppliercity', TRUE); 
        $isuppliernpwpaddress   = $this->input->post('isuppliernpwpaddress', TRUE);
        $itypeindustry          = $this->input->post('itypeindustry', TRUE);
        $ilevelcompany          = $this->input->post('ilevelcompany', TRUE);
        $i_kategori_produk      = $this->input->post('ikategoriproduk[]', TRUE);
        $i_jenis_makloon        = $this->input->post('ijenismakloon[]', TRUE);
        $inorekening            = $this->input->post('inorekening', TRUE);
        $enamarekening          = $this->input->post('enamarekening', TRUE);
        $enamabank              = $this->input->post('ibank', TRUE);
        $isupplierdiskon        = $this->input->post('isupplierdiskon', TRUE);
        $ikepalapusat           = $this->input->post('ikepalapusat', TRUE);
        $inter_exter            = $this->input->post('inter_exter', TRUE);
        $idcompany              = $this->session->userdata('id_company');
        $jml                    = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->mmaster->deletedetail($isupplierold, $idcompany);
        $this->mmaster->update($id, $isupplierold, $isupplier, $isuppliername, $isupplieraddres, $esupplierownername, $isupplierphone, $isupplierfax, $isupplierpostalcode, $isuppliercity, $pkp, $ftipepajak,$isuppliernpwp, $esuppliernpwpname, $isuppliernpwpaddress, $isuppliertoplength, $isuppliergroup, $itypeindustry, $ilevelcompany, $enamabank, $inorekening, $enamarekening, $isupplierdiskon, $ikepalapusat, $idcompany, $this->input->post('jenis_pembelian', TRUE),$inter_exter);

        if ($i_kategori_produk) {
            foreach($i_kategori_produk as $ikategoriproduk){
                $ikategoriproduk = $ikategoriproduk;
                $this->mmaster->insert_kelompokbarang($isupplier, $ikategoriproduk, $idcompany);
            }
        }
        
        if ($isuppliergroup == 'KTG02') {
            foreach($i_jenis_makloon as $ijenismakloon){
                $ijenismakloon = $ijenismakloon;
                $this->mmaster->insert_makloon($isupplier, $ijenismakloon, $idcompany);
            }
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,         
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isupplier);
            $data = array(
                'sukses' => true,
                'kode'      => $isupplier,
            );
        }

        $this->load->view('pesan', $data);  
    }


    public function view(){

        $id             = $this->uri->segment(4);
        $isupplier      = $this->uri->segment(5);
        $isuppliergroup = $this->uri->segment(6);
        $ipusat         = $this->uri->segment(7);
        $ilevelcompany  = $this->uri->segment(8);
        $idcompany      = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->get_data($id)->row(),
            'typeindustry'  => $this->mmaster->get_type_industry($idcompany)->result(), 
            'levelcompany'  => $this->mmaster->get_level_company($idcompany)->result(), 
            'bank'          => $this->mmaster->get_bank($idcompany),
            'typepajak'     => $this->mmaster->get_type_pajak(),
            'suppliergroup' => $this->mmaster->getsupplier_group($idcompany),
            'makloon'       => $this->mmaster->getmakloon($isupplier, $idcompany)->result(),
            'kategori'      => $this->mmaster->getbarang($isupplier, $idcompany)->result(),
            'kepalapusat'   => $this->mmaster->getpusat($isuppliergroup,$ilevelcompany, $idcompany)->result(),
            'jahit'         => $this->db->get('tr_kategori_jahit'),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */