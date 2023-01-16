<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-table"></i> &nbsp; <?= "Target Penjualan Per Area" ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
        </div>
        <div class="white-box">
            <h3 class="box-title m-b-0">Periode : <?= mbulan($bulan)." ".$tahun;?></h3>
            <table class="tablesaw table-bordered table-hover table" data-tablesaw-mode="swipe" data-tablesaw-sortable data-tablesaw-sortable-switch data-tablesaw-minimap data-tablesaw-mode-switch cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th style="text-align: center; font-size: 11px;">Area</th>
                        <th style="text-align: center; font-size: 11px;">Target</th>
                        <th style="text-align: center; font-size: 11px;">Penjualan</th>
                        <th style="text-align: center; font-size: 11px;">% Penjualan</th>
                        <th style="text-align: center; font-size: 11px;">Reguler</th>
                        <th style="text-align: center; font-size: 11px;">% Reguler</th>
                        <th style="text-align: center; font-size: 11px;">Baby</th>
                        <th style="text-align: center; font-size: 11px;">% Baby</th>
                        <th style="text-align: center; font-size: 11px;">Retur</th>
                        <th style="text-align: center; font-size: 11px;">% Retur</th>
                        <th style="text-align: center; font-size: 11px;">Jual Non Ins</th>
                        <th style="text-align: center; font-size: 11px;">Retur Non Ins</th>
                        <th style="text-align: center; font-size: 11px;">SPB Bln ini</th>
                        <th style="text-align: center; font-size: 11px;">% SPB</th>
                        <th style="text-align: center; font-size: 11px;">Action</th>
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
                            } ?>
                            <tr>
                                <!-- <td style="text-align: center;"><?= $i;?></td> -->
                                <td style="font-size: 11px;"><a href="javascript:void(0)"><?= $row->i_area."-".$row->e_area_name; ?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_target); ?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_nota_grossinsentif);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= $persen; ?>%</a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_real_regularinsentif);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= $persenreg; ?>%</a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_real_babyinsentif);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= $persenbaby; ?>%</a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_retur_insentif);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= $persenretur; ?>%</a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_nota_grossnoninsentif);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_retur_noninsentif);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= number_format($row->v_spb_gross);?></a></td>
                                <td style="text-align: right; font-size: 11px;"><a href="javascript:void(0)"><?= $persenspb; ?>%</a></td>
                                <td style="text-align: center; font-size: 11px;">
                                    <a href="#" title="Per Sales" onclick="persales('<?= $iperiode;?>','<?= $row->i_area; ?>');">
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                    &nbsp;
                                    <a href="#" title="Per Nota" onclick="pernota('<?= $iperiode;?>','<?= $row->i_area; ?>');">
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                    &nbsp;
                                    <a href="#" title="Per Kota" onclick="perkota('<?= $iperiode;?>','<?= $row->i_area; ?>');">
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                    &nbsp;
                                    <a href="#" title="Retur" onclick="retur('<?= $iperiode;?>','<?= $row->i_area; ?>');">
                                        <i class='fa fa-pencil'></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $i ++; 
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function persales(iperiode,area){
        lebar =1366;
        tinggi=768;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/persales/"+iperiode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
    function pernota(iperiode,area){
        lebar =1366;
        tinggi=768;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/pernota/"+iperiode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
    function perkota(iperiode,area){
        lebar =1366;
        tinggi=768;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/perkota/"+iperiode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
    function retur(iperiode,area){
        lebar =1366;
        tinggi=768;
        eval('window.open("<?php echo site_url(); ?>"+"/<?= $folder;?>/cform/retur/"+iperiode+"/"+area,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>