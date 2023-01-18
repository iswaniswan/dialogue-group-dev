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
        	<br><h2>Detail Per Salesman Area <?php echo $namaarea ?></h2><br>
			<p>Periode <?php echo $dfrom." s/d ".$dto ?> </p>
			<i>* Data bersifat terpisah, nilai SJ dan nilai nota bukan dari realisasi SPB</i>
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                    <tr>
						<th>Salesman</th>
						<th>Nilai SPB</th>
						<th>Nilai SJ</th>
						<th>Nilai Nota</th>
						<th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
				<?php 
					if (is_array($isi)) {
						for($j=0;$j<count($isi);$j++){
					?>
							<tr>
								<td><?php echo $isi[$j]['i_salesman']." - ".$isi[$j]['e_salesman_name'] ?></td>
								<td align="right"><?php echo number_format($isi[$j]['nilaispb'],'2','.',',') ?></td>
								<td align="right"><?php echo number_format($isi[$j]['nilaisj'],'2','.',',') ?></td>
								<td align="right"><?php echo number_format($isi[$j]['nilainota'],'2','.',',')  ?></td>
								<td class="action">
								<a href="#" onclick="showdetailspbsjnota('<?php echo $isi[$j]['i_salesman'] ?>','<?php echo $dfrom ?>', '<?php echo $dto ?>', '<?php echo $iarea ?>' );">Detail</a>
							</tr>
					<?php 
						}
					}
	      		?>
				<td>
					<input type="hidden" name="is_groupbrg" value="<?php echo $is_groupbrg ?>" >
				</td>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
</script>