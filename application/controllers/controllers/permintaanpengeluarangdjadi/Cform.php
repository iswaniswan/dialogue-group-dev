<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050119';

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

    public function index()
    {
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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  DAFTAR SJ MAKLOON  ----------*/
    
    public function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    /*----------  MASUK FORM TAMBAH DATA  ----------*/    
    
    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan()->result(),
            'number'        => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    /*----------  CEK NO DOK  ----------*/
    
    public function cekkode()
    {
        
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    /*----------  GET NO DOK  ----------*/    

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '' && $this->input->post('ibagian', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CARI PIC INTERNAL  ----------*/

    public function pic()
    {
        $filter = [];
        $data = $this->mmaster->pic(str_replace("'","",$this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_nama_karyawan
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

    /*----------  CARI JENIS PENGELUARAN  ----------*/

    public function jenis()
    {
        $filter = [];
        if ($this->input->get('idtujuan') != '') {
            $data = $this->mmaster->jenis(str_replace("'","",$this->input->get('q')),$this->input->get('idtujuan'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->e_jenis_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Pengeluaran Int / Ek harus dipilih!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI BAGIAN YANG TYPE NYA SAMA  ----------*/

    public function gudang()
    {
        $filter = [];
        if ($this->input->get('ibagian') != '') {
            $data = $this->mmaster->gudang(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->e_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Bagian Pembuat Harus Dipilih!"
            );
        }
        echo json_encode($filter);
    }   

    /*----------  CARI PARTNER  ----------*/

    public function partner()
    {
        $filter = [];
        if ($this->input->get('idtujuan') != '' && $this->input->get('idtujuan') != '') {
            $data = $this->mmaster->partner(str_replace("'","",$this->input->get('q')),$this->input->get('idtujuan'),$this->input->get('idjenis'),$this->input->get('ibagian'));
            if ($data->num_rows()>0) {
                $group   = [];
                $arr     = [];
                foreach ($data->result() as $key) {
                    $arr[] = $key->grouppartner;
                }
                $unique_data = array_unique($arr);
                foreach($unique_data as $val) {
                    $child  = [];
                    foreach ($data->result() as $row) {
                        if ($val==$row->grouppartner) {
                            $child[] = array(
                                'id' => $row->id.'|'.$row->grouppartner, 
                                'text' => $row->e_name, 
                            );
                        }
                    }
                    $filter[] = array(
                        'id' => 0,
                        'text' => strtoupper($val),
                        'children' => $child
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Jenis Tujuan & Internal / Eksternal Harus Dipilih!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI MATERIAL  ----------*/

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') != '' || $this->input->get('ibagian') != '') {
            $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'));
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->i_product_base.' - '.$row->e_product_basename.' - '.$row->e_color_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Cari Berdasarkan Nama / Kode Barang (Sesuai Bagian)!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $itujuan        = $this->input->post('itujuan', TRUE);
        $ijeniskeluar   = $this->input->post('ijeniskeluar', TRUE);
        $igudang        = $this->input->post('igudang', TRUE);
        $dperkiraan     = $this->input->post('dperkiraan', TRUE);
        if ($dperkiraan != '') {
            $dperkiraan = date('Y-m-d', strtotime($dperkiraan));
        }else{
            $dperkiraan = null;
        }
        $imemo          = $this->input->post('imemo', TRUE);
        $dmemo          = $this->input->post('dmemo', TRUE);
        if ($dmemo != '') {
            $dmemo      = date('Y-m-d', strtotime($dmemo));
        }else{
            $dmemo      = null;
        }
        $ipartnertype   = explode('|', $this->input->post('ipartner', TRUE));
        $ipartner       = $ipartnertype[0];
        $typepartner    = $ipartnertype[1];
        $picinternal    = $this->input->post('picinternal', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $piceksternal   = $this->input->post('piceksternal', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if ($idocument!='' && $ddocument!='' && $ibagian != '' && $ijeniskeluar != '' && $ipartner != '' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id,$idocument,$ddocument,$ibagian,$itujuan,$ijeniskeluar,$ipartner,$typepartner,$picinternal,$piceksternal,$imemo,$dmemo,$eremarkh,$igudang,$dperkiraan);
            for ($x = 1; $x <= $jml ; $x++) {
                $idproduct  = $this->input->post('idproduct'.$x, TRUE);
                $nquantity  = str_replace(",","",$this->input->post('nquantity'.$x, TRUE));
                $eremark    = $this->input->post('eremark'.$x, TRUE);
                if (($idproduct != "" || $idproduct != NULL) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id,$idproduct,$nquantity,$eremark);
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
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $itujuan        = $this->input->post('itujuan', TRUE);
        $ijeniskeluar   = $this->input->post('ijeniskeluar', TRUE);
        $igudang        = $this->input->post('igudang', TRUE);
        $dperkiraan     = $this->input->post('dperkiraan', TRUE);
        if ($dperkiraan != '') {
            $dperkiraan = date('Y-m-d', strtotime($dperkiraan));
        }else{
            $dperkiraan = null;
        }
        $imemo          = $this->input->post('imemo', TRUE);
        $dmemo          = $this->input->post('dmemo', TRUE);
        if ($dmemo != '') {
            $dmemo      = date('Y-m-d', strtotime($dmemo));
        }else{
            $dmemo      = null;
        }
        $ipartnertype   = explode('|', $this->input->post('ipartner', TRUE));
        $ipartner       = $ipartnertype[0];
        $typepartner    = $ipartnertype[1];
        $picinternal    = $this->input->post('picinternal', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $piceksternal   = $this->input->post('piceksternal', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian != '' && $ijeniskeluar != '' && $ipartner != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$ddocument,$ibagian,$itujuan,$ijeniskeluar,$ipartner,$typepartner,$picinternal,$piceksternal,$imemo,$dmemo,$eremarkh,$igudang,$dperkiraan);
            $this->mmaster->delete($id);
            for ($x = 1; $x <= $jml ; $x++) {
                $idproduct  = $this->input->post('idproduct'.$x, TRUE);
                $nquantity  = str_replace(",","",$this->input->post('nquantity'.$x, TRUE));
                $eremark    = $this->input->post('eremark'.$x, TRUE);
                if (($idproduct != "" || $idproduct != NULL) && $nquantity > 0) {
                    $this->mmaster->simpandetail($id,$idproduct,$nquantity,$eremark);
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
                    'id'     => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU VIEW  ----------*/
    
    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Detail ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
    }    

}
/* End of file Cform.php */