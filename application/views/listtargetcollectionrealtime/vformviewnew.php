<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
    <p class="text-muted" style="text-align: center;">Periode : <?= $iperiode;?></p>
    <div class="panel-body table-responsive">
        <table class="table color-bordered-table info-bordered-table" id="sitabel" cellpadding="0" cellspacing="0">
            <thead>
                <input name="iperiode" id="iperiode" value="<?php echo $iperiode; ?>" type="hidden" readonly>
                <input name="akhir" id="akhir" value="<?php echo $akhir; ?>" type="hidden" readonly>
                <?php if($isi){ ?>
                <tr>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>NO</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>AREA</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>TARGET (Rp)</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>BLM BAYAR (Rp)</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=6>REALISASI</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>/NOTA</th>
                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=3>/SALES</th>
                </tr>

                <tr>
                    <th style="font-size: 12px;text-align: center;" colspan=2>Tidak Telat</th>
                    <th style="font-size: 12px;text-align: center;" colspan=2>Telat</th>
                    <th style="font-size: 12px;text-align: center;" colspan=2>Total</th>
                </tr>

                <tr>
                    <th style="font-size: 12px;text-align: center;">Jumlah (Rp)</th>
                    <th style="font-size: 12px;text-align: center;">Persen</th>
                    <th style="font-size: 12px;text-align: center;">Jumlah (Rp)</th>
                    <th style="font-size: 12px;text-align: center;">Persen</th>
                    <th style="font-size: 12px;text-align: center;">Jumlah (Rp)</th>
                    <th style="font-size: 12px;text-align: center;">Persen</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                    if($isi){
                        $i=1;
                        $ttarget=0;
                        $trealis=0;
                        $tblm=0;
                        $tsdh=0;
                        $ttlt=0;
                        $trealsdh=0;
                        $trealtlt=0;
                        $persenall=0;
                        $persenalltdktelat=0;
                        $persenalltelat=0;
                    
                        foreach($isi as $row){
			  	            settype($row->lama,"integer");
                            if($row->realisasi==null || $row->realisasi=='')$row->realisasi=0;
                            
                            if($row->total!=0){
                                $persen         = number_format(($row->realisasi/$row->total)*100,2);
                                $persentdktelat = number_format(($row->realisasitdktelat/$row->total)*100,2);
                                $persentelat    = number_format(($row->realisasitelat/$row->total)*100,2);
        		            }else{
                                $persen         = '0';
                                $persentdktelat = '0';
                                $persentelat    = '0';
                            }

                            $tblm       = $tblm+$row->blmbayar;
                            $tsdh       = $tsdh+$row->tdktelat;
                            $ttlt       = $ttlt+$row->telat;
                            $trealsdh   = $trealsdh+$row->realisasitdktelat;
                            $trealtlt   = $trealtlt+$row->realisasitelat;
                            $ttarget    = $ttarget+$row->total;
                            $trealis    = $trealis+$row->realisasi;
                            $iarea      = $row->i_area;
                            
                            echo "<tr>
                                    <td align=right><a href=\"#\" onclick='chartx(\"$iperiode\");'>$i</a></td>
                                    <td>$row->i_area-$row->e_area_name</td>
                                    <td align=right>".number_format($row->total)."</td>
                                    <td align=right>".number_format($row->blmbayar)."</td>";
                            echo "
                                    <td align=right>".number_format($row->realisasitdktelat)."</td>
                                    <td align=right>".number_format($persentdktelat,2)." %</td>
                                    <td align=right>".number_format($row->realisasitelat)."</td>
                                    <td align=right>".number_format($persentelat,2)." %</td>
                                    <td align=right>".number_format($row->realisasi)."</td>
                                    <td align=right>".number_format($persen,2)." %</td>";
                        $i++;

                            echo "<td class=\"action\">";
                            echo "<a class='fa fa-pencil' href=\"#\" onclick='view_detail(\"$iperiode\",\"$akhir\",\"$iarea\");'></a>";
                            echo "</td>";
                            echo "<td class=\"action2\">";
                            echo "<a class='fa fa-pencil' href=\"#\" onclick='view_sales(\"$iperiode\",\"$akhir\",\"$iarea\");'></a>";
                            echo "</td></tr>";	
                        }
                        
                        if($ttarget!=0){ 
                            $persenall          = number_format(($trealis/$ttarget)*100,2);
                            $persenalltdktelat  = number_format(($trealsdh/$ttarget)*100,2);
                            $persenalltelat     = number_format(($trealtlt/$ttarget)*100,2);
                        }else{
                            $persenall          = '0';
                            $persenalltdktelat  = '0';
                            $persenalltelat     = '0';
                        }
                        
                        echo "<tr><th colspan=2 style ='text-align: center;'>Total</th>";
                        echo "<th style ='text-align: right;'>".number_format($ttarget)."</th>";
                        echo "<th style ='text-align: right;'>".number_format($tblm)."</th>";
                        echo "<th style ='text-align: right;'>".number_format($trealsdh)."</th>";
                        echo "<th style ='text-align: right;'>".number_format($persenalltdktelat,2)." %</th>";
                        echo "<th style ='text-align: right;'>".number_format($trealtlt)."</th>";
                        echo "<th style ='text-align: right;'>".number_format($persenalltelat,2)." %</th>";
                        echo "<th style ='text-align: right;'>".number_format($trealis)."</th>";
                        echo "<th style ='text-align: right;'>".number_format($persenall,2)." %</th>";
                        echo "<th colspan=3></th></tr>";
		            }
                ?>
            </tbody>

            <?php }?>
            <!-- end if isi -->
        </table>
    </div> <!-- end div awal -->

    <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
    $("#cmdreset").click(function() {
        var Contents = $('#sitabel').html();
        window.open('data:application/vnd.ms-excel, ' + '<table>' + encodeURIComponent($('#sitabel').html()) +
            '</table>');
    });

    function dipales() {
        this.close();
    }

    function view_detail(a, b, c) {
        lebar = 1366;
        tinggi = 768;
        eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/detail/"+a+"/"+b+"/"+c,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top=' +
            (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }

    function view_sales(a, b, c) {
        lebar = 1366;
        tinggi = 768;
        periode = document.getElementById("iperiode").value;
        akhir = document.getElementById("akhir").value;
        eval('window.open("<?php echo site_url(); ?>"+"/listtargetcollectionrealtime/cform/sales/"+a+"/"+b+"/"+c,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top=' +
            (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }
    </script>