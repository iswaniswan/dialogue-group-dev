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
                <?php 
                $periode = '';
                if($isi){
                    $total=0;
                    $real=0;
                    foreach($isi as $row){
                        $periode=$row->i_periode;
                        $total=$total+$row->target;
                        $real=$real+$row->nota;
                    }
                }else{
                    $total=0;
                    $real=0;
                    $periode=$iperiode;
                }
                $i=0;
                if($periode==''){
                    $periode=$iperiode;
                }
                if($total>0){
                    $persenrea=($real/$total)*100;
                    $persenrea=number_format($persenrea,2);
                }else{
                    $persenrea="0.00";
                }
                $a=substr($periode,0,4);
                $b=substr($periode,4,2);
                $periode=$a;
                $total=number_format($total);
                $real=number_format($real);
                ?>
                <!-- <h5 class="box-title">Periode : <?= $periode;?></h5> -->
                <p class="text-muted">Periode : <?= $periode;?></p>
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 3%;">No</th>
                            <th style="text-align: center;">Bulan</th>
                            <th style="text-align: center;">Target</th>
                            <th style="text-align: center;">SPB</th>
                            <th style="text-align: center;">SJ</th>
                            <th style="text-align: center;">Nota</th>
                            <th style="text-align: center;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($isi){
                            $target=0;
                            $spb=0;
                            $sj=0;
                            $nota=0;
                            $chart_data = '';
                            $data = '';
                            foreach($isi as $row){
                                $i++;
                                if($row->nota==null || $row->nota=='')$row->nota=0;
                                if($row->target!=0){
                                    $persen=number_format(($row->nota/$row->target)*100,2);
                                }else{
                                    $persen='0.00';
                                }
                                if($row->spb==null || $row->spb=='')$row->spb=0;
                                $period=mbulan(substr($row->i_periode,4,2));

                                if($row->sj==null || $row->sj=='')$row->sj=0;
                                $period=mbulan(substr($row->i_periode,4,2));
                                ?>

                                <tr>
                                    <td style='font-size:12px; text-align: center;'><?= $i;?></td>
                                    <td style='font-size:12px;'><?= $period;?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->target);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->spb);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->sj);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->nota);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= $persen;?> %</td>
                                </tr>
                                <?php    
                                $target=$target+$row->target;
                                $nota=$nota+$row->nota;
                                $spb=$spb+$row->spb;
                                $sj=$sj+$row->sj;
                                $chart_data .= "{month:'".$period."', target:".$row->target.", spb:".$row->spb.", sj:".$row->sj.", nota:".$row->nota."}, ";
                                $data .= "{month:'".$period."', target:".$row->target.", spb:".$row->spb.", sj:".$row->sj."}, ";
                            }
                            $chart_data = substr($chart_data, 0, -2);
                            $data = substr($data, 0, -2);
                            $persen=number_format(($nota/$target)*100,2);?>
                            <tr>
                                <td style='font-size:12px; text-align: center;' colspan='2'><b>Total</b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($target);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($spb);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($sj);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($nota);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= $persen;?> %</b></td>
                            </tr>
                        <?php } 
                        $blfrom = date('m', strtotime($dfrom));
                        $bulan  = date('m', strtotime($dto));
                        $jud_periode = mbulan($blfrom)." s.d ".mbulan($bulan)." ".$periode;
                        $overall_chart_data = "{month:'".$jud_periode."', target:".$target.", spb:".$spb.", sj:".$sj.", nota:".$nota."}";
                        ?>
                    </tbody>
                </table>
                <br>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
            </div>
        </div>
    </div>
</div>
<div class="row">
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
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/raphael/raphael-min.js"></script>
<script src="<?= base_url(); ?>assets/plugins/bower_components/morrisjs/morris.js"></script>
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });

    Morris.Bar({
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
    });
</script>
