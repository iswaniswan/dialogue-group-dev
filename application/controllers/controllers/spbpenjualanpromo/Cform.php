<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '21103';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->departement  = $this->session->i_departement;
        $this->company      = $this->session->id_company;
        $this->level        = $this->session->i_level;
        $this->username     = $this->session->username;

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
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
            'dfrom'     => $dfrom,
            'dto'       => $dto,
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

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
            'salesman'   => $this->mmaster->sales()->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'number'     => "SPB-".date('ym')."-123456",
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

    public function cekkode() {
        if ($this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE))->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    /** Get Data Promo */
	public function get_promo()
	{
		$filter = [];
		$tanggal = $this->input->get('tanggal', TRUE);
		if ($tanggal != '') {
			$data = $this->mmaster->get_promo(str_replace("'", "", $this->input->get('q')), $tanggal);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->id_promo,
					'text' => $row->i_promo_code . ' - ' . $row->e_promo_name,
				);
			}
		}
		echo json_encode($filter);
	}

    /** Get Detail Promo */
	public function get_promo_detail()
	{
		header("Content-Type: application/json", true);
		$ipromo = $this->input->post('i_promo', TRUE);
		$query  = array(
			'promo' => $this->mmaster->get_promo_detail($ipromo)->result_array()
		);
		echo json_encode($query);
	}

    /** Get Data Area */
	public function get_area()
	{
		$filter = [];
		$i_promo = $this->input->get('i_promo');
		$f_all_area = $this->input->get('f_all_area');
		if ($i_promo != '' && $f_all_area != '') {
			$data = $this->mmaster->get_area(str_replace("'", "", $this->input->get('q')), $i_promo, $f_all_area);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->id,
					'text' => $row->i_area . ' - ' . $row->e_area,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => 'Pilih Promo',
			);
		}
		echo json_encode($filter);
	}
    
    /*----------  CARI PELANGGAN  ----------*/

	public function get_customer()
	{
		$filter = [];
		$cari 	= str_replace("'", "", $this->input->get('q'));
		$i_area = $this->input->get('i_area');
		$i_promo = $this->input->get('i_promo');
		$f_all_customer = $this->input->get('f_all_customer');
		if ($i_area != '' && $i_promo != '') {
			$data = $this->mmaster->get_customer($cari, $i_area, $i_promo, $f_all_customer);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->id,
					'text' => $row->i_customer . ' - ' . $row->e_customer_name,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => 'Pilih Promo & Area',
			);
		}
		echo json_encode($filter);
	}

    /*----------  GET DETAIL PELANGGAN  ----------*/
    
    public function getdetailcustomer()
    {
        header("Content-Type: application/json", true);
        if ($this->input->post('idcustomer',TRUE)!='') {
            echo json_encode($this->mmaster->getdetailcustomer($this->input->post('idcustomer'))->result_array());
        }else{
            echo json_encode(0);
        }
    }

    /** Get Detail Customer */
	public function get_customer_detail()
	{
		header("Content-Type: application/json", true);
		$icustomer = $this->input->post('idcustomer', TRUE);
		$query  = array(
			'header' => $this->mmaster->get_customer_detail($icustomer)->result_array()
		);
		echo json_encode($query);
	}

    /*----------  GET KELOMPOK BARANG  ----------*/

    public function kelompok()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->kelompok(str_replace("'", "", $this->input->get('q')),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Kategori',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,  
                    'text' => ucwords(strtolower($key->e_nama_kelompok)),
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Tidak Boleh Kosong!',
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET JENIS BARANG  ----------*/

    public function jenis()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->jenis(str_replace("'", "", $this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Jenis',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_type_code,  
                    'text' => ucwords(strtolower($key->e_type_name)),
                );
            }          
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => 'Bagian Tidak Boleh Kosong!',
            );
        }
        echo json_encode($filter);
    }

    /** Get Product */
	public function get_product()
	{
		$filter = [];
		$cari = str_replace("'", "", $this->input->get('q'));
		$i_price_group = $this->input->get('i_price_group');
		$i_promo = $this->input->get('i_promo');
		$f_all_product = $this->input->get('f_all_product');
		if ($i_price_group != '' && $i_promo != '') {
			$data = $this->mmaster->get_product($cari, $i_price_group, $i_promo, $f_all_product);
			foreach ($data->result() as $row) {
				$filter[] = array(
					'id'   => $row->id,
					'text' => $row->i_product_base . ' - ' . $row->e_product_basename,
				);
			}
		} else {
			$filter[] = array(
				'id'   => null,
				'text' => 'Pilih Pelanggan / Promo',
			);
		}
		echo json_encode($filter);
	}

    /** Get Detail Product */
	public function get_product_price()
	{
		header("Content-Type: application/json", true);
		$i_price_group = $this->input->post('i_price_group', TRUE);
		$i_product = $this->input->post('i_product', TRUE);
		$i_promo = $this->input->post('i_promo');
		$f_all_product = $this->input->post('f_all_product');
		$query  = $this->mmaster->get_product_price($i_price_group, $i_product, $i_promo, $f_all_product)->result_array();
		echo json_encode($query);
	}

    /*----------  GET BARANG JADI  ----------*/    

    public function product()
    {
        $filter = [];
        $data = $this->mmaster->product(str_replace("'","",$this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ijenis'),$this->input->get('ibagian'),$this->input->get('idharga'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->i_product_base.' - '.$row->e_product_basename,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => 'Tidak Ada Data Barang',
            );   
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/    

    public function getproduct()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->getproduct($this->input->post('idproduct'),$this->input->post('idharga'),$this->input->post('ddocument'),$this->input->post('idcustomer'))->result_array());
    }

    /*----------  SIMPAN DATA  ----------*/    

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ipromo         = $this->input->post('ipromo', TRUE);
        $idcustomer     = $this->input->post('icustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomer', TRUE);
        $idarea         = $this->input->post('iarea', TRUE);
        $idsales        = $this->input->post('isales', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $etypespb       = $this->input->post('etypespb', TRUE);
        $vdiskon        = str_replace(",","",$this->input->post('ndiskontotal', TRUE));
        $vkotor         = str_replace(",","",$this->input->post('nkotor', TRUE));
        $vppn           = str_replace(",","",$this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",","",$this->input->post('nbersih', TRUE));
        $idharga        = $this->input->post('idkodeharga', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $vdpp           = str_replace(",","",$this->input->post('vdpp', TRUE));
        $jml            = $this->input->post('jml', TRUE);
        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
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
                $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp,$idharga,$etypespb,$ipromo);
                for ($i = 0; $i < $jml; $i++) {
                    $idproduct    = $this->input->post('idproduct'.$i, TRUE);
                    $nquantity    = str_replace(",","",$this->input->post('nquantity'.$i, TRUE));
                    $vprice       = str_replace(",","",$this->input->post('vharga'.$i, TRUE));
                    $ndiskon1     = str_replace(",","",$this->input->post('ndisc1'.$i, TRUE));
                    $ndiskon2     = str_replace(",","",$this->input->post('ndisc2'.$i, TRUE));
                    $ndiskon3     = str_replace(",","",$this->input->post('ndisc3'.$i, TRUE));
                    $ndiskon4     = str_replace(",","",$this->input->post('ndisc4'.$i, TRUE));
                    $vdiskon1     = str_replace(",","",$this->input->post('vdisc1'.$i, TRUE));
                    $vdiskon2     = str_replace(",","",$this->input->post('vdisc2'.$i, TRUE));
                    $vdiskon3     = str_replace(",","",$this->input->post('vdisc3'.$i, TRUE));
                    $vdiskon4     = str_replace(",","",$this->input->post('vdisc4'.$i, TRUE));
                    $vdiskonplus  = str_replace(",","",$this->input->post('vdiscount'.$i, TRUE));
                    $vtotal       = str_replace(",","",$this->input->post('vtotal'.$i, TRUE));
                    $vtotaldiskon = str_replace(",","",$this->input->post('vtotaldiskon'.$i, TRUE));
                    $eremark      = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($nquantity > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetail($id,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$ndiskon4,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskon4,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
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
            'area'       => $this->mmaster->area()->result(),
            'salesman'   => $this->mmaster->sales()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4), $this->uri->segment(7)),
            'number'     => "SPB-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

     public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument != '') {
            $ddocument  = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ipromo         = $this->input->post('ipromo', TRUE);
        $ibagianold     = $this->input->post('ibagianold', TRUE);
        $idcustomer     = $this->input->post('icustomer', TRUE);
        $ecustomername  = $this->input->post('ecustomer', TRUE);
        $idarea         = $this->input->post('iarea', TRUE);
        $idsales        = $this->input->post('isales', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $vdiskon        = str_replace(",","",$this->input->post('ndiskontotal', TRUE));
        $vkotor         = str_replace(",","",$this->input->post('nkotor', TRUE));
        $vppn           = str_replace(",","",$this->input->post('vppn', TRUE));
        $vbersih        = str_replace(",","",$this->input->post('nbersih', TRUE));
        $idharga        = $this->input->post('idkodeharga', TRUE);
        $eremarkh       = $this->input->post('eremarkh', TRUE);
        $vdpp           = str_replace(",","",$this->input->post('vdpp', TRUE));
        $jml            = $this->input->post('jml', TRUE);
        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian!='' && $idcustomer!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$eremarkh,$vdpp,$idharga,$ipromo);
                $this->mmaster->delete($id);
                for ($i = 1; $i <= $jml; $i++) {
                    $idproduct    = $this->input->post('idproduct'.$i, TRUE);
                    $nquantity    = str_replace(",","",$this->input->post('nquantity'.$i, TRUE));
                    $vprice       = str_replace(",","",$this->input->post('vharga'.$i, TRUE));
                    $ndiskon1     = str_replace(",","",$this->input->post('ndisc1'.$i, TRUE));
                    $ndiskon2     = str_replace(",","",$this->input->post('ndisc2'.$i, TRUE));
                    $ndiskon3     = str_replace(",","",$this->input->post('ndisc3'.$i, TRUE));
                    $ndiskon4     = str_replace(",","",$this->input->post('ndisc4'.$i, TRUE));
                    $vdiskon1     = str_replace(",","",$this->input->post('vdisc1'.$i, TRUE));
                    $vdiskon2     = str_replace(",","",$this->input->post('vdisc2'.$i, TRUE));
                    $vdiskon3     = str_replace(",","",$this->input->post('vdisc3'.$i, TRUE));
                    $vdiskon4     = str_replace(",","",$this->input->post('vdisc4'.$i, TRUE));
                    $vdiskonplus  = str_replace(",","",$this->input->post('vdiscount'.$i, TRUE));
                    $vtotal       = str_replace(",","",$this->input->post('vtotal'.$i, TRUE));
                    $vtotaldiskon = str_replace(",","",$this->input->post('vtotaldiskon'.$i, TRUE));
                    $eremark      = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($nquantity > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetail($id,$idproduct,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$ndiskon4,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskon4,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark);
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
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4),$this->uri->segment(7))->result(),
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
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4), $this->uri->segment(7))->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/    

    public function changestatus() 
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
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

    /*----------  REDIRECT KE FORM CETAK  ----------*/
    
    public function cetak()
    {

        $data = check_role($this->i_menu, 5);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Cetak ".$this->global['title'],
            'id'         => $this->uri->segment(4),
            'bagian'     => $this->mmaster->bagian()->result(),
            'area'       => $this->mmaster->area()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4)),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4), $this->uri->segment(7)),
            'history'    => $this->mmaster->history($this->uri->segment(5),$this->uri->segment(6))->row(),
            'dhistory'   => $this->uri->segment(5),
            'piutang'    => $this->mmaster->piutang($this->uri->segment(6)),
        );

        $this->Logger->write('Membuka Menu Cetak '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }

    /*----------  UPDATE STATUS PRINT  ----------*/
    
    public function updateprint(){

        $id = $this->input->post('id', true);
        $this->db->trans_begin();
        $this->mmaster->updateprint($id);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo 'fail';
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Print ' . $this->global['folder'] . ' Id : ' . $id);
            echo $id;
        }
    }
    
}
/* End of file Cform.php */