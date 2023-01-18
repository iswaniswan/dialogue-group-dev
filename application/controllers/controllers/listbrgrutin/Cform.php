<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107050903';

    public function __construct()
    {
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
        echo $this->mmaster->data($this->global['folder'],$this->i_menu);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'supplier'          => $this->mmaster->get_supplier()->result(),
            'productgroup'      => $this->mmaster->get_productgroup()->result(),
            'productclass'      => $this->mmaster->get_productclass()->result(),
            'producttype'       => $this->mmaster->get_producttype()->result(),
            'productcategory'   => $this->mmaster->get_iproductcategory()->result(),
            'productseri'       => $this->mmaster->get_iproductseri()->result(),
            'productstatus'     => $this->mmaster->get_productstatus()->result() 
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cari_barang() {
        $nbrg   = $this->input->post('nbrg', TRUE);
        $qnbrg  = $this->mmaster->cari_brg($nbrg);
        if($qnbrg->num_rows()>0) {
            echo "Maaf, Kode Brg sudah ada.";
        }
    }

    public function datatype(){
        $filter = [];
        if($this->input->get('iproductgroup') != '') {
            $filter = [];
            $data   = $this->mmaster->bacatype(strtoupper($this->input->get('q')),$this->input->get('iproductgroup'));
            foreach($data->result() as $row){
                    $filter[] = array(
                    'id'    => $row->i_product_type,  
                    'text'  => $row->e_product_typename
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function datakategori(){
        $filter = [];
        if($this->input->get('iproductclass') != '') {
            $filter = [];
            $data   = $this->mmaster->bacakategori(strtoupper($this->input->get('q')),$this->input->get('iproductclass'));
            foreach($data->result() as $row){
                    $filter[] = array(
                    'id'    => $row->i_product_category,  
                    'text'  => $row->e_product_categoryname
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct 			    = $this->input->post('iproduct', TRUE);
        $iproductsupplier 		= $this->input->post('iproductsupplier', TRUE);
        $isupplier	            = $this->input->post('isupplier', TRUE);
        $iproductgroup	        = $this->input->post('iproductgroup', TRUE);
        $iproductclass	        = $this->input->post('iproductclass', TRUE);
        $vproductretail	        = str_replace(",", "", $this->input->post('vproductretail', TRUE));
        $dproductstopproduction	= $this->input->post('dproductstopproduction', TRUE);
        $fproductpricelist	    = $this->input->post('fproductpricelist', TRUE);
        $eproductname	        = $this->input->post('eproductname', TRUE);
        $eproductsuppliername	= $this->input->post('eproductsuppliername', TRUE);
        $iproductstatus	        = $this->input->post('iproductstatus', TRUE);
        $iproducttype	        = $this->input->post('iproducttype', TRUE);
        $iproductcategory	    = $this->input->post('iproductcategory', TRUE);
        $vproductmill	        = str_replace(",", "", $this->input->post('vproductmill', TRUE));
        $dproductregister	    = $this->input->post('dproductregister', TRUE);
        $nproductmargin	        = $this->input->post('nproductmargin', TRUE);
        $iproductseri	        = $this->input->post('iproductseri', TRUE);
        if($vproductretail=='')
            $vproductretail=0;
        if($vproductmill=='')
            $vproductmill=0;
        if ($iproduct != '' &&  $eproductname != ''){
            /*$cekada = $this->mmaster->cek_data($iproduct);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{*/
                $this->mmaster->insert($iproduct,$iproductsupplier,$isupplier,$iproductstatus,$iproducttype,$iproductcategory,$iproductclass,$iproductgroup,$eproductname,$eproductsuppliername,$vproductretail,$vproductmill,$fproductpricelist,$dproductstopproduction,$dproductregister);
                $this->mmaster->insertmotif('00','ST',$iproduct,$eproductname);
                $this->mmaster->insertprice($iproduct,$eproductname,'A',$nproductmargin,$vproductmill);

                $this->Logger->write('Simpan Data Barang '.$this->global['title'].' Kode : '.$iproduct);

                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
                );
            /*}*/
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

        $iproduct = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproduct)->row(), 
        );

        $data['isupplier']          = $this->mmaster->bacasupplier();
        $data['iproductgroup']      = $this->mmaster->bacaproductgroup();
        $data['iproductclass']      = $this->mmaster->bacaproductclass();
        $data['iproductstatus']     = $this->mmaster->bacaproductstatus();
        $data['iproducttype']       = $this->mmaster->bacaproducttype();
        $data['iproductcategory']   = $this->mmaster->bacaproductcategory();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct 			    = $this->input->post('iproduct', TRUE);
        $iproductsupplier 		= $this->input->post('iproductsupplier', TRUE);
        $isupplier	            = $this->input->post('isupplier', TRUE);
        $iproductstatus	        = $this->input->post('iproductstatus', TRUE);
        $iproducttype	        = $this->input->post('iproducttype', TRUE);
        $iproductcategory	    = $this->input->post('iproductcategory', TRUE);
        $iproductclass	        = $this->input->post('iproductclass', TRUE);
        $iproductgroup	        = $this->input->post('iproductgroup', TRUE);
        $eproductname	        = $this->input->post('eproductname', TRUE);
        $eproductsuppliername	= $this->input->post('eproductsuppliername', TRUE);
        $vproductretail	        = str_replace(",","",$this->input->post('vproductretail', TRUE));
        $vproductmill			= str_replace(",","",$this->input->post('vproductmill', TRUE));
        $fproductpricelist	    = $this->input->post('fproductpricelist', TRUE);
        $dproductstopproduction	= $this->input->post('dproductstopproduction', TRUE);
        $dproductregister	    = $this->input->post('dproductregister', TRUE);
        if($vproductretail=='')
            $vproductretail=0;
        if($vproductmill=='')
            $vproductmill=0;
        if ($iproduct != '' &&  $eproductname != ''){
            /*$cekada = $this->mmaster->cek_data($iproduct);
            if($cekada->num_rows() > 0){*/
                $this->mmaster->update($iproduct,$iproductsupplier,$isupplier,$iproductstatus,$iproducttype,$iproductcategory,$iproductclass,$iproductgroup,$eproductname,$eproductsuppliername,$vproductretail,$vproductmill,$fproductpricelist,$dproductstopproduction,$dproductregister);
                $this->Logger->write('Update Data Barang '.$this->global['title'].' Kode : '.$iproduct);

                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
                );

            /*}else{
                $data = array(
                    'sukses' => false
                );
            }*/
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $iproduct = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($iproduct)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
