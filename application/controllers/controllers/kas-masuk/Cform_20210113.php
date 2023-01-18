<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040302';

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

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),  
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function kasbank(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_kas_bank");
            $this->db->where("i_jenis_kas","01");
                                //order by e_nama_kas");
                               // where e_nama_kas like '%$cari%'
            $data = $this->db->get();
            foreach($data->result() as  $ikdoe){
                    $filter[] = array(
                    'id'   => $ikdoe->i_kode_kas,  
                    'text' => $ikdoe->e_nama_kas
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function bank(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("* from tr_bank where e_bank_name like '%$cari%' order by e_bank_name");
            $data = $this->db->get();
            foreach($data->result() as  $ikdoe){
                    $filter[] = array(
                    'id'   => $ikdoe->i_bank,  
                    'text' => $ikdoe->e_bank_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function customer(){
        $cari = strtoupper($this->input->get('q'));
        $query = $this->mmaster->customer($cari);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_customer." >".$row->e_customer_name."</option>";
            }
            $kop  = "<option value=\"ALCUS\">Semua Customer".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Customer Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function getcustomer(){
        header("Content-Type: application/json", true);
        $icustomer  = $this->input->post('icustomer');
        
       // $data = $this->mmaster->getcustomer($icustomer);


        $dataa = array(
            //'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getcustomer($icustomer)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian   = $this->input->post("ibagian",true);
        $dmasuk    = $this->input->post("dmasuk",true);
        if($dmasuk){
                 $tmp   = explode('-', $dmasuk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datemasuk = $year.'-'.$month.'-'.$day;
        }

        $icustomer    = $this->input->post('icustomer', TRUE);
        $ikasbank     = $this->input->post('ikasbank', TRUE);
        $ibank        = $this->input->post('ibank', TRUE);
        $vnilai       = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark      = $this->input->post('eremark', TRUE);
       
        $jml          = $this->input->post('jml', TRUE); 
       
        $this->db->trans_begin();
        $ikasmasuk   = $this->mmaster->runningnumber($yearmonth, $ibagian);
        $this->mmaster->insertheader($ikasmasuk, $ibagian, $datemasuk, $ikasbank, $icustomer, $ibank, $eremark, $vnilai);

        for($i=1;$i<=$jml;$i++){
            if($cek=$this->input->post('cek'.$i)=='cek'){
                $icustomer  = $this->input->post('icustomer'.$i, TRUE);
                //$vnilai     = str_replace(',','',$this->input->post('vnilai'.$i,TRUE));
                $edesc      = $this->input->post('edesc'.$i, TRUE);
                $nitemno    = $i;

                $this->mmaster->insertdetail($ikasmasuk, $icustomer, $edesc, $nitemno);
            }
        }
            
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikasmasuk);
            $data = array(
                'sukses' => true,
                'kode'      => $ikasmasuk,
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
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ikasmasuk = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'customer'      => $this->mmaster->bacacustomer(),
            'kasbank'       => $this->mmaster->bacakasbank(),
            'bank'          => $this->mmaster->bacabank(),
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'data'          => $this->mmaster->baca_header($ikasmasuk)->row(),
            'datadetail'    => $this->mmaster->baca_detail($ikasmasuk)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ikasmasuk = $this->input->post("ikasmasuk",true);
        $ibagian   = $this->input->post("ibagian",true);
        $dmasuk    = $this->input->post("dmasuk",true);
        if($dmasuk){
                 $tmp   = explode('-', $dmasuk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datemasuk = $year.'-'.$month.'-'.$day;
        }

        $icustomer    = $this->input->post('icustomer', TRUE);
        $ikasbank     = $this->input->post('ikasbank', TRUE);
        $ibank        = $this->input->post('ibank', TRUE);
        $vnilai       = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark      = $this->input->post('eremark', TRUE);
       
        $jml          = $this->input->post('jml', TRUE); 
       
        $this->db->trans_begin();
        $this->mmaster->updateheader($ikasmasuk, $ibagian, $datemasuk, $ikasbank, $icustomer, $ibank, $eremark, $vnilai);
        $this->mmaster->deletedetail($ikasmasuk);

        for($i=1;$i<=$jml;$i++){
            if($cek=$this->input->post('cek'.$i)=='cek'){
                $icustomer  = $this->input->post('icustomer'.$i, TRUE);
                //$vnilai     = str_replace(',','',$this->input->post('vnilai'.$i,TRUE));
                $edesc      = $this->input->post('edesc'.$i, TRUE);
                $nitemno    = $i;

                $this->mmaster->insertdetail($ikasmasuk, $icustomer, $edesc, $nitemno);
            }
        }
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data '.$this->global['title'].' No SJ : '.$ikasmasuk);
            $data = array(
                'sukses' => true,
                'kode'      => $ikasmasuk,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $ikasmasuk = $this->input->post('ikasmasuk');
        $this->mmaster->sendd($ikasmasuk);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $ikasmasuk = $this->input->post('ikasmasuk');
        $this->mmaster->cancel_approve($ikasmasuk);
    }

    public function view(){

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ikasmasuk = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'customer'      => $this->mmaster->bacacustomer(),
            'kasbank'       => $this->mmaster->bacakasbank(),
            'bank'          => $this->mmaster->bacabank(),
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'data'          => $this->mmaster->baca_header($ikasmasuk)->row(),
            'datadetail'    => $this->mmaster->baca_detail($ikasmasuk)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ikasmasuk = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'customer'      => $this->mmaster->bacacustomer(),
            'kasbank'       => $this->mmaster->bacakasbank(),
            'bank'          => $this->mmaster->bacabank(),
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'data'          => $this->mmaster->baca_header($ikasmasuk)->row(),
            'datadetail'    => $this->mmaster->baca_detail($ikasmasuk)->result(),
        );
        $this->Logger->write('Membuka Menu approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ikasmasuk = $this->input->post('ikasmasuk', true);
       
        $this->db->trans_begin();
        $this->mmaster->approve($ikasmasuk);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $ikasmasuk,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $ikasmasuk = $this->input->post('ikasmasuk');
        $this->mmaster->change_approve($ikasmasuk);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $ikasmasuk = $this->input->post('ikasmasuk');
        $this->mmaster->reject_approve($ikasmasuk);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ikasmasuk = $this->input->post('ikasmasuk', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ikasmasuk);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Penerimaan Piutang' . $ikasmasuk);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */