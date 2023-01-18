<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'NA';
        }else{
            return 'XX';
        }
    }

    public function bacaarea($username,$idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_spb,
                TO_CHAR(a.d_spb, 'dd-mm-yyyy') AS d_spb,
                g.e_product_groupname,
                TO_CHAR(a.d_approve1, 'dd-mm-yyyy') AS d_appsales,
                TO_CHAR(a.d_approve2, 'dd-mm-yyyy') AS d_appar,
                a.i_salesman,
                CASE WHEN SUBSTRING(a.i_customer, 3) = '000' THEN x.e_customer_name
                ELSE '(' || a.i_customer || ') ' || b.e_customer_name END AS customer,
                a.i_area,
                a.v_spb - a.v_spb_discounttotal AS v_spb,
                d.v_nota_netto,
                (a.v_spb - a.v_spb_discounttotal) - d.v_nota_netto AS pending,
                d.v_nota_netto /(a.v_spb - a.v_spb_discounttotal)* 100 AS persen,
                f_spb_cancel AS status,
                a.i_sj,
                TO_CHAR(d.d_sj, 'dd-mm-yyyy') AS d_sj,
                TO_CHAR(d_dkb, 'dd-mm-yyyy') AS d_dkb,
                d.i_nota,
                TO_CHAR(d.d_nota, 'dd-mm-yyyy') AS d_nota,
                to_char(d_sj_receive, 'dd-mm-yyyy') AS d_sj_receive,
                CASE WHEN f_spb_stockdaerah = 'true' THEN 'Ya' ELSE 'Tidak' END AS daerah,
                a.i_approve1,
                i_notapprove,
                a.i_approve2,
                a.i_store,
                f_spb_siapnotagudang,
                f_spb_op,
                f_spb_opclose,
                f_spb_siapnotasales,
                i_dkb,
                f_spb_stockdaerah,
                f_spb_cancel,
                a.i_spb_program,
                a.i_price_group,
                '$folder' AS folder,
                '$i_menu' AS i_menu,
                '$username' AS username,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                 a.i_customer,
                 '$iarea' AS xarea,
                 f_spb_consigment
            FROM
                tm_spb a
            LEFT JOIN tm_nota d ON
                (a.i_spb = d.i_spb
                AND a.i_area = d.i_area
                AND d.f_nota_cancel = 'f')
            LEFT JOIN tr_customer b ON
                (a.i_customer = b.i_customer
                AND a.i_area = b.i_area)
            LEFT JOIN tr_customer_tmp x ON
                (a.i_customer = x.i_customer
                AND a.i_spb = x.i_spb
                AND a.i_area = x.i_area
                AND x.i_customer LIKE '%000')
            LEFT JOIN tr_product_group g ON
                (g.i_product_group = a.i_product_group),
                tr_area c
            WHERE
                a.i_area = c.i_area
                $sql
                AND (a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy'))
                AND x.i_customer LIKE '%000'
            ORDER BY
                a.i_area,
                a.i_spb"
        , FALSE);

        $datatables->edit('status', function ($data) {
            $f_spb_cancel           = $data['status'];
            $i_approve1             = $data['i_approve1'];
            $i_approve2             = $data['i_approve2'];
            $i_notapprove           = $data['i_notapprove'];
            $i_store                = $data['i_store'];
            $f_spb_stockdaerah      = $data['f_spb_stockdaerah'];
            $f_spb_siapnotagudang   = $data['f_spb_siapnotagudang'];
            $f_spb_op               = $data['f_spb_op'];
            $f_spb_opclose          = $data['f_spb_opclose'];
            $f_spb_siapnotasales    = $data['f_spb_siapnotasales'];
            $i_nota                 = $data['i_nota'];
            $i_sj                   = $data['i_sj'];
            $i_dkb                  = $data['i_dkb'];
            $d_sj_receive           = $data['d_sj_receive'];
            if(($f_spb_cancel == 't')){
                $status='Batal';
            }elseif(($i_approve1 == null) && ($i_notapprove == null)){
                $status='Sales';
            }elseif(($i_approve1 == null) && ($i_notapprove != null)){
                $status='Reject (sls)';
            }elseif(($i_approve1 != null) && ($i_approve2 == null) & ($i_notapprove == null)){
                $status='Keuangan';
            }elseif(($i_approve1 != null) && ($i_approve2 == null) && ($i_notapprove != null)){
                $status='Reject (ar)';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store == null)){
                $status='Gudang';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 'f')){
                $status='Pemenuhan SPB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 'f') && ($f_spb_op == 't') && ($f_spb_opclose == 'f')){
                $status='Proses OP';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') &&
                ($f_spb_siapnotagudang == 'f') && ($f_spb_siapnotasales == 'f') && ($f_spb_opclose == 't')){
                $status='OP Close';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 'f')){
                $status='Siap SJ (sales)';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') &&
                ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                $status='Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && 
                ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj == null)){
                $status='Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb == null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                $status='Siap DKB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_dkb != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 'f') && ($f_spb_siapnotagudang == 't') && ($f_spb_siapnotasales == 't') && ($i_sj != null)){
                $status='Siap Nota';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($f_spb_stockdaerah == 't') && ($i_sj == null)){
                $status='Siap SJ';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb == null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                $status='Siap DKB';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota == null) && ($i_dkb != null) && ($f_spb_stockdaerah == 't') && ($i_sj != null)){
                $status='Siap Nota';
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota != null) && ($d_sj_receive != null)){
                $status='Sudah diterima';             
            }elseif(($i_approve1 != null) && ($i_approve2 != null) && ($i_store != null) && ($i_nota != null) && ($d_sj_receive != null)){
                $status='Sudah dinotakan';            
            }elseif(($i_nota != null)){
                $status='Sudah dinotakan';
            }else{
                $status='Unknown';      
            }
            return $status;
        });
        $datatables->add('action', function ($data) {
            $i_spb              = trim($data['i_spb']);
            $d_spb              = trim($data['d_spb']);
            $i_spb_program      = trim($data['i_spb_program']);
            $i_store            = trim($data['i_store']);
            $i_sj               = trim($data['i_sj']);
            $f_spb_cancel       = trim($data['f_spb_cancel']);
            $f_spb_op           = trim($data['f_spb_op']);
            $f_spb_stockdaerah  = trim($data['f_spb_stockdaerah']);
            $i_approve1         = trim($data['i_approve1']);
            $i_approve2         = trim($data['i_approve2']);
            $ipricegroup        = trim($data['i_price_group']);
            $f_spb_consigment   = $data['f_spb_consigment'];
            $i_area             = $data['i_area'];
            $xarea              = $data['xarea'];
            $i_menu             = $data['i_menu'];
            $folder             = $data['folder'];
            $username           = $data['username'];
            $dfrom              = $data['dfrom'];
            $dto                = $data['dto'];
            $icustomer          = trim($data['i_customer']);
            $data               = '';
            if(check_role($i_menu, 2)||check_role($i_menu, 3)){
                if($i_spb_program!=null){
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/editspbpromo/$i_spb/$i_area/$i_spb_program/$d_spb/$xarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                }else{
                    if(substr($icustomer,2,3)!='000' && $f_spb_consigment!='t'){
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/editspb/$i_spb/$i_area/$ipricegroup/$xarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                    }elseif($f_spb_consigment=='t'){
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/editspbmo/$i_spb/$i_area/$ipricegroup/$xarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                    }else{
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/editcustomernew/$i_spb/$i_area/$ipricegroup/$xarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
                    }
                }
                if(($i_store == null) && ($i_sj==null) && ($f_spb_cancel == 'f') && ($username=='spvpusat' || $username=='admin')){
                    $data .= "<a href=\"#\" onclick='balik(\"$i_spb\",\"$i_area\"); return false;'><i class='fa fa-undo'></i></a>&nbsp;&nbsp;";
                }
            }
            if(check_role($i_menu, 4)){
                if(($i_store == null) && ($i_approve1==null) && ($i_approve2==null) ){
                    if( ($f_spb_stockdaerah == 'f') && ($f_spb_op == 't') ){
                        if($i_area == '00'){
                            $data .= "<a href=\"#\" onclick='cancel(\"$i_spb\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                        }
                    }else{
                        if($f_spb_cancel == 'f'){
                            $data .= "<a href=\"#\" onclick='cancel(\"$i_spb\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                        }
                    }
                }elseif($i_sj==null){
                    $data .= "<a href=\"#\" onclick='cancel(\"$i_spb\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });
        $datatables->edit('persen', function ($data) {
            return number_format($data['persen'],2)." %";
        });
        $datatables->edit('v_spb', function ($data) {
            return number_format($data['v_spb'],2);
        });
        $datatables->edit('v_nota_netto', function ($data) {
            return number_format($data['v_nota_netto'],2);
        });
        $datatables->edit('pending', function ($data) {
            return number_format($data['pending'],2);
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('xarea');
        $datatables->hide('username');
        $datatables->hide('i_approve1');
        $datatables->hide('i_notapprove');
        $datatables->hide('i_approve2');
        $datatables->hide('i_store');
        $datatables->hide('f_spb_siapnotagudang');
        $datatables->hide('f_spb_op');
        $datatables->hide('f_spb_opclose');
        $datatables->hide('f_spb_siapnotasales');
        $datatables->hide('i_dkb');
        $datatables->hide('f_spb_stockdaerah');
        $datatables->hide('f_spb_cancel');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_price_group');
        $datatables->hide('i_spb_program');
        $datatables->hide('i_customer');
        $datatables->hide('f_spb_consigment');
        return $datatables->generate();
    }

    public function total($dfrom, $dto, $iarea){
        if ($iarea=='NA') {
            $sql = "";
        }else{
            $sql = "AND a.i_area = '$iarea'";
        }
        return $this->db->query("
            SELECT
                sum(a.v_spb - a.v_spb_discounttotal) AS nilaispb,
                sum(d.v_nota_netto) AS nilainota,
                sum((a.v_spb - a.v_spb_discounttotal) - d.v_nota_netto) AS nilaipending
            FROM
                tm_spb a
            LEFT JOIN tm_nota d ON
                (a.i_spb = d.i_spb
                AND a.i_area = d.i_area
                AND d.f_nota_cancel = 'f')
            LEFT JOIN tr_customer b ON
                (a.i_customer = b.i_customer
                AND a.i_area = b.i_area)
            LEFT JOIN tr_customer_tmp x ON
                (a.i_customer = x.i_customer
                AND a.i_spb = x.i_spb
                AND a.i_area = x.i_area
                AND x.i_customer LIKE '%000')
            LEFT JOIN tr_product_group g ON
                (g.i_product_group = a.i_product_group),
                tr_area c
            WHERE
                a.i_area = c.i_area
                AND f_spb_cancel = 'f' 
                $sql
                AND (a.d_spb >= TO_DATE('$dfrom', 'dd-mm-yyyy')
                AND a.d_spb <= TO_DATE('$dto', 'dd-mm-yyyy'))"
        , FALSE);
    }

    public function bacagroup(){
        $this->db->select('*');
        $this->db->from('tr_product_group');
        $this->db->where('f_spb', 'true');
        $this->db->order_by('e_product_groupname');
        $query = $this->db->get();
        if ($query->num_rows() > 0){          
            return $query->result();
        }
    }

     public function bacapromospb($username, $idcompany, $dspb){
        $dspb  = date('Y-m-d', strtotime($dspb));
        $query = $this->db->query("
            SELECT    
                *
            FROM
                (
                SELECT
                    DISTINCT(i_promo) AS tes,
                    *
                FROM
                    tm_promo
                WHERE
                    f_all_area = 't'
                    AND d_promo_start <= '$dspb'
                    AND d_promo_finish >= '$dspb'
                    AND f_all_reguler = 'f'
            UNION ALL
                SELECT
                    DISTINCT(a.i_promo) AS tes,
                    a.*
                FROM
                    tm_promo a
                INNER JOIN tm_promo_area b ON
                    (a.i_promo = b.i_promo
                    AND b.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany'))
                WHERE
                    a.f_all_area = 'f'
                    AND d_promo_start <= '$dspb'
                    AND d_promo_finish >= '$dspb'
                    AND f_all_reguler = 'f' ) AS x
            ORDER BY
                x.i_promo", FALSE);
        if ($query->num_rows() > 0){          
            return $query->result();
        }
    }

    public function bacaspbpromo($ispb,$iarea){
        $query = $this->db->query("
            SELECT
                a.e_remark1 AS emark1,
                a.*,
                e.e_price_groupname,
                f.e_promo_name,
                b.i_customer_group,
                d.e_area_name,
                b.e_customer_name,
                b.e_customer_address,
                c.e_salesman_name,
                b.f_customer_first
            FROM
                tm_spb a
            LEFT JOIN tm_promo f ON
                (a.i_spb_program = f.i_promo)
            INNER JOIN tr_customer b ON
                (a.i_customer = b.i_customer)
            INNER JOIN tr_salesman c ON
                (a.i_salesman = c.i_salesman)
            INNER JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            LEFT JOIN tr_price_group e ON
                (a.i_price_group = e.i_price_group)
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$iarea'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetailspbpromo($ispb,$iarea){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_product_motifname
            FROM
                tm_spb_item a,
                tr_product_motif b
            WHERE
                a.i_spb = '$ispb'
                AND i_area = '$iarea'
                AND a.i_product = b.i_product
                AND a.i_product_motif = b.i_product_motif
            ORDER BY
                a.n_item_no
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacapelanggan(){
        $this->db->select('*');
        $this->db->from('tr_customer');
        return $this->db->get();
    }

    public function bacadetailnilaispbpromo($ispb,$iarea){
        return $this->db->query(" select (sum(a.n_deliver * a.v_unit_price)) AS nilaispb from tm_spb_item a
                 where a.i_spb = '$ispb' and a.i_area='$iarea' ", false);
    }
    public function bacadetailnilaiorderspbpromo($ispb,$iarea){
        return $this->db->query(" select (sum(a.n_order * a.v_unit_price)) AS nilaiorderspb from tm_spb_item a
                 where a.i_spb = '$ispb' and a.i_area='$iarea' ", false);
    }

    public function getpromo($ipromo){
        $this->db->select('*');
        $this->db->from('tm_promo');
        $this->db->where('i_promo', $ipromo);
        return $this->db->get();
    }

    public function cariareapromo($ipromo, $a){
        $username = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        if($a=='f'){   
            $this->db->select(" 
                DISTINCT(a.i_area) AS i_area,
                    b.*
                FROM
                    tm_promo_area a,
                    tr_area b
                WHERE
                    a.i_promo = '$ipromo'
                    AND a.i_area = b.i_area
                    AND a.i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$id_company')
                ORDER BY
                    a.i_area", false
            );
        }else{
            $this->db->select("
                    *
                FROM
                    tr_area
                WHERE
                    i_area IN(
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$id_company') ",false);
        }
        return $query = $this->db->get();
    }

    public function getpelangganpromo($cari, $iarea, $promo, $c, $g, $type, $per){
        $cari = str_replace("'", "", $cari);
        if(($c=='f') && ($g=='f')){           
            $query = $this->db->query("
                SELECT DISTINCT
                    x.i_customer, x.e_customer_name
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customer y
                INNER JOIN tm_promo yy ON
                    (y.i_promo = yy.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND (UPPER(x.i_customer) LIKE '%$cari%'
                    OR UPPER(x.e_customer_name) LIKE '%$cari%')
                    AND x.i_customer = y.i_customer
                    AND x.i_area = '$iarea' "
            , false);              
        }else if(($c=='t') && ($g=='f')){           
            $query = $this->db->query("
                SELECT DISTINCT
                    x.i_customer, x.e_customer_name
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer)
                WHERE
                    x.i_area = '$iarea'
                    AND x.f_approve = 't'
                    AND (UPPER(x.i_customer) LIKE '%$cari%'
                    OR UPPER(x.e_customer_name) LIKE '%$cari%')"
            , false);       
        }else if(($c=='f') && ($g=='t')){           
            $query = $this->db->query("
                SELECT DISTINCT
                    x.i_customer, x.e_customer_name
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customergroup y
                INNER JOIN tm_promo p ON
                    (y.i_promo = p.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND (UPPER(x.i_customer) LIKE '%$cari%'
                    OR UPPER(x.e_customer_name) LIKE '%$cari%')
                    AND x.i_customer_group = y.i_customer_group
                    AND x.i_area = y.i_area
                    AND x.i_area = '$iarea' 
            ", false);       
        }
        return $query;   
    }

    public function getdetailpelpromo($icustomer, $iarea, $promo, $c, $g, $type, $per, $disc1, $disc2){
        if(($c=='f') && ($g=='f')){           
            $query = $this->db->query("
                SELECT
                    *, 
                    $type AS type,
                    $disc1 AS disc1,
                    $disc2 AS disc2
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customer y
                INNER JOIN tm_promo yy ON
                    (y.i_promo = yy.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND x.i_customer = y.i_customer
                    AND x.i_area = '$iarea' 
                    AND x.i_customer = '$icustomer'
            ", false);              
        }else if(($c=='t') && ($g=='f')){           
            $query = $this->db->query("
                SELECT
                    *, 
                    $type AS type,
                    $disc1 AS disc1,
                    $disc2 AS disc2
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer)
                WHERE
                    x.i_area = '$iarea'
                    AND x.f_approve = 't'
                    AND x.i_customer = '$icustomer'
            ", false);       
        }else if(($c=='f') && ($g=='t')){           
            $query = $this->db->query("
                SELECT
                    *, 
                    $type AS type,
                    $disc1 AS disc1,
                    $disc2 AS disc2
                FROM
                    tr_customer x
                LEFT JOIN tr_customer_pkp b ON
                    (x.i_customer = b.i_customer)
                LEFT JOIN tr_price_group c ON
                    (x.i_price_group = c.n_line
                    OR x.i_price_group = c.i_price_group)
                LEFT JOIN tr_customer_area d ON
                    (x.i_customer = d.i_customer)
                LEFT JOIN tr_customer_salesman e ON
                    (x.i_customer = e.i_customer
                    AND e.e_periode = '$per')
                LEFT JOIN tr_customer_discount f ON
                    (x.i_customer = f.i_customer) ,
                    tm_promo_customergroup y
                INNER JOIN tm_promo p ON
                    (y.i_promo = p.i_promo)
                WHERE
                    y.i_promo = '$promo'
                    AND x.f_approve = 't'
                    AND x.i_customer_group = y.i_customer_group
                    AND x.i_area = y.i_area
                    AND x.i_area = '$iarea'
                    AND x.i_customer = '$icustomer' 
            ", false);       
        }
        return $query; 
    }

    public function getsalespromo($iarea, $cari, $per){
        $cari = str_replace("'", "", $cari);
        return  $this->db->query("
            SELECT DISTINCT 
                a.i_salesman,
                a.e_salesman_name
            FROM
                tr_customer_salesman a,
                tr_salesman b
            WHERE
                (UPPER(a.e_salesman_name) LIKE '%$cari%'
                OR UPPER(a.i_salesman) LIKE '%$cari%')
                AND a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND b.f_salesman_aktif = 'true'
                AND a.e_periode = '$per' "
            );
    }

    public function getdetailsalpromo($isalesman, $per, $iarea){
        return  $this->db->query("
            SELECT DISTINCT 
                a.i_salesman,
                a.e_salesman_name
            FROM
                tr_customer_salesman a,
                tr_salesman b
            WHERE
                a.i_salesman = '$isalesman'
                AND a.i_area = '$iarea'
                AND a.i_salesman = b.i_salesman
                AND b.f_salesman_aktif = 'true'
                AND a.e_periode = '$per' "
            );
    }

    public function bacaproductpromo($cari,$kdharga,$groupbarang,$ipromo,$kdgroup) {
        $this->db->select("*");
        $this->db->from("tm_promo");
        $this->db->where("i_promo",$ipromo);
        $query = $this->db->get();      
        foreach($query->result() as $pro){         
            $p    = $pro->f_all_product;         
            $b    = $pro->f_all_baby;         
            $r    = $pro->f_all_reguler;         
            $tipe = $pro->i_promo_type;      
        }
        $cari = str_replace("'", "", $cari);      
        if($p=='f'){         
            if( ($tipe=='1') || ($tipe=='3')){            
                if($b=='t'){               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND (UPPER(c.i_product) LIKE '%$cari%'
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product 
                        " ,false);               
                    }else{
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND c.i_product_status = g.i_product_status
                                AND (UPPER(c.i_product) LIKE '%$cari%' 
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product" ,false);
                    }            
                }else{               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND (UPPER(c.i_product) LIKE '%$cari%' 
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }else{                            
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND c.i_product_status = g.i_product_status
                                AND e.i_product_group = f.i_product_group
                                AND (UPPER(c.i_product) LIKE '%$cari%' 
                                OR UPPER(c.e_product_name) LIKE '%$cari%')
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }            
                }         
            }else{            
                if($kdgroup!='G0031'){
                    $query = $this->db->query("                        
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND e.i_product_group = f.i_product_group
                            AND f.i_product_group = '$groupbarang'
                            AND a.i_product_motif = '00'
                            AND (UPPER(c.i_product) LIKE '%$cari%' 
                            OR UPPER(c.e_product_name) LIKE '%$cari%')
                            AND a.i_product = c.i_product
                            AND c.i_product_status = g.i_product_status
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }else{
                    $query = $this->db->query("
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND a.i_product_motif = '00'
                            AND e.i_product_group = f.i_product_group
                            AND c.i_product_status = g.i_product_status
                            AND (UPPER(c.i_product) LIKE '%$cari%' 
                            OR UPPER(c.e_product_name) LIKE '%$cari%')
                            AND a.i_product = c.i_product
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }         
            }      
        }else{         
            $query = $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.i_product_status,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga,
                    g.e_product_statusname,
                    '1' AS n_quantity_min
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c,
                    tr_product_status g
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND c.i_product_status = g.i_product_status
                    AND (UPPER(b.i_product) LIKE '%$cari%' 
                    OR UPPER(b.e_product_name) LIKE '%$cari%')
                    AND b.i_price_group = '$kdharga'
                ORDER BY
                    a.i_product
            ",false);
        }
        return $query;    
    }

    public function bacaproductxpromo($kdharga,$iproduct,$ipromo,$kdgroup,$groupbarang) {
        $this->db->select("*");
        $this->db->from("tm_promo");
        $this->db->where("i_promo",$ipromo);
        $query = $this->db->get();      
        foreach($query->result() as $pro){         
            $p    = $pro->f_all_product;         
            $b    = $pro->f_all_baby;         
            $r    = $pro->f_all_reguler;         
            $tipe = $pro->i_promo_type;      
        }     
        if($p=='f'){         
            if( ($tipe=='1') || ($tipe=='3')){            
                if($b=='t'){               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product 
                        " ,false);               
                    }else{
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                '1' AS n_quantity_min
                            FROM
                                tr_product_motif a,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                c.i_product = a.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND c.i_product_status = g.i_product_status
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                            ORDER BY
                                a.i_product" ,false);
                    }            
                }else{               
                    if($kdgroup!='G0031'){                  
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND e.i_product_group = f.i_product_group
                                AND f.i_product_group = '$groupbarang'
                                AND c.i_product_status = g.i_product_status
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }else{                            
                        $query = $this->db->query("
                            SELECT
                                a.i_product AS kode,
                                a.i_product_motif AS motif,
                                c.i_product_status,
                                c.e_product_name AS nama,
                                d.v_product_retail AS harga,
                                a.e_product_motifname AS namamotif,
                                g.e_product_statusname,
                                b.n_quantity_min
                            FROM
                                tr_product_motif a,
                                tm_promo_item b,
                                tr_product c,
                                tr_product_price d,
                                tr_product_type e,
                                tr_product_group f,
                                tr_product_status g
                            WHERE
                                b.i_product = a.i_product
                                AND b.i_product_motif = a.i_product_motif
                                AND a.i_product = c.i_product
                                AND a.i_product_motif = '00'
                                AND c.i_product_type = e.i_product_type
                                AND c.i_product_status = g.i_product_status
                                AND e.i_product_group = f.i_product_group
                                AND c.i_product = '$iproduct'
                                AND d.i_product = a.i_product
                                AND d.i_price_group = '$kdharga'
                                AND b.i_promo = '$ipromo'
                            ORDER BY
                                a.i_product
                        " ,false);               
                    }            
                }         
            }else{            
                if($kdgroup!='G0031'){
                    $query = $this->db->query("                        
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND e.i_product_group = f.i_product_group
                            AND f.i_product_group = '$groupbarang'
                            AND a.i_product_motif = '00'
                            AND c.i_product = '$iproduct'
                            AND a.i_product = c.i_product
                            AND c.i_product_status = g.i_product_status
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }else{
                    $query = $this->db->query("
                        SELECT
                            a.i_product AS kode,
                            a.i_product_motif AS motif,
                            a.e_product_motifname AS namamotif,
                            c.i_product_status,
                            c.e_product_name AS nama,
                            b.v_unit_price AS harga,
                            g.e_product_statusname,
                            b.n_quantity_min
                        FROM
                            tr_product_motif a,
                            tm_promo_item b,
                            tr_product c,
                            tr_product_type e,
                            tr_product_group f,
                            tr_product_status g
                        WHERE
                            b.i_product = a.i_product
                            AND b.i_product_motif = a.i_product_motif
                            AND c.i_product_type = e.i_product_type
                            AND a.i_product_motif = '00'
                            AND e.i_product_group = f.i_product_group
                            AND c.i_product_status = g.i_product_status
                            AND c.i_product = '$iproduct'
                            AND a.i_product = c.i_product
                            AND b.i_promo = '$ipromo'
                        ORDER BY
                            a.i_product
                    ",false);            
                }         
            }      
        }else{         
            $query = $this->db->query("
                SELECT
                    a.i_product AS kode,
                    a.i_product_motif AS motif,
                    a.e_product_motifname AS namamotif,
                    c.i_product_status,
                    c.e_product_name AS nama,
                    b.v_product_retail AS harga,
                    g.e_product_statusname,
                    '1' AS n_quantity_min
                FROM
                    tr_product_motif a,
                    tr_product_price b,
                    tr_product c,
                    tr_product_status g
                WHERE
                    b.i_product = a.i_product
                    AND a.i_product_motif = '00'
                    AND a.i_product = c.i_product
                    AND c.i_product_status = g.i_product_status
                    AND c.i_product = '$iproduct'
                    AND b.i_price_group = '$kdharga'
                ORDER BY
                    a.i_product
            ",false);
        }
        return $query;
    }
    
    public function updateheaderpromo($ispb, $iarea, $dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman,$ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid,$fspbsiapnota, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $nspbdiscount4,$vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscount4, $vspbdiscounttotal, $vspb,$fspbconsigment,$ispbold,$eremark1,$ispbprogram,$iproductgroup){
        $dspbupdate = current_datetime();
        $data = array( 
            'd_spb'                 => $dspb,
            'i_customer'            => $icustomer,
            'i_spb_po'              => $ispbpo,
            'n_spb_toplength'       => $nspbtoplength,
            'i_salesman'            => $isalesman,
            'i_price_group'         => $ipricegroup,
            'd_spb_receive'         => $dspb,
            'i_spb_program'         => $ispbprogram,
            'f_spb_op'              => $fspbop,
            'e_customer_pkpnpwp'    => $ecustomerpkpnpwp,
            'f_spb_pkp'             => $fspbpkp,
            'f_spb_plusppn'         => $fspbplusppn,
            'f_spb_plusdiscount'    => $fspbplusdiscount,
            'f_spb_stockdaerah'     => $fspbstockdaerah,
            'f_spb_program'         => $fspbprogram,
            'f_spb_valid'           => $fspbvalid,
            'f_spb_siapnotagudang'  => $fspbsiapnota,
            'f_spb_cancel'          => $fspbcancel,
            'n_spb_discount1'       => $nspbdiscount1,
            'n_spb_discount2'       => $nspbdiscount2,
            'n_spb_discount3'       => $nspbdiscount3,
            'n_spb_discount4'       => $nspbdiscount4,
            'v_spb_discount1'       => $vspbdiscount1,
            'v_spb_discount2'       => $vspbdiscount2,
            'v_spb_discount3'       => $vspbdiscount3,
            'v_spb_discount4'       => $vspbdiscount4,
            'v_spb_discounttotal'   => $vspbdiscounttotal,
            'v_spb'                 => $vspb,
            'f_spb_consigment'      => $fspbconsigment,
            'd_spb_update'          => $dspbupdate,
            'i_spb_old'             => $ispbold,
            'e_remark1'             => $eremark1,
            'i_product_group'       => $iproductgroup
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data);
    }

    public function deletedetail($ispb, $iarea, $iproduct, $iproductgrade, $iproductmotif){
        $this->db->query("DELETE FROM tm_spb_item WHERE i_spb='$ispb' and i_area='$iarea' 
            and i_product='$iproduct' and i_product_grade='$iproductgrade'
            and i_product_motif='$iproductmotif'");
    }

    public function insertdetail($ispb,$iarea,$iproduct,$iproductstatus,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i){
        if($eremark=='') {
            $eremark=null;
        }
        $this->db->set(
            array(
                'i_spb'             => $ispb,
                'i_area'            => $iarea,
                'i_product'         => $iproduct,
                'i_product_status'  => $iproductstatus,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_order'           => $norder,
                'n_deliver'         => $ndeliver,
                'v_unit_price'      => $vunitprice,
                'e_product_name'    => $eproductname,
                'e_remark'          => $eremark,
                'n_item_no'         => $i
            )
        );
        $this->db->insert('tm_spb_item');
    }

	public function bacapromo($ispb,$area){
        return $this->db->query(" 
                SELECT
                    *, 
                    tm_promo.e_promo_name
                FROM 
                    tm_spb 
                INNER JOIN 
                    tm_promo on (tm_spb.i_spb_program=tm_promo.i_promo)
                INNER JOIN 
                    tr_customer on (tm_spb.i_customer=tr_customer.i_customer)
                INNER JOIN 
                    tr_salesman on (tm_spb.i_salesman=tr_salesman.i_salesman)
                INNER JOIN 
                    tr_customer_area on (tm_spb.i_customer=tr_customer_area.i_customer)
                INNER JOIN 
                    tr_price_group on (tm_spb.i_price_group=tr_price_group.i_price_group)
                WHERE 
                    i_spb ='$ispb' 
                    AND tm_spb.i_area='$area'
        ");
    }

    public function bacadetailpromo($ispb,$area){
        return $this->db->query(" 
                SELECT
                    a.*, 
                    b.e_product_motifname 
                FROM 
                    tm_spb_item a, 
                    tr_product_motif b
                WHERE 
                    a.i_spb = '$ispb' 
                    AND i_area='$area'
                    AND a.i_product=b.i_product 
                    AND a.i_product_motif=b.i_product_motif
                ORDER BY a.i_product"
        );
    }

    public function bacacustomernew($ispb,$iarea){
		return $this->db->query("SELECT * FROM tr_customer_tmp a
                                    LEFT JOIN tr_city
                                    ON (a.i_city = tr_city.i_city and a.i_area = tr_city.i_area)
                                    LEFT JOIN tr_customer_group
                                    ON (a.i_customer_group = tr_customer_group.i_customer_group)
                                    LEFT JOIN tr_area
                                    ON (a.i_area = tr_area.i_area)
                                    LEFT JOIN tr_customer_status 
                                    ON (a.i_customer_status = tr_customer_status.i_customer_status)
                                    LEFT JOIN tr_customer_producttype
                                    ON (a.i_customer_producttype = tr_customer_producttype.i_customer_producttype)
                                    LEFT JOIN tr_customer_specialproduct
                                    ON (a.i_customer_specialproduct = tr_customer_specialproduct.i_customer_specialproduct)
                                    LEFT JOIN tr_customer_grade
                                    ON (a.i_customer_grade = tr_customer_grade.i_customer_grade)
                                    LEFT JOIN tr_customer_service
                                    ON (a.i_customer_service = tr_customer_service.i_customer_service)
                                    LEFT JOIN tr_customer_salestype
                                    ON (a.i_customer_salestype = tr_customer_salestype.i_customer_salestype)
                                    LEFT JOIN tr_customer_class 
                                    ON (a.i_customer_class=tr_customer_class.i_customer_class)
                                    LEFT JOIN tr_shop_status 
                                    ON (a.i_shop_status=tr_shop_status.i_shop_status)
                                    LEFT JOIN tr_marriage 
                                    ON (a.i_marriage=tr_marriage.i_marriage)
                                    LEFT JOIN tr_jeniskelamin 
                                    ON (a.i_jeniskelamin=tr_jeniskelamin.i_jeniskelamin)
                                    LEFT JOIN tr_religion 
                                    ON (a.i_religion=tr_religion.i_religion)
                                    LEFT JOIN tr_traversed 
                                    ON (a.i_traversed=tr_traversed.i_traversed)
                                    LEFT JOIN tr_paymentmethod 
                                    ON (a.i_paymentmethod=tr_paymentmethod.i_paymentmethod)
                                    LEFT JOIN tr_call 
                                    ON (a.i_call=tr_call.i_call)
                                    LEFT JOIN tr_customer_plugroup
                                    ON (a.i_customer_plugroup=tr_customer_plugroup.i_customer_plugroup)
                                    LEFT JOIN tr_price_group
                                    ON (a.i_price_group=tr_price_group.i_price_group)
                                    where a.i_spb = '$ispb' and a.i_area='$iarea'", false);
    }

    public function bacadetailcustomernew($ispb,$iarea,$ipricegroup){
        return $this->db->query("select a.i_spb,a.i_product,a.i_product_grade,a.i_product_motif,a.n_order,a.n_deliver,a.n_stock,
                                a.v_unit_price,a.e_product_name,a.i_op,a.i_area,a.e_remark as ket,a.n_item_no, 
                                b.e_product_motifname, c.v_product_retail as hrgnew, a.i_product_status, x.i_product_group
		                        from tm_spb_item a, tr_product_motif b, tr_product_price c, tm_spb x
						        where a.i_spb = '$ispb' and a.i_area='$iarea' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif 
		                        and a.i_product=c.i_product and c.i_price_group='$ipricegroup'
		                        and a.i_spb=x.i_spb and a.i_area=x.i_area
						        order by a.n_item_no", false);
    }

	public function bacaspb($ispb,$area){
        $this->db->select(" a.e_remark1 AS emark1, a.*, e.e_price_groupname,
            d.e_area_name, b.e_customer_name, b.e_customer_address, c.e_salesman_name, b.f_customer_first
            from tm_spb a
            inner join tr_customer b on (a.i_customer=b.i_customer)
            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
            inner join tr_customer_area d on (a.i_customer=d.i_customer)
            left join tr_price_group e on (a.i_price_group=e.i_price_group)
            where a.i_spb ='$ispb' and a.i_area='$area'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetailspb($ispb,$area,$ipricegroup){
        $this->db->select(" a.i_spb,a.i_product,a.i_product_grade,a.i_product_motif,a.n_order,a.n_deliver,a.n_stock,
            a.v_unit_price,a.e_product_name,a.i_op,a.i_area,a.e_remark as ket,a.n_item_no, b.e_product_motifname,
            c.v_product_retail as hrgnew, a.i_product_status
            from tm_spb_item a, tr_product_motif b, tr_product_price c
            where a.i_spb = '$ispb' and i_area='$area' and a.i_product=b.i_product and a.i_product_motif=b.i_product_motif
            and a.i_product=c.i_product and c.i_price_group='$ipricegroup'
            order by a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetailnilaispb($ispb,$area,$ipricegroup){
        return $this->db->query("
            SELECT
                (SUM(a.n_deliver * a.v_unit_price)) AS nilaispb
            FROM
                tm_spb_item a
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$area'
        ", false);
    }

    public function bacadetailnilaiorderspb($ispb,$area,$ipricegroup){
        return $this->db->query("
            SELECT
                (SUM(a.n_order * a.v_unit_price)) AS nilaiorderspb
            FROM
                tm_spb_item a
            WHERE
                a.i_spb = '$ispb'
                AND a.i_area = '$area'
        ", false);
    }

    public function bacaproductreguler($cari,$kdharga,$groupbarang) {
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
        SELECT
            a.i_product AS kode,
            c.e_product_name AS nama
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e
        WHERE
            d.i_product_type = c.i_product_type
            AND d.i_product_group = '$groupbarang'
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND c.i_product_status <> '4'
            AND b.i_price_group = '$kdharga'
            AND b.i_product_grade = 'A'
            AND (UPPER(a.i_product) LIKE '%$cari%'
            OR UPPER(c.e_product_name) LIKE '%$cari%') "
        );
    }

    public function bacaproducticreguler($cari,$kdharga,$istore,$groupbarang){
      $cari = str_replace("'", "", $cari);
      return $this->db->query(" 
        SELECT
          a.i_product AS kode,
          c.e_product_name AS nama
      FROM
          tr_product_motif a,
          tr_product_price b,
          tr_product c,
          tr_product_type d,
          tr_product_status e,
          tm_ic f
      WHERE
          d.i_product_type = c.i_product_type
          AND d.i_product_group = '$groupbarang'
          AND b.i_product = a.i_product
          AND a.i_product_motif = '00'
          AND a.i_product = c.i_product
          AND c.i_product_status = e.i_product_status
          AND b.i_price_group = '$kdharga'
          AND b.i_product_grade = 'A'
          AND a.i_product = f.i_product
          AND f.i_store = '$istore'
          AND f.f_product_active = 't'
          AND f.n_quantity_stock>0
          AND b.i_product_grade = f.i_product_grade
          AND (UPPER(a.i_product) LIKE '%$cari%'
          OR UPPER(c.e_product_name) LIKE '%$cari%')");
    }

    public function bacaproductxreguler($kdharga,$group,$iproduct) {
      return $this->db->query("
        SELECT
            a.i_product AS kode,
            a.i_product_motif AS motif,
            a.e_product_motifname AS namamotif,
            c.i_product_status,
            e.e_product_statusname,
            c.e_product_name AS nama,
            b.v_product_retail AS harga
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e
        WHERE
            d.i_product_type = c.i_product_type
            AND d.i_product_group = '$group'
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND c.i_product_status <> '4'
            AND b.i_price_group = '$kdharga'
            AND b.i_product_grade = 'A'
            AND c.i_product = '$iproduct' "
        );
    }

    public function bacaproducticxreguler($kdharga,$istore,$group,$iproduct){
      return $this->db->query(" 
        SELECT
            a.i_product AS kode,
            a.i_product_motif AS motif,
            a.e_product_motifname AS namamotif,
            c.i_product_status,
            e.e_product_statusname,
            c.e_product_name AS nama,
            b.v_product_retail AS harga
        FROM
            tr_product_motif a,
            tr_product_price b,
            tr_product c,
            tr_product_type d,
            tr_product_status e,
            tm_ic f
        WHERE
            d.i_product_type = c.i_product_type
            AND d.i_product_group = '$group'
            AND b.i_product = a.i_product
            AND a.i_product_motif = '00'
            AND a.i_product = c.i_product
            AND c.i_product_status = e.i_product_status
            AND b.i_price_group = '$kdharga'
            AND b.i_product_grade = 'A'
            AND a.i_product = f.i_product
            AND f.i_store = '$istore'
            AND f.f_product_active = 't'
            AND f.n_quantity_stock>0
            AND b.i_product_grade = f.i_product_grade
            AND c.i_product = '$iproduct' ");
    }

    public function bacaspbcustomernew($ispb,$iarea){
        $this->db->select("
                tm_spb.e_remark1 AS emark1,
                tm_spb.i_spb,
                tm_spb.i_customer,
                tm_spb.i_salesman,
                tm_spb.i_price_group,
                tm_spb.i_nota,
                tm_spb.i_spb_program,
                tm_spb.i_spb_po,
                tm_spb.i_sj,
                tm_spb.i_store,
                tm_spb.i_store_location,
                tm_spb.d_spb,
                tm_spb.d_nota,
                tm_spb.d_spb_entry,
                tm_spb.d_spb_update,
                tm_spb.d_sj,
                tm_spb.d_spb_delivery,
                tm_spb.d_spb_receive,
                tm_spb.d_spb_storereceive,
                tm_spb.d_spb_email,
                tm_spb.e_customer_pkpnpwp,
                tm_spb.f_spb_op,
                tm_spb.f_spb_pkp,
                tm_spb.f_spb_plusppn,
                tm_spb.f_spb_plusdiscount,
                tm_spb.f_spb_stockdaerah,
                tm_spb.f_spb_program,
                tm_spb.f_spb_consigment,
                tm_spb.f_spb_valid,
                tm_spb.f_spb_siapnotagudang,
                tm_spb.f_spb_cancel,
                tm_spb.n_spb_toplength,
                tm_spb.n_spb_discount1,
                tm_spb.n_spb_discount2,
                tm_spb.n_spb_discount3,
                tm_spb.v_spb_discount1,
                tm_spb.v_spb_discount2,
                tm_spb.v_spb_discount3,
                tm_spb.v_spb_discounttotal,
                tm_spb.v_spb_discounttotalafter,
                tm_spb.v_spb,
                tm_spb.v_spb_after,
                tm_spb.i_approve1,
                tm_spb.i_approve2,
                tm_spb.d_approve1,
                tm_spb.d_approve2,
                tm_spb.e_approve1,
                tm_spb.e_approve2,
                tm_spb.i_area,
                tm_spb.n_spb_discount4,
                tm_spb.v_spb_discount4,
                tm_spb.i_notapprove,
                tm_spb.d_notapprove,
                tm_spb.e_notapprove,
                tm_spb.f_spb_siapnotasales,
                tm_spb.i_spb_old,
                tm_spb.i_product_group,
                tm_spb.f_spb_opclose,
                tm_spb.f_spb_pemenuhan,
                tm_spb.n_print,
                tm_spb.i_cek,
                tm_spb.d_cek,
                tm_spb.e_cek
            FROM
                tm_spb
            LEFT JOIN tr_customer ON
                (tm_spb.i_customer = tr_customer.i_customer)
            LEFT JOIN tr_customer_tmp ON
                (tm_spb.i_spb = tr_customer_tmp.i_spb
                AND tm_spb.i_area = tr_customer_tmp.i_area)
            INNER JOIN tr_salesman ON
                (tm_spb.i_salesman = tr_salesman.i_salesman)
            LEFT JOIN tr_customer_area ON
                (tm_spb.i_customer = tr_customer_area.i_customer)
            INNER JOIN tr_price_group ON
                (tm_spb.i_price_group = tr_price_group.i_price_group)
            WHERE
                tm_spb.i_spb = '$ispb'
                AND tm_spb.i_area = '$iarea'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacaareax(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }
  
    public function bacaretensi(){
      return $this->db->order_by('i_retensi','ASC')->get('tr_retensi')->result();
    }

    public function bacashop(){
      return $this->db->order_by('i_shop_status','ASC')->get('tr_shop_status')->result();
    }

    public function bacastatus(){
      return $this->db->order_by('i_marriage','ASC')->get('tr_marriage')->result();
    }

    public function bacakelamin(){
      return $this->db->order_by('i_jeniskelamin','ASC')->get('tr_jeniskelamin')->result();
    }

    public function bacaagama(){
      return $this->db->order_by('i_religion','ASC')->get('tr_religion')->result();
    }

    public function bacatraversed(){
      return $this->db->order_by('i_traversed','ASC')->get('tr_traversed')->result();
    }

    public function bacaclass(){
      return $this->db->order_by('i_customer_class','ASC')->get('tr_customer_class')->result();
    }

    public function bacapayment(){
      return $this->db->order_by('i_paymentmethod','ASC')->get('tr_paymentmethod')->result();
    }

    public function bacacall(){
      return $this->db->order_by('i_call','ASC')->get('tr_call')->result();
    }

    public function bacacustomergroup(){
      return $this->db->order_by('i_customer_group','ASC')->get('tr_customer_group')->result();
    }

    public function bacaplugroup(){
      return $this->db->order_by('i_customer_plugroup','ASC')->get('tr_customer_plugroup')->result();
    }

    public function bacacustomertype(){
      return $this->db->order_by('i_customer_producttype','ASC')->get('tr_customer_producttype')->result();
    }

    public function bacacustomerstatus(){
      return $this->db->order_by('i_customer_status','ASC')->get('tr_customer_status')->result();
    }

    public function bacacustomergrade(){
      return $this->db->order_by('i_customer_grade','ASC')->get('tr_customer_grade')->result();
    }

    public function bacacustomerservice(){
      return $this->db->order_by('i_customer_service','ASC')->get('tr_customer_service')->result();
    }

    public function bacacustomersalestype(){
      return $this->db->order_by('i_customer_salestype','ASC')->get('tr_customer_salestype')->result();
    }

    public function bacapricegroup(){
      return $this->db->order_by('i_price_group','ASC')->get('tr_price_group')->result();
    }

    public function getkota($iarea,$cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_city,
                e_city_name
            FROM
                tr_city
            WHERE
                (UPPER(i_city) LIKE '%$cari%'
                OR UPPER(e_city_name) LIKE '%$cari%')
                AND i_area = '$iarea'
            ORDER BY
                i_city", 
        FALSE);
    }

    public function getsalesman($iarea,$cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                tr_salesman
            WHERE
                (UPPER(i_salesman) LIKE '%$cari%'
                OR UPPER(e_salesman_name) LIKE '%$cari%')
                AND f_salesman_aktif = 'true'
            ORDER BY
                i_salesman", 
        FALSE);
    }

    public function getcustomerspecialproduct($iproducttype) {
        $this->db->select("
                *
            FROM
                tr_customer_specialproduct
            WHERE
                i_customer_producttype = '$iproducttype'
            ORDER BY
                i_customer_specialproduct ",false);
        return $this->db->get();
    }

    public function bacaproduct($cari,$kdharga) {      
        $cari = str_replace("'", "", $cari);      
        return $this->db->query("
            SELECT
                DISTINCT a.i_product AS kode,
                c.e_product_name AS nama
            FROM
                tr_product_motif a,
                tr_product_price b,
                tr_product c,
                tr_product_type d,
                tr_product_status e
            WHERE
                b.i_product = a.i_product
                AND c.i_product_status = e.i_product_status
                AND a.i_product = c.i_product
                AND d.i_product_type = c.i_product_type
                AND b.i_price_group = '$kdharga'
                AND a.i_product_motif = '00'
                AND c.f_product_pricelist = 't'
                AND (UPPER(a.i_product) LIKE '%$cari%'
                OR UPPER(c.e_product_name) LIKE '%$cari%')", 
        FALSE);
    }

    public function bacaproductx($kdharga, $iproduct){
        return $this->db->query(" 
            SELECT
                DISTINCT a.i_product AS kode,
                a.i_product_motif AS motif,
                a.e_product_motifname AS namamotif,
                d.i_product_group,
                c.e_product_name AS nama,
                b.v_product_retail AS harga,
                c.i_product_status,
                e.e_product_statusname
            FROM
                tr_product_motif a,
                tr_product_price b,
                tr_product c,
                tr_product_type d,
                tr_product_status e
            WHERE
                b.i_product = a.i_product
                AND c.i_product_status = e.i_product_status
                AND a.i_product = c.i_product
                AND d.i_product_type = c.i_product_type
                AND b.i_price_group = '$kdharga'
                AND a.i_product_motif = '00'
                AND c.f_product_pricelist = 't'
                AND a.i_product = '$iproduct'",
        FALSE);
    }

    public function updateheaderreguler($ispb, $iarea, $dspb, $icustomer, $ispbpo, $nspbtoplength, $isalesman,$ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp,$fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid,$fspbsiapnotagudang, $fspbcancel, $nspbdiscount1,$nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2,$vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$ispbold,$eremark1){
        $dspbupdate = current_datetime();
        $data = array( 
            'd_spb'                  => $dspb,
            'i_customer'             => $icustomer,
            'i_spb_po'               => $ispbpo,
            'n_spb_toplength'        => $nspbtoplength,
            'i_salesman'             => $isalesman,
            'i_price_group'          => $ipricegroup,
            'd_spb_receive'          => $dspb,
            'f_spb_op'               => $fspbop,
            'e_customer_pkpnpwp'     => $ecustomerpkpnpwp,
            'f_spb_pkp'              => $fspbpkp,
            'f_spb_plusppn'          => $fspbplusppn,
            'f_spb_plusdiscount'     => $fspbplusdiscount,
            'f_spb_stockdaerah'      => $fspbstockdaerah,
            'f_spb_program'          => $fspbprogram,
            'f_spb_valid'            => $fspbvalid,
            'f_spb_siapnotagudang'   => $fspbsiapnotagudang,
            'f_spb_cancel'           => $fspbcancel,
            'n_spb_discount1'        => $nspbdiscount1,
            'n_spb_discount2'        => $nspbdiscount2,
            'n_spb_discount3'        => $nspbdiscount3,
            'v_spb_discount1'        => $vspbdiscount1,
            'v_spb_discount2'        => $vspbdiscount2,
            'v_spb_discount3'        => $vspbdiscount3,
            'v_spb_discounttotal'    => $vspbdiscounttotal,
            'v_spb'                  => $vspb,
            'f_spb_consigment'       => $fspbconsigment,
            'd_spb_update'           => $dspbupdate,
            'i_product_group'        => '01',
            'i_spb_old'              => $ispbold,
            'e_remark1'              => $eremark1
        );
        $this->db->where('i_spb', $ispb);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_spb', $data);
    }

    public function balik($ispb, $iarea){
        $this->db->set(
            array(
                'i_approve1'  => NULL,
                'i_approve2'  => NULL,
                'd_approve1'  => NULL,
                'd_approve2'  => NULL,
                'e_approve1'  => NULL,
                'e_approve2'  => NULL
            )
        );
        $this->db->where('i_spb',$ispb);
        $this->db->where('i_area',$iarea);
        return $this->db->update('tm_spb');
    }

    public function cancel($ispb, $iarea){
        $this->db->set(
            array(
                'f_spb_cancel'  => 't'
            )
        );
        $this->db->where('i_spb',$ispb);
        $this->db->where('i_area',$iarea);
        return $this->db->update('tm_spb');
    }

    public function deleteheaderspb($ispb,$iarea){
        $this->db->query(" 
            DELETE
            FROM
                tm_spb
            WHERE
                i_spb = '$ispb'
                AND i_area = '$iarea'
        ", false);
    }

    public function insertheadercustomernew($ispb, $dspb, $icustomer, $iarea, $ispbpo, $nspbtoplength, $isalesman, $ipricegroup, $dspbreceive, $fspbop, $ecustomerpkpnpwp, $fspbpkp, $fspbplusppn, $fspbplusdiscount, $fspbstockdaerah, $fspbprogram, $fspbvalid, $fspbsiapnotagudang, $fspbcancel, $nspbdiscount1, $nspbdiscount2, $nspbdiscount3, $vspbdiscount1, $vspbdiscount2, $vspbdiscount3, $vspbdiscounttotal, $vspb, $fspbconsigment,$ispbold,$eremarkx,$iproductgroup){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_spb'                 => $ispb,
                'd_spb'                 => $dspb,
                'i_customer'            => $icustomer,
                'i_area'                => $iarea,
                'i_spb_po'              => $ispbpo,
                'n_spb_toplength'       => $nspbtoplength,
                'i_salesman'            => $isalesman,
                'i_price_group'         => $ipricegroup,
                'd_spb_receive'         => $dspb,
                'f_spb_op'              => $fspbop,
                'e_customer_pkpnpwp'    => $ecustomerpkpnpwp,
                'f_spb_pkp'             => $fspbpkp,
                'f_spb_plusppn'         => $fspbplusppn,
                'f_spb_plusdiscount'    => $fspbplusdiscount,
                'f_spb_stockdaerah'     => $fspbstockdaerah,
                'f_spb_program'         => $fspbprogram,
                'f_spb_valid'           => $fspbvalid,
                'f_spb_siapnotagudang'  => $fspbsiapnotagudang,
                'f_spb_cancel'          => $fspbcancel,
                'n_spb_discount1'       => $nspbdiscount1,
                'n_spb_discount2'       => $nspbdiscount2,
                'n_spb_discount3'       => $nspbdiscount3,
                'v_spb_discount1'       => $vspbdiscount1,
                'v_spb_discount2'       => $vspbdiscount2,
                'v_spb_discount3'       => $vspbdiscount3,
                'v_spb_discounttotal'   => $vspbdiscounttotal,
                'v_spb'                 => $vspb,
                'f_spb_consigment'      => $fspbconsigment,
                'd_spb_entry'           => $dentry,
                'i_spb_old'             => $ispbold,
                'i_product_group'       => $iproductgroup,
                'e_remark1'             => $eremarkx
            )
        );        
        $this->db->insert('tm_spb');
    }

    public function deletedetailspb( $ispb,$iarea,$iproduct,$iproductgrade,$iproductmotif){
        $this->db->query("
            DELETE
            FROM
                tm_spb_item
            WHERE
                i_spb = '$ispb'
                AND i_area = '$iarea'
                AND i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '00'
        ", false);
    }

    public function insertdetailcustomernew($ispb,$iarea,$iproduct,$iproductgrade,$eproductname,$norder,$ndeliver,$vunitprice,$iproductmotif,$eremark,$i,$iproductstatus){
        if($eremark=='') {
            $eremark=null;
        }
        $this->db->set(
            array(
                'i_spb'             => $ispb,
                'i_area'            => $iarea,
                'i_product'         => $iproduct,
                'i_product_status'  => $iproductstatus,
                'i_product_grade'   => $iproductgrade,
                'i_product_motif'   => $iproductmotif,
                'n_order'           => $norder,
                'n_deliver'         => $ndeliver,
                'v_unit_price'      => $vunitprice,
                'e_product_name'    => $eproductname,
                'e_remark'          => $eremark,
                'n_item_no'         => $i
            )
        );
        $this->db->insert('tm_spb_item');
    }

    public function deleteheadercustomernew($ispb,$iarea){
        $this->db->query(" 
            DELETE
            FROM
                tr_customer_tmp
            WHERE
                i_spb = '$ispb'
                AND i_area = '$iarea'
        ", false);
    }

    public function insertcustomernew($ispb,$icustomer,$iarea,$isalesman,$esalesmanname,$dsurvey,$nvisitperiod,$fcustomernew,$ecustomername,$ecustomeraddress,$ecustomersign,$ecustomerphone,$ert1,$erw1,$epostal1,$ecustomerkelurahan1,$ecustomerkecamatan1,$ecustomerkota1,$ecustomerprovinsi1,$efax1,$ecustomermonth,$ecustomeryear,$ecustomerage,$eshopstatus,$ishopstatus,$nshopbroad,$ecustomerowner,$ecustomerownerttl,$emarriage,$imarriage,$ejeniskelamin,$ijeniskelamin,$ereligion,$ireligion,$ecustomerowneraddress,$ecustomerownerphone,$ecustomerownerhp,$ecustomerownerfax,$ecustomerownerpartner,$ecustomerownerpartnerttl,$ecustomerownerpartnerage,$ert2,$erw2,$epostal2,$ecustomerkelurahan2,$ecustomerkecamatan2,$ecustomerkota2,$ecustomerprovinsi2,$ecustomersendaddress,$ecustomersendphone,$etraversed,$itraversed,$fparkir,$fkuli,$eekspedisi1,$eekspedisi2,$ert3,$erw3,$epostal3,$ecustomerkota3,$ecustomerprovinsi3,$ecustomerpkpnpwp,$fspbpkp,$ecustomernpwpname,$ecustomernpwpaddress,$ecustomerclassname,$icustomerclass,$epaymentmethod,$ipaymentmethod,$ecustomerbank1,$ecustomerbankaccount1,$ecustomerbankname1,$ecustomerbank2,$ecustomerbankaccount2,$ecustomerbankname2,$ekompetitor1,$ekompetitor2,$ekompetitor3,$nspbtoplength,$ncustomerdiscount,$epricegroupname,$ipricegroup,$nline,$fkontrabon,$ecall,$icall,$ekontrabonhari,$ekontrabonjam1,$ekontrabonjam2,$etagihhari,$etagihjam1,$etagihjam2,$icustomergroup,$icustomerplugroup,$icustomerproducttype,$icustomerspecialproduct,$icustomerstatus,$icustomergrade,$icustomerservice,$icustomersalestype,$ecustomerownerage,$ecustomerrefference,$iretensi,$icity,$ecustomercontact,$ecustomercontactgrade,$ecustomermail,$inik){
        $dentry = current_datetime();
        $ecustomername = str_replace("'","''",$ecustomername);
        if($nshopbroad==''){
            $nshopbroad=0;
        }
        $this->db->query("
            INSERT
                INTO
                tr_customer_tmp
            VALUES ('$ispb',
            '$icustomer',
            '$iarea',
            '$isalesman',
            '$ipricegroup',
            '$icustomerclass',
            '$icustomerplugroup',
            '$icustomergroup',
            '$icustomerstatus',
            '$icustomerproducttype',
            '$icustomerspecialproduct',
            '$icustomergrade',
            '$icustomerservice',
            '$icustomersalestype',
            '$icity',
            '$ishopstatus',
            '$imarriage',
            '$ijeniskelamin',
            '$ireligion',
            '$itraversed',
            '$ipaymentmethod',
            '$icall',
            '$esalesmanname',
            '$dsurvey',
            $nvisitperiod,
            '$fcustomernew',
            '$ecustomername',
            '$ecustomeraddress',
            '$ecustomersign',
            '$ecustomerphone',
            '$ert1',
            '$erw1',
            '$epostal1',
            '$ecustomerkelurahan1',
            '$ecustomerkecamatan1',
            '$ecustomerkota1',
            '$ecustomerprovinsi1',
            '$efax1',
            '$ecustomermonth',
            '$ecustomeryear',
            '$ecustomerage',
            '$nshopbroad',
            '$ecustomerowner',
            '$ecustomerownerttl',
            '$ecustomerowneraddress',
            '$ecustomerownerphone',
            '$ecustomerownerhp',
            '$ecustomerownerfax',
            '$ecustomerownerpartner',
            '$ecustomerownerpartnerttl',
            '$ecustomerownerpartnerage',
            '$ert2',
            '$erw2',
            '$epostal2',
            '$ecustomerkelurahan2',
            '$ecustomerkecamatan2',
            '$ecustomerkota2',
            '$ecustomerprovinsi2',
            '$ecustomersendaddress',
            '$ecustomersendphone',
            '$fparkir',
            '$fkuli',
            '$eekspedisi1',
            '$eekspedisi2',
            '$ert3',
            '$erw3',
            '$epostal3',
            '$ecustomerkota3',
            '$ecustomerprovinsi3',
            '$ecustomerpkpnpwp',
            '$fspbpkp',
            '$ecustomernpwpname',
            '$ecustomernpwpaddress',
            '$ecustomerbank1',
            '$ecustomerbankaccount1',
            '$ecustomerbankname1',
            '$ecustomerbank2',
            '$ecustomerbankaccount2',
            '$ecustomerbankname2',
            '$ekompetitor1',
            '$ekompetitor2',
            '$ekompetitor3',
            $nspbtoplength,
            '$ncustomerdiscount',
            '$fkontrabon',
            '$ekontrabonhari',
            '$ekontrabonjam1',
            '$ekontrabonjam2',
            '$etagihhari',
            '$etagihjam1',
            '$etagihjam2',
            '$dentry',
            '$ecustomerownerage',
            'f',
            NULL,
            NULL,
            NULL,
            '$ecustomercontact',
            '$ecustomercontactgrade',
            '$ecustomersendphone',
            '$ecustomermail',
            '$ecustomerrefference',
            'f',
            't',
            NULL,
            NULL,
            NULL,
            '$iretensi',
            '$inik')
        ");
    }
}

/* End of file Mmaster.php */
