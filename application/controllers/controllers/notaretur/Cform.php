<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040107';

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
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->supplier($cari);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $ikode){
                    $filter[] = array(
                    'id'   => $ikode->id,  
                    'text' => $ikode->e_supplier_name,
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

    public function getreferensi(){
        $isupplier = $this->input->post('isupplier');
        $query = $this->mmaster->getreferensi($isupplier);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->id." >".$row->i_document.' || '.$row->d_document."</option>";
            }
            $kop  = "<option value=\"\">Pilih No Referensi".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getdetailreff(){
        header("Content-Type: application/json", true);
        $ireferensi        = $this->input->post('ireferensi');
        $isupplier         = $this->input->post('isupplier');

        $query  = array(
                        'dataitem'   => $this->mmaster->getdetailreff($ireferensi, $isupplier)->result_array(),
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
        $dnoteretur     = $this->input->post('dnoteretur', TRUE); 
        if($dnoteretur){
                 $tmp   = explode('-', $dnoteretur);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datenoteretur = $year.'-'.$month.'-'.$day;
        }
        $isupplier  = $this->input->post("isupplier",TRUE);
        $vtotalfa   = str_replace(',','',$this->input->post("vtotalfa",TRUE));
        $eremark    = $this->input->post("eremark",TRUE);
        $jml        = $this->input->post('jml', TRUE); 
        
        $id         = $this->mmaster->runningid();
        $this->db->trans_begin();       
        $this->mmaster->insertheader($id, $inoteretur, $datenoteretur, $ibagian, $isupplier, $vtotalfa, $eremark);
 
        for($i=1;$i<=$jml;$i++){ 
            $idnotaretur    = $this->input->post('idnotaretur'.$i, TRUE);
            $idmaterial     = $this->input->post('idmaterial'.$i, TRUE);
            $nquantity      = $this->input->post('nquantity'.$i, TRUE);
            $vprice         = str_replace(',','',$this->input->post('vprice'.$i, TRUE));
            $vpricetotal    = str_replace(',','',$this->input->post('vpricetotal'.$i, TRUE));
            $this->mmaster->insertdetail($id, $idnotaretur, $idmaterial, $nquantity, $vprice, $vpricetotal);
            
        }
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$inoteretur);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $inoteretur,
                    'id'     => $id,
                );
        }
        $this->load->view('pesan2', $data); 
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom       = $this->uri->segment('4');
        $dto         = $this->uri->segment('5');
        $id          = $this->uri->segment('6');
        $idsupplier  = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'number'     => "DN-".date('ym')."-123456",
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_header($id, $idsupplier)->row(),
            'datadetail' => $this->mmaster->cek_detail($id)->result(),            
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE); 
        $inoteretur     = $this->input->post('inoteretur', TRUE); 
        $dnoteretur     = $this->input->post('dnoteretur', TRUE); 
        if($dnoteretur){
                 $tmp   = explode('-', $dnoteretur);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datenoteretur = $year.'-'.$month.'-'.$day;
        }
        $isupplier  = $this->input->post("isupplier",TRUE);
        $vtotalfa   = str_replace(',','',$this->input->post("vtotalfa",TRUE));
        $eremark    = $this->input->post("eremark",TRUE);
        $jml        = $this->input->post('jml', TRUE); 

        //$this->db->trans_begin();
        $this->mmaster->updateheader($id, $inoteretur, $datenoteretur, $isupplier, $vtotalfa, $eremark);
        $this->mmaster->deletedetail($id);

        for($i=1;$i<=$jml;$i++){ 
            $idnotaretur    = $this->input->post('idnotaretur'.$i, TRUE);
            $idmaterial     = $this->input->post('idmaterial'.$i, TRUE);
            $nquantity      = $this->input->post('nquantity'.$i, TRUE);
            $vprice         = str_replace(',','',$this->input->post('vprice'.$i, TRUE));
            $vpricetotal    = str_replace(',','',$this->input->post('vpricetotal'.$i, TRUE));
            $this->mmaster->insertdetail($id, $idnotaretur, $idmaterial, $nquantity, $vprice, $vpricetotal);
            
        }
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$inoteretur);
        
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $inoteretur,
                    'id'     => $id,
                );
        }
    $this->load->view('pesan2', $data); 
    }

    public function view(){

        $dfrom       = $this->uri->segment('4');
        $dto         = $this->uri->segment('5');
        $id          = $this->uri->segment('6');
        $idsupplier  = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_header($id, $idsupplier)->row(),
            'datadetail' => $this->mmaster->cek_detail($id)->result(),            
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom       = $this->uri->segment('4');
        $dto         = $this->uri->segment('5');
        $id          = $this->uri->segment('6');
        $idsupplier  = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_header($id, $idsupplier)->row(),
            'datadetail' => $this->mmaster->cek_detail($id)->result(),            
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
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
}
/* End of file Cform.php */