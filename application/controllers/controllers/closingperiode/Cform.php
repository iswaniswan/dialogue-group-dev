<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1060111';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function closing(){
        $bulan     = $this->input->post('bulan', TRUE);
        $tahun     = $this->input->post('tahun', TRUE);
        $iperiode  = $tahun.$bulan;
        list($amanopname, $store) = $this->mmaster->cekopname($iperiode);
        if($amanopname){
            list($amanap, $do) = $this->mmaster->cekap($iperiode);
            if($amanap){
                list($amanar, $sj) = $this->mmaster->cekar($iperiode);
                if($amanar){
                    $this->db->trans_begin();
                    $this->mmaster->pindah($iperiode);
                    if(($this->db->trans_status()=== False)){
                        $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false
                        );
                    }else{
                        $this->db->trans_commit();
                        $this->Logger->write('Closing Transaksi Periode : '.$iperiode);
                        $data = array(
                            'sukses'    => true,
                            'kode'      => $iperiode
                        );
                    }
                }else{
                    $data = array(
                        'sukses' => false
                    );
                }
            }else{
                $data = array(
                    'sukses' => false
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
