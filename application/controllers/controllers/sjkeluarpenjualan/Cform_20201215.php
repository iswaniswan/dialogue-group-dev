<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050205';

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

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'lokasi'        => $lokasi,
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getpic(){
       
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getpartner($cari);
            foreach($data->result() as  $iper){
                    $filter[] = array(
                    'id'   => $iper->partner,  
                    'text' => $iper->epartner,
                );
            }          
            echo json_encode($filter);
    }

    public function getmemobaru(){
        $ipelanggan = $this->input->post('ipelanggan');
    
        $query = $this->mmaster->getmemobaru($ipelanggan);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                //$c.="<option value=".$row->i_faktur_code." >".$row->i_faktur_code." || ".$row->d_faktur."</option>";
                $c.="<option value=".$row->i_permintaan." >".$row->i_permintaan." || ".$row->d_pp."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih No Referensi -- ".$c."</option>";
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

    function getdataitemmemo(){
        header("Content-Type: application/json", true);
        $i_memo        = $this->input->post('i_memo');

        $this->db->select("* from tm_permintaanpengeluaranbb_detail a where a.i_permintaan = '$i_memo'");
        $data = $this->db->get();

        $query   = $this->mmaster->getdataitemmemo($i_memo);

        $dataa = array(
            'data'       => $data->result_array(),
            'jmlitem'    => $query->num_rows(),
            'dataitem'   => $this->mmaster->getdataitemmemo($i_memo)->result_array(),
        );
        echo json_encode($dataa);
    }

//cek
    public function getpicq(){
        $tujuankeluar = $this->input->post('tujuankeluar');
        if($tujuankeluar == 'internal'){
            $query = $this->mmaster->getpicIN();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_sub_bagian." >".$row->e_sub_bagian."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih -- ".$c."</option>";
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
        }else {
            $query = $this->mmaster->getpicEK();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_supplier." >".$row->e_supplier_name."</option>";
                    
                }
                $kop  = "<option value=\"\"> -- Pilih -- ".$c."</option>";
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
        
    }
// end test ------------------------------------------------------------------    

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsjk   = $this->input->post("dsjk",true);
        if($dsjk){
                 $tmp   = explode('-', $dsjk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datesjk = $year.'-'.$month.'-'.$day;
        }

        $dmemo   = $this->input->post("dmemo",true);
        if($dmemo){
                 $tmp   = explode('-', $dmemo);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $datememo = $year.'-'.$month.'-'.$day;
        }

        $istore        = $this->input->post('istore', TRUE);
        $ilokasi       = $this->input->post('ilokasi', TRUE);
        $imemo         = $this->input->post('i_memo', TRUE);
        $ipartner       = $this->input->post('edept', TRUE);
        $remark        = $this->input->post('eremark', TRUE);
        $nosjkeluar    = $this->mmaster->runningnumberkeluar($yearmonth, $ilokasi);
        $jml           = $this->input->post('jml', TRUE); 
       
        $i_product     = $this->input->post('iproduct[]', TRUE);
        $n_quantityp   = $this->input->post('nquantity[]', TRUE);
        $n_quantity    = $this->input->post('nquantitysj[]', TRUE);
        $i_satuan      = $this->input->post('isatuan[]', TRUE); 
        $e_desc        = $this->input->post('edesc[]', TRUE);        

        $this->db->trans_begin();
        $this->mmaster->insertheader($nosjkeluar, $imemo, $datesjk, $datememo, $ipartner, $istore, $remark);

            $no=0;
            foreach ($i_product as $iproduct) {
                $iproduct   = $iproduct;
                $nquantityp = $n_quantityp[$no];
                $nquantity  = $n_quantity[$no];
                $isatuan    = $i_satuan[$no];
                $edesc      = $e_desc[$no];

                $sisa       = $nquantityp - $nquantity;
                $pemenuhan  = $this->mmaster->ceksjkeluar2($imemo, $iproduct, $sisa, $nquantity);
                $npemenuhan = $pemenuhan + $nquantity;

                $this->mmaster->insertdetail($nosjkeluar, $iproduct, $nquantityp, $nquantity, $isatuan, $edesc, $no);
                $this->mmaster->updatepermintaan($imemo, $iproduct, $sisa, $nquantity, $npemenuhan);

                $no++;
            // for($i=1;$i<=$jml;$i++){
            // }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nosjkeluar);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nosjkeluar,
                );
        }
    $this->load->view('pesan', $data);      
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

        $isj = $this->uri->segment('4');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'head'          => $this->mmaster->baca_header($isj)->row(),
            'detail'        => $this->mmaster->baca_detail($isj)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $nosjkeluar   = $this->input->post("isj", TRUE);
        $dsjk         = $this->input->post("dsjk", TRUE);
        if($dsjk){
                 $tmp   = explode('-', $dsjk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $datesjk = $year.'-'.$month.'-'.$day;
        }

        $dmemo   = $this->input->post("dmemo", TRUE);
        if($dmemo){
                 $tmp   = explode('-', $dmemo);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $datememo = $year.'-'.$month.'-'.$day;
        }

        $istore        = $this->input->post('istore', TRUE);
        $imemo         = $this->input->post('i_memo', TRUE);
        $ipartner      = $this->input->post('edept', TRUE);
        $remark        = $this->input->post('eremark', TRUE);
        $jml           = $this->input->post('jml', TRUE); 
       
        //$i_material      = $this->input->post('imaterial[]', TRUE);
        $i_product       = $this->input->post('iproduct[]', TRUE);
        $n_quantityp     = $this->input->post('nquantityp[]', TRUE);
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);
        //var_dump($nosjkeluar);
        $this->db->trans_begin();
        $this->mmaster->updateheader($nosjkeluar, $datesjk, $istore, $imemo, $datememo, $ipartner, $remark);
        $this->mmaster->deletedetail($nosjkeluar);

            $no=0;
            foreach ($i_product as $iproduct) {
                $iproduct   = $iproduct;
                $nquantityp = $n_quantityp[$no];
                $nquantity  = $n_quantity[$no];
                $isatuan    = $i_satuan[$no];
                $edesc      = $e_desc[$no];
                $sisa       = $nquantityp - $nquantity;
                $npemenuhan = $nquantity;

                $this->mmaster->insertdetail($nosjkeluar, $iproduct, $nquantityp, $nquantity, $isatuan, $edesc, $no);
                $this->mmaster->updatepermintaan($imemo, $iproduct, $sisa, $nquantity, $npemenuhan);

                $no++;
            }
            //for($i=1;$i<=$jml;$i++){
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' No SJ : '.$nosjkeluar.' Gudang :'.$istore);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nosjkeluar,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->sendd($isj);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->cancel_approve($isj);
    }

    public function view(){

        $isj = $this->uri->segment('4');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'head'          => $this->mmaster->baca_header($isj)->row(),
            'detail'        => $this->mmaster->baca_detail($isj)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj = $this->uri->segment('4');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'head'          => $this->mmaster->baca_header($isj)->row(),
            'detail'        => $this->mmaster->baca_detail($isj)->result(),
        );
        $this->Logger->write('Membuka Menu approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isj = $this->input->post('isj', true);
       
        $this->db->trans_begin();
        $this->mmaster->approve($isj);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->change_approve($isj);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->reject_approve($isj);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isj = $this->input->post('isj', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isj);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Penjualan Bahan Baku' . $isj);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */