<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Tgl Terdaftar</label>
                        <label class="col-md-4">Kode Barang</label>
                        <label class="col-md-4">Nama Barang</label>
                        <div class="col-sm-4">
                            <input type="text" id="dproductregister" name="dproductregister" class="form-control input-sm date" value="<?= $data->d_daftar; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="id" id="id" class="form-control input-sm" value="<?= $data->id; ?>">
                            <input type="text" name="iproductbase" id="iproductbase" class="form-control input-sm" value="<?= $data->i_product_base; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="eproductbasename" id="eproductbasename" class="form-control input-sm" placeholder="Nama Barang Jadi" required="" value="<?= $data->e_product_basename; ?>">
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="form-group row">
                        <label class="col-md-4">Tambahan Barang Jadi Pelengkap (Optional)</label>
                        <label class="col-md-4">Group Barang</label>
                        <label class="col-md-4">Divisi</label>
                        <div class="col-sm-4">
                            <select name="id_product_base" id="id_product_base" class="form-control input-sm select2">
                                <option value="<?= $data->id_product_base_tambahan; ?>"><?= $data->e_product_tambahan; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="igroupbrg" id="igroupbrg" class="form-control input-sm select2">
                                <option value="<?= $data->i_kode_group_barang; ?>"><?= $data->e_nama_group_barang; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="idivisi" id="idivisi" class="form-control input-sm select2" onchange="getkategori();">
                                <option value="<?= $data->id_divisi; ?>"><?= $data->e_nama_divisi; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Kategori Barang</label>
                        <label class="col-md-4">Sub Kategori Barang</label>
                        <label class="col-md-4">Kategori Penjualan</label>
                        <div class="col-sm-4">
                            <select name="ikelompok" id="ikelompok" class="form-control input-sm select2" onchange="get(this.value);">
                                <option value="<?= $data->i_kode_kelompok; ?>"><?= $data->e_nama_kelompok; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ijenisbrg" id="ijenisbrg" class="form-control input-sm select2">
                                <option value="<?= $data->i_type_code; ?>"><?= $data->e_type_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ikelasbrg" id="ikelasbrg" required class="form-control input-sm input-sm select2" data-placeholder="Pilih Kelas Barang">
                                <option value=''></option>
                                <?php if ($class) {
                                    foreach ($class as $key) { ?>
                                        <option value="<?= $key->id; ?>" <?php if ($key->id == $data->id_class_product) { ?> selected <?php } ?>><?= $key->e_class_name; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="form-group row">
                        <label class="col-md-4">Satuan Barang</label>
                        <label class="col-md-4">Status Produksi</label>
                        <label class="col-md-4">Nama Motif/Warna</label>
                        <div class="col-sm-4">
                            <select name="isatuan" id="isatuan" class="form-control input-sm select2">
                                <option value="<?= $data->i_satuan_code; ?>"><?= $data->e_satuan_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="istatusproduksi" id="istatusproduksi" class="form-control input-sm select2">
                                <option value="<?= $data->i_status_produksi; ?>"><?= $data->e_status_produksi; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="iwarna_old" id="iwarna_old" value="<?= $data->i_color ?>" />
                            <select name="iwarna" id="iwarna" class="form-control input-sm select2">
                                <option value="<?= $data->i_color; ?>"><?= $data->e_color_name; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Brand</label>
                        <label class="col-md-4">Series</label>
                        <label class="col-md-4">Tgl SPH</label>
                        <div class="col-sm-4">
                            <select name="ibrand" id="ibrand" class="form-control input-sm select2">
                                <option value="<?= $data->i_brand; ?>"><?= $data->e_brand_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="istyle" readonly id="istyle" class="form-control input-sm select2">
                                <option value="<?= $data->i_style; ?>"><?= $data->e_style_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dtanggalpenawaran" class="form-control input-sm date" value="<?= $data->d_surat_penawaran; ?>" readonly>
                        </div>
                    </div>
                    <!-- <hr> -->
                    <div class="form-group row">
                        <label class="col-md-4">Tgl Launching SPH</label>
                        <label class="col-md-4">Tgl STP</label>
                        <label class="col-md-4">Surat Penawaran</label>
                        <div class="col-sm-4">
                            <input type="text" name="dlaunching" class="form-control input-sm date" value="<?= $data->d_launch; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dstp" class="form-control input-sm date" value="<?= $data->d_stps; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="esuratpenawaran" class="form-control input-sm" value="<?= $data->e_surat_penawaran; ?>" placeholder="Nomor Surat Penawaran">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">HJP</label>
                        <label class="col-md-4">Harga Grosir</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-4">
                            <input type="text" name="ehjp" id="ehjp" autocomplete="off" class="form-control input-sm" onkeyup="angkahungkul(this);reformat(this);" value="<?= number_format($data->v_unitprice); ?>" placeholder="0">
                        </div>
                        <div class="col-sm-4">
                            <input type="input" name="fhargagrosir" autocomplete="off" class="form-control input-sm" onkeyup="angkahungkul(this);reformat(this);" value="<?= number_format($data->v_grosir); ?>" placeholder="0">
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="edeskripsi" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm" onclick="return validasi();"> <i class="fa fa-save fa-lg mr-2"></i>Update</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick='show("<?= $folder; ?>/cform","#main")'><i class="fa fa-lg fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar('.date');

        $('#idivisi').select2({
            placeholder: 'Pilih Divisi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getdivisi'); ?>',
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

        $('#igroupbrg').select2({
            placeholder: 'Pilih Group Barang',
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
        });

        $('#ikelompok').select2({
            placeholder: 'Pilih Kelompok Barang',
            allowClear: true,
            ajax: {
                type: "POST",
                url: '<?= base_url($folder . '/cform/getkelompokedit'); ?>',
                dataType: 'json',
                data: {
                    igroupbrg: $("#igroupbrg").val(),
                    idivisi: $("#idivisi").val(),
                },
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#ijenisbrg').select2({
            placeholder: 'Pilih Jenis Barang',
            allowClear: true,
            ajax: {
                type: "POST",
                url: '<?= base_url($folder . '/cform/getjenisedit'); ?>',
                dataType: 'json',
                data: {
                    ikelompok: $("#ikelompok").val(),
                },
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#id_product_base').select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_product/'); ?>',
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
        });

        $('#ibrand').select2({
            placeholder: 'Pilih Brand',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getbrand'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function() {
            $('#istyle').val('');
            $('#istyle').html('');
        });

        $('#istyle').select2({
            placeholder: 'Pilih Series Barang',
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/getstyle/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        ibrand: $('#ibrand').val(),
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

        $('#iproductwip').select2({
            placeholder: 'Pilih Kode Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getbarangwip'); ?>',
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

        $('#iwarna').select2({
            placeholder: 'Pilih Warna/Motif',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getwarnamotif'); ?>',
                dataType: 'json',
                data: function(params) {
                    var query = {
                        q: params.term,
                        i_product_base: $('#iproductbase').val(),
                    }
                    return query;
                },
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
                url: '<?= base_url($folder . '/cform/getsatuanbarang'); ?>',
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

        $('#istatusproduksi').select2({
            placeholder: 'Pilih Status Produksi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getstatusproduksi'); ?>',
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
    });

    function checklength(el) {
        if (el.value.length != 7) {
            swal("Kode Harus 7 Karakter");
        }
    }

    function angka(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function huruf(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode > 32)
            return false;
        return true;
    }

    function checklength(el) {
        if (el.value.length != 7) {
            swal("Kode Harus 7 Karakter");
        }
    }

    function getkategori() {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getkelompok'); ?>",
            data: {
                igroupbrg: $("#igroupbrg").val(),
                idivisi: $("#idivisi").val(),
            },
            dataType: 'json',
            success: function(data) {
                $("#ikelompok").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                }
            },

            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function get(ikelompok) {
        $("#ijenisbrg").attr("disabled", false);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getjenis'); ?>",
            data: "ikelompok=" + ikelompok,
            dataType: 'json',
            success: function(data) {
                $("#ijenisbrg").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);

                } else {
                    $("#submit").attr("disabled", false);
                }
            },

            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function formatSelection(val) {
        return val.id;
    }

    function kodebarang(iproductbase) {
        $.ajax({
            type: "post",
            data: {
                'iproductbase': iproductbase
            },
            url: '<?= base_url($folder . '/cform/getkodebarang'); ?>',
            dataType: "json",
            success: function(data) {
                $('#eproductbasename').val(data[0].e_product_wipname);
                $('#productmotif').val(data[0].i_product_wip);
            },
            error: function() {
                alert('Error :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function validasi() {
        var igroupbrg = $('#igroupbrg').val();
        var ikelompok = $('#ikelompok').val();
        var ijenisbrg = $('#ijenisbrg').val();
        var ikodebrg = $('#ikodebrg').val();
        var enamabrg = $('#enamabrg').val();
        var isatuan = $('#isatuan').val();
        var iwarna = $('#iwarna').val();

        if (igroupbrg == '' || igroupbrg == null) {
            swal('Group Barang Belum dipilih');
            return false;
        } else if (ikelompok == '' || ikelompok == null) {
            swal('Kategori Barang Belum dipilih');
            return false;
        } else if (ijenisbrg == '' || ijenisbrg == null) {
            swal('Jenis Barang Belum dipilih');
            return false;
        } else if (iproductbase == '' || iproductbase == null) {
            swal('Kode Barang Belum dipilih');
            return false;
        } else if (eproductbasename == '') {
            swal('Nama Barang Belum diisi');
            return false;
        } else if (isatuan == '' || isatuan == null) {
            swal('Jenis Satuan Belum dipilih');
            return false;
        } else if (iwarna == '' || iwarna == null) {
            swal('Warna/Motif Belum dipilih');
            return false;
        } else {
            return true;
        }
    }
</script>