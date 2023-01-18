<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title">History Nota (DT)</h3>
        <div class="table-responsive">
            <table id="tabledata" class="table color-bordered-table info-bordered-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No Nota</th>
                        <th>Tgl Nota</th>
                        <th>No DT</th>
	                    <th>Tgl DT</th>
	                    <th>Bayar</th>
	                    <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
		            if($isi){
		            	foreach($isi as $row){
                    if($row->d_nota!=''){
                      $tmp=explode('-',$row->d_nota);
		            		  $tgl=$tmp[2];
		            		  $bln=$tmp[1];
		            		  $thn=$tmp[0];
		            		  $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
                    }
                    if($row->d_dt!=''){
                      $tmp=explode('-',$row->d_dt);
		            		  $tgl=$tmp[2];
		            		  $bln=$tmp[1];
		            		  $thn=$tmp[0];
		            		  $row->d_dt=$tgl.'-'.$bln.'-'.$thn;
                    }
		            	  echo "<tr> 
		            		  <td>$row->i_nota</td>
		            		  <td>$row->d_nota</td>
		            		  <td>$row->i_dt</td>
		            		  <td>$row->d_dt</td>
		            		  <td align=right>".number_format($row->v_jumlah)."</td>
		            		  <td align=right>".number_format($row->v_sisa)."</td>
		            		</tr>";
                
		            	}
		            }
	            ?>
                </tbody>
            </table>
            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="dipales();"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>&nbsp;
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    function dipales() {
        this.close();
    }
</script>