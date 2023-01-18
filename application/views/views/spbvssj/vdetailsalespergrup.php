<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(){background-color: #f2f2f2}

th {
  background-color: #737373;
  color: white;
}
</style>

<script language="javascript" type="text/javascript">
function showdetailspbsjnota(i_salesman,dfrom,dto,iarea) {
	lebar =1000;
	tinggi=600;
	eval('window.open("<?php echo site_url(); ?>"+"/spbvssj/cform/detailperdata/"+i_salesman+"/"+dfrom+"/"+dto+"/"+iarea,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1, top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
}
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
        	<h3>Periode <?php echo $dfrom." s/d ".$dto ?> </h3>
        	<i>* Data bersifat terpisah, nilai SJ dan nilai nota bukan dari realisasi SPB</i>
        	    <table class="tablesaw table-bordered table-hover table" id="sitabel">
        	        <thead>
					<tr>
						<th rowspan="2">Salesman</th>
						<th colspan="<?php echo count($datagrup) ?>">Nilai SPB</th>
						<th colspan="<?php echo count($datagrup) ?>">Nilai SJ</th>
						<th colspan="<?php echo count($datagrup) ?>">Nilai Nota</th>
						<th rowspan="2">Opsi</th>
					</tr>

					<tr>
						<?php 
							for($xx=0;$xx<count($datagrup);$xx++){
								echo "<th>".$datagrup[$xx]['e_product_groupname']."</th>";
							}

							for($xx=0;$xx<count($datagrup);$xx++){
								echo "<th>".$datagrup[$xx]['e_product_groupname']."</th>";
							}

							for($xx=0;$xx<count($datagrup);$xx++){
								echo "<th>".$datagrup[$xx]['e_product_groupname']."</th>";
							}
						?>
					</tr>
        	        </thead>
        	        <tbody>
        	        	<?php 
							if (is_array($isi)) {
								for($j=0;$j<count($isi);$j++){
							?>
							<tr>
								<td><?php echo $isi[$j]['i_salesman']." - ".$isi[$j]['e_salesman_name'] ?></td>
								
								<?php 
									$spbpergrup = $isi[$j]['nilaispb'];
									$sjpergrup = $isi[$j]['nilaisj'];
									$notapergrup = $isi[$j]['nilainota'];

									for($j1=0;$j1<count($spbpergrup);$j1++){
										if ($datagrup[$j1]['i_product_group'] == $spbpergrup[$j1]['i_product_group']) {
											$datagrup[$j1]['totalspb']+= $spbpergrup[$j1]['nilaispb'];
										}
										echo "<td align='right'>".number_format($spbpergrup[$j1]['nilaispb'],'2','.',',')."</td>";
									}

									for($j1=0;$j1<count($sjpergrup);$j1++){
										if ($datagrup[$j1]['i_product_group'] == $sjpergrup[$j1]['i_product_group']) {
											$datagrup[$j1]['totalsj']+= $sjpergrup[$j1]['nilaisj'];
										}
										echo "<td align='right'>".number_format($sjpergrup[$j1]['nilaisj'],'2','.',',')."</td>";
									}

									for($j1=0;$j1<count($notapergrup);$j1++){
										if ($datagrup[$j1]['i_product_group'] == $notapergrup[$j1]['i_product_group']) {
											$datagrup[$j1]['totalnota']+= $notapergrup[$j1]['nilainota'];
										}
										echo "<td align='right'>".number_format($notapergrup[$j1]['nilainota'],'2','.',',')."</td>";
									}
								?>
									<td class="action">
									<a href="#" onclick="showdetailspbsjnota('<?php echo $isi[$j]['i_salesman'] ?>','<?php echo $dfrom ?>', '<?php echo $dto ?>', '<?php echo $iarea ?>' );">Detail</a>
								</tr>
							<?php 
								}
							}
	      				?>
                	</tbody>
            	</table>
        </div>
    </div>
</div>
</div>

<script>
</script>