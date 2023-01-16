<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Tgl Terdaftar</label>
                        <!-- <label class="col-md-3">Kode Barang WIP</label> -->
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-7">Nama Barang</label>
                        <div class="col-sm-2">
                            <input type="text" id="dproductregister" name="dproductregister" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" readonly="">
                        </div>
                        <!-- <div class="col-sm-3">
                            <select name="iproductwip" id="iproductwip" class="form-control input-sm select2" onchange="kodebarang(this.value);"></select>
                        </div> -->
                        <div class="col-sm-3">
                            <input type="text" name="iproductbase" id="iproductbase" class="form-control text-uppercase input-sm" placeholder="Kode Barang Jadi" required="" value="" onkeyup="clearcode($this);" autocomplete="off">
                            <span id="cek" hidden="true">
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font>
                            </span>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" name="eproductbasename" id="eproductbasename" class="form-control text-uppercase input-sm" placeholder="Nama Barang Jadi" required="" value="" onkeyup="clearname($this);">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-12">Group Barang</label>
                        <div class="col-sm-3">
                            <select name="igroupbrg" id="igroupbrg" class="form-control input-sm select2">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Divisi</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Sub Kategori Barang</label>
                        <label class="col-md-3">Kategori Penjualan</label>
                        <div class="col-sm-3">
                            <select name="idivisi" id="idivisi" class="form-control input-sm select2" onchange="getkategori();"></select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikelompok" id="ikelompok" class="form-control input-sm select2" onchange="get(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenisbrg" id="ijenisbrg" class="form-control input-sm select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikelasbrg" id="ikelasbrg" required class="form-control input-sm select2" data-placeholder="Pilih Kelas Barang">
                                <option value=''></option>
                                <?php if ($class) {
                                    foreach ($class as $key) { ?>
                                        <option value="<?= $key->id; ?>"><?= $key->e_class_name; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-3">Satuan Barang</label>
                        <div class="col-md-9">
                            <label class="col-md-1" hidden>Panjang</label>
                            <label class="col-md-1" hidden>Lebar</label>
                            <label class="col-md-2" hidden>Tinggi</label>
                            <label class="col-md-5" hidden>Berat</label>
                        </div>
                        <div class="col-sm-3">
                            <select name="isatuan" id="isatuan" class="form-control input-sm select2">
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="npanjang" id="npanjang" class="form-control input-sm" maxlength="30" value="0" placeholder="0" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" readonly hidden>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="nlebar" id="nlebar" class="form-control input-sm" maxlength="30" value="0" placeholder="0" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" readonly hidden>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ntinggi" id="ntinggi" class="form-control input-sm" maxlength="30" value="0" placeholder="0" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" readonly hidden>
                        </div>
                        <div class="col-sm-1">
                            <input type="hidden" name="isatuanukuran" id="isatuanukuran" class="form-control input-sm" maxlength="30" value="CM" readonly="" hidden>
                            <input type="text" name="esatuanukuran" id="esatuanukuran" class="form-control input-sm" maxlength="30" value="CM" readonly="" hidden>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="nberat" id="nberat" class="form-control input-sm" maxlength="30" value="0" placeholder="0" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}" readonly hidden>
                        </div>
                        <div class="col-sm-2">
                            <input name="isatuanberat" id="isatuanberat" class="form-control input-sm" value="Gram" readonly hidden>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Status Produksi</label>
                        <label class="col-md-3">Nama Motif/Warna</label>
                        <label class="col-md-3">Series</label>
                        <label class="col-md-3">Brand</label>
                        <div class="col-sm-3">
                            <select name="istatusproduksi" id="istatusproduksi" class="form-control input-sm select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="iwarna[]" id="iwarna" multiple class="form-control input-sm select2" konchange="getkode(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="istyle" id="istyle" class="form-control input-sm select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ibrand" id="ibrand" class="form-control input-sm select2">
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-2">Tgl SPH</label>
                        <label class="col-md-2">Tgl Launching SPH</label>
                        <label class="col-md-2">Tgl STP</label>
                        <label class="col-md-6">Surat Penawaran</label>
                        <div class="col-sm-2">
                            <input type="text" name="dtanggalpenawaran" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" readonly="">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dlaunching" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" readonly="">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dstp" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" readonly="">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="esuratpenawaran" class="form-control input-sm" value="" placeholder="Nomor Surat Penawaran">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2">HJP</label>
                        <label class="col-md-10">Harga Grosir</label>
                        <div class="col-sm-2">
                            <input type="text" name="ehjp" id="ehjp" autocomplete="off" class="form-control input-sm" value="0" onkeyup="angkahungkul(this);reformat(this);" placeholder="0" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}">
                        </div>
                        <div class="col-sm-2">
                            <input type="input" name="fhargagrosir" autocomplete="off" class="form-control input-sm" value="0" onkeyup="angkahungkul(this);reformat(this);" placeholder="0" onblur="if(this.value=='' ){this.value='0' ;}" onfocus="if(this.value=='0' ){this.value='' ;}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="edeskripsi" class="form-control input-sm" value="" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder; ?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
        $("#ikelompok").attr("disabled", true);
        $("#ijenisbrg").attr("disabled", true);

        $("#iproductbase").keyup(function() {
            var kode = $('#iproductbase').val();;
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
        });

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
        });

        $('#ijenisbrg').select2({
            placeholder: 'Pilih Sub Kategori Barang',
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
        });

        $('#istyle').select2({
            placeholder: 'Pilih Series Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getstyle'); ?>',
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
        $("#ikelompok").attr("disabled", false);
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

    function kodebarang(iproductwip) {
        var iproductwip = $('#iproductwip').val();
        //alert(iproductwip);
        $.ajax({
            type: "post",
            data: {
                'iproductwip': iproductwip
            },
            url: '<?= base_url($folder . '/cform/getkodebarang'); ?>',
            dataType: "json",
            success: function(data) {
                $('#eproductbasename').val(data[0].e_product_wipname);
                $('#productmotif').val(data[0].i_product_wip);
                $('#npanjang').val(data[0].n_panjang);
                $('#nlebar').val(data[0].n_lebar);
                $('#ntinggi').val(data[0].n_tinggi);
                $('#nberat').val(data[0].n_berat);
                getdatawip(iproductwip);
            },
            error: function() {
                alert('Error :)');
            }
        });
    }

    function getdatawip(kode) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getdatawip'); ?>",
            data: {
                'iproductwip': kode
            },
            dataType: 'json',
            success: function(data) {
                $("#istatusproduksi").html(data.kop);
                $('#iwarna').html(data.warna);
                $('#istyle').html(data.style);
                $('#ibrand').html(data.brand);
                $('#isatuan').html(data.satuan);
                $('#iproductbase').val(data.iproductbase);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                    $("#istatusproduksi").attr("disabled", false);
                    $("#iwarna").attr("disabled", false);
                    $("#ibrand").attr("disabled", false);
                    $("#istyle").attr("disabled", false);
                    $("#isatuan").attr("disabled", false);
                }

            },
            error: function(XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
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