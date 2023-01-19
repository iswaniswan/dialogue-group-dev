<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function cekarea()
    {
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $query = $this->db->query("
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
                    AND id_company = '$idcompany'
                    AND i_area = '00')
        ", FALSE);
        if ($query->num_rows()>0) {
            return 'AS';
        }else{
            return 'XX';
        }
    }
    
    public function bacasupplier($username,$idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_supplier
            ORDER BY i_supplier
        ", FALSE);
    }

    public function baca($iap,$isupplier){
        $query = $this->db->query(" SELECT
                                      *
                                    FROM
                                      tm_ap
                                    INNER JOIN tr_supplier ON
                                      (tm_ap.i_supplier = tr_supplier.i_supplier)
                                    INNER JOIN tr_area ON
                                      (tm_ap.i_area = tr_area.i_area)
                                    WHERE
                                      tm_ap.i_ap = '$iap' ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($iap){
        $query = $this->db->query(" SELECT
                                      a.*,
                                      b.e_product_motifname
                                    FROM
                                      tm_ap_item a,
                                      tr_product_motif b
                                    WHERE
                                      a.i_ap = '$iap'
                                      AND a.i_product = b.i_product
                                      AND a.i_product_motif = b.i_product_motif
                                    ORDER BY
                                      a.i_product ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function data($dfrom,$dto,$isupplier,$folder,$imenu){
        if ($isupplier=='AS') {
            $sql = "";
        }else{
            $sql = "AND a.i_supplier = '$isupplier'";
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                              DISTINCT
                              a.i_ap,
                              to_char(a.d_ap, 'dd-mm-yyyy') AS d_ap,
                              a.i_op,
                              a.i_supplier,
                              '(' || a.i_supplier || ') ' || b.e_supplier_name AS esupplier,
                              c.i_dtap AS i_nota , 
                              to_char(c.d_dtap,'dd-mm-yyyy') AS d_nota, 
                              a.i_area,
                              a.v_ap_gross,
                              a.f_ap_cancel,
                              '$dfrom' AS dfrom,
                              '$dto' AS dto,
                              '$folder' AS folder,
                              '$imenu' AS i_menu,
                              '$isupplier' AS allsupp
                            FROM
                              tm_ap a 
                              INNER JOIN tr_supplier b ON(a.i_supplier = b.i_supplier)
                              LEFT JOIN tm_dtap_item d ON (a.i_ap = d.i_do AND a.i_op = d.i_op) 
                              LEFT JOIN tm_dtap c ON(trim(d.i_dtap) = trim(c.i_dtap) AND d.i_supplier =c.i_supplier )
                            WHERE
                              a.d_ap >= to_date('$dfrom', 'dd-mm-yyyy')
                              AND a.d_ap <= to_date('$dto', 'dd-mm-yyyy')	
                              $sql
                            ORDER BY
                              a.i_ap DESC ", FALSE);

        $datatables->edit('i_ap', function ($data) {
          if ($data['f_ap_cancel']=='t') {
              $data = '<p class="h2 text-danger">'.$data['i_ap'].'</p>';
          }else{
              $data = $data['i_ap'];
          }
          return $data;
        });

        $datatables->add('action', function ($data) {
            $i_op               = $data['i_op'];
            $i_ap               = $data['i_ap'];
            $i_supplier         = $data['i_supplier'];
            $i_menu             = $data['i_menu'];
            $folder             = $data['folder'];
            $dfrom              = $data['dfrom'];
            $dto                = $data['dto'];
            $i_nota             = $data['i_nota'];
            $f_ap_cancel        = $data['f_ap_cancel'];
            $isupplierx         = $data['allsupp'];
            $data               = '';

            if($i_nota!=''){
              $inota = "y";
            }else{
              $inota = "t";
            }

            if(check_role($i_menu, 2)||check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$inota/$i_ap/$i_supplier/$dfrom/$dto/$isupplierx/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 4)){
                if(($i_nota=="" || $i_nota == NULL)&&($f_ap_cancel!='t')){
                  $data .= "<a href=\"#\" onclick='cancel(\"$i_op\",\"$i_ap\",\"$i_supplier\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });

        $datatables->hide('i_supplier');
        $datatables->hide('f_ap_cancel');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_area');
        $datatables->hide('v_ap_gross');
        $datatables->hide('allsupp');
        
        return $datatables->generate();
    }
      
    public function deletedetail($iproduct,$iproductmotif,$iproductgrade,$iap){
      $this->db->query("DELETE FROM tm_ap_item WHERE i_ap='$iap'
                        AND i_product='$iproduct' AND i_product_motif='$iproductmotif' AND i_product_grade='$iproductgrade' ");
      
      return TRUE;
  }

    public function cancel($iap,$isupplier,$iop) 
    {
      $this->db->query("update tm_ap set f_ap_cancel='t' WHERE i_ap='$iap' and i_supplier='$isupplier'");
      $this->db->query("update tm_op set f_op_close='f' WHERE i_op='$iop' and i_supplier='G0000' ",False);
  ######
      $iap=trim($iap);
      $this->db->select(" e_product_name, d_ap, n_receive, i_product, i_product_grade, i_product_motif from tm_ap_item
                          WHERE i_ap='$iap' and i_supplier='$isupplier'");
      $query = $this->db->get();
      foreach($query->result() as $row){
        $jml    = $row->n_receive;
        $product= $row->i_product;
        $grade  = $row->i_product_grade;
        $motif  = $row->i_product_motif;
        $eproductname = $row->e_product_name;
        $dap    = $row->d_ap;
        $this->db->query("update tm_op_item set n_delivery=n_delivery-$jml WHERE i_op='$iop'
                          and i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'");

        $query=$this->db->query(" select i_area, i_reff from tm_op where i_op='$iop' ",false);
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            if(substr($row->i_reff,0,3)=='SPB'){
              $ispb=$row->i_reff;
              $iarea=$row->i_area;
              $this->db->query("update tm_spb_item set n_stock=n_stock-$jml where i_spb='$ispb' and i_area='$iarea' and 
                                i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'");
            }else if(substr($row->i_reff,0,4)=='SPMB'){
              $ispmb=$row->i_reff;
              $iarea=$row->i_area;
              $this->db->query("update tm_spmb_item set n_stock=n_stock-$jml where i_spmb='$ispmb' and i_area='$iarea' and 
                                i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'");
            }
          }
        }
        $istore				    = 'AA';
        $istorelocation		= '01';
        $istorelocationbin= '00';
        $th=substr($dap,0,4);
        $bl=substr($dap,5,2);
        $emutasiperiode=$th.$bl;

        $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                    where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' and i_refference_document='$iap'
                                    order by d_transaction desc, i_trans desc",false);
        if ($queri->num_rows() > 0){
          $row   		= $queri->row();
          $que 	= $this->db->query("SELECT current_timestamp as c");
          $ro 	= $que->row();
          $now	 = $ro->c;
          $this->db->query(" 
                              INSERT INTO tm_ic_trans
                              (
                                i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                n_quantity_in, n_quantity_out,
                                n_quantity_akhir, n_quantity_awal)
                              VALUES 
                              (
                                '$product','$grade','$motif','$istore','$istorelocation','$istorelocationbin', 
                                '$eproductname', '$iap', '$now', 0, $jml, $row->n_quantity_akhir-$jml, $row->n_quantity_akhir
                              )
                          ",false);
        }
        if( ($jml!='') && ($jml!=0) ){
          $this->db->query(" 
                            UPDATE tm_mutasi set n_mutasi_pembelian=n_mutasi_pembelian-$jml, n_saldo_akhir=n_saldo_akhir-$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                            and e_mutasi_periode='$emutasiperiode'
                          ",false);
          $this->db->query(" 
                            UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$jml
                            where i_product='$product' and i_product_grade='$grade' and i_product_motif='$motif'
                            and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                          ",false);
        }

      }
      $this->db->query("update tm_bbm set f_bbm_cancel='t' WHERE i_refference_document='$iap'");#" and i_supplier='$isupplier'");
  ######
    }//END FUNCTION cancel

        function updateheader($iap,$isupplier,$iop,$iarea,$dap,$vapgross,$iapold)
        {
          $query 	= $this->db->query("SELECT current_timestamp as c");
          $row   	= $query->row();
          $dentry	= $row->c;
            $data = array(
                'i_ap'		        => $iap,
                'i_ap_old'	      => $iapold,
                'i_supplier'      => $isupplier,
                'i_op'		        => $iop,
                'i_area'	        => $iarea,
                'd_ap'		        => $dap,
                'v_ap_gross'      => $vapgross,
                'f_ap_cancel'     => 'f',
                'd_update'        => $dentry
                        );
          $this->db->where('i_ap', $iap);
          $this->db->update('tm_ap', $data);
          $this->db->query("update tm_op set f_op_close='t' where i_op='$iop'");
        }

        function updatebbmheader($iap,$dap,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea)
        {
          $this->db->set(
            array(
            'i_refference_document'	=> $iap,
            'd_refference_document'	=> $dap,
            'd_bbm'	        				=> $dbbm,
            'e_remark'	      			=> $eremark,
            'i_area'	        			=> $iarea
            )
          );
          $this->db->where('i_bbm',$ibbm);
          $this->db->where('i_bbm_type',$ibbmtype);
          $this->db->update('tm_bbm');
        }

        public function deletedetail2($iproduct, $iproductgrade, $iap, $isupplier, $iproductmotif, $tahun) 
        {
          $this->db->query("DELETE FROM tm_ap_item WHERE i_ap='$iap' 
                    and i_product='$iproduct' and i_product_grade='$iproductgrade' 
                    and i_product_motif='$iproductmotif'");
          $this->db->query("DELETE FROM tm_bbm_item WHERE i_refference_document='$iap' and i_bbm_type='04' and to_char(d_refference_document,'yyyy')='$tahun'
                    and i_product='$iproduct' and i_product_motif='$iproductmotif' and i_product_grade='$iproductgrade'");
        }

        public function updatespb($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispb,$iarea){
          $this->db->query("	update tm_spb_item set n_stock=n_stock+$nreceive where i_spb='$ispb' and i_area='$iarea' and i_product='$iproduct' 
                    and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
        }
        public function updatespmb($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispmb,$iarea){
          $this->db->query("	update tm_spmb_item set n_stock=n_stock+$nreceive where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' 
                    and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
        }

        public function updatespbx($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispb,$iarea){
          $this->db->query("	update tm_spb_item set n_stock=n_stock-$nreceive where i_spb='$ispb' and i_area='$iarea' and i_product='$iproduct' 
                    and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
        }

        public function updatespmbx($iproduct,$iproductgrade,$iproductmotif,$nreceive,$ispmb,$iarea){
          $this->db->query("	update tm_spmb_item set n_stock=n_stock-$nreceive where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' 
                    and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
        }

        function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$iap,$ntmp,$eproductname)
        {
          $queri 		= $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin'
                                        order by i_trans desc",false);
    #and i_refference_document='$iap'
          if ($queri->num_rows() > 0){
            $row   		= $queri->row();
            $que 	= $this->db->query("SELECT current_timestamp as c");
            $ro 	= $que->row();
            $now	 = $ro->c;
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
                                        '$eproductname', '$iap', '$now', 0, $ntmp, $row->n_quantity_akhir-$ntmp, $row->n_quantity_akhir
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

        function updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qap,$emutasiperiode)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_mutasi set n_mutasi_pembelian=n_mutasi_pembelian-$qap, n_saldo_akhir=n_saldo_akhir-$qap
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    and e_mutasi_periode='$emutasiperiode'
                                  ",false);
        }
        
        function updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qap)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_ic set n_quantity_stock=n_quantity_stock-$qap
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
        }

        function insertdetail($iap,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$vproductmill,$dap,$iop,$i)
        {
          $th=substr($dap,0,4);
          $bl=substr($dap,5,2);
          $pr=$th.$bl;
          $this->db->set(
            array(
              'i_ap'				    => $iap,
              'd_ap'				    => $dap,
              'i_supplier'		  => $isupplier,
              'i_product'			  => $iproduct,
              'i_product_grade'	=> $iproductgrade,
              'i_product_motif'	=> $iproductmotif,
              'e_product_name'	=> $eproductname,
              'n_receive'			  => $nreceive,
              'v_product_mill'	=> $vproductmill,
              'e_mutasi_periode'=> $pr,
              'n_item_no'       => $i
            )
          );
          $this->db->insert('tm_ap_item');
          $this->db->query("	update tm_op_item set n_delivery=n_delivery+$nreceive where i_op='$iop' and i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
          $this->db->query("	update tm_spmb_item set n_deliver=n_deliver+$nreceive where i_op='$iop' and i_product='$iproduct' 
                              and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'");
        }

        function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
        {
          $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                    from tm_ic_trans
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    order by i_trans desc",false);
          if ($query->num_rows() > 0){
            return $query->result();
          }
        }

        function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
        {
          $query=$this->db->query(" SELECT n_quantity_stock
                                    from tm_ic
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
          if ($query->num_rows() > 0){
            return $query->result();
          }
        }

        function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$qdo,$q_aw,$q_ak)
        {
          $query 	= $this->db->query("SELECT current_timestamp as c");
          $row   	= $query->row();
          $now	  = $row->c;
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

        function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
        {
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

        function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_mutasi 
                                    set n_mutasi_pembelian=n_mutasi_pembelian+$qdo, n_saldo_akhir=n_saldo_akhir+$qdo
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    and e_mutasi_periode='$emutasiperiode'
                                  ",false);
        }

        function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$emutasiperiode)
        {
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

        function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
        {
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

        function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qdo,$q_ak)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qdo
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
        }

        function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$qdo)
        {
          $query=$this->db->query(" 
                                    insert into tm_ic 
                                    values
                                    (
                                      '$iproduct', '$iproductmotif', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname',$qdo, 't'
                                    )
                                  ",false);
        }

        function inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$qbbm,$q_aw,$q_ak)
        {
          $query 	= $this->db->query("SELECT current_timestamp as c");
          $row   	= $query->row();
          $now	  = $row->c;
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
                                      '$eproductname', '$ibbm', '$now', $q_in+$qbbm, $q_out, $q_ak+$qbbm, $q_aw
                                    )
                                  ",false);
        }

        function insertbbmdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,$vunitprice,$iap,$ibbm,$eremark,$dap)
        {
          $th=substr($dap,0,4);
          $bl=substr($dap,5,2);
          $pr=$th.$bl;
            $this->db->set(
            array(
            'i_bbm'					        => $ibbm,
            'i_refference_document'	=> $iap,
            'i_product'		      		=> $iproduct,
            'i_product_motif'	    	=> $iproductmotif,
            'i_product_grade'   		=> $iproductgrade,
            'e_product_name'	    	=> $eproductname,
            'n_quantity'      			=> $nquantity,
            'v_unit_price'	    		=> $vunitprice,
            'e_remark'      				=> $eremark,
            'd_refference_document'	=> $dap,
            'e_mutasi_periode'      => $pr,
            'i_bbm_type'            => '04'
            )
          );    	
          $this->db->insert('tm_bbm_item');
        }
}


/* End of file Mmaster.php */
