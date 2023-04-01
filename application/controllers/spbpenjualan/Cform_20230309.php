<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20718';
    public $i_menu_konversi = '20707';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->departement  = $this->session->i_departement;
        $this->company      = $this->session->id_company;
        $this->id_company   = $this->session->id_company;
        $this->level        = $this->session->i_level;
        $this->username     = $this->session->username;

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    /*----------  DEFAULT CONTROLLERS  ----------*/
    
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
            'dfrom'     => formatdmY($dfrom),
            'dto'       => formatdmY($dto),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  DAFTAR DATA SPB  ----------*/
    
    public function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'area'       => $this->mmaster->area()->result(),
            // 'salesman'   => $this->mmaster->sales()->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'number'     => "SPB-".date('ym')."-123456",
            'jenisbarang'=> $this->db->query("SELECT * FROM tr_jenis_barang_keluar WHERE id IN (1,3)"),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number() 
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE),$this->input->post('idcustomer', TRUE));
        }
        echo json_encode($number);
    }
    
    /*----------  GET PPN  ----------*/

    public function get_ppn() 
    {
        $ppn = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $ppn = $this->mmaster->get_ppn(formatYmd($this->input->post('tgl', TRUE)));
        }
        echo json_encode($ppn);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode() {
        if ($this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE))->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }    
    
    /*----------  CARI PELANGGAN  ----------*/
    
    public function customer()
    {
        $filter = [];
        $iarea  = $this->input->get('iarea');
        $data   = $this->mmaster->customer(str_replace("'", "", $this->input->get('q')),$iarea);
        // if($iarea!=''){
            if ($data->num_rows()>0) {
                foreach($data->result() as  $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->e_customer_name." (".$key->i_customer.") "
                    );
                }          
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => 'Pelanggan Tidak Ada',
                );
            }
        // }else{
        //     $filter[] = array(
        //         'id'   => null,  
        //         'text' => 'Pilih Area',
        //     );
        // }
        echo json_encode($filter);
    }


    /*----------  CARI SALESMAN  ----------*/
    
    public function salesman()
    {
        $filter = [];
        $icustomer  = $this->input->get('icustomer');
        if($icustomer!=''){
            $data   = $this->mmaster->salesman(str_replace("'", "", $this->input->get('q')),$icustomer);
            if ($data->num_rows()>0) {
                foreach($data->result() as  $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->e_sales." (".$key->i_sales.") "
                    );
                }          
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => 'Salesman by customer belum ada',
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Pilih customer terlebih dahulu',
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL PELANGGAN  ----------*/
    
    public function getdetailcustomer()
    {
        header("Content-Type: application/json", true);
        if ($this->input->post('idcustomer',TRUE)!='') {
            echo json_encode($this->mmaster->getdetailcustomer($this->input->post('idcustomer'))->result_array());
        }else{
            echo json_encode(0);
        }
    }

    /*----------  GET KELOMPOK BARANG  ----------*/

    public function kelompok()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->kelompok(str_replace("'", "", $this->input->get('q')),$this->input->get('ibagian'));
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
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Tidak Boleh Kosong!',
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET JENIS BARANG  ----------*/

    public function jenis()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->jenis(str_replace("'", "", $this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ibagian'));
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
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Tidak Boleh Kosong!',
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET BARANG JADI  ----------*/    

    public function product()
    {
        $filter = [];
        $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ijenis'),$this->input->get('ibagian'),$this->input->get('idharga'),$this->input->get('id_jenis_barang_keluar'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_product_base.' - '.$row->e_product_basename,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => 'Tidak Ada Data Barang',
            );   
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/    

    public function getproduct()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->getproduct($this->input->post('idproduct'),$this->input->post('idharga'),$this->input->post('ddocument'),$this->input->post('idcustomer'),$this->input->post('id_jenis_barang_keluar'))->result_array());
    }

    /*----------  SIMPAN DATA  ----------*/    

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idcustomer     = $this->input->post('icustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomer', TRUE);
        $idarea         = $this->input->post('iarea', TRUE);
        $idsales        = $this->input->post('isales', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $id_jenis_barang_keluar     = $this->input->post('id_jenis_barang_keluar', TRUE);
        $nppn     = $this->input->post('nppn', TRUE);
        $etypespb       = $this->input->post('etypespb', TRUE);
        $vdiskon        = str_replace(",","",$this->input->post('ndiskontotal', TRUE));
        $vkotor         = str_replace(",","",$this->input->post('nkotor', TRUE));
        $vppn           = str_replace(",","",$this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",","",$this->input->post('nbersih', TRUE));
        $idharga        = $this->input->post('idkodeharga', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $vdpp           = str_replace(",","",$this->input->post('vdpp', TRUE));
        $jml            = $this->input->post('jml', TRUE);

        $f_spb_stockdaerah            = $this->input->post('f_spb_stockdaerah', TRUE);

        if ($f_spb_stockdaerah == "on") {
            $f_spb_stockdaerah = 't';
        } else {
            $f_spb_stockdaerah = 'f';
        }

        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
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
                $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp,$idharga,$etypespb,$id_jenis_barang_keluar,$nppn, '', $f_spb_stockdaerah);
                for ($i = 0; $i < $jml; $i++) {
                    $idproduct    = $this->input->post('idproduct'.$i, TRUE);
                    $nquantity    = str_replace(",","",$this->input->post('nquantity'.$i, TRUE));
                    $vprice       = str_replace(",","",$this->input->post('vharga'.$i, TRUE));
                    $ndiskon1     = str_replace(",","",$this->input->post('ndisc1'.$i, TRUE));
                    $ndiskon2     = str_replace(",","",$this->input->post('ndisc2'.$i, TRUE));
                    $ndiskon3     = str_replace(",","",$this->input->post('ndisc3'.$i, TRUE));
                    $vdiskon1     = str_replace(",","",$this->input->post('vdisc1'.$i, TRUE));
                    $vdiskon2     = str_replace(",","",$this->input->post('vdisc2'.$i, TRUE));
                    $vdiskon3     = str_replace(",","",$this->input->post('vdisc3'.$i, TRUE));
                    $vdiskonplus  = str_replace(",","",$this->input->post('vdiscount'.$i, TRUE));
                    $vtotal       = str_replace(",","",$this->input->post('vtotal'.$i, TRUE));
                    $vtotaldiskon = str_replace(",","",$this->input->post('vtotaldiskon'.$i, TRUE));
                    $eremark      = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($nquantity > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetail($id,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
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
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'area'       => $this->mmaster->area()->result(),
            'salesman'   => $this->mmaster->sales()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4), $this->uri->segment(7)),
            'number'     => "SPB-".date('ym')."-123456",
            'jenisbarang'=> $this->db->query("SELECT * FROM tr_jenis_barang_keluar WHERE id IN (1,3)"),
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
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ibagianold     = $this->input->post('ibagianold', TRUE);
        $idcustomer     = $this->input->post('icustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomer', TRUE);
        $idarea         = $this->input->post('iarea', TRUE);
        $idsales        = $this->input->post('isales', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $id_jenis_barang_keluar     = $this->input->post('id_jenis_barang_keluar', TRUE);
        $nppn     = $this->input->post('nppn', TRUE);
        $vdiskon        = str_replace(",","",$this->input->post('ndiskontotal', TRUE));
        $vkotor         = str_replace(",","",$this->input->post('nkotor', TRUE));
        $vppn           = str_replace(",","",$this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",","",$this->input->post('nbersih', TRUE));
        $idharga        = $this->input->post('idkodeharga', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $vdpp           = str_replace(",","",$this->input->post('vdpp', TRUE));
        $jml            = $this->input->post('jml', TRUE);

        $f_spb_stockdaerah            = $this->input->post('f_spb_stockdaerah', TRUE);

        if ($f_spb_stockdaerah == "on") {
            $f_spb_stockdaerah = 't';
        } else {
            $f_spb_stockdaerah = 'f';
        }

        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp,$idharga,$id_jenis_barang_keluar,$nppn, $f_spb_stockdaerah);
                $this->mmaster->delete($id);
                for ($i = 1; $i <= $jml; $i++) {
                    $idproduct    = $this->input->post('idproduct'.$i, TRUE);
                    $nquantity    = str_replace(",","",$this->input->post('nquantity'.$i, TRUE));
                    $vprice       = str_replace(",","",$this->input->post('vharga'.$i, TRUE));
                    $ndiskon1     = str_replace(",","",$this->input->post('ndisc1'.$i, TRUE));
                    $ndiskon2     = str_replace(",","",$this->input->post('ndisc2'.$i, TRUE));
                    $ndiskon3     = str_replace(",","",$this->input->post('ndisc3'.$i, TRUE));
                    $vdiskon1     = str_replace(",","",$this->input->post('vdisc1'.$i, TRUE));
                    $vdiskon2     = str_replace(",","",$this->input->post('vdisc2'.$i, TRUE));
                    $vdiskon3     = str_replace(",","",$this->input->post('vdisc3'.$i, TRUE));
                    $vdiskonplus  = str_replace(",","",$this->input->post('vdiscount'.$i, TRUE));
                    $vtotal       = str_replace(",","",$this->input->post('vtotal'.$i, TRUE));
                    $vtotaldiskon = str_replace(",","",$this->input->post('vtotaldiskon'.$i, TRUE));
                    $eremark      = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($nquantity > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetail($id,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
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
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

     /*----------  MEMBUKA MENU APPROVE  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/
    
    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4), $this->uri->segment(7))->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/    

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

    /*----------  REDIRECT KE FORM TRANSFER  ----------*/

    public function transfer()
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

        $customer = $this->input->post('idcustomer');
        if($customer== ''){
            $customer  = $this->uri->segment(6);
            if($customer== ''){
                $customer = 'SD';
            }
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
            'idcustomer' => $customer,
            'customer'   => $this->mmaster->customertransfer()->result(),
        );

        $this->Logger->write('Membuka Menu List Transfer '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlistop', $data);
    }

    /*----------  DAFTAR DATA TRANSFER OP  ----------*/
    
    public function datatransfer()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        $icustomer = $this->input->post('idcustomer', TRUE);
        if ($icustomer == '') {
            $icustomer = $this->uri->segment(6);
        }

        echo $this->mmaster->datatransfer($this->global['folder'],$this->i_menu,$dfrom,$dto,$icustomer);
    }

    /*----------  PROSES SIMPAN DATA  ----------*/

    public function prosesdata() 
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom    = $this->input->post('dfrom', true);
        $dto      = $this->input->post('dto', true);

        $customer = [];
        $op       = [];
        if ($this->input->post('jml', true) > 0) {
            for ($i=1; $i <= $this->input->post('jml', true); $i++) { 
                $check      = $this->input->post('chk'.$i, true);
                $iop        = $this->input->post('iop'.$i, true);
                $idcustomer = $this->input->post('idcustomer'.$i, true);
                if ($check=='on') {
                    array_push($customer,$idcustomer);
                    array_push($op,$iop);
                }
            }
        }
        $customer   = array_unique($customer);
        $op         = array_unique($op);
        $idcustomer = implode(",", $customer);
        $op         = "'".implode("','", $op)."'";

        if (count($customer) == 1) {
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Transfer ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data'       => $this->mmaster->dataheader($idcustomer)->row(), 
                'idcustomer' => $idcustomer,
                'datadetail' => $this->mmaster->datadetail($idcustomer,$op,$dfrom,$dto)->result(),
                'dataop'     => $op,
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'bagian'     => $this->mmaster->bagian()->result(),
            );
            $this->Logger->write('Membuka Menu Transfer Item '.$this->global['title']);
            $this->load->view($this->global['folder'].'/vformtransfer', $data);
        } else {
            echo '<script>
            swal({
                title: "Maaf :(",
                text: "Distributor Tidak Boleh Beda!",
                showConfirmButton: true,
                type: "error",
                },function(){
                    show("'.$this->global['folder'].'/cform/transfer/'.$dfrom.'/'.$dto.'","#main");
                    });
            </script>';
        }
    }


    /*----------  CHECK REFERENSI ----------*/    
    public function checkreferensi() {

            $dataop        = $this->input->post('dataop', TRUE);
            $idcustomer    = $this->input->post('idcustomer', TRUE);

            $and   = "AND i_referensi IN (" . $dataop . ")";
            
            $cek = $this->db->query("
                SELECT string_agg(i_referensi, ',') as i_referensi from tm_spb where id_customer = $idcustomer and id_company = $this->company and i_status in ('1','2','3', '6') $and
            ", FALSE);

            if ($cek->row()->i_referensi == null) {
                $data = array(
                    'sukses' => true,
                    'kode'   => ''
                );    
            } else {
                $data = array(
                    'sukses' => false,
                    'kode'   => $cek->row()->i_referensi
                );    
            }
            echo json_encode($data);
    }

    /*----------  SIMPAN DATA TRANSFER ----------*/    

    public function simpantransfer()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idcustomer     = $this->input->post('idcustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomer', TRUE);
        $etypespb       = $this->input->post('etypespb', TRUE);
        $ndiskon1       = str_replace(",","",$this->input->post('vdiscount1', TRUE));
        $ndiskon2       = str_replace(",","",$this->input->post('vdiscount2', TRUE));
        $ndiskon3       = str_replace(",","",$this->input->post('vdiscount3', TRUE));
        $idharga        = $this->input->post('idharga', TRUE);
        $idarea         = $this->db->query("select id_area  from tr_customer where id = $idcustomer")->row()->id_area;//$this->input->post('idarea', TRUE);
        
        $jml            = $this->input->post('jml', TRUE);
        $xdocument      = '';
        $idocument      = '';

        $nppn = $this->db->query("select n_tax from public.tr_tax_amount where f_active = true and current_date between d_start and d_finish", false)->row()->n_tax;
        if ($idharga!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
            $this->db->trans_begin();
            $group_ref = '';
            for ($i = 0; $i <= $jml; $i++) {
                $ireferensi = $this->input->post('iop'.$i, TRUE);
                //$idarea     = $this->input->post('idarea'.$i, TRUE);
                $i_area_distributor  = $this->input->post('i_area_distributor'.$i, TRUE);
                $idproduct  = $this->input->post('idproduct'.$i, TRUE);
                $nquantity  = str_replace(",","",$this->input->post('qty'.$i, TRUE));
                $vprice     = str_replace(",","",$this->input->post('harga'.$i, TRUE));
                $eremark    = $this->input->post('eremark'.$i, TRUE);
                $ceklis     = $this->input->post('ceklis'.$i, TRUE);
                if ($ceklis == "on" && $nquantity > 0 && $idproduct != "") {

                    // $cekspb = $this->mmaster->cekspb($ireferensi,$idcustomer);
                    // if ($cekspb->num_rows()==0) {
                    //     if ($group_ref == '') {
                    //         $id = $this->mmaster->runningid();
                    //         $idocument  = $this->mmaster->runningnumber(date('ym'),date('Y'),$ibagian);
                    //         $ddocument  = date('Y-m-d');
                    //     } else if ($group_ref != $ireferensi) {
                    //         $id = $this->mmaster->runningid();
                    //     } else {
                    //         $id = $this->mmaster->notrunningid();
                    //     }
                    //     $group_ref = $this->input->post('iop'.$i, TRUE);
                    // }
                    $cekspb = $this->mmaster->cekspb($ireferensi,$idcustomer);
                    if ($cekspb->num_rows()>0) {
                        $id = $this->mmaster->notrunningid();
                    }else{
                        $id = $this->mmaster->runningid();
                        $idocument  = $this->mmaster->runningnumber(date('ym'),date('Y'),$ibagian,$idcustomer);
                        $ddocument  = date('Y-m-d');
                        $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,1,$ireferensi,0,0,0,0,'',0,$idharga,'Transfer',1,$nppn, $i_area_distributor, 'f');
                    }
                    $jumlah   = $nquantity * $vprice;
                    $vdiskon1 = $jumlah * ($ndiskon1/100);
                    $vdiskon2 = ($jumlah - $vdiskon1) * ($ndiskon2/100);
                    $vdiskon3 = ($jumlah - $vdiskon1 - $vdiskon2) * ($ndiskon3/100);
                    $vdiskonplus  = 0;
                    $vtotaldiskon = $vdiskon1 + $vdiskon2 + $vdiskon3 + $vdiskonplus;
                    $vtotal   = $jumlah - $vtotaldiskon;
                    $this->mmaster->insertdetail($id,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
                    if ($cekspb->num_rows() <= 0) {
                        $xdocument .= $idocument." - ";
                    }
                    $this->mmaster->update_spb_new_word($id);
                    
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => substr($xdocument,0,-3),
                    'id'     => $id
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => substr($xdocument,0,-3),
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data Transfer ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $xdocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  REDIRECT KE FORM TAMBAH KONVERSI ----------*/

    public function konversi()
    {

        $data = check_role($this->i_menu_konversi, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title']." Dari Fc",
            'title_list' => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'area'       => $this->mmaster->area()->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'number'     => "SPB-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformkonversi', $data);
    }

    /*----------  GET DETAIL CUSTOMER & REFERENSI  ----------*/
    
    public function getdetailref()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'head'   => $this->mmaster->getdetailcustomer($this->input->post('idcustomer'))->row(),
            'detail' => $this->mmaster->getdetailforecast($this->input->post('idcustomer'),$this->input->post('ireferensi'))->result_array(),
        );
        echo json_encode($query);  
    }

    /*----------  REDIRECT KE FORM CETAK  ----------*/
    
    public function cetak()
    {

        $data = check_role($this->i_menu, 5);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Cetak ".$this->global['title'],
            'id'         => $this->uri->segment(4),
            'bagian'     => $this->mmaster->bagian()->result(),
            'area'       => $this->mmaster->area()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4)),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4), $this->uri->segment(7)),
            'history'    => $this->mmaster->history($this->uri->segment(5),$this->uri->segment(6))->row(),
            'dhistory'   => $this->uri->segment(5),
            'piutang'    => $this->mmaster->piutang($this->uri->segment(6)),
        );

        $this->Logger->write('Membuka Menu Cetak '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }

    /*----------  UPDATE STATUS PRINT  ----------*/
    
    public function updateprint(){

        $id = $this->input->post('id', true);
        $this->db->trans_begin();
        $this->mmaster->updateprint($id);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo 'fail';
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Print ' . $this->global['folder'] . ' Id : ' . $id);
            echo $id;
        }
    }
    
    /*----------  GET FORECAST  ----------*/
    public function getforecast()
    {
        $filter = [];
        $idcustomer  = $this->input->get('idcustomer');
        $data   = $this->mmaster->getforecast(str_replace("'", "", $this->input->get('q')),$idcustomer);
        if($idcustomer!=''){
            if ($data->num_rows()>0) {
                foreach($data->result() as  $key){
                    $filter[] = array(
                        'id'   => $key->periode,  
                        'text' => $key->bulan. " ".$key->tahun 
                    );
                }          
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => 'Data Tidak Ada',
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Pilih Pelanggan',
            );
        }
        echo json_encode($filter);
    }
}
/* End of file Cform.php */