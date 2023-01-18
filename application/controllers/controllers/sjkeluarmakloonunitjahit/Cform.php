<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090402';

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
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  DAFTAR SJ MAKLOON  ----------*/
    
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
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    /*----------  MASUK FORM TAMBAH DATA  ----------*/    
    
    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
            'number'        => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    /*----------  CEK NO DOK  ----------*/
    
    public function cekkode()
    {
        if ($this->input->post('ibagian',TRUE)!='') {
            $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
            if ($data->num_rows()>0) {
                echo json_encode(1);
            }else{
                echo json_encode(0);
            }
        }else{
            echo json_encode(0);   
        }
    }

    /*----------  GET NO DOK  ----------*/    

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '' && $this->input->post('ibagian', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CARI SUPPLIER  ----------*/

    public function partner()
    {
        $filter = [];
        $data = $this->mmaster->partner(str_replace("'","",$this->input->get('q')),$this->i_menu, $this->input->get('itype'));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_supplier_name
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }  

    /*----------  CARI BARANG  ----------*/

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('partner'),$this->input->get('ddocument'),$this->input->get('idtype'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id_product,
                        'text' => $row->i_product . ' - ' . $row->e_product . ' - ' .$row->e_color_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data, Barang di Harga Makloon Belum diinput"
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

    public function detailproduct()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detailproduct($this->input->post('id',TRUE),$this->input->post('partner',TRUE),$this->input->post('ddocument',TRUE),$this->input->post('idtype',TRUE))->result_array()
        );
        echo json_encode($query);  
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $dback      = $this->input->post('dback', TRUE);
        if ($dback != '') {
            $dback  = date('Y-m-d', strtotime($dback));
        }
        $iforecast  = $this->input->post('iforecast', TRUE);
        $dforecast  = $this->input->post('dforecast', TRUE);
        if ($dforecast != '') {
            $dforecast = date('Y-m-d', strtotime($dforecast));
        }
        $itype      = $this->input->post('itype', TRUE);
        $ipartner   = $this->input->post('ipartner', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $ipartner != '' && $jml>0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id,$idocument,$ddocument,$ibagian,$itype,$ipartner,$dback,$iforecast,$dforecast,$eremarkh);
            for ($x = 0; $x <= $jml ; $x++) { 
                $idproduct    = $this->input->post('idproduct'.$x, TRUE);
                $nquantitywip = str_replace(",","",$this->input->post('nquantitywip'.$x, TRUE));
                if ($idproduct != "" || $idproduct != NULL) {
                    $i = 0;
                    foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                        if ($idproduct == $idproductwip) {
                            $idmaterial     = $this->input->post("idmaterial[]", TRUE)[$i];
                            $vharga         = str_replace(",","",$this->input->post("vharga[]", TRUE)[$i]);
                            $nquantity      = str_replace(",","",$this->input->post("nquantity[]", TRUE)[$i]);
                            $eremark        = $this->input->post("eremark[]", TRUE)[$i];
                            if (($idproductwip!=null || $idproductwip!='' || $idproductwip!=0) && ($idmaterial!=null || $idmaterial!='' || $idmaterial!=0)) {
                                $this->mmaster->simpandetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantity,$eremark,$vharga);
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
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
            'number'        => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->input->post('id', TRUE);
        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $dback      = $this->input->post('dback', TRUE);
        if ($dback != '') {
            $dback  = date('Y-m-d', strtotime($dback));
        }
        $iforecast  = $this->input->post('iforecast', TRUE);
        $dforecast  = $this->input->post('dforecast', TRUE);
        if ($dforecast != '') {
            $dforecast = date('Y-m-d', strtotime($dforecast));
        }
        $ipartner   = $this->input->post('ipartner', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian != '' && $ipartner != '' && $jml>0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$ddocument,$ibagian,$ipartner,$dback,$iforecast,$dforecast,$eremarkh);
            $this->mmaster->delete($id);
            for ($x = 0; $x <= $jml ; $x++) { 
                $idproduct    = $this->input->post('idproduct'.$x, TRUE);
                $nquantitywip = str_replace(",","",$this->input->post('nquantitywip'.$x, TRUE));
                if ($idproduct != "" || $idproduct != NULL) {
                    $i = 0;
                    foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                        if ($idproduct == $idproductwip) {
                            $idmaterial     = $this->input->post("idmaterial[]", TRUE)[$i];
                            $vharga         = str_replace(",","",$this->input->post("vharga[]", TRUE)[$i]);
                            $nquantity      = str_replace(",","",$this->input->post("nquantity[]", TRUE)[$i]);
                            $eremark        = $this->input->post("eremark[]", TRUE)[$i];
                            if (($idproductwip!=null || $idproductwip!='') && ($idmaterial!=null || $idmaterial!='')) {
                                $this->mmaster->simpandetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantity,$eremark,$vharga);
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
        }else{
            $data = array(
                'sukses' => false,
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU VIEW  ----------*/
    
    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "VIEW ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/
    
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
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {

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

}
/* End of file Cform.php */