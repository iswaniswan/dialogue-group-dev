<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekdepartemen($username,$idcompany){
        $this->db->select('i_departement');
        $this->db->from('public.tm_user_deprole');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $idepartemen = $kuy->i_departement; 
        }else{
            $idepartemen = '';
        }
        return $idepartemen;
    }

    public function bacaarea($username, $idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
        ", FALSE);
    }

    public function bacaperiode(){
        $query = $this->db->query("select i_periode from tm_periode",false);
        foreach($query->result() as $row){
            $periodeskrg = $row->i_periode;
        }
        return $periodeskrg;
    }

    public function data($dfrom, $dto, $iarea, $cekdepartemen, $folder,$title,$i_menu){
        $this->load->library('fungsi');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" 
                            SELECT 
                                a.i_do, 
                                a.d_do, 
                                a.i_area, 
                                a.i_op, 
                                e.e_supplier_name, 
                                a.i_supplier,
                                a.f_do_cancel as status,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$cekdepartemen' as departemen,
                                '$folder' as folder,
                                '$title' as title,
                                '$i_menu' as i_menu
                             FROM 
                                tm_dofc a, tr_supplier e 
                             WHERE 
                                a.d_do >= to_date('$dfrom', 'dd-mm-yyyy') 
                                and a.d_do <= to_date('$dto', 'dd-mm-yyyy') 
                                and a.i_supplier=e.i_supplier 
                             ORDER BY
                                a.d_do
                             ",false);

        $datatables->edit('d_do', function($data){
            return date("d-m-Y", strtotime($data['d_do']));
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->add('action', function ($data) {
            $folder     = $data['folder'];
            $iarea      = $data['i_area'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $ido        = $data['i_do'];
            $iop        = $data['i_op'];
            $isupplier  = $data['i_supplier'];
            $title      = $data['title'];
            $departemen = $data['departemen'];
            $i_menu     = $data['i_menu'];
            $fdocancel  = $data['status'];
            $data       = '';
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ido/$isupplier/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $fdocancel == 'f'){
                $data .= "<a href=\"#\" onclick='hapus(\"$ido\",\"$iop\",\"$isupplier\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('departemen');
        $datatables->hide('folder');
        $datatables->hide('title');
        $datatables->hide('i_supplier');
        $datatables->hide('i_menu');

        
        return $datatables->generate();
    }

//     public function delete($ido,$op,$isupplier) 
//     {
// 		  $ido=trim($ido);
// 		  $this->db->select(" n_deliver, i_product, i_product_grade, i_product_motif from tm_do_item
// 							  WHERE i_do='$ido' and i_supplier='$isupplier'");
// 		  $query = $this->db->get();
// 		  foreach($query->result() as $row){
// 			  $jml=$row->n_deliver;
// 			  $product=$row->i_product;
// 			  $grade=$row->i_product_grade;
// 			  $motif=$row->i_product_motif;
// 			  $this->db->query("update tm_op_item set n_delivery=n_delivery-$jml WHERE i_op='$op'
// 						    	        and i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'");
// 		  }
//       $bb='';
// 		  $query=$this->db->query(" select i_bbm from tm_bbm WHERE i_refference_document='$ido' and i_supplier='$isupplier'");
// 		  foreach($query->result() as $raw){
// 			  $bb=$raw->i_bbm;
// 		  }
// 		  $this->db->query("update tm_bbm set f_bbm_cancel='t' WHERE i_refference_document='$ido' and i_supplier='$isupplier'");
// 		  $this->db->query("update tm_op set f_op_close='f' WHERE i_op='$op'");
// 		  $this->db->query("UPDATE tm_do set f_do_cancel='t' WHERE i_do='$ido' and i_supplier='$isupplier'");
// ######
//       $qry = $this->db->query("select * from tm_do where i_do='$ido' and i_supplier='$isupplier'");
// 		if ($qry->num_rows() > 0){
//             foreach($qry->result() as $qyr){  
//               $ddo=$qyr->d_do;
//             }   
//         }
//       $th=substr($ddo,0,4);
// 		  $bl=substr($ddo,5,2);
// 		  $emutasiperiode=$th.$bl;
//       $istore='AA';
// 			$istorelocation='01';
// 			$istorelocationbin='00';
//       $this->db->select(" * from tm_do_item where i_do='$ido' and i_supplier='$isupplier' order by n_item_no");
// 			$qery = $this->db->get();
// 			if ($qery->num_rows() > 0){
//         foreach($qery->result() as $qyre){  
//           $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
//                                         where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
//                                         and i_product_motif='$qyre->i_product_motif'
//                                         and i_store='$istore' and i_store_location='$istorelocation'
//                                         and i_store_locationbin='$istorelocationbin' and i_refference_document='$ido'
//                                         order by d_transaction desc, i_trans desc",false);
//           if ($queri->num_rows() > 0){
//         	  $row   		= $queri->row();
//             $que 	= $this->db->query("SELECT current_timestamp as c");
// 	          $ro 	= $que->row();
// 	          $now	 = $ro->c;
//             $this->db->query(" 
//                               INSERT INTO tm_ic_trans
//                               (
//                                 i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
//                                 i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
//                                 n_quantity_in, n_quantity_out,
//                                 n_quantity_akhir, n_quantity_awal)
//                               VALUES 
//                               (
//                                 '$qyre->i_product','$qyre->i_product_grade','$qyre->i_product_motif',
//                                 '$istore','$istorelocation','$istorelocationbin', 
//                                 '$qyre->e_product_name', '$ido', '$now', 0, $qyre->n_deliver, $row->n_quantity_akhir-$qyre->n_deliver, 
//                                 $row->n_quantity_akhir
//                               )
//                              ",false);
//           }
//           $this->db->query(" 
//                             UPDATE tm_mutasi set n_mutasi_pembelian=n_mutasi_pembelian-$qyre->n_deliver, 
//                             n_saldo_akhir=n_saldo_akhir-$qyre->n_deliver
//                             where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
//                             and i_product_motif='$qyre->i_product_motif'
//                             and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
//                             and e_mutasi_periode='$emutasiperiode'
//                            ",false);
//           $this->db->query(" 
//                             UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qyre->n_deliver
//                             where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
//                             and i_product_motif='$qyre->i_product_motif' and i_store='$istore' and i_store_location='$istorelocation' 
//                             and i_store_locationbin='$istorelocationbin'
//                            ",false);
//         }
//       }
// ######
// 		  return TRUE;
//     }

public function delete($ido, $op, $isupplier){
    $ido=trim($ido);
    $this->db->select("
            n_deliver,
            i_product,
            i_product_grade,
            i_product_motif
        FROM
            tm_dofc_item
        WHERE
            i_do = '$ido'
            AND i_supplier = '$isupplier'
    ");
    $query = $this->db->get();
    foreach($query->result() as $row){
        $jml=$row->n_deliver;
        $product=$row->i_product;
        $grade=$row->i_product_grade;
        $motif=$row->i_product_motif;
        $this->db->query("
            UPDATE
                tm_opfc_item
            SET
                n_delivery = n_delivery-$jml
            WHERE
                i_op = '$op'
                AND i_product = '$product'
                AND i_product_grade = '$grade'
                AND i_product_motif = '$motif'
        ", FALSE);
    }
    $bb='';
    $query=$this->db->query("
        SELECT
            i_bbm
        FROM
            tm_bbm
        WHERE
            i_refference_document = '$ido'
            AND i_supplier = '$isupplier'
    ");
    foreach($query->result() as $raw){
        $bb=$raw->i_bbm;
    }
    $this->db->query("
        UPDATE
            tm_bbm
        SET
            f_bbm_cancel = 't'
        WHERE
            i_refference_document = '$ido'
            AND i_supplier = '$isupplier'   
    ");
    $this->db->query("
        UPDATE
            tm_opfc
        SET
            f_op_close = 'f'
        WHERE
            i_op = '$op'
    ");
    $this->db->query("
        UPDATE
            tm_dofc
        SET
            f_do_cancel = 't'
        WHERE
            i_do = '$ido'
            AND i_supplier = '$isupplier'
    ");
    $this->db->select("
            *
        FROM
            tm_dofc
        WHERE
            i_do = '$ido'
            AND i_supplier = '$isupplier'
    ");
    $qry = $this->db->get();
    if ($qry->num_rows() > 0){
        foreach($qry->result() as $qyr){
            $ddo=$qyr->d_do;
        }
    }
    $th=substr($ddo,0,4);
    $bl=substr($ddo,5,2);
    $emutasiperiode=$th.$bl;
    $istore='AA';
    $istorelocation='01';
    $istorelocationbin='00';
    $this->db->select("
            *
        FROM
            tm_dofc_item
        WHERE
            i_do = '$ido'
            AND i_supplier = '$isupplier'
        ORDER BY
            n_item_no
            ");
    $qery = $this->db->get();
    if ($qery->num_rows() > 0){
        foreach($qery->result() as $qyre){
            $queri = $this->db->query("
                SELECT
                    n_quantity_akhir,
                    i_trans
                FROM
                    tm_ic_trans
                WHERE
                    i_product = '$qyre->i_product'
                    AND i_product_grade = '$qyre->i_product_grade'
                    AND i_product_motif = '$qyre->i_product_motif'
                    AND i_store = '$istore'
                    AND i_store_location = '$istorelocation'
                    AND i_store_locationbin = '$istorelocationbin'
                    AND i_refference_document = '$ido'
                ORDER BY
                    d_transaction DESC,
                    i_trans DESC
            ",false);
            if ($queri->num_rows() > 0){
                $row   = $queri->row();
                $now   = current_datetime();
                $this->db->query("
                    INSERT
                        INTO
                        tm_ic_trans ( i_product, i_product_grade, i_product_motif, i_store, i_store_location, i_store_locationbin, e_product_name, i_refference_document, d_transaction, n_quantity_in, n_quantity_out, n_quantity_akhir, n_quantity_awal)
                    VALUES ( '$qyre->i_product', '$qyre->i_product_grade', '$qyre->i_product_motif', '$istore', '$istorelocation', '$istorelocationbin', '$qyre->e_product_name', '$ido', '$now', 0, $qyre->n_deliver, $row->n_quantity_akhir-$qyre->n_deliver, $row->n_quantity_akhir )
                ",false);
            }
            $this->db->query("
                UPDATE
                    tm_mutasi
                SET
                    n_mutasi_pembelian = n_mutasi_pembelian-$qyre->n_deliver,
                    n_saldo_akhir = n_saldo_akhir-$qyre->n_deliver
                WHERE
                    i_product = '$qyre->i_product'
                    AND i_product_grade = '$qyre->i_product_grade'
                    AND i_product_motif = '$qyre->i_product_motif'
                    AND i_store = '$istore'
                    AND i_store_location = '$istorelocation'
                    AND i_store_locationbin = '$istorelocationbin'
                    AND e_mutasi_periode = '$emutasiperiode'
            ",false);
            $this->db->query("
                UPDATE
                    tm_ic
                SET
                    n_quantity_stock = n_quantity_stock-$qyre->n_deliver
                WHERE
                    i_product = '$qyre->i_product'
                    AND i_product_grade = '$qyre->i_product_grade'
                    AND i_product_motif = '$qyre->i_product_motif'
                    AND i_store = '$istore'
                    AND i_store_location = '$istorelocation'
                    AND i_store_locationbin = '$istorelocationbin'
            ",false);
        }
    }
    return TRUE;
}

public function baca($id,$isupplier){
    $query = $this->db->query("
        SELECT
            a.*,
            b.*,
            c.*
        FROM
            tm_do a,
            tr_supplier b,
            tr_area c
        WHERE
            a.i_supplier = b.i_supplier
            AND a.i_area = c.i_area
            AND a.i_do = '$id'
            AND a.i_supplier = '$isupplier'
    ", false);
    if ($query->num_rows() > 0){
        return $query->row();
    }
}

    public function bacado($ido,$isupplier){
        return $this->db->query("
                        SELECT a.*, b.*, c.*
                        from tm_dofc a, tr_supplier b, tr_area c
                        where a.i_supplier=b.i_supplier
                        and a.i_area=c.i_area
                        and a.i_do ='$ido'
                        and a.i_supplier='$isupplier'", false);
    }

    public function bacadetaildo($ido,$isupplier){
        return $this->db->query("
                        select a.*,d.*, b.e_product_motifname, c.n_order, (d.n_deliver * d.v_product_mill) as total from tm_dofc a, tr_product_motif b, tm_opfc_item c, tm_dofc_item d
            where a.i_do=d.i_do and a.i_do = '$ido' and a.i_supplier='$isupplier' and d.i_product=b.i_product and d.i_product_motif=b.i_product_motif
            and d.i_product=c.i_product and a.i_op=c.i_op
            order by d.i_product
                        ",false);
    }

    public function product($cari,$op) {
        $cari = str_replace("'", "", $cari);
            return $this->db->query("
                SELECT
                    a.i_product,
                    a.i_product_motif,
                    a.e_product_motifname,
                    c.e_product_name,
                    d.v_product_mill,
                    b.n_order
                FROM
                    tr_product_motif a,
                    tr_product c,
                    tm_opfc_item b,
                    tr_harga_beli d
                WHERE
                    b.i_op = '$op'
                    AND a.i_product = c.i_product
                    AND (b.n_delivery<b.n_order
                    OR b.n_delivery ISNULL)
                    AND b.i_product = a.i_product
                    AND b.i_product_motif = a.i_product_motif
                    AND b.i_product = d.i_product
                    AND d.i_price_group = '00'
                    AND (UPPER(b.i_product) LIKE '%$cari%'
                    OR UPPER(c.e_product_name) LIKE '%$cari%')
            ", FALSE);
    }

    public function detailproduct($iproduct,$op) {
        return $this->db->query("
            SELECT
                a.i_product,
                a.i_product_motif,
                a.e_product_motifname,
                c.e_product_name,
                d.v_product_mill,
                b.n_order
            FROM
                tr_product_motif a,
                tr_prohduct c,
                tm_op_item b,
                tr_harga_beli d
            WHERE
                b.i_op = '$op'
                AND a.i_product = c.i_product
                AND (b.n_delivery<b.n_order
                OR b.n_delivery ISNULL)
                AND b.i_product = a.i_product
                AND b.i_product_motif = a.i_product_motif
                AND b.i_product = d.i_product
                AND d.i_price_group = '00'
                AND b.i_product = '$iproduct'
        ", FALSE);
    }

    public function deletedetail($iproduct, $iproductgrade, $ido, $isupplier, $iproductmotif, $tahun, $idoold){
        $this->db->query("
                        DELETE
                        FROM
                           tm_dofc_item 
                        WHERE
                           i_do = '$idoold' 
                           and i_supplier = '$isupplier' 
                           and i_product = '$iproduct' 
                           and i_product_grade = '$iproductgrade' 
                           and i_product_motif = '$iproductmotif'
                        ");
        return TRUE;
    }

    public function uphead($ido,$isupplier,$iop,$iarea,$ddo,$vdogross){
      $data = array(
               'i_do'      => $ido,
               'i_supplier'=> $isupplier,
               'i_op'      => $iop,
               'i_area'    => $iarea,
               'd_do'      => $ddo,
               'v_do_gross'=> $vdogross

            );
      $this->db->where('i_do', $ido);
      $this->db->where('i_supplier', $isupplier);
      $this->db->update('tm_dofc', $data);
    }

    public function insertheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross){
        $query   = $this->db->query("SELECT current_timestamp as c");
        $row     = $query->row();
        $dentry  = $row->c;
        $this->db->set(
            array(
                'i_do'      => $ido,
                'i_supplier'=> $isupplier,
                'i_op'      => $iop,
                'i_area'    => $iarea,
                'd_do'      => $ddo,
                'v_do_gross'=> $vdogross,
                'd_entry'   => $dentry
            )
        );
        $this->db->insert('tm_dofc');
    }

    public function insertdetail($iop,$ido,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vproductmill,$ddo,$eremark,$i,$idoold){
        $th=substr($ddo,0,4);
        $bl=substr($ddo,5,2);
        $pr=$th.$bl;
        $this->db->set(
            array(
                'i_do'            => $ido,
                'd_do'            => $ddo,
                'i_supplier'      => $isupplier,
                'i_product'       => $iproduct,
                'i_product_grade' => $iproductgrade,
                'i_product_motif' => $iproductmotif,
                'e_product_name'  => $eproductname,
                'n_deliver'       => $ndeliver,
                'v_product_mill'  => $vproductmill,
                'i_op'            => $iop,
                'e_remark'        => $eremark,
                'e_mutasi_periode'=> $pr,
                'n_item_no'       => $i
            )
        );
        $this->db->insert('tm_dofc_item');
    }

    public function updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$ndeliverhidden,$ntmp){
        if($ntmp==''){
            $this->db->query("
                            update 
                                tm_opfc_item 
                            set 
                                n_delivery=n_delivery+$ndeliver
                            where 
                                i_op='$iop' 
                                and i_product='$iproduct' 
                                and i_product_grade='$iproductgrade'
                                and i_product_motif='$iproductmotif'");              
        }else{
            $this->db->query("
                            update 
                                tm_opfc_item 
                            set 
                                n_delivery=n_delivery+$ndeliver-$ntmp
                            where 
                                i_op='$iop' 
                                and i_product='$iproduct' 
                                and i_product_grade='$iproductgrade'
                                and i_product_motif='$iproductmotif'");
        }
    }

    public function updatesaldofc($iproduct,$ndeliver,$period){
        $this->db->select("e_periode from tm_saldoawal_fc where i_product='$iproduct' and e_periode='$period'", false);
        $query = $this->db->get();
        foreach($query->result() as $row){
           $prod=$row->i_product;
        }
        if($prod!=NULL){
            $this->db->query("update tm_saldoawal_fc set n_sisa=n_sisa-$ndeliver
                            where i_product='$iproduct' and e_periode='$period'");              
        }
    }

    public function updatespbdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver){
        $this->db->select("  i_reff, i_area from tm_op where i_op='$iop'", false);
        $query = $this->db->get();
        foreach($query->result() as $row){
           $spb =$row->i_reff;
           $area=$row->i_area;
        }
        $que=$this->db->query(" select n_order, n_deliver from tm_spb_item
                                where i_spb='$spb' and i_area='$area' and i_product='$iproduct'
                                and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
        if($que->num_rows>0){
           $tmp=0;
            foreach($que->result() as $raw){
                $jmlord =$raw->n_order;
                $jmldel =$raw->n_deliver;
                $tmp=$ndeliver+$jmldel;
            }
            if($jmlord>=$tmp){
                $this->db->query("update tm_spb_item set n_deliver=n_deliver+$ndeliver, n_stock=n_stock+$ndeliver, i_op='$iop'
                                where i_spb='$spb' and i_area='$area' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                                and i_product_motif='$iproductmotif'");
            }
        }
    }

    public function updateheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross,$idoold){
        $data = array(
                    'i_do'      => $ido,
                    'i_supplier'=> $isupplier,
                    'i_op'      => $iop,
                    'i_area'    => $iarea,
                    'd_do'      => $ddo,
                    'v_do_gross'=> $vdogross
        );
        $this->db->where('i_do', $idoold);
        $this->db->where('i_supplier', $isupplier);
        $this->db->where('i_op', $iop);
        $this->db->update('tm_dofc', $data);
    }

    public function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out
                                  from tm_ic_trans
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  order by i_trans desc",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $query=$this->db->query(" SELECT n_quantity_stock
                                  from tm_ic
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                ",false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak){
        $query   = $this->db->query("SELECT current_timestamp as c");
        $row    = $query->row();
        $now   = $row->c;
        $query=$this->db->query("
                                INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location,
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction,
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES
                                (
                                  '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin',
                                  '$eproductname', '$ido', '$now', $qdo, 0, $q_ak+$qdo, $q_ak
                                )
                              ",false);
    }

    public function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode){
        $ada=false;
        $query=$this->db->query(" SELECT i_product
                                  from tm_mutasi
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  and e_mutasi_periode='$emutasiperiode'
                                ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
                                UPDATE tm_mutasi
                                set n_mutasi_pembelian=n_mutasi_pembelian+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    public function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode){
        $query=$this->db->query("
                                insert into tm_mutasi
                                (
                                  i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                  e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                  n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,f_mutasi_close)
                                values
                                (
                                  '$iproduct','$iproductmotif','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,$qdo,0,0,0,0,0,$qdo,0,'f')
                              ",false);
    }

    public function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin){
        $ada=false;
        $query=$this->db->query(" SELECT i_product
                                  from tm_ic
                                  where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                  and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                ",false);
        if ($query->num_rows() > 0){
            $ada=true;
        }
        return $ada;
    }

    public function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak){
      $query=$this->db->query("
                                UPDATE tm_ic set n_quantity_stock=$q_ak+$qdo
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }

    public function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo){
      $query=$this->db->query("
                                insert into tm_ic
                                values
                                (
                                  '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname',$qdo, 't'
                                )
                              ",false);
    }

    public function hitungtotal($iop){
        $this->db->select(" sum(n_order*v_product_mill) as total from tm_opfc_item where i_op='$iop'
                            and (n_delivery<n_order or n_delivery isnull)", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $tes){
            $total=$tes->total;
        }
        return $total;
        }
    }

    public function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ido,$ntmp,$eproductname){
        $queri      = $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans
                                          where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                          and i_store='$istore' and i_store_location='$istorelocation'
                                          and i_store_locationbin='$istorelocationbin' 
                                          order by i_trans desc",false);
        if ($queri->num_rows() > 0){
            $row    = $queri->row();
            $que    = $this->db->query("SELECT current_timestamp as c");
            $ro     = $que->row();
            $now    = $ro->c;
            if($ntmp!=0 || $ntmp!=''){
                $query=$this->db->query("
                                      INSERT INTO tm_ic_trans
                                      (
                                        i_product, i_product_grade, i_product_motif, i_store, i_store_location,
                                        i_store_locationbin, e_product_name, i_refference_document, d_transaction,
                                        n_quantity_in, n_quantity_out,
                                        n_quantity_akhir, n_quantity_awal)
                                      VALUES
                                      (
                                        '$iproduct','$iproductgrade','$iproductmotif','$istore','$istorelocation','$istorelocationbin',
                                        '$eproductname', '$ido', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
                                      )
                                    ",false);
            }
        }
        if(isset($row->i_trans)){
            if($row->i_trans!=''){
                return $row->i_trans;
            }else{
                return 1;
            }
        }else{
            return 1;
        }
    }

    public function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode){
        $query=$this->db->query("
                                UPDATE tm_mutasi set n_mutasi_pembelian=n_mutasi_pembelian-$qsj, n_saldo_akhir=n_saldo_akhir-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                              ",false);
    }

    public function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj){
        $query=$this->db->query("
                                UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qsj
                                where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                              ",false);
    }
}

/* End of file Mmaster.php */
