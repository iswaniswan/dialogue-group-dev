<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010703';

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

    public function data(){      
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);  
    }

    public function databrg(){    
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tr_product");
            $this->db->like("UPPER(i_product)", $cari);
            $this->db->or_like("UPPER(e_product_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                $filter[] = array(
                    'id'    => $product->i_product,  
                    'text'  => $product->i_product.'-'.$product->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
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
            'data_groupco' => $this->mmaster->data_groupco()
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getharga(){
        header("Content-Type: application/json", true);
        $igroup = $this->input->post('igroup');      
        $data = $this->mmaster->getharga($igroup);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct           = $this->input->post('iproduct', TRUE);
        $eproductname       = $this->input->post('eproductname', TRUE);
        $iproductgrade      = $this->input->post('iproductgrade', TRUE);
        $ipricegroup        = $this->input->post('ipricegroup', TRUE);
        $vproductretail     = $this->input->post('vproductretail', TRUE);
        $vproductretail     = str_replace(",","",$this->input->post('vproductretail', TRUE));
        if($vproductretail==''){
            $vproductretail=0;
        }
        $vproductmill       = $this->input->post('vproductmill', TRUE);
        $vproductmill       = str_replace(",","",$this->input->post('vproductmill', TRUE));
        if($vproductmill==''){
            $vproductmill=0;
        }
        $ipricegroupco      = $this->input->post('ipricegroupco', TRUE);
        if ((isset($iproduct) && $iproduct != '') && (isset($iproductgrade) && $iproductgrade != '') && (isset($vproductretail) && $vproductretail != '' && $vproductretail != '0') && (isset($vproductmill) && $vproductmill != '') && (isset($ipricegroup) && $ipricegroup != '') && (isset($ipricegroupco) && $ipricegroupco != '')){
            $nmargin=$this->input->post('nmargin', TRUE);
            if($this->mmaster->cekco($iproduct,$ipricegroup,$iproductgrade)){              
                $this->mmaster->insert($iproduct,$ipricegroup,$iproductgrade,$vproductretail,$nmargin,$ipricegroupco); 
                if($this->mmaster->cekharga($iproduct,$ipricegroup,$iproductgrade)){              
                    $this->mmaster->insertnet($iproduct,$ipricegroup,$iproductgrade,$eproductname,$vproductmill,$nmargin); 

                }   
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
                );      
            }else{              
                $data = array(
                    'sukses' => false,
                );
            }          
            if (($this->db->trans_status() === FALSE)){          
                $this->db->trans_rollback();   
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Harga Konsinyasi Dengan Kode : '.$iproduct);
                
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

        $iproduct    = $this->uri->segment(4);
        $ipricegroup = $this->uri->segment(5);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iproduct'      => $iproduct,
            'ipricegroup'   => $ipricegroup,
            'isi'           => $this->mmaster->baca($iproduct,$ipricegroup),
            'data_groupco'  => $this->mmaster->data_groupco()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct           = $this->input->post('iproduct');
        $eproductname       = $this->input->post('eproductname');
        $iproductgrade      = $this->input->post('iproductgrade');
        $ipricegroup        = $this->input->post('ipricegroup');
        $vproductmill       = $this->input->post('vproductmill');
        $vproductmill       = str_replace(",","",$this->input->post('vproductmill', TRUE));
        if($vproductmill==''){
            $vproductmill=0;
        }
        $vproductretail     = $this->input->post('vproductretail');
        $vproductretail     = str_replace(",","",$this->input->post('vproductretail', TRUE));
        if($vproductretail==''){
            $vproductretail=0;
        }
        $hitung             = ($vproductretail-$vproductmill)/$vproductretail;
        $nmargin            = $hitung*100; 
        if ((isset($iproduct) && $iproduct != '') && (isset($iproductgrade) && $iproductgrade != '') && (isset($vproductretail) && $vproductretail != '' && $vproductretail != '0') && (isset($vproductmill) && $vproductmill != '') && (isset($ipricegroup) && $ipricegroup != '')){
            $this->mmaster->update($iproduct,$eproductname,$iproductgrade,$ipricegroup,$vproductretail,$nmargin); 
            if (($this->db->trans_status() === FALSE)){          
                $this->db->trans_rollback();   
            }else{
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
                ); 
                $this->db->trans_commit();
                $this->Logger->write('Update Harga Konsinyasi Dengan Kode : '.$iproduct);
                
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
