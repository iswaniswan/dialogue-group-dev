<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		cek_session();
		$this->load->model('m_custom');
	}
	
	public function index()
	{
		$id_company 	= $this->session->userdata('id_company');
		$i_level 	    = $this->session->userdata('i_level');
		$username 		= $this->session->userdata('username');
		$i_departement  = $this->session->userdata('i_departement');
    	$query   		= $this->db->query("SELECT current_timestamp as c");
		$row     		= $query->row();
		$today  		= $row->c;
		$year 			= date('Y').'-01-01';
		$data_holiday 	= $this->db->query("SELECT d_holiday FROM tr_holiday WHERE d_holiday >= '$year' ", FALSE)->result();
		$holiday 		= [];		
		foreach ($data_holiday as $key) {
			$holiday[] = date("d/m/Y", strtotime($key->d_holiday) );
		}
		$data_periode 	= $this->db->query("SELECT date(substring(i_periode, 1,4)||'-'|| substring(i_periode, 5,2) ||'-01') AS i_periode FROM tm_periode WHERE id_company = '$id_company'", FALSE)->row();

		if ($data_periode == '') {
			$data_periode = '2021-01-01';
		} else {
			$data_periode = $data_periode->i_periode;
		}
		$data = array(
			'nama_company' 		=> $this->db->query("SELECT name FROM public.company WHERE id = '$id_company' AND f_status = 't' ", FALSE)->row()->name,
			'menu'				=> $this->menu('0', $h=""),
			'departement' 		=> $this->db->query("SELECT * FROM public.tr_departement WHERE f_status = 't' ORDER BY e_departement_name ASC", FALSE)->result(),
			'departement_user' 	=> $this->db->query("SELECT e_departement_name FROM public.tm_user_deprole a, public.tr_departement b WHERE a.i_departement = b.i_departement AND a.username = '$username' AND a.id_company = '$id_company' AND a.f_status = 't' AND b.f_status = 't' GROUP BY b.e_departement_name", FALSE),
			'level' 			=> $this->db->query("SELECT * FROM public.tr_level WHERE f_status = 't' ORDER BY e_level_name ASC ", FALSE)->result(),
			'level_user' 		=> $this->db->query("SELECT b.e_level_name FROM public.tm_user_deprole a, public.tr_level b WHERE a.i_level = b.i_level AND a.username = '$username' AND a.id_company = '$id_company' AND a.i_departement = '$i_departement' AND a.f_status = 't' AND b.f_status = 't' GROUP BY b.e_level_name", FALSE),
			'today' 			=> substr($today, 0, 19),
			'holiday' 			=> $holiday,
			'header'			=> $this->db->query("SELECT * FROM public.tm_menu WHERE e_folder = '#' "),
			'cls'				=> $data_periode,
			'notif' 			=> $this->m_custom->get_notif($id_company,$i_level,$username,$i_departement),
		);
		$this->load->view('main', $data);
	}

	
	// 										) as x	
	private function menu($parent=0,$hasil)
	{
		$i_level   		= $this->session->userdata('i_level');
		$i_departement 	= $this->session->userdata('i_departement');
		$i_apps 		= $this->session->userdata('i_apps');

		$w = $this->db->query("
			SELECT
				a.i_menu,
				a.e_menu,
				a.e_folder,
				a.i_parent,
				a.n_urut,
				icon
			FROM
				tm_menu a
				LEFT JOIN tm_user_role b ON
				(a.i_menu = b.i_menu)
				LEFT JOIN tm_user_power c ON
				(b.id_user_power = c.id)
			WHERE
				c.id = '2'
				AND b.i_level = '$i_level'
				AND b.i_departement = '$i_departement'
				AND a.i_parent = '$parent'
				AND b.i_apps = '$i_apps'
			ORDER BY
				a.n_urut
			", FALSE);

		if($parent == 0){
			$hasil .= '<ul class="nav" id="side-menu">';
			$hasil .= '  <li class="user-pro">
			<a href="#" onclick="return false;" class="waves-effect"><img src="'.base_url().'assets/images/admin.jpg" alt="user-img" class="img-circle"> <span class="hide-menu">'.$this->session->e_name.'</span>
			</a>
			</li>';
			$hasil .= '<li> <a href="'.base_url().'" class="waves-effect"><i class="icon-speedometer fa-fw"></i> <span class="hide-menu">Dashboard</span></a></li>';
		}

		if(($w->num_rows())>0)
		{
			if($parent != 0){
				$s=strval($parent);
				if(strlen($s)==3){
					$hasil .= '<ul class="nav nav-second-level">';
				}elseif(strlen($s)==5){
					$hasil .= '<ul class="nav nav-third-level">';
				}else{
					$hasil .= '<ul class="nav nav-fourth-level">';
				}
			}
		}

		foreach($w->result() as $h)
		{
			$cek_row = $this->db->query("
				SELECT DISTINCT
					a.i_menu,
					a.e_menu,
					a.e_folder,
					a.i_parent,
					a.n_urut,
					icon
				FROM
					tm_menu a
					LEFT JOIN tm_user_role b ON
					(a.i_menu = b.i_menu)
					LEFT JOIN tm_user_power c ON
					(b.id_user_power = c.id)
				WHERE
					c.id = '2'
					AND b.i_level = '$i_level'
					AND a.i_parent = '$h->i_menu'
					AND b.i_apps = '$i_apps'
				ORDER BY
					a.n_urut
				", FALSE);

			if($cek_row->num_rows() > 0){
				/*$x = $cek_row->num_rows();*/
				if($parent == 0){
					$hasil .= '<li><a href="javascript:void(0);" class="waves-effect"><i class="'.$h->icon.' fa-fw text-info"></i>&nbsp;<span class="hide-menu text-info">'.$h->e_menu.'<span class="fa arrow"></span></span></a>';
				}else{
					$hasil .= '<li><a href="javascript:void(0);" class="waves-effect"><span class="hide-menu ml-1 text-success">'.$h->e_menu.'<span class="fa arrow"></span></span></a>';
				}
			}else{
				if($h->e_folder != '#'){
					$hasil .= '<li>'.$this->pquery->link_to_remote('<span class="ml-2"><i>'.$h->e_menu.'</i></span>',array('url'=>base_url().$h->e_folder.'/cform','update'=>'#main'));
				}else{
					$hasil .= '<li><a href = "#" onclick="return false"><span class="ml-2 text-danger"><i>'.$h->e_menu.'</i></span></a>';
				}
			}
			$hasil  = $this->menu($h->i_menu,$hasil);
			$hasil .= "</li>";
		}

		if($parent == 0){
			$hasil .= '<li><a href="'.base_url().'auth/logout" class="waves-effect text-info"><i class="icon-logout fa-fw"></i>&nbsp;<span class="hide-menu">Log out</span></a></li>';
			$hasil .= "</ul>";
		}

		if(($w->num_rows)>0)
		{
			if($parent != 0){
				$hasil .= "</ul>";
			}
		}

		return $hasil;
	}

	public function get_level()
	{
		$i_departement 	= $this->input->post('i_departement');
		$id_company 	= $this->session->userdata('id_company');
		$username 		= $this->session->userdata('username');

		$this->session->set_userdata('i_departement', $i_departement);
		$this->session->unset_userdata('i_level');

		$data = $this->db->query("
			SELECT
			    a.i_level,
			    b.e_level_name
			FROM
			    tm_user_deprole a,
			    tr_level b
			WHERE
			    a.i_level = b.i_level
			    AND a.f_status = 't'
			    AND b.f_status = 't'
			    AND a.id_company = '$id_company'
			    AND a.username = '$username'
			    AND a.i_departement = '$i_departement'
			ORDER BY b.e_level_name
		", FALSE);

		if($data->num_rows() > 0){
			echo '<option style="display: none;">Pilih</option>';
			foreach ($data->result() as $row) {
				echo '<option value="'.$row->i_level.'">'.$row->e_level_name.'</option>';
			}
		}
	}


	public function set_level()
	{
		$i_level 	= $this->input->post('i_level');
		$this->session->set_userdata('i_level', $i_level);
		/*$i_depart 	= $this->session->userdata('i_departement');
		$i_user 	= $this->session->userdata('username');
		$idcompany  = $this->session->userdata('id_company');
		$this->db->select('c.i_kode_lokasi, c.i_kode_master, c.i_kode_jenis, c.i_supplier_group, c.i_kode_kelompok, i_type_makloon');
		$this->db->from('public.tm_user a');
		$this->db->join('public.company b', 'a.id_company = b.id');
		$this->db->join('public.tm_user_deprole c', 'a.username = c.username and b.id = c.id_company');
		$this->db->where('c.username', $i_user);
		$this->db->where('c.i_level',$i_level);
		$this->db->where('c.id_company',$idcompany);
		$this->db->where('c.i_departement', $i_depart);
		$bebas = $this->db->get()->row();
		$cek_user 		= $bebas->i_kode_lokasi;
		$jenis 			= $bebas->i_kode_jenis;
		$kelompok 		= $bebas->i_kode_kelompok;
		$typemakloon 	= $bebas->i_type_makloon;
		$suppliergroup 	= $bebas->i_supplier_group;
		$gudang 		= $bebas->i_kode_master;

		$username = $this->session->userdata('username');
		$this->session->set_userdata('i_level', $i_level);
		$this->session->set_userdata('i_lokasi', $cek_user);
		$this->session->set_userdata('jenis_gudang', $jenis);
		$this->session->set_userdata('gudang', $gudang);
		$this->session->set_userdata('kelompok_barang', $kelompok);
		$this->session->set_userdata('type_makloon', $typemakloon);
		$this->session->set_userdata('group_supplier', $suppliergroup);*/
	}

	public function data_wip()
	{
		echo $this->m_custom->mutasi_wip();
	}

	public function data_material()
	{
		echo $this->m_custom->mutasi_material();
	}
}
