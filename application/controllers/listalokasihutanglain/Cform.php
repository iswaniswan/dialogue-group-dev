<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107010901';
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
            'iarea'     => $iarea,
            'total'     => $this->mmaster->total($dfrom,$dto,$iarea)->row()
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $iperiode   = $this->mmaster->cekperiode();
//        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'], $iperiode, $this->global['title']);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'], $iperiode, $this->global['title']);
    }

    public function proses(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $idt        = $this->uri->segment(4);
        $tgl        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $query      = $this->mmaster->cekedit($idt,$tgl,$iarea);
        if($query->num_rows()>0){
            $edit = false;
        }else{
            $edit = true;
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'area1'     => $this->mmaster->cekuser($username, $idcompany),
            'bisaedit'  => $edit,
            'isi'       => $this->mmaster->baca($idt,$iarea,$tgl),
            'detail'    => $this->mmaster->bacadetail($idt,$iarea,$tgl),
            'area'      => $this->mmaster->bacaarea($username, $idcompany),
            'iperiode'  => $this->mmaster->cekperiode()
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function getnota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getnota($cari,$iarea);
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
        $iarea = $this->input->post('iarea');
        $data  = $this->mmaster->getdetailnota($inota,$iarea);
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
        $iarea       = $this->input->post('iarea', TRUE);
        $vjumlah     = $this->input->post('vjumlah',TRUE);
        $vjumlah     = str_replace(',','',$vjumlah);
        $jml         = $this->input->post('jml', TRUE);
        if(($idt!='') && ($iarea!='') && (($vjumlah!='') || ($vjumlah!='0')) && ($ddt!='')){
            $this->db->trans_begin();
            $fsisa='f';
            $this->mmaster->deletedetail($idt,$ddt,$iarea,$vjumlah,$xddt);
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
                    $this->mmaster->insertdetail($idt,$ddt,$inota,$iarea,$dnota,$icustomer,$vsisa,$vjumlah,$x);
                }
            }
            /*die();*/
            $vjumlah = $this->input->post('vjumlah',TRUE);
            $vjumlah = str_replace(',','',$vjumlah);
            $this->mmaster->updateheader($idt,$iarea,$ddt,$vjumlah,$fsisa);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update DT Area:'.$iarea.' No:'.$idt);
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
        $iarea      = $this->uri->segment(5);*/
        $idt        = $this->input->post('idt', TRUE);
        $iarea      = $this->input->post('iarea', TRUE);
        /*var_dump($idt, $iarea);
        die();*/
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($idt, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            /*$data = array(
                'sukses' => false
            );*/
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel Daftar Tagihan Area '.$iarea.' No:'.$idt);
            echo json_encode($data);
            /*$data = array(
                'sukses'    => true,
                'kode'      => $idt
            );*/
        }
    }
}
/* End of file Cform.php */
