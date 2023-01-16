<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050205';

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
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'supplier'=> $this->mmaster->bacasupplier(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }
    function getmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->where("UPPER(a.i_material)", $imaterial);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_material, a.e_material_name ,b.i_satuan, b.e_satuan");
            $this->db->from("from tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->nama,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    function simpan(){

		$data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
				$query 	= $this->db->query("SELECT current_timestamp as c");
		   		$row   	= $query->row();
		    	$now	  = $row->c;

		    	$datesj = $this->input->post("dsj",true);
		    	// if($dsj){
		    	// 	 $tmp 	= explode('-', $dsj);
		    	// 	 $day 	= $tmp[0];
		    	// 	 $month = $tmp[1];
		    	// 	 $year	= $tmp[2];
		    	// 	 $yearmonth = $year.$month;
		    	// 	 $datesj = $year.'-'.$month.'-'.$day;
                // }

                $this->db->trans_begin(); 
                $imaterial          = $this->input->post('i_material');
				$isj                = $this->input->post("isj",TRUE);
				$isupplier          = $this->input->post("isupplier",TRUE);
				$paymenttype        = $this->input->post('paymenttype',TRUE);
				$tipepajak          = $this->input->post("tipepajak",TRUE);
				$totppn             = str_replace(',','',$this->input->post("totppn",TRUE));
				$pkp                = $this->input->post('pkp',TRUE);
                $grandtot           = str_replace(',','',$this->input->post("grandtot",TRUE)); 
                $eremark            = $this->input->post("eremark",TRUE);
                $vtotalop            = $this->input->post('vtotalop',TRUE);
				$dentry            = $now;
                $this->mmaster->insertheader($imaterial, $isj, $datesj, $isupplier, $paymenttype, 
                $tipepajak, $totppn, $pkp, $grandtot, $eremark, $vtotalop, $dentry);

				$jml                    = $this->input->post("jml",true);
				for($i=1;$i<=$jml;$i++){
					$iop                    = $this->input->post("iop".$i,TRUE);
					$imaterial              = $this->input->post("imaterial".$i,TRUE);
					$qty                    = $this->input->post("nquantity".$i,TRUE);
					$ndiscount              = $this->input->post("diskon".$i,TRUE);
					$vunitprice             = str_replace(',','',$this->input->post("vprice".$i,TRUE));
					$vunitpriceop           = str_replace(',','',$this->input->post("vpriceop".$i,TRUE));
					$iunit                 = $this->input->post("isatuan".$i,TRUE);
					$iformula              = '0';
					$nformula_factor       = '0';
                    $querycek = $this->mmaster->cekqty($iop, $imaterial);
                     if($querycek->num_rows() > 0){
                        $querycek= $querycek->row();
                        $qtyop = $querycek->op;
                        $qtysj = $querycek->sj;
                        $totsj = $qtysj+$qty;
    
                        //var_dump($qtypp,$qtyop,$totop);die;
                        if($totsj >= $qtyop){
                            $qty= $qtyop-$qtysj;
                            $fcomplete  = true;
                            $complete   = $this->mmaster->itemComplete($fcomplete,$iop,$imaterial);
                        }
                     }
                     $this->mmaster->insertdetail($iop, $imaterial, $qty, $ndiscount, $vunitprice, 
                        $vunitpriceop, $iunit, $iformula, $nformula_factor, $i, $isj, $now);
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
                        'kode'      => $isj,
                    );
            }
        $this->load->view('pesan', $data);      
        }
        
        function save(){

            $data = check_role($this->i_menu, 1);
            if(!$data){
                redirect(base_url(),'refresh');
            }
                    $query 	= $this->db->query("SELECT current_timestamp as c");
                       $row   	= $query->row();
                    $now	  = $row->c;
    
                    $datesj = $this->input->post("dsj",true);
                    $pkp                = $this->input->post('pkp',TRUE);
                    // if($dsj){
                    // 	 $tmp 	= explode('-', $dsj);
                    // 	 $day 	= $tmp[0];
                    // 	 $month = $tmp[1];
                    // 	 $year	= $tmp[2];
                    // 	 $yearmonth = $year.$month;
                    // 	 $datesj = $year.'-'.$month.'-'.$day;
                    // }
                    if($pkp == TRUE){
                        $pkp = "t";
                    }else{
                        $pkp = "f";
                    }

    
                    $this->db->trans_begin(); 
                    $imaterial          = $this->input->post('i_material');
                    $isj                = $this->input->post("isj",TRUE);
                    $isupplier          = $this->input->post("isupplier",TRUE);
                    $paymenttype        = $this->input->post('paymenttype',TRUE);
                    $tipepajak          = $this->input->post("tipepajak",TRUE);
                    $totppn             = str_replace(',','',$this->input->post("totppn",TRUE));
                    
                    $grandtot           = str_replace(',','',$this->input->post("grandtot",TRUE)); 
                    $eremark            = $this->input->post("eremark",TRUE);
                    //$vtotalop            = $this->input->post('vtotalop',TRUE);
                    $dentry            = $now;
                    $this->mmaster->insertheadertanpaop($isj, $datesj, $isupplier, $paymenttype, 
                    $tipepajak, $totppn, $pkp, $grandtot, $eremark, $dentry);
    
                    $jml                    = $this->input->post("jml",true);
                    for($i=1;$i<=$jml;$i++){

                        $imaterial              = $this->input->post("imaterial".$i,TRUE);
                        $qty                    = $this->input->post("nquantity".$i,TRUE);
                        $ndiscount              = $this->input->post("diskon".$i,TRUE);
                        $vunitprice             = str_replace(',','',$this->input->post("vprice".$i,TRUE));
                        // $vunitpriceop           = str_replace(',','',$this->input->post("vpriceop".$i,TRUE));
                        $iunit                 = $this->input->post("isatuan".$i,TRUE);
                        $iformula              = '0';
                        $nformula_factor       = '0';
                        
                         $this->mmaster->insertdetailtanpaop($imaterial, $qty, $ndiscount, $vunitprice, 
                             $iunit, $iformula, $nformula_factor, $i, $isj, $now);
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
                            'kode'      => $isj,
                        );
                }
            $this->load->view('pesan', $data);      
            }
        
    // public function simpann(){

    //     $data = check_role($this->i_menu, 1);
    //     if(!$data){
    //         redirect(base_url(),'refresh');
    //     }
    //     $ikodemaster 	= $this->input->post('ikodemaster', TRUE);
    //     $remark         = $this->input->post('eremark', TRUE);
    //     $ikodemaster 	= $this->input->post('ikodemaster', TRUE);
    //     $remark         = $this->input->post('eremark', TRUE);
    //     $ikodemaster 	= $this->input->post('ikodemaster', TRUE);
    //     $remark         = $this->input->post('eremark', TRUE);
    //     $ikodemaster 	= $this->input->post('ikodemaster', TRUE);
    //     $remark         = $this->input->post('eremark', TRUE);
    //     $jml= $this->input->post('jml', TRUE); 
    //     $ppcancel = 'f';
    //     $query 	= $this->db->query("SELECT current_timestamp as c");
        
	//    		$row   	= $query->row();
	//     	$now	  = $row->c;

	//     	$dpp = $this->input->post("dpp",true);
	//     	if($dpp){
	//     		 $tmp 	= explode('-', $dpp);
	//     		 $day 	= $tmp[0];
	//     		 $month = $tmp[1];
	//     		 $year	= $tmp[2];
	//     		 $yearmonth = $year.$month;
	//     		 $datepp = $year.'-'.$month.'-'.$day;
    //     }
    //         $this->db->trans_begin(); 
    //         $ipp = $this->mmaster->runningnumber($yearmonth);
    //         $this->mmaster->insertheader($ikodemaster,$ppcancel,$now,$datepp,$ipp,$remark);
    //         $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ipp);
    //         for($i=1;$i<=$jml;$i++){
    //             $imaterial= $this->input->post('imaterial'.$i, TRUE);
    //             $isatuan= $this->input->post('isatuan'.$i, TRUE);
    //             // echo $isatuan;
    //             // die; 
    //             $nquantity= $this->input->post('nquantity'.$i, TRUE); 
    //             $eremark= $this->input->post('eremark'.$i, TRUE);

    //             $vprice = '0';
    //             $fopcomplete = 'f';
    //             $this->mmaster->insertdetail($ipp, $imaterial ,$isatuan ,
    //             $nquantity ,$vprice ,$fopcomplete,$i);
    //         }
    //         if ($this->db->trans_status() === FALSE)
    //         {
    //             $this->db->trans_rollback();
    //                 $data = array(
    //                     'sukses'    => false,
                        
    //                 );
    //         }else{
    //             $this->db->trans_commit();
    //             $data = array(
    //                 'sukses' => true,
    //                 'kode'      => $ipp,
    //             );
    //     }
    // $this->load->view('pesan', $data);      
    // }
    function datamaterial2(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $data = $this->db->get();
            foreach($data->result() as  $imaterial){       
                    $filter[] = array(
                    'id' => $material->i_material,  
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  
    
    public function proses(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier = $this->input->post('isupplier', TRUE);
        $jenispembayaran = $this->input->post('jenispembayaran', TRUE);
        $acuanop = $this->input->post('acuanop', TRUE);
        if($acuanop == TRUE){
			// $acuanop='TRUE';
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'jenisp'    => $jenispembayaran,
            'data' => $this->mmaster->cek_data($isupplier, $jenispembayaran)->result(),
        );
        $this->Logger->write('Membuka Menu proses tambah Penerimaan Berdasarkan OP'.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminputacuanop', $data);
    }else{
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'jenisp'    => $jenispembayaran,
            'getpkp'=>$this->mmaster->getpkp($isupplier)->row(),
        
            // 'data'   => $this->mmaster->cek_dataapr($i_sj)->row(),
            // 'data2'  => $this->mmaster->cek_datadetapr($i_sj)->result(),
        );
        $this->Logger->write('Membuka Menu proses tambah Penerimaan Tanpa Acuan OP '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformnoacuanop', $data);
        }
    }
    function datagudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_kode_master, e_nama_master");
            $this->db->from("tr_master_gudang");
            $this->db->like("UPPER(i_kode_master)", $cari);
            $this->db->or_like("UPPER(e_nama_master)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $kodemaster){
                    $filter[] = array(
                    'id' => $kodemaster->i_kode_master,  
                    'text' => $kodemaster->i_kode_master.' - '.$kodemaster->e_nama_master
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_material, e_material_name");
            $this->db->from("tr_material");
            $this->db->like("UPPER(i_material)", $cari);
            $this->db->or_like("UPPER(e_material_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $imaterial){
                    $filter[] = array(
                    'id' => $imaterial->i_material,  
                    'text' => $imaterial->i_material.' - '.$imaterial->e_material_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function prosesacuanop(){

    $data = check_role($this->i_menu, 3);
    if(!$data){
        redirect(base_url(),'refresh');
    }
    $jml            = $this->input->post('jml', TRUE);
    $jenispembayaran= $this->input->post('jenisp', TRUE);
    for($i=1;$i<=$jml;$i++){
        $isupplier      = $this->input->post('isupplier'.$i, TRUE);
        $iop            = $this->input->post('iop'.$i, TRUE);
        $ipaymenttype   = $this->input->post('ipayment'.$i, TRUE);
        $acuanop   = $this->input->post('acuanop'.$i, TRUE);
        $imaterial   = $this->input->post('imaterial'.$i, TRUE);
        if($acuanop == 'on'){
            $op[] = $iop;
            $data['op'] = array_unique($op);
            
            // 'data' => $this->mmaster->cek_header($isupplier, $iop, $ipaymenttype)->row(),
            $data['data2'][]=$this->mmaster->cek_det($iop, $imaterial, $isupplier)->result();
            //'data2' => $this->mmaster->cek_det($iop, $imaterial, $isupplier)->result(),
     
        }
    }

        $data['folder']= $this->global['folder'];
        $data['title']= "Edit ".$this->global['title'];
        $data['title_list']= 'List '.$this->global['title'];
        $data['jenisp']=$jenispembayaran;
        $data['getpkp']=$this->mmaster->getpkp($isupplier)->row();
        $data['data']=$this->mmaster->cek_header($isupplier, $iop, $ipaymenttype)->row();

    
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformacuanop2', $data);
    }
    function get_pkp_tipe_pajak($supp) 
	{
		$rows = array();
		if(isset($supp)) {
			$rows = $this->Mmaster->getpkp($supp)->result();
		}
		echo json_encode($rows);
	}

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_sj            = $this->uri->segment('4');
        //$isupplier       = $this->uri->segment('5');
        //$jenispembayaran = $this->uri->segment('6');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'   => $this->mmaster->cek_dataapr($i_sj)->row(),
            'data2'  => $this->mmaster->cek_datadetapr($i_sj)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
    
    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
            $isj 			            = $this->input->post('isj', TRUE);
            $grandtot                     = str_replace(',','',$this->input->post("grandtot",TRUE));
            $grandtotop                 = str_replace(',','',$this->input->post("grandtotop",TRUE));
            // $grandtot 			        = $this->input->post('grandtot', TRUE);
            // $grandtotop 			    = $this->input->post('grandtotop', TRUE);
            $query 	                    = $this->db->query("SELECT current_timestamp as c");
	   		$row   	                    = $query->row();
	    	$now	                    = $row->c;
            $this->db->trans_begin(); 
            $this->mmaster->updateheader($grandtot ,$grandtotop, $isj, $now);
            $jml= $this->input->post('jml', TRUE);
        if ($isj != ''){
            $cekada = $this->mmaster->cek_dataheader($isj);
            if($cekada->num_rows() > 0){
            for($i=1;$i<=$jml;$i++){
                $imaterial                 = $this->input->post('imaterial'.$i, TRUE);
                $nquantity                 = $this->input->post('nquantity'.$i, TRUE);
                $vprice                 = $this->input->post('vprice'.$i, TRUE);
                $this->mmaster->updatedetail($nquantity, $vprice, $imaterial, $isj);
                }
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$isj);
            }
        }
            if ($this->db->trans_status() === FALSE)
            {
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
    
        
    public function view(){

        $dfrom            = $this->input->post('dfrom', TRUE);
        $dto            = $this->input->post('dto', TRUE);

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'      => $dto,
            //'data'   => $this->mmaster->cek_dataapr($i_sj)->row(),
            'data'  => $this->mmaster->cek_datadet2($dfrom, $dto)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformproses', $data);
    }

        public function approve(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $i_sj = $this->uri->segment('4');
            // $i_sj= $this->input->post('isj', TRUE);
            $data = array(
                'folder' => $this->global['folder'],
                'title' => "Approve ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data'   => $this->mmaster->cek_dataapr($i_sj)->row(),
                'data2'  => $this->mmaster->cek_datadetapr($i_sj)->result(),
            );        
            $this->Logger->write('Membuka Menu Approve BTB'.$this->global['title']);
        
            $this->load->view($this->global['folder'].'/vformapprove', $data);
        }

        // public function approve(){

        //     $data = check_role($this->i_menu, 3);
        //     if(!$data){
        //         redirect(base_url(),'refresh');
        //     }
    
        //     $ipp = $this->uri->segment('4');
    
        //     $data = array(
        //         'folder' => $this->global['folder'],
        //         'title' => "Edit ".$this->global['title'],
        //         'title_list' => 'List '.$this->global['title'],
        //         'data' => $this->mmaster->cek_data($ipp)->row(),
        //         'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        //     );
    
        //     $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
        //     $this->load->view($this->global['folder'].'/vformapprove', $data);
        // }
        public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj 			= $this->input->post('isj', TRUE);
        // $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
            $now	  = $row->c;
            $this->db->trans_begin(); 
        $this->mmaster->approve($isj, $now);
        if ($this->db->trans_status() === FALSE)
            {
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
     }
/* End of file Cform.php */
