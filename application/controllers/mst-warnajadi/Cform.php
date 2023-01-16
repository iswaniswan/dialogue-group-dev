<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010205';
   
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

    function warnajadi(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_color, e_color_name");
            $this->db->from("tr_color");
            $this->db->like("UPPER(i_color)", $cari);
            $this->db->or_like("UPPER(e_color_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->e_color_name,
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'productmotif'  => $this->mmaster->get_productmotif()->result(),
            'productcolor'  => $this->mmaster->get_productcolor()->result(), 
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $iproductcolor  = $this->input->post('iproductcolor', TRUE);
        $iproductmotif  = $this->input->post('iproductmotif', TRUE);        
        $jml            = $this->input->post('jml', TRUE);                
        
        if ($iproductmotif != ''){
            $cekada = $this->mmaster->cek_data($iproductcolor);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                for($i=1;$i<=$jml;$i++){  
                    $icolor       = $this->input->post('icolor'.$i, TRUE);                
                    $this->mmaster->insert($iproductmotif, $icolor);                 
                }
                
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproductcolor);
               // $this->mmaster->insert($iproductmotif);
                 
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproductcolor
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

        $iproductcolor = $this->uri->segment('4');
        $iproductmotif = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductcolor)->row(),
            'data2'         => $this->mmaster->cek_data2($iproductcolor,$iproductmotif)->result(),  
            'productmotif'  => $this->mmaster->get_productmotif()->result(),
            'productcolor'  => $this->mmaster->get_productcolor()->result(),             
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproductcolor  = $this->input->post('iproductcolor', TRUE);
        $iproductmotif  = $this->input->post('iproductmotif', TRUE); 
        $jml            = $this->input->post('jml', TRUE); 

        if ($iproductmotif != ''){
            $cekada = $this->mmaster->cek_data2($iproductcolor, $iproductmotif);
            if($cekada->num_rows() > 0){ 
                for($i=1;$i<=$jml;$i++){  
                    $icolor   = $this->input->post('icolor'.$i, TRUE);

                    //$this->mmaster->delete($iproductcolor);
                    $this->mmaster->insertdetail($iproductmotif, $icolor);       
                }
               
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproductcolor
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

        $iproductcolor = $this->uri->segment('4');
        $iproductmotif = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproductcolor)->row(),
            'data2'         => $this->mmaster->cek_data2($iproductcolor,$iproductmotif)->result(),  
            'productmotif'  => $this->mmaster->get_productmotif()->result(),
            'productcolor'  => $this->mmaster->get_productcolor()->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */