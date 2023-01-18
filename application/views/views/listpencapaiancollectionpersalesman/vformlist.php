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
      <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
      <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
        class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
        <?php 
        ?><h3>&nbsp;&nbsp;&nbsp;<?php echo 'Periode : '.substr($dfrom,0,2).' '.mbulan(substr($dfrom,3,2)).' '.substr($dfrom,6,4).' s/d '.substr($dto,0,2).' '.mbulan(substr($dto,3,2)).' '.substr($dto,6,4); ?></h3>
            <table class="table color-table info-table hover-table" id="sitabel">
                <thead>
                    <?php 
		            if($isi){
                    ?>
	   	              <th rowspan=2>Area</th>
	                  <th rowspan=2>Salesman</th>
                    <?php 
                      if($dfrom!=''){
		                    $tmp=explode("-",$dfrom);
		                    $blasal=$tmp[1];
                        settype($blasal,'integer');
	                    }
                      echo '<th align=center colspan=3>Kredit</th>';
                      echo '<th align=center colspan=3>Tunai</th>';
                      echo '<th align=center colspan=3>Gabungan</th>';
                      echo '</tr><tr>';
                      echo '<th>Target</th><th>Realisasi</th><th>Persen</th>';
                      echo '<th>Target</th><th>Realisasi</th><th>Persen</th>';
                      echo '<th>Target</th><th>Realisasi</th><th>Persen</th>';
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $bl=$blasal;
                    $grandtargetcredit=0;
                    $grandrealcredit=0;
                    $grandtargetcash=0;
                    $grandrealcash=0;
                    $grandtargetgabung=0;
                    $grandrealgabung=0;

		                foreach($isi as $row){
                      $grandtargetcredit=$grandtargetcredit+$row->totalcredit;
                      $grandrealcredit=$grandrealcredit+$row->realisasicredit;
                      $grandtargetcash=$grandtargetcash+$row->totalcash;
                      $grandrealcash=$grandrealcash+$row->realisasicash;
                      $grandtargetgabung=$grandtargetgabung+$row->totalcredit+$row->totalcash;
                      $grandrealgabung=$grandrealgabung+$row->realisasicredit+$row->totalcash;
                    
                      if($row->realisasicredit>0){
                        $persen=($row->realisasicredit*100)/$row->totalcredit;
                      }else{
                        $persen=0;
                      }
                      echo '<tr><td>'.$row->i_area.' - '.$row->e_area_name.'</td><td>'.$row->i_salesman.' - '.$row->e_salesman_name.'</td>';
                      echo '<td align=right>'.number_format($row->totalcredit).'</td>';
                      echo '<td align=right>'.number_format($row->realisasicredit).'</td>';
                      echo '<td align=right>'.number_format($persen,2).' %</td>';
                      if($row->realisasicash>0){
                        $persen=($row->realisasicash*100)/$row->totalcash;
                      }else{
                        $persen=0;
                      }
                      echo '<td align=right>'.number_format($row->totalcash).'</td>';
                      echo '<td align=right>'.number_format($row->realisasicash).'</td>';
                      echo '<td align=right>'.number_format($persen,2).' %</td>';
                      if(($row->realisasicash+$row->realisasicredit)>0){
                        $persen=(($row->realisasicash+$row->realisasicredit)*100)/($row->totalcredit+$row->totalcash);
                      }else{
                        $persen=0;
                      }
                      echo '<td align=right>'.number_format($row->totalcredit+$row->totalcash).'</td>';
                      echo '<td align=right>'.number_format($row->realisasicredit+$row->totalcash).'</td>';
                      echo '<td align=right>'.number_format($persen,2).' %</td></tr>';
                    }

                    echo '<tr><td colspan=2 align=center><b>Total</b></td>';
                    echo '<td align=right><b>'.number_format($grandtargetcredit).'</b></td>';
                    echo '<td align=right><b>'.number_format($grandrealcredit).'</b></td>';
                    if($grandrealcredit>0){
                      $persen=($grandrealcredit*100)/$grandtargetcredit;
                    }else{
                      $persen=0;
                    }
                    echo '<td align=right><b>'.number_format($persen,2).' %</b></td>';
                    echo '<td align=right><b>'.number_format($grandtargetcash).'</b></td>';
                    echo '<td align=right><b>'.number_format($grandrealcash).'</b></td>';
                    if($grandrealcash>0){
                      $persen=($grandrealcash*100)/$grandtargetcash;
                    }else{
                      $persen=0;
                    }
                    echo '<td align=right><b>'.number_format($persen,2).' %</b></td>';
                    echo '<td align=right><b>'.number_format($grandtargetgabung).'</b></td>';
                    echo '<td align=right><b>'.number_format($grandrealgabung).'</b></td>';
                    if($grandrealgabung>0){
                      $persen=($grandrealgabung*100)/$grandtargetgabung;
                    }else{
                      $persen=0;
                    }
                    echo '<td align=right><b>'.number_format($persen,2).' %</b></td></tr>';
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
</div>
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });
</script>
