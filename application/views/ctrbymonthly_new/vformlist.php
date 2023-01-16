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
                };
                $tahun = date('Y', strtotime($dfrom));
                ?>
                <p class="text-muted">Periode : <b><?= $tahun;?></b> Dari Tanggal : <b><?= $dfrom;?></b> Sampai Tanggal : <b><?= $dto;?></b></p>
                <p class="text-muted">Product : <b><?= $iproductgroup;?></b></p>
                <?php 
                if($isi){
                    $totvnota=0;
                    $totqty=0;
                    foreach($isi as $ro){
                        $totvnota=$totvnota+$ro->vnota;
                        $totqty=$totqty+$ro->qty;
                    }
                }?>
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th style="text-align: center;" rowspan="2">Bulan</th>
                            <th style="text-align: center;" rowspan="2">OB</th>
                            <th style="text-align: center;" colspan="3">OA</th>
                            <th style="text-align: center;" colspan="3">Sales Qty(Unit)</th>
                            <th style="text-align: center;" colspan="3">Net Sales(Rp.)</th>
                            <th style="text-align: center;" rowspan="2">%Ctr Net Sales(Rp.)</th>
                        </tr>
                        <tr>
                            <?php $prevth = $tahun-1; ?>
                            <th style="text-align: center;"><?php echo $prevth ?></th>
                            <th style="text-align: center;"><?php echo $tahun ?></th>
                            <th style="text-align: center;">Growth OA</th>
                            <th style="text-align: center;"><?php echo $prevth ?></th>
                            <th style="text-align: center;"><?php echo $tahun ?></th>
                            <th style="text-align: center;">Growth Qty</th>
                            <th style="text-align: center;"><?php echo $prevth ?></th>
                            <th style="text-align: center;"><?php echo $tahun ?></th>
                            <th style="text-align: center;">Growth Rp</th>
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
                            $ctrrp=0;
                            $totoaprev=0;
                            $totoa=0;
                            $totqtyprev=0;
                            $totvnotaprev=0;
                            $totctrrp=0;
                            $totgrwoa=0;
                            $totgrwqty=0;
                            $totgrwrp=0;
                            $totpersenvnota=0;
                            $totob=0;

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
                                }else{ /*//jika pembagi tidak 0*/
                                    $grwoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                                }

                                if ($row->qtyprev == 0) {
                                    $grwqty = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwqty = (($row->qty-$row->qtyprev)/$row->qtyprev)*100;
                                }

                                if ($row->vnotaprev == 0) {
                                    $grwrp = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwrp = (($row->vnota-$row->vnotaprev)/$row->vnotaprev)*100;
                                }

                                $ctrrp= ($row->vnota/$totrp)*100;
                                $totoaprev=$totoaprev+$row->oaprev;
                                $totoa=$totoa+$row->oa;
                                $totqtyprev=$totqtyprev+$row->qtyprev;
                                $totvnotaprev=$totvnotaprev+$row->vnotaprev;
                                $totctrrp=$totctrrp+$ctrrp;
                                $totob=$totob+$row->ob;

                                if(($row->i_periode != '')||($row->i_periode!=NULL)){
                                    $period=mbulan($row->i_periode);
                                }else{
                                    $period= "Tidak Order";
                                } ?>

                                <tr>
                                    <td style='font-size:12px;'><?= $period;?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->ob);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->oaprev);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->oa);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($grwoa,2);?> %</td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->qtyprev);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->qty);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($grwqty,2);?> %</td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->vnotaprev);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->vnota);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($grwrp,2);?> %</td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($ctrrp,2);?> %</td>
                                </tr>
                            <?php }
                            if ($totoaprev == 0) {
                                $totgrwoa = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwoa = (($totoa-$totoaprev)/$totoaprev)*100;
                            }

                            if ($totqtyprev == 0) {
                                $totgrwqty = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwqty = (($totqty-$totqtyprev)/$totqtyprev)*100;
                            }

                            if ($totvnotaprev == 0) {
                                $totgrwrp = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwrp = (($totvnota-$totvnotaprev)/$totvnotaprev)*100;
                            }?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style='font-size:12px;' ><b>Total</b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totob);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totoaprev);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totoa);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totgrwoa,2);?> %</b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totqtyprev);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totqty);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totgrwqty,2);?> %</b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totvnotaprev);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totvnota);?></b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totgrwrp,2);?> %</b></td>
                                <td style='font-size:12px; text-align: right;'><b><?= number_format($totctrrp,2);?> %</b></td>
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
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });
</script>
