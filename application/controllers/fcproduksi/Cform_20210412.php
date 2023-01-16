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
    public $i_menu = '2090701';

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
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function getbarang(){
        $ikodemaster = $this->input->post('ikodemaster');
        $query = $this->mmaster->getbarang($ikodemaster);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_product_motif." >".$row->i_product_motif."-".$row->e_product_basename."</option>";
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

        $dfrom    = $this->input->post('dfrom', TRUE);
        $dto      = $this->input->post('dto', TRUE);
        $filename = "Forecast_Produksi_".$dfrom.".xls";

        $config = array(
            'upload_path'   => "./import/fcproduksi/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Produksi Tanggal : '.$dfrom);
            //echo 'berhasil';
            
            if ($dfrom =="" || $dfrom == "null" || $dfrom == null) {
                $param =  array(
                    'dfrom'  => $dfrom,
                    'dto'    => $dto,
                    'status' => 'gagal'
                );
            } else {
                $param =  array(
                    'dfrom'  => $dfrom,
                    'dto'    => $dto, 
                    'status' => 'berhasil'
                );
            }
            echo json_encode($param);
        }else{
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
        }
    }

    public function views(){
        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthfrom = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }
        if($dto){
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthto = $year.$month;
            $dto = $year.'-'.$month.'-'.$day;
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'yearmonthfrom' => $yearmonthfrom,
            'yearmonthto'   => $yearmonthto,
            'fc'            => $this->mmaster->bacafc($dfrom,$dto)->result(),
            'isi'           => $this->mmaster->cek_dataheader($dfrom,$dto)->row(),
            'detail'        => $this->mmaster->cek_datadetail($dfrom,$dto)->result()
            
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom = $this->input->post("dfrom",true);
        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post("dto",true);
        if($dto == ''){
            $dto = $this->uri->segment(4);
        }

        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthfrom = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }
        if($dto){
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthto = $year.$month;
            $dto = $year.'-'.$month.'-'.$day;
        }

        $query = $this->mmaster->cek_datadetail($dfrom,$dto);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'yearmonthfrom' => $yearmonthfrom,
            'yearmonthto'   => $yearmonthto,
            'jmlitem'       => $query->num_rows(),
            'fc'            => $this->mmaster->bacafc($dfrom,$dto)->result(),
            'barang'        => $this->mmaster->getbarang()->row(),
            'isi'           => $this->mmaster->cek_dataheader($dfrom,$dto)->row(),
            'detail'        => $this->mmaster->cek_datadetail($dfrom,$dto)->result()
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function loadview(){
        
        $dfrom            = $this->uri->segment(4);
        $dto            = $this->uri->segment(5);
        $filename       = "Forecast_Produksi_".$dfrom.".xls";
        $inputFileName  = './import/fcproduksi/'.$filename;
        $spreadsheet    = IOFactory::load($inputFileName);
        $worksheet      = $spreadsheet->getActiveSheet();
        $sheet          = $spreadsheet->getSheet(0);
        $hrow           = $sheet->getHighestDataRow('A');
        $aray           = array();
        for ($n=5; $n<=$hrow; $n++){
            $iproduct       = $spreadsheet->getActiveSheet()->getCell('A'.$n)->getValue();
            $eproductname   = $spreadsheet->getActiveSheet()->getCell('B'.$n)->getValue();
            $icolor         = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getValue();
            $nquantity      = $spreadsheet->getActiveSheet()->getCell('D'.$n)->getValue();
            $aray[] = array( 
                'iproduct'       => $iproduct,
                'eproductname'   => $eproductname,
                'icolor'         => $icolor,
                'nquantity'      => $nquantity
            );
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'data2'         => $aray
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function dataproduct(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->bacaproduct($cari);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_product_motif,
                    'name' => $row->e_product_basename,
                    'text' => $row->i_product_motif.' - '.$row->e_product_basename.' - '.$row->i_color,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailfc(){
        header("Content-Type: application/json", true);
        $ifc     = $this->input->post('ifc', FALSE);
        $dfrom   = $this->input->post('dfrom', FALSE);
        $dto     = $this->input->post('dto', FALSE);
        $query  = array(
            'detail' => $this->mmaster->bacadetailfc($ifc,$dfrom,$dto)->result_array()
        );
        echo json_encode($query);  
    }

    public function getproduct(){
        header("Content-Type: application/json", true);
        
        $iproduct   = $this->input->post('iproduct');
        $this->db->select("*");
        $this->db->from("tr_product_base");
        $this->db->where("i_product_motif", $iproduct);
        $this->db->order_by('i_product_motif', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dfrom   = $this->input->post('dfrom', TRUE);
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom   = $year.'-'.$month.'-'.$day;
        }
        $jml   = $this->input->post('jml', TRUE); 
        $ifc   = '';
        $this->db->trans_begin();
        $data = $this->mmaster->cekfc($dfrom);
        if ($data->num_rows() > 0){
            foreach($data->result() as $row){
                $ifc=$row->i_fc;
            }
            $this->mmaster->updateheaderfc($ifc, $dfrom, $yearmonth);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ifc);
        } else {
            $ifc = $this->mmaster->runningnumber($yearmonth);
           
            $this->mmaster->insertheader($ifc, $dfrom, $yearmonth);
            
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ifc);
        }  
        $this->mmaster->deletedetail($ifc);
        for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);          
                $icolor         = $this->input->post('icolor'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $nitemno        = $i;
                $this->mmaster->insertdetail($ifc, $iproduct, $icolor, $nquantity, $nitemno);
                
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
                'kode'      => $ifc,
            );
        }
        $this->load->view('pesan', $data); 
    }

    public function deletedetailinput(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ifc			= $this->input->post('ifc', TRUE);
		$iproduct		= $this->input->post('iproduct', TRUE);

        $this->db->trans_begin();
        $this->mmaster->deletedetailinput($iproduct);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Item Forecast Produksi '.$iproduct);
            echo json_encode($data);
        }
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dfrom  = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(4));
        $dto    = ($this->input->post('dto',TRUE) != '' ? $this->input->post('dto',TRUE) : $this->uri->segment(5));

        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }
        if($dto){
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dto = $year.'-'.$month.'-'.$day;
        }
        $query  = $this->mmaster->cek_datadetail($dfrom,$dto)->result();

        foreach ($query as $row) {
            $spreadsheet  = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
                [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE 
                ]
            ); 

            $sharedStyle1->applyFromArray(
                ['alignment' => [
                  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                  'bottom' => ['borderStyle' => Border::BORDER_THIN],
                  'right' => ['borderStyle' => Border::BORDER_THIN],
                ],]
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
            $spreadsheet->getActiveSheet()->mergeCells("A1:D1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:D2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:D3");
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A1', "Forecast Produksi")
                        ->setCellValue('A2', "Tanggal Forecast : $dfrom sd $dto")
                        ->setCellValue('A4', 'Kode_Barang')
                        ->setCellValue('B4', 'Nama_Barang')
                        ->setCellValue('C4', 'Warna')
                        ->setCellValue('D4', 'Jumlah_Forecast');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:D1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:D2');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A3:D3');
            $kolom = 6;
            $nomor = 1;
            $nol = 0;
            foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, $row->i_product)
                            ->setCellValue('B' . $kolom, $row->e_product_basename)
                            ->setCellValue('C' . $kolom, $row->i_color)
                            ->setCellValue('D' . $kolom, $row->jumlah);      
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':D'.$kolom);
            
                $kolom++;
                $nomor++;
            }
            $writer = new Xls($spreadsheet);
            $nama_file = "Forecast_Produksi_".$dfrom.".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }   
        $ifc     = $this->input->post('ifc', TRUE);
        $dfrom   = $this->input->post('dfrom', TRUE);
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom   = $year.'-'.$month.'-'.$day;
        }
        $dto   = $this->input->post('dto', TRUE);
        if($dto){
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth2 = $year.$month;
            $dto   = $year.'-'.$month.'-'.$day;
        }
        $jml   = $this->input->post('jml', TRUE); 

        //ITEM
        $i_product    = $this->input->post('iproduct[]',TRUE);
        $e_product    = $this->input->post('eproductname[]',TRUE);
        $i_color      = $this->input->post('icolor[]',TRUE);
        $n_quantity      = $this->input->post('nquantity[]',TRUE);

        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ifc);
        $this->mmaster->updateheader($ifc, $yearmonth);
        $no=0;
        foreach ($i_product as $iproduct) {     
            $iproduct   = $iproduct;
            $eproduct   = $e_product[$no];
            $icolor     = $i_color[$no];
            $nquantity  = $n_quantity[$no];
            $nitemno    = $no;
           
            $this->mmaster->updatedetail($ifc, $iproduct, $nquantity,$nitemno);
            $no++;
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
                'kode'      => $ifc,
            );
        }
        $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */