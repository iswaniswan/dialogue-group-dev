<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '21102';

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
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
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
            'kodearea'      => $this->mmaster->kodearea()->result(),
            // 'kodesalesman'  => $this->mmaster->kodesalesman()->result(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'id'            => $this->mmaster->runningid()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
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

    public function datarencana(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->datarencana($cari);
        foreach($data->result() as $rencana){       
            $filter[] = array(
                'id'    => $rencana->id_rencana,
                'name'  => $rencana->nama_rencana,
                'text'  => $rencana->id_rencana.' - '.$rencana->nama_rencana
            );
        }   
        echo json_encode($filter);
    }

    public function datacustomer(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->datacustomer($cari);
        foreach($data->result() as $customer){       
            $filter[] = array(
                'id'    => $customer->id_customer,
                'name'  => $customer->e_customer_name,
                'text'  => $customer->i_customer.' - '.$customer->e_customer_name.' - '.$customer->area
            );
        }   
        echo json_encode($filter);
    }

    public function getarea(){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));
        $data       = $this->mmaster->getarea($cari);
        foreach($data->result() as $kuy){
            $filter[] = array(
                'id'    => $kuy->id,
                'text'  => $kuy->e_area
            );
            
            echo json_encode($filter);
        }
    }

    public function getsalesman($area){
        $filter = [];
        $cari       = strtoupper($this->input->get('q'));        
        $iarea      = $area;
        $data       = $this->mmaster->getsalesman($cari,$iarea);
        foreach($data->result() as $kuy){
            $filter[] = array(
                'id'    => $kuy->id,
                'text'  => $kuy->i_sales." - ".$kuy->e_sales
            );
        }
        echo json_encode($filter);
    }

    public function getcity($area){
        $filter     = [];
        $cari       = strtoupper($this->input->get('q'));
        $iarea      = $area;
        $data       = $this->mmaster->getcity($cari,$iarea);
        foreach($data->result() as $kuy){
            $filter[] = array(
                'id'    => $kuy->id,  
                'text'  => $kuy->i_city." - ".$kuy->e_city_name
            );
        }
        echo json_encode($filter);
        
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = [];
        $folder         = $this->global['folder'];
        $company        = $this->session->userdata('id_company');
        $bagian         = $this->input->post('ibagian', TRUE);
        $bulan          = $this->input->post('bulan', TRUE);
        $bulan          = date("m", strtotime($bulan));
        $tahun          = $this->input->post('tahun', TRUE);
        $kodearea       = $this->input->post('kode_area', TRUE);
        $kota           = $this->input->post('kota[]', TRUE);
        $sales          = $this->input->post('sales[]', TRUE);
        $target         = $this->input->post('target[]', TRUE);

        $periode         = $tahun.$bulan;

        $this->db->trans_begin();
        if (
            /* $iproductwip != "" && */
            $bagian != ""
        ) {
            
            $i = 0;
            foreach($kota as $kotas){
                $kota_2 = $kotas;
                $sales_2 = $sales[$i];
                $target_2 = $target[$i];
                //var_dump();
                
                $this->mmaster->insert($company,$bagian,$periode,$kodearea,$kota_2, $sales_2, $target_2);
                $i++;
            }
            //die();

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = [
                    "sukses" => false,
                    "kode" => $periode." - ".$kodearea,
                ];
            } else {
                $this->db->trans_commit();
                $data = [
                    "sukses" => true,
                    "periode" => $periode,
                    "kode" => $periode." - ".$kodearea,
                ];
                $this->Logger->write(
                    "Simpan Data " .
                        $this->global["title"] .
                        " Periode : " .
                        $periode
                );
            }
            $this->load->view("pesan", $data);
        }
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $bagian     = $this->uri->segment('4');
        $periode    = $this->uri->segment('5');
        $cekarea    = $this->uri->segment('6');
        $imenu      = $this->uri->segment('7');
        $area       = str_replace("%20", " ", $cekarea);
        $tahun      = substr($periode, 0, 4);
        $bulan      = substr($periode, 4);
        $cekdata    = $this->mmaster->cek_data($periode, $area, $imenu)->result();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $bagian,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'area'          => $area,     
            'i_menu'        => $imenu,
            'dfrom'         => $this->uri->segment(8),
            'dto'           => $this->uri->segment(9),
            'data'          => $this->mmaster->cek_data($periode, $area, $imenu)->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $bagian     = $this->uri->segment('4');
        $periode    = $this->uri->segment('5');
        $cekarea    = $this->uri->segment('6');
        $imenu      = $this->uri->segment('7');
        $idarea     = $this->uri->segment('10');
        $area       = str_replace("%20", " ", $cekarea);
        $tahun      = substr($periode, 0, 4);
        $bulan      = substr($periode, 4);
        $cekdata    = $this->mmaster->cek_data($periode, $area, $imenu)->result();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $bagian,
            'databagian'    => $this->mmaster->bagian()->result(),
            'kodearea'      => $this->mmaster->kodearea()->result(),
            'kodecity'      => $this->mmaster->kodecity($idarea)->result(),
            'kodesalesman'  => $this->mmaster->kodesalesman($idarea)->result(),
            'periode'       => $periode,
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'area'          => $area,     
            'i_menu'        => $imenu,
            'dfrom'         => $this->uri->segment(8),
            'dto'           => $this->uri->segment(9),
            'data'          => $this->mmaster->cek_data($periode, $area, $imenu)->result(), 
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data           = [];
        $folder         = $this->global['folder'];
        $company        = $this->session->userdata('id_company');
        $bagian         = $this->input->post('ibagian', TRUE);
        $bulan          = $this->input->post('bulan', TRUE);
        $bulan          = date("m", strtotime($bulan));
        $tahun          = $this->input->post('tahun', TRUE);
        $idarea         = $this->input->post('idarea', TRUE);
        $kodearea       = $this->input->post('kode_area', TRUE);
        //$targetperiode  = $this->input->post('targetperiode', TRUE);
        $periodelama    = $this->input->post('periodelama', TRUE);
        $periode        = $tahun.$bulan;
        $kota           = $this->input->post('kota[]', TRUE);
        $sales          = $this->input->post('sales[]', TRUE);
        $target         = $this->input->post('target[]', TRUE);

        $this->db->trans_begin();
        if (
            /* $iproductwip != "" && */
            $bagian != ""
        ) {
            $this->Logger->write(
                "Simpan Data " .
                    $this->global["title"] .
                    " Periode : " .
                    $periode
            );

            $this->mmaster->delete($periodelama,$idarea);    
            
            $i = 0;
            foreach($kota as $kotas){
                $kota_2   = $kotas;
                $sales_2  = $sales[$i];
                $target_2 = $target[$i];
                //var_dump();
                
                $this->mmaster->insert($company,$bagian,$periode,$kodearea,$kota_2, $sales_2, $target_2);
                $i++;
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = [
                    "sukses" => false,
                    "kode" => $periode." - ".$kodearea,
                ];
            } else {
                $this->db->trans_commit();
                $data = [
                    "sukses" => true,
                    "periode" => $periode,
                    "kode" => $periode." - ".$kodearea,
                ];
            }
            $this->load->view("pesan", $data);
        }
    }

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
}
/* End of file Cform.php */