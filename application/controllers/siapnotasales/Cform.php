<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1030105';

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
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        $count      = $this->mmaster->total();
        $total      = $count->num_rows();
        echo $this->mmaster->data($this->global['folder'], $total);
    }

    public function detail(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $ispb    = $this->uri->segment(4);
        $iarea   = $this->uri->segment(5);
        $ipgroup = $this->uri->segment(6);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'isi'       => $this->mmaster->baca($ispb,$iarea),
            'detail'    => $this->mmaster->bacadetail($ispb,$iarea,$ipgroup)
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $jml = $this->input->post('jml', TRUE);
        $this->db->trans_begin();
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $ispb = $this->input->post('ispb'.$i, TRUE);
                $iarea= $this->input->post('iarea'.$i, TRUE);
                $this->mmaster->updatespb($ispb, $iarea);
            }
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update SPB Siap Nota Sales No:'.$ispb.' Area:'.$iarea);
            $data = array(
                'sukses'    => true,
                'kode'      => ""
            );
        }
        $this->load->view('pesan', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb           = $this->input->post('ispb', TRUE);
        $dspb           = $this->input->post('dspb', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomername', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $eareaname      = $this->input->post('eareaname', TRUE);
        $ispbpo         = $this->input->post('ispbpo', TRUE);
        $nspbtoplength  = $this->input->post('nspbtoplength', TRUE);
        $isalesman      = $this->input->post('isalesman',TRUE);
        $esalesmanname  = $this->input->post('esalesmanname',TRUE);
        $ipricegroup    = $this->input->post('ipricegroup',TRUE);
        $inota          = $this->input->post('inota',TRUE);
        $dspbreceive    = $this->input->post('dspbreceive',TRUE);
        if($ispbpo!=''){
            $fspbop         = 't';
        }else{
            $fspbop         = 'f';
        }
        $ecustomerpkpnpwp   = $this->input->post('ecustomerpkpnpwp',TRUE);
        if($ecustomerpkpnpwp!=''){
            $fspbpkp        = 't';
        }else{
            $fspbpkp        = 'f';
        }
        $fspbconsigment     = $this->input->post('fspbconsigment',TRUE);
        if($fspbconsigment!=''){
            $fspbconsigment="t";
        }else{
            $fspbconsigment="f";
        }
        $fspbplusppn        = $this->input->post('fspbplusppn',TRUE);
        $fspbplusdiscount   = $this->input->post('fspbplusdiscount',TRUE);
        $fspbstockdaerah    = $this->input->post('fspbstockdaerah',TRUE);
        if($fspbstockdaerah!=''){
            $fspbstockdaerah= 't';
        }else{
            $fspbstockdaerah= 'f';
        }
        $fspbprogram        = 'f';
        $fspbvalid          = 't';
        $fspbsiapnotagudang = 't';
        $fspbcancel         = 'f';

        $nspbdiscount1      = $this->input->post('ncustomerdiscount1',TRUE);
        $nspbdiscount2      = $this->input->post('ncustomerdiscount2',TRUE);
        $nspbdiscount3      = $this->input->post('ncustomerdiscount3',TRUE);
        $vspbdiscount1      = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscount2      = $this->input->post('vcustomerdiscount2',TRUE);
        $vspbdiscount3      = $this->input->post('vcustomerdiscount3',TRUE);
        $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal',TRUE);
        $vspb               = $this->input->post('vspb',TRUE);
        $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
        $nspbdiscount2      = str_replace(',','',$nspbdiscount2);
        $nspbdiscount3      = str_replace(',','',$nspbdiscount3);
        $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        $vspbdiscount2      = str_replace(',','',$vspbdiscount2);
        $vspbdiscount3      = str_replace(',','',$vspbdiscount3);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspb               = str_replace(',','',$vspb);
        $vspbdiscounttotalafter = $this->input->post('vspbdiscounttotalafter',TRUE);
        $vspbafter          = $this->input->post('vspbafter',TRUE);
        $vspbdiscounttotalafter = str_replace(',','',$vspbdiscounttotalafter);
        $vspbafter          = str_replace(',','',$vspbafter);
        $jml            = $this->input->post('jml', TRUE);
        if(($ecustomername!='') && ($ispb!='')){
            $this->db->trans_begin();
            $this->mmaster->updateheader($ispb, $iarea, $dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$vspbdiscounttotalafter,$vspbafter);
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $iproductstatus = $this->input->post('iproductstatus'.$i, TRUE);
                $iproductgrade  = 'A';
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice     = $this->input->post('vproductretail'.$i, TRUE);
                $vunitprice     = str_replace(',','',$vunitprice);
                $norder         = $this->input->post('norder'.$i, TRUE);
                $ndeliver       = $this->input->post('ndeliver'.$i, TRUE);
                $eremark        = $this->input->post('eremark'.$i, TRUE);
                $iproductstatus = $this->input->post('iproductstatus'.$i, TRUE);
                $this->mmaster->updatedetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update SPB Siap Nota Sales No:'.$ispb.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
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
