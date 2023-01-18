<style>
    .font{
        font-size: 12px !important;
    }
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
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;"><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
            <table id="tabledatax" class="table color-table inverse-table nowrap font table-bordered class" cellspacing="0"
                width="100%">
                <thead>
                    <tr>
                        <th colspan="3"></th>
                        <!-- <th class="text-center" width="10%">Harga</th> -->
                        <th class="text-center"><span id="totaldist" ><b></b></span></th>
                        <th class="text-center"><span id="totaljadi" ><b></b></span></th>
                        <th class="text-center"><span id="totalwip" ><b></b></span></th>
                        <th class="text-center"><span id="totaljahit" ><b></b></span></th>
                        <th class="text-center"><span id="totalpengadaan" ><b></b></span></th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                        <th class="text-center"><span id="totalqty" ><b></b></span></th>
                        <th class="text-center"></th>
                    </tr>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th width="25%">Barang</th>
                        <!-- <th class="text-center" width="10%">Harga</th> -->
                        <th class="text-right">Kategori</th>
                        <th class="text-right">FC Dist</th>
                        <th class="text-right">Stock<br>Jadi</th>
                        <th class="text-right">Stock<br>WIP</th>
                        <th class="text-right">Stock<br>Jahit</th>
                        <th class="text-right">Stock<br>Pengadaan</th>
                        <th class="text-right">% Up</th>
                        <th class="text-right">Estimasi<br> FC</th>
                        <th class="text-right">Jml<br> FC</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    $totaldist = 0;
                    $totaljadi = 0;
                    $totalwip = 0;
                    $totaljahit = 0;
                    $totalpengadaan = 0;
                    $totalqty = 0;
                    foreach ($datadetail as $key) {
                        $i++;
                    ?>
                    <tr>
                        <td class="text-center">
                            <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                        </td>
                        <td>
                            <?= $key["i_product_base"] . ' - ' . $key["e_product_basename"] . ' - ' . $key["e_color_name"]; ?>
                        </td>
                        <td class="text-right"><?= $key["kategori"]; ?></td>
                        <td class="text-right"><span ><?= $key["n_quantity_fc"]; ?></span></td>                        
                        <td class="text-right"><span ><?= $key["n_quantity_stock"]; ?></span></td>
                        <td class="text-right"><span ><?= $key["n_quantity_wip"]; ?></span></td>
                        <td class="text-right"><span ><?= $key["n_quantity_unitjahit"]; ?></span></td>
                        <td class="text-right"><span ><?= $key["n_quantity_pengadaan"]; ?></span></td>
                        <td class="text-right"><?= $key["persen_up"]; ?></td>
                        <td class="text-right">0</td>
                        <td class="text-right"><span ><?= $key["n_quantity"]; ?></span></td>
                        <td><?= $key["e_remark"]; ?></td>
                    </tr>
                    <?php 
                    $totaldist      = $totaldist + $key["n_quantity_fc"];
                    $totaljadi      = $totaljadi + $key["n_quantity_stock"];
                    $totalwip       = $totalwip + $key["n_quantity_wip"];
                    $totaljahit     = $totaljahit + $key["n_quantity_unitjahit"];
                    $totalpengadaan = $totalpengadaan + $key["n_quantity_pengadaan"];
                    $totalqty       = $totalqty + $key["n_quantity"];
                    }
                    $totaldist      = number_format($totaldist,0,",",".");
                    $totaljadi      = number_format($totaljadi,0,",",".");
                    $totalwip       = number_format($totalwip,0,",",".");
                    $totaljahit     = number_format($totaljahit,0,",",".");
                    $totalpengadaan = number_format($totalpengadaan,0,",",".");
                    $totalqty       = number_format($totalqty,0,",",".");
                    ?>
                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script> -->
<script>
$(document).ready(function() {
    
    document.getElementById("totaldist").innerHTML = <?php echo $totaldist; ?>;
    document.getElementById("totaljadi").innerHTML = <?php echo $totaljadi; ?>;
    document.getElementById("totalwip").innerHTML = <?php echo $totalwip; ?>;
    document.getElementById("totaljahit").innerHTML = <?php echo $totaljahit; ?>;
    document.getElementById("totalpengadaan").innerHTML = <?php echo $totalpengadaan; ?>;
    document.getElementById("totalqty").innerHTML = <?php echo $totalqty; ?>;  
    
    /** Auto Numeric */

    // new AutoNumeric.multiple('.autoNum', {
    //     aSep: '.', 
    //     aDec: ',',
    //     decimalPlaces:'0',
    //     aForm: true,
    //     unformatOnSubmit: true,
    //     vMax: '999999999999',
    //     vMin: '-999999999999',
    //     watchExternalChanges: true

    // }); 

    // var i = 0;
    // var jml = $('#jml').val();
    // for (i;i<jml;i++){
    //     new AutoNumeric.multiple('.autoNum', {
    //     aSep: '.', 
    //     aDec: ',',
    //     decimalPlaces:'0',
    //     aForm: true,
    //     unformatOnSubmit: true,
    //     vMax: '999999999999',
    //     vMin: '-999999999999',

    // }); 
    // }
});
</script>