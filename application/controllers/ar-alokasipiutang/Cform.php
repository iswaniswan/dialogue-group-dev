<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '2040216';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama / Judul Menu  ----------*/        
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->i_level            = $this->session->i_level;
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/        
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    /*----------  DEFAULT CONTROLLERS  ----------*/
    
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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  DAFTAR DATA SPB  ----------*/
    
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

        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }
    
    /*----------  REDIRECT LIST KN  ----------*/
    
    public function indexx()
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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlistvoucher', $data);
    }

    /*----------  DAFTAR DATA SPB  ----------*/
    
    public function datareferensi()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->datareferensi($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }

    public function get_data_referensi()
    {
        $dfrom = $this->uri->segment(4);
        $dto = $this->uri->segment(5);

        echo $this->mmaster->get_data_referensi($dfrom, $dto, $this->global['folder'], $this->i_menu);
    }

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id 		= $this->uri->segment(4);
		$dfrom 		= $this->uri->segment(5);
		$dto 		= $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'id'         => $id,
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
            // 'data'       => $this->mmaster->getdataref($this->uri->segment(4),$this->uri->segment(7),$this->uri->segment(8))->row(),
            'data' 	 => $this->mmaster->get_data_rv($id)->row(),
            'number'     => "AKB-".date('ym')."-123456",
            'all_area' => $this->mmaster->area()->result(),
            'all_customer' => $this->mmaster->get_all_customer()->result()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number() 
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode() 
    {
        if ($this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE))->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    /*----------  GET REFERENSI BERDASARKAN CUSTOMER  ----------*/    

    public function referensi()
    {
        $filter = [];
        if ($this->input->get('idcustomer')!='') {
            $data   = $this->mmaster->referensi(str_replace("'", "", $this->input->get('q')),$this->input->get('idcustomer'));
            if ($data->num_rows()>0) {
                foreach($data->result() as  $key){
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => $key->i_document.' - '.$key->groupfaktur,
                    );
                }          
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => 'Tidak Ada Data!',
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Pelanggan Tidak Boleh Kosong!',
            );   
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/
    
    public function getdetailref()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->getdetailref($this->input->post('idnota'),$this->input->post('idcustomer'))->result_array());
    }

    /*----------  SIMPAN DATA  ----------*/    

    public function __simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument          = $this->input->post('idocument', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     !="") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $idcustomer         = $this->input->post('idcustomer', TRUE);
        $ecustomer          = $this->input->post('ecustomer', TRUE);
        $grouppartner       = $this->input->post('grouppartner', TRUE);
        $idreferensi        = $this->input->post('idreferensi', TRUE);
        $idreferensiitem    = $this->input->post('idreferensiitem', TRUE);
        $idjenis            = $this->input->post('idjenis', TRUE);
        $vjumlah            = str_replace(",","",$this->input->post('vjumlah', TRUE));
        $vlebih             = str_replace(",","",$this->input->post('vjumlahlebih', TRUE));
        $eremarkh           = $this->input->post('eremarkh', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if ($idocument!='' && $ddocument!='' && $idjenis!='' && $idreferensiitem!='' && $idreferensi!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomer,$idreferensi,$idreferensiitem,$idjenis,$vjumlah,$vlebih,$eremarkh, $grouppartner);
                for ($i = 1; $i <= $jml; $i++) {
                    $idnota      = $this->input->post('idnota'.$i, TRUE);
                    $groupfaktur = $this->input->post('groupfaktur'.$i, TRUE);
                    $vbayar  = str_replace(",","",$this->input->post('vbayar'.$i, TRUE));
                    $vsisa   = str_replace(",","",$this->input->post('vsisa'.$i, TRUE));
                    $eremark = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($vbayar > 0 && ($idnota!=null || $idnota!='')) {
                        $this->mmaster->insertdetail($id,$idreferensi,$idreferensiitem,$idnota,$vbayar,$vsisa,$eremark, $groupfaktur);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
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
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id_company = $this->session->userdata('id_company');

        $id_bagian = $this->input->post('ibagian');
        // $i_document = $this->input->post('i_document');
        /** regenerate nomor dokumen */
        $i_document = $this->mmaster->generate_nomor_dokumen($id_bagian);

        $d_document = $this->input->post('d_document');
        /** reformat tanggal */
        $d_document = formatYmd($d_document);

        $i_rv = $this->input->post('i_rv');
        $i_rv_id = $this->input->post('i_rv_id');
        $i_rv_item = $this->input->post('i_rv_item');
        $d_bukti = $this->input->post('d_bukti');
        $e_bank_name = $this->input->post('e_bank_name');
        $id_customer = $this->input->post('id_customer');
        $id_area = $this->input->post('id_area');

        $v_jumlah = $this->input->post('v_jumlah');
        $v_jumlah = str_replace("Rp. ", "", $v_jumlah);
        $v_jumlah = str_replace(",", "", $v_jumlah);
        $v_jumlah = str_replace(".", "", $v_jumlah);

        $v_lebih = $this->input->post('v_lebih');
        $v_lebih = str_replace("Rp. ", "", $v_lebih);
        $v_lebih = str_replace(",", "", $v_lebih);
        $v_lebih = str_replace(".", "", $v_lebih);

        $items = $this->input->post('items');        

        $result = [
            'sukses' => false,
            'kode' => '-',
            'id' => '-'
        ];

        /** insert table */
        $this->db->trans_begin();            
        $this->mmaster->insert_alokasi_piutang($i_document, $i_rv, $i_rv_item, $d_document, $e_bank_name, $v_jumlah, $v_lebih,
                                                null, $id_area, $id_customer, $id_bagian);

        $insert_id = $this->db->insert_id();

        foreach ($items as $item) {
            $id_nota = $item['id_nota'];
            $d_nota = $item['dnota'];
            
            $v_nilai = $item['vnilai'];
            $v_nilai = str_replace(".", "", $v_nilai);
            $v_nilai = str_replace(",", "", $v_nilai);

            $v_jumlah = $item['vjumlah'];
            $v_jumlah = str_replace(".", "", $v_jumlah);
            $v_jumlah = str_replace(",", "", $v_jumlah);

            $v_sisa = $item['vsesa'];
            $v_sisa = str_replace(".", "", $v_sisa);
            $v_sisa = str_replace(",", "", $v_sisa);

            $v_lebih = $item['vlebih'];
            $v_lebih = str_replace(".", "", $v_lebih);
            $v_lebih = str_replace(",", "", $v_lebih);
            
            $e_remark = @$item['eremark'];

            $this->mmaster->insert_alokasi_piutang_item($insert_id, $i_alokasi_item=null, $i_rv_item, $id_nota, $d_nota, 
                                                        $v_jumlah, $v_sisa, $n_item_no=null, $e_remark, $id_company, $id_area);
        }        
            
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $result = [
                'sukses' => true,
                'kode' => $i_document,
                'id' => $insert_id
            ];
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $insert_id);
            echo json_encode($result);
            return;
        } 

        $this->db->trans_rollback();
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            // 'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
            'datadetail' => $this->mmaster->data_detail($this->uri->segment(4))->result(),
            'number'     => "AKB-".date('ym')."-123456",
            'all_area' => $this->mmaster->area()->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/
    
    public function __update() 
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id                 = $this->input->post('id', TRUE);
        $idocumentold       = $this->input->post('idocumentold', TRUE);
        $idocument          = $this->input->post('idocument', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument      != '') {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $ibagianold         = $this->input->post('ibagianold', TRUE);
        $idcustomer         = $this->input->post('idcustomer', TRUE);
        $ecustomer          = $this->input->post('ecustomer', TRUE);
        $idreferensi        = $this->input->post('idreferensi', TRUE);
        $idreferensiitem    = $this->input->post('idreferensiitem', TRUE);
        $idjenis            = $this->input->post('idjenis', TRUE);
        $vjumlah            = str_replace(",","",$this->input->post('vjumlah', TRUE));
        $vlebih             = str_replace(",","",$this->input->post('vjumlahlebih', TRUE));
        $eremarkh           = $this->input->post('eremarkh', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if ($id!='' && $idocument!='' && $ddocument!='' && $idjenis!='' && $idreferensiitem!='' && $idreferensi!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomer,$idreferensi,$idreferensiitem,$idjenis,$vjumlah,$vlebih,$eremarkh);
                $this->mmaster->delete($id);
                for ($i = 1; $i <= $jml; $i++) {
                    $idnota      = $this->input->post('idnota'.$i, TRUE);
                    $groupfaktur = $this->input->post('groupfaktur'.$i, TRUE);
                    $vbayar  = str_replace(",","",$this->input->post('vbayar'.$i, TRUE));
                    $vsisa   = str_replace(",","",$this->input->post('vsisa'.$i, TRUE));
                    $eremark = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($vbayar > 0 && ($idnota!=null || $idnota!='')) {
                        $this->mmaster->insertdetail($id,$idreferensi,$idreferensiitem,$idnota,$vbayar,$vsisa,$eremark,$groupfaktur);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }


    public function update()
    {
        /** update tanggal & detail->keterangan saja */        
        $id = $this->input->post('id');
        $i_document = $this->input->post('i_document');
        $d_alokasi = $this->input->post('d_alokasi');
        $d_alokasi = formatYmd($d_alokasi);
        $items = $this->input->post('items');

        $this->db->trans_begin();
        $this->mmaster->update_header($id, $d_alokasi);

        foreach ($items as $item) {
            $id = $item['id'];
            $e_remark = $item['eremark'];
            $this->mmaster->update_alokasi_piutang_item($id, $e_remark);
        }

        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $i_document,
                'id'     => $id
            );
            $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);  
            echo json_encode($data);
            return;          
        }

        $this->db->trans_rollback();
        $data = array(
            'sukses' => false,
            'kode'   => $idocument,
            'id'     => $id
        );
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->data_detail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/
    
    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            // 'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            // 'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
            'datadetail' => $this->mmaster->data_detail($this->uri->segment(4))->result(),
            'bagian' => $this->mmaster->bagian()
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/    

    public function __changestatus() 
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        if ($istatus=='6') {
            $this->mmaster->updatesisa($id);
            $this->mmaster->updatesisanota($id);
        }
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    public function generate_nomor_dokumen()
    {
        $number = "";

        if ($this->input->post('tgl', TRUE) != '') {
            $id_bagian = $this->input->post('ibagian');
            $number = $this->mmaster->generate_nomor_dokumen($id_bagian);
        }

        echo json_encode($number);
    }

    public function get_all_customer()
    {
        $q = $this->input->get('q');
        $id_area = $this->input->get('id_area');

        $data = [];

        $query = $this->mmaster->get_all_customer(str_replace("'", "", $q), $id_area);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->e_customer_name
            );
        }
        echo json_encode($data);
    }

    public function get_nota()
	{
		$filter = [];
		$q = str_replace("'", "", $this->input->get('q'));
		$id_area = $this->input->get('id_area');
		$id_customer = $this->input->get('id_customer');
        
		if (($id_area != '' || $id_area != null) && ($id_customer != '' || $id_customer != null)) {
			
            $data = $this->mmaster->get_nota($q, $id_area, $id_customer);
			
            foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->id,
					'text' => $row->i_document,
				);
			}
		} 
		echo json_encode($filter);
	}

    public function get_detail_nota()
	{
		header("Content-Type: application/json", true);
		$query = '';
		$id_nota = $this->input->post('id_nota', TRUE);
		if ($id_nota != '' || $id_nota != null) {
			$query = $this->mmaster->get_detail_nota($id_nota)->result_array();
		}
		echo json_encode($query);
	}

}
/* End of file Cform.php */