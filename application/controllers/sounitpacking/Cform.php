<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2051107';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'tahun'     => date('Y'),
            'msg'       => '',
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function lokasipacking(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_unit_packing");
            $this->db->like("UPPER(i_unit_packing)", $cari);
            $this->db->or_like("UPPER(e_nama_packing)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $row){
                    $filter[] = array(
                    'id'   => trim($row->i_unit_packing), 
                    'text' => trim($row->i_unit_packing).'-'.trim($row->e_nama_packing),
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

     function getkodemaster(){
        header("Content-Type: application/json", true);
        $ilokasigudang = $this->input->post('ilokasigudang');
            $this->db->select('a.i_kode_lokasi, b.i_kode_master');
            $this->db->from('tr_lokasi_gudang a');
            $this->db->join('tr_master_gudang b', 'b.i_kode_lokasi= a.i_kode_lokasi');
            $this->db->where("i_kode_lokasi", $ilokasigudang);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function add(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ilokasigudang = $this->input->post('ilokasipacking', TRUE);
        $iperiodebl    = $this->input->post('iperiodebl', TRUE);
        $iperiodeth    = $this->input->post('iperiodeth', TRUE);
        // $per           = $iperiodeth.$iperiodebl;            
        $ikodemaster   = $this->input->post('ikodepacking', TRUE);
        $data =array(
                        'folder'     => $this->global['folder'],
                        'title'      => "Tambah ".$this->global['title'],
                        'title_list' => 'List '.$this->global['title'],
                        'bulan'      => $iperiodebl,
                        'tahun'      => $iperiodeth,
                        'data'       => $this->mmaster->getkodemaster($ikodemaster)->row(),
                        'data2'      => $this->mmaster->getproduct($ilokasipacking)->result(),
                        // 'lokasigudang'      => $this->mmaster->getproduct($ilokasigudang)->result(), 
                );
        $data['msg']= "Input stok opname untuk bulan ".$iperiodebl." ".$iperiodeth." tidak dapat diproses karena SO di bulan sebelumnya belum beres..!";
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminput', $data);
    
        
        // $ilokasigudang = $this->input->post('ilokasigudang', TRUE);
        // $ikodemaster   = $this->input->post('ikodemaster', TRUE);
        // $iperiodebl    = $this->input->post('iperiodebl', TRUE);
        // $iperiodeth    = $this->input->post('iperiodeth', TRUE);  
        // if ($iperiodebl > 1) {
        //     $bulan_sebelumnya = $iperiodebl-1;
        //     $tahun_sebelumnya = $iperiodeth;
        // }
        // else if ($iperiodebl == 1) {
        //     $bulan_sebelumnya = 12;
        //     $tahun_sebelumnya = $iperiodeth-1;
        // }
        // if ($iperiodebl == '01')
        //     $nama_bln = "Januari";
        //     else if ($iperiodebl == '02')
        //         $nama_bln = "Februari";
        //     else if ($iperiodebl == '03')
        //         $nama_bln = "Maret";
        //     else if ($iperiodebl == '04')
        //         $nama_bln = "April";
        //     else if ($iperiodebl == '05')
        //         $nama_bln = "Mei";
        //     else if ($iperiodebl == '06')
        //         $nama_bln = "Juni";
        //     else if ($iperiodebl == '07')
        //         $nama_bln = "Juli";
        //     else if ($iperiodebl == '08')
        //         $nama_bln = "Agustus";
        //     else if ($iperiodebl == '09')
        //         $nama_bln = "September";
        //     else if ($iperiodebl == '10')
        //         $nama_bln = "Oktober";
        //     else if ($iperiodebl == '11')
        //         $nama_bln = "November";
        //     else if ($iperiodebl == '12')
        //         $nama_bln = "Desember";

        // $cek_so_sebelum = $this->mmaster->cekso_sebelum($ilokasigudang, $iperiodebl, $iperiodeth);
        // if ($cek_so_sebelum->num_rows() > 0){
        //      $data =array(
        //             'folder'     => $this->global['folder'],
        //             'title'      => "Tambah ".$this->global['title'],
        //             'title_list' => 'List '.$this->global['title'],
        //             'bulan'      => $iperiodebl,
        //             'tahun'      => $iperiodeth,
        //             'lokasigudang'      => $this->mmaster->getlokasi($ilokasigudang, $ikodemaster)->row(), 
        //     );
        //     $data['msg']= "Input stok opname untuk bulan ".$nama_bln." ".$iperiodeth." tidak dapat diproses karena SO di bulan sebelumnya belum beres..!";
        //     $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        //     $this->load->view($this->global['folder'].'/vform', $data);

        // }else{
        //     $cek_so = $this->mmaster->cekso($ilokasigudang, $iperiodebl, $iperiodeth);
        //     if($cek_so->num_rows() > 0){
        //         $data =array(
        //                 'folder'     => $this->global['folder'],
        //                 'title'      => "Tambah ".$this->global['title'],
        //                 'title_list' => 'List '.$this->global['title'],
        //                 'bulan'      => $iperiodebl,
        //                 'tahun'      => $iperiodeth,
        //                  'lokasigudang'      => $this->mmaster->getlokasi($ilokasigudang, $ikodemaster)->row(), 
        //         );
        //         $data['msg']= "Input stok opname untuk bulan ".$nama_bln." ".$iperiodeth." tidak dapat diproses karena di bulan berikutnya sudah ada SO..!";
        //         $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        //         $this->load->view($this->global['folder'].'/vform', $data);

        //     }else{
        //         $data = array(
        //             'folder'     => $this->global['folder'],
        //             'title'      => "Tambah ".$this->global['title'],
        //             'title_list' => 'List '.$this->global['title'],
        //             'bulan'      => $iperiodebl,
        //             'tahun'      => $iperiodeth,
        //             'lokasigudang'      => $this->mmaster->getlokasi($ilokasigudang, $ikodemaster)->row(), 
        //             'stok'       => $this->mmaster->get_all_stok_opname_bahanbaku($ilokasigudang, $iperiodebl, $iperiodeth)->result(),
        //         );

        //         $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        //         $this->load->view($this->global['folder'].'/vforminput', $data);
        //     }
        // }
    }
    

    public function simpan(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
            $ilokasigudang = $this->input->post('ilokasigudang', TRUE);
            $iperiodebl    = $this->input->post('iperiodebl', TRUE);
            $iperiodeth    = $this->input->post('iperiodeth', TRUE);
            $per           = $iperiodeth.$iperiodebl;  
            $dso           = $this->input->post('dso', TRUE);
            $jml         = $this->input->post('jml', TRUE);
            if($dso!=''){
                $tmp=explode("-",$dso);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $yearmonth = $th.$bl;
                $dateso=$th."-".$bl."-".$hr;
                
            }
            $this->db->trans_begin(); 
            $iso = $this->mmaster->runningnumber($yearmonth, $ilokasigudang);
            // $query2 = $this->db->query(" SELECT * FROM tt_stokopname_gdjadi where i_so = '$iso' 
            // and d_periode = '$per' and i_kode_lokasi = '$ilokasigudang'",FALSE);
            // if ($query2->num_rows() > 0) {

            // }
                $this->mmaster->insertheader($iso, $dateso, $per, $ilokasigudang);
                for($i=1;$i<=$jml;$i++){
                    // if 
                    $iproduct       = $this->input->post('ikodebarang'.$i, TRUE);
                    $icolor         = $this->input->post('icolor'.$i, TRUE);
                    $iproductgrade        = $this->input->post('iproductgrade'.$i, TRUE);
                    $qty      = $this->input->post('quantity'.$i, TRUE); 
                    // $eremark        = $this->input->post('eremark'.$i, TRUE);
                    // $vprice         = '0';
                    // $fopcomplete    = 'f';
                    $this->mmaster->insertdetail($iso, $per, $iproduct, $icolor, $qty, $iproductgrade, $i);
                }
            // }    
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iso,
                );
            }
    $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */