<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <?php 
    $periode=$iperiode;
    $a=substr($periode,0,4);
    $b=substr($periode,4,2);
    $periode=mbulan($b)." - ".$a;
    ?>
    <div class="white-box">
        <h3 class="box-title"><?= $title;?></h3>
        <p class="text-muted">Periode : <code><?= $periode;?></code></p>
        <div class="table-responsive">
            <table id="tabledata" class="table color-bordered-table success-bordered-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Salesman</th>
                        <th>Target</th>
                        <th>SPB</th>
                        <th>%</th>
                        <th>Nota</th>
                        <th>%</th>
                        <th>Retur</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($isi){
                        $i = 0;
                        foreach($isi as $row){
                            $i++;
                            if($row->v_nota_gross==null || $row->v_nota_gross=='')$row->v_nota_gross=0;
                            if($row->v_target!=0){
                                $persen=number_format(($row->v_nota_gross/$row->v_target)*100,2);
                            }else{
                                $persen='0.00';
                            }
                            if($row->v_retur_insentif==null || $row->v_retur_insentif=='')$row->v_retur_insentif=0;
                            if($row->v_nota_grossinsentif!=0){
                                $persenretur=number_format(($row->v_retur_insentif/$row->v_nota_grossinsentif)*100,2);
                            }else{
                                $persenretur='0.00';
                            }
                            if($row->v_spb_gross==null || $row->v_spb_gross=='')$row->v_spb_gross=0;
                            if($row->v_target!=0){
                                $persenspb=number_format(($row->v_spb_gross/$row->v_target)*100,2);
                            }else{
                                $persenspb='0.00';
                            }?>
                            <tr>
                                <td style="text-align: center;"><?= $i;?></td>
                                <td><?= $row->i_salesman.' - '.$row->e_salesman_name;?></td>
                                <td style="text-align: right;"><?= number_format($row->v_target);?></td>
                                <td style="text-align: right;"><?= number_format($row->v_spb_gross);?></td>
                                <td style="text-align: right;"><?= $persenspb;?> %</td>
                                <td style="text-align: right;"><?= number_format($row->v_nota_gross);?></td>
                                <td style="text-align: right;"><?= $persen;?> %</td>
                                <td style="text-align: right;"><?= number_format($row->v_retur_insentif);?></td>
                                <td style="text-align: right;"><?= $persenretur;?> %</td>
                            </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="dipales();"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>&nbsp;
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    function dipales() {
        this.close();
    }

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>