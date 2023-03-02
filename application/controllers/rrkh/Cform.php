<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '21101';

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

/* 
    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea(),
            'kunjungan' => $this->mmaster->bacakunjungan()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }
 */ 

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
            // 'dfrom'     => $dfrom,
            // 'dto'       => $dto,
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
        $idcompany = $this->session->userdata('id_company');
        $kodesales = $this->mmaster->kodesalesman()->result();
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'kodearea'      => $this->mmaster->kodearea()->result(),
            'kodesalesman'  => $kodesales,
            'id_salesman_upline'   => (!empty($kodesales)) ? $kodesales[0]->id_upline : null,
            'i_sales_upline'       => (!empty($kodesales)) ? $kodesales[0]->i_sales_upline : null,
            'e_sales_upline'       => (!empty($kodesales)) ? $kodesales[0]->e_sales_upline : null,
            'number'        => "RRKH-".date('ym')."-000001",
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            // 'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getsalesmanupline()
    {
        $data = $this->mmaster->getsalesmanupline($this->input->post('kode', true));
        if($data->num_rows() > 0) {
            echo json_encode($data->row());
        } else {
            echo json_encode(null);
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

    public function datarencana(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->datarencana($cari);
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
        $cari = strtoupper($this->input->get('q'));
        $iarea = strtoupper($this->input->get('iarea'));
        $data = $this->mmaster->datacustomer($cari, $iarea);
        foreach($data->result() as $customer){       
            $filter[] = array(
                'id'    => $customer->id_customer,
                'name'  => $customer->e_customer_name,
                'text'  => $customer->i_customer.' - '.$customer->e_customer_name.' - '.$customer->area
            );
        }   
        echo json_encode($filter);
    }

    public function getcustomergenerate()
    {
        header("Content-Type: application/json", true);
        $date = formatYmd($this->input->post('date', true));
        $area = $this->input->post('area', true);
        $salesman = $this->input->post('salesman', true);
        $data = $this->mmaster->getcustomergenerate($date, $area, $salesman);
        // if($data->num_rows()>0) {
        echo json_encode($data->result());
        // }
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getsalesman($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_salesman,
                    'text'  => $kuy->i_salesman." - ".$kuy->e_salesman_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcustomer(){
        /* $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcustomer($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_customer,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        } */
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getcustomer($this->input->post('ecust'));

        echo json_encode($data->result_array());
    }

    public function getcity(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $iarea      = $this->input->get('iarea', FALSE);
            $data       = $this->mmaster->getcity($cari,$iarea);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_city,  
                    'text'  => $kuy->i_city." - ".$kuy->e_city_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $folder     = $this->global['folder'];
        $ibagian     = $this->input->post('ibagian', TRUE);
        $dok_rrkh  = $this->input->post('dok_rrkh', TRUE);
        $drrkh      = $this->input->post('drrkh', TRUE);
        // $dreceive1  = $this->input->post('dreceive1', TRUE);

        // if($drrkh!=''){
        //     $tmp=explode("-",$drrkh);
        //     $th=$tmp[2];
        //     $bl=$tmp[1];
        //     $hr=$tmp[0];
        //     $drrkh=$th."-".$bl."-".$hr;
        // }

        if($drrkh){
            $tmp   = explode('-', $drrkh);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $drrkh  = $year.'-'.$month.'-'.$day;
       }
        
        $kode_area      = $this->input->post('kode_area', TRUE);
        $kode_salesman      = $this->input->post('kode_salesman', TRUE);
        $id_salesman_upline      = $this->input->post('id_salesman_upline', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $id           = $this->mmaster->runningid();
/* 
        $idcust    = $this->input->post('idcust[]',TRUE);
        // $waktu_x      = $this->input->post('waktu[]',TRUE);
        $waktu_x      = $this->input->post('waktu[]',TRUE);
        $idrencana = $this->input->post('idrencana[]',TRUE);
        $real      = $this->input->post('real[]',TRUE);
        $bukti     = $this->input->post('bukti[]',TRUE);
        // $n_qtyproduct = str_replace(',','',$n_qtyproduct);
        $eremark       = $this->input->post('eremark[]',TRUE);
 */
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$dok_rrkh);
        // var_dump($id, $dok_rrkh, $ibagian, $drrkh, $kode_area, $kode_salesman);
        $this->mmaster->insertheader($id, $dok_rrkh, $ibagian, $drrkh, $kode_area, $kode_salesman, $id_salesman_upline, $eremark);  
    //    $waktu_xx = '22-01-2022';
       
    // var_dump($_POST);
        
        // $i=0;
        // foreach ($this->input->post('idcust') as $id_customer) { 
        for($i=1;$i<=$jml;$i++){   
            /* $idcust    = $idcust;
            $idrencana = $idrencana[$no];
            $real      = $real[$no];
            $bukti     = $bukti[$no];

            
            $eremark   = $eremark[$no];*/
            
            
            // $eremark = $this->input->post('eremark[]')[$i];
            // $id_rencana = $this->input->post('idrencana[]')[$i];
            // $waktu = $this->input->post('waktu[]')[$i];
            $id_customer    = $this->input->post('idcust'.$i);
            $eremark = $this->input->post('eremark'.$i);
            $id_rencana = $this->input->post('idrencana'.$i);
            $waktu = $this->input->post('waktu'.$i);
            $waktu = date('Y-m-d', strtotime($waktu));
            $e_remark   = $this->input->post('e_remark'.$i, TRUE);

            // if ($i >= 1){
            //     $x = $i-1;
                $realx = $this->input->post('real'.$i);

                if($realx == 'on'){
                    $real = 't';
                }
                else{
                    $real = 'f';
                }
            // }
            // $real = $this->input->post('real[]');
            // $real = $this->input->post('real[]', TRUE);
            

            // if ($i >= 1){
            //     $x = $i-1;
                $buktix = $this->input->post('bukti'.$i);

                if($buktix == 'on'){
                    $bukti = 't';
                }
                else{
                    $bukti = 'f';
                }
            // }
            // $bukti = $this->input->post('bukti'.$i);
            // $bukti = $this->input->post('bukti[]');
            // $bukti = $this->input->post('bukti[]', TRUE)[$i];
            // if($bukti == 'on'){
            //     $bukti = 't';
            // }
            // else{
            //     $bukti = 'f';
            // }

            // if ( ! isset($this->input->post('real[]')[$i])) {
            //     $real = 'f';
            // }else{
            //     $real = $this->input->post('real[]')[$i];
            //     if($real == 'on'){
            //         $real = 't';
            //     }
            //     else{
            //         $real = 'f';
            //     }
            // }
            // if ( ! isset($this->input->post('bukti[]')[$i])) {
            //     $bukti = 'f';
            // }else{
            //     $bukti = $this->input->post('bukti[]')[$i];
            //     if($bukti == 'on'){
            //         $bukti = 't';
            //     }
            //     else{
            //         $bukti = 'f';
            //     }
            // }
            
            // var_dump($id, $id_customer, $waktu, $id_rencana, $real, $bukti, $eremark);
            $this->mmaster->insertdetail($id, $id_customer, $waktu, $id_rencana, $real, $bukti, $e_remark);
            // $i++;
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
                'kode'   => $dok_rrkh,
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
        $id        = $this->uri->segment(4);
        $idcompany = $this->session->userdata('id_company');
        $kodesales = $this->mmaster->kodesalesman()->result();
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'kodearea'      => $this->mmaster->kodearea()->result(),
            'kodesalesman'  => $kodesales,
            // 'id_salesman_upline'   => (!empty($kodesales)) ? $kodesales[0]->id_upline : null,
            // 'i_sales_upline'       => (!empty($kodesales)) ? $kodesales[0]->i_sales_upline : null,
            // 'e_sales_upline'       => (!empty($kodesales)) ? $kodesales[0]->e_sales_upline : null,
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany)->result(), 
            'number'        => "RRKH-".date('ym')."-000001",
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            // 'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $folder     = $this->global['folder'];
        $id          = $this->input->post('id', TRUE);
        $ibagian     = $this->input->post('ibagian', TRUE);
        $dok_rrkh  = $this->input->post('dok_rrkh', TRUE);
        $drrkh      = $this->input->post('drrkh', TRUE);
        // $dreceive1  = $this->input->post('dreceive1', TRUE);

        // if($drrkh!=''){
        //     $tmp=explode("-",$drrkh);
        //     $th=$tmp[2];
        //     $bl=$tmp[1];
        //     $hr=$tmp[0];
        //     $drrkh=$th."-".$bl."-".$hr;
        // }

        if($drrkh){
            $tmp   = explode('-', $drrkh);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $drrkh  = $year.'-'.$month.'-'.$day;
       }
        
        $kode_area      = $this->input->post('kode_area', TRUE);
        $kode_salesman      = $this->input->post('kode_salesman', TRUE);
        $id_salesman_upline      = $this->input->post('id_salesman_upline', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        // $id           = $this->mmaster->runningid();
/* 
        $idcust    = $this->input->post('idcust[]',TRUE);
        // $waktu_x      = $this->input->post('waktu[]',TRUE);
        $waktu_x      = $this->input->post('waktu[]',TRUE);
        $idrencana = $this->input->post('idrencana[]',TRUE);
        $real      = $this->input->post('real[]',TRUE);
        $bukti     = $this->input->post('bukti[]',TRUE);
        // $n_qtyproduct = str_replace(',','',$n_qtyproduct);
        $eremark       = $this->input->post('eremark[]',TRUE);
 */
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$dok_rrkh);
        // var_dump($id, $dok_rrkh, $ibagian, $drrkh, $kode_area, $kode_salesman);
        $this->mmaster->updateheader($id, $dok_rrkh, $ibagian, $drrkh, $kode_area, $kode_salesman, $id_salesman_upline, $eremark);  
        $this->mmaster->deletedetail($id);
    //    $waktu_xx = '22-01-2022';
       
    // var_dump($_POST);
        
        // $i=0;
        // foreach ($this->input->post('idcust') as $id_customer) { 
        for($i=1;$i<=$jml;$i++){   
            /* $idcust    = $idcust;
            $idrencana = $idrencana[$no];
            $real      = $real[$no];
            $bukti     = $bukti[$no];

            
            $eremark   = $eremark[$no];*/
            
            
            // $eremark = $this->input->post('eremark[]')[$i];
            // $id_rencana = $this->input->post('idrencana[]')[$i];
            // $waktu = $this->input->post('waktu[]')[$i];
            $id_customer    = $this->input->post('idcust'.$i);
            $eremark = $this->input->post('eremark'.$i);
            $id_rencana = $this->input->post('idrencana'.$i);
            $waktu = $this->input->post('waktu'.$i);
            $e_remark      = $this->input->post('e_remark'.$i, TRUE);
            $waktu = date('Y-m-d', strtotime($waktu));
            // if ($i >= 1){
            //     $x = $i-1;
                $realx = $this->input->post('real'.$i);

                if($realx == 'on'){
                    $real = 't';
                }
                else{
                    $real = 'f';
                }
            // }
            // $real = $this->input->post('real[]');
            // $real = $this->input->post('real[]', TRUE);
            

            // if ($i >= 1){
            //     $x = $i-1;
                $buktix = $this->input->post('bukti'.$i);

                if($buktix == 'on'){
                    $bukti = 't';
                }
                else{
                    $bukti = 'f';
                }
            // }
            // $bukti = $this->input->post('bukti'.$i);
            // $bukti = $this->input->post('bukti[]');
            // $bukti = $this->input->post('bukti[]', TRUE)[$i];
            // if($bukti == 'on'){
            //     $bukti = 't';
            // }
            // else{
            //     $bukti = 'f';
            // }

            // if ( ! isset($this->input->post('real[]')[$i])) {
            //     $real = 'f';
            // }else{
            //     $real = $this->input->post('real[]')[$i];
            //     if($real == 'on'){
            //         $real = 't';
            //     }
            //     else{
            //         $real = 'f';
            //     }
            // }
            // if ( ! isset($this->input->post('bukti[]')[$i])) {
            //     $bukti = 'f';
            // }else{
            //     $bukti = $this->input->post('bukti[]')[$i];
            //     if($bukti == 'on'){
            //         $bukti = 't';
            //     }
            //     else{
            //         $bukti = 'f';
            //     }
            // }
            // var_dump($id, $id_customer, $waktu, $id_rencana, $real, $bukti, $eremark);
            $this->mmaster->insertdetail($id, $id_customer, $waktu, $id_rencana, $real, $bukti, $e_remark);
            // $i++;
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
                'kode'   => $dok_rrkh,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
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
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $d_document = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');
        // $idtypespb  = $this->uri->segment(8);

        $dataHeader = $this->mmaster->cek_data($id, $idcompany)->row();

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $dataHeader,
            'detail'    => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
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
          
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany)->result(),    
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */
