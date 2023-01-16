<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010505';

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

        $dberlaku = $this->input->post('dberlaku', TRUE);
        if ($dberlaku == '') {
            $dberlaku = $this->uri->segment(4);
            if ($dberlaku == '') {
                $dberlaku = date('d-m-Y');
            }
        }

        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['offset'] = $offset;
        $this->pagination->initialize($config);
        $data["links"] = $this->pagination->create_links();
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dberlaku)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $dberlaku = $this->uri->segment(4);
        echo $this->mmaster->data($dberlaku, $this->i_menu, $this->global['folder']);
    }

    public function view2(){
        $dberlaku     = $this->input->post('dberlaku');
        $idcompany    = $this->session->userdata('id_company');

        if($dberlaku == ''){
            $dberlaku = $this->uri->segment(4);
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "View ".$this->global['title'],
            'title_list'=> 'List '.$this->global['title'],
            'dfrom'     => $dberlaku,

        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function status(){
        $data = check_role($this->i_menu, 3);

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        $str = explode('|', $id);
        $id        = $str[0];
        $iproduct  = $str[1];

        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id, $iproduct);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $group_supplier   = $this->session->userdata('group_supplier');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title']
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function kategoribarang(){
        $filter     = [];
        $cari       = strtoupper($this->input->get('q'));
        $idcompany  = $this->session->userdata('id_company');

        $data = $this->mmaster->kategoribarang($cari, $idcompany);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->id.'|'.$key->i_kode_kelompok,  
                'text' => $key->e_nama_kelompok,
            );
        }          
        echo json_encode($filter);
    }

    public function getjenisbarang(){
        $kodekelompok  = explode('|', $this->input->post('ikodekelompok'));
        $idkodekelompok= $kodekelompok[0];
        $ikodekelompok = $kodekelompok[1];
        $idcompany     = $this->session->userdata('id_company');

        $query = $this->mmaster->getjenisbarang($ikodekelompok, $idcompany);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->id." >".$row->e_type_name."</option>";
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

    public function getproduct(){
            $ikodejenis     = $this->input->post('ikodejenis');
            $kodekelompok   = explode('|', $this->input->post('ikodekelompok'));
            $idkodekelompok = $kodekelompok[0];
            $ikodekelompok  = $kodekelompok[1];
            $idcompany      = $this->session->userdata('id_company');

            $query = $this->mmaster->getproduct($ikodejenis, $idkodekelompok, $idcompany);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->id." >".$row->i_material." - ".$row->e_material_name."</option>";
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

    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $kodekelompok   = explode('|', $this->input->post('ikodekelompok',true));
        $idkodekelompok = $kodekelompok[0];
        $ikodekelompok  = $kodekelompok[1];
        $ikodejenis     = $this->input->post('ikodejenis',true);
        $iproduct       = $this->input->post('iproduct',true);
        $idcompany      = $this->session->userdata('id_company');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'ikodekelompok' => $idkodekelompok,
            'ikodejenis'    => $ikodejenis,
            'iproduct'      => $iproduct,
            'proses'        => $this->mmaster->getinput($idkodekelompok, $ikodejenis, $iproduct, $idcompany),
            'kodeharga'     => $this->mmaster->getkodeharga($idcompany),
        );
        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);  
    }

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
         
        $ikodekelompok   = $this->input->post('ikodekelompok', TRUE);   
        $ikodejenis      = $this->input->post('ikodejenis', TRUE);   
        $jml             = $this->input->post('jml', TRUE);

        if ($ikodekelompok != ''){
                for($i=1;$i<=$jml;$i++){ 
                    if($this->input->post('cek'.$i)=='cek'){
                        $kodebrg            = $this->input->post('kodebrg'.$i, TRUE); 
                        $ikodebrg           = $this->input->post('ikodebrg'.$i, TRUE); 
                        $ekodebrg           = $this->input->post('namabrg'.$i, TRUE); 
                        $harga              = $this->input->post('harga'.$i, TRUE);
                        $harga              = str_replace(',','',$harga);
                        $ikodeharga         = $this->input->post('ikodeharga'.$i, TRUE);      
                        $dberlaku           = $this->input->post('dberlaku'.$i, TRUE);
                        if($dberlaku){
                             $tmp   = explode('-', $dberlaku);
                             $day   = $tmp[0];
                             $month = $tmp[1];
                             $year  = $tmp[2];
                             $dateberlaku = $year.'-'.$month.'-'.$day;
                        }
                        $this->mmaster->insert($kodebrg, $harga, $dateberlaku, $ikodeharga);                     
                    }
                }          
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodebrg.'-'.$ekodebrg,
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

        $id         = $this->uri->segment(4);
        $kodebrg    = $this->uri->segment(5);
        $dberlaku   = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dberlaku'      => $dberlaku,
            'dfrom'         => $dfrom,
            'kodebrg'       => $kodebrg,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($kodebrg, $id, $idcompany)->row(),
            'kodeharga'     => $this->mmaster->getkodeharga($idcompany),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function ubahtanggalberlaku(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id              = $this->input->post('id', TRUE);
        $kodebrg         = $this->input->post('kodebrg', TRUE);
        $ikodeharga      = $this->input->post('ikodeharga', TRUE);     
        $harga           = $this->input->post('harga', TRUE);
        $harga           = str_replace(',','',$harga);
        $dberlaku        = $this->input->post('dberlaku', TRUE);
        $dberlakusebelum = $this->input->post('dberlakusebelum', TRUE);
        $dakhirsebelum   = $this->input->post('dakhirsebelum', TRUE);
        $dfrom           = $this->input->post('dfrom', TRUE);
        $idcompany       = $this->session->userdata('id_company');

        $tmp   = explode('-', $dberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dateberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $dberlakusebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dateberlakusebelum = $year.'-'.$month.'-'.$day;

        $this->db->trans_begin();
        $data =  $this->mmaster->update($id, $kodebrg, $ikodeharga, $harga, $dateberlaku, $idcompany);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Ubah Harga Jual Barang Jadi' . $kodebrg);
            echo json_encode($data);
        }
    }

    public function inserttanggalberlaku(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id              = $this->input->post('id', TRUE);
        $kodebrg         = $this->input->post('kodebrg', TRUE);
        $ikodeharga      = $this->input->post('ikodeharga', TRUE);     
        $harga           = $this->input->post('harga', TRUE);
        $harga           = str_replace(',','',$harga);
        $dberlaku        = $this->input->post('dberlaku', TRUE);
        $dberlakusebelum = $this->input->post('dberlakusebelum', TRUE);
        $dakhirsebelum   = $this->input->post('dakhirsebelum', TRUE);
        $dfrom           = $this->input->post('dfrom', TRUE);
        $idcompany       = $this->session->userdata('id_company');

        $tmp   = explode('-', $dberlaku);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year.$month;
        $dateberlaku = $year.'-'.$month.'-'.$day;

        $tmp   = explode('-', $dberlakusebelum);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $dateberlakusebelum = $year.'-'.$month.'-'.$day;

        $this->db->trans_begin();
        $data =  $this->mmaster->updatetglakhir($id, $kodebrg, $ikodeharga, $harga, $dateberlaku, $dateberlakusebelum, $idcompany);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Ubah Harga Jual Barang Jadi ' . $kodebrg);
            echo json_encode($data);
        }
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $kodebrg    = $this->uri->segment(5);
        $dberlaku   = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');
        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dberlaku'      => $dberlaku,
            'dfrom'         => $dfrom,
            'kodebrg'       => $kodebrg,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($kodebrg, $id, $idcompany)->row(),
            'kodeharga'     => $this->mmaster->getkodeharga($idcompany),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
