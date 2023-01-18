<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2050119';

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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'tujuan'        => $this->mmaster->tujuan(),
            //'gudang'        => $this->mmaster->gudang(),
            'pembuat'       => $this->session->userdata('username'),
            'gudangjadi'        => $this->mmaster->gudangjadi(),
        );

        //var_dump($this->mmaster->gudangjadi()->result());
        //die();
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function tujuan(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->tujuan($cari);
        foreach($data->result() as  $key){
            $filter[] = array(
                'id'   => $key->i_tujuan,  
                'text' => $key->e_tujuan_name,
            );
        }          
        echo json_encode($filter);
    }

    public function departemen(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->departemen($cari,$this->input->get('itujuan'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->name,
            );
        }          
        echo json_encode($filter);
    }

    public function ppic(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->ppic($cari,$this->input->get('itujuan'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->name,
            );
        }          
        echo json_encode($filter);
    }

    public function dataproduct(){
        $filter = [];
        if($this->input->get('q') != ''){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->product($cari);
            //var_dump($data->result());
            //die();
            foreach($data->result() as $product){       
                $filter[] = array(
                    'id'    => $product->i_product_motif.'|'.$product->i_color,
                    'name' => $product->e_product_basename,
                    'text'  => $product->i_product_motif.' - '.$product->e_product_basename.' - '.$product->e_color_name,
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }

    }  

    public function getproduct(){
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('iproduct'), $this->input->post('icolor'), $this->input->post('ikodelokasi'));
        echo json_encode($data->result_array());
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dbonk = $this->input->post("dbonk", true);
        if ($dbonk!='') {
            $datebonk  = date('Y-m-d', strtotime($dbonk));
            $yearmonth = date('Ym', strtotime($dbonk));
        }

        $dback = $this->input->post("dback", true);
        if ($dback!='') {
            $dateback = date('Y-m-d', strtotime($dback));
        }else{
            $dateback = date('Y-m-d', strtotime('+1 month', strtotime($datebonk)));
        }

        $ikodelokasi     = $this->input->post('ikodelokasi', TRUE);
        $ikodemaster    = $this->input->post('ikodemaster', TRUE);
        $tujuankeluar   = $this->input->post('itujuan', TRUE);
        $pic            = $this->input->post('ppic', TRUE);
        $epic           = $this->input->post('epic', TRUE);
        $dept           = $this->input->post('idepartemen', TRUE);
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $imemo = '';
     
        if ($dbonk!='' && $tujuankeluar!='' && $dept!='') {
            $this->db->trans_begin();
            $imemo = $this->mmaster->runningnumber($yearmonth);
            //var_dump($imemo, $ikodemaster,$datebonk,$tujuankeluar, $dept, $ikodelokasi,$dateback,$pic,$epic,$remark);
            // die();
            $this->mmaster->insertheader($imemo, $ikodemaster,$datebonk,$tujuankeluar, $dept, $ikodelokasi,$dateback,$pic,$epic,$remark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('xproduct'.$i, TRUE);
                $icolor         = $this->input->post('icolorproduct'.$i, TRUE);
                //$eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                if($nquantity>0){
                    $x++;
                    $this->mmaster->insertdetail($imemo,$ikodelokasi,$iproduct,$icolor,$nquantity,$edesc,$x);
                    //var_dump($imemo,$ikodelokasi,$iproduct,$icolor,$nquantity,$edesc,$x);
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
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $imemo);
                $data = array(
                    'sukses' => true,
                    'kode'   => $imemo,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $imemo      = $this->uri->segment(4);
        $ikodelokasi = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'tujuan'        => $this->mmaster->tujuan(),
            'gudangjadi'    => $this->mmaster->gudangjadi(),
            'i_menu'        => $this->i_menu,
            'data'          => $this->mmaster->baca($imemo,$ikodelokasi),
            'detail'        => $this->mmaster->bacadetail($imemo,$ikodelokasi),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function updatestatus(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonk   = $this->input->post('ibonk', true);
        $istatus = $this->input->post('istatus', true);
        $ibagian = $this->input->post('ibagian', true);
        $this->db->trans_begin();
        $data = $this->mmaster->updatestatus($ibonk,$istatus,$ibagian);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status '.$this->global['folder'].' Menjadi : '.$istatus.' No : '.$ibonk);
            echo json_encode($data);
        }
    }

    public function deletedetail(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonk = $this->input->post('ibonk', true);
        $icolor = $this->input->post('icolor', true);
        $iproduct = $this->input->post('iproduct', true);
        $ibagian = $this->input->post('ibagian', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancelitem($ibonk,$icolor,$iproduct,$ibagian);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Item '.$this->global['folder'].' No : '.$ibonk.' Product : '.$iproduct.' Color : '.$icolor);
            echo json_encode($data);
        }
    }


    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $imemo = $this->input->post("imemo", true);

        $dmemo = $this->input->post("dmemo", true);
        if ($dmemo!='') {
            $datememo = date('Y-m-d', strtotime($dmemo));
        }

        $dback = $this->input->post("dback", true);
        if ($dback!='') {
            $dateback = date('Y-m-d', strtotime($dback));
        }else{
            $dateback = date('Y-m-d', strtotime('+1 month', strtotime($datebonk)));
        }

        $ikodelokasi     = $this->input->post('ikodelokasi', TRUE);
        $tujuankeluar   = $this->input->post('itujuan', TRUE);
        $pic            = $this->input->post('ppic', TRUE);
        $epic           = $this->input->post('epic', TRUE);
        $dept           = $this->input->post('idepartemen', TRUE);
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

       

        if ($dmemo!='' && $tujuankeluar!='' && $dept!='') {
            $this->db->trans_begin();
            //  var_dump($imemo, $datememo,  $dateback, $ikodelokasi, $tujuankeluar, $pic, $epic, $dept, $remark);
            // die();
            $this->mmaster->update($imemo, $datememo,  $dateback, $ikodelokasi, $tujuankeluar, $pic, $epic, $dept, $remark);
            $this->mmaster->deletedetail($imemo,$ikodelokasi);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('xproduct'.$i, TRUE);
                $icolor         = $this->input->post('icolorproduct'.$i, TRUE);
                //$eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                if($nquantity>0){
                    $x++;
                    $this->mmaster->insertdetail($imemo,$ikodelokasi,$iproduct,$icolor,$nquantity,$edesc,$x);
                    //var_dump($imemo,$ikodelokasi,$iproduct,$icolor,$nquantity,$edesc,$x);
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
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $imemo);
                $data = array(
                    'sukses' => true,
                    'kode'   => $imemo,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $imemo = $this->input->post('imemo');
        $ikodelokasi = $this->input->post('ikodelokasi');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($imemo, $ikodelokasi);
        if (($this->db->trans_status() === False)) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Data '.$this->global['title'].' No Memo : '.$imemo. ' Lokasi : ' .$ikodelokasi);
            echo json_encode($data);
        }
    }

    public function view()
    {
        $imemo      = $this->uri->segment(4);
        $ikodelokasi = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'tujuan'        => $this->mmaster->tujuan(),
            'gudangjadi'    => $this->mmaster->gudangjadi(),
            'i_menu'        => $this->i_menu,
            'data'          => $this->mmaster->baca($imemo,$ikodelokasi),
            'detail'        => $this->mmaster->bacadetail($imemo,$ikodelokasi),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id      = $this->uri->segment(4);
        $ibagian = $this->uri->segment(5);
        $dfrom   = $this->uri->segment(6);
        $dto     = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'tujuan'        => $this->mmaster->tujuan(),
            'gudang'        => $this->mmaster->gudang(),
            'i_menu'        => $this->i_menu,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'ibagian'       => $ibagian,
            'idepartemen'   => trim($this->session->userdata('i_departement')),
            'ilevel'        => trim($this->session->userdata('i_level')),
            'data'          => $this->mmaster->baca($id,$ibagian),
            'detail'        => $this->mmaster->bacadetail($id,$ibagian),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function cetak()
    {
        $id      = $this->uri->segment(4);
        $ibagian = $this->uri->segment(5);
        $dfrom   = $this->uri->segment(6);
        $dto     = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'tujuan'        => $this->mmaster->tujuan(),
            'gudang'        => $this->mmaster->gudang(),
            'i_menu'        => $this->i_menu,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'ibagian'       => $ibagian,
            'idepartemen'   => trim($this->session->userdata('i_departement')),
            'ilevel'        => trim($this->session->userdata('i_level')),
            'data'          => $this->mmaster->baca($id,$ibagian),
            'detail'        => $this->mmaster->bacadetail($id,$ibagian),
        );

        $this->Logger->write('Membuka Menu Cetak ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }
}
/* End of file Cform.php */
