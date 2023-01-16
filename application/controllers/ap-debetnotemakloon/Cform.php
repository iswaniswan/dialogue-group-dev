<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040108';

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
    

    public function index()    {
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

    public function data(){
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
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "DN-".date('ym')."-123456",
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

    public function supplier(){
        $filter = [];
        $data = $this->mmaster->getsupplier();
        foreach($data->result() as  $ikode){
                $filter[] = array(
                'id'   => $ikode->id_supplier,  
                'text' => $ikode->e_supplier_name,
            );
        }          
        echo json_encode($filter);
    }

    public function getreferensi(){
        $isupplier = $this->input->get('isupplier');
        $data = $this->mmaster->getreferensi($isupplier);
        if ($data->num_rows()>0) {
            $groupg   = [];
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->group;
            }
            $unique_data = array_unique($arr);
            foreach($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val==$row->group) {
                        $child[] = array(
                            'id' => $row->id.'|'.$row->i_bagian, 
                            'text' => $row->i_document, 
                        );
                    }
                }
                $filter[] = array(
                    'id' => 0,
                    'text' => strtoupper($val),
                    'children' => $child
                );
            }
        }else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function getdetailreff(){
        header("Content-Type: application/json", true);
        $reff    = explode('|', $this->input->post('ireferensi'));
        $id      = $reff[0];
        $ibagian = $reff[1];
        $idsupplier = $this->input->post('isupplier');
        $query  = array(
            'head'       => $this->mmaster->getdetailreff($id, $idsupplier, $ibagian)->row(),
            'dataitem'   => $this->mmaster->getdetailreff($id, $idsupplier, $ibagian)->result_array(),
        );
        echo json_encode($query); 
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE); 
        $inoteretur     = $this->input->post('inoteretur', TRUE); 
        $dnoteretur     = date('Y-m-d', strtotime($this->input->post('dnoteretur', TRUE))); 
        $isupplier      = $this->input->post("isupplier",TRUE);
        $ireferensi     = explode('|', $this->input->post('ireferensi'));
        $idreferensi    = $ireferensi[0];
        $ibagianrefe    = $ireferensi[1];

        $ibagianref     = $this->input->post("ibagianreff", TRUE);
        $dreferensi     = date('Y-m-d', strtotime($this->input->post("dreferensi",TRUE)));
        $ifaksup        = $this->input->post("ifaksup",TRUE);
        $ifakpajak      = $this->input->post("ifakpajak",TRUE);
        $dfakpajak      = date('Y-m-d', strtotime($this->input->post("dfakpajak",TRUE)));
        $vtotalppn      = str_replace(',','',$this->input->post("vtotalppn",TRUE));
        $vtotaldpp      = str_replace(',','',$this->input->post("vtotaldpp",TRUE));
        $vtotalfa       = str_replace(',','',$this->input->post("vtotalfa",TRUE));
        $eremark        = $this->input->post("eremark",TRUE);
        $jml            = $this->input->post('jml', TRUE);
        
        if($ibagian != '' && $inoteretur != '' && $dnoteretur != '' && $isupplier != '' && $idreferensi != ''){
            $cekkode = $this->mmaster->cek_kode($inoteretur, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();  
                $id = $this->mmaster->runningid();     
                $this->mmaster->insertheader($id, $inoteretur, $dnoteretur, $ibagian, $isupplier, $ifakpajak, $dfakpajak, $ifaksup, $vtotalppn, $vtotaldpp, $vtotalfa, $eremark, $ibagianref);
                for($i=0;$i<$jml;$i++){ 
                    $idmaterial     = $this->input->post('idmaterial'.$i, TRUE);
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $vprice         = str_replace(',','',$this->input->post('vprice'.$i, TRUE));
                    $vpricetotal    = str_replace(',','',$this->input->post('vpricetotal'.$i, TRUE));
                    $dpp            = str_replace(',','',$this->input->post('dpp'.$i, TRUE));
                    $ppn            = str_replace(',','',$this->input->post('ppn'.$i, TRUE));
                    $this->mmaster->insertdetail($id, $idreferensi, $idmaterial, $nquantity, $vprice, $vpricetotal, $dpp, $ppn);
                }
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$inoteretur);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        'kode'      => $inoteretur,
                        
                    );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $inoteretur,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $inoteretur,
            );
        }
        
        $this->load->view('pesan2', $data); 
    }

    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom       = $this->uri->segment(4);
        $dto         = $this->uri->segment(5);
        $id          = $this->uri->segment(6);
        $isupplier   = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'number'        => "DN-".date('ym')."-123456",
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->bacaheader($id, $isupplier)->row(),
            'datadetail'    => $this->mmaster->bacadetail($id, $isupplier)->result(),      
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE); 
        $inoteretur     = $this->input->post('inoteretur', TRUE); 
        $ikodeold       = $this->input->post('inotereturold', TRUE);
        $dnoteretur     = date('Y-m-d', strtotime($this->input->post('dnoteretur', TRUE))); 
        $isupplier      = $this->input->post("isupplier",TRUE);
        $ireferensi     = explode('|', $this->input->post('ireferensi'));
        $idreferensi    = $ireferensi[0];
        $ibagianrefe    = $ireferensi[1];
        $ibagianref     = $this->input->post("ibagianreff", TRUE);
        $dreferensi     = date('Y-m-d', strtotime($this->input->post("dreferensi",TRUE)));
        $irefer         = $this->input->post("irefer",TRUE);
        $ifaksup        = $this->input->post("ifaksup",TRUE);
        $ifakpajak      = $this->input->post("ifakpajak",TRUE);
        $dfakpajak      = date('Y-m-d', strtotime($this->input->post("dfakpajak",TRUE)));
        $vtotalppn      = str_replace(',','',$this->input->post("vtotalppn",TRUE));
        $vtotaldpp      = str_replace(',','',$this->input->post("vtotaldpp",TRUE));
        $vtotalfa       = str_replace(',','',$this->input->post("vtotalfa",TRUE));
        $eremark        = $this->input->post("eremark",TRUE);
        $jml            = $this->input->post('jum', TRUE); 
        if($inoteretur != '' && $dnoteretur != '' && $ibagian != '' && $isupplier != '' && $idreferensi != ''){
            $cekkode = $this->mmaster->cek_kodeedit($inoteretur, $ikodeold, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();       
                $this->mmaster->updateheader($id, $inoteretur, $dnoteretur, $ibagian, $isupplier, $ifakpajak, $dfakpajak, $ifaksup, $vtotalppn, $vtotaldpp, $vtotalfa, $eremark, $ibagianref);
                $this->mmaster->deletedetail($id);
                for($i=1;$i<=$jml;$i++){ 
                    $idmaterial     = $this->input->post('idmaterial'.$i, TRUE);
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $vprice         = str_replace(',','',$this->input->post('vprice'.$i, TRUE));
                    $vpricetotal    = str_replace(',','',$this->input->post('vpricetotal'.$i, TRUE));
                    $dpp            = str_replace(',','',$this->input->post('dpp'.$i, TRUE));
                    $ppn            = str_replace(',','',$this->input->post('ppn'.$i, TRUE));
                    $this->mmaster->insertdetail($id, $idreferensi, $idmaterial, $nquantity, $vprice, $vpricetotal, $dpp, $ppn);
                }
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$inoteretur);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                            'kode'   => $inoteretur,

                        );
                }else{
                        $this->db->trans_commit();
                        $data = array(
                            'sukses' => true,
                            'kode'   => $inoteretur,
                            'id'     => $id,
                        );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $inoteretur,
            );
        }
        
        $this->load->view('pesan2', $data); 
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom       = $this->uri->segment(4);
        $dto         = $this->uri->segment(5);
        $id          = $this->uri->segment(6);
        $isupplier   = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->bacaheader($id, $isupplier)->row(),
            'datadetail' => $this->mmaster->bacadetail($id, $isupplier)->result(),     
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
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

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom       = $this->uri->segment(4);
        $dto         = $this->uri->segment(5);
        $id          = $this->uri->segment(6);
        $isupplier   = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $id,
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->bacaheader($id, $isupplier)->row(),
            'datadetail' => $this->mmaster->bacadetail($id, $isupplier)->result(),      
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
}
/* End of file Cform.php */