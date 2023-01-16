<link href="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/view/<?=$iperiode;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <?php 
            if($detail){
                foreach($detail as $row){
                    $periode=$row->periode;
                }
            }else{
                $periode=$iperiode;
            }
            $perper=$periode;
            if($detail){
              $perper=$periode;
              $customer=$row->customer;
              $a=substr($periode,0,4);
              $b=substr($periode,4,2);
              $periode=mbulan($b)." - ".$a;?>
              <div class="col-sm-12">
                <div class="white-box">
                    <input name="iperiode" id="iperiode" value="<?= $perper;?>" type="hidden">
                    <input name="icustomer" id="icustomer" value="<?= $customer;?>" type="hidden">
                    <input name="iproduct" id="iproduct" value="<?= $iproduct;?>" type="hidden">
                    <input name="nsaldo" id="nsaldo" value="<?= $saldo;?>" type="hidden">
                    <h3 class="box-title m-b-0">LAPORAN MUTASI STOCK - <?= $customer." (".$row->e_customer_name.")";?></h3>
                    <h3 class="box-title m-b-0">Periode : <?= $periode;?></h3>
                    <h3 class="box-title m-b-0">Kode : <?= $iproduct.'-'.$row->e_product_name;?></h3>
                    <div class="table-responsive">
                        <table data-show-columns="true" id="clmtable" data-height="500" data-mobile-responsive="true" class="table color-table success-table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Refferensi</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Nama Toko</th>
                                    <th class="text-center">Awal</th>
                                    <th class="text-center">In</th>
                                    <th class="text-center">Out</th>
                                    <th class="text-center">Akhir</th>
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
                                        $tmp=explode('-',$row->dreff);
                                        $tgl=$tmp[2];
                                        $bln=$tmp[1];
                                        $thn=$tmp[0];
                                        if(strlen($tgl)==2){
                                            $row->dreff=$tgl.'-'.$bln.'-'.$thn;
                                        }
                                        $sawal=$sahir;
                                        if($no==0)$tsawal=$sawal;
                                        $no++;
                                        $sahir=$sawal+$row->in-$row->out;?>
                                        <tr>
                                            <td class="text-center"><?= $row->ireff;?></td>
                                            <td class="text-center"><?= $row->dreff;?></td>
                                            <td class="text-left"><?= $row->e_customer_name;?></td>
                                            <td class="text-right"><?= $sawal;?></td>
                                            <td class="text-right"><?= $row->in;?></td>
                                            <td class="text-right"><?= $row->out;?></td>
                                            <td class="text-right"><?= $sahir;?></td>
                                        </tr>
                                    </tbody>
                                    <?php 
                                    $in =$in+$row->in;
                                    $out=$out+$row->out;
                                }?>
                                <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="3">Total</th>
                                        <th class="text-right"><?= number_format($tsawal);?></th>
                                        <th class="text-right"><?= number_format($in);?></th>
                                        <th class="text-right"><?= number_format($out);?></th>
                                        <th class="text-right"><?= number_format($sahir);?></th>
                                    </tr>
                                </tfoot>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" id="cetak" name="cetak" class="btn btn-info btn-rounded btn-sm" onclick="yyy();"><i class="fa fa-print"></i>&nbsp;&nbsp;PRINT</button>
                    </div>
                </div>
            </div>
            <?php 
        }else{ ?>
            <center><h1>Data Mutasi belum ada</h1></center>
        <?php } ?>
    </div>
</div>
</div>
<script src="<?= base_url();?>assets/plugins/bower_components/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script>
    function yyy(){
        var lebar    = 1024;
        var tinggi   = 768;
        var periode  = $("#iperiode").val();
        var customer = $("#icustomer").val();
        var product  = $("#iproduct").val();
        var sawal    = $("#nsaldo").val();
        eval('window.open("<?= site_url($folder); ?>"+"/cform/cetakdetail/"+periode+"/"+customer+"/"+product+"/"+sawal,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>