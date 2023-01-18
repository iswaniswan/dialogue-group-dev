<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2051205';
   
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    function data(){
        $dfrom      = $this->uri->segment('4');
        $dto        = $this->uri->segment('5');

        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$dd.'-'.$mm.'-'.$yy;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$dd.'-'.$mm.'-'.$yy;

        echo $this->mmaster->data($this->i_menu, $from, $to);
    }

    public function list(){
        $dfrom      = $this->input->post('dfrom', TRUE);       
        $dto        = $this->input->post('dto', TRUE);       

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );
        $this->Logger->write('Membuka Menu List '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function schedule(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_spbb");
            // $this->db->like("UPPER(i_schedule)", $cari);
            // $this->db->or_like("UPPER(i_schedule)", $cari);
            $this->db->where("f_spbb_cancel",'f');
            // $this->db->where("i_schedule isnull");
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_spbb,  
                    'text' => $iproduct->i_spbb.' - '.$iproduct->d_spbb
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getschedule2(){
        header("Content-Type: application/json", true);
        $ischedule = $this->input->post('ischedule');
        $this->db->select("d_schedule, i_spbb");
        $this->db->from("tm_schedule");
        $this->db->where("i_schedule", $ischedule);
        $this->db->where ("f_schedule_cancel",'f');
        // $this->db->where ("f_status_complete",'f');
        $this->db->where("i_spbb isnull");
        $data = $this->db->get();

        // $query   = $this->db->query("sselect * from tm_schedule_item a 
        //                     join tm_schedule b on a.i_schedule = b.i_schedule 
        //                     where a.i_schedule = '$ischedule' and b.f_schedule_cancel = 'f'");

        $dataa = array(
            'data' => $data->result_array(),
            // 'jmlitem' => $query->num_rows(),
            'brg'   => $this->mmaster->bacadetail($ischedule)->result_array()
        );
        echo json_encode($dataa);
    }
    public function getschedule(){
        header("Content-Type: application/json", true);
        $ispbb = $this->input->post('ischedule');
        // $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->getspbb($ispbb)->row(),
            'detail' => $this->mmaster->getspbb_detail($ispbb)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    function gudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_master_gudang");
            $this->db->where ("i_kode_jenis",'JNG0001');
            // $this->db->like("UPPER(i_kode_master)", $cari);
            // $this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_kode_master,  
                    'text' => $iproduct->i_kode_master.' - '.$iproduct->e_nama_master
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'], 
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibonk  = $this->input->post('ibonk', TRUE);
        $dbonk  = $this->input->post('dbonk', TRUE);
        if($dbonk){
             $tmp   = explode('-', $dbonk);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $thbl = $year.$month;
             $datebonk = $year.'-'.$month.'-'.$day;
        }
        $ischedule    = $this->input->post('ischedule', TRUE);
        $isched    = $this->input->post('isched', TRUE);
        $dschedule    = $this->input->post('dschedule', TRUE); 
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $igudang      = $this->input->post('igudang', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        $this->db->trans_begin();
        $ibonk= $this->mmaster->runningnumberbonk($thbl);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
        $this->mmaster->insertheader($ibonk, $datebonk, $eremarkh, $igudang);

        for($i=1;$i<=$jml;$i++){ 
        //    if($this->input->post('cek'.$i)=='cek'){                  
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $imaterial         = $this->input->post('imaterial'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $icolor           = $this->input->post('icolor'.$i, TRUE);
                $ecolorname       = $this->input->post('warna'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                $npemenuhan       = $this->input->post('npemenuhan'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $nitemno          = $i;

                $this->mmaster->insertbonkdetail($iproduct,$eproductname,$icolor,$npemenuhan,$eremark,$ibonk,$ischedule,$nitemno, $isched);
                $this->mmaster->insertbonkdetailitem($iproduct,$icolor,$npemenuhan,$ibonk,$ischedule,$nitemno, $imaterial, $nquantity);
                $this->mmaster->updatescheduledetail($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk);
                $this->mmaster->updateheaderschedule($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk);
            // }
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
                'kode'      => $ibonk,
            );
        }
    $this->load->view('pesan', $data);      
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonk = $this->uri->segment('4');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($ibonk)->row(),
            'dataschedule'      => $this->mmaster->cek_schedule($ibonk)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ibonk)->result(),           
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $ibonk  = $this->input->post('ibonk', TRUE);
        $dbonk  = $this->input->post('dbonk', TRUE);
        $ischedule    = $this->input->post('ischedule', TRUE);
        $dschedule    = $this->input->post('dschedule', TRUE); 
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $igudang      = $this->input->post('igudang', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
        $this->mmaster->updateheader($ibonk,$dbonk,$ischedule,$igudang,$eremarkh);

        for($i=0;$i<=$jml;$i++){ 
            if($this->input->post('cek'.$i)=='cek'){                  
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $icolor           = $this->input->post('icolor'.$i, TRUE);
                $ecolorname       = $this->input->post('warna'.$i, TRUE);
                $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                $npemenuhan       = $this->input->post('npemenuhan'.$i, TRUE);
                $nsaldo           = $this->input->post('nsaldo'.$i, TRUE);
                $fitemcancel      = $this->input->post('fitemcancel'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $nitemno          = $i;

                $this->mmaster->deletedetail($ibonk,$iproduct,$ischedule,$icolor);
                $this->mmaster->insertdetail2($ischedule,$iproduct,$icolor,$eproductname,$nquantity,$npemenuhan,$nsaldo,$eremark,$ibonk,$fitemcancel,$nitemno);
                $this->mmaster->updatesaldo($ibonk,$dbonk,$ischedule,$iproduct,$icolor);
            }
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
                    'kode'      => $ischedule,
                );
        }
        $this->load->view('pesan', $data); 
        
    }

    public function view(){

        $ibonk= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],            
            'data'              => $this->mmaster->cek_data($ibonk)->row(),
            'dataschedule'      => $this->mmaster->cek_schedule($ibonk)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ibonk)->result(),  
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */