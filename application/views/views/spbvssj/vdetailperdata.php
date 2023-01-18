<style type="text/css" media="all">

   .isinya {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 13px;
    
    }
    
    .kepala {
		font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 19px;
    font-weight: bold;
	}
	.kepalaxx {
		font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 11px;
	}
	.kepala2 {
		font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 13px;
	}
	
	.detailbrg2 {
		font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 12px;
	}
	.detailbrg {
		font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 10px;
	}
	
	.kotak {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 13px;
    border-collapse:collapse;
    border:1px solid black;
    }
    
    .kotak2 {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
    font-size: 12px;
    border-collapse:collapse;
    border:1px solid black;
    }
    
    .tabelheader {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
          font-size: 13px;}
    	
	.garisbawah { 
		border-bottom:#000000 0.1px solid;
	}
	.gariskanan { 
		border-right:#000000 0.1px solid;
	}
	
	.judulnomor {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
          font-size: 15px;}
</style>

<style type="text/css" media="print">
.noDisplay{
	display:none;
}
</style>
<h3 class="kepala">Detail Nomor SPB/SJ/Nota Area <?php echo $namaarea ?> <br>(Salesman: <?php echo $namasalesman ?> )</h3>
<h3 class="kepala2">Periode <?php echo $dfrom." s/d ".$dto ?> </h3>
<table border="1" width="100%" align="center" class="kotak">
	<tr align="center">
		<td>No</td>
		<td>Pelanggan</td>
		<td>No SPB</td>
		<td>Tgl SPB</td>
		<td>No SJ</td>
		<td>Tgl SJ</td>
		<td>Hari SPB->SJ</td>
		<td>No Nota</td>
		<td>Tgl Nota</td>
		<td>Hari Nota->SJ</td>
		<td>Nilai Penjualan (Rp.)</td>
	</tr>

	<?php 
		if (is_array($listdata)) {
			$no = 1;
			for($j=0;$j<count($listdata);$j++){
				echo "<tr>";
					echo "<td align='right'>".$no."&nbsp;</td>";
					echo "<td style='white-space:nowrap;'> &nbsp;".$listdata[$j]['icustomer']." - ".$listdata[$j]['ecustomername']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['ispb']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['dspb']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['isj']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['dsj']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['selisihsj']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['inota']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['dnota']."</td>";
					echo "<td> &nbsp;".$listdata[$j]['selisihnota']."</td>";
					echo "<td align='right'> &nbsp;".number_format($listdata[$j]['vnotagross'],'2','.',',')."&nbsp;</td>";
				echo "</tr>";
				$no++;
			}
		}
	?>
</table><br>
*Nilai SJ Sama Dengan Nilai NOTA
<div class="noDisplay"><center><b><a href="#" onClick="window.print()">Cetak</a></b></center></div>