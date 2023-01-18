<style type="text/css">
    .tableFixHead {
        white-space: nowrap !important;
    }
    .form-group {
        margin-bottom: 10px !important;
    }
    .table>thead>tr>th {
        padding: 6px 6px;
    }
    .dropify-wrapper {
        height: 105px !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil mr-2"></i> <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-5">Keterangan</label>

                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row) : ?>
                                        <option value="<?= $row->i_bagian; ?>">
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control select2" id="ibulan" name="ibulan">
                                <?php
                                $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

                                $jlh_bln = count($bulan);
                                for ($c = 0; $c < $jlh_bln; $c += 1) {
                                    $sel = "";
                                    $i = $c + 1;
                                    if ($i <= 9) {
                                        $i = '0' . $i;
                                    }
                                    if ($i == date('m')) $sel = "selected";
                                    echo "<option value=$i $sel> $bulan[$c] </option>";
                                } ?>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control select2" name="tahun" id="tahun"></select>
                        </div>

                        <div class="col-sm-5">
                            <input type="hidden" name="f_over_budget" value="t">
                            <textarea name="eremarkh" id="eremarkh" placeholder="Keterangan ..." class="form-control input-sm"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i class="fa fa-plus mr-2"></i>Tambah</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-3">
                            <button type="button" disabled="true" id="send" onclick="changestatus('<?= $folder; ?>',$('#kode').val(),'2');" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class=""></i>Upload Detail <a href="#" onclick="show('fcproduksi/cform','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i> List Forecast Produksi</a>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-6">Upload File (Optional)</label>
                        <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                        <div class="col-sm-12">
                            <input type="file" id="input-file-now" name="userfile" class="dropify" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                        </div>
                        <div class="col-md-6">
                            <?php $url_download_template = site_url('fcproduksi/cform/export_template_overbudget') ?>
                            <a id="href" onclick="return export_data();"
                               href="<?= $url_download_template ?>">
                                <button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>
                                    Download Template
                                </button>
                            </a>
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
        <div class="table_fixed" style="width: 100%; max-height: 600px;">
            <table id="tabledatay" class="table color-table tableFixHead success-table table-bordered" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th width="50%">Nama Barang</th>
                        <!-- <th width="10%">Warna</th> -->
                        <th class="text-center" width="5%;">Jumlah Fc</th>
                        <th>Keterangan</th>
                        <th class="text-center" width="3%" ;>Act</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-center">TOTAL</th>
                        <th class="text-right" width="3%;" id="total"><?= 0; ?></th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
</form>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        // fixedtable($('.table'));
        $(".table_fixed").freezeTable({
            'columnNum': 3,
            'scrollable': true,
        });
        var min = new Date().getFullYear() - 1,
            max = min + 2,
            select = document.getElementById('tahun');

        for (var i = min; i <= max; i++) {
            var opt = document.createElement('option');
            if (i == new Date().getFullYear()) {
                opt.selected = true;
            }
            opt.value = i;
            opt.innerHTML = i;
            select.appendChild(opt);
        }
        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct' + i).select2({
                placeholder: 'Cari Kode / Nama Barang',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/barang/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            ibagian: $('#ibagian').val(),
                            ddocument: $('#ddocument').val(),
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
            }).change(function(event) {
                /**
                 * Cek Barang Sudah Ada
                 * Get Harga Barang
                 */
                var kode = $(this).val().split("|");

                var z = $(this).data('nourut');
                var ada = true;
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ($(this).val() != null) {
                        if (($(this).val() == $('#idproduct' + x).val()) && (z != x)) {
                            swal("Kode barang yang dipilih sudah ada !!!!!");
                            ada = false;
                            break;
                        } else {
                            $('#e_satuan' + z).val(kode[1]);
                        }
                    }
                }
                if (!ada) {
                    $(this).val('');
                    $(this).html('');
                } else {
                    $('#nquantity' + z).focus();
                }
            });
        }

        $("#send").click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });
    });

    $("#submit").click(function(event) {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Kode barang tidak boleh kosong!');
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
    })

    /**
     * After Submit
     */

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        i++;
        $("#jml").val(i);
        var no = $('#tabledatay tbody tr').length + 1;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
        // cols += `<td><input type="text" id="e_satuan${i}" class="form-control input-sm inputitem" autocomplete="off" name="e_satuan${i}" readonly></td>`;
        cols += `<td class="text-center"><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\"if(this.value==''){this.value='0';}\" onfocus=\"if(this.value=='0'){this.value='';}\" value="0" onkeyup="angkahungkul(this); sumo();"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger text-center"><i class="ti-close"></i></button></td></tr>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
            // allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/barang/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
                        ddocument: $('#ddocument').val(),
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
        }).change(function(event) {
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var kode = $(this).val().split("|");

            var z = $(this).data('nourut');
            var ada = true;
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if (($(this).val() == $('#idproduct' + x).val()) && (z != x)) {
                        swal("Kode barang yang dipilih sudah ada !!!!!");
                        ada = false;
                        break;
                    } else {
                        $('#e_satuan' + z).val(kode[1]);
                    }
                }
            }
            if (!ada) {
                $(this).val('');
                $(this).html('');
            } else {
                $('#nquantity' + z).focus();
            }
        });
    });



    function sumo() {
        // var qty = $("#nquantity"+id).val();
        var jml = $("#jml").val();
        var sumqty = 0;
        var qty = 0;
        for (n = 1; n <= jml; n++) {
            // alert($("#nquantity" + n).val());
            if (typeof $("#nquantity" + n).val() !== "undefined") {
                if ($("#nquantity" + n).val() !== '') {
                    qty = parseFloat($("#nquantity" + n).val());
                } else {
                    qty = 0;
                }
            } else {
                qty = 0;
            }
            sumqty += qty
        }
        $("#total").html(sumqty);
    }
    /**
     * Hapus Detail Item
     */

    $("#tabledatay").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();
        $('#jml').val(i);
        var obj = $('#tabledatay tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
        sumo();
    });

    /** overbudget upload */
    $('.dropify').dropify();
    $('#upload').on('click', function() {
        const inputFile = $('input[type=file]')[0].files[0];
        const url = "<?= base_url($folder . '/cform/read_data_from_excel'); ?>";
        let formData = new FormData();
        formData.append('file', inputFile);
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            success: function(data) {
                const result = JSON.parse(data);
                if (result.length > 0) {
                    autoInputDetail(result);
                }
                console.log(result);
            },

        })
    })

    const autoInputDetail = (data) => {
        clear_table();

        /** i diambil dari global variable */
        data.map(({id_product, qty, text, keterangan}) => {
            $('#addrow').trigger('click');

            let row = i;
            setTimeout(() => {
                let params = [row, id_product, text];
                createSelect2Record(params);

                params = [row, qty];
                createQtyRecord(params);

                params = [row, keterangan];
                createKeteranganRecord(params);

                sumo();
            }, 200);
        })
    };

    const createSelect2Record = (params) => {
        const [row, id_product, text] = params;
        const option =  $("<option selected='selected'></option>").val(id_product).text(text);
        $(`#idproduct${row}`).append(option).trigger('change');
    }

    const createQtyRecord = (params) => {
        const [row, qty] = params;
        $(`#nquantity${row}`).val(qty);
    }

    const createKeteranganRecord = (params) => {
        const [row, keterangan] = params;
        $(`#eremark${row}`).val(keterangan);
    }

    const clear_table = () => {
        $("#tabledatay tbody tr").remove();
        $("#jml").val(0);
    }

</script>