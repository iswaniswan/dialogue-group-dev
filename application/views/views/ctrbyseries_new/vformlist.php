<!--This page css - Morris CSS -->
<link href="<?= base_url(); ?>assets/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-bar-chart-o"></i> <?= $title; ?>
            <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <p class="text-muted">Periode : <?php echo $dfrom." s/d ".$dto;?></p>
                <?php 
                    if($ob){
                        foreach ($ob as $riw) {
                          echo "<h3>Total OB : ".number_format($riw->ob)."</h3>";
                        }
                    }
                ?>
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Kode Series</th>
                            <th rowspan="2">Nama Series</th>
                            <th colspan="3">OA</th>
                            <th colspan="3">Sales Qty (Unit)</th>
                            <th colspan="3">Net Sales (Rp.)</th>
                            <th rowspan="2">% Ctr<br>Net Sales (Rp.)</th>
                        </tr>
                        <tr>
                            <th><?php echo $prevthn ?></th>
                            <th><?php echo $thn ?></th>
                            <th>Growth</th>
                            <th><?php echo $prevthn ?></th>
                            <th><?php echo $thn ?></th>
                            <th>Growth</th>
                            <th><?php echo $prevthn ?></th>
                            <th><?php echo $thn ?></th>
                            <th>Growth</th>
                        </tr>
                    </thead>
                    <?php 
                    if($isi) {
                        $totnota=0;
                        $totrp=0;
                        $totoaprev=0;
                        $totoa=0;
                        $totqty=0;
                        $totqtyprev=0;
                        $totvnota=0;
                        $totvnotaprev=0;
                        $totctrrp=0;
                        $i=0;
                          foreach($isi as $rew){
                              $totnota+=$rew->netsls;
                          }
                          $totrp = $totnota; 

                          foreach($isi as $row){
                            $i++;

                              if ($row->oaprev == 0) {
                                  $grwoa = 0;
                              } else { //jika pembagi tidak 0
                                  $grwoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                              }
                          
                              if ($row->slsqtyprev == 0) {
                                  $grwqty = 0;
                              } else { //jika pembagi tidak 0
                                  $grwqty = (($row->slsqty-$row->slsqtyprev)/$row->slsqtyprev)*100;
                              }
                          
                              if ($row->netslsprev == 0) {
                                  $grwrp = 0;
                              } else { //jika pembagi tidak 0
                                  $grwrp = (($row->netsls-$row->netslsprev)/$row->netslsprev)*100;
                              }
                          
                              $ctrrp = ($row->netsls/$totrp)*100;
                          
                              echo "<tr>
                                      <td>$i</td>
                                      <td>$row->iseri</td>
                                      <td>$row->seriname</td>
                                      <td align='right'>".number_format($row->oaprev)."</td>
                                      <td align='right'>".number_format($row->oa)."</td>
                                      <td align='right'>".number_format($grwoa,2) ."%</td>
                                      <td align='right'>".number_format($row->slsqtyprev)."</td>
                                      <td align='right'>".number_format($row->slsqty)."</td>
                                      <td align='right'>".number_format($grwqty,2)."%</td>
                                      <td align='right'>".number_format($row->netslsprev)."</td>
                                      <td align='right'>".number_format($row->netsls)."</td>
                                      <td align='right'>".number_format($grwrp,2)."%</td>
                                      <td align='right'>".number_format($ctrrp,2)."%</td>
                                    </tr>";

                              $totoaprev = $totoaprev+$row->oaprev;
                              $totoa = $totoa+$row->oa;
                              $totqty = $totqty+$row->slsqty;
                              $totqtyprev = $totqtyprev+$row->slsqtyprev;
                              $totvnota = $totvnota+$row->netsls;
                              $totvnotaprev = $totvnotaprev+$row->netslsprev;
                              $totctrrp=$totctrrp+$ctrrp;
                          
                              if ($totoaprev == 0) {
                                  $totgrwoa = 0;
                              } else { //jika pembagi tidak 0
                                  $totgrwoa = (($totoa-$totoaprev)/$totoaprev)*100;
                              }
                          
                              if ($totqtyprev == 0) {
                                  $totgrwqty = 0;
                              } else { //jika pembagi tidak 0
                                  $totgrwqty = (($totqty-$totqtyprev)/$totqtyprev)*100;
                              }
                          
                              if ($totvnotaprev == 0) {
                                  $totgrwrp = 0;
                              } else { //jika pembagi tidak 0
                                  $totgrwrp = (($totvnota-$totvnotaprev)/$totvnotaprev)*100;
                              }
                          }
                            echo "<tr>
                                      <td colspan='3'><b>Grand Total</b></td>
                                      <td align='right'><b>".number_format($totoaprev)."</b></td>
                                      <td align='right'><b>".number_format($totoa)."</b></td>
                                      <td align='right'><b>".number_format($totgrwoa,2)."%</b></td>
                                      <td align='right'><b>".number_format($totqtyprev)."</b></td>
                                      <td align='right'><b>".number_format($totqty)."</b></td>
                                      <td align='right'><b>".number_format($totgrwqty,2)."%</b></td>
                                      <td align='right'><b>".number_format($totvnotaprev)."</b></td>
                                      <td align='right'><b>".number_format($totvnota)."</b></td>
                                      <td align='right'><b>".number_format($totgrwrp,2)."%</b></td>
                                      <td align='right'><b>".number_format($totctrrp,2)."%</b></td>
                                  </tr>";
                    }
        ?>          
  </table>
  <br>
  <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
</div>
</div>
</div>
</div>
<!-- <div class="row">
    <div class="col-sm-6">
        <div class="white-box">
            <h3 class="box-title">Overall Chart</h3>
            <div>
                <div id="overallchart" height="150"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="white-box">
            <h3 class="box-title">Bar Chart</h3>
            <div>
                <div id="chart" height="150"></div>
            </div>
        </div>
    </div>
</div> -->
<script src="<?= base_url(); ?>assets/plugins/bower_components/raphael/raphael-min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/morrisjs/morris.js"></script>
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });

    /*Morris.Bar({
        element : 'overallchart',
        data:[<?php echo $overall_chart_data; ?>],
        xkey:'month',
        ykeys:['target', 'spb', 'sj', 'nota'],
        labels:['Target', 'SPB', 'SJ','NOTA'],
        hideHover:'auto'
    });

    Morris.Bar({
        element : 'chart',
        data:[<?php echo $chart_data; ?>],
        xkey:'month',
        ykeys:['target', 'spb', 'sj', 'nota'],
        labels:['Target', 'SPB', 'SJ','NOTA'],
        hideHover:'auto',
        xLabelAngle: 60,
        barColors:['#55ce63','#009efb','#2f3d4a','orange']
    });*/
</script>
