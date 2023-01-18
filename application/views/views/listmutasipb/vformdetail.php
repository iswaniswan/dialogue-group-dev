<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
        <?php if($detail){ 
                foreach($detail as $row){
                    $periode=$row->periode;
                }
                $a=substr($iperiode,0,4);
                $b=substr($iperiode,4,2);
                $periode = mbulan($b)." - ".$a;
                ?>
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/view/<?=$b;?>/<?=$a;?>/<?=$icustomer;?>/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            
            <div class="col-sm-12">
                <div class="white-box">
                    <input type="hidden" name="iperiode" id="iperiode" class="form-control" value="<?=$iperiode;?>">
                    <input type="hidden" name="icustomer" id="icustomer" class="form-control" value="<?=$icustomer;?>">
                    <input type="hidden" name="iproduct" id="iproduct" class="form-control" value="<?=$iproduct;?>">
                    <input type="hidden" name="nsaldo" id="nsaldo" class="form-control" value="<?=$saldo;?>">
                    
                    <h3 class="box-title m-b-0"><?= strtoupper($title).'-'.$icustomer.' ('.$row->e_customer_name.')';?></h3>
                    <p class="text-muted m-b-30">Periode : <?= $periode;?></p>
                    <h3 class="box-title m-b-0">Kode : <?= $iproduct.'-'.str_replace('%20',' ',$row->e_product_name);?></h3>
                    <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                        <thead>
                            <tr>
                                <th>Refferensi</th>
			                    <th>Tanggal</th>
	     	                    <th>Nama Toko</th>
			                    <th>Awal</th>
			                    <th>In</th>
			                    <th>Out</th>
			                    <th>Akhir</th>
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
                                foreach ($detail as $row) {
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
                                    if($no==0){
                                        $tsawal=$sawal;
                                    }
                                    $no++;
                                    $sahir=$sawal+$row->in-$row->out;
                            ?>
                                    <tr>
                                        <td><?= $row->ireff;?></td>
                                        <td><?= $row->dreff;?></td>
                                        <td><?= $row->e_customer_name;?></td>
                                        <td class="text-right"><?= $sawal;?></td>
                                        <td class="text-right"><?= $row->in;?></td>
                                        <td class="text-right"><?= $row->out;?></td>
                                        <td class="text-right"><?= $sahir;?></td>
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
                                </tr>
                            <? } 
                        }else{
                            echo "<h2>Belum Ada Mutasi!!!</h2>";
                        }?>
                        </tbody>
                    </table>
                    <div style="text-align: center;">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $b."/".$a."/".$icustomer;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        <button type="button" id="cetak" name="cetak" class="btn btn-secondary btn-rounded btn-sm" onclick="yyy();"><i class="fa fa-download"></i>&nbsp;&nbsp;PRINT</button>
                    </div>
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
        //periode=document.getElementById("iperiode").value;
        //area   =document.getElementById("iarea").value;
        //product=document.getElementById("iproduct").value;
        //sawal  =document.getElementById("saldo").value;
        //istorelocation   =document.getElementById("istorelocation").value;
        eval('window.open("<?php echo site_url(); ?>"+"/listmutasidaerah/cform/cetakdetail/"+periode+"/"+area+"/"+product+"/"+sawal+"/"+istorelocation,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>
<!-- <script type="text/javascript">
    $(function () {
        $('#clmtable').bootstrapTable('destroy').bootstrapTable();
    });
</script> -->