<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040002';

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
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function bank(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_bank");
            $this->db->like("UPPER(i_bank)", $cari);
            $this->db->or_like("UPPER(e_bank_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $isupplier){
                    $filter[] = array(
                    'id'   => $isupplier->i_bank,  
                    'text' => $isupplier->i_bank.'-'.$isupplier->e_bank_name,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    
    function data(){
        $dfrom      = $this->uri->segment('4');
        $dto        = $this->uri->segment('5');
        $ibank      = $this->uri->segment('6');

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

        echo $this->mmaster->data($this->i_menu, $from,$to, $ibank);
    }

    public function list(){
        $dfrom      = $this->input->post('dfrom1', TRUE);       
        $dto        = $this->input->post('dto1', TRUE);       
        $ibank      = $this->input->post('ibank', TRUE);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "List ".$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'ibank'         => $ibank
        );
        $this->Logger->write('Membuka Menu List '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikbank     = $this->uri->segment(4);
        $ibank      = $this->uri->segment(5);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'ebank'      => $this->mmaster->gettrbank($ibank)->row(), 
            'bank'       => $this->mmaster->gettmbank($ikbank)->row() 
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function supplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_supplier");
            $this->db->like("UPPER(i_supplier)", $cari);
            $this->db->or_like("UPPER(e_supplier_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_supplier,  
                    'text' => $icolor->i_supplier.'-'.$icolor->e_supplier_name,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datanota(){
        $filter = [];
        $isupplier = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_notabtb");
            $this->db->where("i_supplier", $isupplier);
             $this->db->order_by('i_nota', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $nota){       
                    $filter[] = array(
                    'id' => $nota->i_nota,  
                    'text' => $nota->i_nota//.' - '.$nota->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    function getnota(){
        header("Content-Type: application/json", true);
        // $i_kb = $this->uri->segment('4');
        $inota = $this->input->post('i_nota');
        //$isupplier = $this->input->post('isupplier');

        $this->db->select("*");
            $this->db->from("tm_notabtb");
            //$this->db->where("i_supplier", $isupplier);
            $this->db->where("i_nota", $inota);
            //$this->db->where("v_sisa", 0);
            $this->db->where("f_status_lunas", 'f');
            $this->db->order_by('i_nota', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
            $ikbank    = $this->input->post('ikbank', TRUE);
            $dalokasi  = $this->input->post('dalokasi', TRUE);
            // if($dalokasi){
            //      $tmp   = explode('-', $dalokasi);
            //      $day   = $tmp[0];
            //      $month = $tmp[1];
            //      $year  = $tmp[2];
            //      $yearmonth = $year.$month;
            //      $dalokasii = $year.'-'.$month.'-'.$day;
            // }
            // if($dalokasi!=''){
            //     $tmp=explode("-",$dalokasi);
            //     $th=$tmp[2];
            //     $bl=$tmp[1];
            //     $hr=$tmp[0];
            //     $dalokasii=$th."-".$bl."-".$hr;
            //     $iperiode=$th.$bl;
            // }
            $icoabank  = $this->input->post('icoabank', TRUE);
            $ebankname = $this->input->post('ebank', TRUE);
            $isupplier = $this->input->post('isupplier', TRUE);
            $iarea     = "00";
            $dbank     = $this->input->post('dbank', TRUE);
            if($dbank!=''){
                $tmp=explode("-",$dbank);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dbank=$th."-".$bl."-".$hr;
                $thbl=$th.$bl;
            }
            $vjumlahx    = $this->input->post('vjumlah',TRUE);
            $vjumlahx    = str_replace(',','',$vjumlahx);
            $vlebihx     = $this->input->post('vlebihh',TRUE);
            $vlebihx     = str_replace(',','',$vlebihx);
            $jml         = $this->input->post('jml', TRUE);
            $ada         = false;

           if(($dbank!='') && ($dalokasi!='') && ($ikbank!='') && ($vjumlahx!='') && ($vjumlahx!='0') && ($jml!='')){
                //if(!$ada) {
                    $this->db->trans_begin();
                    $ialokasi         = $this->mmaster->runningnumberpl($iarea, $thbl);
                    $egirodescription = "Alokasi Bank Keluar No : ".$ikbank;
                    $fclose           = "f";

                    for($i=1;$i<=$jml;$i++){
                        $inota  = $this->input->post('inota'.$i, TRUE);
                        //$idtap = $this->input->post('id_'.$i, TRUE);
                        $ireff = $ialokasi.'|'.$inota; 
                        //if($i==0){
                            $this->mmaster->inserttransheader($ireff, $iarea, $egirodescription, $fclose, $dalokasi); 
                        
                        $ddtap   = $this->input->post('dnota'.$i, TRUE);
                        $vjumlah = $this->input->post('vbayar'.$i, TRUE);
                        $vjumlah = str_replace(',','',$vjumlah);
                        $vsisa   = $this->input->post('vsisa'.$i, TRUE);
                        $vsisa   = str_replace(',','',$vsisa);
                        $eremark = $this->input->post('eremark'.$i,TRUE);
                        $inoitem = $i;
                        $accdebet   = HutangDagang;
                        $acckredit  = HutangDagangSementara;
                        $namakredit = $this->mmaster->namaacc($acckredit);
                        $namadebet  = $this->mmaster->namaacc($accdebet);

                      
                        $this->mmaster->insertdetail($ialokasi, $ikbank, $isupplier, $inota, $ddtap, $vjumlah, $vsisa, $inoitem, $eremark, $icoabank);
                        $this->mmaster->inserttransitemkredit($acckredit, $ireff, $namakredit, $iarea, $egirodescription, $vjumlah, $dalokasi, $icoabank);
                        $this->mmaster->inserttranskredit($ikbank,$iarea,$dalokasi,$icoabank);
                        $this->mmaster->inserttransitemdebet($accdebet,$ireff,$namadebet,$iarea,$egirodescription,$vjumlah,$dalokasi,$icoabank);
                        $this->mmaster->updatenota($inota,$isupplier,$vjumlah);
                        $this->mmaster->insertgldebet($accdebet,$ireff,$namadebet,$vjumlah,$dalokasi,$iarea,$egirodescription,$icoabank);
                        $this->mmaster->insertglkredit($acckredit,$ireff,$namakredit,$vjumlah,$dalokasi,$iarea,$egirodescription,$icoabank);
                    }
                    $this->mmaster->insertheader($ialokasi,$ikbank,$isupplier,$dalokasi,$ebankname,$vjumlahx,$vlebihx,$icoabank);
                    $asal      = 0;
                    $pengurang = $vjumlahx-$vlebihx;
                    $this->mmaster->updatebank($ikbank,$icoabank,$isupplier,$pengurang);

                    //jika sisa uang lebih dari dan kurang dari 100
                    if ($vlebihx > 0 && $vlebihx <= 100) {
                        $egirodescription = "Alokasi Bank Keluar No : ".$ikbank.'('.$icoabank.')';
                        $ireff = $ialokasi.'|'.$ikbank;
                        $this->mmaster->inserttransheader($ireff,$iarea,$egirodescription,$fclose,$dalokasi);
                        $vjumlah    = $this->input->post('vlebih', TRUE);
                        $vjumlah    = str_replace(',','',$vjumlah);
                        $accdebet   = ByPembulatan;
                        $namadebet  = $this->mmaster->namaacc($accdebet);
                        $tmp        = $this->mmaster->carisaldo($accdebet,$iperiode);
                        if($tmp){
                            $vsaldoaw1    = $tmp->v_saldo_awal;
                        }else{
                            $vsaldoaw1    = 0;
                        }
                        if($tmp){
                            $vmutasidebet1  = $tmp->v_mutasi_debet;
                        }else{
                            $vmutasidebet1  = 0;
                        }
                        if($tmp){
                            $vmutasikredit1 = $tmp->v_mutasi_kredit;
                        }else{
                            $vmutasikredit1 = 0;
                        }
                        if($tmp) {
                            $vsaldoak1    = $tmp->v_saldo_akhir;
                        }else{
                            $vsaldoak1    = 0;
                        }

                        $acckredit     = $icoabank;
                        $namakredit    = $this->mmaster->namaacc($acckredit);
                        $saldoawkredit = $this->mmaster->carisaldo($acckredit,$iperiode);
                        if($tmp) {
                            $vsaldoaw2    = $tmp->v_saldo_awal;
                        }else{
                            $vsaldoaw2    = 0;
                        }
                        if($tmp){
                            $vmutasidebet2  = $tmp->v_mutasi_debet;
                        }else{
                            $vmutasidebet2  = 0;
                        }
                        if($tmp){
                            $vmutasikredit2 = $tmp->v_mutasi_kredit;
                        }else{
                            $vmutasikredit2 = 0;
                        }
                        if($tmp){
                            $vsaldoak2    = $tmp->v_saldo_akhir;
                        }else{
                            $vsaldoak2    = 0;
                        }
                        $this->mmaster->insertdetail($ialokasi, $ikbank, $isupplier, $inota, $ddtap, $vjumlah, $vsisa, $inoitem, $eremark, $icoabank);
                        $this->mmaster->inserttransitemkredit($acckredit, $ireff, $namakredit, $iarea, $egirodescription, $vjumlah, $dalokasi, $icoabank);
                        $this->mmaster->inserttranskredit($ikbank,$iarea,$dalokasi,$icoabank);
                        $this->mmaster->inserttransitemdebet($accdebet,$ireff,$namadebet,$iarea,$egirodescription,$vjumlah,$dalokasi,$icoabank);
                        $this->mmaster->updatenota($inota,$isupplier,$vjumlah);
                        $this->mmaster->insertgldebet($accdebet,$ireff,$namadebet,$vjumlah,$dalokasi,$iarea,$egirodescription,$icoabank);
                        $this->mmaster->insertglkredit($acckredit,$ireff,$namakredit,$vjumlah,$dalokasi,$iarea,$egirodescription,$icoabank);
                        $this->mmaster->updatebank($ikbank,$icoabank,$iarea,$vjumlah);
                    }
                //}       
                if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'      => $ialokasi,
                    );
                }
            }
                
        $this->load->view('pesan', $data);  
    }
}
/* End of file Cform.php */