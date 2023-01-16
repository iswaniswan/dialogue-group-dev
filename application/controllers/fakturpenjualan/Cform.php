<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '204020';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    public function index()
    {
        $d = new DateTime();

        $one_year = new DateInterval('P1M');
        $one_year_ago = new DateTime();
        $one_year_ago->sub($one_year);

        // Output the microseconds.
        $akhir = $d->format('d-m-Y');
        $awal  = $one_year_ago->format('d-m-Y');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'         => $awal,
            'dto'           => $akhir,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }
    // public function index()
    // {
    //     $data = array(
    //         'folder'    => $this->global['folder'],
    //         'title'     => $this->global['title']
    //     );

    //     $this->Logger->write('Membuka Menu '.$this->global['title']);

    //     $this->load->view($this->global['folder'].'/vformmain', $data);
    // }

    // function data(){
    // 	$dfrom = $this->uri->segment('4');
    //     $dto = $this->uri->segment('5');
        
    //     $tmp=explode('-',$dfrom);
    //     $dd=$tmp[0];
    //     $mm=$tmp[1];
    //     $yy=$tmp[2];
    //     $from=$yy.'-'.$mm.'-'.$dd;

    //     $tmp=explode('-',$dto);
    //     $dd=$tmp[0];
    //     $mm=$tmp[1];
    //     $yy=$tmp[2];
    //     $to=$yy.'-'.$mm.'-'.$dd;
            
    // 	echo $this->mmaster->data($from,$to, $this->i_menu);
    // }
    function data(){
        $dfrom  = $this->input->post('dfrom');
        $dto  = $this->input->post('dto');

        // if($supplier==''){
        //     $supplier=$this->uri->segment(4);
        // }
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
            
    	echo $this->mmaster->data($this->i_menu, $dfrom, $dto, $username, $idcompany, $idepartemen, $ilevel);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $username   = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'area'=> $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
        );

        
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function bacado(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            // $this->db->select("*");
            // $this->db->from("tr_supplier");
            // $this->db->like("UPPER(i_supplier)", $cari);
            // $this->db->or_like("UPPER(e_supplier_name)", $cari);
            //$data = $this->db->get();
            $data = $this->mmaster->bacado($cari);
            foreach($data->result() as  $ido){
                    $filter[] = array(
                    'id'   => $ido->i_do,  
                    'text' => $ido->i_do,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdo(){
        header("Content-Type: application/json", true);
        $ido        = $this->input->post('ido');
        // $ibtb       = $this->input->post('ibtb');
        // $isjmanual  = $this->input->post('isjmanual');
        // $ilokasi      = $this->session->userdata('i_lokasi');
        // $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->getsj($ido)->row(),
            'detail' => $this->mmaster->getsj_detail($ido)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function realisasi(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodelokasi  = $this->input->post('ikodelokasi', TRUE);
        $iop	      = $this->input->post('iop', TRUE);
       
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'gudang'=> $this->mmaster->bacastore($ikodelokasi)->row(),
            'data' => $this->mmaster->cek_data($iop)->row(),
            'data2' => $this->mmaster->cek_datadetail($iop, $ikodelokasi)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformreal', $data);
    }
    function dataproduct(){
        $filter = [];
        //$iunit = $this->uri->segment(4);
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct(a.i_product) , b.e_product_namewip, a.i_color , c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b","a.i_product=b.i_product_wip");
            $this->db->join("tr_color c","a.i_color=c.i_color ");
            $this->db->order_by('a.i_product', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                     'id' => $product->i_product.' - '.$product->i_color,  
                    'text' => $product->i_product.' - '.$product->e_product_namewip.'-'.$product->e_color_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }
    
    // function getproduct(){
    //     header("Content-Type: application/json", true);
    //     $iproduct = $this->input->post('i_product');
    //     $kdproduct = $this->input->post('kdproduct');
    //     $color = $this->input->post('color');
    //         $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name");
    //         $this->db->from("tr_polacutting a");
    //         $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
    //         $this->db->join("tr_color c", "a.i_color = c.i_color");
    //         $this->db->where("UPPER(b.i_product_wip)", trim($kdproduct));
    //         $this->db->where("a.i_color", trim($color));
    //         $this->db->order_by("a.i_product");  
    //         $data = $this->db->get();
    //     echo json_encode($data->result_array());
    // }

    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $kodelokasi = explode("-", $this->input->post('i_kode_lokasi'));
        $ikodelokasi = $kodelokasi[0];
        $kdproduct = $this->input->post('kdproduct');
        $color = $this->input->post('color');
        //$e_color_name = $this->input->post('e_color_name');  
            $this->db->select("distinct (a.i_product_wip), a.e_product_namewip, b.i_color, c.e_color_name, d.n_quantity_stock");   
            $this->db->from("tr_product_wip a"); 
            $this->db->join("tr_product_wipcolor b","a.i_product_wip = b.i_product_wip");
            $this->db->join("tr_color c","b.i_color=c.i_color ");
            $this->db->join("tm_ic d","a.i_product_wip = d.i_product and b.i_color = d.i_color","left");
            $this->db->where("UPPER(a.i_product_wip)", trim($kdproduct));
            $this->db->where("b.i_color", trim($color));
            $this->db->where("d.i_kode_lokasi", $ikodelokasi); 
            $this->db->where("a.f_product_active", 't'); 
            $this->db->order_by("a.i_product_wip");    
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }
    
    public function view(){
        $dfrom = $this->input->post('dfrom',true);
        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto',true);
        if($dto == ''){
            $dto = $this->uri->segment(5);
        }
        #$dfrom = ( $this->input->post('dfrom') == '' ) ? $this->input->post('dfrom') : $this->uri->segment(4) ;

        #$dto = ( $this->input->post('dto') == '' ) ? $this->input->post('dto') : $this->uri->segment(5) ;        

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ifaktur = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            // 'gudang'=> $this->mmaster->bacagudang(),
            'data' => $this->mmaster->cek_data($ifaktur)->row(),
            'data2' => $this->mmaster->cek_datadet($ifaktur)->result(),
            
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function getdetstore(){
        header("Content-Type: application/json", true);
        $ikodelokasi = $this->input->post('ikodelokasi');
        $data = $this->mmaster->getdetstore($ikodelokasi);
        echo json_encode($data->result_array());  
    }

    public function bacado2(){
        // $isupplier = $this->input->post('isupplier');
        $query = $this->mmaster->bacado2();
        if($query->num_rows()>0) {
            $c         = "";
            $reff  = $query->result();
            foreach($reff as $row) {
                $c.="<option value=".$row->i_do." >".$row->i_do."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih No DO -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">No Referensi Tidak Ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function detail(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_bonk = $this->uri->segment('4');
        $i_product = $this->uri->segment('5');
        $i_color = $this->uri->segment('6');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'datheader' => $this->mmaster->cek_datadetheader($i_bonk)->row(),
            'datdetail' => $this->mmaster->cek_datdetail($i_bonk, $i_product, $i_color)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ifaktur        = $this->input->post('ifaktur', TRUE);
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancelheader($ifaktur);
        // $data = $this->mmaster->cancelsemuadetail($ifaktur);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Cancel bon k qc'.$i_bonk);
                echo json_encode($data);
            }
        }
        public function deletedetail(){
            $data = check_role($this->i_menu, 4);
            if(!$data){
                redirect(base_url(),'refresh');
            }
            
            $i_bonk = $this->uri->segment('4');
            $i_product = $this->uri->segment('5');
            $i_color = $this->uri->segment('6');
            
            $this->db->trans_begin();
            $data = $this->mmaster->deleteheader($i_bonk, $i_product, $i_color);
            $data = $this->mmaster->deletesemuadetail($i_bonk, $i_product);
            if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Cancel Cancel bon k qc detail '.$i_bonk);
                    echo json_encode($data);
                    $this->edit();
                }
            }

            public function updatestatus(){
                $data = check_role($this->i_menu, 3);
                if (!$data) {
                    redirect(base_url(), 'refresh');
                }
        
                $ifaktur   = $this->input->post('ifaktur', true);
                // $istatus = $this->input->post('istatus', true);
                $this->db->trans_begin();
                $data = $this->mmaster->updatestatus($ifaktur);
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                }else {
                    $this->db->trans_commit();
                    $this->Logger->write('Kirim Draft '.$this->global['folder'].' No : '.$ifaktur);
                    echo json_encode($data);
                }
            }

        //     public function update2(){

        //         $data = check_role($this->i_menu, 3);
        //         if(!$data){
        //             redirect(base_url(),'refresh');
        //         }
                
        //             $isj 		 = $this->input->post('isj', TRUE);
        //             $dsj 		 = $this->input->post('dsj', TRUE);
        //             $jeniskeluar = $this->input->post('ijenis',TRUE);
        //             $ikodemaster = $this->input->post('ikodemaster',TRUE);
        //             $eremark     = $this->input->post('eremark',TRUE);
        //             $jml	  	 = $this->input->post('jml', TRUE);
        //             $query 	                = $this->db->query("SELECT current_timestamp as c");
        //                $row   	            = $query->row();
        //             $now	            = $row->c;
        //             $this->db->trans_begin(); 
        //             $this->mmaster->updateheader($isj,$dsj,$eremark);
        //         if ($isj != '' && $dsj != ''){
        //             // $cekada = $this->mmaster->cek_dataheader($isj);
        //             // echo $jml;
        //             //     die;
        //             // if($cekada->num_rows() > 0){
        //                 for($i=1;$i<=$jml;$i++){
        //                     $iproduct			= $this->input->post('iproduct'.$i, TRUE);
        //                     $icolor	            = $this->input->post('icolor'.$i, TRUE);
        //                     $eproductname		= $this->input->post('eproductname'.$i, TRUE);
        //                     $nquantity   		= $this->input->post('nqty'.$i, TRUE);
        //                     $eremark		  	= $this->input->post('eremark'.$i, TRUE);
        //                     $kodelokasi	        = '01';//$this->mmaster->ceklokasi($ikodemaster);
        //                     $qtysj	            =$this->mmaster->cekqtysj($isj,$iproduct,$icolor);
        //                     // $qtystock	=$this->mmaster->cekstock($iproduct,$kodelokasi); 
        //                     $qtystock           = $this->mmaster->cekstock($iproduct,$kodelokasi,$icolor);
        //                     if($qtysj>$nquantity){
        //                         $total2 = $qtysj-$nquantity;
        //                         $total = $qtystock+$total2;
        //                     }else if($qtysj<$nquantity){
        //                         $total2 = $nquantity-$qtysj;
        //                         $total = $qtystock-$total2;
        //                     }else if($Nquantity == 0){
        //                         $total2 = $qtysj+$qtystock;
        //                         $this->Mmaster->delete($isj,$iproduct,$icolor,$i);
        //                     }
        //                           $this->mmaster->updatestock($iproduct, $total, $kodelokasi);
        //                           $this->mmaster->updatedetail($isj,$iproduct,$icolor,$eproductname,$nquantity,$eremark,$i);
                              
        //               }
        //               $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isj);
        
        //             if ($this->db->trans_status() === FALSE)
        //             {
        //                 $this->db->trans_rollback();
        //                $data = array(
        //                     'sukses'    => false
                            
        //                 );
        //             }else{
        //                 $this->db->trans_commit();
        //                 $data = array(
        //                     'sukses'    => true,
        //                     'kode'      => $isj
        //                 );
        //             }
        //         }else{
        //             $data = array(
        //                 'sukses' => false,
        //             );
        //         }
        //         $this->load->view('pesan', $data);  
        // }

        public function simpan(){
            $data = check_role($this->i_menu, 1);
            if(!$data){
                redirect(base_url(),'refresh');
            }
            
            $dept           = trim($this->input->post('dept', TRUE));
            $dnota        = $this->input->post('dfaktur', TRUE);
            if($dnota){
                 $tmp   = explode('-', $dnota);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $thbl = $year.$month;
                 $dfaktur = $year.'-'.$month.'-'.$day;
            }
    
            $eremark            = $this->input->post('eremark', TRUE);
            $icustomer          = $this->input->post('icustomer', TRUE);
            $ido                = $this->input->post('ido', TRUE);
            $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal', TRUE);
            $vspb               = $this->input->post('vspb', TRUE);
            $vspbbersih         = $this->input->post('vspbbersih', TRUE);
            $ndis               = $this->input->post('discount', TRUE);   
            $dpp                = $this->input->post('dpp', TRUE);
            $dpp                = str_replace(',','',$dpp);
            $ppn                = $this->input->post('ppn', TRUE);
            $ppn                = str_replace(',','',$ppn);
            $jml                = $this->input->post('jml', TRUE);
            $lok                = $this->session->userdata('i_lokasi');
            $ifaktur            = $this->mmaster->runningnumber($thbl, $lok);
            $vspbfppn           = ($vspbbersih - $vspbdiscounttotal) + $ppn;
            $vdo                = 0;
            $vnetto             = 0;
            $totdisc            = 0;
            $this->db->trans_begin();
            $this->mmaster->insertheader($ifaktur, $icustomer, $dfaktur, $ndis, $vspbdiscounttotal, $vspb, $vspbbersih, $dpp, $ppn, $vspbfppn,$dept);
            for($j=1;$j<=$jml;$j++){
                $idoo                = $this->input->post('idoo'.$j, TRUE);
                $iproduct           = $this->input->post('iproduct'.$j, TRUE);
                $eproductname           = $this->input->post('eproductname'.$j, TRUE);
                $ndeliver           = $this->input->post('nquantity'.$j, TRUE);
                $vproductretail     = $this->input->post('vprice'.$j, TRUE);                
                $total     = $this->input->post('total'.$j, TRUE);                
                // $vprice             = $this->input->post('vprice'.$j, TRUE);
                $this->mmaster->insertdetail($ifaktur, $idoo, $iproduct, $eproductname, $ndis, $ndeliver, $vproductretail,$j,$total); 
            }
            $this->mmaster->updatedo($ido);
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ido);
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
                        'kode'      => $ifaktur,
                    );
                }
                $this->load->view('pesan', $data);
        }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
            $ido		            = $this->input->post('ido', TRUE);
            $ikodemaster	        = $this->input->post('ikodemaster', TRUE);    
            $icustomer		        = $this->input->post('icustomer', TRUE);
            $ibranch		        = $this->input->post('ibranch', TRUE);
            $dfaktur		            = $this->input->post('dnota', TRUE);
            $vspb		            = $this->input->post('vspb', TRUE);
            $vspbdiscounttotal		= $this->input->post('vspbdiscounttotal', TRUE);
            $vspbbersih		        = $this->input->post('vspbbersih', TRUE);
            $vspb         = str_replace(',','',$vspb);
            $vspbdiscounttotal         = str_replace(',','',$vspbdiscounttotal);
            $vspbbersih         = str_replace(',','',$vspbbersih);
			if($dfaktur!=''){
				$tmp=explode("-",$dfaktur);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dfaktur=$th."-".$bl."-".$hr;
				$thbl=$th.$bl;
            }
           
			// $eremark  = $this->input->post('eremark', TRUE);
            $jml	  = $this->input->post('jml', TRUE);
			if($dfaktur!=''){
				if($jml == 0){
					exit; 
				}else{
                $ifaktur	=$this->mmaster->runningnumber($thbl);
				$this->mmaster->insertheader($ifaktur, $ikodemaster, $icustomer, $ibranch, $dfaktur, $vspb, $vspbdiscounttotal, $vspbbersih);
					for($i=1;$i<=$jml;$i++){
                    //   $product			= explode("-", $this->input->post('iproduct'.$i, TRUE));
                    //   $iproduct = $product[0];
                    $iproduct	                = $this->input->post('iproduct'.$i, TRUE);
                    $eproductname	            = $this->input->post('eproductname'.$i, TRUE);
                    $icolor	                    = $this->input->post('icolor'.$i, TRUE);
					// $eproductname		        = $this->input->post('eproduct'.$i, TRUE);
                    $ndeliver   		        = $this->input->post('nqty'.$i, TRUE);
                    $vproductretail   		    = $this->input->post('vproductretail'.$i, TRUE);
                    $vproductretail             = str_replace(',','',$vproductretail);
                    $ncustomerdiscount1   		= $this->input->post('ncustomerdiscount1'.$i, TRUE);
                    $ncustomerdiscount2   		= $this->input->post('ncustomerdiscount2'.$i, TRUE);
                    $ncustomerdiscount3   		= $this->input->post('ncustomerdiscount3'.$i, TRUE);
                    $vtotal   		            = $this->input->post('vtotal'.$i, TRUE);
                    $vtotal                     = str_replace(',','',$vtotal);
                    $ikodelokasi                = '01';
                    //   $eremark		  	= $this->input->post('eremark'.$i, TRUE);
                    // $query2 = $this->mmaster->cekstock($iproduct,$ikodelokasi,$icolor);
                    // if ($query2->num_rows() > 0){
                    //     $hasilrow6 = $query2->row();
                    //     $n_quantity_stock = $hasilrow6->n_quantity_stock;
                    //     $total = $n_quantity_stock-$ndeliver;
                    //     $this->mmaster->updatestock($iproduct, $total, $ikodelokasi);
                    // }
                    // $query3 = $this->mmaster->cekqtyop($iop, $iproduct, $icolor);
                    // if ($query3->num_rows()>0){
                    //     $hasilrow7 = $query3->row();
                    //     $nqty = $hasilrow7->n_count;
                    //     if($nqty = '0'){
                    //         $this->mmaster->updateop($iop, $iproduct, $icolor, $ndeliver);
                    //         $this->mmaster->updateop($iop, $iproduct, $icolor, $ndeliver);
                    //     }else{
                    //         $ndeliver = $nqty+$ndeliver;
                    //         $this->mmaster->updateopheader($iop);
                    //         $this->mmaster->updateop($iop, $iproduct, $icolor, $ndeliver);
                    //     }
                    // }
                    $this->mmaster->insertdetail($ido, $ifaktur, $iproduct, $eproductname, $icolor, $ndeliver, 
                    $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3,
                    $vproductretail, $vtotal, $i);
					}
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
                    'kode'      => $ifaktur,
                );
            }
            $this->load->view('pesan', $data);

        }  
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
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

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $isj   = $this->input->post('ido');
        
        $this->db->trans_begin();
        $this->mmaster->approve($isj);
        
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
}

/* End of file Cform.php */
