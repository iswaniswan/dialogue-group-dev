<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
      <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
        class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table color-table info-table hover-table" id="sitabel">
              <thead>
                <?php 
                if($isi){
                  $j=0;
                  ?>
                  <tr>
                      <th>NO</th>
                      <th>PROMO</th>
                      <th>AREA</th>
                      <th>TOKO</th>
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
                      ?>
                  </tr>
              </thead>
              <tbody>
                <?php 

                $grandtot01=0;
                $grandtot02=0;
                $grandtot03=0;
                $grandtot04=0;
                $grandtot05=0;
                $grandtot06=0;
                $grandtot07=0;
                $grandtot08=0;
                $grandtot09=0;
                $grandtot10=0;
                $grandtot11=0;
                $grandtot12=0;
                foreach($isi as $row){
                  $j++;
                  echo "<tr>
                  <td>$j</td>
                  <td>$row->promo</td>
                  <td>(".$row->area.") - ".$row->areanya."</td>
                  <td>(".$row->kode.") - ".$row->nama."</td>";
                  $bl=$blasal;
                  for($i=1;$i<=$interval;$i++){
                    switch ($bl){
                        case '1' :
                        $grandtot01=$grandtot01+$row->jan;
                        echo '<th align=right>'.number_format($row->jan).'</th>';
                        break;
                        case '2' :
                        $grandtot02=$grandtot02+$row->feb;
                        echo '<th align=right>'.number_format($row->feb).'</th>';
                        break;
                        case '3' :
                        $grandtot03=$grandtot03+$row->mar;
                        echo '<th align=right>'.number_format($row->mar).'</th>';
                        break;
                        case '4' :
                        $grandtot04=$grandtot04+$row->apr;
                        echo '<th align=right>'.number_format($row->apr).'</th>';
                        break;
                        case '5' :
                        $grandtot05=$grandtot05+$row->may;
                        echo '<th align=right>'.number_format($row->may).'</th>';
                        break;
                        case '6' :
                        $grandtot06=$grandtot06+$row->jun;
                        echo '<th align=right>'.number_format($row->jun).'</th>';
                        break;
                        case '7' :
                        $grandtot07=$grandtot07+$row->jul;
                        echo '<th align=right>'.number_format($row->jul).'</th>';
                        break;
                        case '8' :
                        $grandtot08=$grandtot08+$row->aug;
                        echo '<th align=right>'.number_format($row->aug).'</th>';
                        break;
                        case '9' :
                        $grandtot09=$grandtot09+$row->sep;
                        echo '<th align=right>'.number_format($row->sep).'</th>';
                        break;
                        case '10' :
                        $grandtot10=$grandtot10+$row->oct;
                        echo '<th align=right>'.number_format($row->oct).'</th>';
                        break;
                        case '11' :
                        $grandtot11=$grandtot11+$row->nov;
                        echo '<th align=right>'.number_format($row->nov).'</th>';
                        break;
                        case '12' :
                        $grandtot12=$grandtot12+$row->des;
                        echo '<th align=right>'.number_format($row->des).'</th>';
                        break;
                    }
                    $bl++;
                }
            }
            echo "<tr>
            <td style='background-color:#F2F2F2;' colspan=4 align=center>G r a n d   T o t a l</td>";
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              switch ($bl){
                  case '1' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot01).'</th>';
                  break;
                  case '2' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot02).'</th>';
                  break;
                  case '3' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot03).'</th>';
                  break;
                  case '4' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot04).'</th>';
                  break;
                  case '5' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot05).'</th>';
                  break;
                  case '6' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot06).'</th>';
                  break;
                  case '7' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot07).'</th>';
                  break;
                  case '8' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot08).'</th>';
                  break;
                  case '9' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot09).'</th>';
                  break;
                  case '10' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot10).'</th>';
                  break;
                  case '11' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot11).'</th>';
                  break;
                  case '12' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot12).'</th>';
                  break;
              }
              $bl++;
          }
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
