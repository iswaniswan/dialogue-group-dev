<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <?php 
                if($detail){
                    foreach($detail as $row){
                        $periode=$row->periode;
                    }
                }else{
                    $periode=$iperiode;
                }
                $perper=$periode;
                $area=$iarea;
                if($detail){
                    $perper=$periode;
                    $area=$row->area;
                    $a=substr($periode,0,4);
                    $b=substr($periode,4,2);
                    $periode=mbulan($b)." - ".$a;?>
                    <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
                </div>
                <div class="col-sm-12">
                    <div class="white-box">
                        <input type="hidden" name="iperiode" id="iperiode" class="form-control" value="<?=$perper;?>">
                        <input type="hidden" name="iarea" id="iarea" class="form-control" value="<?=$iarea;?>">
                        <input type="hidden" name="istorelocation" id="istorelocation" class="form-control" value="<?=$istorelocation;?>">
                        <input type="hidden" name="iproduct" id="iproduct" class="form-control" value="<?=$iproduct;?>">
                        <input type="hidden" name="nsaldo" id="nsaldo" class="form-control" value="<?=$saldo;?>">

                        <?php
                        if($row->area=='00') {
                            $gudang='AA'; 
                        }else{
                            $gudang=$row->area;
                        }
                        ?>
                        <h3 class="box-title m-b-0"><?= strtoupper($title).'-'.$gudang.' ('.$istorelocation.')';?></h3>
                        <p class="text-muted m-b-30">Periode : <?= $iperiode;?></p>
                        <h3 class="box-title m-b-0">Kode : <?= $row->product.'-'.$row->e_product_name;?></h3>
                        <div class="table-responsive">
                            <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Refferensi</th>
                                        <th class="text-center">Refferensi2</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Nama Toko</th>
                                        <th class="text-center">Awal</th>
                                        <th class="text-center">In</th>
                                        <th class="text-center">Out</th>
                                        <th class="text-center">Akhir</th>
                                        <th class="text-center">GiT</th>
                                        <th class="text-center">GiT Penj</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($detail) {
                                        $no=0;
                                        $tsawal=0;
                                        $in=0;
                                        $out=0;
                                        $sawal=0;
                                        $sahir=$saldo;
                                        $sgit=0;
                                        $sgitp=0;
                                        foreach($detail as $row){
                                            if($row->dreff!=''){
                                                $tmp=explode('-',$row->dreff);
                                                $tgl=$tmp[2];
                                                $bln=$tmp[1];
                                                $thn=$tmp[0];
                                                if(strlen($tgl)==2){
                                                    $row->dreff=$tgl.'-'.$bln.'-'.$thn;
                                                }
                                            }
                                            $sawal=$sahir;
                                            if($no==0)$tsawal=$sawal;
                                            $no++;
                                            $sahir=$sawal+$row->in-$row->out;
                                            $sgit =$sgit+$row->git;
                                            $sgitp =$sgitp+$row->gitpenjualan;
                                            $xx=0;
                                            foreach($detail as $raw){
                                                if($row->ireff2==$raw->ireff2 or $row->ireff==$raw->ireff2 or $row->ireff2==$raw->ireff){
                                                    $xx++;            
                                                }
                                            }?>
                                            <tr>
                                                <?php if($xx>1){?>
                                                    <td><b><?= $row->ireff;?></b></td>
                                                <?php }else{?>
                                                    <td class='text-danger'><b><?= $row->ireff;?></b></td>
                                                <?php } ?>
                                                <td class="text-left"><?= $row->ireff2;?></td>
                                                <td class="text-left"><?= $row->dreff;?></td>
                                                <td class="text-left"><?= $row->e_customer_name;?></td>
                                                <td class="text-right"><?= $sawal;?></td>
                                                <td class="text-right"><?= $in;?></td>
                                                <td class="text-right"><?= $out;?></td>
                                                <td class="text-right"><?= $sahir;?></td>
                                                <td class="text-right"><?= $row->git;?></td>
                                                <td class="text-right"><?= $row->gitpenjualan;?></td>
                                            </tr>
                                            <?php 
                                            $in=$in+$row->in;
                                            $out=$out+$row->out;
                                        }?>
                                        <tr>
                                            <td colspan="3" class="text-center">TOTAL</td>
                                            <td class="text-right"><?= number_format($tsawal);?></td>
                                            <td class="text-right"><?= number_format($in);?></td>
                                            <td class="text-right"><?= number_format($out);?></td>
                                            <td class="text-right"><?= number_format($sahir);?></td>
                                            <td class="text-right"><?= number_format($sgit);?></td>
                                            <td class="text-right"><?= number_format($sgitp);?></td>
                                            <?php 
                                        } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="text-align: center;">
                            <button type="button" id="cetak" name="cetak" class="btn btn-info btn-rounded btn-sm" onclick="yyy();"><i class="fa fa-print"></i>&nbsp;&nbsp;PRINT</button>
                        </div>
                    <?php }else{ ?>
                        <center><h2>Data Mutasi belum ada</h2></center>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script>
    function yyy(){
        lebar =1024;
        tinggi=768;
        var periode = $('#iperiode').val();
        var area = $('#iarea').val();
        var product = $('#iproduct').val();
        var sawal = $('#nsaldo').val();
        var istorelocation = $('#istorelocation').val();
        eval('window.open("<?= site_url($folder); ?>"+"/cform/cetakdetail/"+periode+"/"+area+"/"+product+"/"+sawal+"/"+istorelocation,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>