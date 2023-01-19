<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea($username, $idcompany){
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            return '00';
        }else{
            return 'xx';
        }
    }

    public function data($tahun,$bulan,$iarea,$username,$idcompany,$folder){
        $iperiode   = $tahun.$bulan ;
        if ($iarea=='00') {
            $sql = "AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')";
        }else{
            $sql = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_area,
                e_area_name,
                v_target,
                v_nota_grossinsentif,
                CASE WHEN v_target <> 0 THEN (v_nota_grossinsentif / v_target)* 100
                ELSE 0 END AS persen,
                v_real_regularinsentif,
                CASE WHEN v_nota_grossinsentif <> 0 THEN (v_real_regularinsentif / v_nota_grossinsentif)* 100
                ELSE 0 END AS persenreg,
                v_real_babyinsentif,
                CASE WHEN v_nota_grossinsentif <> 0 THEN (v_real_babyinsentif / v_nota_grossinsentif)* 100
                ELSE 0 END AS persenbaby,
                v_spb_gross,
                CASE WHEN v_target <> 0 THEN (v_spb_gross / v_target)* 100
                ELSE 0 END AS persenspb,
                '$folder' AS folder,
                '$iperiode' AS iperiode
            FROM
                tm_target a
            INNER JOIN tr_area b ON
                (a.i_area = b.i_area)
            WHERE
                a.i_periode = '$iperiode'
                $sql
            ORDER BY
                a.i_area"
        , FALSE);
        $datatables->add('action', function ($data) {
            $iarea      = trim($data['i_area']);
            $iperiode   = substr($data['iperiode'],2,6);
            $xperiode   = $data['iperiode'];
            $folder     = $data['folder'];
            $data       = '';
            $data      .= "&nbsp;&nbsp;<a href=\"#\" title=\"Detail Nota\" onclick='window.open(\"$folder/cform/detailnota/$iarea/$iperiode/\",\"#main\")'><i class='fa fa-external-link'></i></a>&nbsp;&nbsp;<a href=\"#\" title=\"Detail KN\" onclick='window.open(\"$folder/cform/detailkn/$iarea/$xperiode/\",\"#main\")'><i class='fa fa-external-link-square'></i></a>";
            return $data;
        });

        $datatables->edit('v_target', function ($data) {
            return number_format($data['v_target']);
        });
        $datatables->edit('v_nota_grossinsentif', function ($data) {
            return number_format($data['v_nota_grossinsentif']);
        });
        $datatables->edit('persen', function ($data) {
            return number_format($data['persen'],2)." %";
        });
        $datatables->edit('v_real_regularinsentif', function ($data) {
            return number_format($data['v_real_regularinsentif']);
        });
        $datatables->edit('persenreg', function ($data) {
            return number_format($data['persenreg'],2)." %";
        });
        $datatables->edit('v_real_babyinsentif', function ($data) {
            return number_format($data['v_real_babyinsentif']);
        });
        $datatables->edit('persenbaby', function ($data) {
            return number_format($data['persenbaby'],2)." %";
        });
        $datatables->edit('v_spb_gross', function ($data) {
            return number_format($data['v_spb_gross']);
        });
        $datatables->edit('persenspb', function ($data) {
            return number_format($data['persenspb'],2)." %";
        });
        $datatables->hide('i_area');
        $datatables->hide('iperiode');
        $datatables->hide('folder');
        return $datatables->generate();
    }

    public function total($tahun,$bulan,$iarea,$username,$idcompany){  
        $iperiode   = $tahun.$bulan ;
        if ($iarea=='00') {
            $sql = "AND a.i_area IN (
                    SELECT
                        i_area
                    FROM
                        public.tm_user_area
                    WHERE
                        username = '$username'
                        AND id_company = '$idcompany')";
        }else{
            $sql = "";
        }    
        return $this->db->query("
            SELECT
                sum(v_target) AS target,
                sum(v_nota_grossinsentif) AS penjualan,
                sum(v_real_regularinsentif) AS reguler,
                sum(v_real_babyinsentif) AS baby,
                sum(v_spb_gross) AS spb
            FROM
                tm_target a
            INNER JOIN tr_area b ON
                (a.i_area = b.i_area)
            WHERE
                a.i_periode = '$iperiode'
                $sql"
        , FALSE);
    }

    public function bacadetailnota($iarea,$period){
        $this->db->select("  a.i_nota, a.d_nota, a.v_nota_gross, a.i_customer, b.e_customer_name,
            b.e_customer_address, c.e_city_name, a.i_area, a.i_salesman, d.e_salesman_name
            from tm_nota a, tr_customer b, tr_city c, tr_salesman d
            where a.i_customer=b.i_customer and a.i_area=b.i_area and b.i_city=c.i_city
            and a.i_salesman=d.i_salesman
            and b.i_area=c.i_area and a.i_area='$iarea' and a.i_nota like 'FP-$period-%'
            order by a.i_salesman, a.i_nota", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetailkn($iarea,$periode){
        $this->db->select("  a.i_kn, a.d_kn, a.v_netto, a.i_customer, b.e_customer_name, b.e_customer_address, c.e_city_name, a.i_area
            from tm_kn a, tr_customer b, tr_city c
            where a.i_customer=b.i_customer and b.i_city=c.i_city and b.i_area=c.i_area
            and a.i_area='$iarea' and to_char(a.d_kn,'yyyymm')='$periode'
            order by a.i_kn", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
}

/* End of file Mmaster.php */
