<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050304';

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
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'gudang'            => $this->mmaster->bacagudang(),
            'customer'          => $this->mmaster->bacapelanggan()
            // 'customergroup'     => $this->mmaster->bacagroup()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }
    public function getcust(){
        $iarea = $this->input->post('i_area');
        $groupcustomer = $this->input->post('group_customer');
        // if($groupcustomer == 1){
        $query = $this->mmaster->getcust($groupcustomer, $iarea);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_branch." >".$row->e_branch_name."</option>";
                    // $c.= "<optoin value='$row->i_customer - $row->i_branch'>$row->e_branch_name</option>";
                    // .$row->i_customer." - ".$row->i_branch."
                }
                $kop  = "<option value=\"\"> -- Pilih Cabang -- ".$c."</option>";
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
        // }else{

        public function getcust2(){
            $query = $this->mmaster->getcust2();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_branch." >".$row->e_branch_name."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Cabang -- ".$c."</option>";
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
    


    // function getcust(){
    //     header("Content-Type: application/json", true);
    //     $iarea = $this->input->post('i_area');
    //     $groupcustomer = $this->input->post('group_customer');
    //     // $kodelokasi = explode("-", $this->input->post('i_kode_lokasi'));
    //     // $ikodelokasi = $kodelokasi[0];
    //     // $kdproduct = $this->input->post('kdproduct');
    //     // $color = $this->input->post('color');
    //     //$e_color_name = $this->input->post('e_color_name');  
    //         if($groupcustomer == 1){
    //             $this->db->select("a.i_customer, a.e_branch_name");   
    //             $this->db->from("tr_branch a"); 
    //             $this->db->join("tr_customer b","a.i_customer = b.i_customer");
    //             $this->db->where("a.i_code = ", $iarea); 
    //             $this->db->order_by("a.i_branch");
    //         }    
    //         $data = $this->db->get();
    //     echo json_encode($data->result_array());
    // }

    // public function getpelanggan(){
    //     $filter = [];
    //     if($this->input->get('q') != '' && $this->input->get('i_area') != '') {
    //         $filter = [];
    //         $iarea  = $this->input->get('i_area');
    //         $cari = strtoupper($this->input->get('q'));
    //         $data = $this->mmaster->getpelanggan($iarea, $cari);
    //         foreach($data->result() as $row){
    //             $filter[] = array(
    //                 'id'    => $row->i_customer,  
    //                 'text'  => $row->i_customer.' - '.$row->e_customer_name
    //             );
    //         }        
    //         echo json_encode($filter);
    //     } else {
    //         echo json_encode($filter);
    //     }
    // }

    // function getpelanggan(){
    //     $filter = [];
    //     $iarea  = $this->input->get('i_area');
    //     if($this->input->get('q') != '') {
    //         $filter = [];
    //         $cari = strtoupper($this->input->get('q'));
    //         $this->db->select("i_customer, e_customer_name");
    //         $this->db->from("tr_branch");
    //         // $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
    //         // $this->db->join("tr_master_gudang c","a.i_kode_master=b.i_store");
    //         $this->db->where('i_code', $iarea);
    //         // $this->db->order_by('a.i_material', 'ASC');
    //         $data = $this->db->get();
    //         foreach($data->result() as  $customer){       
    //                 $filter[] = array(
    //                 'id' => $customer->i_customer,  
    //                 'text' => $customer->i_customer.' - '.$customer->e_customer_name
    //             );
    //         }   
    //         echo json_encode($filter);
    //     }else{
    //         echo json_encode($filter);
    //     }
    // }  

    public function getarea(){
        $iarea = $this->input->post('iarea');
        $query = $this->mmaster->getareanya($iarea);
        if($query->num_rows()>0) {
            $c  = "";
            $area = $query->result();
            foreach($area as $row) {
                $istore = $row->i_store;
                $fstock = $row->f_stock;
                $earea  = $row->e_area_name;
            }
            echo json_encode(array(
                'istore'   => $istore,
                'fstock'   => $fstock,
                'earea'    => $earea
            ));
        }
    }

    public function getdetailpel(){
        header("Content-Type: application/json", true);
        $icustomer = $this->input->post('icustomer');
        $dspb      = $this->input->post('dspb');
        // $iarea     = $this->input->post('iarea');
        $per='';
        if($dspb!=''){          
            $tmp=explode('-',$dspb);          
            $yy=$tmp[2];          
            $bl=$tmp[1];          
            $per=$yy.$bl;      
        }      
        $data = $this->mmaster->getdetailpel($icustomer, $per);      
        echo json_encode($data->result_array());  
    }

    public function getsales(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $iarea  = $this->input->get('i_area');
            $dspb   = $this->input->get('d_spb');
            if($dspb!=''){
                $tmp=explode('-',$dspb);
                $yy=$tmp[2];
                $bl=$tmp[1];
                $per=$yy.$bl;
            }
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getsales($iarea, $cari, $per);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_salesman,  
                    'text'  => $row->e_salesman_name.' - '.$row->i_salesman
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailsal(){
        header("Content-Type: application/json", true);
        $isalesman = $this->input->post('isalesman');
        $dspb      = $this->input->post('dspb');
        $iarea     = $this->input->post('iarea');
        $per='';
        if($dspb!=''){          
            $tmp=explode('-',$dspb);          
            $yy=$tmp[2];          
            $bl=$tmp[1];          
            $per=$yy.$bl;      
        }      
        $data = $this->mmaster->getdetailsal($isalesman, $per, $iarea);      
        echo json_encode($data->result_array());  
    }

    function getdetailbar(){
        header("Content-Type: application/json", true);
        $eproductname = $this->input->post('eproductname');
        $this->db->select(" a.*, b.v_price from tr_material a 
        left join tr_supplier_materialprice b on a.i_material = b.i_material where a.i_material = '$eproductname'", false);           
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function getdiscount(){
        header("Content-Type: application/json", true);
        $ibranch = $this->input->post('i_branch');
        $this->db->select("*");
            $this->db->from("tr_customer_discount");
            // $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->where("UPPER(i_customer)", $ibranch);
            $this->db->order_by('i_customer', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function databrg(){
        $gudang = $this->uri->segment(4);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("* "); 
            $this->db->from("tr_material");
            $this->db->where("i_kode_kelompok","KTB0001");
            $this->db->order_by('i_material', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id'   => $product->i_material, 
                    'name' => $product->e_material_name, 
                    'text' => $product->i_material.' - '.$product->e_material_name,
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    } 

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        // $ispb    = $this->input->post('ispb', TRUE);
        $dspb    = $this->input->post('dspb', TRUE);         
        if($dspb!=''){   
            $tmp=explode("-",$dspb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspb=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $icustomer      = $this->input->post('customer', TRUE);
        $ndisc      = $this->input->post('ndiscc', TRUE);
        // $ibranch        = $this->input->post('ibranch', TRUE);
        // $iarea        = $this->input->post('iarea', TRUE);
        $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal',TRUE);
        $vspb               = $this->input->post('vspb',TRUE);
        $vspbbersih         = $this->input->post('vspbbersih',TRUE);
        // $customergroup      = $this->input->post('customergroup',TRUE);
        
        // $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        // $vspbdiscount2      = str_replace(',','',$vspbdiscount2);
        // $vspbdiscount3      = str_replace(',','',$vspbdiscount3);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspbbersih         = str_replace(',','',$vspbbersih);
        $vspb               = str_replace(',','',$vspb);
        // $ispbold            = $this->input->post('ispbold',TRUE);
        $jml                = $this->input->post('jml', TRUE);
        // $icustomer  = '101';
        // $ibranch    = '102';

        if(($icustomer!='') && ($dspb!='') && ($jml>0)){
            // $bener = "false";
            $this->db->trans_begin();
            $ispb =$this->mmaster->runningnumber($thbl);
            $this->mmaster->insertheader($ispb, $dspb, $icustomer, $ndisc, $vspbdiscounttotal, $vspbbersih, $vspb);
            for($i=1;$i<=$jml;$i++){              
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);              
                // $iproductstatus = $this->input->post('iproductstatus'.$i, TRUE);              
                // $iproductgrade      = 'A';              
                // $iproductmotif      = $this->input->post('motif'.$i, TRUE);              
                $eproductname       = $this->input->post('eproductname'.$i, TRUE);              
                $vprice         = $this->input->post('vprice'.$i, TRUE);
                $vtotal         = $this->input->post('vtotal'.$i, TRUE);
                // $nspbdiscount1      = $this->input->post('ncustomerdiscount1'.$i,TRUE);
                // $nspbdiscount2      = $this->input->post('ncustomerdiscount2'.$i,TRUE);
                // $nspbdiscount3      = $this->input->post('ncustomerdiscount3'.$i,TRUE);  
                // $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
                // $nspbdiscount2      = str_replace(',','',$nspbdiscount2);
                // $nspbdiscount3      = str_replace(',','',$nspbdiscount3);            
                $vprice         = str_replace(',','',$vprice);              
                $norder             = $this->input->post('norder'.$i, TRUE);
                $vtotal             = $this->input->post('vtotal'.$i, TRUE);              
                // $icolor             = $this->input->post('icolor'.$i, TRUE);              
                $eremark            = $this->input->post('eremark'.$i, TRUE);              
                if($norder>0 && ($iproduct!='' || $iproduct!=null)){                
                    $this->mmaster->insertdetail($ispb, $iproduct, $eproductname, $norder, $eremark, $i, $vprice);            
                }        
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Input SPB Group No :'.$ispb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
