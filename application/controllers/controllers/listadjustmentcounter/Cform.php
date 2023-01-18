<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070319';

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
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function datacustomer(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        if ($cari!='') {
            $data   = $this->mmaster->getcustomer($cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_customer,  
                    'text'  => $row->e_customer_name
                );
            }
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function data(){
        $icustomer  = $this->input->post('icustomer');
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($icustomer==''){
            $icustomer=$this->uri->segment(6);
        }
        echo $this->mmaster->data($dfrom,$dto,$icustomer,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $icustomer  = $this->input->post('icustomer');
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($icustomer==''){
            $icustomer=$this->uri->segment(4);
        } 
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'icustomer'     => $icustomer,
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
        $icustomer  = $this->input->post('icustomer');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($id, $icustomer);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus Adjustment Counter Toko : '.$icustomer.' No : '.$id);
            echo json_encode($data);
        }
    }

    public function edit(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $id         = $this->uri->segment(4);
            $icustomer  = $this->uri->segment(5);
            $dfrom      = $this->uri->segment(6);
            $dto        = $this->uri->segment(7);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'icustomer'     => $icustomer,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'icustomer'     => $icustomer,
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($id,$icustomer),
                'detail'        => $this->mmaster->bacadetail($id,$icustomer),
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformupdate', $data);
    }

    public function getcustomer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getcustomer($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getso(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('icustomer') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $icustomer  = strtoupper($this->input->get('icustomer'));
            $data       = $this->mmaster->getso($cari, $icustomer);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_sopb,  
                    'text'  => $kuy->i_sopb." - ".$kuy->d_sopb
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('icustomer') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $icustomer = strtoupper($this->input->get('icustomer'));
            $data   = $this->mmaster->getproduct($cari, $icustomer);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->kode,  
                    'text'  => $kuy->kode." - ".$kuy->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);
        $iproduct    = $this->input->post('iproduct');
        $icustomer   = $this->input->post('icustomer');
        $data  = $this->mmaster->getdetailproduct($iproduct, $icustomer);      
        echo json_encode($data->result_array());  
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iadj           = $this->input->post('iadj', TRUE);
        $dadj           = $this->input->post('dadj', TRUE);
        $thbl           = date('Ym', strtotime($dadj));
        $dadj           = date('Y-m-d', strtotime($dadj)); // Hasil Tahun Bulan Hari
        $icustomer      = $this->input->post('icustomer', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $istockopname   = $this->input->post('istockopname', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if($dadj!='' && $istockopname!='' && $icustomer!=''){
            $this->db->trans_begin();
            $this->mmaster->updateheader($iadj, $icustomer, $dadj, $istockopname, $eremark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade    = $this->input->post('grade'.$i, TRUE);
                $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                if($nquantity!=0){
                    $this->mmaster->updatedetail($iadj,$icustomer,$iproduct,$iproductmotif,$iproductgrade,$eproductname,$nquantity,$eremark,$i);
                }else{
                    $this->mmaster->deletedetail($iadj,$icustomer,$iproduct,$iproductmotif,$iproductgrade);   
                }
            }
            if(($this->db->trans_status() === False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Adjustment Counter No : '.$iadj.' Customer : '.$icustomer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iadj
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
