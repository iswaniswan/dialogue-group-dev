<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '20209';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->id_company       = $this->session->id_company;
        $this->id_level         = $this->session->i_level;
        $this->id_departement   = $this->session->i_departement;
        $this->id_company       = $this->session->id_company;
        $this->username         = $this->session->username;

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
            'number'     => "BP-".date('ym')."-123456",
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

    public function getop()
    {
        $filter = [];
        $data = $this->mmaster->get_op(str_replace("'","",$this->input->get('q')));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_op,
                );
            }
        echo json_encode($filter);
    }

    public function material()
    {
        $filter = [];
        if ($this->input->get('iop')!='') {
            $data = $this->mmaster->get_item_op(str_replace("'","",$this->input->get('q')),$this->input->get('iop'));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_material,
                    'text' => $row->i_material.' - '.$row->e_material_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Pilih OP Terlebih Dahulu!",
            );
        }
        echo json_encode($filter);
    }

    public function getmaterial()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->get_item_op_detail($this->input->post('iop', TRUE), $this->input->post('imaterial', TRUE))->result_array());
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', true);
        $idocument  = $this->input->post('ibp', true);
        $ddocument  = date('Y-m-d', strtotime($this->input->post('ddocument', true)));
        $remark     = $this->input->post('remark', true);
        $jml        = $this->input->post('jml', true);
        if ($ibagian != '' && $idocument != '' && $ddocument != '' && $jml > 0) {
            $i_op        = $this->input->post('iop[]', true);
            $i_material  = $this->input->post('imaterial[]', true);
            $id_pp       = $this->input->post('idpp[]', true);
            $n_quantity  = str_replace(',','',$this->input->post('nquantity[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
            $cekdata     = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                    'kode'   => $idocument,
                    'id'     => null,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id,$ibagian,$idocument,$ddocument,$remark);
                $no = 0;
                foreach ($i_op as $iop) {
                    $iop        = $iop;
                    $imaterial  = $i_material[$no];
                    $idpp       = $id_pp[$no];
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];
                    if (($iop != '' || $iop != null) && ($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id,$iop,$idpp,$imaterial,$nquantity,$eremark);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => null,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null,
            );
        }
        $this->load->view('pesan2', $data);
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
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4))->result(),
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
            'number'     => "BP-".date('ym')."-123456",
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4))->result(),
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

        $id                 = $this->input->post('id', TRUE);
        $idocumentold       = $this->input->post('ibpold', TRUE);
        $idocument          = $this->input->post('ibp', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument      != '') {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $ibagianold         = $this->input->post('ibagianold', TRUE);
        $remark             = $this->input->post('remark', true);
        $jml                = $this->input->post('jml', true);
        if ($id!= '' && $ibagian != '' && $idocument != '' && $ddocument != '' && $jml > 0) {
            $i_op        = $this->input->post('iop[]', true);
            $i_material  = $this->input->post('imaterial[]', true);
            $id_pp       = $this->input->post('idpp[]', true);
            $n_quantity  = str_replace(',','',$this->input->post('nquantity[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
            $cekdata     = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekdata->num_rows()<=0) {
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$ibagian,$idocument,$ddocument,$remark);
                $this->mmaster->deletedetail($id);
                $no = 0;
                foreach ($i_op as $iop) {
                    $iop        = $iop;
                    $imaterial  = $i_material[$no];
                    $idpp       = $id_pp[$no];
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];
                    if (($iop != '' || $iop != null) && ($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id,$iop,$idpp,$imaterial,$nquantity,$eremark);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => null,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                }
            }else{
                $data = array(
                    'sukses' => false,
                    'kode'   => $idocument,
                    'id'     => null,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null,
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
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */
