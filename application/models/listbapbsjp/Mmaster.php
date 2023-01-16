<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($cari){
        $cari      = str_replace("'", "", $cari);
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
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
                AND (UPPER(i_area) LIKE '%$cari%'
                OR UPPER(e_area_name) LIKE '%$cari%')
        ", FALSE);
    }

    public function bacaareasj($iarea){
        $query = $this->db->query("
                                select
                                   e_area_name 
                                from
                                   tr_area 
                                where
                                   i_area = '$iarea'

                                ", FALSE);
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $areasj = $kuy->e_area_name; 
        }else{
          $areasj = '';
        }
        return $areasj;
    }

    public function data($dfrom,$dto,$iarea,$areasj,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                                i_bapb AS id,
                                to_char(d_bapb, 'dd-mm-yyyy') AS d_bapb, 
                                '$areasj' as areasj,
                                UPPER(e_area_name) AS e_area_name,
                                a.i_area,
                                i_customer,
                                f_bapb_cancel AS status,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto,
                                '$i_menu' AS i_menu, 
                                '$folder' AS folder
                            from
                               tm_bapbsjp a,
                               tr_area b 
                            where
                               a.i_area = b.i_area 
                               and a.i_area = '$iarea' 
                               and a.d_bapb >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_bapb <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                               a.i_bapb desc");

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $status         = $data['status'];
            $i_area         = $data['i_area'];
            $i_customer     = $data['i_customer'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_area/$dfrom/$dto/$i_customer\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $status == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('i_customer');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cancel($id,$iarea){
        return $this->db->query("
            UPDATE tm_bapbsjp 
            SET f_bapb_cancel='t' 
            WHERE i_bapb='$id' 
            AND i_area='$iarea'");
    }

    public function jumlahitem($id,$iarea){
        return $this->db->query("
                                SELECT 
                                    *
                                FROM
                                    tm_bapbsjp_item
                                WHERE
                                    i_bapb='$id'
                                    and i_area='$iarea'
                                ");
    }

    public function jumlaheskpedisi($id,$iarea){
        return $this->db->query("
                                SELECT
                                    *
                                FROM
                                    tm_bapbsjp_ekspedisi
                                WHERE
                                    i_bapb='$id'
                                    and i_area='$iarea'
                                ");
    }

    public function baca($id,$iarea){
        $query = $this->db->query("
                                select
                                   tm_bapbsjp.i_bapb,
                                   tm_bapbsjp.i_dkb_kirim,
                                   tm_bapbsjp.i_area,
                                   tm_bapbsjp.d_bapb,
                                   tm_bapbsjp.i_bapb_old,
                                   tm_bapbsjp.f_bapb_cancel,
                                   tm_bapbsjp.n_bal,
                                   tr_customer.e_customer_name,
                                   tm_bapbsjp.i_customer,
                                   tr_area.e_area_name,
                                   tr_dkb_kirim.e_dkb_kirim,
                                   tm_bapbsjp.v_bapb,
                                   tm_bapbsjp.v_kirim,
                                   tm_bapbsjp.i_segel_pergikirim,
                                   tm_bapbsjp.i_segel_pulangkirim 
                                from
                                   tm_bapbsjp 
                                   inner join
                                      tr_area 
                                      on(tm_bapbsjp.i_area = tr_area.i_area) 
                                   left join
                                      tr_customer 
                                      on(tm_bapbsjp.i_customer = tr_customer.i_customer) 
                                   inner join
                                      tr_dkb_kirim 
                                      on(tm_bapbsjp.i_dkb_kirim = tr_dkb_kirim.i_dkb_kirim) 
                                where
                                   tm_bapbsjp.i_bapb = '$id' 
                                   and tm_bapbsjp.i_area = '$iarea'
                                ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($id,$iarea){
        $query = $this->db->query("
                                select
                                   a.*,
                                   sum(b.n_quantity_deliver) as quantity,
                                   sum(c.v_product_retail) as jumlah03 
                                from
                                   tm_bapbsjp_item a,
                                   tm_sjp_item b 
                                   left join
                                      tr_product_price c 
                                      on (b.i_product = c.i_product 
                                      and b.i_product_grade = c.i_product_grade) 
                                where
                                   a.i_bapb = '$id' 
                                   and a.i_area = '$iarea' 
                                   and a.i_sj = b.i_sjp 
                                   and c.i_price_group = '03' 
                                group by
                                   a.i_bapb,
                                   a.d_bapb,
                                   a.i_area,
                                   a.i_sj 
                                order by
                                   i_bapb
                                ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetailx($id,$iarea){
        $query = $this->db->query("
                                select
                                   a.*,
                                   b.e_ekspedisi 
                                from
                                   tm_bapbsjp_ekspedisi a,
                                   tr_ekspedisi b 
                                where
                                   a.i_bapb = '$id' 
                                   and a.i_area = '$iarea' 
                                   and a.i_ekspedisi = b.i_ekspedisi 
                                order by
                                   a.i_bapb
                                ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadkb($username, $idcompany){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $this->db->select('*');
            $this->db->from('tr_dkb_kirim');
            return $this->db->get();
        }else{
            $this->db->select('*');
            $this->db->from('tr_dkb_kirim');
            $this->db->where('i_dkb_kirim','1');
            return $this->db->get();
        }
    }

    public function bacasj($cari,$iarea){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
                                select
                                   a.* 
                                from
                                   tm_sjp a 
                                where
                                   a.i_area = '$iarea' 
                                   and a.i_bapb isnull 
                                   and 
                                   (
                                      upper(a.i_sjp) like '%$cari%' 
                                      or upper(a.i_sjp_old) like '%$cari%'
                                   )
                                order by
                                   a.i_sjp
                                
                                ", FALSE);
    }

    // public function bacasjx($iarea,$icustomer,$isj){
    //     $username  = $this->session->userdata('username');
    //     $idcompany = $this->session->userdata('id_company');
    //     return $this->db->query("
    //         SELECT
    //             a.*,
    //             to_char(a.d_sj,'dd-mm-yyyy') AS dsj,
    //             b.i_customer, 
    //             b.e_customer_name
    //         FROM
    //             tm_nota a,
    //             tr_customer b
    //         WHERE
    //             a.i_area = '$iarea'
    //             AND (SUBSTRING(a.i_sj, 9, 2) IN (
    //             SELECT
    //                 i_area
    //             FROM
    //                 public.tm_user_area
    //             WHERE
    //                 username = '$username'
    //                 AND id_company = '$idcompany'))
    //             AND a.i_bapb ISNULL
    //             AND a.i_customer = b.i_customer
    //             AND a.i_customer = '$icustomer'
    //             AND a.i_sj = '$isj'
    //         ORDER BY
    //             a.i_sj", FALSE);
    // }

    public function bacaex($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT 
                i_ekspedisi,
                e_ekspedisi
            FROM
                tr_ekspedisi
            WHERE (UPPER(i_ekspedisi) LIKE '%$cari%'
                OR UPPER(e_ekspedisi) LIKE '%$cari%')
            ORDER BY
                i_ekspedisi", FALSE);
    }

    public function bacaexx($iekspedisi){
        return $this->db->query("
            SELECT 
                *
            FROM
                tr_ekspedisi
            WHERE i_ekspedisi = '$iekspedisi'
            ORDER BY
                i_ekspedisi", FALSE);
    }

    public function updatebapb($ibapb, $dbapb, $iarea, $idkbkirim, $nbal, $ibapbold, $vbapb, $vkirim, $i_segel){
        $data = array(
            'i_dkb_kirim'        => $idkbkirim,
            'i_area'             => $iarea,
            'n_bal'              => $nbal,
            'i_bapb_old'         => $ibapbold,
            'v_bapb'             => $vbapb,
            'v_kirim'            => $vkirim,
            'i_segel_pergikirim' => $i_segel,
        );

        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);

        $this->db->update('tm_bapbsjp', $data);

    }

    public function deletedetail($ibapb, $iarea, $isj, $daer){
        $query = $this->db->query("
                                    select 
                                        * 
                                    from 
                                        tm_bapbsjp_item 
                                    where 
                                        i_bapb = '$ibapb' 
                                        and i_area='$iarea' 
                                        and i_sj='$isj'"
                                    , false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $this->db->query("
                                update 
                                    tm_sjp 
                                set 
                                    i_bapb=null 
                                    and d_bapb=null 
                                where 
                                    i_sjp='$isj' 
                                    and i_area='$iarea'
                                ");
            }
        }
        $this->db->query("
                            DELETE 
                            FROM 
                                tm_bapbsjp_item 
                            WHERE 
                                i_bapb='$ibapb' 
                                and i_area='$iarea' 
                                and i_sj='$isj'
                            ");
    }

    public function insertdetail($ibapb, $iarea, $isj, $dbapb, $dsj, $eremark, $vsj){
        $this->db->query("
                        DELETE 
                        FROM 
                            tm_bapbsjp_item
                        WHERE 
                            i_bapb='$ibapb' 
                            and i_area='$iarea' 
                            and i_sj='$isj'
                        ");
        $this->db->set(
            array(
                'i_bapb' => $ibapb,
                'i_area' => $iarea,
                'i_sj' => $isj,
                'd_bapb' => $dbapb,
                'd_sj' => $dsj,
                'e_remark' => $eremark,
                'v_sj' => $vsj,
            )
        );
        $this->db->insert('tm_bapbsjp_item');
    }

    public function updatesj($ibapb, $isj, $iarea, $dbapb){
        $this->db->set(
            array(
                'i_bapb' => $ibapb,
                'd_bapb' => $dbapb,
        ));
        $this->db->where('i_sjp', $isj);
        $this->db->where('i_area', $iarea);
        $this->db->update('tm_sjp');
    }

    public function deletedetailekspedisi($ibapb, $iarea, $iekspedisi){
        $this->db->query("
                        DELETE 
                        FROM 
                            tm_bapbsjp_ekspedisi 
                        WHERE 
                            i_bapb='$ibapb' 
                            and i_area='$iarea' 
                            and i_ekspedisi='$iekspedisi'
                        ");
    }

    public function insertdetailekspedisi($ibapb, $iarea, $iekspedisi, $dbapb, $eremark){
        $this->db->query("
                        DELETE 
                        FROM 
                            tm_bapbsjp_ekspedisi 
                        WHERE 
                            i_bapb='$ibapb' 
                            and i_area='$iarea' 
                            and i_ekspedisi='$iekspedisi'
                        ");
        $this->db->set(
            array(
                'i_bapb'        => $ibapb,
                'i_area'        => $iarea,
                'i_ekspedisi'   => $iekspedisi,
                'd_bapb'        => $dbapb,
                'e_remark'      => $eremark,
                ));
        $this->db->insert('tm_bapbsjp_ekspedisi');
    }
}

/* End of file Mmaster.php */
