<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/approval'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Pembuat Dokumen</label>
                        <label class="col-md-4">Customer</label>
                        <label class="col-md-2">Bulan</label>
                        <label class="col-md-2">Tahun</label>

                        <div class="col-sm-4">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?php if ($head) echo $head->id; ?>" readonly>
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian; ?>" readonly>
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name; ?>" readonly>
                        </div>

                        <div class="col-sm-4">
                            <input type="hidden" name="idcustomer" id="idcustomer" class="form-control" value="<?= $customer->id; ?>" readonly>
                            <input type="text" name="e_customer_name" id="e_customer_name" class="form-control input-sm" value="<?= $customer->e_customer_name; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="hidden" name="bulan" id="ibulan" class="form-control" value="<?= $customer->ibulan; ?>" readonly>
                            <input type="text" name="ibulan" id="bulan" class="form-control input-sm" value="<?= $customer->bulan; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="tahun" id="tahun" class="form-control input-sm" value="<?= $customer->tahun; ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                             <button hidden="true" type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>

                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$head->id;?>','3','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                            <button type="button" class="btn btn-danger btn-rounded btn-sm"  onclick="statuschange('<?= $folder."','".$head->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                            <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$head->id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-6">Barang</label>
            <div class="col-sm-6">
                <input type="text" name="ekodebrg" id="ekode" class="form-control date" value="<?= $barang->e_material_name; ?>"disabled = 't'>
            </div>
        </div>
    </div> -->
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <div class="mb-3 row">
                <label class="col-md-12">Filter Berdasarkan Kategori Penjualan</label>
                <div class="col-sm-10">
                    <select class="form-control select2" name="class" id="class">
                        <option value="all" <?php if($iclass=='all'){ echo "selected"; }?>>Semua Kelas</option>
                        <?php if ($class->num_rows() > 0) {
                            foreach ($class->result() as $key) { ?>
                                <option value="<?= $key->id; ?>" <?php if($iclass==$key->id){ echo "selected"; }?>><?= $key->e_class_name; ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table tableFixHead inverse-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr > 
                        <th class="text-center">No</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Kategori Penjualan</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Rata<sup>2</sup> OP (3 bln)</th>
                        <th class="text-center">Jumlah FC</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                    $tot = 0;
                    foreach ($datadetail as $key) {
                        $i++;
                        $tot += $key["n_quantity"];
                    ?>
                        <tr >
                            <td class="text-center">
                                <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                            </td>
                            <td ><?= $key["i_product_base"]; ?></td>
                            <td ><?= $key["e_product_basename"] . ' - ' . $key["e_color_name"]; ?></td>
                            <td ><?= $key["e_class_name"]; ?></td>
                            <td class="text-right"><?= number_format($key["v_harga"]); ?></td>
                            <td class="text-right"><?= $key["n_rata2"]; ?></td>
                            <td class="text-right"><?= $key["n_quantity"]; ?></td>
                            <td ><?= $key["e_remark"]; ?></td>
                        </tr>
                    <?php }
                    ?>
                    <input style="width:50px" type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
                <tfoot>
                    <tr >
                        <th class="text-right" colspan="6">TOTAL</th>
                        <th class="text-right"><?= number_format($tot); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.floatThead.min.js"></script>
<script>
    $(document).ready(function() {
        var $table = $('table.tableFixHead');
            $table.floatThead({
                responsiveContainer: function($table) {
                    return $table.closest('.table-responsive');
                }
            });

        $('.select2').select2();
        $("#class").change(function(event) {
            $("#submit").click();
        });
    });
</script>