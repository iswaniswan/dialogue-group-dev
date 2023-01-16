<style>
    .table {
        white-space:nowrap !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>

            <div class="white-box" id="detail">
                <div class="col-sm-6">
                    <h3 class="box-title m-b-0">Detail Barang Budgeting</h3>
                    <div class="m-b-0">
                        <div class="form-group row">
                            <label class="col-md-12">Data Detail Material Berdasarkan Budgeting</label>
                            <div class="col-sm-7">
                                <select class="form-control select2" name="i_budgeting" id="i_budgeting">
                                    <option value="<?= $data->id; ?>"><?= $data->i_document . ' [ ' . $data->periode . ' ]'; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','3','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                        <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','6','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;
                    </div>
                </div>
                
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="3"></th>
                                    <th class="text-right" colspan="7">Grand Total</th>
                                    <th class="text-left" colspan="3"><span id="grandtotal"></span><?php echo number_format($data->total, 3); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Barang</th>
                                    <th class="text-center">Satuan <br> Pembelian</th>
                                    <!-- <th class="text-center">Sisa</th> -->
                                    <th class="text-center">Jml Kebutuhan <br> Real</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Jenis Harga</th>
                                    <th class="text-center">Min Order</th>
                                    <th class="text-center">Jml <br>Adjusment</th>
                                    <th class="text-center">Harga <br>Supplier</th>
                                    <!-- <th class="text-center">Harga Adj</th> -->
                                    <th class="text-center">Sub Total</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td colspan="9"></td>
                                </tr> -->
                                <? $i = 0;
                                if ($detail) {
                                    foreach ($detail as $row) {
                                        $i++; ?>
                                        <tr>
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= $row->i_material; ?></td>
                                            <td><?= $row->e_material_name; ?></td>
                                            <td><?= $row->e_satuan_name; ?></td>
                                            <td class="text-right"><?= $row->n_quantity_old; ?></td>
                                            <td><?= $row->kode_supplier . " - " . $row->nama_supplier; ?></td>
                                            <td class="text-right"><?= $row->inex; ?></td>
                                            <td class="text-right"><?= $row->n_min_order; ?></td>
                                            <td class="text-right"><?= $row->n_adjusment; ?></td>
                                            <td class="text-right"><?= $row->v_price; ?></td>
                                            <!-- <td class="text-right"><?= $row->v_price_adj; ?></td> -->
                                            <td class="text-right"><?= number_format($row->sub_total_edit); ?></td>
                                            <td><?= $row->e_remark; ?></td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                            <!--                             <tfoot>
                                <tr> 
                                    <td colspan="6" class="text-right">Total</td>
                                    <td colspan="2" class="text-right"><b><?= 'Rp. ' . number_format($total, 2); ?></b></td>
                                </tr>
                            </tfoot> -->
                        </table>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                </div>
                
            </div>


        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        fixedtable($('.table'));
    });
</script>