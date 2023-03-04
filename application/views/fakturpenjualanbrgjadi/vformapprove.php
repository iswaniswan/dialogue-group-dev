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
                    <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
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
                                <!-- <label class="col-md-2">Nomor Pajak</label>
                    <label class="col-md-2">Tanggal Pajak</label> -->
                                <label class="col-md-2">Tgl Terima Faktur</label>
                                <label class="col-md-2">Tgl Jatuh Tempo</label>
                                <label class="col-md-8">Keterangan</label>
                                <div class="col-sm-2">
                                    <input type="hidden" name="ipajak" id="ipajak" class="form-control" value="<?= $head->i_pajak; ?>" readonly>
                                    <input type="hidden" name="dpajak" id="dpajak" class="form-control" value="<?= $head->d_pajak; ?>" readonly>
                                    <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control" value="<?= $head->d_terima_faktur; ?>" readonly="">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control" value="<?= $head->d_jatuh_tempo; ?>" readonly>
                                </div>
                                <div class="col-sm-8">
                                    <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!" readonly><?= $head->e_remark; ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','1','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                                </div>
                                <div class="col-sm-3">
                                    <button type="button" class="btn btn-success btn-block btn-sm" onclick="approve();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
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
                                    <td>
                                        <?= $key->e_remark; ?>
                                        <input type="hidden" readonly class="form-control input-sm text-right" placeholder="%1" name="ndisc1<?= $i; ?>" id="ndisc1<?= $i; ?>" value="<?= $key->n_diskon1; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc1<?= $i; ?>" id="vdisc1<?= $i; ?>" value="<?= $key->v_diskon1; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" placeholder="%2" name="ndisc2<?= $i; ?>" id="ndisc2<?= $i; ?>" value="<?= $key->n_diskon2; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc2<?= $i; ?>" id="vdisc2<?= $i; ?>" value="<?= $key->v_diskon2; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" placeholder="%3" name="ndisc3<?= $i; ?>" id="ndisc3<?= $i; ?>" value="<?= $key->n_diskon3; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vdisc3<?= $i; ?>" id="vdisc3<?= $i; ?>" value="<?= $key->v_diskon3; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="e_product<?= $i; ?>" id="e_product<?= $i; ?>" value="<?= $key->e_product_basename; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="id_document<?= $i; ?>" id="id_document<?= $i; ?>" value="<?= $key->id_document; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm" name="id_product<?= $i; ?>" id="id_product<?= $i; ?>" value="<?= $key->id_product; ?>" />
                                        <input type="hidden" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity; ?>" onkeyup="angkahungkul(this); hitungtotal();" readonly>
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="nquantity_sj<?= $i; ?>" id="nquantity_sj<?= $i; ?>" value="<?= $key->n_quantity_sj; ?>" />
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vharga<?= $i; ?>" id="vharga<?= $i; ?>" value="<?= number_format($key->v_price); ?>" />
                                        <input type="hidden" class="form-control input-sm text-right" name="vdiscount<?= $i; ?>" id="vdiscount<?= $i; ?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= number_format($key->v_diskon_tambahan); ?>" onkeyup="angkahungkul(this); hitungtotal(); reformat(this);" readonly />
                                        <input type="hidden" readonly class="form-control input-sm text-right" name="vtotal<?= $i; ?>" id="vtotal<?= $i; ?>" value="<?= number_format($total); ?>" /><input type="hidden" readonly class="form-control input-sm text-right" name="vtotaldiskon<?= $i; ?>" id="vtotaldiskon<?= $i; ?>" value="<?= $key->v_diskon_total; ?>" />
                                    </td>
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
<script>
    function approve() {
        var data = [];
        for (var i = 1; i <= $('#jml').val(); i++) {
            if (parseInt($('#nquantity' + i).val()) > parseInt($('#nquantity_sj' + i).val())) {
                swal('Maaf :(', 'Quantity ' + $('#i_product_base' + i).val() + ' Tidak Boleh lebih dari ' + $('#nquantity_sj' + i).val() + ' !', 'error');
                data.push("lebih");
            } else {
                data.push("oke");
            }
        }
        if (data.includes("lebih") == false) {
            statuschange('<?= $folder . "','" . $id; ?>', '6', '<?= $dfrom . "','" . $dto; ?>');
        }
    }
</script>