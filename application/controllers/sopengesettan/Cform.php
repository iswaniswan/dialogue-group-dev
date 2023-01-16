<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090314';

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
        $this->global['title']  = $data[0]['e_menu'];
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
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

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SO-" . date('ym') . "-0001",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
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
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function referensi()
    {
        $filter = [];
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->i_document,
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') !== '') {
            //var_dump($ireferensi);

            $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - ' . $row->e_color_name
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Cari Barang Berdasarkan Nama / Kode"
            );
        }
        echo json_encode($filter);
    }

    public function alasanretur()
    {
        $filter = [];
        if ($this->input->get('q') !== '') {
            //var_dump($ireferensi);

            $data = $this->mmaster->alasanretur();
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->e_alasan_name
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih alasan"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/

    public function detailproduct()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detailproduct($this->input->post('id', TRUE))->result_array()
        );
        echo json_encode($query);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            die;
            redirect(base_url(), 'refresh');
        }

        $ibagian     = $this->input->post('ibagian', TRUE);
        $idoc        = $this->input->post('idoc', TRUE);
        $ddoc        = $this->input->post('ddoc', TRUE);
        if ($ddoc) {
            $tmp   = explode('-', $ddoc);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datedoc = $year . '-' . $month . '-' . $day;
        }
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $id           = $this->mmaster->runningid();

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idoc);
        $this->mmaster->insertheader($id, $idoc, $ibagian, $datedoc, $eremarkh);

        for ($x = 1; $x <= $jml; $x++) {
            $idproduct = $this->input->post('idproduct' . $x, TRUE);
            $i  = 0;
            $count  = count($this->input->post("idproductwip[]", TRUE));
            if ($idproduct != "" || $idproduct != NULL) {
                foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                    if ($idproduct == $idproductwip) {
                        $id_panel_item     = $this->input->post("id_panel_item[]", TRUE)[$i];
                        $nqtysobagus     = $this->input->post("n_so_bagus[]", TRUE)[$i];
                        $idmaterial     = $this->input->post("idmaterial[]", TRUE)[$i];
                        $eremark        = $this->input->post("eremark[]", TRUE)[$i];

                        $this->mmaster->insertdetail($id, $idproductwip, $idmaterial, $nqtysobagus, $eremark, $id_panel_item);
                    }
                    $i++;
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idoc,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "SO-" . date('ym') . "-0001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($id)->result(),
            'alasan'        => $this->mmaster->alasanretur()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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

        $id           = $this->input->post('id', TRUE);
        $idoc        = $this->input->post('idoc', TRUE);
        $ddoc        = $this->input->post('ddoc', TRUE);
        if ($ddoc) {
            $tmp   = explode('-', $ddoc);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedoc = $year . '-' . $month . '-' . $day;
        }
        $ireferensi   = $this->input->post('ireferensi', TRUE);
        $ibagian      = $this->input->post('ibagian', TRUE);
        $itujuan      = $this->input->post('itujuan', TRUE);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idoc);

        $this->mmaster->updateheader($id, $idoc, $ibagian, $datedoc, $eremarkh);
        $this->mmaster->deletedetail($id);

        for ($x = 1; $x <= $jml; $x++) {
            $idproduct = $this->input->post('idproduct' . $x, TRUE);
            $i  = 0;
            $count  = count($this->input->post("idproductwip[]", TRUE));
            if ($idproduct != "" || $idproduct != NULL) {
                foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                    if ($idproduct == $idproductwip) {
                        $id_panel_item     = $this->input->post("id_panel_item[]", TRUE)[$i];
                        $nqtysobagus     = $this->input->post("n_so_bagus[]", TRUE)[$i];
                        $idmaterial     = $this->input->post("idmaterial[]", TRUE)[$i];
                        $eremark        = $this->input->post("eremark[]", TRUE)[$i];

                        $this->mmaster->insertdetail($id, $idproductwip, $idmaterial, $nqtysobagus, $eremark, $id_panel_item);
                    }
                    $i++;
                }
            }
        }

        // for ($x=1; $x <= $jml ; $x++) { 
        //     $idproduct = $this->input->post('idproduct'.$x, TRUE);
        //     $ialasan   = $this->input->post('ialasan'.$x, TRUE);
        //     $i  = 0;
        //     $count  = count($this->input->post("idproductwip[]", TRUE));
        //     if ($idproduct != "" || $idproduct != NULL) {
        //         foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
        //             if ($idproduct == $idproductwip) {
        //                 $idmaterial     = $this->input->post("idmaterial[]", TRUE)[$i];
        //                 $nqty      = $this->input->post('nquantityset'.$x, TRUE)[$i];
        //                 $hitung    = $nqty / $count;
        //                 $nqtysisa  = round($hitung);
        //                 $nquantitymat   = $nqtysisa;
        //                 $edesc          = $this->input->post("eremarkset".$x, TRUE)[$i];
        //                 $nquantityset1  = $nqty;
        //                 $alasanretur    = $ialasan;

        //                 $this->mmaster->insertdetail($id, $idproductwip, $idmaterial, $nquantitymat, $edesc, $nquantityset1 ,$alasanretur);
        //                 //var_dump($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark);
        //             }   
        //             $i++;
        //         }
        //     }
        // }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idoc,
                'id'     => $id,
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

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($id)->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($id)->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}
/* End of file Cform.php */