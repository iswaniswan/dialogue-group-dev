<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020301';

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
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $count      = $this->mmaster->total($username, $id_company);
        $total      = $count->num_rows();
        echo $this->mmaster->data($this->global['folder'], $username, $id_company, $total);
    }

    public function detail(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $ispb    = $this->uri->segment(4);
        $iarea   = $this->uri->segment(5);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'isi'       => $this->mmaster->baca($ispb,$iarea),
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

        $jml = $this->input->post('jml', TRUE);
        $this->db->trans_begin();
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $ispb = $this->input->post('ispb'.$i, TRUE);
                $iarea= $this->input->post('iarea'.$i, TRUE);
                $this->mmaster->updatespb($ispb, $iarea);
            }
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Gudang Terima SPB No:'.$ispb.' Area:'.$iarea);
            $data = array(
                'sukses'    => true,
                'kode'      => ""
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
