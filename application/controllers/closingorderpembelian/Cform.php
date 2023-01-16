<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20204';

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

    public function index()
    {
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
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
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
        echo $this->mmaster->data($this->i_menu,$dfrom,$dto);
    }

    public function closing()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $xnoop = '';
        $this->db->trans_begin();
        if ($this->input->post('jml', true)>0) {
            for ($i=1; $i <= $this->input->post('jml', true); $i++) { 
                $check = $this->input->post('chk'.$i, true);
                $id    = $this->input->post('id'.$i, true);
                $iop   = $this->input->post('iop'.$i, true);
                if ($check=='on') {
                    $this->mmaster->closing($id);
                    $xnoop .= $iop." - ";
                }
            }
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Closing OP '.$this->global['title'].' Id : '.$id.' No OP : '.substr($xnoop,0,-3));

            $data = array(
                'sukses'    => true,
                'kode'      => substr($xnoop,0,-3),
            );
        }
        $this->load->view('pesan', $data);
    }

    public function unclosing()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id  = $this->input->post('id', TRUE);
        $iop = $this->input->post('iop', TRUE);
        $this->db->trans_begin();
        $data = $this->mmaster->unclosing($id);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Unclosing Order Pembelian Id : '.$id.' No OP : '.$iop);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */
