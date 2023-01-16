<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		cek_login();
		$data = array(
			'apps' => $this->db->query("SELECT * FROM public.tr_apps", FALSE)
		);
		$this->load->view('auth', $data);
	}

	public function set_company(){
		$company = explode('|', $this->input->post('company', TRUE)); 

		if($company != '0'){
			$this->session->set_userdata('schema',$company[0]);
		}else{
			$this->session->set_userdata('schema','public');
		}
	}

	public function login(){
		$username = $this->input->post('username');
		$password = md5(md5($this->input->post('password')));
		$company  =  explode('|', $this->input->post('company', TRUE)); 

		// var_dump($company);
		// die();
		// $this->db->select('a.username, a.e_name, b.name e_company_name, a.id_company, b.i_apps, a.i_store, a.i_status, a.e_printer_uri, a.e_printer_host');
		// $this->db->from('public.tm_user a');
		// $this->db->join('public.company b', 'a.id_company = b.id');
		// $this->db->where('a.f_status', 't');
		// $this->db->where('a.username', $username);
		// $this->db->where('a.e_password', $password);
		// $this->db->where('b.id', $company[1]);
		// $cek_user = $this->db->get();
		$cek_user = $this->db->query(
			"SELECT
				a.username,
				a.e_name,
				b.name e_company_name,
				a.id_company,
				b.i_apps,
				a.i_store,
				a.i_status,
				a.e_printer_uri,
				a.e_printer_host,
				CASE WHEN a.i_departement_last IS NULL OR a.i_departement_last = '' THEN c.i_departement ELSE a.i_departement_last END as i_departement,
				CASE WHEN a.i_level_last IS NULL OR a.i_level_last = '' THEN c.i_level ELSE a.i_level_last END as i_level,
				CASE WHEN a.i_departement_last IS NULL OR a.i_departement_last = '' THEN d.e_departement_name ELSE ab.e_departement_name END,
				CASE WHEN a.i_level_last IS NULL OR a.i_level_last = '' THEN e.e_level_name ELSE ac.e_level_name END 
			FROM
				public.tm_user a
			INNER JOIN public.company b ON
				(a.id_company = b.id)
			LEFT JOIN public.tr_departement ab ON
				(ab.i_departement = a.i_departement_last)
			LEFT JOIN public.tr_level ac ON
				(ac.i_level = a.i_level_last)
			INNER JOIN public.tm_user_deprole c ON
				(a.username = c.username)
			INNER JOIN public.tr_departement d ON
				(c.i_departement = d.i_departement)
			INNER JOIN public.tr_level e ON
				(c.i_level = e.i_level)
			WHERE
				a.f_status = 't'
				AND a.username = '$username'
				AND c.i_departement = d.i_departement
				AND c.username = '$username'
				AND a.e_password = '$password'
				AND b.id = '$company[1]'
				AND c.id_company = '$company[1]'
				AND c.i_level = e.i_level
				AND a.f_status = 't'
				AND b.f_status = 't'
				AND c.f_status = 't'
				AND d.f_status = 't' limit 1;"
		);

		if($cek_user->num_rows() > 0){
			$datauser = $cek_user->row();
			$newdata = array(
				'username'  		=> $datauser->username,
				'e_name'   			=> $datauser->e_name,
				'id_company' 		=> $datauser->id_company,
				'i_apps' 			=> $datauser->i_apps,
				'i_store' 			=> $datauser->i_store,
				'status' 			=> $datauser->i_status,
				'uri' 				=> $datauser->e_printer_uri,
				'printerhost'		=> $datauser->e_printer_host,
				'e_company_name'	=> $datauser->e_company_name,
				'i_departement'		=> $datauser->i_departement,
				'e_departement_name'=> $datauser->e_departement_name,
				'i_level'			=> $datauser->i_level,
				'e_level_name'		=> $datauser->e_level_name,
			);

			$this->session->set_userdata($newdata);
			cek_session();
			$this->Logger->write('Login');

			redirect(base_url('main'));
		}else{
			redirect('auth','refresh');
		}
	}

	public function logout(){
		cek_session();
		$this->Logger->write('Logout');

		$departement = $this->uri->segment(3);
		$level = $this->uri->segment(4);
		$id_company = $this->session->userdata('id_company');
		$username = $this->session->userdata('username');

		$this->db->query("UPDATE public.tm_user SET i_departement_last='$departement', i_level_last='$level' WHERE id_company='$id_company' AND username='$username'");

		$this->session->sess_destroy();
		redirect(base_url('auth'),'refresh');
	}

	public function get_company(){
		$i_apps = $this->input->post('i_apps');
		$data   = $this->db->query("SELECT * FROM company WHERE i_apps = '$i_apps' AND f_status = 't' ORDER BY name ASC", FALSE);

		if($data->num_rows() > 0){
			echo '<option value=""></option>';
			foreach ($data->result() as $row) {
				echo '<option value="'.$row->short.'|'.$row->id.'">'.strtoupper($row->name).'</option>';
			}
		}
	}
}
