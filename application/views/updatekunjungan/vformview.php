<?php 
$tmp=explode("-",$dfrom);
$th=$tmp[0];
$bl=$tmp[1];
$hr=$tmp[2];
$dfroms=$hr." ".mbulan($bl)." ".$th;
$tmp=explode("-",$dto);
$th=$tmp[0];
$bl=$tmp[1];
$hr=$tmp[2];
$dtos=$hr." ".mbulan($bl)." ".$th;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-table"></i> &nbsp; <?= "Laporan Kunjungan Salesman ( lama order : ".$nlama." )"; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
        </div>
        <div class="white-box">
            <h3 class="box-title m-b-0">Periode : <?= $dfroms." s/d ".$dtos;?></h3>
            <table class="tablesaw table-bordered table-hover table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th style="text-align: center; font-size: 14px;">No</th>
                        <th style="text-align: center; font-size: 14px;">Area</th>
                        <th style="text-align: center; font-size: 14px;">Salesman</th>
                        <th style="text-align: center; font-size: 14px;">Jml Kunj</th>
                        <th style="text-align: center; font-size: 14px;">Jml Order</th>
                        <th style="text-align: center; font-size: 14px;">Hasil Kunj (%)</th>
                        <th style="text-align: center; font-size: 14px;">Rata2 Kunj per hari (%)</th>
                        <th style="text-align: center; font-size: 14px;">Efektif Kunj</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($data){
                        $i=0;
                        foreach($data as $row){
                            $i++;
                            /*if ($row->jml!=0) {
                            }else{
                                $hasil = 0;
                            }*/
                            $hasil    = ($row->jmlreal/$row->jml)*100;
                            $ratahari = $row->jml/$nlama;
                            $efektif  = $row->jmlreal/$nlama; 
                            ?>
                            <tr>
                                <!-- <td style="text-align: center;"><?= $i;?></td> -->
                                <td style="font-size: 13px; text-align: center;"><a href="javascript:void(0)"><?= $i; ?></a></td>
                                <td style="font-size: 13px;"><a href="javascript:void(0)"><?= $row->i_area." - ".$row->e_area_name; ?></a></td>
                                <td style="font-size: 13px;"><a href="javascript:void(0)"><?= $row->i_salesman." - ".$row->e_salesman_name;?></a></td>
                                <td style="text-align: right; font-size: 13px;"><a href="javascript:void(0)"><?= number_format($row->jml);?></a></td>
                                <td style="text-align: right; font-size: 13px;"><a href="javascript:void(0)"><?= number_format($row->jmlreal);?></a></td>
                                <td style="text-align: right; font-size: 13px;"><a href="javascript:void(0)"><?= number_format($hasil,2);?></a></td>
                                <td style="text-align: right; font-size: 13px;"><a href="javascript:void(0)"><?= number_format($ratahari);?></a></td>
                                <td style="text-align: right; font-size: 13px;"><a href="javascript:void(0)"><?= number_format($efektif,2);?></a></td>
                                <td style="text-align: center; font-size: 13px;">
                                    <a href="#" title="Detail Kunjungan" onclick="kunjungan('<?= $dfrom;?>','<?= $dto; ?>','<?= $nlama; ?>');">
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                    &nbsp;
                                    <a href="#" title="Detail Order" onclick="order('<?= $dfrom;?>','<?= $dto; ?>','<?= $nlama; ?>');">
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function kunjungan(from,to,lama){
        lebar =1366;
        tinggi=768;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/kunjungan/"+from+"/"+to+"/"+lama,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
    function order(from,to,lama){
        lebar =1366;
        tinggi=768;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/order/"+from+"/"+to+"/"+lama,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>