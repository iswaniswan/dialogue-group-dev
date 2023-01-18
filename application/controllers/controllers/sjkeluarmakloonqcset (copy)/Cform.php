<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090207';

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
        if ($this->input->post('itype',TRUE)!='') {
            $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE),$this->input->post('itype',TRUE));
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
        if ($this->input->post('tgl', TRUE) != '' && $this->input->post('itype', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE),$this->input->post('itype', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CARI SUPPLIER  ----------*/

    public function partner()
    {
        $filter = [];
        if ($this->input->get('itype') != '') {
            $data = $this->mmaster->partner(str_replace("'","",$this->input->get('q')),$this->input->get('itype'));
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
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Type Makloon Harus Dipilih!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL SUPPLIER  ----------*/

    public function partnerdetail()
    {
        header("Content-Type: application/json", true);   
        echo json_encode($this->mmaster->partnerdetail($this->input->post('id'))->result_array());  
    }    

    /*----------  CARI BARANG  ----------*/

    public function product()
    {
        $filter = [];
        if ($this->input->get('itype') != '' && $this->input->get('ipartner') != '' && $this->input->get('ddocument') != '') {
            $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('itype'),$this->input->get('ipartner'),$this->input->get('ddocument'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id_product,
                        'text' => $row->i_product . ' - ' . $row->e_product. ' - ' . $row->e_color_name
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
                'text' => "Type Makloon, Partner & Tgl Dok Tidak Boleh Kosong!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET HARGA  ----------*/

    public function price()
    {
        header("Content-Type: application/json", true);   
        echo json_encode($this->mmaster->price($this->input->post('id'),$this->input->post('itype'),$this->input->post('ipartner'),$this->input->post('ddocument'))->result_array());  
    }  

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $itype      = $this->input->post('itype', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $dperkiraan = $this->input->post('dperkiraan', TRUE);
        if ($dperkiraan != '') {
            $dperkiraan = date('Y-m-d', strtotime($dperkiraan));
        }
        $ipermintaan = $this->input->post('ipermintaan', TRUE);
        $dpermintaan = $this->input->post('dpermintaan', TRUE);
        if ($dpermintaan != '') {
            $dpermintaan = date('Y-m-d', strtotime($dpermintaan));
        }
        $ipartner   = $this->input->post('ipartner', TRUE);
        $fpkp       = $this->input->post('fpkp', TRUE);
        $ndiskon    = $this->input->post('ndiskon', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $itype != '' && $ipartner != '' && $jml>0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id,$idocument,$ddocument,$dperkiraan,$ibagian,$itype,$ipartner,$ipermintaan,$dpermintaan,$fpkp,$ndiskon,$eremarkh);
            for ($i = 1; $i <= $jml; $i++) {
                $idproduct = $this->input->post('idproduct' . $i, TRUE);
                $nquantity = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
                $vprice    = str_replace(",","",$this->input->post('vprice' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);
                if ($nquantity>0 && ($idproduct!=null || $idproduct!='')) {
                    $this->mmaster->simpandetail($id,$idproduct,$nquantity,$vprice,$eremark);
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
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
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
        $itype      = $this->input->post('itype', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $dperkiraan = $this->input->post('dperkiraan', TRUE);
        if ($dperkiraan != '') {
            $dperkiraan = date('Y-m-d', strtotime($dperkiraan));
        }
        $ipermintaan = $this->input->post('ipermintaan', TRUE);
        $dpermintaan = $this->input->post('dpermintaan', TRUE);
        if ($dpermintaan != '') {
            $dpermintaan = date('Y-m-d', strtotime($dpermintaan));
        }
        $ipartner   = $this->input->post('ipartner', TRUE);
        $fpkp       = $this->input->post('fpkp', TRUE);
        $ndiskon    = $this->input->post('ndiskon', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian != '' && $itype != '' && $ipartner != '' && $jml>0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$ddocument,$dperkiraan,$ibagian,$itype,$ipartner,$ipermintaan,$dpermintaan,$fpkp,$ndiskon,$eremarkh);
            $this->mmaster->delete($id);
            for ($i = 1; $i <= $jml; $i++) {
                $idproduct = $this->input->post('idproduct' . $i, TRUE);
                $nquantity = str_replace(",","",$this->input->post('nquantity' . $i, TRUE));
                $vprice    = str_replace(",","",$this->input->post('vprice' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);
                if ($nquantity>0 && ($idproduct!=null || $idproduct!='')) {
                    $this->mmaster->simpandetail($id,$idproduct,$nquantity,$vprice,$eremark);
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
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-".date('ym')."-123456",
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
            'type'          => $this->mmaster->typemakloon($this->i_menu)->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-".date('ym')."-123456",
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