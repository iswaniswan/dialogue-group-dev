<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011402';

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

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iproductmotif = $this->input->post('i_product_motif', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iproductmotif);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Permintaan Pembelian ' . $iproductmotif);
            echo json_encode($data);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproductmotif = $this->uri->segment('4');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($iproductmotif)->row(),
            'barang_jadi'       => $this->mmaster->get_barangjadi()->result(),
            'kelompokbrgjadi'   => $this->mmaster->get_kelompokbrgjadi()->result(),          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproductmotif      = $this->input->post('iproductmotif', TRUE);
        $eproductmotifname  = $this->input->post('eproductmotifname', TRUE); 
        $ikelbrgjadi        = $this->input->post('ikelbrgjadi', TRUE); 

        if ($iproductmotif != ''){
            $cekada = $this->mmaster->cek_data($iproductmotif);
            if($cekada->num_rows() > 0){ 
               
                $this->mmaster->update($iproductmotif, $eproductmotifname, $ikelbrgjadi);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproductmotif
                );
            }else{
                $data = array(
                    'sukses' => false
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $iproductmotif= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductmotif)->row(),
            'kelompokbrgjadi'   => $this->mmaster->get_kelompokbrgjadi()->result(),  
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */