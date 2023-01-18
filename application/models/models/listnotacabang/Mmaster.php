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
            $ispb       = $data['i_spb'];
            $iarea      = $data['i_area'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $iperiode   = $data['iperiode'];
            $title      = $data['title'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$inota/$ispb/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
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
        SELECT	sum(a.v_sisa) as jml,
                sum(a.v_nota_netto) as jmlnilai
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

    function bacanota($inota,$ispb,$iarea){
        return $this->db->query("select tm_nota.i_nota, tm_nota.d_nota, tm_nota.i_customer, tm_nota.i_salesman, tm_nota.i_area,
                                 tm_spb.n_spb_toplength, tm_nota.e_remark, tm_nota.f_cicil, tm_nota.i_nota_old, tm_nota.v_nota_netto,
                                 tm_nota.v_nota_discount1, tm_nota.v_nota_discount2, tm_nota.v_nota_discount3, tm_nota.v_nota_discount4,
                                 tm_nota.n_nota_discount1, tm_nota.n_nota_discount2, tm_nota.n_nota_discount3, tm_nota.n_nota_discount4,
                                 tm_nota.v_nota_gross, tm_nota.v_nota_discounttotal, tm_nota.n_price, tm_nota.v_nota_ppn, tm_nota.f_cicil,
                                 tm_nota.i_dkb, tm_nota.d_dkb, tm_nota.i_bapb, tm_nota.d_bapb, tm_nota.v_sisa,
                                 tm_nota.i_spb, tm_spb.i_spb_old, tm_nota.d_spb, tm_spb.v_spb, tm_spb.f_spb_consigment, tm_spb.i_spb_po,
                                 tm_spb.v_spb_discounttotal, tm_spb.f_spb_plusppn, tm_spb.f_spb_plusdiscount, tm_nota.i_sj, tm_nota.d_sj,
                                 tr_customer.e_customer_name, tm_nota.f_masalah, tm_nota.f_insentif,
                                 tr_customer_area.e_area_name, tm_spb.i_price_group, tr_price_group.e_price_groupname,
                                 tr_salesman.e_salesman_name
                                 from tm_nota 
                                 left join tm_spb on (tm_nota.i_spb=tm_spb.i_spb and tm_nota.i_area=tm_spb.i_area and tm_spb.i_spb = '$ispb')
                                 inner join tr_price_group on(tm_spb.i_price_group=tr_price_group.i_price_group)
                                 left join tm_promo on (tm_nota.i_spb_program=tm_promo.i_promo)
                                 inner join tr_customer on (tm_nota.i_customer=tr_customer.i_customer)
                                 inner join tr_salesman on (tm_nota.i_salesman=tr_salesman.i_salesman)
                                 inner join tr_customer_area on (tm_nota.i_customer=tr_customer_area.i_customer)
                                 where tm_nota.i_nota = '$inota' and tm_nota.i_area='$iarea'"
                                , false);
    }

    function bacadetailnota($inota,$iarea){
        return $this->db->query("select a.i_product_motif, a.i_product, a.e_product_name, b.e_product_motifname, a.v_unit_price, a.n_deliver
                                from tm_nota_item a
                                inner join tr_product_motif b on (b.i_product_motif=a.i_product_motif
                                and b.i_product=a.i_product)
                                where a.i_nota = '$inota' and a.i_area = '$iarea'  
                                order by a.n_item_no", false);
    }
}

/* End of file Mmaster.php */
