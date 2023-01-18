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
                <p class="text-muted">Periode : Dari <b><?= $dfrom;?></b> Sampai <b><?= $dto;?></b></p>
                <h3>Total OB : <b><?= $ob->ob;?></b></h3>
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
                            <th style="text-align: center;" rowspan="2">Brand</th>
                            <th style="text-align: center;" colspan="3">OA</th>
                            <th style="text-align: center;" colspan="3">Sales Qty(Unit)</th>
                            <th style="text-align: center;" colspan="3">Net Sales (Rp.)</th>
                            <th style="text-align: center;" rowspan="2">% Ctr Net Sales(Rp.)</th>
                        </tr>
                        <tr>
                            <?php $prevtahun = $tahun-1 ?>
                            <th style="text-align: center;"><?php echo $prevtahun ?></th>
                            <th style="text-align: center;"><?php echo $tahun ?></th>
                            <th style="text-align: center;">Growth OA</th>
                            <th style="text-align: center;"><?php echo $prevtahun ?></th>
                            <th style="text-align: center;"><?php echo $tahun ?></th>
                            <th style="text-align: center;">Growth Qty</th>
                            <th style="text-align: center;"><?php echo $prevtahun ?></th>
                            <th style="text-align: center;"><?php echo $tahun ?></th>
                            <th style="text-align: center;">Growth Rp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($isi){
                            $totpersenvnota=0;
                            $grwoa=0;
                            $grwqty=0;
                            $grwrp=0;
                            $totrp=0;
                            $ctrrp=0;
                            $totnota=0;
                            $totoaprev=0;
                            $totoa=0;
                            $totgrwoa=0;
                            $totgrwqty=0;
                            $totgrwrp=0;
                            $totoaprev=0;
                            $totob=0;
                            $totqnotaprev=0;
                            $totvnotaprev=0;
                            $totctrrp=0;
                            $totsales=0;
                            foreach($isi as $row){
                                $totnota+=$row->vnota;
                            }

                            $totrp=$totnota;

                            foreach($isi as $row){
                                if($totvnota==0){
                                    $persenvnota=0;
                                }else{
                                    $persenvnota=($row->vnota/$totvnota)*100;
                                }
                                $totpersenvnota=$totpersenvnota+$persenvnota;


                                if ($row->oaprev == 0) {
                                    $grwoa = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                                }

                                if ($row->qnotaprev == 0) {
                                    $grwqty = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwqty = (($row->qnota-$row->qnotaprev)/$row->qnotaprev)*100;
                                }

                                if ($row->vnotaprev == 0) {
                                    $grwrp = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwrp = (($row->vnota-$row->vnotaprev)/$row->vnotaprev)*100;
                                }

                                $ctrrp= ($row->vnota/$totrp)*100;
                                $totoaprev=$totoaprev+$row->oaprev;
                                $totsales=$totsales+$row->totsales;
                                $totob=$totob+$row->ob;
                                $totoa=$totoa+$row->oa;
                                $totqnotaprev= $totqnotaprev+$row->qnotaprev;
                                $totvnotaprev= $totvnotaprev+$row->vnotaprev;
                                $totctrrp=$totctrrp+$ctrrp;?>

                                <tr>
                                    <td style='font-size:12px;'><?= $row->group;?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($row->oaprev);?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($row->oa);?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($grwoa,2);?> %</td>
                                    <td style='font-size:12px;' align=right><?= number_format($row->qnotaprev);?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($row->qnota);?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($grwqty,2);?> %</td>
                                    <td style='font-size:12px;' align=right><?= number_format($row->vnotaprev);?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($row->vnota);?></td>
                                    <td style='font-size:12px;' align=right><?= number_format($grwrp,2);?> %</td>
                                    <td style='font-size:12px;' align=right><?= number_format($ctrrp,2);?> %</td>
                                </tr>
                            <?php }

                            if ($totoaprev == 0) {
                                $totgrwoa = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwoa = (($totoa-$totoaprev)/$totoaprev)*100;
                            }

                            if ($totqnotaprev == 0) {
                                $totgrwqty = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwqty = (($totqnota-$totqnotaprev)/$totqnotaprev)*100;
                            }

                            if ($totvnotaprev == 0) {
                                $totgrwrp = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwrp = (($totvnota-$totvnotaprev)/$totvnotaprev)*100;
                            }?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style='font-size:12px;' ><b>Total</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totoaprev);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totoa);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totgrwoa,2);?> %</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totqnotaprev);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totqnota);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totgrwqty,2);?> %</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totvnotaprev);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totvnota);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totgrwrp,2);?> %</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totctrrp,2);?> %</b></th>
                            </tr>
                        </tfoot>
                    <?php } ?>
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
