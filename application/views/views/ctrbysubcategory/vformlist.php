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
                <p class="text-muted">Dari Tanggal : <?php echo $dfrom;?></p>
                <p class="text-muted">Sampai Tanggal : <?php echo $dto;?></p>
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
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Category</th>
                            <th colspan="3">OA</th>
                            <th colspan="3">Sales Qty(Unit)</th>
                            <th colspan="3">Net Sales</th>
                            <th rowspan="2">% Ctr <br> Net Sales</th>
                        </tr>
                        <?php 
                            $pecah1       = explode('-', $dfrom);
                            $tgl1       = $pecah1[0];
                            $bln1       = $pecah1[1];
                            $tahun1     = $pecah1[2];
                            $tahunprev1 = intval($tahun1) - 1;

                            $pecah2       = explode('-', $dto);
                            $tgl2       = $pecah2[0];
                            $bln2       = $pecah2[1];
                            $tahun2     = $pecah2[2];
                            $tahunprev2 = intval($tahun2) - 1;

                            $gabung1 = $tgl1.'-'.$bln1.'-'.$tahunprev1;
                            $gabung2 = $tgl2.'-'.$bln2.'-'.$tahunprev2;
                        ?>
                        <tr  align="center">
                            <th><?php echo $tahunprev1; ?></th>
                            <th><?php echo $tahun1; ?></th>
                            <th>%</th>
                            <th><?php echo $tahunprev1; ?></th>
                            <th><?php echo $tahun1; ?></th>
                            <th>%</th>
                            <th><?php echo $tahunprev1; ?></th>
                            <th><?php echo $tahun1; ?></th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <?php 
                        $no = 1;
                        $totalob            = 0;
                        $totaloaprev        = 0;
                        $totaloa            = 0;
                        $totalqtyprev       = 0;
                        $totalqty           = 0;
                        $totalvnotaprev     = 0;
                        $totalvnota         = 0;
                        $totalctrsales      = 0;
                        $totalnotaberjalan  = 0;
                        foreach ($isi as $key ) {
                          $totalnotaberjalan += $key->vnota;
                        }
                        foreach ($isi as $row) {
                            $growthoa    = 0;
                            $growthqty   = 0;
                            $growthvnota = 0;
                            
                            //untuk OA
                            if($row->oaprev == 0){
                                $growthoa = 0;
                            }else{
                                $growthoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                            }
                        
                            //untuk QTY
                            if($row->qtyprev == 0){
                                $growthqty = 0;
                            }else{
                                $growthqty = (($row->qty-$row->qtyprev)/$row->qtyprev)*100;
                            }
                        
                            //untuk Vnota
                            if($row->vnotaprev == 0){
                                $growthvnota = 0;
                            }else{
                                $growthvnota = (($row->vnota-$row->vnotaprev)/$row->vnotaprev)*100;
                            }      
                            if($row->vnota == 0){
                                $ctrsales = 0; 
                            }else{
                                $ctrsales =  ($row->vnota/$totalnotaberjalan)*100;      
                            }
                            //<td>".$row->ob."</td>
                                echo "<tr>
                                        <td>".$no."</td>
                                        <td>".$row->e_product_categoryname."</td>
                                        <td align='right'>".number_format($row->oaprev)."</td>
                                        <td align='right'>".number_format($row->oa)."</td>
                                        <td align='right'>".number_format($growthoa,2)."%</td>
                                        <td align='right'>".number_format($row->qtyprev)."</td>
                                        <td align='right'>".number_format($row->qty)."</td>
                                        <td align='right'>".number_format($growthqty,2)."%</td>
                                        <td align='right'>".number_format($row->vnotaprev,2)."</td>
                                        <td align='right'>".number_format($row->vnota,0)."</td>
                                        <td align='right'>".number_format($growthvnota,2)."%</td>
                                        <td align='right'>".number_format($ctrsales,2)."%</td>
                                      </tr>";
                            $no++;
                            $totalob            += $row->ob;
                            $totaloaprev        += $row->oaprev;
                            $totaloa            += $row->oa;
                            $totalqtyprev       += $row->qtyprev;
                            $totalqty           += $row->qty;
                            $totalvnotaprev     += $row->vnotaprev;
                            $totalvnota         += $row->vnota;
                            $totalctrsales      += $ctrsales;
                        }
                    ?>
                    <tbody>
                        <?php
                            $totalgrowthoa      = (($totaloa-$totaloaprev)/$totaloaprev)*100;
                            $totalgrowthqty     = (($totalqty-$totalqtyprev)/$totalqtyprev)*100;
                            $totalgrowthvnota   = (($totalvnota-$totalvnotaprev)/$totalvnotaprev)*100;
                        ?>
                        <tr align="center">
                          <td colspan="2"><b>Total</b></td>
                          <!--<td><b><?php echo number_format($totalob,0);?></b></td>-->
                          <td><b><?php echo number_format($totaloaprev,0);?></b></td>
                          <td><b><?php echo number_format($totaloa,0);?></b></td>
                          <td><b><?php echo number_format($totalgrowthoa,2);?>%</b></td>
                          <td><b><?php echo number_format($totalqtyprev,0);?></b></td>
                          <td><b><?php echo number_format($totalqty,0);?></b></td>
                          <td><b><?php echo number_format($totalgrowthqty,2);?>%</b></td>
                          <td><b><?php echo number_format($totalvnotaprev,2);?></b></td>
                          <td><b><?php echo number_format($totalvnota,2);?></b></td>
                          <td><b><?php echo number_format($totalgrowthvnota,2);?>%</b></td>
                          <td><b><?php echo number_format($totalctrsales,2);?>%</b></td>
                        </tr>  
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
