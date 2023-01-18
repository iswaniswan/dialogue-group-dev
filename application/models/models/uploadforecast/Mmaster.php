<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_1supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_customer, a.periode, b.e_customer_name, a.i_product, a.i_color, 
                            d.e_product_motifname, c.e_color_name, a.n_quantity,'$i_menu' as i_menu 
                            from tm_forecast a 
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_color c on (a.i_color = c.i_color)
                            inner join tr_product_motif d on (a.i_product = d.i_product and a.i_color::character = d.i_color)", false);

        
        $datatables->add('action', function ($data) {
            $i_customer         = trim($data['i_customer']);
            $i_periode          = trim($data['periode']);
            $i_product          = trim($data['i_product']);
            $i_color            = trim($data['i_color']);
            $i_menu             = $data['i_menu'];
            // $f_pp_cancel  = $data['f_pp_cancel'];
            $data         = '';

            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"uploadforecast/cform/view/$i_customer/$i_periode/$i_product/$i_color/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"uploadforecast/cform/edit/$i_customer/$i_periode/$i_product/$i_color/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            // if(check_role($i_menu, 1)){
            //   $data .= "<a href=\"#\" onclick='show(\"pembelianpp/cform/approve/$i_pp/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            // }
            // if ($f_pp_cancel!='t') {
            //       $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_pp\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
			return $data;
        });
        $datatables->hide('i_menu');
        // $datatables->hide('i_customer');
        $datatables->hide('periode');
        // $datatables->hide('i_product');
        $datatables->hide('i_color');

        return $datatables->generate();
    }
    
    function cek_data($i_customer, $i_periode, $i_product, $i_color){
        $this->db->select(" a.*, b.e_customer_name, c.e_color_name, d.e_product_motifname
                            from tm_forecast a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_color c on (a.i_color = c.i_color)
                            inner join tr_product_motif d on (a.i_product = d.i_product and a.i_color::character = d.i_color)
                            where a.i_customer = '$i_customer' 
                            and a.periode = '$i_periode'
                            and a.i_product = '$i_product'
                            and a.i_color = '$i_color'",false);
		// $this->db->select('a.*, b.e_customer_name, c.e_color_name, d.e_product_motifname');
        // $this->db->from('tm_forecast a');
        // $this->db->join('tr_customer b','a.i_customer=b.i_customer');
        // $this->db->join('tr_color c','a.i_customer=b.i_customer');
        // $this->db->join('tr_customer d','a.i_customer=b.i_customer');
        // $this->db->where('a.i_pp', $id);
        return $this->db->get();

        // $this->db->select(" a.*, b.e_customer_name, c.e_color_name, d.e_product_motifname
        //                 from tm_forecast a
        //                 inner join tr_customer b on (a.i_customer=b.i_customer)
        //                 inner join tr_color c on (a.i_color = c.i_color)
        //                 inner join tr_product_motif d on (a.i_product = d.i_product and a.i_color::character = d.i_color)
        //                 where a.i_customer = '$i_customer' 
        //                 and a.i_periode = '$i_periode'
        //                 and a.i_product = '$i_product'
        //                 and a.i_color = '$i_color'",false);
        }

        public function updateheader($periode, $icustomer, $icolor, $iproduct, $qty){
            $data = array(
                'n_quantity' => $qty
        );

        $this->db->where('i_customer', $icustomer);
        $this->db->where('periode', $periode);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_color', $icolor);
        $this->db->update('tm_forecast', $data);
        }

        public function updatetransfer($periode, $customer, $icolor, $iprod){
            $data = array(
                'i_color' => $icolor
        );

        $this->db->where('i_customer', $customer);
        $this->db->where('periode', $periode);
        $this->db->where('i_product', $iprod);
        $this->db->update('tm_forecast', $data);
        }

    public function bacadistributor(){
        return $this->db->order_by('e_customer_name','ASC')->get('tr_customer')->result();
    }

    public function cekforecast($customer, $periode, $iprod, $icolor){
        $this->db->select(" a.*, b.e_customer_name, c.e_color_name, d.e_product_motifname
                            from tm_forecast a
                            inner join tr_customer b on (a.i_customer=b.i_customer)
                            inner join tr_color c on (a.i_color = c.i_color)
                            inner join tr_product_motif d on (a.i_product = d.i_product and a.i_color::character = d.i_color)
                            where a.i_customer = '$customer' 
                            and a.periode = '$periode'
                            and a.i_product = '$iprod'
                            and a.i_color = '$icolor'",false);
                        return $this->db->get();
    }

    public function bacadistributorbyid($customer){
        $this->db->select('');
        $this->db->where('i_customer', $customer);
        $query = $this->db->get('tr_customer');
        return $query->row();
    }

    public function deleteforecast($customer,$periode) {
         $this->db->query("
                DELETE FROM 
                    tm_forecast
                WHERE
                    i_customer = '$customer'
                    AND periode = '$periode'
            ",false);
    }

    public function runningnumber($iarea,$thbl){
        $th = substr($thbl,0,2);
        $this->db->select(" 
                max(substr(i_stockopname,9,2)) AS max 
            FROM tm_stockopname
            WHERE substr(i_stockopname,4,2)='$th' 
            AND i_area='$iarea'
        ", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir = $row->max;
            }
            $noso = $terakhir+1;
            settype($noso,"string");
            $a = strlen($noso);
            while($a<2){
                $noso="0".$noso;
                $a=strlen($noso);
            }
            $noso  ="SO-".$thbl."-".$noso;
            return $noso;
        }else{
            $noso  ="01";
            $noso  ="SO-".$thbl."-".$noso;
            return $noso;
        }
    }

    public function insertheader($istockopname, $dstockopname, $istore, $istorelocation, $iarea){
        $dentry = current_datetime();
        $this->db->set(
            array(
                'i_stockopname'     => $istockopname,
                'd_stockopname'     => $dstockopname,
                'i_store'           => $istore,
                'i_store_location'  => $istorelocation,
                'i_area'            => $iarea,
                'd_entry'           => $dentry
            )
        );
        $this->db->insert('tm_stockopname');
        /*update ke mutasi header*/
        $emutasiperiode='20'.substr($istockopname,3,4);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_header
            WHERE
                i_store = '$istore'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($query->num_rows()>0){
            $this->db->query("   
                UPDATE
                    tm_mutasi_header
                SET
                    i_stockopname_akhir = '$istockopname'
                WHERE
                    i_store = '$istore'
                    AND e_mutasi_periode = '$emutasiperiode'    
            ",false);
        }else{
            $this->db->query("
                INSERT
                    INTO
                    tm_mutasi_header
                VALUES ('$istore',
                '$emutasiperiode',
                NULL,
                '$istockopname',
                '$istorelocation')
            ",false);
        }
        $query = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi_header
            WHERE
                i_store = '$istore'
                AND e_mutasi_periode = '$perdpn'
        ",false);
        if($query->num_rows()>0){
            $this->db->query("
                UPDATE
                    tm_mutasi_header
                SET
                    i_stockopname_awal = '$istockopname'
                WHERE
                    i_store = '$istore'
                    AND e_mutasi_periode = '$perdpn'
            ",false);
        }else{
            $this->db->query("
                INSERT
                    INTO
                    tm_mutasi_header
                VALUES ('$istore',
                '$perdpn',
                '$istockopname',
                NULL,
                '$istorelocation')
            ",false);
        }
        /*end update ke mutasi header*/
    }

    public function eproductname($iproduct){
        $this->db->select('e_product_name');
        $this->db->from('tr_product');
        $this->db->where('i_product',$iproduct);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row = $query->row();
            $eproductname = $row->e_product_name;
        }else{
            $eproductname = null;
        }
        return $eproductname;
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" 
            SELECT
                n_quantity_awal,
                n_quantity_akhir,
                n_quantity_in,
                n_quantity_out
            FROM
                tm_ic_trans
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
            ORDER BY
                i_trans DESC
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" 
            SELECT
                n_quantity_stock
            FROM
                tm_ic
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
        ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }
    
    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada = false;
        $query = $this->db->query("
            SELECT
                i_product
            FROM
                tm_mutasi
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'    
        ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_saldo_stockopname = $qdo
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $que=$this->db->query("
            SELECT
                *
            FROM
                tm_mutasi
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($que->num_rows()>1){
            foreach($que->result() as $row){
                $gitasal=$row->n_mutasi_git;
                $gitpenjualanasal=$row->n_git_penjualan;
            }
        }else{
            $gitasal=0;
            $gitpenjualanasal=0;
        } 
        $query = $this->db->query("
            UPDATE
                tm_mutasi
            SET
                n_saldo_awal = $qdo,
                n_saldo_akhir =($qdo + $gitasal + $gitpenjualanasal + n_mutasi_pembelian + n_mutasi_returoutlet + n_mutasi_bbm)-(n_mutasi_penjualan + n_mutasi_returpabrik + n_mutasi_bbk),
                n_mutasi_gitasal = $gitasal,
                n_git_penjualanasal = $gitpenjualanasal
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$perdpn'
        ",false);
    }

    public function insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product,
                i_product_motif,
                i_product_grade,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_mutasi_periode,
                n_saldo_awal,
                n_mutasi_pembelian,
                n_mutasi_returoutlet,
                n_mutasi_bbm,
                n_mutasi_penjualan,
                n_mutasi_returpabrik,
                n_mutasi_bbk,
                n_saldo_akhir,
                n_saldo_stockopname,
                f_mutasi_close)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$emutasiperiode',
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            $qdo,
            'f')
        ",false);
        $bldpn=substr($emutasiperiode,4,2)+1;
        if($bldpn==13){
            $perdpn=substr($emutasiperiode,0,4)+1;
            $perdpn=$perdpn.'01';
        }else{
            $perdpn=substr($emutasiperiode,0,4);
            $perdpn=$perdpn.substr($emutasiperiode,4,2)+1;;
        }
        $que = $this->db->query("
            SELECT
                *
            FROM
                tm_mutasi
            WHERE
                i_product = '$iproduct'
                AND i_product_grade = '$iproductgrade'
                AND i_product_motif = '$iproductmotif'
                AND i_store = '$istore'
                AND i_store_location = '$istorelocation'
                AND i_store_locationbin = '$istorelocationbin'
                AND e_mutasi_periode = '$emutasiperiode'
        ",false);
        if($que->num_rows()>1){
            foreach($que->result() as $row){
                $gitasal=$row->n_mutasi_git;
                $gitpenjualanasal=$row->n_git_penjualan;
            }
        }else{
            $gitasal=0;
            $gitpenjualanasal=0;
        }
        $query=$this->db->query("
            INSERT
                INTO
                tm_mutasi ( i_product,
                i_product_motif,
                i_product_grade,
                i_store,
                i_store_location,
                i_store_locationbin,
                e_mutasi_periode,
                n_saldo_awal,
                n_mutasi_pembelian,
                n_mutasi_returoutlet,
                n_mutasi_bbm,
                n_mutasi_penjualan,
                n_mutasi_returpabrik,
                n_mutasi_bbk,
                n_saldo_akhir,
                n_saldo_stockopname,
                f_mutasi_close,
                n_mutasi_git,
                n_mutasi_pesan,
                n_mutasi_ketoko,
                n_mutasi_daritoko,
                n_git_penjualan,
                n_mutasi_gitasal,
                n_git_penjualanasal)
            VALUES ( '$iproduct',
            '$iproductmotif',
            '$iproductgrade',
            '$istore',
            '$istorelocation',
            '$istorelocationbin',
            '$perdpn',
            $qdo,
            0,
            0,
            0,
            0,
            0,
            0,
            $qdo + $gitasal + $gitpenjualanasal,
            0,
            'f',
            0,
            0,
            0,
            0,
            0,
            $gitasal,
            $gitpenjualanasal)
        ",false);
    }
}

/* End of file Mmaster.php */