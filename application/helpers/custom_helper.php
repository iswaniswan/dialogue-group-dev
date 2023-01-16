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

if (!function_exists('format_Ym')) {
	function format_Ym($date)
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