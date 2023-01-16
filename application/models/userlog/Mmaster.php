<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($from,$to){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("SELECT user_id, ip_address, to_char(waktu,'dd-mm-yyyy hh:mi:ss') as waktu, activity 
		FROM dgu_log WHERE to_char(waktu,'yyyy-mm-dd')>='$from' AND to_char(waktu,'yyyy-mm-dd')<='$to' 
		order by user_id, ip_address, waktu");

        return $datatables->generate();
	}
}

/* End of file Mmaster.php */
