<style type="text/css">
    .font {
        font-size: 12px;
        /* background-color: #e1f1e4; */
    }

    #tabledatalistx td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
    }
</style>
<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-lg fa-plus mr-2"></i><?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list fa-lg mr-2"></i><?= $title_list; ?> </a>
                </div>
                <div class="panel-body table-responsive">
                    <div id="pesan"></div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Perkiraan Kembali</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                    <?php if ($bagian) {
                                        foreach ($bagian->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number; ?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="note">Format : (<?= $number; ?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                                <input type="hidden" id="id" nama="id" value="">
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="destimate" required="" id="destimate" class="form-control input-sm tgl" value="<?= date('d-m-Y'); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Penerima</label>
                            <label class="col-md-3">Tipe Makloon</label>
                            <label class="col-md-3">Partner</label>
                            <label class="col-md-3">Keterangan</label>
                            <div class="col-sm-3">
                                <select name="ibagianreceive" required="" id="ibagianreceive" class="form-control select2">
                                    <?php if ($bagian_receive) {
                                        foreach ($bagian_receive->result() as $key) { ?>
                                            <option value="<?= trim($key->i_bagian); ?>"><?= $key->e_bagian_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="idtype" required="" id="idtype" class="form-control select2" data-placeholder="Pilih Tipe Makloon">
                                    <option value=""></option>
                                    <?php if ($type) {
                                        foreach ($type->result() as $key) { ?>
                                            <option value="<?= $key->id; ?>"><?= $key->e_name; ?></option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="idpartner" id="idpartner" required="" class="form-control input-sm select2" data-placeholder="Pilih Partner"></select>
                            </div>
                            <div class="col-sm-3">
                                <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm mr-2"><i class="fa fa-lg fa-save mr-2"></i>Simpan</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main')"> <i class="fa fa-lg fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" disabled="true" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-lg fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                        </div>
                        <!-- <span class="notekode"><b>Note : Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!</b></span> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="white-box" id="detail">
        <div class="form-group row">
            <div class="col-sm-2">
                <h3 class="box-title m-b-0 ml-1">Detail Barang</h3>
            </div>
            <div class="col-sm-2 text-right"><span class="text-right mr-1"><?= $this->doc_qe; ?></span></div>
            <div class="col-sm-2 ml-auto">
                <button type="button" class="btn btn-info btn-block btn-sm mr-2" id="addrowlist"> <i class="fa fa-plus fa-lg mr-2"></i>Item</button>
            </div>
            <!-- <div class="col-sm-1"></div> -->
        </div>
        <div class="table-responsive">
            <table id="tabledatalistx" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 25%;">Product dan Material Keluar</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center" style="width: 25%;">Material Masuk</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center" style="width: 20%;">Keterangan</th>
                        <th colspan="2" class="text-center" style="width: 7%;">Act</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
        </div>
    </div>
    <input type="hidden" name="jml" id="jml" value="0">
    <input type="hidden" name="jmlitem" id="jmlitem" value="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    /*----------  LOAD SAAT DOKUMEN READY  ----------*/

    $(document).ready(function() {
        /*----------  Load Form Validation  ----------*/
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        $('#isj').mask('SS-0000-000000S');
        number();
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date', null, 0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl', 0);

        $('#idtype').change(function(event) {
            $('#idpartner').val('');
            $('#idpartner').html('');
            $('#idreff').val('');
            $('#idreff').html('');
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#idpartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        idtype: $('#idtype').val(),
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

        /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/

        $('#ceklis').click(function(event) {
            if ($('#ceklis').is(':checked')) {
                $("#isj").attr("readonly", false);
            } else {
                $("#isj").attr("readonly", true);
                $("#ada").attr("hidden", true);
                number();
            }
        });

        /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/

        $("#isj").keyup(function() {
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
                    swal('Error :(');
                }
            });
        });

        /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/

        $('#send').click(function(event) {
            statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        });

        /*----------  UPDATE NO DOKUMEN SAAT TANGGAL DOKUMEN DAN BAGIAN PEMBUAT DIRUBAH  ----------*/

        $('#ddocument, #ibagian').change(function(event) {
            number();
        });



        $("#idtype, #idpartner, #dfrom, #dto").change(function(event) {
            clear_table();
        });

        /**
         * Tambah Item Khusus Makloon
         */

        var iter = $('#jml').val();
        $("#addrowlist").on("click", function() {
            iter++;
            $("#jml").val(iter);
            var no = $('#tabledatalistx .tr').length;
            var newRow = $('<tr class="table-warning xx' + iter + '">');
            var cols = "";
            var col = "";
            cols += `<td class="text-center"><spanlistx id="snum${iter}"><b>${(no+1)}</b></spanlistx></td>`;
            cols += `<td><select data-xx="${iter}" id="id_wip${iter}" class="form-control input-sm" name="id_wip${iter}" ></select></td>`;
            cols += `<td><input type="hidden" name="id_keluar${iter}" id="id_keluar${iter}" value="${iter}" ></td>`;
            cols += `<td></td>`;
            cols += `<td></td>`;
            cols += `<td><input type="hidden" class="form-control input-sm" name="eremark${iter}" id="eremark${iter}" placeholder="Isi keterangan jika ada!"/></td>`;
            // cols += `<td class="text-center"><i data-urut="${iter}" title="Tambah List" id="addlist${iter}" class="fa fa-plus fa-lg text-info"></i></td>`;
            cols += `<td class="text-center"><button data-urut="${iter}" type="button" id="addlist${iter}" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i class="ti-plus"></i></button></td>`;
            cols += `<td class="text-center"><button type="button" onclick="hapusdetail(${iter});" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
            newRow.append(cols);
            // $("#tabledatalistx").append(newRow);
            $("#tabledatalistx tr:first").after(newRow);
            var newRow1 = $('<tr class="tr table-info del' + iter + '" id="tr' + iter + '"><td class="text-center"><a href="#" onclick="toge(' + iter + '); return false;" class="toggler' + iter + '" data-icon-name="fa-eye" data-prod-cat="eye_' + iter + '"><i class="fa fa-eye fa-lg text-success"></i></a></td><td colspan="7" class="font"><b>LIST BARANG MATERIAL</b></td></tr>');
            // $("#tabledatalistx").append(newRow1);
            // $("#tabledatalistx tr:last").after(newRow1);
            $(newRow1).insertAfter("#tabledatalistx .xx" + iter);
            restart();
            $('#id_wip' + iter).select2({
                placeholder: 'Cari Kode / Nama WIP',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_wip/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
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
                // var z = $(this).data('xx');
                // var ada = true;
                // for (var x = 1; x <= $('#jml').val(); x++) {
                //     if ($(this).val() != null) {
                //         if ((($(this).val()) == $('#id_wip' + x).val()) && (z != x)) {
                //             swal("kode barang tersebut sudah ada !!!!!");
                //             ada = false;
                //             break;
                //         }
                //     }
                //     // $('#idmaterialhead' + z + x).val($('#id_wip' + z).val());
                // }
                // if (!ada) {
                //     $(this).val('');
                //     $(this).html('');
                // }
            });
            $('#idmaterial' + iter).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/product_material/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
                            dfrom: $('#dfrom').val(),
                            dto: $('#dto').val(),
                            id_wip: $('#id_wip'+$(this).data('nourut')).val(),
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
                // var z = $(this).data('nourut');
                // var ada = true;
                // for (var x = 1; x <= $('#jml').val(); x++) {
                //     if ($(this).val() != null) {
                //         if ((($(this).val()) == $('#idmaterial' + x).val()) && (z != x)) {
                //             swal("kode barang tersebut sudah ada !!!!!");
                //             ada = false;
                //             break;
                //         }
                //     }
                //     $('#idmaterialhead' + z + x).val($('#idmaterial' + z).val());
                // }
                // if (!ada) {
                //     $(this).val('');
                //     $(this).html('');
                // }
                /* else {
                                   $.ajax({
                                       type: "post",
                                       data: {
                                           'id_material': $(this).val(),
                                           'dfrom': $('#dfrom').val(),
                                           'dto': $('#dfrom').val(),
                                       },
                                       url: '<?= base_url($folder . '/cform/detail_product'); ?>',
                                       dataType: "json",
                                       success: function(data) {
                                           // console.log(data['detail'][0]['n_quantity']);
                                           $('#nquantity'+z).val(data['detail'][0]['n_quantity']);
                                       },
                                       error: function() {
                                           swal('Error :(');
                                       }
                                   });
                               } */
            });

            var nox = 0;
            $("#addlist" + iter).on("click", function() {
                let jmlitem = parseInt($('#jmlitem').val()) + 1;
                var u = $(this).data('urut');
                nox++;
                var newRow1 = $('<tr id="trdetail' + u + nox + '" class="table-success add' + u + ' del' + u + ' cat_eye_' + u + '">');
                var nomer = $('#tabledatalistx .add' + u).length;
                col += `<td class="text-right"><spanlist${u} id="snum${u}"></spanlist${u}><input type="hidden" id="idkeluarhead${u}${nox}" name="idkeluarhead${jmlitem}"><input type="hidden" id="idwip${u}${nox}" name="idwip${jmlitem}"><input type="hidden" name="itemperkeluar" value="${nox}"></td>`;
                col += `<td><select data-urutan="${u}${nox}" data-nourut="${u}${nox}" id="idmateriallist${u}${nox}" class="form-control input-sm" name="idmateriallist${jmlitem}"></select></td>`;
                col += `<td><input type="text" id="nquantitylist${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist${jmlitem}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);sumqty(this, ${u}, ${nox}, null)"></td>`;
                col += `<td><select data-nourut="${u}${nox}" id="idmateriallist2${u}${nox}" class="form-control input-sm" name="idmateriallist2${jmlitem}" ></select></td>`;
                col += `<td><input type="text" id="nquantitylist2${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2${jmlitem}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hetang(${u}${nox});hetangqtysisa(this, ${u}${nox});sumqty(this, ${u}, ${nox}, 2)"><input type="hidden" id="nquantitylist2sisa${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist2sisa${jmlitem}"></td>`;
                col += `<td><input type="text" class="form-control input-sm" name="eremarklist${jmlitem}" id="eremarklist${u}${nox}" placeholder="Isi keterangan jika ada!"/></td>`;
                col += `<td colspan="2" class="text-center"><button type="button" title="Delete" data-b = "${u}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="ti-close"></i></button></td></tr>`;
                newRow1.append(col);
                if (nox > 1) {
                    var v = nox - 1;
                    if (typeof $('#idmateriallist' + u + v).val() == 'undefined') {
                        $(newRow1).insertAfter("#tabledatalistx #tr" + u);
                    } else {
                        $(newRow1).insertAfter("#tabledatalistx #trdetail" + u + v)
                    }
                } else {
                    $(newRow1).insertAfter("#tabledatalistx #tr" + u);
                }

                $('#jmlitem').val(jmlitem);
                $('#idkeluarhead' + u + nox).val($('#id_keluar' + u).val());
                $('#idwip' + u + nox).val($('#id_wip' + u).val());
                $('#nquantityhead' + u + nox).val($('#nquantity' + u).val());

                $('#idmateriallist' + u + nox).select2({
                    placeholder: 'Cari Kode / Nama Material',
                    allowClear: true,
                    width: "100%",
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder . '/cform/product/'); ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            var query = {
                                q: params.term,
                                dfrom: $('#dfrom').val(),
                                dto: $('#dto').val(),
                                idtype: $('#idtype').val(),
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: true
                    }
                }).change(function(event) {
                    var z = $(this).data('urutan');
                    console.log(z)
                    var ada = true;
                    for (var x = 1; x <= $('#tabledatalistx .add' + u).length; x++) {
                        y = String(u) + x;
                        if ($(this).val() != null) {
                            if ((($(this).val()) == $('#idmateriallist' + u + x).val()) && (z != y)) {
                                swal("kode barang sudah ada !!!!!");
                                ada = false;
                                break;
                            }
                        }
                    }
                    if (!ada) {
                        $(this).val('');
                        $(this).html('');
                    }
                });
                $('#idmateriallist2' + u + nox).select2({
                    placeholder: 'Cari Kode / Nama Material',
                    allowClear: true,
                    width: "100%",
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder . '/cform/product/'); ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            var query = {
                                q: params.term,
                                dfrom: $('#dfrom').val(),
                                dto: $('#dto').val(),
                                idtype: $('#idtype').val(),
                            }
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: true
                    }
                }).change(function(event) {
                    var z = $(this).data('nourut');
                    var ada = true;
                    for (var x = 1; x <= $('#tabledatalistx .add' + u).length; x++) {
                        y = String(u) + x;
                        if ($(this).val() != null) {
                            if ((($(this).val()) == $('#idmateriallist2' + u + x).val()) && (z != y)) {
                                swal("kode barang sudah ada !!!!!");
                                ada = false;
                                break;
                            }
                        }
                    }
                    if (!ada) {
                        $(this).val('');
                        $(this).html('');
                    }
                });
            });

            /*  $(".toggler").click(function(e) {
                 e.preventDefault();
                 $('.cat_' + $(this).attr('data-prod-cat')).toggle();
                 console.log($(this).find('.mata'));
                 // $(this).addClass('active');

                 //Remove the icon class
                 if ($(this).find('.mata').hasClass('fa-eye-slash')) {
                     //then change back to the original one
                     $(this).find('.mata').removeClass('fa-eye-slash').addClass($(this).data('icon-name'));
                 } else {
                     //Remove the cross from all other icons
                     $('.faq-links').each(function() {
                         if ($(this).find('.mata').hasClass('fa-eye-slash')) {
                             $(this).find('.mata').removeClass('fa-eye-slash').addClass($(this).data('icon-name'));
                         }
                     });

                     $(this).find('.mata').addClass('fa-eye-slash').removeClass($(this).data('icon-name'));
                 }
             }); */

            $("#tabledatalistx").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();
                var obj = $('#tabledatalistx tr:visible').find('spanlistx');
                $.each(obj, function(key, value) {
                    id = value.id;
                    $('#' + id).html(key + 1);
                });
            });

        });

        /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
        $("#submit").click(function(event) {
            var valid = $("#cekinputan").valid();
            if (valid) {
                ada = false;
                /* if ($('#jml').val() == 0) {
                    swal('Isi item minimal 1!');
                    return false;
                } else { */
                swal({
                    title: "Simpan Data Ini?",
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Simpan!",
                    closeOnConfirm: false
                }, function() {
                    $.ajax({
                        type: "POST",
                        data: $("form").serialize(),
                        url: '<?= base_url($folder . '/cform/simpan/'); ?>',
                        dataType: "json",
                        success: function(data) {
                            if (data.sukses == true) {
                                $('#id').val(data.id);
                                swal("Sukses!", "No Dokumen : " + data.kode +
                                    ", Berhasil Disimpan :)", "success");
                                $("input").attr("disabled", true);
                                $("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("disabled", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "Data tersebut sudah ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode +
                                    ", Gagal Disimpan :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Disimpan :(", "error");
                        }
                    });
                });
                // }
            }
            return false;
        });
    });


    /*----------  CEK QTY HEADER  ----------*/

    function cekqty(i) {
        if (parseInt($('#nquantity' + i).val()) > parseInt($('#nquantitysisa' + i).val())) {
            swal('Maaf', 'Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = ' + $('#nquantitysisa' + i).val() + '!', 'error');
            $('#nquantity' + i).val($('#nquantitysisa' + i).val());
        }
    }

    /*----------  CEK QTY ITEM  ----------*/

    function cekjml(i) {
        if (parseInt($('#nqtylist' + i).val()) > parseInt($('#nqtylistsisa' + i).val())) {
            swal('Maaf', 'Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = ' + $('#nqtylistsisa' + i).val() + '!', 'error');
            $('#nqtylist' + i).val($('#nqtylistsisa' + i).val());
        }
        if (parseInt($('#nqtylist' + i).val()) <= 0) {
            swal('Maaf :(', 'Jumlah Pemenuhan List Harus Lebih Besar dari 0!', 'error');
            $('#nqtylist' + i).val($('#nqtylistsisa' + i).val());
        }
    }

    /*----------  SET VALUE DETAIL  ----------*/

    function hetang(qty, id) {
        for (var i = 0; i < $('#jml').val(); i++) {
            if (id == $("#idmaterial" + i).val()) {
                if (qty == '') {
                    qty = 0;
                }
                $('#nqty' + i).val(qty);
            }
        }
    }


    function sumqty(i, u, nox, qty) {
        let allValues = $(`.add${u} td:first-child input[name^="itemperkeluar"]`).map(function() { return +this.value; }).toArray();
        var maxValue = Math.max.apply(Math, allValues);
        if(qty) {
            if($(`#idmateriallist2${u}${nox}`).val()) {
                let totalQtyKeluar = 0;
                let totalQtyMasuk = 0;
                for(let a = 1; a<=maxValue; a++) {
                    totalQtyKeluar += parseInt($(`#nquantitylist${u}${a}`).val())
                    totalQtyMasuk += parseInt($(`#nquantitylist2${u}${a}`).val())
                }
                if(totalQtyMasuk > totalQtyKeluar) {
                    swal(`total quantity keluar: ${totalQtyKeluar} tidak boleh lebih kecil dari total quantity masuk: ${totalQtyMasuk}`);
                    $(`#nquantitylist2${u}${nox}`).val(0)
                }
            } else {
                $(`#nquantitylist2${u}${nox}`).val(0)
            }
        } else {
            if($(`#idmateriallist${u}${nox}`).val()) {
                let totalQtyKeluar = 0;
                let totalQtyMasuk = 0;
                for(let a = 1; a<=maxValue; a++) {
                    totalQtyKeluar += parseInt($(`#nquantitylist${u}${a}`).val())
                    totalQtyMasuk += parseInt($(`#nquantitylist2${u}${a}`).val())
                }
                if(totalQtyMasuk > totalQtyKeluar) {
                    swal(`total quantity keluar: ${totalQtyKeluar} tidak boleh lebih kecil dari total quantity masuk: ${totalQtyMasuk}`);
                    $(`#nquantitylist2${u}${nox}`).val(0)
                }
            } else {
                $(`#nquantitylist${u}${nox}`).val(0)
            }
        }
    }

    /*---------- HETANG QTY SISA ----------*/
    function hetangqtysisa(i, o) {
        let valueqty = $(i).val();
        console.log(valueqty);
        $(`#nquantitylist2sisa${o}`).val(valueqty);
    }

    /*----------  NOMOR DOKUMEN  ----------*/

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#isj').val(data);
            },
            error: function() {
                swal('Error :(');
            }
        });
    }

    function hetang(i) {
        for (var x = 1; x <= $('#tabledatalistx .add' + i).length; x++) {
            $('#nquantityhead' + i + x).val($('#nquantity' + i).val());
        }
    }

    /**
     * Hapus Detail Item
     */

    function hapusdetail(x) {
        $("#tabledatalistx tbody").each(function() {
            $("tr.del" + x).remove();
        });
    }

    function toge(i) {
        /* $(".toggler"+i).click(function(e) {
            e.preventDefault(); */
        $('.cat_' + $(".toggler" + i).attr('data-prod-cat')).toggle();
        // console.log($(".toggler" + i).find('i'));
        // $(".toggler"+i).addClass('active');

        //Remove the icon class
        if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
            //then change back to the original one
            $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
        } else {
            //Remove the cross from all other icons
            $('.faq-links').each(function() {
                if ($(".toggler" + i).find('i').hasClass('fa-eye-slash')) {
                    $(".toggler" + i).find('i').removeClass('fa-eye-slash').addClass($(".toggler" + i).data('icon-name'));
                }
            });

            $(".toggler" + i).find('i').addClass('fa-eye-slash').removeClass($(".toggler" + i).data('icon-name'));
        }
        // });
    }

    function restart() {
        var obj = $('#tabledatalistx tr:visible').find('spanlistx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function clear_table() {
        // $('#tabledatalistx'),remove();
        // $('#tabledatalistx > tbody').remove();

        // $("#tabledatalistx > tr:eq(1)").remove();
        $("#tabledatalistx tr:gt(0)").remove();
        // $('#tableBody').find('tr').remove();
        $('#jml').val(0);
    }
</script>