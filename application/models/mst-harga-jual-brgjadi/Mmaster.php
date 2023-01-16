<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

	function data($dberlaku, $i_menu, $folder)
	{
		$datatables = new Datatables(new CodeigniterAdapter);
		$idcompany  = $this->session->userdata('id_company');
		$now = date('d-m-Y');

		$datatables->query("
                          SELECT DISTINCT
                             x.no,
                             x.d_akhir_tmp,
                             x.id_hargabarang,
                             x.id_product_base,
                             x.i_product_base,
                             x.e_product_basename,
                             x.e_color_name,
							 x.e_jenis_name,
                             x.v_price,
                             x.d_berlaku,
                             x.d_akhir,
                             x.i_menu,
                             x.folder,
                             x.tanggal_berlaku,
                             x.id_company,
                             x.i_level, 
                             x.e_level_name,
                             x.i_status, 
                             x.e_status_name, 
                             x.label_color,
                             x.status
                          FROM
                             (
                                SELECT
                                   0 as no,
                                   case
                                      when
                                         a.d_akhir is not null 
                                      then
                                         a.d_akhir 
                                      else
                                         '5000-01-01' 
                                   end
                                   as d_akhir_tmp, a.id as id_hargabarang, a.id_product_base, c.i_product_base, c.e_product_basename, a.v_price, a.d_berlaku, a.d_akhir, a.id_company, d.e_color_name,
                                   case
                                      when
                                         a.f_status = TRUE 
                                      then
                                         'Aktif' 
                                      else
                                         'Tidak Aktif' 
                                   end
                                   as status, 
                                   '$i_menu' as i_menu, 
                                   '$folder' as folder, 
                                   '$dberlaku' as tanggal_berlaku ,
                                    f.i_level, 
                                    l.e_level_name,
                                    a.i_status, 
                                    e_status_name, 
                                    label_color,
									e_jenis_name
                                FROM
                                   tr_harga_jualbrgjd a 
                                   inner join tr_status_document h on (h.i_status = a.i_status)
                                   LEFT JOIN
                                      tr_product_base c 
                                      on a.id_product_base = c.id 
                                      and a.id_company = c.id_company 
                                    LEFT JOIN
                                      tr_color d 
                                      on c.i_color = d.i_color 
                                      and c.id_company = d.id_company 
                                    left join tr_menu_approve f on (a.i_approve_urutan = f.n_urut and f.i_menu = '$i_menu')
                                    left join public.tr_level l on (f.i_level = l.i_level)
									LEFT JOIN tr_jenis_barang_keluar hh ON (hh.id = a.id_jenis_barang_keluar)
                                WHERE
                                   a.id_company = '$idcompany' 
                             )
                             AS x 
                          WHERE
                             x.d_berlaku <= to_date('$dberlaku', 'dd-mm-yyyy') 
                             AND x.d_akhir_tmp >= to_date('$dberlaku', 'dd-mm-yyyy')
                          ", FALSE);

		$datatables->edit(
			'status',
			function ($data) {
				$id             = trim($data['id_hargabarang']);
				$kode           = trim($data['id_product_base']);
				$folder         = $data['folder'];
				$id_menu        = $data['i_menu'];
				$status         = $data['status'];
				if ($status == 'Aktif') {
					$warna = 'success';
				} else {
					$warna = 'danger';
				}
				$data    = '';
				$combine = $id . '|' . $kode;
				if (check_role($id_menu, 3)) {
					$data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$combine\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
				} else {
					$data   .= "<span class=\"label label-$warna\">$status</span>";
				}
				return $data;
			}
		);

		$datatables->edit('e_status_name', function ($data) {
			$i_status = $data['i_status'];
			if ($i_status == '2') {
				$data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
			}
			return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
		});

		$datatables->edit('v_price', function ($data) {
			return "Rp. " . $data['v_price'];
		});

		$datatables->edit('d_berlaku', function ($data) {
			$d_berlaku = $data['d_berlaku'];
			if ($d_berlaku == '') {
				return '';
			} else {
				return date("d-m-Y", strtotime($d_berlaku));
			}
		});

		$datatables->edit('d_akhir', function ($data) {
			$d_akhir = $data['d_akhir'];
			if ($d_akhir == '') {
				return '';
			} else {
				return date("d-m-Y", strtotime($d_akhir));
			}
		});

		$datatables->add('action', function ($data) {
			$id         = $data['id_hargabarang'];
			$kodebrg    = trim($data['id_product_base']);
			$i_menu     = $data['i_menu'];
			$i_level = $data["i_level"];
			$i_status = $data["i_status"];
			$folder     = $data['folder'];
			$dberlaku   = $data['d_berlaku'];
			$dfrom      = $data['tanggal_berlaku'];

			$data = '';
			if (check_role($i_menu, 2)) {
				$data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$kodebrg/\",\"#main\"); return false;'><i class='ti-eye text-success fa-lg mr-3'></i></a>";
			}
			if (check_role($i_menu, 3)) {
				$data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$kodebrg/$dberlaku/$dfrom/\",\"#main\"); return false;'><i class='ti-pencil-alt fa-lg mr-3'></i></a>";
			}

			if (check_role($i_menu, 7) && $i_status == '2') {
				if (($i_level == $this->session->userdata('i_level') || $this->session->userdata('i_level') == 1)) {
					$data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$kodebrg/$dberlaku/$dfrom/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
				}
			}

			return $data;
		});
		$datatables->hide('i_menu');
		$datatables->hide('folder');
		$datatables->hide('d_akhir_tmp');
		$datatables->hide('tanggal_berlaku');
		$datatables->hide('id_hargabarang');
		$datatables->hide('id_company');
		$datatables->hide('id_product_base');
		$datatables->hide('i_status');
		$datatables->hide('label_color');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
		$datatables->hide('e_status_name');

		return $datatables->generate();
	}

	public function status($id, $iproduct)
	{
		$this->db->select('f_status');
		$this->db->from('tr_harga_jualbrgjd');
		$this->db->where('id', $id);
		$this->db->where('id_product_base', $iproduct);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$row    = $query->row();
			$status = $row->f_status;
			if ($status == 't') {
				$stat = 'f';
			} else {
				$stat = 't';
			}
		}
		$data = array(
			'f_status' => $stat
		);
		$this->db->where('id', $id);
		$this->db->where('id_product_base', $iproduct);
		$this->db->update('tr_harga_jualbrgjd', $data);
	}

	public function kategoribarang($cari, $idcompany)
	{
		return $this->db->query("
                              SELECT DISTINCT
                                 a.id,
                                 a.i_kode_kelompok,
                                 a.e_nama_kelompok
                              FROM
                                 tr_kelompok_barang a 
                              JOIN 
                                 tr_product_base b
                                 ON a.i_kode_kelompok = b.i_kode_kelompok AND a.id_company = b.id_company
                              WHERE
                                 a.id_company = '$idcompany'
                              AND
                                 (
                                    a.i_kode_kelompok like '%$cari%' 
                                    or a.e_nama_kelompok like '%$cari%'
                                 )
                              ORDER BY
                                 a.e_nama_kelompok
                              ", FALSE);
	}

	public function getjenisbarang($ikodekelompok, $idcompany)
	{
		$this->db->select("id, i_type_code, e_type_name");
		$this->db->from('tr_item_type');
		$this->db->where('id_company', $idcompany);
		if ($ikodekelompok != 'AKB') {
			$this->db->where('i_kode_kelompok', $ikodekelompok);
		}
		$this->db->order_by('i_type_code');
		return $this->db->get();
	}

	public function getproduct($ikodejenis, $idkodekelompok, $idcompany)
	{
		$where = '';
		if ($ikodejenis != 'AJB') {
			$where .= "AND c.id = '$ikodejenis'";
		}
		if ($idkodekelompok != 'AKB') {
			$where .= "AND d.id = '$idkodekelompok'";
		}

		return $this->db->query("
                                SELECT
                                   a.id as id_product,
                                   a.i_product_base,
                                   a.e_product_basename,
                                   e.id as id_color,
                                   e.e_color_name,
                                   a.i_kode_kelompok,
                                   d.e_nama_kelompok,
                                   a.i_type_code,
                                   c.e_type_name 
                                FROM
                                   tr_product_base a 
                                   LEFT JOIN
                                      tr_item_type c 
                                      ON (a.i_type_code = c.i_type_code 
                                      and a.id_company = c.id_company) 
                                   LEFT JOIN
                                      tr_kelompok_barang d 
                                      ON (a.i_kode_kelompok = d.i_kode_kelompok 
                                      and a.id_company = d.id_company) 
                                   LEFT JOIN
                                      tr_color e
                                      ON (a.i_color = e.i_color 
                                      and a.id_company = e.id_company)
                                FULL JOIN 
                                      tr_harga_kode f ON (a.id_company = f.id_company)
                                WHERE
                                   a.id_company = '" . $this->session->userdata('id_company') . "' 
                                   AND a.id::text||f.id::text NOT IN 
                                   (
                                      SELECT
                                   id_product_base::text||id_harga_kode::text
                                      FROM
                                   tr_harga_jualbrgjd 
                                      WHERE
                                   id_company = '" . $this->session->userdata('id_company') . "' 
                                   )
                                   AND a.f_status = 't' 
                                   AND f.f_status = 't'
                                   $where  
                                   ORDER BY a.i_product_base
                              ", FALSE);
	}

	public function getinput($ikodekelompok, $ikodejenis, $iproduct, $idcompany)
	{
		$where = '';
		$kodekelompok   = explode('|', $ikodekelompok);
		$idkodekelompok = $kodekelompok[0];
		if ($ikodejenis != 'AJB') {
			$where .= " AND c.id = '$ikodejenis'";
		}

		if ($idkodekelompok != 'AKB') {
			$where .= " AND d.id = '$idkodekelompok'";
		}
		if ($iproduct != 'BRG') {
			$where .= " AND a.id = '$iproduct'";
		}

		return $this->db->query("                            
                                SELECT
                                   a.id as id_product,
                                   a.i_product_base,
                                   a.e_product_basename,
                                   e.id as id_color,
                                   e.e_color_name,
                                   a.i_kode_kelompok,
                                   d.e_nama_kelompok,
                                   a.i_type_code,
                                   c.e_type_name,
                                   f.id as id_harga_kode,
                                   f.e_harga
                                FROM
                                   tr_product_base a 
                                   LEFT JOIN
                                      tr_item_type c 
                                      ON (a.i_type_code = c.i_type_code 
                                      and a.id_company = c.id_company) 
                                   LEFT JOIN
                                      tr_kelompok_barang d 
                                      ON (a.i_kode_kelompok = d.i_kode_kelompok 
                                      and a.id_company = d.id_company) 
                                   LEFT JOIN
                                      tr_color e
                                      ON (a.i_color = e.i_color 
                                      and a.id_company = e.id_company)
                                   FULL JOIN 
                                      tr_harga_kode f ON (a.id_company = f.id_company)
                                WHERE
                                   a.id_company = '" . $this->session->userdata('id_company') . "' 
                                   AND a.id::text||f.id::text NOT IN 
                                   (
                                      SELECT
                                   id_product_base::text||id_harga_kode::text
                                      FROM
                                   tr_harga_jualbrgjd 
                                      WHERE
                                   id_company = '" . $this->session->userdata('id_company') . "' 
                                   )
                                   AND a.f_status = 't' 
                                   AND f.f_status = 't'
                                   $where  
                                   ORDER BY a.i_product_base, f.id ASC
                              ", FALSE);
	}

	public function getkodeharga($idcompany)
	{
		return $this->db->query("SELECT * FROM tr_harga_kode WHERE id_company = '$idcompany' ORDER BY e_harga", FALSE)->result();
	}

	public function insert($kodebrg, $harga, $dateberlaku, $ikodeharga, $id_jenis_barang_keluar)
	{
		$idcompany  = $this->session->userdata('id_company');

		$data = array(
			'id_product_base'     	 => $kodebrg,
			'id_harga_kode'       	 => $ikodeharga,
			'v_price'             	 => $harga,
			'd_berlaku'           	 => $dateberlaku,
			'id_company'          	 => $idcompany,
			'id_jenis_barang_keluar' => $id_jenis_barang_keluar,
			'd_entry'             	 => current_datetime(),
			'i_status'				 => '6'
		);
		$this->db->insert('tr_harga_jualbrgjd', $data);
	}

	function cek_data($ikodebrg, $id, $idcompany)
	{
		return $this->db->query("                              
                                SELECT
                                   a.*,
                                   to_char(a.d_berlaku, 'dd-mm-yyyy') as d_berlaku,
                                   b.i_product_base,
                                   b.e_product_basename,
                                   b.i_kode_kelompok,
                                   d.e_nama_kelompok,
                                   b.i_type_code,
                                   e.e_type_name,
                                   c.e_color_name,
                                   f.e_harga,
								   e_jenis_name
                                FROM
                                   tr_harga_jualbrgjd a 
                                   LEFT JOIN
                                      tr_product_base b 
                                      ON (a.id_product_base = b.id 
                                      and a.id_company = b.id_company) 
                                   LEFT JOIN
                                      tr_color c 
                                      ON (b.i_color = c.i_color 
                                      and b.id_company = c.id_company) 
                                   LEFT JOIN
                                      tr_kelompok_barang d 
                                      ON (b.i_kode_kelompok = d.i_kode_kelompok 
                                      and b.id_company = d.id_company) 
                                   LEFT JOIN
                                      tr_item_type e 
                                      ON (b.i_kode_kelompok = e.i_kode_kelompok 
                                      and b.id_company = e.id_company) 
                                   LEFT JOIN
                                      tr_harga_kode f 
                                      ON (a.id_harga_kode = f.id 
                                      and b.id_company = e.id_company) 
									LEFT JOIN tr_jenis_barang_keluar h ON
										(h.id = a.id_jenis_barang_keluar)
                                WHERE
                                   a.id_company = '$idcompany' 
                                   AND a.id_product_base = '$ikodebrg' 
                                   AND a.id = '$id' 
                                ORDER BY
                                   a.id_product_base
                              ", FALSE);
	}

	public function update($id, $kodebrg, $ikodeharga, $harga, $dateberlaku, $idcompany, $id_jenis_barang_keluar)
	{

		$data = array(
			'id_product_base'     	=> $kodebrg,
			'id_harga_kode'       	=> $ikodeharga,
			'v_price'             	=> $harga,
			'd_berlaku'           	=> $dateberlaku,
			'i_status'           	=> '1',
			'd_update'            	=> current_datetime(),
			'id_jenis_barang_keluar' => $id_jenis_barang_keluar
		);
		$this->db->where('id_product_base', $kodebrg);
		$this->db->where('id', $id);
		$this->db->where('id_company', $idcompany);
		$this->db->update('tr_harga_jualbrgjd', $data);
	}

	public function updatetglakhir($id, $kodebrg, $ikodeharga, $harga, $dateberlaku, $dateberlakusebelum, $idcompany, $id_jenis_barang_keluar)
	{
		$dakhir   = date('Y-m-d', strtotime('-1 days', strtotime($dateberlaku))); //kurang tanggal sebanyak 1 hari

		$data = array(
			'id_product_base'     => $kodebrg,
			'id_harga_kode'       => $ikodeharga,
			'v_price'             => $harga,
			'd_berlaku'           => $dateberlaku,
			'id_company'          => $idcompany,
			'd_entry'             => current_datetime(),
			'id_jenis_barang_keluar' => $id_jenis_barang_keluar
		);
		$this->db->insert('tr_harga_jualbrgjd', $data);

		$data2 = array(
			'd_akhir'       => $dakhir,
			'd_update'      => current_datetime(),
		);
		$this->db->where('id_product_base', $kodebrg);
		$this->db->where('d_berlaku', $dateberlakusebelum);
		$this->db->where('id', $id);
		$this->db->where('id_company', $idcompany);
		$this->db->update('tr_harga_jualbrgjd', $data2);
	}

	public function changestatus($id, $istatus)
	{
		$now = date('Y-m-d');
		if ($istatus == '3' || $istatus == '6') {
			$awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
        from tr_harga_jualbrgjd a
        inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
        where a.id = '$id'
        group by 1,2", FALSE)->row();
			if ($istatus == '3') {
				if ($awal->i_approve_urutan - 1 == 0) {
					$data = array(
						'i_status'  => $istatus,
					);
				} else {
					$data = array(
						'i_approve_urutan'  => $awal->i_approve_urutan - 1,
					);
				}
				$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
			} else if ($istatus == '6') {
				if ($awal->i_approve_urutan + 1 > $awal->n_urut) {
					$data = array(
						'i_status'  => $istatus,
						'i_approve_urutan'  => $awal->i_approve_urutan + 1,
					);
				} else {
					$data = array(
						'i_approve_urutan'  => $awal->i_approve_urutan + 1,
					);
				}
				$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
          ('$this->i_menu','$this->i_level','$id','$this->username','$now','tr_harga_jualbrgjd');", FALSE);
			}
		} else {
			$data = array(
				'i_status'  => $istatus,
			);
		}
		$this->db->where('id', $id);
		$this->db->update('tr_harga_jualbrgjd', $data);
	}

	public function estatus($istatus)
	{
		$this->db->select('e_status_name');
		$this->db->from('tr_status_document');
		$this->db->where('i_status', $istatus);
		return $this->db->get()->row()->e_status_name;
	}
}
/* End of file Mmaster.php */