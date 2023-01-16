<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1010211';

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
            'isi' => $this->mmaster->bacakode(),
            'productgrade'=> $this->mmaster->bacagrade()
            //$data['iproductgroup'] = $this->mmaster->bacagroup();
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
        
    }

    function data(){
        $data['isi']=$this->mmaster->bacakode();
		echo $this->mmaster->data($this->i_menu);
    }

    function databrg(){
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
                    'id' => $product->i_product,  
                    'text' => $product->i_product.' - '.$product->e_product_name
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
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    // public function simpanxxx(){
    //     echo $iproduct 	= $this->input->post('iproduct', TRUE).' || ';
	// 	echo $eproductname 	= $this->input->post('eproductname', TRUE).' || ';
	// 	echo $iproductgrade 	= $this->input->post('iproductgrade', TRUE).' || ';
	// 	echo $vproductretail1	= $this->input->post('vproductretail1', TRUE).' || ';
    // }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct 	= $this->input->post('iproduct', TRUE);
		$eproductname 	= $this->input->post('eproductname', TRUE);
		$iproductgrade 	= substr($this->input->post('iproductgrade', TRUE),0,1);
        $jml	= $this->input->post('jmlitem', TRUE);
        
        if ((isset($iproduct) && $iproduct != '') && (isset($eproductname) && $eproductname != '') && (isset($iproductgrade) && $iproductgrade != ''))
			{
                $this->db->trans_begin();
                for($i=1; $i<= $jml; $i++){
                    $ipricegroup	= $this->input->post('ipricegroup'.$i, TRUE);
		            $vproductmill 	= $this->input->post('vproductmill'.$i, TRUE);
                    $vproductretail	= $this->input->post('vproductretail'.$i, TRUE);
                        $vproductmill	 = str_replace(",","",$vproductmill);
			            $vproductretail= str_replace(",","",$vproductretail);
                    if($vproductmill == '')
                    $vproductretail = 0;
                    if($vproductretail == '')
                    $vproductretail = 0;
                    
        if($vproductretail !=0){
            $query = $this->db->query("select distinct i_product, i_product_grade, v_product_mill 
            from tr_product_price where i_product='$iproduct' 
            and i_product_grade='$iproductgrade' and i_price_group='$ipricegroup'",false);
        if($query->num_rows() >0){
            $this->mmaster->update($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill,$vproductretail);
        }else{
            $this->mmaster->insert($iproduct,$ipricegroup,$eproductname,$iproductgrade,$vproductmill,$vproductretail);            
        }
                }            
            }
        }
        if(($this->db->trans_status()=== False))
        {
            $this->db->trans_rollback();
            $data = array(
            'sukses' => false);
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproduct);
                    //$this->mmaster->insert($isuppliergroup,$esuppliergroupname,$esuppliergroupnameprint1,$esuppliergroupnameprint2);
            $data = array(
                'sukses'    => true,
                'kode'    => $iproduct
                    );
        }
        // }else{
        //         $data = array(
        //             'sukses' => false
        //         );
        // }
        $this->load->view('pesan', $data);  
        }
    }
/* End of file Cform.php */
