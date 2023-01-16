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
  background-color: #0099ff;
  color: white;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                <?php
                if($isi){?>
                    <tr>
                        <th class="text-center">K-LANG</th>
                        <th class="text-center">NAMA LANG</th>
                        <th class="text-center">KELAS</th>
                        <th class="text-center">KOTA</th>
                        <th class="text-center">SALES</th>
                        <?php 
                            if($dfrom!=''){
	                        	  $tmp=explode("-",$dfrom);
	                        	  $blasal=$tmp[1];
                              settype($bl,'integer');
	                          }
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                              switch ($bl){
                              case '1' :
                                echo '<th>Jan</th>';
                                break;
                              case '2' :
                                echo '<th>Feb</th>';
                                break;
                              case '3' :
                                echo '<th>Mar</th>';
                                break;
                              case '4' :
                                echo '<th>Apr</th>';
                                break;
                              case '5' :
                                echo '<th>Mei</th>';
                                break;
                              case '6' :
                                echo '<th>Jun</th>';
                                break;
                              case '7' :
                                echo '<th>Jul</th>';
                                break;
                              case '8' :
                                echo '<th>Agu</th>';
                                break;
                              case '9' :
                                echo '<th>Sep</th>';
                                break;
                              case '10' :
                                echo '<th>Okt</th>';
                                break;
                              case '11' :
                                echo '<th>Nov</th>';
                                break;
                              case '12' :
                                echo '<th>Des</th>';
                                break;
                              }
                              $bl++;
                              if($bl==13)$bl=1;
                            }
                            echo '<th>Rata2</th>';
                        ?>
                    </tr>
                </thead>
                <tbody>
                <?php 
			    foreach($isi as $row){
                    $rata=0;
  	                echo "<tr>
                        <td>$row->kode</td>
                        <td>$row->nama</td>
                        <td>$row->kelas</td>
                        <td>$row->kota</td>
                        <td>$row->sales</td>";
                    $bl=$blasal;
                    for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                            case '1' :
                              $rata=$rata+$row->jan;
                              echo '<th align=right>'.number_format($row->jan).'</th>';
                              break;
                            case '2' :
                              $rata=$rata+$row->feb;
                              echo '<th align=right>'.number_format($row->feb).'</th>';
                              break;
                            case '3' :
                              $rata=$rata+$row->mar;
                              echo '<th align=right>'.number_format($row->mar).'</th>';
                              break;
                            case '4' :
                              $rata=$rata+$row->apr;
                              echo '<th align=right>'.number_format($row->apr).'</th>';
                              break;
                            case '5' :
                              $rata=$rata+$row->may;
                              echo '<th align=right>'.number_format($row->may).'</th>';
                              break;
                            case '6' :
                              $rata=$rata+$row->jun;
                              echo '<th align=right>'.number_format($row->jun).'</th>';
                              break;
                            case '7' :
                              $rata=$rata+$row->jul;
                              echo '<th align=right>'.number_format($row->jul).'</th>';
                              break;
                            case '8' :
                              $rata=$rata+$row->aug;
                              echo '<th align=right>'.number_format($row->aug).'</th>';
                              break;
                            case '9' :
                              $rata=$rata+$row->sep;
                              echo '<th align=right>'.number_format($row->sep).'</th>';
                              break;
                            case '10' :
                              $rata=$rata+$row->oct;
                              echo '<th align=right>'.number_format($row->oct).'</th>';
                              break;
                            case '11' :
                              $rata=$rata+$row->nov;
                              echo '<th align=right>'.number_format($row->nov).'</th>';
                              break;
                            case '12' :
                              $rata=$rata+$row->des;
                              echo '<th align=right>'.number_format($row->des).'</th>';
                              break;
                        }
                        $bl++;
                        if($bl==13){
                            $bl=1;
                        }
                    }
                    $rata=$rata/$interval;
                    echo '<th align=right>'.number_format($rata).'</th>';
                    echo "</tr>";
			    }
		    }
	      ?>
                </tbody>
            </table>
            <td colspan='13' align='center'>
				<br>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
			</td>
        </div>
    </div>
</form>
</div>
</div>

<script>
    $( "#cmdreset" ).click(function() {  
    	var Contents = $('#sitabel').html();    
    	window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  	});
</script>
