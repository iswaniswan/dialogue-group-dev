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

    public function data($iarea, $folder, $iperiode, $title){
        $datatables = new Datatables(new CodeigniterAdapter);
        
			    $datatables->query("
                select a.customer, a.i_alokasi, a.d_alokasi, a.v_jumlah, a.v_lebih,
                '$folder' AS folder,
                '$iarea' AS iarea,
                '$iperiode' AS iperiode,
                '$title' AS title
                from(
                    select a.i_customer||b.e_customer_name as customer, a.i_pelunasan as i_alokasi, a.d_bukti as d_alokasi, a.v_jumlah, a.v_lebih
                        from tr_customer b, tm_pelunasan_lebih a left join tr_jenis_bayar c on(a.i_jenis_bayar=c.i_jenis_bayar)
						            where a.i_customer=b.i_customer and a.f_pelunasan_cancel='f' and a.v_lebih>0 and a.i_jenis_bayar<>'04'
                        and a.f_giro_tolak='f' and a.f_giro_batal='f'
						            and a.i_area='$iarea' 
                    union all
                    select a.i_customer||b.e_customer_name as customer, a.i_alokasi, a.d_alokasi, a.v_jumlah, a.v_lebih
                    from tr_customer b, tm_alokasi_lebih a 
                    where a.i_customer=b.i_customer and a.f_alokasi_cancel='f' and a.v_lebih>0
                    and a.i_area='$iarea'
                    ) as a
                    ORDER BY a.d_alokasi, a.i_alokasi ",false);
		$datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });
        
        $datatables->edit('v_lebih', function($data){
            return number_format($data['v_lebih']);
        });

        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('iperiode');
        $datatables->hide('iarea');
        return $datatables->generate();
    }

    public function total($isupplier){
            return $this->db->query("
            select sum(a.v_lebih) as jml
            from(
                select a.v_lebih
                from tr_supplier b, tm_pelunasanap_lebih a left join tr_jenis_bayar c on(a.i_jenis_bayar=c.i_jenis_bayar)
                where a.i_supplier=b.i_supplier and a.f_pelunasanap_cancel='f' and a.v_lebih>0
                and a.i_supplier='$isupplier' 
                union all
                select a.v_lebih
                from tr_supplier b, tm_alokasi_bk_lebih a 
                where a.i_supplier=b.i_supplier and a.f_alokasi_cancel='f' and a.v_lebih>0
                and a.i_supplier='$isupplier' 
                union all
                select a.v_lebih
                from tr_supplier b, tm_alokasi_kb_lebih a 
                where a.i_supplier=b.i_supplier and a.f_alokasi_cancel='f' and a.v_lebih>0
                and a.i_supplier='$isupplier' 
                ) as a
        ", false);
    }

    public function bacadetail($idtap,$supp,$area,$tahun){
        $idtap=str_replace('%20',' ',$idtap);
        $this->db->select("  a.* from (
            select a.i_dtap, a.d_dtap, a.v_netto, c.i_pelunasanap, c.d_bukti, c.i_giro, c.e_bank_name, b.v_jumlah, 
          d.e_jenis_bayarname, d.i_jenis_bayar
          from tm_dtap a
          left join tm_pelunasanap_item b on(b.i_dtap=a.i_dtap and a.i_area=b.i_area)
          inner join tm_pelunasanap c on(b.i_pelunasanap=c.i_pelunasanap and c.f_pelunasanap_cancel='f' 
          and b.i_area=c.i_area and a.i_supplier=c.i_supplier)
          left join tr_jenis_bayar d on(d.i_jenis_bayar=c.i_jenis_bayar)
          where a.i_dtap='$idtap' and a.i_area='$area' and a.i_supplier='$supp' and a.n_dtap_year=$tahun
          union all
          SELECT a.i_dtap, a.d_dtap, a.v_netto, c.i_alokasi, c.d_alokasi, '' as i_giro, '' as e_bank_name, b.v_jumlah, 
          '' as e_jenis_bayarname, '' as i_jenis_bayar
          from tm_dtap a
          left join tm_alokasi_kb_item b on(b.i_nota=a.i_dtap)
          inner join tm_alokasi_kb c on(b.i_alokasi=c.i_alokasi and c.f_alokasi_cancel='f' and b.i_kb=c.i_kb and 
          a.i_supplier=c.i_supplier)
          where a.i_dtap='$idtap' and a.i_area='$area' and a.i_supplier='$supp' and a.n_dtap_year=$tahun
          union all
          SELECT a.i_dtap, a.d_dtap, a.v_netto, c.i_alokasi, c.d_alokasi, '' as i_giro, '' as e_bank_name, b.v_jumlah, 
          '' as e_jenis_bayarname, '' as i_jenis_bayar
          from tm_dtap a
          left join tm_alokasi_bk_item b on(b.i_nota=a.i_dtap)
          inner join tm_alokasi_bk c on(b.i_alokasi=c.i_alokasi and c.f_alokasi_cancel='f' and b.i_kbank=c.i_kbank 
          and a.i_supplier=c.i_supplier)
          where a.i_dtap='$idtap' and a.i_area='$area' and a.i_supplier='$supp' and a.n_dtap_year=$tahun
          ) as a
          order by a.i_pelunasanap", false);
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
