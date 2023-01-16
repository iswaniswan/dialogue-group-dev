<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2011305';

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

    function color(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_kode_color, e_color_name");
            $this->db->from("tr_color");
            $this->db->like("UPPER(i_kode_color)", $cari);
            $this->db->or_like("UPPER(e_color_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_kode_color,  
                    'text' => $icolor->i_kode_color.'-'.$icolor->e_color_name,
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
            'wip_warnabrg'  => $this->mmaster->get_wipwarnabrg()->result(),
            'wip_barang'    => $this->mmaster->get_wipbarang()->result(), 
            'warna'         => $this->mmaster->get_warna()->result(),  
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $iwarna     = $this->input->post('iwarna', TRUE);
        $ikodebrg   = $this->input->post('ikodebrg', TRUE);        
        $jml        = $this->input->post('jml', TRUE); 

        $qwarna = $this->db->query("SELECT i_warna FROM tm_warna_wip ORDER BY i_warna DESC LIMIT 1");
                if ($qwarna->num_rows() > 0) {
                    $row_warna = $qwarna->row();
                    $iwarna= $row_warna->i_warna+1;
                }
                else
                    $iwarna = 1;

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iwarna);
        $this->mmaster->insertheader($ikodebrg, $iwarna);
        
        for($i=1;$i<=$jml;$i++){  
            $icolor       = $this->input->post('icolor'.$i, TRUE); 
            $this->mmaster->insertdetail($iwarna, $ikodebrg, $icolor);                 
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $iwarna,
            );
        }
    $this->load->view('pesan', $data);     
    }      

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iwarna = $this->uri->segment('4');
        $ikodebrg = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_dataheader($iwarna)->row(),
            'data2'         => $this->mmaster->cek_datadetail($iwarna,$ikodebrg)->result(),  
            'wip_warnabrg'  => $this->mmaster->get_wipwarnabrg()->result(),
            'wip_barang'    => $this->mmaster->get_wipbarang()->result(), 
            'warna'         => $this->mmaster->get_warna()->result(),            
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iwarna     = $this->input->post('iwarna', TRUE);
        $ikodebrg   = $this->input->post('ikodebrg', TRUE); 
        $jml        = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data'.$this->global['title'].' Kode : '.$iwarna);
        //$this->mmaster->update($iwarna, $ikodebrg);
       
        for($i=1;$i<=$jml;$i++){  
            $icolor   = $this->input->post('icolor'.$i, TRUE);

            $this->mmaster->deletedetail($iwarna, $ikodebrg, $icolor);
            $this->mmaster->insertdetail($iwarna, $ikodebrg, $icolor);                
        }
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $iwarna,
                );
        }
        $this->load->view('pesan', $data);     
    }      


    public function view(){

        $iwarna = $this->uri->segment('4');
        $ikodebrg = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iwarna)->row(),
            'data2'         => $this->mmaster->cek_data2($iwarna,$ikodebrg)->result(),  
            'wip_warnabrg'  => $this->mmaster->get_wipwarnabrg()->result(),
            'wip_barang'    => $this->mmaster->get_wipbarang()->result(), 
            'warna'         => $this->mmaster->get_warna()->result(),  
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */