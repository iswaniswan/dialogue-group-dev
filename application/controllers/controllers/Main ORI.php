<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		cek_session();
	}
	
	public function index()
	{
		$id_company 	= $this->session->userdata('id_company');
		$i_level 	= $this->session->userdata('i_level');
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
			'cls'	=> $data_periode,
			'notif' 			=> $this->db->query("
										with cte as (
											select distinct b.i_menu as i_menu  from tm_user_role a
											inner join tm_menu b on (a.i_menu = b.i_menu)
											where a.i_departement = '$i_departement' and a.i_level = '$i_level' and a.id_user_power = '7' and b.e_database is not null
										)
										select * from (
											/*forecast distributor*/
											select i_menu, e_menu, e_folder, total, '01-' || substring(dfrom, 5, 2) || '-' || substring(dfrom, 0, 5) as dfrom  , 
											'01-' || substring(dto, 5, 2) || '-' || substring(dto, 0, 5) as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(periode) as dfrom, max(periode) as dto  from tm_forecast_distributor a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20701')
												inner join tm_menu f on (f.i_menu = '20701')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
											union all
											select i_menu, e_menu, e_folder, total, '01-' || substring(dfrom, 5, 2) || '-' || substring(dfrom, 0, 5) as dfrom  , 
											'01-' || substring(dto, 5, 2) || '-' || substring(dto, 0, 5) as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(periode) as dfrom, max(periode) as dto  
												from tm_forecast_produksi a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090701')
												inner join tm_menu f on (f.i_menu = '2090701')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_budgeting a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090702')
												inner join tm_menu f on (f.i_menu = '2090702')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_berlaku) as dfrom, max(d_berlaku) as dto  
												from tr_supplier_materialprice a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2010501')
												inner join tm_menu f on (f.i_menu = '2010501')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_pp) as dfrom, max(d_pp) as dto  
												from tm_pp a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20201')
												inner join tm_menu f on (f.i_menu = '20201')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_op) as dfrom, max(d_op) as dto  
												from tm_opbb a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20202')
												inner join tm_menu f on (f.i_menu = '20202')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_nota) as dfrom, max(d_nota) as dto  
												from tm_notabtb a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2040101')
												inner join tm_menu f on (f.i_menu = '2040101')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_btb) as dfrom, max(d_btb) as dto  
												from tm_btb a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '20207')
												inner join tm_menu f on (f.i_menu = '20207')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_keluar_makloon_pengadaan a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090303')
												inner join tm_menu f on (f.i_menu = '2090303')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_qcset) as dfrom, max(d_keluar_qcset) as dto  
												from tm_keluar_qcset a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090202')
												inner join tm_menu f on (f.i_menu = '2090202')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_keluar_makloon_pengadaan_retur a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090311')
												inner join tm_menu f on (f.i_menu = '2090311')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto 
												from tm_masuk_pengadaan a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090301')
												inner join tm_menu f on (f.i_menu = '2090301')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_pengadaan) as dfrom, max(d_keluar_pengadaan) as dto  
												from tm_keluar_pengadaan a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090302')
												inner join tm_menu f on (f.i_menu = '2090302')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_pengadaan_retur) as dfrom, max(d_keluar_pengadaan_retur) as dto  
												from tm_keluar_pengadaan_retur a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090312')
												inner join tm_menu f on (f.i_menu = '2090312')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_jahit) as dfrom, max(d_keluar_jahit) as dto  
												from tm_keluar_jahit a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090403')
												inner join tm_menu f on (f.i_menu = '2090403')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_unitjahit a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090401')
												inner join tm_menu f on (f.i_menu = '2090401')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_keluar_makloonqcset a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090207')
												inner join tm_menu f on (f.i_menu = '2090207')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto 
												from tm_masuk_qc a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090501')
												inner join tm_menu f on (f.i_menu = '2090501')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_keluar_qc) as dfrom, max(d_keluar_qc) as dto  
												from tm_keluar_qc a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090503')
												inner join tm_menu f on (f.i_menu = '2090503')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_stockopname_qcset a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090205')
												inner join tm_menu f on (f.i_menu = '2090205')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_retur_produksi_gdjd a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050118')
												inner join tm_menu f on (f.i_menu = '2050118')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_stockopname_pengadaan a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090308')
												inner join tm_menu f on (f.i_menu = '2090308')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_retur_wip a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090508')
												inner join tm_menu f on (f.i_menu = '2090508')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_stockopname_unitjahit a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090409')
												inner join tm_menu f on (f.i_menu = '2090409')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x
											union all
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_stockopname_qc a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090507')
												inner join tm_menu f on (f.i_menu = '2090507')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all
											/** Gudang Jadi **/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_gudang_jadi a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050102')
												inner join tm_menu f on (f.i_menu = '2050102')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											/** End Gudang Jadi **/
											union all
											/** STB Cutting **/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_keluar_cutting a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090105')
												inner join tm_menu f on (f.i_menu = '2090105')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											/** End STB Cutting **/
											union all
											/** STB Cutting **/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_qcset a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090201')
												inner join tm_menu f on (f.i_menu = '2090201')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											/** End STB Cutting **/
											UNION ALL
											/** SJ WIP **/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_keluar_makloonqc a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090504')
												inner join tm_menu f on (f.i_menu = '2090504')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											/** End SJ WIP **/
											UNION ALL
											/** Terima Retur Jahit **/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_retur_masuk_pengadaan a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090305')
												inner join tm_menu f on (f.i_menu = '2090305')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											/** End Terima Retur Jahit **/
											UNION ALL
											/** Penerimaan Pengadaan **/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_pengadaan_fgudang a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090310')
												inner join tm_menu f on (f.i_menu = '2090310')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											/** End Penerimaan Pengadaan **/
											union all /*forecast cutting*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_fccutting a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090100')
												inner join tm_menu f on (f.i_menu = '2090100')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all /*schedule cutting*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_schedule a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090101')
												inner join tm_menu f on (f.i_menu = '2090101')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all /*STB cutting*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_stb_cutting a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2050204')
												inner join tm_menu f on (f.i_menu = '2050204')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all /*Penerimaan cutting*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_cutting a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090103')
												inner join tm_menu f on (f.i_menu = '2090103')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all /*FC Jahit*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_fcjahit a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090412')
												inner join tm_menu f on (f.i_menu = '2090412')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all /*Uraian Jahit*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_uraianjahit a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090413')
												inner join tm_menu f on (f.i_menu = '2090413')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x	
											union all /*Retur Jahit Ke Pengadaan*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_retur_jahit_topengadaan a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090405')
												inner join tm_menu f on (f.i_menu = '2090405')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
											union all /*Retur WIP Ke Jahit*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_retur_keluar_wip a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090505')
												inner join tm_menu f on (f.i_menu = '2090505')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
											union all /*Penerimaan Retur WIP*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_masuk_retur_jahit a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090407')
												inner join tm_menu f on (f.i_menu = '2090407')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
											union all /*STB Retur Jahit*/
											select i_menu, e_menu, e_folder, total, to_char(dfrom, 'dd-mm-yyyy') as dfrom  , 
											to_char(dto, 'dd-mm-yyyy') as dto from (
												select f.i_menu, f.e_menu, f.e_folder , count(a.id) as total, min(d_document) as dfrom, max(d_document) as dto  
												from tm_stbjahit_retur a
												inner join tr_menu_approve e on (a.i_approve_urutan = e.n_urut and e.i_menu = '2090406')
												inner join tm_menu f on (f.i_menu = '2090406')
												where e.i_level = '$i_level' and a.i_status = '2' and f.i_menu in (select i_menu from cte ) and a.id_company = '$id_company'
												group by 1,2,3
											) as x		
										) as x
								   ", FALSE),
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
}
