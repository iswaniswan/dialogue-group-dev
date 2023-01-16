<?php include ("php/fungsi.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
    <title>Untitled Document</title>
</head>
<body>
    <style type="text/css" media="all">
        *{
            size: landscape;
        }

        @page { size: Letter; }

        .huruf {
            FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
        }
        .miring {
            font-style: italic;

        }
        .ceKotak{
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
            font-size: 20px;
            FONT-WEIGHT: normal; 
        }
        .nmper {
            font-size: 18px;
            FONT-WEIGHT: normal; 
        }
        .isi {
            font-size: 14px;
            font-weight:normal;
            padding:1px;
        }
        .eusinya {
            font-size: 10px;
            font-weight:normal;
        }
        .garisbawah { 
            border-bottom:#000000 0.1px solid;
        }
        .prioritas {
            font-size: 50px;
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
    $ambilprioritas = $this->db->query("select f_prioritas from tr_customer where i_customer = '$row->i_customer' and f_prioritas = 't' ");
    if($ambilprioritas->num_rows() > 0){    
        $prioritas = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &#9733;";
    }else{
        $prioritas = '&nbsp;';
    }
    ?>
    <table width="100%" border="0" class="nmper">
        <tr>
          <td colspan="3" class="huruf judul" ><?= check_constant('NmPerusahaan'); ?></td>
          <td width="18">&nbsp;</td>
          <td width="24">&nbsp;</td>
          <td width="144">&nbsp;</td>
          <td width="16">&nbsp;</td>
          <td width="326" class="prioritas"><b><?php echo $prioritas; ?></b></td>
      </tr>
      <tr>
          <td colspan="3" class="huruf nmper">Surat Pemesanan Barang</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td class="huruf nmper">No.SPB</td>
          <td class="huruf nmper">:</td>
          <td class="huruf nmper"><?php echo substr($row->i_spb,9,6); ?></td>
      </tr>
      <tr>
          <td width="185" class="huruf nmper" align=center>(SPB)</td>
          <td width="11">&nbsp;</td>
          <td width="329">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td class="huruf nmper">Tgl.Pesan</td>
          <td class="huruf nmper">:</td>
          <td class="huruf nmper"><?php 
          $tmp=explode("-",$row->d_spb);
          $th=$tmp[0];
          $bl=$tmp[1];
          $hr=$tmp[2];
          $dspb=$hr." ".mbulan($bl)." ".$th;
          echo $dspb; ?></td>
      </tr>
      <tr>
          <td class="huruf nmper">Data Pelanggan</td>
          <td class="huruf nmper">:</td>
          <td class="huruf nmper"><?php echo $row->e_customer_classname;?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td class="huruf nmper">Kode Sales</td>
          <td class="huruf nmper">:</td>
          <td class="huruf nmper"><?php echo $row->i_salesman." - ".$row->e_salesman_name; ?></td>
      </tr>
      <tr>
          <td class="huruf nmper" colspan=3>( <?php echo $row->i_customer; ?> ) <?php echo $row->e_customer_name; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td class="huruf nmper">Top</td>
          <td class="huruf nmper">:</td>
          <td class="huruf nmper"><?php echo $row->n_spb_toplength; ?></td>
      </tr>
      <tr>
          <td class="huruf nmper" colspan=3><?php echo $row->e_customer_address; ?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
      </tr>
  </table>
  <br>
  <table>
      <tr>
        <td colspan=7 class=garisbawah>&nbsp;</td>
    </tr>
    <tr>
        <td width="56" class="huruf nmper">No Urut</td>
        <td width="125" class="huruf nmper">Kode Barang</td>
        <td width="370" class="huruf nmper">Nama Barang</td>
        <td width="117" class="huruf nmper">Banyak yg dipesan</td>
        <td width="103" class="huruf nmper">Harga Satuan</td>
        <td width="154" class="huruf nmper">Banyak yg dipenuhi</td>
        <td width="126" class="huruf nmper">Motif</td>
    </tr>
    <tr>
        <td colspan=7 class=garisbawah>&nbsp;</td>
    </tr>
</table>
<?php 
$i=0; 
if($detail){
    foreach($detail as $rowi){
      $i++;
      $hrg=number_format($rowi->v_unit_price);
      $prod = $rowi->i_product;
      if($rowi->i_product_status=='4') $rowi->e_product_name='* '.$rowi->e_product_name;
      if(strlen($rowi->e_product_name ) > 46){
        $name = $rowi->e_product_name.str_repeat(" ",1);
    }else{
        $name = $rowi->e_product_name.str_repeat(" ",46-strlen($rowi->e_product_name ));
    }
    $motif  = $rowi->e_remark;
    $orde = number_format($rowi->n_order);
    $deli = number_format($rowi->n_deliver);
    $aw   = 13;
    $pjg  = strlen($i);
    for($xx=1;$xx<=$pjg;$xx++){
        $aw=$aw-1;
    }
    $pjg  = strlen($orde);
    $spcord = 4;      
    for($xx=1;$xx<=$pjg;$xx++){
        $spcord = $spcord-1;
    }
    $pjg  = strlen($hrg);
    $spcprc = 13;
    for($xx=1;$xx<=$pjg;$xx++){
        $spcprc = $spcprc-1;
    }
    ?>
    <table width="1147">
      <tr>
        <td width="27" class="huruf nmper"><?php echo $i; ?></td>
        <td width="90" class="huruf nmper"><?php echo $prod; ?></td>
        <td width="445" class="huruf nmper"><?php echo $name; ?></td>
        <td width="102" class="huruf nmper"><?php echo $orde; ?></td>
        <td width="104" class="huruf nmper"><?php echo $hrg; ?></td>
        <td width="95" class="huruf nmper">__________</td>
        <td width="253" class="huruf nmper"><?php echo $motif; ?></td>
    </tr>
</table>
<?php 
}
}
$kotor=$row->v_spb;
$kotor=number_format($kotor);
$pjg=strlen($kotor);
$spckot=14;
for($xx=1;$xx<=$pjg;$xx++){
    $spckot=$spckot-1;
}
$spckot=str_repeat(" ",$spckot);

$nNDisc      = $row->n_spb_discount1 + $row->n_spb_discount2*(100-$row->n_spb_discount1)/100;
$nNDisc0     = $row->n_spb_discount3 + $row->n_spb_discount4*(100-$row->n_spb_discount3)/100;
$dis      = $nNDisc + (100-$nNDisc)*$nNDisc0/100;
$dis  = number_format($dis,2);
$pjg  = strlen($dis);
$spcdis = 6;
for($xx=1;$xx<=$pjg;$xx++){
    $spcdis = $spcdis-1;
}
$spcdis=str_repeat(" ",$spcdis);
$vdis = number_format($row->v_spb_discounttotal);
$pjg  = strlen($vdis);
$spcvdis  = 14;
for($xx=1;$xx<=$pjg;$xx++){
    $spcvdis = $spcvdis-1;
}
$spcvdis=str_repeat(" ",$spcvdis);

$nb = number_format($row->v_spb-$row->v_spb_discounttotal);
$pjg=strlen($nb);
$spcnb=14;
for($xx=1;$xx<=$pjg;$xx++){
    $spcnb=$spcnb-1;
}
$spcnb=str_repeat(" ",$spcnb);
?>
<table>
  <tr>
    <td colspan=7 class=garisbawah>&nbsp;</td>
</tr>
<tr>
 <td width="300" class="huruf nmper">Tanggal Daftar</td>
 <td width="9"  class="huruf nmper">:</td>
 <td width="194" class="huruf nmper"><?php echo $row->d_signin; ?></td>
 <td width="185">&nbsp;</td>
 <td width="230" class="huruf nmper">NILAI KOTOR</td>
 <td width="16" class="huruf nmper">:</td>
 <td width="119" align=right class="huruf nmper"><?php echo $spckot.$kotor; ?></td>
</tr>
<tr>
    <td width="300" class="huruf nmper">Plafon</td>
    <td width="9"  class="huruf nmper">:</td>
    <td width="194" class="huruf nmper">Rp <?php echo number_format($row->v_flapond); ?></td>
    <td width="185">&nbsp;</td>
    <td class="huruf nmper">POTONGAN (<?php echo "$dis"."$spcdis" ?> %)</td>
    <td class="huruf nmper">:</td>
    <td align=right class="huruf nmper"><?php echo $spcvdis.$vdis ?></td>
</tr>
<tr>
    <td width="300" class="huruf nmper">Rata-rata Keterlambatan Pelunasan</td>
    <td width="9"  class="huruf nmper">:</td>
    <td width="194" class="huruf nmper"><?php echo $row->n_ratatelat; ?> Hari</td>
    <td width="185">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align=right class="huruf nmper">------------------</td>
</tr>
<tr>
    <td width="300" class="huruf nmper">Saldo Piutang</td>
    <td width="9"  class="huruf nmper">:</td>
    <!--    <td width="194" class="huruf nmper">Rp <?php echo number_format($row->v_saldo); ?></td>-->
    <td width="194" class="huruf nmper">Rp <?php echo number_format($saldopiutang); echo "  "; echo "(".$notapiutang.")"; ?></td>
    <td width="185">&nbsp;</td>
    <td class="huruf nmper">NILAI BERSIH</td>

    <td class="huruf nmper">:</td>
    <td align=right class="huruf nmper"><?php echo $spcnb.$nb; ?></td>
</tr>
<?php 
$cuss = $this->mmaster->baca_nilai($row->i_customer);
foreach ($cuss->result() as $customm) {
    $nilai_spb = $customm->nilai_spb;
}
?>
<tr>
    <td width="300" class="huruf nmper">Jumlah SPB </td>
    <td width="9"  class="huruf nmper">:</td>
    <td width="194" class="huruf nmper"><?php echo number_format($nilai_spb); ?></td>
    <td width="185">&nbsp;</td>
    <td class="huruf nmper">&nbsp;</td>
    <td class="huruf nmper">&nbsp;</td>
    <td align=right class="huruf nmper">&nbsp;</td>
</tr> 
</table>

<table width="902">
    <?php 
    if($row->d_signin){
      $tmp=explode("-",$row->d_signin);
      $th=$tmp[0];
      $bl=$tmp[1];
      $hr=$tmp[2];
      $row->d_signin=$hr.'-'.$bl.'-'.$th;
  }
  ?>
</br>
<tr>
    <td width="300" class="huruf nmper">Penjualan</td>
    <td width="9"  class="huruf nmper">:</td>
    <?php 
    $per=substr($row->i_spb,4,4);
    $perth=substr($per,0,2);
    $perbl=substr($per,2,2);
    for($q=1;$q<=6;$q++){
        settype($perth,"integer");
        settype($perbl,"integer");
        $perbl=$perbl-1;
        if($perbl==0){
          $perbl=12;
          $perth=$perth-1;
      }      
      settype($perth,"string");
      settype($perbl,"string");
      $a=strlen($perth);
      while($a<2){
          $perth="0".$perth;
          $a=strlen($perth);
      }
      $a=strlen($perbl);
      while($a<2){
          $perbl="0".$perbl;
          $a=strlen($perbl);
      }
      $row->i_area=substr($row->i_customer,0,2);
      $nota='FP-'.$perth.$perbl.'-'.$row->i_area.'%';
      $thnota=$perth.$perbl;
      $tesi=0;
      $totalharibyr=0;
      $totalalokasi=0;

//==================datanotadanalokasi======================================//
      $this->db->select(" sum(v_nota_netto) as total, substring(i_nota,1,10) as no from tm_nota
        where i_nota like '$nota' and i_customer='$row->i_customer' 
        and f_nota_cancel='f' group by no",false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
          foreach($query->result() as $tes){
            $tesi=$tes->total;
           // $tesi=$tes->total;
            //$total=$tes->total;
            //$inot=$tes->no;
            //$ialok=$tes->i_alokasi;
            //$haribayar=$tes->haribayar;
            //$bykalokasi=$tes->bykalokasi;
            //$totalharibyr+=$tes->haribayar;
            //$totalalokasi+=$tes->bykalokasi;
            //$totall=round($totalharibyr/$totalalokasi);

        }
    }
//====================hitungrataratabayarhari================================//
    $ratarata=0;
    $this->db->select(" round(totalharibyr/totalalokasi) as ratarata from( 
        select sum(haribayar) as totalharibyr, sum(bykalokasi) as totalalokasi from( 
        select distinct on (a.total, a.no, a.i_alokasi, a.haribayar) a.total, a.no, a.i_alokasi, a.haribayar, count(a.i_alokasi) as bykalokasi from( 
        SELECT sum(d.v_nota_netto) as total, d.i_nota as no, c.i_alokasi, (c.d_alokasi)-(d.d_nota) as haribayar, 0 as bykalokasi 
        from tm_alokasi_item b, tm_alokasi c, tm_nota d 
        where b.i_alokasi=c.i_alokasi and b.i_nota=d.i_nota and c.f_alokasi_cancel='f' and d.i_nota like '%$nota%' 
        and c.i_customer='$row->i_customer' and c.i_customer=d.i_customer 
        group by d.v_nota_netto, d.i_nota, c.i_alokasi, c.d_alokasi, d.d_nota) as a 
        group by a.total, a.no, a.i_alokasi, a.haribayar) as b ) as c",false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $tes2){
          $ratarata=$tes2->ratarata;
      }
  }
  $peri=substr(mbulan($perbl),0,3).'-20'.$perth;
  $spasi    = 12;
  $pjg  = strlen(number_format($tesi));
  for($xx=1;$xx<=$pjg;$xx++){
      $spasi=$spasi-1;
  }
  $spasi=str_repeat(" ",$spasi);
  if($ratarata==null)$ratarata=0;
      // echo " $peri --> $spasi $tesi $spasi $ratarata/hari $spasi | $spasi";
  switch($q){
    case 1:
    echo "<td width=\"261\">$peri --> $spasi $tesi $spasi $ratarata/Hari";
    break;
    case 2:
    echo "<td width=\"261\">$peri --> $spasi $tesi $spasi $ratarata/Hari</td>";
    break;
    case 3:
    echo "<td width=\"261\">$peri --> $spasi $tesi $spasi $ratarata/Hari</td></tr>";
    break;
    case 4:
    echo "<tr><td colspan=2></td><td width=\"261\">$peri --> $spasi $tesi $spasi $ratarata/Hari</td>";
    break;
    case 5:
    echo "<td width=\"261\">$peri --> $spasi $tesi $spasi $ratarata/Hari</td>";
    break;
    case 6:
    echo "<td width=\"261\">$peri --> $spasi $tesi $spasi $ratarata/Hari</td></tr>";
    break;
}
}
?>
<table class="garisy" width="903">
  <tr>
    <td rowspan=2 align=center class="huruf isi">Tgl & Jam Terima SPB</td>
    <td colspan=3 align=center class="huruf isi">GUDANG</td>
    <td colspan=3 align=center class="huruf isi">Serah Terima Gudang</td>
    <td rowspan=2 width="72" align=center class="huruf isi">MD</td>
    <td colspan=3 align=center class="huruf isi">Tgl&Jam Terima Nota</td>
    <td colspan=3 align=center class="huruf isi">CEK PLAFON</td>
