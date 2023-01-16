<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050102';

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
        $d = new DateTime();

        $one_year = new DateInterval('P1M');
        $one_year_ago = new DateTime();
        $one_year_ago->sub($one_year); 

        $akhir = $d->format('d-m-Y');
        $awal  = $one_year_ago->format('d-m-Y');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $awal,
            'dto'       => $akhir
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $dfrom  = $this->uri->segment(4);
        $dto    = $this->uri->segment(5);
		echo $this->mmaster->data($dfrom,$dto,$this->i_menu,$this->global['folder']);
    }

    public function getbonmk(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang = $this->input->get('gudang', FALSE);
            $data = $this->mmaster->bonmk($cari,$gudang);
            foreach($data->result() as  $bonmk){       
                $filter[] = array(
                    'id' => $bonmk->i_bonk,  
                    'text' => $bonmk->i_bonk.' || '.$bonmk->d_bonk
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    } 

    public function getdetailbonmk(){
        header("Content-Type: application/json", true);
        $ibonmk  = $this->input->post('ibonmk', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head'      => $this->mmaster->getbonmk($ibonmk, $gudang)->row(),
            'detail'    => $this->mmaster->getbonmk_detail($ibonmk, $gudang)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bagianpembuat' => $this->mmaster->bagianpembuat()->result(),
            'dfrom'         => $dfrom,
            'dto'           => $dto
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $username = $this->session->userdata('username');
        $query = $this->db->query("SELECT i_kode_lokasi FROM public.tm_user_deprole WHERE username='$username'");
        foreach($query->result() as $row){
            $isubbagian = $row->i_kode_lokasi;
        }
        $dbonm = $this->input->post("dbonm",true);
        if($dbonm){
                 $tmp   = explode('-', $dbonm);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datebonm = $year.'-'.$month.'-'.$day;
        }

        $istore         = $this->input->post('istore', TRUE);
        $ireferensi     = $this->input->post('ibonmk', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $ibonm          = $this->mmaster->runningnumber($yearmonth, $isubbagian);
        $jml            = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
        $this->mmaster->insertheader($ibonm, $datebonm, $istore, $ireferensi, $eremark);

            for($i=1;$i<=$jml;$i++){
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);
                $icolor             = $this->input->post('icolor'.$i, TRUE); 
                $nquantitykeluar    = $this->input->post('nquantitykeluar'.$i, TRUE); 
                $nquantitymasuk     = $this->input->post('nquantitymasuk'.$i, TRUE); 
                $edesc              = $this->input->post('edesc'.$i, TRUE);
                $nitemno            = $i;   

                $this->mmaster->insertdetail($ibonm, $iproduct, $icolor, $nquantitykeluar, $nquantitymasuk, $edesc, $nitemno);              
            }

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonm);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibonm,
                );
        }
        $this->load->view('pesan', $data);      
    
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonm = $this->uri->segment(4);
        $dfrom = $this->uri->segment(5);
        $dto   = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->baca_header($ibonm)->row(),
            'datadetail' => $this->mmaster->baca_detail($ibonm)->result(),
            'kodemaster' => $this->mmaster->cek_gudang()->result(),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'ibonm'      => $ibonm
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibonm          = $this->input->post('ibonm', TRUE);
        $dbonm          = $this->input->post("dbonm",true);
        if($dbonm){
                 $tmp   = explode('-', $dbonm);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datebonm = $year.'-'.$month.'-'.$day;
        }

        $istore         = $this->input->post('ikodemaster', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);        
        $jml            = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
        $this->mmaster->updateheader($ibonm, $datebonm, $istore, $ireferensi, $eremark);
        $this->mmaster->deletedetail($ibonm);

            for($i=1;$i<=$jml;$i++){
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);
                $icolor             = $this->input->post('icolor'.$i, TRUE); 
                $nquantitykeluar    = $this->input->post('nquantitykeluar'.$i, TRUE); 
                $nquantitymasuk     = $this->input->post('nquantitymasuk'.$i, TRUE); 
                $edesc              = $this->input->post('edesc'.$i, TRUE);
                $nitemno            = $i;   

                $this->mmaster->insertdetail($ibonm, $iproduct, $icolor, $nquantitykeluar, $nquantitymasuk, $edesc, $nitemno);
            }
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ibonm);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ibonm,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function view(){

        $ibonm  = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->baca_header($ibonm)->row(),
            'datadetail' => $this->mmaster->baca_detail($ibonm)->result(),
            'kodemaster' => $this->mmaster->cek_gudang()->result(),
            'dto'        => $dto,
            'dfrom'      => $dfrom,
            'ibonm'      => $ibonm
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function approval() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonm  = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->baca_header($ibonm)->row(),
            'datadetail' => $this->mmaster->baca_detail($ibonm)->result(),
            'kodemaster' => $this->mmaster->cek_gudang()->result(),
            'dto'        => $dto,
            'dfrom'      => $dfrom,
            'ibonm'      => $ibonm
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibonm          = $this->input->post('ibonm', TRUE);
        $dbonm          = $this->input->post("dbonm",true);
        if($dbonm){
                 $tmp   = explode('-', $dbonm);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datebonm = $year.'-'.$month.'-'.$day;
        }

        $istore         = $this->input->post('ikodemaster', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);        
        $jml            = $this->input->post('jml', TRUE); 
        $ikodelokasi    = substr($ibonm,5,2);
        $iproductgrade  = 'A';

        $this->db->trans_begin();
        $this->mmaster->approve($ibonm, $datebonm, $istore, $ireferensi, $eremark);
        $this->mmaster->deletedetail($ibonm);
            for($i=1;$i<=$jml;$i++){
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);
                $icolor             = $this->input->post('icolor'.$i, TRUE); 
                $nquantitykeluar    = $this->input->post('nquantitykeluar'.$i, TRUE); 
                $nquantitymasuk     = $this->input->post('nquantitymasuk'.$i, TRUE); 
                $edesc              = $this->input->post('edesc'.$i, TRUE);
                $nitemno            = $i;   
                $this->mmaster->insertdetail($ibonm, $iproduct, $icolor, $nquantitykeluar, $nquantitymasuk, $edesc, $nitemno);
                $cekic = $this->mmaster->cekic($iproduct, $icolor);
                if(!$cekic){
                    $this->mmaster->insertic($iproduct, $ikodelokasi, $nquantitymasuk);
                    $nqty_in    = $nquantitymasuk;
                    $nqty_out   = 0;
                    $nqty_akhir = 0+$nquantitymasuk;
                    $nqty_awal  = 0;
                }else{
                    $this->mmaster->updateic($iproduct, $ikodelokasi, $nquantitymasuk, $cekic->n_quantity_stock);
                    $nqty_in    = $nquantitymasuk;
                    $nqty_out   = 0;
                    $nqty_akhir = $cekic->n_quantity_stock + $nquantitymasuk;
                    $nqty_awal  = $cekic->n_quantity_stock;
                }
                $this->mmaster->insertictrans($iproduct, $iproductgrade, $ikodelokasi, $ibonm, $nqty_in, $nqty_out, $nqty_akhir, $nqty_awal, $i);
            }
            
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Approve Data '.$this->global['title'].' Kode : '.$ibonm);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ibonm,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->change($kode);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->reject($kode);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibonm   = $this->input->post('ibonm');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ibonm);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Penerimaan Gudang Jadi '.$ibonm);
            echo json_encode($data);
        }
    }

}
/* End of file Cform.php */
