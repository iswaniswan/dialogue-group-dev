<style>
    .nowrap {
        white-space:nowrap !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Pembuat Dokumen</label>
                        <label class="col-md-2">Bulan</label>
                        <label class="col-md-2">Tahun</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="id" id="id" class="form-control"
                                value="<?php if ($head) echo $head->id; ?>" readonly>
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control"
                                value="<?= $bagian->i_bagian; ?>" readonly>
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm"
                                value="<?= $bagian->e_bagian_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="hidden" name="ibulan" id="ibulan" class="form-control" value="<?= $bulan; ?>"
                                readonly>
                            <input type="text" name="bulan" id="bulan" class="form-control input-sm"
                                value="<?= mbulan($bulan); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="tahun" id="tahun" class="form-control input-sm"
                                value="<?= $tahun; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea id="eremarkh" readonly name="eremarkh"
                                class="form-control"><?= $head->e_remark_supplier;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table tableFixHead inverse-table nowrap table-bordered class display" cellspacing="0"
                width="100%">
                <thead>
                    <tr class="d-flex">
                        <th class="text-center col-1" colspan="1"></th>
                        <th class="text-center col-1"><span id="h_fc_berjalan" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_do_berjalan" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_fcd" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_fc_selanjutnya" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_sjd" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_swip" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_sjahit" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_speng" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_spack" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_jml_tmp" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_qty_up" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"><span id="h_jml" class="autoNum"><b></b></span></th>
                        <th class="text-center col-1"></th>
                    </tr>
                    <tr class="d-flex">
                        <th class="text-center col-1">No</th>
                        <th class="text-center col-1">FC BLN<br>Berjalan</th>
                        <th class="text-center col-1">DO BLN<br>Berjalan</th>
                        <th class="text-center col-1">FC <br>Dist</th>
                        <th class="text-center col-1">FC BLN<br>Selanjutnya</th>
                        <th class="text-center col-1">Stok<br>Jadi</th>
                        <th class="text-center col-1">Stok<br>WIP</th>
                        <th class="text-center col-1">Stok<br>Jahit</th>
                        <th class="text-center col-1">Stok<br>Pengadaan</th>
                        <th class="text-center col-1">Stok<br>Packing</th>
                        <th class="text-center col-1">Jml FC <br>Produksi<br> Perhitungkan </th>
                        <th class="text-center col-1">QTY Up</th>
                        <th class="text-center col-1">Jml FC <br>Produksi<br> Budgetkan </th>
                        <th class="text-center col-1">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height:20px;">
                        <td class="text-center col-13"></td>
                    </tr> 
                    <?php $i = 0;
                    
                    $tot_nquantity_fc = 0;
                    $tot_nquantity_stock = 0;
                    $tot_nquantity_stock_wip = 0;
                    $tot_nquantity_stock_jahit = 0;
                    $tot_nquantity_stock_pengadaan = 0;
                    $tot_nquantity_stock_packing = 0;
                    $tot_nquantity_up = 0;
                    $tot_nquantity = 0;

                    $tot_nquantity_fc_berjalan = 0;
                    $tot_nquantity_do_berjalan = 0;
                    $tot_nquantity_fc_selanjutnya = 0;
                    $tot_nquantity_tmp = 0;

                    foreach ($datadetail as $key) {
                        $i++;
                    ?>
                   <tr class="d-flex">
                        <td class="text-center  col-1">
                            <spanx id="snum<?= $i; ?>"><b><?= $i; ?></b></spanx>
                        </td>
                        <td class="col-10"> 
                           <b> <?= $key["i_product_base"] . ' - ' . $key["e_product_basename"] . ' - ' . $key["e_color_name"]; ?> </b>
                        </td>
                        <td class="text-right  col-2"><b><?= $key["kategori"]; ?></b></td>
                    </tr>
                    <tr class="d-flex">
                        <td class="col-1"></td>
                        <td class="text-right col-1"><?= number_format($key["n_fc_berjalan"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["qty_do"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_fc"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_fc_next"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_stock"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_wip"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_unitjahit"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_pengadaan"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_packing"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity_tmp"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["persen_up"],0); ?></td>
                        <td class="text-right col-1"><?= number_format($key["n_quantity"],0); ?></td>
                        <td class="col-1"><?= $key["e_remark"]; ?></td>
                    </tr>
                    <?php
                        $tot_nquantity_fc += $key["n_quantity_fc"];
                        $tot_nquantity_stock += $key["n_quantity_stock"];
                        $tot_nquantity_stock_wip += $key["n_quantity_wip"];
                        $tot_nquantity_stock_jahit += $key["n_quantity_unitjahit"];
                        $tot_nquantity_stock_pengadaan += $key["n_quantity_pengadaan"];
                        $tot_nquantity_stock_packing += $key["n_quantity_packing"];
                        $tot_nquantity_up += $key["persen_up"];
                        $tot_nquantity_fc_berjalan += $key["n_fc_berjalan"];
                        $tot_nquantity_do_berjalan += $key["qty_do"];
                        $tot_nquantity_fc_selanjutnya += $key["n_fc_next"];
                        $tot_nquantity_tmp += (int) $key["n_quantity_tmp"];
                        $tot_nquantity += (int) $key["n_quantity"];
                    } 
                    ?>

                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script> -->
<script>
$(document).ready(function() {

    var $table = $('table.tableFixHead');
            $table.floatThead({
                responsiveContainer: function($table) {
                    return $table.closest('.table-responsive');
                }
            });

    // console.log(formatcemua('<?= $tot_nquantity_fc ?>'));
    // console.log('<?= number_format($tot_nquantity_fc) ?>');

    $('#h_fcd').text('<?= number_format($tot_nquantity_fc,0) ?>');
    $('#h_sjd').text('<?= number_format($tot_nquantity_stock,0) ?>');
    $('#h_swip').text('<?= number_format($tot_nquantity_stock_wip,0) ?>');
    $('#h_sjahit').text('<?= number_format($tot_nquantity_stock_jahit,0) ?>');
    $('#h_speng').text('<?= number_format($tot_nquantity_stock_pengadaan,0) ?>');
    $('#h_spack').text('<?= number_format($tot_nquantity_stock_packing,0) ?>');
    $('#h_qty_up').text('<?= number_format($tot_nquantity_up,0) ?>');
    $('#h_jml').text('<?= number_format($tot_nquantity,0) ?>');
    $('#h_fc_berjalan').text('<?= number_format($tot_nquantity_fc_berjalan,0) ?>');
    $('#h_do_berjalan').text('<?= number_format($tot_nquantity_do_berjalan,0) ?>');
    $('#h_fc_selanjutnya').text('<?= number_format($tot_nquantity_fc_selanjutnya,0) ?>');
    $('#h_jml_tmp').text('<?= number_format($tot_nquantity_tmp,0) ?>');
    
    /** Auto Numeric */

    // new AutoNumeric.multiple('.autoNum', {
    //     aSep: '.', 
    //     aDec: ',',
    //     decimalPlaces:'0',
    //     aForm: true,
    //     unformatOnSubmit: true,
    //     vMax: '999999999999',
    //     vMin: '-999999999999',

    // }); 
});
</script>