<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040001';

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
        $dfrom = $this->uri->segment('4');
        $dto = $this->uri->segment('5');
        
        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$yy.'-'.$mm.'-'.$dd;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$yy.'-'.$mm.'-'.$dd;

		echo $this->mmaster->data($this->i_menu, $dfrom,$dto);
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
            'area'=> $this->mmaster->bacagudang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    function getnota(){
        header("Content-Type: application/json", true);
        // $i_kb = $this->uri->segment('4');
        $kom = '0';
        $inota = $this->input->post('i_nota');
        // $isupplier = $this->input->post('isupplier');
        $this->db->select("*");
            $this->db->from("tm_notabtb");
            // $this->db->where("i_supplier", $isupplier);
            $this->db->where("i_nota", $inota);
            $this->db->where("v_sisa >", $kom);
            $this->db->order_by('i_nota', 'ASC');            
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
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $remark= $this->input->post('eremark', TRUE);
        $jml= $this->input->post('jml', TRUE); 
        $ppcancel = 'f';
        $query 	= $this->db->query("SELECT current_timestamp as c");
        
	   		$row   	= $query->row();
	    	$now	  = $row->c;

	    	$dpp = $this->input->post("dpp",true);
	    	if($dpp){
	    		 $tmp 	= explode('-', $dpp);
	    		 $day 	= $tmp[0];
	    		 $month = $tmp[1];
	    		 $year	= $tmp[2];
	    		 $yearmonth = $year.$month;
	    		 $datepp = $year.'-'.$month.'-'.$day;
        }
            $this->db->trans_begin(); 
            $ipp = $this->mmaster->runningnumber($yearmonth);
            $this->mmaster->insertheader($ikodemaster,$ppcancel,$now,$datepp,$ipp,$remark);
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ipp);
            for($i=1;$i<=$jml;$i++){
                $imaterial= $this->input->post('imaterial'.$i, TRUE);
                $isatuan= $this->input->post('isatuan'.$i, TRUE);
                // echo $isatuan;
                // die; 
                $nquantity= $this->input->post('nquantity'.$i, TRUE); 
                $eremark= $this->input->post('eremark'.$i, TRUE);

                $vprice = '0';
                $fopcomplete = 'f';
                $this->mmaster->insertdetail($ipp, $imaterial ,$isatuan ,
                $nquantity ,$vprice ,$fopcomplete,$i);
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
                    'kode'      => $ipp,
                );
        }
    $this->load->view('pesan', $data);      
    }
    function datanota(){
        $filter = [];
        $isupplier = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_notabtb");
            $this->db->where("i_supplier", $isupplier);
             $this->db->order_by('i_nota', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $nota){       
                    $filter[] = array(
                    'id' => $nota->i_nota,  
                    'text' => $nota->i_nota//.' - '.$nota->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_kb = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'supplier'=> $this->mmaster->bacasupplier(),
            'data' => $this->mmaster->cek_data($i_kb)->row(),
            // 'data2' => $this->mmaster->cek_datadet($i_kb)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
             redirect(base_url(),'refresh');
        }
		$ikb    	= $this->input->post('ikb', TRUE);
		$dalokasi   = $this->input->post('dalokasi', TRUE);
			if($dalokasi!=''){
				$tmp=explode("-",$dalokasi);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dalokasi=$th."-".$bl."-".$hr;
				$iperiode=$th.$bl;
			}
			$icoa  	   = $this->input->post('icoa', TRUE);//belum ada
			$isupplier = $this->input->post('isupplier', TRUE);
			$iarea 	   = "00";
			$dkb 	   = $this->input->post('dkb', TRUE);
			if($dkb!=''){
				$tmp=explode("-",$dkb);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dkb=$th."-".$bl."-".$hr;
				$thbl=$th.$bl;
			}
			$vjumlahx    = $this->input->post('vjumlah',TRUE);
			$vjumlahx    = str_replace(',','',$vjumlahx);
			$vlebihx     = $this->input->post('vlebih',TRUE);
			$vlebihx     = str_replace(',','',$vlebihx);
			$tarasi      = $this->input->post('iteration', TRUE);
            $jml         = $this->input->post('jml', TRUE);
			$ada 		 = false;
			//if(($dkb!='') && ($dalokasi!='') && ($ikb!='') && ($vjumlahx!='') && ($vjumlahx!='0') && ($tarasi!='')){
				//if(!$ada) {
					$this->db->trans_begin();
					$ialokasi 		  = $this->mmaster->runningnumberpl($iarea, $thbl);
					$egirodescription = "Alokasi Kas Besar Keluar No : ".$ikb;
					$fclose     	  = "f";
					/*----------------------------------------------| START FOR |---------------------------------------- */
					for($i=1;$i<=$jml;$i++){
						$nota  = $this->input->post('inota'.$i, TRUE);
						//$idtap = $this->input->post('id_'.$i, TRUE);
						$ireff = $ialokasi.'|'.$nota; 
						//if($i==1){
							$this->mmaster->inserttransheader($ireff, $iarea, $egirodescription, $fclose, $dkb);
						//}
						$ddtap   = $this->input->post('dnota'.$i, TRUE);
						$vjumlah = $this->input->post('vjumlah'.$i, TRUE);
						$vjumlah = str_replace(',','',$vjumlah);
						$vsisa 	 = $this->input->post('vsisa'.$i, TRUE);
						$vsisa 	 = str_replace(',','',$vsisa);
						$eremark = $this->input->post('eremark'.$i,TRUE);
                        $inoitem = $i;
						if($ddtap!=''){
							$tmp=explode("-",$ddtap);
							$th=$tmp[2];
							$bl=$tmp[1];
							$hr=$tmp[0];
							$ddtap=$th."-".$bl."-".$hr;
                        }
                        $query  = $this->db->query("SELECT current_timestamp as c");
		                $row    = $query->row();
		                $dentry = $row->c;
						$accdebet   = HutangDagang;
						$acckredit  = HutangDagangSementara;
						$namakredit = $this->mmaster->namaacc($acckredit);
						$namadebet  = $this->mmaster->namaacc($accdebet);
						/*-----------------------------------| INSERT ITEM TABEL ALOKASI BK |-------------------------------------------*/
						$this->mmaster->insertdetail($ialokasi, $ikb, $isupplier, $nota, $ddtap, $vjumlah, $vsisa, $inoitem, $eremark);
						$this->mmaster->inserttransitemkredit($acckredit, $ireff, $namakredit, $iarea, $egirodescription, $vjumlah, $dkb);
                        $this->mmaster->inserttranskredit($ikb,$iarea,$dkb);
                        $this->mmaster->inserttransitemdebet($accdebet,$ireff,$namadebet,$iarea,$egirodescription,$vjumlah,$dkb);
                        $this->mmaster->updatenota($inota,$isupplier,$vjumlah);
                        $this->mmaster->insertgldebet($accdebet,$ireff,$namadebet,$vjumlah,$dalokasi,$iarea,$egirodescription);
                        
						$this->mmaster->insertglkredit($acckredit,$ireff,$namakredit,'t',$vjumlah,$dalokasi,$iarea,$egirodescription, $dentry);
                    }
                    $this->mclasskas->insertheader($ialokasi,$ikb,$isupplier,$dalokasi,$vjumlahx,$vlebihx);
					/*----------------------------------------------------------| END FOR |--------------------------------------------------------*/
					/*-----------------------------------| INSERT HEADER TABEL ALOKASI BK |-------------------------------------------*/
					// $this->mmaster->insertheader($ialokasi,$ikb,$isupplier,$dalokasi,$vjumlahx,$vlebihx);
					// $asal 	   = 0;
					// $pengurang = $vjumlahx-$vlebihx;
					// /*-------------------------| UPDATE SISA UANG DI BANK |-------------------*/
					// $this->mmaster->updatekasbesar($ikb,$isupplier,$pengurang);
					/*--------| PEMBULATAN JIKA SISA UANG LEBIH DARI 0 DAN KURANG DARI 100 |--------- */
					/*if ($vlebihx > 0 && $vlebihx <= 100) {
						$egirodescription = "Alokasi Bank Keluar No : ".$ikb.'('.$icoa.')';
						$ireff = $ialokasi.'|'.$ikb;
						$this->mclasskas->inserttransheader($ireff,$iarea,$egirodescription,$fclose,$dalokasi);
						$vjumlah    = $this->input->post('vlebih', TRUE);
						$vjumlah    = str_replace(',','',$vjumlah);
						$accdebet   = ByPembulatan;
						$namadebet  = $this->mmaster->namaacc($accdebet);
						$tmp        = $this->mmaster->carisaldo($accdebet,$iperiode);
						if($tmp){
							$vsaldoaw1    = $tmp->v_saldo_awal;
						}else{
							$vsaldoaw1    = 0;
						}
						if($tmp){
							$vmutasidebet1  = $tmp->v_mutasi_debet;
						}else{
							$vmutasidebet1  = 0;
						}
						if($tmp){
							$vmutasikredit1 = $tmp->v_mutasi_kredit;
						}else{
							$vmutasikredit1 = 0;
						}
						if($tmp) {
							$vsaldoak1    = $tmp->v_saldo_akhir;
						}else{
							$vsaldoak1    = 0;
						}

						$acckredit     = $icoa;
						$namakredit    = $this->mmaster->namaacc($acckredit);
						$saldoawkredit = $this->mmaster->carisaldo($acckredit,$iperiode);
						if($tmp) {
							$vsaldoaw2    = $tmp->v_saldo_awal;
						}else{
							$vsaldoaw2    = 0;
						}
						if($tmp){
							$vmutasidebet2  = $tmp->v_mutasi_debet;
						}else{
							$vmutasidebet2  = 0;
						}
						if($tmp){
							$vmutasikredit2 = $tmp->v_mutasi_kredit;
						}else{
							$vmutasikredit2 = 0;
						}
						if($tmp){
							$vsaldoak2    = $tmp->v_saldo_akhir;
						}else{
							$vsaldoak2    = 0;
						}
						$this->mclasskas->insertdetail($ialokasi,$ikb,$isupplier,$idtap,$ddtap,$vjumlah,$vsisa,$i,$eremark,$icoa);
						$this->mclasskas->inserttransitemkredit($acckredit,$ireff,$namakredit,'f','t',$iarea,$egirodescription,$vjumlah,$dalokasi,$icoa);
						$this->mclasskas->inserttranskredit($ikb,$iarea,$dalokasi,$icoa);
						$this->mclasskas->inserttransitemdebet($accdebet,$ireff,$namadebet,'t','t',$iarea,$egirodescription,$vjumlah,$dalokasi,$icoa);
						$this->mclasskas->updatenota($idtap,$isupplier,$vjumlah);
						$this->mclasskas->insertgldebet($accdebet,$ireff,$namadebet,'f',$vjumlah,$dalokasi,$iarea,$dalokasi,$icoa,$egirodescription,$icoa);
						$this->mclasskas->insertglkredit($acckredit,$ireff,$namakredit,'t',$vjumlah,$dalokasi,$iarea,$dalokasi,$icoa,$egirodescription,$icoa);
						$this->mclasskas->updatebank($ikb,$icoa,$iarea,$vjumlah);
					}*/
					/*-----------------------------------------------------------| END PEMBULATAN |----------------------------------------------------*/
					

                    if ($this->db->trans_status() === FALSE){
                        $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
                    }else{
                        $this->db->trans_commit();
                        $data = array(
                            'sukses' => true,
                            'inomor'   => $ialokasi,
                        );
                    }
				//}
			//}
	}

//     public function update(){

//         $data = check_role($this->i_menu, 3);
//         if(!$data){
//             redirect(base_url(),'refresh');
//         }
        
//             $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
//             $ipp 			        = $this->input->post('ipp', TRUE);
//             $remark                 = $this->input->post('eremark', TRUE);
//             $jml                    = $this->input->post('jml', TRUE);
//             $ppcancel               = 'f';
//             $query 	                = $this->db->query("SELECT current_timestamp as c");
// 	   		$row   	            = $query->row();
// 	    	$now	            = $row->c;
//             $dpp                = $this->input->post("dpp",true);
//             $this->db->trans_begin(); 
// 	    	$this->mmaster->updateheader($ipp, $ikodemaster, $remark, $dpp, $now);
//         if ($ipp != '' && $ikodemaster != ''){
//             $cekada = $this->mmaster->cek_dataheader($ipp);
//             // echo $jml;
//             //     die;
//             if($cekada->num_rows() > 0){
//             for($i=1;$i<=$jml;$i++){
                
//                 $imaterial= $this->input->post('imaterial'.$i, TRUE);
//                 $isatuan= $this->input->post('isatuan'.$i, TRUE); 
//                 $nquantity= $this->input->post('qty'.$i, TRUE); 
//                 $eremark= $this->input->post('eremark'.$i, TRUE);
//                 $vprice = '0';
//                 $fopcomplete = 'f';
//                 $cekdet = $this->mmaster->cekdatadetail($ipp, $imaterial);
//                 if($cekdet->num_rows() > 0){
//                     $this->mmaster->updatedetail($nquantity,$ipp,$imaterial);
//                 }else{
//                     $this->mmaster->insertdetail($ipp, $imaterial ,$isatuan ,
//                                                 $nquantity ,$vprice ,$fopcomplete,$i);
//                 }
//                 $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ipp);
//             }
//         }
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
//                     'kode'      => $ipp
//                 );
//             }
//         }else{
//             $data = array(
//                 'sukses' => false,
//             );
//         }
//         $this->load->view('pesan', $data);  
// }

   public function view(){
    	$dfrom = $this->input->post('dfrom');
    	$dto   = $this->input->post('dto');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

        // public function approve(){
        // $data = check_role($this->i_menu, 3);
        // if(!$data){
        //     redirect(base_url(),'refresh');
        // }
        
        //     $ipp= $this->input->post('ipp', TRUE);
        //     $data = array(
        //         'folder' => $this->global['folder'],
        //         'title' => "View ".$this->global['title'],
        //         'title_list' => 'List '.$this->global['title'],
        //         'data' => $this->mmaster->cek_data($ipp)->row(),
        //         'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        //     );
        
        //     $this->Logger->write('Membuka Menu Approve PP'.$this->global['title']);
        
        //     $this->load->view($this->global['folder'].'/vformapprove', $data);
        // }

        public function approve(){

            $data = check_role($this->i_menu, 3);
            if(!$data){
                redirect(base_url(),'refresh');
            }
    
            $ipp = $this->uri->segment('4');
    
            $data = array(
                'folder' => $this->global['folder'],
                'title' => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data' => $this->mmaster->cek_data($ipp)->row(),
                'data2' => $this->mmaster->cek_datadet($ipp)->result(),
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
        }
        public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ipp 			= $this->input->post('ipp', TRUE);
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
            $now	  = $row->c;
            $this->db->trans_begin(); 
        $this->mmaster->approve($ipp, $now);
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
                    'kode'      => $ipp
                );
            }
            $this->load->view('pesan', $data);  
        }
    }
/* End of file Cform.php */
