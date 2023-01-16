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
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
		echo $this->mmaster->data($username, $idcompany, $idepartemen, $ilevel, $this->i_menu, $this->global['folder'], $dfrom, $dto);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'departement'   => $idepart,
            'area'          => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getproduct(){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $dsjk         = $this->uri->segment('4');
            //var_dump($dsjk);
            $data = $this->mmaster->getproduct($cari, $dsjk);
            foreach($data->result() as $ma){       
                    $filter[] = array(
                    'id'    => $ma->i_material, 
                    'name'  => $ma->e_namabrg,  
                    'text'  => $ma->i_material.' - '.$ma->e_namabrg
                );
            }   
            echo json_encode($filter);
    } 

    public function get_product(){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $dsjk         = $this->uri->segment('4');
            //var_dump($dsjk);
            $data = $this->mmaster->get_product($cari, $dsjk);
            foreach($data->result() as $ma){       
                    $filter[] = array(
                    'id'    => $ma->i_material, 
                    'name'  => $ma->e_namabrg,  
                    'text'  => $ma->i_material.' - '.$ma->e_namabrg
                );
            }   
            echo json_encode($filter);
    }  

    public function get_productt(){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $dsjk         = $this->uri->segment('4');
            //var_dump($dsjk);
            $data = $this->mmaster->get_productt($cari, $dsjk);
            foreach($data->result() as $ma){       
                    $filter[] = array(
                    'id'    => $ma->i_material, 
                    'name'  => $ma->e_namabrg,  
                    'text'  => $ma->i_material.' - '.$ma->e_namabrg
                );
            }   
            echo json_encode($filter);
    }  

    public function get(){
        header("Content-Type: application/json", true);
            $eproduct       = $this->input->post('eproduct');
            $ipartner       = $this->input->post('ipartner');
            $itypemakloon   = $this->input->post('itypemakloon');
            $dsjk           = $this->input->post('dsjk');

            $data = $this->mmaster->getma($eproduct, $ipartner, $itypemakloon, $dsjk);
            //$data = $this->db->get();
            echo json_encode($data->result_array());
            //var_dump($data);
            //echo json_encode($data); 
    }

    public function get_get(){
        header("Content-Type: application/json", true);
            $eproduct       = $this->input->post('eproduct');
            $ipartner       = $this->input->post('ipartner');
            $itypemakloon   = $this->input->post('itypemakloon');
            $dsjk           = $this->input->post('dsjk');

            $data = $this->mmaster->get_ma($eproduct, $ipartner, $itypemakloon, $dsjk);
            //$data = $this->db->get();
            echo json_encode($data->result_array());
            //var_dump($data);
            //echo json_encode($data); 
    }

     public function gett(){
        header("Content-Type: application/json", true);
            $eproduct       = $this->input->post('eproduct');
            $ipartner       = $this->input->post('ipartner');
            $itypemakloon   = $this->input->post('itypemakloon');
            $dsjk           = $this->input->post('dsjk');

            $data = $this->mmaster->get_maa($eproduct, $ipartner, $itypemakloon, $dsjk);
            //$data = $this->db->get();
            echo json_encode($data->result_array());
            //var_dump($data);
            //echo json_encode($data); 
    }

    function getpartner(){
        $filter = [];
        $data = $this->mmaster->getpartner();
        foreach($data->result() as  $kdoe){       
            $filter[] = array(
                'id'   => $kdoe->i_partner,  
                'text' => $kdoe->e_partner
            );
        }   
        echo json_encode($filter);
    }

    function getpartner2(){
        $filter = [];
        $data = $this->mmaster->getpartner2();
        foreach($data->result() as  $kdoe){       
            $filter[] = array(
                'id'   => $kdoe->i_partner,  
                'text' => $kdoe->e_partner
            );
        }   
        echo json_encode($filter);
    }

    function getpartner3(){
        $filter = [];
        $data = $this->mmaster->getpartner3();
        foreach($data->result() as  $kdoe){       
            $filter[] = array(
                'id'   => $kdoe->i_partner,  
                'text' => $kdoe->e_partner
            );
        }   
        echo json_encode($filter);
    }

    function gettypemakloon(){
       $ipartner = $this->input->post('ipartner');

        $query     = $this->mmaster->gettypemakloon($ipartner);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_type_makloon." >".$row->e_type_makloon."</option>";
            }
            $kop  = "<option value=\"\">Pilih Type Makloon".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function gettypemakloon2(){
       $i_partner = $this->input->post('i_partner');

        $query     = $this->mmaster->gettypemakloon2($i_partner);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_type_makloon." >".$row->e_type_makloon."</option>";
            }
            $kop  = "<option value=\"\">Pilih Type Makloon".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function gettypemakloon3(){
       $ipartnerr = $this->input->post('ipartnerr');

        $query     = $this->mmaster->gettypemakloon3($ipartnerr);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_type_makloon." >".$row->e_type_makloon."</option>";
            }
            $kop  = "<option value=\"\">Pilih Type Makloon".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibagian         = $this->input->post('ibagian', TRUE);
        $dsjk            = $this->input->post('dsjk',TRUE);
        if($dsjk != ''){
            $tmp   = explode('-', $dsjk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datesjk = $year.'-'.$month.'-'.$day;
        }
        $dback           = $this->input->post('dback', TRUE);
        if($dback != ''){
            $tmp   = explode('-', $dback);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dateback = $year.'-'.$month.'-'.$day;
        }
        $ipermintaan     = $this->input->post('ipermintaan', TRUE);
        $dpermintaan     = $this->input->post('dpermintaan', TRUE);
        if($dpermintaan != ''){
            $tmp   = explode('-', $dpermintaan);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datepermintaan = $year.'-'.$month.'-'.$day;
        }      
        $ipartner        = $this->input->post('ipartner', TRUE);
        $itypemakloon    = $this->input->post('itypemakloon', TRUE);
        $vdiskon         = $this->input->post('vdiskon', TRUE);
        $fpkp            = $this->input->post('fpkp', TRUE);
        $eremark         = $this->input->post('eremark', TRUE);
        $ddok            = $this->input->post('ddok', TRUE);
        $jml             = $this->input->post('jml', TRUE); 


        $i_bagian         = $this->input->post('i_bagian', TRUE);
        $d_sjk            = $this->input->post('d_sjk',TRUE);
        if($d_sjk != ''){
            $tmp   = explode('-', $d_sjk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth2 = $year.$month;
            $date_sjk = $year.'-'.$month.'-'.$day;
        }
        $d_back           = $this->input->post('d_back', TRUE);
        if($d_back != ''){
            $tmp   = explode('-', $d_back);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $date_back = $year.'-'.$month.'-'.$day;
        }
        $i_permintaan     = $this->input->post('i_permintaan', TRUE);
        $d_permintaan     = $this->input->post('d_permintaan', TRUE);
        if($d_permintaan != ''){
            $tmp   = explode('-', $d_permintaan);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $date_permintaan = $year.'-'.$month.'-'.$day;
        }      
        $i_partner        = $this->input->post('i_partner', TRUE);
        $i_typemakloon    = $this->input->post('i_typemakloon', TRUE);
        $v_diskon         = $this->input->post('v_diskon', TRUE);
        $f_pkp            = $this->input->post('f_pkp', TRUE);
        $e_remark         = $this->input->post('e_remark', TRUE);
        $j_ml             = $this->input->post('j_jml', TRUE); 

        $ibagiann         = $this->input->post('ibagiann', TRUE);
        $dsjkk            = $this->input->post('dsjkk',TRUE);
        if($dsjkk != ''){
            $tmp   = explode('-', $dsjkk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth3 = $year.$month;
            $datesjkk = $year.'-'.$month.'-'.$day;
        }
        $dbackk           = $this->input->post('dbackk', TRUE);
        if($dbackk != ''){
            $tmp   = explode('-', $dbackk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datebackk = $year.'-'.$month.'-'.$day;
        }
        $ipermintaann     = $this->input->post('ipermintaann', TRUE);
        $dpermintaann     = $this->input->post('dpermintaann', TRUE);
        if($dpermintaann != ''){
            $tmp   = explode('-', $dpermintaann);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datepermintaann = $year.'-'.$month.'-'.$day;
        }
      
        $ipartnerr        = $this->input->post('ipartnerr', TRUE);
        $itypemakloonn    = $this->input->post('itypemakloonn', TRUE);
        $vdiskonn         = $this->input->post('vdiskonn', TRUE);
        $fpkpp            = $this->input->post('fpkpp', TRUE);
        $eremarkk         = $this->input->post('eremarkk', TRUE);
        $jmll             = $this->input->post('jmll', TRUE); 

        //ITEM
        $i_product              = $this->input->post('iproduct[]', TRUE);
        $n_quantitypermintaan   = $this->input->post('nquantitypermintaan[]', TRUE);
        $n_quantity             = $this->input->post('nquantity[]',TRUE);
        $v_harga                = $this->input->post('vharga[]', TRUE);
        $e_desc                 = $this->input->post('edesc[]',TRUE);
        //var_dump($ddok);
        $this->db->trans_begin();
        if($ddok == '1'){
            $isj  = $this->mmaster->runningnumber_a($yearmonth, $ibagian);
            $this->mmaster->insertheader_a($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ddok);
        }else if($ddok == '2'){
            $i_sj  = $this->mmaster->runningnumber_b($yearmonth2, $i_bagian);
            $this->mmaster->insertheader_b($i_sj, $i_bagian, $date_sjk, $date_back, $i_permintaan, $date_permintaan, $i_partner, $i_typemakloon, $v_diskon, $f_pkp, $e_remark, $ddok);
        }else if($ddok == '3'){
            $isjj  = $this->mmaster->runningnumber_c($yearmonth3, $ibagiann);
            $this->mmaster->insertheader_c($isjj, $ibagiann, $datesjkk, $datebackk, $ipermintaann, $datepermintaann, $ipartnerr, $itypemakloonn, $vdiskonn, $fpkpp, $eremarkk, $ddok);
        }

        $no=0;
        foreach ($i_product as $iproduct) {
           
            $iproduct   = $iproduct;
            $nquantity  = $n_quantity[$no];
            $vharga     = $v_harga[$no];
            $edesc      = $e_desc[$no];

            if($ddok == '1'){
                $this->mmaster->insertdetail_a($isj, $iproduct, $nquantity, $vharga, $edesc, $no); 
            }else if($ddok == '2'){
                $this->mmaster->insertdetail_b($i_sj, $iproduct, $nquantity, $vharga, $edesc, $no); 
            }else if($ddok == '3'){
                $this->mmaster->insertdetail_c($isjj, $iproduct, $nquantity, $vharga, $edesc, $no); 
            }
            $no++;
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
        }else{
            if($ddok == '1'){
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
                $data = array(
                    'sukses' => true,
                    'kode'   => $isj,
                );
            }else if($ddok == '2'){
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$i_sj);
                $data = array(
                    'sukses' => true,
                    'kode'   => $i_sj,
                );
             }else if($ddok == '3'){
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isjj);
                $data = array(
                    'sukses' => true,
                    'kode'   => $isjj,
                );
             }
        } 
        $this->load->view('pesan', $data);      
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function s_send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->s_send($kode);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $sj             = $this->uri->segment('4');
        $itypemakloon   = $this->uri->segment('5');
        $partner        = $this->uri->segment('6');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->baca_header($sj)->row(),
            'bagian'        => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany)->result(),
            'partner'       => $this->mmaster->baca_partner($itypemakloon)->result(),
            'typemakloon'   => $this->mmaster->baca_typemakloon($partner)->result(),
            'detail'        => $this->mmaster->baca_detail($sj)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isj             = $this->input->post('isj',TRUE);
        $ibagian         = $this->input->post('ibagian', TRUE);
        $dsjk            = $this->input->post('dsjk',TRUE);
        if($dsjk != ''){
            $tmp   = explode('-', $dsjk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datesjk = $year.'-'.$month.'-'.$day;
        }
        $dback           = $this->input->post('dback', TRUE);
        if($dback != ''){
            $tmp   = explode('-', $dback);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dateback = $year.'-'.$month.'-'.$day;
        }
        $ipermintaan     = $this->input->post('ipermintaan', TRUE);
        $dpermintaan     = $this->input->post('dpermintaan', TRUE);
        if($dpermintaan != ''){
            $tmp   = explode('-', $dpermintaan);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datepermintaan = $year.'-'.$month.'-'.$day;
        }      
        $ipartner        = $this->input->post('ipartner', TRUE);
        $itypemakloon    = $this->input->post('itypemakloon', TRUE);
        $vdiskon         = $this->input->post('vdiskon', TRUE);
        $fpkp            = $this->input->post('fpkp', TRUE);
        $eremark         = $this->input->post('eremark', TRUE);
        $ndok            = $this->input->post('ndok', TRUE);
        $jml             = $this->input->post('jml', TRUE); 

        $i_product              = $this->input->post('iproduct[]', TRUE);
        $n_quantity             = $this->input->post('nquantity[]',TRUE);
        $v_harga                = $this->input->post('vharga[]', TRUE);
        $e_desc                 = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);

        if($ndok == '1'){
            $this->mmaster->updateheader_print($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ndok);
            $this->mmaster->deletedetail_print($isj); 
        }else if($ndok == '2'){
            $this->mmaster->updateheader_bordir($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ndok);
            $this->mmaster->deletedetail_bordir($isj);
        }else if($ndok == '3'){
            $this->mmaster->updateheader_embosh($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ndok);
            $this->mmaster->deletedetail_embosh($isj);
        }        
        
        $i_sj = $isj;
        $isjj = $isj;
        $no=0;
        foreach ($i_product as $iproduct) {
           
            $iproduct   = $iproduct;
            $nquantity  = $n_quantity[$no];
            $vharga     = $v_harga[$no];
            $edesc      = $e_desc[$no];

            if($ndok == '1'){
                $this->mmaster->insertdetail_a($isj, $iproduct, $nquantity, $vharga, $edesc, $no); 
            }else if($ndok == '2'){
                $this->mmaster->insertdetail_b($i_sj, $iproduct, $nquantity, $vharga, $edesc, $no); 
            }else if($ndok == '3'){
                $this->mmaster->insertdetail_c($isjj, $iproduct, $nquantity, $vharga, $edesc, $no); 
            }
            $no++;
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data SJ Keluar Makloon'.$this->global['title'].' No SJ : '.$isj);
            $data = array(
                'sukses' => true,
                'kode'   => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function getproductedit(){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $dsjk     = $this->uri->segment('4');
            $ndok     = $this->uri->segment('5');
            if($ndok == '1'){
                $data = $this->mmaster->getproduct($cari, $dsjk);
                foreach($data->result() as $ma){       
                    $filter[] = array(
                    'id'    => $ma->i_material, 
                    'name'  => $ma->e_namabrg,  
                    'text'  => $ma->i_material.' - '.$ma->e_namabrg
                );
                }
            }else if($ndok == '3'){
                $data = $this->mmaster->get_productt($cari, $dsjk);
                foreach($data->result() as $ma){       
                    $filter[] = array(
                    'id'    => $ma->i_material, 
                    'name'  => $ma->e_namabrg,  
                    'text'  => $ma->i_material.' - '.$ma->e_namabrg
                );
                }   
            }else if($ndok == '2'){
                $data = $this->mmaster->get_product($cari, $dsjk);
                foreach($data->result() as $ma){       
                    $filter[] = array(
                    'id'    => $ma->i_material, 
                    'name'  => $ma->e_namabrg,  
                    'text'  => $ma->i_material.' - '.$ma->e_namabrg
                );
                }      
            }
            echo json_encode($filter);
    } 

    public function getedit(){
        header("Content-Type: application/json", true);
            $eproduct       = $this->input->post('eproduct');
            $ipartner       = $this->input->post('ipartner');
            $itypemakloon   = $this->input->post('itypemakloon');
            $dsjk           = $this->input->post('dsjk');
            $ndok           = $this->input->post('ndok');

            if($ndok == '1'){
                $data = $this->mmaster->getma($eproduct, $ipartner, $itypemakloon, $dsjk);
            }else if($ndok == '2'){
                $data = $this->mmaster->get_ma($eproduct, $ipartner, $itypemakloon, $dsjk);
            }else if($ndok == '3'){
                $data = $this->mmaster->get_maa($eproduct, $ipartner, $itypemakloon, $dsjk);
            }
            echo json_encode($data->result_array());
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $ndok = $this->input->post('ndok');
        if($ndok == '1'){
            $this->mmaster->send_print($isj);
        }else if($ndok == '2'){
            $this->mmaster->send_bordir($isj);      
        }else if($ndok == '3'){
            $this->mmaster->send_embosh($isj);    
        }
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $ndok = $this->input->post('ndok');
        if($ndok == '1'){
            $this->mmaster->cancel_print($isj);
        }else if($ndok == '2'){
            $this->mmaster->cancel_bordir($isj);      
        }else if($ndok == '3'){
            $this->mmaster->cancel_embosh($isj);    
        }
    }

    public function view(){
        $sj             = $this->uri->segment('4');
        $itypemakloon   = $this->uri->segment('5');
        $partner        = $this->uri->segment('6');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->baca_header($sj)->row(),
            'bagian'        => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany)->result(),
            'partner'       => $this->mmaster->baca_partner($itypemakloon)->result(),
            'typemakloon'   => $this->mmaster->baca_typemakloon($partner)->result(),
            'detail'        => $this->mmaster->baca_detail($sj)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $sj             = $this->uri->segment('4');
        $itypemakloon   = $this->uri->segment('5');
        $partner        = $this->uri->segment('6');

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->baca_header($sj)->row(),
            'bagian'        => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany)->result(),
            'partner'       => $this->mmaster->baca_partner($itypemakloon)->result(),
            'typemakloon'   => $this->mmaster->baca_typemakloon($partner)->result(),
            'detail'        => $this->mmaster->baca_detail($sj)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $isj   = $this->input->post('isj');
        $ndok  = $this->input->post('ndok');
        $this->db->trans_begin();
        if($ndok == '1'){
             $this->mmaster->approve_print($isj);
        }else if($ndok == '2'){
             $this->mmaster->approve_bordir($isj);
        }else if($ndok == '3'){
             $this->mmaster->approve_embosh($isj);
        }
       
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $ndok  = $this->input->post('ndok');
        if($ndok == '1'){
               $this->mmaster->change_print($isj);
        }else if($ndok == '2'){
               $this->mmaster->change_bordir($isj);
        }else if($ndok == '3'){
               $this->mmaster->change_embosh($isj);
        }      
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $ndok  = $this->input->post('ndok');
        if($ndok == '1'){
             $this->mmaster->reject_print($isj);
        }else if($ndok == '2'){
             $this->mmaster->reject_bordir($isj);
        }else if($ndok == '3'){
             $this->mmaster->reject_embosh($isj);
        }
    }   

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj         = $this->input->post('isj');
        $ndok        = $this->input->post('ndok');
        $this->db->trans_begin();
        if($ndok == '1'){
            $data = $this->mmaster->delete_print($isj);
        }else if($ndok == '2'){
            $data = $this->mmaster->delete_bordir($isj);
        }else if($ndok == '3'){
            $data = $this->mmaster->delete_embosh($isj);
        }
        
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJ Keluar Makloon QC Set : '.$isj);
            echo json_encode($data);
        }
    }

  //   public function deletedetail(){
  //       $data = check_role($this->i_menu, 4);
  //       if(!$data){
  //           redirect(base_url(),'refresh');
  //       }

  //       $isj			= $this->input->post('isj', TRUE);
		// $iproduct		= $this->input->post('iproduct', TRUE);
		// $imaterial	    = $this->input->post('imaterial', TRUE);
		// $icolor	        = $this->input->post('icolor', TRUE);

  //       $this->db->trans_begin();
  //       $this->mmaster->deletedetail($isj, $iproduct, $imaterial, $icolor);
  //       if ($this->db->trans_status() === FALSE){
  //               $this->db->trans_rollback();
  //       }else{
  //           $this->db->trans_commit();
  //           $this->Logger->write('Delete Item SJ Keluar Makloon Unit Jahit : '.$isj.' Produk : '.$iproduct.' Material : '.$imaterial );
  //           echo json_encode($data);
  //       }
  //   }  
}
/* End of file Cform.php */