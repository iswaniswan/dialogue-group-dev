<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107031802';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function dataarea(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacaarea($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_area,  
                'text'  => $row->e_area_name
            );
        }
        echo json_encode($filter);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        $areasj = $this->mmaster->bacaareasj($iarea);
        echo $this->mmaster->data($dfrom,$dto,$iarea,$areasj,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $area   = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        $areasj = $this->mmaster->bacaareasj($area);
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area,
            'areasj'        => $areasj
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id     = $this->input->post('id');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($id, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus BAPB SJP Area '.$iarea.' No:'.$id);
            echo json_encode($data);
        }
    }

    public function edit(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $id         = $this->uri->segment(4);
            $iarea      = $this->uri->segment(5);
            $dfrom      = $this->uri->segment(6);
            $dto        = $this->uri->segment(7);
            $icustomer  = $this->uri->segment(8);
            $jmlitem    = $this->mmaster->jumlahitem($id,$iarea);
            $jmlitemx   = $this->mmaster->jumlaheskpedisi($id,$iarea);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'icustomer'     => $icustomer,
                'jmlitem'       => $jmlitem->num_rows(),
                'jmlitemx'      => $jmlitemx->num_rows(),
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($id,$iarea),
                'detail'        => $this->mmaster->bacadetail($id,$iarea),
                'detailx'       => $this->mmaster->bacadetailx($id,$iarea),
                'kirim'         => $this->mmaster->bacadkb($username,$idcompany)
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    // public function getpelanggan(){
    //     $filter = [];
    //     if($this->input->get('i_area') != '' ) {
    //         $filter = [];
    //         $iarea  = $this->input->get('i_area');
    //         $cari = strtoupper($this->input->get('q'));
    //         $data = $this->mmaster->getpelanggan($iarea, $cari);
    //         foreach($data->result() as $row){
    //             $filter[] = array(
    //                 'id'    => $row->i_customer,  
    //                 'text'  => $row->i_customer.' - '.$row->e_customer_name
    //             );
    //         }        
    //         echo json_encode($filter);
    //     } else {
    //         echo json_encode($filter);
    //     }
    // }

    public function datasj(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = $this->input->get('iarea', FALSE);
            //$icust   = $this->input->get('icus', FALSE);
            $data    = $this->mmaster->bacasj($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sj,  
                    'text'  => $kuy->i_sj
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsj(){
        header("Content-Type: application/json", true);
        $isj   = $this->input->post('isj', FALSE);
        $iarea = $this->input->post('iarea', FALSE);
        //$icus  = $this->input->post('icus', FALSE);
        $data = $this->mmaster->bacasjx($iarea,$isj);
        echo json_encode($data->result_array());  
    }

    public function dataex(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->bacaex($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_ekspedisi,  
                    'text'  => $kuy->i_ekspedisi." - ".$kuy->e_ekspedisi
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailex(){
        header("Content-Type: application/json", true);
        $iekspedisi = $this->input->post('iekspedisi', FALSE);
        $data = $this->mmaster->bacaexx($iekspedisi);
        echo json_encode($data->result_array());  
    }

    public function deletedetail(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->input->post('ibapb');
        $iarea  = $this->input->post('iarea');
        $isj    = $this->input->post('isj');
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetail($id,$iarea,$isj);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete item BAPB '.$iarea.' No:'.$id);
            echo json_encode($data);
        }
    }

    public function deletedetailx(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->input->post('ibapb');
        $iarea      = $this->input->post('iarea');
        $iekspedisi    = $this->input->post('iekspedisi');
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetailx($id,$iarea,$iekspedisi);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete item ekspedisi BAPB Area : '.$iarea.' No :'.$id);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dbapb  = $this->input->post('dbapb', TRUE);
        if($dbapb!=''){
            $tmp=explode("-",$dbapb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbapb=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }else{
            $dbapb = date('Y-m-d');
        }
        $ibapbold       = $this->input->post('ibapbold', TRUE);
        $ibapb          = $this->input->post('ibapb', TRUE);
        $ibapb          = str_replace('%20','',$ibapb);
        $iarea          = $this->input->post('iarea', true);
        $edkbkirim      = $this->input->post('edkbkirim', true);
        $idkbkirim      = $this->input->post('idkbkirim', true);
        $nbal           = $this->input->post('nbal', TRUE);
        $nbal           = str_replace(',','',$nbal);
        $jml            = $this->input->post('jml', TRUE);
        $jmlx           = $this->input->post('jmlx', TRUE);
        $vbapb          = $this->input->post('vbapb', TRUE);
        $vbapb          = str_replace(',','',$vbapb);
        $vkirim         = $this->input->post('vkirim', TRUE);
        $vkirim         = str_replace(',','',$vkirim);
        $i_segel        = $this->input->post('i_segel', true);
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $area = $this->mmaster->bacaarea($username,$idcompany);
        // var_dump($dbapb);
        // var_dump($iarea);
        // var_dump($idkbkirim);
        // var_dump($jml);
        // var_dump($jmlx);
        // var_dump($vbapb);
        if($dbapb != '' && $iarea != '' && $idkbkirim != '' && $nbal != '' && $jml != '0' && $jmlx != '0' && $vbapb != '0'){
            $this->db->trans_begin();
            if ($area == '00') {
                $daer = 'f';
            } else {
                $daer = 't';
            }
            $this->mmaster->updatebapb($ibapb, $dbapb, $iarea, $idkbkirim, $nbal, $ibapbold, $vbapb, $vkirim, $i_segel);
            for ($i = 1; $i <= $jml; $i++){
                $isj = $this->input->post('isj' . $i, true);
                $dsj = $this->input->post('dsj' . $i, true);
                if ($dsj != '') {
                    $tmp = explode("-", $dsj);
                    $th = $tmp[2];
                    $bl = $tmp[1];
                    $hr = $tmp[0];
                    $dsj = $th . "-" . $bl . "-" . $hr;
                }
                $this->mmaster->deletedetail($ibapb, $iarea, $isj, $daer);
                $vsj = $this->input->post('vsj' . $i, true);
                $vsj = str_replace(',', '', $vsj);
                $eremark = $this->input->post('eremark' . $i, true);
                if ($eremark == '') {
                    $eremark = null;
                }
                $this->mmaster->insertdetail($ibapb, $iarea, $isj, $dbapb, $dsj, $eremark, $vsj);
                $this->mmaster->updatesj($ibapb, $isj, $iarea, $dbapb);
            }
            for ($i = 1; $i <= $jmlx; $i++) {
                $iekspedisi = $this->input->post('iekspedisi' . $i, true);
                $eremark = $this->input->post('eremarkx' . $i, true);
                $this->mmaster->deletedetailekspedisi($ibapb, $iarea, $iekspedisi);
                $this->mmaster->insertdetailekspedisi($ibapb, $iarea, $iekspedisi, $dbapb, $eremark);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                // $data = array(
                //     'sukses' => false
                // );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update BAPB No:'.$ibapb.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibapb
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
