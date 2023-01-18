<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010102';

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
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'supplier_group'=> $this->mmaster->get_supplier_group()->result()
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }


        $isupplier              = $this->input->post('isupplier', TRUE);
        $isuppliergroup         = $this->input->post('isuppliergroup', TRUE);
        $isuppliername          = $this->input->post('isuppliername', TRUE);
        $isupplieraddres        = $this->input->post('isupplieraddres', TRUE);
        $isuppliercity          = $this->input->post('isuppliercity', TRUE);
        $isupplierpostalcode    = $this->input->post('isupplierpostalcode', TRUE);
        $isupplierphone         = $this->input->post('isupplierphone', TRUE);
        $isupplierfax           = $this->input->post('isupplierfax', TRUE);
        $isupplierownername     = $this->input->post('isupplierownername', TRUE);
        $isupplierowneraddress  = $this->input->post('isupplierowneraddress', TRUE);
        $isuppliernpwp          = $this->input->post('isuppliernpwp', TRUE);
        $isupplierphone2        = $this->input->post('isupplierphone2', TRUE);
        $isuppliercontact       = $this->input->post('isuppliercontact', TRUE);
        $isupplieremail         = $this->input->post('isupplieremail', TRUE);
        $isupplierdiscount      = $this->input->post('isupplierdiscount', TRUE);
        $isupplierdiscount2     = $this->input->post('isupplierdiscount2', TRUE);
        $isuppliertoplength     = $this->input->post('isuppliertoplength', TRUE);
        $isupplierpkp           = $this->input->post('isupplierpkp', TRUE);
        if (isset($isupplierpkp)){ $pkp='t';} else { $pkp='f';}
        $isupplierppn           = $this->input->post('isupplierppn', TRUE);
        if (isset($isupplierppn)){ $ppn='t';} else { $pkp='f';}    


        if ($isupplier != '' && $isuppliername != ''){
                $cekada = $this->mmaster->cek_data($isupplier);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isupplier);
                    $this->mmaster->insert($isupplier, $isuppliergroup, $isuppliername, $isupplieraddres, $isuppliercity, $isupplierpostalcode, $isupplierphone, 
                        $isupplierfax, $isupplierownername, $isupplierowneraddress, $isuppliernpwp, $isupplierphone2, $isuppliercontact, $isupplieremail, $isupplierdiscount, 
                        $isupplierdiscount2, $isuppliertoplength, $pkp, $ppn);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $isupplier
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($isupplier)->row(),
            'supplier_group'=> $this->mmaster->get_supplier_group()->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isupplier              = $this->input->post('isupplier', TRUE);
        $isuppliergroup         = $this->input->post('isuppliergroup', TRUE);
        $isuppliername          = $this->input->post('isuppliername', TRUE);
        $isupplieraddres        = $this->input->post('isupplieraddres', TRUE);
        $isuppliercity          = $this->input->post('isuppliercity', TRUE);
        $isupplierpostalcode    = $this->input->post('isupplierpostalcode', TRUE);
        $isupplierphone         = $this->input->post('isupplierphone', TRUE);
        $isupplierfax           = $this->input->post('isupplierfax', TRUE);
        $isupplierownername     = $this->input->post('isupplierownername', TRUE);
        $isupplierowneraddress  = $this->input->post('isupplierowneraddress', TRUE);
        $isuppliernpwp          = $this->input->post('isuppliernpwp', TRUE);
        $isupplierphone2        = $this->input->post('isupplierphone2', TRUE);
        $isuppliercontact       = $this->input->post('isuppliercontact', TRUE);
        $isupplieremail         = $this->input->post('isupplieremail', TRUE);
        $isupplierdiscount      = $this->input->post('isupplierdiscount', TRUE);
        $isupplierdiscount2     = $this->input->post('isupplierdiscount2', TRUE);
        $isuppliertoplength     = $this->input->post('isuppliertoplength', TRUE);
        $isupplierpkp           = $this->input->post('isupplierpkp', TRUE);
        if (isset($isupplierpkp)){ $pkp='t';} else { $pkp='f';}
        $isupplierppn           = $this->input->post('isupplierppn', TRUE);
        if (isset($isupplierppn)){ $ppn='t';} else { $pkp='f';}   


        if ($isupplier != '' && $isuppliername != ''){
            $cekada = $this->mmaster->cek_data($isupplier);
            if($cekada->num_rows() > 0){ 
                //$this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isuppliergroup);
                $this->mmaster->update($isupplier, $isuppliergroup, $isuppliername, $isupplieraddres, $isuppliercity, $isupplierpostalcode, $isupplierphone, 
                        $isupplierfax, $isupplierownername, $isupplierowneraddress, $isuppliernpwp, $isupplierphone2, $isuppliercontact, $isupplieremail, $isupplierdiscount, 
                        $isupplierdiscount2, $isuppliertoplength, $pkp, $ppn);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isupplier
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

        $isupplier= $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($isupplier)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
