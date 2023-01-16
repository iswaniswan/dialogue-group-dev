<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
	<h3 class="box-title"><?= $title; ?></h3>
	<p class="text-muted">Periode : <?= $dfrom." s/d ".$dto;?></p>
	<div class="panel-body table-responsive">
		<table class="table color-bordered-table info-bordered-table" id="sitabel" cellpadding="0" cellspacing="0">
			<thead>
				<?php if($isi){ ?>
					<tr>
						<th style="font-size: 12px;"  rowspan=2>AREA</th>
						<th style="font-size: 12px;"  rowspan=2>K-LANG</th>
						<th style="font-size: 12px;"  rowspan=2>KOTA/KAB</th>
						<th style="font-size: 12px;"  rowspan=2>JENIS</th>
						<th style="font-size: 12px;"  rowspan=2>NAMA LANG</th>
						<th style="font-size: 12px;"  rowspan=2>ALAMAT</th>
						<?php if($dfrom!=''){
							$tmp=explode("-",$dfrom);
							$blasal=$tmp[1];
							settype($bl,'integer');
						}
						$bl = $blasal;?>
						<th style="font-size: 12px;"  style="text-align: center;" colspan="<?= $interval; ?>">SPB</th>
						<th style="font-size: 12px;"  rowspan=2>Total SPB</th>
					</tr>
					<tr>
						<?php 
						$loop=1;
						for($i=1;$i<=$interval;$i++){
							switch ($bl){
								case '1' :
								echo '<th style="font-size: 12px;" >Jan</th>';
								break;
								case '2' :
								echo '<th style="font-size: 12px;" >Feb</th>';
								break;
								case '3' :
								echo '<th style="font-size: 12px;" >Mar</th>';
								break;
								case '4' :
								echo '<th style="font-size: 12px;" >Apr</th>';
								break;
								case '5' :
								echo '<th style="font-size: 12px;" >Mei</th>';
								break;
								case '6' :
								echo '<th style="font-size: 12px;" >Jun</th>';
								break;
								case '7' :
								echo '<th style="font-size: 12px;" >Jul</th>';
								break;
								case '8' :
								echo '<th style="font-size: 12px;" >Agu</th>';
								break;
								case '9' :
								echo '<th style="font-size: 12px;" >Sep</th>';
								break;
								case '10' :
								echo '<th style="font-size: 12px;" >Okt</th>';
								break;
								case '11' :
								echo '<th style="font-size: 12px;" >Nov</th>';
								break;
								case '12' :
								echo '<th style="font-size: 12px;" >Des</th>';
								break;
							}
							$bl++;
							if($bl==13){
								$bl=1;
								$loop++;
							}
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php 
					$subtot01=0;
					$subtot02=0;
					$subtot03=0;
					$subtot04=0;
					$subtot05=0;
					$subtot06=0;
					$subtot07=0;
					$subtot08=0;
					$subtot09=0;
					$subtot10=0;
					$subtot11=0;
					$subtot12=0;
					$grandtot01=0;
					$grandtot02=0;
					$grandtot03=0;
					$grandtot04=0;
					$grandtot05=0;
					$grandtot06=0;
					$grandtot07=0;
					$grandtot08=0;
					$grandtot09=0;
					$grandtot10=0;
					$grandtot11=0;
					$grandtot12=0;
					$totarea01=0;
					$totarea02=0;
					$totarea03=0;
					$totarea04=0;
					$totarea05=0;
					$totarea06=0;
					$totarea07=0;
					$totarea08=0;
					$totarea09=0;
					$totarea10=0;
					$totarea11=0;
					$totarea12=0;
					if($loop>1){
						$lsubtot01=0;
						$lsubtot02=0;
						$lsubtot03=0;
						$lsubtot04=0;
						$lsubtot05=0;
						$lsubtot06=0;
						$lsubtot07=0;
						$lsubtot08=0;
						$lsubtot09=0;
						$lsubtot10=0;
						$lsubtot11=0;
						$lsubtot12=0;
						$lgrandtot01=0;
						$lgrandtot02=0;
						$lgrandtot03=0;
						$lgrandtot04=0;
						$lgrandtot05=0;
						$lgrandtot06=0;
						$lgrandtot07=0;
						$lgrandtot08=0;
						$lgrandtot09=0;
						$lgrandtot10=0;
						$lgrandtot11=0;
						$lgrandtot12=0;
						$ltotarea01=0;
						$ltotarea02=0;
						$ltotarea03=0;
						$ltotarea04=0;
						$ltotarea05=0;
						$ltotarea06=0;
						$ltotarea07=0;
						$ltotarea08=0;
						$ltotarea09=0;
						$ltotarea10=0;
						$ltotarea11=0;
						$ltotarea12=0;
					}
					$icity='';
					$kode='';
					$totkota=0;
					$totarea=0;
					$grandtotkota=0;

					foreach($isi as $row){
						$total=0;

						if($icity=='' || ($icity==$row->icity && $kode==substr($row->kode,0,2)) ){
							echo "<tr>
							<td style='font-size: 12px;'>".substr($row->kode,0,2)."-".$row->area."</td>
							<td style='font-size: 12px;'>$row->kode</td>
							<td style='font-size: 12px;'>$row->kota</td>
							<td style='font-size: 12px;'>$row->jenis</td>
							<td style='font-size: 12px;'>$row->nama</td>
							<td style='font-size: 12px;'>$row->alamat</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									$total=$total+$row->spbjan;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjan).'</th>';
									$subtot01=$subtot01+$row->spbjan;
									$totarea01=$totarea01+$row->spbjan;
									$grandtot01=$grandtot01+$row->spbjan;
									$totkota=$totkota+$row->spbjan;
									$totarea=$totarea+$row->spbjan;
									break;
									case '2' :
									$total=$total+$row->spbfeb;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbfeb).'</th>';
									$subtot02=$subtot02+$row->spbfeb;
									$totarea02=$totarea02+$row->spbfeb;
									$grandtot02=$grandtot02+$row->spbfeb;
									$totkota=$totkota+$row->spbfeb;
									$totarea=$totarea+$row->spbfeb;
									break;
									case '3' :
									$total=$total+$row->spbmar;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbmar).'</th>';
									$subtot03=$subtot03+$row->spbmar;
									$totarea03=$totarea03+$row->spbmar;
									$grandtot03=$grandtot03+$row->spbmar;
									$totkota=$totkota+$row->spbmar;
									$totarea=$totarea+$row->spbmar;
									break;
									case '4' :
									$total=$total+$row->spbapr;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbapr).'</th>';
									$subtot04=$subtot04+$row->spbapr;
									$totarea04=$totarea04+$row->spbapr;
									$grandtot04=$grandtot04+$row->spbapr;
									$totkota=$totkota+$row->spbapr;
									$totarea=$totarea+$row->spbapr;
									break;
									case '5' :
									$total=$total+$row->spbmay;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbmay).'</th>';
									$subtot05=$subtot05+$row->spbmay;
									$totarea05=$totarea05+$row->spbmay;
									$grandtot05=$grandtot05+$row->spbmay;
									$totkota=$totkota+$row->spbmay;
									$totarea=$totarea+$row->spbmay;
									break;
									case '6' :
									$total=$total+$row->spbjun;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjun).'</th>';
									$subtot06=$subtot06+$row->spbjun;
									$totarea06=$totarea06+$row->spbjun;
									$grandtot06=$grandtot06+$row->spbjun;
									$totkota=$totkota+$row->spbjun;
									$totarea=$totarea+$row->spbjun;
									break;
									case '7' :
									$total=$total+$row->spbjul;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjul).'</th>';
									$subtot07=$subtot07+$row->spbjul;
									$totarea07=$totarea07+$row->spbjul;
									$grandtot07=$grandtot07+$row->spbjul;
									$totkota=$totkota+$row->spbjul;
									$totarea=$totarea+$row->spbjul;
									break;
									case '8' :
									$total=$total+$row->spbaug;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbaug).'</th>';
									$subtot08=$subtot08+$row->spbaug;
									$totarea08=$totarea08+$row->spbaug;
									$grandtot08=$grandtot08+$row->spbaug;
									$totkota=$totkota+$row->spbaug;
									$totarea=$totarea+$row->spbaug;
									break;
									case '9' :
									$total=$total+$row->spbsep;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbsep).'</th>';
									$subtot09=$subtot09+$row->spbsep;
									$totarea09=$totarea09+$row->spbsep;
									$grandtot09=$grandtot09+$row->spbsep;
									$totkota=$totkota+$row->spbsep;
									$totarea=$totarea+$row->spbsep;
									break;
									case '10' :
									$total=$total+$row->spboct;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spboct).'</th>';
									$subtot10=$subtot10+$row->spboct;
									$totarea10=$totarea10+$row->spboct;
									$grandtot10=$grandtot10+$row->spboct;
									$totkota=$totkota+$row->spboct;
									$totarea=$totarea+$row->spboct;
									break;
									case '11' :
									$total=$total+$row->spbnov;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbnov).'</th>';
									$subtot11=$subtot11+$row->spbnov;
									$totarea11=$totarea11+$row->spbnov;
									$grandtot11=$grandtot11+$row->spbnov;
									$totkota=$totkota+$row->spbnov;
									$totarea=$totarea+$row->spbnov;
									break;
									case '12' :
									$total=$total+$row->spbdes;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbdes).'</th>';
									$subtot12=$subtot12+$row->spbdes;
									$totarea12=$totarea12+$row->spbdes;
									$grandtot12=$grandtot12+$row->spbdes;
									$totkota=$totkota+$row->spbdes;
									$totarea=$totarea+$row->spbdes;
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}

						}elseif( $kode!=substr($row->kode,0,2) ){
							echo "<tr>
							<td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   K o t a</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</th>';
									break;
									case '2' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
									break;
									case '3' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</th>';
									break;
									case '4' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</th>';
									break;
									case '5' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</th>';
									break;
									case '6' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</th>';
									break;
									case '7' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</th>';
									break;
									case '8' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</th>';
									break;
									case '9' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</th>';
									break;
									case '10' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</th>';
									break;
									case '11' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</th>';
									break;
									case '12' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</th>';
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</th></tr>';
							$grandtotkota=$grandtotkota+$totkota;

							echo "<tr>
							<td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</th>';
									break;
									case '2' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</th>';
									break;
									case '3' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</th>';
									break;
									case '4' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</th>';
									break;
									case '5' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</th>';
									break;
									case '6' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</th>';
									break;
									case '7' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</th>';
									break;
									case '8' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</th>';
									break;
									case '9' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</th>';
									break;
									case '10' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</th>';
									break;
									case '11' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</th>';
									break;
									case '12' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</th>';
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th></tr>';
							$totarea01=0;
							$totarea02=0;
							$totarea03=0;
							$totarea04=0;
							$totarea05=0;
							$totarea06=0;
							$totarea07=0;
							$totarea08=0;
							$totarea09=0;
							$totarea10=0;
							$totarea11=0;
							$totarea12=0;
							$totarea=0;

							$subtot01=0;
							$subtot02=0;
							$subtot03=0;
							$subtot04=0;
							$subtot05=0;
							$subtot06=0;
							$subtot07=0;
							$subtot08=0;
							$subtot09=0;
							$subtot10=0;
							$subtot11=0;
							$subtot12=0;
							$totkota=0;
							echo "<tr>
							<td style='font-size: 12px;'>".substr($row->kode,0,2)."-".$row->area."</td>
							<td style='font-size: 12px;'>$row->kode</td>
							<td style='font-size: 12px;'>$row->kota</td>
							<td style='font-size: 12px;'>$row->jenis</td>
							<td style='font-size: 12px;'>$row->nama</td>
							<td style='font-size: 12px;'>$row->alamat</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									$total=$total+$row->spbjan;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjan).'</th>';
									$subtot01=$subtot01+$row->spbjan;
									$totarea01=$totarea01+$row->spbjan;
									$grandtot01=$grandtot01+$row->spbjan;
									$totkota=$totkota+$row->spbjan;
									$totarea=$totarea+$row->spbjan;
									break;
									case '2' :
									$total=$total+$row->spbfeb;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbfeb).'</th>';
									$subtot02=$subtot02+$row->spbfeb;
									$totarea02=$totarea02+$row->spbfeb;
									$grandtot02=$grandtot02+$row->spbfeb;
									$totkota=$totkota+$row->spbfeb;
									$totarea=$totarea+$row->spbfeb;
									break;
									case '3' :
									$total=$total+$row->spbmar;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbmar).'</th>';
									$subtot03=$subtot03+$row->spbmar;
									$totarea03=$totarea03+$row->spbmar;
									$grandtot03=$grandtot03+$row->spbmar;
									$totkota=$totkota+$row->spbmar;
									$totarea=$totarea+$row->spbmar;
									break;
									case '4' :
									$total=$total+$row->spbapr;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbapr).'</th>';
									$subtot04=$subtot04+$row->spbapr;
									$totarea04=$totarea04+$row->spbapr;
									$grandtot04=$grandtot04+$row->spbapr;
									$totkota=$totkota+$row->spbapr;
									$totarea=$totarea+$row->spbapr;
									break;
									case '5' :
									$total=$total+$row->spbmay;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbmay).'</th>';
									$subtot05=$subtot05+$row->spbmay;
									$totarea05=$totarea05+$row->spbmay;
									$grandtot05=$grandtot05+$row->spbmay;
									$totkota=$totkota+$row->spbmay;
									$totarea=$totarea+$row->spbmay;
									break;
									case '6' :
									$total=$total+$row->spbjun;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjun).'</th>';
									$subtot06=$subtot06+$row->spbjun;
									$totarea06=$totarea06+$row->spbjun;
									$grandtot06=$grandtot06+$row->spbjun;
									$totkota=$totkota+$row->spbjun;
									$totarea=$totarea+$row->spbjun;
									break;
									case '7' :
									$total=$total+$row->spbjul;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjul).'</th>';
									$subtot07=$subtot07+$row->spbjul;
									$totarea07=$totarea07+$row->spbjul;
									$grandtot07=$grandtot07+$row->spbjul;
									$totkota=$totkota+$row->spbjul;
									$totarea=$totarea+$row->spbjul;
									break;
									case '8' :
									$total=$total+$row->spbaug;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbaug).'</th>';
									$subtot08=$subtot08+$row->spbaug;
									$totarea08=$totarea08+$row->spbaug;
									$grandtot08=$grandtot08+$row->spbaug;
									$totkota=$totkota+$row->spbaug;
									$totarea=$totarea+$row->spbaug;
									break;
									case '9' :
									$total=$total+$row->spbsep;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbsep).'</th>';
									$subtot09=$subtot09+$row->spbsep;
									$totarea09=$totarea09+$row->spbsep;
									$grandtot09=$grandtot09+$row->spbsep;
									$totkota=$totkota+$row->spbsep;
									$totarea=$totarea+$row->spbsep;
									break;
									case '10' :
									$total=$total+$row->spboct;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spboct).'</th>';
									$subtot10=$subtot10+$row->spboct;
									$totarea10=$totarea10+$row->spboct;
									$grandtot10=$grandtot10+$row->spboct;
									$totkota=$totkota+$row->spboct;
									$totarea=$totarea+$row->spboct;
									break;
									case '11' :
									$total=$total+$row->spbnov;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbnov).'</th>';
									$subtot11=$subtot11+$row->spbnov;
									$totarea11=$totarea11+$row->spbnov;
									$grandtot11=$grandtot11+$row->spbnov;
									$totkota=$totkota+$row->spbnov;
									$totarea=$totarea+$row->spbnov;
									break;
									case '12' :
									$total=$total+$row->spbdes;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbdes).'</th>';
									$subtot12=$subtot12+$row->spbdes;
									$totarea12=$totarea12+$row->spbdes;
									$grandtot12=$grandtot12+$row->spbdes;
									$totkota=$totkota+$row->spbdes;
									$totarea=$totarea+$row->spbdes;
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}

						}elseif( ($icity!='' && $icity!=$row->icity) ){
							echo "<tr>
							<td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   K o t a</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</th>';
									break;
									case '2' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
									break;
									case '3' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</th>';
									break;
									case '4' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</th>';
									break;
									case '5' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</th>';
									break;
									case '6' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</th>';
									break;
									case '7' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</th>';
									break;
									case '8' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</th>';
									break;
									case '9' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</th>';
									break;
									case '10' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</th>';
									break;
									case '11' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</th>';
									break;
									case '12' :
									echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</th>';
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</th></tr>';
							$grandtotkota=$grandtotkota+$totkota;

							if($kode!=substr($row->kode,0,2)){
								echo "<tr>
								<td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
								$bl=$blasal;
								for($i=1;$i<=$interval;$i++){
									switch ($bl){
										case '1' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</th>';
										break;
										case '2' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</th>';
										break;
										case '3' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</th>';
										break;
										case '4' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</th>';
										break;
										case '5' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</th>';
										break;
										case '6' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</th>';
										break;
										case '7' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</th>';
										break;
										case '8' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</th>';
										break;
										case '9' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</th>';
										break;
										case '10' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</th>';
										break;
										case '11' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</th>';
										break;
										case '12' :
										echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</th>';
										break;
									}
									$bl++;
									if($bl==13)$bl=1;
								}
								echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th></tr>';
								$totarea01=0;
								$totarea02=0;
								$totarea03=0;
								$totarea04=0;
								$totarea05=0;
								$totarea06=0;
								$totarea07=0;
								$totarea08=0;
								$totarea09=0;
								$totarea10=0;
								$totarea11=0;
								$totarea12=0;
								$totarea=0;
							}

							$subtot01=0;
							$subtot02=0;
							$subtot03=0;
							$subtot04=0;
							$subtot05=0;
							$subtot06=0;
							$subtot07=0;
							$subtot08=0;
							$subtot09=0;
							$subtot10=0;
							$subtot11=0;
							$subtot12=0;
							$totkota=0;
							echo "<tr>
							<td style='font-size; 12px;'>".substr($row->kode,0,2)."-".$row->area."</td>
							<td style='font-size; 12px;'>$row->kode</td>
							<td style='font-size; 12px;'>$row->kota</td>
							<td style='font-size; 12px;'>$row->jenis</td>
							<td style='font-size; 12px;'>$row->nama</td>
							<td style='font-size; 12px;'>$row->alamat</td>";
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch ($bl){
									case '1' :
									$total=$total+$row->spbjan;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjan).'</th>';
									$subtot01=$subtot01+$row->spbjan;
									$totarea01=$totarea01+$row->spbjan;
									$grandtot01=$grandtot01+$row->spbjan;
									$totkota=$totkota+$row->spbjan;
									$totarea=$totarea+$row->spbjan;
									break;
									case '2' :
									$total=$total+$row->spbfeb;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbfeb).'</th>';
									$subtot02=$subtot02+$row->spbfeb;
									$totarea02=$totarea02+$row->spbfeb;
									$grandtot02=$grandtot02+$row->spbfeb;
									$totkota=$totkota+$row->spbfeb;
									$totarea=$totarea+$row->spbfeb;
									break;
									case '3' :
									$total=$total+$row->spbmar;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbmar).'</th>';
									$subtot03=$subtot03+$row->spbmar;
									$totarea03=$totarea03+$row->spbmar;
									$grandtot03=$grandtot03+$row->spbmar;
									$totkota=$totkota+$row->spbmar;
									$totarea=$totarea+$row->spbmar;
									break;
									case '4' :
									$total=$total+$row->spbapr;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbapr).'</th>';
									$subtot04=$subtot04+$row->spbapr;
									$totarea04=$totarea04+$row->spbapr;
									$grandtot04=$grandtot04+$row->spbapr;
									$totkota=$totkota+$row->spbapr;
									$totarea=$totarea+$row->spbapr;
									break;
									case '5' :
									$total=$total+$row->spbmay;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbmay).'</th>';
									$subtot05=$subtot05+$row->spbmay;
									$totarea05=$totarea05+$row->spbmay;
									$grandtot05=$grandtot05+$row->spbmay;
									$totkota=$totkota+$row->spbmay;
									$totarea=$totarea+$row->spbmay;
									break;
									case '6' :
									$total=$total+$row->spbjun;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjun).'</th>';
									$subtot06=$subtot06+$row->spbjun;
									$totarea06=$totarea06+$row->spbjun;
									$grandtot06=$grandtot06+$row->spbjun;
									$totkota=$totkota+$row->spbjun;
									$totarea=$totarea+$row->spbjun;
									break;
									case '7' :
									$total=$total+$row->spbjul;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbjul).'</th>';
									$subtot07=$subtot07+$row->spbjul;
									$totarea07=$totarea07+$row->spbjul;
									$grandtot07=$grandtot07+$row->spbjul;
									$totkota=$totkota+$row->spbjul;
									$totarea=$totarea+$row->spbjul;
									break;
									case '8' :
									$total=$total+$row->spbaug;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbaug).'</th>';
									$subtot08=$subtot08+$row->spbaug;
									$totarea08=$totarea08+$row->spbaug;
									$grandtot08=$grandtot08+$row->spbaug;
									$totkota=$totkota+$row->spbaug;
									$totarea=$totarea+$row->spbaug;
									break;
									case '9' :
									$total=$total+$row->spbsep;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbsep).'</th>';
									$subtot09=$subtot09+$row->spbsep;
									$totarea09=$totarea09+$row->spbsep;
									$grandtot09=$grandtot09+$row->spbsep;
									$totkota=$totkota+$row->spbsep;
									$totarea=$totarea+$row->spbsep;
									break;
									case '10' :
									$total=$total+$row->spboct;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spboct).'</th>';
									$subtot10=$subtot10+$row->spboct;
									$totarea10=$totarea10+$row->spboct;
									$grandtot10=$grandtot10+$row->spboct;
									$totkota=$totkota+$row->spboct;
									$totarea=$totarea+$row->spboct;
									break;
									case '11' :
									$total=$total+$row->spbnov;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbnov).'</th>';
									$subtot11=$subtot11+$row->spbnov;
									$totarea11=$totarea11+$row->spbnov;
									$grandtot11=$grandtot11+$row->spbnov;
									$totkota=$totkota+$row->spbnov;
									$totarea=$totarea+$row->spbnov;
									break;
									case '12' :
									$total=$total+$row->spbdes;
									echo '<th style="font-size: 12px;"  align=right>'.number_format($row->spbdes).'</th>';
									$subtot12=$subtot12+$row->spbdes;
									$totarea12=$totarea12+$row->spbdes;
									$grandtot12=$grandtot12+$row->spbdes;
									$totkota=$totkota+$row->spbdes;
									$totarea=$totarea+$row->spbdes;
									break;
								}
								$bl++;
								if($bl==13)$bl=1;
							}
						}
						echo '<th style="font-size: 12px;"  align=right>'.number_format($total).'</th>';
						echo "</tr>";
						$icity=$row->icity;
						$kode=substr($row->kode,0,2);
					}

					echo "<tr>
					<td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   K o t a</td>";
					$bl=$blasal;
					for($i=1;$i<=$interval;$i++){
						switch ($bl){
							case '1' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</th>';
							break;
							case '2' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
							break;
							case '3' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</th>';
							break;
							case '4' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</th>';
							break;
							case '5' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</th>';
							break;
							case '6' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</th>';
							break;
							case '7' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</th>';
							break;
							case '8' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</th>';
							break;
							case '9' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</th>';
							break;
							case '10' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</th>';
							break;
							case '11' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</th>';
							break;
							case '12' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</th>';
							break;
						}
						$bl++;
						if($bl==13)$bl=1;
					}
					echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</th></tr>';
					$grandtotkota=$grandtotkota+$totkota;

					echo "<tr>
					<td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
					$bl=$blasal;
					for($i=1;$i<=$interval;$i++){
						switch ($bl){
							case '1' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</th>';
							break;
							case '2' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</th>';
							break;
							case '3' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</th>';
							break;
							case '4' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</th>';
							break;
							case '5' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</th>';
							break;
							case '6' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</th>';
							break;
							case '7' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</th>';
							break;
							case '8' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</th>';
							break;
							case '9' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</th>';
							break;
							case '10' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</th>';
							break;
							case '11' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</th>';
							break;
							case '12' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</th>';
							break;
						}
						$bl++;
						if($bl==13)$bl=1;
					}
					echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th></tr>';
					echo "<tr>
					<td style='background-color:#F2F2F2;' colspan=6 align=center>G r a n d   T o t a l</td>";
					$bl=$blasal;
					for($i=1;$i<=$interval;$i++){
						switch ($bl){
							case '1' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot01).'</th>';
							break;
							case '2' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot02).'</th>';
							break;
							case '3' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot03).'</th>';
							break;
							case '4' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot04).'</th>';
							break;
							case '5' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot05).'</th>';
							break;
							case '6' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot06).'</th>';
							break;
							case '7' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot07).'</th>';
							break;
							case '8' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot08).'</th>';
							break;
							case '9' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot09).'</th>';
							break;
							case '10' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot10).'</th>';
							break;
							case '11' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot11).'</th>';
							break;
							case '12' :
							echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtot12).'</th>';
							break;
						}
						$bl++;
						if($bl==13)$bl=1;
					}
					echo '<th style="font-size: 12px;"  style="background-color:#F2F2F2;" align=right>'.number_format($grandtotkota).'</th></tr>';

				}
				?>
			</tbody>
		</table>
		<td colspan='13' align='center'>
			<br>
			<button type="button" name="cmdreset" id="cmdreset" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button></a>&nbsp;
			<button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="dipales();"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>
		</td>
	</div>
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
	$( "#cmdreset" ).click(function() {  
		var Contents = $('#sitabel').html();    
		window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
	});

	function dipales() {
		this.close();
	}
</script>