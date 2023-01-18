<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1060106';

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
            'ikk'       => '',
            'bulan'     => date('m'),
            'tahun'     => date('Y')
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
        $tanggal    = $this->input->post('dkk', TRUE);
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
        $icoabank   = KasBesar;
        $iperiode   = $this->mmaster->cekperiode();
        if($tanggal!=''){
            $tmp=explode("-",$tanggal);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $tgl=$th."-".$bl."-".$hr;
        }
        $dbukti = $this->input->post('dbukti',TRUE);
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
        $saldo  = $this->mmaster->bacasaldo($area,$periode,$tgl);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iarea'     => $area,
            'eareaname' => $eareaname,
            'iperiode'  => $iperiode,
            'tanggal'   => $tanggal,
            'bulan'     => $bulan,
            'tahun'     => $tahun,
            'saldo'     => $saldo,
            'icoabank'  => $icoabank,
            'dbukti'    => $dbukti,
            'area'      => $this->mmaster->bacaarea($xarea)

        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    function dataarea(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $this->db->select(" * from tr_area where (upper(i_area) like '%$cari%' or upper(e_area_name) like '%$cari%') and (i_area in ( select i_area from tm_user_area where i_user='$username') )",false);
            $data = $this->db->get();
            foreach($data->result() as  $area){
                    $filter[] = array(
                    'id' => $area->i_area,  
                    'text' => $area->i_area.'-'.$area->e_area_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
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

        $iarea		= $this->input->post('iarea', TRUE);
			$iperiode	= $this->input->post('iperiodeth', TRUE).$this->input->post('iperiodebl', TRUE);
			$irvtype  = '00';
			$tah			= substr($this->input->post('iperiodeth', TRUE),2,2);
			$bul			= $this->input->post('iperiodebl', TRUE);
			$vkk			= $this->input->post('vkk', TRUE);
			$vkk			= str_replace(',','',$vkk);
			$edescription	= $this->input->post('edescription', TRUE);
			$coa_area = $this->db->query("select i_coa from tr_coa where i_area = '$iarea' and e_coa_name like '%Kas Kecil%'")->row()->i_coa;
			if($edescription=="") $edescription=null;
			$dkk			= $this->input->post('dkk', TRUE);
			if($dkk!=''){
				$tmp=explode("-",$dkk);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dkk=$th."-".$bl."-".$hr;
			}
			$dbukti			= $this->input->post('dbukti', TRUE);
			if($dbukti!=''){
				$tmp=explode("-",$dbukti);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dbukti=$th."-".$bl."-".$hr;
			}
			$fdebet		= 'f';
			$icoa			= Penyesuaian;
        if ((isset($iperiode) && $iperiode != '') && (isset($iarea) && $iarea != '') && (isset($dkk) && $dkk != '') && (isset($dbukti) && $dbukti != '') && (isset($vkk) && (($vkk != 0) || ($vkk != '')))){
            $ecoaname	= $this->mmaster->namaacc($icoa);
			$this->db->trans_begin();
			$irv=$this->mmaster->runningnumberrv($tah,$bul,$iarea,$irvtype);
			$ikk=$this->mmaster->runningnumberkk($tah,$bul,$iarea);
			$this->mmaster->insert($iarea,$ikk,$iperiode,$icoa,$vkk,$dkk,$dbukti,$ecoaname,$edescription,$fdebet);
            $nomor=$ikk;
            $eremark		= $edescription;
            $fclose			= 'f';
            $this->mmaster->inserttransheader($ikk,$iarea,$eremark,$fclose,$dkk);
            if($fdebet=='t'){
                $accdebet		  = $icoa;
                $namadebet		= $ecoaname;
                $acckredit		= $coa_area;
                $namakredit		= $this->mmaster->namaacc($acckredit);
            }else{
                $acckredit		= $icoa;
                $namakredit		= $ecoaname;
                $accdebet		  = $coa_area;
                $namadebet		= $this->mmaster->namaacc($accdebet);
            }
            $this->mmaster->inserttransitemdebet($accdebet,$ikk,$namadebet,'t','t',$iarea,$eremark,$vkk,$dkk);
			$this->mmaster->updatesaldodebet($accdebet,$iperiode,$vkk);
			$this->mmaster->inserttransitemkredit($acckredit,$ikk,$namakredit,'f','t',$iarea,$eremark,$vkk,$dkk);
			$this->mmaster->updatesaldokredit($acckredit,$iperiode,$vkk);
			$this->mmaster->insertgldebet($accdebet,$ikk,$namadebet,'t',$iarea,$vkk,$dkk,$eremark);
            $this->mmaster->insertglkredit($acckredit,$ikk,$namakredit,'f',$iarea,$vkk,$dkk,$eremark);
            $this->mmaster->insertrvitem( $irv,$iarea,$icoa,$ecoaname,$vkk,$edescription,$ikk,$irvtype,$iarea);
            $icoa=$coa_area;
            $this->mmaster->insertrv( $irv,$iarea,$iperiode,$icoa,$dkk,$vkk,$eremark,$irvtype);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $kode = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Kas Kecil keluar No:'.$ikk.' Periode:'.$iperiode.' Area:'.$iarea);
                $kode = array(
                    'sukses'    => true,
                    'kode'      => $ikk
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
