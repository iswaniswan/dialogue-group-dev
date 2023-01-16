<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050408';

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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'memo'          => $this->mmaster->bacamemo()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang =  $this->uri->segment(4);
            $data = $this->mmaster->product($cari,$gudang);
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id'    => $material->i_material,  
                    'name'  => $material->e_material_name,  
                    'text'  => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerialname = $this->input->post('ematerialname');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->where("UPPER(i_material)", $ematerialname);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function customer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_customer");
            $this->db->like("UPPER(i_customer)", $cari);
            $this->db->or_like("UPPER(e_customer_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_customer,  
                    'text' => $icolor->e_customer_name,
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
        
        $dsjk   = $this->input->post("dsjk",true);
        if($dsjk){
                 $tmp   = explode('-', $dsjk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datesjk = $year.'-'.$month.'-'.$day;
        }

        $dmemo   = $this->input->post("dmemo",true);
        if($dmemo){
                 $tmp   = explode('-', $dmemo);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datememo = $year.'-'.$month.'-'.$day;
        }

        $istore        = $this->input->post('istore', TRUE);
        $imemo         = $this->input->post('imemo', TRUE);
        $icustomer     = $this->input->post('icustomer', TRUE);
        $remark        = $this->input->post('eremark', TRUE);
        $nosjkeluar    = $this->mmaster->runningnumberkeluar($yearmonth, $istore);
        $jml           = $this->input->post('jml', TRUE); 
       
        $i_material      = $this->input->post('imaterial[]', TRUE);
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $this->mmaster->insertheader($nosjkeluar, $imemo, $datesjk, $datememo, $icustomer, $istore, $remark);

            $no=0;
            foreach ($i_material as $imaterial) {
                $imaterial  = $imaterial;
                $nquantity  = $n_quantity[$no];
                $isatuan    = $i_satuan[$no];
                $edesc      = $e_desc[$no];
                $this->mmaster->insertdetail($nosjkeluar, $imaterial, $nquantity, $isatuan, $edesc, $no);

                $no++;
            // for($i=1;$i<=$jml;$i++){
            // }
            }
            if ($this->db->trans_status() === FALSE)
            {
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'customer'      => $this->mmaster->bacacustomer(),
            'head'          => $this->mmaster->baca_header($isj)->row(),
            'detail'        => $this->mmaster->baca_detail($isj)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $nosjkeluar   = $this->input->post("isj",true);
        $dsjk   = $this->input->post("dsjk",true);
        if($dsjk){
                 $tmp   = explode('-', $dsjk);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $yearmonth = $year.$month;
                 $datesjk = $year.'-'.$month.'-'.$day;
        }

        $dmemo   = $this->input->post("dmemo",true);
        if($dmemo){
                 $tmp   = explode('-', $dmemo);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $yearmonth = $year.$month;
                 $datememo = $year.'-'.$month.'-'.$day;
        }

        $istore        = $this->input->post('istore', TRUE);
        $imemo         = $this->input->post('imemo', TRUE);
        $icustomer     = $this->input->post('icustomer', TRUE);
        $remark        = $this->input->post('eremark', TRUE);
        $jml           = $this->input->post('jml', TRUE); 
       
        $i_material      = $this->input->post('imaterial[]', TRUE);
        $n_quantity      = $this->input->post('nquantity[]', TRUE);
        $i_satuan        = $this->input->post('isatuan[]', TRUE); 
        $e_desc          = $this->input->post('edesc[]', TRUE);
        //var_dump($nosjkeluar);
        $this->db->trans_begin();
        $this->mmaster->updateheader($nosjkeluar, $datesjk, $istore, $imemo, $datememo, $icustomer, $remark);
        $this->mmaster->deletedetail($nosjkeluar);

            $no=0;
            foreach ($i_material as $imaterial) {
                $imaterial  = $imaterial;
                $nquantity  = $n_quantity[$no];
                $isatuan    = $i_satuan[$no];
                $edesc      = $e_desc[$no];

                $this->mmaster->insertdetail($nosjkeluar, $imaterial, $nquantity, $isatuan, $edesc, $no);

                $no++;
            }
            //for($i=1;$i<=$jml;$i++){
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' No SJ : '.$nosjkeluar.' Gudang :'.$istore);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nosjkeluar,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $sj   = $this->input->post('sj');
        $gudang  = $this->input->post('gudang');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($sj, $gudang);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel SJ Keluar Makloon '.$sj.' Gudang:'.$gudang);
            echo json_encode($data);
        }
    }

     public function view(){

        $isj = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'customer'      => $this->mmaster->bacacustomer(),
            'head'          => $this->mmaster->baca_header($isj)->row(),
            'detail'        => $this->mmaster->baca_detail($isj)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */