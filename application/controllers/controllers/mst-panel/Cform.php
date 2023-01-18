<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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
                        'id'   => $row->id . '-' . $row->i_product_wip . '-' . $row->i_color . '-' . $row->e_color_name,
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

    /*-------------- CARI MARKER ------------- */
    public function marker()
    {
        $filter = [];
        // if ($this->input->get('q') != '') {
            $data = $this->mmaster->marker(str_replace("'", "", $this->input->get('q')), str_replace("'", "", $this->input->get('i_color')), str_replace("'", "", $this->input->get('id_product_wip')));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->e_marker_name
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
        //         'text' => "Cari Barang Berdasarkan Nama / Kode"
        //     );
        // }
        echo json_encode($filter);
    }

    public function material()
    {
        $filter = [];
        // if ($this->input->get('q') != '') {
            $data = $this->mmaster->material(str_replace("'", "", $this->input->get('q')), str_replace("'", "", $this->input->get('idmarker')), str_replace("'", "", $this->input->get('idproduct')));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->i_material . ' - ' . $row->e_material_name
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
        //         'text' => "Cari Barang Berdasarkan Nama / Kode"
        //     );
        // }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/

    public function detailproduct()
    {
        header("Content-Type: application/json", true);
        $query  = array(
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

        $idproduct   = $this->input->post('idproduct', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idmarker   = $this->input->post('idmarker', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        if ($jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id, $idproduct, $eremarkh, $idmarker);

            for ($x = 1; $x <= $jml; $x++) {
                $imaterial      = $this->input->post('imaterial' . $x, TRUE);

                if($imaterial != '' || $imaterial != NULL){
                    $ebagian        = strtoupper($this->input->post('ebagian' . $x, TRUE));
                    $ipanel         = $this->input->post('ipanel' . $x, TRUE);
                    $edesc          = $this->input->post("eremark" . $x, TRUE);
                    $n_qty_penyusun = $this->input->post("n_qty_penyusun" . $x, TRUE);
                    $n_panjang_cm   = $this->input->post("n_panjang_cm" . $x, TRUE);
                    $n_lebar_cm     = $this->input->post("n_lebar_cm" . $x, TRUE);

                    $n_pg_cm        = $this->input->post("n_pg_cm" . $x, TRUE);
                    $n_lg_cm        = $this->input->post("n_lg_cm" . $x, TRUE);
                    $n_hg_set       = $this->input->post("n_hg_set" . $x, TRUE);
                    $n_efficiency   = $this->input->post("n_efficiency" . $x, TRUE);

                    $imaterialmakloon      = $this->input->post('imaterialmakloon' . $x, TRUE);

                    if(null !== $this->input->post("print" . $x, TRUE)) {
                        $print = true;
                    } else {
                        $print = false;
                    }

                    if(null !== $this->input->post("bordir" . $x, TRUE)) {
                        $bordir = true;
                    } else {
                        $bordir = false;
                    }

                    if(null !== $this->input->post("f_khusus_pengadaan" . $x, TRUE)) {
                        $f_khusus_pengadaan = true;
                    } else {
                        $f_khusus_pengadaan = false;
                    }

                    // if ($imaterialmakloon == null) {
                    //     $imaterialmakloon = $imaterial;
                    // }

                    $this->mmaster->insertdetail($idproduct, $idmarker, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $print, $bordir,$n_pg_cm, $n_lg_cm, $n_hg_set, $n_efficiency, $f_khusus_pengadaan, $imaterialmakloon);
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
                    'kode'   => $idproduct,
                    'id'     => $id
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

        $idcompany  = $this->session->userdata('id_company');
        // var_dump($this->uri->segment(4), $this->uri->segment(7)); die;
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4), $this->uri->segment(7))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4), $this->uri->segment(7))->result(),
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
        $idproduct   = $this->input->post('idproduct', TRUE);
        $idmarker   = $this->input->post('idmarker', TRUE);
        $eremarkh   = $this->input->post('eremarkh');
        $jml        = $this->input->post('jml', TRUE);

        if ($jml > 0) {
            $this->db->trans_begin();
            // $this->mmaster->updateheader($id, $idproduct, $eremarkh);
            // $this->mmaster->deletedetail($idproduct);

            for ($x = 1; $x <= $jml; $x++) {
                $imaterial      = $this->input->post('imaterial' . $x, TRUE);
                
                if($imaterial != '' || $imaterial != NULL){
                    $iditem         = $this->input->post('iditem' . $x, TRUE); 
                    $ebagian        = strtoupper($this->input->post('ebagian' . $x, TRUE));
                    $ipanel         = $this->input->post('ipanel' . $x, TRUE);
                    $edesc          = $this->input->post("eremark" . $x, TRUE);
                    $n_qty_penyusun = $this->input->post("n_qty_penyusun" . $x, TRUE);
                    $n_panjang_cm   = $this->input->post("n_panjang_cm" . $x, TRUE);
                    $n_lebar_cm     = $this->input->post("n_lebar_cm" . $x, TRUE);

                    $n_pg_cm        = $this->input->post("n_pg_cm" . $x, TRUE);
                    $n_lg_cm        = $this->input->post("n_lg_cm" . $x, TRUE);
                    $n_hg_set       = $this->input->post("n_hg_set" . $x, TRUE);
                    $n_efficiency   = $this->input->post("n_efficiency" . $x, TRUE);

                    $status = $this->input->post('status' . $x);
                    if(null !== $this->input->post("print" . $x, TRUE))
                    {
                        $print = true;
                    }
                    else {
                        $print = false;
                    }
                    if(null !== $this->input->post("bordir" . $x, TRUE))
                    {
                        $bordir = true;
                    }
                    else {
                        $bordir = false;
                    }

                    $imaterialmakloon      = $this->input->post('imaterialmakloon' . $x, TRUE);

                    if(null !== $this->input->post("f_khusus_pengadaan" . $x, TRUE)) {
                        $f_khusus_pengadaan = true;
                    } else {
                        $f_khusus_pengadaan = false;
                    }

                    // if ($imaterialmakloon == null) {
                    //     $imaterialmakloon = $imaterial;
                    // }

                    if ($iditem!=null || $iditem!='') {
                        $this->mmaster->updatedetail($iditem, $idproduct, $idmarker, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $status, $print, $bordir,$n_pg_cm, $n_lg_cm, $n_hg_set, $n_efficiency, $f_khusus_pengadaan, $imaterialmakloon);
                    }
                    else{
                        $this->mmaster->insertdetail($idproduct, $idmarker, $imaterial, $ebagian, $ipanel, $edesc, $n_qty_penyusun, $n_panjang_cm, $n_lebar_cm, $print, $bordir,$n_pg_cm, $n_lg_cm, $n_hg_set, $n_efficiency, $f_khusus_pengadaan, $imaterialmakloon);

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
                    'kode'   => $idproduct,
                    'id'     => $id
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

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-" . date('ym') . "-123456",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
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

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4), $this->uri->segment(5))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4), $this->uri->segment(5))->result(),
            'number'        => "SJ-" . date('ym') . "-123456",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}
/* End of file Cform.php */