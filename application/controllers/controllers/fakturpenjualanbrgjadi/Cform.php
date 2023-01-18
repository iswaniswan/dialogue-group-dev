<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20704';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_departement = $this->session->i_departement;
        $this->id_company    = $this->session->id_company;
        $this->i_level       = $this->session->i_level;
        $this->username      = $this->session->username;

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

        $iop = $this->uri->segment('4');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'], 
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
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
        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    public function awal(){
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

        $iop = $this->uri->segment('4');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'], 
            'title_list' => 'List '.$this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function awalnext(){

        echo $this->mmaster->awalnext();
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
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    function getipayment(){
        header("Content-Type: application/json", true);
        $isupplier = $this->input->post('isupplier');
            $this->db->select("a.i_jenis_pembelian, case a.i_jenis_pembelian when '0' then 'Cash' when '1' then 'Kredit' end as epayment
                    from duta_prod.tr_supplier a
                    where i_supplier='$isupplier'");
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function proses(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier      = $this->input->post('isupplier', TRUE); 
        $iop            = $this->input->post('id', TRUE); 
        $ibtb           = $this->input->post('ibtb', TRUE); 
        $isj            = $this->input->post('isj', TRUE); 
//var_dump($iop);
//var_dump($ibtb);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'datasup'    => $this->mmaster->cek_sup($isupplier)->row(), 
            'data'       => $this->mmaster->get_btbitem($isupplier)->row(),
            'data1'      => $this->mmaster->get_item2($isupplier, $iop, $ibtb)->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => $this->mmaster->runningnumber(date('ym')),
        );
        $this->Logger->write('Membuka Menu Input Item '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

     /*---------- Proses List Jadi Input  ----------*/
    public function proses2() {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $arr_customer = array();
        $arr_sj = array();
        
        if ($this->input->post('jml', true)>0) {
            for ($i=1; $i <= $this->input->post('jml', true); $i++) { 
                $check = $this->input->post('chk'.$i, true);
                $id    = $this->input->post('id'.$i, true);
                $id_customer   = $this->input->post('id_customer'.$i, true);
    
                if ($check=='on') {
                    array_push($arr_customer, $id_customer);
                    array_push($arr_sj, $id);
                }
            }
        }
        $arr_customer = array_unique($arr_customer);
        $arr_sj = array_unique($arr_sj);
        $id_sj =  "'".implode("', '", $arr_sj)."'"; //implode(",", $arr_sj);
        // $idbtb = implode(",", ."'".$idbtb."'");
        $id_customer = implode(",", $arr_customer);
        if (count($arr_customer) == 1) {
            $head = $this->mmaster->get_customer($id_sj, $id_customer)->row();
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Tambah ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'head'       => $this->mmaster->get_customer($id_sj, $id_customer)->row(),
                'detail'     => $this->mmaster->get_item2($id_sj)->result(),
                'dfrom'      => $this->input->post('dfrom', true),
                'dto'        => $this->input->post('dto', true),
                'bagian'     => $this->mmaster->bagian()->result(),
                'number'     => "FP-".date('ym')."-123456",
            );
            $this->Logger->write('Membuka Menu Input Item '.$this->global['title']);
            $this->load->view($this->global['folder'].'/vforminput', $data);
        } else {
            $data = array(
                'sukses' => false,
                'kode' => "Customer Tidak Boleh Beda"
            );
            $this->load->view('pesan2', $data);
        }     
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/
    public function cekkode() {
        if ($this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE))->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }    

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/
    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  SIMPAN DATA  ----------*/    
    public function simpan(){

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

        $id_customer     = $this->input->post('id_customer', TRUE);
        $e_customer_name = $this->input->post('e_customer_name', TRUE);
        $f_pkp           = $this->input->post('f_pkp', TRUE);
        $n_customer_toplength     = $this->input->post('n_customer_toplength', TRUE);
        $ipajak      = $this->input->post('ipajak', TRUE);
        $dpajak      = $this->input->post('dpajak', TRUE);
        if ($dpajak != '') {
            $dpajak  = date('Y-m-d', strtotime($dpajak));
        } else {
            $dpajak = null;
        }

        $dreceivefaktur      = $this->input->post('dreceivefaktur', TRUE);
        if ($dreceivefaktur != '') {
            $dreceivefaktur  = date('Y-m-d', strtotime($dreceivefaktur));
        } else {
            $dreceivefaktur = null;
        }

        $djatuhtempo      = $this->input->post('djatuhtempo', TRUE);
        if ($djatuhtempo != '') {
            $djatuhtempo  = date('Y-m-d', strtotime($djatuhtempo));
        }

        $eremark        = $this->input->post('eremark', TRUE);
        $vkotor         = str_replace(",","",$this->input->post('nkotor', TRUE));
        $vdiskon        = str_replace(",","",$this->input->post('ndiskontotal', TRUE));
        $vdpp           = str_replace(",","",$this->input->post('vdpp', TRUE));
        $vppn           = str_replace(",","",$this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",","",$this->input->post('nbersih', TRUE));
        $jml            = $this->input->post('jml', TRUE);

        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $id_customer!='' && $jml>0) {
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
                $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$id_customer,$e_customer_name,$f_pkp,$n_customer_toplength,$dreceivefaktur,$ipajak,$dpajak,$djatuhtempo,$eremark,$vkotor,$vdiskon,$vdpp,$vppn,$vbersih);
                for ($i = 1; $i <= $jml; $i++) {
                    $id_document  = $this->input->post('id_referensi_item'.$i, TRUE);
                    $id_product   = $this->input->post('id_product'.$i, TRUE);
                    $e_product    = $this->input->post('e_product'.$i, TRUE);
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
                    //if ($nquantity > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetail($id, $id_document, $id_product,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
                    //}
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],   
            'dfrom'      => $dfrom,
            'dto'        => $dto,   
            'bagian'     => $this->mmaster->bagian()->result(),
            'head'       => $this->mmaster->get_head_edit($id)->row(),
            'detail'     => $this->mmaster->get_item_edit($id)->result(),
            'number'     => "FP-".date('ym')."-123456",
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
        $ibagianold     = $this->input->post('ibagianold', TRUE);

        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);

        $id_customer    = $this->input->post('id_customer', TRUE);
        $e_customer_name  = $this->input->post('e_customer_name', TRUE);
        $f_pkp            = $this->input->post('f_pkp', TRUE);
        $n_customer_toplength     = $this->input->post('n_customer_toplength', TRUE);
        $ipajak      = $this->input->post('ipajak', TRUE);
        $dpajak      = $this->input->post('dpajak', TRUE);
        if ($dpajak != '') {
            $dpajak  = date('Y-m-d', strtotime($dpajak));
        } else {
            $dpajak = null;
        }

        $dreceivefaktur      = $this->input->post('dreceivefaktur', TRUE);
        if ($dreceivefaktur != '') {
            $dreceivefaktur  = date('Y-m-d', strtotime($dreceivefaktur));
        } else {
            $dreceivefaktur = null;
        }

        $djatuhtempo      = $this->input->post('djatuhtempo', TRUE);
        if ($djatuhtempo != '') {
            $djatuhtempo  = date('Y-m-d', strtotime($djatuhtempo));
        }

        $eremark        = $this->input->post('eremark', TRUE);
        $vkotor         = str_replace(",","",$this->input->post('nkotor', TRUE));
        $vdiskon        = str_replace(",","",$this->input->post('ndiskontotal', TRUE));
        $vdpp           = str_replace(",","",$this->input->post('vdpp', TRUE));
        $vppn           = str_replace(",","",$this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",","",$this->input->post('nbersih', TRUE));
        $jml            = $this->input->post('jml', TRUE);

        // var_dump($id, $idocument, $ddocument, $ibagian, $id_customer, $jml);
        // die();
        if ($id != '' && $idocument!='' && $ddocument!='' && $ibagian!='' && $id_customer!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$id_customer,$e_customer_name,$f_pkp,$n_customer_toplength,$dreceivefaktur,$ipajak,$dpajak,$djatuhtempo,$eremark,$vkotor,$vdiskon,$vdpp,$vppn,$vbersih);
                $this->mmaster->delete($id);
                for ($i = 1; $i <= $jml; $i++) {
                    $id_document  = $this->input->post('id_document_refferensi'.$i, TRUE);
                    $id_product   = $this->input->post('id_product'.$i, TRUE);
                    $e_product    = $this->input->post('e_product'.$i, TRUE);
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
                    //if ($nquantity > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetail($id, $id_document, $id_product,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
                    //}
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

    /*----------  UPDATE STATUS DOKUMEN  ----------*/    
    public function changestatus(){
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

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],   
            'dfrom'      => $dfrom,
            'dto'        => $dto,   
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'head'       => $this->mmaster->get_head_edit($id)->row(),
            'detail'     => $this->mmaster->get_item_edit($id)->result(),
            'number'     => "FP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],   
            'dfrom'      => $dfrom,
            'dto'        => $dto,   
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'head'       => $this->mmaster->get_head_edit($id)->row(),
            'detail'     => $this->mmaster->get_item_edit($id)->result(),
            'number'     => "FP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
}
/* End of file Cform.php */