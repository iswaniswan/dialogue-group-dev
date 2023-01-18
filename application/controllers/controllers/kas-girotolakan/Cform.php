<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040306';

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
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
        );


        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "GRT-".date('ym')."-123456"            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function referensikriling(){
        $filter = [];
        $data   = $this->mmaster->referensikriling(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->i_document,
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

    public function getgiro(){
        $ikriling = $this->input->get('ikriling');
        $data     = $this->mmaster->getgiro($ikriling);
        // if($query->num_rows()>0) {
        //     $c  = "";
        //     $jenis = $query->result();
        //     foreach($jenis as $row) {
        //         $c.="<option value=".$row->id_document_reff." >".$row->i_giro."</option>";
        //     }
        //     $kop  = "<option value=\"\">Pilih Nomor Giro".$c."</option>";
        //     echo json_encode(array(
        //         'kop'   => $kop
        //     ));
        // }else{
        //     $kop  = "<option value=\"\">Nomor Giro Kosong</option>";
        //     echo json_encode(array(
        //         'kop'    => $kop,
        //         'kosong' => 'kopong'
        //     ));
        // }
        if ($data->num_rows()>0) {
            $groupreff   = [];
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->i_giro;
            }
            $unique_data = array_unique($arr);
            foreach($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val==$row->i_giro) {
                        $child[] = array(
                            'id' => $row->id_document_reff, 
                            'text' => $row->i_document, 
                        );
                    }
                }
                $filter[] = array(
                    'id' => 0,
                    'text' => "Nomor Giro : " .strtoupper($val),
                    'children' => $child
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    function getitemgiro(){
        header("Content-Type: application/json", true);
        $ireferensigiro  = $this->input->post('ireferensigiro');
        $ikriling        = $this->input->post('ikriling');

        $data = $this->mmaster->getitemgiro($ireferensigiro, $ikriling);

        $dataa = array(
            'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getitemgiro($ireferensigiro, $ikriling)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument      = $this->input->post('idocument', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikriling       = $this->input->post('ikriling', TRUE);
        $ireferensigiro = $this->input->post('ireferensigiro', TRUE);
        $usegiro        = $this->input->post('usegiro', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
        $id = $this->mmaster->runningid();
        $this->mmaster->insertheader($id, $idocument, $ibagian, $datedocument, $ikriling, $ireferensigiro, $eremark);

        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $ikriling != '' && $ireferensigiro != '' && $usegiro != '' && $jml>0) {
            for($i=1;$i<=$jml;$i++){ 
                $idkrilinggiro  = $this->input->post('idkrilinggiro'.$i, TRUE);
                $idgiro         = $this->input->post('idgiro'.$i, TRUE);
                $ibank          = $this->input->post('ibank'.$i, TRUE);
                $itujuan        = $this->input->post('itujuan'.$i, TRUE);
                $ipenyetor      = $this->input->post('ipenyetor'.$i, TRUE);
                $jumlah         = str_replace(',','',$this->input->post('jumlah'.$i,TRUE));

                $this->mmaster->insertdetail($id, $idkrilinggiro, $idgiro, $ibank, $itujuan, $ipenyetor, $jumlah);

                if($usegiro == '1'){
                    $this->mmaster->updategiro($id, $idkrilinggiro, $idgiro, $jumlah);
                }else if($usegiro == '2'){
                    $this->mmaster->updatekliring($id, $idkrilinggiro, $idgiro, $jumlah);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view(){
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        $idcompany  = $this->session->userdata('id_company');   

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $dfrom, 
            'dto'           => $dto,
            'id'            => $id,
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
        );
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}
/* End of file Cform.php */