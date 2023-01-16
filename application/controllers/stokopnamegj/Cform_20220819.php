<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/* use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory; */

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090607';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
        $data = check_role_folder($this->uri->segment(1), 2);
        // $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_menu = $data[0]['i_menu'];
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        // var_dump($this->uri->segment(1));
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
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
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    public function index2()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SO-".date('ym')."-1234"
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function tambah(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post("ibagian",true);
        $ddocument  = $this->input->post("ddocument",true);
        $idocument  = $this->input->post("idocument",true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($ddocument == "") $ddocument = $this->uri->segment(5);
        if ($idocument == "") $idocument = $this->uri->segment(6);
        $dfrom      = $this->input->post("dfrom",true);
        $dto        = $this->input->post("dto",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $idocument,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->id_company)->row(),
            'datadetail'    => $this->mmaster->datadetail($this->id_company,$ibagian)->result(),
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang() {
        $filter = [];
        $data = $this->mmaster->barang(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'), $this->input->get('ddocument') );
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id.'|'.$row->e_color_name,
                    'text' => $row->i_product_wip.' - '.$row->e_product_wipname.' ('.$row->e_color_name.')'
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


    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE); 
        $idocument  = $this->input->post('idocument', TRUE);
        
        $eremarkh  = $this->input->post('eremarkh', TRUE);
        $idcompany = $this->id_company;
        $jml        = $this->input->post('xml', TRUE);

        $format     = DateTime::createFromFormat('d-m-Y', $this->input->post('ddocument', TRUE));
        $iperiode   = $format->format('Ym');
        $ddocument  = $format->format('Y-m-d');
        $this->db->trans_begin();
        $id = $this->mmaster->runningid();
        $this->mmaster->simpan($id,$idcompany,$ibagian,$idocument,$ddocument,$iperiode, $eremarkh);
        //$this->mmaster->hapusdetail($idcompany, $id);
        for ($i = 1; $i <= $jml; $i++) {
            $idmaterial = $this->input->post('idmaterial' . $i, TRUE);
            $idmaterial = explode('|', $idmaterial);
            $qty        = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
            $qty_repair = str_replace(",","",$this->input->post('nquantity_repair' . $i, TRUE));
            $qty_gradeb = str_replace(",","",$this->input->post('nquantity_gradeb' . $i, TRUE));
            $eremark    = $this->input->post('eremark' . $i, TRUE);
            if ($idmaterial[0]!=null || $idmaterial[0]!='') {
                $this->mmaster->simpandetail($idcompany, $id, $idmaterial[0], $qty, $qty_repair, $qty_gradeb, $eremark);
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data); 
    }

    public function changestatus(){
        $id         = $this->input->post('id', true);
        $istatus    = $this->input->post('istatus', true);
        $estatus    = $this->mmaster->estatus($istatus);
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

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);
       
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE); 
        $idocument  = $this->input->post('idocument', TRUE);        
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idcompany  = $this->id_company;
        $jml        = $this->input->post('xml', TRUE);

        $format     = DateTime::createFromFormat('d-m-Y', $this->input->post('ddocument', TRUE));
        $iperiode   = $format->format('Ym');
        $ddocument  = $format->format('Y-m-d');
        $this->db->trans_begin();
        $id = $this->input->post('id', TRUE);
        /* var_dump($_POST);
        die; */
        $this->mmaster->updateheader($id, $eremarkh);
        $this->mmaster->hapusdetail($id);
        for ($i = 1; $i <= $jml; $i++) {
            $idmaterial = $this->input->post('idmaterial' . $i, TRUE);
            $idmaterial = explode('|', $idmaterial);
            $qty        = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
            $qty_repair = str_replace(",","",$this->input->post('nquantity_repair' . $i, TRUE));
            $qty_gradeb = str_replace(",","",$this->input->post('nquantity_gradeb' . $i, TRUE));
            $eremark    = $this->input->post('eremark' . $i, TRUE);
            if ($idmaterial[0]!=null || $idmaterial[0]!='') {
                // $this->mmaster->simpandetail($idcompany, $id, $idmaterial[0], $qty, $eremark);  
                $this->mmaster->simpandetail($idcompany, $id, $idmaterial[0], $qty, $qty_repair, $qty_gradeb, $eremark);          
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data); 
    }
     

    public function approval(){
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);
       

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

     public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        // var_dump($id, $dfrom, $dto);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */