<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040205';

    public function __construct(){
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

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            
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

     function partner(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data =$this->mmaster->partner();
            foreach($data->result() as  $ikode){
                    $filter[] = array(
                    'id'   => $ikode->i_customer,  
                    'text' => $ikode->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getreferensi(){
        $ipartner = $this->input->post('ipartner');
        $query = $this->mmaster->getreferensi($ipartner);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_referensi." >".$row->i_referensi."</option>";
            }
            $kop  = "<option value=\"\">Pilih Referensi".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Referensi Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getreferensikb(){
        $ipartner = $this->input->post('ipartner');
        $query = $this->mmaster->getreferensikb($ipartner);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_referensi." >".$row->i_referensi."</option>";
            }
            $kop  = "<option value=\"\">Pilih Referensi".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Referensi Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function getitemp(){
        header("Content-Type: application/json", true);
        $ireferensipp  = $this->input->post('ireferensipp');
        $ipartner      = $this->input->post('ipartner');

        
        $data = $this->mmaster->getitemp($ireferensipp, $ipartner);


        $dataa = array(
            'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getitemp($ireferensipp, $ipartner)->result_array(),
        );
        echo json_encode($dataa);
    }

    function getitemk(){
        header("Content-Type: application/json", true);
        $ireferensikb  = $this->input->post('ireferensikb');
        $ipartner      = $this->input->post('ipartner');

        
        $data = $this->mmaster->getitemk($ireferensikb, $ipartner);


        $dataa = array(
            'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getitemk($ireferensikb, $ipartner)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function simpan(){
       
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian           = $this->input->post('ibagian', TRUE);
        $dnota             = $this->input->post('dnota', TRUE);
        if($dnota!=''){
            $tmp=explode("-",$dnota);
            $year=$tmp[2];
            $month=$tmp[1];
            $day=$tmp[0];
            $datenota=$year."-".$month."-".$day;
            $yearmonth  =$year.$month;
        }
        $ireferensipp       = $this->input->post('ireferensipp', TRUE);
        $ireferensikb       = $this->input->post('ireferensikb', TRUE);
        if($ireferensipp == ''){
            $ireferensi     = $this->input->post('ireferensikb', TRUE);
        }else if ($ireferensikb == '') {
            $ireferensi     = $this->input->post('ireferensipp', TRUE);
        }
        $ipartner           = $this->input->post('ipartner', TRUE);
        $ikasbank           = $this->input->post('ikasbank', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        
        $inotaar = $this->mmaster->runningnumber($yearmonth, $ibagian);

        $this->mmaster->insertheader($inotaar, $ibagian, $datenota, $ireferensi, $ipartner, $ikasbank, $eremark);
           for($i=1;$i<=$jml;$i++){
                if($cek=$this->input->post('cek'.$i)=='cek'){
                    //var_dump($cek);
                    $nodok        = $this->input->post('nodok'.$i, TRUE);
                    $ddok         = $this->input->post('ddok'.$i, TRUE);
                    $partner      = $this->input->post('partner'.$i, TRUE);
                    $jumlah_lebih = str_replace(',','',$this->input->post('jumlah_lebih'.$i,TRUE));
                    $jumlah       = str_replace(',','',$this->input->post('jumlah'.$i, TRUE));
                    $nitemno      = $i;

                    $this->mmaster->insertdetail($inotaar, $nodok, $ddok, $partner, $jumlah_lebih, $jumlah, $nitemno);

                    $query  = $this->mmaster->cekalokasi($ireferensi, $ipartner);
                    $query2 = $this->mmaster->cekhutangdagang($ireferensi, $ipartner);

                    if($query == $ireferensi){
                        $nilai  = $this->mmaster->ceknilaialokasi($ireferensi, $ipartner);
                        $total  =  $nilai - $jumlah;
                        $this->mmaster->updatejumlahalokasi($ireferensi, $ipartner, $total);
                    }
                    if($query2 == $ireferensi){
                        $nilaihd  = $this->mmaster->ceknilaihd($ireferensi, $ipartner);
                        $totalhd  =  $nilaihd - $jumlah;
                        $this->mmaster->updatejumlahhutangdagang($ireferensi, $ipartner, $totalhd);
                    }
                }
            }

            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Tambah Debet Note AR No:'.$inotaar);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $inotaar
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

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $inotaar    = $this->uri->segment(4);
        $ipartner   = $this->uri->segment(5);
        $ireferensi = $this->uri->segment(6);
        $cek1       = $this->mmaster->cek1($ireferensi)->result();
        $cek2       = $this->mmaster->cek2($ireferensi)->result();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),  
            'partner'       => $this->mmaster->partner()->result(), 
            'referensi'     => $this->mmaster->cek_referensi($ipartner)->result(),
            'referensii'    => $this->mmaster->cek_referensii($ipartner)->result(),
            'kasbank'       => $this->mmaster->cek_kasbank()->result(),
            'cek1'          => $cek1,
            'cek2'          => $cek2,
            'data'          => $this->mmaster->cek_dataheader($inotaar)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($inotaar)->result(),
        );


        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
       
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $inotaar         = $this->input->post('inotaar', TRUE);
        $ibagian         = $this->input->post('ibagian', TRUE);
        $dnota           = $this->input->post('dnota', TRUE);
        if($dnota!=''){
            $tmp=explode("-",$dnota);
            $year=$tmp[2];
            $month=$tmp[1];
            $day=$tmp[0];
            $datenota=$year."-".$month."-".$day;
        }
        $ireferensi      = $this->input->post('ireferensi', TRUE);
        $ipartner        = $this->input->post('ipartner', TRUE);
        $ikasbank        = $this->input->post('ikasbank', TRUE);
        $eremark         = $this->input->post('eremark', TRUE);
        $jml             = $this->input->post('jml', TRUE);

        $this->db->trans_begin();

        $this->mmaster->updateheader($inotaar, $ibagian, $datenota, $ireferensi, $ipartner, $ikasbank, $eremark);
        $this->mmaster->deletedetail($inotaar);

           for($i=1;$i<=$jml;$i++){
                $nodok        = $this->input->post('nodok'.$i, TRUE);
                $ddok         = $this->input->post('ddok'.$i, TRUE);
                $partner      = $this->input->post('partner'.$i, TRUE);
                $jumlah_lebih = str_replace(',','',$this->input->post('jumlah_lebih'.$i,TRUE));
                $jumlah       = str_replace(',','',$this->input->post('jumlah'.$i, TRUE));
                $nitemno      = $i;

                $this->mmaster->insertdetail($inotaar, $nodok, $ddok, $partner, $jumlah_lebih, $jumlah, $nitemno);

                $query  = $this->mmaster->cekalokasi($ireferensi, $ipartner);
                $query2 = $this->mmaster->cekhutangdagang($ireferensi, $ipartner);

                if($query == $ireferensi){
                    $nilai  = $this->mmaster->ceknilaialokasi($ireferensi, $ipartner);
                    $total  =  $nilai - $jumlah;
                    $this->mmaster->updatejumlahalokasi($ireferensi, $ipartner, $total);
                }
                if($query2 == $ireferensi){
                    $nilaihd  = $this->mmaster->ceknilaihd($ireferensi, $ipartner);
                    $totalhd  =  $nilaihd - $jumlah;
                    $this->mmaster->updatejumlahhutangdagang($ireferensi, $ipartner, $totalhd);
                }
                
            }

            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Debet Nota AR No:'.$inotaar);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $inotaar
                );
            }
        $this->load->view('pesan', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $inotaar = $this->input->post('inotaar');
        $this->mmaster->sendd($inotaar);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $inotaar = $this->input->post('inotaar');
        $this->mmaster->cancel_approve($inotaar);
    }

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $inotaar    = $this->uri->segment(4);
        $ipartner   = $this->uri->segment(5);
        $ireferensi = $this->uri->segment(6);
        $cek1       = $this->mmaster->cek1($ireferensi)->result();
        $cek2       = $this->mmaster->cek2($ireferensi)->result();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),  
            'partner'       => $this->mmaster->partner()->result(), 
            'referensi'     => $this->mmaster->cek_referensi($ipartner)->result(),
            'referensii'    => $this->mmaster->cek_referensii($ipartner)->result(),
            'kasbank'       => $this->mmaster->cek_kasbank()->result(),
            'cek1'          => $cek1,
            'cek2'          => $cek2,
            'data'          => $this->mmaster->cek_dataheader($inotaar)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($inotaar)->result(),
        );


        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $inotaar = $this->input->post('inotaar', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($inotaar);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Debet Nota AR' . $inotaar);
            echo json_encode($data);
        }
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
        $inotaar    = $this->uri->segment(4);
        $ipartner   = $this->uri->segment(5);
        $ireferensi = $this->uri->segment(6);
        $cek1       = $this->mmaster->cek1($ireferensi)->result();
        $cek2       = $this->mmaster->cek2($ireferensi)->result();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),  
            'partner'       => $this->mmaster->partner()->result(), 
            'referensi'     => $this->mmaster->cek_referensi($ipartner)->result(),
            'referensii'    => $this->mmaster->cek_referensii($ipartner)->result(),
            'kasbank'       => $this->mmaster->cek_kasbank()->result(),
            'cek1'          => $cek1,
            'cek2'          => $cek2,
            'data'          => $this->mmaster->cek_dataheader($inotaar)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($inotaar)->result(),
        );


        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $inotaar = $this->input->post('inotaar', true);
       
        $this->db->trans_begin();
        $this->mmaster->approve($inotaar);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $inotaar,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $inotaar = $this->input->post('inotaar');
        $this->mmaster->change_approve($inotaar);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $inotaar = $this->input->post('inotaar');
        $this->mmaster->reject_approve($inotaar);
    }
}
/* End of file Cform.php */