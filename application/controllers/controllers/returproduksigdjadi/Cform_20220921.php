<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050118';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
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
            
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

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

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "RTK-".date('ym')."-000001",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }
    public function dataproduct(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->dataproduct($cari);
        if ($data->num_rows()>0) {
            foreach($data->result() as $product){       
                $filter[] = array(
                    'id'    => $product->id,
                    'name'  => $product->e_product_basename,
                    'text'  => $product->i_product_base.' - '.$product->e_product_basename.' - '.$product->e_color_name,
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

    public function getproduct(){
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('eproduct'));

        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            die;
            redirect(base_url(),'refresh');
        }
        
        $ibagian     = $this->input->post('ibagian', TRUE);
        $iretur      = $this->input->post('iretur', TRUE);
        $dretur      = $this->input->post('dretur', TRUE);
        if($dretur){
             $tmp   = explode('-', $dretur);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $dateretur = $year.'-'.$month.'-'.$day;
        }
        
        $itujuan      = $this->input->post('itujuan', TRUE);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $id           = $this->mmaster->runningid();

        $i_product    = $this->input->post('idproduct[]',TRUE);
        $i_color      = $this->input->post('idcolorproduct[]',TRUE);
        $n_qtyproduct = $this->input->post('nquantity[]',TRUE);
        $n_qtyproduct = str_replace(',','',$n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iretur);
        $this->mmaster->insertheader($id, $iretur, $ibagian, $dateretur, $itujuan, $eremarkh);  
        
        $no=0;
        foreach ($i_product as $iproduct) {     
            $iproduct    = $iproduct;
            $icolor      = $i_color[$no];
            $nqtyproduct = $n_qtyproduct[$no];
            $edesc       = $e_desc[$no];

            $this->mmaster->insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc); 

            $no++;
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
                'kode'   => $iretur,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "RTK-".date('ym')."-000001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            //die;
            redirect(base_url(),'refresh');
        }

        $id           = $this->input->post('id', TRUE);
        $iretur       = $this->input->post('iretur', TRUE);       
        $dretur       = $this->input->post('dretur', TRUE);
        if($dretur){
             $tmp   = explode('-', $dretur);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $dateretur  = $year.'-'.$month.'-'.$day;
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $itujuan      = $this->input->post('itujuan', TRUE);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $i_product    = $this->input->post('idproduct[]',TRUE);
        $i_color      = $this->input->post('idcolorproduct[]',TRUE);
        $n_qtyproduct = $this->input->post('nquantity[]',TRUE);
        $n_qtyproduct = str_replace(',','',$n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iretur);

        $this->mmaster->updateheader($id, $iretur, $dateretur, $ibagian, $itujuan, $eremarkh);
        $this->mmaster->deletedetail($id);
        
        $no=0;
        foreach ($i_product as $iproduct) {     
            $iproduct    = $iproduct;
            $icolor      = $i_color[$no];
            $nqtyproduct = $n_qtyproduct[$no];
            $edesc       = $e_desc[$no];

            $this->mmaster->insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc); 

            $no++;
        }    

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $iretur,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }   

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */