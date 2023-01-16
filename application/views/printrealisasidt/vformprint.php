<?php 
include ("php/fungsi.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
    <title>Realisasi Daftar Tagihan</title>
</head>
<body>
    <style type="text/css" media="all">
/*
@page land {size: landscape;}
*/
*{
    size: landscape;
}
.pagebreak {
    page-break-before: always;
}
.huruf {
  FONT-FAMILY: Tahoma, Verdana, Arial, Helvetica, sans-serif;
}
.miring {
  font-style: italic;
  
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
    border-style: solid;
    border-width:0.01px;
    border-collapse: collapse;
    cellspacing:0.1px;
}
.garis td { 
    background-color:#FFFFFF;
    border-style: solid;
    border-width:0.01px;
    font-size: 12px;
    FONT-WEIGHT: normal; 
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
}
.eusi {
    font-size: 14px;
    font-weight:normal;
}
.garisbawah { 
    border-bottom:#000000 0.1px solid;
}
</style>
<style type="text/css" media="print">
    .noDisplay{
        display:none;
    }
</style>
<?php if($isi){
    foreach($isi as $row){?>
        <table width="1160px" border="0">
            <tr>
                <td width="112"><img src="<?php echo base_url().'assets/images/logo/'.$company->logo; ?>" width="115" height="45" alt="" /></td>
                <td colspan="3" valign="top"><strong><?= $company->name;?></strong></td>
                
                <td width="250">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>No. <?php echo $row->i_dt; ?> </td>
                <?php 
                $tmp=explode("-",$row->d_dt);
                $th =$tmp[0];
                $bl =$tmp[1];
                $hr =$tmp[2];
                $bl=mbulan($bl);
                if($row->i_area=='PB')$row->e_area_name='Bandung (PB)';
                ?>
                <td align="right"><?php echo $row->e_area_name.' , '.$hr.' '.$bl.' '.$th; ?> </td>
            </tr>
        </table>
        <CENTER>
        </CENTER>
        <table width="960px" cellspacing="0.1px" class="garis huruf">
            <tr>
                <td align="center" rowspan="3" class="huruf isi">NO</td>
                <td align="center" colspan="4" rowspan="2" class="huruf isi">FAKTUR</td>
                <td align="center" colspan="2" rowspan="2" class="huruf isi">DEBITUR</td>
                <td align="center" rowspan="3" class="huruf isi">JUMLAH</td>
                <td width="8" rowspan="3">&nbsp;</td>
                <td align="center" rowspan="3" class="huruf isi">TUNAI</td>
                <td align="center" colspan="6" class="huruf isi">DATA PEMBAYARAN</td>
            </tr>
            <tr>
                <td align="center" colspan="4" class="huruf isi">CEK / GIRO BILYET</td>
                <td align="center" rowspan="2" class="huruf isi">TOTAL</td>
                <td align="center" rowspan="2" class="huruf isi">CATATAN</td>
            </tr>
            <tr>
                <td align="center" class="huruf isi">NO</td>
                <td align="center" class="huruf isi">TGL</td>
                <td align="center" class="huruf isi">JT</td>
                <td align="center" class="huruf isi">SM</td>
                <td align="center" class="huruf isi">NAMA</td>
                <td align="center" class="huruf isi">KOTA</td>
                <td align="center" class="huruf isi">NO</td>
                <td align="center" class="huruf isi">BANK</td>
                <td align="center" class="huruf isi">JUMLAH</td>
                <td align="center" class="huruf isi">TGL</td>
            </tr>
            <?php 
            $i=0;
            $j=0;
            $jum=count($detail);
            $jumsisa =0;
            $jumtunai=0;
            $jumgiro =0;
            $jumtotal=0;
            $cekheader='';
            $grandtotal=0;
            $total=0;
            $v_sisa=0;
            foreach($detail as $rowi){
                if($row->d_dt>$rowi->d_alokasi && ($rowi->d_alokasi!=null||$rowi->d_alokasi='')){

                }else{
                    $i++;
                    $j++;
                    $tmp=explode("-",$rowi->d_nota);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $rowi->d_nota=$hr."-".$bl."-".substr($th,2,2);
                    $tmp=explode("-",$rowi->d_jatuh_tempo);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $rowi->d_jatuh_tempo=$hr."-".$bl."-".substr($th,2,2);
                    $jumsisa=$jumsisa+$rowi->v_sisa;
                    $v_sisa = number_format($rowi->v_sisa);
                    $jumlah=number_format($rowi->jumlahitem);
                    $nota=substr($rowi->i_nota,8,8);
                    $rowi->i_customer=substr($rowi->i_customer,2,3);
                    $rowi->e_customer_city=substr($rowi->e_customer_city,0,8);
                    echo "<tr height=27>";
                    echo "
                    <td align=right>$i</td>
                    <td>$nota</td>
                    <td>$rowi->d_nota</td>
                    <td>$rowi->d_jatuh_tempo</td>
                    <td>$rowi->i_customer</td>
                    <td>$rowi->e_customer_name</td>
                    <td>$rowi->e_customer_city</td>
                    <td align='right'>$v_sisa</td>
                    <td>&nbsp;</td>";
                    echo "<td>&nbsp;</td>";
                    echo"
                    <td>$rowi->i_giro</td>
                    <td></td>";
                    echo "<td>$jumlah</td>";
                    echo "<td>$rowi->d_alokasi</td>";
                    echo "<td>$jumlah</td>
                    <td>$rowi->i_alokasi / $rowi->e_remark</td>
                    </tr>";
                    $total = $rowi->jumlahitem;
                    $grandtotal = $grandtotal+$total;
                    ?>
                    <?php 
                }
            }
            ?>
        </table>
        <table width="1160px" border="0">
            <tr>
                <td width="110" class="huruf eusi">Sudah terima : </td>
                <td width="75" class="huruf eusi">Tunai</td>
                <td width="50">&nbsp;</td>
                <td width="45" class="huruf eusi">= Rp. </td>
                <td width="100">&nbsp;</td>
                <td width="775" colspan=4 class="huruf eusi"><div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ditagih Oleh :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diserahkan Oleh :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dibuat Oleh :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Diterima Oleh :</div></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class="huruf eusi">Giro / Cek </td>
                <td class="huruf eusi">=.......lbr</td>
                <td class="huruf eusi">= Rp. </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
          </tr>
          <tr>
              <td colspan="2" class="huruf eusi"><div align="center"><u>(............................)</u></div></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan=2 class="huruf eusi"><div align="center" class="garisbawah">(............................)&nbsp;&nbsp;(............................)</div></td>
              <td colspan=2 class="huruf eusi"><div align="center" class="garisbawah">(............................)&nbsp;&nbsp;(............................)</div></td>
              <tr>
                  <td colspan="2" class="huruf eusi"><div align="center">K a s i r</div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td colspan="2" class="huruf eusi"><div align="center">P e n a g i h</div></td>
                  <td colspan="2" class="huruf eusi"><div align="center">Adm . Keuangan</div></td>
              </tr>
          </table>
          <?php 
          break;
      }
  }
  ?>
  <div class="noDisplay"><center><b><a href="#" onClick="window.print()">Print</a></b></center></div>
</BODY>
</html>
