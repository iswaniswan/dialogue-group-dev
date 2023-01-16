<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107031803';

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
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        echo $this->mmaster->data($dfrom,$dto,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto
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
            $this->Logger->write('Hapus BAPB SJPB Area '.$iarea.' No:'.$id);
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
            $jmlitem    = $this->mmaster->jumlahitem($id,$iarea);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'jmlitem'       => $jmlitem->num_rows(),
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($id,$iarea),
                'detail'        => $this->mmaster->bacadetail($id,$iarea)
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
            $iarea   = $this->input->get('iarea', FALSE);
            //$icust   = $this->input->get('icus', FALSE);
            $data    = $this->mmaster->bacasj($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sjpb,  
                    'text'  => $kuy->i_sjpb
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
        $data = $this->mmaster->bacasjdetail($iarea,$isj);
        echo json_encode($data->result_array());  
    }

    public function deletedetail(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->input->post('ibapb');
        $iarea      = $this->input->post('iarea');
        $isjpb      = $this->input->post('isjpb');
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetail($id,$iarea,$isjpb);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete item BAPB SJPB : '.$iarea.' No:'.$id);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dbapb 	= $this->input->post('dbapb', TRUE);
        $vbapb	= $this->input->post('vbapb', TRUE);
        $vbapb	= str_replace(',','',$vbapb);
        $jml 	=  $this->input->post('jml', TRUE);
        $ibapb	=  $this->input->post('ibapb', TRUE);
        if($dbapb!=''){
            $tmp=explode("-",$dbapb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbapb=$th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
        }
        if($dbapb!='' &&  $vbapb!='0' ){
            $this->db->trans_begin();
            $iarea = 'PB';
            $this->mmaster->deleteitem($ibapb, $iarea);
				$nilaitotal = 0;
				for($i=1;$i<=$jml;$i++){
					$isj			= $this->input->post('isj'.$i, TRUE);
					$dsj			= $this->input->post('dsj'.$i, TRUE);
					
					if($dsj!=''){
						$tmp=explode("-",$dsj);
						$th=$tmp[2];
						$bl=$tmp[1];
						$hr=$tmp[0];
						$dsj=$th."-".$bl."-".$hr;
					}
					$vsj	= $this->input->post('vsj'.$i, TRUE);
					$vsj	= str_replace(',','',$vsj);
					$eremark	= $this->input->post('eremark'.$i, TRUE);
					if($eremark=='') $eremark=null;
					$this->mmaster->insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$vsj);
					$this->mmaster->updatesj($ibapb,$isj,$iarea,$dbapb);
					
					$nilaitotal = $nilaitotal + $vsj;
				}
				$this->mmaster->updatesjb($ibapb,$iarea,$nilaitotal);
				$this->mmaster->updatesjpb($ibapb,$iarea);

            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
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
