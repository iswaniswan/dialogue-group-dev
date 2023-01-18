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
    public $i_menu = '2050211';

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
        $kelompokbrg    = $this->session->userdata('kelompok_barang');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'kelompokbrg'   => $kelompokbrg,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function gudang(){
        $gudang    = $this->session->userdata('gudang');
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', $gudang);
        $data = $this->db->get();
        foreach ($data->result() as $itype) {
            $filter[] = array(
                'id' => $itype->i_kode_master,
                'text' => $itype->e_nama_master,

            );
        }
        echo json_encode($filter);
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
            $kop  = "<option value=\"BRG\">  Semua Barang  ".$c."</option>";
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

    public function tambah(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $kelompokbrg    = $this->session->userdata('kelompok_barang');
        $dso            = $this->input->post("dso",true);
        $ikodemaster    = $this->input->post("ikodemaster",true);
        $ikodebarang    = $this->input->post("ikodebarang",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'gudang'        => $this->mmaster->bacagudang($ikodemaster)->row(),
            'kodemaster'    => $ikodemaster,
            'dso'           => $dso,
            'barang'        => $this->mmaster->getbarang($ikodemaster)->row(),
            'data2'         => $this->mmaster->cek_datadet($dso, $ikodebarang, $ikodemaster, $kelompokbrg)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function datamaterial(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select a.*,b.e_satuan from tr_material a, tr_satuan b where a.i_satuan_code=b.i_satuan_code  and a.i_kode_kelompok='KTB0002' and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%') order by a.i_material");
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

        $ikodemaster    = $this->input->post('ikodemaster', TRUE); 
        $dso            = $this->input->post('dso', TRUE);
        if($dso){
                 $tmp   = explode('-', $dso);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $dateso = $year.'-'.$month.'-'.$day;
        }
        
        $jml            = $this->input->post('jml', TRUE); 
        $istokopname = '';
        $this->db->trans_begin();
        $data = $this->db->query("select i_stok_opname_bahanbaku from tt_stok_opname_bahan_baku where d_tahun = '$year' and d_bulan = '$month' ");
        if ($data->num_rows() > 0){
            foreach($data->result() as $row){
                $istokopname=$row->i_stok_opname_bahanbaku;
            }
            $this->mmaster->updateheaderso($istokopname, $ikodemaster, $dateso, $yearmonth);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$istokopname);
        } else {
            $istokopname = $this->mmaster->runningnumber($yearmonth, $ikodemaster);
            $this->mmaster->insertheader($istokopname, $ikodemaster, $dateso, $yearmonth);
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$istokopname);
        }  
        $this->mmaster->deletedetail($istokopname, $ikodemaster);

            for($i=1;$i<=$jml;$i++){   
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);             
                $saldoawal      = $this->input->post('saldoawal'.$i, TRUE);
                $saldoakhir     = $this->input->post('saldoakhir'.$i, TRUE);
                $stokopname     = $this->input->post('stokopname'.$i, TRUE);
                $nitemno        = $i;

                $this->mmaster->insertdetail($istokopname, $imaterial, $saldoawal, $saldoakhir, $stokopname, $nitemno);
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
        $ikodemaster = ($this->input->post('ikodemaster',TRUE) != '' ? $this->input->post('ikodemaster',TRUE) : $this->uri->segment(4));
        $dso = ($this->input->post('dso',TRUE) != '' ? $this->input->post('dso',TRUE) : $this->uri->segment(5));
        $ikodebarang = ($this->input->post('ikodebarang',TRUE) != '' ? $this->input->post('ikodebarang',TRUE) : $this->uri->segment(6));
        $kelompokbrg = ($this->input->post('kelompokbrg',TRUE) != '' ? $this->input->post('kelompokbrg',TRUE) : $this->uri->segment(7));

        $query = $this->mmaster->cek_datadet($dso, $ikodebarang, $ikodemaster, $kelompokbrg)->result();
    //if($query->num_rows()>0) {

        //}

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
        foreach(range('A','G') as $columnID) {
          $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
          $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Stok Opname')
                      ->setCellValue('A2', "Stok Opname $row->gudang")
                      ->setCellValue('A3', "Tanggal SO : $dso")
                      ->setCellValue('A5', 'KODEBARANG')
                      ->setCellValue('B5', 'NAMABARANG')
                      ->setCellValue('C5', 'SATUAN')
                      ->setCellValue('D5', 'SALDOAWAL')
                      ->setCellValue('E5', 'SALDOAKHIR')
                      ->setCellValue('F5', 'STOKOPNAME')
                      ->setCellValue('G5', 'SELISIH');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:G1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:G2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:G3');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:G5');

          $kolom = 6;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $row->kode)
                        ->setCellValue('B' . $kolom, $row->barang)
                        ->setCellValue('C' . $kolom, $row->satuan)
                        ->setCellValue('D' . $kolom, $row->saldoawal)
                        ->setCellValue('E' . $kolom, $row->saldoakhir)
                        ->setCellValue('F' . $kolom, $row->so)
                        ->setCellValue('G' . $kolom, $row->selisih);
            //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':G'.$kolom);
           
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "SO_Bahan_Baku_".$dso."_".$ikodemaster.".xls";
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

      public function view(){

        $iperiodebl     = $this->input->post("iperiodebl",true);
        $iperiodeth     = $this->input->post("iperiodeth",true);
        $ikodemaster    = $this->input->post("ikodemaster",true);


        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approval ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'gudang'        => $this->mmaster->bacagudang($ikodemaster)->row(),
            'kodemaster'    => $ikodemaster,
            'bulan'         => $iperiodebl,
            'tahun'         => $iperiodeth,
            'data'          => $this->mmaster->cek_data($iperiodebl, $iperiodeth, $ikodemaster)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($iperiodebl, $iperiodeth, $ikodemaster)->result(),
            
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
            $ikodemaster    = $this->input->post('ikodemaster', TRUE);
            $dbulan         = $this->input->post('dbulan', TRUE);
            $dtahun         = $this->input->post('dtahun', TRUE);

            $jml            = $this->input->post('jml', TRUE);

            $this->Logger->write('Approve Data '.$this->global['title'].' Kode : '.$ikodeso);
            $this->mmaster->updateheader($ikodeso, $ikodemaster, $dbulan, $dtahun);
                
            for($i=1;$i<=$jml;$i++){  
                    //$ikodeso    = $this->input->post('ikodeso'.$i, TRUE);
                    $imaterial  = $this->input->post('imaterial'.$i, TRUE);
                    $this->mmaster->updatedetail($ikodeso, $imaterial);
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

    public function load(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $gudang   = $this->input->post('gudang', TRUE);
        $dso      = $this->input->post('dso', TRUE);
        $filename = "SO_Bahan_Baku_".$dso."_".$gudang.".xls";

        $config = array(
            'upload_path'   => "./import/soproduksi/gudangbahanbaku",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Stok Opname Tanggal : '.$dso.', Gudang : '.$gudang);
            //echo 'berhasil';
          if ($gudang=="" || $gudang == "null" || $gudang == null ) {
                $param =  array(
                    'gudang' => $gudang, 
                    'dso'    => $dso,
                    'status' => 'gagal'
                );
            } else {
            $param =  array(
                'gudang' => $gudang, 
                'dso'    => $dso,
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

     public function loadview(){
        
        $dso      = $this->uri->segment(4);
        $gudang   = $this->uri->segment(5);
        $filename = "SO_Bahan_Baku_".$dso."_".$gudang.".xls";
        //$e_bulan =mbulan($bulan);
        //var_dump($dso);
        //var_dump($filename);
        $inputFileName = './import/soproduksi/gudangbahanbaku/'.$filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');
        $aray = array();
        for ($n=6; $n<=$hrow; $n++){
            $saldoakhir = $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue();
            $so = $spreadsheet->getActiveSheet()->getCell('F'.$n)->getCalculatedValue();
            $selisih = $so - abs($saldoakhir);
            $aray[] = array( 
                'kode'        => strtoupper($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue()),
                'barang'      => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getValue(),
                'satuan'      => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getValue(),
                'saldoawal'   => $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue(),
                'saldoakhir'  => $saldoakhir,
                'so'          => $so,
                'selisih'     => $selisih,
            );
        }
         $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'gudang'        => $this->mmaster->bacagudang($gudang)->row(),
            'kodemaster'    => $gudang,
            'dso'           => $dso,
            'barang'        => $this->mmaster->getbarang($gudang)->row(),
            //'jenis'         => $this->mmaster->jenisbarang($jnsbarang)->row(),
            'data2'         => $aray,
        );


        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }
}
/* End of file Cform.php */