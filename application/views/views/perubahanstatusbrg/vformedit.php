<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-md-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian; ?>">
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id; ?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document; ?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document; ?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number; ?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                        </div>

                        <div class="col-md-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-md-3">
                            <textarea id="eremarkh" name="eremarkh" placeholder="Keterangan" class="form-control input-sm"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') { ?>
                            <div class="col-sm-3">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return konfirm();"><i class="fa fa-save mr-2 fa-lg"></i>Update</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2 fa-lg"></i>Send</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash mr-2 fa-lg"></i>Delete</button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                            </div>
                        <?php } elseif ($data->i_status == '2') { ?>
                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh mr-2 fa-lg"></i>Cancel</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2 fa-lg"></i>Kembali</button>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <div class="form-group row">
                <label class="col-md-5">Kategori Barang</label>
                <label class="col-md-6">Jenis Barang</label>
                <label class="col-md-1"></label>
                <div class="col-sm-5">
                    <select class="form-control select2" name="ikategori" id="ikategori">
                        <option value="all">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select class="form-control select2" name="ijenis" id="ijenis">
                        <option value="all">Semua Jenis</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th style="width: 45%;">Nama Barang</th>
                        <th class="text-right" style="width: 10%;">Jumlah</th>
                        <th class="text-center" style="width: 10%;">Grade Awal</th>
                        <th class="text-center" style="width: 10%;">Grade Akhir</th>
                        <th>Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($datadetail) {
                        foreach ($datadetail as $row) { ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td>
                                    <select id="imaterial<?= $i; ?>" class="form-control select2" name="imaterial[]" onchange="getmaterial(<?= $i; ?>);">
                                        <option value="<?= $row->id_product_base; ?>"><?= $row->i_product_base . " - " . $row->e_product_basename; ?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity; ?>" id="nquantity<?= $i; ?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);">
                                </td>
                                <td>
                                    <select id="i_grade_awal<?= $i; ?>" class="form-control select2" name="i_grade_awal[]" onchange="gantigrade(this.value, <?= $i; ?>);">
                                        <option value="A" <? if ($row->i_grade_awal == 'A') { ?> selected <?php } ?>>A</option>
                                        <option value="B" <? if ($row->i_grade_awal == 'B') { ?> selected <?php } ?>>B</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="i_grade_akhir<?= $i; ?>" class="form-control input-sm" name="i_grade_akhir[]" value="<?= $row->i_grade_akhir; ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" id="eremark<?= $i; ?>" class="form-control input-sm" value="<?= $row->e_remark; ?>" name="eremark[]">
                                </td>
                                <td class="text-center">
                                    <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td>
                            </tr>
                    <?php $i++;
                        }
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
        showCalendar('.date');
        //  number();

        for (let i = 1; i <= $('#jml').val(); i++) {
            $('#imaterial' + i).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            ikategori: $('#ikategori').val(),
                            ijenis: $('#ijenis').val(),
                            ibagian: $('#ibagian').val(),
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        }

        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ikategori: $('#ikategori').val(),
                        ibagian: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
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

    var i = $("#jml").val();;
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum' + i + '">' + no + '</spanx></td>';
        cols += '<td><select id="imaterial' + i + '" class="form-control select2" name="imaterial[]" onchange="getmaterial(' + i + ');"></td>';
        cols += '<td><input type="text" id="nquantity' + i + '" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td><select id="i_grade_awal' + i + '" class="form-control select2" name="i_grade_awal[]" onchange="gantigrade(this.value, ' + i + ');"><option value="A">A</option><option value="B">B</option></td>';
        cols += '<td><input type="text" id="i_grade_akhir' + i + '" class="form-control input-sm" name="i_grade_akhir[]" value="B" readonly/></td>';
        cols += '<td><input type="text" id="eremark' + i + '" class="form-control input-sm" name="eremark[]"/></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#i_grade_awal' + i).select2();
        $('#imaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ikategori: $('#ikategori').val(),
                        ijenis: $('#ijenis').val(),
                        ibagian: $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function getmaterial(id) {
        $.ajax({
            type: "post",
            data: {
                'imaterial': $('#imaterial' + id).val(),
            },
            url: '<?= base_url($folder . '/cform/getmaterial'); ?>',
            dataType: "json",
            success: function(data) {
                ada = false;
                for (var i = 1; i <= $('#jml').val(); i++) {
                    var ima = (data[0].i_product_base);
                    if (($('#imaterial' + id).val() == $('#imaterial' + i).val()) && (i != id)) {
                        swal("kode : " + ima + " sudah ada !!!!!");
                        ada = true;
                        break;
                    } else {
                        ada = false;
                    }
                }
                if (!ada) {

                } else {
                    $('#imaterial' + id).html('');
                    $('#imaterial' + id).val('');
                }
            },
            error: function() {
                swal('Ada kesalahan :(');
            }
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        $('#jml').val(i);
        del();
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function gantigrade(val, i) {
        //alert(val);
        if (val == 'A') {
            $('#i_grade_akhir' + i).val('B');
        } else {
            $('#i_grade_akhir' + i).val('A');
        }
    }

    $('#ceklis').click(function(event) {
        if ($('#ceklis').is(':checked')) {
            $("#idocument").attr("readonly", false);
        } else {
            $("#idocument").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("#idocument").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
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
                $(this).find("td .inputitem").each(function() {
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