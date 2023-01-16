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
                <p class="text-muted">Periode : <b><?= $tahun;?></b> Dari Tanggal : <b><?= $dfrom;?></b> Sampai Tanggal : <b><?= $dto;?></b></p>
                <p class="text-muted">Product : <b><?= $iproductgroup;?></b></p>
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
                            <th style="text-align: center;" rowspan="2">Island</th>
                            <th style="text-align: center;" rowspan="2">Provinsi</th>
                            <th style="text-align: center;" rowspan="2">OB</th>
                            <th style="text-align: center;" colspan="3">OA</th>
                            <th style="text-align: center;" colspan="3">Sales Qty(Unit)</th>
                            <th style="text-align: center;" colspan="3">Net Sales(Rp.)</th>
                            <th style="text-align: center;" rowspan="2">%Ctr Net Sales(Rp.)</th>
                        </tr>
                        <tr>
                            <?php $prevth= $tahun-1; ?>
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
                            $totprevoa=0;
                            $totoa=0;
                            $totprevqnota=0;
                            $totprevvnota=0;
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

                                if ($row->prevoa == 0) {
                                    $grwoa = 0;
                                }else{ /*//jika pembagi tidak 0*/
                                    $grwoa = (($row->oa-$row->prevoa)/$row->prevoa)*100;
                                }

                                if ($row->prevqnota == 0) {
                                    $grwqty = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwqty = (($row->qnota-$row->prevqnota)/$row->prevqnota)*100;
                                }

                                if ($row->prevvnota == 0) {
                                    $grwrp = 0;
                                } else { /*//jika pembagi tidak 0*/
                                    $grwrp = (($row->vnota-$row->prevvnota)/$row->prevvnota)*100;
                                }

                                $totob=$totob+$row->ob;
                                $ctrrp= ($row->vnota/$totrp)*100;
                                $totprevoa=$totprevoa+$row->prevoa;
                                $totoa=$totoa+$row->oa;
                                $totprevqnota=$totprevqnota+$row->prevqnota;
                                $totprevvnota=$totprevvnota+$row->prevvnota;
                                $totctrrp=$totctrrp+$ctrrp;?>

                                <tr>
                                    <td style='font-size:12px;'><?= $row->e_area_island;?></td>
                                    <td style='font-size:12px;'><?= $row->e_provinsi;?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->ob);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->prevoa);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->oa);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($grwoa,2);?> %</td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->prevqnota);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->qnota);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($grwqty,2);?> %</td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->prevvnota);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($row->vnota);?></td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($grwrp,2);?> %</td>
                                    <td style='font-size:12px; text-align: right;'><?= number_format($ctrrp,2);?> %</td>
                                </tr>
                            <?php }

                            if ($totprevoa == 0) {
                                $totgrwoa = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwoa = (($totoa-$totprevoa)/$totprevoa)*100;
                            }

                            if ($totprevqnota == 0) {
                                $totgrwqty = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwqty = (($totqnota-$totprevqnota)/$totprevqnota)*100;
                            }

                            if ($totprevvnota == 0) {
                                $totgrwrp = 0;
                            } else { /*//jika pembagi tidak 0*/
                                $totgrwrp = (($totvnota-$totprevvnota)/$totprevvnota)*100;
                            }?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style='font-size:12px; text-align: center;' colspan='2'><b>Total</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totob);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totprevoa);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totoa);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totgrwoa,2);?> %</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totprevqnota);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totqnota);?></b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totgrwqty,2);?> %</b></th>
                                <th style='font-size:12px; text-align: right;'><b><?= number_format($totprevvnota);?></b></th>
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
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });
</script>
