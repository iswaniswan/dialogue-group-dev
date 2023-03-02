<style type="text/css">
    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #ddd;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                </div>
                <div class="panel-body">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <?php if ($head) {
                        ?>
                            <div class="form-group row">
                                <label class="col-md-3">Bagian Pembuat</label>
                                <label class="col-md-3">Nomor Dokumen</label>
                                <label class="col-md-2">Tanggal Dokumen</label>
                                <label class="col-md-4">Customer</label>
                                <div class="col-md-3">
                                    <input type="text" readonly="" class="form-control input-sm" value="<?= $head->e_bagian_name; ?>">
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" readonly="" class="form-control input-sm" value="<?= $head->i_document; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control input-sm date" readonly value="<?= $head->d_document; ?>">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" readonly value="<?= $head->e_customer_name; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2">Nomor Pajak</label>
                                <label class="col-md-2">Tanggal Pajak</label>
                                <label class="col-md-2">Tgl Terima Faktur</label>
                                <label class="col-md-2">Tgl Jatuh Tempo</label>
                                <label class="col-md-4">Keterangan</label>
                                <div class="col-sm-2">
                                    <input type="text" name="ipajak" id="ipajak" class="form-control input-sm" value="<?= $head->i_pajak; ?>" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="dpajak" id="dpajak" class="form-control input-sm" value="<?= $head->d_pajak; ?>" readonly>
                                </div>

                                <div class="col-sm-2">
                                    <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control input-sm" value="<?= $head->d_terima_faktur; ?>" readonly="">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control input-sm" value="<?= $head->d_jatuh_tempo; ?>" readonly>
                                </div>
                                <div class="col-sm-4">
                                    <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!" readonly><?= $head->e_remark; ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                </div>
                            </div>
                    </div>
                    <div class="col-md-12">
                    <?php
                        } else {
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada Data!</td></tr></table>";
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0;
    $group = "";
    $no = 0;
    if ($detail) { ?>
        <div class="white-box" id="detail">
            <div class="col-sm-6">
                <h3 class="box-title m-b-0">Detail Barang</h3>
            </div>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="tabledatay" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">No</th>
                                <th class="text-center" width="30%;">Barang</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-center" colspan="3" width="15%;">Discount 123 (%)</th>
                                <th class="text-right">Discount (Rp.)</th>
                                <th class="text-right">Sub Total</th>
                                <th class="text-center">Keterangan</th>
                                <!--  <th class="text-center" width="3%">Act</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($detail as $key) {
                                $i++;
                                $no++;
                                $total = round($key->v_price * $key->n_quantity);
                                if ($group == "") { ?>
                                    <tr class="pudding">
                                        <td colspan="10">Nomor SJ : <b><?= $key->i_document; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal SJ : <b><?= $key->d_document; ?></b></td>
                                    </tr>
                                    <?php } else {
                                    if ($group != $key->id_document) { ?>
                                        <tr class="pudding">
                                            <td colspan="10">Nomor SJ : <b><?= $key->i_document; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal SJ : <b><?= $key->d_document; ?></b></td>
                                        </tr>
                                <?php $no = 1;
                                    }
                                }
                                $group = $key->id_document; ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i; ?>"><?= $no; ?></spanx>
                                    </td>
                                    <td><?= $key->i_product_base . ' - ' . $key->e_product_basename; ?></td>
                                    <td class="text-right"><?= $key->n_quantity; ?></td>
                                    <td class="text-right"><?= number_format($key->v_price); ?></td>
                                    <td class="text-right"><?= $key->n_diskon1; ?></td>
                                    <td class="text-right"><?= $key->n_diskon2; ?></td>
                                    <td class="text-right"><?= $key->n_diskon3; ?></td>
                                    <td class="text-right"><?= number_format($key->v_diskon_tambahan); ?></td>
                                    <td class="text-right"><?= number_format($total); ?></td>
                                    <td><?= $key->e_remark; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>

                            <tr>
                                <td class="text-right bold" colspan="8">Total :</td>
                                <td class="text-right bold"><?= number_format($head->v_kotor); ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" colspan="8">Diskon :</td>
                                <td class="text-right bold"><?= number_format($head->v_diskon); ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" colspan="8">DPP :</td>
                                <td class="text-right bold"><?= number_format($head->v_dpp); ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" colspan="8">PPN (10%) :</td>
                                <td class="text-right bold"><?= number_format($head->v_ppn); ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" colspan="8">Bea Meterai :</td>
                                <td class="text-right bold"><?= number_format($head->v_meterai); ?></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" colspan="8">Grand Total :</td>
                                <td class="text-right bold"><?= number_format($head->v_bersih + $head->v_meterai); ?></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
</form>