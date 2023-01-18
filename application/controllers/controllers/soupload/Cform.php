<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021002';

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
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'folderx'   => "transferso",
            'title'     => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function upload(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $istore = $this->session->userdata('i_store');
        if($istore=='AA'){
            $istore='00';
        }

        $config = array(
            'upload_path'   => "./import/so/".$istore,
            'allowed_types' => "xls",
            /*'file_name'     => "So-".$istore."_".date('Ym'),*/
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File So Periode : '.date('Ym').', Store : '.$istore);
            echo 'berhasil';
        }else{
            echo 'gagal';
        }
    }
}
/* End of file Cform.php */
