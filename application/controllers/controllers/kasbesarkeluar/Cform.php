<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2030102';

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
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'], 
            'bank'       => $this->mmaster->get_bank()->result(),
            'iarea'      => $iarea,
            'earea'      => $earea,
            'periode'    => $iperiode,
            'bulan'      => date('m'),
            'tahun'      => date('Y'),
        );      

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
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
        $saldo  = $this->mmaster->bacasaldo($area,$dtos,$icoabank,$periode);
       
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

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }    

    function getcoa(){
        header("Content-Type: application/json", true);
        $icoa = $this->input->post('i_coa');
        $this->db->select("i_coa, e_coa_name");
        $this->db->from("tr_coa");
        $this->db->where("i_coa", $icoa);
        $data = $this->db->get();
        echo json_encode($data->result_array());
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

    function datacoa(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_coa, e_coa_name");
            $this->db->from("tr_coa");
            $this->db->like("UPPER(i_coa)", $cari);
            $this->db->or_like("UPPER(e_coa_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->i_coa,  
                    'text' => $product->i_coa.'-'.$product->e_coa_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function dataareaa(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_area, e_area_name");
            $this->db->from("tr_area");
            $this->db->like("UPPER(i_area)", $cari);
            $this->db->or_like("UPPER(e_area_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->i_area,  
                    'text' => $product->i_area.'-'.$product->e_area_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
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
        $dkb      = $this->input->post('dbank', TRUE);
        // $ibank      = $this->input->post('ibank', TRUE);
        // $icoabank   = $this->input->post('icoabank', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
	    	$now	  = $row->c;
        if($dkb!=''){
            $tmp=explode("-",$dkb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dkb=$th."-".$bl."-".$hr;
            $dpv=$th."-".$bl."-".$hr;
        }
        $ikode = '';
        $fdebet='t';
        if ($iperiode != '' &&  $iarea != '' &&  $dkb != '' &&  $jml != '0'){
            $this->db->trans_begin();
            $tot  = 0;
            // $ipvb = $this->mmaster->runningnumberpvb($tah,$bul,$icoabank,$iarea);
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
                $vkb        = $this->input->post('vbank'.$a, TRUE);
                $vkb        = str_replace(',','',$vkb);
                $tot=$tot+$vkb;
                $edescription = $this->input->post('edescription'.$a, TRUE);
                if($edescription==""){
                    $edescription=null;
                }
                $ikb=$this->mmaster->runningnumberbank($tah,$bul,$iareax);
                $this->mmaster->insert($iareax,$ikb,$iperiode,$icoa,$vkb,$dbukti,$ecoaname,$edescription,$fdebet,$dkb);
                $eremark    = $edescription;
                $fclose     = 'f';
                $this->mmaster->inserttransheader($ikb,$iareax,$eremark,$fclose,$dkb,$now);
                if($fdebet=='t'){
                    $accdebet       = $icoa;
                    $namadebet      = $ecoaname;
                    $acckredit      = KasBesar;
                    $namakredit     = $this->mmaster->namaacc($acckredit);
                }else{
                    $accdebet       = $KasBesar;
                    $namadebet      = $this->mmaster->namaacc($accdebet);
                    $acckredit      = $icoa;
                    $namakredit     = $ecoaname;
                }
                // $fdebet,$fposting,
                $this->mmaster->inserttransitemdebet($accdebet,$ikb,$namadebet,$iareax,$eremark,$vkb,$dkb,$now);
                $this->mmaster->updatesaldodebet($accdebet,$iperiode,$vkb);
                $this->mmaster->inserttransitemkredit($acckredit,$ikb,$namakredit,$iareax,$eremark,$vkb,$dkb,$now);
                $this->mmaster->updatesaldokredit($acckredit,$iperiode,$vkb);
                $this->mmaster->insertgldebet($accdebet,$ikb,$namadebet,$iareax,$vkb,$dkb,$eremark);
                $this->mmaster->insertglkredit($acckredit,$ikb,$namakredit,$iareax,$vkb,$dkb,$eremark);
                $this->mmaster->insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vkb,$edescription,$ikb,$ipvtype,$iareax);
            }
            $this->mmaster->insertpv($ipv,$iarea,$iperiode,$dpv,$tot,$eremark,$ipvtype);
            // $this->mmaster->insertpvb($ipvb,$icoabank,$ipv,$iarea,$ipvtype);
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
                    'kode'      => $ikb,$ipv
                );
            }
        }else{
            $kode = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $kode);
    }
    /*
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikbank      = $this->input->post('ikbank');
        $iarea       = $this->input->post('iarea');
        $iperiode    = $this->input->post('iperiodebl').$this->input->post('iperiodeth');
        $iperiodebl  = $this->input->post('iperiodebl');
        $iperiodeth  = $this->input->post('iperiodeth');
        $ibank       = $this->input->post('ibank');
        $dbank       = $this->input->post('dbank');
        if($dbank){
                 $tmp   = explode('-', $dbank);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datebank = $year.'-'.$month.'-'.$day;
        }
        $fdebet      ='t';
        $vsaldo      = $this->input->post('vsaldo', TRUE);    
        $jml         = $this->input->post('jml', TRUE); 
        $ipvtype     = $this->input->post('ipvtype', TRUE);
        $imutasi     = $this->input->post('imutasi', TRUE);

        $nomor=array();
        if ((isset($iperiode) && $iperiode != '') && (isset($ibank) && $ibank != '') &&
                (isset($iarea) && $iarea != '') && (isset($ibank) && $ibank != '') &&
                (isset($dbank) && $dbank != '') && (isset($jml) && $jml != '' && $jml != '0')){

            $this->db->trans_begin();
            $tot=0;
            $ipvb=$this->input->post('no_pv',TRUE);
                $ipbvada=$this->mmaster->cekpvb($ipvb, $ipvtype, $iperiodeth, $ibank);
                if($ipbvada=="tidak ada"){
                    $ipv=$this->mmaster->runningnumberpv($iperiodeth, $iperiodebl, $iarea, $ipvtype);
                }else{
                    $qgetpv = $this->mmaster->getpv($ipvb, $ipvtype, $iperiodeth, $ibank);
                    if ($qgetpv->num_rows()>0) {
                        $key = $qgetpv->row();
                        $ipv = $key->i_pv;
                    }
                }
                if ($ipbvada=="tidak ada") {
                    for ($a=1;$a<=$jml;$a++) {
                         $icoa        = $this->input->post('icoa'.$a, TRUE);
                         $ecoaname    = $this->input->post('ecoaname'.$a, TRUE);
                         $idarea      = $this->input->post('idarea'.$a, TRUE);             
                         $date        = $this->input->post('date'.$a, TRUE);
                         $eremark     = $this->input->post('eremark'.$a, TRUE);
                         $vtotal      = $this->input->post('vtotal'.$a, TRUE);

                         $tot=$tot+$vtotal;
                         if($eremark=="") $eremark=null;
                            $ikbank=$this->mmaster->runningnumberbank($iperiodeth, $iperiodebl,$iarea,$ibank);
                            $this->mmaster->insert($iarea, $ikbank, $iperiode, $ibank, $datebank, $vsaldo, $icoa, $ecoaname, $idarea, $date, $eremark, $vtotal, $fdebet);
                            $nomor[]=$ikbank;
                            $eremark        = $eremark;
                            $fclose         = 'f';
                            $this->mmaster->inserttransheader($ikbank,$iarea,$eremark,$fclose, $imutasi, $dbank,$ibank);
                            if($fdebet=='t'){
                                $accdebet       = $icoa;
                                $acckredit      = $ibank;
                                $namakredit     = $this->mmaster->namaacc($acckredit);
                                $namadebet      = $ecoaname;
                            }else{
                                $accdebet       = $ibank;
                                $namadebet      = $this->mmaster->namaacc($accdebet);
                                $acckredit      = $icoa;
                                $namakredit     = $ecoaname;
                            }
                            $this->mmaster->inserttransitemdebet($accdebet,$ikbank,$namadebet,'t','t',$iarea,$eremark,$vtotal,$imutasi,$date,$ibank);
                            $this->mmaster->updatesaldodebet($accdebet,$iperiode,$vsaldo);
                            $this->mmaster->inserttransitemkredit($acckredit,$ikbank,$namakredit,'f','t',$iarea,$eremark,$vtotal,$imutasi,$dbank,$ibank);
                            $this->mmaster->updatesaldokredit($acckredit,$iperiode,$vtotal);
                            $this->mmaster->insertgldebet($accdebet,$ikbank,$namadebet,'f',$iarea,$vtotal,$dbank,$eremark,$ibank);
                            $this->mmaster->insertglkredit($acckredit,$ikbank,$namakredit,'t',$iarea,$vtotal,$dbank,$eremark,$ibank);
                            $this->mmaster->insertpvitem($ipv,$iarea,$icoa,$ecoaname,$vtotal,$eremark,$ikbank,$ipvtype, $idarea, $ibank);
                        }
                        $this->mmaster->insertpv($ipv,$iarea,$iperiode,$ibank,$datebank,$tot,$eremark,$ipvtype);
                        $this->mmaster->insertpvb( $ipvb,$ibank,$ipv,$iarea,$ipvtype);
                    }else{
                    for ($a=1;$a<=$jml;$a++){
                        $icoa         = $this->input->post('icoa'.$a, TRUE);
                        $ecoaname     = $this->input->post('ecoaname'.$a, TRUE);
                        $iarea        = $this->input->post('iarea'.$a, TRUE);
                        $date         = $this->input->post('date'.$a, TRUE);
                        
                        $eremark      = null;
                        $vtotal       = $this->input->post('vtotal'.$a, TRUE);
                        $vtotal       = str_replace(',','',$vtotal);
                        $tot=$tot+$vtotal;
                        $eremark = $this->input->post('eremark'.$a, TRUE);
                        if($eremark=="") $eremark=null;
                           $ikbank=$this->mmaster->runningnumberbank($iperiodeth, $iperiodebl,$iarea,$ibank);
                            $this->mmaster->insert($iarea, $ikbank, $iperiode, $ibank, $datebank, $vsaldo, $icoa, $ecoaname, $idarea, $date, $eremark, $vtotal, $fdebet);
                            $nomor[]=$ikbank;
                            $eremark        = $eremark;
                            $fclose         = 'f';
                            $this->mmaster->inserttransheader($ikbank,$iarea,$eremark,$fclose,$imutasi, $date,$ibank);
                        if($fdebet=='t'){
                            $accdebet       = $icoa;
                            $acckredit      = $ibank;
                            $namakredit     = $this->mmaster->namaacc($acckredit);
                            $namadebet      = $ecoaname;
                        }else{
                            $accdebet       = $ibank;
                            $namadebet      = $this->mmaster->namaacc($accdebet);
                            $acckredit      = $icoa;
                            $namakredit     = $ecoaname;
                        }
                        $this->mmaster->inserttransitemdebet($accdebet,$ikbank,$namadebet,'t','t',$iarea,$eremark,$vtotal,$imutasi,$date,$ibank);
                            $this->mmaster->updatesaldodebet($accdebet,$iperiode,$vsaldo);
                            $this->mmaster->inserttransitemkredit($acckredit,$ikbank,$namakredit,'f','t',$iarea,$eremark,$vtotal,$dbank,$ibank);
                            $this->mmaster->updatesaldokredit($acckredit,$iperiode,$vtotal);
                            $this->mmaster->insertgldebet($accdebet,$ikbank,$namadebet,'f',$iarea,$vtotal,$dbank,$eremark,$ibank);
                            $this->mmaster->insertglkredit($acckredit,$ikbank,$namakredit,'t',$iarea,$vtotal,$dbank,$eremark,$ibank);
                            $this->mmaster->insertpvitem( $ipv,$iarea,$icoa,$ecoaname,$vtotal,$eremark,$ikbank,$ipvtype,$ibank);
                        }
                        $this->mmaster->updatepv($ipv, $ipvtype, $iperiode, $tot);
                        $this->mmaster->updatepvb($ipvb, $ipvtype, $iperiode, $ibank);
                    }
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikbank);
       
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $ikbank,
                    'inomor'  => $nomor,
                    'ipvb'    => $ipvb,
                );
        }
    }
    $this->load->view('pesan', $data); 
    }*/
}
/* End of file Cform.php */