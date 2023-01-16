<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<?php 
$tmp=explode("-",$dfrom);
$th=$tmp[2];
$bl=$tmp[1];
$hr=$tmp[0];
$dfroms=$hr." ".mbulan($bl)." ".$th;
$tmp=explode("-",$dto);
$th=$tmp[2];
$bl=$tmp[1];
$hr=$tmp[0];
$dtos=$hr." ".mbulan($bl)." ".$th;
?>
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title">Laporan Jumlah Order Salesman</h3>
        <p class="text-muted">Periode : <?= $dfroms." s/d ".$dtos;?></p>
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table">
                <thead>
                    <tr>
                        <th style="text-align: center;font-size: 14px;">No</th>
                        <th style="text-align: center;font-size: 14px;">Salesman</th>
                        <th style="text-align: center;font-size: 14px;">Area</th>
                        <th style="text-align: center;font-size: 14px;">Toko</th>
                        <th style="text-align: center;font-size: 14px;">Tipe Toko</th>
                        <th style="text-align: center;font-size: 14px;">Senin</th>
                        <th style="text-align: center;font-size: 14px;">Selasa</th>
                        <th style="text-align: center;font-size: 14px;">Rabu</th>
                        <th style="text-align: center;font-size: 14px;">Kamis</th>
                        <th style="text-align: center;font-size: 14px;">Jumat</th>
                        <th style="text-align: center;font-size: 14px;">Sabtu</th>
                        <th style="text-align: center;font-size: 14px;">Minggu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($data){
                        $i=0;
                        $va=0;
                        $vb=0;
                        $vc=0;
                        $vd=0;
                        $ve=0;
                        $vf=0;
                        $vg=0;
                        foreach($data as $row){
                            $tmp = explode("-",$row->d_spb);
                            $bl=$tmp[1];
                            $tg=$tmp[2];
                            $th=$tmp[0];
                            $tgl=mktime(0,0,0,$bl,$tg,$th);
                            $hasil=date("w", $tgl);
                            $tgl=$tg.'-'.$bl.'-'.$th;
                            if($icustomerx==$row->i_customer || $icustomerx==''){
                                switch($hasil){
                                    case 1:
                                    $hari='minggu';
                                    $va=$va+$row->v_spb;
                                    break;
                                    case 2:
                                    $hari='senin';
                                    $vb=$vb+$row->v_spb;
                                    break;
                                    case 3:
                                    $hari='selasa';
                                    $vc=$vc+$row->v_spb;
                                    break;
                                    case 4:
                                    $hari='rabu';
                                    $vd=$vd+$row->v_spb;
                                    break;
                                    case 5:
                                    $hari='kamis';
                                    $ve=$ve+$row->v_spb;
                                    break;
                                    case 6:
                                    $hari='jumat';
                                    $vf=$vf+$row->v_spb;
                                    break;
                                    case 7:
                                    $hari='sabtu';
                                    $vg=$vg+$row->v_spb;
                                    break;
                                }
                            }
                            if($icustomerx!=''){
                                if($icustomerx!=$row->i_customer){
                                    $i++;
                                    ?>
                                    <tr>
                                        <td style="font-size: 13px;"><?= $i; ?></td>
                                        <td style="font-size: 13px;"><?= $isal - $isalname; ?></td>
                                        <td style="font-size: 13px;"><?= $iare - $iarename; ?></td>
                                        <td style="font-size: 13px;"><?= $icust - $icustname; ?></td>
                                        <td style="font-size: 13px;"><?= $iclass; ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vax); ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vbx); ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vcx); ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vdx); ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vex); ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vfx); ?></td>
                                        <td style="text-align: right; font-size: 13px;"><?= number_format($vgx); ?></td>
                                    </tr>
                                    <?php 
                                    $va=0;
                                    $vb=0;
                                    $vc=0;
                                    $vd=0;
                                    $ve=0;
                                    $vf=0;
                                    $vg=0;
                                    switch($hasil){
                                        case 1:
                                        $hari='minggu';
                                        $va=$va+$row->v_spb;
                                        break;
                                        case 2:
                                        $hari='senin';
                                        $vb=$vb+$row->v_spb;
                                        break;
                                        case 3:
                                        $hari='selasa';
                                        $vc=$vc+$row->v_spb;
                                        break;
                                        case 4:
                                        $hari='rabu';
                                        $vd=$vd+$row->v_spb;
                                        break;
                                        case 5:
                                        $hari='kamis';
                                        $ve=$ve+$row->v_spb;
                                        break;
                                        case 6:
                                        $hari='jumat';
                                        $vf=$vf+$row->v_spb;
                                        break;
                                        case 7:
                                        $hari='sabtu';
                                        $vg=$vg+$row->v_spb;
                                        break;
                                    }
                                }
                            }
                            $isal=$row->i_salesman;
                            $isalname=$row->e_salesman_name;
                            $iare=$row->i_area;
                            $iarename=$row->e_area_name;
                            $icust=$row->i_customer;
                            $icustname=$row->e_customer_name;
                            $iclass=$row->e_customer_classname;
                            $vax=$va;
                            $vbx=$vb;
                            $vcx=$vc;
                            $vdx=$vd;
                            $vex=$ve;
                            $vfx=$vf;
                            $vgx=$vg;
                            $icustomerx=$row->i_customer;
                        }
                        $i++;
                        ?>
                        <tr>
                            <td style="font-size: 13px;"><?= $i; ?></td>
                            <td style="font-size: 13px;"><?= $isal - $isalname; ?></td>
                            <td style="font-size: 13px;"><?= $iare - $iarename; ?></td>
                            <td style="font-size: 13px;"><?= $icust - $icustname; ?></td>
                            <td style="font-size: 13px;"><?= $iclass; ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vax); ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vbx); ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vcx); ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vdx); ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vex); ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vfx); ?></td>
                            <td style="text-align: right; font-size: 13px;"><?= number_format($vgx); ?></td>
                        </tr>
                        <?php 
                    } ?>
                </tbody>
            </table>
            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="dipales();"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    function dipales() {
        this.close();
    }
</script>