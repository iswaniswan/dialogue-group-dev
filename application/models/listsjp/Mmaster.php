<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($username,$idcompany){
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
        ", FALSE);
    }

    public function baca($isjp,$iarea){
        $query = $this->db->query("
                                    SELECT
                                        distinct(c.i_store),
                                        c.i_store_location,
                                        a.*,
                                        b.e_area_name
                                    FROM
                                        tm_sjp a,
                                        tr_area b,
                                        tm_sjp_item c
                                    WHERE
                                        a.i_area = b.i_area
                                        AND a.i_sjp = c.i_sjp
                                        AND a.i_area = c.i_area
                                        AND a.i_sjp = '$isjp'
                                        AND a.i_area = '$iarea' ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($isjp,$iarea){
        $query = $this->db->query("
                                    SELECT
                                        a.i_sjp,
                                        a.d_sjp,
                                        a.i_area,
                                        a.i_product,
                                        a.i_product_grade,
                                        a.i_product_motif,
                                        a.n_quantity_order,
                                        a.n_quantity_deliver,
                                        a.v_unit_price,
                                        a.e_product_name,
                                        a.i_store,
                                        a.i_store_location,
                                        a.i_store_locationbin,
                                        a.e_remark,
                                        b.e_product_motifname
                                    FROM
                                        tm_sjp_item a,
                                        tr_product_motif b
                                    WHERE
                                        a.i_sjp = '$isjp'
                                        AND a.i_area = '$iarea'
                                        AND a.i_product = b.i_product
                                        AND a.i_product_motif = b.i_product_motif
                                    ORDER BY
                                        a.n_item_no ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function data($dfrom,$dto,$iarea,$folder,$imenu){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $idepartement = $this->session->userdata('i_departement');

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                            a.i_sjp,
                            to_char(a.d_sjp, 'dd-mm-yyyy') AS d_sjp,
                            a.i_bapb,
                            a.i_area,
                            b.e_area_name,
                            a.i_spmb,
                            CASE
                                WHEN d_sjp_receive isnull THEN 'Belum'
                                ELSE 'Terima'
                            END AS status,
                            to_char(a.d_sjp_receive, 'dd-mm-yyyy') as d_sjp_receive,
                            CASE
                                WHEN c.f_spmb_consigment = 'f' THEN 'tdk'
                                ELSE 'ya'
                            END AS konsinyasi,
                            a.f_sjp_cancel,
                            '$folder' AS folder,
                            '$imenu' AS i_menu,
                            '$idepartement' AS idepartement,
                            '$dfrom' AS dfrom,
                            '$dto' AS dto
                        FROM
                            tm_sjp a,
                            tr_area b,
                            tm_spmb c
                        WHERE
                            a.i_area = b.i_area
                            AND a.i_spmb = c.i_spmb
                            AND a.i_area = '$iarea'
                            AND a.d_sjp >= to_date('$dfrom', 'dd-mm-yyyy')
                            AND a.d_sjp <= to_date('$dto', 'dd-mm-yyyy')", FALSE);

        $datatables->edit('status', function ($data) {
            if ($data['status']=='Belum') {
                $data = '<span class="label label-success label-rouded">Belum</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Terima</span>';
            }
            return $data;
        });

        $datatables->edit('konsinyasi', function ($data) {
            if ($data['konsinyasi']=='tdk') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->edit('i_sjp', function ($data) {
            if ($data['f_sjp_cancel']=='t') {
                $data = '<p class="h2 text-danger">'.$data['i_sjp'].'</p>';
            }else{
                $data = $data['i_sjp'];
            }
            return $data;
        });

        $datatables->add('action', function ($data) {
            $i_sjp              = trim($data['i_sjp']);
            $i_area             = $data['i_area'];
            $i_menu             = $data['i_menu'];
            $folder             = $data['folder'];
            $dfrom              = $data['dfrom'];
            $dto                = $data['dto'];
            $i_spmb             = trim($data['i_spmb']);
            $f_sjp_cancel       = trim($data['f_sjp_cancel']);
            $dsjpreceive        = trim($data['d_sjp_receive']);
            $idepartement       = trim($data['idepartement']);
            $data               = '';

            if(check_role($i_menu, 2)||check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_sjp/$i_area/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 4)){
                if(($f_sjp_cancel == 'f')&&($dsjpreceive == ''||$dsjpreceive == NULL)&&($idepartement=='21' || $idepartement=='1')){
                $data .= "<a href=\"#\" onclick='cancel(\"$i_sjp\",\"$i_area\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('f_sjp_cancel');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('idepartement');
        
        return $datatables->generate();
    }
      
        public function cancel($isjp, $iarea){
        $this->db->set(
            array(
                'f_sjp_cancel'  => 't'
            )
        );
        $this->db->where('i_sjp',$isjp);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_sjp');

        $this->db->select("i_spmb, d_sjp from tm_sjp where i_sjp='$isjp' and i_area='$iarea'");
        $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                    $ispmb  = $row->i_spmb;
                    $dsj    = $row->d_sjp;
                
                    $this->db->select(" * from tm_sjp_item where i_sjp='$isjp' and i_area='$iarea'");
                    $quer = $this->db->get();
                    if ($quer->num_rows() > 0){
                        foreach($quer->result() as $ro){
                            $this->db->query("  update tm_spmb_item set n_deliver=n_deliver-$ro->n_quantity_deliver, 
                                                n_saldo=n_saldo+$ro->n_quantity_deliver
                                                WHERE i_spmb='$ispmb' and i_product='$ro->i_product' and i_product_motif='$ro->i_product_motif'
                                                and i_product_grade='$ro->i_product_grade'");
                        }
                    }
                }
            }
        #####
          $th=substr($dsj,0,4);
              $bl=substr($dsj,5,2);
              $emutasiperiode=$th.$bl;
          $query=$this->db->query(" select f_spmb_consigment from tm_spmb where i_spmb='$ispmb'",false);
          $consigment='f';
                if ($query->num_rows() > 0){
                    foreach($query->result() as $qq){
                        $consigment=$qq->f_spmb_consigment;
                    }
                }
          $areapusat='00';
          $que = $this->db->query("select i_store from tr_area where i_area='$areapusat'");
          $st=$que->row();
          $istore=$st->i_store;
          if($istore=='AA'){
                    $istorelocation     = '01';
                }else{
            if($consigment=='t')
              $istorelocation       = 'PB';
            else
                    $istorelocation     = '00';
                }
                $istorelocationbin  = '00';
          $this->db->select(" * from tm_sjp_item where i_sjp='$isjp' and i_area='$iarea' order by n_item_no");
                $qery = $this->db->get();
                if ($qery->num_rows() > 0){
            foreach($qery->result() as $qyre){  
              $queri        = $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                            where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                            and i_product_motif='$qyre->i_product_motif'
                                            and i_store='$istore' and i_store_location='$istorelocation'
                                            and i_store_locationbin='$istorelocationbin' and i_refference_document='$isjp'
                                            order by d_transaction desc, i_trans desc",false);
              if ($queri->num_rows() > 0){
                  $row          = $queri->row();
                $nawal=$row->n_quantity_akhir;
              }else{
                $queri      = $this->db->query("SELECT n_quantity_stock FROM tm_ic
                                  where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                  and i_product_motif='$qyre->i_product_motif'
                                  and i_store='$istore' and i_store_location='$istorelocation'
                                  and i_store_locationbin='$istorelocationbin'",false);
                if ($queri->num_rows() > 0){
                  $row          = $queri->row();
                  $nawal=$row->n_quantity_stock;
                }
              }
              $que  = $this->db->query("SELECT current_timestamp as c");
              $ro   = $que->row();
              $now   = $ro->c;
              $this->db->query("INSERT INTO tm_ic_trans
                                (
                                  i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                  i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                  n_quantity_in, n_quantity_out,
                                  n_quantity_akhir, n_quantity_awal)
                                VALUES 
                                (
                                  '$qyre->i_product','$qyre->i_product_grade','$qyre->i_product_motif',
                                  '$istore','$istorelocation','$istorelocationbin', 
                                  '$qyre->e_product_name', '$isjp', '$now', $qyre->n_quantity_deliver, 0, 
                                  $nawal+$qyre->n_quantity_deliver, $nawal
                                )
                               ",false);

              $this->db->query("UPDATE tm_mutasi set n_mutasi_penjualan=n_mutasi_penjualan-$qyre->n_quantity_deliver, 
                                n_saldo_akhir=n_saldo_akhir+$qyre->n_quantity_deliver
                                where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                and i_product_motif='$qyre->i_product_motif'
                                and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                and e_mutasi_periode='$emutasiperiode'
                               ",false);
              $this->db->query("UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qyre->n_quantity_deliver
                                where i_product='$qyre->i_product' and i_product_grade='$qyre->i_product_grade' 
                                and i_product_motif='$qyre->i_product_motif' and i_store='$istore' and i_store_location='$istorelocation' 
                                and i_store_locationbin='$istorelocationbin'
                               ",false);

            }
          }
        }//END FUNCTION cancel

        function searchsjheader($isjp,$iarea)
        {
          return $this->db->query(" SELECT * FROM tm_sjp WHERE i_sjp='$isjp' AND i_area='$iarea' ");
        }    

        function deletesjheader($isjp,$iarea)
        {
          $this->db->query(" delete from tm_sjp where i_sjp='$isjp' and i_area='$iarea' ",false);
        } 

        function insertsjheader2($ispb,$dspb,$isj,$dsj,$iarea,$vspbnetto,$isjold)
        {
            $query      = $this->db->query("SELECT current_timestamp as c");
            $row        = $query->row();
            $dsjentry   = $row->c;
            $this->db->set(
                array(
                    'i_sjp'                 => $isj,
                    'i_sjp_old'         => $isjold,
                    'i_spmb'                => $ispb,
                    'd_spmb'                => $dspb,
                    'd_sjp'               => $dsj,
                    'i_area'            => $iarea,
                    'v_sjp'           => $vspbnetto,
                    'd_sjp_entry'       => $dsjentry,
                    'f_sjp_cancel'  => 'f'
                )
            );
            
            $this->db->insert('tm_nota');
        }  

        public function deletesjdetail($ispmb, $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif, $ndeliver) 
        {
          $cek=$this->db->query("select * from tm_sjp_item WHERE i_sjp='$isj' 
                              and i_area='$iarea'
                                                  and i_product='$iproduct' and i_product_grade='$iproductgrade' 
                                                  and i_product_motif='$iproductmotif'");
          if($cek->num_rows()>0)
          {
                $this->db->query("DELETE FROM tm_sjp_item WHERE i_sjp='$isj'
                              and i_area='$iarea'
                                                  and i_product='$iproduct' and i_product_grade='$iproductgrade' 
                                                  and i_product_motif='$iproductmotif'");
    #       $this->db->query(" update tm_spmb_item set n_deliver = n_deliver-$ndeliver, n_saldo=n_saldo+$ndeliver
    #                           where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade'
    #                           and i_product_motif='$iproductmotif' ",false);
          }
        }  

        function deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj,$ntmp,$eproductname)
        {
            $queri  = $this->db->query("SELECT n_quantity_akhir, i_trans FROM tm_ic_trans 
                                        where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                        and i_store='$istore' and i_store_location='$istorelocation'
                                        and i_store_locationbin='$istorelocationbin' 
                                        order by i_trans desc");
    #and i_refference_document='$isj'
          if ($queri->num_rows() > 0){
              $row          = $queri->row();
              $que    = $this->db->query("SELECT current_timestamp as c");
              $ro   = $que->row();
              $now   = $ro->c;
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
                                        '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                        '$eproductname', '$isj', '$now', $ntmp, 0, $row->n_quantity_akhir+$ntmp, $row->n_quantity_akhir
                                      )
                                    ",false);
            }
    /*
          $query=$this->db->query(" 
                                    DELETE FROM tm_ic_trans 
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='$iproductmotif'
                                    and i_store='$istore' and i_store_location='$istorelocation'
                                    and i_store_locationbin='$istorelocationbin' and i_refference_document='$isj'
                                  ",false);
    */
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

        function updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_mutasi set n_mutasi_bbk=n_mutasi_bbk-$qsj, n_mutasi_git=n_mutasi_git-$qsj, n_saldo_akhir=n_saldo_akhir+$qsj

                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    and e_mutasi_periode='$emutasiperiode'
                                  ",false);
        }

        function updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_ic set n_quantity_stock=n_quantity_stock+$qsj
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
        }

        function nambihspmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea)
        {
            $this->db->query(" update tm_spmb_item set n_deliver = n_deliver-$ndeliver, n_saldo=n_saldo+$ndeliver
                                   where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                                   and i_product_motif='$iproductmotif' ",false);
        }

        function insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$norder,$ndeliver,
                                  $vunitprice,$ispmb,$dspmb,$isj,$dsj,$iarea,
                                  $istore,$istorelocation,$istorelocationbin,$eremark,$i)
        {
          $th=substr($dsj,0,4);
          $bl=substr($dsj,5,2);
          $pr=$th.$bl;
            $this->db->set(
                array(
                    'i_sjp'                   => $isj,
                    'd_sjp'                   => $dsj,
                    'i_area'                  => $iarea,
                    'i_product'             => $iproduct,
                    'i_product_motif'       => $iproductmotif,
                    'i_product_grade'       => $iproductgrade,
                    'e_product_name'        => $eproductname,
                    'n_quantity_order'      => $norder,
                    'n_quantity_deliver'    => $ndeliver,
                    'v_unit_price'          => $vunitprice,
                    'i_store'               => $istore,
                    'i_store_location'    => $istorelocation,
                    'i_store_locationbin'   => $istorelocationbin, 
            'e_remark'            => $eremark,
            'e_mutasi_periode'    => $pr,
            'n_item_no'           => $i
                )
            );
            
            $this->db->insert('tm_sjp_item');
        }

        function qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
        {
          $query=$this->db->query(" SELECT n_quantity_stock
                                    from tm_ic
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
          if ($query->num_rows() > 0){
                    return $query->result();
                }
        }

        function inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$qsj,$q_aw,$q_ak)
        {
          $query    = $this->db->query("SELECT current_timestamp as c");
            $row    = $query->row();
            $now      = $row->c;
          $query=$this->db->query(" 
                                    INSERT INTO tm_ic_trans
                                    (
                                      i_product, i_product_grade, i_product_motif, i_store, i_store_location, 
                                      i_store_locationbin, e_product_name, i_refference_document, d_transaction, 
                                      n_quantity_in, n_quantity_out,
                                      n_quantity_akhir, n_quantity_awal)
                                    VALUES 
                                    (
                                      '$iproduct','$iproductgrade','00','$istore','$istorelocation','$istorelocationbin', 
                                      '$eproductname', '$isj', '$now', 0, $qsj, $q_ak-$qsj, $q_ak
                                    )
                                  ",false);
        }

        function updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_mutasi 
                                    set n_mutasi_git=n_mutasi_git+$qsj
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    and e_mutasi_periode='$emutasiperiode'
                                  ",false);
    #, n_saldo_akhir=n_saldo_akhir-$qsj
        }

        function insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$emutasiperiode)
        {
          $query=$this->db->query(" 
                                    insert into tm_mutasi 
                                    (
                                      i_product,i_product_motif,i_product_grade,i_store,i_store_location,i_store_locationbin,
                                      e_mutasi_periode,n_saldo_awal,n_mutasi_pembelian,n_mutasi_returoutlet,n_mutasi_bbm,n_mutasi_penjualan,
                                      n_mutasi_returpabrik,n_mutasi_bbk,n_saldo_akhir,n_saldo_stockopname,n_mutasi_git,f_mutasi_close)
                                    values
                                    (
                                      '$iproduct','00','$iproductgrade','$istore','$istorelocation','$istorelocationbin','$emutasiperiode',0,0,0,0,0,0,0,0,0,$qsj,'f')
                                  ",false);
        }

        function cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
        {
          $ada=false;
          $query=$this->db->query(" SELECT i_product
                                    from tm_ic
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
          if ($query->num_rows() > 0){
                    $ada=true;
                }
          return $ada;
        }
        function updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$qsj,$q_ak)
        {
          $query=$this->db->query(" 
                                    UPDATE tm_ic set n_quantity_stock=$q_ak-$qsj
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                  ",false);
        }
        function insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver)
        {
          $query=$this->db->query(" 
                                    insert into tm_ic 
                                    values
                                    (
                                      '$iproduct', '00', '$iproductgrade', '$istore', '$istorelocation', '$istorelocationbin', '$eproductname', 0-$ndeliver, 't'
                                    )
                                  ",false);
        }

        function updatespmbitem($ispmb,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$iarea)
        {
            $this->db->query(" update tm_spmb_item set n_deliver = n_deliver+$ndeliver, n_saldo=n_saldo-$ndeliver
                                   where i_spmb='$ispmb' and i_area='$iarea' and i_product='$iproduct' and i_product_grade='$iproductgrade'
                                   and i_product_motif='$iproductmotif' ",false);
        }

        function updatesjheader($isj,$iarea,$isjold,$dsj,$vsjnetto)
        {
          $query        = $this->db->query("SELECT current_timestamp as c");
              $row          = $query->row();
              $dsjupdate= $row->c;
            $this->db->set(
                array(
                    'i_sjp_old'   => $isjold,
                    'v_sjp'       => $vsjnetto,
                    'd_sjp'       => $dsj,
            'd_sjp_update'=> $dsjupdate

                )
            );
            $this->db->where('i_sjp',$isj);
            $this->db->where('i_area',$iarea);
            $this->db->update('tm_sjp');
        }

        function lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)
        {
          $query=$this->db->query(" SELECT n_quantity_awal, n_quantity_akhir, n_quantity_in, n_quantity_out 
                                    from tm_ic_trans
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    order by i_trans desc",false);
          if ($query->num_rows() > 0){
                    return $query->result();
                }
        }

        function cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)
        {
          $hasil='kosong';
          $query=$this->db->query(" SELECT i_product
                                    from tm_mutasi
                                    where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_product_motif='00'
                                    and i_store='$istore' and i_store_location='$istorelocation' and i_store_locationbin='$istorelocationbin'
                                    and e_mutasi_periode='$emutasiperiode'
                                  ",false);
          if ($query->num_rows() > 0){
                    $hasil='ada';
                }
          return $hasil;
        }
}


/* End of file Mmaster.php */
