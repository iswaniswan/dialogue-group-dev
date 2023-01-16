<?php  include ("php/fungsi.php");?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>

<body>
    <style type="text/css" media="all">
        * {
            size: landscape;
        }

        @page {
            margin: 0.03in 0.37in 0.07in 0.26in;
        }

        .huruf {
            /*FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;*/
            font-family: : "Times New Roman", Times, serif;
        }

        .miring {
            font-style: italic;

        }

        .wrap {
            margin: 0 auto;
            text-align: left;
        }

        .ceKotak {
            - background-color: #f0f0f0;
            border-bottom: #80c0e0 1px solid;
            border-top: #80c0e0 1px solid;
            border-left: #80c0e0 1px solid;
            border-right: #80c0e0 1px solid;
        }

        .garis {
            background-color: #000000;
            width: 100%;
            height: 50%;
            font-size: 100px;
            border-style: solid;
            border-width: 0.01px;
            border-collapse: collapse;
            spacing: 1px;
        }

        .garis td {
            background-color: #FFFFFF;
            border-style: solid;
            border-width: 0.01px;
            font-size: 10px;
            FONT-WEIGHT: normal;
            padding: 1px;
        }

        .garisy {
            background-color: #000000;
            width: 100%;
            height: 50%;
            border-style: solid;
            border-width: 0.01px;
            border-collapse: collapse;
            spacing: 1px;
        }

        .garisy td {
            background-color: #FFFFFF;
            border-style: solid;
            border-width: 0.01px;
            padding: 1px;
        }

        .garisx {
            background-color: #000000;
            width: 100%;
            height: 50%;
            border-style: none;
            border-collapse: collapse;
            spacing: 1px;
        }

        .garisx td {
            background-color: #FFFFFF;
            border-style: none;
            font-size: 10px;
            FONT-WEIGHT: normal;
            padding: 1px;
        }

        .judul {
            font-size: 18px;
            FONT-WEIGHT: normal;
        }

        .catatan {
            font-size: 14px;
            FONT-WEIGHT: normal;
        }

        .nmper {
            margin-top: 0;
            font-size: 11px;
            FONT-WEIGHT: normal;
        }

        .isi {
            font-size: 11px;
            font-weight: normal;
        }

        .eusinya {
            font-size: 8px;
            font-weight: normal;
        }

        .garisbawah {
            border-bottom: #000000 0.1px solid;
        }

        .garisatas {
            border-top: #000000 0.1px solid;
        }

        .kotak {
            border-collapse: collapse;
            border-bottom-width: 0px;
            border-right-width: 0px;
            border-bottom: #000000 0.1px solid;
            border-left: #000000 0.1px solid;
            border-right: #000000 0.1px solid;
            border-top: #000000 0.1px solid;
        }

        .pagebreak {
            page-break-after: always;
        }

        .button {
            background-color: #008CBA; /* blue */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 4px 2px;
            cursor: pointer;
        }
        .button1 {
            font-size: 12px;
            border-radius: 8px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
        }
    </style>
    <style type="text/css" media="print">
        .noDisplay {
            display: none;
        }
    </style>
    <?php 
    foreach($isi as $row){?>
        <table width="100%" class="nmper" border="0">
            <tr>
                <td colspan="3" class="huruf judul"><?= strtoupper($company->name); ?></td>
                <td>KEPADA Yth.</td>
            </tr>

            <tr>
                <td colspan="3"><?php echo $company->alamat_company." ".$company->kota_company; ?></td>
                <td><?php echo rtrim($row->e_customer_name);?></td>
            </tr>

            <tr>
                <td colspan="3">Telp.&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->telepon_company; ?></td>
                <td><?php echo trim($row->e_customer_address);?></td>
            </tr>

            <tr>
                <td colspan="3">Fax.&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->fax_company; ?></td>
                <td><?php echo rtrim($row->e_customer_city);?></td>
            </tr>

            <tr>
                <td colspan="3">NPWP&nbsp;:&nbsp;<?php echo $company->npwp_company; ?></td>
                <td>
                    <?php 
                    if($row->f_customer_pkp=='t'){
                        echo "NPWP : ".$row->e_customer_pkpnpwp;
                    }else{
                        echo "";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="4" class="huruf judul" align="center" style="font-size: 20px">NOTA PENJUALAN</td>
            </tr>

            <tr>
                <td colspan="2" style="width: 33%"><?php echo "NO PO : ".trim($row->i_spb_po)?></td>
                <td style="width: 26%"><?php echo "No.FAK. / No.SJ ";?></td>
                <td>: <?php echo trim(substr($row->i_nota,8,7))."/".substr($row->i_sj,8,6)?></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td><?php echo "KODE SALES/KODELANG";?></td>
                <td>: <?php echo $row->i_salesman."/".$row->i_customer?></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>MASA PEMBAYARAN</td>
                <td>
                  <?php $xxx=$row->n_customer_toplength_print;
                  if(($xxx)>0){
                    echo ": ".$xxx." hari SETELAH BARANG DITERIMA";
                }else{
                    echo ": "."TUNAI";
                };?>
            </td>
        </tr>
    </table>

    <table>
        <tr align="center">
            <td colspan="4">
                <table width="98%" class="nmper" border="0">
                    <tr align="center">
                        <td class="garisatas garisbawah">
                            NO.
                        </td>
                        <td class="garisatas garisbawah">
                            KODE
                        </td>
                        <td width="800px" class="garisatas garisbawah">
                            NAMA BARANG
                        </td>
                        <td width="100px" class="garisatas garisbawah">
                            UNIT
                        </td>
                        <td width="100px" class="garisatas garisbawah">
                            HARGA
                        </td>
                        <td width="100px" class="garisatas garisbawah">
                            JUMLAH
                        </td>
                    </tr>
                    <?php 
                    $i        = 0;
                    $hrg      = 0;
                    $total    = 0;
                    $vdistot  = 0;
                    foreach($detail as $rowi){
                        $i++;
                        ?>
                        <tr>
                            <td width="25">
                                <?php echo $i;?>
                            </td>
                            <td width="20px">
                                <?php echo $rowi->i_product;?>
                            </td>
                            <td>
                                <?php 
                                if(strlen($rowi->e_product_name )>50){
                                    $nam  = substr($rowi->e_product_name,0,50);
                                }else{
                                    $nam  = $rowi->e_product_name.str_repeat(" ",50-strlen($rowi->e_product_name ));
                                }
                                echo $nam;?>
                            </td>
                            <td align="center">
                                <?php echo number_format($rowi->n_deliver);?>
                            </td>

                            <!-- HARGA BARANG -->
                            <td align="right">
                                <?php            
                                if($row->f_plus_ppn=='t'){
                                    $pric = ($rowi->v_unit_price);
                                }else{
                                    $pric = ($rowi->v_unit_price/1.1);

                                    $parts      = explode('.', (string)$pric);
                                    $hitung     = count($parts);

                                    if($hitung > 1){
                                        $belakang = round($parts[1]);
                                        $pric = $parts[0].".".$parts[1];
                                    }else{
                                       $pric = $parts[0].".00";
                                   }
                               }
                               echo number_format($pric,2,',','.') ;?>
                           </td>

                           <!-- HARGA UNIT X QTY BARANG -->
                           <td align="right"><?php 
                           /*TOTAL UNIT x HARGA*/
                           if($row->f_plus_ppn=='t'){
                              echo number_format($rowi->n_deliver*$rowi->v_unit_price,2,',','.');
                          }else{
                #echo number_format(($rowi->n_deliver*($rowi->v_unit_price/1.1)),2,',','.');
                              $sub = ($rowi->n_deliver*($rowi->v_unit_price/1.1));
                              $parts2     = explode('.', (string)$sub);
                              $hitung2    = count($parts2);

                              if($hitung2 > 1){
                                $belakang = round($parts2[1]);
                                $sub = $parts2[0].".".$parts2[1];
                            }else{
                                $sub = $parts2[0].".00";
                            }

                            echo number_format($sub,2,',','.') ;
                        }
              // SUBTOTAL DI ITEM
                        if($row->f_plus_ppn=='t'){
                          $tot  = ($rowi->n_deliver*$rowi->v_unit_price);
                      }else{
                #$tot  = number_format(($rowi->n_deliver*($rowi->v_unit_price/1.1)),2,',','.');
                          $tot  = ($rowi->n_deliver*($rowi->v_unit_price/1.1));
                      }

                      $total  = $total+$tot;
              #$tot=number_format($tot);
                      ?>
                  </td>
              </tr>
              <?php ;}

              /************* HARGA TOTAL *************/
              $parts3     = explode('.', (string)$total);
              $row->v_nota_gross = $parts3[0].".00";
      #$row->v_nota_gross = $total;
      #$tot  = number_format(($row->v_nota_gross-$row->v_nota_discounttotal),2,',','.');    
      #$tot  = ($row->v_nota_gross-$row->v_nota_discounttotal);    

############BACA JIKA ADA DISKON RUPIAH
              if(($row->n_nota_discount1>0 && ($row->n_nota_discount2==0 && $row->v_nota_discount2>0))){
                  $vdisc1= $row->v_nota_discount1;
                  $vdisc2= $row->v_nota_discount2;
                  $vdisc3= $row->v_nota_discount3;
                  $vdisc4= $row->v_nota_discount4;
              }else{
                  $vdisc1=0;
                  $vdisc2=0;
                  $vdisc3=0;
                  $vdisc4=0;
              }
##############################################

              /***CODINGAN LAMA HILANGKAN TANDA "#" COMMENT TANGGAL 30 MAR 2019 ***/
#      if($row->n_nota_discount1==0)
#          $vdisc1=$row->v_nota_discounttotal;
#      else
#          $vdisc1=0;
        //if( ($row->n_nota_discount1+$row->n_nota_discount3+$row->n_nota_discount3+$row->n_nota_discount4==0) && $row->v_nota_discounttotal <> 0 )
        //{
           // $vdisc1=$row->v_nota_discounttotal;
#            $vdisc2=0;
#            $vdisc3=0;
#            $vdisc4=0;
        //} else {
################################################

#############HITUNG DISKON RUPIAH
              if(($row->n_nota_discount1>0 && ($row->n_nota_discount2==0 && $row->v_nota_discount2>0))){
          #$vdistot  = $row->v_nota_discounttotal;
                  $vdistot  = ($vdisc1+$vdisc2+$vdisc3+$vdisc4);
              }else{
                  $vdisc1 = $vdisc1+($total*$row->n_nota_discount1)/100;
                  $vdisc2 = $vdisc2+((($total-$vdisc1)*$row->n_nota_discount2)/100);
                  $vdisc3 = $vdisc3+((($total-($vdisc1+$vdisc2))*$row->n_nota_discount3)/100);
                  $vdisc4 = $vdisc4+((($total-($vdisc1+$vdisc2+$vdisc3))*$row->n_nota_discount4)/100);
                  $vdistot  = ($vdisc1+$vdisc2+$vdisc3+$vdisc4);
              }
##################################################
              if( ($row->f_plus_ppn=='f') && ($row->n_nota_discount1==0) ){
                  $vdistot=$vdistot/1.1;
              }
              /* DISKON */          
              $parts4     = explode('.', (string)$vdistot);
              $hitung4    = count($parts4);

              $row->v_nota_discounttotal = $parts4[0].".00";

          #$row->v_nota_discounttotal = $vdistot;
          #$dis  = number_format($row->v_nota_discounttotal);
              $dis  = $row->v_nota_discounttotal;
              ?>
              <tr>
                  <td colspan="6" class="garisbawah">&nbsp;</td>
              </tr>
              <tr>
                  <td colspan="4">&nbsp;</td>
                  <td style="width: 15%">TOTAL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td align="right"><?php echo number_format($row->v_nota_gross,2,',','.');?></td>
              </tr>
              <tr>
                  <td colspan="4">&nbsp;</td>
                  <td>POTONGAN &nbsp;&nbsp;&nbsp;</td>
                  <td align="right"><?php echo number_format(($row->v_nota_discounttotal),2,',','.'); ?></td>
              </tr>
              <tr>
                  <td colspan="3">&nbsp;</td>
                  <td></td>
                  <td></td>
                  <td align="right">--------------------</td>
                  <td>-</td>
              </tr>

              <!-- PPN -->
              <?php 
              if($row->f_plus_ppn=='f'){
                  /* DPP */
          #$dpp = ($row->v_nota_gross - $row->v_nota_discounttotal);
                  $dpp = ($total - $vdistot);
                  $parts6     = explode('.', (string)$dpp);
                  $dpp = $parts6[0].".00";

                  /* PPN */  
                  $vppn=($total-$vdistot)*0.1;
        #$row->v_nota_ppn=$vppn;
                  $parts5     = explode('.', (string)$vppn);
                  $row->v_nota_ppn = $parts5[0].".00";

       # $row->v_nota_netto = (($row->v_nota_gross - $row->v_nota_discounttotal)+$row->v_nota_ppn);
                  $netto              = (($total-$vdistot)+$vppn);
                  $parts7             = explode('.', (string)$netto);
                  $row->v_nota_netto  = $parts7[0];
                  ?>
                  <tr>
                    <td colspan="4">&nbsp;</td>
                    <td>DPP &nbsp;&nbsp;&nbsp;</td>
                    <td align="right"><?php echo number_format(($dpp),2,',','.'); ?></td>
                </tr>

                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td>PPN(10%) &nbsp;&nbsp;&nbsp;</td>
                    <td align="right"><?php echo number_format(($row->v_nota_ppn),2,',','.'); ?></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <!-- <td align="right">----------------</td> -->
                    <td align="right">--------------------</td>
                    <td>+</td>
                </tr>
            <?php }?>

            <tr>
              <td colspan="4">&nbsp;</td>
              <td>NILAI FAKTUR</td>
              <td align="right"><?php echo number_format(($row->v_nota_netto),2,',','.'); ?></td>
          </tr>
      </table>

      <tr>
        <td colspan=4 class="huruf nmper">(<?php 
          $bilangan = new Terbilang;
    #$kata=ucwords($bilangan->eja($rowi->n_deliver*$rowi->v_unit_price));  
          $kata=ucwords($bilangan->eja($row->v_nota_netto));  
          $tmp=explode("-",$row->d_nota);
          $th=$tmp[0];
          $bl=$tmp[1];
          $hr=$tmp[2];
          $dnota=$hr." ".mbulan($bl)." ".$th;
          echo $kata." Rupiah";?>)</td>
      </tr>
  </table>
  <table width="100%" class="nmper" border="0">
    <tr>
      <td></td>
      <td colspan="0" align="center">&nbsp;</td>
      <td align="center"><?php echo "Bandung, ".$dnota;?>
  </tr>
  <tr>
      <td width="200px" align="center">
        Penerima
    </td>
    <td>&nbsp;</td>
    <td colspan="0" width="200px" align="center">
        S E & O
    </td>
</tr>
<tr align="center">
    <td colspan="4" class="huruf catatan"><?php echo "<br>"."<br>"."<br>";?>
</td>
</tr>
<tr>
  <td align="center">
    (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
</td>
<td>&nbsp;</td>
<td align="center" colspan="2"><?php echo "( ".$company->ttd_nota." )";?></td>
</tr>

<tr>
  <td colspan="3">Catatan :</td>
</tr>

<tr>
  <td colspan="6">1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, kecuali ada perjanjian
  terlebih dahulu</td>
</tr>

<tr>
  <td colspan="6">2. Faktur asli merupakan bukti pembayaran yang sah. <!-- (Harga sudah termasuk PPN) --></td>
</tr>

<tr>
  <td colspan="6">3. Pembayaran dengan cek/giro berharga baru dianggap sah setelah diuangkan/cair.</td>
</tr>

<tr>
  <td colspan="6">4. Pembayaran dapat ditransfer ke rekening :</td>
</tr>
</table>

<table class="nmper" style="font-size: 12px">
  <tr>
    <td style="width: 7%"></td>
    <td class="kotak"><?php echo $company->name;?> <br> <?php echo $company->bca_bdg;?> <br> <?php echo $company->bri_bdg;?></td>
</tr>
</table>
<br class="pagebreak">
<?php 
}
?>
<!-- <div class="noDisplay">
  <center><b><a href="#" onClick="window.print()">Print</a></b></center>
</div> -->
<div class="noDisplay"><center><b><a href="#" class="button button1" onClick="window.print();">Print</a></b></center></div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">  
  window.onafterprint = function (){
    var id    = '<?php echo $id ?>';
    $.ajax({
      type: "POST",
      url: "<?= site_url($folder.'/cform/update');?>",
      data: {
        'id'  : id,
    },
    success: function(data){
        opener.window.refreshview();
        setTimeout(window.close,0);
    },
    error:function(XMLHttpRequest){
        alert('fail');
    }
});
}
</script>