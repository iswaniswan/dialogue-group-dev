<!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
<form class="form-horizontal">
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list mr-2"></i>List <?= $title; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12 row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Tanggal Promo</label>
                                <input type="text" readonly id= "d_promo" name="d_promo" class="form-control input-sm date" value="<?= date('d-m-Y');?>">
                                <input id="ipromo" name="ipromo" type="hidden">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Tanggal Berlaku Dari</label>
                                <input type="text" required readonly id="d_promo_start" placeholder="Berlaku Dari Tanggal" name="d_promo_start" class="form-control input-sm date" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Tanggal Berlaku Sampai</label>
                                <input type="text" required readonly id="d_promo_finish" placeholder="Berlaku Sampai Tanggal" name="d_promo_finish" class="form-control input-sm date" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Nama Promo</label>
                                <input type="text" id="e_promo_name" name="e_promo_name" class="form-control input-sm text-capitalize" placeholder="Keterangan Promo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Jenis Promo</label>
                                <select name="id_promo_type" id="id_promo_type" required class="form-control select2" data-placeholder="Pilih Tipe Promo">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                        <div class="col-md-12">
                                <label>Kelompok Harga</label>
                                <select name="id_harga" id="id_harga" required class="form-control select2" data-placeholder="Pilih Harga">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="f_all_product" name="f_all_product" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Product</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="f_all_customer" name="f_all_customer" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Pelanggan</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="f_all_area" name="f_all_area" class="custom-control-input" value="on" checked>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Semua Area</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Discount 1 (%)</label>
                                <input type="number" readonly id="n_promo_discount1" name="n_promo_discount1" class="form-control input-sm" placeholder="Discount Persen" value="0" min="0" max="100" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Discount 2 (%)</label>
                                <input type="number" readonly id="n_promo_discount2" name="n_promo_discount2" class="form-control input-sm" placeholder="Discount Persen" value="0" min="0" max="100" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12 row">
                    <div class="col-md-12">
                        <button type="button" id="submit" class="btn mr-2 btn-success btn-rounded btn-sm"> <i class="fa fa-save mr-2"></i>Simpan</button>
                        <button type="button" id="addrowproduct" class="btn mr-2 btn-info btn-rounded btn-sm"><i class="fa fa-plus mr-2"></i>Product</button>
                        <button type="button" id="addrowcustomer" class="btn mr-2 btn-warning btn-rounded btn-sm"><i class="fa fa-plus mr-2"></i>Pelanggan</button>
                        <button hidden="true" type="button" id="addrowarea" class="btn mr-2 btn-primary btn-rounded btn-sm"><i class="fa fa-plus mr-2"></i>Area</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box tableproduct">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tableproduct" class="table tabledatax color-table info-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="40%;">Barang</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Minimum Order</th>
                        <th class="text-center" width="3%">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <input type="hidden" id="jml_product" name="jml_product" value="0">
            </table>
        </div>
    </div>
</div>
<div class="white-box tablecustomer">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Customer</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tablecustomer" class="table tabledatax color-table warning-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="47%;">Nama Pelanggan</th>
                        <th class="text-center" width="47%;">Alamat Pelanggan</th>
                        <th class="text-center" width="3%">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <input type="hidden" id="jml_customer" name="jml_customer" value="0">
            </table>
        </div>
    </div>
</div>
<div class="white-box tablearea" hidden="true">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Area</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tablearea" class="table tabledatax color-table primary-table table-bordered class" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="96%;">Nama Area</th>
                        <th class="text-center" width="3%">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <input type="hidden" id="jml_area" name="jml_area" value="0">
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $("#id_promo_type").select2({
            dropdownAutoWidth: true,
            width: "100%",
            allowClear: true,
            ajax: {
                url: "<?= base_url($folder.'/cform/get_type/'); ?>",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    };
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        }).change(function(event) {
            $.ajax({
                type: "post",
                data: {
                    'id_promo_type': $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/get_valid/'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#n_promo_discount1').val(0);
                    $('#n_promo_discount2').val(0);
                    if (data['valid'].length > 0) {
                        let read = data['valid'][0]['n_valid'];
                        if (read == 2) {
                            $('#n_promo_discount1').attr('readonly', false);
                            $('#n_promo_discount2').attr('readonly', false);
                        } else if (read == 1) {
                            $('#n_promo_discount1').attr('readonly', false);
                            $('#n_promo_discount2').attr('readonly', true);
                        } else {
                            $('#n_promo_discount1').attr('readonly', true);
                            $('#n_promo_discount2').attr('readonly', true);
                        }
                    } else {
                        $('#n_promo_discount1').attr('readonly', true);
                        $('#n_promo_discount2').attr('readonly', true);
                    }
                },
            });
        });

        $("#id_harga").select2({
            dropdownAutoWidth: true,
            width: "100%",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/get_group/'); ?>',
                dataType: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    };
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });

        /* $('#ipricegroup').select2({
            placeholder: 'Cari Berdasarkan Kode',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getgroup/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var ipromotype = $('#ipromotype').val();
                    var query = {
                        q: params.term,
                        ipromotype: ipromotype
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        }); */
        
        $('#f_all_product').click(function (event) {
            if (this.checked) {
                $('.tableproduct').attr('hidden', true);
                $('#addrowproduct').attr('hidden', true);
            } else {             
                $('.tableproduct').attr('hidden', false);
                $('#addrowproduct').attr('hidden', false);
            }
            clear_tabel_product();
        });

        var Product = $(function() {
            var i = $("#jml_product").val();
            $("#addrowproduct").on("click", function() {
                if ($('#i_price_group').val() != '') {
                    i++;
                    var no = $("#tableproduct tbody tr").length + 1;
                    $("#jml_product").val(i);
                    var newRow = $("<tr>");
                    var cols = "";
                    cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
                    cols += `<td><select data-nourut="${i}" required class="form-control" name="i_product[]" id="i_product${i}"><option value=""></option></select></td>`;
                    cols += `<td><input type="text" readonly class="form-control input-sm text-right" id="v_unit_price${i}" name="v_unit_price[]"></td>`;
                    cols += `<td><input type="text" class="form-control input-sm text-right" onkeyup="angkahungkul(this);" id="n_quantity_min${i}" name="n_quantity_min[]" value="1" onblur=\"if(this.value==''){this.value='1';}\" onfocus=\"if(this.value=='1'){this.value='';}\"></td>`;
                    cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                    newRow.append(cols);
                    $("#tableproduct").append(newRow);
                    $("#i_product" + i).select2({
                        placeholder: 'Cari Product',
                        dropdownAutoWidth: true,
                        width: '100%',
                        allowClear: true,
                        ajax: {
                            url: '<?= base_url($folder.'/cform/product/'); ?>',
                            dataType: "json",
                            delay: 250,
                            data: function(params) {
                                var query = {
                                    q: params.term,
                                    id_harga: $("#id_harga").val(),
                                };
                                return query;
                            },
                            processResults: function(data) {
                                return {
                                    results: data,
                                };
                            },
                            cache: false,
                        },
                    }).change(function(event) {
                        var z = $(this).data("nourut");
                        var ada = false;
                        for (var x = 1; x <= $("#jml_product").val(); x++) {
                            if ($(this).val() != null) {
                                if ($(this).val() == $("#i_product" + x).val() && z != x) {
                                    swal({
                                        type: "error",
                                        title: "Maaf",
                                        text: "Data sudah ada ..",
                                        confirmButtonClass: "btn btn-danger",
                                    });
                                    ada = true;
                                    break;
                                }
                            }
                        }
                        if (ada) {
                            $(this).val("");
                            $(this).html("");
                        } else {
                            $.ajax({
                                type: "post",
                                data: {
                                    'i_product': $(this).val(),
                                    i_price_group: $("#i_price_group").val(),
                                },
                                url: "<?= base_url($folder.'/cform/get_detail_product/'); ?>",
                                dataType: "json",
                                success: function(data) {
                                    if(data['detail'].length > 0){
                                        /* $("#i_product_motif" + z).val(data['detail'][0]['i_product_motif']);
                                        $("#i_product_grade" + z).val(data['detail'][0]['i_product_grade']);
                                        $("#e_product_motifname" + z).val(data['detail'][0]['e_product_motifname']); */
                                        $("#v_unit_price" + z).val(data['detail'][0]['v_unitprice']);
                                        $("#n_quantity_min" + z).focus();
                                    }else{
                                        $("#v_unit_price" + z).val(0);
                                        $("#n_quantity_min" + z).focus();
                                    }
                                },
                            });
                        }
                    });
                } else {
                    swal("Maaf", "Data ada yang salah :(", "error");
                }
            });

            /*----------  Hapus Baris Data Saudara  ----------*/

            $("#tableproduct").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();

                $("#jml_product").val(i);
                var obj = $("#tableproduct tr:visible").find("spanx");
                $.each(obj, function(key, value) {
                    id = value.id;
                    $("#" + id).html(key + 1);
                });
            });
        });

        $('#f_all_customer').click(function (event) {
            if (this.checked) {
                $('.tablecustomer').attr('hidden', true);
                $('#addrowcustomer').attr('hidden', true);
            } else {             
                $('.tablecustomer').attr('hidden', false);
                $('#addrowcustomer').attr('hidden', false);
            }
            clear_tabel_customer();
        });

        var Customer = $(function() {
            var i = $("#jml_customer").val();
            $("#addrowcustomer").on("click", function() {
                i++;
                var no = $("#tablecustomer tbody tr").length + 1;
                $("#jml_customer").val(i);
                var newRow = $("<tr>");
                var cols = "";
                cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
                cols += `<td><select data-nourut="${i}" required class="form-control" name="i_customer[]" id="i_customer${i}"><option value=""></option></select></td>`;
                cols += `<td><input type="text" readonly class="form-control input-sm" id="e_customer_address${i}" readonly></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                newRow.append(cols);
                $("#tablecustomer").append(newRow);
                $("#i_customer" + i).select2({
                    placeholder: "Pilih Customer",
                    dropdownAutoWidth: true,
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: "<?= base_url($folder.'/cform/get_customer/'); ?>",
                        dataType: "json",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                q: params.term,
                            };
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: false,
                    },
                }).change(function(event) {
                    var z = $(this).data("nourut");
                    var ada = false;
                    for (var x = 1; x <= $("#jml_customer").val(); x++) {
                        if ($(this).val() != null) {
                            if ($(this).val() == $("#i_customer" + x).val() && z != x) {
                                swal({
                                    type: "error",
                                    title: "Maaf :(",
                                    text: "Data Tersebut Sudah Ada",
                                    confirmButtonClass: "btn btn-danger",
                                });
                                ada = true;
                                break;
                            }
                        }
                    }
                    if (ada) {
                        $(this).val("");
                        $(this).html("");
                    } else {
                        $.ajax({
                            type: "post",
                            data: {
                                'i_customer': $(this).val(),
                            },
                            url: "<?= base_url($folder.'/cform/get_detail_customer/'); ?>",
                            dataType: "json",
                            success: function(data) {
                                if(data['detail'].length > 0){
                                    $("#e_customer_address" + z).val(data['detail'][0]['e_customer_address']);
                                }else{
                                    $("#e_customer_address" + z).val(null);
                                }
                            },
                        });
                    }
                });
            });

            /*----------  Hapus Baris Data Saudara  ----------*/

            $("#tablecustomer").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();

                $("#jml_customer").val(i);
                var obj = $("#tablecustomer tr:visible").find("spanx");
                $.each(obj, function(key, value) {
                    id = value.id;
                    $("#" + id).html(key + 1);
                });
            });
        });

        $('#f_all_area').click(function (event) {
            if (this.checked) {
                $('.tablearea').attr('hidden', true);
                $('#addrowarea').attr('hidden', true);
            } else {
                $('.tablearea').attr('hidden', false);
                $('#addrowarea').attr('hidden', false);
            }
            clear_tabel_area();
        });

        var Area = $(function() {
            var i = $("#jml_area").val();
            $("#addrowarea").on("click", function() {
                i++;
                var no = $("#tablearea tbody tr").length + 1;
                $("#jml_area").val(i);
                var newRow = $("<tr>");
                var cols = "";
                cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
                cols += `<td><select data-nourut="${i}" required class="form-control" name="i_area[]" id="i_area${i}"><option value=""></option></select></td>`;
                cols += `<td class="text-center"><i title="Delete" class="fa fa-minus-circle fa-lg text-danger ibtnDel"></i></td>`;
                newRow.append(cols);
                $("#tablearea").append(newRow);
                $("#i_area" + i).select2({
                    placeholder: "Pilih Area",
                    dropdownAutoWidth: true,
                    width: '100%',
                    allowClear: true,
                    ajax: {
                        url: "<?= base_url($folder.'/cform/get_area/'); ?>",
                        dataType: "json",
                        delay: 250,
                        data: function(params) {
                            var query = {
                                q: params.term,
                            };
                            return query;
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                        cache: false,
                    },
                }).change(function(event) {
                    var z = $(this).data("nourut");
                    var ada = false;
                    for (var x = 1; x <= $("#jml_area").val(); x++) {
                        if ($(this).val() != null) {
                            if ($(this).val() == $("#i_area" + x).val() && z != x) {
                                swal({
                                    type: "error",
                                    title: "Maaf :(",
                                    text: "Data Tersebut Sudah Ada",
                                    confirmButtonClass: "btn btn-danger",
                                });
                                ada = true;
                                break;
                            }
                        }
                    }
                    if (ada) {
                        $(this).val("");
                        $(this).html("");
                    }
                });
            });

            /*----------  Hapus Baris Data Saudara  ----------*/

            $("#tablearea").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();

                $("#jml_area").val(i);
                var obj = $("#tablearea tr:visible").find("spanx");
                $.each(obj, function(key, value) {
                    id = value.id;
                    $("#" + id).html(key + 1);
                });
            });
        });

        $( "#submit" ).click(function(event) {
            ada = false;
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                let tableproduct = $("#tableproduct tbody tr").length;
                const product = $('#f_all_product').prop("checked");
                if (product === false) {
                    if (tableproduct < 1) {
                        swal({
                            type: "error",
                            title: "Maaf :(",
                            text: "Input Barang Minimal 1!",
                            confirmButtonClass: "btn btn-danger",
                        });
                        return false;
                    }

                    $("#tableproduct tbody tr").each(function() {
                        $(this).find("td select").each(function() {
                            if ($(this).val() == '' || $(this).val() == null) {
                                swal({
                                    type: "error",
                                    title: "Maaf :(",
                                    text: "Kode Barang tidak boleh kosong!",
                                    confirmButtonClass: "btn btn-danger",
                                });
                                ada = true;
                            }
                        });
                    });
                }
                let tablecustomer = $("#tablecustomer tbody tr").length;
                const customer = $('#f_all_customer').prop("checked");
                if (customer === false) {
                    if (tablecustomer < 1) {
                        swal({
                            type: "error",
                            title: "Maaf :(",
                            text: "Input Pelanggan Minimal 1!",
                            confirmButtonClass: "btn btn-danger",
                        });
                        return false;
                    }

                    $("#tablecustomer tbody tr").each(function() {
                        $(this).find("td select").each(function() {
                            if ($(this).val() == '' || $(this).val() == null) {
                                swal({
                                    type: "error",
                                    title: "Maaf :(",
                                    text: "Pelanggan tidak boleh kosong!",
                                    confirmButtonClass: "btn btn-danger",
                                });
                                ada = true;
                            }
                        });
                    });
                }
                let tablearea = $("#tablearea tbody tr").length;
                const area = $('#f_all_area').prop("checked");
                if (area === false) {
                    if (tablearea < 1) {
                        swal({
                            type: "error",
                            title: "Maaf :(",
                            text: "Input Area Minimal 1!",
                            confirmButtonClass: "btn btn-danger",
                        });
                        return false;
                    }

                    $("#tablearea tbody tr").each(function() {
                        $(this).find("td select").each(function() {
                            if ($(this).val() == '' || $(this).val() == null) {
                                swal({
                                    type: "error",
                                    title: "Maaf :(",
                                    text: "Area tidak boleh kosong!",
                                    confirmButtonClass: "btn btn-danger",
                                });
                                ada = true;
                            }
                        });
                    });
                }
                if (!ada) {
                    swal({   
                        title: "Simpan Data Ini?",   
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Simpan!",   
                        closeOnConfirm: false 
                    }, function(){
                        $.ajax({
                            type: "POST",
                            data: $( "form" ).serialize(),
                            url: '<?= base_url($folder.'/cform/simpan/'); ?>',
                            dataType: "json",
                            success: function (data) {
                                if (data.sukses==true) {                                
                                    $('#id').val(data.id);
                                    swal("Disimpan!", "No Dokumen : "+data.kode+", Berhasil Disimpan :)", "success"); 
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                }else{
                                    swal("Maaf", "Data Gagal Disimpan :(", "error");    
                                }
                            },
                            error: function () {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
                }else{
                    return false;
                }
            }
        })
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    function clear_tabel_product() {
        $('#tableproduct tbody').empty();
        $('#jml_product').val(0);
    }

    function clear_tabel_customer() {
        $('#tablecustomer tbody').empty();
        $('#jml_customer').val(0);
    }

    function clear_tabel_area() {
        $('#tablearea tbody').empty();
        $('#jml_area').val(0);
    }
</script>