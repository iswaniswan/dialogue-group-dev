<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050902';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
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
            
    	echo $this->mmaster->data($from,$to, $this->i_menu);
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
    function dataproduct(){
        // select distinct(a.i_product) , b.e_product_namewip, a.i_color , c.e_color_name
        // from duta_prod.tr_polacutting a
        // inner join duta_prod.tr_product_wip b on a.i_product=b.i_product_wip
        // inner join duta_prod.tr_color c on a.i_color=c.i_color 
        // order by a.i_product
        $filter = [];
        $ikodemaster = $this->uri->segment(4);
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
                    'id' => $product->i_product,  
                    'text' => $product->i_product.' - '.$product->e_product_namewip
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }
    function getproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('i_product');
        $this->db->select("distinct(a.i_product) , b.e_product_namewip, a.i_color , c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b","a.i_product=b.i_product_wip");
            $this->db->join("tr_color c","a.i_color=c.i_color ");
            $this->db->where("UPPER(a.i_product)", $iproduct);
            // $this->db->order_by('a.i_product', 'ASC');            
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
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_bonk = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($i_bonk)->row(),
            'data2' => $this->mmaster->cek_datadet($i_bonk)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
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
        
        $i_bonk        = $this->input->post('i_bonk', TRUE);
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancelheader($i_bonk);
        $data = $this->mmaster->cancelsemuadetail($i_bonk);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Cancel bon k qc set '.$i_bonk);
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
                    $this->Logger->write('Cancel Cancel bon k qc set detail '.$i_bonk);
                    echo json_encode($data);
                    $this->edit();
                }
            }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            echo "kokoko";
            die;
            redirect(base_url(),'refresh');
        }
        
            $dbon		= $this->input->post('dbonk', TRUE);
			$igudang    = $this->input->post('ikodemaster',TRUE);
			if($dbon!=''){
				$tmp=explode("-",$dbon);
				$th=$tmp[2];
				$bl=$tmp[1];
				$hr=$tmp[0];
				$dbon=$th."-".$bl."-".$hr;
				$thbl=$th.$bl;
            }
           
			$eremark  = $this->input->post('eremark', TRUE);
			$jml	  = $this->input->post('jml', TRUE);
            
			if($dbon!=''){

				if($jml == 0){
					exit; 
				}else{
            
				// $this->load->model('bonkeluarqcset/Mmaster');
                $ibon	=$this->mmaster->runningnumber($thbl);
                // echo "okok";
                // die;
    
				$this->mmaster->insertheader($ibon, $dbon, $eremark, $igudang);
					for($i=1;$i<=$jml;$i++){
					  $iproduct			= $this->input->post('product'.$i, TRUE);
					  $icolor	        = $this->input->post('icolor'.$i, TRUE);
					  $eproductname		= $this->input->post('eproductname'.$i, TRUE);
					  $nquantity   		= $this->input->post('nquantity'.$i, TRUE);
					  $eremark		  	= $this->input->post('eremark'.$i, TRUE);

						if(($nquantity == 0)||($nquantity == '')){
						exit;
						}else{
							$this->mmaster->insertdetail($ibon,$iproduct,$icolor,$eproductname,$nquantity,$eremark,$i);
						}
					}
					$query=$this->db->query("select * from f_bonqcset_detailitem('$ibon')");
			    	if ($query->num_rows() > 0){
			    		$no=0;
			        	foreach($query->result() as $row){
			        		$no++;
			          		$iproduct 	   = $row->i_product;
			          		$icolor   	   = $row->i_color;
			          		$imaterial 	   = $row->i_material;
			          		$ematerialname = $row->e_material_name;
			          		$nquantity 	   = $row->n_quantity;
			          		$nitemno 	   = $row->n_item_no;
			          		$nodetail 	   = $row->no_detail;

						if(($iproduct == '') || ($icolor == '')  || ($imaterial == '') || ($nquantity == '')   ){
						exit;
						}else{
							$this->mmaster->insertdetailproduct($ibon,$iproduct,$icolor,$imaterial,$ematerialname,$nquantity,$nitemno,$nodetail);
						}

			      		}   
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
                    'kode'      => $ibon,
                );
            }
            $this->load->view('pesan', $data);

        }  
    }
}

/* End of file Cform.php */
