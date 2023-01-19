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
            SELECT	a.i_nota, a.d_nota, a.i_spb, a.d_spb, '('||a.i_customer||') '||b.e_customer_name as customer, a.v_nota_netto, a.i_area,
            a.v_sisa, '$folder' AS folder, '$dfrom' AS dfrom,
            '$dto' AS dto, '$iperiode' AS iperiode, '$title' AS title
            from tm_nota a, tr_customer b, tr_area c
            where a.i_customer=b.i_customer and a.i_area=c.i_area
            and a.f_ttb_tolak='f'
            and a.f_nota_cancel='f'
            and not a.i_nota isnull
            and a.i_area='$iarea' and
            a.d_nota >= '$dfrom' AND
            a.d_nota <= '$dto'
            ORDER BY a.i_nota ",false);
        
        $datatables->edit('v_nota_netto', function($data){
            return number_format($data['v_nota_netto']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->add('action', function ($data) {
            $inota      = $data['i_nota'];
            $folder     = $data['folder'];
            $iarea      = $data['i_area'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $iperiode   = $data['iperiode'];
            $title      = $data['title'];
            $data       = '';
            $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$inota/$iarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('iperiode');
        $datatables->hide('i_area');
        return $datatables->generate();
    }

    public function cetak($dfrom, $dto, $iarea){
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
        SELECT	count(*) as jml
                from tm_nota a, tr_area b
                where
                a.i_area=b.i_area and a.i_area='$iarea' and
                a.d_nota >= '$dfrom' AND
                a.d_nota <= '$dto'
                and a.f_ttb_tolak='f' and a.n_print>0
                and a.f_nota_cancel='f'
                and not a.i_nota isnull
        ", false);
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
                from tm_nota a, tr_area b
                where
                a.i_area=b.i_area and a.i_area='$iarea' and
                a.d_nota >= '$dfrom' AND
                a.d_nota <= '$dto'
                and a.f_ttb_tolak='f'
                and a.f_nota_cancel='f'
                and not a.i_nota isnull
        ", false);
    }

    public function bacadetail($nota,$iarea){
        $this->db->select("  
        c.i_nota, c.d_nota, c.v_nota_netto, c.i_alokasi, c.d_alokasi, c.i_kbank, c.e_bank_name, c.v_jumlah,
        c.e_jenis_bayarname, c.i_jenis_bayar from 
        (
        select a.i_nota, a.d_nota, a.v_nota_netto, c.i_pelunasan as i_alokasi, c.d_bukti as d_alokasi, c.i_giro as i_kbank, c.e_bank_name, 
        b.v_jumlah, d.e_jenis_bayarname, d.i_jenis_bayar
        from tm_nota a
        left join tm_pelunasan_item b on(b.i_nota=a.i_nota)
        inner join tm_pelunasan c on(b.i_pelunasan=c.i_pelunasan and c.f_pelunasan_cancel='f'
        and c.f_giro_tolak='f' and c.f_giro_batal='f' and b.i_area=c.i_area)
        left join tr_jenis_bayar d on(d.i_jenis_bayar=c.i_jenis_bayar)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kbank, g.e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasi_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasi f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kbank=f.i_kbank)
        inner join tr_bank_old g on(f.i_coa_bank=g.i_coa)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kbank, g.e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasi_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasi f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kbank=f.i_kbank)
        inner join tr_bank g on(f.i_coa_bank=g.i_coa)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kn as i_kbank, '' as e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasikn_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasikn f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kn=f.i_kn)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, '' as i_kbank, '' as e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasihl_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasihl f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area)
        where a.i_nota='$nota' and a.i_area='$iarea'
        union all
        select a.i_nota, a.d_nota, a.v_nota_netto, f.i_alokasi, f.d_alokasi, f.i_kn as i_kbank, '' as e_bank_name, e.v_jumlah,
        '' as e_jenis_bayarname, '' as i_jenis_bayar
        from tm_nota a
        left join tm_alokasiknr_item e on(e.i_nota=a.i_nota)
        inner join tm_alokasiknr f on(e.i_alokasi=f.i_alokasi and f.f_alokasi_cancel='f' and e.i_area=f.i_area 
        and e.i_kn=f.i_kn)
        where a.i_nota='$nota' and a.i_area='$iarea'
        )as c
        order by c.d_alokasi, c.i_alokasi", false);
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
