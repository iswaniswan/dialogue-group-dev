<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020906';

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
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        echo $this->mmaster->data($this->global['folder']);
    }

    public function edit(){
        $ispmb      = $this->uri->segment(4);
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'isi'               => $this->mmaster->baca($ispmb),
            'detail'            => $this->mmaster->bacadetail($ispmb)
        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function siapsj(){
        $ispmb = $this->input->post('ispmb', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->spmbsiapsj($ispmb);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Siap SJ untuk No : '.$ispmb);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispmb          = $this->input->post('ispmb', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $fspmbop        = 't';
        $fspmbclose     = 'f';
        $fspmbcancel    = 'f';
        if($ispmb!=''){
            $this->db->trans_begin();
            $this->mmaster->updateheader($ispmb,$fspmbop,$fspmbclose,$fspmbcancel);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Pemenuhan SPMB Area:'.$iarea.' No:'.$ispmb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispmb
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
