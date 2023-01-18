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
                        <th>Area</th>
			            <th>Nilai SPB</th>
			            <th>Nilai SJ</th>
			            <th>Nilai Nota</th>
			            <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
	                    $totalspb = 0; $totalsj = 0; $totalnota = 0;
		                if (is_array($isi)) {
		                	for($j=0;$j<count($isi);$j++){
		            ?>
		            		<tr>
		            			<td><?php echo $isi[$j]['i_area']." - ".$isi[$j]['namaarea'] ?></td>
		            			<td align="right"><?php $totalspb+=$isi[$j]['nilaispb']; echo number_format($isi[$j]['nilaispb'],'2','.',','); ?></td>
		            			<td align="right"><?php $totalsj+=$isi[$j]['nilaisj']; echo number_format($isi[$j]['nilaisj'],'2','.',','); ?></td>
		            			<td align="right"><?php $totalnota+=$isi[$j]['nilainota']; echo number_format($isi[$j]['nilainota'],'2','.',',');  ?></td>
		            			<td class="action">
		            			<a href="#" onclick='show("spbvssj/cform/detailpersales/<?php echo $isi[$j]['i_area']."/".$dfrom."/".$dto."/".$is_groupbrg ?>/","#main")'>Detail Per Sales</a></td>
		            		</tr>
		            <?php 
		            	}
		            ?>
		            	<tr>
		            		<td align="center"><b>TOTAL SELURUH AREA/NASIONAL</b></td>
		            		<td align="right"><b><?php echo number_format($totalspb,'2','.',',') ?></b></td>
		            		<td align="right"><b><?php echo number_format($totalsj,'2','.',',') ?></b></td>
		            		<td align="right"><b><?php echo number_format($totalnota,'2','.',',') ?></b></td>
		            		<td align="center">
							<?php 
								$attributes = array('name' => 'f_export', 'id' => 'f_export');
								echo form_open('spbvssj/cform/export_excel', $attributes); ?>
									<input type="hidden" name="dfrom" value="<?php echo $dfrom ?>" >
									<input type="hidden" name="dto" value="<?php echo $dto ?>" >
									<input type="hidden" name="is_groupbrg" value="<?php echo $is_groupbrg ?>" >
									<input type="submit" name="export_excel" id="export_excel" value="Export ke Excel">
								<?php echo form_close();  ?>
		            		</td>
		            	</tr>
		            <?php 
		                }
	                ?>
                </tbody>
            </table>
            <br>
        </div>
    </div>
</div>
</div>

<script>
</script>