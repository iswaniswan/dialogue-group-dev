<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function cekarea($username, $idcompany)
    {
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return '00';
        } else {
            return 'xx';
        }
    }

    public function bacaperiode($iarea, $dfrom, $dto)
    {
        $where = '';
        if ($iarea != 'SA') {
            $where .= "and a.i_area='$iarea'";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select distinct on (a.d_giro, a.i_giro) a.i_giro, a.i_area||' - '||tr_area.e_area_name as i_area,
        a.d_giro,
        a.i_rv,
        a.d_rv, a.d_giro_duedate,
        tm_dt.i_dt,
        tm_dt.d_dt,
        tr_customer.i_customer||' - '||tr_customer.e_customer_name as i_customer,
        a.e_giro_bank,
        a.v_jumlah,
        a.v_sisa from tm_giro a
inner join tr_area on(a.i_area=tr_area.i_area)
          inner join tr_customer on(a.i_customer=tr_customer.i_customer and a.i_area=tr_customer.i_area)
          left join tm_pelunasan on(a.i_giro=tm_pelunasan.i_giro and tm_pelunasan.i_area=a.i_area
                      and tm_pelunasan.i_jenis_bayar='01' and tm_pelunasan.f_pelunasan_cancel='f')
          left join tm_dt on(tm_pelunasan.i_dt=tm_dt.i_dt and tm_pelunasan.i_area=tm_dt.i_area)
          where a.d_giro >= to_date('$dfrom','dd-mm-yyyy') and a.d_giro <= to_date('$dto','dd-mm-yyyy') $where
ORDER BY a.d_giro, a.i_giro");

        $datatables->edit('v_jumlah', function ($data) {
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_sisa', function ($data) {
            return number_format($data['v_sisa']);
        });
        return $datatables->generate();
    }
}

/* End of file Mmaster.php */