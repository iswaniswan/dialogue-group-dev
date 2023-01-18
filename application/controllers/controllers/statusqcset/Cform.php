<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090206';
   
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

    function product(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct (i_product), b.e_product_namewip, a.i_color , c.e_color_name");
            $this->db->from("tt_stok_opname_qcset_detail a");
            $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
            $this->db->join("tr_color c",  "a.i_color = c.i_color");
            $this->db->like("UPPER(a.i_product)", $cari);
            $this->db->or_like("UPPER(b.e_product_namewip)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_product, 
                    'name' => $iproduct->e_product_namewip, 
                    'text' => $iproduct->i_product.' - '.$iproduct->e_product_namewip
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $iproduct = $this->uri->segment(4);
            $this->db->select("distinct (a.i_product), a.i_material ,d.e_material_name, a.i_color, b.e_product_namewip, d.e_material_name, c.e_color_name");
            $this->db->from("tt_stok_opname_qcset_detail a");
            $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
            $this->db->join("tr_color c",  "a.i_color = c.i_color");
            $this->db->join("tr_material d",  "a.i_material= d.i_material");
            $this->db->where("UPPER(i_product)", $iproduct);
            $this->db->order_by('a.i_product', 'ASC');          
            $data = $this->db->get();
            foreach($data->result() as  $imaterial){
                    $filter[] = array(
                    'id' => $imaterial->i_material, 
                    'name' => $imaterial->e_material_name, 
                    'text' => $imaterial->i_material.' - '.$imaterial->e_material_name
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $this->db->select("distinct (a.i_product), b.e_product_namewip, a.i_color , c.e_color_name, a.i_material ,d.e_material_name");
            $this->db->from("tt_stok_opname_qcset_detail a");
            $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
            $this->db->join("tr_color c",  "a.i_color = c.i_color");
            $this->db->join("tr_material d",  "a.i_material= d.i_material");
            $this->db->where("UPPER(i_product)", $iproduct);
            $this->db->order_by('a.i_product', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerialname = $this->input->post('ematerialname');
        $this->db->select("i_material,e_material_name");
        $this->db->from("tr_material");
        $this->db->where("UPPER(i_material)", $ematerialname);
        $this->db->order_by('i_material', 'ASC');            
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }


    function schedule(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_schedule");
            // $this->db->like("UPPER(i_schedule)", $cari);
            // $this->db->or_like("UPPER(i_schedule)", $cari);
            $this->db->where("f_schedule_cancel",'f');
            $this->db->where("i_spbb isnull");
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id' => $iproduct->i_schedule,  
                    'text' => $iproduct->i_schedule.' - '.$iproduct->d_schedule
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getschedule(){
        header("Content-Type: application/json", true);
        $ischedule = $this->input->post('ischedule');
        $this->db->select("d_schedule, i_spbb");
        $this->db->from("tm_schedule a");
        $this->db->where("UPPER(i_schedule)", $ischedule);
        $this->db->where ("f_schedule_cancel",'f');
        $this->db->where("i_spbb isnull");
        $data = $this->db->get();

        // $query   = $this->db->query("select * from tm_schedule where i_schedule = '$ischedule' and f_schedule_cancel = 'f' and i_spbb isnull");
        $query2   = $this->db->query("select * from tm_schedule where i_schedule = '$ischedule' and f_schedule_cancel = 'f' and i_spbb isnull");
        $jmlitem = $query2->num_rows();
        $dataa = array(
            'data' => $data->result_array(),
            'jmlitem' => $query2->num_rows(),
            'brgop'   => $this->mmaster->bacadetail($ischedule)->result_array()
        );
        echo json_encode($dataa);
    }

    function gudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_master_gudang");
            $this->db->like("UPPER(i_kode_master)", $cari);
            $this->db->or_like("UPPER(e_nama_master)", $cari);
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
       
        $ispbb  = $this->input->post('ispbb', TRUE);
        $dspbb  = $this->input->post('dspbb', TRUE);
        if($dspbb){
             $tmp   = explode('-', $dspbb);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $thbl = $year.$month;
             $datespbb = $year.'-'.$month.'-'.$day;
        }
        // var_dump($datespbb);
        $ischedule    = $this->input->post('ischedule', TRUE);
        $dschedule    = $this->input->post('dschedule', TRUE); 
        // if($dschedule){
        //      $tmp   = explode('-', $dschedule);
        //      $day   = $tmp[0];
        //      $month = $tmp[1];
        //      $year  = $tmp[2];
        //      $yearmonth = $year.$month;
        //      $dateschedule = $year.'-'.$month.'-'.$day;
        // }
        // var_dump($dateschedule);
        // var_dump($datespbb);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $igudang      = $this->input->post('igudang', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        $this->db->trans_begin();
        $ispbb= $this->mmaster->runningnumberispbb($thbl);
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ispbb);
        $this->mmaster->insertheader($ispbb, $datespbb, $ischedule, $dschedule, $eremarkh, $igudang);       

        for($i=0;$i<$jml;$i++){                 
            $iproduct           = $this->input->post('iproduct'.$i, TRUE);
            // var_dump($iproduct);
            $eproductname       = $this->input->post('eproductname'.$i, TRUE);
            $icolor             = $this->input->post('icolor'.$i, TRUE);
            $ecolorname         = $this->input->post('warna'.$i, TRUE);
            $imaterial          = $this->input->post('imaterial'.$i, TRUE);
            // var_dump($imaterial);
            $ematerialname      = $this->input->post('ematerial'.$i, TRUE);
            $nquantity          = $this->input->post('nquantity'.$i, TRUE);
            $vset               = $this->input->post('vset'.$i, TRUE);
            $vgelar             = $this->input->post('vgelar'.$i, TRUE);
            $jumgelar           = $this->input->post('jumgelar'.$i, TRUE);
            $pjgkain            = $this->input->post('pjgkain'.$i, TRUE);
            $fbisbisan          = $this->input->post('fbisbisan'.$i,TRUE);
            $nitemno            = $i;
            $this->mmaster->insertdetail($iproduct,$eproductname,$icolor, $imaterial,$ematerialname, $nquantity, 
            $vset, $vgelar, $jumgelar, $pjgkain, $fbisbisan, $nitemno, $ispbb, $datespbb, $ischedule, $dschedule);
            $this->mmaster->updateheaderschedule($ischedule,$ispbb,$datespbb,$igudang);
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
                'kode'      => $ispbb,
            );
        }
    $this->load->view('pesan', $data); 
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispbb = $this->uri->segment('4');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($ispbb)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ispbb)->result(),           
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ispbb        = $this->input->post('ispbb', TRUE);
        $dspbb        = $this->input->post('dspbb', TRUE);
        $ischedule    = $this->input->post('ischedule', TRUE);
        $dschedule    = $this->input->post('dschedule', TRUE); 
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $igudang      = $this->input->post('igudang', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ispbb);
        $this->mmaster->updateheader($ispbb,$dspbb,$igudang,$eremarkh);
        $this->mmaster->updateheadersch($ischedule,$ispbb,$dspbb,$igudang);       

        for($i=1;$i<=$jml;$i++){                  
            $iproduct           = $this->input->post('iproduct'.$i, TRUE);
            $eproductname       = $this->input->post('eproductname'.$i, TRUE);
            $icolor             = $this->input->post('icolor'.$i, TRUE);
            //$ecolorname         = $this->input->post('warna'.$i, TRUE);
            $imaterial          = $this->input->post('imaterial'.$i, TRUE);
            $ematerialname      = $this->input->post('ematerial'.$i, TRUE);
            $nquantity          = $this->input->post('nquantity'.$i, TRUE);
            $vset               = $this->input->post('vset'.$i, TRUE);
            $vgelar             = $this->input->post('vgelar'.$i, TRUE);
            $jumgelar           = $this->input->post('jumgelar'.$i, TRUE);
            $pjgkain            = $this->input->post('pjgkain'.$i, TRUE);
            $fbisbisan          = $this->input->post('fbisbisan'.$i,TRUE);
            $nitemno            = $i;
            
            $this->mmaster->deletedetail($ispbb,$iproduct,$icolor,$imaterial);
            $this->mmaster->detailup($iproduct,$eproductname,$icolor,$imaterial,$ematerialname,$nquantity,$vset,$vgelar,$jumgelar,$pjgkain,$nitemno,$ischedule,$dschedule,$ispbb,$dspbb,$fbisbisan);
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

        $ispbb= $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($ispbb)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ispbb)->result(), 
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}

/* End of file Cform.php */