<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20206';

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
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){

        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }

        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $ikategori = $this->input->post('kategori', TRUE);
        if($ikategori == ''){
            $ikategori = $this->uri->segment(6);
            if($ikategori == ''){
                $ikategori = 'ALL';
            }
        }

        $ijenis = $this->input->post('jenis', TRUE);
        if($ijenis == ''){
            $ijenis = $this->uri->segment(7);
            if($ijenis == ''){
                $ijenis = 'ALL';
            }
        }

        $interval	= $this->mmaster->interval($dfrom,$dto);

        $data = array(
            'folder'      => $this->global['folder'],
            'title'       => $this->global['title'],
            'dfrom'       => date('d-m-Y', strtotime($dfrom)),
            'dto'         => date('d-m-Y', strtotime($dto)),
            'ikategori'   => $ikategori,
            'ijenis'      => $ijenis,
            'interfall'   => $this->mmaster->interval($dfrom,$dto)->row(),
            'data'        => $this->mmaster->bacaperiode($dfrom,$dto,$ikategori,$ijenis,$interval)->result(),
            'kategori'    => $this->mmaster->bacakategori(),
            'namekategori'=> $this->mmaster->getnamekategori($ikategori)->row(),
            'namejenis'   => $this->mmaster->getnamejenis($ijenis)->row()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);

    }

    public function getjenisbarang(){
        $ikategori = $this->input->post('ikategori');
        $query = $this->mmaster->getjenisbarang($ikategori);
        if($query->num_rows()>0) {
            $c         = "";
            $jenis  = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_type_code." >".$row->i_type_code." - ".$row->e_type_name."</option>";
            }
            $kop  = "<option value=\"ALL\">Semua Jenis Barang".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"ALL\">Semua Jenis Barang</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }
}

/* End of file Cform.php */
