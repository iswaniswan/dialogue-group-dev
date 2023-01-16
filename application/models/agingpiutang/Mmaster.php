<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username, $idcompany){
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
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('i_area','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $area = '00';
        }else{
            $area = 'xx';
        }
        return $area;
    }

//    public function data($dfrom, $dto, $iarea, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $folder, $title){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfromy	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $djtx	= $yir."/".$mon."/".$det;
        $toptujuh=-7;
        $toplapan=-30;
        $dudet	= $this->fungsi->dateAdd("d",0,$djtx);
        $djatuhtempo=$dudet;
        $dudettujuh	= $this->fungsi->dateAdd("d",$toptujuh,$djtx);
        $dudetlapan	= $this->fungsi->dateAdd("d",$toplapan,$djtx);
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
        select a.d_nota, a.i_nota, a.e_customer_name, a.e_salesman_name, a.d_jth, a.blmjt, a.tujuh, a.tiga, a.lebih from (
            select a.d_nota, a.i_nota, a.e_customer_name ||'('||a.i_customer||')' as e_customer_name, a.e_salesman_name ||'('||a.i_salesman||')' as e_salesman_name, 
            a.d_jth, sum(a.blmjt) as blmjt, sum(a.tujuh) as tujuh, sum(a.tiga) as tiga, sum(a.lebih) as lebih 
            from(
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name, a.i_salesman, c.e_salesman_name,
            a.d_jatuh_tempo as d_jth, a.v_sisa as blmjt, 0 as tujuh, 0 as tiga, 0 as lebih
            from tm_nota a, tr_customer b, tr_salesman c
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f' and a.i_salesman=c.i_salesman
            and a.d_nota>='$dfromy' and a.d_jatuh_tempo>'$djatuhtempo'
            union all
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name, a.i_salesman, c.e_salesman_name,
            a.d_jatuh_tempo as d_jth, 0 as blmjt, a.v_sisa as tujuh, 0 as tiga, 0 as lebih
            from tm_nota a, tr_customer b, tr_salesman c
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f' and a.i_salesman=c.i_salesman
            and a.d_nota>='$dfromy' and d_jatuh_tempo < '$djatuhtempo' and d_jatuh_tempo >= '$dudettujuh'
            union all
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name, a.i_salesman, c.e_salesman_name,
            a.d_jatuh_tempo as d_jth, 0 as blmjt, 0 as tujuh, a.v_sisa as tiga, 0 as lebih
            from tm_nota a, tr_customer b, tr_salesman c
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f' and a.i_salesman=c.i_salesman
            and a.d_nota>='$dfromy' and d_jatuh_tempo < '$dudettujuh' and d_jatuh_tempo >= '$dudetlapan'
            union all
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name, a.i_salesman, c.e_salesman_name,
            a.d_jatuh_tempo as d_jth, 0 as blmjt, 0 as tujuh, 0 as tiga, a.v_sisa as lebih
            from tm_nota a, tr_customer b, tr_salesman c
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f' and a.i_salesman=c.i_salesman
            and a.d_nota>='$dfromy' and d_jatuh_tempo <= '$dudetlapan'
            ) as a
            group by a.d_nota, a.i_nota, a.i_customer, a.e_customer_name, a.i_salesman, a.e_salesman_name, a.d_jth
            ) as a
            ORDER BY a.d_nota, a.i_nota, a.e_customer_name, a.e_salesman_name, a.d_jth
            ", false);
        $datatables->edit('blmjt', function($data){
            return number_format($data['blmjt']);
        });
        $datatables->edit('tujuh', function($data){
            return number_format($data['tujuh']);
        });
        $datatables->edit('tiga', function($data){
            return number_format($data['tiga']);
        });
        $datatables->edit('lebih', function($data){
            return number_format($data['lebih']);
        });
        return $datatables->generate();
    }

    public function total($dfrom, $dto){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfromy	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $djtx	= $yir."/".$mon."/".$det;
        $toptujuh=-7;
        $toplapan=-30;
        $dudet	= $this->fungsi->dateAdd("d",0,$djtx);
        $djatuhtempo=$dudet;
        $dudettujuh	= $this->fungsi->dateAdd("d",$toptujuh,$djtx);
        $dudetlapan	= $this->fungsi->dateAdd("d",$toplapan,$djtx);
        return $this->db->query("
            select sum(a.blmjt) as blmjt, sum(a.tujuh) as tujuh, sum(a.tiga) as tiga, sum(a.lebih) as lebih 
            from(
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name,
            a.d_jatuh_tempo as d_jth, a.v_sisa as blmjt, 0 as tujuh, 0 as tiga, 0 as lebih
            from tm_nota a, tr_customer b
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f'
            and a.d_nota>='$dfromy' and a.d_jatuh_tempo>'$djatuhtempo'
            union all
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name,
            a.d_jatuh_tempo as d_jth, 0 as blmjt, a.v_sisa as tujuh, 0 as tiga, 0 as lebih
            from tm_nota a, tr_customer b
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f'
            and a.d_nota>='$dfromy' and d_jatuh_tempo < '$djatuhtempo' and d_jatuh_tempo >= '$dudettujuh'
            union all
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name,
            a.d_jatuh_tempo as d_jth, 0 as blmjt, 0 as tujuh, a.v_sisa as tiga, 0 as lebih
            from tm_nota a, tr_customer b
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f'
            and a.d_nota>='$dfromy' and d_jatuh_tempo < '$dudettujuh' and d_jatuh_tempo >= '$dudetlapan'
            union all
            select a.d_nota, a.i_nota, a.i_customer, b.e_customer_name,
            a.d_jatuh_tempo as d_jth, 0 as blmjt, 0 as tujuh, 0 as tiga, a.v_sisa as lebih
            from tm_nota a, tr_customer b
            where a.i_customer=b.i_customer and not a.i_nota isnull and a.v_sisa>0 and a.f_nota_cancel='f'
            and a.d_nota>='$dfromy' and d_jatuh_tempo <= '$dudetlapan'
            ) as a
            ", false);
    }

    public function cekedit($idt, $tgl, $iarea){
        $this->db->select('i_alokasi');
        $this->db->from('tm_alokasi');
        $this->db->where('i_dt', $idt);
        $this->db->where('i_area_dt', $iarea);
        /*$this->db->where('d_dt', $tgl);*/
        $this->db->where('f_alokasi_cancel', 'f');
        return $this->db->get();
    }

    public function baca($idt,$iarea,$tgl){
        $this->db->select("
                *,
                to_char(d_dt, 'dd-mm-yyyy') AS ddt,
                to_char(d_dt, 'yyyymm') AS periodedt
            FROM
                tm_dt
            INNER JOIN tr_area ON
                (tm_dt.i_area = tr_area.i_area)
            WHERE
                tm_dt.i_dt = '$idt'
                AND tm_dt.i_area = '$iarea'
                AND tm_dt.d_dt = '$tgl'"
        , false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($idt,$iarea,$tgl){
        $this->db->select("
                a.*,
                b.v_sisa,
                b.d_jatuh_tempo,
                c.e_customer_name,
                c.e_customer_city
            FROM
                tm_dt_item a
            INNER JOIN tm_nota b ON
                (b.i_nota = a.i_nota)
            INNER JOIN tr_customer c ON
                (b.i_customer = c.i_customer)
            INNER JOIN tr_customer_groupbayar d ON
                (d.i_customer = c.i_customer)
            WHERE
                a.i_dt = '$idt'
                AND a.i_area = '$iarea'
                AND a.d_dt = '$tgl'
            ORDER BY
                a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function getnota($cari, $iarea){
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
                    SUBSTRING(i_customer, 1, 2)= '$iarea')) )
                AND (UPPER(a.i_nota) LIKE '%$cari%'
                OR a.i_nota_old LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%')
            GROUP BY
                a.i_nota,
                a.i_area,
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

    public function getdetailnota($inota,$iarea){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_area,
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
                    SUBSTRING(i_customer, 1, 2)= '$iarea')) )
                AND a.i_nota = '$inota'
            GROUP BY
                a.i_nota,
                a.i_area,
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

    public function deletedetail($idt,$ddt,$iarea,$vjumlah,$xddt){
        $this->db->set(
            array(
                'v_jumlah'  => $vjumlah
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_dt');

        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        /*$this->db->where('i_nota',$inota);*/
        $this->db->where('i_area',$iarea);
        $this->db->delete('tm_dt_item');
    }

    public function updateheader($idt,$iarea,$ddt,$vjumlah,$fsisa){
        $this->db->where('i_dt',$idt);
        $this->db->where('i_area',$iarea);
        $this->db->delete('tm_dt');
        
        $this->db->set(
            array(
                'i_dt'      => $idt,
                'i_area'    => $iarea,
                'd_dt'      => $ddt,
                'v_jumlah'  => $vjumlah,
                'f_sisa'    => $fsisa
            )
        );
        $this->db->insert('tm_dt');
    }

    public function insertdetail($idt,$ddt,$inota,$iarea,$dnota,$icustomer,$vsisa,$vjumlah,$i){
        $this->db->set(
            array(
                'i_dt'       => $idt,
                'd_dt'       => $ddt,
                'i_nota'     => $inota,
                'i_area'     => $iarea,
                'd_nota'     => $dnota,
                'i_customer' => $icustomer,
                'v_sisa'     => $vsisa,
                'v_jumlah'   => $vjumlah,
                'n_item_no'  => $i
            )
        );
        $this->db->insert('tm_dt_item');
    }

    public function cancel($idt, $iarea){
        $this->db->set(
            array(
                'f_dt_cancel'  => 't'
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('i_area',$iarea);
        return $this->db->update('tm_dt');
    }
}

/* End of file Mmaster.php */
