<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
            <?php 
		        $periode=$iperiode;
		        $a=substr($periode,0,4);
	            $b=substr($periode,4,2);
		        $periode=mbulan($b)." - ".$a;
            ?>
            <input name="iperiode" id="iperiode" value="<?php echo $periode; ?>" type="hidden">
            <?php 
		        echo "<center><h3>Target Collection per Area</h3></center>";
		        echo "<center><h3>Periode $periode</h3></center>";
            ?>
                <table class="table color-bordered-table info-bordered-table">
                    <thead>
                        <tr>
                            <th>No</th>
	   	                    <th>Area</th>
	   	                    <th>Jumlah Target</th>
			                <th>Target Realisasi</th>
			                <th>Persen</th>
			                <th>Jumlah Non Insentif</th>
			                <th>Jumlah Realisasi</th>
			                <th>Persen</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
		                if($isi){
                            $i=1;
                            $ttarget=0;
                            $trealis=0;
                            $ttarget2=0;
                            $trealis2=0;
			                foreach($isi->result() as $row){
                                if($row->realisasi==null || $row->realisasi=='')$row->realisasi=0;
                                if($row->total!=0){
                                  $persen=number_format(($row->realisasi/$row->total)*100,2);
                                }else{
                                  $persen='0.00';
                                }
                                if($row->realisasinon==null || $row->realisasinon=='')$row->realisasinon=0;
                                if($row->totalnon!=0){
                                  $persennon=number_format(($row->realisasinon/$row->totalnon)*100,2);
                                }else{
                                  $persennon='0.00';
                                }
                                $ttarget=$ttarget+$row->total;
                                $trealis=$trealis+$row->realisasi;
                                $ttarget2=$ttarget2+$row->totalnon;
                                $trealis2=$trealis2+$row->realisasinon;
	                            echo "<tr>
                                <td align=right>$i</td>
                                <td>$row->i_area-$row->e_area_name</td>
                                <td align=right>Rp. ".number_format($row->total)."</td>
                                <td align=right>RP. ".number_format($row->realisasi)."</td>
			                    <td align=right>".$persen." %</td>
			                    <td align=right>Rp. ".number_format($row->totalnon)."</td>
			                    <td align=right>RP. ".number_format($row->realisasinon)."</td>
			                    <td align=right>".$persennon." %</td>";
                                $i++;
			                    echo "</tr>";	
			                }
                            echo "<tr>
                            <td colspan=2>Total</td>
                            <td align=right>Rp. ".number_format($ttarget)."</td>
                            <td align=right>RP. ".number_format($trealis)."</td>
		                    <td align=right></td>
		                    <td align=right>Rp. ".number_format($ttarget2)."</td>
		                    <td align=right>RP. ".number_format($trealis2)."</td>
		                    <td colspan=2></td></tr>";	
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