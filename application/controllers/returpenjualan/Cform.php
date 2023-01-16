<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '20714';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama / Judul Menu  ----------*/
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/
        $this->load->model($this->global['folder'] . '/mmaster');
    }

    /*----------  DEFAULT CONTROLLERS  ----------*/

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

    /*----------  DAFTAR DATA SPB  ----------*/

    public function data()
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

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/

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
            'bagian'     => $this->mmaster->bagian()->result(),
            'retur'      => $this->mmaster->retur()->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'number'     => "TTB-" . date('ym') . "-1234",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode()
    {
        if ($this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE))->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  CARI PELANGGAN  ----------*/

    public function customer()
    {
        $filter = [];
        $data   = $this->mmaster->customer(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as  $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => $key->e_customer_name . " (" . $key->i_customer . ") "
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => 'Pelanggan Tidak Ada',
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET REFERENSI BERDASARKAN CUSTOMER  ----------*/

    public function referensi()
    {
        $filter = [];
        if ($this->input->get('icustomer') != '') {
            $data   = $this->mmaster->referensi(str_replace("'", "", $this->input->get('q')), $this->input->get('icustomer'), $this->input->get('periode'));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as  $key) {
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => $key->i_document . ' / ' . $key->d_document,
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => 'Tidak Ada Data!',
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => 'Pelanggan Tidak Boleh Kosong!',
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL CUSTOMER & REFERENSI  ----------*/

    public function getdetailref()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->getdetailref($this->input->post('idnota'))->result_array(),
        );
        echo json_encode($query);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idcustomer     = $this->input->post('icustomer', TRUE);
        $ialasan        = $this->input->post('ialasan', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $vdiskon        = str_replace(",", "", $this->input->post('ndiskontotal', TRUE));
        $vkotor         = str_replace(",", "", $this->input->post('nkotor', TRUE));
        $vppn           = str_replace(",", "", $this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",", "", $this->input->post('nbersih', TRUE));
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $vdpp           = str_replace(",", "", $this->input->post('vdpp', TRUE));
        $jml            = $this->input->post('jml', TRUE);
        if ($idocument != '' && $ddocument != '' && $ibagian != '' && $idcustomer != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $idcustomer, $ialasan, $vdiskon, $vkotor, $vppn, $vbersih, $eremarkh, $vdpp);
                for ($i = 0; $i < $jml; $i++) {
                    $iddocument         = $this->input->post('iddocument' . $i, TRUE);
                    $iddocumentdetail   = $this->input->post('iddocumentdetail' . $i, TRUE);
                    $idproduct          = $this->input->post('idproduct' . $i, TRUE);
                    $nquantity          = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
                    $vprice             = str_replace(",", "", $this->input->post('vharga' . $i, TRUE));
                    $ndiskon1           = str_replace(",", "", $this->input->post('ndisc1' . $i, TRUE));
                    $ndiskon2           = str_replace(",", "", $this->input->post('ndisc2' . $i, TRUE));
                    $ndiskon3           = str_replace(",", "", $this->input->post('ndisc3' . $i, TRUE));
                    $vdiskon1           = str_replace(",", "", $this->input->post('vdisc1' . $i, TRUE));
                    $vdiskon2           = str_replace(",", "", $this->input->post('vdisc2' . $i, TRUE));
                    $vdiskon3           = str_replace(",", "", $this->input->post('vdisc3' . $i, TRUE));
                    $vdiskonplus        = str_replace(",", "", $this->input->post('vdiscount' . $i, TRUE));
                    $vtotal             = str_replace(",", "", $this->input->post('vtotal' . $i, TRUE));
                    $vtotaldiskon       = str_replace(",", "", $this->input->post('vtotaldiskon' . $i, TRUE));
                    $eremark            = str_replace("'", "", $this->input->post('eremark' . $i, TRUE));
                    if ($nquantity > 0 && ($idproduct != null || $idproduct != '') && ($iddocument != null || $iddocument != '')) {
                        $this->mmaster->insertdetail($id, $iddocument, $iddocumentdetail, $idproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vtotaldiskon, $vtotal, $eremark);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
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
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

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
            'bagian'     => $this->mmaster->bagian()->result(),
            'retur'      => $this->mmaster->retur()->result(),
            'referensi'  => $this->mmaster->noreferensi($this->uri->segment(4))->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
            'number'     => "TTB-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ibagianold     = $this->input->post('ibagianold', TRUE);
        $idcustomer     = $this->input->post('icustomer', TRUE);
        $ialasan        = $this->input->post('ialasan', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $vdiskon        = str_replace(",", "", $this->input->post('ndiskontotal', TRUE));
        $vkotor         = str_replace(",", "", $this->input->post('nkotor', TRUE));
        $vppn           = str_replace(",", "", $this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",", "", $this->input->post('nbersih', TRUE));
        $vdpp           = str_replace(",", "", $this->input->post('vdpp', TRUE));
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if ($id != '' && $idocument != '' && $ddocument != '' && $ibagian != '' && $idcustomer != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument, $ibagian, $idocumentold, $ibagianold);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $idcustomer, $ialasan, $vdiskon, $vkotor, $vppn, $vbersih, $eremarkh, $vdpp);
                $this->mmaster->delete($id);
                for ($i = 0; $i < $jml; $i++) {
                    $iddocument         = $this->input->post('iddocument' . $i, TRUE);
                    $iddocumentdetail   = $this->input->post('iddocumentdetail' . $i, TRUE);
                    $idproduct          = $this->input->post('idproduct' . $i, TRUE);
                    $nquantity          = str_replace(",", "", $this->input->post('nquantity' . $i, TRUE));
                    $vprice             = str_replace(",", "", $this->input->post('vharga' . $i, TRUE));
                    $ndiskon1           = str_replace(",", "", $this->input->post('ndisc1' . $i, TRUE));
                    $ndiskon2           = str_replace(",", "", $this->input->post('ndisc2' . $i, TRUE));
                    $ndiskon3           = str_replace(",", "", $this->input->post('ndisc3' . $i, TRUE));
                    $vdiskon1           = str_replace(",", "", $this->input->post('vdisc1' . $i, TRUE));
                    $vdiskon2           = str_replace(",", "", $this->input->post('vdisc2' . $i, TRUE));
                    $vdiskon3           = str_replace(",", "", $this->input->post('vdisc3' . $i, TRUE));
                    $vdiskonplus        = str_replace(",", "", $this->input->post('vdiscount' . $i, TRUE));
                    $vtotal             = str_replace(",", "", $this->input->post('vtotal' . $i, TRUE));
                    $vtotaldiskon       = str_replace(",", "", $this->input->post('vtotaldiskon' . $i, TRUE));
                    $eremark            = str_replace("'", "", $this->input->post('eremark' . $i, TRUE));
                    if ($nquantity > 0 && ($idproduct != null || $idproduct != '') && ($iddocument != null || $iddocument != '')) {
                        $this->mmaster->insertdetail($id, $iddocument, $iddocumentdetail, $idproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vtotaldiskon, $vtotal, $eremark);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
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
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/

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
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'referensi'  => $this->mmaster->noreferensi($this->uri->segment(4))->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/

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
            'referensi'  => $this->mmaster->noreferensi($this->uri->segment(4))->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        /* if ($istatus == '6') {
            $this->mmaster->updatesisa($id);
        } */
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
}
/* End of file Cform.php */