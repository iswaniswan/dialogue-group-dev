<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area where f_area_real='t' order by i_area
        ", FALSE)->result();
    }

    public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
    } 

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_supplier');
        $this->db->where('username',$username);
        $this->db->where('i_supplier','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $supplier = '00';
        }else{
            $supplier = 'xx';
        }
        return $supplier;
    }

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($notajt, $dopname, $iarea, $folder, $iperiode, $title){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dopname);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dopname	= $yir."-".$mon."-".$det;
        $datatables = new Datatables(new CodeigniterAdapter);
        if($notajt=='1'){
          $datatables->query("
              SELECT	'('||a.i_customer||') '||a.e_customer_name as customer, '('||a.i_salesman||') '|| a.e_salesman_name as sales,  
	                          a.i_customer, a.i_salesman, a.n_customer_toplength, a.i_area, a.v_nota_netto, a.v_sisa,
              '$folder' AS folder, '$notajt' AS notajt,
              '$dopname' AS dopname, '$iperiode' AS iperiode, '$title' AS title
              from (
	                          select distinct on (a.i_customer) a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, 
	                          b.n_customer_toplength, sum(a.v_nota_netto) as v_nota_netto, sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_nota <= '$dopname' and a.i_area='$iarea' and a.f_nota_cancel='f' and a.v_sisa>0
                            group by a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, b.n_customer_toplength
                            union all
                            select distinct on (a.i_customer) a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, 
                            b.n_customer_toplength, sum(a.v_nota_netto), sum(a.v_sisa) 
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_customer_consigment d on (a.i_customer=d.i_customer and d.i_area_real='$iarea')
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_nota <= '$dopname' and a.f_nota_cancel='f' and a.v_sisa>0
                            group by a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, b.n_customer_toplength
                            ) as a ",false);
        }else{
          $datatables->query("
              SELECT	'('||a.i_customer||') '||a.e_customer_name as customer, '('||a.i_salesman||') '|| a.e_salesman_name as sales,  
	                          a.i_customer, a.i_salesman, a.n_customer_toplength, a.i_area, a.v_nota_netto, a.v_sisa,
              '$folder' AS folder, '$notajt' AS notajt,
              '$dopname' AS dopname, '$iperiode' AS iperiode, '$title' AS title
              from (
	                          select distinct(a.i_customer) a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, 
	                          b.n_customer_toplength, sum(a.v_nota_netto) as v_nota_netto, sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_jatuh_tempo <= '$dopname' and a.i_area='$iarea' and a.f_nota_cancel='f' and a.v_sisa>0
                            group by a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, b.n_customer_toplength
                            union all
                            select distinct(a.i_customer) a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, 
                            b.n_customer_toplength, sum(a.v_nota_netto) as v_nota_netto, sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_customer_consigment d on (a.i_customer=d.i_customer and d.i_area_real='$iarea')
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_jatuh_tempo <= '$dopname' and a.f_nota_cancel='f' and a.v_sisa>0
                            group by a.i_customer, a.i_area, a.i_salesman, c.e_salesman_name, b.e_customer_name, b.n_customer_toplength
                            ) as a ",false);
        }
        $datatables->edit('v_nota_netto', function($data){
            return number_format($data['v_nota_netto']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });
        $datatables->add('action', function ($data) {
            $icustomer  = $data['i_customer'];
            $isalesman  = $data['i_salesman'];
            $folder     = $data['folder'];
            $notajt     = $data['notajt'];
            $dopname    = $data['dopname'];
            $iperiode   = $data['iperiode'];
            $title      = $data['title'];
            $data       = '';
            $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$icustomer/$isalesman/$dopname/$notajt\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('notajt');
        $datatables->hide('dopname');
        $datatables->hide('iperiode');
        $datatables->hide('i_customer');
        $datatables->hide('i_salesman');
        return $datatables->generate();
    }

    public function total($notajt, $dopname, $iarea){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dopname);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dopname	= $yir."-".$mon."-".$det;
        if($notajt=='1'){
          return $this->db->query("
              select sum(a.v_sisa) as jml
              from (
	                          select sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_nota <= '$dopname' and a.i_area='$iarea' and a.f_nota_cancel='f' and a.v_sisa>0
                            union all
                            select sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_customer_consigment d on (a.i_customer=d.i_customer and d.i_area_real='$iarea')
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_nota <= '$dopname' and a.f_nota_cancel='f' and a.v_sisa>0
                            ) as a ",false);
        }else{
          return $this->db->query("
              select sum(a.v_sisa) as jml
              from (
	                          select sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_jatuh_tempo <= '$dopname' and a.i_area='$iarea' and a.f_nota_cancel='f' and a.v_sisa>0
                            union all
                            select sum(a.v_sisa) as v_sisa
                            from tm_nota a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_customer_consigment d on (a.i_customer=d.i_customer and d.i_area_real='$iarea')
                            inner join tr_salesman c on (a.i_salesman=c.i_salesman)
                            where a.d_jatuh_tempo <= '$dopname' and a.f_nota_cancel='f' and a.v_sisa>0
                            ) as a ",false);
        }

    }

    public function bacadetail($icustomer,$isalesman,$dopname){
        $this->db->select("  
        i_customer, i_salesman, i_nota, d_nota, d_jatuh_tempo, v_nota_netto, v_sisa
                        from tm_nota
                        where i_customer='$icustomer' and i_salesman='$isalesman'
                        and v_sisa>0 and f_nota_cancel='f' and d_nota<='$dopname'
                        order by i_nota", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function cekedit($ialokasi,$ikn,$isupplier){
        $this->db->select('i_alokasi');
        $this->db->from('tm_alokasikn');
        $this->db->where('i_alokasi', $ikn);
        $this->db->where('i_supplier', $isupplier);
        $this->db->where('i_kn', $ikn);
        $this->db->where('f_alokasi_cancel', 'f');
        return $this->db->get();
    }

    public function getnota($cari, $isupplier){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_nota,
                b.e_customer_name
            FROM
                tm_nota a,
                tr_customer b,
                tr_customer_groupbayar c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = b.i_customer
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_cancel = 'f'
                AND a.v_sisa>0
                AND NOT (a.i_nota ISNULL
                OR TRIM(a.i_nota)= '')
                AND ( (c.i_customer_groupbayar IN(
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    SUBSTRING(i_customer, 1, 2)= '$isupplier')) )
                AND (UPPER(a.i_nota) LIKE '%$cari%'
                OR a.i_nota_old LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%')
            GROUP BY
                a.i_nota,
                a.i_supplier,
                a.d_nota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                b.e_customer_city
            ORDER BY
                a.i_customer,
                a.i_nota", 
        FALSE);
    }

    public function getdetailnota($inota,$isupplier){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_supplier,
                a.d_nota,
                to_char(a.d_nota, 'dd-mm-yyyy') AS dnota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') AS djtp,
                b.e_customer_city
            FROM
                tm_nota a,
                tr_customer b,
                tr_customer_groupbayar c
            WHERE
                a.i_customer = c.i_customer
                AND a.i_customer = b.i_customer
                AND a.f_ttb_tolak = 'f'
                AND a.f_nota_cancel = 'f'
                AND a.v_sisa>0
                AND NOT (a.i_nota ISNULL
                OR TRIM(a.i_nota)= '')
                AND ( (c.i_customer_groupbayar IN(
                SELECT
                    i_customer_groupbayar
                FROM
                    tr_customer_groupbayar
                WHERE
                    SUBSTRING(i_customer, 1, 2)= '$isupplier')) )
                AND a.i_nota = '$inota'
            GROUP BY
                a.i_nota,
                a.i_supplier,
                a.d_nota,
                a.i_customer,
                b.e_customer_name,
                a.v_nota_netto,
                a.v_sisa,
                a.d_jatuh_tempo,
                b.e_customer_city
            ORDER BY
                a.i_customer,
                a.i_nota", 
        FALSE);
    }

    public function deletedetail($idt,$ddt,$isupplier,$vjumlah,$xddt){
        $this->db->set(
            array(
                'v_jumlah'  => $vjumlah
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        $this->db->where('i_supplier',$isupplier);
        $this->db->update('tm_dt');

        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        /*$this->db->where('i_nota',$inota);*/
        $this->db->where('i_supplier',$isupplier);
        $this->db->delete('tm_dt_item');
    }

    public function updateheader($idt,$isupplier,$ddt,$vjumlah,$fsisa){
        $this->db->where('i_dt',$idt);
        $this->db->where('i_supplier',$isupplier);
        $this->db->delete('tm_dt');
        
        $this->db->set(
            array(
                'i_dt'      => $idt,
                'i_supplier'    => $isupplier,
                'd_dt'      => $ddt,
                'v_jumlah'  => $vjumlah,
                'f_sisa'    => $fsisa
            )
        );
        $this->db->insert('tm_dt');
    }

    public function insertdetail($idt,$ddt,$inota,$isupplier,$dnota,$icustomer,$vsisa,$vjumlah,$i){
        $this->db->set(
            array(
                'i_dt'       => $idt,
                'd_dt'       => $ddt,
                'i_nota'     => $inota,
                'i_supplier'     => $isupplier,
                'd_nota'     => $dnota,
                'i_customer' => $icustomer,
                'v_sisa'     => $vsisa,
                'v_jumlah'   => $vjumlah,
                'n_item_no'  => $i
            )
        );
        $this->db->insert('tm_dt_item');
    }

    public function cancel($idt, $isupplier){
        $this->db->set(
            array(
                'f_dt_cancel'  => 't'
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('i_supplier',$isupplier);
        return $this->db->update('tm_dt');
    }
}

/* End of file Mmaster.php */
