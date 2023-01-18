<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10803';

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

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'company'   => $this->session->userdata('id_company'),
            'username'  => $this->session->userdata('username')
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }
    
    function datanotafrom(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom      = $this->input->get('dfrom');
            $dto        = $this->input->get('dto');
            $to         = $this->input->get('to');

            if($to=='' || $to%10==0){
                $to='';
			    $this->db->select(" a.i_nota from tm_nota a
				                    where (upper(a.i_customer) like '%$cari%' or upper(a.i_nota) like '%$cari%')
                                    and a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')",false);
            }else{
			    $this->db->select(" a.i_nota from tm_nota a
				                    where (upper(a.i_customer) like '%$cari%' or upper(a.i_nota) like '%$cari%')
                                    and a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')
                                    and a.i_nota<='$to'",false);
            }
            $query = $this->db->get();
            foreach($query->result() as  $nota){
                    $filter[] = array(
                    'id' => $nota->i_nota,  
                    'text' => $nota->i_nota
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datanotato(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $dfrom      = $this->input->get('dfrom');
            $dto        = $this->input->get('dto');
            $from         = $this->input->get('from');

            if($from==''){
                $this->db->select(" a.* from tm_nota a
                              where (upper(a.i_customer) like '%$cari%' or upper(a.i_nota) like '%$cari%')
                              and a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')
                              order by a.i_nota", false);
            }else{
                $this->db->select(" a.* from tm_nota a
                              where (upper(a.i_customer) like '%$cari%' or upper(a.i_nota) like '%$cari%')
                              and a.d_nota >= to_date('$dfrom','dd-mm-yyyy') and a.d_nota <= to_date('$dto','dd-mm-yyyy')
                              and a.i_nota >= '$from'
                              order by a.i_nota", false);
            }
            $query = $this->db->get();
            foreach($query->result() as  $nota){
                    $filter[] = array(
                    'id' => $nota->i_nota,  
                    'text' => $nota->i_nota
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

        $istart	= $this->input->post('istart');
        $iseri	= $this->input->post('iseripajak');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        $notafrom = $this->input->post('notafrom');
        $notato   = $this->input->post('notato');
        if($istart!='' && $dfrom!='' && $dto!='' && $notafrom!='' && $notato!=''){
            $query = $this->db->query("select i_sj, i_nota, i_area, d_nota from tm_nota
                                        where 
                                        d_nota >= to_date('$dfrom','dd-mm-yyyy') and d_nota <= to_date('$dto','dd-mm-yyyy')
                                        and i_nota >= '$notafrom' and i_nota <= '$notato' and f_nota_cancel = 'f'
                                        order by d_nota, i_nota", false);
            if ($query->num_rows() > 0){
                $x=0;
                $this->db->trans_begin();
                settype($istart,"integer");
		        foreach($query->result() as $row){
		            $x++;
                    $no=$istart;
                    settype($no,"string");
		            $a=strlen($no);
                    $dnota=$row->d_nota;
                    if($dnota!=''){
				        $tmp=explode("-",$dnota);
				        $th=$tmp[0];
				        $bl=$tmp[1];
				        $hr=$tmp[2];
                        $thbl=substr($th,2,2).$bl;
			        }
                    if($thbl>'1303'){
                      while($a<5){
                        $no="0".$no;
                        $a=strlen($no);
                      }
                    }else{
                      while($a<6){
                        $no="0".$no;
                        $a=strlen($no);
                      }
                    }		        
                    $ifakturkomersial	= "FK-".$thbl."-".$no;
                    if($x==1){
                      $fakturfrom=$ifakturkomersial;
                      $fakturto=$ifakturkomersial; 
                    }else{
                      $fakturto=$ifakturkomersial;
                    }
                    //$this->db->trans_begin();
                    //var_dump($thbl);
                    //var_dump($ifakturkomersial);
                   // $this->mmaster->updatenota($row->i_sj,$row->i_nota,$row->i_area,$ifakturkomersial);
                    if($thbl>'1303'){
  		                if(strlen($ifakturkomersial)==13){
	  	                  $this->mmaster->updatenota($row->i_sj,$row->i_nota,$row->i_area,$ifakturkomersial);
		                }
                    }else{
  		                if(strlen($ifakturkomersial)==14){
	  	                  $this->mmaster->updatenota($row->i_sj,$row->i_nota,$row->i_area,$ifakturkomersial);
		                }
                    }
	                  $istart++;
                }
                $this->mmaster->close($fakturfrom,$fakturto,$iseri);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();

                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ifakturkomersial);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ifakturkomersial
                    );
                }
              
                $istart--;
            }      
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }
}

/* End of file Cform.php */
