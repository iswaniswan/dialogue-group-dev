<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '103082';

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
        $iuser  = $this->session->userdata("username");
        $query1 = $this->db->query("select * from tr_spg where i_user='$iuser' order by i_spg");
        $hasil1 = $query1->row();
        $ispg = $hasil1->i_spg;
        $query = $this->db->query("select * from tr_area where i_area in ( select i_area from tr_spg where i_user='$iuser') order by i_area");
        $hasil = $query->row();
        $iarea = $hasil->i_area;
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'ispg'              => $ispg,
            'iarea'             => $iarea,
            'icustomer'         => '',
            'espgname'          => '',
            'ecustomername'     => '',
            'iorderpb'           => '',
            'jmlitem'           => '',
            'tgl'               => date('d-m-Y'),
            'isi'               => $this->mmaster->baca($ispg,$iarea)->row()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function databarang(){
        $filter = [];
        $icustomer = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name, c.v_product_retail
                                    from tr_product_motif a,tr_product c
                                    where a.i_product=c.i_product
                                    and (upper(a.i_product) like '%$cari%' 
                                    or upper(c.e_product_name) like '%$cari%')
                                    order by a.e_product_motifname asc",false);
            foreach($query->result() as  $produk){
                    $filter[] = array(
                    'id' => $produk->i_product,  
                    'text' => $produk->i_product.'-'.$produk->e_product_name
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
        //$icustomer = $this->uri->segment('4');
        $data=$this->db->query("select a.i_product, a.i_product_motif, a.e_product_motifname, c.e_product_name,c.v_product_retail
                                from tr_product_motif a,tr_product c
                                where a.i_product=c.i_product
                                and upper(a.i_product) = '$iproduct'
                                order by a.e_product_motifname asc");
        //$data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iorderpb 		= $this->input->post('iorderpb', TRUE);
		$dorderpb 		= $this->input->post('dorderpb', TRUE);
		if($dorderpb!=''){
			$tmp=explode("-",$dorderpb);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dorderpb=$th."-".$bl."-".$hr;
			$thbl=substr($th,2,2).$bl;
		}
		$iarea	= $this->input->post('iarea', TRUE);
		$ispg   = $this->input->post('ispg', TRUE);
		$icustomer= $this->input->post('icustomer', TRUE);
		$jml		= $this->input->post('jml', TRUE);
        if($dorderpb!='' && $jml>0){
            $bisa=true;
            for($j=1;$j<=$jml;$j++){
                $nquantityorder = $this->input->post('nquantityorder'.$j, TRUE);
				$nquantityorder	= str_replace(',','',$nquantityorder);
				$nquantitystock = $this->input->post('nquantitystock'.$j, TRUE);
				$nquantitystock	= str_replace(',','',$nquantitystock);
                if($nquantityorder=='' || $nquantitystock==''){
                  $bisa=false;
                  break;
                }
            }
            if($bisa){
                $this->db->trans_begin();
                $iorderpb	=$this->mmaster->runningnumber($thbl);
				$this->mmaster->insertheader($iorderpb, $dorderpb, $iarea, $ispg, $icustomer);
				for($i=1;$i<=$jml;$i++){
					$iproduct			  = $this->input->post('iproduct'.$i, TRUE);
				    $iproductgrade	= 'A';
				    $iproductmotif	= $this->input->post('motif'.$i, TRUE);
				    $eproductname		= $this->input->post('eproductname'.$i, TRUE);
				    $nquantityorder = $this->input->post('nquantityorder'.$i, TRUE);
				    $nquantityorder	= str_replace(',','',$nquantityorder);
				    $nquantitystock = $this->input->post('nquantitystock'.$i, TRUE);
				    $nquantitystock	= str_replace(',','',$nquantitystock);
                    $eremark        = $this->input->post('eremark'.$i, TRUE);
					$cek_brg_opb    = $this->db->query("select i_orderpb from tm_orderpb_item where i_orderpb = '$iorderpb' and i_area = '$iarea' 
					                                    and i_customer = '$icustomer' and i_product = '$iproduct' and i_product_motif = '$iproductmotif' and i_product_grade = '$iproductgrade'")->num_rows();
				    if($nquantityorder>0 && $cek_brg_opb == 0){
				      $this->mmaster->insertdetail( $iorderpb,$iarea,$icustomer,$dorderpb,$iproduct,$iproductmotif,$iproductgrade,$nquantityorder,$nquantitystock,$i,$eproductname,$eremark);
				    }
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Input Order Konsinyasi :'.$this->global['title'].' Kode : '.$iorderpb);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => 'Tambah Order Konsinyasi '.$iorderpb
                    );
                }
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
