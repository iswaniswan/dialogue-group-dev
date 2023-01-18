<?php include ("php/fungsi.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//Ddiv XHTML 1.0 divansitional//EN" "http://www.w3.org/div/xhtml1/Ddiv/xhtml1-divansitional.ddiv">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
</head>
<body>
    <style type="text/css" media="all">
    @page { 
        size:Legal; margin: 0.29in 0.29in 0.29in 0.70in;
    }
    *{
        size: landscape;
    }
    body{
        margin: 0px 40px 0px 0px;  
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
    .garis div { 
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
    .garisy div { 
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
    .garisx div { 
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
        margin-left: 10px;
        font-size: 11px;
        font-family: Arial;
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
    .garissemua { 
        border:#000000 0.1px solid;
    }
    .gariskanan {
        border-right: 1px solid black;
    }
    .gariskiri {
        border-left: 1px solid black;
    }
    .button {
        background-color: #008CBA; /* Blue */
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

<table width="100%" style="font-size: 12px;">
	<tr><td colspan="5" widht="100%" align="center" valign="middle" style="color: black;"><b>BERITA ACARA PEMERIKSAAN KAS</b><br></td></tr>
	<tr>
        <td colspan="5" align="center" widht="100%" style="color: black;">
            Nama Perusahaan : <?php echo " <b>$company</b>";?><br>

            Tanggal : <?php echo date('d-m-Y');?><br>

            Waktu : <?php echo date("h:i:s a"); ?><br>

            No Opname : <?php echo $i_opname; ?>
            <hr color="black" width="100%" size="2px" style="margin-bottom: 0;"></hr>
        </td>
    </tr>
    <?php 
    $totalpisik = 0;
    foreach($opdetail as $row1) : 
        $totalpisik = $row1->v_kertas_100000 + $row1->v_kertas_50000 + $row1->v_kertas_20000 + $row1->v_kertas_10000 +
        $row1->v_kertas_5000 + $row1->v_kertas_2000 + $row1->v_kertas_1000 + $row1->v_kertas_500 +
        $row1->v_logam_1000 + $row1->v_logam_500 + $row1->v_logam_200 + $row1->v_logam_100 + $row1->v_logam_50;
        ?>
        <!-- ==== Uang kertas Start ==== -->

        <tr>
            <td rowspan="9" align="center" class="garissemua" >Uang Kertas</td>
            <td class="garisbawah gariskanan" align="center">PECAHAN</td>
            <td class="garisbawah gariskanan" align="center">JUMLAH<br>(LBR/KPG)</td>
            <td class="gariskanan">&nbsp;</td>
            <td class="garisbawah gariskanan" align="center">TOTAL</td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">100.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_100000; ?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_100000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">50.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_50000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_50000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">20.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_20000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_20000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">10.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_10000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_10000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">5.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_5000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_5000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">2.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_2000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_2000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">1.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_1000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_1000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">500</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_kertas_500?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_kertas_500); ?></td>
        </tr>
        <!-- ==== Uang kertas END ==== -->
        <!-- ==== Uang LOGAM Start ==== -->
        <tr>
            <td rowspan="6" align="center" class="garissemua" >Uang Logam</td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">1.000</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_logam_1000?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_logam_1000); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">500</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_logam_500?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_logam_500); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">200</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_logam_200?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_logam_200); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">100</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_logam_100?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_logam_100); ?></td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan" align="right">50</td>
            <td class="garisbawah gariskanan" align="center"><?php echo $row1->n_logam_50?></td>
            <td class="gariskanan" align="center">=</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row1->v_logam_50); ?></td>

        </tr>
        <!-- ==== Uang LOGAM END ==== -->
    <?php endforeach; ?>
    <tr>
        <td class="garisbawah gariskanan gariskiri" rowspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="gariskanan">&nbsp;</td>
    </tr>
    <?php foreach ($opkas as $row2) : $totalpisik += $row2->v_saldo_atm + $row2->v_saldo_lain;?>
        <tr>
            <td class="garisbawah garisatas">Saldo Kas Di ATM</td>
            <td class="garisbawah garisatas">&nbsp;</td>
            <td class="garisbawah garisatas gariskanan">&nbsp;</td>
            <td class="gariskanan garisbawah garisatas" align="right">Rp. <?php echo number_format($row2->v_saldo_atm); ?></td>
        </tr>
        <tr>
            <td class="garisbawah">Lain-lain</td>
            <td class="garisbawah">&nbsp;</td>
            <td class="garisbawah gariskanan">&nbsp;</td>
            <td class="gariskanan garisbawah" align="right">Rp. <?php echo number_format($row2->v_saldo_lain); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="garisbawah gariskiri gariskanan">TOTAL PHISIK UANG</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($totalpisik); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="garisbawah gariskiri gariskanan">NOTA BELUM DIBIAYAKAN <?= date('d-m-Y', strtotime($row2->d_nota_biayafrom));?> S/D <?=  date('d-m-Y', strtotime($row2->d_nota_biayato));?></td>
            <td class="garisbawah gariskanan gariskanan" align="right">Rp. <?php echo number_format($row2->n_nota_biaya);?></td>
        </tr>
        <tr>
            <td colspan="4" class="garisbawah gariskiri gariskanan">KASBON GANTUNG <?= date('d-m-Y', strtotime($row2->d_kasbon_gantungfrom));?> S/D <?=  date('d-m-Y', strtotime($row2->d_nota_biayato));?></td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row2->n_kasbon_gantung); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="garisbawah gariskanan gariskiri">TOTAL DANA YANG ADA</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($totalpisik + $row2->n_nota_biaya + $row2->n_kasbon_gantung); ?></td>
        </tr>
        <tr>
            <td colspan="4" class="garisbawah gariskanan gariskiri">DANA YANG SEHARUSNYA</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format($row2->n_dana_seharusnya);?>S</td>
        </tr>
        <tr>
            <td class="garisbawah gariskanan gariskiri" colspan="4">SELISIH LEBIH/KURANG</td>
            <td class="garisbawah gariskanan" align="right">Rp. <?php echo number_format(($totalpisik + $row2->n_nota_biaya + $row2->n_kasbon_gantung) - $row2->n_dana_seharusnya);?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <?php 
        $i = 1;
        $totalbank = 0;
        $tglbank = '';
        foreach ($opbank as $row):$totalbank += $row->n_saldo;     
            $pecahbank = explode("-",$row->d_bank);
            $thbank = $pecahbank[2]; 
            $blbank = $pecahbank[1]; 
            $tglbank = $pecahbank[0];
            $tglbank = $tglbank."-".$blbank."-".$thbank;
        endforeach;?>
        <td colspan="5" class="gariskanan gariskiri"> SALDO BANK TGL <b><?= date('d-m-Y', strtotime($tglbank));?></b> </td>
    </tr>
    <?php foreach ($opbank as $row): ?>
        <tr>
            <td class="gariskiri">&nbsp;</td>
            <td colspan="3" class="gariskanan">REKENING <?php echo $i." : ".$row->e_coa_name;?></td>
            <td class="gariskanan" align="right">Rp. <?php echo number_format($row->n_saldo);?></td>
        </tr>
        <?php $i++; 
    endforeach; ?>
    <tr>
        <td class="garisbawah gariskiri" colspan="4">&nbsp;</td>
        <td class="garisbawah garisatas gariskanan" align="right" >Rp. <?php echo number_format($totalbank);?></td>
    </tr>
    <tr>
        <td colspan="5" align="left" class="gariskanan gariskiri">Keterangan Selisih :</td>
    </tr>
    <tr>
        <td class="garisbawah gariskiri">&nbsp;</td>
        <?php foreach ($opkas as $key) {
            echo "<td colspan='4' class='garisbawah gariskanan' > ".$key->e_description." </td>";
        } ?>
    </tr>
</table>
<table width="100%"  style="font-size: 12px;">
    <tr>
        <td width="30%" align="center" class="gariskiri">Yang Meyaksikan</td>
        <td width="40%" align="center" >Mengetahui<br>Pimpinan Perusahaan</td>
        <td width="30%" align="center" class="gariskanan">Pemegang Kas</td>
    </tr>
    <tr>
        <td colspan="3" height="60px" class="gariskanan gariskiri">&nbsp;</td>
    </tr>
    <tr>
        <td class="garisbawah gariskiri" width="30%" align="center">(....................)</td>
        <td class="garisbawah" width="40%" align="center">(....................)</td>
        <td class="garisbawah gariskanan" width="30%" align="center">(....................)</td>
    </tr>
</table>
<div class="noDisplay"><center><b><a href="#" class="button button1" onClick="window.print()">Print</a></b></center></div>
