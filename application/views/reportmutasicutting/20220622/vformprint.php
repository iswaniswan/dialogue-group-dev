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
$hal=1;?>
    <!-- color CSS -->
    <div class="white-box printableArea">
        <!-- <table class="isinya" border='0' align="center" width="70%"> -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                              <h3 align="center" style="font-size: 16px; line-height: 0px;"><b>Laporan Mutasi</b><br></h3>
                            <div class="pull-left"> 
                                <address style="padding:0px">
                                    <table cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td width="160px" class="xx text-muted m-l-3" style="font-size: 12px"><b>Nama Gudang</b></td> 
                                            <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b><?= $bagian->i_bagian. ' - '.$bagian->e_bagian_name;?></b></td> 
                                        </tr>

                                        <tr>
                                            <td width="160px" class="text-muted m-l-3" style="font-size: 12px"><b>Tanggal Mutasi</b></td> 
                                            <td width="10px" class="text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="text-muted m-l-3" style="font-size: 12px"><b><?php echo $dfrom. ' s/d '. $dto; ?></b></td> 
                                        </tr>

                                        <tr>
                                            <td width="160px" class="xx text-muted m-l-3" style="font-size: 12px"><b>Kelompok Barang</b></td> 
                                            <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b><?= $kategori->e_nama_kelompok;?></b></td> 
                                        </tr>

                                        <tr>
                                            <td width="160px" class="xx text-muted m-l-3" style="font-size: 12px"><b>Jenis Barang</b></td> 
                                            <td width="10px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b>:</b></td> 
                                            <td width="300px" class="xx huruf2 text-muted m-l-3" style="font-size: 12px"><b><?= $jenis->e_type_name;?></b></td> 
                                        </tr>
                                    </table>
                                </address>
                            </div>
                            <!-- <div class="pull-right text-right" style="font-size: 13px"> <address>Cimahi,&nbsp;//date('d', strtotime($row->d_op)).' '.$this->fungsi->mbulan(date('m', strtotime($row->d_op))).' '.date('Y', strtotime($row->d_op));</address> </div> -->
                        </div>
                    </div>
                    <hr style="margin-top: -1rem;
                    margin-bottom: 0rem;">
                    <br>
                    <div class="row">
                        <div class="col-sm-12" >
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="table table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle; width: 3%;vertical-align: middle;" class="text-center">No</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;">Kode Barang</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle; width: 40%">Nama Barang</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;">Warna</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Saldo Awal</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Pembelian</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Penerimaan dari Internal</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Pengembalian Pinjaman</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Retur Penjualan</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Pengeluaran ke Gudang Lain</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Pengeluaran Pinjaman</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Penjualan</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Retur Produksi</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Retur Pembelian</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Adjustment</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Saldo Akhir</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">SO</th>
                                            <th style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center">Selisih</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;
                                        $gudang = '';
                                            foreach ($data2 as $row) {
                                            $i++;
                                        ?>
                                        <tr>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $i; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;"><?= $row->i_product_base; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;"><?= $row->e_product_basename; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;"><?= $row->e_color_name; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->saldoawal; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->m_beli; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->m_masuk; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->m_pinjam; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->m_retur; ?></td>
                                        
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->k_keluar; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->k_pinjam; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->k_penjualan; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->k_retur; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->k_retur_beli; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->adjust; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->saldo_akhir; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->so; ?></td>
                                        <td style="font-size: 11px; padding: 2px 7px;vertical-align: middle;" class="text-center"><?= $row->selisih; ?></td>
                                        </tr>
                                        <?php  } ?>
                                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12" >
                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-3">
                                    <div class="pull-center m-t-30 text-center">
                                        <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Dibuat,</p>
                                        <h3 style="font-size: 12px;">(...................................)</h3>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="pull-center m-t-30 text-center">
                                        <p style="font-size: 12px; margin-top: -1rem; margin-bottom: 1rem;">Mengetahui,</p>
                                        <h3 style="font-size: 12px;">(...................................)</h3>
                                    </div>
                                </div>
                                <div class="col-sm-3"></div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top: 0rem;
                    margin-bottom: 0rem; font-size: 10px;">
                    <font face="Courier New" size="2"><?php date_default_timezone_set('Asia/Jakarta'); echo "Tanggal Cetak : ".$tgl=date("d")." ".$this->fungsi->mbulan(date("m"))." ".date("Y").",  Jam : ".date("H:i:s");
                    ?>
                </font>
                <div class="row">
            </div>
            <div class="noDisplay">
                <div class="text-center"> <button id="print" class="btn btn-info btn-outline" onclick="window.print();" type="button"> <span><i class="fa fa-print"></i> Print</span> </button> </div>
            </div>
        </div>
    </div>
    <!-- </table> -->
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
