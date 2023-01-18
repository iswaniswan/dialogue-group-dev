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
                <h3>Total OB : <b><?= $ob->ob;?></b></h3>
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th style="text-align: center;" rowspan="2">No.</th>
                            <th style="text-align: center;" rowspan="2">Kode Product</th>
                            <th style="text-align: center;" rowspan="2">Nama Product</th>
                            <th style="text-align: center;" colspan="3">OA</th>
                            <th style="text-align: center;" colspan="3">Sales Qty (Unit)</th>
                            <th style="text-align: center;" colspan="3">Net Sales (Rp.)</th>
                            <th style="text-align: center;" rowspan="2">% Ctr <br> Net Sales (Rp.)</th>
                            <th style="text-align: center;" rowspan="2">% Ctr <br> Sales Qty </th>
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
                            <th style="text-align: center;"><?php echo $tahunprev1; ?></th>
                            <th style="text-align: center;"><?php echo $tahun1; ?></th>
                            <th style="text-align: center;">% Growth</th>
                            <th style="text-align: center;"><?php echo $tahunprev1; ?></th>
                            <th style="text-align: center;"><?php echo $tahun1; ?></th>
                            <th style="text-align: center;">% Growth</th>
                            <th style="text-align: center;"><?php echo $tahunprev1;?></th>
                            <th style="text-align: center;"><?php echo $tahun1; ?></th>
                            <th style="text-align: center;">% Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $totalob          = 0;
                        $totaloaprev      = 0;
                        $totaloa          = 0;
                        $totalnetitemprev = 0;  
                        $totalnetitem     = 0;  
                        $totalqtyprev     = 0;  
                        $totalqty         = 0;  
                        $totalctrsales    = 0;  
                        $totalctrqty      = 0;  
                        foreach ($isi as $key ) {
                            $totalnetitem += $key->netitem;
                            $totalnetitemprev   += $key->netitemprev;
                        }

                        foreach ($isi as $row) {
                            $growthoa    = 0;
                            $growthjml   = 0;
                            $growthvnota = 0;

                            /*untuk OA*/
                            if($row->oaprev == 0){
                                $growthoa = 0;
                            }else{
                                $growthoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                            }

                            /*untuk Qty*/
                            if($row->jmlprev == 0){
                                $growthjml = 0;
                            }else{
                                $growthjml = (($row->jml-$row->jmlprev)/$row->jmlprev)*100;
                            }

                            /*untuk net*/
                            if($row->netitemprev == 0){
                                $growthnetitem = 0;
                            }else{
                                $growthnetitem = (($row->netitem-$row->netitemprev)/$row->netitemprev)*100;
                            }

                            $ctrnetsales = ($row->netitem/$totalnetitem)*100;
                            if($totalqty == 0){
                                $ctrqty = 0;
                            }else{
                                $ctrqty = ($row->jml/$totalqty)*100;
                            }?>

                            <tr>
                                <td style="text-align: center; font-size: 12px;"><?= $no;?></td>
                                <td style="font-size: 12px;"><?= $row->i_product;?></td>
                                <td style="font-size: 12px;"><?= $row->e_product_name;?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($row->oaprev);?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($row->oa);?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($growthoa,2);?>%</td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($row->jmlprev);?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($row->jml);?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($growthjml,2);?>%</td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($row->netitemprev,2);?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($row->netitem,2);?></td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($growthnetitem,2);?>%</td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($ctrnetsales,2);?>%</td>
                                <td style="text-align: right; font-size: 12px;"><?= number_format($ctrqty,2);?>%</td>
                            </tr>
                            <?php
                            $no++;
                            $totalob            += $row->ob;
                            $totaloaprev        += $row->oaprev;
                            $totaloa            += $row->oa;
                            $totalqtyprev       += $row->jmlprev;
                            $totalqty           += $row->jml;
                            $totalctrsales      += $ctrnetsales;
                            $totalctrqty        += $ctrqty;
                        }
                        $totalgrowthoa      = (($totaloa-$totaloaprev)/$totaloaprev)*100;
                        $totalgrowthqty     = (($totalqty-$totalqtyprev)/$totalqtyprev)*100;
                        $totalgrowthnetitem = (($totalnetitem-$totalnetitemprev)/$totalnetitemprev)*100;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align: center;"><b>Total</b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totaloaprev,0);?></b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totaloa,0);?></b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalgrowthoa,2);?>%</b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalqtyprev,0);?></b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalqty,0);?></b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalgrowthqty,2);?>%</b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalnetitemprev,2);?></b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalnetitem,2);?></b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalgrowthnetitem,2);?>%</b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalctrsales,2);?>%</b></th>
                            <th style="text-align: right;"><b><?php echo number_format($totalctrqty,2);?>%</b></th>
                        </tr> 
                    </tfoot>
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
