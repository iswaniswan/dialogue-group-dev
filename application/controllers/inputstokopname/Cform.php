<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050211';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->doc_qe = $data[0]['doc_qe'];

        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index()
    {
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
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {
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

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function index2()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SO-" . date('ym') . "-123"
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformmain', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function tambah()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post("ibagian", true);
        $ddocument      = $this->input->post("ddocument", true);
        $idocument      = $this->input->post("idocument", true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($ddocument == "") $ddocument = $this->uri->segment(5);
        if ($idocument == "") $idocument = $this->uri->segment(6);
        $dfrom      = $this->input->post("dfrom", true);
        $dto      = $this->input->post("dto", true);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $idocument,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'datadetail'    => $this->mmaster->datadetail($this->session->userdata('id_company'), $ddocument, $ibagian)->result_array(),
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang()
    {
        $filter = [];
        // if ($this->input->get('q') != '') {
        $data = $this->mmaster->barang(str_replace("'", "", $this->input->get('q')), $this->input->get('ibagian'), $this->input->get('ddocument'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id . '|' . $row->e_satuan_name,
                    'text' => $row->i_material . ' - ' . $row->e_material_name . ' (' . $row->e_satuan_name . ')'
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        // } else {
        //     $filter[] = array(
        //         'id'   => null,
        //         'text' => "Cari Berdasarkan Nama / Kode Barang!"
        //     );
        // }
        echo json_encode($filter);
    }


    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = formatYmd($this->input->post('ddocument', TRUE));
        $iperiode   = formatperiode($this->input->post('ddocument', TRUE));
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        $this->db->trans_begin();
        $id = $this->mmaster->runningid();
        $this->mmaster->simpan($id, $ibagian, $idocument, $ddocument, $iperiode, $eremarkh);
        for ($i = 1; $i <= $jml; $i++) {
            $idmaterial = $this->input->post('idmaterial' . $i, TRUE);
            $idmaterial = explode('|', $idmaterial);
            $qty = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
            $eremark   = $this->input->post('eremark' . $i, TRUE);
            if ($idmaterial[0] != null || $idmaterial[0] != '') {
                $this->mmaster->simpandetail($id, $idmaterial[0], $qty, $eremark);
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
                'id'     => $id
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        echo json_encode($data);
        // $this->load->view('pesan2', $data); 
    }


    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $id = $this->uri->segment(4);
        $dfrom = $this->uri->segment(5);
        $dto = $this->uri->segment(6);


        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument  = $this->input->post('idocument', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idcompany  = $this->session->userdata('id_company');
        $jml        = $this->input->post('jml', TRUE);
        $id         = $this->input->post('id', TRUE);
        $this->db->trans_begin();
        $this->mmaster->updateheader($id, $eremarkh);
        $this->mmaster->hapusdetail($id);
        for ($i = 1; $i <= $jml; $i++) {
            $idmaterial = $this->input->post('idmaterial' . $i, TRUE);
            $idmaterial = explode('|', $idmaterial);
            $qty = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
            $eremark   = $this->input->post('eremark' . $i, TRUE);
            if ($idmaterial[0] != null || $idmaterial[0] != '') {
                $this->mmaster->simpandetail($id, $idmaterial[0], $qty, $eremark);
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
                'id'     => $id
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
        }
        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }


    public function approval()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);


        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function export()
    {
        /** Parameter */
        /* $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $i_supplier = $this->input->post('i_supplier');
        $laporan = $this->input->post('laporan');
        $check = $this->input->post('check'); */
        $i_bagian = $this->uri->segment(6);
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
            ->setCellValue("A2", "FORMAT UPLOAD STOCKOPNAME MATERIAL");
        $spreadsheet->getActiveSheet()->setTitle('Format Stockopname');
        $h = 3;
        $header = ['#', 'ID MATERIAL', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'SO', 'KETERANGAN'];
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
        $sql = $this->mmaster->get_export_so($i_bagian);
        
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
                $no++;
                $isi = [
                    $no, $row->id, trim($row->i_material), capitalize(trim($row->e_material_name)), trim($row->e_satuan_name), 0, ''
                ];
                for ($i = 0; $i < count($isi); $i++) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                }
                $j++;
            }
        }
        $y = $j - 1;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = "SO_Material.xls";
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

        $ibagian = $this->input->post('ibagian', TRUE);
        $i_so = $this->input->post('i_so', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        $dfrom = $this->input->post('dfrom', TRUE);
        $dto = $this->input->post('dto', TRUE);
        // var_dump($_FILES);
        $filename = $this->id_company . "_SO_Material_".$ibagian."_" . $ddocument . ".xls";
        // $filename = "SO_Material.xls";

        $config = array(
            'upload_path'   => "./import/soproduksi/material/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File SO Material : ' . $i_so);

            $param =  array(
                'ibagian'   => $ibagian,
                'i_so'      => $i_so,
                'ddocument' => $ddocument,
                'dfrom'     => $dfrom,
                'dto'       => $dto,
                'status'    => 'berhasil'
            );
            echo json_encode($param);
        } else {
            $param =  array(
                'status' => 'gagal',
                'error' => $this->upload->display_errors(),
            );
            echo json_encode($param);
        }
    }

    public function loadview()
    {

        $ibagian    = $this->uri->segment(4);
        $i_so       = $this->uri->segment(5);
        $ddocument  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        // $filename = $this->id_company . "_SO_" .$ibagian."_". $ddocument . ".xls";
        $filename = $this->id_company . "_SO_Material_".$ibagian."_" . $ddocument.".xls";


        $inputFileName = "./import/soproduksi/material/". $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        // $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $aray = array();
        for ($n = 4; $n <= $hrow; $n++) {
            $id_material = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
            $qty_so  = (int)$spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue();
            if ($qty_so > 0 && $id_material != "") {
                $aray[] = array(
                    'id'                => $id_material,
                    'i_material'        => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
                    'e_material_name'   => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
                    'e_satuan_name'     => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
                    'n_quantity'        => $qty_so,
                    'e_remark'          => $spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue(),
                );
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $i_so,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->id_company)->row(),
            'datadetail'    => $aray,
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }
}
/* End of file Cform.php */