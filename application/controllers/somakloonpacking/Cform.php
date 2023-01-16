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
    public $i_menu = '2080101';

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
    

    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'tahun'         => date('Y'),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dso        = $this->input->post("dso",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dso'           => $dso,
            'data'          => $this->mmaster->cek_data($dso)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    function dataproduct(){
        $filter = [];
        $iproduct = $this->uri->segment(4);
        if($this->input->get('q') != ''){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name
                                from tr_polacutting a, tr_product_wip b, tr_color c
                                where a.i_product = b.i_product_wip
                                and a.i_color = c.i_color
                                order by a.i_product", false); 
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id'    => $product->i_product,
                    'name'  => $product->e_product_namewip,
                    'text'  => $product->i_product.' - '.$product->e_product_namewip.'-'.$product->e_color_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getdetailproduct(){
        header("Content-Type: application/json", true);
        $eproductname = $this->input->post('eproductname');
        $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
            $this->db->join("tr_color c", "a.i_color = c.i_color");
            $this->db->where("a.i_product", $eproductname);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function datamaterial(){
        $filter = [];
        $eproductname = $this->uri->segment(4);
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select a.i_product, a.i_material, b.e_material_name 
                                from tr_polacutting a
                                join tr_material b on a.i_material=b.i_material
                                where a.i_product='$eproductname'");
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
        $ematerial = $this->input->post('ematerial');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
        $this->db->from("tr_material a");
        $this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        $this->db->where("UPPER(i_material)", $ematerial);
        $this->db->order_by('a.i_material', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dso          = $this->input->post('dso', TRUE);
        if($dso){
                 $tmp   = explode('-', $dso);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $dateso = $year.'-'.$month.'-'.$day;
        }
        
        $jml         = $this->input->post('jml', TRUE); 

        $istokopname = '';
        $month = $month;
        $year = (int)$year;
        $this->db->trans_begin();
        $data = $this->db->query("
            select i_stok_opname_makloonpacking
            from tt_stok_opname_makloonpacking
            where d_tahun = '$year' and d_bulan = '$month'
        ");
        if ($data->num_rows() > 0){
            foreach($data->result() as $row){
                $istokopname=$row->i_stok_opname_makloonpacking;
            }
            $this->mmaster->updateheaderso($istokopname, $dso, $yearmonth);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$istokopname);
        } else {
            $istokopname = $this->mmaster->runningnumber($yearmonth);
            $this->mmaster->insertheader($istokopname, $dso, $yearmonth);
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istokopname);
        }  
        $this->mmaster->deletedetail($istokopname);

            for($i=1;$i<=$jml;$i++){   
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);   
                $icolor         = $this->input->post('icolor'.$i, TRUE);
                //$imaterial      = $this->input->post('imaterial'.$i, TRUE);   
                $stokopname     = $this->input->post('stokopname'.$i, TRUE);
                $saldoawal      = $this->input->post('saldoawal'.$i, TRUE);   
                $saldoakhir     = $this->input->post('saldoakhir'.$i, TRUE);
                $nitemno        = $i;

                //$this->mmaster->insertdetail($istokopname, $iproduct, $icolor, $imaterial, $saldoawal, $saldoakhir, $stokopname, $nitemno);
                $this->mmaster->insertdetail($istokopname, $iproduct, $icolor, $saldoawal, $saldoakhir, $stokopname, $nitemno);
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

        $query = $this->mmaster->cek_data($dso)->result();
        foreach ($query as $row) {
            
        $spreadsheet  = new Spreadsheet;
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
          'borders' => [
                'bottom' => ['borderStyle' => Border::BORDER_THIN],
                'right' => ['borderStyle' => Border::BORDER_THIN],
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
        //foreach(range('A','I') as $columnID) {
        foreach(range('A','G') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->getActiveSheet()->mergeCells("A1:E1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:E2");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Stok Opname')
                      ->setCellValue('A2', "Periode $dso")
                      ->setCellValue('A4', 'KODE BARANG')
                      ->setCellValue('B4', 'NAMA BARANG')
                      ->setCellValue('C4', 'KODE WARNA')
                      ->setCellValue('D4', 'WARNA')
                      /*->setCellValue('E4', 'KODE BARANG BB')
                      ->setCellValue('F4', 'NAMA BARANG BB')
                      ->setCellValue('G4', 'SALDO AWAL')
                      ->setCellValue('H4', 'SALDO AKHIR')
                      ->setCellValue('I4', 'STOK OPNAME');*/
                      ->setCellValue('E4', 'SALDO AWAL')
                      ->setCellValue('F4', 'SALDO AKHIR')
                      ->setCellValue('G4', 'STOK OPNAME');

          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:I1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:I2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A4:I4');
*/
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:G1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:G2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A4:G4');

          $kolom = 5;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        /*->setCellValue('A' . $kolom, $row->kodewip)
                        ->setCellValue('B' . $kolom, $row->barangwip)*/
                        ->setCellValue('A' . $kolom, $row->kodebarang)
                        ->setCellValue('B' . $kolom, $row->namabarang)
                        ->setCellValue('C' . $kolom, $row->icolor)
                        ->setCellValue('D' . $kolom, $row->ecolor)
                        /*->setCellValue('E' . $kolom, $row->kode)
                        ->setCellValue('F' . $kolom, $row->barang)
                        ->setCellValue('G' . $kolom, $row->saldoawal)
                        ->setCellValue('H' . $kolom, $row->saldoakhir)
                        ->setCellValue('I' . $kolom, null);*/
                        ->setCellValue('E' . $kolom, $row->saldoawal)
                        ->setCellValue('F' . $kolom, $row->saldoakhir)
                        ->setCellValue('G' . $kolom, null);
                        
            //$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':I'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':G'.$kolom);
           
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        //$nama_file = "SO_Pengadaan_Periode_".$dso.".xls";
        $nama_file = "SO_Makloonpacking_Periode_".$dso.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        }
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

      public function approval(){
        $dso=$this->uri->segment(4);
        if($dso){
            $tmp   = explode('-', $dso);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dso = $year.'-'.$month.'-'.$day;
        }
        
        $month2 = (int)$month;
        $year2 = (int)$year;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approval ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'bulan'         => $month,
            'tahun'         => $year,
            'data'          => $this->mmaster->cek_dataheader($yearmonth)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($yearmonth)->result_array(),
        );
        $this->Logger->write('Membuka Menu Approval '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapproval', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }   
            $ikodeso        = $this->input->post('ikodeso', TRUE);
            $dbulan         = $this->input->post('dbulan', TRUE);
            $dtahun         = $this->input->post('dtahun', TRUE);

            $jml            = $this->input->post('jml', TRUE);

            $this->Logger->write('Approve Data '.$this->global['title'].' Kode : '.$ikodeso);
            $this->mmaster->updateheader($ikodeso, $dbulan, $dtahun);
        
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

     public function load(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dso   = $this->input->post('dso', TRUE);
        
        //$filename = "SO_Pengadaan_Periode_".$dso.".xls";
        $filename = "SO_Makloonpacking_Periode_".$dso.".xls";

        $config = array(
            //'upload_path'   => "./import/soproduksi/pengadaan",
            'upload_path'   => "./import/soproduksi/makloonpacking",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File SO Periode : '.$dso);
            //echo 'berhasil';
          if ($dso == "") {
                $param =  array(
                    'dso'    => $dso, 
                    'status' => 'gagal'
                );
            } else {
            $param =  array(
                'dso'        => $dso, 
                'status'     => 'berhasil'
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

     public function loadview(){
        
        $dso      = $this->uri->segment(4);

        //$filename = "SO_Pengadaan_Periode_".$dso.".xls";
        $filename = "SO_Makloonpacking_Periode_".$dso.".xls";
        //$inputFileName = './import/soproduksi/pengadaan/'.$filename;
        $inputFileName = './import/soproduksi/makloonpacking/'.$filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');
        $aray = array();
        for ($n=5; $n<=$hrow; $n++){
            $aray[] = array( 
                /*'kodewip'     => strtoupper($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue()),
                'barangwip'   => strtoupper($spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue()),*/
                'kodebarang'     => strtoupper($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue()),
                'namabarang'   => strtoupper($spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue()),
                'icolor'      => strtoupper($spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue()),
                'ecolor'      => strtoupper($spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue()),
                /*'kode'        => strtoupper($spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue()),
                'barang'      => strtoupper($spreadsheet->getActiveSheet()->getCell('F'.$n)->getCalculatedValue()),
                'saldoawal'   => strtoupper($spreadsheet->getActiveSheet()->getCell('G'.$n)->getCalculatedValue()),
                'saldoakhir'  => strtoupper($spreadsheet->getActiveSheet()->getCell('H'.$n)->getCalculatedValue()),
                'so'          => strtoupper($spreadsheet->getActiveSheet()->getCell('I'.$n)->getCalculatedValue()),*/
                'saldoawal'   => strtoupper($spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue()),
                'saldoakhir'  => strtoupper($spreadsheet->getActiveSheet()->getCell('F'.$n)->getCalculatedValue()),
                'so'          => strtoupper($spreadsheet->getActiveSheet()->getCell('G'.$n)->getCalculatedValue()),
            );
        }
         $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dso'           => $dso,
            'data'          => $aray,
        );


        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }
}
/* End of file Cform.php */