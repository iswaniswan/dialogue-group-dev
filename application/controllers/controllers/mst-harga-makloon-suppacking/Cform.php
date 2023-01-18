<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010707';

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
        if ($dfrom=='') {
            $dfrom = $this->uri->segment(5);
            if ($dfrom=='') {
                $dfrom = date('d-m-Y');
            }
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
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom  = $this->uri->segment(4);
		echo $this->mmaster->data($this->i_menu, $dfrom);
    }

    public function satuan(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_satuan a");
            //$this->db->like("UPPER(i_satuan_code)", $cari);
            //$this->db->or_like("UPPER(e_satuan)", $cari);
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

    public function getrumus(){
        $satuan_awal = $this->input->post('satuan_awal');
        $satuan_akhir = $this->input->post('satuan_akhir');
        $query     = $this->mmaster->getrumus($satuan_awal, $satuan_akhir);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_satuan." >".$row->rumus_konversi."</option>";
            }
            $kop  = "<option value=".$row->rumus_konversi." >".$row->rumus_konversi."</option>";
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

    public function getrumuss(){
        $satuan_awal = $this->input->post('satuan_awal');
        $satuan_akhir = $this->input->post('satuan_akhir');
        $query     = $this->mmaster->getrumus($satuan_awal, $satuan_akhir);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_satuan." >".$row->rumus_konversi."</option>";
            }
            $kop  = "<option value=".$row->angka_faktor_konversi." >".$row->angka_faktor_konversi."</option>";
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'supplier'      => $this->mmaster->get_supplier()->result(),
            'groupbarang'   => $this->mmaster->get_groupbarang()->result(),
            'kodekelompok'  => $this->mmaster->get_kodekelompok()->result(),
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
            // 'data'          => $this->mmaster->cek_dataproses($ikodekelompok, $ikodejenis)->row(),
            'proses'        => $this->mmaster->get_hargas($ikodekelompok, $ikodejenis, $isupplier, $imaterial),
            'satuan'        => $this->mmaster->get_satuan()->result(),
            //'proses'        => $this->mmaster->get_data_harga($ikodekelompok, $ikodejenis, $isupplier),
        );
        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);  
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier       = $this->input->post('isupplier', TRUE);       
        $igroupbrg       = $this->input->post('igroupbrg', TRUE);   
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
                        $dberlaku       = $this->input->post('dberlaku'.$i, TRUE);
                        if($dberlaku){
                             $tmp   = explode('-', $dberlaku);
                             $day   = $tmp[0];
                             $month = $tmp[1];
                             $year  = $tmp[2];
                             $yearmonth = $year.$month;
                             $dateberlaku = $year.'-'.$month.'-'.$day;
                        }
                        $ipriceno = $i;
                        $itipe    = $this->input->post('itipe'.$i, TRUE);
        
                        $this->mmaster->insert($isupplier, $kodebrg, $harga, $ipriceno, $dateberlaku, $igroupbrg, $ikodekelompok, $ikodejenis, $isatuan,  $itipe);                     
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $kodebrg         = $this->uri->segment('4');
        $isupplier       = $this->uri->segment('5');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($kodebrg,$isupplier)->row(),
            'satuan'        => $this->mmaster->satuan()->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier      = $this->input->post('isupplier', TRUE);
        $igroupbrg      = $this->input->post('igroupbrg', TRUE);
        $ikodekelompok  = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis     = $this->input->post('ikodejenis', TRUE);   
        $kodebrg        = $this->input->post('kodebrg', TRUE);
        $harga          = $this->input->post('harga', TRUE);
        $isatuan        = $this->input->post('isatuan', TRUE);
        $itipe          = $this->input->post('itipe', TRUE);
        $dateberlaku    = $this->input->post('dberlaku', TRUE);
        $dsebelum       = $this->input->post('dberlakusebelum', TRUE);
        $ipriceno       = '1';

        $tmp   = explode('-', $dateberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dateberlaku = $year.'-'.$month.'-'.$day;

        if ($isupplier != '' && $harga != ''){
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$kodebrg);
                $this->mmaster->insert($isupplier, $kodebrg, $harga, $ipriceno, $dateberlaku, $igroupbrg, $ikodekelompok, $ikodejenis, $isatuan,  $itipe);
                $this->mmaster->update($isupplier, $kodebrg, $dsebelum, $dateberlaku);
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
            $this->Logger->write('Cancel Harga Makloon by Supplier Packing ' . $kodebrg);
            echo json_encode($data);
        }
    }

    // public function update(){

    //         $data = check_role($this->i_menu, 3);
    //         if(!$data){
    //             redirect(base_url(),'refresh');
    //         }
            
    //         $isupplier 	    = $this->input->post('isupplier', TRUE);
    //         $kodebrg 	    = $this->input->post('kodebrg', TRUE);
    //         $harga 		    = $this->input->post('harga', TRUE);
            
    //         $satuan         = $this->input->post('isatuan', TRUE);
          
    //         $itipe          = $this->input->post('itipe', TRUE);
    
    //         if ($isupplier != '' && $harga != ''){
    //                 $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$kodebrg);
    //                 $this->mmaster->update($isupplier, $kodebrg, $harga, $satuan, $itipe);
    //                 $data = array(
    //                     'sukses'    => true,
    //                     'kode'      => $kodebrg
    //                 );
    //         }else{
    //             $data = array(
    //                 'sukses' => false,
    //             );
    //         }
    //         $this->load->view('pesan', $data);  
    // }

    public function ubah(){

            $data = check_role($this->i_menu, 3);
            if(!$data){
                redirect(base_url(),'refresh');
            }
            
            $isupplier       = $this->input->post('isupplier', TRUE);       
            $igroupbrg       = $this->input->post('igroupbrg', TRUE);   
            $ikodekelompok   = $this->input->post('ikodekelompok', TRUE);   
            $ikodejenis      = $this->input->post('ikodejenis', TRUE);   
            $isatuan   = $this->input->post('isatuan', TRUE);
            $norder   = $this->input->post('norder', TRUE);
            $dberlaku = $this->input->post('dberlaku', TRUE);
            if($dberlaku){
                 $tmp   = explode('-', $dberlaku);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $dateberlaku = $year.'-'.$month.'-'.$day;
            }
    
            if ($isupplier != '' && $harga != ''){
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$kodebrg);
                    $this->mmaster->insertubah($isupplier, $kodebrg, $isatuan, $harga, $igroupbrg, $ikodekelompok, $ikodejenis, $norder, $dateberlaku);
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

    public function view(){

        $kodebrg         = $this->uri->segment('4');
        $isupplier       = $this->uri->segment('5');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($kodebrg,$isupplier)->row(),
            'satuan'        => $this->mmaster->satuan()->result(),
            // 'data2' => $this->mmaster->cek_data2($id,$iunitjahit)->result()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
