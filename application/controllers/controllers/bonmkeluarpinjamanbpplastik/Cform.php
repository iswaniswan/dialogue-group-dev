<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050404';

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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
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
            'number'        => "BBK-".date('ym')."-000001",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
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
        $data = $this->mmaster->partner(str_replace("'","",$this->input->get('q')));
        if ($data->num_rows()>0) {
            $group   = [];
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->grouppartner;
            }
            $unique_data = array_unique($arr);
            foreach($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val==$row->grouppartner) {
                        $child[] = array(
                            'id' => $row->id.'|'.$row->grouppartner, 
                            'text' => $row->e_name, 
                        );
                    }
                }
                $filter[] = array(
                    'id' => 0,
                    'text' => strtoupper($val),
                    'children' => $child
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function referensi(){
        $filter = [];
        $partner     = explode('|', $this->input->get('ipartner', TRUE));        
        $idpartner   = $partner[0];
        $ipartner    = $partner[1];
        
        $data   = $this->mmaster->referensi(str_replace("'","",$this->input->get('q')), $idpartner, $ipartner);            
        if ($data->num_rows()>0) {
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->id,  
                    'text' => $key->i_document,
                );
            }   
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function getdetailrefeks(){
        header("Content-Type: application/json", true);
        $id     = $this->input->post('id');

        $query  = array(
            'head'   => $this->mmaster->getdetailrefeks($id)->row(),
            'detail' => $this->mmaster->getdetailrefeks($id)->result_array()
        );
        echo json_encode($query);  
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
        $dback = $this->input->post("dback",TRUE);
        if ($dback) {
            $tmp = explode('-', $dback);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $dateback = $year . '-' . $month . '-' . $day;
        }
        $imemo      = $this->input->post('imemo', TRUE);
        $partner    = explode('|', $this->input->post('ipartner', TRUE));        
        $idpartner  = $partner[0];
        $ipartner   = $partner[1];
        $idpic      = $this->input->post('idpic', TRUE);
        $epic       = $this->input->post('epic', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE); 
        $id         = $this->mmaster->runningid();

        $i_material        = $this->input->post('idmaterial[]', TRUE);    
        $n_quantity_memo   = $this->input->post('nquantitymemo[]', TRUE);
        $n_sisa            = $this->input->post('sisa[]', TRUE);
        $n_quantity        = str_replace(',','',$this->input->post('nquantity[]', TRUE));
        $e_desc            = $this->input->post('edesc[]', TRUE); 

        if ($ikeluar!='' && $jml>0) {        
            $this->db->trans_begin();
            $this->mmaster->insertheader($id, $ibagian, $ikeluar, $datekeluar, $dateback, $imemo, $idpartner, $ipartner, $idpic, $epic, $eremark);

            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantitymemo = $n_quantity_memo[$no];
                $nsisa         = $n_sisa[$no];
                $nquantity     = $n_quantity[$no];
                $edesc         = $e_desc[$no];    
                
                if($nquantity > 0){
                    $this->mmaster->insertdetail($id, $imaterial, $nquantitymemo, $nsisa, $nquantity, $edesc, $imemo);
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

    public function changestatus(){

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "BBK-".date('ym')."-000001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id)->row(),
            'datadetail'    => $this->mmaster->baca_detail($id)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post("ibagian", TRUE);
        $id         = $this->input->post("id", TRUE);
        $ikeluar    = $this->input->post("ikeluar", TRUE);
        $dkeluar    = $this->input->post("dkeluar",true);
        if ($dkeluar) {
            $tmp    = explode('-', $dkeluar);
            $day    = $tmp[0];
            $month  = $tmp[1];
            $year   = $tmp[2];
            $datekeluar = $year . '-' . $month . '-' . $day;
        }
        $dback = $this->input->post("dback",true);
        if ($dback) {
            $tmp    = explode('-', $dback);
            $day    = $tmp[0];
            $month  = $tmp[1];
            $year   = $tmp[2];
            $dateback = $year . '-' . $month . '-' . $day;
        }
        $imemo      = $this->input->post('imemo', TRUE);
        $partner    = explode('|', $this->input->post('ipartner', TRUE));        
        $idpartner  = $partner[0];
        $ipartner   = $partner[1];
        $idpic      = $this->input->post('idpic', TRUE);
        $epic       = $this->input->post('epic', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        $i_material        = $this->input->post('idmaterial[]', TRUE);    
        $n_quantity_memo   = $this->input->post('nquantitymemo[]', TRUE);
        $n_sisa            = $this->input->post('sisa[]', TRUE);
        $n_quantity        = str_replace(',','',$this->input->post('nquantity[]', TRUE));
        $e_desc            = $this->input->post('edesc[]', TRUE); 

        $this->db->trans_begin();
        $this->mmaster->updateheader($id, $ibagian, $ikeluar, $datekeluar, $dateback, $imemo, $idpartner, $ipartner, $idpic, $epic, $eremark);
            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantitymemo = $n_quantity_memo[$no];
                $nsisa         = $n_sisa[$no];
                $nquantity     = $n_quantity[$no];
                $edesc         = $e_desc[$no];    
                
                $this->mmaster->updatedetail($id, $imaterial, $nquantitymemo, $nsisa, $nquantity, $edesc, $imemo);

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
                    'id'     => $id
                );
        }
        $this->load->view('pesan2', $data);
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id)->row(),
            'datadetail'    => $this->mmaster->baca_detail($id)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id)->row(),
            'datadetail'    => $this->mmaster->baca_detail($id)->result(),
            );
    
            $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
}
/* End of file Cform.php */