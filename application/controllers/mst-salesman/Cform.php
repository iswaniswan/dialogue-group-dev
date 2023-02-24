<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010302';

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

    function data()
    {
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],               
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE)/* , $this->input->post('area',TRUE) */);
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit()
    {
        $data = $this->mmaster->cek_kode_edit($this->input->post('kode',TRUE), $this->input->post('kode_old',TRUE)/* , $this->input->post('area',TRUE) */);
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function area(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->area($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->e_area,
            );
        }          
        echo json_encode($filter);
    }

    public function role(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->role($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_role,  
                'text' => $key->e_role_name,
            );
        }          
        echo json_encode($filter);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isales    = $this->input->post('isales', TRUE);
        $esales    = $this->input->post('esales', TRUE); 
        $iarea     = $this->input->post('iarea', TRUE);
        $irole     = $this->input->post('irole', TRUE);
        $ekota     = $this->input->post('ekota', TRUE); 
        $etelepon  = $this->input->post('etelepon', TRUE); 
        $ealamat   = $this->input->post('ealamat', TRUE);
        $ekodepos  = $this->input->post('ekodepos', TRUE);           
        $id        = $this->mmaster->runningid();

        if ($isales != '' && $esales != ''){
            $cekada = $this->mmaster->cek_data($id);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->insert($id, $isales, $esales, $iarea, $irole, $ekota, $ealamat, $ekodepos, $etelepon);
                if($this->db->trans_status() === False){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isales);
                    $data = array(
                        'sukses'  => true,
                        'kode'    => $isales
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

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->uri->segment(4);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_datas($id)->row(),

        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id        = $this->input->post('id', TRUE);
        $isales    = $this->input->post('isales', TRUE);
        $esales    = $this->input->post('esales', TRUE); 
        $iarea     = $this->input->post('iarea', TRUE);
        $irole     = $this->input->post('irole', TRUE);
        $ekota     = $this->input->post('ekota', TRUE); 
        $etelepon  = $this->input->post('etelepon', TRUE); 
        $ealamat   = $this->input->post('ealamat', TRUE);
        $ekodepos  = $this->input->post('ekodepos', TRUE);

        if ($id != '' && $isales != '' && $esales != ''){            
            $this->db->trans_begin();       
            $this->mmaster->update($id, $isales, $esales, $iarea, $irole, $ekota, $ealamat, $ekodepos, $etelepon);
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isales);
                $data = array(
                    'sukses' => true,
                    'kode'   => $isales
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

        $id = $this->uri->segment(4);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_datas($id)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */