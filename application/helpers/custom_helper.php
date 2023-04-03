<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//-- check logged user
function cek_session()
{
	$ci = &get_instance();
	$username = $ci->session->userdata('username');
	if ($username == '') {
		$ci->session->sess_destroy();
		redirect(base_url('auth'));
	}
	$schema = $ci->session->userdata('schema');
	if ($schema == 'public') {
		$ci->session->sess_destroy();
		redirect(base_url('auth'));
	}
}

function cek_login()
{
	$ci = &get_instance();
	$username = $ci->session->userdata('username');
	if ($username != '') {
		redirect(base_url('main'));
	}
}


if (!function_exists('check_role')) {
	function check_role($i_menu, $id)
	{
		$ci = get_instance();

		$ci->load->model('M_custom');
		$option = $ci->M_custom->cek_role($i_menu, $id);

		return $option;
	}
}

if (!function_exists('check_role_folder')) {
	function check_role_folder($folder, $id)
	{
		$ci = get_instance();

		$ci->load->model('M_custom');
		$option = $ci->M_custom->check_role_folder($folder, $id);

		return $option;
	}
}


if (!function_exists('check_constant')) {
	function check_constant($constant)
	{

		$ci = get_instance();
		$id_company = $ci->session->userdata('id_company');
		$cek = $ci->db->query("
	        SELECT
                detail_constant
            FROM
                public.company a,
                public.constant b
            WHERE
                a.id = b.id_company
                AND id = '$id_company' 
                AND constant = '$constant' ");
		if ($cek->num_rows() > 0) {
			return $cek->row()->detail_constant;
		} else {
			return '';
		}
	}
}

//-- current date time function
if (!function_exists('current_datetime')) {
	function current_datetime()
	{
		$ci = get_instance();
		$query   = $ci->db->query("SELECT current_timestamp as c");
		$row     = $query->row();
		$waktu   = $row->c;
		return $waktu;
	}
}

function encrypt_url($string)
{
	$output = false;
	$secret_key     = 'dialogue-group';
	$secret_iv      = 'group';
	$encrypt_method = 'aes-256-cbc';
	$key    = hash("sha256", $secret_key);
	$iv     = substr(hash("sha256", $secret_iv), 0, 16);
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	$output = base64_encode($result);
	$output = str_replace('=', '', $output);
	return $output;
}
function decrypt_url($string)
{
	$output = false;
	$secret_key     = 'dialogue-group';
	$secret_iv      = 'group';
	$encrypt_method = 'aes-256-cbc';
	$key    = hash("sha256", $secret_key);
	$iv = substr(hash("sha256", $secret_iv), 0, 16);
	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	return $output;
}

function getTahun()
{
	$interval = new DateInterval('P1Y');

	$dateTime = new DateTime();
	$lastYear = $dateTime->sub($interval)->format('Y');

	$dateTime = new DateTime();
	$nextYear = $dateTime->add($interval)->format('Y');

	$dateTime = new DateTime();
	$thisYear = $dateTime->format('Y');

	//$data = $lastYear . ' ' . $thisYear . ' ' . $nextYear;
	$data = array();
	array_push($data, $lastYear, $thisYear, $nextYear);
	return $data;
}

function getBulan()
{
	$data = array(
		"01" => "Januari",
		"02" => "Februari",
		"03" => "Maret",
		"04" => "April",
		"05" => "Mei",
		"06" => "Juni",
		"07" => "Juli",
		"08" => "Agustus",
		"09" => "September",
		"10" => "Oktober",
		"11" => "November",
		"12" => "Desember",
	);
	return $data;
}

function removetext($input)
{
	$res = preg_replace("/[^0-9]/", "", $input);
	return $res;
}

function removeEmoji($text)
{

	$clean_text = "";

	// Match Emoticons
	$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
	$clean_text = preg_replace($regexEmoticons, '', $text);

	// Match Miscellaneous Symbols and Pictographs
	$regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
	$clean_text = preg_replace($regexSymbols, '', $clean_text);

	// Match Transport And Map Symbols
	$regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
	$clean_text = preg_replace($regexTransport, '', $clean_text);

	// Match Miscellaneous Symbols
	$regexMisc = '/[\x{2600}-\x{26FF}]/u';
	$clean_text = preg_replace($regexMisc, '', $clean_text);

	// Match Dingbats
	$regexDingbats = '/[\x{2700}-\x{27BF}]/u';
	$clean_text = preg_replace($regexDingbats, '', $clean_text);

	return $clean_text;
}


function my_operator($a, $b, $char) {
	switch($char) {
		case '=': return $a = $b;
		case '*': return $a * $b;
		case '+': return $a + $b;
		case '/': return $a / $b;
		case '-': return $a - $b;
	}
}

function get_menu()
{
	$ci = get_instance();

	$ci->load->model('M_custom');
	$option = $ci->M_custom->get_menu();
	return $option;
}

function get_submenu($i_menu)
{
	$ci = get_instance();

	$ci->load->model('M_custom');
	$option = $ci->M_custom->get_submenu($i_menu);
	return $option;
}


function formatYmd($date)
{
	return date('Y-m-d', strtotime($date));
}

function formatperiode($date)
{
	return date('Ym', strtotime($date));
}

function formatdmY($date)
{
	return date('d-m-Y', strtotime($date));
}

if (!function_exists('month')) {
	function month($a)
	{
		if ($a == '') $b = '';
		if ($a == '01') $b = 'Januari';
		if ($a == '02') $b = 'Februari';
		if ($a == '03') $b = 'Maret';
		if ($a == '04') $b = 'April';
		if ($a == '05') $b = 'Mei';
		if ($a == '06') $b = 'Juni';
		if ($a == '07') $b = 'Juli';
		if ($a == '08') $b = 'Agustus';
		if ($a == '09') $b = 'September';
		if ($a == '10') $b = 'Oktober';
		if ($a == '11') $b = 'November';
		if ($a == '12') $b = 'Desember';
		return $b;
	}
}

if (!function_exists('format_indo')) {
	function format_indo($date)
	{
		return date('d-m-Y', strtotime($date));
	}
}

if (!function_exists('format_bulan')) {
	function format_bulan($date)
	{
		return date('d', strtotime($date)).' '.month(date('m', strtotime($date))).' '.date('Y', strtotime($date));
	}
}

if (!function_exists('format_ym')) {
	function format_ym($date)
	{
		return date('ym', strtotime($date));
	}
}

if (!function_exists('format_to_ym')) {
	function format_to_ym($date)
	{
		return date('ym', strtotime($date));
	}
}

if (!function_exists('format_Ym')) {
	function format_Ym($date)
	{
		return date('Ym', strtotime($date));
	}
}

if (!function_exists('formatYm')) {
	function formatYm($date)
	{
		return date('Ym', strtotime($date));
	}
}

if (!function_exists('format_Y')) {
	function format_Y($date)
	{
		return date('Y', strtotime($date));
	}
}

if (!function_exists('warna')) {
	function warna($angka)
	{
		if ($angka > 0) {
			return "table-success bold";
		} else if ($angka < 0) {
			return "table-danger red_bold";
		}
	}
}

function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = $t;
        }
    }
    return implode(",", $result); // format
}

  
// function my_operator($a, $b, $char)
// {
// 	switch ($char) {
// 		case '=':
// 			return $a = $b;
// 		case '*':
// 			return $a * $b;
// 		case '+':
// 			return $a + $b;
// 		case '/':
// 			return $a / $b;
// 		case '-':
// 			return $a - $b;
// 	}
// }


