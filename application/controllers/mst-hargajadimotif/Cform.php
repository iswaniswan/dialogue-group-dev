<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010204';
   
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


    function getkode(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('a.i_product, a.i_product_motif, a.e_product_motifname, b.v_unitprice');
            $this->db->from('tr_product_motif a');
            $this->db->join('tr_product_base b', 'b.i_product_base = a.i_product');
            $this->db->where('a.n_active = 1');
            $this->db->like("UPPER(i_product)", $cari);
            $this->db->or_like("UPPER(e_product_motifname)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_product,  
                    'text' => $iproduct->i_product.'-'.$iproduct->e_product_motifname
                );
            }      
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
   
    function getkodebarang(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
            $this->db->select('a.i_product, a.i_product_motif, a.e_product_motifname, b.v_unitprice');
            $this->db->from('tr_product_motif a');
            $this->db->join('tr_product_base b', 'b.i_product_base = a.i_product');
            $this->db->where("UPPER(i_product)", $iproduct);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }


    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'barangjadi'    => $this->mmaster->get_barangjadi()->result(),          
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproductprice  = $this->input->post('iproductprice', TRUE);
        $iproduct       = $this->input->post('iproduct', TRUE);
        $iproductmotif  = $this->input->post('iproductmotif', TRUE);
        $vprice         = $this->input->post('vprice', TRUE);                        
        
        if ($iproduct != ''){
                $cekada = $this->mmaster->cek_data($iproductprice);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                   );
                }else{               
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproductprice);
                    
                    $this->mmaster->insert($iproductprice, $iproduct, $iproductmotif, $vprice);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iproductprice,
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

        $iproductprice = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductprice)->row(),
            'barangjadi'    => $this->mmaster->get_barangjadi()->result(), 
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproductprice  = $this->input->post('iproductprice', TRUE);
        $iproduct       = $this->input->post('iproduct', TRUE);
        $iproductmotif  = $this->input->post('iproductmotif', TRUE);
        $vprice         = $this->input->post('vprice', TRUE);    

        if ($iproduct != '' && $iproductmotif != ''){
            $cekada = $this->mmaster->cek_data($iproductprice);
            if($cekada->num_rows() > 0){                
                $this->mmaster->update($iproductprice, $iproduct, $iproductmotif, $vprice);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproductprice
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

        $iproductprice= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductprice)->row(),
            'barangjadi'    => $this->mmaster->get_barangjadi()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */