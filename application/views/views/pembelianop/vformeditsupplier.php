<style type="text/css">
    .pudding {
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }

    .table>thead>tr>th {
        padding: 5px 5px !important;
    }

    #tabledatax td {
        vertical-align: middle;
        padding: 3px 3px !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update_supplier'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Batasan Pemenuhan</label>
                        <label class="col-md-2">Jenis Pembelian</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="true">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->bagian_pembuat; ?></option>
                            </select>
                            <input type="hidden" id="id" name="id" value="<?= $data->id ?>">
                            <input type="hidden" id="ibagian" name="ibagian" value="<?= $data->i_bagian; ?>">
                            <input type="hidden" id="istatus" name="istatus" value="<?= $data->i_status; ?>">
                        </div>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_op; ?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $data->i_op; ?>)</span><br>
                                <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_op; ?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>
                        <?php
                        } ?>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dop" name="dop" class="form-control input-sm date" required="" readonly value="<?= $data->d_op; ?>">
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dop" name="dop" class="form-control input-sm" required="" readonly value="<?= $data->d_op; ?>">
                            </div>
                        <?php } ?>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dbp" name="dbp" class="form-control input-sm date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_deliv)); ?>">
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-2">
                                <input type="text" id="dbp" name="dbp" class="form-control input-sm" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_deliv)); ?>">
                            </div>
                        <?php } ?>
                        <div class="col-sm-2">
                            <input type="text" id="jenis" name="jenis" class="form-control input-sm" required="" readonly value="<?= $data->jenis_pembelian; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <!-- <label class="col-md-3">Gudang</label> -->
                        <!-- <label class="col-md-3">No Referensi</label> -->
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">Importance Status</label>
                        <label class="col-md-6">Keterangan</label>
                        <!-- <div class="col-sm-3"> -->
                        <?php $e_bagian_name = str_replace('"', '', str_replace("}", "", str_replace("{", "", str_replace(",", ",", $data->e_bagian_name)))); ?>
                        <!-- <input type="text" name="egudang" id="egudang" class="form-control input-sm" value="<?= $e_bagian_name ?>" readonly required> -->
                        <!-- </div> -->
                        <!-- <div class="col-sm-3">
                            <input type="text" name="ipp" id="ipp" class="form-control input-sm" value="<?= $data->i_pp ?>" readonly required>
                        </div> -->
                        <div class="col-sm-3">
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_supplier . " - " . $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" id="esuppliername" name="esuppliername" value="<?= $data->e_supplier_name; ?>">
                            <input type="hidden" id="ntop" name="ntop" value="<?= $data->n_top; ?>">
                            <?php if ($data->i_type_pajak == 'I') {
                                $fppn = 't';
                            } else if ($data->i_type_pajak == 'E') {
                                $fppn = 'f';
                            }
                            ?>
                            <input type="hidden" id="itypepajak" name="itypepajak" value="<?= $data->i_type_pajak; ?>">
                            <input type="hidden" id="fppn" name="fppn" value="<?= $fppn; ?>">
                            <input type="hidden" id="ndiskon" name="ndiskon" value="<?= $data->n_diskon; ?>">
                            <input type="hidden" id="fpkp" name="fpkp" value="<?= $data->f_pkp; ?>">
                        </div>
                        <div class="col-sm-3">
                            <?php if ($data->i_status != '6') { ?>
                                <select name="importantstatus" id="importantstatus" class="form-control select2">
                                    <option value="<?= $data->i_status_op; ?>"><?= $data->e_status_op; ?></option>
                                </select>
                            <?php } else { ?>
                                <input type="hidden" name="importantstatusharga" id="importantstatusharga" value="<?= $data->i_status_op; ?>">
                                <input type="text" name="emportantstatus" class="form-control input-sm " id="emportantstatus" value="<?= $data->e_status_op; ?>" readonly>
                            <?php } ?>
                        </div>
                        <?php if ($data->i_status != '6') { ?>
                            <div class="col-sm-6">
                                <textarea type="text" name="eremarkh" id="eremark" class="form-control input-sm" value="" placeholder="Keterangan"><?= $data->e_remark; ?></textarea>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-6">
                                <textarea type="text" name="eremarkh" id="eremark" class="form-control input-sm" value="" placeholder="Keterangan" readonly><?= $data->e_remark; ?></textarea>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return validasi();"><i class="fa fa-save fa-lg mr-2"></i>Update</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                        <?php if ($data->i_status_harga == '6') { ?>
                        <?php } ?>
                        <!-- <?php if ($data->i_status_harga == '2') { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?> -->
                        <!-- </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Form ini hanya untuk update quantity yang akan ganti Supplier!</span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th width="3%" class="text-center">No</th>
                        <th width="10%">Kode</th>
                        <th>Nama Material</th>
                        <th width="10%">Kode Material Supplier </th>
                        <th width="10%">Satuan</th>
                        <th class="text-right" width="8%">Qty OP Sisa</th>
                        <th class="text-right" width="8%">Qty OP Revisi</th>
                        <th width="12%">Keterangan Ganti Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($data2) {
                        $i = 0;
                        $group = "";
                        $no = 0;
                        foreach ($data2 as $row) {
                            $i++;
                            $no++;
                            if ($group == "") { ?>
                                <tr class="table-success">
                                    <td colspan="8">Nomor PP : <b><?= $row->i_pp; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp; ?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name; ?> )</td>
                                </tr>
                                <?php } else {
                                if ($group != $row->id_pp) { ?>
                                    <tr class="table-success">
                                        <td colspan="8">Nomor PP : <b><?= $row->i_pp; ?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp; ?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name; ?> )</td>
                                    </tr>
                            <?php $no = 1;
                                }
                            }
                            $group = $row->id_pp

                            ?>
                            <tr>
                                <td class="text-center">
                                    <?= $i; ?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i; ?>" name="baris<?= $i; ?>" value="<?= $i; ?>">
                                    <input type="hidden" id="id_op_item<?= $i; ?>" name="id_op_item<?= $i; ?>" value="<?= $row->id; ?>" readonly>
                                    <input type="hidden" id="ipp<?= $i; ?>" name="ipp<?= $i; ?>" value="<?= $row->i_pp; ?>" readonly>
                                    <input type="hidden" name="idpp<?= $i; ?>" id="idpp<?= $i; ?>" value="<?= $row->id_pp; ?>">
                                    <input type="hidden" class="form-control" id="ibagian<?= $i; ?>" name="ibagian<?= $i; ?>" value="<?= $row->i_bagian; ?>" readonly>
                                </td>
                                <td><?= $row->i_material; ?>
                                    <input type="hidden" class="form-control input-sm" id="imaterial<?= $i; ?>" name="imaterial<?= $i; ?>" value="<?= $row->i_material; ?>" readonly>
                                </td>
                                <td><?= $row->e_material_name; ?>
                                    <input type="hidden" class="form-control input-sm" id="ematerialname<?= $i; ?>" name="ematerialname<?= $i; ?>" value="<?= $row->e_material_name; ?>" readonly>
                                </td>
                                <td><?= $row->i_material_supplier; ?>
                                    <input type="hidden" class="form-control input-sm" id="imaterialsupplier<?= $i; ?>" name="imaterialsupplier<?= $i; ?>" value="<?= $row->i_material_supplier; ?>" readonly>
                                </td>

                                <td><?= $row->e_satuan_name; ?>
                                    <input type="hidden" id="isatuan<?= $i; ?>" name="i_satuan_code<?= $i; ?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                    <input type="hidden" class="form-control input-sm" id="isatuan1<?= $i; ?>" name="isatuan1<?= $i; ?>" value="<?= $row->e_satuan_name; ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" readonly class="form-control input-sm text-right" id="n_qty_sisa<?= $i; ?>" name="n_qty_sisa<?= $i; ?>" value="<?= $row->n_sisa; ?>" autocomplete="off" readonly>
                                </td>

                                <td>
                                    <input type="number" min="0" class="form-control input-sm text-right" id="n_qty_revisi<?= $i; ?>" name="n_qty_revisi<?= $i; ?>" onfocus="if($(this).val()=='0'){ $(this).val('')}" onblur="if($(this).val()==''){ $(this).val('0')}" value="0" autocomplete="off" onkeyup="cek_qty(<?= $i; ?>);">
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm" id="e_note_subtitution<?= $i; ?>" name="e_note_subtitution<?= $i; ?>" value="" placeholder="Isi Keterangan Ganti">
                                </td>
                            </tr>

                    <? }
                    } else {
                        $i = 0;
                        $read = "disabled";
                        echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada PP!</td></tr></table>";
                    } ?>
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        fixedtable($('.table'));

        $("form").submit(function(event) {
            event.preventDefault();
            $("input").attr("disabled", true);
            $("select").attr("disabled", true);
            $("#submit").attr("disabled", true);
            $("#sendd").attr("disabled", true);
            $("#cancel").attr("disabled", true);
        });
    });

    function cek_qty(i) {
        if (parseFloat($('#n_qty_revisi'+i).val()) > parseFloat($('#n_qty_sisa'+i).val())) {
            swal("Maaf :( ", "Jumlah Revisi = "+$('#n_qty_revisi'+i).val()+", tidak boleh lebih dari jumlah sisa "+$('#n_qty_sisa'+i).val()
            ,"error");
            $('#n_qty_revisi'+i).val($('#n_qty_sisa'+i).val());
        }
    }
</script>