function capitalize($text)
{
	return ucwords(strtolower($text));
}

function upper($text)
{
	return strtoupper($text);
}

function lower($text)
{
	return strtolower($text);
}


function excelColumnRange($lower, $upper) {
    ++$upper;
    for ($i = $lower; $i !== $upper; ++$i) {
        yield $i;
    }
}

if (!function_exists('replace_kutip')) {
	function replace_kutip($str)
	{
		return str_replace("'","",$str);
	}
}

if (!function_exists('replace_space')) {
	function replace_space($str)
	{
		return str_replace("%20"," ",$str);
	}
}

function v_js()
{
    return date('YmdHis');
}

function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}

function angkaRomawi($angka)
{
	$angka = intval($angka);
	$result = '';
	 
	$array = [
		'M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1
	];
	
	foreach($array as $roman => $value){
		$matches = intval($angka/$value);
		
		$result .= str_repeat($roman,$matches);
		
		$angka = $angka % $value;
	}
	
	return $result;
}

function getBrowser() { 
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
	$platform = 'Unknown';
	$version= "";

	//First get the platform?
	if (preg_match('/linux/i', $u_agent)) {
		$platform = 'linux';
	}elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	}elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
	}

	// Next get the name of the useragent yes seperately and for good reason
	if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	}elseif(preg_match('/Firefox/i',$u_agent)){
		$bname = 'Mozilla Firefox';
		$ub = "Firefox";
	}elseif(preg_match('/OPR/i',$u_agent)){
		$bname = 'Opera';
		$ub = "Opera";
	}elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
		$bname = 'Google Chrome';
		$ub = "Chrome";
	}elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
		$bname = 'Apple Safari';
		$ub = "Safari";
	}elseif(preg_match('/Netscape/i',$u_agent)){
		$bname = 'Netscape';
		$ub = "Netscape";
	}elseif(preg_match('/Edge/i',$u_agent)){
		$bname = 'Edge';
		$ub = "Edge";
	}elseif(preg_match('/Trident/i',$u_agent)){
		$bname = 'Internet Explorer';
		$ub = "MSIE";
	}

	// finally get the correct version number
	$known = array('Version', $ub, 'other');
	$pattern = '#(?<browser>' . join('|', $known) .	')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $u_agent, $matches)) {
	// we have no matching number just continue
	}
	// see how many we have
	$i = count($matches['browser']);
	if ($i != 1) {
	//we will have two since we are not using 'other' argument yet
	//see if version is before or after the name
	if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
		$version= $matches['version'][0];
	}else {
		$version= $matches['version'][1];
	}
	}else {
		$version= $matches['version'][0];
	}

	// check if we have a number
	if ($version==null || $version=="") {$version="?";}

	return [
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'    => $pattern
	];
} 