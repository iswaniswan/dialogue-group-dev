<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2040106';

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
        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index()
    {
        $d = new DateTime();

        $one_year = new DateInterval('P1M');
        $one_year_ago = new DateTime();
        $one_year_ago->sub($one_year);

        // Output the microseconds.
        $akhir = $d->format('d-m-Y');
        $awal  = $one_year_ago->format('d-m-Y');

        if ($this->input->post('dfrom', TRUE) != '') {
            $awal = $this->input->post('dfrom', TRUE);
        } else {
            if ($this->uri->segment(4) != '') {
                $awal = $this->uri->segment(4);
            }
        }

        if ($this->input->post('dto', TRUE) != '') {
                $akhir = $this->input->post('dto', TRUE);
        } else {
            if ($this->uri->segment(5) != '') {
                $akhir = $this->uri->segment(5);
            }
        }       

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function tambah() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'partner'       => $this->mmaster->partner(),
            'jenis'         => $this->mmaster->jenis(),
            'number'        => "PPAP-".date('ym')."-000001",
            'dfrom'         => $awal,
            'dto'           => $akhir,
            'ldfrom'        => $this->uri->segment(4),
            'ldto'          => $this->uri->segment(5),
        );

        //var_dump($this->mmaster->gudangjadi()->result());
        //die();
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function cekkode() {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode',TRUE),$this->input->post('kodeold',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function getdetail(){
        header("Content-Type: application/json", true);
        $partner  = $this->input->post('partner', FALSE);
        $jenis    = $this->input->post('jenis', FALSE);
        $jtawal   = $this->input->post('jtawal', FALSE);
        $jtakhir  = $this->input->post('jtakhir', FALSE);

        $query  = array(
            'detail' => $this->mmaster->getdetail($partner, $jenis, $jtawal, $jtakhir)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function changestatus() {

        // $data = check_role($this->i_menu, 7);
        // if (!$data) {
        //     redirect(base_url(), 'refresh');
        // }

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

    public function simpan() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $dppap  = date('Y-m-d', strtotime($this->input->post("dppap", true)));
        $drppap= date('Y-m-d', strtotime($this->input->post("drppap", true)));
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ippap          = $this->input->post('ippap', TRUE);
        $partner        = $this->input->post('partner', TRUE);
        $jumlah         = removetext($this->input->post('jumlah', TRUE));
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $id_company     = $this->session->userdata('id_company');
        
        // var_dump($id_company, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);
        // die();
        if ($ippap != '') {
            $cekdata     = $this->mmaster->cek_kode($ippap,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id_company, $id, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);

                for($i=1;$i<=$jml;$i++){
                    $id_nota        = $this->input->post('id_nota'.$i, TRUE);
                    $ijenis         = $this->input->post('ijenis'.$i, TRUE);
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $check          = $this->input->post('chk'.$i, TRUE);
                    // var_dump($check);
                    // die();
                    if($check=="cek"){
                        // var_dump($check);
                        // die();
                        $this->mmaster->insertdetail($id_nota,$id,$ijenis,$edesc);  
                    }
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ippap);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ippap,
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

    public function data() {
        $dfrom  = $this->uri->segment(4);
        $dto    = $this->uri->segment(5);
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }

    public function view() {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "View " . $this->global['title'],
            'title_list'=> 'List ' . $this->global['title'],
            'bagian'    => $this->mmaster->bagian()->result(),
            'partner'   => $this->mmaster->partner(),
            'jenis'     => $this->mmaster->jenis(),
            'number'    => "PPAP-".date('ym')."-000001",
            'data'      => $this->mmaster->baca($this->uri->segment(4), $this->uri->segment(5)),
            'dfrom'     => $awal,
            'dto'       => $akhir,
            'id'        => $this->uri->segment(4),
            'ldfrom'    => $this->uri->segment(5),
            'ldto'      => $this->uri->segment(6),
            'ijenis'    => $this->uri->segment(7)
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Update " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'partner'    => $this->mmaster->partner(),
            'jenis'      => $this->mmaster->jenis(),
            'number'     => "PPAP-".date('ym')."-000001",
            'data'       => $this->mmaster->baca($this->uri->segment(4), $this->uri->segment(7)),
            'dfrom'      => $awal,
            'dto'        => $akhir,
            'jenis'      => $this->mmaster->jenis(),
            'id'         => $this->uri->segment(4),
            'ldfrom'     => $this->uri->segment(5),
            'ldto'       => $this->uri->segment(6),
            'ijenis'     => $this->uri->segment(7)
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function getdetailedit(){
        header("Content-Type: application/json", true);
        $id  = $this->input->post('id', FALSE);
        $ijenis = $this->input->post('ijenis', FALSE);
        $query  = array(
            'detail' => $this->mmaster->getdetailedit($id, $ijenis)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }
   
    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dppap          = date('Y-m-d', strtotime($this->input->post("dppap", true)));
        $drppap         = date('Y-m-d', strtotime($this->input->post("drppap", true)));
        $id             = $this->input->post('id', true);
        $ippapold       = $this->input->post('ippapold', true);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ippap          = $this->input->post('ippap', TRUE);
        $partner        = $this->input->post('partner', TRUE);
        $jumlah         = removetext($this->input->post('jumlah', TRUE));
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $id_company     = $this->session->userdata('id_company');
        
        // var_dump($id_company, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);
        // die();
        if ($ippap != '') {
            $cekdata     = $this->mmaster->cek_kode($ippapold,$ibagian);
            if ($cekdata->num_rows()==0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id_company, $id, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);
                $this->mmaster->deletedetail($id);

                for($i=1;$i<=$jml;$i++){
                    $id_nota        = $this->input->post('id_nota'.$i, TRUE);
                    $ijenis         = $this->input->post('ijenis'.$i, TRUE);
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $check          = $this->input->post('chk'.$i, TRUE);
                    if($check=="on"){
                        $this->mmaster->insertdetail($id_nota,$id,$ijenis,$edesc);  
                    }
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $ippap);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ippap,
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

    public function approval() {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'partner'       => $this->mmaster->partner(),
            'jenis'         => $this->mmaster->jenis(),
            'number'        => "PPAP-".date('ym')."-000001",
            'data'          => $this->mmaster->baca($this->uri->segment(4),$this->uri->segment(7)),
            'dfrom'         => $awal,
            'dto'           => $akhir,
            'id'            => $this->uri->segment(4),
            'ldfrom'        => $this->uri->segment(5),
            'ldto'          => $this->uri->segment(6),
            'ijenis'        => $this->uri->segment(7)
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function approve() {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $id     = $this->input->post('id', TRUE);
        $ippap  = $this->input->post('ippap', TRUE);
        $jml    = $this->input->post('jml', TRUE);

        $i_ppap = "";
        $i_nota = "";
        for($i=1;$i<=$jml;$i++){
            $id_nota        = $this->input->post('id_nota'.$i, TRUE);
            
            $data = $this->mmaster->cek_approve($id, $id_nota);
            if ($data->num_rows() > 0) {
                $i_ppap = $data->row()->i_ppap;
                $i_nota = $this->input->post('i_nota'.$i, TRUE);
                //break;
            }
                
        }
        // var_dump($i_ppap);
        // die();
        if ($i_ppap == null || $i_ppap == "") {
            $this->db->trans_begin();
            $this->mmaster->approve($id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Approve Data ' . $this->global['title'] . ' Kode : ' . $id);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ippap,
                    'id'     => $id
                );
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => "Faktur ".$i_nota. " Sudah Dibuat Permintaan Pembayaran " .$i_ppap,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->change($kode);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->reject($kode);
    }

    public function delete() {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $ipembayaran = $this->input->post('ipembayaran');
        $partner = $this->input->post('partner');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ipembayaran, $partner);
        if (($this->db->trans_status() === False)) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Data '.$this->global['title'].' No Permintaan Pembayaran : '.$ipembayaran. ' Partner : ' .$partner);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */