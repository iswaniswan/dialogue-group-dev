<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050204';

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
        $this->idcompany = $this->session->id_company;

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'] . '/mmaster');
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
            'number'     => "STB-".date('ym')."-123456",
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

    public function material()
    {
        $filter = [];
        if ($this->input->get('q')!='' && $this->input->get('ibagian')!='') {
            $data = $this->mmaster->material(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_material.' - '.$row->e_material_name.' - Tgl : '.$row->d_schedule.'',
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function getmaterial()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->getmaterial($this->input->post('idscheduleitem', TRUE))->result_array());
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian = $this->input->post('ibagian', true);
        $istb_cutting = $this->input->post('istb_cutting', true);
        $dstb_cutting     = date('Y-m-d', strtotime($this->input->post('dstb_cutting', true)));
        $remark     = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);

        if ($ibagian != '' && $istb_cutting != '' && $dstb_cutting != '' && $jml > 0) {
            $id_schedule_item  = $this->input->post('idscheduleitem[]', true);
            $n_quantity  = str_replace(',','',$this->input->post('nquantity_kirim[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
           
            $cekdata     = $this->mmaster->cek_kode($istb_cutting,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id,$ibagian,$istb_cutting,$dstb_cutting,$remark);
                $no = 0;
                foreach ($id_schedule_item as $idscheduleitem) {
                    $idscheduleitem  = $idscheduleitem;
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];

                    if (($idscheduleitem != '' || $idscheduleitem != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id,$idscheduleitem, $nquantity,$eremark);  
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
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $istb_cutting);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $istb_cutting,
                        'id'     => $id,
                    );
                }
            }
        }else{
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
            'number'     => "STB-".date('ym')."-123456",
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }
    
    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id      = $this->input->post('id', true);
        $ibagian = $this->input->post('ibagian', true);
        $istb_cutting = $this->input->post('istb_cutting', true);
        $istb_cuttingold = $this->input->post('istb_cuttingold', true);
        $dstb_cutting     = date('Y-m-d', strtotime($this->input->post('dstb_cutting', true)));
        $remark     = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);

        if ($ibagian != '' && $istb_cutting != '' && $dstb_cutting != '' && $jml > 0) {
            $id_schedule_item  = $this->input->post('idscheduleitem[]', true);
            $n_quantity  = str_replace(',','',$this->input->post('nquantity_kirim[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
           
            $cekdata     = $this->mmaster->cek_kodeedit($istb_cutting, $istb_cuttingold,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                    'kode'   => ' Sudah Ada',
                    'id'     => '',
                );
            }else{
                $this->db->trans_begin();
                // $id = $this->mmaster->runningid();
                $this->mmaster->updateheader($id,$ibagian,$istb_cutting,$dstb_cutting,$remark);
                $this->mmaster->deletedetail($id);

                $no = 0;
                foreach ($id_schedule_item as $idscheduleitem) {
                    $idscheduleitem  = $idscheduleitem;
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];

                    if (($idscheduleitem != '' || $idscheduleitem != null) && $nquantity > 0) {
                        $this->mmaster->insertdetail($id,$idscheduleitem, $nquantity,$eremark);  
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
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $istb_cutting);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $istb_cutting,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
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
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function cetak()
    {
        
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => "Cetak ".$this->global['title'],
            'id'     => $this->uri->segment(4),
            'data'   => $this->mmaster->dataheader($this->uri->segment(4)),
            'detail' => $this->mmaster->datadetail($this->uri->segment(4)),
        );

        $this->Logger->write('Cetak '.$this->global['title'].' Id : '.$this->uri->segment(4));

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }
}
/* End of file Cform.php */
