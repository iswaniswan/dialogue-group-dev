<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-info">
			<div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
			<?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
				class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
			<?php } ?>
		</div>
		<div class="panel-body table-responsive">
			<table class="table color-bordered-table info-bordered-table" id="sitabel">
				<?php if($isi){ ?>
					<thead>
						<!-- <th>KodeLang</th> -->
						<th>Customer</th>
						<?php foreach($diskon as $row){?>
							<th> Jml Pcs <?= $row->diskon;?> %</th>
							<th> Kotor <?= $row->diskon;?> %</th>
						<?php }?>
						<th class="action">Act</th>
					</thead>
					<tbody>
						<?php 
						echo "<input type='hidden' id='iperiode' name='iperiode' value='$iperiode'>";
						$i=0;
						$disc='';
						$cust='';
						$jmltot=count($diskon);
						$pos=0;
						foreach($isi as $raw)
						{
							$x=0;
							if($cust==''){
								$i++;
								echo "<tr><input type='hidden' id='icustomer".$i."' name='icustomer".$i."' value='$raw->i_customer'>
								<input type='hidden' id='iarea".$i."' name='iarea".$i."' value='$raw->i_area'>
								<td>($raw->i_customer) $raw->e_customer_name
								<input type='hidden' id='ecustomername".$i."' name='ecustomername".$i."' value='$raw->e_customer_name'></td>";
								foreach($diskon as $row){
									$x++;
									if($row->diskon==$raw->n_notapb_discount){
										echo "
										<td>$raw->jumlah</td>
										<td>$raw->kotor</td>";
										$pos=$x;
										break;
									}else{
										echo "
										<td>0</td>
										<td>0</td>";
									}
								}
							}
							if($cust==$raw->i_customer){
								foreach($diskon as $row){
									$x++;
									if($row->diskon==$raw->n_notapb_discount){
										echo "
										<td>$raw->jumlah</td>
										<td>$raw->kotor</td>";
										$pos=$x;
										break;
									}elseif($x>$pos){
										echo "
										<td>0</td>
										<td>0</td>";
									}
								}
							}
							if($cust!=$raw->i_customer && $cust!=''){
								while($pos<$jmltot){
									echo "  <td>0</td>
									<td>0</td>";
									$pos++;
								}
								if($pos==$jmltot){
									echo "<td valign=top class=\"action\"><input type='checkbox' name='chk".$i."' id='chk".$i."' value='' onclick='pilihan(this.value,".$i.")'>";
									echo "</td></tr>";	
								}
								$i++;
								echo "<tr><input type='hidden' id='icustomer".$i."' name='icustomer".$i."' value='$raw->i_customer'>
								<input type='hidden' id='iarea".$i."' name='iarea".$i."' value='$raw->i_area'>
								<td>($raw->i_customer) $raw->e_customer_name
								<input type='hidden' id='ecustomername".$i."' name='ecustomername".$i."' value='$raw->e_customer_name'></td>";
								foreach($diskon as $row){
									$x++;
									if($row->diskon==$raw->n_notapb_discount){
										echo "
										<td>$raw->jumlah</td>
										<td>$raw->kotor</td>";
										$pos=$x;
										break;
									}elseif($x>$pos){
										echo "
										<td>0</td>
										<td>0</td>";
									}
								}
							}
							$cust=$raw->i_customer;
						}
						while($pos<$jmltot){
							echo "  <td>0</td>
							<td>0</td>";
							$pos++;
						}
						if($pos==$jmltot){
							echo "</tr>";	
						}
					}else{
						echo "<h2>Tidak Ada SPB dari Bon Penjualan</h2>";
					}
					?>
				</tbody>
			</table>
			<td colspan='13' align='center'>
				<br>
				<button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button></a>
			</td>
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