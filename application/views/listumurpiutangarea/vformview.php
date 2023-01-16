<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <table class="table color-table success-table table-bordered" cellspacing="0" width="100%" id="sitabel">
                    <thead>
                        <tr>
                            <th rowspan="2" align="center">No</th>
 	                        <th rowspan="2" align="center">Area</th>
			                <th align="center" colspan="8">Kelompok</th>
			                <th rowspan="2" align="center">Total</th>
                        </tr>
                        <tr>
                            <th align="center">Blm Jatuh Tempo</th>
			                <th align="center">0-15</th>
			                <th align="center">16-30</th>
			                <th align="center">31-45</th>
			                <th align="center">46-60</th>
			                <th align="center">61-75</th>
			                <th align="center">76-90</th>
			                <th align="center">>90</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
		            if($isi){
		                $i=0;
		                $j=0;
		                $area='';
		                $total=0;
		                $grandtotal=0;
		                foreach($isi as $row){
                            if($area==''){
                              $i++;
    	                        echo "<tr>";
		                    	echo "<td>$i</td>
		                    		<td>$row->area</td>";
     	                      }elseif($area!='' && $area!=$row->area){
     	                        $i++;
  		                        echo "<td align='right'>".number_format($total)."</td>";
     	                        $total=0;
     	                        echo "</tr><tr>";
		                    	echo "<td>$i</td>
		                    		 <td>$row->area</td>";
     	                      }
                            $j++;
		                    echo "<td align='right'>".number_format($row->jumlah)."</td>";
                            $area=$row->area;
                            $total = $total+$row->jumlah;
                        }
	                    echo "<td align='right'>".number_format($total)."</td>";
                        echo "</tr>";
	                    echo "<tr rowspan=2>";
                        echo "<th colspan=2>TOTAL AREA</th>";
                        $iperiode=$tahun.$bulan;
                        $sql = "select * from f_umur_piutang_nasional('$iperiode','$d_opname')";
                        $rs		= pg_query($sql);
    	                while($raw=pg_fetch_assoc($rs)){
    	                	$jumlah    = $raw['jumlah'];
                            $grandtotal = $grandtotal + $jumlah;
                            echo "<th align=right>".number_format($jumlah)."</th>";
                        }
                        echo "
                        <th align=right>".number_format($grandtotal)."</th>";
                        echo "</tr>";
	                }
                    ?>
                    </tbody>
                </table>
                <button type="button" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
            </div>
        </div>
    </div>
</div>
<script>
    $( "#cmdreset" ).click(function() {  
		var Contents = $('#sitabel').html();    
		window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
	});
</script>