<?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    function samadenganqtyx(qtyset, xi) {
        // iih++;
        // alert(qtywip+ '-'+i);
        $('.qtyset' + xi).val(qtyset);
    }

    function samadenganmarkx(erset, xi) {
        // i++;
        $('.erset' + xi).val(erset);
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian; ?>">
                            <select name="ibagian" id="ibagian" class="form-control select2">
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
                                <input type="hidden" name="ireturold" id="ireturold" value="<?= $data->i_retur; ?>">
                                <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_retur; ?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control date" required="" readonly value="<?= $data->d_retur; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>" <?php if ($row->i_bagian == $data->i_tujuan) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-md-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2">
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_document; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <textarea id="eremarkh" name="eremarkh" class="form-control"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <!-- <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp; -->
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') { ?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php } elseif ($data->i_status == '2') { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0;
if ($datadetail) { ?>
    <div class="white-box" id="detail">
        <div class="col-sm-3">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 25%;">Kode</th>
                            <th class="text-center" style="width: 35%;">Barang</th>
                            <th class="text-center" style="width: 10%;">Qty Retur</th>
                            <th class="text-center" style="width: 10%;">Qty Terima</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $z = 0;
                        $group = "";
                        foreach ($datadetail as $key) {
                            $i++;
                            if ($group != $key->id_product_wip) {
                                $z++;
                            }
                        ?>
                            <?php if ($group == "") { ?>
                                <tr id="tr<?= $z; ?>">
                                    <td colspan="6">
                                        <input type="text" id="iproduct<?= $z; ?>" class="form-control input-sm" name="iproduct<?= $z; ?>"  value="<?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>" readonly>
                                        <input type="hidden" id="idproduct<?= $z; ?>" class="form-control" name="idproduct<?= $z; ?>" value="<?= $key->id_product_wip; ?>" readonly>
                                    </td>
                                    <!-- <td class="text-center">
                                        <button type="button" title="Delete" onclick="hapusdetail(<?= $z; ?>);" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                    </td> -->
                                </tr>

                                <?php } else {
                                if ($group != $key->id_product_wip) { ?>
                                    <tr id="tr<?= $z; ?>">
                                        <td colspan="6">
                                            <input type="text" id="iproduct<?= $z; ?>" class="form-control input-sm" name="iproduct<?= $z; ?>"  value="<?= $key->i_product_wip . ' - ' . $key->e_product_wipname . ' - ' . $key->e_color_name; ?>" readonly>
                                            <input type="hidden" id="idproduct<?= $z; ?>" class="form-control" name="idproduct<?= $z; ?>" value="<?= $key->id_product_wip; ?>" readonly>
                                        </td>
                                        <!-- <td class="text-center">
                                            <button type="button" title="Delete" onclick="hapusdetail(<?= $z; ?>);" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                        </td> -->
                                    </tr>
                            <?php $i = 1;
                                }
                            } ?>
                            <?php $group = $key->id_product_wip; ?>
                            <tr class="del<?= $z; ?>">
                                <td class="text-center"><?= $i; ?><input type="hidden" name="idproductwip[]" value="<?= $key->id_product_wip; ?>"><input type="hidden" name="iditem[]" id="iditem<?= $i; ?>" value="<?= $key->id; ?>" ></td>
                                <td><input type="text" id="epanel<?= $i; ?>" class="form-control input-sm" value="<?= $key->i_panel.' - '.$key->bagian; ?>" readonly></td>
                                <td><input type="text" id="imaterial<?= $i; ?>" class="form-control input-sm" name="imaterial<?= $i; ?>" value="<?= $key->i_material.' - '.htmlentities($key->e_material_name); ?>" readonly>
                                <input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial<?= $i; ?>" value="<?= $key->id_material; ?>">
                                <input type="hidden" class="idpanel" name="idpanel[]" id="idpanel<?= $i; ?>" value="<?= $key->id_panel_item; ?>"></td>
                                <td><input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantityretur<?= $i; ?>" name="nquantityretur[]" value="<?= $key->n_qty_retur; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="validasi(<?= $i; ?>);" ></td>
                                <td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantityterima<?= $i; ?>" name="nquantityterima[]" value="<?= $key->n_qty_terima; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="0" onkeyup="validasi(<?= $i; ?>);" ></td>
                                <td><input class="form-control input-sm" type="text" name="edesc[]" id="edesc<?= $i; ?>" value="<?= $key->e_remark; ?>" placeholder="Isi keterangan jika ada!"></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="<?= $z; ?>">
<?php } ?>
</form>

<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');
        
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });


    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
    });


    /**
     * Tambah Item
     */

    var i = $('#jml').val();

    function hetang(x, idproductwip) {

        var n_max = 0;

        var n_qty_retur = parseFloat($('#n_qty_retur_'+idproductwip+'_'+x).val());
        var n_qty_penyusun = parseFloat($('#n_qty_penyusun_'+idproductwip+'_'+x).val());

        var n_quantity_akhir = n_qty_retur / n_qty_penyusun;

        if (isFinite(n_quantity_akhir)  ) {
            $('#nquantity_'+idproductwip+'_'+x).val(n_quantity_akhir);
            $('#nquantity_tmp_'+idproductwip+'_'+x).val(n_quantity_akhir);
        }else{
            $('#nquantity_'+idproductwip+'_'+x).val(0);
            $('#nquantity_tmp_'+idproductwip+'_'+x).val(0);
        }

        n_max = Math.ceil(Math.max.apply(Math,$("input[name='nquantity_tmp_"+idproductwip+"[]']").map(function(){return parseFloat($(this).val());}).get()));
        $('#n_qty_kekurangan_'+idproductwip+'_'+x).val(n_max * n_qty_penyusun - n_qty_retur );
        
        for(var i = 1; i<= $("input[name='nquantity_tmp_"+idproductwip+"[]']").length ; i++) {
            var n_qty_retur = parseFloat($('#n_qty_retur_'+idproductwip+'_'+i).val());
            var n_qty_penyusun = parseFloat($('#n_qty_penyusun_'+idproductwip+'_'+i).val());

            var n_quantity_akhir = n_qty_retur / n_qty_penyusun;

            if (isFinite(n_quantity_akhir)  ) {
                $('#nquantity_'+idproductwip+'_'+i).val(n_quantity_akhir);
                $('#nquantity_tmp_'+idproductwip+'_'+i).val(n_quantity_akhir);
            }else{
                $('#nquantity_'+idproductwip+'_'+i).val(0);
                $('#nquantity_tmp_'+idproductwip+'_'+i).val(0);
            }

            n_max = Math.ceil(Math.max.apply(Math,$("input[name='nquantity_tmp_"+idproductwip+"[]']").map(function(){return parseFloat($(this).val());}).get()));
            $('#n_qty_kekurangan_'+idproductwip+'_'+i).val(n_max * n_qty_penyusun - n_qty_retur );
        }

        console.log(n_max + " " + n_qty_penyusun + " " + n_qty_retur + " " + $("input[name='nquantity_tmp_"+idproductwip+"[]']").length );
    }

    /**
     * Hapus Detail Item
     */

    function hapusdetail(x) {
        $("#tabledatax tbody").each(function() {
            $("tr.del" + x).remove();
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
    });

    //new script
    function number() {
        // if (($('#ibagian').val() == $('#ibagianold').val())) {
        //     $('#isj').val($('#isjold').val());
        // } else {
            
        // }

        $.ajax({
                type: "post",
                data: {
                    'tgl': $('#dretur').val(),
                    'ibagian': $('#ibagian').val(),
                },
                url: '<?= base_url($folder . '/cform/number'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#iretur').val(data);
                },
                error: function() {
                    swal('Error :)');
                }
            });
    }

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#iretur").attr("readonly", false);
        } else {
            $("#iretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("#iretur").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'kodeold': $('#ireturold').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    function validasi(id){
  
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td nquantityset").each(function() {
                    if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }

    }
</script>