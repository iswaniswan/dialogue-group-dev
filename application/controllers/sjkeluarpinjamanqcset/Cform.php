<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090209';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
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

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

		echo $this->mmaster->data($this->global['folder'],$this->i_menu, $dfrom, $dto);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SJP-".date('ym')."-000001",
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function number() {
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

    public function partner(){
        $filter = [];
        $data   = $this->mmaster->partner(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id.'|'.$row->i_partner,
                'text'  => $row->e_partner,
            );
        }
        echo json_encode($filter);
    }

    public function referensi(){
        $filter = [];
        $ipartner = $this->input->get('ipartner');
         if($ipartner){
            $tmp      = explode('|', $ipartner);
            $id       = $tmp[0];
            $ipartner = $tmp[1];
        }
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')), $id, $ipartner);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->i_document,
            );
        }
        $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
        echo json_encode($filter);
    }

    /*----------  CARI BARANG  ----------*/

    public function product() {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - ' .$row->e_color_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Cari Barang Berdasarkan Nama / Kode"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/

    public function detailproduct() {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detailproduct($this->input->post('id',TRUE))->result_array()
        );
        echo json_encode($query);  
    } 

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if($ddocument){
             $tmp   = explode('-', $ddocument);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $datedocument  = $year.'-'.$month.'-'.$day;
        }
        $dback          = $this->input->post('dback', TRUE);
        if($dback){
             $tmp   = explode('-', $dback);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $dateback  = $year.'-'.$month.'-'.$day;
        }
        $ipartner       = $this->input->post('ipartner', TRUE);
        if($ipartner){
             $tmp       = explode('|', $ipartner);
             $idpartner = $tmp[0];
             $epartner  = $tmp[1];
        }        
        $ireff          = $this->input->post('ireff', TRUE);
        if($ireff == '' || $ireff == null){
            $ireff = 0;
        }else{
            $ireff = $ireff;
        }
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $id             = $this->mmaster->runningid();

        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id, $idocument, $datedocument, $ibagian, $dateback, $idpartner, $epartner, $ireff, $eremarkh);

            for ($x=0; $x <= $jml ; $x++) { 
                $idproduct = $this->input->post('idproduct'.$x, TRUE);
                $nquantitywip = str_replace(",",".",$this->input->post('nquantity'.$x, TRUE));
                $i  = 0;
                if ($idproduct != "" || $idproduct != NULL) {
                    foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                        if ($idproduct == $idproductwip) {
                            $idmaterial  = $this->input->post("idmaterial[]", TRUE)[$i];
                            $nquantitymat   = str_replace(",",".",$this->input->post("nquantity[]", TRUE)[$i]);
                            $eremark     = $this->input->post("eremark[]", TRUE)[$i];
                            $this->mmaster->insertdetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark);
                            //var_dump($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark);
                        }   
                        $i++;
                    }
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
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }

        $this->load->view('pesan2', $data);
    }


   /*----------  MEMBUKA MENU EDIT  ----------*/
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }


     public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if($ddocument){
             $tmp   = explode('-', $ddocument);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $datedocument  = $year.'-'.$month.'-'.$day;
        }
        $dback          = $this->input->post('dback', TRUE);
        if($dback){
             $tmp   = explode('-', $dback);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $dateback  = $year.'-'.$month.'-'.$day;
        }
        $ipartner       = $this->input->post('ipartner', TRUE);
        if($ipartner){
             $tmp       = explode('|', $ipartner);
             $idpartner = $tmp[0];
             $epartner  = $tmp[1];
        }        
        $ireff          = $this->input->post('ireff', TRUE);
        if($ireff == '' || $ireff == null){
            $ireff = 0;
        }else{
            $ireff = $ireff;
        }
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        
        // var_dump($id_company, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);
        // die();
       
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $cekdata     = $this->mmaster->cek_kode($idocumentold,$ibagian);
            if ($cekdata->num_rows()==0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $idocument, $datedocument, $ibagian, $dateback, $idpartner, $epartner, $ireff, $eremarkh);
                $this->mmaster->deletedetail($id);

                for ($x=0; $x <= $jml ; $x++) { 
                    $idproduct = $this->input->post('idproduct'.$x, TRUE);
                    $nquantitywip = str_replace(",",".",$this->input->post('nquantity'.$x, TRUE));
                    $i  = 0;
                    if ($idproduct != "" || $idproduct != NULL) {
                        foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                            if ($idproduct == $idproductwip) {
                                $idmaterial  = $this->input->post("idmaterial[]", TRUE)[$i];
                                $nquantitymat   = str_replace(",",".",$this->input->post("nquantity[]", TRUE)[$i]);
                                $eremark     = $this->input->post("eremark[]", TRUE)[$i];
                                $this->mmaster->insertdetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark);
                                //var_dump($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark);
                            }   
                            $i++;
                        }
                    }
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
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode',TRUE),$this->input->post('kodeold',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function changestatus() {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
   }  

     /*----------  MEMBUKA MENU Approve  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }



    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

       $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */