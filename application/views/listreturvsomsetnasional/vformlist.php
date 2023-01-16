<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<a href="#" onclick="show('<?= $folder; ?>/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
			</div>
			<div class="panel-body table-responsive">
				<h5 class="box-title">Dari Tanggal <code><?= $dfrom;?></code> Sampai Tanggal <code><?= $dto;?></code></h5>
				<table class="table color-table success-table table-bordered" id="sitabel" width="100%;">
					<?php 
					if($isi){
						?>
						<thead>
							<tr>
								<th rowspan="2" style="text-align: center;">Area</th>
								<!-- <th rowspan="2" style="text-align: center;">Outlet</th> -->
								<?php 
								if($dfrom!=''){
									$tmp=explode("-",$dfrom);
									$blasal=$tmp[1];
									settype($blasal,'integer');
								}
								$bl=$blasal;
								for($i=1;$i<=$interval;$i++){
									switch ($bl){
										case '1' :
										echo '<th colspan=3>Jan</th>';
										break;
										case '2' :
										echo '<th colspan=3>Feb</th>';
										break;
										case '3' :
										echo '<th colspan=3>Mar</th>';
										break;
										case '4' :
										echo '<th colspan=3>Apr</th>';
										break;
										case '5' :
										echo '<th colspan=3>Mei</th>';
										break;
										case '6' :
										echo '<th colspan=3>Jun</th>';
										break;
										case '7' :
										echo '<th colspan=3>Jul</th>';
										break;
										case '8' :
										echo '<th colspan=3>Agu</th>';
										break;
										case '9' :
										echo '<th colspan=3>Sep</th>';
										break;
										case '10' :
										echo '<th colspan=3>Okt</th>';
										break;
										case '11' :
										echo '<th colspan=3>Nov</th>';
										break;
										case '12' :
										echo '<th colspan=3>Des</th>';
										break;
									}
									$bl++;
									if($bl==13)$bl=1;
								}
								$bl=$blasal;
								echo '</tr><tr>';
								for($i=1;$i<=$interval;$i++){
									switch ($bl){
										case '1' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '2' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '3' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '4' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '5' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '6' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '7' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '8' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '9' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '10' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '11' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
										case '12' :
										echo '<th>Omset</th><th>Retur</th><th>%</th>';
										break;
									}
									$bl++;
									if($bl==13)$bl=1;
								}
								?>
							</tr>
						</thead>
						<tbody>
							<?php 
							$bl=$blasal;
							$jenis='';
							$area='';
							$subomset01=0;
							$subomset02=0;
							$subomset03=0;
							$subomset04=0;
							$subomset05=0;
							$subomset06=0;
							$subomset07=0;
							$subomset08=0;
							$subomset09=0;
							$subomset10=0;
							$subomset11=0;
							$subomset12=0;
							$subretur01=0;
							$subretur02=0;
							$subretur03=0;
							$subretur04=0;
							$subretur05=0;
							$subretur06=0;
							$subretur07=0;
							$subretur08=0;
							$subretur09=0;
							$subretur10=0;
							$subretur11=0;
							$subretur12=0;
							$grandomset01=0;
							$grandomset02=0;
							$grandomset03=0;
							$grandomset04=0;
							$grandomset05=0;
							$grandomset06=0;
							$grandomset07=0;
							$grandomset08=0;
							$grandomset09=0;
							$grandomset10=0;
							$grandomset11=0;
							$grandomset12=0;
							$grandretur01=0;
							$grandretur02=0;
							$grandretur03=0;
							$grandretur04=0;
							$grandretur05=0;
							$grandretur06=0;
							$grandretur07=0;
							$grandretur08=0;
							$grandretur09=0;
							$grandretur10=0;
							$grandretur11=0;
							$grandretur12=0;
							foreach($isi as $row){
								$tmp=explode('.',$row->kode);
								$kodenya=$tmp[0];
								//$jenisnya=$tmp[1].'.'.$tmp[2];

								if($row->omset>0){
									$persen=($row->retur*100)/$row->omset;
								}else{
									$persen=0;
								}

								if($area==''){
									if($jenis==''){
										if($row->bln==$bl){
											echo '<tr><td><b>'.$kodenya.'</b></td>';
											//echo '<td>'.$jenisnya.'</td>';
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											$bl=$blasal;
											echo '<tr><td><b>'.$kodenya.'</b></td>';
											//echo '<td>'.$jenisnya.'</td>';
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}/*elseif($jenis==$jenisnya){
										if($row->bln==$bl){
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}*/else{
										$bl=$blasal;
										if($row->bln==$bl){
											$akhir=($blasal+$interval)-1;
  #          echo 'akhir='.$akhir.'<br>';
											if($blakhir!=$akhir){
												while ($blakhir<$akhir){
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
													$blakhir++;
												}
											}
											echo '<tr><td><b>'.$kodenya.'</b></td>';
											//echo '<td>'.$jenisnya.'</td>';
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}elseif($bl==$blasal){
													$akhir=($blasal+$interval)-1;
													if($blakhir!=$akhir){
														while ($blakhir<$akhir){
															echo '<td align=right>0</td>';
															echo '<td align=right>0</td>';
															echo '<td align=right>0%</td>';
															$blakhir++;
														}
													}
													echo '<tr><td><b>'.$kodenya.'</b></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}
								}elseif($area==$kodenya){
									if($jenis==''){
										if($row->bln==$bl){
											//echo '<td>'.$jenisnya.'</td>';
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											$bl=$blasal;
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<tr><td><b>'.$kodenya.'</b></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}else{
													echo '<tr><td><b>'.$kodenya.'</b></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}/*elseif($jenis==$jenisnya){
										if($row->bln==$bl){
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}*/else{
										$bl=$blasal;
										if($row->bln==$bl){
											$akhir=($blasal+$interval)-1;
  											if($blakhir!=$akhir){
												while ($blakhir<$akhir){
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
													$blakhir++;
												}
											}
											echo '<tr><td></td>';
											//echo '<td>'.$jenisnya.'</td>';
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}elseif($bl==$blasal){
													$akhir=($blasal+$interval)-1;
													if($blakhir!=$akhir){
														while ($blakhir<$akhir){
															echo '<td align=right>0</td>';
															echo '<td align=right>0</td>';
															echo '<td align=right>0%</td>';
															$blakhir++;
														}
													}
													echo '<tr><td></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}
								}else{
									if($jenis==''){
										if($row->bln==$bl){
											echo '<tr><td><b>'.$kodenya.'</b></td>';
											//echo '<td>'.$jenisnya.'</td>';
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											$bl=$blasal;
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<tr><td><b>'.$kodenya.'</b></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}else{
													echo '<tr><td><b>'.$kodenya.'</b></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}/*elseif($jenis==$jenisnya){
										if($row->bln==$bl){
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}*/else{
										$bl=$blasal;
										if($row->bln==$bl){
											$akhir=($blasal+$interval)-1;
  											if($blakhir!=$akhir){
												while ($blakhir<$akhir){
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
													$blakhir++;
												}
											}
											echo '</tr>';
											echo '<tr><td colspan=1 align=center><b>Total</td>';
											$bl=$blasal;
											for($i=1;$i<=$interval;$i++){
												switch($bl){
													case '1':
													if($subomset01>0){
														$persen=($subretur01*100)/$subomset01;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset01).'</td>';
													echo '<td align=right><b>'.number_format($subretur01).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '2':
													if($subomset02>0){
														$persen=($subretur02*100)/$subomset02;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset02).'</td>';
													echo '<td align=right><b>'.number_format($subretur02).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '3':
													if($subomset03>0){
														$persen=($subretur03*100)/$subomset03;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset03).'</td>';
													echo '<td align=right><b>'.number_format($subretur03).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '4':
													if($subomset04>0){
														$persen=($subretur04*100)/$subomset04;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset04).'</td>';
													echo '<td align=right><b>'.number_format($subretur04).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '5':  
													if($subomset05>0){
														$persen=($subretur05*100)/$subomset05;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset05).'</td>';
													echo '<td align=right><b>'.number_format($subretur05).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '6':
													if($subomset06>0){
														$persen=($subretur06*100)/$subomset06;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset06).'</td>';
													echo '<td align=right><b>'.number_format($subretur06).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '7':
													if($subomset07>0){
														$persen=($subretur07*100)/$subomset07;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset07).'</td>';
													echo '<td align=right><b>'.number_format($subretur07).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '8':
													if($subomset08>0){
														$persen=($subretur08*100)/$subomset08;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset08).'</td>';
													echo '<td align=right><b>'.number_format($subretur08).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '9':
													if($subomset09>0){
														$persen=($subretur09*100)/$subomset09;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset09).'</td>';
													echo '<td align=right><b>'.number_format($subretur09).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '10':
													if($subomset10>0){
														$persen=($subretur10*100)/$subomset10;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset10).'</td>';
													echo '<td align=right><b>'.number_format($subretur10).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '11':
													if($subomset11>0){
														$persen=($subretur11*100)/$subomset11;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset11).'</td>';
													echo '<td align=right><b>'.number_format($subretur11).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
													case '12':  
													if($subomset12>0){
														$persen=($subretur12*100)/$subomset12;
													}else{
														$persen=0;
													}
													echo '<td align=right><b>'.number_format($subomset12).'</td>';
													echo '<td align=right><b>'.number_format($subretur12).'</td>';
													echo '<td align=right><b>'.number_format($persen,2).'%</td>';
													break;
												}
												$bl++;
											}
											$bl=$blasal;
											$subomset01=0;
											$subomset02=0;
											$subomset03=0;
											$subomset04=0;
											$subomset05=0;
											$subomset06=0;
											$subomset07=0;
											$subomset08=0;
											$subomset09=0;
											$subomset10=0;
											$subomset11=0;
											$subomset12=0;
											$subretur01=0;
											$subretur02=0;
											$subretur03=0;
											$subretur04=0;
											$subretur05=0;
											$subretur06=0;
											$subretur07=0;
											$subretur08=0;
											$subretur09=0;
											$subretur10=0;
											$subretur11=0;
											$subretur12=0;
											echo '<tr><td><b>'.$kodenya.'</b></td>';
											//echo '<td>'.$jenisnya.'</td>';
											echo '<td align=right>'.number_format($row->omset).'</td>';
											echo '<td align=right>'.number_format($row->retur).'</td>';
											echo '<td align=right>'.number_format($persen,2).'%</td>';
											switch ($row->bln){
												case '1' :
												$subomset01=$subomset01+$row->omset;
												$subretur01=$subretur01+$row->retur;
												$grandomset01=$grandomset01+$row->omset;
												$grandretur01=$grandretur01+$row->retur;
												break;
												case '2' :
												$subomset02=$subomset02+$row->omset;
												$subretur02=$subretur02+$row->retur;
												$grandomset02=$grandomset02+$row->omset;
												$grandretur02=$grandretur02+$row->retur;
												break;
												case '3' :
												$subomset03=$subomset03+$row->omset;
												$subretur03=$subretur03+$row->retur;
												$grandomset03=$grandomset03+$row->omset;
												$grandretur03=$grandretur03+$row->retur;
												break;
												case '4' :
												$subomset04=$subomset04+$row->omset;
												$subretur04=$subretur04+$row->retur;
												$grandomset04=$grandomset04+$row->omset;
												$grandretur04=$grandretur04+$row->retur;
												break;
												case '5' :
												$subomset05=$subomset05+$row->omset;
												$subretur05=$subretur05+$row->retur;
												$grandomset05=$grandomset05+$row->omset;
												$grandretur05=$grandretur05+$row->retur;
												break;
												case '6' :
												$subomset06=$subomset06+$row->omset;
												$subretur06=$subretur06+$row->retur;
												$grandomset06=$grandomset06+$row->omset;
												$grandretur06=$grandretur06+$row->retur;
												break;
												case '7' :
												$subomset07=$subomset07+$row->omset;
												$subretur07=$subretur07+$row->retur;
												$grandomset07=$grandomset07+$row->omset;
												$grandretur07=$grandretur07+$row->retur;
												break;
												case '8' :
												$subomset08=$subomset08+$row->omset;
												$subretur08=$subretur08+$row->retur;
												$grandomset08=$grandomset08+$row->omset;
												$grandretur08=$grandretur08+$row->retur;
												break;
												case '9' :
												$subomset09=$subomset09+$row->omset;
												$subretur09=$subretur09+$row->retur;
												$grandomset09=$grandomset09+$row->omset;
												$grandretur09=$grandretur09+$row->retur;
												break;
												case '10' :
												$subomset10=$subomset10+$row->omset;
												$subretur10=$subretur10+$row->retur;
												$grandomset10=$grandomset10+$row->omset;
												$grandretur10=$grandretur10+$row->retur;
												break;
												case '11' :
												$subomset11=$subomset11+$row->omset;
												$subretur11=$subretur11+$row->retur;
												$grandomset11=$grandomset11+$row->omset;
												$grandretur11=$grandretur11+$row->retur;
												break;
												case '12' :
												$subomset12=$subomset12+$row->omset;
												$subretur12=$subretur12+$row->retur;
												$grandomset12=$grandomset12+$row->omset;
												$grandretur12=$grandretur12+$row->retur;
												break;
											}
											$blakhir=$bl;
										}else{
											for($i=1;$i<=$interval;$i++){
												if($row->bln==$bl){
													echo '<td align=right>'.number_format($row->omset).'</td>';
													echo '<td align=right>'.number_format($row->retur).'</td>';
													echo '<td align=right>'.number_format($persen,2).'%</td>';
													switch ($row->bln){
														case '1' :
														$subomset01=$subomset01+$row->omset;
														$subretur01=$subretur01+$row->retur;
														$grandomset01=$grandomset01+$row->omset;
														$grandretur01=$grandretur01+$row->retur;
														break;
														case '2' :
														$subomset02=$subomset02+$row->omset;
														$subretur02=$subretur02+$row->retur;
														$grandomset02=$grandomset02+$row->omset;
														$grandretur02=$grandretur02+$row->retur;
														break;
														case '3' :
														$subomset03=$subomset03+$row->omset;
														$subretur03=$subretur03+$row->retur;
														$grandomset03=$grandomset03+$row->omset;
														$grandretur03=$grandretur03+$row->retur;
														break;
														case '4' :
														$subomset04=$subomset04+$row->omset;
														$subretur04=$subretur04+$row->retur;
														$grandomset04=$grandomset04+$row->omset;
														$grandretur04=$grandretur04+$row->retur;
														break;
														case '5' :
														$subomset05=$subomset05+$row->omset;
														$subretur05=$subretur05+$row->retur;
														$grandomset05=$grandomset05+$row->omset;
														$grandretur05=$grandretur05+$row->retur;
														break;
														case '6' :
														$subomset06=$subomset06+$row->omset;
														$subretur06=$subretur06+$row->retur;
														$grandomset06=$grandomset06+$row->omset;
														$grandretur06=$grandretur06+$row->retur;
														break;
														case '7' :
														$subomset07=$subomset07+$row->omset;
														$subretur07=$subretur07+$row->retur;
														$grandomset07=$grandomset07+$row->omset;
														$grandretur07=$grandretur07+$row->retur;
														break;
														case '8' :
														$subomset08=$subomset08+$row->omset;
														$subretur08=$subretur08+$row->retur;
														$grandomset08=$grandomset08+$row->omset;
														$grandretur08=$grandretur08+$row->retur;
														break;
														case '9' :
														$subomset09=$subomset09+$row->omset;
														$subretur09=$subretur09+$row->retur;
														$grandomset09=$grandomset09+$row->omset;
														$grandretur09=$grandretur09+$row->retur;
														break;
														case '10' :
														$subomset10=$subomset10+$row->omset;
														$subretur10=$subretur10+$row->retur;
														$grandomset10=$grandomset10+$row->omset;
														$grandretur10=$grandretur10+$row->retur;
														break;
														case '11' :
														$subomset11=$subomset11+$row->omset;
														$subretur11=$subretur11+$row->retur;
														$grandomset11=$grandomset11+$row->omset;
														$grandretur11=$grandretur11+$row->retur;
														break;
														case '12' :
														$subomset12=$subomset12+$row->omset;
														$subretur12=$subretur12+$row->retur;
														$grandomset12=$grandomset12+$row->omset;
														$grandretur12=$grandretur12+$row->retur;
														break;
													}
													$blakhir=$bl;
													break;
												}elseif($bl==$blasal){
													$akhir=($blasal+$interval)-1;
													if($blakhir!=$akhir){
														while ($blakhir<$akhir){
															echo '<td align=right>0</td>';
															echo '<td align=right>0</td>';
															echo '<td align=right>0%</td>';
															$blakhir++;
														}
													}
													echo '<tr><td><b>'.$kodenya.'</b></td>';
													//echo '<td>'.$jenisnya.'</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}else{
													echo '<td align=right>0</td>';
													echo '<td align=right>0</td>';
													echo '<td align=right>0%</td>';
												}
												$bl++;
												if($bl==13)$bl=1;
											}
										}
									}
								}
								$area=$kodenya;
								//$jenis=$jenisnya;
								$bl++;
								if($bl>($interval+$blasal))$bl=1;
							}
#####
							echo '</tr>';
							echo '<tr><td colspan=1 align=center><b>Total</td>';
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch($bl){
									case '1':
									if($subomset01>0){
										$persen=($subretur01*100)/$subomset01;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset01).'</td>';
									echo '<td align=right><b>'.number_format($subretur01).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '2':
									if($subomset02>0){
										$persen=($subretur02*100)/$subomset02;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset02).'</td>';
									echo '<td align=right><b>'.number_format($subretur02).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '3':
									if($subomset03>0){
										$persen=($subretur03*100)/$subomset03;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset03).'</td>';
									echo '<td align=right><b>'.number_format($subretur03).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '4':
									if($subomset04>0){
										$persen=($subretur04*100)/$subomset04;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset04).'</td>';
									echo '<td align=right><b>'.number_format($subretur04).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '5':  
									if($subomset05>0){
										$persen=($subretur05*100)/$subomset05;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset05).'</td>';
									echo '<td align=right><b>'.number_format($subretur05).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '6':
									if($subomset06>0){
										$persen=($subretur06*100)/$subomset06;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset06).'</td>';
									echo '<td align=right><b>'.number_format($subretur06).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '7':
									if($subomset07>0){
										$persen=($subretur07*100)/$subomset07;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset07).'</td>';
									echo '<td align=right><b>'.number_format($subretur07).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '8':
									if($subomset08>0){
										$persen=($subretur08*100)/$subomset08;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset08).'</td>';
									echo '<td align=right><b>'.number_format($subretur08).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '9':
									if($subomset09>0){
										$persen=($subretur09*100)/$subomset09;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset09).'</td>';
									echo '<td align=right><b>'.number_format($subretur09).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '10':
									if($subomset10>0){
										$persen=($subretur10*100)/$subomset10;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset10).'</td>';
									echo '<td align=right><b>'.number_format($subretur10).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '11':
									if($subomset11>0){
										$persen=($subretur11*100)/$subomset11;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset11).'</td>';
									echo '<td align=right><b>'.number_format($subretur11).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '12':  
									if($subomset12>0){
										$persen=($subretur12*100)/$subomset12;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($subomset12).'</td>';
									echo '<td align=right><b>'.number_format($subretur12).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
								}
								$bl++;
							}
							$bl=$blasal;
							$subomset01=0;
							$subomset02=0;
							$subomset03=0;
							$subomset04=0;
							$subomset05=0;
							$subomset06=0;
							$subomset07=0;
							$subomset08=0;
							$subomset09=0;
							$subomset10=0;
							$subomset11=0;
							$subomset12=0;
							$subretur01=0;
							$subretur02=0;
							$subretur03=0;
							$subretur04=0;
							$subretur05=0;
							$subretur06=0;
							$subretur07=0;
							$subretur08=0;
							$subretur09=0;
							$subretur10=0;
							$subretur11=0;
							$subretur12=0;
#####
							/*echo '</tr>';
							echo '<tr><td colspan=1 align=center><b>Grand Total</td>';
							$bl=$blasal;
							for($i=1;$i<=$interval;$i++){
								switch($bl){
									case '1':
									if($grandomset01>0){
										$persen=($grandretur01*100)/$grandomset01;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset01).'</td>';
									echo '<td align=right><b>'.number_format($grandretur01).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '2':
									if($grandomset02>0){
										$persen=($grandretur02*100)/$grandomset02;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset02).'</td>';
									echo '<td align=right><b>'.number_format($grandretur02).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '3':
									if($grandomset03>0){
										$persen=($grandretur03*100)/$grandomset03;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset03).'</td>';
									echo '<td align=right><b>'.number_format($grandretur03).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '4':
									if($grandomset04>0){
										$persen=($grandretur04*100)/$grandomset04;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset04).'</td>';
									echo '<td align=right><b>'.number_format($grandretur04).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '5':  
									if($grandomset05>0){
										$persen=($grandretur05*100)/$grandomset05;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset05).'</td>';
									echo '<td align=right><b>'.number_format($grandretur05).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '6':
									if($grandomset06>0){
										$persen=($grandretur06*100)/$grandomset06;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset06).'</td>';
									echo '<td align=right><b>'.number_format($grandretur06).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '7':
									if($grandomset07>0){
										$persen=($grandretur07*100)/$grandomset07;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset07).'</td>';
									echo '<td align=right><b>'.number_format($grandretur07).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '8':
									if($grandomset08>0){
										$persen=($grandretur08*100)/$grandomset08;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset08).'</td>';
									echo '<td align=right><b>'.number_format($grandretur08).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '9':
									if($grandomset09>0){
										$persen=($grandretur09*100)/$grandomset09;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset09).'</td>';
									echo '<td align=right><b>'.number_format($grandretur09).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '10':
									if($grandomset10>0){
										$persen=($grandretur10*100)/$grandomset10;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset10).'</td>';
									echo '<td align=right><b>'.number_format($grandretur10).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '11':
									if($grandomset11>0){
										$persen=($grandretur11*100)/$grandomset11;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset11).'</td>';
									echo '<td align=right><b>'.number_format($grandretur11).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
									case '12':  
									if($grandomset12>0){
										$persen=($grandretur12*100)/$grandomset12;
									}else{
										$persen=0;
									}
									echo '<td align=right><b>'.number_format($grandomset12).'</td>';
									echo '<td align=right><b>'.number_format($grandretur12).'</td>';
									echo '<td align=right><b>'.number_format($persen,2).'%</td>';
									break;
								}
								$bl++;
							}*/
						}
						?>
					</tbody>
				</table>
				<button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$( "#cmdreset" ).click(function() {  
		var Contents = $('#sitabel').html();    
		window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
	});
</script>