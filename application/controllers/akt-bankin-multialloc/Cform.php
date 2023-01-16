<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

	public $global = array();
	public $i_menu = '1051203';

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

	public function index(){
		$username  = $this->session->userdata('username');
		$idcompany = $this->session->userdata('id_company');
		$data = array(
			'folder'    => $this->global['folder'],
			'title'     => $this->global['title'],
			'area'      => $this->mmaster->bacaarea($username, $idcompany)
		);
		$this->Logger->write('Membuka Menu '.$this->global['title']);
		$this->load->view($this->global['folder'].'/vmainform', $data);

	}

	public function view(){
		$dfrom     = $this->input->post('dfrom', TRUE);
		if ($dfrom =='') {
			$dfrom = $this->uri->segment(4);
		}
		$dto       = $this->input->post('dto', TRUE);
		if ($dto   =='') {
			$dto   = $this->uri->segment(5);
		}
		$iarea     = $this->input->post('iarea', TRUE);
		if ($iarea =='') {
			$iarea = $this->uri->segment(6);
		}
		$data = array(
			'folder'    => $this->global['folder'],
			'title'     => $this->global['title'],
			'dfrom'     => $dfrom,
			'dto'       => $dto,
			'iarea'     => $iarea
		);
		$this->Logger->write('Melihat Menu '.$this->global['title']);
		$this->load->view($this->global['folder'].'/vformview', $data);
	}

	public function data(){
		$dfrom      = $this->uri->segment(4);
		$dto        = $this->uri->segment(5);
		$iarea      = $this->uri->segment(6);
		echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder']);
	}

	public function proses(){
		$username   = $this->session->userdata('username');
		$idcompany  = $this->session->userdata('id_company');
		$ikbank     = $this->uri->segment(4);
		$iarea      = $this->uri->segment(5);
		$dfrom      = $this->uri->segment(6);
		$dto        = $this->uri->segment(7);
		$data = array(
			'folder'    => $this->global['folder'],
			'title'     => $this->global['title'],
			'dfrom'     => $dfrom,
			'dto'       => $dto,
			'iarea'     => $iarea,
			'isi'       => $this->mmaster->baca($ikbank,$iarea,$dfrom,$dto)
		);
		$this->Logger->write('Input '.$this->global['title']);
		$this->load->view($this->global['folder'].'/vform', $data);
	}

	public function getdt(){
		$filter = [];
		if($this->input->get('q') != '' && $this->input->get('dbank') !='') {
			$filter     = [];
			$cari       = strtoupper($this->input->get('q'));
			$dbank  	= $this->input->get('dbank', FALSE);
			$dfrom   	= date('Y-m', strtotime($dbank))."-01";
			$dto     	= date('Y-m-d');
			$data       = $this->mmaster->getdt($cari,$dfrom,$dto);
			foreach($data->result() as $kuy){
				$filter[] = array(
					'id'    => $kuy->i_dt,  
					'text'  => $kuy->i_dt." / ".$kuy->d_dt." / ".trim($kuy->i_area)
				);
			}
			echo json_encode($filter);
		} else {
			echo json_encode($filter);
		}
	}

	public function getcustomer(){
		$filter = [];
		if($this->input->get('q') != '' && $this->input->get('iarea') !='' && $this->input->get('idt') !='') {
			$filter     = [];
			$cari       = strtoupper($this->input->get('q'));
			$iarea  	= $this->input->get('iarea', FALSE);
			$idt  		= $this->input->get('idt', FALSE);
			$data       = $this->mmaster->getcustomer($cari,$iarea,$idt);
			foreach($data->result() as $kuy){
				$filter[] = array(
					'id'    => $kuy->i_customer,  
					'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
				);
			}
			echo json_encode($filter);
		} else {
			echo json_encode($filter);
		}
	}  

	public function getdetailcustomer(){
		header("Content-Type: application/json", true);
		$iarea     = $this->input->post('iarea');
		$icustomer = $this->input->post('icustomer');
		$idt       = $this->input->post('idt');
		$data      = $this->mmaster->getdetailcustomer($icustomer,$iarea,$idt);
		echo json_encode($data->result_array());  
	}

	public function getnota(){
		$filter = [];
		if($this->input->get('q') != '' && $this->input->get('icustomer') !='' && $this->input->get('idt') !='') {
			$filter     = [];
			$cari       = strtoupper($this->input->get('q'));
			$icustomer  = $this->input->get('icustomer', FALSE);
			$idt        = $this->input->get('idt', FALSE);
			$igroup     = $this->mmaster->groupbayar($icustomer);
			if ($igroup==''||$igroup==null) {
				$igroup = 'xxx';
			}else{
				$igroup = $igroup;
			}
			$data       = $this->mmaster->getnota($cari,$igroup,$idt);
			foreach($data->result() as $kuy){
				$filter[] = array(
					'id'    => $kuy->i_nota,  
					'text'  => $kuy->i_nota." - ".$kuy->d_nota
				);
			}
			echo json_encode($filter);
		} else {
			echo json_encode($filter);
		}
	}

	public function getdetailnota(){
		header("Content-Type: application/json", true);
		$inota     = $this->input->post('inota');
		$icustomer = $this->input->post('icustomer');
		$idt       = $this->input->post('idt');
		$igroup    = $this->mmaster->groupbayar($icustomer);
		$data      = $this->mmaster->getdetailnota($inota,$igroup,$idt);
		echo json_encode($data->result_array());  
	}

	public function simpan(){
		$data = check_role($this->i_menu, 1);
		if(!$data){
			redirect(base_url(),'refresh');
		}

		$ikbank  			= $this->input->post('ikbank', TRUE);
		$icoabank 			= $this->input->post('icoabank', TRUE);
		$dalokasi  			= $this->input->post('dalokasi', TRUE);
		if ($dalokasi != '') {
			$tmp = explode("-", $dalokasi);
			$th = $tmp[2];
			$bl = $tmp[1];
			$hr = $tmp[0];
			$dalokasi = $th . "-" . $bl . "-" . $hr;
			$thbl = $th . $bl;
			$iperiode = $th . $bl;
		}
		$dbank  = $this->input->post('dbank', TRUE);
		if ($dbank != '') {
			$tmp = explode("-", $dbank);
			$th = $tmp[2];
			$bl = $tmp[1];
			$hr = $tmp[0];
			$dbank = $th . "-" . $bl . "-" . $hr;
		}
		$icustomer     		= $this->input->post('icustomer', TRUE);
		$ecustomername 		= $this->input->post('ecustomername', TRUE);
		$ecustomeraddress 	= $this->input->post('ecustomeraddress', TRUE);
		$ecustomercity 		= $this->input->post('ecustomercity', TRUE);
		$iarea         		= $this->input->post('iarea', TRUE);
		$eareaname     		= $this->input->post('eareaname', TRUE);
		$ebankname     		= $this->input->post('ebankname', TRUE);
		$vjumlahx      		= $this->input->post('vjumlah', TRUE);
		$vjumlahx      		= str_replace(',', '', $vjumlahx);
		$vlebihx       		= $this->input->post('vlebih', TRUE);
		$vlebihx       		= str_replace(',', '', $vlebihx);
		$jml           		= $this->input->post('jml', TRUE);
		$igiro         		= $this->input->post('igiro', TRUE);
		$idt           		= $this->input->post('idt', TRUE);
		$areadt        		= $this->input->post('iareadt', TRUE);
		$ada              	= false;
		if (($dbank != '') && ($dalokasi != '') && ($ikbank != '') && ($vjumlahx != '') && ($vjumlahx != '0') && ($jml != '0') && ($icustomer != '') && ($igiro != '')){
			for ($i = 1; $i <= $jml; $i++) {
				$inota = $this->input->post('inota'.$i, TRUE);
				if ($inota!=''||$inota!=null) {
					$vjumla = $this->input->post('vjumlah' . $i, TRUE);
					$vsisa  = $this->input->post('vsisa' . $i, TRUE);
					$vjumla = str_replace(',', '', $vjumla);
					$vsisa  = str_replace(',', '', $vsisa);
					$vsisa  = $vsisa - $vjumla;
				}
			}
			if (!$ada) {
				$vjumlah = 0;
				$this->db->trans_begin();
				$ialokasi         = $this->mmaster->runningnumberpl($iarea, $thbl);
				$egirodescription = "Alokasi Bank Masuk no:" . $ikbank;
				$fclose           = 'f';
				$jml              = $this->input->post('jml', TRUE);
				for ($i = 1; $i <= $jml; $i++) {
					$inota = $this->input->post('inota'.$i, TRUE);
					if ($inota!=''||$inota!=null) {
						$ireff = $ialokasi . '|' . $inota;
						if ($i == 1) {
							$this->mmaster->inserttransheader($ireff, $iarea, $egirodescription, $fclose, $dalokasi);
						}
						$vjumlah    = $this->input->post('vjumlah'.$i, TRUE);
						$vjumlah    = str_replace(',', '', $vjumlah);
						$accdebet   = PiutangDagang;
						$namadebet  = $this->mmaster->namaacc($accdebet);
						$tmp        = $this->mmaster->carisaldo($accdebet, $iperiode);
						if ($tmp){
							$vsaldoaw1      = $tmp->v_saldo_awal;
						}else{
							$vsaldoaw1      = 0;
						}
						if ($tmp){
							$vmutasidebet1  = $tmp->v_mutasi_debet;
						}else{
							$vmutasidebet1  = 0;
						}
						if ($tmp){
							$vmutasikredit1 = $tmp->v_mutasi_kredit;
						}else{
							$vmutasikredit1 = 0;
						}
						if ($tmp){
							$vsaldoak1      = $tmp->v_saldo_akhir;
						}else{
							$vsaldoak1      = 0;
						}

						$acckredit      = PiutangDagang . $iarea;
						$namakredit     = $this->mmaster->namaacc($acckredit);
						$saldoawkredit  = $this->mmaster->carisaldo($acckredit, $iperiode);
						if ($tmp){
							$vsaldoaw2  = $tmp->v_saldo_awal;
						}else{
							$vsaldoaw2      = 0;
						}
						if ($tmp){
							$vmutasidebet2   = $tmp->v_mutasi_debet;
						}else{
							$vmutasidebet2   = 0;
						}
						if ($tmp){
							$vmutasikredit2   = $tmp->v_mutasi_kredit;
						}else{
							$vmutasikredit2   = 0;
						}
						if ($tmp){
							$vsaldoak2      = $tmp->v_saldo_akhir;
						}else{
							$vsaldoak2      = 0;
						}
						$this->mmaster->inserttransitemdebet($accdebet, $ireff, $namadebet, 't', 't', $iarea, $egirodescription, $vjumlah, $dalokasi, $icoabank);
						$this->mmaster->updatesaldodebet($accdebet, $iperiode, $vjumlah);
						$this->mmaster->inserttransitemkredit($acckredit, $ireff, $namakredit, 'f', 't', $iarea, $egirodescription, $vjumlah, $dalokasi, $icoabank);
						$this->mmaster->updatesaldokredit($acckredit, $iperiode, $vjumlah);
						$this->mmaster->insertgldebet($accdebet, $ireff, $namadebet, 't', $iarea, $vjumlah, $dalokasi, $egirodescription, $icoabank);
						$this->mmaster->insertglkredit($acckredit, $ireff, $namakredit, 'f', $iarea, $vjumlah, $dalokasi, $egirodescription, $icoabank);
					}
				}
				$this->mmaster->insertheader($ialokasi, $ikbank, $iarea, $icustomer, $dbank, $dalokasi, $ebankname, $vjumlahx, $vlebihx, $icoabank, $igiro, $idt, $areadt);
				$asal = 0;
				$pengurang = $vjumlahx - $vlebihx;
				$group = $this->mmaster->groupbayar($icustomer);
				if ($group==''||$group==null) {
					$group = 'xxx';
				}else{
					$group = $group;
				}
				$fupdatebank = $this->mmaster->updatebank($ikbank, $icoabank, $iarea, $pengurang);
				$x = 0;
				for ($i = 1; $i <= $jml; $i++) {
					$inota = $this->input->post('inota'.$i, TRUE);
					if ($inota!=''||$inota!=null) {                         
						$x++;  
						$dnota            = $this->input->post('dnota'.$i, TRUE);
						if ($dnota != '') {
							$tmp = explode("-", $dnota);
							$th = $tmp[2];
							$bl = $tmp[1];
							$hr = $tmp[0];
							$dnota = $th . "-" . $bl . "-" . $hr;
						}
						$vjumlah = $this->input->post('vjumlah' . $i, TRUE);
						$vsisa  = $this->input->post('vsisa' . $i, TRUE);
						$vsiso  = $this->input->post('vsisa' . $i, TRUE);
						$vjumlah = str_replace(',', '', $vjumlah);
						$vsisa = str_replace(',', '', $vsisa);
						$vsiso = str_replace(',', '', $vsiso);
						$vsiso  = $vsiso - $vjumlah;
						$eremark = $this->input->post('eremark' . $i, TRUE);
						$this->mmaster->insertdetail($ialokasi, $ikbank, $iarea, $inota, $dnota, $vjumlah, $vsisa, $x, $eremark, $icoabank);
						$fupdatenota = $this->mmaster->updatenota($inota, $vjumlah);
						if ($fupdatenota == false) {
							break;
						}
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
				$this->Logger->write('Input Bank Allocation No:' . $ialokasi . ' Area:' . $iarea);
				$data = array(
					'sukses'    => true,
					'kode'      => $ialokasi
				);
			}
		}else{
			$data = array(
				'sukses' => false
			);
		}
		$this->load->view('pesan', $data);
	}
}
/* End of file Cform.php */
