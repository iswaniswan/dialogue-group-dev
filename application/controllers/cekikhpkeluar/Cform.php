<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1052005';

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

    public function cek(){
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $iikhpkeluar = $this->uri->segment(4);
        $iarea       = $this->uri->segment(5);
        $dfrom       = $this->uri->segment(6);
        $dto         = $this->uri->segment(7);;
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'isi'       => $this->mmaster->baca($iikhpkeluar),
            'area'      => $this->mmaster->area($username, $idcompany)->result(),
            'urai'      => $this->mmaster->uraian()->result()
        );
        $this->Logger->write('Membuka '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iikhp          = $this->input->post('iikhp', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $ibukti         = $this->input->post('ibukti', TRUE);
        $dbukti         = $this->input->post('dbukti', TRUE);
        $dtmp           = $dbukti;
        $dbukti         = date('Y-m-d', strtotime($dbukti));
        if($ibukti=='') {
            $ibukti=null;
        }
        $eikhptypename  = $this->input->post('eikhptypename', TRUE);
        $icoa           = $this->input->post('icoa', TRUE);
        $ecoaname       = $this->input->post('ecoaname', TRUE);
        $iikhptype      = $this->input->post('iikhptype', TRUE);
        $vterimatunai   = $this->input->post('vterimatunai', TRUE);
        if($vterimatunai=='') {
            $vterimatunai=0;
        }
        $vterimatunai   = str_replace(',','',$vterimatunai);
        $vterimagiro    = $this->input->post('vterimagiro', TRUE);
        if($vterimagiro=='') {
            $vterimagiro=0;
        }
        $vterimagiro    = str_replace(',','',$vterimagiro);
        $vkeluartunai   = $this->input->post('vkeluartunai', TRUE);
        if($vkeluartunai=='') {
            $vkeluartunai=0;
        }
        $vkeluartunai   = str_replace(',','',$vkeluartunai);
        $vkeluargiro    = $this->input->post('vkeluargiro', TRUE);
        if($vkeluargiro=='') {
            $vkeluargiro=0;
        }
        $vkeluargiro    = str_replace(',','',$vkeluargiro);
        $ecek1          = $this->input->post('ecek1',TRUE);
        if($ecek1==''){
            $ecek1=null;
        }
        $user = $this->session->userdata('username');
        if (($dbukti != '') && ($eikhptypename != '') && ($iarea != '')&& ( ($vterimatunai!='') || ($vterimagiro!='') || ($vkeluartunai!='') || ($vkeluargiro!='') )){
            $this->db->trans_begin();
            $this->mmaster->update($iikhp,$iarea,$dbukti,$ibukti,$icoa,$iikhptype,$vterimatunai,$vterimagiro,$vkeluartunai,$vkeluargiro,$ecek1,$user);
            $nomor=$dtmp." - ".$eikhptypename;
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Cek IKHP Keluar No:'.$iikhp.' Area:'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $nomor
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
