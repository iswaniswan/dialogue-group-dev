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
            ORDER BY
                i_area
        ", FALSE)->result();
    }

    public function data($dfrom, $dto, $iarea, $folder, $i_menu){
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
            SELECT
                DISTINCT a.i_kum,
                g.i_dt,
                g.d_dt,
                a.d_kum,
                a.e_bank_name,
                c.e_customer_name,
                e.e_customer_setor,
                a.v_jumlah,
                a.f_kum_cancel,
                a.i_customer,
                a.e_remark,
                a.v_sisa,
                a.f_close,
                a.n_kum_year,
                d.i_area,
                f.i_giro,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu
            FROM
                tm_kum a
            LEFT JOIN tr_customer c ON
                (a.i_customer = c.i_customer)
            LEFT JOIN tr_area d ON
                (a.i_area = d.i_area)
            LEFT JOIN tr_customer_owner e ON
                (a.i_customer = e.i_customer)
            LEFT JOIN tm_pelunasan f ON
                (a.i_kum = f.i_giro
                AND a.d_kum = f.d_giro
                AND f.f_pelunasan_cancel = 'f'
                AND f.f_giro_tolak = 'f'
                AND f.f_giro_batal = 'f'
                AND a.i_area = f.i_area)
            LEFT JOIN tm_dt g ON
                (f.i_dt = g.i_dt
                AND f.d_dt = g.d_dt
                AND f.i_area = g.i_area
                AND f.f_pelunasan_cancel = 'f'
                AND f.f_giro_tolak = 'f'
                AND f.f_giro_batal = 'f'
                AND g.i_area = a.i_area)
            WHERE
                ((f.i_jenis_bayar != '02'
                AND f.i_jenis_bayar != '01'
                AND f.i_jenis_bayar != '04'
                AND f.i_jenis_bayar = '03')
                OR ((f.i_jenis_bayar = '03') IS NULL))
                AND a.i_area = '$iarea'
                AND (a.d_kum >= '$dfrom'
                AND a.d_kum <= '$dto')
                AND a.f_kum_cancel = 'f'
            ORDER BY
                a.d_kum DESC"
        ,false);    
        $datatables->edit('i_kum', function($data){
            if($data['f_kum_cancel']=='t'){
                return '<h1>'.($data['i_kum']).'</h1>';
            }else{
                return ($data['i_kum']);
            }
        });

        $datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->edit('d_dt', function($data){
            if($data['d_dt']==''){
              return '';
            }else{
              return date("d-m-Y", strtotime($data['d_dt']));
            }
        });

        $datatables->edit('d_kum', function($data){
            return date("d-m-Y", strtotime($data['d_kum']));
        });

        $datatables->edit('i_customer', function($data){
            if($data['i_customer']==''){
                return 'Belum Ada';
            }
                return ($data['i_customer']);
        });

        $datatables->edit('e_customer_name', function($data){
            return '('.($data['i_customer']).')'.($data['e_customer_name']);
        });

        $datatables->edit('e_customer_setor', function($data){
            return trim(($data['e_customer_setor']).'/'.trim(($data['e_remark'])));
        });

        $datatables->add('action', function ($data) {
            $ikum           = $data['i_kum'];
            $dkum           = $data['d_kum'];
            $folder         = $data['folder'];
            $iarea          = $data['i_area'];
            $i_menu         = $data['i_menu'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $nkumyear       = $data['n_kum_year'];
            $fclose         = $data['f_close'];
            $vjumlah        = $data['v_jumlah'];
            $vsisa          = $data['v_sisa'];
            $data           = '';
            $kum            = str_replace('/','|',$ikum);
            if(($fclose == 'f') && ($vsisa>0)){
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$kum/$nkumyear/$iarea/$dfrom/$dto/$dkum\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                if(check_role($i_menu,4) && ($fclose == 'f') && ($vsisa==$vjumlah)){
                  $data      .= "<a href=\"#\" onclick='hapus(\"$kum\",\"$nkumyear\",\"$iarea\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_kum_cancel');
        $datatables->hide('i_customer');
        $datatables->hide('i_menu');
        $datatables->hide('e_remark');
        $datatables->hide('f_close');
        $datatables->hide('n_kum_year');
        $datatables->hide('i_area');
        $datatables->hide('i_giro');

        return $datatables->generate();
    }

    public function cancel($ikum,$nkumyear,$iarea){
  		return $this->db->query("
            UPDATE
                tm_kum
            SET
                f_kum_cancel = 't'
            WHERE
                i_kum = '$ikum'
                AND n_kum_year = $nkumyear
                AND i_area = '$iarea'
        ");
    }

    public function baca($iarea, $ikum, $tahun){
		$query = $this->db->query("	
            SELECT
                *
            FROM
                tm_kum a
            LEFT JOIN tr_customer_salesman e ON
                (a.i_customer = e.i_customer
                AND a.i_area = e.i_area
                AND a.i_salesman = e.i_salesman)
            LEFT JOIN tr_customer c ON
                (a.i_customer = c.i_customer)
            LEFT JOIN tr_area d ON
                (a.i_area = d.i_area)
            LEFT JOIN tr_bank b ON
                (a.i_bank = b.i_bank)
            WHERE
                a.i_kum = '$ikum'
                AND a.i_area = '$iarea'
                AND a.n_kum_year = '$tahun'",false);
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    public function bacaareauser($idcompany,$username){
        return $this->db->query("
            SELECT
                i_area
            FROM
                public.tm_user_area
            WHERE
                id_company = '$idcompany'
                AND username = '$username'
                AND i_area = '00'    
        ", FALSE)->row('i_area');
    }

    public function getarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacabank(){
      return $this->db->order_by('i_bank','ASC')->get('tr_bank')->result();
    }

    public function getcustomer($cari, $iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT ON
                (a.i_customer,
                c.i_salesman) a.i_customer,
                a.e_customer_name
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_salesman c ON
                (a.i_customer = c.i_customer
                AND a.i_area = c.i_area)
            LEFT JOIN tr_customer_owner d ON
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
                DISTINCT ON
                (a.i_customer,
                c.i_salesman) a.i_customer,
                a.e_customer_name,
                b.i_customer_groupar,
                c.i_salesman,
                c.e_salesman_name,
                b.i_customer_groupar,
                d.e_customer_setor
            FROM
                tr_customer a
            LEFT JOIN tr_customer_groupar b ON
                (a.i_customer = b.i_customer)
            LEFT JOIN tr_customer_salesman c ON
                (a.i_customer = c.i_customer
                AND a.i_area = c.i_area)
            LEFT JOIN tr_customer_owner d ON
                (a.i_customer = d.i_customer)
            WHERE
                a.i_area = '$iarea'
                AND a.i_customer = '$icustomer'
            ORDER BY
                a.i_customer", 
        FALSE);
    }

    public function update($ikum,$xkum,$dkum,$xtahun,$tahun,$ebankname,$iarea,$icustomer,$icustomergroupar,$isalesman,$eremark,$vjumlah,$vsisa,$iareaasal){
        $fkumcancel='f';
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_area'                    => $iarea,
                'i_kum'                     => $ikum,
                'i_customer'                => $icustomer,
                'i_customer_groupar'        => $icustomergroupar,
                'i_salesman'                => $isalesman,
                'd_kum'                     => $dkum,
                'd_update'                  => $dentry,
                'e_bank_name'               => $ebankname,
                'e_remark'                  => $eremark,
                'n_kum_year'                => $tahun,
                'v_jumlah'                  => $vjumlah,
                'v_sisa'                    => $vsisa,
                'f_kum_cancel'              => 'f'
            )
        );
        $this->db->where('i_kum',$xkum);
        $this->db->where('i_area',$iareaasal);
        $this->db->where('n_kum_year',$xtahun);
        $this->db->update('tm_kum');
    }
}
