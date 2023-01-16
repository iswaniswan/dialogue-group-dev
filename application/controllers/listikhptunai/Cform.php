<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107012002';

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
        require('php/fungsi.php');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->area($username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function data(){
        $dfrom       = $this->uri->segment(4);
        $dto         = $this->uri->segment(5);
        $iarea       = $this->uri->segment(6);
        $tmp         = explode("-", $dfrom);
        $tahun       = $tmp[2];
        $bulan       = $tmp[1];
        $tanggal     = '01';
        $dsaldo      = $tahun."/".$bulan."/".$tanggal;
        $dtos        = dateAdd("d",-1,$dsaldo);
        $tmp         = explode("-", $dtos);
        $th          = $tmp[0];
        $bl          = $tmp[1];
        $dt          = $tmp[2];
        $xfrom       = $dt."-".$bl."-".$th;
        $saldo       = $this->mmaster->bacasaldo($iarea,$dfrom,$xfrom)->row();
        $saldoawalt  = $saldo->saldotunai;
        $saldoawalg  = $saldo->saldogiro;
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'], $saldoawalt, $saldoawalg, $xfrom);
        $akhir       = $this->mmaster->saldoakhir($dfrom, $dto, $iarea, $saldoawalt, $saldoawalg, $xfrom)->row();
        $saldoakhirt = $akhir->saldotunai;
        $saldoakhirg = $akhir->saldogiro;
        $ceksaldo    = $this->mmaster->ceksaldo($dto, $saldoakhirt, $saldoakhirg, $iarea);
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
        $tmp       = explode("-", $dfrom);
        $tahun     = $tmp[2];
        $bulan     = $tmp[1];
        $tanggal   = '01';
        $dsaldo    = $tahun."/".$bulan."/".$tanggal;
        $dtos      = dateAdd("d",-1,$dsaldo);
        $tmp       = explode("-", $dtos);
        $th        = $tmp[0];
        $bl        = $tmp[1];
        $dt        = $tmp[2];
        $xfrom     = $dt."-".$bl."-".$th;
        $data  = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'saldo'     => $this->mmaster->bacasaldo($iarea,$dfrom,$xfrom)->row()
        );
        $this->Logger->write("Membuka Data IKHP Area ".$iarea." Periode:".$dfrom." s/d ".$dto);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function cek(){
        $idt        = $this->uri->segment(4);
        $iarea      = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $tgl        = date('Y-m-d', strtotime($this->uri->segment(8)));
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'isi'       => $this->mmaster->baca($idt,$iarea,$tgl),
            'detail'    => $this->mmaster->bacadetail($idt,$iarea,$tgl),
        );
        $this->Logger->write('Membuka '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom  = $this->input->post('dfrom', TRUE);
        $dto    = $this->input->post('dto', TRUE);
        $iarea  = $this->input->post('iarea', TRUE);
        $user   = $this->session->userdata('username');
        if(($iarea!='') && ($dfrom!='') && ($dto!='')){
            $this->db->trans_begin();
            $this->mmaster->updateikhp($iarea,$dfrom,$dto,$user);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Cek IKHP periode:'.$dfrom.' s/d '.$dto.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => ""
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
