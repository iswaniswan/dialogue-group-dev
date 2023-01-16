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
    public function data($dfrom, $dto, $iarea, $folder, $iperiode, $title){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT	a.i_kn, a.d_kn, a.i_kn_type, a.i_refference, a.i_area, 
            '('||a.i_customer||') '||b.e_customer_name as customer, a.v_gross, a.v_discount, a.v_netto, 
            a.v_sisa, '$folder' AS folder, '$dfrom' AS dfrom, '$dto' AS dto, '$iperiode' AS iperiode, 
            '$title' AS title
            from tm_kn a, tr_customer b
            where a.i_customer=b.i_customer 
            and a.f_kn_cancel='f'
            and not a.i_kn isnull
            and a.i_area='$iarea' and
            a.d_kn >= '$dfrom' AND
            a.d_kn <= '$dto'
            ORDER BY a.i_kn
            ",false);
		$datatables->edit('v_gross', function($data){
            return number_format($data['v_gross']);
        });
        
        $datatables->edit('v_discount', function($data){
            return number_format($data['v_discount']);
        });

        $datatables->edit('v_netto', function($data){
            return number_format($data['v_netto']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->add('action', function ($data) {
            $ikn      = $data['i_kn'];
            $folder     = $data['folder'];
            $iarea      = $data['i_area'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $iperiode   = $data['iperiode'];
            $title      = $data['title'];
            $data       = '';
            $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail KN\" onclick='window.open(\"$folder/cform/detail/$ikn/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('iperiode');
        return $datatables->generate();
    }

    public function total($dfrom, $dto, $iarea){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        return $this->db->query("
        SELECT	sum(a.v_sisa) as jml
                from tm_kn a, tr_area b
                where
                a.i_area=b.i_area and a.i_area='$iarea' and
                a.d_kn >= '$dfrom' AND
                a.d_kn <= '$dto'
                and a.f_kn_cancel='f'
                and not a.i_kn isnull
        ", false);
    }

    public function bacadetail($kn,$iarea){
        $this->db->select("  
        c.i_kn, c.d_kn, c.v_netto, c.i_alokasi, c.d_alokasi, c.v_jumlah, c.i_nota, c.d_nota from ( 
        select a.i_kn, a.d_kn, a.v_netto, '' as i_alokasi, null as d_alokasi, null as v_jumlah, '' as i_nota, null as d_nota from dgu.tm_kn a 
        where a.i_kn='$kn' and a.i_area='$iarea' 
        union all 
        select a.i_kn, a.d_kn, a.v_netto, b.i_pelunasan as i_alokasi, b.d_bukti as d_alokasi, c.v_jumlah, c.i_nota, c.d_nota from dgu.tm_kn a 
        inner join dgu.tm_pelunasan b on (a.i_area=b.i_area and a.i_kn=b.i_giro and a.i_customer=b.i_customer and b.f_pelunasan_cancel='f') 
        inner join dgu.tm_pelunasan_item c on (b.i_pelunasan=c.i_pelunasan and a.i_area=c.i_area and b.i_area=c.i_area) 
        where a.i_kn='$kn' and a.i_area='$iarea' 
        union all 
        select a.i_kn, a.d_kn, a.v_netto, f.i_alokasi, f.d_alokasi, e.v_jumlah, e.i_nota, e.d_nota from dgu.tm_kn a 
        inner join dgu.tm_alokasikn f on(f.f_alokasi_cancel='f' and a.i_area=f.i_area and a.i_kn=f.i_kn) 
        inner join dgu.tm_alokasikn_item e on(e.i_kn=a.i_kn and e.i_area=f.i_area and e.i_alokasi=f.i_alokasi) 
        where a.i_kn='$kn' and a.i_area='$iarea' 
        union all 
        select a.i_kn, a.d_kn, a.v_netto, f.i_alokasi, f.d_alokasi, e.v_jumlah, e.i_nota, e.d_nota from dgu.tm_kn a 
        inner join dgu.tm_alokasiknr f on(f.f_alokasi_cancel='f' and a.i_area=f.i_area and a.i_kn=f.i_kn) 
        inner join dgu.tm_alokasiknr_item e on(e.i_kn=a.i_kn and e.i_area=f.i_area and e.i_alokasi=f.i_alokasi) 
        where a.i_kn='$kn' and a.i_area='$iarea' 
        )as c 
        order by c.d_kn, c.i_kn", false);
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
