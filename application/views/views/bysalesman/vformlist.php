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
                <table class="table color-table info-table hover-table table-bordered" id="sitabel">
                    <thead>
                        <tr>
                            <th style="text-align: center;" rowspan="2">Area</th>
                            <th style="text-align: center;" rowspan="2">Salesman</th>
                            <th style="text-align: center;" colspan="3">Collection(Ytd)</th>
                            <th style="text-align: center;" colspan="3">Selling Out(Ytd)</th>
                            <th style="text-align: center;" rowspan="2">OB</th>
                            <th style="text-align: center;" colspan="3">OA</th>
                            <th style="text-align: center;" colspan="3">Sales Qty(Unit)</th>
                            <th style="text-align: center;" colspan="3">Net Sales(Rp)</th>
                            <th style="text-align: center;" colspan="1">%ctr</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">Target</th>
                            <th style="text-align: center;">Realisasi</th>
                            <th style="text-align: center;">%</th>
                            <th style="text-align: center;">Target</th>
                            <th style="text-align: center;">Realisasi</th>
                            <th style="text-align: center;">%</th>
                            <th style="text-align: center;"><?php echo $prevth; ?></th>
                            <th style="text-align: center;"><?php echo $th?></th>
                            <th style="text-align: center;">Growth</th>
                            <th style="text-align: center;"><?php echo $prevth; ?></th>
                            <th style="text-align: center;"><?php echo $th?></th>
                            <th style="text-align: center;">Growth</th>
                            <th style="text-align: center;"><?php echo $prevth; ?></th>
                            <th style="text-align: center;"><?php echo $th?></th>
                            <th style="text-align: center;">Growth</th>
                            <th style="text-align: center;">Net Sales(Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($isi){
                            $perper=$th.$bl;
                            /*$this->db->query("DELETE FROM tm_spbysls WHERE i_periode='$perper'");*/

                            $area='';
                            $persencoll=0;
                            $persensales=0;
                            $grwoa=0;
                            $grwqty=0;
                            $grwrp=0;
                            $totnota=0;
                            $ctrnota=0;
                            $gtottargetcoll=0;
                            $gtotrealisasicoll=0;
                            $gtottargetsls=0;
                            $gtotob=0;
                            $gtotoaprev=0;
                            $gtotoa=0;
                            $gtotqtyprev=0;
                            $gtotqty=0;
                            $gtotvnotaprev=0;
                            $gtotnetsales=0;
                            $gtotctrnota=0;
                            foreach ($isi as $riw) {
                                $totnota=$totnota+$riw->netsales;
                            }

                            foreach ($isi as $row ) {
                                if($area==''){
                                    $totoaprev=0;
                                    $totoa=0;
                                    $totqtyprev=0;
                                    $totqty=0;
                                    $totvnotaprev=0;
                                    $totnetsales=0;
                                    $totob=0;
                                    $totctrnota=0;
                                    $tottargetcoll=0;
                                    $totrealisasicoll=0;
                                    $tottargetsls=0;
                                    $totsencoll=0;
                                    $totsensls=0;
                                    $totgrwoa = 0;
                                    $totgrwqty = 0;
                                    $totgrwrp = 0;                  
                                }else{
                                    if($area!=$row->i_area){
                                     echo "<tr>
                                     <th colspan='2'><b>Total ".strtoupper($area ."-". $areaname)."</b></td>
                                     <th align=right><b>".number_format($tottargetcoll)."</b></th>
                                     <th align=right><b>".number_format($totrealisasicoll)."</b></th>
                                     <th align=right><b>".number_format($totsencoll,2)."%</b></th>
                                     <th align=right><b>".number_format($tottargetsls)."</b></th>
                                     <th align=right><b>".number_format($totnetsales)."</b></th>
                                     <th align=right><b>".number_format($totsensls,2)."%</b></th>
                                     <th align=right><b>".number_format($totob)."</b></th>
                                     <th align=right><b>".number_format($totoaprev)."</b></th>
                                     <th align=right><b>".number_format($totoa)."</b></th>
                                     <th align=right><b>".number_format($totgrwoa,2)."%</b></th>
                                     <th align=right><b>".number_format($totqtyprev)."</b></th>
                                     <th align=right><b>".number_format($totqty)."</b></th>
                                     <th align=right><b>".number_format($totgrwqty,2)."%</b></th>
                                     <th align=right><b>".number_format($totvnotaprev)."</b></th>
                                     <th align=right><b>".number_format($totnetsales)."</b></th>
                                     <th align=right><b>".number_format($totgrwrp,2)."%</b></th>
                                     <th align=right><b>".number_format($totctrnota,2)."%</b></th>
                                     </tr>";

                                     $tottargetcoll=0;
                                     $totrealisasicoll=0;
                                     $totsencoll=0;
                                     $tottargetsls=0;
                                     $totoaprev=0;
                                     $totoa=0;
                                     $totqtyprev=0;
                                     $totqty=0;
                                     $totvnotaprev=0;
                                     $totnetsales=0;
                                     $totob=0;
                                     $totctrnota=0;
                                     $totsensls=0;
                                     $totgrwoa = 0;
                                     $totgrwqty = 0;
                                     $totgrwrp = 0;
                                 }
                             }
                             $area=$row->i_area;
                             $areaname=$row->e_area_name;
                             if($row->vtargetcoll==0){
                                 $persencoll=0;
                             }else{
                                $persencoll=($row->vrealisasicoll/$row->vtargetcoll)*100;
                            }

                            if($row->vtargetsls==0){
                             $persensales=0;
                         }else{
                            $persensales=($row->netsales/$row->vtargetsls)*100;
                        }

                        if ($row->oaprev == 0) {
                            $grwoa = 0;
                        } else { /*//jika pembagi tidak 0*/
                            $grwoa = (($row->oa-$row->oaprev)/$row->oaprev)*100;
                        }

                        if ($row->qtyprev == 0) {
                          $grwqty = 0;
                      } else { /*//jika pembagi tidak 0*/
                          $grwqty = (($row->qty-$row->qtyprev)/$row->qtyprev)*100;
                      }

                      if ($row->netsalesprev == 0) {
                          $grwrp = 0;
                      } else { /*//jika pembagi tidak 0*/
                          $grwrp = (($row->netsales-$row->netsalesprev)/$row->netsalesprev)*100;
                      }

                      if($totnota==0){
                        $ctrnota=0;
                    }else{
                        $ctrnota= ($row->netsales/$totnota)*100;
                    }
                    $xcoll=str_replace(',','',number_format($persencoll,2));
                    $xsls=str_replace(',','',number_format($persensales,2));
                    $xoa=str_replace(',','',number_format($grwoa,2));
                    $xqty=str_replace(',','',number_format($grwqty,2));
                    $xrp=str_replace(',','',number_format($grwrp,2));
                    $xctr=str_replace(',','',number_format($ctrnota,2));
                    /*$this->db->query(" insert into tm_spbysls(i_periode, i_area, e_area_name, i_salesman, e_salesman_name, v_targetcoll, v_realcoll,
                      n_realcoll, v_targetsls, v_realsls, n_realsls, n_ob, n_lastoa, n_nowoa, n_growthoa, n_lastslsqty, n_nowslsqty,
                      n_growthslsqty, v_lastnetsls, v_nownetsls, n_growthnetsls, n_ctr) values ('$perper', '$row->i_area', '$row->e_area_name',
                      '$row->i_salesman', '$row->e_salesman_name', $row->vtargetcoll, $row->vrealisasicoll, $xcoll, $row->vtargetsls, 
                      $row->netsales, $xsls, $row->ob, $row->oaprev, $row->oa, $xoa, $row->qtyprev, $row->qty, $xqty, 
                      $row->netsalesprev, $row->netsales, $xrp, $xctr)");*/
                    echo "<tr>
                    <td>$row->i_area-$row->e_area_name</td>
                    <td>$row->i_salesman-$row->e_salesman_name</td>
                    <td align=right >".number_format($row->vtargetcoll)."</td>
                    <td align=right >".number_format($row->vrealisasicoll)."</td>
                    <td align=right >".number_format($persencoll,2)."%</td>
                    <td align=right >".number_format($row->vtargetsls)."</td>
                    <td align=right >".number_format($row->netsales)."</td>
                    <td align=right >".number_format($persensales,2)."%</td>
                    <td align=right >".number_format($row->ob)."</td>
                    <td align=right >".number_format($row->oaprev)."</td>
                    <td align=right >".number_format($row->oa)."</td>
                    <td align=right >".number_format($grwoa,2)."%</td>
                    <td align=right >".number_format($row->qtyprev)."</td>
                    <td align=right >".number_format($row->qty)."</td>
                    <td align=right >".number_format($grwqty,2)."%</td>
                    <td align=right >".number_format($row->netsalesprev)."</td>
                    <td align=right >".number_format($row->netsales)."</td>
                    <td align=right >".number_format($grwrp,2)."%</td>
                    <td align=right >".number_format($ctrnota,2)."%</td>
                    <tr>";



                    $tottargetcoll=$tottargetcoll+$row->vtargetcoll;
                    $totrealisasicoll=$totrealisasicoll+$row->vrealisasicoll;
                    $tottargetsls=$tottargetsls+$row->vtargetsls;
                    $totob=$totob+$row->ob;
                    $totoaprev=$totoaprev+$row->oaprev;
                    $totoa=$totoa+$row->oa;
                    $totqtyprev=$totqtyprev+$row->qtyprev;
                    $totqty=$totqty+$row->qty;
                    $totvnotaprev=$totvnotaprev+$row->netsalesprev;
                    $totnetsales=$totnetsales+$row->netsales;
                    $totctrnota=$totctrnota+$ctrnota;

                    $gtottargetcoll=$gtottargetcoll+$row->vtargetcoll;
                    $gtotrealisasicoll=$gtotrealisasicoll+$row->vrealisasicoll;
                    $gtottargetsls=$gtottargetsls+$row->vtargetsls;
                    $gtotob=$gtotob+$row->ob;
                    $gtotoaprev=$gtotoaprev+$row->oaprev;
                    $gtotoa=$gtotoa+$row->oa;
                    $gtotqtyprev=$gtotqtyprev+$row->qtyprev;
                    $gtotqty=$gtotqty+$row->qty;
                    $gtotvnotaprev=$gtotvnotaprev+$row->netsalesprev;
                    $gtotnetsales=$gtotnetsales+$row->netsales;
                    $gtotctrnota=$gtotctrnota+$ctrnota;

                    if ($tottargetcoll == 0) {
                      $totsencoll = 0;
                  } else { /*//jika pembagi tidak 0*/
                      $totsencoll = ($totrealisasicoll/$tottargetcoll)*100;
                  }

                  if ($tottargetsls == 0) {
                      $totsensls = 0;
                  } else { /*//jika pembagi tidak 0*/
                      $totsensls = ($totnetsales/$tottargetsls)*100;
                  }

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
                      $totgrwrp = (($totnetsales-$totvnotaprev)/$totvnotaprev)*100;
                  }

              }/*// END FOREACH */
              if ($tottargetcoll == 0) {
                  $totsencoll = 0;
              } else { /*//jika pembagi tidak 0*/
                  $totsencoll = ($totrealisasicoll/$tottargetcoll)*100;
              }

              if ($tottargetsls == 0) {
                  $totsensls = 0;
              } else { /*//jika pembagi tidak 0*/
                  $totsensls = ($totnetsales/$tottargetsls)*100;
              }

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
                  $totgrwrp = (($totnetsales-$totvnotaprev)/$totvnotaprev)*100;
              }

              if ($gtottargetcoll == 0) {
                  $gtotsencoll = 0;
              } else { /*//jika pembagi tidak 0*/
                  $gtotsencoll = ($gtotrealisasicoll/$gtottargetcoll)*100;
              }

              if ($gtottargetsls == 0) {
                  $gtotsensls = 0;
              } else { /*//jika pembagi tidak 0*/
                  $gtotsensls = ($gtotnetsales/$gtottargetsls)*100;
              }

              if ($gtotoaprev == 0) {
                  $gtotgrwoa = 0;
              } else { /*//jika pembagi tidak 0*/
                  $gtotgrwoa = (($gtotoa-$gtotoaprev)/$gtotoaprev)*100;
              }

              if ($gtotqtyprev == 0) {
                  $gtotgrwqty = 0;
              } else { /*//jika pembagi tidak 0*/
                  $gtotgrwqty = (($gtotqty-$gtotqtyprev)/$gtotqtyprev)*100;
              }

              if ($gtotvnotaprev == 0) {
                  $gtotgrwrp = 0;
              } else { /*//jika pembagi tidak 0*/
                  $gtotgrwrp = (($gtotnetsales-$gtotvnotaprev)/$gtotvnotaprev)*100;
              }
              echo "<tr>
              <th colspan='2'><b>Total ".strtoupper($area ."-". $areaname)."</b></th>
              <th align=right><b>".number_format($tottargetcoll)."</b></th>
              <th align=right><b>".number_format($totrealisasicoll)."</b></th>
              <th align=right><b>".number_format($totsencoll,2)."%</b></th>
              <th align=right><b>".number_format($tottargetsls)."</b></th>
              <th align=right><b>".number_format($totnetsales)."</b></th>
              <th align=right><b>".number_format($totsensls,2)."%</b></th>
              <th align=right><b>".number_format($totob)."</b></th>
              <th align=right><b>".number_format($totoaprev)."</b></th>
              <th align=right><b>".number_format($totoa)."</b></th>
              <th align=right><b>".number_format($totgrwoa,2)."%</b></th>
              <th align=right><b>".number_format($totqtyprev)."</b></th>
              <th align=right><b>".number_format($totqty)."</b></th>
              <th align=right><b>".number_format($totgrwqty,2)."%</b></th>
              <th align=right><b>".number_format($totvnotaprev)."</b></th>
              <th align=right><b>".number_format($totnetsales)."</b></th>
              <th align=right><b>".number_format($totgrwrp,2)."%</b></th>
              <th align=right><b>".number_format($totctrnota,2)."%</b></th>
              </tr>";
              echo "<tr>
              <th colspan='2'><b>Total</b></th>
              <th align=right><b>".number_format($gtottargetcoll)."</b></th>
              <th align=right><b>".number_format($gtotrealisasicoll)."</b></th>
              <th align=right><b>".number_format($gtotsencoll,2)."%</b></th>
              <th align=right><b>".number_format($gtottargetsls)."</b></th>
              <th align=right><b>".number_format($gtotnetsales)."</b></th>
              <th align=right><b>".number_format($gtotsensls,2)."%</b></th>
              <th align=right><b>".number_format($gtotob)."</b></th>
              <th align=right><b>".number_format($gtotoaprev)."</b></th>
              <th align=right><b>".number_format($gtotoa)."</b></th>
              <th align=right><b>".number_format($gtotgrwoa,2)."%</b></th>
              <th align=right><b>".number_format($gtotqtyprev)."</b></th>
              <th align=right><b>".number_format($gtotqty)."</b></th>
              <th align=right><b>".number_format($gtotgrwqty,2)."%</b></th>
              <th align=right><b>".number_format($gtotvnotaprev)."</b></th>
              <th align=right><b>".number_format($gtotnetsales)."</b></th>
              <th align=right><b>".number_format($gtotgrwrp,2)."%</b></th>
              <th align=right><b>".number_format($gtotctrnota,2)."%</b></th>
              </tr>";
          }/*END IF*/
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
