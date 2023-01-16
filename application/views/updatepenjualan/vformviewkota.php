<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<?php 
$periode=$iperiode;
$a=substr($periode,0,4);
$b=substr($periode,4,2);
$periode=mbulan($b)." - ".$a;
?>
<?php 
$eareaname='';
if($data){
    foreach($data as $row){
        $eareaname=$row->e_area_name;
    }
}
?>
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title">Target Penjualan Per Kota Area <?= $eareaname;?></h3>
        <p class="text-muted">Periode : <?= $periode;?></p>
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table">
                <thead>
                    <tr>
                        <th style="font-size: 13px; text-align: center;">Kota</th>
                        <th style="font-size: 13px; text-align: center;">Salesman</th>
                        <th style="font-size: 13px; text-align: center;">Target</th>
                        <th style="font-size: 13px; text-align: center;">Penjualan</th>
                        <th style="font-size: 13px; text-align: center;">% Penjualan</th>
                        <th style="font-size: 13px; text-align: center;">Reguler</th>
                        <th style="font-size: 13px; text-align: center;">% Reguler</th>
                        <th style="font-size: 13px; text-align: center;">Baby</th>
                        <th style="font-size: 13px; text-align: center;">% Baby</th>
                        <th style="font-size: 13px; text-align: center;">Retur</th>
                        <th style="font-size: 13px; text-align: center;">% Retur</th>
                        <th style="font-size: 13px; text-align: center;">Jual Non Ins</th>
                        <th style="font-size: 13px; text-align: center;">Retur Non Ins</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($data){
                        $i=1;
                        foreach($data as $row){
                            if($row->v_nota_grossinsentif==null || $row->v_nota_grossinsentif==''){
                                $row->v_nota_grossinsentif=0;
                            }
                            if($row->v_target!=0){                          
                                $persen=number_format(($row->v_nota_grossinsentif/$row->v_target)*100,2);
                            }else{
                                $persen='0.00';
                            }
                            if($row->v_real_regularinsentif==null || $row->v_real_regularinsentif==''){
                                $row->v_real_regularinsentif=0;
                            }
                            if($row->v_nota_grossinsentif!=0){
                                $persenreg=number_format(($row->v_real_regularinsentif/$row->v_nota_grossinsentif)*100,2);
                            }else{
                                $persenreg='0.00';
                            }
                            if($row->v_real_babyinsentif==null || $row->v_real_babyinsentif==''){
                                $row->v_real_babyinsentif=0;
                            }
                            if($row->v_nota_grossinsentif!=0){
                                $persenbaby=number_format(($row->v_real_babyinsentif/$row->v_nota_grossinsentif)*100,2);
                            }else{
                                $persenbaby='0.00';
                            }
                            if($row->v_retur_insentif==null || $row->v_retur_insentif==''){
                                $row->v_retur_insentif=0;
                            }
                            if($row->v_nota_grossinsentif!=0){
                                $persenretur=number_format(($row->v_retur_insentif/$row->v_nota_grossinsentif)*100,2);
                            }else{
                                $persenretur='0.00';
                            }
                            if($row->v_spb_gross==null || $row->v_spb_gross==''){
                                $row->v_spb_gross=0;
                            }
                            if($row->v_target!=0){
                                $persenspb=number_format(($row->v_spb_gross/$row->v_target)*100,2);
                            }else{
                                $persenspb='0.00';
                            }
                            ?>
                            <tr>
                                <td style="font-size: 12px;"><?= $row->e_city_name;?></td>
                                <td style="font-size: 12px;"><?= $row->i_salesman."-".$row->e_salesman_name;?></td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_target); ?></td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_nota_grossinsentif); ?></td>
                                <td style="font-size: 12px; text-align: right;"><?= $persen; ?> %</td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_real_regularinsentif); ?></td>
                                <td style="font-size: 12px; text-align: right;"><?= $persenreg; ?> %</td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_real_babyinsentif); ?></td>
                                <td style="font-size: 12px; text-align: right;"><?= $persenbaby; ?> %</td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_retur_insentif); ?></td>
                                <td style="font-size: 12px; text-align: right;"><?= $persenretur; ?> %</td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_nota_grossnoninsentif); ?></td>
                                <td style="font-size: 12px; text-align: right;"><?= number_format($row->v_retur_noninsentif); ?></td>
                            </tr>
                        <?php }
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