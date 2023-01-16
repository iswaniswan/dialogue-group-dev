<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050402';

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
            'igiro'   => ''
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function datasupplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select * from tr_supplier where (upper(i_supplier) like '%$cari%' or upper(e_supplier_name) like '%$cari%')",false);
            foreach($data->result() as  $supplier){
                    $filter[] = array(
                    'id' => $supplier->i_supplier,  
                    'text' => $supplier->i_supplier.'-'.$supplier->e_supplier_name
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
        $ikuk 	= $this->input->post('ikuk', TRUE);
		$dkuk	= $this->input->post('dkuk', TRUE);
		if($dkuk!=''){
			$tmp=explode("-",$dkuk);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dkuk=$th."-".$bl."-".$hr;
			$tahun=$th;
		}
		$ebankname			= $this->input->post('ebankname', TRUE);
		$eareaname			= $this->input->post('eareaname', TRUE);
		$isupplier			= $this->input->post('isupplier', TRUE);
		$esuppliername		= $this->input->post('esuppliername', TRUE);
		$eremark			= $this->input->post('eremark', TRUE);
		$vjumlah			= $this->input->post('vjumlah', TRUE);
		$vjumlah			= str_replace(',','',$vjumlah);
		$vsisa				= $this->input->post('vsisa', TRUE);
		$vsisa				= str_replace(',','',$vsisa);
        if (($ikuk != '') && ($tahun!='')){
            $this->db->trans_begin();
			$cek=$this->mmaster->cek($ikuk,$tahun);
			if(!$cek){							
				$this->mmaster->insert($ikuk,$dkuk,$tahun,$ebankname,$isupplier,
									   $eremark,$vjumlah,$vsisa);
			}else{
				$nomor="Bukti transfer ".$ikuk." sudah ada, untuk mengedit lewat menu edit Transfer";
			}
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Transfer Uang Keluar'.$this->global['title'].' No : '.$ikuk.' Supplier: '.$isupplier);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Input Transfer Uang Keluar No: '.$ikuk
                );
            }
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
