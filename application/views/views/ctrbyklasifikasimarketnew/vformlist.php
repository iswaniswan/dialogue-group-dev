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
                if($isi){
                  $totob=0;
                  $totoaprev=0;
                  $totqtyprev=0;
                  $totqty=0;
                  $totoa=0;
                  $totvnotaprev=0;
                  $totvnota=0;
                
                  foreach($isi as $ro){
                    $totob=$totob+$ro->ob;
                    $totoaprev=$totoaprev+$ro->oaprev;
                    $totoa=$totoa+$ro->oa;
                    $totvnotaprev=$totvnotaprev+$ro->vnotaprev;
                    $totvnota=$totvnota+$ro->vnota;
                    $totqty=$totqty+$ro->qnota;
                    $totqtyprev=$totqtyprev+$ro->qnotaprev;
                    if($iproductgroup!="NA"){
                    //$group = $ro->group;
                    $iproductgroup = $this->db->query("select e_product_groupname from tr_product_group where i_product_group = '$ro->i_product_group'")->row()->e_product_groupname;
                    // $group = $ro->i_product_group;
                    }else{
                      $iproductgroup='NA';
                    }
                  }
                }
            ?>
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
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th rowspan=3>No</th>
                            <th rowspan=3>Klasifikasi Outlet</th>
                            <th rowspan=2>OB</th>
                            <th colspan=3>OA</th>
                            <th colspan=3>Qty Sales</th>
                            <th colspan=3>Net Sales</th>
                            <th rowspan=2>% CTR</th>
                        </tr>
                        <?php 
                            $tmp=explode("-",$dfrom);
                            $thlalu=$tmp[2]-1;
                            $th=$tmp[2];
                        ?>
                        <tr>
                            <th><?php echo $thlalu; ?></th>
                            <th><?php echo $th; ?></th>
                            <th>%</th>
                            <th><?php echo $thlalu; ?></th>
                            <th><?php echo $th; ?></th>
                            <th>%</th>
                            <th><?php echo $thlalu; ?></th>
                            <th><?php echo $th; ?></th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if($isi){
                          $i=0;
                          $totnota=0;
                          $totpersenoaprev=0;
                          $totpersenoa=0;
                          $totctrrp=0;
                        
                        
                          foreach($isi as $rowa){
                              $totnota+=$rowa->vnota;
                          }
                          $totrp=$totnota;
                      
                          foreach($isi as $row){
                            $i++;
                            $growthnota=0;
                            $growthoa=0;
                            $growthqty=0;
                            $qtyly = $row->qnota-$row->qnotaprev;
                            $spbsely=$row->vnota-$row->vnotaprev;
                            $custsely=$row->oa-$row->oaprev;
                            ####################################################
                            $growthytdval=$totvnota/$totvnotaprev*100-100;
                            $growthytdoa=$totoa/$totoaprev*100-100;
                            $growthytdqty=$totqty/$totqtyprev*100-100;
                            $ctrrp= ($row->vnota/$totrp)*100;
                            $totctrrp= $totctrrp+$ctrrp;

                            if($row->vnotaprev==0){
                              $growthnota=0;
                            }else{
                              $growthnota=($spbsely/$row->vnotaprev)*100;
                            }
                            if($row->oaprev==0){
                              $growthoa=0;
                            }else{
                              $growthoa=($custsely/$row->oaprev)*100;
                            }
                            if($row->qnotaprev==0){
                              $growthqty=0;
                            }else{
                              $growthqty=($qtyly/$row->qnotaprev)*100;
                            }
                             //
                            echo "<tr>
                                <td style='font-size:12px;'>$i</td>
                                <td style='font-size:12px;'>$row->e_customer_classname</td>
                                <td style='font-size:12px;' align=right>".number_format($row->ob)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->oaprev)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->oa)."</td>
                                <td style='font-size:12px;' align=right>".number_format($growthoa,2)." %</td>
                                <td style='font-size:12px;' align=right>".number_format($row->qnotaprev)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->qnota)."</td>
                                <td style='font-size:12px;' align=right>".number_format($growthqty)." %</td>
                                <td style='font-size:12px;' align=right>".number_format($row->vnotaprev)."</td>
                                <td style='font-size:12px;' align=right>".number_format($row->vnota)."</td>
                                <td style='font-size:12px;' align=right>".number_format($growthnota,2)." %</td>
                        
                                <td style='font-size:12px;' align=right>".number_format($ctrrp,2)." %</td>
                                </tr>";
                           }
                          $growthytdval=$totvnota/$totvnotaprev*100-100;
                          $growthytdoa=$totoa/$totoaprev*100-100;
                          $growthytdqty=$totqty/$totqtyprev*100-100;
                          //
                          echo "<tr>
                          <td style='font-size:12px;' colspan=2><b>Total</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totob)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totoaprev)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totoa)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($growthytdoa,2)." %</b></td>
                       
                          <td style='font-size:12px;' align=right><b>".number_format($totqtyprev)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totqty)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($growthytdqty)." %</b></td>
                       
                          <td style='font-size:12px;' align=right><b>".number_format($totvnotaprev)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($totvnota)."</b></td>
                          <td style='font-size:12px;' align=right><b>".number_format($growthytdval,2)." %</b></td>
                       
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
        xkey:'island',
        ykeys:['grwoa', 'grwqty', 'grwrp'],
        labels:['GrowthOA', 'GrowthQTY', 'GrorwthRP'],
        hideHover:'auto'
    });

    Morris.Bar({
        element : 'chart',
        data:[<?php echo $chart_data; ?>],
        xkey:'island',
        ykeys:['grwoa', 'grwqty', 'grwrp'],
        labels:['GrowthOA', 'GrowthQTY', 'GrorwthRP'],
        hideHover:'auto',
        xLabelAngle: 60,
        barColors:['#55ce63','#009efb','#2f3d4a','orange']
    });
</script>
