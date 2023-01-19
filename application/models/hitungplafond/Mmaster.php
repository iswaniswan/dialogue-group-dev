<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function bacaperiode($iperiodeawal,$iperiodeakhir){
		$query = $this->db->query("select distinct on(i_customer_groupbayar) data.*, case when v_ratapenjualan isnull then 0 else 
		                        	cast(n_index*v_ratapenjualan as integer) end as v_plafon,
                                	v_flapond as v_plafonsblmnya, '$iperiodeawal' as periodeawal, '$iperiodeakhir' as periodeakhir        
                                	from(
                                    select a.i_area, area.e_area_name, a.i_customer, b.i_customer_groupbayar, 
                                    e_customer_name, e_customer_address, e_customer_city, to_char(d_signin,'dd-mm-yyyy') as d_signin, 
                                    n_customer_toplength, 
                                    	case when n_ratatelat isnull then '99-Toko Baru'
                                        else 
                                          case when n_ratatelat <=30 then '01-Baik Sekali'
                                               when n_ratatelat <=45 then '02-Baik'
                                               when n_ratatelat <=60 then '03-Cukup' 
                                               when n_ratatelat <=70 then '04-Calon BL'
                                          else '05-Black List' end                                     
                                      	end as e_kategori,
                                      	case when n_ratatelat isnull then
                                        0
                                        else
                                        case 
                                          when n_ratatelat <=30 then 2
                                          when n_ratatelat <=45 then 1.5
                                          when n_ratatelat <=60 then 1                                    
                                          when n_ratatelat <=75 then 0.5
                                        else 0 end 
                                      	end as n_index,
                                   		n_ratatelat, 
                                      	case when v_totalpenjualan is null then 0 
										   when v_totalpenjualan is not null then v_totalpenjualan end
										v_totalpenjualan, 
                                      	case when v_maxpenjualan is null then 0 
                                           when v_maxpenjualan is not null then v_maxpenjualan end
                                    	v_maxpenjualan, 
                                      	case when v_ratapenjualan is null then 0 
                                      	when v_ratapenjualan is not null then v_ratapenjualan end
                                    	v_ratapenjualan
                                    	from tr_customer a left join tr_area area on (area.i_area=a.i_area)
                                    	left join tr_customer_groupbayar b on (b.i_customer=a.i_customer)
                                    	left join f_keterlambatan_alokasi_piutang_pergroupbayar('$iperiodeawal','$iperiodeakhir') c on
                                    	(c.i_customer_groupbayar=b.i_customer_groupbayar)
                                    	left join f_penjualan_pergroupbayar('$iperiodeawal','$iperiodeakhir') d on (d.i_customer_groupbayar=b.i_customer_groupbayar)
                                    	order by a.i_area, e_customer_name, a.i_customer
                                	) as data
                                	left join tr_customer_groupar b on (b.i_customer=data.i_customer)
                                	order by i_customer_groupbayar, e_customer_name");
		if ($query->num_rows() > 0){
			return $query->result();
		}
	}
	
	function bacano($iperiodeawal, $iperiodeakhir){
    	$this->db->select(" a.e_area_name, b.i_customer_groupbayar, c.e_customer_name , b.e_periode_awal, b.e_periode_akhir, 
    	                    b.i_kategori, b.e_kategori, 
    	                    b.n_rata_telat, b.i_index, b.v_total_penjualan, b.v_max_penjualan, b.v_rata_penjualan, b.v_plafond, b.v_plafond_before, b.v_plafond_acc, 
    	                    c.n_customer_toplength
    	                    from tm_plafond b, tr_area a, tr_customer c
    	                    where a.i_area = b.i_area and b.e_periode_awal ='$iperiodeawal' and b.e_periode_akhir ='$iperiodeakhir'
    	                    and c.i_customer= b.i_customer_groupbayar
    	                    order by b.i_customer_groupbayar",false);
    	$tes=$this->db->get();
    	return $tes;
  	}
}

/* End of file Mmaster.php */