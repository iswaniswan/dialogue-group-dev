<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050201';

    public function __construct(){
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder']);
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

    public function proses(){
        $dsj    = $this->uri->segment(4);
        $isj    = $this->uri->segment(5);
        $iarea  = $this->uri->segment(6);
        $dfrom  = $this->uri->segment(7);
        $dto    = $this->uri->segment(8);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'isi'       => $this->mmaster->baca($isj,$iarea),
            'detail'    => $this->mmaster->bacadetail($isj,$iarea)
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb       = $this->input->post('ispb', TRUE);
        $eareaname  = $this->input->post('eareaname', TRUE);
        $isj        = $this->input->post('isj', TRUE);
        $inotaold   = $this->input->post('inotaold', TRUE);
        $iarea      = $this->input->post('iarea', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        $dnota      = $this->input->post('dnota', TRUE);
        if($dnota!=''){
            $tmp  = explode("-", $dnota);
            $det  = $tmp[0];
            $mon  = $tmp[1];
            $yir  = $tmp[2];
            $ddspb    = $yir."/".$mon."/".$det;
            $iperiode=$yir.$mon;
            $nnotatoplength = $this->input->post('nspbtoplength',TRUE);
            if($nnotatoplength<0) {
                $nnotatoplength = $nnotatoplength*-1;
            }
            $dudet    = $this->fungsi->dateAdd("d",$nnotatoplength,$ddspb);
            $djatuhtempo=$dudet;

            $tmp=explode("-",$dnota);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dnota=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $nprice     = $this->input->post('nprice',TRUE);
        $inota      = $this->input->post('inota', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        if(($dnota!='') && ($inota=='')){
            $fclose         = 'f';
            $icustomer      = $this->input->post('icustomer', TRUE);
            $ecustomername  = $this->input->post('ecustomername', TRUE);
            $eremarkacc     = "Penjualan kepada:".$icustomer."-".$ecustomername;
            $this->db->trans_begin();
            $inota                  = $this->mmaster->runningnumber($iarea,$thbl);
            $vspbdiscounttotalafter = $this->input->post('vspbdiscounttotalafter',TRUE);
            $vspbdiscounttotalafter = str_replace(',','',$vspbdiscounttotalafter);
            $vspbafter              = $this->input->post('vspbafter',TRUE);
            $vspbafter              = str_replace(',','',$vspbafter);
            $this->mmaster->updatespb($ispb,$iarea,$inota,$dnota,$vspbdiscounttotalafter,$vspbafter);
            $this->mmaster->updatenotabaru($isj,$iarea,$inota,$dnota,$eremark,$inotaold,$djatuhtempo,$nnotatoplength,$nprice,$vspbdiscounttotalafter,$vspbafter);
            $gros=$vspbafter+$vspbdiscounttotalafter;
            $vdpp=$vspbafter/1.1;
            $vdis=$vspbdiscounttotalafter;
            $vpen=$vdpp+$vspbdiscounttotalafter;
            $vppn=$vdpp*0.1;
            $vpiu=$vspbafter;
            $this->mmaster->inserttransheader($inota,$iarea,$eremarkacc,$fclose,$dnota);
            $this->mmaster->updatenotaacc($inota,$iarea);
            $accdebet         = PiutangDagang.$iarea;
            $namadebet        = $this->mmaster->namaacc($accdebet);
            if($namadebet=='')$namadebet='Piutang Dagang ('.$eareaname.')';
            $accdebet2        = PotonganPenjualan;
            $namadebet2       = $this->mmaster->namaacc($accdebet2);
            $acckredit        = HasilPenjualanKotor;
            $namakredit       = $this->mmaster->namaacc($acckredit);
            $acckredit2       = HutangPPN;
            $namakredit2      = $this->mmaster->namaacc($acckredit2);
            $this->mmaster->inserttransitemdebet($accdebet,$inota,$namadebet,'t','t',$iarea,$eremarkacc,$vpiu,$dnota);
            $this->mmaster->updatesaldodebet($accdebet,$iperiode,$vpiu);
            if($vspbdiscounttotalafter!='' && $vspbdiscounttotalafter!=0 && $vspbdiscounttotalafter!='0'){              
                $this->mmaster->inserttransitemdebet($accdebet2,$inota,$namadebet2,'t','t',$iarea,$eremarkacc,$vdis,$dnota);
                $this->mmaster->updatesaldodebet($accdebet2,$iperiode,$vdis);
            }
            $this->mmaster->inserttransitemkredit($acckredit,$inota,$namakredit,'f','t',$iarea,$eremarkacc,$vpen,$dnota);
            $this->mmaster->updatesaldokredit($acckredit,$iperiode,$vpen);
            $this->mmaster->inserttransitemkredit($acckredit2,$inota,$namakredit2,'f','t',$iarea,$eremarkacc,$vppn,$dnota);
            $this->mmaster->updatesaldokredit($acckredit2,$iperiode,$vppn);
            $this->mmaster->insertgldebet($accdebet,$inota,$namadebet,'t',$iarea,$vpiu,$dnota,$eremarkacc);
            if($vspbdiscounttotalafter!='' && $vspbdiscounttotalafter!=0 && $vspbdiscounttotalafter!='0'){              
                $this->mmaster->insertgldebet($accdebet2,$inota,$namadebet2,'t',$iarea,$vdis,$dnota,$eremarkacc);
            }
            $this->mmaster->insertglkredit($acckredit,$inota,$namakredit,'f',$iarea,$vpen,$dnota,$eremarkacc);
            $this->mmaster->insertglkredit($acckredit2,$inota,$namakredit2,'f',$iarea,$vppn,$dnota,$eremarkacc);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input Nota No:'.$inota);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $inota
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
