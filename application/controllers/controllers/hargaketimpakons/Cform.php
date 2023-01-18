<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010704';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }
    
   public function delete(){

    $i_customer = $this->uri->segment('4');
    $i_notapb = $this->uri->segment('5');
    
    $this->db->query("update tm_notapb set i_cek = null, d_cek = null where i_notapb = '$i_notapb' and i_customer = '$i_customer'");
    $this->Logger->write('Batal Cek Bon '.$i_notapb.' Customer : '.$i_customer);

    $this->index();

   }


}

/* End of file Cform.php */
