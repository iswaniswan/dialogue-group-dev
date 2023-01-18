<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20702';

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

    public function area(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->area($cari);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->e_area,
            );
        }          
        echo json_encode($filter);
    }

    public function customer(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $iarea  = $this->input->get('iarea', TRUE);
        $data   = $this->mmaster->customer($cari, $iarea);
        foreach($data->result() as  $key){
                $filter[] = array(
                'id'   => $key->id,  
                'text' => $key->e_customer_name,
            );
        }          
        echo json_encode($filter);
    }

    public function sales(){
        $filter     = [];
        $cari       = strtoupper($this->input->get('q'));
        $iarea      = $this->input->get('iarea', TRUE);
        $icustomer  = $this->input->get('icustomer', TRUE);
        $ddocument  = date('Ym', strtotime($this->input->get('ddocument', TRUE)));
        $data       = $this->mmaster->sales($cari, $iarea, $icustomer, $ddocument);
        if($data->num_rows() > 0){
            foreach($data->result() as  $key){
                    $filter[] = array(
                    'id'   => $key->id_salesman,  
                    'text' => $key->e_sales,
                );
            }
        }else{   
            $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data, Ubah Periode Customer Persalesman",
                );
        }       
        echo json_encode($filter);
    }

    public function getdiskon(){
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getdiskon($this->input->post('icustomer'));

        echo json_encode($data->result_array());
    }

    public function kelompok(){
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->kelompok($this->input->get('q'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Kategori',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,  
                    'text' => ucwords(strtolower($key->e_nama_kelompok)),
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function jenis(){
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->jenis($this->input->get('q'),$this->input->get('ikategori'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Jenis',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_type_code,  
                    'text' => ucwords(strtolower($key->e_type_name)),
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function product(){
        $filter = [];
        $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ijenis'),$this->input->get('ibagian'),$this->input->get('kodeharga'));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->id,
                'name' => $row->e_product_basename,
                'text' => $row->i_product_base.' - '.$row->e_product_basename,
            );
        }
        echo json_encode($filter);
    }

    public function getproduct(){
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('eproduct'),$this->input->post('tgl'), $this->input->post('kodeharga'));

        echo json_encode($data->result_array());
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
            'number'     => "SPBDS-".date('ym')."-000001",
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
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

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $dsend  = $this->input->post('dsend', TRUE);
        if ($dsend != '') {
            $dsend = date('Y-m-d', strtotime($dsend));
        }
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $isales         = $this->input->post('isales', TRUE);
        $ireferensiop   = $this->input->post('ireferensiop', TRUE);
        $nkotor         = $this->input->post('nkotor', TRUE);
        $ndiskontotal   = $this->input->post('ndiskontotal', TRUE);
        $vdpp           = $this->input->post('vdpp', TRUE);
        $vppn           = $this->input->post('vppn', TRUE);
        $nbersih        = $this->input->post('nbersih', TRUE);
        $kodeharga      = $this->input->post('kodeharga', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $id_product      = $this->input->post('idproduct[]', TRUE);
            $n_quantity      = str_replace(",","",$this->input->post('nquantity[]', TRUE));
            $v_harga         = str_replace(",","",$this->input->post('vharga[]', TRUE));
            $n_diskon        = str_replace(",","",$this->input->post('ndiskon[]', TRUE));
            $n_diskonn       = str_replace(",","",$this->input->post('ndiskonn[]', TRUE));
            $n_diskonnn      = str_replace(",","",$this->input->post('ndiskonnn[]', TRUE));
            $add_diskon      = str_replace(",","",$this->input->post('adddiskon[]', TRUE));
            $v_diskon        = str_replace(",","",$this->input->post('vdiskon[]', TRUE));
            $v_diskonn       = str_replace(",","",$this->input->post('vdiskonn[]', TRUE));
            $v_diskonnn      = str_replace(",","",$this->input->post('vdiskonnn[]', TRUE));
            $v_total         = str_replace(",","",$this->input->post('vtotal[]', TRUE));
            $v_totaldiskon   = str_replace(",","",$this->input->post('vtotaldiskon[]', TRUE));
            $e_remark        = $this->input->post('eremark[]', TRUE);

            $cekkode = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $dsend, $iarea, $icustomer, $isales, $ireferensiop, $nkotor, $ndiskontotal, $vdpp, $vppn, $nbersih, $eremarkh, $kodeharga);

                // var_dump($id,$idocument,$ddocument,$ibagian,$eremarkh);
                // die();
                $no = 0;
                foreach ($id_product as $idproduct) {
                    $idproduct  = $idproduct;
                    $nquantity  = $n_quantity[$no];
                    $vharga     = $v_harga[$no];
                    $ndiskon1   = $n_diskon[$no];
                    $ndiskon2   = $n_diskonn[$no];
                    $ndiskon3   = $n_diskonnn[$no];
                    $adddiskon  = $add_diskon[$no];
                    $vdiskon1   = $v_diskon[$no];
                    $vdiskon2   = $v_diskonn[$no];
                    $vdiskon3   = $v_diskonnn[$no];
                    $vtotal     = $v_total[$no];
                    $vtotaldis  = $v_totaldiskon[$no];
                    $eremark    = $e_remark[$no];
                    if (($idproduct != '' || $idproduct != null)) {
                        $this->mmaster->insertdetail($id, $idproduct, $nquantity, $vharga, $ndiskon1, $ndiskon2, $ndiskon3, $adddiskon, $vdiskon1, $vdiskon2, $vdiskon3, $vtotal, $vtotaldis, $eremark);
                    }
                    $no++;
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
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

       /*----------  MEMBUKA MENU EDIT  ----------*/
    
    public function edit(){

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
            'data'          => $this->mmaster->get_dataheader($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->get_datadetail($this->uri->segment(4))->result(),
            'number'        => "SPBDS-".date('ym')."-123456",
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
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $dsend  = $this->input->post('dsend', TRUE);
        if ($dsend != '') {
            $dsend = date('Y-m-d', strtotime($dsend));
        }
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $isales         = $this->input->post('isales', TRUE);
        $ireferensiop   = $this->input->post('ireferensiop', TRUE);
        $nkotor         = $this->input->post('nkotor', TRUE);
        $ndiskontotal   = $this->input->post('ndiskontotal', TRUE);
        $vdpp           = $this->input->post('vdpp', TRUE);
        $vppn           = $this->input->post('vppn', TRUE);
        $nbersih        = $this->input->post('nbersih', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $kodeharga      = $this->input->post('kodeharga', TRUE);
        $jml            = $this->input->post('jml', TRUE);
       
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $jml>0) {
            $id_product      = $this->input->post('idproduct[]', TRUE);
            $n_quantity      = str_replace(",","",$this->input->post('nquantity[]', TRUE));
            $v_harga         = str_replace(",","",$this->input->post('vharga[]', TRUE));
            $n_diskon        = str_replace(",","",$this->input->post('ndiskon[]', TRUE));
            $n_diskonn       = str_replace(",","",$this->input->post('ndiskonn[]', TRUE));
            $n_diskonnn      = str_replace(",","",$this->input->post('ndiskonnn[]', TRUE));
            $add_diskon      = str_replace(",","",$this->input->post('adddiskon[]', TRUE));
            $v_diskon        = str_replace(",","",$this->input->post('vdiskon[]', TRUE));
            $v_diskonn       = str_replace(",","",$this->input->post('vdiskonn[]', TRUE));
            $v_diskonnn      = str_replace(",","",$this->input->post('vdiskonnn[]', TRUE));
            $v_total         = str_replace(",","",$this->input->post('vtotal[]', TRUE));
            $v_totaldiskon   = str_replace(",","",$this->input->post('vtotaldiskon[]', TRUE));
            $e_remark        = $this->input->post('eremark[]', TRUE);
            $cekdata     = $this->mmaster->cek_kode($idocumentold,$ibagian);
            if ($cekdata->num_rows()==0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $dsend, $iarea, $icustomer, $isales, $ireferensiop, $nkotor, $ndiskontotal, $vdpp, $vppn, $nbersih, $eremarkh, $kodeharga);
                $this->mmaster->deletedetail($id);

                $no = 0;
                foreach ($id_product as $idproduct) {
                    $idproduct  = $idproduct;
                    $nquantity  = $n_quantity[$no];
                    $vharga     = $v_harga[$no];
                    $ndiskon1   = $n_diskon[$no];
                    $ndiskon2   = $n_diskonn[$no];
                    $ndiskon3   = $n_diskonnn[$no];
                    $adddiskon  = $add_diskon[$no];
                    $vdiskon1   = $v_diskon[$no];
                    $vdiskon2   = $v_diskonn[$no];
                    $vdiskon3   = $v_diskonnn[$no];
                    $vtotal     = $v_total[$no];
                    $vtotaldis  = $v_totaldiskon[$no];
                    $eremark    = $e_remark[$no];
                    
                    $this->mmaster->insertdetail($id, $idproduct, $nquantity, $vharga, $ndiskon1, $ndiskon2, $ndiskon3, $adddiskon, $vdiskon1, $vdiskon2, $vdiskon3, $vtotal, $vtotaldis, $eremark);
                    
                    $no++;
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

     /*----------  MEMBUKA MENU Approve  ----------*/
    
    public function approval(){

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
            'data'          => $this->mmaster->get_dataheader($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->get_datadetail($this->uri->segment(4))->result(),
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
            'data'          => $this->mmaster->get_dataheader($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->get_datadetail($this->uri->segment(4))->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */