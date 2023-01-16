<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2090501';
    public $i_menu1 = '2090609';

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
        $this->idcompany = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['folder1'] = 'bonmasukpackingfgudang';
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
            'folder1'       => $this->global['folder1'],
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
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
        echo $this->mmaster->data($this->i_menu, $this->i_menu1, $this->global['folder'], $this->global['folder1'], $dfrom, $dto);
    }

    /* public function bagianpengirim()
    {
        $filter = [];
        $imenu = $this->input->get('imenu');
        $ibagian = $this->input->get('ibagian');
        $data   = $this->mmaster->bagianpengirim(replace_kutip($this->input->get('q')),$imenu, $ibagian);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->i_bagian,
                'text'  => $row->e_bagian_name,
            );
        }
        echo json_encode($filter);
    } */

    public function bagianpengirim()
    {
        $filter = [];
            $cari = replace_kutip($this->input->get('q'));
        $ibagian = $this->input->get('ibagian');
            $data = $this->mmaster->bagianpengirim($cari, $ibagian);
            if ($data->num_rows()>0) {
                $group   = [];
                $arr     = [];
                foreach ($data->result() as $key) {
                    $arr[] = $key->company_name;
                }
                $unique_data = array_unique($arr);
                foreach($unique_data as $val) {
                    $child  = [];
                    foreach ($data->result() as $row) {
                        if ($val==$row->company_name) {
                            $child[] = array(
                                'id' => $row->i_bagian.'|'.$row->id_company, 
                                'text' => $row->e_bagian_name, 
                                'title' => $row->company_name,
                            );
                        }
                    }
                    $filter[] = array(
                        'id' => 0,
                        'text' => strtoupper($val),
                        'children' => $child
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

    public function referensi()
    {
        $filter = [];
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')),$this->input->get('iasal'));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->i_document.' - '.$row->e_jenis_name,
            );
        }
        echo json_encode($filter);
    }

    public function getdataitem()
    {
        header("Content-Type: application/json", true);
        $idreff    = $this->input->post('idreff');
        $ipengirim = $this->input->post('ipengirim');
        $jml = $this->mmaster->getdataitem($idreff, $ipengirim);
        $data = array(
            'datahead'   => $this->mmaster->getdataheader($idreff, $ipengirim)->row(),
            'jmlitem'    => $jml->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($idreff, $ipengirim)->result_array()
        );
        echo json_encode($data);
    }

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'       => $this->global['folder'],
            'title'        => "Tambah " . $this->global['title'],
            'title_list'   => ' List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'imenu'         => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "BBM-".date('ym')."-1234"
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonm        = $this->input->post('idocument', TRUE);
        $ikodemaster  = $this->input->post('ibagian', TRUE);
        $dbonm        = $this->input->post('ddocument', TRUE);
        if ($dbonm) {
            $tmp   = explode('-', $dbonm);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datebonm = $year . '-' . $month . '-' . $day;
        }

        $iasal            = $this->input->post('ipengirim', TRUE);
        $ireff            = $this->input->post('ireff', TRUE);
        $jref             = $this->input->post('jreferensi', TRUE);
        $eremark          = $this->input->post('eremark', TRUE);
        $jml              = $this->input->post('jml', TRUE);

        $id_product       = $this->input->post('idproduct[]', TRUE);
        $id_color         = $this->input->post('idcolor[]', TRUE);
        $n_quantity       = $this->input->post('nquantity[]', TRUE);
        $n_quantitymasuk  = $this->input->post('nquantitymasuk[]', TRUE);
        $e_desc           = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
        $id = $this->mmaster->runningid();
        $this->mmaster->insertheader($id, $ibonm, $datebonm, $ikodemaster, $iasal, $ireff, $eremark, $jref);

        $no = 0;

        foreach ($id_product as $idproduct) {
            $idproduct           = $idproduct;
            $idcolor             = $id_color[$no];
            $nquantity           = $n_quantity[$no];
            $nquantitymasuk      = $n_quantitymasuk[$no];
            $edesc               = $e_desc[$no];

            $this->mmaster->insertdetail($id, $ireff, $ibonm, $idproduct, $idcolor, $nquantity, $nquantitymasuk, $edesc);

            $no++;
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
                'kode'   => $ibonm,
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

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()
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
        $ibonm        = $this->input->post('idocument', TRUE);
        $ikodemaster  = $this->input->post('ibagian', TRUE);
        $dbonm        = $this->input->post('ddocument', TRUE);
        if ($dbonm) {
            $tmp   = explode('-', $dbonm);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datebonm = $year . '-' . $month . '-' . $day;
        }

        $iasal        = $this->input->post('ipengirim', TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $jref         = $this->input->post('jreferensi', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $id_product       = $this->input->post('idproduct[]', TRUE);
        $id_color         = $this->input->post('idcolor[]', TRUE);
        $n_quantitymasuk  = $this->input->post('nquantitymasuk[]', TRUE);
        $e_desc           = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
        $this->mmaster->updateheader($id, $ikodemaster, $ibonm, $datebonm, $eremark, $iasal, $ireff, $jref);
        $this->mmaster->deletedetail($id);

        // var_dump($n_quantitymasuk);
        // die();
        $no = 0;
        foreach ($id_product as $idproduct) {
            $idproduct           = $idproduct;
            $idcolor             = $id_color[$no];
            $nquantity           = $n_quantitymasuk[$no];
            $nquantitymasuk      = $n_quantitymasuk[$no];
            $edesc               = $e_desc[$no];

           $this->mmaster->insertdetail($id, $ireff, $ibonm, $idproduct, $idcolor, $nquantity, $nquantitymasuk, $edesc);

            $no++;
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
                'kode'   => $ibonm,
                'id'     => $id,
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

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()
        );
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
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
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(6),
            'dto'        => $this->uri->segment(7),
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()

        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
    
}
/* End of file Cform.php */