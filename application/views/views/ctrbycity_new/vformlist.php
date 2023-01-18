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
                <p class="text-muted">Periode : <?= $tahun.' ('.$dfrom.' s/d '.$dto.')';?></p>
                <?php $query = $this->db->query("select e_product_groupname from tr_product_group where i_product_group='$iproductgroup'");
                    if ($query->num_rows() > 0){
                        foreach($query->result() as $tmp){
                            $iproductgroup=$tmp->e_product_groupname;
                            ?>
                            <p class="text-muted">Group : <?= $iproductgroup;?></p>
                            <?php
                        }
                    }
                    if($iproductgroup=="NA"){
                        $iproductgroup="NASIONAL";
                        ?>
                        <p class="text-muted">Group : <?= $iproductgroup;?></p>
                        <?php
                    }
                ?>
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
                            <th rowspan="2">No</th>
                            <th rowspan="2">Island</th>
                            <th rowspan="2">Provinsi</th>
                            <th rowspan="2">Area</th>
                            <th rowspan="2">City</th>
                            <th rowspan="2">OB</th>
                            <th colspan="3">OA</th>
                            <th colspan="3">Sales Qty(Unit)</th>
                            <th colspan="3">Net Sales(Rp.)</th>
                            <th rowspan="2">%Ctr Net Sales(Rp.)</th>
                        </tr>
                        <tr>
                            <?php $prevth= $tahun-1; ?>
                            <th><?php echo $prevth ?></th>
                            <th><?php echo $tahun ?></th>
                            <th>Growth OA</th>
                            <th><?php echo $prevth ?></th>
                            <th><?php echo $tahun ?></th>
                            <th>Growth Qty</th>
                            <th><?php echo $prevth ?></th>
                            <th><?php echo $tahun ?></th>
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
                          $i=0;
                        
                          foreach($isi as $row){
                              $totnota+=$row->vnota;
                          }
                          $totrp=$totnota;
                      
                      
                          foreach($isi as $row){
                            $i++;

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
                        
                            $totob=$totob+$row->ob;
                            $ctrrp= ($row->vnota/$totrp)*100;
                            $totprevoa=$totprevoa+$row->prevoa;
                            $totoa=$totoa+$row->oa;
                            $totprevqnota=$totprevqnota+$row->prevqnota;
                            $totprevvnota=$totprevvnota+$row->prevvnota;
                            $totctrrp=$totctrrp+$ctrrp;
                        
                        
                            echo "<tr>
                                <td style='font-size:12px;'>$i</td>
                                <td style='font-size:12px;'>$row->e_area_island</td>
                                <td style='font-size:12px;'>$row->e_provinsi</td>
                                <td style='font-size:12px;'>$row->e_area_name</td>
                                <td style='font-size:12px;'>$row->e_city_name</td>
                                <td style='font-size:12px;' align=right>".number_format($row->ob)."</td>
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
                          <td style='font-size:12px;' colspan='5'><b>Total</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totob)."</b></td>
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
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });
</script>
