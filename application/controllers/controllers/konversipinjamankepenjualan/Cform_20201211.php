<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050215';

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

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
       // var_dump($dfrom);
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
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
            'ilokasi'       => $lokasi,
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function partner(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $this->db->select("distinct (a.i_supplier), a.e_supplier_name");
        $this->db->from("tr_supplier a");
        $this->db->join("tm_bonmkeluar_pinjamanbb b", "a.i_supplier = b.department");
        $this->db->like("UPPER(a.i_supplier)", $cari);
        $this->db->or_like("UPPER(a.e_supplier_name)", $cari);
        $data = $this->db->get();
        foreach($data->result() as  $ikode){
                $filter[] = array(
                'id'   => $ikode->i_supplier,  
                'text' => $ikode->e_supplier_name,
            );
        }          
        echo json_encode($filter);
    }

    function getsjkp(){
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $ipartner = $this->input->get('ipartner', FALSE);
        $data = $this->mmaster->sjkp($cari, $ipartner);
        foreach($data->result() as  $sjkp){       
            $filter[] = array(
                'id' => $sjkp->i_bonmk,  
                'text' => $sjkp->i_bonmk.' || '.$sjkp->d_bonmk
            );
        }   
        echo json_encode($filter);
    }

    public function getdetailsjkp(){
        header("Content-Type: application/json", true);
        $isjkp      = $this->input->post('isjkp', FALSE);
        $ipartner   = $this->input->post('ipartner', FALSE);
        $query  = array(
            'head'   => $this->mmaster->getsjkp($isjkp, $ipartner)->row(),
            'detail' => $this->mmaster->getsjkp_detail($isjkp)->result_array()
        );
        echo json_encode($query);  
    } 

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $istore         = $this->input->post('istore', TRUE);
        $ilokasi        = $this->input->post('ilokasi', TRUE);
        $dkonversi      = $this->input->post('dkonversi', TRUE);
        if($dkonversi){
             $tmp   = explode('-', $dkonversi);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $datekonversi = $year.'-'.$month.'-'.$day;
        }
        $dbonmk      = $this->input->post('dbonmk', TRUE);
        if($dbonmk){
             $tmp   = explode('-', $dbonmk);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $datebonmk = $year.'-'.$month.'-'.$day;
        }
        $isjkp          = $this->input->post('isjkp', TRUE);
        $ipartner       = $this->input->post('ipartner', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE); 

        $i_material     = $this->input->post('i_material[]', TRUE);
        $n_qtyawal      = $this->input->post('n_qtyawal[]', TRUE);
        $n_qtyout       = $this->input->post('n_qtyout[]', TRUE);
        $n_quantity     = $this->input->post('n_quantity[]', TRUE);
        $i_satuan       = $this->input->post('i_satuan[]', TRUE);
        $e_desc         = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin(); 
        $nokonversi     = $this->mmaster->runningnumbernokonversi($yearmonth, $ilokasi);
        $this->mmaster->insertheader($nokonversi, $istore, $datekonversi, $isjkp, $ipartner, $eremark, $datebonmk);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nokonversi);

             $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial  = $imaterial;
                $nqtyawal   = $n_qtyawal[$no];
                $nqtyout    = $n_qtyout[$no];
                $nquantity  = $n_quantity[$no];
                $isatuan    = $i_satuan[$no];
                $edesc      = $e_desc[$no];

                $this->mmaster->insertdetail($nokonversi, $imaterial, $nqtyawal, $nqtyout , $nquantity, $isatuan, $no, $edesc);

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
                    'kode'      => $nokonversi,
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

        $nokonversi    = $this->uri->segment('4');
        $ipartner      = $this->uri->segment('5');
        $ireferensi    = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'head'       => $this->mmaster->get_konversi($nokonversi)->row(),
            'datadetail' => $this->mmaster->get_konversidetail($nokonversi)->result(),
            'gudang'     => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'    => $this->mmaster->bacapartner($ireferensi),
            'referensi'  => $this->mmaster->bacareferensi($ipartner),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $nokonversi     = $this->input->post('nokonversi', TRUE);
        $istore         = $this->input->post('istore', TRUE);
        $dkonversi      = $this->input->post('dkonversi', TRUE);
        if($dkonversi){
             $tmp   = explode('-', $dkonversi);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $datekonversi = $year.'-'.$month.'-'.$day;
        }

        $isjkp          = $this->input->post('isjkp', TRUE);
        $dreferensi     = $this->input->post('dreferensi', TRUE);
        if($dreferensi){
             $tmp   = explode('-', $dreferensi);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $datereferensi = $year.'-'.$month.'-'.$day;
        }
        $ipartner       = $this->input->post('ipartner', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE); 

        $i_material     = $this->input->post('imaterial[]', TRUE);
        $n_qtyawal      = $this->input->post('nqtyawal[]', TRUE);
        $n_qtyout       = $this->input->post('nqtyout[]', TRUE);
        $n_quantity     = $this->input->post('nquantity[]', TRUE);
        $i_satuan       = $this->input->post('isatuan[]', TRUE);
        $e_desc         = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin(); 
        $this->mmaster->updateheader($nokonversi, $istore, $datekonversi, $isjkp, $ipartner, $eremark, $datereferensi);
        $this->mmaster->deletedetail($nokonversi);
       
            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial  = $imaterial;
                $nqtyawal   = $n_qtyawal[$no];
                $nqtyout    = $n_qtyout[$no];
                $nquantity  = $n_quantity[$no];
                $isatuan    = $i_satuan[$no];
                $edesc      = $e_desc[$no];

                $this->mmaster->insertdetail($nokonversi, $imaterial, $nqtyawal, $nqtyout, $nquantity, $isatuan, $no, $edesc);

                $no++;
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                 $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nokonversi);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nokonversi,
                );
        }
    $this->load->view('pesan', $data);  
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $nokonversi = $this->input->post('nokonversi');
        $this->mmaster->sendd($nokonversi);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $nokonversi = $this->input->post('nokonversi');
        $this->mmaster->cancel_approve($nokonversi);
    }

    public function view(){

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $nokonversi    = $this->uri->segment('4');
        $ipartner      = $this->uri->segment('5');
        $ireferensi    = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'head'       => $this->mmaster->get_konversi($nokonversi)->row(),
            'datadetail' => $this->mmaster->get_konversidetail($nokonversi)->result(),
            'gudang'     => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'    => $this->mmaster->bacapartner($ireferensi),
            'referensi'  => $this->mmaster->bacareferensi($ipartner),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
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

        $nokonversi    = $this->uri->segment('4');
        $ipartner      = $this->uri->segment('5');
        $ireferensi    = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'head'       => $this->mmaster->get_konversi($nokonversi)->row(),
            'datadetail' => $this->mmaster->get_konversidetail($nokonversi)->result(),
            'gudang'     => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            'partner'    => $this->mmaster->bacapartner($ireferensi),
            'referensi'  => $this->mmaster->bacareferensi($ipartner),
            );
    
            $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $nokonversi = $this->input->post('nokonversi');
        $isjkp      = $this->input->post('isjkp');

        $this->mmaster->approve($nokonversi, $isjkp);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $nokonversi
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function change(){
        header("Content-Type: application/json", true);
        $nokonversi = $this->input->post('nokonversi');
        $this->mmaster->change_approve($nokonversi);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $nokonversi = $this->input->post('nokonversi');
        $this->mmaster->reject_approve($nokonversi);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $nokonversi = $this->input->post('nokonversi', true);
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($nokonversi);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Konversi Pinjaman Bahan Baku' . $nokonversi);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */