<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
    <p class="text-muted" style="text-align: center;">Periode : <?= $iperiode;?></p>
    <div class="panel-body table-responsive">
        <table class="table color-bordered-table info-bordered-table display nowrap" id="sitabel" cellpadding="0"
            cellspacing="0" border="1">
            <thead>
                <?php if($sales){ ?>
                <tr>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>No</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>Salesman</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>Target (Rp)</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>Blm Bayar (Rp)</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=6>Realisasi</th>
                </tr>

                <tr>
                    <th style="font-size: 12px;text-align: center;" colspan=2>Tidak Telat</th>
                    <th style="font-size: 12px;text-align: center;" colspan=2>Telat</th>
                    <th style="font-size: 12px;text-align: center;" colspan=2>Total</th>
                </tr>

                <tr>
                    <th style="font-size: 12px;text-align: center;">Jumlah (Rp)</th>
                    <th style="font-size: 12px;text-align: center;">Persen</th>
                    <th style="font-size: 12px;text-align: center;">Jumlah (Rp)</th>
                    <th style="font-size: 12px;text-align: center;">Persen</th>
                    <th style="font-size: 12px;text-align: center;">Jumlah (Rp)</th>
                    <th style="font-size: 12px;text-align: center;">Persen</th>
                </tr>
            </thead>

            <tbody>
                <?php 
					if($sales)
					{
						$no			= 0;
						$ttotal		= 0;
						$treal		= 0;
						$tpers		= 0;
						$tblm		= 0;
						$ttelat		= 0;
						$trealtelat	= 0;
						$ttdk		= 0;
						$trealtdk	= 0;
						
						foreach($sales as $row){
							$no++;
							$ttotal		= $ttotal+$row->total;
							$treal 		= $treal+$row->realisasi;
							$tblm		= $tblm+$row->blmbayar;
							$trealtelat = $trealtelat+$row->realisasitelat;
							$trealtdk 	= $trealtdk+$row->realisasitdktelat;
							$ttelat 	= $ttelat+$row->telat;
							$ttdk 		= $ttdk+$row->tdktelat;

							if($row->total!=0){
								$persen			= number_format(($row->realisasi/$row->total)*100,2);
								$persentdktelat	= number_format(($row->realisasitdktelat/$row->total)*100,2);
								$persentelat	= number_format(($row->realisasitelat/$row->total)*100,2);
							}else{
								$persen			= '0';
								$persentdktelat	= '0';
								$persentelat	= '0';
							}
							
							echo "<tr>
									<td>$no</td>
									<td>$row->e_salesman_name - $row->i_salesman</td>
									<td align=right>".number_format($row->total)."</td>
									<td align=right>".number_format($row->blmbayar)."</td>";
							echo "
									<td align=right>".number_format($row->realisasitdktelat)."</td>
									<td align=right>".number_format($persentdktelat,2)." %</td>
									<td align=right>".number_format($row->realisasitelat)."</td>	
									<td align=right>".number_format($persentelat,2)." %</td>
									<td align=right>".number_format($row->realisasi)."</td>
									<td align=right>".number_format($persen,2)." %</td>
								</tr>";
						}
						
						if($ttotal!=0){
							$persen=number_format(($treal/$ttotal)*100,2);
						}else{
							$persen='0';
						}
					}
				?>
            </tbody>
        </table>
        <?php 
				}else{
					echo "<center><h2>Target Collection belum ada</h2></center>";
				}
		?>
    </div>

    <script language="javascript" type="text/javascript">
    function bbatal(a) {
        show("listtargetcollection/cform/view/" + a + "/", "#main");
    }

    function yyy() {
        lebar = 1024;
        tinggi = 768;
        periode = document.getElementById("iperiode").value;
        area = document.getElementById("iarea").value;
        eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollection/cform/cetaksales/"+periode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top=' +
            (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }
    </script>