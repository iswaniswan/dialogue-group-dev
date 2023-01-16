<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1030103';

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
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function data(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder'],$siareana);
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
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        $ispb    = $this->uri->segment(4);
        $iarea   = $this->uri->segment(5);
        $dfrom   = $this->uri->segment(6);
        $dto     = $this->uri->segment(7);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'isi'       => $this->mmaster->baca($ispb,$iarea,$siareana),
            'detail'    => $this->mmaster->bacadetail($ispb,$iarea)
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        $ispb       = $this->input->post('ispb', TRUE);
        $dspb       = $this->input->post('dspb', TRUE);
        $iarea      = $this->input->post('iarea',TRUE);
        $ecek1      = $this->input->post('ecek1',TRUE);
        if($ecek1 ==''){
            $ecek1=null;
        }
        $user                   = $this->session->userdata('username');
        if(($iarea!='') && ($ispb!='')){
            $bener = "false";
            $this->db->trans_begin();
            $this->mmaster->updateheader($ispb, $iarea, $ecek1, $user, $siareana);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Cek SPB Area '.$iarea.' No:'.$ispb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
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
