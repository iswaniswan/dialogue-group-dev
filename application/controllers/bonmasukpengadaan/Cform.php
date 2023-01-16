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

    /*----------  DAFTAR SJ MAKLOON  ----------*/

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
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  MASUK FORM TAMBAH DATA  ----------*/

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "BBM-" . date('ym') . "-0001",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  GET NO DOK  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CARI SUPPLIER  ----------*/

    public function partner()
    {
        $filter = [];
        $data = $this->mmaster->partner(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_supplier_name
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

    public function refeksternal()
    {
        $filter = [];
        $data   = $this->mmaster->referensieks(
            str_replace("'", "", $this->input->get('q')),
            $this->input->get('pembuat'),
            $this->input->get('pengirim')
        );
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => $key->i_document . ' | ' . $key->d_document . ' | ' . $key->e_jenis_name,
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

    public function pengirim()
    {
        $filter = [];
        $data   = $this->mmaster->pengirim(
            str_replace("'", "", $this->input->get('q')),
            $this->input->get('pembuat')
        );
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->i_bagian,
                    'text' => $key->e_bagian_name,
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

    public function getdetailrefeks()
    {
        header("Content-Type: application/json", true);
        $id     = $this->input->post('id');

        $query  = array(
            'detail' => $this->mmaster->getdetailrefeks($id)->result_array()
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

        $ibagian    = $this->input->post('ibagian', TRUE);
        $pengirim   = $this->input->post('pengirim', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        $idreff     = $this->input->post('ireffeks', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idjenis    = $this->input->post('idjenis', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }


        if ($idocument != '' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id, $idocument, $ddocument, $ibagian, $idreff, $eremarkh, $idjenis, $pengirim);

            for ($i = 1; $i <= $jml; $i++) {
                $idpanel = $this->input->post('id_panel_item' . $i, TRUE);
                $nquantity = str_replace(",", ".", $this->input->post('nquantity' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);

                $this->mmaster->simpandetail($id, $idpanel, $nquantity, $eremark);
                // if ($nquantity > 0 && $nquantity != null) {
                //     $this->mmaster->simpandetail($id, $idpanel, $nquantity, $eremark);
                // }


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
                    'kode'   => $idocument,
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

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

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

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "BBM-" . date('ym') . "-0001",
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

        $id         = $this->input->post('id', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        $pengirim  = $this->input->post('pengirim', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        $idreff     = $this->input->post('ireffeks', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idjenis    = $this->input->post('idjenis', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }

        if ($idocument != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id, $idocument, $ddocument, $ibagian, $idreff, $eremarkh, $idjenis, $pengirim);
            $this->mmaster->delete($id);
            for ($i = 1; $i <= $jml; $i++) {
                $idpanel = $this->input->post('id_panel_item' . $i, TRUE);
                $nquantity = str_replace(",", ".", $this->input->post('nquantity' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);


                $this->mmaster->simpandetail($id, $idpanel, $nquantity, $eremark);
                // if ($nquantity > 0 && $nquantity != null) {
                //     $this->mmaster->simpandetail($id, $idpanel, $nquantity, $eremark);
                // }


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
                    'kode'   => $idocument,
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


    public function approval()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "BBM-" . date('ym') . "-0001",
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }


    public function approve()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->input->post('id', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        if ($idocument != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->changestatus($id, '6');
            for ($i = 1; $i <= $jml; $i++) {
                $id_document_reff = $this->input->post('id_document' . $i, TRUE);
                $idpanel = $this->input->post('id_panel_item' . $i, TRUE);
                $nquantity = str_replace(",", ".", $this->input->post('nquantity' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);

                // $this->mmaster->updatekeluar($id_document_reff, $idpanel, $nquantity);
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
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => $id
            );
        }

        $this->load->view('pesan2', $data);
    }


    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "BBM-" . date('ym') . "-0001",
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function cekapprove()
    {
        $data = $this->mmaster->cek_approve($this->input->post('kode', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE), $this->input->post('itype', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function cekkodeedit()
    {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode', TRUE), $this->input->post('kodeold', TRUE), $this->input->post('ibagian', TRUE), $this->input->post('itype', TRUE), $this->input->post('itypeold', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }
}
/* End of file Cform.php */