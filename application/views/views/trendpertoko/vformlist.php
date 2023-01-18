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
                <p class="text-muted">Periode : <?php echo $thskrng;?></p>
                <?php 
                    if($isi){
                      $totvnota=0;
                      $totqnota=0;
                      foreach($isi as $ro){
                        $totvnota=$totvnota+$ro->vnota;
                        $totqnota=$totqnota+$ro->qnota;
                      }
                    }
                ?>
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th rowspan="2">Provinsi</th>
                            <th rowspan="2">Area</th>
                            <th rowspan="2">Kode Pelanggan</th>
                            <th rowspan="2">Pelanggan</th>
                            <th rowspan="2">Klasifikasi</th>
                            <th colspan="3">OA</th>
                            <th colspan="3">Sales Qty(Unit)</th>
                            <th colspan="3">Net Sales(Rp.)</th>
                            <th rowspan="2">%Ctr Net Sales(Rp.)</th>
                        </tr>
                        <tr>
                            <th><?php echo $thnsebelumnya ?></th>
                            <th><?php echo $thskrng ?></th>
                            <th>Growth OA</th>
                            <th><?php echo $thnsebelumnya ?></th>
                            <th><?php echo $thskrng ?></th>
                            <th>Growth Qty</th>
                            <th><?php echo $thnsebelumnya ?></th>
                            <th><?php echo $thskrng ?></th>
                            <th>Growth Rp</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if($isi){
                          $grwoa=0;
                          $grwqty=0;
                          $grwrp=0;
                          $totnota=0;
                          $totrp=0;
                          //$ctrrp=0;
                          $totprevoa=0;
                          $totoa=0;
                          $totprevqnota=0;
                          $totprevvnota=0;
                          $totctrrp=0;
                          $totgrwoa=0;
                          $totgrwqty=0;
                          $totgrwrp=0;
                          $totpersenvnota=0;
                        
                          foreach($isi as $row){
                              $totnota+=$row->vnota;
                          }
                          $totrp=$totnota;
                          //var_dump($totrp);
                      
                          foreach($isi as $row){

                            if($totvnota==0){
                              $persenvnota=0;
                            }else{
                              $persenvnota=($row->vnota/$totvnota)*100;
                            }
                            $totpersenvnota=$totpersenvnota+$persenvnota;
                        
                            if ($row->prevoa == 0) {
                                $grwoa = 0;
                            } else { //jika pembagi tidak 0
                                $grwoa = (($row->oa-$row->prevoa)/$row->prevoa)*100;
                            }
                        
                            if ($row->prevqnota == 0) {
                                $grwqty = 0;
                            } else { //jika pembagi tidak 0
                                $grwqty = (($row->qnota-$row->prevqnota)/$row->prevqnota)*100;
                            }
                        
                            if ($row->prevvnota == 0) {
                                $grwrp = 0;
                            } else { //jika pembagi tidak 0
                                $grwrp = (($row->vnota-$row->prevvnota)/$row->prevvnota)*100;
                            }
                        
                            $ctrrp= $row->vnota/$totrp;
                            //var_dump($ctrrp);
                            $totprevoa=$totprevoa+$row->prevoa;
                            $totoa=$totoa+$row->oa;
                            $totprevqnota=$totprevqnota+$row->prevqnota;
                            $totprevvnota=$totprevvnota+$row->prevvnota;
                            $totctrrp=$totctrrp+$ctrrp*100;
                        
                        
                            echo "<tr>
                                <td style='font-size:12px;'>".$row->e_provinsi."</td>
                                <td style='font-size:12px;'>".$row->i_area."-".$row->e_area_name."</td>
                                <td style='font-size:12px;'>".$row->i_customer."</td>
                                <td style='font-size:12px;'>".$row->e_customer_name."</td>
                                <td style='font-size:12px;'>".$row->e_customer_classname."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->prevoa)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->oa)."</td>
                                <td style='font-size:12px;' align=right>".number_format($grwoa,2)." %</td>
                                <td style='font-size:12px;' align=right>".number_format($row->prevqnota)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->qnota)."</td>
                                <td style='font-size:12px;' align=right>".number_format($grwqty,2)." %</td>
                                <td style='font-size:12px;' align=right>".number_format($row->prevvnota)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->vnota)."</td>
                                <td style='font-size:12px;' align=right>".number_format($grwrp,2)." %</td>
                                <td style='font-size:12px;' align=right>".number_format($ctrrp,2)." %</td>
                                </tr>";
                           }
                       
                           if ($totprevoa == 0) {
                                $totgrwoa = 0;
                            } else { //jika pembagi tidak 0
                                $totgrwoa = (($totoa-$totprevoa)/$totprevoa)*100;
                            }
                        
                            if ($totprevqnota == 0) {
                                $totgrwqty = 0;
                            } else { //jika pembagi tidak 0
                                $totgrwqty = (($totqnota-$totprevqnota)/$totprevqnota)*100;
                            }
                        
                            if ($totprevvnota == 0) {
                                $totgrwrp = 0;
                            } else { //jika pembagi tidak 0
                                $totgrwrp = (($totvnota-$totprevvnota)/$totprevvnota)*100;
                            }
                        
                          echo "<tr>
                          <td style='font-size:12px;' colspan=5><b>Total</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totprevoa)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totoa)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totgrwoa,2)." %</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totprevqnota)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totqnota)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totgrwqty,2)." %</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totprevvnota)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totvnota)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totgrwrp,2)." %</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totctrrp,2)." %</b></td>
                          </tr>";
                        }
                    ?>
      </tbody>
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
