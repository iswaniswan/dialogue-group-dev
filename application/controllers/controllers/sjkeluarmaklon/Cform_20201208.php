<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050407';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
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
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'departement'   => $idepart,
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
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

    public function getreff(){
        $ipartner = $this->input->post('ipartner');

        $query     = $this->mmaster->reff($ipartner);
        if($query->num_rows()>0) {
            $c   = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->referensi." >".$row->referensi."</option>";
            }
            $kop  = "<option value=\"\">Pilih Nomor Referensi".$c."</option>";
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

    public function getdetailreff(){
        header("Content-Type: application/json", true);
        $reff         = $this->input->post('reff', FALSE);
        $ipartner     = $this->input->post('ipartner', FALSE);
        $itypemakloon = $this->input->post('itypemakloon', FALSE);

        $query  = array(
            'head'   => $this->mmaster->getreff($reff)->row(),
            'detail' => $this->mmaster->getreff_detail($reff, $ipartner, $itypemakloon)->result_array()

        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dsjk   = $this->input->post("dsjk",true);
        if($dsjk){
                 $tmp   = explode('-', $dsjk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datesjk = $year.'-'.$month.'-'.$day;
        }

        $dback        = $this->input->post('dback', TRUE);
        if($dback){
                 $tmp   = explode('-', $dback);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $dateback = $year.'-'.$month.'-'.$day;
        }
        $dreff        = $this->input->post('dreff', TRUE);
        if($dreff){
                 $tmp   = explode('-', $dreff);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $datereff = $year.'-'.$month.'-'.$day;
        }
        $ibagian       = $this->input->post('ibagian', TRUE);
        $reff          = $this->input->post('reff', TRUE);
        $partner       = $this->input->post('ipartner', TRUE);
        $itypemakloon  = $this->input->post('itypemakloon', TRUE);
        $fpkp          = $this->input->post('fpkp', TRUE); 
        $remark        = $this->input->post('eremark', TRUE);
        $jml           = $this->input->post('jml', TRUE); 
        $cancel        = 'f';
       
        $i_material      = $this->input->post('imaterial[]', TRUE);    
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $i_material2     = $this->input->post('imaterial2[]', TRUE);    
        $n_quantity2     = $this->input->post('nquantity2[]', TRUE);
        $i_satuan2       = $this->input->post('isatuan2[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);
        $pemenuhan       = $this->input->post('pemenuhan[]', TRUE);
        $pemenuhan2      = $this->input->post('pemenuhan2[]', TRUE);
        $v_price         = $this->input->post('v_price[]', TRUE);
        //var_dump($nosjkeluar);

        $this->db->trans_begin();
        $nosjkeluar    = $this->mmaster->runningnumberkeluarm($yearmonth, $ibagian);
        $this->mmaster->insertheader($nosjkeluar, $datesjk, $ibagian, $partner, $remark, $dateback, $reff, $datereff, $itypemakloon, $fpkp);

            $no = 0;
            $lastmaterial = '';
            $lastpemenuhan = 0;

            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantity     = $n_quantity[$no];
                $pemenuhan_1   = $pemenuhan[$no];
                $isatuan       = $i_satuan[$no];
                $edesc         = $e_desc[$no];
                $imaterial2    = $i_material2[$no];
                $nquantity2    = $n_quantity2[$no];
                $pemenuhan_2   = $pemenuhan2[$no];
                $isatuan2      = $i_satuan2[$no];
                $vprice        = $v_price[$no];

                if ($lastmaterial == $imaterial) {
                    $pemenuhan_1 = $lastpemenuhan;
                } else {
                    $pemenuhan_1 = $pemenuhan_1;
                }
                $sisa  = $nquantity - $pemenuhan_1;
                $sisaa = $nquantity2 - $pemenuhan_2;

                $this->mmaster->insertdetail($nosjkeluar, $ibagian, $imaterial, $nquantity, $isatuan, $pemenuhan_1, $imaterial2, $nquantity2, $isatuan2,  $pemenuhan_2, $no, $edesc, $vprice);
                $this->mmaster->updatesisa($reff, $imaterial, $imaterial2, $sisa, $sisaa);

                $no++;
                $lastmaterial  = $imaterial;
                $lastpemenuhan =  $pemenuhan_1;
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nosjkeluar);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nosjkeluar,
                );
        }
    $this->load->view('pesan', $data);      
    
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $sj       = $this->uri->segment('4');
        $ipartner = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'       => $this->mmaster->getpartner()->result(),
            'typemakloon'   => $this->mmaster->gettypemakloon($ipartner)->result(),
            'departement'   => $idepart,
            'head'          => $this->mmaster->baca_header($sj)->row(),
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
        
        $nosjkeluar    = $this->input->post('isj', TRUE);
        $dsjk = $this->input->post("dsjk",true);
        if($dsjk){
                 $tmp   = explode('-', $dsjk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $datesjk = $year.'-'.$month.'-'.$day;
        }
        $ibagian       = $this->input->post('ibagian', TRUE);
        $supplier      = $this->input->post('ipartner', TRUE);
        $dback         = $this->input->post('dback', TRUE);
        if($dback){
                 $tmp   = explode('-', $dback);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $dateback = $year.'-'.$month.'-'.$day;
        }
        $reff          = $this->input->post('reff', TRUE);
        $dreff         = $this->input->post('dreff', TRUE);
        if($dreff){
                 $tmp   = explode('-', $dreff);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $datereff = $year.'-'.$month.'-'.$day;
        }
        $itypemakloon  = $this->input->post('itypemakloon', TRUE);
        $remark        = $this->input->post('eremark', TRUE);
        $jml           = $this->input->post('jml', TRUE); 

        $i_material      = $this->input->post('imaterial[]', TRUE);
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);
        $v_price         = $this->input->post('vprice[]', TRUE);
        //var_dump($nosjkeluar);
        $this->db->trans_begin();
        $this->mmaster->updateheader($nosjkeluar, $datesjk, $ibagian, $reff, $supplier, $remark, $dateback, $datereff, $itypemakloon);
        $this->mmaster->deletedetail($nosjkeluar);

        $urutan = 1;
        $lastmaterial = '';
        for($i=1;$i<=$jml;$i++){
            $imaterial   = $this->input->post('imaterial'.$i, TRUE);
            $nquantity   = $this->input->post('nquantity'.$i, TRUE);
            $isatuan     = $this->input->post('isatuan'.$i, TRUE);
            $pemenuhan_1 = $this->input->post('pemenuhan'.$i, TRUE);

            $imaterial2  = $this->input->post('imaterial2'.$i, TRUE);
            $nquantity2  = $this->input->post('nquantity2'.$i, TRUE);
            $isatuan2    = $this->input->post('isatuan2'.$i, TRUE);
            $pemenuhan_2 = $this->input->post('pemenuhan2'.$i, TRUE);
            $edesc       = $this->input->post('edesc'.$i, TRUE);
            $vprice      = $this->input->post('vprice'.$i, TRUE);

            if ($imaterial == $lastmaterial) {
                $imaterial = $lastimaterial;
                $nquantity = $lastnquantity;
                $isatuan = $lastisatuan;
                $pemenuhan_1 = $lastpemenuhan_1;
            } else {
                $lastimaterial = $imaterial;
                $lastnquantity = $nquantity;
                $lastisatuan = $isatuan;
                $lastpemenuhan_1 = $pemenuhan_1;
            }

            $lastmaterial = $imaterial;
            $this->mmaster->insertdetail($nosjkeluar, $ibagian, $imaterial, $nquantity,$isatuan, $pemenuhan_1, $imaterial2, $nquantity2, $isatuan2,  $pemenuhan_2, $urutan,  $edesc, $vprice);
            $urutan++;
        }
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Uddate Data '.$this->global['title'].' No SJ : '.$nosjkeluar);
            $data = array(
                'sukses' => true,
                'kode'      => $nosjkeluar,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->sendd($isj);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->cancel_approve($isj);
    }

    public function view(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $sj       = $this->uri->segment('4');
        $ipartner = $this->uri->segment('5');

        
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'       => $this->mmaster->getpartner()->result(),
            'typemakloon'   => $this->mmaster->gettypemakloon($ipartner)->result(),
            'departement'   => $idepart,
            'head'          => $this->mmaster->baca_header($sj)->row(),
            'detail'        => $this->mmaster->baca_detail($sj)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function cetak(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $sj       = $this->uri->segment('4');
        $ipartner = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Print ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'       => $this->mmaster->getpartner()->result(),
            'typemakloon'   => $this->mmaster->gettypemakloon($ipartner)->result(),
            'departement'   => $idepart,
            'data'          => $this->mmaster->baca_header($sj)->row(),
            'detail'        => $this->mmaster->baca_detail($sj)->result(),
            'item'          => $this->mmaster->baca_itemm($sj)->result(),
            'idepartemen'   => trim($this->session->userdata('i_departement')),
            'ilevel'        => trim($this->session->userdata('i_level')),
        );

        $this->Logger->write('Membuka Menu Cetak ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $sj       = $this->uri->segment('4');
        $ipartner = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'       => $this->mmaster->getpartner()->result(),
            'typemakloon'   => $this->mmaster->gettypemakloon($ipartner)->result(),
            'departement'   => $idepart,
            'head'          => $this->mmaster->baca_header($sj)->row(),
            'detail'        => $this->mmaster->baca_detail($sj)->result(),
            );
    
            $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj = $this->input->post('isj');
        
        $this->mmaster->approve($isj);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isj
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function change(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->change_approve($isj);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('isj');
        $this->mmaster->reject_approve($isj);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isj = $this->input->post('isj', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isj);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Surat Keluar Makloon Bahan Pembantu' . $isj);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */