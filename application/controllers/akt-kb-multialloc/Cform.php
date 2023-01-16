<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1051102';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    function data(){
    	$dfrom = $this->uri->segment('4');
        $dto = $this->uri->segment('5');
        
        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$dd.'-'.$mm.'-'.$yy;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$dd.'-'.$mm.'-'.$yy;
            
    	echo $this->mmaster->data($from,$to,$this->i_menu);
    }

    function databank(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" * from tr_bank
                                where (upper(i_bank) like '%$cari%' or upper(e_bank_name) like '%$cari%')",false);
            $data = $this->db->get();
            foreach($data->result() as  $ibank){
                    $filter[] = array(
                    'id' => $ibank->i_bank,  
                    'text' => $ibank->i_bank.'-'.$ibank->e_bank_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function view(){
    	$dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikb = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_kb($ikb)->row(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    function data_supplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" * from tr_supplier where upper(i_supplier) like '%$cari%' 
                                or upper(e_supplier_name) like '%$cari%' ",false);
            $data = $this->db->get();
            foreach($data->result() as  $isupplier){
                $filter[] = array(
                    'id' => $isupplier->i_supplier,  
                    'text' => $isupplier->i_supplier.'-'.$isupplier->e_supplier_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datanota(){
        $filter = [];
        $isupplier = str_replace('%20','',$this->uri->segment('4'));
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_dtap");
            $this->db->where("v_sisa > 0");
            $this->db->where("i_supplier", $isupplier);
            $this->db->like ("UPPER (i_dtap)", $cari);
            $this->db->order_by('i_dtap', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $dtap){       
                $filter[] = array(
                    'id' => $dtap->i_dtap,  
                    'text' => $dtap->i_dtap
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    function getdtapitem(){
        header("Content-Type: application/json", true);
        $idtap = $this->input->post('i_dtap');
        $isupplier = str_replace('%20','',$this->uri->segment('4'));
        $this->db->select("*");
        $this->db->from("tm_dtap");
        $this->db->where("v_sisa > 0");
        $this->db->where("i_supplier", $isupplier);
        $this->db->order_by('i_dtap', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikb  = $this->input->post('ikb', TRUE);
        $dalokasi  = $this->input->post('dalokasi', TRUE);
        $dkb  = $this->input->post('dkb', TRUE);
        if($dalokasi!=''){
            $tmp=explode("-",$dalokasi);
            $th=$tmp[0];
            $bl=$tmp[1];
            $hr=$tmp[2];
            $dalokasi=$th."-".$bl."-".$hr;
            $iperiode=$th.$bl;
        }
   		$dkb  = $this->input->post('dkb', TRUE);
        if($dkb!=''){
           $tmp=explode("-",$dkb);
           $th=$tmp[0];
           $bl=$tmp[1];
           $hr=$tmp[2];
           $dkb=$th."-".$bl."-".$hr;
           $thbl=$th.$bl;
        }
        $isupplier  = $this->input->post('isupplier', TRUE);
        $iarea      = '00';
        $vjumlahx   = $this->input->post('vjumlah',TRUE);
        $vjumlahx   = str_replace(',','',$vjumlahx);
        $vlebih     = $this->input->post('vlebih',TRUE);
        $vlebih     = str_replace(',','',$vlebih);
        $jml        = $this->input->post('jml', TRUE);
        $ada=false;
        if(($dkb!='') && ($ikb!='') && ($vjumlahx!='') && ($vjumlahx!='0') && ($jml!='0')){
            if(!$ada){
                $this->db->trans_begin();
                $idtapx = $this->input->post('idtap1', TRUE);
                $ialokasi=$this->mmaster->runningnumberpl($iarea,$thbl,$idtapx);
                $egirodescription="Alokasi Kas Besar Keluar no:".$ikb;
                $fclose     = 'f';
                $jml      = $this->input->post('jml', TRUE);
                for($i=1;$i<=$jml;$i++){
                    $idtap=$this->input->post('idtap'.$i, TRUE);
                    $ireff=$ialokasi.'|'.$idtap; 
                    if($i==1){
                      $this->mmaster->inserttransheader($ireff,$iarea,$egirodescription,$fclose,$dalokasi);
                    }
                    $idtap      = $this->input->post('idtap'.$i, TRUE);
                    $ddtap      = $this->input->post('ddtap'.$i, TRUE);
                    $vjumlah    = $this->input->post('vjumlah'.$i, TRUE);
                    $vjumlah    = str_replace(',','',$vjumlah);
                    if($ddtap!=''){
                        $tmp=explode("-",$ddtap);
                        $th=$tmp[0];
                        $bl=$tmp[1];
                        $hr=$tmp[2];
                        $ddtap=$th."-".$bl."-".$hr;
                    }
                    $iarea         ='00';
                    $vjumlah       = $this->input->post('vjumlah'.$i, TRUE);
                    $vsisa         = $this->input->post('vsisa'.$i, TRUE);
                    $vsiso         = $this->input->post('vsisa'.$i, TRUE);
                    $vjumlah       = str_replace(',','',$vjumlah);
                    $vsisa         = str_replace(',','',$vsisa);
                    $vsiso         = str_replace(',','',$vsiso);
                    $vsiso         = $vsiso-$vjumlah;
                    $accdebet      = HutangDagang;
                    $acckredit     = HutangDagangSementara;
                    $namakredit    = $this->mmaster->namaacc($acckredit);
                    $namadebet     = $this->mmaster->namaacc($accdebet);
                    $eremark       = $this->input->post('eremark'.$i,TRUE);
                    $this->mmaster->insertdetail($ialokasi,$ikb,$isupplier,$idtap,$ddtap,$vjumlah,$vsisa,$i,$eremark);
                    $this->mmaster->inserttransitemkredit($acckredit,$ireff,$namakredit,'f','t',$iarea,$egirodescription,$vjumlah,$dalokasi);
                    $this->mmaster->inserttranskredit($ikb,$iarea,$dalokasi);
                    $this->mmaster->inserttransitemdebet($accdebet,$ireff,$namadebet,'t','t',$iarea,$egirodescription,$vjumlah,$dalokasi);
                    $this->mmaster->updatenota($idtap,$isupplier,$vjumlah);
                    $this->mmaster->insertgldebet($acckredit,$ireff,$namadebet,'f',$vjumlah,$dalokasi,$iarea,$egirodescription);
                    $this->mmaster->insertglkredit($accdebet,$ireff,$namakredit,'t',$vjumlah,$dalokasi,$iarea,$egirodescription);
                }
                $this->mmaster->insertheader($ialokasi,$ikb,$isupplier,$dalokasi,$vjumlahx,$vlebih);
                $asal=0;
                $pengurang=$vjumlahx-$vlebih;
                $this->mmaster->updatekasbesar($ikb,$isupplier,$pengurang);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Nomor Alokasi Bank Keluar'.$this->global['title'].' Kode : '.$ialokasi);

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
}

/* End of file Cform.php */
