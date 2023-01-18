<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logger extends CI_Model {

    public function write($pesan)
    {
        $query   = $this->db->query("SELECT current_timestamp as c");
		$row     = $query->row();
		$waktu  = $row->c;
		$username = $this->session->userdata('username');
		$schema = $this->session->userdata('schema');
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$data = array(
			'user_id' => $username,
			'ip_address' => $ip_address,
			'waktu' => $waktu,
			'activity' => $pesan
		);

		$this->db->insert('dgu_log', $data);
    }

}

/* End of file Logger.php */
