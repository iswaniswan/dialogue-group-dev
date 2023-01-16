<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1050503';

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
        $igiro 			= $this->input->post('igiro', TRUE);
		$dgiro			= $this->input->post('dgiro', TRUE);
		if($dgiro!=''){
			$tmp=explode("-",$dgiro);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dgiro=$th."-".$bl."-".$hr;
		}
        $isupplier		= $this->input->post('isupplier', TRUE);
		$dpv			= $this->input->post('dpv', TRUE);
		if($dpv!=''){
			$tmp=explode("-",$dpv);
			$pvth=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dpv=$pvth."-".$bl."-".$hr;
		}
		$dgiroduedate	= $this->input->post('dgiroduedate', TRUE);
		if($dgiroduedate!=''){
			$tmp=explode("-",$dgiroduedate);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dgiroduedate=$th."-".$bl."-".$hr;
		}
		$egirodescription= $this->input->post('egirodescription', TRUE);
		$egirobank		= $this->input->post('egirobank', TRUE);
		$vjumlah		= $this->input->post('vjumlah', TRUE);
		$vjumlah		= str_replace(',','',$vjumlah);
		$vsisa			= $vjumlah;
        if((isset($igiro) && $igiro != '') && 
            (isset($isupplier) && $isupplier != '') && 
            (isset($dgiro) && $dgiro != '') && 
            (isset($dpv) && $dpv != '') && 
            (isset($dgiroduedate) && $dgiroduedate != '') && 
            (isset($vjumlah) && $vjumlah != '')){
                $ipv = $this->mmaster->runningnumberpv($pvth);
				$this->db->trans_begin();
				$this->mmaster->insert($igiro,$isupplier,$ipv,$dgiro,$dpv,$dgiroduedate,$egirodescription,$egirobank,
									   $vjumlah,$vsisa);
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Input Giro DGU'.$this->global['title'].' No : '.$igiro.' Supplier: '.$isupplier);
            $data = array(
                'sukses'    => true,
                'kode'      => 'Input Giro DGU No: '.$igiro
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
