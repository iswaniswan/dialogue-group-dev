<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050204';

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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {
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

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id,$istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

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
            'gudang'        => $this->mmaster->gudang(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "BBK-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function dataspbb()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->dataspbb(str_replace("'", "", $this->input->get('q')),$this->input->get('ibagian'));
            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->i_spbb
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data."
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Bagian tidak boleh kosong!"
            );
        }
        echo json_encode($filter);
    }

    public function dataspbbdetail()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'head'   => $this->mmaster->getspbb($this->input->post('ispbb',TRUE))->row(),
            'detail' => $this->mmaster->getspbb_detail($this->input->post('ispbb',TRUE))->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ispbb        = $this->input->post('ispbb', TRUE);
        $dspbb        = $this->input->post('dspbb', TRUE); 
        $eremark      = $this->input->post('eremark', TRUE);
        $itujuan      = $this->input->post('itujuan', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($ibagian!='' && $itujuan!='' && $ispbb!='' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$itujuan,$eremark,$ispbb);
            $lastname = '';
            for($i=0;$i<$jml;$i++){
                $iproduct         = $this->input->post('idproduct'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                if ($lastname != $iproduct) {
                    $this->mmaster->insertbonkdetail($id,$iproduct,$nquantity,$ispbb);
                }
                $imaterial        = $this->input->post('idmaterial'.$i, TRUE);
                $npanjangkain     = $this->input->post('npanjangkain'.$i, TRUE);
                $npemenuhan       = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertbonkdetailitem($id,$iproduct,$imaterial,$npemenuhan,$npanjangkain,$ispbb,$eremark);
                $lastname = $iproduct;
            } 

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan2', $data);      
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'number'     => "BBK-".date('ym')."-123456",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        

        $id            = $this->input->post('id', TRUE);
        $idocumentold  = $this->input->post('idocumentold', TRUE);
        $idocument     = $this->input->post('idocument', TRUE);
        $ddocument     = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $ispbb        = $this->input->post('ispbb', TRUE);
        $dspbb        = $this->input->post('dspbb', TRUE); 
        $eremark      = $this->input->post('eremark', TRUE);
        $itujuan      = $this->input->post('itujuan', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($id!='' && $idocumentold!='' && $ibagian!='' && $itujuan!='' && $ispbb!='' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$itujuan,$eremark,$ispbb);
            $this->mmaster->deletedetail($id);
            $lastname = '';
            for($i=0;$i<$jml;$i++){
                $iproduct         = $this->input->post('idproduct'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                if ($lastname != $iproduct) {
                    $this->mmaster->insertbonkdetail($id,$iproduct,$nquantity,$ispbb);
                }
                $imaterial        = $this->input->post('idmaterial'.$i, TRUE);
                $npanjangkain     = $this->input->post('npanjangkain'.$i, TRUE);
                $npemenuhan       = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertbonkdetailitem($id,$iproduct,$imaterial,$npemenuhan,$npanjangkain,$ispbb,$eremark);
                $lastname = $iproduct;
            } 

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Update Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan2', $data);      
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function ceksisa()
    {
        header("Content-Type: application/json", true);
        $data = array(
            'qty' => $this->mmaster->cek_sisa($this->input->post('idspbb',TRUE),$this->input->post('idmaterial',TRUE),$this->input->post('idproductwip',TRUE)), 
            'z'   => $this->input->post('i',TRUE)
        );
        echo json_encode($data);
    }
}

/* End of file Cform.php */