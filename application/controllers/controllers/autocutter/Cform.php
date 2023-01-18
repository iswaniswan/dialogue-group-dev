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
    public $i_menu = '2090116';

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
        
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');
        

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
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom = date("Y-m-d", strtotime($this->input->post('dfrom', TRUE)));
        $dto = date("Y-m-d", strtotime($this->input->post('dto', TRUE)));

        
        $ext = pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION);

        //$pos= strpos($ext, 'db');
        // var_dump($dfrom . " ". $dto);
        // die();
        if ( strpos($ext, 'db') !== false) { 
            $filename =  uniqid().".db";
            $config = array(
                'upload_path'   => "./import/autocutter/",
                'allowed_types' => "*",
                'file_name'     => $filename,
                'overwrite'     => true
            );
            
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("userfile")) {
                $data = array('upload_data' => $this->upload->data());
                
                $path = "./import/autocutter/".$filename;
                chmod($path,0755);

                $db = new SQLite3($path);
 
                if (!$db) {
                    //die ($error);
                    $param =  array(
                        'status' => false,
                        'message' => 'Gagal Akses Database'
                    );
                    echo json_encode($param);
                } else {
                    $result = $db->query("
                        select FileName, LoadTime , MarkerSize , CuttingTime , PositionTime , BitefeedTime , WorkTime  , PauseTime  , SharpTime ,
                        CuttingLength , PositionLength , MarkerXLength  , MaxSpeed , MinSpeed , AverageSpeed , Parts from beckhoff_cadstatistics 
                        where strftime('%Y-%m-%d', LoadTime) between '$dfrom' and '$dto'
                        order by loadtime desc;
                    ");
                    if($result) {
                        /** Parameter */
                        /* $date_from = $this->input->post('date_from');
                        $date_to = $this->input->post('date_to'); */
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
                                    // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                            ]
                        );
                        /** End Style */

                        $abjad  = range('A', 'Z');
                        $satu = 1;
                        $dua = 2;
                        /** Start Sheet */
                        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue("A1", strtoupper($this->session->e_company_name))
                            ->setCellValue("A2", "Extract Data AutoCutter ".$dfrom . " s.d " . $dto);
                        $spreadsheet->getActiveSheet()->setTitle('Extract Data');
                        $h = 3;

                        $header = ['#', 'FileName', 'LoadTime', 'MarkerSize' , 'CuttingTime', 'PositionTime', 'BitefeedTime', 'WorkTime' , 'PauseTime' , 'SharpTime',
                        'CuttingLength', 'PositionLength', 'MarkerxLength' , 'MaxSpeed', 'MinSpeed', 'AverageSpeed', 'Parts'];
                        for ($i = 0; $i < count($header); $i++) {
                            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
                        }
                        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $dua);
                        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
                        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
                        // $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
                        $j = 4;
                        $x = 4;
                        $no = 0;
                        
                        
                        while($row = $result->fetchArray()) {
                            //var_dump($row["loadtime"]);
                            $no++;
                            $isi = [
                                $no, $row["FileName"], $row["LoadTime"], $row["MarkerSize"],  $row["CuttingTime"], $row["PositionTime"], $row["BitefeedTime"], $row["WorkTime"],  $row["PauseTime"],  $row["SharpTime"], $row["CuttingLength"], $row["PositionLength"], $row["MarkerXLength"],  $row["MaxSpeed"], $row["MinSpeed"], $row["AverageSpeed"], $row["Parts"]
                            ];
                            for ($i = 0; $i < count($isi); $i++) {
                                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                            }
                            $j++;
                        }
                        //die();  
                        
                        $y = $j - 1;
                        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
                        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                        /** End Sheet */

                        $writer = new Xls($spreadsheet);
                        $nama_file = "Extract Data AutoCutter ".$dfrom. " s.d ".$dto.".xls";
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename=' . $nama_file . '');
                        header('Cache-Control: max-age=0');
                        ob_start();
                        $writer->save('php://output');
                        $exceldata = ob_get_contents();
                        ob_end_clean();
                        $response =  array(
                            'file'      => "data:application/vnd.ms-excel;base64," . base64_encode($exceldata),
                            'nama_file' => $nama_file,
                            'data'      => true,
                            'status'    => true,
                            'message'   => 'Berhasil',

                        );
                        die(json_encode($response));

                    } else {
                        $response =  array(
                            'status'    => false,
                            'data'      => false,
                            'message'   => 'Tidak Ada Data',
                        );
                        die(json_encode($response));
                    }
                    //var_dump($result->fetchArray());
                }

                
            } else {
                $param =  array(
                    'status' => false,
                    'message' => 'Gagal Upload',
                    'data'      => false,
                );
                echo json_encode($param);
            }

        } else {
            $param =  array(
                'status' => false,
                'message' => 'File Wajib Berextensi .db',
                'data'      => false,
            );
            echo json_encode($param);
        }
    }

}
/* End of file Cform.php */