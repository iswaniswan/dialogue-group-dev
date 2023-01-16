<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Daftar</label>
                        <label class="col-md-3"><input type="checkbox" id="ceklis"
                                aria-label="Checkbox for following text input">&nbsp;&nbsp;Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label>
                        <div class="col-sm-3">
                            <input class="form-control input-sm date" type="text" name="dregister" id="dregister"
                                value="" placeholder="Tanggal Daftar" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ikodebrg" id="ikodebrg" class="form-control input-sm"
                                onkeyup="gede(this);ckkode(this.value);" value="" placeholder="Kode Barang" autocomplete="off">
                            <span id="cek" hidden="true">
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font>
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="enamabrg" class="form-control input-sm" onkeyup="gede(this)"
                                value="" placeholder="Nama Barang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Group Barang</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-6">Sub Kategori Barang</label>
                        <label class="col-md-3" hidden>Divisi</label>
                        <div class="col-sm-3">
                            <select name="igroupbrg" id="igroupbrg" class="form-control select2" required=""
                                onchange="getkelompok();">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikategori" id="ikategori" class="form-control select2" required="" disabled=""
                                onchange="getjenis();"></select>
                        </div>
                        <div class="col-sm-6">
                            <select name="ijenisbrg" id="ijenisbrg" class="form-control select2" disabled=""
                                onchange="getkodebrg();">
                            </select>
                        </div>
                        <div class="col-sm-3" hidden="">
                            <select name="idivisi" id="idivisi" class="form-control select2">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Satuan Pemakaian</label>
                        <label class="col-md-1">Panjang</label>
                        <label class="col-md-1">Lebar</label>
                        <label class="col-md-2">Tinggi</label>
                        <label class="col-md-5">Berat</label>
                        <div class="col-sm-3">
                            <select name="isatuan" id="isatuan" class="form-control select2" disabled="">
                                <option value="" disabled selected>Pilih Satuan</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="npanjang" class="form-control input-sm" maxlength="20" value="0"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="nlebar" class="form-control input-sm" maxlength="20" value="0"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ntinggi" class="form-control input-sm" maxlength="20" value="0"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="isatuanukuran" id="isatuanukuran" class="form-control input-sm"
                                value="CM" readonly>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="nberat" class="form-control input-sm" maxlength="30" value="0"
                                placeholder="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="hidden" class="form-control input-sm" value="GR" name="isatuanberat"
                                id="isatuanberat" readonly>
                            <input type="text" class="form-control input-sm" value="Gram" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Status Produksi</label>
                        <label class="col-md-3">Style Barang</label>
                        <label class="col-md-3">Brand Barang</label>
                        <label class="col-md-3">Supplier Utama</label>
                        <div class="col-sm-3">
                            <select name="istatusproduksi" id="istatusproduksi" class="form-control select2"
                                disabled="true">
                                <option value="" disabled selected>Pilih Status Produksi</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="istyle" id="istyle" class="form-control select2" disabled="true">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ibrand" id="ibrand" class="form-control select2" disabled="true">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="isupplier" id="isupplier" class="form-control select2"
                                disabled="true"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea name="edeskripsi" class="form-control" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-1"
                                onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick='show("<?= $folder; ?>/cform","#main")'><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Barang terdiri dari 7 (tujuh) kombinasi huruf dan
                            angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Barang</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan
                            nomor urutan terakhir pada Barang sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : PLA0001, PLA0002, APB0001, dst</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<div class="white-box" id="detailbis" hidden>
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Ukuran Bis-Bisan Material</h3>
        <br>
        <button type="button" id="addrowbis" class="btn btn-info btn-sm"><i
                class="fa fa-plus"></i>&nbsp;&nbsp;Item</button><br><br>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledataxbis" class="table color-table success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th valign="center" class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:8%;">Ukuran Bisbisan</th>
                        <th class="text-center" style="width:8%;">Lebar kain</th>
                        <th class="text-center" style="width:12%;">Jenis Potong</th>
                        <th class="text-center" style="width:10%;">% Hilang <br>Lebar Kain</th>
                        <th class="text-center" style="width:15%;">Lebar Kain Jadi</th>
                        <th class="text-center" style="width:10%;">Jml Roll</th>
                        <th class="text-center" style="width:12%;">% Tambah <br>Panjang Kain</th>
                        <th class="text-center" style="width:10%;">Panjang Bisbisan</th>
                        <th class="text-center" style="width:15%;">Panjang Bisbisan per 1m</th>
                        <th class="text-center" style="width:5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <input type="hidden" name="jmlbis" id="jmlbis" value="0">
        </div>
    </div>
</div>



