<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username, $idcompany){
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

//    public function data($dfrom, $dto, $iarea, $folder, $iperiode, $title){
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
        if($iarea == "NA"){
          $datatables->query("
            SELECT	b.e_area_name, a.i_alokasi, a.d_alokasi, a.i_kn, a.i_customer, c.e_customer_name, a.v_jumlah, a.f_alokasi_cancel,
                to_char(a.d_alokasi, 'yyyymm') AS dperiode,
                '$folder' AS folder,
                '$iarea' AS iarea,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iperiode' AS iperiode,
                '$title' AS title
				    from tm_alokasiknr a, tr_area b, tr_customer c
				    where
				    a.i_area=b.i_area and a.i_customer=c.i_customer and
				    a.d_alokasi >= '$dfrom' AND
				    a.d_alokasi <= '$dto'
				    ORDER BY a.i_alokasi,a.d_alokasi,a.i_area ",false);
		    }else{    		
			    $datatables->query("
            SELECT	b.e_area_name, a.i_alokasi, a.d_alokasi, a.i_kn, a.i_customer, c.e_customer_name, a.v_jumlah, a.f_alokasi_cancel,
                to_char(a.d_alokasi, 'yyyymm') AS dperiode,
                '$folder' AS folder,
                '$iarea' AS iarea,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$iperiode' AS iperiode,
                '$title' AS title
				    from tm_alokasiknr a, tr_area b, tr_customer c
				    where
				    a.i_area=b.i_area and a.i_customer=c.i_customer and a.i_area='$iarea' and
				    a.d_alokasi >= '$dfrom' AND
				    a.d_alokasi <= '$dto'
				    ORDER BY a.i_alokasi,a.d_alokasi,a.i_area ",false);
		    }

        $datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });
        
        $datatables->add('action', function ($data) {
            $ialokasi   = $data['i_alokasi'];
            $folder     = $data['folder'];
            $iarea      = $data['iarea'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $dalokasi   = $data['d_alokasi'];
            $dperiode   = $data['dperiode'];
            $iperiode   = $data['iperiode'];
            $title      = $data['title'];
            $ikn        = $data['i_kn'];
            $fcancel    = $data['f_alokasi_cancel'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/proses/$ialokasi/$ikn/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            if ($fcancel!='t' && $iperiode<=$dperiode) {
                $data  .= "<a href=\"#\" onclick='hapus(\"$ialokasi\",\"$ikn\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>";
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('iarea');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_alokasi_cancel');
        $datatables->hide('dperiode');
        $datatables->hide('iperiode');
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
        if($iarea == "NA"){
            return $this->db->query("
            SELECT	sum(a.v_jumlah) as jml
				    from tm_alokasiknr a, tr_area b, tr_customer c
				    where
				    a.i_area=b.i_area and a.i_customer=c.i_customer and
				    a.d_alokasi >= '$dfrom' AND
				    a.d_alokasi <= '$dto'
            ", false);
        }else{
            return $this->db->query("
            SELECT	sum(a.v_jumlah) as jml
				    from tm_alokasiknr a, tr_area b, tr_customer c
				    where
				    a.i_area=b.i_area and a.i_customer=c.i_customer and a.i_area='$iarea' and
				    a.d_alokasi >= '$dfrom' AND
				    a.d_alokasi <= '$dto'
            ", false);
        }
    }

    public function cekedit($ialokasi,$ikn,$iarea){
        $this->db->select('i_alokasi');
        $this->db->from('tm_alokasiknr');
        $this->db->where('i_alokasi', $ikn);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_kn', $ikn);
        $this->db->where('f_alokasi_cancel', 'f');
        return $this->db->get();
    }

    public function baca($ialokasi,$iarea,$ikn){
        $this->db->select("
                tm_alokasiknr.i_alokasi, tm_alokasiknr.d_alokasi, tm_alokasiknr.i_kn, tm_alokasiknr.i_area, tm_alokasiknr.v_jumlah, tm_alokasiknr.i_customer,
                tr_customer.e_customer_name, tr_customer.e_customer_city, tr_customer.e_customer_address,
                tr_area.e_area_name,
                tm_kn.d_kn, tm_kn.v_sisa
            FROM
                tm_alokasiknr
            INNER JOIN tr_customer ON
                (tm_alokasiknr.i_customer = tr_customer.i_customer)
            INNER JOIN tr_area ON
                (tm_alokasiknr.i_area = tr_area.i_area)
            INNER JOIN tm_kn ON
                (tm_alokasiknr.i_area = tm_kn.i_area and tm_alokasiknr.i_kn = tm_kn.i_kn)
            WHERE
                tm_alokasiknr.i_alokasi = '$ialokasi'
                AND tm_alokasiknr.i_area = '$iarea'
                AND tm_alokasiknr.i_kn = '$ikn'"
        , false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($ialokasi,$iarea,$ikn){
        $this->db->select("
                a.*,
                b.v_sisa,
                b.d_jatuh_tempo
            FROM
                tm_alokasiknr_item a
            INNER JOIN tm_nota b ON
                (b.i_nota = a.i_nota)
            WHERE
                a.i_alokasi = '$ialokasi'
                AND a.i_area = '$iarea'
                AND a.i_kn = '$ikn'
            ORDER BY
                a.n_item_no", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function getnota($cari, $iarea){
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
                    SUBSTRING(i_customer, 1, 2)= '$iarea')) )
                AND (UPPER(a.i_nota) LIKE '%$cari%'
                OR a.i_nota_old LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%')
            GROUP BY
                a.i_nota,
                a.i_area,
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

    public function getdetailnota($inota,$iarea){
        return $this->db->query("
            SELECT
                a.i_nota,
                a.i_area,
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
                    SUBSTRING(i_customer, 1, 2)= '$iarea')) )
                AND a.i_nota = '$inota'
            GROUP BY
                a.i_nota,
                a.i_area,
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

    public function deletedetail($idt,$ddt,$iarea,$vjumlah,$xddt){
        $this->db->set(
            array(
                'v_jumlah'  => $vjumlah
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_dt');

        $this->db->where('i_dt',$idt);
        $this->db->where('d_dt',$xddt);
        /*$this->db->where('i_nota',$inota);*/
        $this->db->where('i_area',$iarea);
        $this->db->delete('tm_dt_item');
    }

    public function updateheader($idt,$iarea,$ddt,$vjumlah,$fsisa){
        $this->db->where('i_dt',$idt);
        $this->db->where('i_area',$iarea);
        $this->db->delete('tm_dt');
        
        $this->db->set(
            array(
                'i_dt'      => $idt,
                'i_area'    => $iarea,
                'd_dt'      => $ddt,
                'v_jumlah'  => $vjumlah,
                'f_sisa'    => $fsisa
            )
        );
        $this->db->insert('tm_dt');
    }

    public function insertdetail($idt,$ddt,$inota,$iarea,$dnota,$icustomer,$vsisa,$vjumlah,$i){
        $this->db->set(
            array(
                'i_dt'       => $idt,
                'd_dt'       => $ddt,
                'i_nota'     => $inota,
                'i_area'     => $iarea,
                'd_nota'     => $dnota,
                'i_customer' => $icustomer,
                'v_sisa'     => $vsisa,
                'v_jumlah'   => $vjumlah,
                'n_item_no'  => $i
            )
        );
        $this->db->insert('tm_dt_item');
    }

    public function cancel($idt, $iarea){
        $this->db->set(
            array(
                'f_dt_cancel'  => 't'
            )
        );
        $this->db->where('i_dt',$idt);
        $this->db->where('i_area',$iarea);
        return $this->db->update('tm_dt');
    }
}

/* End of file Mmaster.php */
