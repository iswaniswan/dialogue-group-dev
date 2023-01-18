<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050108';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }

    public function index(){
        $periode  = date("Y").'-'.date("m").'-'.'01';

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'data'      => $this->mmaster->bacado($periode)->result(),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
 
    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $jml       = $this->input->post('jml', TRUE);
            
        $this->db->trans_begin();
       

        for($i=1;$i<=$jml;$i++){
            $cek        = $this->input->post('cek'.$i, TRUE);
            if($cek=='cek'){  
                $ido        = $this->input->post('ido'.$i, TRUE);
                $ddo        = $this->input->post('ddo'.$i, TRUE);
                $iop        = $this->input->post('iop'.$i, TRUE);
                $dop        = $this->input->post('dop'.$i, TRUE);
                $ibranch    = $this->input->post('ibranch'.$i, TRUE);
                // $iproduct   = $this->input->post('iproduct'.$i, TRUE);
                // $eproduct   = $this->input->post('eproduct'.$i, TRUE);
                // $ndeliver   = $this->input->post('ndeliver'.$i, TRUE);
                // $vdogross   = $this->input->post('vdogross'.$i, TRUE);
                $icustomer  = $this->input->post('icustomer'.$i, TRUE);
                //$eremark    = $this->input->post('eremark'.$i, TRUE);
                
                $nitemno    = $i;

                // $this->mmaster->insert($ido, $ddo, $iop, $dop, $ibranch, $iproduct, $eproduct, $ndeliver, $vdogross, $icustomer, $eremark, $cek, $jml);
                $this->mmaster->insert($ido, $ddo, $iop, $dop, $ibranch, $icustomer);
            }  
        }
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ido);

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,   
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $ido,
            );
        }
        $this->load->view('pesan', $data);
    }  
}
/* End of file Cform.php */
