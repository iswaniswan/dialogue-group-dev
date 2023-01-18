<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090405';

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

    public function data(){
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

    public function bacatujuan(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->bacatujuan($cari, $this->i_menu);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->i_bagian,  
                'text' => $key->e_bagian_name,
            );
        }          
        echo json_encode($filter);
    }

    public function getreferensi(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $ipartner = $this->input->get('ipartner');
        $data = $this->mmaster->getreferensi($cari, $ipartner);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->i_document,
            );
        }          
        echo json_encode($filter);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number() {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

     /*----------  CARI BARANG  ----------*/

     public function product() {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get("ipartner", TRUE),$this->input->get("ireferensi", TRUE));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id_product_wip,
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

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'number'     => "SJ-".date('ym')."-123456"
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);    
        $idocument  = $this->input->post('idocument', TRUE);  
        $ddocument  = $this->input->post('ddocument',TRUE);
        if($ddocument){
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dateretur = $year.'-'.$month.'-'.$day;
        }
        $idpartner  = $this->input->post('itujuan', TRUE);
        $ireff      = $this->input->post('ireferensi', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        if($ibagian != ''  && $idocument != '' && $ddocument != '' && $idpartner != ''){
            $cekdata     = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin(); 
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idocument, $dateretur, $ibagian, $idpartner, $ireff, $eremark);

                //INSERT DETAIL
                for ($x=0; $x <= $jml ; $x++) { 
                    $idproduct = $this->input->post('idproduct'.$x, TRUE);
                    $nquantitywip = str_replace(",",".",$this->input->post('nquantity'.$x, TRUE));
                    $i  = 0;
                    if ($idproduct != "" || $idproduct != NULL) {
                        foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                            if ($idproduct == $idproductwip) {
                                $idmaterial     = $this->input->post("idmaterial[]", TRUE)[$i];
                                $nquantitymat   = str_replace(",",".",$this->input->post("nquantity[]", TRUE)[$i]);
                                $eremark        = $this->input->post("edesc[]", TRUE)[$i];
                                if($nquantitymat <> 0 && $nquantitywip <> 0){
                                    $this->mmaster->insertdetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat,$eremark);                                   
                                }
                            }   
                            $i++;
                        }
                    }
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                        );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$idocument),
                        'sukses' => true,
                        'kode'   => $idocument,
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

    public function view(){

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);
        
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result() 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $query = $this->mmaster->cek_datadetail($id);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'number'     => "SJ-".date('ym')."-123456",
            'data'       => $this->mmaster->cek_data($id)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result(),
            'jmlitem'    => $query->num_rows()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idpartner      = $this->input->post('itujuan', TRUE);
        $id             = $this->input->post('id', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $eremark        = $this->input->post('eremark', TRUE);
        $ireff          = $this->input->post('ireferensi', TRUE);
        $ireff        = $this->input->post('idreff', TRUE);
        if($ireff == '' || $ireff == null){
            $ireff = 0;
        }
        $jml            = $this->input->post('jml', TRUE);

        if ($idocument!='' && $ddocument!='' && $ibagian != '') {
            $cekdata     = $this->mmaster->cek_kode($idocumentold,$ibagian);
            if ($cekdata->num_rows()==0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $idpartner, $ireff, $eremark);
                $this->mmaster->deletedetail($id);

                for ($x=0; $x <= $jml ; $x++) { 
                    $idproduct = $this->input->post('idproduct'.$x, TRUE);
                    $nquantitywip = str_replace(",",".",$this->input->post('nquantity'.$x, TRUE));
                    $i  = 0;
                    if ($idproduct != "" || $idproduct != NULL) {
                        foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                            if ($idproduct == $idproductwip) {
                                $idmaterial   = $this->input->post("idmaterial[]", TRUE)[$i];
                                $nquantitymat = str_replace(",",".",$this->input->post("nquantity[]", TRUE)[$i]);
                                $eremark      = $this->input->post("edesc[]", TRUE)[$i];
                                if($nquantitymat <> 0 && $nquantitywip <> 0){
                                    $this->mmaster->insertdetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat,$eremark);
                                }
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
        $this->load->view('pesan', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'data'       => $this->mmaster->cek_data($id)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function changestatus(){

        //$data = check_role($this->i_menu, 3);
       // if (!$data) {
         //   redirect(base_url(), 'refresh');
        //}

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
    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iretur = $this->input->post('i_dn', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iretur);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Retur Pembelian Bahan Baku' . $iretur);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */