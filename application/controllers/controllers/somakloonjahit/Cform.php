<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2080201';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        

        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    public function index(){
        // $data = array(
        //     'folder'    => $this->global['folder'],
        //     'title'     => $this->global['title']
        // );
        // $this->Logger->write('Membuka Menu '.$this->global['title']);
        // $this->load->view($this->global['folder'].'/vformmain', $data);
        $d = new DateTime();

        $one_year = new DateInterval('P1M');
        $one_year_ago = new DateTime();
        $one_year_ago->sub($one_year);

        // Output the microseconds.
        $akhir = $d->format('d-m-Y');
        // $awal  = $one_year_ago->format('d-m-Y');
        $awal  = $d->format('01-m-Y');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom  = $this->input->post('dfrom');
        $dto  = $this->input->post('dto');

        // if($supplier==''){
        //     $supplier=$this->uri->segment(4);
        // }
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = trim($this->session->userdata('i_departement'));
        $ilevel      = $this->session->userdata('i_level');
            
        echo $this->mmaster->data($this->i_menu,  $dfrom, $dto, $username, $idcompany, $idepartemen, $ilevel);
    }

    public function index2()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'partner'       => $this->mmaster->getpartner(),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function gudang(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tr_master_gudang');
            $this->db->where('i_kode_jenis','JNG0006');
            //$this->db->like("UPPER(i_kode_master)", $cari);
            //$this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $itype) {
                $filter[] = array(
                    'id' => $itype->i_kode_master,
                    'text' => $itype->e_nama_master,

                );
            }

            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getbarang(){
        $ikodemaster = $this->input->post('ikodemaster');
        $query = $this->mmaster->getbarang($ikodemaster);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_material." >".$row->i_material."-".$row->e_material_name."</option>";
            }
            $kop  = "<option value=\"BRG\" selected>  Semua Barang  ".$c."</option>";
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

     public function load(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $partner    = $this->input->post('partner', TRUE);
        $dso    = $this->input->post('dso', TRUE);
        $filename = "SO_MJ_".$partner."_".$dso.".xls";

        $config = array(
            'upload_path'   => "./import/soproduksi/makloonjahit",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Tanggal : '.$dso. "Makloon".$partner);
            //echo 'berhasil';
            
            if ($dso=="" || $dso == "null" || $dso == null ) {
                $param =  array(
                    'dso' => $dso,
                    'partner' => $partner,
                    'status' => 'gagal'
                );
            } else {
                $param =  array(
                    'dso' => $dso,
                    'partner' => $partner,
                    'status' => 'berhasil'
                );
            }
            echo json_encode($param);
        }else{
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
            //echo 'gagal';
        }
    }


    public function tambah(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dso    = $this->input->post("dso",true);
        $partner    = $this->input->post("partner",true);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dso'           => $dso,
            'partner'       => $this->mmaster->getpartnerbyid($partner),
            'data2'         => $this->mmaster->cek_datadet($dso,$partner)->result_array(),
        );

        //var_dump($this->mmaster->getpartnerbyid($partner));
        //die();
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        //var_dump($this->mmaster->cek_datadet($dso)->result_array());
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function loadview(){
        
        $dso    = $this->uri->segment(4);
        $partner    = $this->uri->segment(5);
       // $filename = "SO_QC-SET_".$dso.".xls";
        $filename = "SO_MJ_".$partner."_".$dso.".xls";
        //$e_bulan =mbulan($bulan);

        //var_dump($filename);
        $inputFileName = './import/soproduksi/makloonjahit/'.$filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $aray = array();
        for ($n=6; $n<=$hrow; $n++){
            $kodewip = strtoupper($spreadsheet->getActiveSheet()->getCell('A'.$n)->getValue());
            $icolor = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getValue();
            $ambilsaldo = $this->mmaster->cek_datadet_upload($dso, $kodewip,$icolor,$partner )->row();
            // var_dump($ambilsaldo);
            // die();
            // break;
            $saldoawal = $ambilsaldo->saldoawal;
            $saldoakhir = $ambilsaldo->saldoakhir;

            $so = $spreadsheet->getActiveSheet()->getCell('G'.$n)->getCalculatedValue();
            $selisih = $so - abs($saldoakhir);
            // var_dump($so, $saldoakhir, $selisih);
            // die();
            $aray[] = array( 
                'kodewip'     => $kodewip,
                'barangwip'   => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getValue(),
                'ecolor'      => $spreadsheet->getActiveSheet()->getCell('D'.$n)->getValue(),
                'icolor'      => $icolor,
                'saldoawal'   => $saldoawal,
                'saldoakhir'  => $saldoakhir,
                'so'          => $so,
                'selisih'     => $selisih,
            );
        }
         $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dso'           => $dso,
            'partner'       => $this->mmaster->getpartnerbyid($partner),
            'data2'         => $aray,
        );


        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function datamaterial(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("
                select a.*,b.e_satuan from tr_material a, tr_satuan b 
                where a.i_satuan_code=b.i_satuan_code 
                and (a.i_kode_kelompok='KTB0004' or a.i_kode_kelompok='KTB0005')
                and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%') order by a.i_material");
            foreach ($data->result() as $material) {
                $filter[] = array(
                    'id'   => $material->i_material,
                    'name' => $material->e_material_name,
                    'text' => $material->i_material.' - '.$material->e_material_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerialname = $this->input->post('ematerialname');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
        $this->db->from("tr_material a");
        $this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        $this->db->where("UPPER(i_material)", $ematerialname);
        $this->db->order_by('a.i_material', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function datawip(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("
                select x.*, c.e_color_name from (
                    select a.i_product, b.e_namabrg, a.i_color  from tr_polacutting a
                    inner join tm_barang_wip b on (b.i_kodebrg = a.i_product)
                    group by  a.i_product,b.e_namabrg, a.i_color 
                ) as x 
                left join tr_color c on (x.i_color = c.i_color)
                where x.e_namabrg ilike '%$cari%' or x.i_product ilike '%$cari%'
                order by x.e_namabrg");
            foreach ($data->result() as $data) {
                $filter[] = array(
                    'id'   => $data->i_product."|".$data->i_color,
                    'name' => $data->e_namabrg,
                    'text' => $data->i_product.' - '.$data->e_namabrg.' - '.$data->e_color_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getwip(){
        header("Content-Type: application/json", true);
        $iwip = $this->input->post('iwip');
        $icolor = $this->input->post('icolor');
        $data = $this->db->query("
                select x.*, c.e_color_name from (
                    select a.i_product, b.e_namabrg, a.i_color  from tr_polacutting a
                    inner join tm_barang_wip b on (b.i_kodebrg = a.i_product)
                    where a.i_product = '$iwip' and a.i_color = '$icolor'
                    group by  a.i_product,b.e_namabrg, a.i_color 
                ) as x 
                left join tr_color c on (x.i_color = c.i_color)
                order by x.e_namabrg");
        echo json_encode($data->result_array());
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $partner    = $this->input->post('partner', TRUE); 
        $dso          = $this->input->post('dso', TRUE);
        if($dso){
                 $tmp   = explode('-', $dso);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $dso = $year.'-'.$month.'-'.$day;
        }
        
        $jml            = $this->input->post('jml', TRUE);
        $lokasi     = $this->session->userdata('i_lokasi');

        $istokopname = '';
        $this->db->trans_begin();
        $data = $this->db->query("
            select i_stok_opname_makloonjahit
            from tt_stok_opname_makloonjahit
            where i_periode = '$yearmonth' and partner = '$partner'
        ");
        // var_dump($lokasi, $istokopname, $yearmonth);
        // die();
        if ($data->num_rows() > 0){
            foreach($data->result() as $row){
                $istokopname=$row->i_stok_opname_makloonjahit;
            }
            $this->mmaster->updateheaderso($istokopname, $dso, $yearmonth);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$istokopname);
        } else {
            $istokopname = $this->mmaster->runningnumber($yearmonth, $partner, $lokasi);
            $this->mmaster->insertheader($istokopname, $dso, $yearmonth, $partner, $year, $month);
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istokopname);
        }  
        
        // var_dump($istokopname);
        // die();
        $this->mmaster->deletedetail($istokopname);
            for($i=1;$i<=$jml;$i++){   
                $iwip      = $this->input->post('iwip'.$i, TRUE);
                if ($iwip != NULL) {
                    $icolor      = $this->input->post('icolor'.$i, TRUE);
                    $saldoawal     = $this->input->post('saldoawal'.$i, TRUE);
                    $saldoakhir     = $this->input->post('saldoakhir'.$i, TRUE);
                    $stokopname     = $this->input->post('stokopname'.$i, TRUE);
                    $nitemno        = $i;
                    //var_dump($istokopname, $iwip,$icolor, $saldoawal, $saldoakhir, $stokopname, $nitemno, $partner);
                    $this->mmaster->insertdetail($istokopname, $iwip,$icolor, $saldoawal, $saldoakhir, $stokopname, $nitemno, $partner);
                }            
               
            }
            //die();
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $istokopname,
                );
        }
    $this->load->view('pesan', $data); 
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $dso = ($this->input->post('dso',TRUE) != '' ? $this->input->post('dso',TRUE) : $this->uri->segment(4));
        $partner = ($this->input->post('partner',TRUE) != '' ? $this->input->post('partner',TRUE) : $this->uri->segment(5));
        $query = $this->mmaster->cek_datadet($dso, $partner)->result();
        $epartner = $this->mmaster->getpartnerbyid($partner)->e_unitjahit_name;

        foreach ($query as $row) {
            
        $spreadsheet = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $conditional3 = new Conditional();
        $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
          [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] ); 

      $sharedStyle1->applyFromArray(
          [
          'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          ],
          'borders' => [
            'bottom' => ['borderStyle' => Border::BORDER_THIN],
            'right' => ['borderStyle' => Border::BORDER_THIN],
          ],
        ]
      );

        $sharedStyle2->applyFromArray(
            [
                'font'=>[
                    'name'  => 'Arial',
                    'bold'  => false,
                    'italic'=> false,
                    'size'  => 10
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        );

        $sharedStyle3->applyFromArray(
            [
                'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        );
        $spreadsheet->getDefaultStyle()
        ->getFont()
        ->setName('Calibri')
        ->setSize(9);
        foreach(range('A','H') as $columnID) {
          $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
          $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Stok Opname')
                      ->setCellValue('A2', "Stok Opname Makloon Jahit : $epartner")
                      ->setCellValue('A3', "Tanggal SO : $dso")
                      ->setCellValue('A5', 'Kode Barang')
                      ->setCellValue('B5', 'Nama Barang')
                      ->setCellValue('C5', 'Kode Warna')
                      ->setCellValue('D5', 'Warna')
                      ->setCellValue('E5', 'Saldo Awal')
                      ->setCellValue('F5', 'Saldo Akhir')
                      ->setCellValue('G5', 'Jumlah SO')
                      ->setCellValue('H5', 'Selisih');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:H3');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:H5');

          $kolom = 6;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $row->kodewip)
                        ->setCellValue('B' . $kolom, $row->barangwip)
                        ->setCellValue('C' . $kolom, $row->icolor)
                        ->setCellValue('D' . $kolom, $row->ecolor)
                        ->setCellValue('E' . $kolom, $row->saldoawal)
                        ->setCellValue('F' . $kolom, $row->saldoakhir)
                        ->setCellValue('G' . $kolom, $row->so)
                        ->setCellValue('H' . $kolom, $row->selisih);
            //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':H'.$kolom);
           
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "SO_MJ_".$partner."_".$dso.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
          // Proses file excel    
          /*header('Content-Type: application/vnd.ms-excel');    
          header('Content-Disposition: attachment; filename='.$nama_file.''); // Set nama file excel nya    
          header('Cache-Control: max-age=0');
          $writer = IOFactory::createWriter($spreadsheet, 'Excel5');
          $writer->save('php://output','w');*/
        }
      }

    

    public function approval(){
        $iso = $this->uri->segment(4);
        $partner = $this->uri->segment(5);
        // if($dso){
        //     $tmp   = explode('-', $dso);
        //     $day   = $tmp[0];
        //     $month = $tmp[1];
        //     $year  = $tmp[2];
        //     $yearmonth = $year.$month;
        //     $dso = $year.'-'.$month.'-'.$day;
        // }
        
        // $month2 = (int)$month;
        // $year2 = (int)$year;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approval ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'data'          => $this->mmaster->cek_dataheader($iso, $partner)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($iso, $partner)->result_array(),
            
        );
        $this->Logger->write('Membuka Menu Approval '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapproval', $data);
    }

      public function approve(){
          $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Approval ".$this->global['title'],
            'tahun'     => date('Y'),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
      }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }   
            $ikodeso        = $this->input->post('ikodeso', TRUE);
            $periode    = $this->input->post('periode', TRUE);
            $partner    = $this->input->post('partner', TRUE);
            $jml            = $this->input->post('jml', TRUE);

            $this->Logger->write('Approve Data '.$this->global['title'].' Kode : '.$ikodeso.' Periode : '.$periode.' Partner : '.$partner);
            $this->mmaster->updateheader($ikodeso, $periode, $partner);
                
            for($i=1;$i<=$jml;$i++){  
                    //$ikodeso    = $this->input->post('ikodeso'.$i, TRUE);
                    //$imaterial  = $this->input->post('imaterial'.$i, TRUE);
                    $this->mmaster->updatedetail($ikodeso, $partner);
            }
        
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodeso,
                );
            }
    $this->load->view('pesan', $data); 
    }

    public function view(){
        $iso = $this->uri->segment(4);
        $partner = $this->uri->segment(5);
        // if($dso){
        //     $tmp   = explode('-', $dso);
        //     $day   = $tmp[0];
        //     $month = $tmp[1];
        //     $year  = $tmp[2];
        //     $yearmonth = $year.$month;
        //     $dso = $year.'-'.$month.'-'.$day;
        // }
        
        // $month2 = (int)$month;
        // $year2 = (int)$year;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approval ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'data'          => $this->mmaster->cek_dataheader($iso, $partner)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($iso, $partner)->result_array(),
            
        );
        $this->Logger->write('Membuka Menu Approval '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */