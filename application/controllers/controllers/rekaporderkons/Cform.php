<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020910';

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
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function getcustomer(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $iarea      = $this->mmaster->cekuser($username, $idcompany);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getcustomer($iarea, $cari, $username, $idcompany);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_customer,  
                    'text'  => $row->i_customer.' - '.$row->e_customer_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function view(){
        $dfrom     = $this->input->post('dfrom', TRUE);
        $dto       = $this->input->post('dto', TRUE);
        $icustomer = $this->input->post('icustomer', TRUE);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'icustomer' => $icustomer
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $icustomer  = $this->uri->segment(6);
        $count      = $this->mmaster->total($username, $idcompany, $icustomer, $dfrom, $dto);
        $total      = $count->num_rows();
        echo $this->mmaster->data($this->global['folder'], $total, $username, $idcompany, $icustomer, $dfrom, $dto);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $jml    = $this->input->post('jml', TRUE);
        $dspmb  = date('Y-m-d');
        $thbl   = date('ym');
        $ispmb  = '';
        $ispmbx = '';
        $this->db->trans_begin();
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $iorderpb     = $this->input->post('iorderpb'.$i, TRUE);
                $iarea        = $this->input->post('iarea'.$i, TRUE);
                $icustomer    = $this->input->post('icustomer'.$i, TRUE);
                if($ispmb==''){
                    $ispmb = $this->mmaster->runningnumberspmb($thbl);
                }
                if($ispmbx==''){
                    $this->mmaster->insertheader($ispmb, $dspmb, $iarea, null);
                }
                $this->mmaster->updateorderpb($iorderpb,$icustomer,$iarea,$ispmb);
                $this->mmaster->insertdetail($iorderpb,$icustomer,$iarea,$ispmb);    
                $ispmbx = $ispmb;            
            }
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Input SPMB Konsinyasi (rekap-order) Area :'.$iarea.' No:'.$ispmb);
            $data = array(
                'sukses'    => true,
                'kode'      => ""
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
