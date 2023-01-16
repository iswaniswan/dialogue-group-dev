<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070326';

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
        $data   = $this->mmaster->area($cari);
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
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $area   = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
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
            $this->Logger->write('Menghapus DKB Area : '.$iarea.' No:'.$id);
            echo json_encode($data);
        }
    }

    public function deleteitem(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id     = $this->input->post('idkb');
        $iarea  = $this->input->post('iarea');
        $isj    = $this->input->post('isj');
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetail($id,$iarea,$isj);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus Detail DKB Area : '.$iarea.' No : '.$id.' untuk SJ : '.$isj);
            echo json_encode($data);
        }
    }

    public function deleteitemx(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id     = $this->input->post('idkb');
        $iarea  = $this->input->post('iarea');
        $iekspedisi    = $this->input->post('iekspedisi');
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetailekspedisi($id,$iarea,$iekspedisi);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus Ekspedisi DKB Area : '.$iarea.' No : '.$id.' Ekspedisi : '.$iekspedisi);
            echo json_encode($data);
        }
    }

    public function edit(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $idkb   = $this->uri->segment(4);
            $iarea  = $this->uri->segment(5);
            $dfrom  = $this->uri->segment(6);
            $dto    = $this->uri->segment(7);
            $qrunningjml = $this->mmaster->bacadetailrunningjml($idkb,$iarea);
            if ($qrunningjml->num_rows()>0) {
                $row_jmlx   = $qrunningjml->row();
                $jumlah     = $row_jmlx->v_total;
            }else{
                $jumlah     = 0;
            }
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Detail ".$this->global['title'],
                'idkb'          => $idkb,
                'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($idkb,$iarea),
                'detail'        => $this->mmaster->bacadetail($idkb,$iarea),
                'detailx'       => $this->mmaster->bacadetailx($idkb,$iarea),
                'area'          => $this->mmaster->bacaarea(),
                'via'           => $this->mmaster->bacavia(),
                'kirim'         => $this->mmaster->bacadkb($username,$idcompany),
                'jumlah'        => $jumlah,
                'iperiode'      => $this->mmaster->cekperiode()->row(),
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function datasj(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = strtoupper($this->input->get('iarea', FALSE));
            $ddkb    = $this->input->get('ddkb', FALSE);
            if($ddkb){              
                $tmp=explode("-",$ddkb);              
                $dd=$tmp[0];
                $mm=$tmp[1];
                $yy=$tmp[2];
                $ddkbx=$yy.'-'.$mm.'-'.$dd;
            }
            $data    = $this->mmaster->bacasj($cari,$iarea,$ddkbx);
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
        $isj   = strtoupper($this->input->post('isj', FALSE));
        $iarea = strtoupper($this->input->post('iarea', FALSE));
        $ddkb  = $this->input->post('ddkb', FALSE);
        if($ddkb){              
            $tmp=explode("-",$ddkb);              
            $dd=$tmp[0];
            $mm=$tmp[1];
            $yy=$tmp[2];
            $ddkbx=$yy.'-'.$mm.'-'.$dd;
        }
        $data = $this->mmaster->bacasjx($iarea,$ddkbx,$isj);
        echo json_encode($data->result_array());  
    }

    public function dataex(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea')!='') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $iarea   = strtoupper($this->input->get('iarea', FALSE));
            $data    = $this->mmaster->bacaex($cari,$iarea);
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
        $iekspedisi = strtoupper($this->input->post('iekspedisi', FALSE));
        $iarea      = strtoupper($this->input->post('iarea', FALSE));
        $data = $this->mmaster->bacaexx($iarea,$iekspedisi);
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ddkb       = $this->input->post('ddkb', TRUE);
        if($ddkb!=''){
            $tmp=explode("-",$ddkb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $ddkb=$th."-".$bl."-".$hr;
        }
        $iarea      = $this->session->userdata('i_area');
        $idkbold    = $this->input->post('idkbold', TRUE);
        $idkb       = $this->input->post('idkb', TRUE);
        $eareaname  = $this->input->post('eareaname', TRUE);
        $iareasj    = $this->input->post('iarea', TRUE);
        $edkbkirim  = $this->input->post('edkbkirim', TRUE);
        $idkbkirim  = $this->input->post('idkbkirim', TRUE);
        $edkbvia    = $this->input->post('edkbvia', TRUE);
        $idkbvia    = $this->input->post('idkbvia', TRUE);
        $eekspedisi = $this->input->post('eekspedisi', TRUE);
        $esupirname = $this->input->post('esupirname', TRUE);
        $ikendaraan = $this->input->post('ikendaraan', TRUE);       
        $vdkb       = $this->input->post('vdkb', TRUE);       
        $vdkb       = str_replace(',','',$vdkb);
        $jml        = $this->input->post('jml', TRUE);
        $jmlx       = $this->input->post('jmlx', TRUE);
        if($idkb!='' && $ddkb!='' && $iareasj!='' && $idkbkirim!='' && $idkbvia!=''){
            $this->db->trans_begin();
            $this->mmaster->deleteheader($idkb, $iareasj);
            $this->mmaster->insertheader($idkb, $ddkb, $iareasj, $idkbkirim, $idkbvia, $ikendaraan, $esupirname, $vdkb, $idkbold);
            for($i=1;$i<=$jml;$i++){              
                $isj          = $this->input->post('isj'.$i, TRUE);
                $dsj          = $this->input->post('dsj'.$i, TRUE);
                $vjumlah      = $this->input->post('vsjnetto'.$i, TRUE);
                $vjumlah      = str_replace(',','',$vjumlah);
                $eremark      = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($idkb,$iareasj,$isj);
                $this->mmaster->insertdetail($idkb,$iareasj,$isj,$ddkb,$dsj,$vjumlah,$eremark,$i);
                $this->mmaster->updatesj($idkb,$isj,$iareasj,$ddkb);
            }
            if($jmlx>0 && $idkbvia!=2){
                for($i=1;$i<=$jmlx;$i++){
                    $iekspedisi = $this->input->post('iekspedisi'.$i, TRUE);
                    $eremark    = $this->input->post('eremarkx'.$i, TRUE);
                    $this->mmaster->deletedetailekspedisi($idkb,$iareasj,$iekspedisi);
                    $this->mmaster->insertdetailekspedisi($idkb,$iareasj,$iekspedisi,$ddkb,$eremark,$i);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update DKB Area : '.$iarea.' No : '.$idkb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idkb
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
