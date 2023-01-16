<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040105';

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
            'title'     => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $count      = $this->mmaster->total();
        $total      = $count->num_rows();
        echo $this->mmaster->data($this->global['folder'], $total);
    }

    function cancel(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $jml = $this->input->post('jml', TRUE);
        $this->db->trans_begin();
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
				if($cek=='on'){
					$iop = $this->input->post('op'.$i, TRUE);
					$this->mmaster->updateop($iop);
				}
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
		}else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel OP '.$this->global['title'].' Kode : '.$iop);

            $data = array(
                'sukses'    => true,
                'kode'      => $iop
            );
        }
        $this->load->view('pesan', $data);
    }

}
/* End of file Cform.php */
