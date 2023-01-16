<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-bar-chart-o"></i> <?= $title; ?>
            <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php 
                if($iproductgroup == "01"){ 
                    $iproductgroup = "Dialogue Baby Bedding";
                }elseif($iproductgroup == "02"){
                    $iproductgroup = "Dialogue Baby Non Bedding";
                }elseif($iproductgroup == "MO"){
                    $iproductgroup = "Modern Outlet";
                }elseif($iproductgroup == "NA"){
                    $iproductgroup = "Nasional";
                }elseif($iproductgroup == "00"){
                    $iproductgroup = "Dialogue Home";
                }elseif($iproductgroup == "06"){
                    $iproductgroup = "Dialogue Fashion";
                }else{
                    $iproductgroup = " ";
                };?>
                <p class="text-muted">Dari Tanggal : <b><?= $dfrom;?></b> Sampai Tanggal : <b><?= $dto;?></b></p>
                <p class="text-muted">Product : <b><?= $iproductgroup;?></b></p>
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th style="font-size: 12px; text-align: center;" rowspan="2">No.</th>
                            <th style="font-size: 12px; text-align: center;" rowspan="2">Island</th>
                            <th style="font-size: 12px; text-align: center;" rowspan="2">Provinsi</th>
                            <th style="font-size: 12px; text-align: center;" rowspan="2">Area</th>
                            <th style="font-size: 12px; text-align: center;" rowspan="2">OB</th>
                            <th style="font-size: 12px; text-align: center;" colspan="3">OA</th>
                            <th style="font-size: 12px; text-align: center;" colspan="3">Sales Qty(Unit)</th>
                            <th style="font-size: 12px; text-align: center;" colspan="3">Net Sales (Rp.)</th>
                            <th style="font-size: 12px; text-align: center;" rowspan="2">% Ctr <br> Net Sales (Rp.)</th>
                        </tr>
                        <?php 
                        $pecah1     = explode('-', $dfrom);
                        $tgl1       = $pecah1[0];
                        $bln1       = $pecah1[1];
                        $tahun1     = $pecah1[2];
                        $tahunprev1 = intval($tahun1) - 1;

                        $pecah2     = explode('-', $dto);
                        $tgl2       = $pecah2[0];
                        $bln2       = $pecah2[1];
                        $tahun2     = $pecah2[2];
                        $tahunprev2 = intval($tahun2) - 1;

                        $gabung1 = $tgl1.'-'.$bln1.'-'.$tahunprev1;
                        $gabung2 = $tgl2.'-'.$bln2.'-'.$tahunprev2;
                        ?>
                        <tr align="center">
                            <th style="font-size: 12px; text-align: center;"><?php echo $tahunprev1; ?></th>
                            <th style="font-size: 12px; text-align: center;"><?php echo $tahun1; ?></th>
                            <th style="font-size: 12px; text-align: center;">% Growth</th>
                            <th style="font-size: 12px; text-align: center;"><?php echo $tahunprev1; ?></th>
                            <th style="font-size: 12px; text-align: center;"><?php echo $tahun1; ?></th>
                            <th style="font-size: 12px; text-align: center;">% Growth</th>
                            <th style="font-size: 12px; text-align: center;"><?php echo $tahunprev1; ?></th>
                            <th style="font-size: 12px; text-align: center;"><?php echo $tahun1; ?></th>
                            <th style="font-size: 12px; text-align: center;">% Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($isi){
                            $no = 0;
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

                                /*untuk OA*/
                                if($row->oaprev == 0){
                                    $growthoa = 0;
                                }else{
                                    $growthoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                                }

                                /*untuk QTY*/
                                if($row->qtyprev == 0){
                                    $growthqty = 0;
                                }else{
                                    $growthqty = (($row->qty-$row->qtyprev)/$row->qtyprev)*100;
                                }

                                /*untuk Vnota*/
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
                                $no++;?>
                                <tr>
                                    <td style="font-size: 12px; text-align: center;"><?= $no;?></td>
                                    <td style="font-size: 12px;"><?= $row->e_area_island;?></td>
                                    <td style="font-size: 12px;"><?= $row->e_provinsi;?></td>
                                    <td style="font-size: 12px;"><?= $row->e_area_name;?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->ob);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->oaprev);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->oa);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($growthoa,2);?> %</td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->qtyprev);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->qty);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($growthqty,2);?> %</td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->vnotaprev);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($row->vnota);?></td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($growthvnota,2);?> %</td>
                                    <td style="font-size: 12px; text-align: right;"><?= number_format($ctrsales,2);?> %</td>
                                </tr>
                                <?php
                                $totalob            += $row->ob;
                                $totaloaprev        += $row->oaprev;
                                $totaloa            += $row->oa;
                                $totalqtyprev       += $row->qtyprev;
                                $totalqty           += $row->qty;
                                $totalvnotaprev     += $row->vnotaprev;
                                $totalvnota         += $row->vnota;
                                $totalctrsales      += $ctrsales;
                            }
                        }
                        $totalgrowthoa      = (($totaloa-$totaloaprev)/$totaloaprev)*100;
                        $totalgrowthqty     = (($totalqty-$totalqtyprev)/$totalqtyprev)*100;
                        $totalgrowthvnota   = (($totalvnota-$totalvnotaprev)/$totalvnotaprev)*100;
                        ?>
                        <tfoot>
                            <tr>
                                <th colspan="4" style="text-align: center;"><b>Total</b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalob);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totaloaprev);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totaloa);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalgrowthoa,2);?> %</b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalqtyprev);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalqty);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalgrowthqty,2);?> %</b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalvnotaprev);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalvnota);?></b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalgrowthvnota,2);?> %</b></th>
                                <th style="text-align: right; font-size: 12px;"><b><?php echo number_format($totalctrsales,2);?> %</b></th>
                            </tr> 
                        </tfoot>
                    </tbody>
                </table>
                <br>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
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
