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

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_bapb AS id,
                to_char(d_bapb, 'dd-mm-yyyy') AS d_bapb, 
                UPPER(e_area_name) AS e_area_name,
                a.i_area,
                i_customer,
                f_bapb_cancel AS status,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$i_menu' AS i_menu, 
                '$folder' AS folder
            FROM
                tm_bapb a,
                tr_area b
            WHERE
                a.i_area = b.i_area
                AND substr(a.i_bapb, 10, 2)= '$iarea'
                AND a.d_bapb >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_bapb <= to_date('$dto', 'dd-mm-yyyy')
            ORDER BY
                a.i_bapb DESC"
        , FALSE);

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
            UPDATE tm_bapb 
            SET f_bapb_cancel='t' 
            WHERE i_bapb='$id' 
            AND i_area='$iarea'");
    }

    public function baca($id,$iarea){
        $query = $this->db->query("
            SELECT
                tm_bapb.i_bapb,
                tm_bapb.i_dkb_kirim,
                tm_bapb.i_area,
                to_char(tm_bapb.d_bapb, 'dd-mm-yyyy') AS d_bapb,
                to_char(tm_bapb.d_bapb, 'mm') AS bl,
                tm_bapb.i_bapb_old,
                tm_bapb.f_bapb_cancel,
                tm_bapb.n_bal,
                tr_customer.e_customer_name,
                tm_bapb.i_customer,
                tr_area.e_area_name,
                tr_dkb_kirim.e_dkb_kirim,
                tm_bapb.v_bapb,
                tm_bapb.v_kirim
            FROM
                tm_bapb
            INNER JOIN tr_area ON
                (tm_bapb.i_area = tr_area.i_area)
            LEFT JOIN tr_customer ON
                (tm_bapb.i_customer = tr_customer.i_customer)
            INNER JOIN tr_dkb_kirim ON
                (tm_bapb.i_dkb_kirim = tr_dkb_kirim.i_dkb_kirim)
            WHERE
                tm_bapb.i_bapb = '$id'
                AND tm_bapb.i_area = '$iarea'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($id,$iarea){
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_bapb_item
            WHERE
                i_bapb = '$id'
                AND i_area = '$iarea'
            ORDER BY
                i_bapb
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function customertodetail($isj,$dsj,$iarea){
        return $this->db->query(" 
            SELECT
                b.i_customer,
                b.e_customer_name
            FROM
                tm_nota a
            INNER JOIN tr_customer b ON
                b.i_customer = a.i_customer
            WHERE
                a.i_sj = '$isj'
                AND a.d_sj = '$dsj'
                AND a.i_area = '$iarea'
        ", FALSE);
    }

    public function bacadetailx($id,$iarea){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_ekspedisi
            FROM
                tm_bapb_ekspedisi a,
                tr_ekspedisi b
            WHERE
                a.i_bapb = '$id'
                AND a.i_area = '$iarea'
                AND a.i_ekspedisi = b.i_ekspedisi
            ORDER BY
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

    public function getpelanggan($iarea, $cari){
        $cari = str_replace("'", "", $cari);
        return  $this->db->query("
            SELECT
                i_customer,
                e_customer_name
            FROM
                tr_customer
            WHERE
                i_area = '$iarea'
                AND (i_customer LIKE '%$cari%'
                OR UPPER(e_customer_name) LIKE '%$cari%') "
            );
    }

    public function bacasj($cari,$iarea,$icustomer){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_sj
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_area = '$iarea'
                AND (SUBSTRING(a.i_sj, 9, 2) IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'))
                AND a.i_bapb ISNULL
                AND a.i_customer = b.i_customer
                AND a.i_customer = '$icustomer'
                AND (UPPER(a.i_sj) LIKE '%$cari%')
            ORDER BY
                a.i_sj", FALSE);
    }

    public function bacasjx($iarea,$icustomer,$isj){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                a.*,
                to_char(a.d_sj,'dd-mm-yyyy') AS dsj,
                b.i_customer, 
                b.e_customer_name
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_area = '$iarea'
                AND (SUBSTRING(a.i_sj, 9, 2) IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'))
                AND a.i_bapb ISNULL
                AND a.i_customer = b.i_customer
                AND a.i_customer = '$icustomer'
                AND a.i_sj = '$isj'
            ORDER BY
                a.i_sj", FALSE);
    }

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

    public function deleteheader($ibapb, $iarea){
        $this->db->query("
            DELETE 
            FROM tm_bapb 
            WHERE i_bapb='$ibapb' 
            and i_area='$iarea'");
    }


    public function insertheader($ibapb, $dbapb, $iarea, $idkbkirim, $icustomer, $nbal, $ibapbold, $vbapb, $vkirim){
        $this->db->set(
            array(
                'i_bapb'      => $ibapb,
                'd_bapb'      => $dbapb,
                'i_dkb_kirim' => $idkbkirim,
                'i_area'      => $iarea,
                'i_customer'  => $icustomer,
                'n_bal'       => $nbal,
                'i_bapb_old'  => $ibapbold,
                'v_bapb'      => $vbapb,
                'v_kirim'     => $vkirim
            )
        );        
        $this->db->insert('tm_bapb');
    }

    public function deletedetail($ibapb, $iarea, $isj){
        $this->db->select(" v_sj from tm_bapb_item where i_bapb = '$ibapb' and i_area='$iarea' and i_sj='$isj'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $this->db->query("update tm_bapb set v_bapb=v_bapb WHERE i_bapb='$ibapb' and i_area='$iarea'");
                $this->db->query("update tm_nota set i_bapb=null and d_bapb=null WHERE i_sj='$isj' and i_area='$iarea'");
            }
        }
        return $this->db->query("DELETE FROM tm_bapb_item WHERE i_bapb='$ibapb' and i_area='$iarea' and i_sj='$isj'");
    }

    public function deletedetailx($ibapb,$iarea,$iekspedisi){
        return $this->db->query("
            DELETE 
            FROM tm_bapb_ekspedisi 
            WHERE i_bapb='$ibapb' 
            AND i_area='$iarea' 
            AND i_ekspedisi='$iekspedisi'");
    }

    public function insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$i,$vsj){
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_sj', $isj);
        $this->db->delete('tm_bapb_item');

        $this->db->set(
            array(
                'i_bapb'    => $ibapb,
                'i_area'    => $iarea,
                'i_sj'      => $isj,
                'd_bapb'    => $dbapb,
                'd_sj'      => $dsj,
                'e_remark'  => $eremark,
                'n_item_no' => $i,
                'v_sj'      => $vsj
            )
        );
        $this->db->insert('tm_bapb_item');
    }

    public function updatesj($ibapb,$isj,$iarea,$dbapb){
        $this->db->set(
            array(
                'i_bapb' => $ibapb, 
                'd_bapb' => $dbapb
            )
        );
        $this->db->where('i_sj',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_nota');
    }

    public function deletedetailekspedisi($ibapb,$iarea,$iekspedisi){
        $this->db->query("
            DELETE 
            FROM tm_bapb_ekspedisi 
            WHERE i_bapb='$ibapb' 
            AND i_area='$iarea' 
            AND i_ekspedisi='$iekspedisi'");
    }

    public function insertdetailekspedisi($ibapb,$iarea,$iekspedisi,$dbapb,$eremark){
        $this->db->where('i_bapb', $ibapb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_ekspedisi', $iekspedisi);
        $this->db->delete('tm_bapb_ekspedisi');
        $this->db->set(
            array(
                'i_bapb'      => $ibapb,
                'i_area'      => $iarea,
                'i_ekspedisi' => $iekspedisi,
                'd_bapb'      => $dbapb,
                'e_remark'    => $eremark
            )
        );
        $this->db->insert('tm_bapb_ekspedisi');
    }
}

/* End of file Mmaster.php */
