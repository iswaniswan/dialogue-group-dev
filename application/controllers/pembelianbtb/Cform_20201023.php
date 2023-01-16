<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050202';

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
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');

        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
		echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto, $idepartemen, $username, $idcompany, $ilevel);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List'.$this->global['title'],
            'supplier'   => $this->mmaster->bacasupplier(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getiop(){
        $dfrom = $this->input->post('dfrom');
        if($dfrom){
             $tmp   = explode('-', $dfrom);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $dfrom1 = $year.'-'.$month.'-'.$day;
        }
        $dto = $this->input->post('dto');
        if($dto){
             $tmp   = explode('-', $dto);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $dto1 = $year.'-'.$month.'-'.$day;
        }
        $isupplier = $this->input->post('isupplier');
        $query     = $this->mmaster->getiop($dfrom1, $dto1, $isupplier);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_op." >".$row->i_op."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih No OP -- ".$c."</option>";
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

    public function satuan()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_satuan a");
            //$this->db->like("UPPER(i_satuan_code)", $cari);
            //$this->db->or_like("UPPER(e_satuan)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $icolor) {
                $filter[] = array(
                    'id' => $icolor->i_satuan,
                    'text' => $icolor->e_satuan,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getsup(){
        $idepartemen = $this->session->userdata('i_departement');
        $dfrom = $this->input->post('dfrom');
        if($dfrom){
             $tmp   = explode('-', $dfrom);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $dfrom1 = $year.'-'.$month.'-'.$day;
        }
        $dto = $this->input->post('dto');
        if($dto){
             $tmp   = explode('-', $dto);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $dto1 = $year.'-'.$month.'-'.$day;
        }
        $query     = $this->mmaster->getsup($dfrom1, $dto1, $idepartemen);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_supplier." >".$row->i_supplier." - ".$row->e_supplier_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Supplier -- ".$c."</option>";
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

    function getop(){
        header("Content-Type: application/json", true);
        $dfrom        = $this->input->post('dfrom');
        $dto          = $this->input->post('dto');
        $isupplier    = $this->input->post('isupplier');
        $iop          = $this->input->post('iop');

        $this->db->select("a.i_op, a.d_op");
        $this->db->from("tm_opbb a");
        $data = $this->db->get();
        $query= $this->mmaster->getOPitem($dfrom, $dto, $isupplier, $iop);

        $dataa = array(
            'data'    => $data->result_array(),
            'jmlitem' => $query->num_rows(),
            'brgop'   => $this->mmaster->getOPitem($dfrom, $dto, $isupplier, $iop)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function prosesacuanop(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $jml            = $this->input->post('jml', TRUE);
        //$jenispembayaran= $this->input->post('jenisp', TRUE);
        $iop            = $this->input->post('iop', TRUE);
        $isupplier      = $this->input->post('isupplier', TRUE);

        for($i=0;$i<=$jml;$i++){
            if($this->input->post('cek'.$i)=='cek'){ 
                //$isupplier      = $this->input->post('isupplier'.$i, TRUE);
                //$iop            = $this->input->post('iop'.$i, TRUE);
                $ipaymenttype   = $this->input->post('ipayment'.$i, TRUE);            
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);
                $cek            = $this->input->post('cek'.$i, TRUE);
                if($cek == 'cek'){
                    $op[] = $iop;
                    $data['op'] = array_unique($op);
                    
                    // 'data' => $this->mmaster->cek_header($isupplier, $iop, $ipaymenttype)->row(),
                    $data['data2'][]=$this->mmaster->cek_det($iop, $imaterial, $isupplier)->row();
                     $data['gudang']     =$this->mmaster->cek_gudang($iop)->result();
                    //'data2' => $this->mmaster->cek_det($iop, $imaterial, $isupplier)->result(),
                }
            }
        }
        $data['folder']     = $this->global['folder'];
        $data['title']      = "Tambah ".$this->global['title'];
        $data['title_list'] = 'List '. $this->global['title'];
        //$data['jenisp']=$jenispembayaran;
        //$data['getpkp']     =$this->mmaster->getpkp($isupplier)->row();
        $data['data']       =$this->mmaster->cek_header($isupplier, $iop)->row();


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformacuanop2', $data);
    }

    function getcode(){
         header("Content-Type: application/json", true);
        $igudang = $this->input->post('igudang');
        $this->db->select("a.*");
        $this->db->from("tr_master_gudang a");
        $this->db->where("UPPER(i_kode_master)", $igudang);
        $this->db->order_by('a.i_kode_master', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dsj = $this->input->post("dsj",TRUE);
        if($dsj){
             $tmp   = explode('-', $dsj);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $datesj = $year.'-'.$month.'-'.$day;
        }

        $dbtb = $this->input->post("dbtb",TRUE);
        if($dbtb){
             $tmp   = explode('-', $dbtb);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $datebtb = $year.'-'.$month.'-'.$day;
        }
        $ikode = $this->input->post("ikode",TRUE);
        $idepart = $this->input->post("idepart", TRUE);

        $this->db->trans_begin(); 
        $ibtb = $this->mmaster->runningnumber($yearmonth, $idepart);

        $imaterial          = $this->input->post('i_material');
        $isj                = $this->input->post("isj",TRUE);
        $isupplier          = $this->input->post("isupplier",TRUE);
        //$paymenttype        = $this->input->post('paymenttype',TRUE);
        $igudang            = $this->input->post('igudang',TRUE);
        $ipp                = $this->input->post('ipp',TRUE);
        $dpp                = $this->input->post('dpp',TRUE);
        $iop                = $this->input->post('iop',TRUE);
        $dop                = $this->input->post('dop',TRUE);
        if($dop){
             $tmp   = explode('-', $dop);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
            $dateop = $year.'-'.$month.'-'.$day;
        }
        // $tipepajak          = $this->input->post("tipepajak",TRUE);
        // $totppn             = str_replace(',','',$this->input->post("totppn",TRUE));
        // $pkp                = $this->input->post('pkp',TRUE);
        // $grandtot           = str_replace(',','',$this->input->post("grandtot",TRUE)); 
        $eremark            = $this->input->post("eremark",TRUE);
        // $vtotalop           = $this->input->post('vtotalop',TRUE);
        $dentry             = date("Y-m-d H:i:s");
        $jml                = $this->input->post("jml",true);
    
        //$this->mmaster->insertheader($ibtb, $imaterial, $isj, $datesj, $isupplier, $paymenttype, $tipepajak, $totppn, $pkp, $grandtot, $eremark, $vtotalop, $dentry); 
        $this->mmaster->insertheader($ibtb, $imaterial, $isj, $datesj, $isupplier, $eremark, $dentry, $igudang, $datebtb);   

        for($i=1;$i<=$jml;$i++){
            //$iop                    = $this->input->post("iop".$i,TRUE);
            $imaterial              = $this->input->post("imaterial".$i,TRUE);
            $qty                    = $this->input->post("nquantity".$i,TRUE);
            // $ndiscount              = $this->input->post("diskon".$i,TRUE);
            $vunitprice             = str_replace(',','',$this->input->post("hrgop".$i,TRUE));
            // $vunitpriceop           = str_replace(',','',$this->input->post("vpriceop".$i,TRUE));
            $iunit                 = $this->input->post("isatuan".$i,TRUE);
            $ikodemaster           = $this->input->post("ikodemaster".$i,TRUE);
            $iformula              = '0';
            $nformula_factor       = '0';
            $qtyeks                = $this->input->post("nquantityeks".$i,TRUE);
            $satuaneks             = $this->input->post("isatuaneks".$i,TRUE);

            if($qtyeks == null && $satuaneks==null){
                $qtyeks            = $this->input->post("nquantity".$i,TRUE);
                $satuaneks         = $this->input->post("isatuan".$i,TRUE);
            }else{
                $qtyeks            = $this->input->post("nquantityeks".$i,TRUE);
                $satuaneks         = $this->input->post("isatuaneks".$i,TRUE);
            }
            
            $querycek = $this->mmaster->cekqty($iop, $imaterial);
            if($querycek->num_rows() > 0){
                $querycek= $querycek->row();
                $qtyop = $querycek->op;
                $qtysj = $querycek->sj;
                $totsj = $qtysj+$qty;

                //var_dump($qtypp,$qtyop,$totop);die;
                if($totsj >= $qtyop){
                    $qty= $qtyop-$qtysj;
                    $fcomplete  = true;
                    $complete   = $this->mmaster->itemComplete($fcomplete,$iop,$imaterial);
                }
             }

             $query2 = $this->mmaster->cekstock($iop,$imaterial);
                if ($query2->num_rows() > 0){
                    $hasilrow6 = $query2->row();
                    $n_pemenuhan = $hasilrow6->n_pemenuhan;
                    $total = $n_pemenuhan-$qty;
                    $this->mmaster->updatestock($iop, $imaterial, $total);
                }
            
             //$this->mmaster->insertdetail($ibtb, $iop, $imaterial, $qty, $ndiscount, $vunitprice, $vunitpriceop, $iunit, $iformula, $nformula_factor, $i, $isj, $now);
             $this->mmaster->insertdetail($ibtb, $iop, $imaterial, $qty, $i, $isj, $dentry, $iunit, $ikodemaster, $ipp, $dpp, $dateop, $qtyeks, $satuaneks, $vunitprice);
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
                'kode'   => $ibtb,
            );
        }
        $this->load->view('pesan', $data);      
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibtb            = $this->uri->segment('4');
        //$isupplier       = $this->uri->segment('5');
        //$jenispembayaran = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'gudang'     => $this->mmaster->gudang($ibtb)->result(),
            'data'       => $this->mmaster->cek_dataapr($ibtb)->row(),
            'data2'      => $this->mmaster->cek_datadetapr($ibtb)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
    
    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }        
            $isj              = $this->input->post('isj', TRUE);
            $ibtb             = $this->input->post('ibtb', TRUE);
            // $grandtot      = str_replace(',','',$this->input->post("grandtot",TRUE));
            // $grandtotop    = str_replace(',','',$this->input->post("grandtotop",TRUE));
            // $grandtot      = $this->input->post('grandtot', TRUE);
            // $grandtotop    = $this->input->post('grandtotop', TRUE);            
            $igudang          = $this->input->post('igudang', TRUE);
            $dbtb             = $this->input->post('dbtb', TRUE);
            $ipp              = $this->input->post('ipp',TRUE);
            $dpp              = $this->input->post('dpp',TRUE);
            $iop              = $this->input->post('iop',TRUE);
            $dop              = $this->input->post('dop',TRUE);
            if($dbtb){
                 $tmp   = explode('-', $dbtb);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $yearmonth = $year.$month;
                $datebtb = $year.'-'.$month.'-'.$day;
            }

            $dsj              = $this->input->post('dsj', TRUE);
            if($dsj){
                 $tmp   = explode('-', $dsj);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $yearmonth = $year.$month;
                $datesj = $year.'-'.$month.'-'.$day;
            }

            $eremark          = $this->input->post('eremark', TRUE);
            $dentry           = date("Y-m-d H:i:s");

            $this->db->trans_begin(); 
            $this->mmaster->updateheader($isj, $dentry, $eremark, $igudang, $datesj, $datebtb, $ibtb);
            $jml               = $this->input->post('jml', TRUE);

            if ($isj != ''){
                $cekada = $this->mmaster->cek_dataheader($ibtb);
                if($cekada->num_rows() > 0){
                for($i=1;$i<=$jml;$i++){
                    $imaterial     = $this->input->post('imaterial'.$i, TRUE);
                    $nquantity     = $this->input->post('nquantity'.$i, TRUE);
                    //$vprice        = $this->input->post('vprice'.$i, TRUE);
                    $this->mmaster->updatedetail($nquantity, $imaterial, $isj);
                    }
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ibtb);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibtb
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function view(){

        $ibtb = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'gudang'     => $this->mmaster->gudang($ibtb)->result(),
            'data'       => $this->mmaster->cek_dataapr($ibtb)->row(),
            'data2'      => $this->mmaster->cek_datadetapr($ibtb)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approve(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibtb = $this->uri->segment('4');
        // $i_sj= $this->input->post('isj', TRUE);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_dataapr($ibtb)->row(),
            'data2'      => $this->mmaster->cek_datadetapr($ibtb)->result(),
            'gudang'     => $this->mmaster->gudang($ibtb)->result(),
        );        
        $this->Logger->write('Membuka Menu Approve BTB'.$this->global['title']);
    
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj        = $this->input->post('isj', TRUE);
        $ibtb        = $this->input->post('ibtb', TRUE);
        // $ikodemaster             = $this->input->post('ikodemaster', TRUE);
        $date     = date("Y-m-d H:i:s");

            $this->db->trans_begin(); 
            $this->mmaster->approve($isj, $date, $ibtb);
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isj
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function approvenext(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibtb = $this->uri->segment('4');
        // $i_sj= $this->input->post('isj', TRUE);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_dataapr($ibtb)->row(),
            'data2'      => $this->mmaster->cek_datadetapr($ibtb)->result(),
            'gudang'     => $this->mmaster->gudang($ibtb)->result(),
        );        
        $this->Logger->write('Membuka Menu Approve BTB'.$this->global['title']);
    
        $this->load->view($this->global['folder'].'/vformapprovenext', $data);
    }

    public function approvenextto(){
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibtb = $this->input->post('ibtb', true);
       
        $this->db->trans_begin();
        $this->mmaster->approvenext($ibtb);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $ibtb,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $ibtb = $this->input->post('ibtb');
        $this->mmaster->sendd($ibtb);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $ibtb = $this->input->post('ibtb');
        $this->mmaster->cancel_approve($ibtb);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $ibtb = $this->input->post('ibtb');
        $this->mmaster->change_approve($ibtb);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $ibtb = $this->input->post('ibtb');
        $this->mmaster->reject_approve($ibtb);
    }

    public function appr2(){
        header("Content-Type: application/json", true);
        $ibtb = $this->input->post('ibtb');
        $this->mmaster->appr2($ibtb);
    }


    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
            
        $ibtb        = $this->input->post('ibtb', TRUE);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ibtb);
            if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
            }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Cancel Bukti Penerimaan Barang '.$ibtb);
                    echo json_encode($data);
            }   
    }

//*

    function getmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->where("UPPER(a.i_material)", $imaterial);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_material, a.e_material_name ,b.i_satuan, b.e_satuan");
            $this->db->from("from tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->nama,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
   
    function save(){

    $data = check_role($this->i_menu, 1);
    if(!$data){
        redirect(base_url(),'refresh');
    }
        $query 	= $this->db->query("SELECT current_timestamp as c");
           $row   	= $query->row();
        $now	  = $row->c;

        $datesj = $this->input->post("dsj",true);
        $pkp                = $this->input->post('pkp',TRUE);
        // if($dsj){
        // 	 $tmp 	= explode('-', $dsj);
        // 	 $day 	= $tmp[0];
        // 	 $month = $tmp[1];
        // 	 $year	= $tmp[2];
        // 	 $yearmonth = $year.$month;
        // 	 $datesj = $year.'-'.$month.'-'.$day;
        // }
        if($pkp == TRUE){
            $pkp = "t";
        }else{
            $pkp = "f";
        }

        $this->db->trans_begin(); 
        $imaterial          = $this->input->post('i_material');
        $isj                = $this->input->post("isj",TRUE);
        $isupplier          = $this->input->post("isupplier",TRUE);
        $paymenttype        = $this->input->post('paymenttype',TRUE);
        $tipepajak          = $this->input->post("tipepajak",TRUE);
        $totppn             = str_replace(',','',$this->input->post("totppn",TRUE));
        
        $grandtot           = str_replace(',','',$this->input->post("grandtot",TRUE)); 
        $eremark            = $this->input->post("eremark",TRUE);
        //$vtotalop            = $this->input->post('vtotalop',TRUE);
        $dentry            = $now;
        $this->mmaster->insertheadertanpaop($isj, $datesj, $isupplier, $paymenttype, 
        $tipepajak, $totppn, $pkp, $grandtot, $eremark, $dentry);

        $jml                    = $this->input->post("jml",true);
        for($i=1;$i<=$jml;$i++){

            $imaterial              = $this->input->post("imaterial".$i,TRUE);
            $qty                    = $this->input->post("nquantity".$i,TRUE);
            $ndiscount              = $this->input->post("diskon".$i,TRUE);
            $vunitprice             = str_replace(',','',$this->input->post("vprice".$i,TRUE));
            // $vunitpriceop           = str_replace(',','',$this->input->post("vpriceop".$i,TRUE));
            $iunit                 = $this->input->post("isatuan".$i,TRUE);
            $iformula              = '0';
            $nformula_factor       = '0';
            
             $this->mmaster->insertdetailtanpaop($imaterial, $qty, $ndiscount, $vunitprice, 
                 $iunit, $iformula, $nformula_factor, $i, $isj, $now);
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
                'kode'      => $isj,
            );
        }
    $this->load->view('pesan', $data);      
    }
        
    function datamaterial2(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $data = $this->db->get();
            foreach($data->result() as  $imaterial){       
                    $filter[] = array(
                    'id' => $material->i_material,  
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  
    
    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier = $this->input->post('isupplier', TRUE);
        $jenispembayaran = $this->input->post('jenispembayaran', TRUE);
        // $acuanop = $this->input->post('acuanop', TRUE);
        // if($acuanop == TRUE){
			// $acuanop='TRUE';
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => '  List '.$this->global['title'],
            'jenisp'     => $jenispembayaran,
            'data'       => $this->mmaster->cek_data($isupplier, $jenispembayaran)->result(),
        );
        $this->Logger->write('Membuka Menu proses tambah Penerimaan Berdasarkan OP'.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminputacuanop', $data);
    // }else{
    //     $data = array(
    //         'folder' => $this->global['folder'],
    //         'title' => "Edit ".$this->global['title'],
    //         'title_list' => 'List '.$this->global['title'],
    //         'jenisp'    => $jenispembayaran,
    //         'getpkp'=>$this->mmaster->getpkp($isupplier)->row(),
        
    //         // 'data'   => $this->mmaster->cek_dataapr($i_sj)->row(),
    //         // 'data2'  => $this->mmaster->cek_datadetapr($i_sj)->result(),
    //     );
    //     $this->Logger->write('Membuka Menu proses tambah Penerimaan Tanpa Acuan OP '.$this->global['title']);
    //     $this->load->view($this->global['folder'].'/vformnoacuanop', $data);
    //     }
    }

    function datagudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_kode_master, e_nama_master");
            $this->db->from("tr_master_gudang");
            $this->db->like("UPPER(i_kode_master)", $cari);
            $this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $kodemaster){
                    $filter[] = array(
                    'id' => $kodemaster->i_kode_master,  
                    'text' => $kodemaster->i_kode_master.' - '.$kodemaster->e_nama_master
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_material, e_material_name");
            $this->db->from("tr_material");
            $this->db->like("UPPER(i_material)", $cari);
            $this->db->or_like("UPPER(e_material_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $imaterial){
                    $filter[] = array(
                    'id' => $imaterial->i_material,  
                    'text' => $imaterial->i_material.' - '.$imaterial->e_material_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function get_pkp_tipe_pajak($supp){
		$rows = array();
		if(isset($supp)) {
			$rows = $this->Mmaster->getpkp($supp)->result();
		}
		echo json_encode($rows);
	}
}
/* End of file Cform.php */
