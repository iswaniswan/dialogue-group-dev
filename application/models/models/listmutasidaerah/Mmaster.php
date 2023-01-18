<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                DISTINCT (b.i_store),
                b.e_store_name,
                c.i_store_location,
                c.e_store_locationname,
                a.i_area
            FROM
                tr_area a,
                tr_store b,
                tr_store_location c
            WHERE 
                a.i_area=b.i_store 
                and b.i_store=c.i_store
                and (a.i_area in ( 
                    select 
                        i_area 
                    from 
                        public.tm_user_area 
                    where 
                        username='$username') 
                    )
                and not a.i_store in ('AA','PB')
                and c.i_store_location='00'
            ORDER BY 
                b.i_store, 
                c.i_store_location
        ", FALSE)->result();
    }

    public function bacastore($istore){
        return $this->db->query("
                                select
                                    c.i_store_location,
                                    b.i_store,
                                    a.i_area
                                from 
                                    tr_area a,
                                    tr_store b,
                                    tr_store_location c
                                where 
                                    a.i_store = b.i_store
                                    and b.i_store = c.i_store
                                    and a.i_store = '$istore'"
                                ,false);
    }

    public function baca($istore,$iperiode,$istorelocation){
      $this->db->select(" 
                            n_modul_no as max 
                        from 
                            tm_dgu_no 
                        where 
                            i_modul='MTS'
                            and i_area='$istore' 
                        for update"
                        , false); 
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        $this->db->query("update 
                            tm_dgu_no 
                          set 
                            e_periode='$iperiode'
                          where 
                            i_modul='MTS' 
                            and i_area='$istore' 
                            and i_store_location='$istorelocation'"
                        ,false);
      }else{
        $this->db->query("
                        insert into 
                            tm_dgu_no(i_modul, i_area, e_periode, i_store_location) 
                        values 
                            ('MTS','$istore','$iperiode', '$istorelocation')
                        ");
      }
      $query->free_result();
      if($iperiode>'201512'){
            $query = $this->db->query("	
                                        select
                                            e_product_groupname, 
                                            i_product, 
                                            i_product_grade,
                                            sum(n_saldo_awal) as n_saldo_awal, 
                                            sum(n_mutasi_pembelian) as n_mutasi_pembelian,
                                            sum(n_mutasi_returoutlet) as n_mutasi_returoutlet, 
                                            sum(n_mutasi_bbm) as n_mutasi_bbm,
                                            sum(n_mutasi_penjualan) as n_mutasi_penjualan, 
                                            sum(n_mutasi_bbk) as n_mutasi_bbk, 
                                            sum(n_saldo_akhir) as n_saldo_akhir, 
                                            sum(n_mutasi_ketoko) as n_mutasi_ketoko, 
                                            sum(n_mutasi_daritoko) as n_mutasi_daritoko, 
                                            sum(n_mutasi_git) as n_saldo_git, 
                                            e_mutasi_periode, 
                                            i_store,
                                            sum(n_git_penjualan) as n_git_penjualan, 
                                            sum(n_git_penjualanasal) as n_git_penjualanasal,
                                            sum(n_mutasi_gitasal) as n_mutasi_gitasal,
                                            sum(n_saldo_stockopname) as n_saldo_stockopname, 
                                            e_product_name, 
                                            i_store_location
                                        from 
                                            f_mutasi_stock_daerah_saldoakhir('$iperiode','$istore','$istorelocation')
                                        group by 
                                            i_product, 
                                            i_product_grade, 
                                            e_product_groupname, 
                                            e_mutasi_periode, 
                                            i_store, 
                                            e_product_name, 
                                            i_store_location
                                        order by 
                                            e_product_groupname, 
                                            e_product_name, 
                                            i_product "
                                        ,false);#->limit($num,$offset);
      }else{
            $query = $this->db->query("	
                                        select
                                            e_product_groupname, 
                                            i_product, 
                                            i_product_grade,
                                            sum(n_saldo_awal) as n_saldo_awal, 
                                            sum(n_mutasi_pembelian) as n_mutasi_pembelian,
                                            sum(n_mutasi_returoutlet) as n_mutasi_returoutlet, 
                                            sum(n_mutasi_bbm) as n_mutasi_bbm,
                                            sum(n_mutasi_penjualan) as n_mutasi_penjualan, 
                                            sum(n_mutasi_bbk) as n_mutasi_bbk, 
                                            sum(n_saldo_akhir) as n_saldo_akhir, 
                                            sum(n_mutasi_ketoko) as n_mutasi_ketoko, 
                                            sum(n_mutasi_daritoko) as n_mutasi_daritoko, 
                                            sum(n_mutasi_git) as n_saldo_git, 
                                            e_mutasi_periode, 
                                            i_store,
                                            sum(n_git_penjualan) as n_git_penjualan, 
                                            sum(n_git_penjualanasal) as n_git_penjualanasal,
                                            sum(n_mutasi_gitasal) as n_mutasi_gitasal,
                                            sum(n_saldo_stockopname) as n_saldo_stockopname, 
                                            e_product_name, 
                                            i_store_location
                                        from 
                                            f_mutasi_stock_daerah('$iperiode','$istore','$istorelocation')
                                        group by 
                                            i_product, 
                                            i_product_grade, 
                                            e_product_groupname, 
                                            e_mutasi_periode, 
                                            i_store, 
                                            e_product_name, 
                                            i_store_location
                                        order by 
                                            e_product_groupname,
                                            e_product_name, 
                                            i_product "
                                        ,false);#->limit($num,$offset);
        }
		if ($query->num_rows() > 0){
		  return $query->result();
		}
        $query->free_result();
    }

    public function detail($istorelocation,$iperiode,$iarea,$iproduct){
        $query =  $this->db->query("
                                select
                                   b.e_product_name,
                                   a.ireff,
                                   a.dreff,
                                   a.area,
                                   a.periode,
                                   a.product,
                                   e.e_customer_name,
                                   a.urut,
                                   sum(a.in) as in,
                                   sum(a.out) as out,
                                   sum(a.git) as git,
                                   sum(a.gitpenjualan) as gitpenjualan 
                                FROM
                                   tr_product b,
                                   vmutasidetail a 
                                   left join
                                      tm_nota_item c 
                                      on c.i_sj = a.ireff 
                                      and a.product = c.i_product 
                                   left join
                                      tm_spb d 
                                      on d.i_sj = c.i_sj 
                                   left join
                                      tr_customer e 
                                      on d.i_customer = e.i_customer 
                                WHERE
                                   b.i_product = a.product 
                                   and a.loc = '$istorelocation' 
                                   AND a.periode = '$iperiode' 
                                   AND a.area = '$iarea' 
                                   AND a.product = '$iproduct' 
                                group by
                                   b.e_product_name,
                                   a.ireff,
                                   a.dreff,
                                   a.area,
                                   a.periode,
                                   a.product,
                                   e.e_customer_name,
                                   a.urut 
                                order by
                                   dreff,
                                   urut,
                                   ireff
                                ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
        $query->free_result();
    }
}

/* End of file Mmaster.php */
