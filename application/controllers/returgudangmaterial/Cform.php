<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090115';

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
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
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

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'tujuan'     => $this->mmaster->tujuan($this->i_menu)->result(),
            'number'     => "STBR-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function tambahbudgeting()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "STBR-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title'] . ' Berdasarkan Budgeting');

        $this->load->view($this->global['folder'] . '/vformaddbudget', $data);
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

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $array_itujuan = explode('|', $this->input->post('itujuan', TRUE));

            // var_dump($array_itujuan); die();

            $thbl = date('ym', strtotime($this->input->post('tgl', TRUE)));
            $tahun = date('Y', strtotime($this->input->post('tgl', TRUE)));
            $ibagian = $this->input->post('ibagian', TRUE);

            $itujuan = $array_itujuan[2];
            $id_bagian = $array_itujuan[0];

            $number = $this->mmaster->runningnumber(
                $thbl, $tahun, $ibagian, $itujuan, $id_bagian                                
            );
        }
        echo json_encode($number);
    }

    public function kelompok()
    {
        $filter = [];
        if ($this->input->get('ibagian') != '') {
            $data = $this->mmaster->kelompok($this->input->get('q'), $this->input->get('ibagian'), $this->input->get('idcompany'));
            $filter[] = array(
                'id'   => 'all',
                'text' => 'Semua Kategori',
            );
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,
                    'text' => ucwords(strtolower($key->e_nama_kelompok)),
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function jenis()
    {
        $filter = [];
        if ($this->input->get('ibagian') != '') {
            $data = $this->mmaster->jenis($this->input->get('q'), $this->input->get('ikategori'), $this->input->get('ibagian'), $this->input->get('idcompany'));
            $filter[] = array(
                'id'   => 'all',
                'text' => 'Semua Jenis',
            );
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->i_type_code,
                    'text' => ucwords(strtolower($key->e_type_name)),
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function material()
    {
        $filter = [];
        if ($this->input->get('q') != '' && $this->input->get('ibagian') != '') {
            $data = $this->mmaster->material(str_replace("'", "", $this->input->get('q')), $this->input->get('ikategori'), $this->input->get('ijenis'), $this->input->get('ibagian'), $this->input->get('idcompany'));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_material . ' - ' . $row->e_material_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getmaterial()
    {
        header("Content-Type: application/json", true);
        /* $awal = DateTime::createFromFormat('d-m-Y', $this->input->post('dfrom', TRUE));
        $akhir   = DateTime::createFromFormat('d-m-Y', $this->input->post('dto', TRUE));

        $dfrom = $awal->format('Y-m-d');
        $dto = $akhir->format('Y-m-d');
        $d_jangka_awal = $awal->format('Y-m-01');
        $i_periode = $awal->format('Ym');
        $d_jangka_akhir = $awal->modify('-1 day')->format('Y-m-d');

        if ($d_jangka_awal == $dfrom) {
            $d_jangka_awal = '9999-01-01';
            $d_jangka_akhir = '9999-01-31';
        } */
        echo json_encode($this->mmaster->getmaterial($this->input->post('imaterial', TRUE), $this->input->post('idcompany', TRUE),/* $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, */ $this->input->post('ibagian', TRUE))->result_array());
    }

    // public function materialbudget()
    // {
    //     $filter = [];
    //     if ($this->input->get('q') != '' && $this->input->get('ibagian') != '' && $this->input->get('dpp') != '') {
    //         $data = $this->mmaster->materialbudget(str_replace("'", "", $this->input->get('q')), $this->input->get('ikategori'), $this->input->get('ijenis'), $this->input->get('ibagian'), $this->input->get('dpp'));
    //         foreach ($data->result() as $row) {
    //             $filter[] = array(
    //                 'id'   => $row->i_material,
    //                 'text' => $row->i_material . ' - ' . $row->e_material_name,
    //             );
    //         }
    //         echo json_encode($filter);
    //     } else {
    //         echo json_encode($filter);
    //     }
    // }

    // /** Rubah 2021-11-24 */
    // public function getmaterialbudgetold()
    // {
    //     header("Content-Type: application/json", true);
    //     echo json_encode($this->mmaster->getmaterialbudget($this->input->post('imaterial', TRUE), $this->input->post('dpp', TRUE))->result_array());
    // }


    // public function budgeting()
    // {
    //     $filter = [];
    //     $cari = str_replace("'", "", $this->input->get('q'));
    //     $data = $this->mmaster->budgeting($cari);
    //     foreach ($data->result() as $row) {
    //         $filter[] = array(
    //             'id'   => $row->id,
    //             'text' => $row->i_document . ' [ ' . $row->periode . ' ]',
    //         );
    //     }
    //     echo json_encode($filter);
    // }

    // public function getmaterialbudget()
    // {
    //     header("Content-Type: application/json", true);
    //     $i_budgeting = $this->input->post('i_budgeting', TRUE);
    //     $i_bagian = $this->input->post('i_bagian', TRUE);
    //     if (!empty($i_budgeting)) {
    //         echo json_encode($this->mmaster->getmaterialbudget($i_budgeting, $i_bagian)->result_array());
    //     } else {
    //         echo json_encode('');
    //     };
    // }

    // public function getmaterialprice()
    // {
    //     header("Content-Type: application/json", true);
    //     $i_supplier = $this->input->post('i_supplier', TRUE);
    //     $i_material = $this->input->post('i_material', TRUE);
    //     $d_document = $this->input->post('d_document', TRUE);
    //     echo json_encode($this->mmaster->getmaterialprice($i_supplier, $i_material, $d_document)->result_array());
    // }

    // public function supplier()
    // {
    //     $filter = [];
    //     $data = $this->mmaster->supplier(str_replace("'", "", $this->input->get('q')));
    //     foreach ($data->result() as $row) {
    //         $filter[] = array(
    //             'id'   => $row->id,
    //             'text' => $row->i_supplier . ' - ' . $row->e_supplier_name,
    //         );
    //     }
    //     echo json_encode($filter);
    // }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        //var_dump($_POST);
        $ibagian = $this->input->post('ibagian', true);
        $i_document     = $this->input->post('i_document', true);
        $d_document     = date('Y-m-d', strtotime($this->input->post('d_document', true)));
        $itujuan     = explode('|',$this->input->post('itujuan', true));
        $remark  = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);
        $i_bagian_receive = $itujuan[2];
        $id_company_receive = $itujuan[1];
        if ($ibagian != '' && $i_document != '' && $d_document != '' && $jml > 0) {
            $i_material  = $this->input->post('imaterial[]', true);
            /* $i_kode      = $this->input->post('ikode[]', true);
            $i_satuan    = $this->input->post('isatuan[]', true); */
            $n_quantity  = str_replace(',', '', $this->input->post('nquantity[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
            $cekdata     = $this->mmaster->cek_kode($i_document, $i_bagian_receive, $id_company_receive);
            if ($cekdata->num_rows() > 0) {
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ibagian, $i_document, $d_document, $i_bagian_receive, $remark, $id_company_receive);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial  = $imaterial;
                    // $ikode      = $i_kode[$no];
                    // $isatuan    = $i_satuan[$no];
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];
                    
                    if (($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id, $i_document, $imaterial, /* $ikode, $isatuan, */ $nquantity, $eremark);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $i_document);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $i_document,
                        'id'     => $id,
                    );
                }
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        // $this->load->view('pesan2', $data);
        echo json_encode($data);
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
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'    => $this->mmaster->datadetail1($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'number'     => "STBR-" . date('ym') . "-123456",
            'bagian'     => $this->mmaster->bagian()->result(),
            'tujuan'     => $this->mmaster->tujuan($this->i_menu)->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4), $this->uri->segment(7), $this->uri->segment(8))->result(),
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

        $budgeting = $this->input->post('budgeting', true);
        $id      = $this->input->post('id', true);
        $ibagian = $this->input->post('ibagian', true);
        $itujuan = explode('|',$this->input->post('itujuan', true));
        $i_document     = $this->input->post('i_document', true);
        $i_document_old  = $this->input->post('i_document_old', true);
        $d_document     = date('Y-m-d', strtotime($this->input->post('d_document', true)));
        $remark  = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);
        $i_bagian_receive = $itujuan[2];
        $id_company_receive = $itujuan[1];
        if ($id != '' && $ibagian != '' && $i_document != '' && $d_document != '' && $jml > 0) {
            $i_material  = $this->input->post('imaterial[]', true);
            // $i_kode      = $this->input->post('ikode[]', true);
            // $i_satuan    = $this->input->post('isatuan[]', true);
            $n_quantity  = str_replace(',', '', $this->input->post('nquantity[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
            $cekdata     = $this->mmaster->cek_kode($i_document_old, $i_bagian_receive, $id_company_receive);
            if ($cekdata->num_rows() > 0) {
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ibagian, $i_document, $d_document, $i_bagian_receive, $remark, $id_company_receive);
                $this->mmaster->deletedetail($id);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial  = $imaterial;
                    /* $ikode      = $i_kode[$no];
                    $isatuan    = $i_satuan[$no]; */
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];

                    if (($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id, $i_document, $imaterial, /* $ikode, $isatuan, */ $nquantity, $eremark);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $i_document);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $i_document,
                        'id'     => $id,
                    );
                }
            } else {
                $data = array(
                    'sukses' => false,
                );
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail1($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function __cetak()
    {

        $data = array(
            'folder' => $this->global['folder'],
            'title'  => "Cetak " . $this->global['title'],
            'id'     => $this->uri->segment(4),
            'data'   => $this->mmaster->dataheader($this->uri->segment(4)),
            'detail' => $this->mmaster->datadetail($this->uri->segment(4)),
        );

        $this->Logger->write('Cetak ' . $this->global['title'] . ' Id : ' . $this->uri->segment(4));

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function simpan_budgeting()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        //var_dump($_POST);
        $i_budgeting = $this->input->post('i_budgeting', true);
        $budgeting = $this->input->post('budgeting', true);
        $ibagian = $this->input->post('ibagian', true);
        $ipp     = $this->input->post('ipp', true);
        $dpp     = date('Y-m-d', strtotime($this->input->post('dpp', true)));
        $dbp     = date('Y-m-d', strtotime($this->input->post('dbp', true)));
        $remark  = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);
        if ($ibagian != '' && $ipp != '' && $dpp != '' && $jml > 0) {
            $cekdata     = $this->mmaster->cek_kode($ipp, $ibagian);
            if ($cekdata->num_rows() > 0) {
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ibagian, $ipp, $dpp, $dbp, $remark, $budgeting, $i_budgeting);
                for ($i = 0; $i < $jml; $i++) {
                    $imaterial  = $this->input->post('imaterial' . $i, true);
                    $ikode      = $this->input->post('ikode' . $i, true);
                    $isatuan    = $this->input->post('isatuan' . $i, true);
                    $nquantity  = str_replace(',', '', $this->input->post('nquantity' . $i, true));
                    $eremark    = $this->input->post('eremark' . $i, true);
                    $harga_adj  = str_replace(',', '', $this->input->post('harga_adj' . $i, true));
                    $harga_sup  = str_replace(',', '', $this->input->post('harga_sup' . $i, true));
                    $isupplier  = $this->input->post('isupplier' . $i, true);
                    $check      = $this->input->post('check' . $i, true);
                    if (($imaterial != '' || $imaterial != null) && $nquantity > 0 && $check == "on") {
                        $this->mmaster->insertdetail($id, $ipp, $imaterial, $ikode, $isatuan, $nquantity, $eremark, $isupplier, $harga_adj, $harga_sup);
                    }
                }
                /* $i_material  = $this->input->post('imaterial[]', true);
                    $i_kode      = $this->input->post('ikode[]', true);
                    $i_satuan    = $this->input->post('isatuan[]', true);
                    $n_quantity  = str_replace(',', '', $this->input->post('nquantity[]', true));
                    $e_remark    = $this->input->post('eremark[]', true);
                    $harga       = str_replace(',', '', $this->input->post('harga_adj[]', true));
                    $harga_sup_ar = str_replace(',', '', $this->input->post('harga_sup[]', true));
                    $supplier    = $this->input->post('isupplier[]', true); */
                /* $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial  = $imaterial;
                    $ikode      = $i_kode[$no];
                    $isatuan    = $i_satuan[$no];
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];
                    if (!empty($supplier[$no])) {
                        $isupplier  = $supplier[$no];
                    } else {
                        $isupplier  = null;
                    }
                    if (!empty($harga[$no])) {
                        $harga_adj  = $harga[$no];
                    } else {
                        $harga_adj  = 0;
                    }

                    if (!empty($harga_sup_ar[$no])) {
                        $harga_sup  = $harga_sup_ar[$no];
                    } else {
                        $harga_sup  = 0;
                    }

                    if (($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                        if ($isupplier != 'null') {
                            $this->mmaster->insertdetail($id, $ipp, $imaterial, $ikode, $isatuan, $nquantity, $eremark, $isupplier, $harga_adj, $harga_sup);
                        }
                    }
                    $no++;
                } */
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ipp);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ipp,
                        'id'     => $id,
                    );
                }
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        // $this->load->view('pesan2', $data);
        echo json_encode($data);
    }

    public function export()
    {
        // parameter
        $id = decrypt_url($this->uri->segment(4));
        // end parameter

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

        /* Declare Kolom with array */
        $abjad  = range('A', 'Z');
        $number = range(1,4);
        $query_header = $this->mmaster->get_data_export_header($id)->row();
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A".$number[0], strtoupper($this->session->e_company_name))
            ->setCellValue("A".$number[1], $query_header->i_pp.' - '.format_bulan($query_header->d_pp))
            ->setCellValue("A".$number[2], $query_header->e_bagian_name)
            ->setCellValue("A".$number[3], $query_header->budgeting)
        ;
        $spreadsheet->getActiveSheet()->setTitle('Budgeting vs Realisasi');
        $header = ['#', 'KODE MATERIAL', 'NAMA MATERIAL', 'SATUAN', 'QTY', 'KETERANGAN'];
        $h = 6;
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, $abjad[0] . $number[0] . ":" . $abjad[count($header) - 1] . count($number));
        for ($n=0; $n < count($number) ; $n++) { 
            $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $number[$n] . ":" . $abjad[count($header) - 1] . $number[$n]);
        }
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
        }
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $h . ":" . $abjad[count($header) - 1] . $h)->getAlignment()->setWrapText(true);
        $j = 7;
        $x = 7;
        $no = 0;
        $query_item = $this->mmaster->get_data_export_item($id);
        if ($query_item->num_rows() > 0) {
            foreach ($query_item->result() as $row) {
                $no++;
                $isi = [
                    $no, $row->i_material, $row->e_material_name, $row->e_satuan_name, $row->n_quantity, $row->e_remark
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
        $spreadsheet->getActiveSheet()->mergeCells($abjad[0] . $j . ":" . $abjad[3] . $j);
        if ($query_item->num_rows() > 0) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[0] . $j, "TOTAL")
                ->setCellValue($abjad[4] . $j, "=SUM(" . $abjad[4] . $x . ":" . $abjad[4] . $y . ")");
        }
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, $abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j);
        $spreadsheet->getActiveSheet()->getStyle($abjad[0] . $j . ":" . $abjad[count($header) - 1] . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        /** End Sheet */
        $nama_file = $query_header->i_pp.".xls";
        $writer = new Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function cetak()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id_company = $this->session->userdata('id_company');

        $id = $this->uri->segment(4);
        $dfrom = $this->uri->segment(5);
        $dto = $this->uri->segment(6);
        $ibagian = $this->uri->segment(7);

        $_data = $this->mmaster->dataheader_print($id)->row();
        $no_urut = $this->generate_nomor_urut_cetak($_data->i_document, $ibagian);
        
        $data = [ 
            'folder' => $this->global['folder'],
            'title' => "Cetak ".$this->global['title'], 
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'id' => $id,
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $_data,
            'datadetail' => $this->mmaster->datadetail1_print($id)->result(),
            'company' => $this->mmaster->session_company()->row(),
            'no_urut'   => $no_urut
        ];

        $this->Logger->write('Cetak Data ' . $this->global['title'].' Id : '.$id);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function generate_nomor_urut_cetak($i_document=null, $i_bagian=null)
    {
        $array = explode('-', $i_document);
        $_urutan = $array[2];
        
        $_ym = $array[1];
        $ym = str_split($_ym, 2);
        $y = $ym[0];
        $m = $ym[1];

        $kode_lokasi = null;
        $query = $this->mmaster->get_kode_lokasi_bagian($i_bagian);
        
        if ($query->row() != null) {
            $kode_lokasi = $query->row()->e_kode_lokasi;
        }

        $bulan = angkaRomawi($m);
        
        return "$_urutan-$kode_lokasi-$bulan-$y"; 
        // return $text;
    }
}
/* End of file Cform.php */
