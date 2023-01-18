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
        <h3 class="box-title">Target Penjualan Per Nota Area <?= $eareaname;?></h3>
        <p class="text-muted">Periode : <?= $periode;?></p>
        <div class="table-responsive">
            <table class="table color-bordered-table info-bordered-table">
                <thead>
                    <tr>
                        <th style="width: 14px; text-align: center; width: 4%;">No</th>
                        <th style="width: 14px; text-align: center; width: 12%;">Nota</th>
                        <th style="width: 14px; text-align: center; width: 12%;">Tanggal</th>
                        <th style="width: 14px; text-align: center; width: 8%;">Jumlah</th>
                        <th style="width: 14px; text-align: center;">Salesman</th>
                        <th style="width: 14px; text-align: center;">Toko</th>
                        <th style="width: 14px; text-align: center;">Alamat</th>
                        <th style="width: 14px; text-align: center;">Kota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($data){
                        $i=0;
                        foreach($data as $row){
                            $i++;
                            if($row->d_nota){
                                $tmp=explode('-',$row->d_nota);
                                $tgl=$tmp[2];
                                $bln=$tmp[1];
                                $thn=$tmp[0];
                                $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
                            }
                            if($row->v_netto==null || $row->v_netto==''){
                                $row->v_netto=0;
                            }
                            ?>
                            <tr>
                                <td style="font-size: 13px; text-align: center;"><?= $i; ?></td>
                                <td style="font-size: 13px; text-align: center;"><?= $row->i_nota; ?></td>
                                <td style="font-size: 13px; text-align: center;"><?= $row->d_nota; ?></td>
                                <td style="font-size: 13px; text-align: right;"><?= number_format($row->v_netto); ?></td>
                                <td style="font-size: 13px;"><?= $row->i_salesman."-".$row->e_salesman_name; ?></td>
                                <td style="font-size: 13px;"><?= $row->i_customer."-".$row->e_customer_name; ?></td>
                                <td style="font-size: 13px;"><?= $row->e_customer_address; ?></td>
                                <td style="font-size: 13px;"><?= $row->e_city_name; ?></td>
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