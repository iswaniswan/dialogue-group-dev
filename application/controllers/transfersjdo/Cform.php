<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050108';

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

    public function transfer(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $xnodo = '';
        $this->db->trans_begin();
        if ($this->input->post('jml', TRUE)>0) {
            for ($i=1; $i <= $this->input->post('jml', TRUE); $i++) { 
                $check        = $this->input->post('chk'.$i, TRUE);
                $iddo         = $this->input->post('iddo'.$i, TRUE);
                $idocument    = $this->input->post('idocument'.$i, TRUE);
                $idspb        = $this->input->post('idspb'.$i, TRUE);
                $idtypespb    = $this->input->post('idtypespb'.$i, TRUE);
                $idcustomer   = $this->input->post('idcustomer'.$i, TRUE);
                if ($check=='on') {
                    $this->mmaster->transfer($iddo, $idspb, $idtypespb, $idcustomer);
                    $xnodo .= $idocument." - ";
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
            $this->Logger->write('Transfer DO '.$this->global['title'].' Id : '.$iddo.' No DO : '.substr($xnodo,0,-3));

            $data = array(
                'sukses'    => true,
                'kode'      => substr($xnodo,0,-3),
                'id'        => $iddo
            );
        }
        $this->load->view('pesan2', $data);
    }
}
/* End of file Cform.php */
