<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '21101';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }  

/* 
    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
            'kunjungan' => $this->mmaster->bacakunjungan()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }
 */ 

    public function index(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            // 'dfrom'     => $dfrom,
            // 'dto'       => $dto,
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data()
    {       
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'kodearea'      => $this->mmaster->kodearea()->result(),
            'kodesalesman'  => $this->mmaster->kodesalesman()->result(),
            'number'        => "RRKH-".date('ym')."-000001",
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            // 'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function datarencana(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->datarencana($cari);
        foreach($data->result() as $rencana){       
            $filter[] = array(
                'id'    => $rencana->id_rencana,
                'name'  => $rencana->nama_rencana,
                'text'  => $rencana->id_rencana.' - '.$rencana->nama_rencana
            );
        }   
        echo json_encode($filter);
    }

    public function datacustomer(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->datacustomer($cari);
        foreach($data->result() as $customer){       
            $filter[] = array(
                'id'    => $customer->id_customer,
                'name'  => $customer->e_customer_name,
                'text'  => $customer->i_customer.' - '.$customer->e_customer_name.' - '.$customer->area
            );
        }   
        echo json_encode($filter);
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getsalesman($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_salesman,  
                    'text'  => $kuy->i_salesman." - ".$kuy->e_salesman_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcustomer(){
        /* $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcustomer($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        } */
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getcustomer($this->input->post('ecust'));

        echo json_encode($data->result_array());
    }

    public function getcity(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcity($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_city,  
                    'text'  => $kuy->i_city." - ".$kuy->e_city_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $folder     = $this->global['folder'];
        $isalesman  = $this->input->post('isalesman', TRUE);
        $drrkh      = $this->input->post('drrkh', TRUE);
        $dreceive1  = $this->input->post('dreceive1', TRUE);
        if($drrkh!=''){
            $tmp=explode("-",$drrkh);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $drrkh=$th."-".$bl."-".$hr;
        }
        if($dreceive1!=''){
            $tmp=explode("-",$dreceive1);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $drec1=$th."-".$bl."-".$hr;
        }else{
            $drec1='';
        }
        $iarea      = $this->input->post('iarea', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if($drrkh!='' && $isalesman!='' && $iarea!=''){
            $this->db->trans_begin();
            $cekdata = $this->mmaster->cekdata($isalesman, $drrkh, $iarea);
            if($cekdata->num_rows() > 0){
                echo "<script>swal('Data Sudah Ada');show('".$folder."/cform/','#main');</script>";
                die();
            }
            $this->mmaster->insertheader($isalesman, $drrkh, $iarea,$drec1);
            for($i=1;$i<=$jml;$i++){
                $icustomer            = $this->input->post('icustomer'.$i, TRUE);
                $ikunjungantype       = $this->input->post('ikunjungantype'.$i, TRUE);
                $icity                = $this->input->post('icity'.$i, TRUE);
                $fkunjunganrealisasi  = $this->input->post('fkunjunganrealisasi'.$i, TRUE);
                if($fkunjunganrealisasi == 'on') {
                    $fkunjunganrealisasi ='t'; 
                }else{
                    $fkunjunganrealisasi ='f';
                }
                $fkunjunganvalid      = $this->input->post('fkunjunganvalid'.$i, TRUE);
                if($fkunjunganvalid == 'on'){
                    $fkunjunganvalid = 't';
                }else{
                    $fkunjunganvalid = 'f';
                }
                $eremark              = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertdetail($isalesman,$drrkh,$iarea,$icustomer,$ikunjungantype,$icity,$fkunjunganrealisasi,$fkunjunganvalid,$eremark,$i);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input RRKH Salesman:'.$isalesman.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => "Tanggal ".$drrkh." / Area ".$iarea." / Salesman ".$isalesman
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
