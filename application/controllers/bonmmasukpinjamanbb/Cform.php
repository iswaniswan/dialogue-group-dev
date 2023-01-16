<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050213';

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
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
		echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "BBM-".date('ym')."-000001",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function partner(){
        $filter = [];
        $data   = $this->mmaster->partner();
        foreach($data->result() as  $kode){
            $filter[] = array(
                'id'   => $kode->id_partner.'|'.$kode->i_partner.'|'.$kode->i_partner_group,  
                'text' => $kode->e_partner_name,
            );
        }          
        echo json_encode($filter);
    }

    public function referensi()
    {
        $filter = [];
        $partner = $this->input->get('ipartner');
        $partner = explode('|', $partner);
        $idpartner = $partner[0];
        $ipartner  = $partner[1];
        $ipartnergroup = $partner[2];
        $idpartner = (int)$idpartner;
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')),$idpartner, $ipartnergroup);
        if($data->num_rows() > 0){
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id, 
                    'text'  => $row->i_document,
                );
            }
        }else{
            $filter[] = array(
                    'id' => null,
                    'text' => "Tidak Ada Data"
            );
        }
        
        echo json_encode($filter);
    }

    public function getdetailref(){
        header("Content-Type: application/json", true);
        $data = [];
        $id   = $this->input->post('id', TRUE);
        $data = array(
            'head'   => $this->mmaster->getdetailref($id)->row(),
            'detail' => $this->mmaster->getdetailrefitem($id)->result_array()
        );
        echo json_encode($data);  
    }

    public function cekkode() {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode',TRUE),$this->input->post('kodeold',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian    = $this->input->post("ibagian", TRUE);
        $ikeluar    = $this->input->post("ikeluar", TRUE);
        $dkeluar    = $this->input->post("dkeluar",TRUE);
        if ($dkeluar) {
            $tmp = explode('-', $dkeluar);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $datekeluar = $year . '-' . $month . '-' . $day;
        }
        $imemo      = $this->input->post('imemo', TRUE);
        $partner    = $this->input->post('ipartner', TRUE);  
        $partner = explode('|', $partner);
        $idpartner = $partner[0];
        $ipartner  = $partner[1];      
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE); 
        $id         = $this->mmaster->runningid();

        $i_material  = $this->input->post('idmaterial[]', TRUE);    
        $n_quantity  = str_replace(',','',$this->input->post('nquantity[]', TRUE));
        $e_desc      = $this->input->post('edesc[]', TRUE); 

        if ($ikeluar!='' && $jml>0) {        
            $this->db->trans_begin();
            $this->mmaster->insertheader($id, $ibagian, $ikeluar, $datekeluar, $imemo, $ipartner, $eremark);

            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantity     = $n_quantity[$no];
                $edesc         = $e_desc[$no];    
                
                if($nquantity > 0){
                    $this->mmaster->insertdetail($id, $imaterial,$nquantity, $edesc, $imemo);
                }
                $no++;
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $ikeluar,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);      
    
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id    = $this->uri->segment(4);
        $dfrom = $this->uri->segment(5);
        $dto   = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id)->row(),
            'detail'        => $this->mmaster->baca_detail($id)->result()
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id         = $this->input->post('id', TRUE);
        $kodeold    = $this->input->post('idocumentold', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        $ikeluar    = $this->input->post('ikeluar', TRUE);
        $dkeluar    = $this->input->post('dkeluar',TRUE);
        if ($dkeluar) {
            $tmp = explode('-', $dkeluar);
            $day    = $tmp[0];
            $month  = $tmp[1];
            $year   = $tmp[2];
            $datekeluar = $year . '-' . $month . '-' . $day;
        }
        $imemo      = $this->input->post('imemo', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE); 

        $i_material  = $this->input->post('idmaterial[]', TRUE);    
        $n_quantity  = str_replace(',','',$this->input->post('nquantity[]', TRUE));
        $e_desc      = $this->input->post('edesc[]', TRUE); 

        if($ibagian != '' && $ibagian != null && $ikeluar != '' && $ikeluar != null){
            $cekdata     = $this->mmaster->cek_kode($kodeold,$ibagian);
            if ($cekdata->num_rows()==0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ikeluar, $ibagian, $datekeluar, $eremark);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial     = $imaterial;
                    $nquantity     = $n_quantity[$no];
                    $edesc         = $e_desc[$no];   
                    $this->mmaster->updatedetail($id, $imaterial,$nquantity, $edesc);
                    $no++;
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                        $data = array(
                            'sukses'    => false,
                        );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikeluar);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ikeluar,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'data'          => $this->mmaster->baca_header($id)->row(),
            'detail'        => $this->mmaster->baca_detail($id)->result()
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id    = $this->uri->segment(4);
        $dfrom = $this->uri->segment(5);
        $dto   = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'data'          => $this->mmaster->baca_header($id)->row(),
            'detail'        => $this->mmaster->baca_detail($id)->result(),
            );
    
            $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function changestatus() {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
   }  
}
/* End of file Cform.php */
