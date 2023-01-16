<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2051315XXXXX';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
        // $d = new DateTime();

        // $one_year = new DateInterval('P1M');
        // $one_year_ago = new DateTime();
        // $one_year_ago->sub($one_year);

        // // Output the microseconds.
        // $akhir = $d->format('d-m-Y');
        // $awal  = $one_year_ago->format('d-m-Y');

        // $data = array(
        //     'folder' => $this->global['folder'],
        //     'title' => "Tambah ".$this->global['title'],
        //     'title_list' => 'List '.$this->global['title'],
        //     'dfrom'         => $awal,
        //     'dto'           => $akhir,
        // );

        // $this->Logger->write('Membuka Menu View '.$this->global['title']);
        // $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom  = $this->input->post('dfrom');
        $dto  = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            // 'dfrom'         => $awal,
            // 'dto'           => $akhir,
        );
            
        echo $this->mmaster->data($this->i_menu,  $dfrom, $dto);
        // $this->load->view($this->global['folder'].'/vformlist', $data);
        
    }

    public function getop(){
        header("Content-Type: application/json", true);
        $ireff = $this->input->post('ireff');
        $ilokasi      = $this->session->userdata('i_lokasi');
        // $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->getop($ireff,$ilokasi)->row(),
            'detail' => $this->mmaster->getop_detail($ireff,$ilokasi)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function partner(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("* from tr_customer",false);
            $this->db->like("UPPER(i_customer)", $cari);
            $this->db->or_like("UPPER(e_customer_name)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $x) {
                $filter[] = array(
                    'id' => $x->i_customer,
                    'text' => $x->e_customer_name,

                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getpartnerreffedit(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $cari = str_replace(" ", "", $cari);
            $ipartner = $this->input->get('ipartner');
            $this->db->select(" x.i_sj, x.n_sisa, b.i_tujuan_kirim, to_char(b.d_sj, 'dd-mm-yyyy') as d_sj from (
              select x.i_sj, sum(x.n_sisa) as n_sisa from (
               select a.i_sj, a.i_product, a.i_color, a.n_sisa from tm_sj_keluarqc_item a
               inner join tm_sj_keluarqc b on (a.i_sj = b.i_sj)
               where b.i_tujuan_kirim = 'TES-M-6' and b.i_status = '5'
               group by a.i_sj, a.i_product, a.i_color, a.n_sisa  
              ) as x
              group by i_sj
            ) as x
             inner join tm_sj_keluarqc b on (x.i_sj = b.i_sj)
             where n_sisa > 0 and x.i_sj ilike '%$cari%'
             union all 
             select 'semua' as i_sj, '0' as n_sisa, 'all' as i_unit_jahit, 'refferensi' as d_sj
             order by d_sj asc",false);
            // $this->db->like("UPPER(i_kode_master)", $cari);
            // $this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $x) {
                $filter[] = array(
                    'id' => $x->i_sj,
                    'text' => $x->i_sj." - ". $x->d_sj,

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
        // $isubbagian     = $this->session->userdata('i_sub_bagian');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $username   = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');

        // var_dump($idcompany);
        // die;

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'area'=> $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
        );

        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    public function datareff(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("
                select a.i_sj, to_char(a.d_sj, 'dd-mm-yyyy') as d_sj, a.i_unit_jahit, b.e_unitjahit_name from tm_sj_keluar_makloonunitjahit a
                inner join tr_unit_jahit b on (a.i_unit_jahit = b.i_unit_jahit)
                /*where a.i_status = '5'*/");
            foreach ($data->result() as $data) {
                $filter[] = array(
                    'id'   => $data->i_sj,
                    'name' => $data->i_sj.' - '.$data->e_unitjahit_name,
                    'text' => $data->i_sj.' - '.$data->d_sj.' - '.$data->e_unitjahit_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getpartnerreff(){
        $ipartner = $this->input->post('ipartner');
        $query = $this->mmaster->getpartnerreff($ipartner);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_op_code." >".$row->i_op_code." || ".$row->d_op."</option>";
            }
            $kop  = "<option value=\"semua\">  Pilih OP  ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"kosong\">OP Tidak ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }


    public function getreff(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $ipartner = $this->input->post('ipartner');
        // $data = $this->db->query("
        //         select x.*, c.e_color_name from (
        //             select a.i_product, b.e_namabrg, a.i_color  from tr_polacutting a
        //             inner join tm_barang_wip b on (b.i_kodebrg = a.i_product)
        //             where a.i_product = '$iwip' and a.i_color = '$icolor'
        //             group by  a.i_product,b.e_namabrg, a.i_color 
        //         ) as x 
        //         left join tr_color c on (x.i_color = c.i_color)
        //         order by x.e_namabrg");
        // echo json_encode($data->result_array());

        $query  = array(
            'detail' => $this->mmaster->getsjdetail($isj, $ipartner)->result_array()
        );
        echo json_encode($query); 
    }

    public function getreffedit(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $ipartner = $this->input->post('ipartner');

        $query  = array(
            'detail' => $this->mmaster->getsjdetailedit($isj, $ipartner)->result_array()
        );
        echo json_encode($query); 
    }

    
    public function view(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_bonk = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'head' => $this->mmaster->cek_data($i_bonk)->row(),
            //'data2' => $this->mmaster->cek_datadet($i_bonk)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ido = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'head' => $this->mmaster->cek_data($ido)->row(),
            'data2' => $this->mmaster->cek_datadet($ido)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    // public function detail(){

    //     $data = check_role($this->i_menu, 3);
    //     if(!$data){
    //         redirect(base_url(),'refresh');
    //     }

    //     $i_bonk = $this->uri->segment('4');
    //     $i_product = $this->uri->segment('5');
    //     $i_color = $this->uri->segment('6');

    //     $data = array(
    //         'folder' => $this->global['folder'],
    //         'title' => "Edit ".$this->global['title'],
    //         'title_list' => 'List '.$this->global['title'],
    //         'datheader' => $this->mmaster->cek_datadetheader($i_bonk)->row(),
    //         'datdetail' => $this->mmaster->cek_datdetail($i_bonk, $i_product, $i_color)->result(),
    //     );

    //     $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

    //     $this->load->view($this->global['folder'].'/vformdetail', $data);
    // }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ido = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'head' => $this->mmaster->cek_data($ido)->row(),
            'data2' => $this->mmaster->cek_datadet($ido)->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dept           = trim($this->input->post('dept', TRUE));
        $dmasuk         = $this->input->post('dmasuk', TRUE);
        if($dmasuk){
             $tmp   = explode('-', $dmasuk);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $thbl = $year.$month;
             $ddo = $year.'-'.$month.'-'.$day;
        }

        // $nodok      = $this->input->post('nodok', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $icustomer      = $this->input->post('ipartner', TRUE);
        $ireff          = $this->input->post('i_reff', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $lok            = $this->session->userdata('i_lokasi');
        $ido            = $this->mmaster->runningnumber($thbl, $lok);
        $vdo         = 0;
        
        $this->db->trans_begin();
        for($j=1;$j<=$jml;$j++){
            $iproduct           = $this->input->post('iproduct'.$j, TRUE);
            $eproduct           = $this->input->post('eproductname'.$j, TRUE);
            $icolor             = $this->input->post('icolor'.$j, TRUE);
            $qty                = $this->input->post('npemenuhan'.$j, TRUE);
            $qtyorder           = $this->input->post('nquantity'.$j, TRUE);
            $eremarkh           = $this->input->post('eremarkh'.$j, TRUE);
            $cek                = $this->input->post('cek'.$j, TRUE);
            $stok               = $this->mmaster->getstock($lok,$iproduct,$icolor);
            $stokupdate         = $stok-$qty;
            
            $vprice             = $this->input->post('vprice'.$j, TRUE);
            $vgros              = $vprice * $qty;
            $vdo                = $vgros + $vdo;
            if($cek == "on"){   
                $this->mmaster->updatestock($lok,$iproduct,$icolor,$stokupdate);
                $this->mmaster->insertdetail($ido, $ireff, $iproduct, $eproduct, $icolor, $vprice, $qty, $vgros, $eremarkh, $j,$qtyorder);
            }
            
        }
        $this->mmaster->updateop($ireff);
        $this->mmaster->insertheader($ido, $ddo, $icustomer, $vdo, $dept, $eremark, $ireff);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ido);
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $ido,
                );
            }
            $this->load->view('pesan', $data);
    }

    // public function getdetailsj(){
    //     header("Content-Type: application/json", true);
    //     $isj  = $this->input->post('isj', FALSE);
    //     $ipartner  = $this->input->post('ipartner', FALSE);
    //     //var_dump($isjkm, $isjmm, $gudang);
    //     $query  = array(
    //         'detail' => $this->mmaster->getdetailsj($isj, $ipartner)->result_array()
    //     );
    //     echo json_encode($query);  
    // }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idept  = $this->input->post('idept', TRUE);
        $dmasuk       = $this->input->post('dmasuk', TRUE);
        if($dmasuk){
             $tmp   = explode('-', $dmasuk);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $ddo = $year.'-'.$month.'-'.$day;
        }

        $ireff          = $this->input->post('ireff', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $ido            = $this->input->post('ido', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $lok            = $this->session->userdata('i_lokasi');



        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ido);

        
        $this->mmaster->deletedetail($ido);
        $vdo = '0';
        //var_dump($ibonk, $ikodemaster, $dbonk, $eremark);
        //die();
        for($j=1;$j<=$jml;$j++){
            $iproduct           = $this->input->post('iproduct'.$j, TRUE);
            $eproduct           = $this->input->post('eproductname'.$j, TRUE);
            $icolor             = $this->input->post('icolor'.$j, TRUE);
            $qty                = $this->input->post('qty'.$j, TRUE);
            $qtyorder           = $this->input->post('qtyorder'.$j, TRUE);
            $qtyprev           = $this->input->post('qtyprev'.$j, TRUE);
            $eremarkh           = $this->input->post('eremarkh'.$j, TRUE);
            $vprice             = $this->input->post('vprice'.$j, TRUE);
            $cek                = $this->input->post('cek'.$j, TRUE);
            $vgros              = $vprice * $qty;
            $vdo                = $vgros + $vdo;
            $stok               = $this->mmaster->getstock($lok,$iproduct,$icolor);
            if($cek == "on"){  
                if($qtyprev>$qty){
                    $qtybaru    = $qtyprev- $qty;
                    $stokupdate = $stok +$qtybaru;
                    $this->mmaster->updatestock($lok,$iproduct,$icolor,$stokupdate);
                    // $this->mmaster->updatestock($lok,$iproduct,$icolor,$stokupdate);
                    // $this->mmaster->insertdetail($ido, $ireff, $iproduct, $eproduct, $icolor, $vprice, $qty, $vgros, $eremarkh, $j,$qtyorder);
                }else if($qtyprev<$qty) {
                    $qtybaru    = $qty - $qtyprev;
                    $stokupdate = $stok - $qtybaru;
                    $this->mmaster->updatestock($lok,$iproduct,$icolor,$stokupdate);
                    
                }
                // $this->mmaster->updatestock($lok,$iproduct,$icolor,$stokupdate);
                $this->mmaster->insertdetail($ido, $ireff, $iproduct, $eproduct, $icolor, $vprice, $qty, $vgros, $eremarkh, $j,$qtyorder);
            }
  
            
        }
        $this->mmaster->updateheader($idept, $ddo, $ireff, $eremark, $ido);


            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $ido,
                );
            }
            $this->load->view('pesan', $data);
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->change($kode);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->reject($kode);
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $isj   = $this->input->post('ido');
        // $i_reff       = $this->input->post('ireff[]',TRUE);
        // $i_color      = $this->input->post('icolor[]',TRUE);
        // $i_product   = $this->input->post('iproduct[]',TRUE);
        // $n_qtymasuk   = $this->input->post('qtymasuk[]',TRUE);
        // $e_desc       = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        //$this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);

        $this->mmaster->approve($isj);
        //var_dump($ibonk, $ikodemaster, $dbonk, $eremark);
        //die();
        // $no=0;
        // foreach ($i_reff as $ireff) {     
        //         $ireff       = $ireff;
        //         $icolor      = $i_color[$no];
        //         $iproduct    = $i_product[$no];
        //         $qtymasuk    = $n_qtymasuk[$no];
        //         $edesc       = $e_desc[$no];
               
        //         $this->mmaster->updatedetailkeluar($ireff, $iproduct, $icolor, $qtymasuk); 
        //         $no++;
        // }
        //die();
        
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

     public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ido   = $this->input->post('ido');
        $this->db->trans_begin();
        $data = $this->mmaster->batal($ido);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel DO  '.$ido);
            echo json_encode($data);
        }
    }
}

/* End of file Cform.php */
