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
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090110';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
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
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        $date = $this->input->post('tgl', TRUE);
        if ($date != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($date)), $this->input->post('i_bagian', TRUE));
        }
        echo json_encode($number);
    }

    public function status()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id == '') {
            $id = $this->uri->segment(4);
        }
        /* $iproductcolor = explode('|', $id);
        $iproduct = $iproductcolor[0];
        $icolor   = $iproductcolor[1]; */
        /*$id       = $iproductcolor[2];*/
        if ($id != '') {
            $this->db->trans_begin();
            $data = $this->mmaster->status(/* $iproduct, $icolor, */$id);
            if (($this->db->trans_status() === False)) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status ' . $this->global['title'] . ' ID : ' . $id);
                echo json_encode($data);
            }
        }
    }

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $format = $this->mmaster->getformat()->row();
        $no  = substr($format->i_document, 8);
        $no = (int)$no + 1;
        $num = sprintf("%04d", $no);
        $str = "SJ-" . date("ym") . "-" . $num;

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'format'     => $str,
            'bagian'     => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function productwip()
    {
        $filter = [];
        $id_referensi = $this->input->get('id_referensi');
        if ($id_referensi != '') {
            # code...
            $data   = $this->mmaster->productwip(str_replace("'", "", $this->input->get('q')), $id_referensi);
            if ($data->num_rows() > 0) {
                foreach ($data->result() as  $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname,
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data",
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih Referensi !!!",
            );
        }
        echo json_encode($filter);
    }

    public function getbagian()
    {
        $filter = [];
        $data   = $this->mmaster->bagianpembuat(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->i_bagian,
                    'text' => $row->e_bagian_name,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getkategori()
    {
        $filter = [];
        $data   = $this->mmaster->getkategori(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_nama_kategori,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getunit()
    {
        $filter      = [];
        $cari        = str_replace("'", "", $this->input->get('q'));
        $kategori    = $this->input->get('kategori');
        $data   = $this->mmaster->getunit($cari, $kategori);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_nama_unit,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }


    public function productwipref()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $i_product_wip  = explode('|', $this->input->get('i_product_wip', TRUE))[0];
        $i_color        = explode('|', $this->input->get('i_product_wip', TRUE))[1];
        $data           = $this->mmaster->productwipref($cari, $i_product_wip, $i_color);
        if ($i_product_wip != '') {
            if ($data->num_rows() > 0) {
                foreach ($data->result() as  $row) {
                    $filter[] = array(
                        'id'   => $row->i_product_wip . '|' . $row->i_color,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - [' . $row->e_color_name . ']',
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data",
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih Product WIP",
            );
        }
        echo json_encode($filter);
    }

    public function get_bisbisan()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $i_material     = $this->input->get('i_material', false);
        $data           = $this->mmaster->get_bisbisan($cari, $i_material);
        if ($i_material != '') {
            if ($data->num_rows() > 0) {
                foreach ($data->result() as  $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->n_bisbisan . ' - ' . $row->e_jenis_potong,
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data",
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih Product Terlebih Dahulu",
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/

    public function getdetailref()
    {
        header("Content-Type: application/json", true);
        $i_product_wip  = explode('|', $this->input->post('i_product_wip', TRUE))[0];
        $i_color        = explode('|', $this->input->post('i_product_wip', TRUE))[1];
        $query  = array(
            'detail' => $this->mmaster->getdetailref($i_product_wip, $i_color)->result_array(),
        );
        echo json_encode($query);
    }

    /*----------  GET DETAIL MATERIAL  ----------*/

    public function getdetailmaterial()
    {
        header("Content-Type: application/json", true);
        $i_material  = $this->input->post('i_material', TRUE);
        $query  = array(
            'detail' => $this->mmaster->getdetailmaterial($i_material)->result_array(),
        );
        echo json_encode($query);
    }

    public function material()
    {
        $filter = [];
        $data   = $this->mmaster->material(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->i_material,
                    'text' => $row->i_material . ' - ' . $row->e_material_name . ' - ' . $row->e_satuan_name,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    /** Tambah Dari Referensi Uraian Jahit */
    public function tambah_referensi()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        if ($this->mmaster->getformat()->num_rows() > 0) {
            # code...
            $format = $this->mmaster->getformat()->row();
            $no  = substr($format->i_document, 8);
            $no = (int)$no + 1;
            $num = sprintf("%04d", $no);
            $str = "SJ-" . date("ym") . "-" . $num;
        } else {
            $str = "SJ-" . date("ym") . "-0001";
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'format'     => $str,
            'bagian'     => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']) . ' Dari Referensi';

        $this->load->view($this->global['folder'] . '/vformadd_referensi', $data);
    }

    /** GET REFERENSI URAIAN JAHIT */
    public function get_referensi()
    {
        $filter = [];
        $cari = str_replace("'", "", $this->input->get('q'));
        $ibagian = $this->input->get('ibagian');
        $data = $this->mmaster->get_data_referensi($cari, $ibagian);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id,
                'text'  => $row->i_document . ' - [' . $row->periode . '] - ' . $row->company_name_doc . ' - ' . substr($row->e_remark, 0, 50),
            );
        }
        echo json_encode($filter);
    }

    /** GET DETAIL REFERENSI URAIAN JAHIT */
    public function get_detail_referensi()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id');
        $ibagian = $this->input->post('ibagian');
        $data = array(
            'detail'   => $this->mmaster->get_detail_referensi($id, $ibagian)->result_array()
        );
        echo json_encode($data);
    }

    public function det_detail_product()
    {
        header("Content-Type: application/json", true);
        $id_product  = $this->input->post('id_product', TRUE);
        $id_referensi  = $this->input->post('id_referensi', TRUE);
        $query  = array(
            'detail' => $this->mmaster->get_detail_wip($id_product, $id_referensi)->result_array(),
        );
        echo json_encode($query);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument         = $this->input->post('idocument', TRUE);
        $id_referensi   = $this->input->post('id_uraian', TRUE);
        $id_company_referensi = $this->db->get_where('tm_uraianjahit', ['id' => $id_referensi])->row()->id_company;
        $ddocument         = $this->input->post('ddocument', TRUE);
        $tmp   = explode('-', $ddocument);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year . $month;
        $ddocument = $year . '-' . $month . '-' . $day;
        $ibagian        = $this->input->post('ibagian', TRUE);
        $keterangan     = $this->input->post('keterangan', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $group_jahit    = $this->input->post('group_jahit', TRUE);
        if ($jml > 0) {
            $id = $this->mmaster->runningid();
            $this->db->trans_begin();
            $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $keterangan, $id_referensi, $group_jahit, $id_company_referensi);
            for ($i = 0; $i < $jml; $i++) {
                /* $tanggal     = $this->input->post('tanggal'.$i, TRUE);
                $tmp         = explode('-', $tanggal);
                $day         = $tmp[0];
                $month       = $tmp[1];
                $year        = $tmp[2];
                $yearmonth   = $year . $month;
                $tanggal     = $year . '-' . $month . '-' . $day;
                $ibarang     = $this->input->post('ibarang'.$i, TRUE);
                $tmpbarang   = explode('|', $ibarang);
                $ibarang     = $tmpbarang[0];
                $icolor      = $tmpbarang[1];
                $nqty        = $this->input->post('nqty'.$i, TRUE);
                $ikategori   = $this->input->post('ikategori'.$i, TRUE);
                $iunit       = $this->input->post('iunit'.$i, TRUE);
                $eremark     = $this->input->post('eremark'.$i, TRUE); */

                $id_product   = $this->input->post('idproduct' . $i, TRUE);
                $n_schedule_jahit = str_replace(",", "", $this->input->post('n_schedule_jahit' . $i, TRUE));
                $e_note = $this->input->post('e_note' . $i, TRUE);
                $f_uraian_jahit = $this->input->post('f_uraian_jahit' . $i, TRUE);
                $d_schedule = $this->input->post('d_schedule' . $i, TRUE);
                if ($d_schedule != '') {
                    $d_schedule = formatYmd($d_schedule);
                }
                if ($id_product != '' || $id_product != null) {
                    $this->mmaster->insertdetail($id, $id_product, $n_schedule_jahit, $e_note, $f_uraian_jahit, $d_schedule);
                }
            }
            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => '',
                    'id'     => ''
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $idocument,
                    'id'      => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode Dokumen : ' . $idocument);
            }
        } else {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
                'kode'   => '',
                'id'     => ''
            );
        }
        // $this->load->view('pesan', $data);   
        echo json_encode($data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        if ($this->mmaster->getformat()->num_rows() > 0) {
            # code...
            $format = $this->mmaster->getformat()->row();
            $no  = substr($format->i_document, 8);
            $no = (int)$no + 1;
            $num = sprintf("%04d", $no);
            $str = "SJ-" . date("ym") . "-" . $num;
        } else {
            $str = "SJ-" . date("ym") . "-0001";
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'format'     => $str,
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4))->result(),
            'bagian'     => $this->mmaster->bagian()->result(),
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

        $id          = $this->input->post('id', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $id_referensi = $this->input->post('id_uraian', TRUE);
        $id_company_referensi = $this->db->get_where('tm_uraianjahit', ['id' => $id_referensi])->row()->id_company;
        $ddocument      = $this->input->post('ddocument', TRUE);
        $tmp         = explode('-', $ddocument);
        $day         = $tmp[0];
        $month       = $tmp[1];
        $year        = $tmp[2];
        $yearmonth   = $year . $month;
        $ddocument   = $year . '-' . $month . '-' . $day;
        $ibagian     = $this->input->post('ibagian', TRUE);
        $keterangan  = $this->input->post('keterangan', TRUE);
        //$jml            = $this->input->post('jml', TRUE);
        $tanggal     = $this->input->post('tanggal', TRUE);
        $ibarang     = $this->input->post('ibarang', TRUE);
        $nqty        = $this->input->post('nqty', TRUE);
        $ikategori   = $this->input->post('ikategori', TRUE);
        $iunit       = $this->input->post('iunit', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);
        $group_jahit = $this->input->post('group_jahit', TRUE);
        // $count       = count($tanggal);
        if ($jml > 0) {
            $this->db->trans_begin();
            // $this->mmaster->updateheader($idocument, $ddocument, $ibagian, $keterangan);
            $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $keterangan, $id_referensi, $group_jahit, $id_company_referensi);
            $this->mmaster->deletedetail($id);
            /* for ($i=0; $i < $count; $i++) { 
                $itanggal     = $tanggal[$i];
                $itmp         = explode('-', $itanggal);
                $iday         = $itmp[0];
                $imonth       = $itmp[1];
                $iyear        = $itmp[2];
                $iyearmonth   = $iyear . $imonth;
                $itanggal     = $iyear . '-' . $imonth . '-' . $iday;
                $iibarang     = $ibarang[$i];
                $itmpbarang   = explode('|', $iibarang);
                $iibarang     = $itmpbarang[0];
                $iicolor      = $itmpbarang[1];
                $inqty        = $nqty[$i];
                $iikategori    = $ikategori[$i];
                $iiunit        = $iunit[$i];
                $ieremark     = $eremark[$i];
                $this->mmaster->updatedetail($idocument, $itanggal, $iibarang, $iicolor, $inqty, $iikategori, $iiunit, $ieremark);
            } */
            for ($i = 0; $i < $jml; $i++) {
                $id_product   = $this->input->post('idproduct' . $i, TRUE);
                $n_schedule_jahit = str_replace(",", "", $this->input->post('n_schedule_jahit' . $i, TRUE));
                $e_note = $this->input->post('e_note' . $i, TRUE);
                $f_uraian_jahit = $this->input->post('f_uraian_jahit' . $i, TRUE);
                $d_schedule = $this->input->post('d_schedule' . $i, TRUE);
                if ($d_schedule != '') {
                    $d_schedule = formatYmd($d_schedule);
                }
                if ($id_product != '' || $id_product != null) {
                    $this->mmaster->insertdetail($id, $id_product, $n_schedule_jahit, $e_note, $f_uraian_jahit, $d_schedule);
                }
            }
            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'    => $idocument
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $idocument
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode Dokumen : ' . $idocument);
            }
        } else {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
                'kode'    => $idocument
            );
        }
        // $this->load->view('pesan', $data);   
        echo json_encode($data);
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function cancel_approval()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function upload()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Upload " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
        );

        $this->Logger->write('Membuka Menu Upload ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformupload', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

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

    public function download()
    {
        /* $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } */
        $header = $this->mmaster->dataheader($this->uri->segment(4))->row();
        $query = $this->mmaster->detail($this->uri->segment(4));
        if ($query->num_rows() > 0) {

            $spreadsheet = new Spreadsheet;
            $title = new Style();
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
                ]
            );

            $sharedStyle1->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                ]
            );

            $title->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => true,
                        'italic' => false,
                        'size'  => 12
                    ],
                ]
            );

            $sharedStyle2->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
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
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
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
            foreach (range('A', 'X') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Schedule Jahit');
            $spreadsheet->getActiveSheet()->duplicateStyle($title, 'A1');
            $spreadsheet->getActiveSheet()->setTitle('Schedule Jahit');
            $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Hari')
                ->setCellValue('C2', 'Tanggal')
                ->setCellValue('D2', 'Kode Barang')
                ->setCellValue('E2', 'Nama Barang')
                ->setCellValue('F2', 'Qty')
                ->setCellValue('G2', 'Warna')
                ->setCellValue('H2', 'Kategori Unit')
                ->setCellValue('I2', 'Unit Jahit')
                ->setCellValue('J2', 'Group');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:J2');
            $sheet = $spreadsheet->getActiveSheet();
            $validation = $sheet->getCell("P3")->getDataValidation();
            $validation
                ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setFormula1('"TRUE,FALSE"')
                ->setAllowBlank(false)
                ->setShowDropDown(true)
                ->setShowInputMessage(true)
                ->setPromptTitle("Note")
                ->setPrompt("Must select one from the drop down options.")
                ->setShowErrorMessage(true)
                ->setErrorStyle(
                    \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
                )
                ->setErrorTitle("Invalid option")
                ->setError("Select one from the drop down list.");

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $hari  = date('D', strtotime($row->d_schedule));
                if ($hari == 'Mon') {
                    $hari = 'Senin';
                } else
                if ($hari == 'Tue') {
                    $hari = 'Selasa';
                } else
                if ($hari == 'Wed') {
                    $hari = 'Rabu';
                } else
                if ($hari == 'Thu') {
                    $hari = 'Kamis';
                } else
                if ($hari == 'Fri') {
                    $hari = 'Jumat';
                } else
                if ($hari == 'Sat') {
                    $hari = 'Sabtu';
                } else
                if ($hari == 'Sun') {
                    $hari = 'Minggu';
                }
                /* $f_auto_cutter = ($row->f_auto_cutter == 't') ? 'Auto Cutter' : 'Manual'; */
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $hari)
                    ->setCellValue('C' . $kolom, $row->d_schedule)
                    ->setCellValue('D' . $kolom, $row->i_product_wip)
                    ->setCellValue('E' . $kolom, $row->e_product_wipname)
                    ->setCellValue('F' . $kolom, $row->n_quantity_wip)
                    ->setCellValue('G' . $kolom, $row->e_color_name)
                    ->setCellValue('H' . $kolom, $row->e_nama_kategori)
                    ->setCellValue('I' . $kolom, $row->e_nama_unit)
                    ->setCellValue('J' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':J' . $kolom);
                $kolom++;
                $nomor++;
            }
            $sheet->setDataValidation("P3:P" . $kolom, $validation);
            $writer = new Xls($spreadsheet);
            $nama_file = "Jadwal_jahit.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        } else {
            echo "<center><h1>Tidak Ada Data :)</h1></center>";
        }
    }

    public function realisasi()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        if ($this->mmaster->getformat()->num_rows() > 0) {
            $format = $this->mmaster->getformat()->row();
            $no  = substr($format->i_document, 8);
            $no = (int)$no + 1;
            $num = sprintf("%04d", $no);
            $str = "SJ-" . date("ym") . "-" . $num;
        } else {
            $str = "SJ-" . date("ym") . "-0001";
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Realisasi " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'format'     => $str,
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            // 'detail'     => $this->mmaster->detail($this->uri->segment(4))->result(),
            'detail'     => $this->mmaster->detail_item($this->uri->segment(4))->result(),
            'bagian'     => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformrealisasi', $data);
    }

    public function product()
    {
        $filter = [];
        $data   = $this->mmaster->product(str_replace("'", "", $this->input->get('q')), $this->input->get('id_company_referensi'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function get_detail_product()
    {
        header("Content-Type: application/json", true);
        $id_product  = $this->input->post('id_product', TRUE);
        $query  = array(
            'detail' => $this->mmaster->get_detail_product($id_product)->result_array(),
        );
        echo json_encode($query);
    }

    public function realisasi_act()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id          = $this->input->post('id', TRUE);
        $idocument   = $this->input->post('idocument', TRUE);
        $jml         = $this->input->post('jml_detail', TRUE);
        if ($jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->delete_detail($id);
            for ($i = 0; $i < $jml; $i++) {
                $id_item  = $this->input->post('id_item_sc' . $i, TRUE);
                $id_product  = $this->input->post('id_product' . $i, TRUE);
                $n_realisasi = $this->input->post('n_realisasi' . $i, TRUE);
                $e_note      = $this->input->post('e_note' . $i, TRUE);
                if (($id_item != '' || $id_item != null) && ($id_product != '' || $id_product != null) && $n_realisasi > 0) {
                    $this->mmaster->insert_realisasi($id_item, $id_product, $n_realisasi, $e_note);
                }
            }
            if ($this->db->trans_status() === False) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'    => $idocument
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $idocument
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode Dokumen : ' . $idocument);
            }
        } else {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
                'kode'    => $idocument
            );
        }
        echo json_encode($data);
    }

    public function export()
    {
        /** Parameter */
        $id = $this->uri->segment(6);
        $ddocument = $this->db->query("select to_date(i_periode, 'yyyymm') as d_document  from tm_uraianjahit where id = $id ")->row();  

        if($ddocument){
            $ddocument = $ddocument->d_document;
        } else {
            $ddocument = $this->uri->segment(7);
        }
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

        $abjadBanyak = array();
        foreach (excelColumnRange('A', 'ZZ') as $value) {
            array_push($abjadBanyak, $value);
        }
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

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A$zero", $id)
            ->setCellValue("A$satu", strtoupper($this->session->e_company_name))
            ->setCellValue("A$dua", "FORMAT UPLOAD SCHEDULE JAHIT");
        $spreadsheet->getActiveSheet()->setTitle('Format Schedule Jahit');
        /* $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getStyle('A5')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle('B10')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED); */

        $validation = $spreadsheet->getActiveSheet()->getCell('AZ1')->getDataValidation();
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
            'KODE BARANG',
            'NAMA BARANG',
            'WARNA',
            'URAIAN JAHIT',
            'SISA URAIAN JAHIT',
        ];
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
        }
        $hari = date('t', strtotime($ddocument));
        // $firstMonth = date('m-t', strtotime( date("Y-m-01", strtotime( $ddocument ) ) . "-12 day" ));
        // $firstMonthDay = date("d", strtotime( date("Y-m-01", strtotime( $ddocument ) ) . "-12 day" ) );
        // $firstMonthM = date("m", strtotime( date("Y-m-01", strtotime( $ddocument ) ) . "-12 day" ) );
        $firstDay = 20;
        $firstMonthM = date('m', strtotime("-1 month", strtotime( $ddocument )));
        $firstPeriode = date('Y-m', strtotime("-1 month", strtotime( $ddocument )));
        $lastDayMonthBefore = date('t', strtotime("-1 month", strtotime( $ddocument )));
        $selisih = $lastDayMonthBefore - $firstDay;
        for ($a = 0; $a <= ($hari + $selisih); $a++) {
            $day = $firstDay+$a;
            $d = DateTime::createFromFormat('m-d', "$firstMonthM-$day");
            $date = $d->format('d');
            $cHeader = count($header);
            $month = $d->format('F');
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header))] . 2, (string) $d->format('Y-m'));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header))] . 3, (string) $d->format('D'));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header))] . 1, (string) strtoupper($month));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header))] . 4, (string) $date);
            $spreadsheet->getActiveSheet()->getColumnDimension($abjadBanyak[$a + (count($header))])->setWidth(7);
            // $spreadsheet->getActiveSheet()->getColumnDimension($abjadBanyak[$a + (count($header) - 1)])->setAutoSize(true);
            // if(explode('-',$firstMonth)[1] == 31) {
            //     $selisih = 0;
            // } else if (explode('-',$firstMonth)[1] == 30) {
            //     $selisih = 1;
            // } else if (explode('-',$firstMonth)[1] == 29) {
            //     $selisih = 2;
            // } else if (explode('-',$firstMonth)[1] == 28) {
            //     $selisih = 3;
            // }
        }
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

        // echo $abjadBanyak[count($header) + ($hari - (15 + $selisih)) + 1];
        // die;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . 3 . ":" . $abjadBanyak[count($header) + (($hari + $selisih))] . 3);
        $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . 3 . ":" . $abjadBanyak[count($header) + (($hari + $selisih))] . 3)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . 2 . ":" . $abjadBanyak[count($header) + (($hari + $selisih))] . 2);
        $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . 2 . ":" . $abjadBanyak[count($header) + (($hari + $selisih))] . 2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . 1 . ":" . $abjadBanyak[count($header) + (($hari + $selisih))] . 1);
        $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . 1 . ":" . $abjadBanyak[count($header) + (($hari + $selisih))] . 1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->mergeCells($abjadBanyak[count($header)] . 1 . ":" . $abjadBanyak[count($header) + $selisih] . 1);
        $spreadsheet->getActiveSheet()->mergeCells($abjadBanyak[count($header) + $selisih + 1] . 1 . ":" . $abjadBanyak[count($header) + ($hari + $selisih)] . 1);


        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . $h . ":" . $abjadBanyak[count($header) + ($hari+$selisih)] . $h);
        $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . $h . ":" . $abjadBanyak[count($header) + ($hari+$selisih)] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[7] . ($h + 1));
        $spreadsheet->getActiveSheet()->setAutoFilter($abjad[0] . $h . ":" . $abjadBanyak[count($header) + ($hari+$selisih)] . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $dua);
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        // $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
        $j = 5;
        $x = 5;
        $no = 0;
        $sql = $this->mmaster->get_export($id);
        if ($sql->num_rows() > 0) {
            foreach ($sql->result() as $row) {
                $no++;
                $isi = [
                    $no, $row->id, $row->i_product_wip, $row->e_product_wipname, $row->e_color_name, $row->n_quantity, $row->n_quantity_sisa,
                ];
                for ($i = 0; $i < count($isi); $i++) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
                }
                $j++;
            }
        }
        $y = $j - 1;
        // $spreadsheet->getActiveSheet()->getStyle('A2:B2')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjadBanyak[count($header)] . $x . ":" . $abjadBanyak[count($header) + ($hari + $selisih)] . $y);
        $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . $j . ":" . $abjadBanyak[count($header) + ($hari + $selisih)] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        for ($n = 5; $n <= $y; $n++) {
            $spreadsheet->getActiveSheet()->setDataValidation($abjadBanyak[count($header)] . $n . ":" . $abjadBanyak[count($header) + ($hari + $selisih)] . $n, $validation);
            $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . $n . ":" . $abjadBanyak[count($header) + ($hari + $selisih)] . $n)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        }

        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_Schedule_Uraian_$id.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    // public function export()
    // {
    //     /** Parameter */
    //     $id = $this->uri->segment(6);
    //     $ddocument = $this->uri->segment(7);
    //     $dsplit = explode('-',$ddocument);
    //     $nama_file = "";
    //     /** End Parameter */

    //     /** Style And Create New Spreedsheet */
    //     $spreadsheet  = new Spreadsheet;
    //     $sharedTitle = new Style();
    //     $sharedStyle1 = new Style();
    //     $sharedStyle2 = new Style();
    //     $sharedStyle3 = new Style();
    //     /* $conditional3 = new Conditional(); */
    //     $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
    //         [
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
    //         ]
    //     );

    //     $sharedTitle->applyFromArray(
    //         [
    //             'alignment' => [
    //                 'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             ],
    //             'font' => [
    //                 'name'   => 'Arial',
    //                 'bold'   => true,
    //                 'size'   => 26
    //             ],
    //         ]
    //     );

    //     $sharedStyle1->applyFromArray(
    //         [
    //             'alignment' => [
    //                 'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             ],
    //             'font' => [
    //                 'name'   => 'Arial',
    //                 'bold'   => true,
    //                 'italic' => false,
    //                 'size'   => 14
    //             ],
    //         ]
    //     );

    //     $sharedStyle2->applyFromArray(
    //         [
    //             'font' => [
    //                 'name'   => 'Arial',
    //                 'bold'   => false,
    //                 'italic' => false,
    //                 'size'   => 11
    //             ],
    //             'borders' => [
    //                 'left'   => ['borderStyle' => Border::BORDER_THIN],
    //                 'right'  => ['borderStyle' => Border::BORDER_THIN]
    //             ],
    //         ]

    //     );

    //     $sharedStyle3->applyFromArray(
    //         [
    //             'font' => [
    //                 'name'   => 'Arial',
    //                 'bold'   => true,
    //                 'italic' => false,
    //                 'size'   => 11,
    //             ],
    //             'borders' => [
    //                 'top'    => ['borderStyle' => Border::BORDER_THIN],
    //                 'bottom' => ['borderStyle' => Border::BORDER_THIN],
    //                 'left'   => ['borderStyle' => Border::BORDER_THIN],
    //                 'right'  => ['borderStyle' => Border::BORDER_THIN]
    //             ],
    //             'alignment' => [
    //                 'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //                 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             ],
    //         ]
    //     );
    //     /** End Style */

    //     $abjad  = range('A', 'Z');
    //     $zero = 1;
    //     $satu = 2;
    //     $dua = 3;

    //     $abjadBanyak = array();
    //     foreach (excelColumnRange('A', 'ZZ') as $value) {
    //         array_push($abjadBanyak, $value);
    //     }
    //     /** Start Sheet */
    //     $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
    //     $spreadsheet->setActiveSheetIndex(0)
    //         ->setCellValue("A$zero", $id)
    //         ->setCellValue("A$satu", strtoupper($this->session->e_company_name))
    //         ->setCellValue("A$dua", "FORMAT UPLOAD SCHEDULE JAHIT");
    //     $spreadsheet->getActiveSheet()->setTitle('Format Schedule Jahit');
    //     /* $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
    //     $spreadsheet->getActiveSheet()->getStyle('A5')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);
    //     $spreadsheet->getActiveSheet()->getStyle('B10')->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED); */

    //     $validation = $spreadsheet->getActiveSheet()->getCell('A1')->getDataValidation();
    //     $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
    //     $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
    //     $validation->setAllowBlank(true);
    //     $validation->setShowInputMessage(true);
    //     $validation->setShowErrorMessage(true);
    //     $validation->setErrorTitle('Input error');
    //     $validation->setError('Number is not allowed!');
    //     $validation->setPromptTitle('Allowed input');
    //     $validation->setPrompt("Only Value $id allowed");
    //     $validation->setFormula1($id);
    //     $validation->setFormula2($id);

    //     $h = 4;
    //     $header = [
    //         '#',
    //         'ID BARANG',
    //         'KODE BARANG',
    //         'NAMA BARANG',
    //         'WARNA',
    //         'URAIAN JAHIT',
    //         'SISA URAIAN JAHIT',
    //     ];
    //     for ($i = 0; $i < count($header); $i++) {
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
    //     }
    //     $hari = 45;
    //     $firstMonth = date('m-t', strtotime($ddocument));
    //     $selisih = 0;
    //     for ($a = 1; $a <= $hari; $a++) {
    //         $d = DateTime::createFromFormat('m-d', "$dsplit[1]-$a");
    //         $date = $d->format('d');
    //         $cHeader = count($header);
    //         $month = $d->format('F');
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header) - 1)] . 2, (string) $d->format('Y-m'));
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header) - 1)] . 3, (string) $d->format('D'));
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header) - 1)] . 1, (string) strtoupper($month));
    //         $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjadBanyak[$a + (count($header) - 1)] . 4, (string) $date);
    //         $spreadsheet->getActiveSheet()->getColumnDimension($abjadBanyak[$a + (count($header) - 1)])->setWidth(7);
    //         // $spreadsheet->getActiveSheet()->getColumnDimension($abjadBanyak[$a + (count($header) - 1)])->setAutoSize(true);
    //         if(explode('-',$firstMonth)[1] == 31) {
    //             $selisih = 0;
    //         } else if (explode('-',$firstMonth)[1] == 30) {
    //             $selisih = 1;
    //         } else if (explode('-',$firstMonth)[1] == 29) {
    //             $selisih = 2;
    //         } else if (explode('-',$firstMonth)[1] == 28) {
    //             $selisih = 3;
    //         }
    //     }
    //     $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

    //     // echo $abjadBanyak[count($header) + ($hari - (15 + $selisih)) + 1];
    //     // die;
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . 3 . ":" . $abjadBanyak[count($header) + ($hari - 1)] . 3);
    //     $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . 3 . ":" . $abjadBanyak[count($header) + ($hari-1)] . 3)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . 2 . ":" . $abjadBanyak[count($header) + ($hari - 1)] . 2);
    //     $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . 2 . ":" . $abjadBanyak[count($header) + ($hari-1)] . 2)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . 1 . ":" . $abjadBanyak[count($header) + ($hari - 1)] . 1);
    //     $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . 1 . ":" . $abjadBanyak[count($header) + ($hari-1)] . 1)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
    //     $spreadsheet->getActiveSheet()->mergeCells($abjadBanyak[count($header)] . 1 . ":" . $abjadBanyak[count($header) + ($hari - (15 + $selisih))] . 1);
    //     $spreadsheet->getActiveSheet()->mergeCells($abjadBanyak[count($header) + ($hari - (15 + $selisih)) + 1] . 1 . ":" . $abjadBanyak[count($header) + ($hari-1)] . 1);


    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjadBanyak[count($header)] . $h . ":" . $abjadBanyak[count($header) + ($hari - 1)] . $h);
    //     $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . $h . ":" . $abjadBanyak[count($header) + ($hari-1)] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
    //     $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $dua);
    //     $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $satu . ":" . $abjad[count($header) - 1] . $satu);
    //     $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $dua . ":" . $abjad[count($header) - 1] . $dua);
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
    //     $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
    //     // $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
    //     $j = 5;
    //     $x = 5;
    //     $no = 0;
    //     $sql = $this->mmaster->get_export($id);
    //     if ($sql->num_rows() > 0) {
    //         foreach ($sql->result() as $row) {
    //             $no++;
    //             $isi = [
    //                 $no, $row->id, $row->i_product_wip, $row->e_product_wipname, $row->e_color_name, $row->n_quantity, $row->n_quantity_sisa,
    //             ];
    //             for ($i = 0; $i < count($isi); $i++) {
    //                 $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $j, $isi[$i]);
    //             }
    //             $j++;
    //         }
    //     }
    //     $y = $j - 1;
    //     // $spreadsheet->getActiveSheet()->getStyle('A2:B2')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjad[0] . $x . ":" . $abjad[count($header) - 1] . $y);
    //     $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //     $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, $abjadBanyak[count($header)] . $x . ":" . $abjadBanyak[count($header) + ($hari -1)] . $y);
    //     $spreadsheet->getActiveSheet()->getStyle($abjadBanyak[count($header)] . $j . ":" . $abjadBanyak[count($header) + ($hari -1)] . $j)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    //     /** End Sheet */

    //     $writer = new Xls($spreadsheet);
    //     $nama_file = "Format_Schedule_Uraian_$id.xls";
    //     header('Content-Type: application/vnd.ms-excel');
    //     header('Content-Disposition: attachment;filename=' . $nama_file . '');
    //     header('Cache-Control: max-age=0');
    //     ob_end_clean();
    //     ob_start();
    //     $writer->save('php://output');
    // }

    // public function load()
    // {
    //     $data = check_role($this->i_menu, 1);
    //     if (!$data) {
    //         redirect(base_url(), 'refresh');
    //     }

    //     /* $ibagian = $this->input->post('ibagian', TRUE);
    //     $i_so = $this->input->post('i_so', TRUE);
    //     $ddocument = $this->input->post('ddocument', TRUE);
    //     $dfrom = $this->input->post('dfrom', TRUE);
    //     $dto = $this->input->post('dto', TRUE); */
    //     $id_uraian = $this->input->post('id_uraian');
    //     $filename = $this->id_company . "_Schedule_" . $id_uraian . ".xls";
    //     $aray = array();

    //     $config = array(
    //         'upload_path'   => "./import/scjahit/",
    //         'allowed_types' => "xls",
    //         'file_name'     => $filename,
    //         'overwrite'     => true
    //     );

    //     $this->load->library('upload', $config);
    //     if ($this->upload->do_upload("userfile")) {
    //         $data = array('upload_data' => $this->upload->data());

    //         $inputFileName = "./import/scjahit/" . $filename;
    //         $spreadsheet   = IOFactory::load($inputFileName);
    //         $worksheet     = $spreadsheet->getActiveSheet();
    //         $sheet         = $spreadsheet->getSheet(0);
    //         $hrow          = $sheet->getHighestDataRow('A');
    //         $id_referensi  = $spreadsheet->getActiveSheet()->getCell('A1')->getValue();
    //         if ($id_referensi == (int)$id_uraian) {
    //             for ($n = 5; $n <= $hrow; $n++) {
    //                 $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
    //                 $n_schedule = (int)$spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue();
    //                 $d_schedule = $spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue();
    //                 if ($d_schedule != '') {
    //                     $d_schedule = date('d-m-Y', ($d_schedule - 25569) * 86400);
    //                     /* $d_schedule = $d_schedule; */
    //                 };
    //                 $e_remark = $spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue();
    //                 $e_remark = ($e_remark == null) ? '' : $e_remark;
    //                 if ($n_schedule > 0 && $id_product != "") {
    //                     $id_color = $this->db->query("SELECT id FROM tr_color WHERE i_color IN (SELECT i_color FROM tr_product_wip WHERE id = '$id_product') AND id_company = '$this->id_company' ")->row()->id;
    //                     $aray[] = array(
    //                         'id'                => $id_product,
    //                         'i_product_wip'     => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
    //                         'e_product_wipname' => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
    //                         'e_color_name'      => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
    //                         'd_schedule'        => $d_schedule,
    //                         'n_uraian_jahit'    => $this->mmaster->get_detail_wip($id_product, $id_referensi)->row()->n_quantity,
    //                         'n_schedule'        => $n_schedule,
    //                         'id_color'          => $id_color,
    //                         'e_remark'          => $e_remark,
    //                     );
    //                 }
    //             }
    //             usort($aray, function ($b, $a) {
    //                 return $b['d_schedule'] <=> $a['d_schedule'];
    //             });
    //             $param = array(
    //                 'folder'        => $this->global['folder'],
    //                 'title'         => "Tambah " . $this->global['title'],
    //                 'title_list'    => $this->global['title'],
    //                 'datadetail'    => $aray,
    //                 'status'        => 'berhasil',
    //                 'sama'          => true
    //             );
    //         } else {
    //             $param = array(
    //                 'folder'        => $this->global['folder'],
    //                 'title'         => "Tambah " . $this->global['title'],
    //                 'title_list'    => $this->global['title'],
    //                 'datadetail'    => $aray,
    //                 'status'        => 'gagal',
    //                 'sama'          => false
    //             );
    //         }
    //         echo json_encode($param);
    //     } else {
    //         $param =  array(
    //             'status'        => 'gagal',
    //             'datadetail'    => $aray,
    //             'sama'          => false
    //         );
    //         echo json_encode($param);
    //     }
    // }

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
        $abjadBanyak = array();
        foreach (excelColumnRange('A', 'ZZ') as $value) {
            array_push($abjadBanyak, $value);
        }
        $cellMulaiTanggal = 7;
        $id_uraian = $this->input->post('id_uraian');
        $filename = $this->id_company . "_Schedule_" . $id_uraian . ".xls";
        $aray = array();

        $config = array(
            'upload_path'   => "./import/scjahit/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());

            $inputFileName = "./import/scjahit/" . $filename;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestDataRow('A');
            $id_referensi  = $spreadsheet->getActiveSheet()->getCell('A1')->getValue();
            $data = [];
            $data2 = [];
            if ($id_referensi == (int)$id_uraian) {
                for ($a = 8; $a < 52; $a++) {
                    for ($n = 5; $n <= $hrow; $n++) {
                        $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
                        $n_schedule = (int)$spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue();
                        $d_schedule = $spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue();
                        $d_schedule2 = $spreadsheet->getActiveSheet()->getCell($abjadBanyak[$a - 1] . 4)->getValue();
                        $d_schedule3 = $spreadsheet->getActiveSheet()->getCell($abjadBanyak[$a - 1] . 2)->getValue();
                        // $spreadsheet->getActiveSheet()->getCell($abjadBanyak[count($header)] . $n . ":" . $abjadBanyak[count($header) + ($hari - 1)] . $n)->getValue();
                        $rowPertama = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($a, $n)->getValue();
                        // if($a < 52) {
                        //     array_push($data, $rowPertama);
                        //     array_push($data2, $d_schedule3 . '-' . $d_schedule2);
                        // }
                        if ($d_schedule != '') {
                            $d_schedule = date('d-m-Y', ($d_schedule - 25569) * 86400);
                            /* $d_schedule = $d_schedule; */
                        };
                        // $e_remark = $spreadsheet->getActiveSheet()->getCell('J' . $n)->getValue();
                        // $e_remark = ($e_remark == null) ? '' : $e_remark;
                        $dschedule3 = explode('-',$d_schedule3);
                        if ($rowPertama > 0 && $id_product != "") {
                            $id_color = $this->db->query("SELECT id FROM tr_color WHERE i_color IN (SELECT i_color FROM tr_product_wip WHERE id = '$id_product') /* AND id_company = '$this->id_company' */ ")->row()->id;
                            $aray[] = array(
                                'id'                => $id_product,
                                'i_product_wip'     => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
                                'e_product_wipname' => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
                                'e_color_name'      => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
                                'd_schedule'        => $d_schedule2 . '-' . $dschedule3[1] . '-' . $dschedule3[0],
                                'n_uraian_jahit'    => $this->mmaster->get_detail_wip($id_product, $id_referensi)->row()->n_quantity,
                                'n_schedule'        => $rowPertama,
                                'id_color'          => $id_color,
                                // 'e_remark'          => $e_remark,
                            );
                        }
                        // if($cellMulaiTanggal < 52) {
                        //     $cellMulaiTanggal++;
                        // }
                    }
                }
                // echo '<pre>' . var_export($aray, true) . '</pre>';
                // var_dump($data2);
                usort($aray, function ($b, $a) {
                    return $b['d_schedule'] <=> $a['d_schedule'];
                });
                $param = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Tambah " . $this->global['title'],
                    'title_list'    => $this->global['title'],
                    'datadetail'    => $aray,
                    'status'        => 'berhasil',
                    'sama'          => true
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
