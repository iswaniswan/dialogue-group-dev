<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
/* use PhpOffice\PhpSpreadsheet\Style\Fill; */
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment; */
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090403';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
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

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'jenis'         => $this->db->query("SELECT * from tr_jenis_barang_keluar where id in (1,2)")->result(),
            'number'        => "SJ-".date('ym')."-0001",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
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
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE),$this->input->post('itujuan', TRUE));
        }
        echo json_encode($number);
    }

    // public function bagian(){
    //     $filter = [];
    //     $cari = replace_kutip($this->input->get('q'));
    //     $ibagian = $this->input->get('ibagian');
    //     $data = $this->mmaster->bagian($cari,$ibagian);
    //     foreach($data->result() as $product){       
    //         $filter[] = array(
    //             'id'    => $product->id,
    //             'name'  => $product->e_product_basename,
    //             'text'  => $product->i_product_base.' - '.$product->e_product_basename.' - '.$product->e_color_name,
    //         );
    //     }   
    //     echo json_encode($filter);
    // }

    public function dataproduct(){
        $filter = [];
        $cari = replace_kutip($this->input->get('q'));
        $itujuan = $this->input->get('itujuan');
        $data = $this->mmaster->dataproduct($cari,$itujuan);
        foreach($data->result() as $product){       
            $filter[] = array(
                'id'    => $product->id,
                'name'  => $product->e_product_basename,
                'text'  => $product->i_product_base.' - '.$product->e_product_basename.' - '.$product->e_color_name,
            );
        }   
        echo json_encode($filter);
    }  

    public function getproduct(){
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('eproduct'), $this->input->post('itujuan'));

        echo json_encode($data->result_array());
    }

    public function getproduct3(){
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct3($this->input->post('idproduct'), $this->input->post('eproduct'), $this->input->post('itujuan'));

        echo json_encode($data->result_array());
    }

    public function getstok(){
        header("Content-Type: application/json", true);
        $produk = $this->input->post('idproduct');
        $ibagian = $this->input->post('ibagian');
        $data = $this->mmaster->getstok($produk,$ibagian);

        echo json_encode($data->row());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian     = $this->input->post('ibagian', TRUE);
        $ibonk       = $this->input->post('ibonk', TRUE);
        $dbonk       = $this->input->post('dbonk', TRUE);
        if($dbonk){
             $tmp   = explode('-', $dbonk);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $datebonk  = $year.'-'.$month.'-'.$day;
        }
        
        $itujuan      = $this->input->post('itujuan', TRUE);
        $ijenisbarang = $this->input->post('ijenisbarang', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $id           = $this->mmaster->runningid();

        $i_product    = $this->input->post('idproduct[]',TRUE);
        $i_color      = $this->input->post('idcolorproduct[]',TRUE);
        $n_qtyproduct = $this->input->post('nquantity[]',TRUE);
        $n_qtyproduct = str_replace(',','',$n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
        $this->mmaster->insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark, $ijenisbarang);  
        
        // var_dump($n_qtyproduct);
        // die();
        $no=0;
        foreach ($i_product as $iproduct) {     
            $iproduct    = $iproduct;
            $icolor      = $i_color[$no];
            $nqtyproduct = $n_qtyproduct[$no];
            $edesc       = $e_desc[$no];
            if($nqtyproduct!=0) {
                $this->mmaster->insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc); 
            }
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
                'sukses' => true,
                'kode'   => $ibonk,
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

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $i_bagian        = $this->uri->segment('7');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "SJ-".date('ym')."-0001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenis'         => $this->db->query(" select * from tr_jenis_barang_keluar where id in (1,2)")->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany,$i_bagian)->result(),            
        );        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id          = $this->input->post('id', TRUE);
        $ibagian     = $this->input->post('ibagian', TRUE);
        $ibonk       = $this->input->post('ibonk', TRUE);
        $dbonk       = $this->input->post('dbonk', TRUE);
        if($dbonk){
            $tmp   = explode('-', $dbonk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datebonk  = $year.'-'.$month.'-'.$day;
        }
        
        $itujuan      = $this->input->post('itujuan', TRUE);
        $ijenisbarang = $this->input->post('ijenisbarang', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $i_product    = $this->input->post('idproduct[]',TRUE);
        $i_color      = $this->input->post('idcolorproduct[]',TRUE);
        $n_qtyproduct = $this->input->post('nquantity[]',TRUE);
        $n_qtyproduct = str_replace(',','',$n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
        $this->mmaster->updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark, $ijenisbarang);  
        $this->mmaster->deletedetail($id);
        
        $no=0;
        foreach ($i_product as $iproduct) {     
            $iproduct    = $iproduct;
            $icolor      = $i_color[$no];
            $nqtyproduct = $n_qtyproduct[$no];
            $edesc       = $e_desc[$no];
            if($nqtyproduct != 0)
                $this->mmaster->insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc); 
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
                'sukses' => true,
                'kode'   => $ibonk,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
          $i_bagian        = $this->uri->segment('7');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenis'         => $this->db->query("SELECT * from tr_jenis_barang_keluar where id in (1,2)")->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany, $i_bagian)->result(),    
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
   
    public function approval(){
       $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $i_bagian        = $this->uri->segment('7');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenis'         => $this->db->query(" select * from tr_jenis_barang_keluar where id in (1,2)")->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany, $i_bagian)->result(), 
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function export()
    {
        /** Parameter */
        $itujuan = $this->uri->segment(6);
        $ddocument = $this->uri->segment(7);
        $ibagian = $this->uri->segment(8);
        $id_company_tujuan = $this->uri->segment(9);
        $dsplit = explode('-',$ddocument);
        $nama_file = "";
        /** End Parameter */

        /** Style And Create New Spreedsheet */
        $spreadsheet  = new Spreadsheet;
        $sharedTitle = new Style();
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        /* $conditional3 = new Conditional(); */
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
            [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
            ]
        );

        $sharedTitle->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'size'   => 26
                ],
            ]
        );

        $sharedStyle1->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'italic' => false,
                    'size'   => 14
                ],
            ]
        );

        $sharedStyle2->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => false,
                    'italic' => false,
                    'size'   => 11
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]

        );

        $sharedStyle3->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'italic' => false,
                    'size'   => 11,
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );
        /** End Style */

        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;

        $validation = $spreadsheet->getActiveSheet()->getCell("AZ1")->getDataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Input is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Number Value allowed");

        /* get name company */
        $company_name = $this->db->get_where('public.company', ['id' => $id_company_tujuan])->row()->name;

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A$zero", $id_company_tujuan)
            ->setCellValue("A$satu", strtoupper($company_name))
            ->setCellValue("A$dua", "FORMAT UPLOAD STB JAHIT");
        $spreadsheet->getActiveSheet()->setTitle('Format STB Jahit');

        $validation = $spreadsheet->getActiveSheet()->getCell('K1')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Number is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Value number allowed");

        $h = 4;
        $header = [
            '#',
            'ID BARANG',
            'KODE',
            'NAMA BARANG',
            'WARNA',
            'QTY KIRIM',
            'KETERANGAN'
        ];
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
        }
        
        $spreadsheet->getActiveSheet()->setAutoFilter("A" . $h . ":G" . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":G" . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A2:G2");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A3:G3");
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->mergeCells('A2:G2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:G3');
        $j = 5;
        $i = 0;
        $no = 1;
        $sql = $this->mmaster->getproduct2($id_company_tujuan, $itujuan);
        if ($sql->num_rows() > 0) {
            foreach($sql->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". ($j), $no);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B". ($j), $row->id_product);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C". ($j), $row->i_product_base);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("D". ($j), $row->e_product_basename);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("E". ($j), $row->e_color_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("F". ($j), "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("G". ($j), "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("Y". ($j), $row->id_color);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . ($j) . ":G". ($j));
                $spreadsheet->getActiveSheet()->setDataValidation("F" . ($j), $validation);
                $j++;
                $no++;
                $i++;
            }
        }
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A');
        
        $spreadsheet->getActiveSheet()->getStyle("F5:F" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("G5:G" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("A4:G4")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_STB_Jahit.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /* $ibagian = $this->input->post('ibagian', TRUE);
        $i_so = $this->input->post('i_so', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        $dfrom = $this->input->post('dfrom', TRUE);
        $dto = $this->input->post('dto', TRUE); */
        // $abjadBanyak = array();
        // foreach (excelColumnRange('A', 'ZZ') as $value) {
        //     array_push($abjadBanyak, $value);
        // }
        $cellMulaiTanggal = 7;
        $ibagian = $this->input->post('ibagian');
        $itujuansplit = explode('|', $this->input->post('itujuan'));
        $id_company = $itujuansplit[0];
        $itujuan = $itujuansplit[1];
        $filename = $id_company . "_STB_Jahit_" . $itujuan . ".xls";
        $aray = array();
        $fc_jahit = 0;
        $fc_jahit_sisa = 0;
        $fc_jahit_urai = 0;

        $config = array(
            'upload_path'   => "./import/stbjahit/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());

            $inputFileName = "./import/stbjahit/" . $filename;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestDataRow('A');
            $id_company_tujuan  = $spreadsheet->getActiveSheet()->getCell('A1')->getValue();
            $data = [];
            $data2 = [];
            if ($id_company_tujuan == (int)$id_company) {
                $no = 0;
                for ($n = 5; $n <= $hrow; $n++) {
                    $n_quantity = strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue());
                    $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
                    if ($n_quantity != "") {
                        $datastok = $this->mmaster->getstok($id_product,$ibagian)->row();
                        $aray[] = array(
                            'id'                => $id_product,
                            'i_product_base'     => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
                            'e_product_basename' => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
                            'id_color'      => strtoupper($spreadsheet->getActiveSheet()->getCell('Y' . $n)->getValue()),
                            'e_color_name'      => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
                            'n_quantity'        => $n_quantity,
                            'n_stock'        => $datastok,
                            'keterangan'   => strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue())
                            // 'e_remark'          => $e_remark,
                        );
                        $no++;
                    }
                    // $fc_jahit += (int) strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue());
                    // $fc_jahit_sisa += (int) strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue());
                    // $fc_jahit_urai += (int) strtoupper($spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue());
                    // if($cellMulaiTanggal < 52) {
                    //     $cellMulaiTanggal++;
                    // }
                }
                // echo '<pre>' . var_export($aray, true) . '</pre>';
                // var_dump($data2);
                // usort($aray, function ($b, $a) {
                //     return $b['d_schedule'] <=> $a['d_schedule'];
                // });
                $param = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Tambah " . $this->global['title'],
                    'title_list'    => $this->global['title'],
                    'detail'    => $aray,
                    'status'        => 'berhasil',
                    'sama'          => true,
                    // 'n_quantity' => $fc_jahit,
                    // 'n_quantity_sisa' => $fc_jahit_sisa,
                    // 'n_quantity_urai' => $fc_jahit_urai
                );
            } else {
                $param = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Tambah " . $this->global['title'],
                    'title_list'    => $this->global['title'],
                    'datadetail'    => $aray,
                    'status'        => 'gagal',
                    'sama'          => false
                );
            }
            echo json_encode($param);
        } else {
            $param =  array(
                'status'        => 'gagal',
                'datadetail'    => $aray,
                'sama'          => false
            );
            echo json_encode($param);
        }
    }
}
/* End of file Cform.php */