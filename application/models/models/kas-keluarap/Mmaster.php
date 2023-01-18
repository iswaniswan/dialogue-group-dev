<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public $idcompany;

    function __construct(){
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

	function data($i_menu, $folder, $dfrom, $dto){
        $datatables = new Datatables(new CodeigniterAdapter);
        
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_kasbank_keluarap BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $datatables->query("
                            SELECT DISTINCT
                                0 as no,
                                a.id,
                                a.i_kasbank_keluarap, 
                                to_char(a.d_kasbank_keluarap,'dd-mm-yyyy') as d_kasbank_keluarap, 
                                a.i_kode_kas, 
                                f.e_kas_name, 
                                a.i_bagian,
                                a.i_supplier,
                                d.e_supplier_name, 
                                b.id_ppap, 
                                c.i_ppap,
                                a.v_bayar, 
                                a.i_voucher,
                                a.i_jenis_faktur,
                                a.i_status, 
                                e.e_status_name,
                                e.label_color as label, 
                                '$i_menu' as i_menu, 
                                '$folder' as folder,
                                '$dfrom' as dfrom,
                                '$dto' as dto
                            FROM 
                                tm_kasbank_keluarap a
                                LEFT JOIN 
                                    tm_kasbank_keluarap_item b
                                    ON (a.id = b.id_kasbank_keluarap)
                                LEFT JOIN
                                    tm_permintaan_pembayaranap c
                                    ON (b.id_ppap = c.id and a.id_company = c.id_company)
                                LEFT JOIN
                                    tr_supplier d
                                    ON (a.i_supplier = d.i_supplier and a.id_company = d.id_company)
                                LEFT JOIN 
                                    tr_status_document e 
                                    ON (a.i_status = e.i_status)
                                LEFT JOIN 
                                    tr_kas_bank f
                                    ON (a.i_kode_kas = f.i_kode_kas and a.id_company = f.id_company)
                            WHERE 
                                a.i_status <> '5'
                                $where
                                and a.id_company = '$this->idcompany'
                            ORDER BY
                                a.i_kasbank_keluarap
                            ",FALSE);

            $datatables->edit('e_status_name', function ($data) {
                return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';
            });

            $datatables->edit('v_bayar', function($data){
                return 'Rp. '.number_format($data['v_bayar'],2);
            }); 

            $datatables->add('action', function ($data) {
                $idkasbank          = trim($data['id']);
                $ikasbankkeluar     = trim($data['i_kasbank_keluarap']);
                $isupplier          = trim($data['i_supplier']);
                $ibagian            = trim($data['i_bagian']);
                $idppap             = trim($data['id_ppap']);
                $ijenisfaktur       = trim($data['i_jenis_faktur']);
                $i_menu             = $data['i_menu'];
                $folder             = $data['folder'];
                $dfrom              = $data['dfrom'];
                $dto                = $data['dto'];
                $i_status           = trim($data['i_status']);
                $data               = '';

                if(check_role($i_menu, 2)){
                    $data .= "<a href=\"#\" title='View' onclick='show(\"$folder/cform/view/$idkasbank/$ikasbankkeluar/$idppap/$isupplier/$dfrom/$dto/$ijenisfaktur/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
                }
                if(check_role($i_menu, 3)) {
                    if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$idkasbank/$ikasbankkeluar/$idppap/$isupplier/$dfrom/$dto/$ijenisfaktur/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
                    }
                }
                if(check_role($i_menu, 7)){
                   if ($i_status == '2') {
                      $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$idkasbank/$ikasbankkeluar/$idppap/$isupplier/$dfrom/$dto/$ijenisfaktur/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                  }
                }
                if (check_role($i_menu, 4) && ($i_status=='1')) {
                   $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$idkasbank\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
                }
			    return $data;
            });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('i_kode_kas');
        $datatables->hide('i_supplier');
        $datatables->hide('id');
        $datatables->hide('id_ppap');
        $datatables->hide('i_bagian');
        $datatables->hide('i_jenis_faktur');
        $datatables->hide('label');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }
    
    public function bagian(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->idcompany);
        $this->db->where('b.id_company', $this->idcompany);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode){
        $this->db->select('i_kasbank_keluarap');
        $this->db->from('tm_kasbank_keluarap');
        $this->db->where('i_kasbank_keluarap', $kode);
        $this->db->where_not_in('i_status', '5');
        $this->db->where('id_company', $this->idcompany);
        return $this->db->get();
    } 

    public function runningnumber($thbl){
        $cek = $this->db->query("
                SELECT 
                    substring(i_kasbank_keluarap, 1, 4) AS kode 
                FROM 
                    tm_kasbank_keluarap 
                WHERE 
                    i_status <> '5'
                    and id_company = '$this->idcompany'
                ORDER BY 
                    id DESC
            ");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'KBAP';
        }
        $query  = $this->db->query("
                SELECT
                    max(substring(i_kasbank_keluarap, 11, 6)) AS max
                FROM
                    tm_kasbank_keluarap
                WHERE 
                    to_char (d_kasbank_keluarap, 'yyyy') >= '".date('Y')."'
                    AND i_status <> '5'
                    AND substring(i_kasbank_keluarap, 1, 4) = '$kode'
                    and id_company = '$this->idcompany'
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

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_kasbank_keluarap');
        return $this->db->get()->row()->id+1;
    }

    public function running_voucher($tahun, $ikasbank, $thbl){
        $query  = $this->db->query("
                SELECT i_pv from tr_voucher where i_periode = '$tahun' and id_company = '$this->idcompany' and i_kode_kas = '$ikasbank'              
            ", false);
        $kode = 'PV';
        
        $this->db->query("  
            insert into tr_voucher (id_company, i_periode, i_kode_kas, i_pv) values ('$this->idcompany','$tahun','$ikasbank','1') 
            ON CONFLICT (id_company, i_periode, i_kode_kas) 
            DO UPDATE SET i_pv = tr_voucher.i_pv + EXCLUDED.i_pv;        
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

    public function bacajenisfaktur($cari){
        return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_jenis_faktur,
                                        a.e_jenis_faktur_name
                                    FROM 
                                        tr_jenis_faktur a
                                    WHERE
                                        a.e_jenis_faktur_name ILIKE '%$cari%'
                                    AND 
                                        a.f_status = 't'
                                    AND
                                        a.i_type = '1'
                                    ORDER BY
                                        a.e_jenis_faktur_name
                                ", FALSE);
    }

    public function bacasupplier($cari, $ijenis){
        return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_supplier,
                                        c.e_supplier_name
                                    FROM 
                                        tm_permintaan_pembayaranap a
                                        JOIN 
                                            tm_permintaan_pembayaranap_item b 
                                            ON (a.id = b.id_ppap and a.id_company = b.id_company)
                                        JOIN 
                                            tr_supplier c 
                                            ON (a.i_supplier = c.i_supplier and a.id_company = c.id_company)
                                    WHERE
                                        a.i_status <> '5'
                                        AND a.i_status = '6'
                                        AND a.v_sisa <> '0'
                                        AND a.id_company = '$this->idcompany'
                                        AND b.i_jenis_faktur = '$ijenis'
                                        /*AND a.id NOT IN 
                                        (
						                    SELECT 
						                    	a.id_ppap
						                    FROM 
						                    	tm_kasbank_keluarap_item a
						                    	INNER JOIN 
						                    		tm_kasbank_keluarap b 
						                    		ON (a.id_kasbank_keluarap = b.id
						                    		AND a.id_company = b.id_company)
						                    WHERE 
						                    	a.id_company = '".$this->session->userdata('id_company')."'
                                        )*/
                                    ORDER BY
                                        c.e_supplier_name
                                ", FALSE);
    }

    public function getreferensi($isupplier, $ijenis){
        return $this->db->query("
                                    SELECT DISTINCT
                                        a.id,
                                        a.i_ppap
                                    FROM
                                        tm_permintaan_pembayaranap a
                                    JOIN tm_permintaan_pembayaranap_item b
                                        ON a.id = b.id_ppap AND a.id_company = b.id_company 
                                    WHERE
                                        a.i_supplier = '$isupplier'
                                        AND a.i_status = '6' 
                                        AND a.v_sisa <> '0'
                                        AND a.id_company = '$this->idcompany'
                                        AND b.i_jenis_faktur = '$ijenis'
                                        /*AND a.id NOT IN 
                                        (
						                    SELECT 
						                    	a.id_ppap
						                    FROM 
						                    	tm_kasbank_keluarap_item a
						                    	INNER JOIN 
						                    		tm_kasbank_keluarap b 
						                    		ON (a.id_kasbank_keluarap = b.id
						                    		AND a.id_company = b.id_company)
						                    WHERE 
						                    	a.id_company = '".$this->session->userdata('id_company')."'
                                        )*/
                                    ORDER BY
                                        a.i_ppap
                                ", FALSE); 
    }

    public function getheadreff($ireferensi, $isupplier, $ijenis){
        return $this->db->query("
                                SELECT
                                    to_char(a.d_ppap,'dd-mm-yyyy') as d_ppap,
                                    a.v_total,
                                    a.v_sisa
                                FROM
                                    tm_permintaan_pembayaranap a
                                JOIN
                                    tm_permintaan_pembayaranap_item b
                                ON a.id = b.id_ppap AND a.id_company = b.id_company
                                WHERE
                                    a.id = '$ireferensi'
                                    AND a.i_supplier = '$isupplier'
                                    AND a.id_company = '$this->idcompany'
                                    AND b.i_jenis_faktur = '$ijenis'
                                ", FALSE);
    }

    public function getitemreff($ireferensi, $isupplier, $ijenis){
        return $this->db->query("                            
                                    SELECT
                                       x.jenis_faktur,
                                       x.i_jenis_faktur,
                                       x.j,
                                       x.id_company,
                                       x.id_nota,
                                       x.id_ppap,
                                       x.i_supplier,
                                       x.e_remark,
                                       x.i_nota,
                                       x.d_nota,
                                       x.total,
                                       x.sisa 
                                    FROM
                                       (
                                          SELECT
                                             'Faktur Pembelian' AS jenis_faktur,
                                             '1' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_nota as i_nota,
                                             to_char(c.d_nota, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notabtb c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company ) 
                                          WHERE
                                             c.v_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Bis2an' AS jenis_faktur,
                                             '2' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonbis2an c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Jahit' AS jenis_faktur,
                                             '3' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonjahit c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Packing' AS jenis_faktur,
                                             '4' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonpacking c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Bordir' AS jenis_faktur,
                                             '5' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonbordir c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Print' AS jenis_faktur,
                                             '6' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonprint c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Embosh' AS jenis_faktur,
                                             '7' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonembosh c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 

                                          UNION ALL
                                          SELECT
                                             'Faktur Makloon Quilting' AS jenis_faktur,
                                             '8' AS i_jenis_faktur,
                                             a.id_company,
                                             a.id_nota,
                                             a.id_ppap,
                                             a.i_jenis_faktur as j,
                                             b.i_supplier,
                                             a.e_remark,
                                             c.i_document as i_nota,
                                             to_char(c.d_document, 'dd-mm-yyyy') as d_nota,
                                             c.v_total as total,
                                             c.v_total_sisa as sisa 
                                          FROM
                                             tm_permintaan_pembayaranap_item a 
                                             JOIN
                                                tm_permintaan_pembayaranap b 
                                                ON (a.id_ppap = b.id) 
                                             JOIN
                                                tm_notamakloonquilting c 
                                                ON (a.id_nota = c.id 
                                                and b.id_company = c.id_company) 
                                          WHERE
                                             c.v_total_sisa <> '0' 
                                             AND b.v_sisa <> '0' 
                                       )
                                       as x 
                                    WHERE
                                       x.id_ppap = '$ireferensi' 
                                       AND x.sisa <> '0' 
                                       AND x.i_supplier = '$isupplier' 
                                       AND x.i_jenis_faktur = '$ijenis' 
                                       AND x.id_company = '$this->idcompany' 
                                    ORDER BY
                                       x.i_nota
                                ", FALSE);
    }

    public function getkasbank($cari){
        return $this->db->query("SELECT * FROM tr_kas_bank WHERE (i_kode_kas like '%$cari%' OR e_kas_name like '%$cari%') and id_company = '$this->idcompany'", FALSE);
    }

    public function getbank($ikodekas){
        return $this->db->query("
                                SELECT
                                    a.i_bank,
                                    b.e_bank_name
                                FROM
                                    tr_kas_bank a
                                    LEFT JOIN 
                                        tr_bank b 
                                        ON (a.i_bank = b.i_bank and a.id_company = b.id_company)
                                WHERE
                                    a.i_kode_kas = '$ikodekas'
                                    and a.id_company = '$this->idcompany'
                                ORDER BY 
                                    b.e_bank_name
                                ", FALSE);
    }

    public function insertheader($id, $ikasbankkeluarap, $ibagian, $datekeluar, $ipembayaran, $partner, $ikasbank, $vbayar, $vsisa, $eremark, $ijenis, $i_voucher){
            $dentry = date("Y-m-d H:s:i");
            $idcompany =  $this->session->userdata('id_company');
            $data   = array(
                        'id_company'        => $idcompany,
                        'id'                => $id,
                        'i_kasbank_keluarap'=> $ikasbankkeluarap,
                        'd_kasbank_keluarap'=> $datekeluar,
                        'i_bagian'          => $ibagian,
                        'i_supplier'        => $partner,
                        'i_kode_kas'        => $ikasbank,
                        'v_bayar'           => $vbayar,
                        'v_sisa'            => $vbayar,
                        'i_jenis_faktur'    => $ijenis,
                        'e_remark'          => $eremark,
                        'i_voucher'         => $i_voucher,
                        'd_entry'           => current_datetime()
            );
            $this->db->insert('tm_kasbank_keluarap', $data);
    }

    public function insertdetail($id, $idppap, $idnota, $vnota, $vbayar, $edesc){ 
        $dentry = date("Y-m-d H:s:i");
        $idcompany =  $this->session->userdata('id_company');
        $data = array(
                        'id_kasbank_keluarap'   => $id,
                        'id_ppap'               => $idppap,
                        'id_nota'               => $idnota,
                        'v_nota'                => $vnota,
                        'v_nota_bayar'          => $vbayar,
                        'e_remark'              => $edesc,
                        'id_company'            => $idcompany,
                        'd_entry'               => current_datetime()
        );
        $this->db->insert('tm_kasbank_keluarap_item', $data);
    } 

    
    public function baca_header($idkasbankkeluarap, $isupplier){
        return $this->db->query("
                                    SELECT DISTINCT
                                        a.id,
                                        a.i_kasbank_keluarap,
                                        to_char(a.d_kasbank_keluarap,'dd-mm-yyyy') as d_kasbank_keluarap,
                                        a.i_bagian,
                                        d.e_bagian_name,
                                        a.i_supplier,
                                        e.e_supplier_name,
                                        b.id_ppap,
                                        c.i_ppap as i_referensi,
                                        to_char(c.d_ppap,'dd-mm-yyyy') as d_ppap,
                                        a.i_kode_kas,
                                        f.e_kas_name,
                                        f.i_bank,
                                        g.e_bank_name,
                                        a.v_sisa,
                                        a.v_bayar,
                                        a.e_remark,
                                        a.i_status,
                                        a.i_jenis_faktur,
                                        h.e_jenis_faktur_name ,
                                        c.v_sisa as sisa_pp
                                    FROM
                                        tm_kasbank_keluarap a
                                        INNER JOIN 
                                            tm_kasbank_keluarap_item b
                                            ON (a.id = b.id_kasbank_keluarap)
                                        INNER JOIN
                                            tm_permintaan_pembayaranap c
                                            ON (b.id_ppap = c.id AND a.id_company = c.id_company)
                                        INNER JOIN
                                            tr_bagian d
                                            ON (a.i_bagian = d.i_bagian AND a.id_company = d.id_company)
                                        INNER JOIN 
                                            tr_supplier e
                                            ON (a.i_supplier = e.i_supplier AND a.id_company = e.id_company)
                                        LEFT JOIN
                                            tr_kas_bank f
                                            ON (a.i_kode_kas = f.i_kode_kas AND a.id_company =  f.id_company)
                                        LEFT JOIN 
                                            tr_bank g
                                            ON (f.i_bank = g.i_bank AND a.id_company = g.id_company)
                                        INNER JOIN tr_jenis_faktur h
                                            ON h.i_jenis_faktur = a.i_jenis_faktur 
                                    WHERE
                                        a.id = '$idkasbankkeluarap'
                                        AND a.i_supplier = '$isupplier'
                                        AND a.id_company = '$this->idcompany'
                                    ORDER BY 
                                        a.id
                                ", FALSE);
    }

    public function baca_detail($idkasbankkeluarap, $idppap, $ijenisfaktur){
        return $this->db->query("                               
                                    SELECT
                                       q.jenis_faktur,
                                       q.i_jenis_faktur,
                                       q.id,
                                       q.id_kasbank_keluarap,
                                       q.id_nota,
                                       q.id_ppap,
                                       q.i_nota,
                                       q.d_nota,
                                       q.v_nota,
                                       q.v_nota_bayar,
                                       q.e_remark,
                                       q.v_sisa_reff  
                                    FROM
                                       (
                                          SELECT DISTINCT
                                             'Faktur Pembelian' AS jenis_faktur,
                                             '1' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_nota,
                                             to_char(f.d_nota, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff 
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notabtb_item e 
                                                ON (a.id_nota = e.id_nota 
                                                AND c.id_nota = e.id_nota 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notabtb f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_nota = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany'

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Bis2an' AS jenis_faktur,
                                             '2' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonbis2an_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonbis2an f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany' 

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Jahit' AS jenis_faktur,
                                             '3' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonjahit_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonjahit f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany' 

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Packing' AS jenis_faktur,
                                             '4' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonpacking_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonpacking f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany' 

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Bordir' AS jenis_faktur,
                                             '5' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonbordir_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonbordir f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany'

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Print' AS jenis_faktur,
                                             '6' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonprint_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonprint f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany'

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Embosh' AS jenis_faktur,
                                             '7' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonembosh_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonembosh f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany' 

                                          UNION ALL
                                          SELECT DISTINCT
                                             'Faktur Makloon Quilting' AS jenis_faktur,
                                             '8' AS i_jenis_faktur,
                                             a.id,
                                             a.id_kasbank_keluarap,
                                             a.id_nota,
                                             a.id_ppap,
                                             f.i_document as i_nota,
                                             to_char(f.d_document, 'dd-mm-yyyy') as d_nota,
                                             a.v_nota,
                                             a.v_nota_bayar,
                                             a.e_remark,
                                             d.v_sisa as v_sisa_reff  
                                          FROM
                                             tm_kasbank_keluarap_item a 
                                             JOIN
                                                tm_kasbank_keluarap b 
                                                ON (a.id_kasbank_keluarap = b.id 
                                                AND a.id_company = b.id_company) 
                                             JOIN
                                                tm_permintaan_pembayaranap_item c 
                                                ON (a.id_ppap = c.id_ppap 
                                                AND a.id_nota = c.id_nota) 
                                             JOIN
                                                tm_permintaan_pembayaranap d 
                                                ON (a.id_ppap = d.id 
                                                AND c.id_ppap = d.id 
                                                and b.id_company = d.id_company) 
                                             JOIN
                                                tm_notamakloonquilting_item e 
                                                ON (a.id_nota = e.id_document 
                                                AND c.id_nota = e.id_document 
                                                and b.id_company = e.id_company) 
                                             JOIN
                                                tm_notamakloonquilting f 
                                                ON (a.id_nota = f.id 
                                                AND c.id_nota = f.id 
                                                AND e.id_document = f.id 
                                                and b.id_company = f.id_company) 
                                          WHERE
                                             a.id_company = '$this->idcompany'
                                       )
                                       as q 
                                    WHERE
                                       q.id_kasbank_keluarap = '$idkasbankkeluarap' 
                                       AND q.id_ppap = '$idppap' 
                                       AND q.i_jenis_faktur = '$ijenisfaktur' 
                                    ORDER BY
                                       q.id
                                ", FALSE);
    }

    public function updateheader($id, $ikasbankkeluarap, $ibagian, $datekeluar, $ipembayaran, $partner, $ikasbank, $vbayar, $vsisa, $eremark, $ijenis){
        $dupdate = date("Y-m-d H:s:i");
        $data = array(         
                        'i_kasbank_keluarap' => $ikasbankkeluarap,
                        'd_kasbank_keluarap' => $datekeluar,
                        'i_bagian'           => $ibagian,
                        'i_kode_kas'         => $ikasbank,
                        'v_bayar'            => $vbayar,
                        'v_sisa'             => $vsisa,
                        'i_jenis_faktur'     => $ijenis,
                        'e_remark'           => $eremark,
                        'd_update'           => current_datetime()
        );        
        $this->db->where('id',$id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_kasbank_keluarap', $data);
    }

    function deletedetail($id){
        $idcompany    = $this->session->userdata('id_company'); 
        return $this->db->query("DELETE FROM tm_kasbank_keluarap_item WHERE id_kasbank_keluarap = '$id' AND id_company = '$idcompany'", FALSE);
    }

    // public function updatedetail($id, $idppap, $idnota, $vnota, $vbayar, $edesc){
    //     $data = array(
    //         'v_nota'       => $vnota,
    //         'v_nota_bayar' => $vbayar,
    //         'e_remark'     => $edesc,
    //         'd_update'     => current_datetime()
    //     );
    //     $this->db->where('id_kasbank_keluarap', $id);
    //     $this->db->where('id_ppap', $idppap);
    //     $this->db->where('id_nota', $idnota);
    //     $this->db->update('tm_kasbank_keluarap_item', $data);
    // }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function getjenisfaktur($id){
        $this->db->select('i_jenis_faktur');
        $this->db->from('tm_kasbank_keluarap');
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        return $this->db->get()->row()->i_jenis_faktur;
    }

    public function changestatus($id, $istatus, $ijenis){
        if ($istatus=='6') {
            $query = $this->db->query("                                  
                                        SELECT
                                           z.jenis_faktur,
                                           z.i_jenis_faktur,
                                           z.id,
                                           z.id_kasbank_keluarap,
                                           z.id_nota,
                                           z.id_ppap,
                                           z.v_nota_bayar,
                                           z.sisa_nota,
                                           z.sisa_ppap,
                                           z.v_bayar,
                                           z.id_company 
                                        FROM
                                           (
                                              SELECT
                                                 'Faktur Pembelian' AS jenis_faktur,
                                                 '1' AS i_jenis_faktur,
                                                 a.id,
                                                 a.id_kasbank_keluarap,
                                                 a.id_nota,
                                                 a.id_ppap,
                                                 a.v_nota_bayar,
                                                 e.v_sisa as sisa_nota,
                                                 d.v_sisa as sisa_ppap,
                                                 b.v_bayar,
                                                 a.id_company 
                                              FROM
                                                 tm_kasbank_keluarap_item a 
                                                 JOIN
                                                    tm_kasbank_keluarap b 
                                                    ON (a.id_kasbank_keluarap = b.id) 
                                                 JOIN
                                                    tm_permintaan_pembayaranap_item c 
                                                    ON (a.id_ppap = c.id_ppap 
                                                    AND a.id_nota = c.id_nota) 
                                                 JOIN
                                                    tm_permintaan_pembayaranap d 
                                                    ON (a.id_ppap = d.id 
                                                    AND c.id_ppap = d.id 
                                                    AND b.id_company = d.id_company) 
                                                 JOIN
                                                    tm_notabtb e 
                                                    ON (a.id_nota = e.id 
                                                    AND c.id_nota = e.id 
                                                    AND b.id_company = e.id_company) 
                                                 UNION ALL
                                                 SELECT
                                                    'Faktur Makloon Bis2an' AS jenis_faktur,
                                                    '2' AS i_jenis_faktur,
                                                    a.id,
                                                    a.id_kasbank_keluarap,
                                                    a.id_nota,
                                                    a.id_ppap,
                                                    a.v_nota_bayar,
                                                    e.v_total_sisa as sisa_nota,
                                                    d.v_sisa as sisa_ppap,
                                                    b.v_bayar,
                                                    a.id_company 
                                                 FROM
                                                    tm_kasbank_keluarap_item a 
                                                    JOIN
                                                       tm_kasbank_keluarap b 
                                                       ON (a.id_kasbank_keluarap = b.id) 
                                                    JOIN
                                                       tm_permintaan_pembayaranap_item c 
                                                       ON (a.id_ppap = c.id_ppap 
                                                       AND a.id_nota = c.id_nota) 
                                                    JOIN
                                                       tm_permintaan_pembayaranap d 
                                                       ON (a.id_ppap = d.id 
                                                       AND c.id_ppap = d.id 
                                                       AND b.id_company = d.id_company) 
                                                    JOIN
                                                       tm_notamakloonbis2an e 
                                                       ON (a.id_nota = e.id 
                                                       AND c.id_nota = e.id 
                                                       AND b.id_company = e.id_company) 
                                                    UNION ALL
                                                    SELECT
                                                       'Faktur Makloon Jahit' AS jenis_faktur,
                                                       '3' AS i_jenis_faktur,
                                                       a.id,
                                                       a.id_kasbank_keluarap,
                                                       a.id_nota,
                                                       a.id_ppap,
                                                       a.v_nota_bayar,
                                                       e.v_total_sisa as sisa_nota,
                                                       d.v_sisa as sisa_ppap,
                                                       b.v_bayar,
                                                       a.id_company 
                                                    FROM
                                                       tm_kasbank_keluarap_item a 
                                                       JOIN
                                                          tm_kasbank_keluarap b 
                                                          ON (a.id_kasbank_keluarap = b.id) 
                                                       JOIN
                                                          tm_permintaan_pembayaranap_item c 
                                                          ON (a.id_ppap = c.id_ppap 
                                                          AND a.id_nota = c.id_nota) 
                                                       JOIN
                                                          tm_permintaan_pembayaranap d 
                                                          ON (a.id_ppap = d.id 
                                                          AND c.id_ppap = d.id 
                                                          AND b.id_company = d.id_company) 
                                                       JOIN
                                                          tm_notamakloonjahit e 
                                                          ON (a.id_nota = e.id 
                                                          AND c.id_nota = e.id 
                                                          AND b.id_company = e.id_company) 
                                                       UNION ALL
                                                       SELECT
                                                          'Faktur Makloon Packing' AS jenis_faktur,
                                                          '4' AS i_jenis_faktur,
                                                          a.id,
                                                          a.id_kasbank_keluarap,
                                                          a.id_nota,
                                                          a.id_ppap,
                                                          a.v_nota_bayar,
                                                          e.v_total_sisa as sisa_nota,
                                                          d.v_sisa as sisa_ppap,
                                                          b.v_bayar,
                                                          a.id_company 
                                                       FROM
                                                          tm_kasbank_keluarap_item a 
                                                          JOIN
                                                             tm_kasbank_keluarap b 
                                                             ON (a.id_kasbank_keluarap = b.id) 
                                                          JOIN
                                                             tm_permintaan_pembayaranap_item c 
                                                             ON (a.id_ppap = c.id_ppap 
                                                             AND a.id_nota = c.id_nota) 
                                                          JOIN
                                                             tm_permintaan_pembayaranap d 
                                                             ON (a.id_ppap = d.id 
                                                             AND c.id_ppap = d.id 
                                                             AND b.id_company = d.id_company) 
                                                          JOIN
                                                             tm_notamakloonpacking e 
                                                             ON (a.id_nota = e.id 
                                                             AND c.id_nota = e.id 
                                                             AND b.id_company = e.id_company) 
                                                          UNION ALL
                                                          SELECT
                                                             'Faktur Makloon Bordir' AS jenis_faktur,
                                                             '5' AS i_jenis_faktur,
                                                             a.id,
                                                             a.id_kasbank_keluarap,
                                                             a.id_nota,
                                                             a.id_ppap,
                                                             a.v_nota_bayar,
                                                             e.v_total_sisa as sisa_nota,
                                                             d.v_sisa as sisa_ppap,
                                                             b.v_bayar,
                                                             a.id_company 
                                                          FROM
                                                             tm_kasbank_keluarap_item a 
                                                             JOIN
                                                                tm_kasbank_keluarap b 
                                                                ON (a.id_kasbank_keluarap = b.id) 
                                                             JOIN
                                                                tm_permintaan_pembayaranap_item c 
                                                                ON (a.id_ppap = c.id_ppap 
                                                                AND a.id_nota = c.id_nota) 
                                                             JOIN
                                                                tm_permintaan_pembayaranap d 
                                                                ON (a.id_ppap = d.id 
                                                                AND c.id_ppap = d.id 
                                                                AND b.id_company = d.id_company) 
                                                             JOIN
                                                                tm_notamakloonbordir e 
                                                                ON (a.id_nota = e.id 
                                                                AND c.id_nota = e.id 
                                                                AND b.id_company = e.id_company) 
                                                             UNION ALL
                                                             SELECT
                                                                'Faktur Makloon Print' AS jenis_faktur,
                                                                '6' AS i_jenis_faktur,
                                                                a.id,
                                                                a.id_kasbank_keluarap,
                                                                a.id_nota,
                                                                a.id_ppap,
                                                                a.v_nota_bayar,
                                                                e.v_total_sisa as sisa_nota,
                                                                d.v_sisa as sisa_ppap,
                                                                b.v_bayar,
                                                                a.id_company 
                                                             FROM
                                                                tm_kasbank_keluarap_item a 
                                                                JOIN
                                                                   tm_kasbank_keluarap b 
                                                                   ON (a.id_kasbank_keluarap = b.id) 
                                                                JOIN
                                                                   tm_permintaan_pembayaranap_item c 
                                                                   ON (a.id_ppap = c.id_ppap 
                                                                   AND a.id_nota = c.id_nota) 
                                                                JOIN
                                                                   tm_permintaan_pembayaranap d 
                                                                   ON (a.id_ppap = d.id 
                                                                   AND c.id_ppap = d.id 
                                                                   AND b.id_company = d.id_company) 
                                                                JOIN
                                                                   tm_notamakloonprint e 
                                                                   ON (a.id_nota = e.id 
                                                                   AND c.id_nota = e.id 
                                                                   AND b.id_company = e.id_company) 
                                                                UNION ALL
                                                                SELECT
                                                                   'Faktur Makloon Embosh' AS jenis_faktur,
                                                                   '7' AS i_jenis_faktur,
                                                                   a.id,
                                                                   a.id_kasbank_keluarap,
                                                                   a.id_nota,
                                                                   a.id_ppap,
                                                                   a.v_nota_bayar,
                                                                   e.v_total_sisa as sisa_nota,
                                                                   d.v_sisa as sisa_ppap,
                                                                   b.v_bayar,
                                                                   a.id_company 
                                                                FROM
                                                                   tm_kasbank_keluarap_item a 
                                                                   JOIN
                                                                      tm_kasbank_keluarap b 
                                                                      ON (a.id_kasbank_keluarap = b.id) 
                                                                   JOIN
                                                                      tm_permintaan_pembayaranap_item c 
                                                                      ON (a.id_ppap = c.id_ppap 
                                                                      AND a.id_nota = c.id_nota) 
                                                                   JOIN
                                                                      tm_permintaan_pembayaranap d 
                                                                      ON (a.id_ppap = d.id 
                                                                      AND c.id_ppap = d.id 
                                                                      AND b.id_company = d.id_company) 
                                                                   JOIN
                                                                      tm_notamakloonembosh e 
                                                                      ON (a.id_nota = e.id 
                                                                      AND c.id_nota = e.id 
                                                                      AND b.id_company = e.id_company) 
                                                                   UNION ALL
                                                                   SELECT
                                                                      'Faktur Makloon Quilting' AS jenis_faktur,
                                                                      '8' AS i_jenis_faktur,
                                                                      a.id,
                                                                      a.id_kasbank_keluarap,
                                                                      a.id_nota,
                                                                      a.id_ppap,
                                                                      a.v_nota_bayar,
                                                                      e.v_total_sisa as sisa_nota,
                                                                      d.v_sisa as sisa_ppap,
                                                                      b.v_bayar,
                                                                      a.id_company 
                                                                   FROM
                                                                      tm_kasbank_keluarap_item a 
                                                                      JOIN
                                                                         tm_kasbank_keluarap b 
                                                                         ON (a.id_kasbank_keluarap = b.id) 
                                                                      JOIN
                                                                         tm_permintaan_pembayaranap_item c 
                                                                         ON (a.id_ppap = c.id_ppap 
                                                                         AND a.id_nota = c.id_nota) 
                                                                      JOIN
                                                                         tm_permintaan_pembayaranap d 
                                                                         ON (a.id_ppap = d.id 
                                                                         AND c.id_ppap = d.id 
                                                                         AND b.id_company = d.id_company) 
                                                                      JOIN
                                                                         tm_notamakloonquilting e 
                                                                         ON (a.id_nota = e.id 
                                                                         AND c.id_nota = e.id 
                                                                         AND b.id_company = e.id_company) 
                                                                   )
                                                                   AS z 
                                                                WHERE
                                                                   z.id_kasbank_keluarap = '$id'
                                                                   AND z.id_nota IN 
                                                                   (
                                                                      SELECT
                                                                         id_nota 
                                                                      FROM
                                                                         tm_kasbank_keluarap_item 
                                                                      WHERE
                                                                         id_kasbank_keluarap = '$id'
                                                                   )
                                                                   AND z.id_company = '$this->idcompany'
                                                                   AND z.i_jenis_faktur = '$ijenis'
                                    ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $vbayar = $key->v_bayar;
                    $idppap = $key->id_ppap;
                    if($key->i_jenis_faktur == '1'){
                        $this->db->query("
                                            UPDATE
                                                tm_notabtb 
                                            SET
                                                v_sisa = v_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                AND id_company = '$this->idcompany'
                                        ", FALSE);

                        $query2 = $this->db->query("SELECT id, v_sisa FROM tm_notabtb WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company='$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        if($query2->num_rows() > 0){
                            foreach ($query2->result() as $key) {
                                $sisa = $key->v_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                UPDATE
                                                    tm_notabtb
                                                SET
                                                    i_status = '$istatusnota',
                                                    f_status_lunas = '$fstatus'
                                                WHERE
                                                    id = '$idnota'
                                                    and id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '2'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonbis2an 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query2 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonbis2an WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);

                        if($query2->num_rows() > 0){
                            foreach ($query2->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonbis2an
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '3'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonjahit 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query3 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonjahit WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        
                        if($query3->num_rows() > 0){
                            foreach ($query3->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonjahit
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '4'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonpacking 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query4 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonpacking WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        
                        if($query4->num_rows() > 0){
                            foreach ($query4->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonpacking
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '5'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonbordir 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query5 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonbordir WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        
                        if($query5->num_rows() > 0){
                            foreach ($query5->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonbordir
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '6'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonprint 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query6 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonprint WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        
                        if($query6->num_rows() > 0){
                            foreach ($query6->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonprint
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '7'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonembosh 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query7 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonembosh WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        
                        if($query7->num_rows() > 0){
                            foreach ($query7->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonembosh
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_faktur == '8'){
                        $this->db->query("
                                            UPDATE
                                                tm_notamakloonquilting 
                                            SET
                                                v_total_sisa = v_total_sisa - $key->v_nota_bayar
                                            WHERE
                                                id = '$key->id_nota'
                                                and id_company = '$this->idcompany'
                                        ", FALSE);

                        $query8 = $this->db->query("SELECT id, v_total_sisa FROM tm_notamakloonquilting WHERE id IN (SELECT id_nota FROM tm_kasbank_keluarap_item a JOIN tm_kasbank_keluarap b ON a.id_kasbank_keluarap = b.id AND a.id_company = b.id_company WHERE a.id_kasbank_keluarap = '$id' AND a.id_company = '$this->idcompany' AND b.i_jenis_faktur = '$ijenis' )", FALSE);
                        
                        if($query8->num_rows() > 0){
                            foreach ($query8->result() as $key) {
                                $sisa     = $key->v_total_sisa;
                                $idnota   = $key->id;
                                if($sisa <> 0){
                                    $istatusnota = '13';
                                    $fstatus = 'f';
                                }else{
                                    $istatusnota = '12';
                                    $fstatus = 't';
                                }
                                $this->db->query("
                                                    UPDATE
                                                        tm_notamakloonquilting
                                                    SET
                                                        i_status = '$istatusnota',
                                                        f_status_lunas = '$fstatus'
                                                    WHERE
                                                        id = '$idnota'
                                                        AND id_company = '$this->idcompany'
                                                ", FALSE);
                            }
                        }
                    }

                }
                $this->db->query("
                                    UPDATE
                                        tm_permintaan_pembayaranap 
                                    SET 
                                        v_sisa =  v_sisa - $vbayar
                                    WHERE 
                                        id = '$idppap'
                                        and id_company = '$this->idcompany'
                                ", FALSE);
            }
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->update('tm_kasbank_keluarap', $data);
    }
}
/* End of file Mmaster.php */