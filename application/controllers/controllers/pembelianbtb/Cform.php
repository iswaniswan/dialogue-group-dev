<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '20207';

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
            'list_title' => 'List Order Pembelian',
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

    public function daftarop()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => 'List Order Pembelian',
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu Daftar OP ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlistop', $data);
    }

    function dataop()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->dataop($this->i_menu, $this->global['folder'], $dfrom, $dto);
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
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->gudang()->result(),
            'number'     => "BTB-" . date('ym') . "-123456",
            'data'       => $this->mmaster->getdataop($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getdataopitem($this->uri->segment(4))->result(),
            'satuan'     => $this->mmaster->satuan()->result(),
        );

        $this->Logger->write('Membuka Menu Input ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function bagian()
    {
        $filter = [];
        if ($this->input->get('ibagian') != '') {
            $data = $this->mmaster->bagian(str_replace("'", "", $this->input->get('q')), $this->input->get('ibagian'));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_bagian,
                    'text' => $row->e_bagian_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
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

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibtb      = $this->input->post('ibtb', true);
        $dbtb      = date('Y-m-d', strtotime($this->input->post('dbtb', true)));
        $thbl      = date('ym', strtotime($dbtb));
        $tahun     = date('Y', strtotime($dbtb));
        $isupplier = $this->input->post('isupplier', true);
        $esupplier = $this->input->post('esupplier', true);
        $isj       = $this->input->post('isj', true);
        $dsj       = date('Y-m-d', strtotime($this->input->post('dsj', true)));
        $idop      = $this->input->post('idop', true);
        $ibagian   = $this->input->post('ibagian', true);
        $igudang   = $this->input->post('igudang', true);
        $remark    = $this->input->post('remark', true);
        $jml       = $this->input->post('jml', true);
        $ibtb      = $this->mmaster->runningnumber($thbl, $tahun, $igudang);
        if ($isupplier != '' && $ibtb != '' && $dbtb != '' && $idop != '' && $jml > 0) {
            $i_material      = $this->input->post('imaterial[]', true);
            $id_pp           = $this->input->post('idpp[]', true);
            $i_satuan_eks    = $this->input->post('isatuaneks[]', true);
            $n_quantity_eks  = str_replace(',', '', $this->input->post('nquantityeks[]', true));
            $i_satuan        = $this->input->post('isatuan[]', true);
            $e_note          = $this->input->post('e_note[]', true);
            $n_quantity      = str_replace(',', '', $this->input->post('nquantity[]', true));
            $hargaop         = str_replace(',', '', $this->input->post('hrgop[]', true));
            $toleransi       = str_replace(',', '', $this->input->post('toleransi[]', true));
            $e_operator      = $this->input->post('eoperator[]', true);
            $n_faktor        = $this->input->post('nfaktor[]', true);
            $i_konversi      = $this->input->post('ikonversi[]', true);
            // $cekdata         = $this->mmaster->cek_kode($ibtb,$igudang);
            /* if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                    'kode'   => '',
                    'id'     => '',
                );
            }else{ */
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id, $ibtb, $dbtb, $isj, $dsj, $isupplier, $ibagian, $remark, $esupplier, $igudang);
            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial    = $imaterial;
                $idpp         = $id_pp[$no];
                $isatuaneks   = $i_satuan_eks[$no];
                $nquantityeks = $n_quantity_eks[$no];
                $isatuan      = $i_satuan[$no];
                if (!empty($e_note[$no])) {
                    $note     = $e_note[$no];
                } else {
                    $note     = null;
                }
                $nquantity    = $n_quantity[$no];
                $price        = $hargaop[$no];
                $n_toleransi  = $toleransi[$no];
                $eoperator    = !strlen($e_operator[$no]) ? '*' : $e_operator[$no];
                $nfaktor      = !strlen($n_faktor[$no]) ? 1 : $n_faktor[$no];
                $ikonversi    = !strlen($i_konversi[$no]) ? 'LM' : $i_konversi[$no];
                if (($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                    // var_dump($id,$idop,$imaterial,$isatuaneks,$nquantityeks,$isatuan,$nquantity,$price,$idpp,$note,$n_toleransi);
                    $this->mmaster->insertdetail($id, $idop, $imaterial, $isatuaneks, $nquantityeks, $isatuan, $nquantity, $price, $idpp, $note, $n_toleransi, $eoperator, $nfaktor, $ikonversi);
                }
                $no++;
            }
            // die;
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => '',
                    'id'     => '',
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibtb . ' Id : ' . $id);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ibtb,
                    'id'     => $id,
                );
            }
            /* } */
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => '',
                'id'     => '',
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
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->gudang()->result(),
            'data'       => $this->mmaster->getdata($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getdataitem($this->uri->segment(4))->result(),
            'satuan'     => $this->mmaster->satuan()->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

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
            'number'     => "BTB-" . date('ym') . "-123456",
            'bagian'     => $this->mmaster->gudang()->result(),
            'data'       => $this->mmaster->getdata($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getdataitem($this->uri->segment(4))->result(),
            'satuan'     => $this->mmaster->satuan()->result(),
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

        $id        = $this->input->post('id', true);
        $ibtb      = $this->input->post('ibtb', true);
        $ibtbold   = $this->input->post('ibtbold', true);
        $dbtb      = date('Y-m-d', strtotime($this->input->post('dbtb', true)));
        $isupplier = $this->input->post('isupplier', true);
        $esupplier = $this->input->post('esupplier', true);
        $isj       = $this->input->post('isj', true);
        $dsj       = date('Y-m-d', strtotime($this->input->post('dsj', true)));
        $idop      = $this->input->post('idop', true);
        $ibagian   = $this->input->post('ibagian', true);
        $igudang   = $this->input->post('igudang', true);
        $igudangold = $this->input->post('igudangold', true);
        $remark    = $this->input->post('remark', true);
        $jml       = $this->input->post('jml', true);
        if ($id != '' && $ibagian != '' && $isupplier != '' && $ibtb != '' && $dbtb != '' && $idop != '' && $jml > 0) {
            $i_material      = $this->input->post('imaterial[]', true);
            $id_pp           = $this->input->post('idpp[]', true);
            $i_satuan_eks    = $this->input->post('isatuaneks[]', true);
            $n_quantity_eks  = str_replace(',', '', $this->input->post('nquantityeks[]', true));
            $i_satuan        = $this->input->post('isatuan[]', true);
            $e_note          = $this->input->post('e_note[]', true);
            $n_quantity      = str_replace(',', '', $this->input->post('nquantity[]', true));
            $hargaop         = str_replace(',', '', $this->input->post('hrgop[]', true));
            $toleransi       = str_replace(',', '', $this->input->post('toleransi[]', true));
            $e_operator      = $this->input->post('eoperator[]', true);
            $n_faktor        = $this->input->post('nfaktor[]', true);
            $i_konversi      = $this->input->post('ikonversi[]', true);
            $cekdata         = $this->mmaster->cek_kode($ibtbold, $igudangold);
            if ($cekdata->num_rows() > 0) {
                //echo "string";
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ibtb, $dbtb, $isj, $dsj, $isupplier, $ibagian, $remark, $esupplier, $igudang);
                $this->mmaster->deletedetail($id);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial    = $imaterial;
                    $idpp         = $id_pp[$no];
                    $isatuaneks   = $i_satuan_eks[$no];
                    $nquantityeks = $n_quantity_eks[$no];
                    $isatuan      = $i_satuan[$no];
                    if (!empty($e_note[$no])) {
                        $note     = $e_note[$no];
                    } else {
                        $note     = null;
                    }
                    $nquantity    = $n_quantity[$no];
                    $price        = $hargaop[$no];
                    $n_toleransi  = $toleransi[$no];
                    $eoperator    = !strlen($e_operator[$no]) ? '*' : $e_operator[$no];
                    $nfaktor      = !strlen($n_faktor[$no]) ? 1 : $n_faktor[$no];
                    $ikonversi    = !strlen($i_konversi[$no]) ? 'LM' : $i_konversi[$no];
                    if (($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id, $idop, $imaterial, $isatuaneks, $nquantityeks, $isatuan, $nquantity, $price, $idpp, $note, $n_toleransi, $eoperator, $nfaktor, $ikonversi);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => '',
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $ibtb . ' Id : ' . $id);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ibtb,
                        'id'     => $id,
                    );
                }
            } else {
                $data = array(
                    'sukses' => false,
                    'kode'   => '',
                );
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => '',
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
            'bagian'     => $this->mmaster->gudang()->result(),
            'data'       => $this->mmaster->getdata($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getdataitem($this->uri->segment(4))->result(),
            'satuan'     => $this->mmaster->satuan()->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function cetak()
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

    public function cetaknonharga()
    {
        $idbtb        = $this->uri->segment(4);
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Cetak " . $this->global['title'],
            'data'          => $this->mmaster->cetak_btb($idbtb)->result(),
            'data2'         => $this->mmaster->cetak_item_btb($idbtb)->result(),
            'approve'       => $this->mmaster->get_approve($idbtb),
        );

        $this->Logger->write('Cetak ' . $this->global['title'] . ' No : ' . $idbtb);

        $this->load->view($this->global['folder'] . '/vformprintnonharga', $data);
    }

    public function edit_sj()
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
            'number'     => "BTB-" . date('ym') . "-123456",
            'bagian'     => $this->mmaster->gudang()->result(),
            'data'       => $this->mmaster->getdata($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getdataitem($this->uri->segment(4))->result(),
            'satuan'     => $this->mmaster->satuan()->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit_sj', $data);
    }

    public function update_sj()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibtb      = $this->input->post('ibtb', true);
        $id        = $this->input->post('id', true);
        $isj       = $this->input->post('isj', true);
        $dsj       = date('Y-m-d', strtotime($this->input->post('dsj', true)));
        if ($id != '' && $isj != '') {
            $this->db->trans_begin();
            $this->mmaster->update_sj($id, $isj, $dsj);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => '',
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $ibtb . ' Id : ' . $id);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ibtb,
                    'id'     => $id,
                );
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => '',
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function approve_validation()
    {
        $id = $this->input->post('id');
        $query = $this->mmaster->get_sisa($id);
        $data = [];
        $sisa = true;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                if ($key->n_quantity_pemenuhan > $key->n_quantity_sisa) {
                    $sisa = false;
                    break;
                }
            }
        }
        $data = array(
            'sisa' => $sisa,
        );
        echo json_encode($data);
    }
}
/* End of file Cform.php */
