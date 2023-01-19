<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacasupplier($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_supplier
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
    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
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
        if($isupplier == "ALL"){
          $datatables->query("
            SELECT	a.i_dtap, a.d_dtap, a.d_due_date, a.i_supplier, b.e_supplier_name, a.i_area, a.n_dtap_year,
            a.v_discount, a.v_ppn, a.v_netto, a.v_sisa, a.f_dtap_cancel,
                '$folder' AS folder,
                '$isupplier' AS isupplier,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iperiode' AS iperiode,
                '$title' AS title
                from tm_dtap a, tr_supplier b
                where a.i_supplier=b.i_supplier and
                a.d_dtap >= '$dfrom' AND
                a.d_dtap <= '$dto'
                order by a.d_dtap, a.i_dtap",false);
		    }else{    		
			    $datatables->query("
            SELECT	a.i_dtap, a.d_dtap, a.d_due_date, a.i_supplier, b.e_supplier_name, a.i_area, a.n_dtap_year,
            a.v_discount, a.v_ppn, a.v_netto, a.v_sisa, a.f_dtap_cancel,
                '$folder' AS folder,
                '$isupplier' AS isupplier,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iperiode' AS iperiode,
                '$title' AS title
                from tm_dtap a, tr_supplier b
                where a.i_supplier=b.i_supplier
                and a.i_supplier='$isupplier' and
                a.d_dtap >= '$dfrom' AND
                a.d_dtap <= '$dto'
                order by a.d_dtap, a.i_dtap ",false);
		    }

        $datatables->edit('v_discount', function($data){
            return number_format($data['v_discount']);
        });
        
        $datatables->edit('v_ppn', function($data){
            return number_format($data['v_ppn']);
        });

        $datatables->edit('v_netto', function($data){
            return number_format($data['v_netto']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->add('action', function ($data) {
            $idtap      = $data['i_dtap'];
            $folder     = $data['folder'];
            $isupplier  = $data['isupplier'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $iperiode   = $data['iperiode'];
            $title      = $data['title'];
            $fcancel    = $data['f_dtap_cancel'];
            $iarea      = $data['i_area'];
            $year       = $data['n_dtap_year'];
            $data       = '';
            $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detail/$idtap/$isupplier/$iarea/$year\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            return $data;
        });
        $datatables->hide('n_dtap_year');
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('isupplier');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_dtap_cancel');
        $datatables->hide('iperiode');
        $datatables->hide('i_supplier');
        $datatables->hide('i_area');
        return $datatables->generate();
    }

    public function total($dfrom, $dto, $isupplier){
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
        if($isupplier == "ALL"){
            return $this->db->query("
            SELECT	sum(a.v_sisa) as jml
				    from tm_dtap a, tr_supplier b
				    where
				    a.i_supplier=b.i_supplier and
				    a.d_dtap >= '$dfrom' AND
				    a.d_dtap <= '$dto'
            ", false);
        }else{
            return $this->db->query("
            SELECT	sum(a.v_sisa) as jml
				    from tm_dtap a, tr_supplier b
				    where
				    a.i_supplier=b.i_supplier and a.i_supplier='$isupplier' and
				    a.d_dtap >= '$dfrom' AND
				    a.d_dtap <= '$dto'
            ", false);
        }
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
