<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107011002';
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
            'supplier'  => $this->mmaster->bacasupplier($idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $isupplier     = $this->input->post('isupplier', TRUE);
        if ($isupplier =='') {
            $isupplier = $this->uri->segment(4);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'isupplier' => $isupplier,
//            'iarea'     => $iarea,
            'total'     => $this->mmaster->total($isupplier)->row()
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $isupplier      = $this->uri->segment(4);
        $iperiode   = $this->mmaster->cekperiode();
//        echo $this->mmaster->data($dfrom, $dto, $isupplier, $this->global['folder'], $iperiode, $this->global['title']);
        echo $this->mmaster->data($isupplier, $this->global['folder'], $iperiode, $this->global['title']);
    }

    public function detail(){
        $idtap    = $this->uri->segment(4);
        $isupplier= $this->uri->segment(5);
        $iarea    = $this->uri->segment(6);
        $tahun    = $this->uri->segment(7);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'isi'           => $this->mmaster->bacadetail($idtap,$isupplier,$iarea,$tahun)
        );
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function getnota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('isupplier') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $isupplier      = $this->input->get('isupplier', FALSE);
            $data       = $this->mmaster->getnota($cari,$isupplier);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_nota,  
                    'text'  => $kuy->i_nota." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailnota(){
        header("Content-Type: application/json", true);
        $inota = $this->input->post('inota');
        $isupplier = $this->input->post('isupplier');
        $data  = $this->mmaster->getdetailnota($inota,$isupplier);
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idt        = $this->input->post('idt', TRUE);
        $xddt       = $this->input->post('xddt', TRUE);
        $ddt        = $this->input->post('ddt', TRUE);
        if($ddt!=''){
            $tmp=explode("-",$ddt);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $ddt=$th."-".$bl."-".$hr;
            $tahun=$th;
        }
        if($xddt!=''){
            $tmp=explode("-",$xddt);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $xddt=$th."-".$bl."-".$hr;
        }
        $isupplier       = $this->input->post('isupplier', TRUE);
        $vjumlah     = $this->input->post('vjumlah',TRUE);
        $vjumlah     = str_replace(',','',$vjumlah);
        $jml         = $this->input->post('jml', TRUE);
        if(($idt!='') && ($isupplier!='') && (($vjumlah!='') || ($vjumlah!='0')) && ($ddt!='')){
            $this->db->trans_begin();
            $fsisa='f';
            $this->mmaster->deletedetail($idt,$ddt,$isupplier,$vjumlah,$xddt);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $baris              = $this->input->post('baris'.$i, TRUE);
                $inota              = $this->input->post('inota'.$i, TRUE);
                $dnota              = $this->input->post('dnota'.$i, TRUE);
                if($dnota!=''){
                    $tmp=explode("-",$dnota);
                    $th=$tmp[2];
                    $bl=$tmp[1];
                    $hr=$tmp[0];
                    $dnota=$th."-".$bl."-".$hr;
                }
                $icustomer          = $this->input->post('icustomer'.$i, TRUE);
                $vsisa              = $this->input->post('vsisa'.$i, TRUE);
                $vsisa              = str_replace(',','',$vsisa);
                $vjumlah            = $this->input->post('vjumlah'.$i, TRUE);
                $vjumlah            = str_replace(',','',$vjumlah);
                if($vsisa>0){
                    $fsisa='t';
                }
                if ($inota!='' || $inota!=null) {
                    $x++;
                    $this->mmaster->insertdetail($idt,$ddt,$inota,$isupplier,$dnota,$icustomer,$vsisa,$vjumlah,$x);
                }
            }
            /*die();*/
            $vjumlah = $this->input->post('vjumlah',TRUE);
            $vjumlah = str_replace(',','',$vjumlah);
            $this->mmaster->updateheader($idt,$isupplier,$ddt,$vjumlah,$fsisa);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update DT supplier:'.$isupplier.' No:'.$idt);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idt
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        /*$idt        = $this->uri->segment(4);
        $isupplier      = $this->uri->segment(5);*/
        $idt        = $this->input->post('idt', TRUE);
        $isupplier      = $this->input->post('isupplier', TRUE);
        /*var_dump($idt, $isupplier);
        die();*/
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($idt, $isupplier);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            /*$data = array(
                'sukses' => false
            );*/
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel Daftar Tagihan supplier '.$isupplier.' No:'.$idt);
            echo json_encode($data);
            /*$data = array(
                'sukses'    => true,
                'kode'      => $idt
            );*/
        }
    }
}
/* End of file Cform.php */
