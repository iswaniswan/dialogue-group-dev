<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($i_menu,$folder,$dfrom,$dto){
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT 
                            	0 as no,
                            	a.id,
                            	a.i_document,
                            	to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                            	to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur,
                            	a.i_partner,
                            	b.e_supplier_name,
                            	a.v_total,
                                a.e_remark,
                                a.i_status,
                            	c.e_status_name,
                            	c.label_color,
                            	'$i_menu' AS i_menu,
                            	'$folder' AS folder,
                            	'$dfrom' AS dfrom,
                            	'$dto' AS dto
                            FROM 
                            	tm_notamakloonprint a
                            	INNER JOIN 
                            		tr_supplier b 
                            		ON (a.i_partner = b.i_supplier 
                            		AND a.id_company = b.id_company)
                            	INNER JOIN 
                            		tr_status_document c
                            		ON (a.i_status = c.i_status)
                            WHERE 
                            	a.i_status <> '5'
                            	AND a.id_company = '".$this->session->userdata('id_company')."'
                            	$where
                            ORDER BY
                            	a.i_document,
                            	a.d_document
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('v_total', function($data){
            return 'Rp. '.number_format($data['v_total'], 2);
        });

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_menu     = $data['i_menu'];
            $i_status   = $data['i_status'];
            $ipartner   = $data['i_partner'];
            $folder     = $data['folder'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';
                if(check_role($i_menu, 2)){
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/$ipartner/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
                }
                if (check_role($i_menu, 3)) {
                    if ($i_status == '1') {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ipartner/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
                    }
                }
                if (check_role($i_menu, 4) && ($i_status=='1')) {
                    $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
                }
                return $data;
        });

        $datatables->hide('id');
        $datatables->hide('i_partner');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        
        return $datatables->generate();
    }

    /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));        
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }
    
    /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
    public function getpartner($cari, $i_menu) {
        return $this->db->query("
                                SELECT DISTINCT
                                	a.id_supplier,
                                	b.i_supplier, 
                                    b.e_supplier_name,
                                    b.i_type_pajak,
                                    b.f_pkp,
                                    b.n_supplier_toplength,
                                    b.n_diskon
                                FROM
                                    tm_masuk_makloonqcset a
                                	INNER JOIN
                                		tr_supplier b 
                                		ON (a.id_supplier = b.id 
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tr_supplier_makloon c 
                                		ON (b.i_supplier = c.i_supplier 
                                		AND a.id_company = c.id_company)
                                	INNER JOIN
                                		tr_type_makloon d 
                                		ON (c.i_type_makloon = d.i_type_makloon
                                		AND d.id_company = c.id_company)
                                	INNER JOIN 
                                		tr_makloon_menu e
                                		ON (d.id = e.id_makloon
                                		AND e.id_company = d.id_company)
                                WHERE
                                	e.i_menu = '$i_menu'
                                	AND a.id_company = '".$this->session->userdata('id_company')."' 
                                    AND a.i_status = '6'
                                    AND a.id_type_makloon IN (SELECT id_makloon FROM tr_makloon_menu WHERE i_menu = '$i_menu' AND id_company='".$this->session->userdata('id_company')."')
                                	AND a.id NOT IN (SELECT id_referensi FROM tm_notamakloonprint WHERE id_company = '".$this->session->userdata('id_company')."')
                                ORDER BY
                                	b.e_supplier_name   
                                ", FALSE);
    }

    public function getreferensi($cari, $idpartner, $i_menu){
        return $this->db->query("
                                SELECT DISTINCT
                                	b.id_document_reff,
                                	c.id,
                                	c.i_document
                                FROM
                                	tm_masuk_makloonqcset a
                                	LEFT JOIN 
                                		tm_masuk_makloonqcset_item b
                                		ON (a.id = b.id_document 
                                		AND a.id_company = b.id_company)
                                	LEFT JOIN 
                                		tm_keluar_makloonqcset c
                                		ON (b.id_document_reff = c.id 
                                		AND b.id_company = c.id_company)
                                WHERE 
                                	a.i_status = '6'
                                    AND a.id_supplier = '$idpartner'
                                    AND a.id_type_makloon IN (SELECT id_makloon FROM tr_makloon_menu WHERE i_menu = '$i_menu' AND id_company='".$this->session->userdata('id_company')."')
                                	AND c.id NOT IN (SELECT id_referensi FROM tm_notamakloonprint WHERE i_status <> '5' AND id_company = '".$this->session->userdata('id_company')."')
                                	AND a.id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
    }

    public function getheadref($id, $ipajak, $fpkp, $ntop, $ndiskon){
        return $this->db->query("
                                SELECT
                                    to_char(d_document,'dd-mm-yyyy') as d_document,
                                    '$ipajak' as i_type_pajak,
                                    '$fpkp' as f_pkp,
                                    '$ntop' as n_top,
                                    '$ndiskon' as n_diskon
                                FROM
                                    tm_keluar_makloonqcset
                                WHERE 
                                    id = '$id'
                                    AND id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
    }

    public function getdetailref($id, $idpartner, $i_menu){
        return $this->db->query("
                                SELECT 
                                	e.id,
                                	e.i_document as dokumen_keluar,
                                	a.id_document,
                                	d.i_document as dokumen_masuk,
                                	to_char(d.d_document, 'dd-mm-yyyy') as d_document,
                                	a.id_product_wip as produk_masuk,
                                	b.id_product_wip as produk_keluar,
                                    c.i_product_wip,
                                    c.e_product_wipname,
                                	a.n_quantity,
                                	a.n_quantity_sisa,
                                    b.v_price,
                                    d.i_status,
                                    c.i_color,
                                    f.e_color_name
                                FROM
                                	tm_masuk_makloonqcset_item a
                                	LEFT JOIN 
                                		tm_keluar_makloonqcset_item b
                                		ON (a.id_document_reff = b.id_document
                                		AND a.id_product_wip = b.id_product_wip
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tr_product_wip c
                                		ON (a.id_product_wip = c.id
                                		AND b.id_product_wip = c.id
                                		AND a.id_company = c.id_company)
                                	LEFT JOIN 
                                		tm_masuk_makloonqcset d
                                		ON (a.id_document = d.id
                                		AND a.id_company = d.id_company)
                                	LEFT JOIN 
                                		tm_keluar_makloonqcset e
                                		ON (a.id_document_reff = e.id
                                        AND a.id_company = e.id_company)
                                    INNER JOIN 
                                        tr_color f
                                        ON (c.i_color = f.i_color
                                        AND c.id_company = f.id_company)
                                WHERE
                                	a.id_company='".$this->session->userdata('id_company')."'
                                	AND d.i_status = '6'
                                	AND a.id_document_reff = '$id'
                                	AND d.id_type_makloon IN (SELECT id_makloon FROM tr_makloon_menu WHERE i_menu = '$i_menu' AND id_company='".$this->session->userdata('id_company')."')
                                	AND d.id_supplier = '$idpartner'
                                ORDER BY
                                	d.i_document
                                ", FALSE);
    }

    public function cekkode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_notamakloonprint');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold,$ibagian) {
        $this->db->select('i_document');
        $this->db->from('tm_notamakloonprint');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function bacapp($id){
        return $this->db->query("
                                SELECT 
                                	a.id
                                FROM
                                	tm_notamakloonprint a
                                	LEFT JOIN 
                                		tm_permintaan_pembayaranap_item b
                                		ON (a.id = b.id_nota)
                                	LEFT JOIN 
                                		tm_permintaan_pembayaranap c
                                		ON (c.id = b.id_ppap
                                		AND c.id_company = a.id_company)
                                WHERE
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id = '$id'
                                	AND b.id_nota NOT IN (
                                				SELECT 
                                					a.id_nota
                                				FROM 
                                					tm_permintaan_pembayaranap_item a
                                					LEFT JOIN 
                                						tm_permintaan_pembayaranap b
                                						ON (a.id_ppap = b.id)
                                				WHERE 
                                					b.id_company = '".$this->session->userdata('id_company')."' 
                                					AND a.id_nota = '$id' 
                                					AND i_jenis_faktur='6'
                                			     )
                                ", FALSE);
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_notamakloonprint');
        return $this->db->get()->row()->id+1;
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/    
    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_notamakloonprint
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'FP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_notamakloonprint
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function insertheader($id, $ibagian, $inota, $dnota, $dreceivefaktur, $ipartner, $ireferensi, $dreferensi, $ifaktursup, $dfaktursup, $ipajak, 
                                 $dpajak, $vdiskon, $vtotaldiskon, $diskonsup, $vtotaldpp, $vtotalppn, $vtotalbruto, $vtotalnetto, $vtotalfa, $eremark, $djatuhtempo) {
        $dentry = date("Y-m-d");
        $data = array(
            'id'               => $id,
            'id_company'       => $this->session->userdata('id_company'),
            'i_document'       => $inota,
            'd_document'       => $dnota,
            'id_referensi'     => $ireferensi,
            'i_nota_supplier'  => $ifaktursup,
            'd_nota_supplier'  => $dfaktursup,
            'i_pajak'          => $ipajak,
            'd_pajak'          => $dpajak,
            'i_bagian'         => $ibagian,
            'i_partner'        => $ipartner,
            'v_total_bruto'    => $vtotalbruto,
            'n_total_discount' => $vtotaldiskon,
            'v_total_discount' => $vdiskon,
            'v_total_netto'    => $vtotalnetto,
            'v_total_sisa'     => $vtotalfa,
            'v_total_dpp'      => $vtotaldpp,
            'v_total_ppn'      => $vtotalppn,
            'v_total'          => $vtotalfa,
            'e_remark'         => $eremark,
            'd_terima_faktur'  => $dreceivefaktur,
            'd_entry'          => current_datetime(),
            'd_jatuh_tempo'    => $djatuhtempo
        );
        $this->db->insert('tm_notamakloonprint', $data);
    }

    public function insertdetail($id, $idreffmasuk, $idwip, $nquantity, $harga, $hargatotal, $edesc) {
        $data = array(
            'id_document'       => $id,
            'id_company'        => $this->session->userdata('id_company'),
            'id_referensi_item' => $idreffmasuk,
            'id_product_wip'    => $idwip,
            'n_quantity'        => $nquantity,
            'n_sisa'            => $nquantity,
            'v_price'           => $harga,
            'v_total'           => $hargatotal,
            'e_remark'          => $edesc,
        );
        $this->db->insert('tm_notamakloonprint_item', $data);
    }

    public function bacaheader($id, $ipartner){
        return $this->db->query("
                                SELECT 
                                    a.*,
                                    to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                    to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur,
                                    to_char(a.d_nota_supplier, 'dd-mm-yyyy') as d_nota_supplier,
                                    to_char(a.d_pajak, 'dd-mm-yyyy') as d_pajak,
                                	b.e_supplier_name,
                                	b.i_type_pajak,
                                	b.f_pkp,
                                	c.e_bagian_name,
                                	d.i_document as i_document_referensi, 
                                    to_char(d.d_document, 'dd-mm-yyyy') as d_document_referensi,
                                    to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo
                                FROM
                                	tm_notamakloonprint a
                                	INNER JOIN 
                                		tr_supplier b
                                		ON (a.i_partner = b.i_supplier 
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tr_bagian c
                                		ON (a.i_bagian = c.i_bagian 
                                		AND a.id_company = c.id_company)
                                	LEFT JOIN 
                                		tm_keluar_makloonqcset d
                                		ON (a.id_referensi = d.id
                                		AND a.id_company = d.id_company)
                                WHERE 
                                	a.i_partner = '$ipartner'
                                	AND a.id = '$id'
                                	AND a.id_company ='".$this->session->userdata('id_company')."'
                                ", FALSE);
    }

    public function bacadetail($id){
        return $this->db->query("
                                SELECT 
                                	a.*,
                                    c.i_document,
                                    d.i_product_wip,
                                    d.e_product_wipname,
                                    d.i_color,
                                	e.e_color_name 
                                FROM
                                	tm_notamakloonprint_item a
                                	LEFT JOIN 
                                		tm_notamakloonprint b 
                                		ON (a.id_document = b.id
                                		AND a.id_company = b.id_company)
                                	LEFT JOIN 
                                		tm_masuk_makloonqcset c
                                		ON (a.id_referensi_item = c.id
                                		AND a.id_company = c.id_company)
                                	INNER JOIN 
                                		tr_product_wip d
                                		ON (a.id_product_wip = d.id
                                		AND a.id_company = d.id_company)
                                	INNER JOIN 
                                		tr_color e 
                                		ON (d.i_color = e.i_color
                                		AND d.id_company = e.id_company)
                                WHERE
                                	a.id_document = '$id'
                                	AND a.id_company = '".$this->session->userdata('id_company')."' 
                                ", FALSE);
    }

    public function updateheader($id, $ibagian, $ipartner, $inota, $dnota, $dreceivefaktur, $ifaktursup, $dfaktursup, $ipajak, 
                                 $dpajak, $vdiskon, $vtotaldiskon, $diskonsup, $vtotaldpp, $vtotalppn, $vtotalbruto, $vtotalnetto, $vtotalfa, $eremark) {
        $data = array(
            'id_company'       => $this->session->userdata('id_company'),
            'i_document'       => $inota,
            'd_document'       => $dnota,
            'i_nota_supplier'  => $ifaktursup,
            'd_nota_supplier'  => $dfaktursup,
            'i_pajak'          => $ipajak,
            'd_pajak'          => $dpajak,
            'i_bagian'         => $ibagian,
            'v_total_bruto'    => $vtotalbruto,
            'n_total_discount' => $vtotaldiskon,
            'v_total_discount' => $vdiskon,
            'v_total_netto'    => $vtotalnetto,
            'v_total_sisa'     => $vtotalfa,
            'v_total_dpp'      => $vtotaldpp,
            'v_total_ppn'      => $vtotalppn,
            'v_total'          => $vtotalfa,
            'e_remark'         => $eremark,
            'd_terima_faktur'  => $dreceivefaktur,
            'd_entry'          => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where('i_partner', $ipartner);
        $this->db->update('tm_notamakloonprint', $data);
    }

    public function updatedetail($id, $idreffmasuk, $idwip, $nquantity, $harga, $hargatotal, $edesc) {
        $data = array(
            'id_document'       => $id,
            'id_company'        => $this->session->userdata('id_company'),
            'e_remark'          => $edesc,
        );
        $this->db->where('id_document', $id);
        $this->db->where('id_referensi_item', $idreffmasuk);
        $this->db->where('id_product_wip', $idwip);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->update('tm_notamakloonprint_item', $data);
    }

    public function send($kode){
        $data = array(
            'i_status'    => '11'
    );
  
    $this->db->where('id', $kode);
    $this->db->update('tm_notamakloonprint', $data);
    }
  
    public function changestatus($id,$istatus) {   
  
        //   if ($istatus == '5') {  
        //     $this->db->query("update tm_btb_item set f_btb_faktur = 'f' where id_btb in (select id_btb from tm_notabtb_item where id_nota= '$id')", false);
        //   } 
  
          $data = array(
            'i_status'  => $istatus
            // 'e_approve' => $this->session->userdata('username'),
            // 'd_approve' => date('Y-m-d'),
          );  
  
          $this->db->where('id', $id);
          $this->db->update('tm_notamakloonprint', $data);    
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
}
/* End of file Mmaster.php */