<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Konversi Material</h3>
        <br>
        <button type="button" id="addrow" class="btn btn-info btn-sm"><i
                class="fa fa-plus"></i>&nbsp;&nbsp;Item</button><br><br>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8"
                cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:15%;">Satuan Pemakaian</th>
                        <th class="text-center" style="width:20%;">Operator</th>
                        <th class="text-center" style="width:15%;">Faktor</th>
                        <th class="text-center" style="width:20%;">Satuan Konversi</th>
                        <th class="text-center" style="width:5%;">Dipakai Pembelian</th>
                        <th class="text-center" style="width:5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="0">
        </div>
    </div>
</div>
</form>
<script>
$(document).ready(function() {
    $(".select2").select2();
    showCalendar('.date', 1835, 30);

    $('#igroupbrg').select2({
        placeholder: 'Pilih Group',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/getgroup'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    })

    $('#ikategori').select2({
        placeholder: 'Pilih Kategori Barang',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/getkategori'); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    igroup: $('#igroupbrg').val(),
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
        $('#ijenisbrg').val('');
        $('#ijenisbrg').html('');
    });

    $('#ijenisbrg').select2({
        placeholder: 'Pilih Sub Kategori Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/getjenis'); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                    ikategori: $('#ikategori').val(),
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
    })

    $('#istyle').select2({
        placeholder: 'Pilih Style Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/style'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#ibrand').select2({
        placeholder: 'Pilih Brand Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/brand'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#isatuan').select2({
        placeholder: 'Pilih Satuan Barang',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/satuan'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    }).change(function(event) {
        for (let i = 1; i <= $('#jml').val(); i++) {
            if (typeof $('#isatuanmaterial' + i).val() != 'undefined') {
                $('#isatuanmaterial' + i).val($('#isatuan').val());
                $('.xspan').text($("#isatuan option:selected").text());
            }
        }
    });

    $('#istatusproduksi').select2({
        placeholder: 'Pilih Status Produksi',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/statusproduksi'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    $('#isupplier').select2({
        placeholder: 'Pilih Supplier Utama',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder . '/cform/supplier'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // $('#idivisi').select2({
    //     placeholder: 'Pilih Divisi',
    //     allowClear: true,
    //     ajax: {
    //         url: '<?= base_url($folder . '/cform/divisi'); ?>',
    //         dataType: 'json',
    //         delay: 250,
    //         processResults: function(data) {
    //             return {
    //                 results: data
    //             };
    //         },
    //         cache: true
    //     }
    // });

    $("#ikodebrg").keyup(function() {
        var kode = $(this).val().replace(/[^a-zA-Z0-9._]/g, '');
        $('#ikodebrg').val(kode);
        if (kode.length == 7) {
            $.ajax({
                type: "post",
                data: {
                    'kode': kode,
                },
                url: '<?= base_url($folder . '/cform/cekkode'); ?>',
                dataType: "json",
                success: function(data) {
                    if (data == 1) {
                        $("#cek").attr("hidden", false);
                        $("#submit").attr("disabled", true);
                    } else {
                        $("#cek").attr("hidden", true);
                        $("#submit").attr("disabled", false);
                    }
                },
                error: function() {
                    swal('Error :)');
                }
            });
        } else {
            $("#cek").attr("hidden", true);
            $("#submit").attr("disabled", false);
        }
    });

    $("#enamabrg").keyup(function() {
        var name = $(this).val().replace(/[^\w\s]/gi, '');
        $('#enamabrg').val(name);
    });

    $(document).ready(function() {
        $("#ikodebrg").focus();
    });

    var i = $('#jml').val();
    $("#addrow").on("click", function() {
        if ($('#isatuan').val() != null) {

            $('#tabledatax').attr("hidden", false);
            i++;
            $("#jml").val(i);
            var no = $('#tabledatax tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum' + i + '">' + no + '</spanx></td>';
            cols += '<td><input type="hidden" id="isatuanmaterial' + i + '" name="isatuanmaterial' + i +
                '" class="form-control" value="' + $("#isatuan").val() + '"><span>' + $(
                    "#isatuan option:selected").text(); + '</span></td>';
            cols += '<td><select id="eperator' + i + '" class="form-control input-sm" name="eperator' +
                i +
                '" required><option value=""></option><option value="*">Kali (*)</option><option value="/">Bagi (/)</option></select></td>';
            cols += '<td><input type="text" id="faktor' + i +
                '" class="form-control text-right input-sm inputitem" autocomplete="off" name="faktor' +
                i +
                '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);" required></td>';
            cols += '<td><select data-nourut="' + i + '" id="isatuankonversi' + i +
                '" class="form-control input-sm" name="isatuankonversi' + i +
                '" required><option></option></select></td>';
            cols += '<td class="text-center"><input type="hidden" id="default' + i + '" name="default' +
                i +
                '" value="f"><input type="radio" class="cekdefault" name="cekdefault" id="cekdefault' +
                i + '" onclick="cek();" value=""></td>';
            cols +=
                '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
            newRow.append(cols);
            $("#tabledatax").append(newRow);
            $('#eperator' + i).select2({
                placeholder: 'Pilih Operator Hitungan',
                width: "100%",
            });
            $('#isatuankonversi' + i).select2({
                placeholder: 'Cari Satuan Konversi',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_satuan_konversi/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
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
                 * Cek Sudah Ada
                 */
                var ada = true;
                var z = $(this).data('nourut');
                for (var x = 1; x <= $('#jml').val(); x++) {
                    if ($(this).val() != null) {
                        if ((($(this).val()) == $('#isatuankonversi' + x).val()) && (z != x)) {
                            swal("Satuan tersebut sudah ada !!!!!");
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
        } else {
            swal("Pilih Satuan Barang Terlebih Dahulu!");
        }
    });

    $("#tabledatax").on("click", ".ibtnDel", function(event) {
        $(this).closest("tr").remove();

        //$('#jml').val(i);
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });



    var i = $('#jmlbis').val();
    $("#addrowbis").on("click", function() {
        if ($('#ikodebrg').val() != null) {


            // <th class="text-center" style="width:10%;">% Hilang <br>Lebar Kain</th>
            // <th class="text-center" style="width:15%;">Lebar Kain Jadi</th>
            // <th class="text-center" style="width:10%;">Jml Roll</th>
            // <th class="text-center" style="width:12%;">% Tambah <br>Panjang Kain</th>
            // <th class="text-center" style="width:10%;">Panjang Bis</th>
            // <th class="text-center" style="width:15%;">Panjang Bis per 1m</th>

            $('#tabledataxbis').attr("hidden", false);
            i++;
            $("#jmlbis").val(i);
            var no = $('#tabledataxbis tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum' + i + '">' + no + '</spanx></td>';

            cols += '<td><input type="text" id="n_bisbisan' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_bisbisan' +
                i +
                '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungbis(' +
                i + ');"></td>';

            cols += '<td><input type="text" id="v_lebar_kain_awal' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_lebar_kain_awal' +
                i +
                '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hitungbis(' +
                i + ');"></td>';
            cols += '<td><select required data-nourut="' + i + '" id="id_jenis_potong' + i +
                '" class="form-control input-sm" name="id_jenis_potong' + i +
                '"><option></option></select></td>';

            cols += '<td><input readonly type="text" id="n_hilang_lebar' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_hilang_lebar' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="v_lebar_kain_akhir' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_lebar_kain_akhir' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="v_jumlah_roll' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_jumlah_roll' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="n_tambah_panjang' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_tambah_panjang' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="n_panjang_bis' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_panjang_bis' +
                i + '" value="0" ></td>';

            cols += '<td><input readonly type="text" id="v_panjang_bis' + i +
                '" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_panjang_bis' +
                i + '" value="0" ></td>';


            cols +=
                '<td class="text-center"><button type="button" title="Delete" class="ibtnDelbis btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
            newRow.append(cols);
            $("#tabledataxbis").append(newRow);
            $('#id_jenis_potong' + i).select2({
                placeholder: 'Cari Jenis Potong',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder . '/cform/get_jenis_potong/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            q: params.term,
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
                $.ajax({
                    type: "post",
                    data: {
                        'id': $(this).val(),
                    },
                    url: '<?= base_url($folder . '/cform/get_jenis_potong_detail/'); ?>',
                    dataType: "json",
                    success: function(data) {
                        if (data['data'] != null) {
                            $("#n_hilang_lebar" + i).val(data['data'][
                                'n_hilang_lebar'
                            ]);
                            $("#n_tambah_panjang" + i).val(data['data'][
                                'n_tambah_panjang'
                            ]);
                            hitungbis(i);
                        }
                    },
                    error: function() {
                        swal('500 internal server error : (');
                    }
                });
            });
        } else {
            swal("Isi Kode Barang Terlebih Dahulu!");
        }
    });

    $("#tabledataxbis").on("click", ".ibtnDelbis", function(event) {
        $(this).closest("tr").remove();

        $('#jml').val(i);
        obj = $('#tabledataxbis tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    });
});

function hitungbis(i) {
    var n_bisbisan = parseFloat($('#n_bisbisan' + i).val());
    var v_lebar_kain_awal = parseFloat($('#v_lebar_kain_awal' + i).val());
    var h_lebar = $('#n_hilang_lebar' + i).val();
    var t_panjang = $('#n_tambah_panjang' + i).val();

    var res_lebarjadi = 0;

    if (h_lebar == 0) {
        res_lebarjadi = v_lebar_kain_awal;
    } else {
        res_lebarjadi = v_lebar_kain_awal - (v_lebar_kain_awal * h_lebar / 100)
    }

    var res_jmlrol = res_lebarjadi / n_bisbisan;
    var res_panjangbis = 0;

    if (t_panjang == 0) {
        res_panjangbis = 1;
    } else {
        res_panjangbis = 1 + (1 * t_panjang / 100);
    }

    var total = res_jmlrol * res_panjangbis;

    $('#v_lebar_kain_akhir' + i).val(res_lebarjadi);
    $('#v_jumlah_roll' + i).val(res_jmlrol);
    $('#n_panjang_bis' + i).val(res_panjangbis);
    $('#v_panjang_bis' + i).val(total);
}

function cek() {
    for (let i = 1; i <= $('#jml').val(); i++) {
        if (typeof $('#cekdefault' + i).val() != 'undefined') {
            if ($('#cekdefault' + i).is(':checked')) {
                $('#default' + i).val('t');
            } else {
                $('#default' + i).val('f');
            }
        }
    }
}

$('#ceklis').click(function(event) {
    var ijenisbrg = $('#ijenisbrg').val();
    if ($('#ceklis').is(':checked')) {
        $("#ikodebrg").attr("readonly", true);
        $("#ikodebrg").val('');
        $("#ikodebrg").html('');
        if (ijenisbrg != null || $ijenisbrg != '') {
            getkodebrg();
        }
    } else {
        $("#ikodebrg").attr("readonly", false);
        $("#ada").attr("hidden", true);
        $("#ikodebrg").val('');
        $("#ikodebrg").html('');
    }
});

function getkelompok() {
    $("#ikategori").attr("disabled", false);
    $("#isatuan").attr("disabled", false);
    $("#istatusproduksi").attr("disabled", false);
    $("#istyle").attr("disabled", false);
    $("#ibrand").attr("disabled", false);
    $("#isupplier").attr("disabled", false);
}

function getjenis() {
    $("#ijenisbrg").attr("disabled", false);
}

function getkodebrg() {
    var ijenisbrg = $('#ijenisbrg').val();
    if ($('#ceklis').is(':checked')) {
        $.ajax({
            type: "post",
            data: {
                'ijenisbrg': $('#ijenisbrg').val(),
            },
            url: '<?= base_url($folder . '/cform/getkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == null || data == '') {
                    var kode = ijenisbrg.substring(0, 3);
                    var kode_a = kode + "0001";
                    $('#ikodebrg').val(kode_a);
                } else {
                    $('#ikodebrg').val(data);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    }
}

function angka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function ckkode(ikode) {
    //var i = document.getElementById('i_spb_code_hidden').value;
    $.ajax({
        type: "POST",
        url: "<?= base_url($folder . '/cform/cekkode'); ?>",
        data: "ikode=" + ikode,
        success: function(data) {
            $("#confkode").html(data);
        },

        error: function(XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    })
};

function validasi() {
    var dregister = $('#dregister').val();
    var igroupbrg = $('#igroupbrg').val();
    var ikelompok = $('#ikategori').val();
    var ijenisbrg = $('#ijenisbrg').val();
    var ikodebrg = $('#ikodebrg').val();
    var enamabrg = $('#enamabrg').val();
    var isatuan = $('#isatuan').val();
    if ($('#tabledatax tr').length > 1) {
        if ($("#tabledatax input:radio:checked").length > 0) {
            if (igroupbrg == '' || igroupbrg == null) {
                swal('Group Barang Belum dipilih');
                return false;
            } else if (ikelompok == '' || ikelompok == null) {
                swal('Kategori Barang Belum dipilih');
                return false;
            } else if (ijenisbrg == '' || ijenisbrg == null) {
                swal('Jenis Barang Belum dipilih');
                return false;
            } else if (enamabrg == '') {
                swal('Nama Barang Belum diisi');
                return false;
            } else if (isatuan == '' || isatuan == null) {
                swal('Jenis Satuan Belum dipilih');
                return false;
            } else if (dregister == '' || dregister == null) {
                swal('Tanggal Daftar Belum dipilih');
                return false;
            } else {

                return true;
            }
        } else {
            swal('Maaf :(', 'Pilih salah satu untuk dipakai di pembelian', 'error');
            return false;
        }
    }

}


$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>