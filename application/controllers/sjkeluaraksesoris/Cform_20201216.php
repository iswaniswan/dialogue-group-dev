<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050307';
   
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

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        // $dfrom      = $this->uri->segment('4');
        // $dto        = $this->uri->segment('5');

        // $tmp=explode('-',$dfrom);
        // $dd=$tmp[0];
        // $mm=$tmp[1];
        // $yy=$tmp[2];
        // $from=$dd.'-'.$mm.'-'.$yy;

        // $tmp=explode('-',$dto);
        // $dd=$tmp[0];
        // $mm=$tmp[1];
        // $yy=$tmp[2];
        // $to=$dd.'-'.$mm.'-'.$yy;

        echo $this->mmaster->data($this->i_menu);
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
            $this->db->from("tm_opaksesoris");
            // $this->db->like("UPPER(i_schedule)", $cari);
            // $this->db->or_like("UPPER(i_schedule)", $cari);
            // $this->db->where("f_op_cancel",'f');
            // $this->db->where("f_op_close",'f');
            // $this->db->where("i_schedule isnull");
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_op_code,  
                    'text' => $iproduct->i_op_code//.' - '.$iproduct->i_customer
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    // function getschedule2(){
    //     header("Content-Type: application/json", true);
    //     $ischedule = $this->input->post('ischedule');
    //     $this->db->select("d_schedule, i_spbb");
    //     $this->db->from("tm_schedule");
    //     $this->db->where("i_schedule", $ischedule);
    //     $this->db->where ("f_schedule_cancel",'f');
    //     // $this->db->where ("f_status_complete",'f');
    //     $this->db->where("i_spbb isnull");
    //     $data = $this->db->get();

    //     // $query   = $this->db->query("sselect * from tm_schedule_item a 
    //     //                     join tm_schedule b on a.i_schedule = b.i_schedule 
    //     //                     where a.i_schedule = '$ischedule' and b.f_schedule_cancel = 'f'");

    //     $dataa = array(
    //         'data' => $data->result_array(),
    //         // 'jmlitem' => $query->num_rows(),
    //         'brg'   => $this->mmaster->bacadetail($ischedule)->result_array()
    //     );
    //     echo json_encode($dataa);
    // }

    public function getmemo(){
        header("Content-Type: application/json", true);
        $imemo = $this->input->post('imemo');
        // $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->getmemo($imemo)->row(),
            'detail' => $this->mmaster->getmemo_detail($imemo)->result_array()
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
            'memo'              => $this->mmaster->bacamemo()->result(),
            'gudang'            => $this->mmaster->bacagudang()->result(),
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $imemo  = $this->input->post('imemo', TRUE);
        $dmemo  = $this->input->post('dmemo', TRUE);
        $eremark  = $this->input->post('eremark', TRUE);
        $datesj    = $this->input->post('dsj', TRUE);
        if($datesj){
             $tmp   = explode('-', $datesj);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $thbl = $year.$month;
             $dsj = $year.'-'.$month.'-'.$day;
        }
        $icustomer    = $this->input->post('icustomer', TRUE); 
        // $eremarkh     = $this->input->post('eremarkh', TRUE);
        $ikodemaster  = $this->input->post('ikodemaster', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        $this->db->trans_begin();
        $isj= $this->mmaster->runningnumberbonk($thbl);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
        $this->mmaster->insertheader($isj, $icustomer, $dsj, $ikodemaster, $imemo, $dmemo,$eremark);

        for($i=1;$i<=$jml;$i++){ 
        //    if($this->input->post('cek'.$i)=='cek'){                  
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);
                $qty                = $this->input->post('npemenuhan'.$i, TRUE);
                $remark             = $this->input->post('eremark'.$i, TRUE);
                $qtydeliver         = $this->input->post('qtydeliver'.$i, TRUE);
                $isatuan            = $this->input->post('isatuan'.$i, TRUE);
                $qtydelivery =$qtydeliver + $qty;
                if($qtydeliver<$qtydelivery){
                    $this->mmaster->insertdetail($isj, $iproduct, $i, $qty, $remark,$isatuan);
                    $this->mmaster->updatememo($imemo, $iproduct, $qtydelivery);
                }
                // $this->mmaster->insertbonkdetailitem($iproduct,$icolor,$npemenuhan,$ibonk,$ischedule,$nitemno, $imaterial, $nquantity);
                // $this->mmaster->updatescheduledetail($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk);
                // $this->mmaster->updateheaderschedule($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk);
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
                'kode'      => $isj,
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
            // 'dataschedule'      => $this->mmaster->cek_schedule($ibonk)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ibonk)->result(),
            'datadetail2'        => $this->mmaster->cek_datadetail2($ibonk)->result(),           
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
        $isj  = $this->input->post('isj', TRUE);
        $dsj  = $this->input->post('dsj', TRUE);
        $imemo  = $this->input->post('imemo', TRUE);
        $dmemo  = $this->input->post('dmemo', TRUE); 
        $eremarkh     = $this->input->post('eremark', TRUE);
        // $igudang      = $this->input->post('igudang', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
        $this->mmaster->updateheader($isj, $dsj, $eremarkh);

        for($i=0;$i<=$jml;$i++){ 
            // if($this->input->post('cek'.$i)=='cek'){                  
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $isatuan          = $this->input->post('isatuan'.$i, TRUE);
                $nquantity        = $this->input->post('npemenuhan'.$i, TRUE);
                $npemenuhan       = $this->input->post('nquantityz'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $nitemno          = $i;
                
                $this->mmaster->updatedetail($isj, $iproduct, $nquantity);
                $this->mmaster->updatedetailop($isj, $iproduct, $nquantity);
                // $this->mmaster->insertdetail2($ischedule,$iproduct,$icolor,$eproductname,$nquantity,$npemenuhan,$nsaldo,$eremark,$ibonk,$fitemcancel,$nitemno);
                // $this->mmaster->updatesaldo($ibonk,$dbonk,$ischedule,$iproduct,$icolor);
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
                    'kode'      => $isj,
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