</tr>
<tr>
    <td width="59" align=center class="huruf isi">CEK I</td>
    <td width="61" align=center class="huruf isi">CEK II</td>
    <td width="62" align=center class="huruf isi">CEK III</td>
    <td width="61" align=center class="huruf isi">I</td>
    <td width="64" align=center class="huruf isi">II</td>
    <td width="64" align=center class="huruf isi">III</td>
    <td width="53" align=center class="huruf isi">I</td>
    <td width="53" align=center class="huruf isi">II</td>
    <td width="54" align=center class="huruf isi">III</td>
    <td width="48" align=center class="huruf isi">AR</td>
    <td width="54" align=center class="huruf isi">FADH</td>
    <td width="43" align=center class="huruf isi">SDH</td>
</tr>
<tr>
    <td align=center width=95 height=80>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
    <td align=center>&nbsp;</td>
</tr>
</table>
<?php echo '<br>'.$row->e_remark1; ?>
<?php 
}
?>
<div class="noDisplay"><center><b><a href="#" class="button button1" onClick="window.print();">Print</a></b></center></div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">  
    window.onafterprint = function (){
        var id    = '<?php echo $ispb ?>';
        var iarea = '<?php echo $iarea ?>';
        $.ajax({
            type: "POST",
            url: "<?= site_url($folder.'/cform/update');?>",
            data: {
                'id'  : id,
                'iarea' : iarea,
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