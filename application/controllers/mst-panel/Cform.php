<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2010216';

    public function __construct()
    {
        parent::__construct();
        cek_session();

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
        $this->load->library('fungsi');
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
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

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

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom' => $this->uri->segment(4),
            'dto' => $this->uri->segment(5),
            'doc' => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE), $this->input->post('itujuan', TRUE));
        }
        echo json_encode($number);
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

    public function cekkodeedit()
    {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode', TRUE), $this->input->post('kodeold', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  CARI BARANG  ----------*/

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id' => $row->id . '-' . $row->i_product_wip . '-' . $row->i_color . '-' . $row->e_color_name,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - ' . $row->e_color_name
                    );
                }
            } else {
                $filter[] = array(
                    'id' => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Cari Barang Berdasarkan Nama / Kode"
            );
        }
        echo json_encode($filter);
    }

    /*-------------- CARI MARKER ------------- */
    public function marker()
    {
        $filter = [];
        $data = $this->mmaster->marker(str_replace("'", "", $this->input->get('q')), str_replace("'", "", $this->input->get('i_color')), str_replace("'", "", $this->input->get('id_product_wip')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->e_marker_name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function material()
    {
        $filter = [];
        $data = $this->mmaster->material(str_replace("'", "", $this->input->get('q')), str_replace("'", "", $this->input->get('idmarker')), str_replace("'", "", $this->input->get('idproduct')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->i_material . ' - ' . $row->e_material_name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/

    public function detailproduct()
    {
        header("Content-Type: application/json", true);
        $query = array(
            'detail' => $this->mmaster->detailproduct($this->input->post('id', TRUE), $this->input->post('color', TRUE))->result_array()
        );
        echo json_encode($query);
    }

    public function getstok()
    {
        header("Content-Type: application/json", true);
        $produk = explode('-', $this->input->post('idproduct'));
        $ibagian = $this->input->post('ibagian');
        $data = $this->mmaster->getstok($produk[0], $ibagian);

        echo json_encode($data->row());
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idproduct = $this->input->post('idproduct', TRUE);
        $eremarkh = $this->input->post('eremarkh', TRUE);
        $idmarker = $this->input->post('idmarker', TRUE);
        $jml = $this->input->post('jml', TRUE);

        if ($jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id, $idproduct, $eremarkh, $idmarker);

            for ($x = 1; $x <= $jml; $x++) {
                $imaterial = $this->input->post('imaterial' . $x, TRUE);

                if ($imaterial != '' || $imaterial != NULL) {
                    $ebagian = strtoupper($this->input->post('ebagian' . $x, TRUE));
                    $ipanel = $this->input->post('ipanel' . $x, TRUE);
                    $edesc = $this->input->post("eremark" . $x, TRUE);
                    $n_qty_penyusun = $this->input->post("n_qty_penyusun" . $x, TRUE);
                    $n_panjang_cm = $this->input->post("n_panjang_cm" . $x, TRUE);
                    $n_lebar_cm = $this->input->post("n_lebar_cm" . $x, TRUE);

                    $n_pg_cm = $this->input->post("n_pg_cm" . $x, TRUE);
                    $n_lg_cm = $this->input->post("n_lg_cm" . $x, TRUE);
                    $n_hg_set = $this->input->post("n_hg_set" . $x, TRUE);
                    $n_efficiency = $this->input->post("n_efficiency" . $x, TRUE);

                    $imaterialmakloon = $this->input->post('imaterialmakloon' . $x, TRUE);

                    if (null !== $this->input->post("print" . $x, TRUE)) {
                        $print = true;
                    } else {
                        $print = false;
                    }

                    if (null !== $this->input->post("bordir" . $x, TRUE)) {
                        $bordir = true;
                    } else {
                        $bordir = false;
                    }

                    if (null !== $this->input->post("f_khusus_pengadaan" . $x, TRUE)) {
                        $f_khusus_pengadaan = true;
                    } else {
                        $f_khusus_pengadaan = false;
                    }

                    // if ($imaterialmakloon == null) {
                    //     $imaterialmakloon = $imaterial;
                    // }

                    $this->mmaster->insertdetail($idproduct, $idmarker, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $print, $bordir, $n_pg_cm, $n_lg_cm, $n_hg_set, $n_efficiency, $f_khusus_pengadaan, $imaterialmakloon);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $idproduct,
                    'id' => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany = $this->session->userdata('id_company');
        // var_dump($this->uri->segment(4), $this->uri->segment(7)); die;
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'data' => $this->mmaster->dataedit($this->uri->segment(4), $this->uri->segment(7))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4), $this->uri->segment(7))->result(),
            'doc' => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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

        $id = $this->input->post('id', TRUE);
        $idproduct = $this->input->post('idproduct', TRUE);
        $idmarker = $this->input->post('idmarker', TRUE);
        $eremarkh = $this->input->post('eremarkh');
        $jml = $this->input->post('jml', TRUE);

        if ($jml > 0) {
            $this->db->trans_begin();
            // $this->mmaster->updateheader($id, $idproduct, $eremarkh);
            // $this->mmaster->deletedetail($idproduct);

            for ($x = 1; $x <= $jml; $x++) {
                $imaterial = $this->input->post('imaterial' . $x, TRUE);

                if ($imaterial != '' || $imaterial != NULL) {
                    $iditem = $this->input->post('iditem' . $x, TRUE);
                    $ebagian = strtoupper($this->input->post('ebagian' . $x, TRUE));
                    $ipanel = $this->input->post('ipanel' . $x, TRUE);
                    $edesc = $this->input->post("eremark" . $x, TRUE);
                    $n_qty_penyusun = $this->input->post("n_qty_penyusun" . $x, TRUE);
                    $n_panjang_cm = $this->input->post("n_panjang_cm" . $x, TRUE);
                    $n_lebar_cm = $this->input->post("n_lebar_cm" . $x, TRUE);

                    $n_pg_cm = $this->input->post("n_pg_cm" . $x, TRUE);
                    $n_lg_cm = $this->input->post("n_lg_cm" . $x, TRUE);
                    $n_hg_set = $this->input->post("n_hg_set" . $x, TRUE);
                    $n_efficiency = $this->input->post("n_efficiency" . $x, TRUE);

                    $status = $this->input->post('status' . $x);
                    if (null !== $this->input->post("print" . $x, TRUE)) {
                        $print = true;
                    } else {
                        $print = false;
                    }
                    if (null !== $this->input->post("bordir" . $x, TRUE)) {
                        $bordir = true;
                    } else {
                        $bordir = false;
                    }

                    $imaterialmakloon = $this->input->post('imaterialmakloon' . $x, TRUE);

                    if (null !== $this->input->post("f_khusus_pengadaan" . $x, TRUE)) {
                        $f_khusus_pengadaan = true;
                    } else {
                        $f_khusus_pengadaan = false;
                    }

                    // if ($imaterialmakloon == null) {
                    //     $imaterialmakloon = $imaterial;
                    // }

                    if ($iditem != null || $iditem != '') {
                        $this->mmaster->updatedetail($iditem, $idproduct, $idmarker, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $status, $print, $bordir, $n_pg_cm, $n_lg_cm, $n_hg_set, $n_efficiency, $f_khusus_pengadaan, $imaterialmakloon);
                    } else {
                        $this->mmaster->insertdetail($idproduct, $idmarker, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $print, $bordir, $n_pg_cm, $n_lg_cm, $n_hg_set, $n_efficiency, $f_khusus_pengadaan, $imaterialmakloon);

                    }
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $idproduct,
                    'id' => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    /*----------  MEMBUKA MENU Approve  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number' => "SJ-" . date('ym') . "-123456",
            'tujuan' => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'doc' => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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

        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4), $this->uri->segment(5))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4), $this->uri->segment(5))->result(),
            'number' => "SJ-" . date('ym') . "-123456",
            'tujuan' => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenisbarang' => $this->mmaster->jeniskeluar()->result(),
            'doc' => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idproduct = $this->input->post('idproduct');
        $idmarker = $this->input->post('idmarker');
        $filename = $this->id_company . "_Panel_" . $idproduct . '_' . $idmarker . ".xls";
        $aray = array();

        $config = array(
            'upload_path' => "./import/panel/",
            'allowed_types' => "xls",
            'file_name' => $filename,
            'overwrite' => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());

            $inputFileName = "./import/panel/" . $filename;
            // $worksheet = $spreadsheet->getActiveSheet();
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getSheet(0);
            $hrow = $sheet->getHighestDataRow('B');
            $i = 0;

            foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
                if ($drawing instanceof MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );
                    $imageContents = ob_get_contents();
                    ob_end_clean();
                    switch ($drawing->getMimeType()) {
                        case MemoryDrawing::MIMETYPE_PNG:
                            $extension = 'png';
                            break;
                        case MemoryDrawing::MIMETYPE_GIF:
                            $extension = 'gif';
                            break;
                        case MemoryDrawing::MIMETYPE_JPEG:
                            $extension = 'jpg';
                            break;
                    }
                } else {
                    if ($drawing->getPath()) {
                        // Check if the source is a URL or a file path
                        if ($drawing->getIsURL()) {
                            $imageContents = file_get_contents($drawing->getPath());
                            $filePath = tempnam(sys_get_temp_dir(), 'Drawing');
                            file_put_contents($filePath, $imageContents);
                            $mimeType = mime_content_type($filePath);
                            // You could use the below to find the extension from mime type.
                            // https://gist.github.com/alexcorvi/df8faecb59e86bee93411f6a7967df2c#gistcomment-2722664
                            $extension = File::mime2ext($mimeType);
                            unlink($filePath);
                        } else {
                            $zipReader = fopen($drawing->getPath(), 'r');
                            $imageContents = '';
                            while (!feof($zipReader)) {
                                $imageContents .= fread($zipReader, 1024);
                            }
                            fclose($zipReader);
                            $extension = $drawing->getExtension();
                        }
                    }
                }
                $myFileName = '00_Image_' . ++$i . '.' . $extension;
                file_put_contents($myFileName, $imageContents);
            }
            $id_product_marker_excel = explode('_',$spreadsheet->getActiveSheet()->getCell('C1')->getValue());
            $id_product = $id_product_marker_excel[0];
            $id_marker = $id_product_marker_excel[1];
            if($id_product == $idproduct && $id_marker == $idmarker) {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idproduct, null, $idmarker);
                $data = [];
                $aray = [];
                for ($n = 10; $n <= $hrow; $n++) {
                    $i_material = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
                    $bagian_panel = strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue());
                    $kode_panel = strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getOldCalculatedValue());
                    $qty_penyusun = strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue());
                    $panjang = strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue());
                    $lebar = strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue());
                    $panjang_gelar = strtoupper($spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue());
                    $lebar_gelar = strtoupper($spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue());
                    $hasil_gelar = strtoupper($spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue());
                    $efficiency = strtoupper($spreadsheet->getActiveSheet()->getCell('K' . $n)->getValue());
                    $print = strtoupper($spreadsheet->getActiveSheet()->getCell('L' . $n)->getValue());
                    if(strlen($print) > 0) {
                        $print = true;
                    } else {
                        $print = false;
                    }
                    $bordir = strtoupper($spreadsheet->getActiveSheet()->getCell('M' . $n)->getValue());
                    if(strlen($bordir) > 0) {
                        $bordir = true;
                    } else {
                        $bordir = false;
                    }
                    $image = $spreadsheet->getActiveSheet()->getCell('N' . $n)->getValue();
                    if($i_material != '') {
                        // check material
                        $res = $this->db->query("SELECT * FROM tr_material WHERE i_material = '$i_material' AND id_company = '$this->id_company'");
                        if($res->num_rows() > 0) {
            
                            // $gambar = $spreadsheet->getActiveSheet()->getDrawingCollection();
                            // var_dump($i_material, $bagian_panel, $kode_panel, $qty_penyusun, $panjang, $lebar, $panjang_gelar, $hasil_gelar, $efficiency, $print, $bordir, $image);
                            $this->mmaster->insertdetail($idproduct, $idmarker, $res->row()->id, $bagian_panel, $kode_panel, null, $qty_penyusun, $panjang, $lebar, $print, $bordir, $panjang_gelar, $lebar_gelar | 0, $hasil_gelar, $efficiency, true, '');
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                $aray = array(
                                    'sukses' => false,
                                );
                            } else {
                                $this->db->trans_commit();
                                $this->Logger->write('Insert Data ' . $this->global['title'] . ' Id : ' . $id);
                            }
                        } else {
                            array_push($aray, ['i_material' => $i_material, 'baris_excel' => $n]);
                        }
                    }
                }
                $param = array(
                    'status' => 'berhasil',
                    'datadetail' => $aray,
                    'sama' => false
                );
                echo json_encode($param);
            } else {
                // array_push($aray, $i_material);
                $param = array(
                    'status' => 'gagal id_product tidak cocok',
                    'datadetail' => 'id_product dan id_marker harus sama dengan id_marker dan id_product di file excel',
                    'sama' => false
                );
                echo json_encode($param);
            }
        } else {
            $param = array(
                'status' => 'gagal',
                'datadetail' => 'upload gagal, pilih file terlebih dahulu',
                'sama' => false
            );
            echo json_encode($param);
        }
    }

    public function load_edit()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idproduct = $this->input->post('idproduct');
        $idmarker = $this->input->post('idmarker');
        $filename = $this->id_company . "_Panel_" . $idproduct . '_' . $idmarker . ".xls";
        $aray = array();

        $config = array(
            'upload_path' => "./import/panel/",
            'allowed_types' => "xls",
            'file_name' => $filename,
            'overwrite' => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());

            $inputFileName = "./import/panel/" . $filename;
            // $worksheet = $spreadsheet->getActiveSheet();
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getSheet(0);
            $hrow = $sheet->getHighestDataRow('B');
            $i = 0;

            foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
                if ($drawing instanceof MemoryDrawing) {
                    ob_start();
                    call_user_func(
                        $drawing->getRenderingFunction(),
                        $drawing->getImageResource()
                    );
                    $imageContents = ob_get_contents();
                    ob_end_clean();
                    switch ($drawing->getMimeType()) {
                        case MemoryDrawing::MIMETYPE_PNG:
                            $extension = 'png';
                            break;
                        case MemoryDrawing::MIMETYPE_GIF:
                            $extension = 'gif';
                            break;
                        case MemoryDrawing::MIMETYPE_JPEG:
                            $extension = 'jpg';
                            break;
                    }
                } else {
                    if ($drawing->getPath()) {
                        // Check if the source is a URL or a file path
                        if ($drawing->getIsURL()) {
                            $imageContents = file_get_contents($drawing->getPath());
                            $filePath = tempnam(sys_get_temp_dir(), 'Drawing');
                            file_put_contents($filePath, $imageContents);
                            $mimeType = mime_content_type($filePath);
                            // You could use the below to find the extension from mime type.
                            // https://gist.github.com/alexcorvi/df8faecb59e86bee93411f6a7967df2c#gistcomment-2722664
                            $extension = File::mime2ext($mimeType);
                            unlink($filePath);
                        } else {
                            $zipReader = fopen($drawing->getPath(), 'r');
                            $imageContents = '';
                            while (!feof($zipReader)) {
                                $imageContents .= fread($zipReader, 1024);
                            }
                            fclose($zipReader);
                            $extension = $drawing->getExtension();
                        }
                    }
                }
                $myFileName = '00_Image_' . ++$i . '.' . $extension;
                file_put_contents($myFileName, $imageContents);
            }
            $id_product_marker_excel = explode('_',$spreadsheet->getActiveSheet()->getCell('C1')->getValue());
            $id_product = $id_product_marker_excel[0];
            $id_marker = $id_product_marker_excel[1];
            if($id_product == $idproduct && $id_marker == $idmarker) {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $data = [];
                $aray = [];
                for ($n = 10; $n <= $hrow; $n++) {
                    $i_material = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
                    $bagian_panel = strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue());
                    $kode_panel = strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getOldCalculatedValue());
                    $qty_penyusun = strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue());
                    $panjang = strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue());
                    $lebar = strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue());
                    $panjang_gelar = strtoupper($spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue());
                    $lebar_gelar = strtoupper($spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue());
                    $hasil_gelar = strtoupper($spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue());
                    $efficiency = strtoupper($spreadsheet->getActiveSheet()->getCell('K' . $n)->getValue());
                    $iditem = strtoupper($spreadsheet->getActiveSheet()->getCell('P' . $n)->getValue());
                    $status = strtoupper($spreadsheet->getActiveSheet()->getCell('O' . $n)->getValue());
                    if(strlen($status) > 0) {
                        $status = true;
                    } else {
                        $status = false;
                    }
                    $print = strtoupper($spreadsheet->getActiveSheet()->getCell('L' . $n)->getValue());
                    if(strlen($print) > 0) {
                        $print = true;
                    } else {
                        $print = false;
                    }
                    $bordir = strtoupper($spreadsheet->getActiveSheet()->getCell('M' . $n)->getValue());
                    if(strlen($bordir) > 0) {
                        $bordir = true;
                    } else {
                        $bordir = false;
                    }
                    $image = $spreadsheet->getActiveSheet()->getCell('N' . $n)->getValue();
                    if($i_material != '') {
                        // check material
                        $res = $this->db->query("SELECT * FROM tr_material WHERE i_material = '$i_material' AND id_company = '$this->id_company'");
                        if($res->num_rows() > 0) {
            
                            // $gambar = $spreadsheet->getActiveSheet()->getDrawingCollection();
                            // var_dump($i_material, $bagian_panel, $kode_panel, $qty_penyusun, $panjang, $lebar, $panjang_gelar, $hasil_gelar, $efficiency, $print, $bordir, $image);
                            if ($status) {
                                if ($iditem != null || $iditem != '') {
                                    $this->mmaster->updatedetail($iditem, $idproduct, $idmarker, $res->row()->id, $bagian_panel, $kode_panel, null, $qty_penyusun, $panjang, $lebar, $status, $print, $bordir, $panjang_gelar, $lebar_gelar | 0, $hasil_gelar, $efficiency, true, '');
                                } else {
                                    $this->mmaster->insertdetail($idproduct, $idmarker, $res->row()->id, $bagian_panel, $kode_panel, null, $qty_penyusun, $panjang, $lebar, $print, $bordir, $panjang_gelar, $lebar_gelar | 0, $hasil_gelar, $efficiency, true, '');
                                }
                            } else {
                                if ($iditem != null || $iditem != '') {
                                    $this->mmaster->updatestatus($iditem, $status);
                                } else {
                                    $this->mmaster->insertdetail($idproduct, $idmarker, $res->row()->id, $bagian_panel, $kode_panel, null, $qty_penyusun, $panjang, $lebar, $print, $bordir, $panjang_gelar, $lebar_gelar | 0, $hasil_gelar, $efficiency, true, '');
                                }
                            }
                            if ($this->db->trans_status() === FALSE) {
                                $this->db->trans_rollback();
                                $aray = array(
                                    'sukses' => false,
                                );
                            } else {
                                $this->db->trans_commit();
                                $this->Logger->write('Insert Data ' . $this->global['title'] . ' Id : ' . $id);
                            }
                        } else {
                            array_push($aray, ['i_material' => $i_material, 'baris_excel' => $n]);
                        }
                    }
                }
                $param = array(
                    'status' => 'berhasil',
                    'datadetail' => $aray,
                    'sama' => false
                );
                echo json_encode($param);
            } else {
                // array_push($aray, $i_material);
                $param = array(
                    'status' => 'gagal id_product tidak cocok',
                    'datadetail' => 'id_product dan id_marker harus sama dengan id_marker dan id_product di file excel',
                    'sama' => false
                );
                echo json_encode($param);
            }
        } else {
            $param = array(
                'status' => 'gagal',
                'datadetail' => 'upload gagal, pilih file terlebih dahulu',
                'sama' => false
            );
            echo json_encode($param);
        }
    }
    
    public function download()
    {
        $this->load->helper('download');
        /** Parameter */
        $idproduct = $this->uri->segment(4);
        $idmarker = $this->uri->segment(5);
        /** End Parameter */
        $nama_file = $this->id_company . "_Panel_" . $idproduct . '_' . $idmarker . ".xls";

        if(file_exists('import/panel/' . $nama_file)) {
            force_download('import/panel/' . $nama_file, null);
        } else {
            /** Style And Create New Spreedsheet */
            $spreadsheet  = new Spreadsheet;
            $sharedTitle = new Style();
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $sharedStyle4 = new Style();
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

            $sharedStyle4->applyFromArray(
                [
                    'font' => [
                        'name'   => 'Arial',
                        'italic' => false,
                        'size'   => 11,
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                ]
            );
            /** End Style */


            /** SHEET 1 */
            $abjad  = range('A', 'Z');
            $zero = 1;
            $satu = 2;
            $dua = 3;
            $tiga = 4;
            $empat = 5;
            $lima = 6;
            $enam = 7;

            // $validation = $spreadsheet->getActiveSheet()->getCell("AZ1")->getDataValidation();
            // $validation->setType(DataValidation::TYPE_DECIMAL);
            // $validation->setErrorStyle(DataValidation::STYLE_STOP);
            // $validation->setAllowBlank(true);
            // $validation->setShowInputMessage(true);
            // $validation->setShowErrorMessage(true);
            // $validation->setErrorTitle('Input error');
            // $validation->setError('Input is not allowed!');
            // $validation->setPromptTitle('Allowed input');
            // $validation->setPrompt("Only Number Value allowed");

            /** Start Sheet */
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("B$zero", "ID")
                ->setCellValue("B$satu", "Kode Product")
                ->setCellValue("B$dua", "Nama Product")
                ->setCellValue("B$tiga", "Marker")
                ->setCellValue("B$empat", "Warna")
                ->setCellValue("B$lima", "Brand")
                ->setCellValue("B$enam", "Series");
            $spreadsheet->getActiveSheet()->setTitle('Upload');

            $sql = $this->mmaster->dataedit($idproduct, $idmarker)->row();
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("C$zero", $sql->id_product_wip . '_' . $sql->id_marker)
                ->setCellValue("C$satu", $sql->i_product_wip)
                ->setCellValue("C$dua", $sql->e_product_wipname)
                ->setCellValue("C$tiga", $sql->e_marker_name)
                ->setCellValue("C$empat", $sql->e_color_name)
                ->setCellValue("C$lima", $sql->e_brand_name)
                ->setCellValue("C$enam", $sql->e_style_name);

            $validation = $spreadsheet->getActiveSheet()->getCell('K1')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Number is not allowed!');
            $validation->setPromptTitle('Allowed input');
            $validation->setPrompt("Only Value number allowed");

            $validation2 = $spreadsheet->getActiveSheet()->getCell('B5')->getDataValidation();
            $validation2->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
            $validation2->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
            $validation2->setAllowBlank(false);
            $validation2->setShowInputMessage(true);
            $validation2->setShowErrorMessage(true);
            $validation2->setShowDropDown(true);
            $validation2->setErrorTitle('Input error');
            $validation2->setError('Value is not in list.');
            $validation2->setPromptTitle('Pick from list');
            $validation2->setPrompt('Please pick a value from the drop-down list.');
            $validation2->setFormula1('"FALSE,TRUE"');

            $h = 8;
            $header = [
                "No",
                "Kode Material",
                "Bagian Panel",
                "Kode Panel",
                "Qty\nPenyusun",
                "Panjang\n(cm)",
                "Lebar\n(cm)",
                "Panjang",
                "Lebar",
                "Hasil",
                "Efficiency",
                "Print",
                "Bordir",
                "Image",
            ];
            $header2 = [
                "Gelaran\n(cm)",
                "Gelaran\n(cm)",
                "Gelaran\n(set)",
                "Marker\n(%)",
            ];
            $a = 0;
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
                if($i > 6 && $i < 11) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . ($h+1), $header2[$a]);
                    $a++;
                }
                if($abjad[$i] == 'D') {
                    $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setWidth(30);
                } else if($abjad[$i] == 'I') {
                    $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setWidth(0);
                } else {
                    $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
                }
            }


            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":N" . $h);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . ($h + 1) . ":N" . ($h + 1));
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle4, "B1:C7");
            $spreadsheet->getActiveSheet()->mergeCells('A' . $h . ':A' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('B' . $h . ':B' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('C' . $h . ':C' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('D' . $h . ':D' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('E' . $h . ':E' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('F' . $h . ':F' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('G' . $h . ':G' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('L' . $h . ':L' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('M' . $h . ':M' .  ($h+1));
            $spreadsheet->getActiveSheet()->mergeCells('N' . $h . ':N' .  ($h+1));
            $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':N' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->getStyle('H' . ($h + 1) . ':K' . ($h + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 2));
            $spreadsheet->getActiveSheet()->getStyle('A8:N9')->getAlignment()->setWrapText(true);

            $sql_item = $this->mmaster->dataeditdetail($idproduct, $idmarker);
            if($sql_item->num_rows() > 0) {
                $no = 1;
                $i = 10;
                foreach ($sql_item->result() as $row) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $no);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $row->i_material);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $row->bagian);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, '=IF(ISBLANK(B' . $i . '), "", CONCATENATE($C$2, "_", B' . $i . ', "_", C' . $i . '))');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $row->n_qty_penyusun);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $row->n_panjang_cm);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $row->n_lebar_cm);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $row->n_panjang_gelar);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $row->n_lebar_gelar);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $row->n_hasil_gelar);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $row->n_efficiency);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, ($row->f_print == 't') ? 'TRUE' : 'FALSE');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, ($row->f_bordir == 't') ? 'TRUE' : 'FALSE');
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, '');
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $i . ":N" . $i);
                    $spreadsheet->getActiveSheet()->setDataValidation("E".$i, $validation);
                    $spreadsheet->getActiveSheet()->setDataValidation("F".$i, $validation);
                    $spreadsheet->getActiveSheet()->setDataValidation("G".$i, $validation);
                    $spreadsheet->getActiveSheet()->setDataValidation("H".$i, $validation);
                    $spreadsheet->getActiveSheet()->setDataValidation("I".$i, $validation);
                    $spreadsheet->getActiveSheet()->setDataValidation("J".$i, $validation);
                    $spreadsheet->getActiveSheet()->setDataValidation("L".$i, $validation2);
                    $spreadsheet->getActiveSheet()->setDataValidation("M".$i, $validation2);
                    $i++;
                    $no++;
                }
                $spreadsheet->getActiveSheet()->getStyle('E10:K100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            }

            /** SHEET 2 */
            $abjad  = range('A', 'Z');
            $zero = 1;
            $satu = 2;
            $dua = 3;
            $tiga = 4;
            $empat = 5;
            $lima = 6;

            $spreadsheet->createSheet();
            // Zero based, so set the second tab as active sheet
            $spreadsheet->setActiveSheetIndex(1);

            /** Start Sheet */
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
            $spreadsheet->getActiveSheet()->setTitle('Master 2');

            $h = 1;
            $header = [
                "No",
                "Kode Barang",
                "Nama Barang",
                "Satuan",
                "Sub Kategori",
                "Kategori",
                "Grup Barang",
            ];
            $a = 0;
            for ($i = 0; $i < count($header); $i++) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $h, $header[$i]);
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
            }

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":G" . $h);
            $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
            $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
            $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getAlignment()->setWrapText(true);

            $j = 2;
            $i = 0;
            $no = 1;
            $sql = $this->mmaster->get_material();
            if ($sql->num_rows() > 0) {
                foreach($sql->result() as $row) {
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("A". $j, $no);
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("B". $j, $row->i_material);
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("C". $j, $row->e_material_name);
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("D". $j, $row->e_satuan_name);
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("E". $j, $row->e_type_name);
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("F". $j, $row->e_nama_kelompok);
                    $spreadsheet->setActiveSheetIndex(1)->setCellValue("G". $j, $row->e_nama_group_barang);
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $j . ":G". $j);
                    $j++;
                    $no++;
                    $i++;
                }
            }
            /* $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
            $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
            $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A'); */

            /* $spreadsheet->getActiveSheet()->getStyle("H6:H" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
            $spreadsheet->getActiveSheet()->getStyle("I6:I" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
            $spreadsheet->getActiveSheet()->getStyle("A4:I4")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED); */

            /** End Sheet */

            $writer = new Xls($spreadsheet);
            $nama_file = $this->id_company . "_Panel_" . $idproduct . "_" . $idmarker . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    public function export()
    {
        /** Parameter */
        $dfrom = $this->uri->segment(4);
        $dto = $this->uri->segment(5);
        $idproduct = $this->uri->segment(6);
        $idmarker = $this->uri->segment(7);
        $nama_file = "";
        /** End Parameter */

        /** Style And Create New Spreedsheet */
        $spreadsheet  = new Spreadsheet;
        $sharedTitle = new Style();
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStyle4 = new Style();
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

        $sharedStyle4->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'italic' => false,
                    'size'   => 11,
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]
        );
        /** End Style */


        /** SHEET 1 */
        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;
        $tiga = 4;
        $empat = 5;
        $lima = 6;
        $enam = 7;

        // $validation = $spreadsheet->getActiveSheet()->getCell("AZ1")->getDataValidation();
        // $validation->setType(DataValidation::TYPE_WHOLE);
        // $validation->setErrorStyle(DataValidation::STYLE_STOP);
        // $validation->setAllowBlank(true);
        // $validation->setShowInputMessage(true);
        // $validation->setShowErrorMessage(true);
        // $validation->setErrorTitle('Input error');
        // $validation->setError('Input is not allowed!');
        // $validation->setPromptTitle('Allowed input');
        // $validation->setPrompt("Only Number Value allowed");

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("B$zero", "ID")
            ->setCellValue("B$satu", "Kode Product")
            ->setCellValue("B$dua", "Nama Product")
            ->setCellValue("B$tiga", "Marker")
            ->setCellValue("B$empat", "Warna")
            ->setCellValue("B$lima", "Brand")
            ->setCellValue("B$enam", "Series");
        $spreadsheet->getActiveSheet()->setTitle('Upload');

        $sql = $this->mmaster->get_header_print($idproduct, $idmarker);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("C$zero", $sql->id . '_' . $sql->id_marker)
            ->setCellValue("C$satu", $sql->i_product_wip)
            ->setCellValue("C$dua", $sql->e_product_wipname)
            ->setCellValue("C$tiga", $sql->e_marker_name)
            ->setCellValue("C$empat", $sql->e_color_name)
            ->setCellValue("C$lima", $sql->e_brand_name)
            ->setCellValue("C$enam", $sql->e_style_name);

        $validation = $spreadsheet->getActiveSheet()->getCell('K1')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Number is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Value number allowed");

        $validation2 = $spreadsheet->getActiveSheet()->getCell('B5')->getDataValidation();
        $validation2->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
        $validation2->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
        $validation2->setAllowBlank(false);
        $validation2->setShowInputMessage(true);
        $validation2->setShowErrorMessage(true);
        $validation2->setShowDropDown(true);
        $validation2->setErrorTitle('Input error');
        $validation2->setError('Value is not in list.');
        $validation2->setPromptTitle('Pick from list');
        $validation2->setPrompt('Please pick a value from the drop-down list.');
        $validation2->setFormula1('"FALSE,TRUE"');

        $h = 8;
        $header = [
            "No",
            "Kode Material",
            "Bagian Panel",
            "Kode Panel",
            "Qty\nPenyusun",
            "Panjang\n(cm)",
            "Lebar\n(cm)",
            "Panjang",
            "Lebar",
            "Hasil",
            "Efficiency",
            "Print",
            "Bordir",
            "Image",
        ];
        $header2 = [
            "Gelaran\n(cm)",
            "Gelaran\n(cm)",
            "Gelaran\n(set)",
            "Marker\n(%)",
        ];
        $a = 0;
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            if($i > 6 && $i < 11) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . ($h+1), $header2[$a]);
                $a++;
            }
            if($abjad[$i] == 'D') {
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setWidth(30);
            } else if($abjad[$i] == 'I') {
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setWidth(0);
            } else {
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
            }
        }

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":N" . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . ($h + 1) . ":N" . ($h + 1));
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle4, "B1:C7");
        $spreadsheet->getActiveSheet()->mergeCells('A' . $h . ':A' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('B' . $h . ':B' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('C' . $h . ':C' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('D' . $h . ':D' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('E' . $h . ':E' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('F' . $h . ':F' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('G' . $h . ':G' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('L' . $h . ':L' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('M' . $h . ':M' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('N' . $h . ':N' .  ($h+1));
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':N' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->getStyle('H' . ($h + 1) . ':K' . ($h + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 2));
        $spreadsheet->getActiveSheet()->getStyle('A8:N9')->getAlignment()->setWrapText(true);

        for ($i = 10; $i <= 100; $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, '=IF(ISBLANK(B' . $i . '), "", CONCATENATE($C$2, "_", B' . $i . ', "_", C' . $i . '))');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, 0);
            $spreadsheet->getActiveSheet()->setDataValidation("E".$i, $validation);
            $spreadsheet->getActiveSheet()->setDataValidation("F".$i, $validation);
            $spreadsheet->getActiveSheet()->setDataValidation("G".$i, $validation);
            $spreadsheet->getActiveSheet()->setDataValidation("H".$i, $validation);
            $spreadsheet->getActiveSheet()->setDataValidation("I".$i, $validation);
            $spreadsheet->getActiveSheet()->setDataValidation("J".$i, $validation);
            $spreadsheet->getActiveSheet()->setDataValidation("L".$i, $validation2);
            $spreadsheet->getActiveSheet()->setDataValidation("M".$i, $validation2);
        }

        $spreadsheet->getActiveSheet()->getStyle('E10:K100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

        /** SHEET 2 */
        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;
        $tiga = 4;
        $empat = 5;
        $lima = 6;

        $spreadsheet->createSheet();
        // Zero based, so set the second tab as active sheet
        $spreadsheet->setActiveSheetIndex(1);

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
        $spreadsheet->getActiveSheet()->setTitle('Master 2');

        $h = 1;
        $header = [
            "No",
            "Kode Barang",
            "Nama Barang",
            "Satuan",
            "Sub Kategori",
            "Kategori",
            "Grup Barang",
        ];
        $a = 0;
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $h, $header[$i]);
            $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":G" . $h);
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getAlignment()->setWrapText(true);

        $j = 2;
        $i = 0;
        $no = 1;
        $sql = $this->mmaster->get_material();
        if ($sql->num_rows() > 0) {
            foreach($sql->result() as $row) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("A". $j, $no);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("B". $j, $row->i_material);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("C". $j, $row->e_material_name);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("D". $j, $row->e_satuan_name);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("E". $j, $row->e_type_name);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("F". $j, $row->e_nama_kelompok);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("G". $j, $row->e_nama_group_barang);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $j . ":G". $j);
                $j++;
                $no++;
                $i++;
            }
        }
        /* $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A'); */

        /* $spreadsheet->getActiveSheet()->getStyle("H6:H" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("I6:I" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("A4:I4")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED); */

        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_Panel_$idproduct_$idmarker.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function export_edit()
    {
        /** Parameter */
        $dfrom = $this->uri->segment(4);
        $dto = $this->uri->segment(5);
        $idproduct = $this->uri->segment(6);
        $idmarker = $this->uri->segment(7);
        $nama_file = "";
        /** End Parameter */

        /** Style And Create New Spreedsheet */
        $spreadsheet  = new Spreadsheet;
        $sharedTitle = new Style();
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $sharedStyle4 = new Style();
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

        $sharedStyle4->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'italic' => false,
                    'size'   => 11,
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]
        );
        /** End Style */


        /** SHEET 1 */
        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;
        $tiga = 4;
        $empat = 5;
        $lima = 6;
        $enam = 7;

        // $validation = $spreadsheet->getActiveSheet()->getCell("AZ1")->getDataValidation();
        // $validation->setType(DataValidation::TYPE_WHOLE);
        // $validation->setErrorStyle(DataValidation::STYLE_STOP);
        // $validation->setAllowBlank(true);
        // $validation->setShowInputMessage(true);
        // $validation->setShowErrorMessage(true);
        // $validation->setErrorTitle('Input error');
        // $validation->setError('Input is not allowed!');
        // $validation->setPromptTitle('Allowed input');
        // $validation->setPrompt("Only Number Value allowed");

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("B$zero", "ID")
            ->setCellValue("B$satu", "Kode Product")
            ->setCellValue("B$dua", "Nama Product")
            ->setCellValue("B$tiga", "Marker")
            ->setCellValue("B$empat", "Warna")
            ->setCellValue("B$lima", "Brand")
            ->setCellValue("B$enam", "Series");
        $spreadsheet->getActiveSheet()->setTitle('Upload');

        $sql = $this->mmaster->dataedit($idproduct, $idmarker)->row();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("C$zero", $sql->id_product_wip . '_' . $sql->id_marker)
            ->setCellValue("C$satu", $sql->i_product_wip)
            ->setCellValue("C$dua", $sql->e_product_wipname)
            ->setCellValue("C$tiga", $sql->e_marker_name)
            ->setCellValue("C$empat", $sql->e_color_name)
            ->setCellValue("C$lima", $sql->e_brand_name)
            ->setCellValue("C$enam", $sql->e_style_name);

        $validation = $spreadsheet->getActiveSheet()->getCell('K1')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_DECIMAL);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Number is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Value number allowed");

        $validation2 = $spreadsheet->getActiveSheet()->getCell('K2')->getDataValidation();
        $validation2->setType( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST );
        $validation2->setErrorStyle( \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION );
        $validation2->setAllowBlank(false);
        $validation2->setShowInputMessage(true);
        $validation2->setShowErrorMessage(true);
        $validation2->setShowDropDown(true);
        $validation2->setErrorTitle('Input error');
        $validation2->setError('Value is not in list.');
        $validation2->setPromptTitle('Pick from list');
        $validation2->setPrompt('Please pick a value from the drop-down list.');
        $validation2->setFormula1('"FALSE,TRUE"');

        $h = 8;
        $header = [
            "No",
            "Kode Material",
            "Bagian Panel",
            "Kode Panel",
            "Qty\nPenyusun",
            "Panjang\n(cm)",
            "Lebar\n(cm)",
            "Panjang",
            "Lebar",
            "Hasil",
            "Efficiency",
            "Print",
            "Bordir",
            "Image",
            "Status",
            "ID Panel"
        ];
        $header2 = [
            "Gelaran\n(cm)",
            "Gelaran\n(cm)",
            "Gelaran\n(set)",
            "Marker\n(%)",
        ];
        $a = 0;
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            if($i > 6 && $i < 11) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . ($h+1), $header2[$a]);
                $a++;
            }
            if($abjad[$i] == 'D') {
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setWidth(30);
            } else if($abjad[$i] == 'I') {
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setWidth(0);
            } else {
                $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D$dua", 'Jika ingin menghapus kode material, silahkan ubah kolom status menjadi FALSE!');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue("D$tiga", 'Kosongkan kolom ID Panel, jika akan menambah material baru!');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle4, "D$dua:H$tiga");
        $spreadsheet->getActiveSheet()->getStyle("D$dua:H$tiga")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('E9C200');

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":P" . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . ($h + 1) . ":P" . ($h + 1));
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle4, "B1:C7");
        $spreadsheet->getActiveSheet()->mergeCells('A' . $h . ':A' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('B' . $h . ':B' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('C' . $h . ':C' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('D' . $h . ':D' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('E' . $h . ':E' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('F' . $h . ':F' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('G' . $h . ':G' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('L' . $h . ':L' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('M' . $h . ':M' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('N' . $h . ':N' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('O' . $h . ':O' .  ($h+1));
        $spreadsheet->getActiveSheet()->mergeCells('P' . $h . ':P' .  ($h+1));
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':P' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->getStyle('H' . ($h + 1) . ':K' . ($h + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 2));
        $spreadsheet->getActiveSheet()->getStyle('A8:P9')->getAlignment()->setWrapText(true);

        $sql_item = $this->mmaster->dataeditdetail($idproduct, $idmarker);
        if($sql_item->num_rows() > 0) {
            $no = 1;
            $i = 10;
            foreach ($sql_item->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $no);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $row->i_material);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $row->bagian);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$i, '=IF(ISBLANK(B' . $i . '), "", CONCATENATE($C$2, "_", B' . $i . ', "_", C' . $i . '))');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $row->n_qty_penyusun);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $row->n_panjang_cm);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $row->n_lebar_cm);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $row->n_panjang_gelar);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $row->n_lebar_gelar);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $row->n_hasil_gelar);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $row->n_efficiency);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, ($row->f_print == 't') ? 'TRUE' : 'FALSE');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, ($row->f_bordir == 't') ? 'TRUE' : 'FALSE');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, '');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, ($row->f_status == 't') ? 'TRUE' : 'FALSE');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, $row->id);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $i . ":P" . $i);
                // $spreadsheet->getActiveSheet()->setDataValidation("E".$i, $validation);
                // $spreadsheet->getActiveSheet()->setDataValidation("F".$i, $validation);
                // $spreadsheet->getActiveSheet()->setDataValidation("G".$i, $validation);
                // $spreadsheet->getActiveSheet()->setDataValidation("H".$i, $validation);
                // $spreadsheet->getActiveSheet()->setDataValidation("I".$i, $validation);
                // $spreadsheet->getActiveSheet()->setDataValidation("J".$i, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("L".$i, $validation2);
                $spreadsheet->getActiveSheet()->setDataValidation("M".$i, $validation2);
                $spreadsheet->getActiveSheet()->setDataValidation("O".$i, $validation2);
                $i++;
                $no++;
            }
            $spreadsheet->getActiveSheet()->getStyle('E10:K100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            // $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
            // $spreadsheet->getDefaultStyle()->getProtection()->setLocked(FALSE);
            // $spreadsheet->getActiveSheet()->getStyle("B10:B".$i)->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
            for ($a = $i; $a <= 100; $a++) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$a, '=IF(ISBLANK(B' . $a . '), "", CONCATENATE($C$2, "_", B' . $a . ', "_", C' . $a . '))');
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $a, 0);
                $spreadsheet->getActiveSheet()->setDataValidation("E".$a, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("F".$a, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("G".$a, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("H".$a, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("I".$a, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("J".$a, $validation);
                $spreadsheet->getActiveSheet()->setDataValidation("L".$a, $validation2);
                $spreadsheet->getActiveSheet()->setDataValidation("M".$a, $validation2);
                $spreadsheet->getActiveSheet()->setDataValidation("O".$a, $validation2);
            }
        }

        /** SHEET 2 */
        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;
        $tiga = 4;
        $empat = 5;
        $lima = 6;

        $spreadsheet->createSheet();
        // Zero based, so set the second tab as active sheet
        $spreadsheet->setActiveSheetIndex(1);

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
        $spreadsheet->getActiveSheet()->setTitle('Master 2');

        $h = 1;
        $header = [
            "No",
            "Kode Barang",
            "Nama Barang",
            "Satuan",
            "Sub Kategori",
            "Kategori",
            "Grup Barang",
        ];
        $a = 0;
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(1)->setCellValue($abjad[$i] . $h, $header[$i]);
            $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":G" . $h);
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':G' . $h)->getAlignment()->setWrapText(true);

        $j = 2;
        $i = 0;
        $no = 1;
        $sql = $this->mmaster->get_material();
        if ($sql->num_rows() > 0) {
            foreach($sql->result() as $row) {
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("A". $j, $no);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("B". $j, $row->i_material);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("C". $j, $row->e_material_name);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("D". $j, $row->e_satuan_name);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("E". $j, $row->e_type_name);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("F". $j, $row->e_nama_kelompok);
                $spreadsheet->setActiveSheetIndex(1)->setCellValue("G". $j, $row->e_nama_group_barang);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $j . ":G". $j);
                $j++;
                $no++;
                $i++;
            }
        }
        /* $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A'); */

        /* $spreadsheet->getActiveSheet()->getStyle("H6:H" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("I6:I" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("A4:I4")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED); */

        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = $this->id_company . "_Panel_" . $idproduct . "_" . $idmarker . ".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }
}
/* End of file Cform.php */