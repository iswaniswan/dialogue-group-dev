<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function baca($iperiode){
        $bulan_bon = '01-'.substr($iperiode, 4, 2).'-'.substr($iperiode, 0, 4);
        $bon =  date('ym', strtotime('+1 month', strtotime($bulan_bon)));
        if($iperiode < '201902'){
            $this->db->select("
                            a.i_product,
                            a.e_product_name,
                            sum(hiji) as hiji,
                            sum(dua) as dua,
                            sum(tilu) as tilu,
                            sum(opat) as opat,
                            sum(lima) as lima,
                            sum(tujuh) as tujuh,
                            sum(salapan) as salapan,
                            sum(sapuluh) as sapuluh,
                            sum(sabelas) as sabelas,
                            sum(duabelas) as duabelas,
                            sum(tujuhbelas) as tujuhbelas,
                            sum(duatilu) as duatilu,
                            sum(tiluhiji) as tiluhiji,
                            sum(AA) as AA,
                            sum(bon) as bon,
                            sum(counter) as counter,
                            sum(PB) as PB
                        from
                        (
                           select
                              i_product,
                              e_product_name,
                              hiji,
                              dua,
                              tilu,
                              opat,
                              lima,
                              tujuh,
                              salapan,
                              sapuluh,
                              sabelas,
                              duabelas,
                              tujuhbelas,
                              duatilu,
                              tiluhiji,
                              AA,
                              bon,
                              counter,
                              PB 
                           from
                              crosstab( 'select * from f_mutasi_semua(''$iperiode'', ''%-$bon-%'')', 'select distinct (i_store) as i_store from f_mutasi_semua(''$iperiode'', ''%-$bon-%'')
                        
                           order by
                              i_store' )as (i_product text, e_product_name text, hiji integer, dua integer, tilu integer, opat integer, lima integer, 
                        tujuh integer, salapan integer, sapuluh integer, sabelas integer, duabelas integer, tujuhbelas integer, duatilu integer, tiluhiji integer, AA integer, bon integer, counter integer, PB integer) 
                        )
                        as a 
                        left join
                           tr_product z 
                           on(TRIM(z.i_product) = TRIM(a.i_product)) 
                        group by
                            a.i_product,
                            a.e_product_name
                        order by
                            a.i_product,
                            a.e_product_name"
                        ,false);        
        }else{
            $this->db->select("
                            a.i_product,
                            a.e_product_name,
                            sum(hiji) as hiji,
                            sum(dua) as dua,
                            sum(tilu) as tilu,
                            sum(opat) as opat,
                            sum(lima) as lima,
                            sum(tujuh) as tujuh,
                            sum(salapan) as salapan,
                            sum(sapuluh) as sapuluh,
                            sum(sabelas) as sabelas,
                            sum(duabelas) as duabelas,
                            sum(tilubelas) as tilubelas,
                            sum(tujuhbelas) as tujuhbelas,
                            sum(duatilu) as duatilu,
                            sum(tiluhiji) as tiluhiji,
                            sum(AA) as AA,
                            sum(bon) as bon,
                            sum(counter) as counter,
                            sum(PB) as PB
                        from
                        (
                           select
                              i_product,
                              e_product_name,
                              hiji,
                              dua,
                              tilu,
                              opat,
                              lima,
                              tujuh,
                              salapan,
                              sapuluh,
                              sabelas,
                              duabelas,
                              tilubelas,
                              tujuhbelas,
                              duatilu,
                              tiluhiji,
                              AA,
                              bon,
                              counter,
                              PB 
                           from
                              crosstab( 'select * from f_mutasi_semua(''$iperiode'',''%-$bon-%'')', 'select distinct (i_store) as i_store from f_mutasi_semua(''$iperiode'',''%-$bon-%'')
                        
                           order by
                              i_store' )as (i_product text, e_product_name text, hiji integer, dua integer, tilu integer, opat integer, lima integer, tujuh integer, 
                        salapan integer, sapuluh integer, sabelas integer, duabelas integer, tilubelas integer, tujuhbelas integer, duatilu integer, tiluhiji integer, AA integer, bon integer, counter integer, PB integer) 
                        )
                        as a 
                        left join
                           tr_product z 
                           on(TRIM(z.i_product) = TRIM(a.i_product)) 
                        group by
                            a.i_product,
                            a.e_product_name
                        order by
                            a.i_product,
                            a.e_product_name
                    ",false);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
        $query->free_result();
    }

    public function bacastore($iperiode){
        $bulan_bon = '01-'.substr($iperiode, 4, 2).'-'.substr($iperiode, 0, 4);
        $bon =  date('ym', strtotime('+1 month', strtotime($bulan_bon)));
        $this->db->select("
                            distinct (i_store) as i_store 
                        from 
                            f_mutasi_semua('$iperiode','%-$bon-%') 
                        order by 
                            i_store"
                        ,false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
