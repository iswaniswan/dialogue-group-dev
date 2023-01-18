<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050308';

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
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'number'     => "RPA-".date('ym')."-123456",
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bacagudang()->result(),
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function bacagudang(){
        $filter = [];       
        $data = $this->mmaster->bacagudang();
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_bagian,  
                'text' => $key->e_bagian_name,
            );
        }          
        echo json_encode($filter);
    }    

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number() {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function bacasupplier(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->bacasupplier($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_supplier,  
                'text' => $key->e_supplier_name,
            );
        }          
        echo json_encode($filter);
    }

    public function getnota(){
        $isupplier = $this->input->post('isupplier');

        $query     = $this->mmaster->bacanota($isupplier);
        if($query->num_rows()>0) {
            $c   = "";
            $e   = "";
            $nota = $query->result();
            foreach($nota as $row) {
                $c.="<option value=".$row->id." >".$row->i_btb."</option>";
                $e=$row->e_supplier_name;
            }
            $kop  = "<option value=\"\">Pilih Nomor Referensi".$c."</option>";
            $esupplier = $e;
            echo json_encode(array(
                'kop'       => $kop,
                'esupplier' => $esupplier,
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'       => $kop,
              //'esupplier' => $esupplier,
                'kosong'    => 'kopong'
            ));
        }
    }

    public function getmemo(){
        header("Content-Type: application/json", true);
        $isupplier = $this->input->post('isupplier');
        $idnota    = $this->input->post('idnota');
        $query  = array(
            'head'   => $this->mmaster->getmemo($idnota)->row(),
            'detail' => $this->mmaster->getmemodetail($isupplier, $idnota)->result_array()
        );
        echo json_encode($query);  
    }    

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ikodemaster', TRUE);    
        $iretur     = $this->input->post('iretur', TRUE);  
        $dretur     = $this->input->post('dretur',TRUE);
        if($dretur){
            $tmp   = explode('-', $dretur);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dateretur = $year.'-'.$month.'-'.$day;
        }
        $ifaktur    = $this->input->post('ifaktur', TRUE);        
        $dnota      = $this->input->post('dnota',TRUE);
        if($dnota){
             $tmp   = explode('-', $dnota);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $datenota = $year.'-'.$month.'-'.$day;
        }
        $isupplier  = $this->input->post('isupplier', TRUE);
        $esupplier  = $this->input->post('esupplier',TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $vtot       = $this->input->post('vtotal',TRUE);
        $jml        = $this->input->post('jml', TRUE);
        $id         = $this->mmaster->runningid();

        if($ibagian != ''  && $iretur != '' && $dretur != '' && $ifaktur != ''){
            $cekdata     = $this->mmaster->cek_kode($iretur,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin(); 
               
                $this->mmaster->insertheader($id, $iretur, $dateretur, $ibagian, $isupplier, $esupplier, $vtot, $eremark);
                //INSERT DETAIL
                for($i=1;$i<=$jml;$i++){
                    $idbtb          = $this->input->post('ibtb'.$i, TRUE);
                    // $idnota         = $this->input->post('inota'.$i, TRUE);
                    $isj            = $this->input->post('isj'.$i, TRUE);
                    $imaterial      = $this->input->post('iproduct'.$i, TRUE);
                    $isatuan        = $this->input->post('isatuan'.$i, TRUE);
                    $nquantity      = $this->input->post('qtyretur'.$i, TRUE);
                    $qty            = $this->input->post('qty'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $vunitprice     = $this->input->post('vunitprice'.$i, TRUE);
                    $cek            = $this->input->post('cek'.$i);
                    if($cek == 'on'|| $nquantity > 0){
                        $this->mmaster->insertdetail($id, $idbtb, $isj, $imaterial, $isatuan, $nquantity, $vunitprice, $edesc);
                        //$this->mmaster->insertreturbeli($idn, $inota);
                    }
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                        );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iretur),
                        'sukses' => true,
                        'kode'   => $iretur,
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $iretur     = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $this->uri->segment(4),
            'isupplier'     => $this->uri->segment(6),
            'number'        => "RPA-".date('ym')."-123456",
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->cek_data($id, $iretur, $isupplier)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $iretur, $isupplier)->result(),
            'supplier'      => $this->mmaster->cek_supplier($this->uri->segment(6)),
            'referensi'     => $this->mmaster->cek_referensi($this->uri->segment(4)),
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
        $ibagian    = $this->input->post('ibagian', TRUE);
        $idn        = $this->input->post('iretur', TRUE);   
        $dretur     = $this->input->post("dretur",true);
        if($dretur){
             $tmp   = explode('-', $dretur);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $dateretur = $year.'-'.$month.'-'.$day;
        }
        $isupplier  = $this->input->post('isupplier', TRUE);
        $ifaktur    = $this->input->post('ifaktur', TRUE);        
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        $this->mmaster->updateheader($id, $idn, $ibagian, $dateretur, $isupplier, $eremark);
        
        for($i=1;$i<=$jml;$i++){
            $imaterial      = $this->input->post('imaterial'.$i, TRUE);
            $isatuan        = $this->input->post('isatuan'.$i, TRUE);
            $nquantity      = $this->input->post('nquantity'.$i, TRUE);
            $edesc          = $this->input->post('edesc'.$i, TRUE);
            $this->mmaster->updatedetail($id, $ifaktur, $imaterial, $isatuan, $nquantity, $edesc);
        }
        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$idn);
        
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idn,
                    'id'        => $id,
                );
            }
        $this->load->view('pesan2', $data);  
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id         = $this->uri->segment(4);
        $iretur     = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $this->uri->segment(4),
            'isupplier'     => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->cek_data($id, $iretur, $isupplier)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $iretur, $isupplier)->result(),
            'supplier'      => $this->mmaster->cek_supplier($this->uri->segment(6)),
            'referensi'     => $this->mmaster->cek_referensi($this->uri->segment(4)),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $iretur     = $this->uri->segment(5);
        $isupplier  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $this->uri->segment(4),
            'isupplier'     => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->cek_data($id, $iretur, $isupplier)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $iretur, $isupplier)->result(),
            'supplier'      => $this->mmaster->cek_supplier($this->uri->segment(6)),
            'referensi'     => $this->mmaster->cek_referensi($this->uri->segment(4)),
            );
    
            $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iretur = $this->input->post('iretur');
        
        $this->mmaster->approve($iretur);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iretur
                );
            }
            $this->load->view('pesan', $data);  
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
}
/* End of file Cform.php */