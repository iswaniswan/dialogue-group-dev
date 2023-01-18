<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '20706';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index(){
       $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "" . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'jenis'         => $this->mmaster->jenis(),
            'dfrom'         => $awal,
            'dto'           => $akhir,
            'ldfrom'        => $this->uri->segment(4),
            'ldto'          => $this->uri->segment(5),
            'seripajak'     => $this->mmaster->seripajak()->row(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getiseri(){
        $ipajak = "";
        if ($this->input->post('ijenis', TRUE) != '') {
            $ipajak = $this->mmaster->getiseri($this->input->post('ijenis', TRUE));
        }
        echo json_encode($ipajak);
    }

    public function getnota(){
        $filter = [];
        $ijenis = $this->input->get('ijenis', TRUE);
        $dawal  = $this->input->get('jtawal', TRUE);
        $dakhir = $this->input->get('jtakhir', TRUE);
// var_dump($ijenis,$dawal,$dakhir);
// die();
        $data   = $this->mmaster->getnota($ijenis, $dawal, $dakhir);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $key){
                    $filter[] = array(
                    'id'   => $key->id,  
                    'text' => $key->i_document.' || '.$key->d_document,
                );
            }    
        }else{
              $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }      
        echo json_encode($filter);
    }

    public function getdetail(){
        header("Content-Type: application/json", true);
        $ijenis     = $this->input->post('ijenis', FALSE);
        $jtawal     = $this->input->post('jtawal', FALSE);
        $jtakhir    = $this->input->post('jtakhir', FALSE);
        // $inotafrom  = $this->input->post('inotafrom', FALSE);
        // $inotato    = $this->input->post('inotato', FALSE);

        $query  = array(
            'detail' => $this->mmaster->getdetail($ijenis, $jtawal, $jtakhir)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');
        $ibagian    = $this->input->post('ibagian', TRUE);
        $ijenis     = $this->input->post('ijenis', TRUE);
        $ipajakawal = $this->input->post('ipajakawal', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        if ($ijenis != '') {
            $this->db->trans_begin();
            $xnofaktur = '';
            
            $i_pajakk        = $this->input->post('ipajak[]', TRUE);
            $jenis_faktur    = $this->input->post('jenisfaktur[]', TRUE);
            $id_faktur       = $this->input->post('idfaktur[]', TRUE);
            $i_faktur        = $this->input->post('ifaktur[]', TRUE);
            $no = 0;
            foreach ($i_pajakk as $ipajakk) {
                $ipajakk        = $ipajakk;
                $jenisfaktur    = $jenis_faktur[$no];
                $idfaktur       = $id_faktur[$no];
                $ifaktur        = $i_faktur[$no];                

                if($no == ($jml-1)){
                    $a = $ipajakk;
                    $this->mmaster->insertseripajak($a);
                }
                $this->mmaster->updatenota($jenisfaktur, $idfaktur, $ifaktur, $ipajakk, $idcompany, $ipajakawal);  
                
                $xnofaktur .= $ifaktur." || ";
                $no++;
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' .substr($xnofaktur,0,-3));
                $data = array(
                    'sukses' => true,
                    'kode'   => substr($xnofaktur,0,-3),
                    'id'     => $idfaktur,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idfaktur,
            );
        }
        $this->load->view('pesan2', $data);
    
    }
}   
/* End of file Cform.php */