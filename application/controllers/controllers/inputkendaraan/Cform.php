<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010901';

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
            'title'     => $this->global['title'],
            'area' => $this->mmaster->bacaarea(),
            'jeniskendaraan' => $this->mmaster->bacajeniskendaraan(),
            'jenisbbm' => $this->mmaster->bacajenisbbm(),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikendaraan 			= $this->input->post('ikendaraan', TRUE);
        $iperiode = $this->input->post('iperiodea', TRUE).$this->input->post('iperiodeb', TRUE);
        $iarea = $this->input->post('iarea', TRUE);
        $ikendaraanjenis = $this->input->post('ikendaraanjenis', TRUE);
        $ikendaraanbbm = $this->input->post('ikendaraanbbm', TRUE);
        $epengguna = $this->input->post('epengguna', TRUE);
        $dpajak = $this->input->post('dpajak', TRUE);


        if ($ikendaraan != ''){
                $cekada = $this->mmaster->cek_data($ikendaraan);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    
                    $this->db->trans_begin();
                    $this->mmaster->insert($ikendaraan,$iperiode,$iarea,$ikendaraanjenis,$ikendaraanbbm,$epengguna,$dpajak);
                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();

                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikendaraan);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $ikendaraan
                        );
                    }  
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }
}

/* End of file Cform.php */
