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

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data()
    {
        $dfrom  = $this->uri->segment(4);
        $dto    = $this->uri->segment(5);
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }

    public function tambah()
    {
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
            'partner'        => $this->mmaster->partner(),
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        //var_dump($this->mmaster->gudangjadi()->result());
        //die();
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
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

    public function simpan() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $dpermintaan = $this->input->post("dpermintaan", true);
        if ($dpermintaan!='') {
            $datepermintaan  = date('Y-m-d', strtotime($dpermintaan));
            $yearmonth = date('Ym', strtotime($dpermintaan));
        }

        $dbayar = $this->input->post("dbayar", true);
        if ($dbayar!='') {
            $datebayar= date('Y-m-d', strtotime($dbayar));
        }

        $partner        = $this->input->post('partner', TRUE);
        $jumlah         = removetext($this->input->post('jumlah', TRUE));
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $ipermintaan = '';
     
        if ($dpermintaan!='') {
            $this->db->trans_begin();
            $ipermintaan = $this->mmaster->runningnumber($yearmonth);
            //var_dump($ipermintaan, $partner, $datepermintaan, $datebayar, $jumlah, $remark);
            
            $this->mmaster->insertheader($ipermintaan, $partner, $datepermintaan, $datebayar, $jumlah, $remark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $i_nota         = $this->input->post('i_nota'.$i, TRUE);
                $d_nota         = date('Y-m-d', strtotime($this->input->post('d_nota'.$i, TRUE)));
                $ijenis         = $this->input->post('ijenis'.$i, TRUE);
                $jatuh_tempo    = date('Y-m-d', strtotime($this->input->post('jatuh_tempo'.$i, TRUE)));
                $v_saldo        = removetext($this->input->post('v_saldo'.$i, TRUE));
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $check          = $this->input->post('chk'.$i, TRUE);

                if($check=="on"){
                    $x++;
                    $this->mmaster->insertdetail($ipermintaan,$partner,$i_nota, $d_nota, $ijenis,$jatuh_tempo,$v_saldo,$edesc,$x);
                    //var_dump($ipermintaan,$partner,$i_nota, $d_nota, $ijenis,$jatuh_tempo,$v_saldo,$edesc,$x);
                }
                
            }
            //die();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ipermintaan);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ipermintaan,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ipembayaran      = $this->uri->segment(4);
        $partner = $this->uri->segment(5);
        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'partner'       => $this->mmaster->partner(),
            'jenis'         => $this->mmaster->jenis(),
            'data'          => $this->mmaster->baca($ipembayaran,$partner),
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function getdetailedit(){
        header("Content-Type: application/json", true);
        $partner  = $this->input->post('partner', FALSE);
        $jenis    = $this->input->post('jenis', FALSE);
        $ipembayaran   = $this->input->post('i_pembayaran', FALSE);

 
        $query  = array(
            'detail' => $this->mmaster->getdetailedit($partner, $jenis, $ipembayaran)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }


    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dpermintaan = $this->input->post("dpermintaan", true);
        if ($dpermintaan!='') {
            $datepermintaan  = date('Y-m-d', strtotime($dpermintaan));
            $yearmonth = date('Ym', strtotime($dpermintaan));
        }

        $dbayar = $this->input->post("dbayar", true);
        if ($dbayar!='') {
            $datebayar= date('Y-m-d', strtotime($dbayar));
        }

        $partner        = $this->input->post('partner', TRUE);
        $jumlah         = removetext($this->input->post('jumlah', TRUE));
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $ipermintaan    = $this->input->post('i_pembayaran', TRUE);

        if ($dpermintaan) {
            $this->db->trans_begin();
            // var_dump($inota, $partner, $isjkeluar, $datefaktur, $nopajak, $datepajak, $gross, $ndiscount, $discount, $netto, $remark);
            // die();
            $this->mmaster->updateheader($ipermintaan, $partner, $datepermintaan, $datebayar, $jumlah, $remark);
            $this->mmaster->deletedetail($ipermintaan);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $i_nota         = $this->input->post('i_nota'.$i, TRUE);
                $d_nota         = date('Y-m-d', strtotime($this->input->post('d_nota'.$i, TRUE)));
                $ijenis         = $this->input->post('ijenis'.$i, TRUE);
                $jatuh_tempo    = date('Y-m-d', strtotime($this->input->post('jatuh_tempo'.$i, TRUE)));
                $v_saldo        = removetext($this->input->post('v_saldo'.$i, TRUE));
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $check          = $this->input->post('chk'.$i, TRUE);

                if($check=="on"){
                    $x++;
                    $this->mmaster->insertdetail($ipermintaan,$partner,$i_nota, $d_nota, $ijenis,$jatuh_tempo,$v_saldo,$edesc,$x);
                    //var_dump($ipermintaan,$partner,$i_nota, $d_nota, $ijenis,$jatuh_tempo,$v_saldo,$edesc,$x);
                }
                
            }
           // die();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $ipermintaan);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ipermintaan,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }


    public function approval() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ipembayaran      = $this->uri->segment(4);
        $partner = $this->uri->segment(5);
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
            'partner'       => $this->mmaster->partner(),
            'jenis'         => $this->mmaster->jenis(),
            'data'          => $this->mmaster->baca($ipembayaran,$partner),
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
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


    public function approve() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $partner        = $this->input->post('partner', TRUE);
        $jumlah         = removetext($this->input->post('jumlah', TRUE));
        $jml            = $this->input->post('jml', TRUE);
        $ipermintaan    = $this->input->post('i_pembayaran', TRUE);
        $inota = "";
        for($i=1;$i<=$jml;$i++){
                $i_nota         = $this->input->post('i_nota'.$i, TRUE);
                $data = $this->mmaster->cek_approve($ipermintaan, $i_nota,$partner);
                if ($data->num_rows() > 0) {
                    $inota = $data->row()->i_nota;
                    break;
                }
                
        }
        if ($inota == null || $inota == "") {
            $this->db->trans_begin();
            $this->mmaster->approve($ipermintaan, $inota,$partner);

            for($i=1;$i<=$jml;$i++){
                $i_nota         = $this->input->post('i_nota'.$i, TRUE);
                $ijenis         = $this->input->post('ijenis'.$i, TRUE);

                $this->mmaster->updatesaldo($i_nota,$ijenis);
                    //var_dump($ipermintaan,$partner,$i_nota, $d_nota, $ijenis,$jatuh_tempo,$v_saldo,$edesc,$x);
                //var_dump($i_nota,$ijenis);
                
            }
            //die();

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Approve Data ' . $this->global['title'] . ' Kode : ' . $ipermintaan);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ipermintaan,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => "Faktur ".$inota. " Sudah Dibuat Permintaan Pembayaran",
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ipembayaran      = $this->uri->segment(4);
        $partner = $this->uri->segment(5);
        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'partner'       => $this->mmaster->partner(),
            'jenis'         => $this->mmaster->jenis(),
            'data'          => $this->mmaster->baca($ipembayaran,$partner),
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    // public function getdetailsjview(){
    //     header("Content-Type: application/json", true);
    //     $sj  = $this->input->post('sj', FALSE);
    //     $partner  = $this->input->post('partner', FALSE);
    //     $nota  = $this->input->post('nota', FALSE);
    //     $query  = array(
    //         'head' => $this->mmaster->gethead($sj, $partner)->row(),
    //         'detail' => $this->mmaster->getdetailview($sj, $partner,$nota)->result_array()
    //     );
    //     //var_dump($query);
    //     echo json_encode($query);  
    // }


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

    














    // public function updatestatus(){
    //     $data = check_role($this->i_menu, 3);
    //     if (!$data) {
    //         redirect(base_url(), 'refresh');
    //     }

    //     $ibonk   = $this->input->post('ibonk', true);
    //     $istatus = $this->input->post('istatus', true);
    //     $ibagian = $this->input->post('ibagian', true);
    //     $this->db->trans_begin();
    //     $data = $this->mmaster->updatestatus($ibonk,$istatus,$ibagian);
    //     if ($this->db->trans_status() === false) {
    //         $this->db->trans_rollback();
    //     }else {
    //         $this->db->trans_commit();
    //         $this->Logger->write('Update Status '.$this->global['folder'].' Menjadi : '.$istatus.' No : '.$ibonk);
    //         echo json_encode($data);
    //     }
    // }

    // public function deletedetail(){
    //     $data = check_role($this->i_menu, 4);
    //     if (!$data) {
    //         redirect(base_url(), 'refresh');
    //     }

    //     $ibonk = $this->input->post('ibonk', true);
    //     $icolor = $this->input->post('icolor', true);
    //     $iproduct = $this->input->post('iproduct', true);
    //     $ibagian = $this->input->post('ibagian', true);
    //     $this->db->trans_begin();
    //     $data = $this->mmaster->cancelitem($ibonk,$icolor,$iproduct,$ibagian);
    //     if ($this->db->trans_status() === false) {
    //         $this->db->trans_rollback();
    //     }else {
    //         $this->db->trans_commit();
    //         $this->Logger->write('Cancel Item '.$this->global['folder'].' No : '.$ibonk.' Product : '.$iproduct.' Color : '.$icolor);
    //         echo json_encode($data);
    //     }
    // }


    // public function cetak()
    // {
    //     $id      = $this->uri->segment(4);
    //     $ibagian = $this->uri->segment(5);
    //     $dfrom   = $this->uri->segment(6);
    //     $dto     = $this->uri->segment(7);

    //     $data = array(
    //         'folder'        => $this->global['folder'],
    //         'title'         => $this->global['title'],
    //         'tujuan'        => $this->mmaster->tujuan(),
    //         'gudang'        => $this->mmaster->gudang(),
    //         'i_menu'        => $this->i_menu,
    //         'dfrom'         => $dfrom,
    //         'dto'           => $dto,
    //         'id'            => $id,
    //         'ibagian'       => $ibagian,
    //         'idepartemen'   => trim($this->session->userdata('i_departement')),
    //         'ilevel'        => trim($this->session->userdata('i_level')),
    //         'data'          => $this->mmaster->baca($id,$ibagian),
    //         'detail'        => $this->mmaster->bacadetail($id,$ibagian),
    //     );

    //     $this->Logger->write('Membuka Menu Cetak ' . $this->global['title']);

    //     $this->load->view($this->global['folder'] . '/vformprint', $data);
    // }

    
}
/* End of file Cform.php */
