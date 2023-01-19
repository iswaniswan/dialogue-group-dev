<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekuser($username, $id_company){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username',$username);
        $this->db->where('i_area','00');
        $this->db->where('id_company',$id_company);
        $querty = $this->db->get();
        if ($querty->num_rows()>0) {
            $area = '00';
        }else{
            $area = 'xx';
        }
        return $area;
    }

    public function bacaarea($username, $idcompany){
        $this->db->select(" i_area, e_area_name FROM tr_area WHERE i_area IN (SELECT i_area FROM public.tm_user_area WHERE username = '$username' AND id_company = '$idcompany') ORDER BY i_area", false);
        return $this->db->get()->result();
    }

    public function data($dfrom, $dto, $iarea, $folder){
        if ($iarea=='NA') {
            $sql = "SELECT
                        tr_area.e_area_name,
                        a.i_giro,
                        to_char(a.d_giro, 'dd-mm-yyyy') AS d_giro,
                        to_char(a.d_rv, 'dd-mm-yyyy') AS d_rv,
                        tm_dt.i_dt,
                        to_char(tm_dt.d_dt, 'dd-mm-yyyy') AS d_dt,
                        tr_customer.i_customer ||' - '|| tr_customer.e_customer_name AS customer,
                        a.e_giro_bank,
                        a.v_jumlah,
                        a.v_sisa,
                        a.f_posting, 
                        tm_pelunasan.i_pelunasan,
                        a.i_area,
                        '$folder' AS folder,
                        '$dfrom' AS dfrom,
                        '$dto' AS dto,
                        '$iarea' AS xarea
                    FROM
                        tm_giro a
                    INNER JOIN tr_area ON
                        (a.i_area = tr_area.i_area)
                    INNER JOIN tr_customer ON
                        (a.i_customer = tr_customer.i_customer)
                    LEFT JOIN tm_pelunasan ON
                        (a.i_giro = tm_pelunasan.i_giro
                        AND tm_pelunasan.i_area = a.i_area
                        AND tm_pelunasan.f_pelunasan_cancel = 'f'
                        AND TO_CHAR(a.d_giro, 'yyyy')= TO_CHAR(tm_pelunasan.d_bukti, 'yyyy'))
                    LEFT JOIN tm_dt ON
                        (tm_pelunasan.i_dt = tm_dt.i_dt
                        AND tm_dt.i_area = a.i_area
                        AND tm_dt.f_dt_cancel = 'f'
                        AND TO_CHAR(a.d_giro, 'yyyy')= TO_CHAR(tm_dt.d_dt, 'yyyy'))
                    WHERE
                        ((tm_pelunasan.i_jenis_bayar != '02'
                        AND tm_pelunasan.i_jenis_bayar != '03'
                        AND tm_pelunasan.i_jenis_bayar != '04'
                        AND tm_pelunasan.i_jenis_bayar = '01')
                        OR ((tm_pelunasan.i_jenis_bayar = '01') IS NULL))
                        AND (a.d_giro >= TO_DATE('01-01-2020', 'dd-mm-yyyy')
                        AND a.d_giro <= TO_DATE('11-02-2020', 'dd-mm-yyyy'))
                        AND a.f_giro_cair = 'f'
                        AND a.f_giro_batal = 'f'
                        AND a.f_giro_batal_input = 'f'
                        AND a.f_giro_tolak = 'f'
                    ORDER BY
                        a.i_area,
                        a.d_giro DESC,
                        a.i_giro DESC";
        }else{
            $sql = " SELECT
                        tr_area.e_area_name,
                        a.i_giro,
                        to_char(a.d_giro, 'dd-mm-yyyy') AS d_giro,
                        to_char(a.d_rv, 'dd-mm-yyyy') AS d_rv,
                        tm_dt.i_dt,
                        to_char(tm_dt.d_dt, 'dd-mm-yyyy') AS d_dt,
                        tr_customer.i_customer ||' - '|| tr_customer.e_customer_name AS customer,
                        a.e_giro_bank,
                        a.v_jumlah,
                        a.v_sisa,
                        a.f_posting,
                        tm_pelunasan.i_pelunasan,
                        a.i_area,
                        '$folder' AS folder,
                        '$dfrom' AS dfrom,
                        '$dto' AS dto,
                        '$iarea' AS xarea
                    FROM
                        tm_giro a
                    INNER JOIN tr_area ON
                        (a.i_area = tr_area.i_area)
                    INNER JOIN tr_customer ON
                        (a.i_customer = tr_customer.i_customer)
                    LEFT JOIN tm_pelunasan ON
                        (a.i_giro = tm_pelunasan.i_giro
                        AND tm_pelunasan.i_area = a.i_area
                        AND tm_pelunasan.f_pelunasan_cancel = 'f'
                        AND TO_CHAR(a.d_giro, 'yyyy')= TO_CHAR(tm_pelunasan.d_bukti, 'yyyy'))
                    LEFT JOIN tm_dt ON
                        (tm_pelunasan.i_dt = tm_dt.i_dt
                        AND tm_dt.i_area = a.i_area
                        AND tm_dt.f_dt_cancel = 'f'
                        AND TO_CHAR(a.d_giro, 'yyyy')= TO_CHAR(tm_dt.d_dt, 'yyyy'))
                    WHERE
                        ((tm_pelunasan.i_jenis_bayar != '02'
                        AND tm_pelunasan.i_jenis_bayar != '03'
                        AND tm_pelunasan.i_jenis_bayar != '04'
                        AND tm_pelunasan.i_jenis_bayar = '01')
                        OR ((tm_pelunasan.i_jenis_bayar = '01') IS NULL))
                        AND a.i_area = '02'
                        AND (a.d_giro >= TO_DATE('01-01-2020', 'dd-mm-yyyy')
                        AND a.d_giro <= TO_DATE('11-02-2020', 'dd-mm-yyyy'))
                        AND a.f_giro_cair = 'f'
                        AND a.f_giro_batal = 'f'
                        AND a.f_giro_batal_input = 'f'
                        AND a.f_giro_tolak = 'f'
                    ORDER BY
                        a.d_giro DESC,
                        a.i_giro DESC ";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("$sql", false);
        $datatables->add('action', function ($data) {
            $folder     = $data['folder'];
            $iarea      = $data['i_area'];
            $xarea      = $data['xarea'];
            $igiro      = $data['i_giro'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $fposting   = $data['f_posting'];
            $ipelunasan = $data['i_pelunasan'];
            $idt        = $data['i_dt'];
            $data       = '';
            if ($fposting=='f') {
                if ($ipelunasan=='') {
                    $ipelunasan = 0; 
                }else{
                    $ipelunasan = $ipelunasan;
                }
                if ($idt=='') {
                    $idt = 0; 
                }else{
                    $idt = $idt;
                }
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$igiro/$iarea/$dfrom/$dto/$ipelunasan/$idt/$xarea\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }else{
                $data      .= "";
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('i_area');
        $datatables->hide('xarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_posting');
        $datatables->hide('i_pelunasan');
        return $datatables->generate();
    }

    public function baca($igiro,$iarea){
        $this->db->select("
                *,
                b.i_coa,
                b.e_bank_name
            FROM
                tm_giro a
            INNER JOIN tr_area ON
                (a.i_area = tr_area.i_area)
            INNER JOIN tr_customer ON
                (a.i_customer = tr_customer.i_customer)
            LEFT JOIN tr_bank b ON
                (a.i_bank = b.i_bank)
            WHERE
                a.i_giro = '$igiro'
                AND a.i_area = '$iarea'"
        , false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($iarea,$ipl,$idt){
        $this->db->select("
                a.*,
                b.v_nota_netto AS v_nota
            FROM
                tm_pelunasan_item a
            INNER JOIN tm_nota b ON
                (a.i_nota = b.i_nota
                AND a.i_area = b.i_area)
            WHERE
                a.i_pelunasan = '$ipl'
                AND a.i_area = '$iarea'
                AND a.i_dt = '$idt'
            ORDER BY
                a.i_pelunasan,
                a.i_area
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacabank(){
        return $this->db->order_by('i_bank','ASC')->get('tr_bank')->result();
    }

    public function area(){
      return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_customer,
                e_customer_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND (UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(a.e_customer_name) LIKE '%$cari%')
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function getdetailcustomer($iarea, $icustomer){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_customer a
            LEFT JOIN tr_customer_area d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function update($igiro,$iarea,$dgirocair,$fgirocair,$ibank){
        $dentry = current_datetime();        
        $this->db->set(
            array(
                'd_giro_cair' => $dgirocair,
                'f_giro_cair' => $fgirocair,
                'i_bank'      => $ibank,
                'd_entry_cair'=> $dentry
            )
        );
        $this->db->where('i_giro',$igiro);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_giro');
    }
}

/* End of file Mmaster.php */
