<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010701';

    public function __construct(){
        
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        $this->load->library('pagination');

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index($offset=NULL){
        $dfrom = $this->input->post('dfrom');
        $dto   = $this->input->post('dto');

        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }
        if($dto == ''){
            $dto = $this->uri->segment(5);
        }
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['offset'] = $offset;
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->uri->segment(4);
		echo $this->mmaster->data($dfrom, $this->global['folder'], $this->i_menu);
    }

    public function getkelompok(){
        $id = $this->input->post('id');
        $query = $this->mmaster->getkelompok($id);
        if($query->num_rows()>0) {
            $c  = "";
            $kelompok = $query->result();
            foreach($kelompok as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->i_kode_kelompok." - ".$row->e_nama."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Kelompok Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Kelompok Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function satuan(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_satuan a");
            $data = $this->db->get();
            foreach ($data->result() as $icolor) {
                $filter[] = array(
                    'id' => $icolor->i_satuan,
                    'text' => $icolor->e_satuan,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getkel(){
        $igroupbrg = $this->input->post('igroupbrg');
        $query = $this->mmaster->getkel($igroupbrg);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_kode_kelompok." >".$row->i_kode_kelompok." - ".$row->e_nama."</option>";
            }
            $kop  = "<option value=\"AKB\"> -- Semua Kategori Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getid(){
        $ikode2 = $this->input->post('ikodeunit');
        $query = $this->mmaster->get_kodeunit($ikode2);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->id." >".$row->id."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Jenis Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'supplier'      => $this->mmaster->get_supplier(),
            'groupbarang'   => $this->mmaster->get_groupbarang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }


    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier     = $this->input->post('isupplier',true);
        $ikodekelompok = $this->input->post('ikodekelompok',true);
        $ikodejenis    = $this->input->post('ikodejenis',true);
        $igroupbrg     = $this->input->post('igroupbrg',true);
        $imaterial     = $this->input->post('imaterial',true);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'datasup'       => $this->mmaster->cek_sup($isupplier)->row(),
            'groupbarang'   => $this->mmaster->cek_group($igroupbrg)->row(),
            'proses'        => $this->mmaster->get_hargas($ikodekelompok, $ikodejenis, $isupplier, $imaterial),
            'satuan'        => $this->mmaster->get_satuan(),
            'supplier'      => $this->mmaster->get_supplier(),
            'groupbarang'   => $this->mmaster->get_groupbarang(),
            'kodekelompok'  => $this->mmaster->getkelompok($isupplier)
        );

        if($isupplier == '' || $ikodekelompok == ''){
            $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

            $this->load->view($this->global['folder'].'/vformadd', $data);  
        }else{
            $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

            $this->load->view($this->global['folder'].'/vforminput', $data);
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier       = $this->input->post('isupplier', TRUE);       
        $igroupbrg       = $this->input->post('igroupbrg', TRUE);   
        $itypemakloon    = $this->input->post('itypemakloon', TRUE);
        $ikodekelompok   = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis      = $this->input->post('ikodejenis', TRUE);   
        $jml             = $this->input->post('jml', TRUE);

        if ($isupplier != ''){
                for($i=1;$i<=$jml;$i++){ 
                    if($this->input->post('cek'.$i)=='cek'){
                        $kodebrg        = $this->input->post('kodebrg'.$i, TRUE); 
                        $harga          = $this->input->post('harga'.$i, TRUE);
                        $harga          = str_replace(',','',$harga);
                        $isatuan        = $this->input->post('isatuan'.$i, TRUE);
                        $dateberlaku    = $this->input->post('dberlaku'.$i, TRUE);
                        if($dateberlaku){
                             $tmp   = explode('-', $dateberlaku);
                             $day   = $tmp[0];
                             $month = $tmp[1];
                             $year  = $tmp[2];
                             $yearmonth = $year.$month;
                             $dberlaku = $year.'-'.$month.'-'.$day;
                        }
                        $ipriceno = $i;
                        $itipe    = $this->input->post('itipe'.$i, TRUE);
        
                        $this->mmaster->insert($isupplier, $kodebrg, $harga, $ipriceno, $dberlaku, $igroupbrg, $itypemakloon, $ikodekelompok, $ikodejenis, $isatuan,  $itipe);                     
                    }
                }          
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isupplier
                );
        }else{
                $data = array(
                    'sukses' => false,
        );
        }
        $this->load->view('pesan', $data);     
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier 	    = $this->input->post('isupplier', TRUE);
        $igroupbrg      = $this->input->post('igroupbrg', TRUE);
        $itypemakloon    = $this->input->post('itypemakloon', TRUE);
        $ikodekelompok  = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis     = $this->input->post('ikodejenis', TRUE);   
        $kodebrg 	    = $this->input->post('kodebrg', TRUE);
        $harga 		    = $this->input->post('harga', TRUE);
        $isatuan        = $this->input->post('isatuan', TRUE);
        $itipe          = $this->input->post('itipe', TRUE);
        $dateberlaku    = $this->input->post('dberlaku', TRUE);
        $datesebelum    = $this->input->post('dberlakusebelum', TRUE);
        $ipriceno       = '1';

        $tmp   = explode('-', $dateberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $datesebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dsebelum = $year.'-'.$month.'-'.$day;

        if ($isupplier != '' && $harga != ''){
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$kodebrg);
                if($dberlaku == $dsebelum){
                    $this->mmaster->update($isupplier, $kodebrg, $harga, $itipe, $isatuan, $dsebelum, $dberlaku);
                }else{
                    $this->mmaster->insert($isupplier, $kodebrg, $harga, $ipriceno, $dberlaku, $igroupbrg, $itypemakloon, $ikodekelompok, $ikodejenis, $isatuan,  $itipe);
                    $this->mmaster->updatetglakhir($isupplier, $kodebrg, $dsebelum, $dberlaku);
                }
                $data = array(
                    'sukses'    => true,
                    'kode'      => $kodebrg
                );
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier       = $this->uri->segment('4');
        $kodebrg         = $this->uri->segment('5');
        $dberlaku        = $this->uri->segment('6');
        $dfrom           = $this->uri->segment('7');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'data'          => $this->mmaster->cek_data($isupplier,$kodebrg,$dberlaku)->row(),
            'satuan'        => $this->mmaster->satuan()->result()
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function getproductname(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $this->db->select("id, kode_brg, nama_brg");
            $this->db->from("tm_barang_wip");
            $this->db->where("UPPER(kode_brg)", $iproduct);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("id, kode_brg, nama_brg");
            $this->db->from("tm_barang_wip");
            $this->db->like("UPPER(kode_brg)", $cari);
            $this->db->or_like("UPPER(nama_brg)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->kode_brg,  
                    'text' => $product->kode_brg//.' - '.$product->e_product_basename
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getjenis(){
            $ikodekelompok = $this->input->post('ikodekelompok');
            $query = $this->mmaster->getjenis($ikodekelompok);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_type_code." >".$row->i_type_code." - ".$row->e_type_name."</option>";
                }
                $kop  = "<option value=\"AJB\"> -- Semua Jenis Barang -- ".$c."</option>";
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

    public function getmaterial(){
            $isupplier= $this->input->post('isupplier');
            $ikodejenis= $this->input->post('ikodejenis');
            $ikodekelompok = $this->input->post('ikodekelompok');
            $query = $this->mmaster->getmaterial($isupplier,$ikodejenis, $ikodekelompok);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_material." >".$row->i_material." - ".$row->e_material_name."</option>";
                }
                $kop  = "<option value=\"BRG\"> -- Semua Barang -- ".$c."</option>";
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

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isupplier = $this->input->post('i_supplier', true);
        $kodebrg = $this->input->post('kode_brg', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isupplier, $kodebrg);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Harga Makloon by Supplier Print ' . $kodebrg);
            echo json_encode($data);
        }
    }

    public function view(){
        $isupplier       = $this->uri->segment('4');
        $kodebrg         = $this->uri->segment('5');
        $dberlaku        = $this->uri->segment('6');
        $dfrom           = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'data'       => $this->mmaster->cek_data($isupplier,$kodebrg,$dberlaku)->row(),
            'satuan'     => $this->mmaster->satuan()->result()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function view2(){
        $dfrom = $this->input->post('dberlaku');

        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }
        $data = array(
            'folder'      => $this->global['folder'],
            'title'       => "View ".$this->global['title'],
            'title_list'  => 'List '.$this->global['title'],
            'dfrom'       => $dfrom
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

}

/* End of file Cform.php */
