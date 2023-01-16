<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getsupplier($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                tr_supplier
            WHERE
                (UPPER(i_supplier) LIKE '%$cari%'
                OR UPPER(e_supplier_name) LIKE '%$cari%')
            ORDER BY
                i_supplier", 
        FALSE);
    }

    public function data($dfrom, $dto, $isupplier, $folder, $i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 
                a.i_kuk,
                to_char(a.d_kuk, 'dd-mm-yyyy') AS d_kuk,
                i_pelunasanap,
                to_char(c.d_bukti, 'dd-mm-yyyy') AS d_bukti,
                a.e_bank_name,
                a.i_supplier,
                '('||a.i_supplier||') - '||b.e_supplier_name AS supplier,
                a.e_remark,
                a.v_jumlah,
                a.v_sisa,
                a.f_kuk_cancel AS status,
                a.f_close,
                a.n_kuk_year,
                '$folder' AS folder,
                '$i_menu' AS i_menu,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_kuk a
            LEFT JOIN tr_supplier b ON
                (a.i_supplier = b.i_supplier)
            LEFT JOIN tm_pelunasanap c ON
                (a.i_kuk = c.i_giro
                AND a.d_kuk = c.d_giro
                AND c.f_pelunasanap_cancel = 'f'
                AND ((c.i_jenis_bayar != '02'
                AND c.i_jenis_bayar != '01'
                AND c.i_jenis_bayar != '04'
                AND c.i_jenis_bayar = '03')
                OR ((c.i_jenis_bayar = '03') IS NULL)))
            WHERE
                a.i_supplier = '$isupplier'
                AND (a.d_kuk >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_kuk <= to_date('$dto', 'dd-mm-yyyy'))
            ORDER BY
                a.i_kuk,
                a.i_supplier"
        ,false);    
        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->edit('v_jumlah', function($data){
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_sisa', function($data){
            return number_format($data['v_sisa']);
        });

        $datatables->add('action', function ($data) {
            $id             = $data['i_kuk'];
            $isupplier      = $data['i_supplier'];
            $ipelunasanap   = $data['i_pelunasanap'];
            $folder         = $data['folder'];
            $i_menu         = $data['i_menu'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $nkukyear       = $data['n_kuk_year'];
            $fclose         = $data['f_close'];
            $status         = $data['status'];
            $data           = '';
            $kuk            = str_replace('/','|',$id);
            if($fclose == 'f'){
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$kuk/$nkukyear/$isupplier/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
                if(check_role($i_menu,4) && ($status != 't') && ($ipelunasanap=='')){
                  $data    .= "<a href=\"#\" onclick='hapus(\"$kuk\",\"$nkukyear\",\"$isupplier\"); return false;'><i class='fa fa-trash'></i></a>";
                }
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_menu');
        $datatables->hide('f_close');
        $datatables->hide('i_supplier');
        $datatables->hide('n_kuk_year');

        return $datatables->generate();
    }

    public function cancel($ikuk,$nkukyear,$isupplier){
  		return $this->db->query("
            UPDATE
                tm_kuk
            SET
                f_kuk_cancel = 't'
            WHERE
                i_kuk = '$ikuk'
                AND n_kuk_year = $nkukyear
        ");
    }

    public function baca($ikuk, $tahun){
		$query = $this->db->query("	
            SELECT
                *
            FROM
                tm_kuk a
            LEFT JOIN tr_supplier c ON
                (a.i_supplier = c.i_supplier)
            WHERE
                a.i_kuk = '$ikuk'
                AND a.n_kuk_year ='$tahun'",false);
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

    public function update($ikuk,$dkuk,$tahun,$ebankname,$isupplier,$eremark,$vjumlah,$vsisa){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_kuk'         => $ikuk,
                'i_supplier'    => $isupplier,
                'd_kuk'         => $dkuk,
                'd_entry'       => $dentry,
                'e_bank_name'   => $ebankname,
                'e_remark'      => $eremark,
                'n_kuk_year'    => $tahun,
                'v_jumlah'      => $vjumlah,
                'v_sisa'        => $vsisa,
                'f_kuk_cancel'  =>'f'
            )
        );
        $this->db->where('i_kuk',$ikuk);
        $this->db->where('n_kuk_year',$tahun);
        $this->db->update('tm_kuk');
    }
}
