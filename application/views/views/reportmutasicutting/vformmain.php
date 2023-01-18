<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Dari Tanggal</label>
                        <label class="col-md-3">Sampai Tanggal</label>
                        <label class="col-md-3">Bagian</label>
                        <label class="col-md-3">Perusahaan</label>
                        <div class="col-sm-3">
                            <input type="text" id="dfrom" name="dfrom" class="form-control input-sm date" readonly value="<?php echo date("01-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dto" name="dto" class="form-control input-sm date" readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="id_company" id="id_company" class="form-control select2">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Kategori Barang</label>
                        <label class="col-md-6">Sub Kategori Barang</label>
                        <div class="col-sm-6">
                            <select name="ikelompok" id="ikelompok" class="form-control select2">
                                
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="jnsbarang" id="jnsbarang" class="form-control select2">
                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" id="submit" class="btn btn-inverse btn-block btn-sm" onclick="print()"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
        showCalendar2('.date');

        var kosong = null;
        $("#ibagian").change(function () {
            $("#ikelompok").html("<option value="+ kosong +">SEMUA KATEGORI</option>");
            $("#jnsbarang").html("<option value="+ kosong +">SEMUA SUB KATEGORI</option>");
    });
    });

    $(document).ready(function() {
        $('#ibagian').select2({
            placeholder: 'Pilih Gudang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/gudang'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
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

        $('#id_company').select2({
            placeholder: 'Pilih Company',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/company'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
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

        $('#ikelompok').select2({
            placeholder: 'Pilih Kategori',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getkategori'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
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
        }).change(function() {
            $('#jnsbarang').val("");
            $('#jnsbarang').html("");
        });

        $('#jnsbarang').select2({
            placeholder: 'Pilih Sub Kategori',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/getjenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
                        ikodekelompok : $('#ikelompok').val()
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

    function getkategori(ikodemaster) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getkategori'); ?>",
            data: "ibagian=" + ikodemaster,
            dataType: 'json',
            success: function(data) {
                $("#ikelompok").html(data.kop);
                getjenis();
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

    function getjenis($ikelompok) {
        var ikelompok = $('#ikelompok').val();
        var ibagian = $('#ibagian').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getjenis'); ?>",
            data: {
                'ikelompok': ikelompok,
                'ibagian': ibagian
            },
            dataType: 'json',
            success: function(data) {
                $("#jnsbarang").html(data.kop);
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

    $("#dfrom").change(function() {
        var dfrom = splitdate($(this).val());
        var dto = splitdate($('#dto').val());
        if (dfrom != null && dto != null) {
            if (dfrom > dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $("#dto").change(function() {
        var dto = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom != null && dto != null) {
            if (dfrom > dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    function print() {
        var lebar = 1024;
        var tinggi = 768;

        var ibagian = $('#ibagian').val();
        var id_company = $('#id_company').val();
        var jnsbarang = $('#jnsbarang').val();
        var ikelompok = $('#ikelompok').val();

        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        if (ibagian === "" || ibagian === null || ibagian === "null") {
            swal("Bagian Harus Dipilih !!!");
        } else {
            eval('window.open("<?php echo site_url($folder); ?>"+"/cform/cetak/"+ibagian+"/"+jnsbarang+"/"+ikelompok+"/"+dfrom+"/"+dto+"/"+id_company,"","screenX=0,screenY=0,left=0,top=0,fullscreen=yes,width="+(screen.availWidth-5)+",height="+(screen.availHeight-(55)))');

            //window.open("about:blank", "", "screenX=0,screenY=0,left=0,top=0,fullscreen=yes,width="+(screen.availWidth-5)+",height="+(screen.availHeight-(55)));
        }

    }
</script>