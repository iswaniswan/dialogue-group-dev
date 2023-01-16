<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1060110';

    public function __construct(){
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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'hariini'   => $this->mmaster->cekoptoday($iarea)->row()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        echo $this->mmaster->data($this->global['folder'], $iarea);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idcompany = $this->session->userdata('id_company');
        date_default_timezone_set('Asia/Jakarta');
        $date = date('d-m-Y');
        $time = date('H:i:s');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'bankcoa'       => $this->mmaster->bacabank(),
            'nama_company'  => $this->mmaster->company($idcompany)->row()->name,
            'date'          => $date,
            'time'          => $time
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);

        /*uang kertas*/
        $txtkertas100000    = intval($this->input->post('txtkertas100000',TRUE));
        $txtkertas50000     = intval($this->input->post('txtkertas50000',TRUE));
        $txtkertas20000     = intval($this->input->post('txtkertas20000',TRUE));
        $txtkertas10000     = intval($this->input->post('txtkertas10000',TRUE));
        $txtkertas5000      = intval($this->input->post('txtkertas5000',TRUE));
        $txtkertas2000      = intval($this->input->post('txtkertas2000',TRUE));
        $txtkertas1000      = intval($this->input->post('txtkertas1000',TRUE));
        $txtkertas500       = intval($this->input->post('txtkertas500',TRUE));

        $totalkertas100000  = intval($this->input->post('hdtotalkertas100000',TRUE));
        $totalkertas50000   = intval($this->input->post('hdtotalkertas50000',TRUE));
        $totalkertas20000   = intval($this->input->post('hdtotalkertas20000',TRUE));
        $totalkertas10000   = intval($this->input->post('hdtotalkertas10000',TRUE));
        $totalkertas5000    = intval($this->input->post('hdtotalkertas5000',TRUE));
        $totalkertas2000    = intval($this->input->post('hdtotalkertas2000',TRUE));
        $totalkertas1000    = intval($this->input->post('hdtotalkertas1000',TRUE));
        $totalkertas500     = intval($this->input->post('hdtotalkertas500',TRUE));

        /*uang logam*/
        $txtlogam1000       = intval($this->input->post('txtlogam1000',TRUE));
        $txtlogam500        = intval($this->input->post('txtlogam500',TRUE));
        $txtlogam200        = intval($this->input->post('txtlogam200',TRUE));
        $txtlogam100        = intval($this->input->post('txtlogam100',TRUE));
        $txtlogam50         = intval($this->input->post('txtlogam50',TRUE));

        $totallogam1000     = intval($this->input->post('hdtotallogam1000',TRUE));
        $totallogam500      = intval($this->input->post('hdtotallogam500',TRUE));
        $totallogam200      = intval($this->input->post('hdtotallogam200',TRUE));
        $totallogam100      = intval($this->input->post('hdtotallogam100',TRUE));
        $totallogam50       = intval($this->input->post('hdtotallogam50',TRUE));

        $totalatm           = intval($this->input->post('totalatm',TRUE));
        $totallain          = intval($this->input->post('totallain',TRUE));
        $totaldanaharus     = intval($this->input->post('totaldanaharus',TRUE));

        $txtket             = $this->input->post('txtket',TRUE);
        $dfrom1             = $this->input->post('dfrom1',TRUE);
        $dfrom1             = date('Y-m-d', strtotime($dfrom1));
        $dto1               = $this->input->post('dto1',TRUE);
        $dto1               = date('Y-m-d', strtotime($dto1));
        $dfrom2             = $this->input->post('dfrom2',TRUE);
        $dfrom2             = date('Y-m-d', strtotime($dfrom2));
        $dto2               = $this->input->post('dto2',TRUE);
        $dto2               = date('Y-m-d', strtotime($dto2));
        $totalnota          = $this->input->post('totalnota',TRUE);
        $totalkasbon        = $this->input->post('totalkasbon',TRUE);
        $thbl               = date('Y-m-d');
        $this->db->trans_begin();
        $rm                 = $this->mmaster->runningnumber($thbl, $iarea);
        $this->mmaster->opnamekas($rm,$thbl,$totaldanaharus,$txtket,$iarea,$totalnota,$dfrom1,$dto1,$totalkasbon,$dfrom2,$dto2,$totalatm,$totallain);
        $this->mmaster->opnamekasdetail($rm,$txtkertas100000,$txtkertas50000,$txtkertas20000,$txtkertas10000,$txtkertas5000,$txtkertas2000,$txtkertas1000,$txtkertas500,$totalkertas100000,$totalkertas50000,$totalkertas20000,$totalkertas10000,$totalkertas5000,$totalkertas2000,$totalkertas1000,$totalkertas500,$txtlogam1000,$txtlogam500,$txtlogam200,$txtlogam100,$txtlogam50,$totallogam1000,$totallogam500,$totallogam200,$totallogam100,$totallogam50);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Input KAS Opname kode area'.$iarea.' No:'.$rm);
            $data = array(
                'sukses'    => true,
                'kode'      => $rm
            );
        }        
        $this->load->view('pesan', $data);  
    }

    public function printopname(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idcompany = $this->session->userdata('id_company');
        $iopname = $this->uri->segment(4);
        $data = array(
            'folder'   => $this->global['folder'],
            'title'    => "Detail ".$this->global['title'],
            'company'  => $this->mmaster->company($idcompany)->row()->name,
            'i_opname' => $iopname,
            'opkas'    => $this->mmaster->opkas($iopname),
            'opbank'   => $this->mmaster->opbank($iopname),
            'opdetail' => $this->mmaster->opdetail($iopname),
        );

        $this->Logger->write('Membuka Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformprint', $data);
    }
}

/* End of file Cform.php */
