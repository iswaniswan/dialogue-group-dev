<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
    <p class="text-muted" style="text-align: center;">Periode : <?= $iperiode;?></p>
    <div class="panel-body table-responsive">
        <table class="table color-bordered-table info-bordered-table display nowrap" id="sitabel" cellpadding="0"
            cellspacing="0" border="1">
            <thead>
                <?php if($divisi){ ?>
                <tr>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">No</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Area</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Product Group</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Nota</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Toko</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tanggal Nota</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Jatuh Tempo</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Umur</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;">Persen</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $perper=$iperiode;
		            if($divisi){
                  $no       = 0;
                  $ttotal   = 0;
                  $ttotsub  = 0;
                  $treal    = 0;
                  $trealsub = 0;
                  $tpers    = 0;
                  $tperssub = 0;
                  $group    = '';

                  foreach($divisi as $row){
                    $tanggalsekarang  = date("Y-m-d");
                    $tanggalsekarang  = new DateTime($tanggalsekarang);
                    $d_nota           = new DateTime($row->d_nota);
                    $umur             = $d_nota->diff($tanggalsekarang)->format("%a");

                    $no++;
                    if($row->d_jatuh_tempo){
                      $tmp  = explode("-",$row->d_jatuh_tempo);
                      $th   = $tmp[0];
                      $bl   = $tmp[1];
                      $dt   = $tmp[2];
                      $row->d_jatuh_tempo=$dt.'-'.$bl.'-'.$th;
                    }

                    if($row->d_nota){
                      $tmp=explode("-",$row->d_nota);
                      $th=$tmp[0];
                      $bl=$tmp[1];
                      $dt=$tmp[2];
                      $row->d_nota=$dt.'-'.$bl.'-'.$th;
                    }
                    $ttotal=$ttotal+$row->total;
                    $treal +=$row->realisasi;
                    if($row->total!=0){
                      $persen=number_format(($row->realisasi/$row->total)*100,2);
                    }else{
                      $persen='0';
                    }
                    if($group==''){
                      echo "<tr><td colspan=11 align=center style='font-size: 15px;'>$row->e_product_groupname</td></tr>";
                    }elseif($group!=$row->i_product_group){
                      if($ttotsub!=0){
                        $persen=number_format(($trealsub/$ttotsub)*100,2);
                      }else{
                        $persen='0';
                      }
                      echo "<tr>
                            <td colspan='8' style='font-size: 15px;'>Sub Total</td>
                            <td align=right style='font-size: 15px;'>Rp. ".number_format($ttotsub)."</td>
                            <td align=right style='font-size: 15px;'>Rp. ".number_format($trealsub)."</td>
                            <td align=right style='font-size: 15px;'>".number_format($persen,2)." %</td></tr>";	
                      echo "<tr><td colspan=11 align=center style='font-size: 15px;'>$row->e_product_groupname</td></tr>";
                      $ttotsub=0;
                      $trealsub=0;
                      $tperssub=0;
                    }
                    $ttotsub=$ttotsub+$row->total;
                    $trealsub =$trealsub+$row->realisasi;
                    if($row->total!=0){
                      $persen=number_format(($row->realisasi/$row->total)*100,2);
                    }else{
                      $persen='0';
                    }
                    $group=$row->i_product_group;
                    echo "<tr>
                      <td>$no</td>
                      <td>$row->i_area - $row->e_area_name</td>
                      <td>$row->e_product_groupname</td>
                      <td>$row->i_nota</td>
                      <td>$row->i_customer - $row->e_customer_name</td>
                      <td>$row->d_nota</td>
                      <td>$row->d_jatuh_tempo</td>
                      <td>$umur</td>
                      <td align=right>Rp. ".number_format($row->total)."</td>
                      <td align=right>Rp. ".number_format($row->realisasi)."</td>
                      <td align=right>".number_format($persen,2)." %</td></tr>";	
                  }
                  if($ttotal!=0){
                    $persen=number_format(($treal/$ttotal)*100,2);
                  }else{
                    $persen='0';
                  }
                  if($ttotsub!=0){
                    $persen=number_format(($trealsub/$ttotsub)*100,2);
                  }else{
                    $persen='0';
                  }
                  echo "<tr>
                        <td colspan='8' style='font-size: 15px;'>Sub Total</td>
                        <td align=right style='font-size: 15px;'>Rp. ".number_format($ttotsub)."</td>
                        <td align=right style='font-size: 15px;'>Rp. ".number_format($trealsub)."</td>
                        <td align=right style='font-size: 15px;'>".number_format($persen,2)." %</td></tr>";	
                  echo "<tr>
                        <td colspan='8' style='font-size: 15px;'>Total</td>
                        <td align=right style='font-size: 15px;'>Rp. ".number_format($ttotal)."</td>
                        <td align=right style='font-size: 15px;'>Rp. ".number_format($treal)."</td>
                        <td align=right style='font-size: 15px;'>".number_format($persen,2)." %</td></tr>";	
                }//END IF Divisi 2
	        ?>
            </tbody>
            <?php }else{
      echo "<center><h2>Target Collection belum ada</h2></center>";
      echo "<center><input type=\"button\" id=\"batal\" name=\"batal\" value=\"Tutup\" onclick=\"bbatal('$perper')\"></center>";
    }?>
            <!-- end if isi -->
        </table>
    </div> <!-- end div awal -->

    <script language="javascript" type="text/javascript">
    function bbatal(a) {
        show("listtargetcollectionrealtime/cform/view/" + a + "/", "#main");
    }

    function yyy() {
        lebar = 1024;
        tinggi = 768;
        periode = document.getElementById("iperiode").value;
        area = document.getElementById("iarea").value;
        eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/cetakdetail/"+periode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top=' +
            (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }
    </script>