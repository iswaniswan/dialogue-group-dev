<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2050109';

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
        $i_lokasi      = $this->session->userdata('i_lokasi');
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto, $i_lokasi);
    }

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
            'customer'        => $this->mmaster->customer(),
            'gudangjadi'        => $this->mmaster->gudangjadi(),
        );

        //var_dump($this->mmaster->gudangjadi()->result());
        //die();
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function ttb() {
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->nottb($cari,$this->input->get('customer'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->i_ttb,  
                'text' => $key->i_ttb. " - ".$key->d_ttb,
            );
        }          
        echo json_encode($filter);
    }

    public function getdetailttb(){
        header("Content-Type: application/json", true);
        $ttb  = $this->input->post('ttb', FALSE);
        $customer  = $this->input->post('customer', FALSE);
        $query  = array(
            //'head' => $this->mmaster->gethead($sj, $partner)->row(),
            'detail' => $this->mmaster->getdetail($ttb, $customer)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function simpan() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dbbm = $this->input->post("dbbm", true);
        if ($dbbm!='') {
            $datebbm  = date('Y-m-d', strtotime($dbbm));
            $yearmonth = date('Ym', strtotime($dbbm));
        }

        $ikodelokasi     = $this->input->post('ikodelokasi', TRUE);
        $customer    = $this->input->post('customer', TRUE);
        $ttb   = $this->input->post('ttb', TRUE);
        $eremark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        
        $ibbm = '';
      
        if ($datebbm!='') {
            $this->db->trans_begin();
            $ibbm = $this->mmaster->runningnumber($yearmonth,$ikodelokasi);
            //var_dump($ibbm, $ikodelokasi,$customer,$ttb,$datebbm,$eremark, $jml);
            // die();
            $this->mmaster->insertheader($ibbm, $ikodelokasi,$customer,$ttb,$datebbm,$eremark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $i_product      = $this->input->post('i_product'.$i, TRUE);
                $i_color        = $this->input->post('i_color'.$i, TRUE);
                $qty_retur      = $this->input->post('qty_retur'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $check          = $this->input->post('chk'.$i, TRUE);

                if($check=="on"){
                    $x++;
                    $this->mmaster->insertdetail($ibbm, $ikodelokasi,$i_product,$i_color,$qty_retur,$edesc, $x);
                    //var_dump($ibbm, $ikodelokasi,$i_product,$i_color,$qty_retur,$edesc, $x);
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
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibbm);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ibbm,
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

    public function getdetailttbedit(){
        header("Content-Type: application/json", true);
        $ibbm  = $this->input->post('ibbm', FALSE);
        $ttb   = $this->input->post('ttb', FALSE);
        $customer  = $this->input->post('customer', FALSE);
        $query  = array(
            //'head' => $this->mmaster->gethead($sj, $partner)->row(),
            'detail' => $this->mmaster->getdetailedit($ibbm, $ttb, $customer)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_bbm      = $this->uri->segment(4);
        $icustomer = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'customer'        => $this->mmaster->customer(),
            'gudangjadi'        => $this->mmaster->gudangjadi(),
            'data'          => $this->mmaster->baca($i_bbm,$icustomer),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dbbm = $this->input->post("dbbm", true);
        if ($dbbm!='') {
            $datebbm  = date('Y-m-d', strtotime($dbbm));
            $yearmonth = date('Ym', strtotime($dbbm));
        }

        $ikodelokasi     = $this->input->post('ikodelokasi', TRUE);
        $customer    = $this->input->post('customer', TRUE);
        $ttb   = $this->input->post('ttb', TRUE);
        $eremark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $ibbm          = $this->input->post('ibbm', TRUE);
        if ($datebbm) {
            $this->db->trans_begin();
            $this->mmaster->updateheader($ibbm, $ikodelokasi,$customer,$ttb,$datebbm,$eremark);
            $this->mmaster->deletedetail($ibbm);

            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $i_product      = $this->input->post('i_product'.$i, TRUE);
                $i_color        = $this->input->post('i_color'.$i, TRUE);
                $qty_retur      = $this->input->post('qty_retur'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $check          = $this->input->post('chk'.$i, TRUE);

                if($check=="on"){
                    $x++;
                    $this->mmaster->insertdetail($ibbm, $ikodelokasi,$i_product,$i_color,$qty_retur,$edesc, $x);
                    //var_dump($ibbm, $ikodelokasi,$i_product,$i_color,$qty_retur,$edesc, $x);
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
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $ibbm);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ibbm,
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

        $i_bbm      = $this->uri->segment(4);
        $icustomer  = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'customer'        => $this->mmaster->customer(),
            'gudangjadi'        => $this->mmaster->gudangjadi(),
            'data'          => $this->mmaster->baca($i_bbm,$icustomer),
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

        $ibbm          = $this->input->post('ibbm', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $ttb          = $this->input->post('ttb', TRUE);
        $ikodelokasi     = $this->input->post('ikodelokasi', TRUE);
       
        if ($ibbm) {
            $this->db->trans_begin();
            // var_dump($inota, $partner, $isjkeluar, $datefaktur, $nopajak, $datepajak, $gross, $ndiscount, $discount, $netto, $remark);
            // die();
            $this->mmaster->approve($ibbm);
             $x = 0;
            for($i=1;$i<=$jml;$i++){
                $i_product      = $this->input->post('i_product'.$i, TRUE);
                $i_color        = $this->input->post('i_color'.$i, TRUE);
                $qty_retur      = $this->input->post('qty_retur'.$i, TRUE);
                $grade = "B";
                
                $this->mmaster->update_ic($i_product, $ikodelokasi, $i_color,$qty_retur,$grade);
                //var_dump($i_product, $ikodelokasi, $i_color,$qty_retur,$grade);

                
            }
           //die();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Approve Data ' . $this->global['title'] . ' Kode : ' . $ibbm);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ibbm,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }


    public function view() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_bbm      = $this->uri->segment(4);
        $icustomer  = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'customer'        => $this->mmaster->customer(),
            'gudangjadi'        => $this->mmaster->gudangjadi(),
            'data'          => $this->mmaster->baca($i_bbm,$icustomer),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }



    public function getdetailsjview(){
        header("Content-Type: application/json", true);
        $sj  = $this->input->post('sj', FALSE);
        $partner  = $this->input->post('partner', FALSE);
        $nota  = $this->input->post('nota', FALSE);
        $query  = array(
            'head' => $this->mmaster->gethead($sj, $partner)->row(),
            'detail' => $this->mmaster->getdetailview($sj, $partner,$nota)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }


     public function delete() {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $kode = $this->input->post('kode');
        $partner = $this->input->post('partner');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($kode, $partner);
        if (($this->db->trans_status() === False)) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Data '.$this->global['title'].' No BBM : '.$kode. ' Partner : ' .$partner);
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
