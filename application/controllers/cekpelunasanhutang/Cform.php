<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10516';

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
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function getsupplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getsupplier($cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_supplier,  
                    'text'  => $row->i_supplier.' - '.$row->e_supplier_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $isupplier, $this->global['folder']);
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
        $isupplier     = $this->input->post('isupplier', TRUE);
        if ($isupplier =='') {
            $isupplier = $this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'isupplier' => $isupplier
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function cek(){
        $ipelunasan = $this->uri->segment(4);
        $isupplier  = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'isupplier' => $isupplier,
            'vsisa'     => $this->mmaster->sisa($isupplier,$ipelunasan),
            'isi'       => $this->mmaster->bacapl($isupplier,$ipelunasan),
            'detail'    => $this->mmaster->bacadetailpl($isupplier,$ipelunasan),
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipl        = $this->input->post('ipelunasanap', TRUE);
        $isupplier  = $this->input->post('isupplier', TRUE);
        $ecek1      = $this->input->post('ecek1',TRUE);
        if($ecek1==''){
            $ecek1=null;
        }
        $user = $this->session->userdata('username');
        if(($isupplier!='') && ($ipl!='')){
            $this->db->trans_begin();
            $this->mmaster->updatecek($ecek1,$user,$ipl,$isupplier);
            $iarea              = '00';
            $dbukti             = $this->input->post('dpelunasanap', TRUE);
            $iperiode           = date('Ym', strtotime($dbukti));
            $dbukti             = date('Y-m-d', strtotime($dbukti));
            $suppname           = $this->input->post('esuppliername', TRUE);
            $esuppname          = str_replace("'","''",$suppname);
            $egirodescription   = "Pelunasan hutang kepada:".$suppname;
            $fclose             = 'f';
            $jml                = $this->input->post('jml', TRUE);
            /*------------------| Posting |--------------------*/
            for($i=1;$i<=$jml;$i++){
                $inota=$this->input->post('inota'.$i, TRUE);
                $ireff=$ipl.'|'.$inota; 
                if($i==1){
                    $this->mmaster->inserttransheader($ireff,$iarea,$egirodescription,$fclose,$dbukti);
                    $this->mmaster->updatepelunasan($ipl,$iarea,$dbukti);
                }
                $vjumlah        = $this->input->post('vjumlah'.$i, TRUE);
                $vjumlah        = str_replace(',','',$vjumlah);
                $accdebet       = HutangDagang;
                $namadebet      = $this->mmaster->namaacc($accdebet);
                $tmp            = $this->mmaster->carisaldo($accdebet,$iperiode);
                if($tmp){
                    $vsaldoaw1  = $tmp->v_saldo_awal;
                }else{
                    $vsaldoaw1  = 0;
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
                if($tmp){
                    $vsaldoak1      = $tmp->v_saldo_akhir;
                }else{
                    $vsaldoak1      = 0;
                }

                $acckredit          = KasBesar;
                $namakredit         = $this->mmaster->namaacc($acckredit);
                $saldoawkredit      = $this->mmaster->carisaldo($acckredit,$iperiode);
                if($tmp) {
                    $vsaldoaw2      = $tmp->v_saldo_awal;
                }else{
                    $vsaldoaw2      = 0;
                }
                if($tmp) {
                    $vmutasidebet2  = $tmp->v_mutasi_debet;
                }else{
                    $vmutasidebet2  = 0;
                }
                if($tmp) {
                    $vmutasikredit2 = $tmp->v_mutasi_kredit;
                }else{
                    $vmutasikredit2 = 0;
                }
                if($tmp) {
                    $vsaldoak2      = $tmp->v_saldo_akhir;
                }else{
                    $vsaldoak2      = 0;
                }
                $this->mmaster->inserttransitemdebet($accdebet,$ireff,$namadebet,'t','t',$iarea,$egirodescription,$vjumlah,$dbukti);
                $this->mmaster->updatesaldodebet($accdebet,$iperiode,$vjumlah);
                $this->mmaster->inserttransitemkredit($acckredit,$ireff,$namakredit,'f','t',$iarea,$egirodescription,$vjumlah,$dbukti);
                $this->mmaster->updatesaldokredit($acckredit,$iperiode,$vjumlah);
                $this->mmaster->insertgldebet($accdebet,$ireff,$namadebet,'t',$iarea,$vjumlah,$dbukti,$egirodescription);
                $this->mmaster->insertglkredit($acckredit,$ireff,$namakredit,'f',$iarea,$vjumlah,$dbukti,$egirodescription);

            }

            /*---------------------| End Posting |-----------------*/
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Cek Pelunasan Hutang No:'.$ipl.' Supplier:'.$isupplier);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ipl
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
