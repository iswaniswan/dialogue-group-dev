<style>
    th,
    td {
        padding: 0.60rem !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check mr-2"></i><?= $title; ?>
                <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control input-sm select2" required="" disabled="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_bagian) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="text" name="inota" id="inota" class="form-control input-sm" value="<?= $data->i_nota; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dnota" id="dnota" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_nota)) ?>" readonly="" onchange="max_tgl(this.value);">
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="isupplier" class="form-control input-sm" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="isuppliername" class="form-control input-sm" value="<?= $data->e_supplier_name; ?>" readonly>
                            <input type="hidden" name="fsupplierpkp" id="fsupplierpkp" class="form-control input-sm" value="<?= $data->f_pkp; ?>" readonly>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">Nomor Pajak</label>
                        <label class="col-md-2">Tanggal Pajak</label>
                        <label class="col-md-2">Nomor Faktur</label>
                        <label class="col-md-2">Tgl Faktur Supplier</label>
                        <label class="col-md-2">Tgl Terima Faktur</label>
                        <label class="col-md-2">Tgl Jatuh Tempo</label>
                        <div class="col-sm-2">
                            <input type="text" name="ipajak" id="ipajak" class="form-control input-sm" value="<?= $data->i_pajak; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dpajak" id="dpajak" class="form-control input-sm" value="<?php if ($data->d_pajak != null) {
                                                                                                                    echo date("d-m-Y", strtotime($data->d_pajak));
                                                                                                                } ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ifaktur" id="ifaktur" class="form-control input-sm" value="<?= $data->i_faktur_supplier; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dfsupp" id="dfsupp" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_faktur_supplier)); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_terima_faktur)); ?>" readonly="">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="djatuhtempo" id="djatuhtempo" class="form-control input-sm" value="<?= date("d-m-Y", strtotime($data->d_jatuh_tempo)); ?>" readonly="">
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                    	<label class="col-md-1">Diskon (rp.)</label>  
                        <label class="col-md-1">Jml Dis Reg</label>
                        <label class="col-md-2">Nilai Total DPP</label>
                        <label class="col-md-2">Nilai Total PPN</label>   
                        <label class="col-md-2">Jumlah Nilai Bruto</label>
                        <label class="col-md-4">Jumlah Nilai Netto</label>
                        <div class="col-sm-2">
                            <input type="text" name="vdiskon" id="vdiskon" class="form-control input-sm" value="<?= $data->v_diskon_lain; ?>" readonly>
                        </div>
                         <div class="col-sm-2">
                            <input type="text" name="vtotaldis" id="vtotaldis" class="form-control input-sm" value="<?= $data->v_sub_diskon; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control input-sm" value="<?= $data->v_dpp; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="vtotalppn" id="vtotalppn" class="form-control input-sm" value="<?= $data->v_ppn; ?>" readonly>
                        </div> 
                        <div class="col-sm-2">
                           <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control input-sm" value="<?= $data->v_total_bruto; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="vtotalnet" id="vtotalnet" class="form-control input-sm" value="<?= $data->v_total_net; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                           <input type="hidden" name="vtotalfa" id="vtotalfa" class="form-control input-sm" value="<?= $data->v_total; ?>" readonly>
                        </div>                    
                    </div>                   -->
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm input-sm" name="eremark" readonly><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-warning btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','3','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','4','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-success btn-block btn-sm" onclick="statuschange('<?= $folder . "','" . $data->id; ?>','11','<?= $dfrom . "','" . $dto; ?>');"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>
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
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No BTB</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <!-- <th>Qty Eks</th>
                        <th>Satuan Eks</th> -->
                        <th>Satuan In</th>
                        <th class="text-right">Qty In</th>
                        <th class="text-right">Toleransi</th>
                        <th class="text-center">Plus Toleransi</th>
                        <th class="text-right">Total Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Harga Manual</th>
                        <th class="text-right">DPP</th>
                        <th class="text-right">PPN</th>
                        <th class="text-right">Jumlah Total (Rp.)</th>
                        <th class="text-right">Pembulatan (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($data1) {
                        $i = 0;
                        foreach ($data1 as $row) {
                            $i++;
                    ?>
                            <tr>
                                <td class="col-sm-1 text-center">
                                    <?php echo $i; ?>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:170px" class="form-control input-sm" type="hidden" id="idiop<?= $i; ?>" name="idiop<?= $i; ?>" value="<?= $row->id_op; ?>" readonly>
                                    <input style="width:170px" class="form-control input-sm" type="hidden" id="idbtb<?= $i; ?>" name="idbtb<?= $i; ?>" value="<?= $row->id_btb; ?>" readonly>
                                    <input style="width:170px" class="form-control input-sm" type="text" id="ibtb<?= $i; ?>" name="ibtb<?= $i; ?>" value="<?= $row->i_btb; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm" type="text" id="imaterial<?= $i; ?>" name="imaterial<?= $i; ?>" value="<?= $row->i_material; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:300px" class="form-control input-sm" type="text" id="ematerial<?= $i; ?>" name="ematerial<?= $i; ?>" value="<?= htmlentities($row->e_material_name); ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="isupplier<?= $i; ?>" name="isupplier<?= $i; ?>" value="<?= $row->i_supplier; ?>" readonly>
                                </td>
                                <td class="col-sm-1" hidden="true">
                                    <input style="width:70px" type="text" id="nquantityeks<?= $i; ?>" name="nquantityeks<?= $i; ?>" value="<?= $row->n_quantity_eks; ?>" class="form-control input-sm" readonly>
                                </td>
                                <td class="col-sm-1" hidden="true">
                                    <input style="width:100px;" type="hidden" id="isatuaneks<?= $i; ?>" class="form-control input-sm" name="isatuaneks<?= $i; ?>" value="<?= $row->i_satuan_code_eks; ?>" readonly>
                                    <input style="width:100px;" type="text" id="esatuaneks<?= $i; ?>" class="form-control input-sm" name="esatuaneks<?= $i; ?>" value="<?= $row->satuaneks; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="isatuan<?= $i; ?>" name="isatuan<?= $i; ?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="text" id="esatuan<?= $i; ?>" name="esatuan<?= $i; ?>" value="<?= $row->e_satuan_name; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="nquantity<?= $i; ?>" name="nquantity<?= $i; ?>" value="<?php echo number_format($row->n_quantity_btb, 2); ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm text-right" type="text" id="toleransi<?= $i; ?>" name="toleransi<?= $i; ?>" value="<?php echo $row->n_toleransi; ?>" readonly>
                                </td>
                                <td class="col-sm-1 text-center">
                                    <input type="checkbox" <?php if ($row->f_toleransi == 't') {
                                                                echo "checked";
                                                            }; ?> id="plus<?php echo $i; ?>" class="form-control input-sm" name="plus<?php echo $i; ?>" onclick="plus(<?php echo $i ?>)">
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm text-right" type="text" id="qty_total<?= $i; ?>" name="nquantity<?= $i; ?>" value="<?php echo $row->n_quantity; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="vharga<?= $i; ?>" name="vharga<?= $i; ?>" value="<?= number_format($row->v_price, 4); ?>" readonly>
                                    <input style="width:100px" class="form-control input-sm" type="hidden" id="nppn<?= $i; ?>" name="nppn<?= $i; ?>" value="<?= $ppnop->ppn_op; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control input-sm text-right" type="text" id="vharga_manual<?= $i; ?>" name="vharga_manual<?= $i; ?>" value="<?= number_format($row->v_price_manual, 4); ?>" readonly onkeyup="angkahungkul(this);hitung();reformat(this);">
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="vdpp<?= $i; ?>" name="vdpp<?= $i; ?>" value="<?= $row->v_dpp; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:100px" class="form-control text-right input-sm" type="text" id="vppn<?= $i; ?>" name="vppn<?= $i; ?>" value="<?= $row->v_ppn; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:150px" type="hidden" id="vtotal<?= $i; ?>" name="vtotal<?= $i; ?>" value="<?= $row->v_total; ?>" readonly>
                                    <input style="width:150px" class="form-control text-right input-sm" name="totalfake<?php echo $i; ?>" id="totalfake<?php echo $i; ?>" type="hidden" value="<?php echo number_format($row->v_total, 2); ?>" readonly>
                                    <input style="width:150px" class="form-control text-right input-sm" type="text" id="vtotalsem<?= $i; ?>" name="vtotalsem<?= $i; ?>" value="<?= $row->v_total; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style="width:150px" class="form-control text-right input-sm" type="text" id="v_pembulatan_item<?= $i; ?>" name="v_pembulatan_item<?= $i; ?>" value="<?= number_format($row->v_pembulatan); ?>" readonly>
                                </td>
                            </tr>
                    <? }
                    } ?>
                </tbody>

                <tfoot>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Bruto Rp.</th>
                        <th><input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control text-right input-sm" value="0" readonly></th>
                        <th colspan="3">Diskon Supplier (%)</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Diskon Tambahan Rp.</th>
                        <th>
                            <input type="text" autocomplete="off" name="vdiskon" id="vdiskon" class="form-control text-right input-sm" readonly value="<?= number_format($data->v_sub_diskon); ?>" onkeyup="hitung();">
                            <input type="hidden" name="vdiskonsup" id="vdiskonsup" class="form-control input-sm" value="0" readonly>
                        </th>
                        <th>
                            <input type="text" name="diskonsup" id="diskonsup" class="form-control text-right input-sm" value="<?= $data->n_diskon; ?>" readonly>
                        </th>
                        <th class="text-right" colspan="2"></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Diskon Total (<span id="diskon_persen"><?= $data->n_diskon; ?></span>%) Rp.</th>
                        <th>
                            <input type="text" autocomplete="off" name="vdiskontotal" id="vdiskontotal" class="form-control text-right input-sm" value="0" readonly>
                        </th>
                        <th class="text-right" colspan="3"></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai DPP Rp.</th>
                        <th><input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control text-right input-sm" value="0" readonly></th>
                        <th class="text-right" colspan="3"></th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai PPN Rp.</th>
                        <th><input type="text" name="vtotalppn" id="vtotalppn" class="form-control text-right input-sm" value="0" readonly></th>
                        <th colspan="3">Pembulatan</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="11">Nilai Netto Rp.</th>
                        <th><input type="text" name="vtotalnet" id="vtotalnet" class="form-control text-right input-sm" value="0" readonly></th>
                        <th>
                            <input type="text" name="v_pembulatan" id="v_pembulatan" class="form-control text-right input-sm" value="<?= number_format($data->v_pembulatan); ?>" readonly>
                        </th>
                        <th class="text-right" colspan="2"></th>
                    </tr>
                </tfoot>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                <input type="hidden" name="ppn_op" id="ppn_op" value="<?= $ppnop->ppn_op; ?>">
            </table>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function() {
        $(".select2").select2();
        fixedtable($('.table'));
        hitung();
    });

    function hitung() {
        var jml = $('#jml').val();
        var bruto = 0;
        var subtotal = 0;
        var dpp = 0;
        var ppn = 0;
        for (var i = 1; i <= jml; i++) {
            var harga = parseFloat(formatulang($('#vharga_manual' + i).val()));
            var qty = parseFloat(formatulang($('#qty_total' + i).val()));
            var n_ppn = parseFloat(formatulang($('#nppn' + i).val()));
            /* if ($("#cek" + i + ":checked").length > 0) { */
            var v_dpp = harga * qty;
            $('#vdpp' + i).val(formatcemua(Math.round(v_dpp)));
            v_ppn = (v_dpp * (n_ppn / 100));
            $('#vppn' + i).val(formatcemua(Math.round(v_ppn)));
            v_total = (parseFloat(v_dpp) + parseFloat(v_ppn));
            $('#vtotalsem' + i).val(formatcemua(Math.round(v_total)));
            dpp += v_dpp;
            ppn += v_ppn;
            subtotal += v_total;
            /* } */
        }
        var diskon_persen_sup = parseFloat(formatulang(document.getElementById('diskonsup').value));
        var diskon = parseFloat(formatulang(document.getElementById('vdiskon').value));
        if (isNaN(diskon)) {
            var diskon = 0;
        }
        var diskon_sup = dpp * (diskon_persen_sup / 100);
        document.getElementById('vdiskonsup').value = formatcemua(Math.round(diskon_sup));
        document.getElementById('vdiskontotal').value = formatcemua(Math.round(diskon_sup + diskon));
        var diskon_total = parseFloat(formatulang(document.getElementById('vdiskontotal').value));
        if (isNaN(diskon_total)) {
            var diskon_total = 0;
        }
        var tot_dpp = dpp - diskon_total;
        var tot_ppn = tot_dpp * (parseFloat(formatulang(document.getElementById('ppn_op').value)) / 100);
        document.getElementById('diskon_persen').textContent = formatcemua(Math.round((diskon_total / dpp) * 100));
        document.getElementById('vtotalbruto').value = formatcemua(Math.round(dpp));
        document.getElementById('vtotaldpp').value = formatcemua(Math.round(tot_dpp));
        document.getElementById('vtotalppn').value = formatcemua(Math.round(tot_ppn));
        document.getElementById('vtotalnet').value = formatcemua(Math.round(tot_dpp) + Math.round(tot_ppn));
    }
</script>