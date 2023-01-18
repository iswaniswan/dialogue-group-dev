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
    public $i_menu = '2090108';

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

        $iperiodebl  = $this->input->post("iperiodebl",true);
        $iperiodeth  = $this->input->post("iperiodeth",true);
        $iperiode    = $iperiodeth.$iperiodebl;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'bulan'         => $iperiodebl,
            'tahun'         => $iperiodeth,
            'data'          => $this->mmaster->cek_data($iperiode)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function datamaterial(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select a.*,b.e_satuan from tr_material a, tr_satuan b where a.i_satuan_code=b.i_satuan_code and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%') order by a.i_material");
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

        $this->db->trans_begin();
        $istokopname = $this->mmaster->runningnumber($yearmonth);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istokopname);
        $this->mmaster->insertheader($istokopname, $dateso, $yearmonth);

            for($i=1;$i<=$jml;$i++){   
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);   
                $stokopname     = $this->input->post('stokopname'.$i, TRUE);
                $nitemno        = $i;

                $this->mmaster->insertdetail($istokopname, $imaterial, $stokopname, $nitemno);
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
        $bulan = ($this->input->post('bulan',TRUE) != '' ? $this->input->post('bulan',TRUE) : $this->uri->segment(4));
        $tahun = ($this->input->post('tahun',TRUE) != '' ? $this->input->post('tahun',TRUE) : $this->uri->segment(5));

        $iperiode = $tahun.$bulan;

        $query = $this->mmaster->cek_data($iperiode)->result();
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

          [/*'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['argb' => 'FFFFFF00'],
          ],*/
           /*'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],*/
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
        foreach(range('A','E') as $columnID) {
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
                      ->setCellValue('A2', "Periode Bulan : $bulan Tahun : $tahun")
                      ->setCellValue('A4', 'KODE BARANG WIP')
                      ->setCellValue('B4', 'NAMA BARANG WIP')
                      ->setCellValue('C4', 'KODE BARANG BB')
                      ->setCellValue('D4', 'NAMA BARANG BB')
                      ->setCellValue('E4', 'STOK OPNAME');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:E1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:E2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A4:E4');

          $kolom = 5;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $row->i_product)
                        ->setCellValue('B' . $kolom, $row->e_product_name)
                        ->setCellValue('C' . $kolom, $row->i_material)
                        ->setCellValue('D' . $kolom, $row->e_material_name)
                        ->setCellValue('E' . $kolom, null);
                        
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':E'.$kolom);
           
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "SO_Cutting_Periode_".$iperiode.".xls";
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
        $iperiodebl=$this->uri->segment(4);
        $iperiodeth=$this->uri->segment(5);

        $iperiode   = $iperiodeth.$iperiodebl;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approval ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'bulan'         => $iperiodebl,
            'tahun'         => $iperiodeth,
            'data'          => $this->mmaster->cek_dataheader($iperiodebl, $iperiodeth)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($iperiodebl, $iperiodeth)->result(),
            
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

        $iperiodebl   = $this->input->post('iperiodebl', TRUE);
        $iperiodeth   = $this->input->post('iperiodeth', TRUE);
        $iperiode     = $iperiodeth.$iperiodebl;

        $filename = "SO_Cutting_Periode_".$iperiode.".xls";

        $config = array(
            'upload_path'   => "./import/soproduksi/cutting",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File SO Periode : '.$iperiode);
            //echo 'berhasil';
          if ($iperiodeth == "" || $iperiodebl == "" ) {
                $param =  array(
                    'iperiodeth' => $iperiodeth, 
                    'iperiodebl' => $iperiodebl,
                    'status' => 'gagal'
                );
            } else {
            $param =  array(
                'iperiodeth' => $iperiodeth, 
                'iperiodebl' => $iperiodebl,
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
        
        $bulan      = $this->uri->segment(4);
        $tahun      = $this->uri->segment(5);
        $iperiode   = $tahun.$bulan;

        $filename = "SO_Cutting_Periode_".$iperiode.".xls";
        //$e_bulan =mbulan($bulan);
        //var_dump($bulan);
        //var_dump($filename);
        $inputFileName = './import/soproduksi/cutting/'.$filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');
        $aray = array();
        for ($n=5; $n<=$hrow; $n++){
            $aray[] = array( 
                'i_product'       => strtoupper($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue()),
                'e_product_name'  => strtoupper($spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue()),
                'i_material'      => strtoupper($spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue()),
                'e_material_name' => strtoupper($spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue()),
                'so'              => strtoupper($spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue()),
            );
        }
         $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'data'          => $aray,
        );


        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }
}
/* End of file Cform.php */