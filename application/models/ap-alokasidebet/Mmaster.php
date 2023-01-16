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
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

        $datatables->query("
                            SELECT DISTINCT
                                0 as no, 	
                            	a.id, 
                                a.i_document, 
                                a.i_bagian, 
                                to_char(a.d_document,'dd-mm-yyyy') as d_document,
                                f.e_jenis_debet_name,
                            	a.id_supplier,
                            	d.i_supplier, 
                            	d.e_supplier_name,
                            	b.id_document_reff,
                            	g.i_ppap as i_document_referensi,
                                a.v_bayar,
                                a.i_status, 
                                e.e_status_name, 
                                e.label_color as label,
                                a.i_jenis_debet,
                                '$dfrom' as dfrom, 
                                '$dto' as dto,
                                '$folder' as folder, 
                                '$i_menu' as i_menu
                            FROM 
                            	tm_alokasi_debet a
                            	LEFT JOIN 
                            		tm_alokasi_debet_item b 
                            		ON (a.id = b.id_document
                            		AND a.id_company = b.id_company)
                            	LEFT JOIN 
                            		(
                            		  SELECT 
                            			id,
                            			i_document as i_document,
                                        id_company,
                                        '1' as i_jenis_debet
                            		  FROM
                            			tm_dn_ap_retur_beli 
                            		  UNION ALL
                            		  SELECT 
                            			id,
                            			i_document,
                                        id_company,
                                        '2' as i_jenis_debet
                            		  FROM 
                            			tm_dn_ap_retur_makloon 
                            		) c
                            		ON (b.id_document_reff = c.id
                                    AND b.id_company = c.id_company
                                    AND a.i_jenis_debet = c.i_jenis_debet)
                            	INNER JOIN
                            		tr_supplier d 
                            		ON (a.id_supplier = d.id
                                    AND a.id_company = d.id_company)
                                INNER JOIN  
                                    tr_status_document e
                                    ON (a.i_status = e.i_status)
                                INNER JOIN 
                                    tr_jenis_debet f
                                    ON (a.i_jenis_debet = f.i_jenis_debet)
                                LEFT JOIN 
                                    tm_permintaan_pembayaranap g
                                    ON (b.id_document_reff = g.id
                                    AND b.id_company = g.id_company)
                            WHERE 
                            	a.i_status <> '5'
                                AND a.id_company = '".$this->session->userdata('id_company')."'
                                $where
                            ORDER BY 
                            	a.i_document
                            ",FALSE);

            $datatables->edit('e_status_name', function ($data) {
                return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';
            });

            $datatables->edit('v_bayar', function($data){
                return 'Rp. '.number_format($data['v_bayar'],2);
            }); 

            $datatables->add('action', function ($data) {
                $id          = trim($data['id']);
                $idsupplier  = trim($data['id_supplier']);
                $ijenisdebet = trim($data['i_jenis_debet']);
                $i_menu      = $data['i_menu'];
                $folder      = $data['folder'];
                $dfrom       = $data['dfrom'];
                $dto         = $data['dto'];
                $i_status    = trim($data['i_status']);
                $data        = '';

                if(check_role($i_menu, 2)){
                    $data .= "<a href=\"#\" title='View' onclick='show(\"$folder/cform/view/$id/$idsupplier/$ijenisdebet/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
                }
                if(check_role($i_menu, 3)) {
                    if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                        $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$idsupplier/$ijenisdebet/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
                    }
                }
                if(check_role($i_menu, 7)){
                   if ($i_status == '2') {
                      $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$idsupplier/$ijenisdebet/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                  }
                }
                if (check_role($i_menu, 4) && ($i_status=='1')) {
                   $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
                }
			    return $data;
            });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('id_supplier');
        $datatables->hide('i_supplier');
        $datatables->hide('id');
        $datatables->hide('id_document_reff');
        $datatables->hide('i_jenis_debet');
        $datatables->hide('label');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_bagian');
        return $datatables->generate();
    }
    
    public function bagian(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
       /* $this->db->where('i_level', $this->session->userdata('i_level'));*/
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->idcompany);
        $this->db->where('b.id_company', $this->idcompany);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian)
    {
      $this->db->select('i_document');
      $this->db->from('tm_alokasi_debet');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata("id_company"));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
    } 

   /*----------  RUNNING NO DOKUMEN  ----------*/    
   public function runningnumber($thbl,$tahun,$ibagian)
   {
       $cek = $this->db->query("
           SELECT 
               substring(i_document, 1, 2) AS kode 
           FROM tm_alokasi_debet
           WHERE i_status <> '5'
           AND i_bagian = '$ibagian'
           AND id_company = '".$this->session->userdata('id_company')."'
           ORDER BY id DESC");
       if ($cek->num_rows()>0) {
           $kode = $cek->row()->kode;
       }else{
           $kode = 'AL';
       }
       $query  = $this->db->query("
           SELECT
               max(substring(i_document, 9, 6)) AS max
           FROM
               tm_alokasi_debet
           WHERE to_char (d_document, 'yyyy') >= '".date('Y')."'
           AND to_char (d_document, 'yyyy') >= '$tahun'
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

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_alokasi_debet');
        return $this->db->get()->row()->id+1;
    }

    public function bacajenisfaktur($isupplier, $ijenis){
        return $this->db->query("
                                SELECT DISTINCT
                                	a.i_supplier,
                                	c.i_jenis_faktur,
                                	d.e_jenis_faktur_name
                                FROM 
                                	tm_permintaan_pembayaranap a
                                	INNER JOIN 
                                		(
                                		  SELECT 
                                            a.id_supplier,
                                			b.i_supplier,
                                			a.id_company,
                                			'1' as i_jenis_debet
                                		  FROM 
                                			tm_dn_ap_retur_beli a
                                            INNER JOIN
                                                tr_supplier b 
                                                ON (a.id_supplier = b.id 
                                                AND a.id_company = b.id_company)
                                		  UNION ALL
                                		  SELECT 
                                            a.id_supplier,
                                			b.i_supplier, 
                                			a.id_company,
                                			'2' as i_jenis_debet
                                		  FROM 
                                			tm_dn_ap_retur_makloon a
                                			INNER JOIN
                                				tr_supplier b 
                                				ON (a.id_supplier = b.id 
                                				AND a.id_company = b.id_company)
                                		) b
                                		ON (a.i_supplier = b.i_supplier
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tm_permintaan_pembayaranap_item c
                                		ON (a.id = c.id_ppap 
                                		AND a.id_company = b.id_company)
                                	INNER JOIN 
                                		tr_jenis_faktur d
                                		ON (c.i_jenis_faktur = d.i_jenis_faktur)
                                WHERE 
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND b.i_supplier = '$isupplier'
                                	AND b.i_jenis_debet = '$ijenis'
                                ", FALSE);
    }

    public function bacajenisdebet($cari){
        return $this->db->query("
                                    SELECT DISTINCT
                                        a.i_jenis_debet,
                                        a.e_jenis_debet_name
                                    FROM 
                                        tr_jenis_debet a
                                    WHERE
                                        a.e_jenis_debet_name ILIKE '%$cari%'
                                        AND a.f_status = 't'
                                    ORDER BY
                                        a.e_jenis_debet_name
                                ", FALSE);
    }

    public function bacasupplier($cari, $ijenis){
        return $this->db->query("
                                SELECT DISTINCT
                                	a.id_supplier, 
                                	b.i_supplier, 
                                	b.e_supplier_name,
                                	a.i_jenis_debet
                                FROM                                     
                                	(
                                	  SELECT 
                                		a.id,
                                		a.id_supplier,
                                		a.id_company,
                                        '1' as i_jenis_debet,
                                        a.i_status
                                	  FROM 
                                		tm_dn_ap_retur_beli a
                                		INNER JOIN 
                                			tr_supplier b
                                			ON (a.id_supplier = b.id
                                			AND a.id_company = b.id_company)
                                	  UNION ALL 
                                	  SELECT 
                                		id,
                                		id_supplier, 
                                		id_company,
                                        '2' as i_jenis_debet,
                                        i_status
                                	  FROM 
                                		tm_dn_ap_retur_makloon 
                                	) a
                                	INNER JOIN 
                                		tr_supplier b 
                                		ON (a.id_supplier = b.id
                                		AND a.id_company = b.id_company)
                                WHERE
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND (
                                		a.id NOT IN (SELECT id_document_debet FROM tm_alokasi_debet WHERE id_company = '".$this->session->userdata('id_company')."')
                                		OR 
                                		a.i_jenis_debet NOT IN (SELECT i_jenis_debet FROM tm_alokasi_debet WHERE id_company = '".$this->session->userdata('id_company')."')
                                    )
                                    AND a.i_jenis_debet = '$ijenis'
                                    AND a.i_status = '6'
                                ", FALSE);
    }

    public function getdebet($idsupplier, $ijenis){
        return $this->db->query("
                                SELECT DISTINCT
                                    a.id,
                                    a.i_document,
                                    a.i_jenis_debet
                                FROM 
                                    (
                                      SELECT 
                                        b.id as id_supplier,
                                        a.id_company,
                                        a.i_status,
                                        '1' as i_jenis_debet,
                                        a.id,
                                        a.i_document as i_document
                                      FROM
                                        tm_dn_ap_retur_beli a
                                        INNER JOIN
                                            tr_supplier b
                                            ON (a.id_supplier = b.id
                                            AND a.id_company = b.id_company)
                                      UNION ALL
                                      SELECT 
                                        a.id_supplier,
                                        a.id_company,
                                        a.i_status,
                                        '2' as i_jenis_debet,
                                        a.id,
                                        a.i_document
                                      FROM
                                        tm_dn_ap_retur_makloon a
                                    ) a 
                                WHERE
                                    a.id_company = '".$this->session->userdata('id_company')."'
                                    AND a.i_status = '6'
                                    AND a.i_jenis_debet = '$ijenis'
                                    AND a.id_supplier = '$idsupplier'
                                ", FALSE); 
    }

    public function bacajumdebet($ireferensi, $isupplier, $ijenis){
        return $this->db->query("
                                SELECT 
                                	a.id,
                                	a.d_document, 
                                	a.v_sisa,
                                	a.id_company,
                                	a.i_supplier,
                                	a.i_jenis_debet
                                FROM 
                                	(
                                	  SELECT 
                                		a.id,
                                		to_char(a.d_document, 'dd-mm-yyyy') as d_document, 
                                		a.v_sisa, 
                                		a.id_company, 
                                		b.i_supplier,
                                        a.id_supplier,
                                        '1' as i_jenis_debet,
                                        a.i_status
                                	  FROM 
                                		tm_dn_ap_retur_beli a
                                        INNER JOIN 
                                            tr_supplier b
                                            ON (a.id_supplier = b.id
                                            AND a.id_company = b.id_company)
                                	  UNION ALL 
                                	  SELECT
                                		a.id, 
                                		to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                		a.v_sisa,
                                		a.id_company,
                                		b.i_supplier,
                                        a.id_supplier,
                                        '2' as i_jenis_debet,
                                        a.i_status
                                	  FROM 
                                		tm_dn_ap_retur_makloon a
                                		INNER JOIN 
                                			tr_supplier b
                                			ON (a.id_supplier = b.id
                                			AND a.id_company = b.id_company)
                                	) a
                                WHERE 
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id = '$ireferensi'
                                    AND a.i_jenis_debet = '$ijenis'
                                    AND a.i_supplier = '$isupplier'
                                    AND a.i_status = '6'
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
                                    AND a.id NOT IN 
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
                                    )
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

    public function insertheader($id, $ikasbankkeluarap, $datekeluar, $ibagian, $idsupplier, $ijenisdebet, $idebet, $ijenisfaktur, $vsisa, $vbayar, $eremark){
        $data   = array(
            'id_company'        => $this->session->userdata('id_company'),
            'id'                => $id,
            'i_document'        => $ikasbankkeluarap,
            'd_document'        => $datekeluar,
            'i_bagian'          => $ibagian,
            'id_supplier'       => $idsupplier,
            'i_jenis_debet'     => $ijenisdebet,
            'i_jenis_faktur'    => $ijenisfaktur,
            'id_document_debet' => $idebet,
            'v_bayar'           => $vbayar,
            'v_sisa'            => $vsisa,
            'e_remark'          => $eremark,
            'd_entry'           => current_datetime()
        );
        $this->db->insert('tm_alokasi_debet', $data);
    }

    public function insertdetail($id, $idppap, $idnota, $vnota, $vbayar, $edesc){ 
        $data = array(
            'id_company'        => $this->session->userdata('id_company'),
            'id_document'       => $id,
            'id_document_reff'  => $idppap,
            'id_nota'           => $idnota,
            'v_nota'            => $vnota,
            'v_nota_bayar'      => $vbayar,
            'e_remark'          => $edesc
        );
        $this->db->insert('tm_alokasi_debet_item', $data);
    } 
    
    public function baca_header($id, $idsupplier, $ijenisdebet){
        return $this->db->query("
                                SELECT	
                                	a.i_bagian, 
                                	a.id,
                                	a.i_document,
                                	to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                	a.i_jenis_debet,
                                	f.e_jenis_debet_name,
                                	a.id_supplier, 
                                	e.i_supplier, 
                                	e.e_supplier_name,
                                	a.id_document_debet,
                                	b.i_document_debet,
                                	to_char(b.d_document_debet, 'dd-mm-yyyy') as d_document_debet,
                                	a.i_jenis_faktur, 
                                	g.e_jenis_faktur_name,
                                	c.id_document_reff,
                                	d.i_ppap as i_document_reff,
                                	to_char(d.d_ppap, 'dd-mm-yyyy') as d_document_reff,
                                	a.v_bayar,
                                	a.v_sisa,
                                    a.e_remark,
                                    a.i_status,
                                    c.v_nota,
                                    b.v_sisa as v_sisa_debet_awal,
                                    b.v_total as v_total_debet
                                FROM 
                                	tm_alokasi_debet a
                                	LEFT JOIN 
                                		(
                                		  SELECT 
                                			id, 
                                			i_document as i_document_debet, 
                                			d_document as d_document_debet,
                                			'1' as i_jenis_debet,
                                            id_company,
                                            v_sisa,
                                            v_total
                                		  FROM 
                                			tm_dn_ap_retur_beli 
                                		  UNION ALL 
                                		  SELECT 
                                			id, 
                                			i_document as i_document_debet, 
                                			d_document as d_document_debet,
                                			'2' as i_jenis_debet,
                                            id_company,
                                            v_sisa,
                                            v_total
                                		  FROM 
                                			tm_dn_ap_retur_makloon 
                                		) b 
                                		ON (a.id_document_debet = b.id
                                		AND a.i_jenis_debet = b.i_jenis_debet 
                                		AND a.id_company = b.id_company)
                                	LEFT JOIN 
                                		tm_alokasi_debet_item c
                                		ON (a.id = c.id_document
                                		AND a.id_company = c.id_company)
                                	LEFT JOIN 
                                		tm_permintaan_pembayaranap d
                                		ON (c.id_document_reff = d.id
                                		AND c.id_company = d.id_company)
                                	INNER JOIN 
                                		tr_supplier e
                                		ON (a.id_supplier = e.id 
                                		AND a.id_company = e.id_company)
                                	INNER JOIN 
                                		tr_jenis_debet f
                                		ON (a.i_jenis_debet = f.i_jenis_debet)
                                	INNER JOIN 
                                		tr_jenis_faktur g 
                                		ON (a.i_jenis_faktur = g.i_jenis_faktur)
                                WHERE 
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id = '$id'
                                	AND a.id_supplier = '$idsupplier'
                                	AND a.i_jenis_debet = '$ijenisdebet'
                                ", FALSE);
    }

    public function baca_detail($id, $idsupplier){
        return $this->db->query("                               
                                SELECT 
                                	a.id, 
                                	a.id_document, 
                                	a.id_document_reff, 
                                	a.id_nota, 
                                	d.i_nota,
                                	to_char(d.d_nota, 'dd-mm-yyyy') as d_nota,
                                	a.v_nota,
                                	a.v_nota_bayar, 
                                	a.e_remark
                                FROM 
                                	tm_alokasi_debet_item a
                                	LEFT JOIN 
                                		tm_alokasi_debet b 
                                		ON (a.id_document = b.id
                                		AND a.id_company = b.id_company)
                                	LEFT JOIN 
                                		tm_permintaan_pembayaranap c 
                                		ON (a.id_document_reff = c.id
                                		AND a.id_company = c.id_company)
                                	LEFT JOIN 
                                		(
                                		  SELECT 
                                			id,
                                			i_nota,
                                			d_nota,
                                			'1' as i_jenis_faktur,
                                			id_company
                                		  FROM 
                                			tm_notabtb 
                                		  UNION ALL
                                		  SELECT
                                			id,
                                			i_document as i_nota,
                                			d_document as d_nota,
                                			'2' as i_jenis_faktur,
                                			id_company
                                		  FROM 
                                			tm_notamakloonbis2an 
                                		  UNION ALL 
                                		  SELECT
                                			id,
                                			i_document as i_nota, 
                                			d_document as d_nota,
                                			'8' as i_jenis_faktur,
                                			id_company 
                                		  FROM 
                                			tm_notamakloonquilting 
                                		) d
                                		ON (a.id_nota = d.id
                                		AND a.id_company = d.id_company
                                		AND b.i_jenis_faktur = d.i_jenis_faktur)
                                WHERE 
                                	a.id_company = '".$this->session->userdata('id_company')."'
                                	AND a.id_document = '$id'
                                	AND b.id_supplier = '$idsupplier'
                                ", FALSE);
    }

    public function cek_kodeedit($ikasbankkeluarap, $ikodeold, $ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_alokasi_debet');
        $this->db->where('i_document', $ikasbankkeluarap);
        $this->db->where('i_document <>', $ikodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status','5');
        return $this->db->get();
    }

    public function updateheader($id, $ikasbankkeluarap, $datekeluar, $ibagian, $idsupplier, $ijenisdebet, $idebet, $ijenisfaktur, $vsisa, $vbayar, $eremark){
        $data   = array(
            'id_company'        => $this->session->userdata('id_company'),
            'i_document'        => $ikasbankkeluarap,
            'd_document'        => $datekeluar,
            'i_bagian'          => $ibagian,
            'id_supplier'       => $idsupplier,
            'i_jenis_debet'     => $ijenisdebet,
            'i_jenis_faktur'    => $ijenisfaktur,
            'id_document_debet' => $idebet,
            'v_bayar'           => $vbayar,
            'v_sisa'            => $vsisa,
            'e_remark'          => $eremark,
            'd_update'          => current_datetime()
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->update('tm_alokasi_debet', $data);
    }

    function deletedetail($id){
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("DELETE FROM tm_alokasi_debet_item WHERE id_document = '$id' AND id_company = '$idcompany'", FALSE);
    }

    public function estatus($istatus) {
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id,$istatus) {
        if ($istatus=='6') {
            $query = $this->db->query("
                SELECT DISTINCT
                	b.id, 
                    b.id_supplier,
                    c.i_supplier,
                	b.id_document_debet as id_debet,
                	a.id_document_reff as id_permintaan_pembayaran,
                	a.id_nota as id_nota,
                	b.v_bayar as total_bayar_nota, /*untuk update ke debet*/
                    /*b.v_sisa as sisa_nota_belum_bayar*/
                    b.i_jenis_debet, 
	                b.i_jenis_faktur
                FROM 
                	tm_alokasi_debet_item a
                	LEFT JOIN 
                		tm_alokasi_debet b 
                		ON (a.id_document = b.id
                        AND a.id_company = b.id_company)
                    INNER JOIN 
		                tr_supplier c
		                ON (b.id_supplier = c.id
		                AND b.id_company = c.id_company)
                WHERE 
                	b.id = '$id'
                	AND b.id_company = '".$this->session->userdata('id_company')."'    
            ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("
                        UPDATE
                            tm_permintaan_pembayaranap 
                        SET 
                            v_sisa = v_sisa - $key->total_bayar_nota
                        WHERE
                            id = '$key->id_permintaan_pembayaran'
                            AND i_supplier = '$key->i_supplier'
                            AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);
                    if($key->i_jenis_debet == '1'){
                        $this->db->query("
                            UPDATE
                              tm_dn_ap_retur_beli
                            SET
                              v_sisa = v_sisa - $key->total_bayar_nota
                            WHERE
                              id = '$key->id_debet'
                              AND id_supplier = '$key->id_supplier'
                              AND id_company = '".$this->session->userdata('id_company')."'
                        ", FALSE);
                        $this->db->query("
                            UPDATE 
                                tm_notabtb 
                            SET 
                                v_sisa = v_sisa - $key->total_bayar_nota
                            WHERE 
                                id = '$key->id_nota'
                                AND id_company = '".$this->session->userdata('id_company')."'
                        ", FALSE);

                        $query2 = $this->db->query("
                            SELECT 
                                id,
                                v_sisa
                            FROM    
                                tm_notabtb
                            WHERE 
                                id IN
                                ( SELECT 
                                    id_nota
                                  FROM
                                    tm_alokasi_debet_item a
                                    LEFT JOIN 
                                        tm_alokasi_debet b
                                        ON (a.id_document = b.id 
                                        AND a.id_company = b.id_company)
                                  WHERE 
                                    a.id_document = '$id'
                                    AND a.id_company = '".$this->session->userdata('id_company')."'
                                    AND b.i_jenis_faktur = '$key->i_jenis_faktur'
                                )
                        ", FALSE);
                        if($query2->num_rows()>0){
                            foreach($query2->result() as $key){
                                $sisa   = $key->v_sisa;
                                $idnota = $key->id;
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
                                        i_status =  '$istatusnota',
                                        f_status_lunas = '$fstatus'
                                    WHERE 
                                        id = '$idnota'
                                        AND id_company = '".$this->session->userdata('id_company')."'
                                ", FALSE);
                            }
                        }
                    }else if($key->i_jenis_debet == '2'){
                        $this->db->query("
                            UPDATE
                                tm_dn_ap_retur_makloon 
                            SET 
                                v_sisa = v_sisa - $key->total_bayar_nota 
                            WHERE
                                id = '$key->id_debet'
                                AND id_supplier = '$key->id_supplier'
                                AND id_company = '".$this->session->userdata('id_company')."'
                        ", FALSE);
                        if($key->i_jenis_faktur == '2'){
                            $this->db->query("
                                UPDATE
                                    tm_notamakloonbis2an 
                                SET 
                                    v_total_sisa = v_total_sisa - $key->total_bayar_nota
                                WHERE 
                                    id = '$key->id_nota'
                                    AND id_company = '".$this->session->userdata('id_company')."'
                            ", FALSE);
                            
                            $query2 = $this->db->query("
                                SELECT 
                                    id,
                                    v_total_sisa
                                FROM    
                                    tm_notamakloonbis2an 
                                WHERE 
                                    id IN
                                    ( SELECT 
                                        id_nota
                                      FROM
                                        tm_alokasi_debet_item a
                                        LEFT JOIN 
                                            tm_alokasi_debet b
                                            ON (a.id_document = b.id 
                                            AND a.id_company = b.id_company)
                                      WHERE 
                                        a.id_document = '$id'
                                        AND a.id_company = '".$this->session->userdata('id_company')."'
                                        AND b.i_jenis_faktur = '$key->i_jenis_faktur'
                                    )
                            ", FALSE);
                            if($query2->num_rows()>0){
                                foreach($query2->result() as $key){
                                    $sisa   = $key->v_total_sisa;
                                    $idnota = $key->id;
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
                                            i_status =  '$istatusnota',
                                            f_status_lunas = '$fstatus'
                                        WHERE 
                                            id = '$idnota'
                                            AND id_company = '".$this->session->userdata('id_company')."'
                                    ", FALSE);
                                }
                            }
                        }else if($key->i_jenis_faktur == '8'){
                            $this->db->query("
                                UPDATE
                                    tm_notamakloonquilting 
                                SET 
                                    v_total_sisa = v_total_sisa - $key->total_bayar_nota
                                WHERE 
                                    id = '$key->id_nota'
                                    AND id_company = '".$this->session->userdata('id_company')."'
                            ", FALSE);
                            
                            $query2 = $this->db->query("
                                SELECT 
                                    id,
                                    v_total_sisa
                                FROM    
                                    tm_notamakloonquilting 
                                WHERE 
                                    id IN
                                    ( SELECT 
                                        id_nota
                                      FROM
                                        tm_alokasi_debet_item a
                                        LEFT JOIN 
                                            tm_alokasi_debet b
                                            ON (a.id_document = b.id 
                                            AND a.id_company = b.id_company)
                                      WHERE 
                                        a.id_document = '$id'
                                        AND a.id_company = '".$this->session->userdata('id_company')."'
                                        AND b.i_jenis_faktur = '$key->i_jenis_faktur'
                                    )
                            ", FALSE);
                            if($query2->num_rows()>0){
                                foreach($query2->result() as $key){
                                    $sisa   = $key->v_total_sisa;
                                    $idnota = $key->id;
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
                                            i_status =  '$istatusnota',
                                            f_status_lunas = '$fstatus'
                                        WHERE 
                                            id = '$idnota'
                                            AND id_company = '".$this->session->userdata('id_company')."'
                                    ", FALSE);
                                }
                            }
                        }
                    }
                }
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
        $this->db->update('tm_alokasi_debet', $data);
      }
}
/* End of file Mmaster.php */