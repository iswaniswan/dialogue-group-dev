<?php include ("php/fungsi.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>
<body>
    <style type="text/css" media="all">
        *{
            size: landscape;
        }

        @page { size: Letter; 
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        .huruf {
            FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
        }
        .miring {
            font-style: italic;

        }
        .wrap {
          margin: 0 auto;
          text-align: left;
      }

      .ceKotak{-
          background-color:#f0f0f0;
          border-bottom:#80c0e0 1px solid;
          border-top:#80c0e0 1px solid;
          border-left:#80c0e0 1px solid;
          border-right:#80c0e0 1px solid;
      }
      .garis { 
          background-color:#000000;
          width: 100%;
          height: 50%;
          font-size: 100px;
          border-style: solid;
          border-width:0.01px;
          border-collapse: collapse;
          spacing:1px;
      }
      .garis td { 
          background-color:#FFFFFF;
          border-style: solid;
          border-width:0.01px;
          font-size: 10px;
          FONT-WEIGHT: normal; 
          padding:1px;
      }
      .garisy { 
          background-color:#000000;
          width: 100%;
          height: 50%;
          border-style: solid;
          border-width:0.01px;
          border-collapse: collapse;
          spacing:1px;
      }
      .garisy td { 
          background-color:#FFFFFF;
          border-style: solid;
          border-width:0.01px;
          padding:1px;
      }
      .garisx { 
          background-color:#000000;
          width: 100%;
          height: 50%;
          border-style: none;
          border-collapse: collapse;
          spacing:1px;
      }
      .garisx td { 
          background-color:#FFFFFF;
          border-style: none;
          font-size: 10px;
          FONT-WEIGHT: normal; 
          padding:1px;
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
          font-size: 12px;
          FONT-WEIGHT: normal; 
      }
      .isi {
          font-size: 12px;
          font-weight:normal;
      }
      .eusinya {
          font-size: 8px;
          font-weight:normal;
      }
      .garisbawah { 
          border-bottom:#000000 0.1px solid;
      }
      .garisatas { 
          border-top:#000000 0.1px solid;
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
    .noDisplay{
      display:none;
  }
  .pagebreak {
      page-break-before: always;
  }
</style>
<?php 
foreach($isi as $row)
{
    $row->f_plus_ppn = 't';
    ?>
    <table width="100%" class="nmper" border="0">
      <tr>
        <td colspan="3" class="huruf judul" ><?php echo $company->name; ?></td>
        <td >Kepada Yth.</td>
    </tr>
    <tr>
       <?php if($row->f_customer_pkp=="t"){?>
        <td colspan="3"><?php echo $company->alamat_company." ".$company->kota_company; ?></td>
        <td><?php echo rtrim($row->e_customer_pkpname);?></td>
    <?php }else{ ?>

        <td colspan="3"><?php echo $company->alamat_company." ".$company->kota_company; ?></td>
        <td><?php echo rtrim($row->e_customer_ownername);?></td>

    <?php } ?>
</tr>
<tr>
  <td colspan="3">Telp.&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->telp_company; ?></td>
  <td><?php echo trim($row->e_customer_address);?></td>
</tr>
<tr>
  <td colspan="3">Fax.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<?php echo $company->fax_company; ?></td>
  <td><?php echo rtrim($row->e_customer_city);?></td>
</tr>
<tr>
  <td colspan="3">NPWP.&nbsp;:&nbsp;<?php echo $company->npwp_company; ?></td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="3">BCA CABANG CIMAHI - BANDUNG</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="3">NO.AC &nbsp;:&nbsp;139.300.1236</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="4" class="huruf judul" align="center">NOTA PENJUALAN</td>
</tr>
<tr>
  <td colspan="4">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" ><?php echo "NO PO : ".trim($row->i_spb_po)?></td>
  <td colspan="2"><?php echo "No.FAK. / No.SJ     : ".trim(substr($row->i_nota,8,7))."/".substr($row->i_sj,8,6);?></td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
  <td colspan="2"><?php echo "KODE SALES/KODELANG : ".$row->i_salesman."/".$row->i_customer;?></td >
</td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td colspan="2"><?php $xxx=$row->n_customer_toplength_print;
    if(($xxx)>0){
     echo "MASA PEMBAYARAN     : ".$xxx." hari SETELAH BARANG DITERIMA";
 }else{
     echo "MASA PEMBAYARAN     : "."TUNAI";
 };?></td >
</td>
</tr>
<tr align="center">
  <td colspan="4">
    <table width="98%" class="nmper" border="0">
      <tr>
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
  $i  = 0;
  $hrg= 0;
  $total=0;
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
  <td>
    <?php 
    echo number_format($rowi->n_deliver);?>
</td>
<td>
    <?php            
    if($row->f_plus_ppn=='t'){
     echo number_format($rowi->v_unit_price);
 }else{
     echo number_format($rowi->v_unit_price/1.1);
 };?>
</td>
<td><?php 
if($row->f_plus_ppn=='t'){
  echo number_format($rowi->n_deliver*$rowi->v_unit_price);
  $tot  = $rowi->n_deliver*$rowi->v_unit_price;
}else{
  echo number_format($rowi->n_deliver*($rowi->v_unit_price/1.1));
  $tot  = $rowi->n_deliver*($rowi->v_unit_price/1.1);
}
?>
</td>
</tr>
<?php 
$total = $total+$tot;
}?>
<tr>
    <td colspan="6" class="garisbawah">&nbsp;</td>
</tr>
<tr>
    <td colspan="4">&nbsp;</td>
    <td colspan="2">TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php 
    $row->v_nota_gross=$total;
    echo number_format($row->v_nota_gross);
    ?></td>
</tr>
<?php 
if( ($row->n_nota_discount1+$row->n_nota_discount2+$row->n_nota_discount3+$row->n_nota_discount4==0) && $row->v_nota_discounttotal <> 0 )
{
    $vdisc1=$row->v_nota_discounttotal;
    $vdisc2=0;
    $vdisc3=0;
    $vdisc4=0;
    $vdistot   = round($vdisc1+$vdisc2+$vdisc3+$vdisc4);
    if( ($row->f_plus_ppn=='f') ){
      $vdistot=$vdistot/1.1;
  }
}
else
{
   $vdisc1=($total*$row->n_nota_discount1)/100;
   $vdisc2=((($total-$vdisc1)*$row->n_nota_discount2)/100);
   $vdisc3=((($total-($vdisc1+$vdisc2))*$row->n_nota_discount3)/100);
   $vdisc4=((($total-($vdisc1+$vdisc2+$vdisc3))*$row->n_nota_discount4)/100);
   $vdistot   = round($vdisc1+$vdisc2+$vdisc3+$vdisc4);
}
$row->v_nota_discounttotal=$vdistot;
?>
<tr>
  <td colspan="4">&nbsp;</td>
  <td colspan="2">POTONGAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?php echo number_format($row->v_nota_discounttotal); ?></td>
</tr>
<?php 
if($row->f_plus_ppn=='f'){
  $vppn=(round($total)-round($vdistot))*0.1;
  $row->v_nota_ppn=$vppn;
  ?>
  <tr>
    <td><?php 
    echo $row->v_nota_ppn;
    ?></td>
</tr>
<?php 
}
?>    
<tr>
  <td colspan="4">&nbsp;</td>
  <td colspan="2">---------------------------------------------</td>
</tr>
<?php 
if($row->f_plus_ppn=='f'){
  $row->v_nota_netto=round($row->v_nota_gross)-round($row->v_nota_discounttotal)+$vppn;
}else{
  $row->v_nota_netto=$row->v_nota_gross-$row->v_nota_discounttotal;
}
?>
<tr>
  <td colspan="4">&nbsp;</td>
  <td colspan="2" class="">NILAI FAKTUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php 
  echo number_format($row->v_nota_netto);
  ?></td>
</tr>
</table>
<tr>
  <td colspan=3>(<?php 
    $bilangan = new Terbilang;
    $kata=ucwords($bilangan->eja($row->v_nota_netto));  
    $tmp=explode("-",$row->d_nota);
    $th=$tmp[0];
    $bl=$tmp[1];
    $hr=$tmp[2];
    $dnota=$hr." ".mbulan($bl)." ".$th;
    echo $kata." Rupiah";?>)</td>
</tr>
</table >
<table width="100%" class="nmper" border="0">
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
    <td align="right"><?php echo "Bandung, ".$dnota;?></td>
</tr>
<tr>
    <td width="200px" align="center">
      Penerima
  </td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td colspan="2" width="200px" align="center">
      S E & O
  </td>
</tr>
<tr align="center">
    <td colspan="4" class="huruf catatan"><?php echo "<br>"."<br>"."<br>";?>
</td>
</tr>
<tr>
  <td align="center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="center" colspan="2"><?php echo "( ".$company->ttd_nota." )";?></td>
</tr>
<tr>
  <td colspan="3">Catatan :</td>
</tr>
<tr>
  <td colspan="6">1. Barang-barang yang sudah dibeli tidak dapat ditukar/dikembalikan, kecuali ada perjanjian terlebih dahulu</td>
</tr>
<tr>
  <td colspan="6">2. Faktur asli merupakan bukti pembayaran yang sah. (Harga sudah termasuk PPN)</td>
</tr> 
<tr>
  <td colspan="6">3. Pembayaran dengan cek/giro berharga baru dianggap sah setelah diuangkan/cair.</td>
</tr>
<tr>
  <td colspan="6">4. Pembayaran dapat ditransfer atas nama PT DIALOGUE GARMINDO UTAMA ke Rekening :</td>
</tr>
<tr>
  <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $company->bca_cmh;?></td>
</tr>
<tr>
  <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $company->bri_bdg;?></td>
</tr>
</table>

<?php    
}
?>
<!-- <div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div> -->
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