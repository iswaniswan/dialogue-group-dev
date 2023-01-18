<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<style type="text/css" media="print">
    .noDisplay{
        display:none;
    }
    .pagebreak {
        page-break-before: always;
    }
    @media print {
        .page-break { display: block; page-break-before: always; }
    }
    .style {
        padding: 1px 8px;
    }
    {
        size: portrait;
    }
    @page { size: Letter;
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
</style>
<?php 
include ("php/fungsi.php");
?>
<?php 
$hal=1;
foreach($data as $row){?>
    <!-- color CSS -->
    <div class="white-box printableArea">
        <!-- <table class="isinya" border='0' align="center" width="70%"> -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-left"> 
                                <address style="padding:0px">
                                    <h3 style="font-size: 14px; line-height: 0px;"><b>SURAT ORDER PEMBELIAN</b><br></h3>
                                    <table cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td width="160px" class="xx text-muted m-l-3" style="font-size: 12px"><b>Nomor OP</b></td> 
                                            <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b><?= $row->i_op;?></b></td> 
                                        </tr>

                                        <!-- <tr>
                                            <td width="160px" class="text-muted m-l-3" style="font-size: 12px"><b>Batas Terakhir</b></td> 
                                            <td width="10px" class="text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="text-muted m-l-3" style="font-size: 12px"><b><?= date('d', strtotime($row->d_deliv)).' '.$this->fungsi->mbulan(date('m', strtotime($row->d_deliv))).' '.date('Y', strtotime($row->d_deliv));?></b></td> 
                                        </tr> -->

                                       <!--  <tr>
                                            <td width="160px" class="text-muted m-l-3" style="font-size: 12px"><b>Status OP</b></td> 
                                            <td width="10px" class="text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="text-muted m-l-3" style="font-size: 12px"><b><?=$row->e_status_op;?></b></td> 
                                        </tr> -->

                                        <tr>
                                            <td width="160px" class="text-muted m-l-3" style="font-size: 12px"><b>Cara Pembayaran</b></td> 
                                            <td width="10px" class="text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="text-muted m-l-3" style="font-size: 12px"><b><?= ucwords(strtolower($row->jenis_pembelian));?><!-- <?php
                                            if($row->n_top != 0){
                                                echo "Kredit ".$row->n_top;
                                            }else if($row->n_top == 0){
                                                echo "Cash";
                                            }?> --></b></td> 
                                        </tr>

                                        <tr>
                                            <td width="160px" class="xx text-muted m-l-3" style="font-size: 12px"><b>Batas Pemenuhan</b></td> 
                                            <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b><?= $row->d_deliv;?></b></td> 
                                        </tr>
                                    </table>
                                </address>
                            </div>
                            <div class="pull-right text-right" style="font-size: 13px"> <address>Cimahi,&nbsp;<?= date('d', strtotime($row->d_op)).' '.$this->fungsi->mbulan(date('m', strtotime($row->d_op))).' '.date('Y', strtotime($row->d_op));?></address> </div>
                        </div>
                    </div>
                    <hr style="margin-top: -1rem;
                    margin-bottom: 0rem;">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-left"> <address>
                                <h4 class="font-bold" style="font-size: 12px; line-height: 10px;"><?= check_constant('NmPerusahaan');?></h4>
                                <h5 style="font-size: 10px;"><?= check_constant('AlmtPerusahaan');?>  </h5>
                                <h5 style="font-size: 10px;">Telp/Fax : <?= check_constant('TlpPerusahaan'). " / ". check_constant('FaxPerusahaan');?>  </h5>
                                <h5 style="font-size: 10px;"><?= check_constant('KotaPerusahaan');?>  </h5>
                            </address> </div>
                            <div class="pull-right text-right"> <address>
                                <h4 style="font-size: 12px;">Kepada Yth,</h4>
                                <h4 class="font-bold" style="font-size: 14px;"><?= $row->e_supplier_name; ?></h4>
                                <h5 style="font-size: 10px;"><?= $row->e_supplier_address;?></h5>
                            </address> </div>
                        </div>
                        <div class="col-sm-12">
                            <p class="text-muted" style="font-size: 12px; margin-top: -0.75rem;
                            margin-bottom: 0rem;"> Dengan hormat, <br>Bersama surat ini, kami mohon untuk mengirimkan barang-barang sebagai berikut : </p>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive m-t-0">
                                <table class="table table-bordered" cellpadding="0" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 13px; padding: 2px 10px; width: 3%;" class="text-center">No</th>
                                            <th style="font-size: 13px; padding: 2px 10px;">Nama Barang</th>
                                            <th style="font-size: 13px; padding: 2px 10px;">Satuan</th>
                                            <th style="font-size: 13px; padding: 2px 10px;" class="text-right">Jml</th>
                                            <th style="font-size: 13px; padding: 2px 10px;" class="text-right">Harga</th>
                                            <th style="font-size: 13px; padding: 2px 10px;" class="text-right">Total</th>
                                            <th style="font-size: 13px; padding: 2px 10px;">Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $counter = 0;
                                        if ($data2) {
                                            $hasiltotal = 0;
                                            $total = 0;
                                            $qty   = 0;
                                            foreach ($data2 as $rowi) {
                                                $counter++;
                                                $total = $rowi->n_quantity * $rowi->v_price;
                                                $total = (int)$total;
                                                $qty   = $qty + $rowi->n_quantity;
                                                $hasiltotal = $hasiltotal + $total;?>
                                                <tr>
                                                    <td style="font-size: 11px; padding: 1px 8px;" class="text-center">
                                                        <?= $counter;?>
                                                    </td>
                                                    <td style="font-size: 11px; padding: 1px 8px;">
                                                        <?php 
                                                        if(strlen($rowi->e_material_name )>50){
                                                            $nam    = substr($rowi->e_material_name,0,50);
                                                        }else{
                                                            $nam    = $rowi->e_material_name.str_repeat(" ",50-strlen($rowi->e_material_name ));
                                                        }
                                                        echo $rowi->i_material." - ".$nam;
                                                        ?> 
                                                    </td>
                                                    <td style="font-size: 11px; padding: 1px 8px;" class="text-left">
                                                        <?= $rowi->e_satuan_name;?>
                                                    </td>
                                                    <td style="font-size: 11px; padding: 1px 8px;" class="text-right">
                                                        <?= $rowi->n_quantity;?>
                                                    </td>
                                                    <td style="font-size: 11px; padding: 1px 8px;" class="text-right"> Rp.
                                                        <?= number_format($rowi->v_price,2,',','.');?>
                                                    </td>
                                                    <td style="font-size: 11px; padding: 1px 8px" class="text-right"> Rp.
                                                        <?= number_format($total,2,',','.');?>
                                                    </td>
                                                    <td style="font-size: 11px; padding: 1px 8px;">
                                                        <?= $rowi->e_remark;?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="3" class="text-right" style="font-size: 12px; padding: 0px 8px;border:0;">Total : </td>
                                                <td colspan="1" class="text-right" style="font-size: 12px; padding: 0px 8px;border:0;">
                                                    <?= number_format($qty,2,',','.');?> 
                                                </td>
                                                <td colspan="1" style="font-size: 12px; padding: 0px 8px;border:0;"></td>
                                                <td colspan="1" class="text-right" style="font-size: 12px; padding: 0px 8px;border:0;">Rp.
                                                    <?= number_format($hasiltotal,2,',','.');?> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" style="font-size: 12px; padding: 0px 8px;border:0;" class="huruf nmper text-right"><i>(<?php 
                                                    $bilangan = new Terbilang;
                                                    $kata=ucwords($bilangan->eja($hasiltotal));  
                                                    $tmp=explode("-",$rowi->d_op);
                                                    $th=$tmp[0];
                                                    $bl=$tmp[1];
                                                    $hr=$tmp[2];
                                                    $d_op=$hr." ".mbulan($bl)." ".$th;
                                                    echo $kata." Rupiah";?>)
                                                </i> </td>
                                            </tr>
                                        <?php }?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12" >
                            <p class="text-muted" style="font-size: 12px; margin-top: -0.5rem;
                            margin-bottom: -3.5rem;">Demikian surat ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih</p> <br> 
                        </div>
                        <?if($cekcetak){?>
                            <div class="col-sm-12" >
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Dibuat,</p>
                                            <h3 style="font-size: 12px;">(<?= $cekcetak->pic;?> )</h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Mengetahui,</p>
                                            <h3 style="font-size: 12px;">(<?= $cekcetak->pembelian;?>)</h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Menyetujui,</p>
                                            <h3 style="font-size: 12px;">(<?= $cekcetak->keuangan;?>)</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}else{?>
                            <div class="col-sm-12" >
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Dibuat,</p>
                                            <h3 style="font-size: 12px;">(...................................)</h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Tanda Terima,</p>
                                            <h3 style="font-size: 12px;">(...................................)</h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Mengetahui,</p>
                                            <h3 style="font-size: 12px;">(...................................)</h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="pull-center m-t-30 text-center">
                                            <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Menyetujui,</p>
                                            <h3 style="font-size: 12px;">(...................................)</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?}?>                        
                    </div>
                    <hr style="margin-top: 0rem;
                    margin-bottom: 0rem; font-size: 10px;">
                    <font face="Courier New" size="2"><?php date_default_timezone_set('Asia/Jakarta'); echo "Tanggal Cetak : ".$tgl=date("d")." ".$this->fungsi->mbulan(date("m"))." ".date("Y").",  Jam : ".date("H:i:s");
                    ?>
                </font>
                <div class="row">
                <?php } ?>
            </div>
            <div class="noDisplay">
                <div class="text-center"> <button id="print" class="btn btn-info btn-outline" onclick="window.print();" type="button"> <span><i class="fa fa-print"></i> Print</span> </button> </div>
            </div>
        </div>
    </div>
    <!-- </table> -->
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>