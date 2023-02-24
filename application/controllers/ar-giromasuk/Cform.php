<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2040214';

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
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
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

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'bagian' => $this->mmaster->bagian()->result(),
            'number' => "GR 0001",
            'dfrom' => $this->uri->segment(4),
            'dto' => $this->uri->segment(5),
            'area' => $this->mmaster->area()->result(),
            'bank' => $this->db->get_where('tr_bank', ['f_status'=>'t', 'id_company'=>$this->id_company])->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('i_bagian', TRUE), $this->input->post('i_area', TRUE), $this->input->post('id', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CARI CUSTOMER  ----------*/

    public function customer()
    {
        $filter = [];
        $data = $this->mmaster->customer(str_replace("'", "", $this->input->get('q')), $this->input->get('i_area'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI CUSTOMER  ----------*/

    public function salesman()
    {
        $filter = [];
        $cari = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        $i_customer = $this->input->get('i_customer');
        $d_kum = $this->input->get('d_kum');
        if (strlen($d_kum) > 0) {
            $d_kum = formatYm($d_kum);
        }else{
            $d_kum = date('Ym');
        }
        $data = $this->mmaster->salesman($cari, $i_area, $i_customer, $d_kum);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI DT  ----------*/

    public function dt()
    {
        $filter = [];
        $cari = str_replace("'", "", $this->input->get('q'));
        $i_area = $this->input->get('i_area');
        $i_customer = $this->input->get('i_customer');
        $data = $this->mmaster->dt($cari, $i_area, $i_customer);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_bagian = $this->input->post('i_bagian');
        $i_giro_id = $this->input->post('i_giro_id');
        $d_giro = $this->input->post('d_giro');
        $d_giro_duedate = $this->input->post('d_giro_duedate');
        $d_giro_terima = $this->input->post('d_giro_terima');
        if (strlen($d_giro) > 0 || strlen($d_giro_duedate) > 0 || strlen($d_giro_terima) > 0) {
            $d_giro = formatYmd($d_giro);
            $d_giro_duedate = formatYmd($d_giro_duedate);
            $d_giro_terima = formatYmd($d_giro_terima);
        } else {
            $d_giro = date('Y-m-d');
            $d_giro_duedate = date('Y-m-d');
            $d_giro_terima = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $i_customer = $this->input->post('i_customer');
        // $i_salesman = $this->input->post('i_salesman');
        $i_dt = $this->input->post('i_dt');
        $e_giro_bank = $this->input->post('e_giro_bank');
        $v_jumlah = str_replace(",","",$this->input->post('v_jumlah'));
        // $e_send_name = $this->input->post('e_send_name');
        $e_giro_description = $this->input->post('e_giro_description');
        if ($i_bagian != '' && $d_giro != '' && $i_customer != '' && $i_area != '' && $v_jumlah > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->create($id, $i_giro_id, $i_bagian, $i_area, $i_customer, $e_giro_bank, $i_dt, $d_giro, $d_giro_duedate, $d_giro_terima, $v_jumlah, $e_giro_description);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $i_giro_id,
                    'id' => $id
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

    /** Cek Number */
	public function cek_code()
	{
		$data = $this->mmaster->cek_code();
		if ($data->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}

    /** Cek Number Edit*/
	public function cek_code_edit()
	{
		$data = $this->mmaster->cek_edit();
		if ($data->num_rows() > 0) {
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}

    /*----------  MEMBUKA MENU EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'area' => $this->mmaster->area()->result(),
            'bank' => $this->db->get_where('tr_bank', ['f_status'=>'t', 'id_company'=>$this->id_company])->result(),
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

        $id = $this->input->post('id', TRUE);
        $i_bagian = $this->input->post('i_bagian');
        $i_giro_id = $this->input->post('i_giro_id');
        $d_giro = $this->input->post('d_giro');
        $d_giro_duedate = $this->input->post('d_giro_duedate');
        $d_giro_terima = $this->input->post('d_giro_terima');
        if (strlen($d_giro) > 0 || strlen($d_giro_duedate) > 0 || strlen($d_giro_terima) > 0) {
            $d_giro = formatYmd($d_giro);
            $d_giro_duedate = formatYmd($d_giro_duedate);
            $d_giro_terima = formatYmd($d_giro_terima);
        } else {
            $d_giro = date('Y-m-d');
            $d_giro_duedate = date('Y-m-d');
            $d_giro_terima = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $i_customer = $this->input->post('i_customer');
        $i_dt = $this->input->post('i_dt');
        $e_giro_bank = $this->input->post('e_giro_bank');
        $v_jumlah = str_replace(",","",$this->input->post('v_jumlah'));
        $e_giro_description = $this->input->post('e_giro_description');
        if ($i_bagian != '' && $d_giro != '' && $i_customer != '' && $i_area != '' && $v_jumlah > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id, $i_giro_id, $i_bagian, $i_area, $i_customer, $e_giro_bank, $i_dt, $d_giro, $d_giro_duedate, $d_giro_terima, $v_jumlah, $e_giro_description);
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode' => $i_giro_id,
                    'id' => $id
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

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'area' => $this->mmaster->area()->result(),
            'bank' => $this->db->get_where('tr_bank', ['f_status'=>'t', 'id_company'=>$this->id_company])->result(),
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

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Lihat " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'area' => $this->mmaster->area()->result(),
            'bank' => $this->db->get_where('tr_bank', ['f_status'=>'t', 'id_company'=>$this->id_company])->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}
/* End of file Cform.php */