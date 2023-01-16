<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050409';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'], 
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
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function customer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct (a.i_supplier), a.e_supplier_name");
            $this->db->from("tr_supplier a");
            $this->db->join("tm_bonmkeluar_pinjamanbpplastik b", "a.i_supplier = b.department");
            $this->db->like("UPPER(a.i_supplier)", $cari);
            $this->db->or_like("UPPER(a.e_supplier_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_supplier,  
                    'text' => $icolor->e_supplier_name,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getsjkp(){
        //var_dump($gudang);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang = $this->input->get('gudang', FALSE);
            $customer = $this->input->get('customer', FALSE);
            $data = $this->mmaster->sjkp($cari,$gudang, $customer);
            foreach($data->result() as  $sjkp){       
                $filter[] = array(
                    'id' => $sjkp->i_bonmk,  
                    'text' => $sjkp->i_bonmk.' || '.$sjkp->e_supplier_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function getdetailsjkp(){
        header("Content-Type: application/json", true);
        $isjkp      = $this->input->post('isjkp', FALSE);
        $gudang     = $this->input->post('gudang', FALSE);
        $customer   = $this->input->post('customer', FALSE);
        $query  = array(
            'head'   => $this->mmaster->getsjkp($isjkp, $gudang, $customer)->row(),
            'detail' => $this->mmaster->getsjkp_detail($isjkp, $gudang)->result_array()
        );
        echo json_encode($query);  
    } 

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

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
        $icustomer      = $this->input->post('icustomer', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE); 

        $i_material     = $this->input->post('i_material[]', TRUE);
        $n_qtyawal      = $this->input->post('n_qtyawal[]', TRUE);
        $n_qtyout       = $this->input->post('n_qtyout[]', TRUE);
        $n_quantity     = $this->input->post('n_quantity[]', TRUE);
        $i_satuan       = $this->input->post('i_satuan[]', TRUE);
        $e_desc         = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin(); 
        $nokonversi     = $this->mmaster->runningnumbernokonversi($yearmonth, $istore);
        $this->mmaster->insertheader($nokonversi, $istore, $datekonversi, $isjkp, $icustomer, $eremark);
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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $nokonversi    = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'head'       => $this->mmaster->getforecast($nokonversi)->row(),
            'datadetail' => $this->mmaster->getforecastdetail($nokonversi)->result(),
            'gudang'     => $this->mmaster->bacagudang(),
            'customer'   => $this->mmaster->bacacustomer(),
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
             $day   = $tmp[2];
             $month = $tmp[1];
             $year  = $tmp[0];
             $yearmonth = $year.$month;
             $datekonversi = $year.'-'.$month.'-'.$day;
        }

        $isjkp          = $this->input->post('isjkp', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE); 

        $i_material     = $this->input->post('imaterial[]', TRUE);
        $n_qtyawal      = $this->input->post('nqtyawal[]', TRUE);
        $n_qtyout       = $this->input->post('nqtyout[]', TRUE);
        $n_quantity     = $this->input->post('nquantity[]', TRUE);
        $i_satuan       = $this->input->post('isatuan[]', TRUE);
        $e_desc         = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin(); 
        $this->mmaster->updateheader($nokonversi, $istore, $datekonversi, $isjkp, $icustomer, $eremark);
        $this->mmaster->deletedetail($nokonversi);
       
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

    public function view(){

        $nokonversi    = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'head'       => $this->mmaster->getforecast($nokonversi)->row(),
            'datadetail' => $this->mmaster->getforecastdetail($nokonversi)->result(),
            'gudang'     => $this->mmaster->bacagudang(),
            'customer'   => $this->mmaster->bacacustomer(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */