<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1060102';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $earea     = $this->mmaster->cekearea($iarea);
        $iperiode  = $this->mmaster->cekperiode();
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iarea'     => $iarea,
            'earea'     => $earea,
            'periode'   => $iperiode,
            'bulan'     => date('m'),
            'tahun'     => date('Y'),
            'bank'      => $this->mmaster->bacabank()

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformawal', $data);

    }

    public function proses(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $xarea     = $this->mmaster->cekarea($username, $idcompany);
        $area      = $this->input->post('iarea', TRUE);
        if ($area=='') {
            $area   = $this->uri->segment(4);
        }
        $eareaname  = $this->input->post('eareaname', TRUE);
        if ($eareaname=='') {
            $eareaname= $this->uri->segment(5);
        }
        $eareaname = str_replace("%20", " ", $eareaname);
        $tanggal    = $this->input->post('dbank', TRUE);
        if ($tanggal=='') {
            $tanggal= $this->uri->segment(6);
        }
        $bulan      = $this->input->post('iperiodebl', TRUE);
        if ($bulan=='') {
            $bulan= $this->uri->segment(7);
        }
        $tahun      = $this->input->post('iperiodeth', TRUE);
        if ($tahun=='') {
            $tahun= $this->uri->segment(8);
        }
        $periode    = $tahun.$bulan;
        $ibank      = $this->input->post('ibank', TRUE);
        if ($ibank=='') {
            $ibank= $this->uri->segment(9);
        }
        $ebankname  = $this->input->post('ebankname', TRUE);
        if ($ebankname=='') {
            $ebankname= $this->uri->segment(10);
        }
        $ebankname = str_replace("%20", " ", $ebankname);
        $icoabank   = $this->mmaster->getcoabank($ibank);
        $iperiode   = $this->mmaster->cekperiode();
        if($tanggal!=''){
            $tmp=explode("-",$tanggal);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $tgl=$th."-".$bl."-".$hr;
        }
        $tmp = explode("-", $tgl);
        $det    = $tmp[2];
        $mon    = $tmp[1];
        $yir    = $tmp[0];
        $dsaldo = $yir."/".$mon."/".$det;
        $dtos   = $this->fungsi->dateAdd("d",1,$dsaldo);
        $tmp    = explode("-", $dtos);
        $det1   = $tmp[2];
        $mon1   = $tmp[1];
        $yir1   = $tmp[0];
        $dtos   = $yir1."-".$mon1."-".$det1;
        $saldo  = $this->mmaster->bacasaldo($area,$dtos,$icoabank);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iarea'     => $area,
            'eareaname' => $eareaname,
            'iperiode'  => $iperiode,
            'tanggal'   => $tanggal,
            'ibank'     => $ibank,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'saldo'     => $saldo,
            'icoabank'  => $icoabank,
            'ebankname' => $ebankname,
            'area'      => $this->mmaster->bacaarea($xarea)

        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function coa(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->bacacoa($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_coa,  
                    'text'  => $kuy->i_coa." - ".$kuy->e_coa_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcoa(){
        header("Content-Type: application/json", true);
        $icoa = $this->input->post('icoa');    
        $data = $this->mmaster->getdetailcoa($icoa);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea      = $this->input->post('iarea', TRUE);
        $ipvtype    = $this->input->post('ipvtype', TRUE);
        $iperiode   = $this->input->post('iperiodeth', TRUE).$this->input->post('iperiodebl', TRUE);
        $tah        = substr($this->input->post('iperiodeth', TRUE),2,2);
        $bul        = $this->input->post('iperiodebl', TRUE);
        $dbank      = $this->input->post('dbank', TRUE);
        $ibank      = $this->input->post('ibank', TRUE);
        $icoabank   = $this->input->post('icoabank', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if($dbank!=''){
            $tmp=explode("-",$dbank);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbank=$th."-".$bl."-".$hr;
            $dpv=$th."-".$bl."-".$hr;
        }
        $ikode = '';
        $fdebet='t';
        if ((isset($iperiode) && $iperiode != '') && (isset($ibank) && $ibank != '') && (isset($iarea) && $iarea != '') && (isset($icoabank) && $icoabank != '') && (isset($dbank) && $dbank != '') && (isset($jml) && $jml != '' && $jml != '0')){
            $this->db->trans_begin();
            $tot  = 0;
            $ipvb = $this->mmaster->runningnumberpvb($tah,$bul,$icoabank,$iarea);
            $ipv  = $this->mmaster->runningnumberpv($tah,$bul,$iarea,$ipvtype);            
            for ($a=1;$a<=$jml;$a++) {
                $icoa         = $this->input->post('icoa'.$a, TRUE);
                $ecoaname     = $this->input->post('ecoaname'.$a, TRUE);
                $iareax       = $this->input->post('iarea'.$a, TRUE);
                $dbukti       = $this->input->post('tgl'.$a, TRUE);
                if($dbukti!=''){
                    $tmp=explode("-",$dbukti);
                    $xth=$tmp[2];
                    $xbl=$tmp[1];
                    $xhr=$tmp[0];
                    $dbukti=$xth."-".$xbl."-".$xhr;
                }
                $eremark      = null;
                $vbank        = $this->input->post('vbank'.$a, TRUE);
                $vbank        = str_replace(',','',$vbank);
                $tot=$tot+$vbank;
                $edescription = $this->input->post('edescription'.$a, TRUE);
                if($edescription==""){
                    $edescription=null;
                }
                $ikode=$this->mmaster->runningnumberbank($tah,$bul,$iareax,$icoabank);
                $this->mmaster->insert( $iareax,$ikode,$iperiode,$icoa,$vbank,$dbukti,$ecoaname,$edescription,$fdebet,$icoabank);
                $eremark    = $edescription;
                $fclose     = 'f';
                $this->mmaster->inserttransheader($ikode,$iareax,$eremark,$fclose,$dbank,$icoabank);
                if($fdebet=='t'){
                    $accdebet       = $icoa;
                    $namadebet      = $ecoaname;
                    $acckredit      = $icoabank;
                    $namakredit     = $this->mmaster->namaacc($acckredit);
                }else{
                    $accdebet       = $icoabank;
                    $namadebet      = $this->mmaster->namaacc($accdebet);
                    $acckredit      = $icoa;
                    $namakredit     = $ecoaname;
                }
                $this->mmaster->inserttransitemdebet($accdebet,$ikode,$namadebet,'t','t',$iareax,$eremark,$vbank,$dbank,$icoabank);
                $this->mmaster->updatesaldodebet($accdebet,$iperiode,$vbank);
                $this->mmaster->inserttransitemkredit($acckredit,$ikode,$namakredit,'f','t',$iareax,$eremark,$vbank,$dbank,$icoabank);
                $this->mmaster->updatesaldokredit($acckredit,$iperiode,$vbank);
                $this->mmaster->insertgldebet($accdebet,$ikode,$namadebet,'f',$iareax,$vbank,$dbank,$eremark,$icoabank);
                $this->mmaster->insertglkredit($acckredit,$ikode,$namakredit,'t',$iareax,$vbank,$dbank,$eremark,$icoabank);
                $this->mmaster->insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vbank,$edescription,$ikode,$ipvtype,$iareax,$icoabank);
            }
            $this->mmaster->insertpv($ipv,$iarea,$iperiode,$icoabank,$dpv,$tot,$eremark,$ipvtype);
            $this->mmaster->insertpvb($ipvb,$icoabank,$ipv,$iarea,$ipvtype);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $kode = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Bank keluar No:'.$ikode.' Periode:'.$iperiode.' Area:'.$iarea);
                $kode = array(
                    'sukses'    => true,
                    'kode'      => $ikode
                );
            }
        }else{
            $kode = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $kode);
    }
}
/* End of file Cform.php */
