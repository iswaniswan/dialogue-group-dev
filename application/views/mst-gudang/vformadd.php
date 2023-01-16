<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Unit</label>
                        <label class="col-md-3">Nama Unit Kerja</label>
                        <label class="col-md-3">Lokasi</label>
                        <label class="col-md-3">Bagian</label>
                        <div class="col-sm-3">
                            <input type="text" name="ikode" id="ikode" class="form-control input-sm" autocomplete="off" required="" placeholder="Standar 5 Digit" maxlength="15" onkeyup="gede(this); clearcode(this);" value="">
                            <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="enama" id="enama" class="form-control input-sm" maxlength="50" onkeyup="gede(this); clearname(this);" placeholder="Maksimal 50 Karakter" value="" required="">
                        </div>
                        <div class="col-sm-3">
                            <select id="ilokasi" name="ilokasi" required="" class="form-control select2" data-placeholder="Pilih Lokasi Gudang">
                                <option value=""></option>
                                <?php if ($lokasigudang) {
                                    foreach ($lokasigudang as $ilokasi) : ?>
                                        <option value="<?= $ilokasi->i_lokasi; ?>"><?= $ilokasi->e_lokasi_name; ?></option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Sub Bagian</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Internal/Eksternal</label>
                        <label class="col-md-3">Jenis Bagian</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" required="" class="form-control select2">
                            </select>
                            <input type="hidden" name="itype" id="itype">
                        </div>
                        <div class="col-sm-3">
                            <select name="ikelompok[]" id="ikelompok" multiple="multiple" class="form-control select2">
                                <option value=""></option>
                            </select>
                            <span class="note">* Hanya untuk gudang. (Boleh dikosongkan)</span>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="inter_exter" id="inter_exter"  class="form-control input-sm select2">
                                <?php if ($jahit->num_rows()>0) {
                                    foreach ($jahit->result() as $key) {?>
                                        <option value="<?= $key->id;?>"><?= $key->e_nama_kategori;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="jenis_bagian" id="jenis_bagian" class="form-control input-sm" maxlength="15" onkeyup="gede(this); clearname(this);" placeholder="Maksimal 15 Karakter" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="note">&nbsp;&nbsp;<b>NOTE :</b></span><br>
                        <span class="note">&nbsp;&nbsp;* Standar Kode terdiri dari 5 (lima) kombinasi huruf dan atau angka</span><br>
                        <!-- <span class="note">&nbsp;&nbsp;* Susunan huruf dapat diambil dari singkatan Nama</span><br>
                            <span class="note">&nbsp;&nbsp;* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Kode sebelumnya</span><br><br> -->
                        <span class="note">&nbsp;&nbsp;<b>Contoh : ABCDE atau GD101 atau 94231 </b></span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".select2").select2();
        $("#ikode").focus();

        $('#ibagian').select2({
            placeholder: 'Pilih Bagian',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        //lokasi : $('#ilokasi').val(),
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

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis Bagian',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        head: $('#ibagian').val(),
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

        $("#ijenis").change(function() {
            $("#ikelompok").val("");
            $("#ikelompok").html("");
            $("#itype").val($(this).val());
            $.ajax({
                type: "post",
                data: {
                    'kode': $(this).val(),
                },
                url: '<?= base_url($folder . '/cform/group'); ?>',
                dataType: "json",
                success: function(data) {
                    $('#itype').val(data);
                },
                error: function() {
                    swal('Error :)');
                }
            });
        });

        $('#ikelompok').select2({
            placeholder: 'Pilih Kategori Barang',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder . '/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                        igroup: $('#itype').val(),
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

    $("#ikode").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode': $(this).val(),
            },
            url: '<?= base_url($folder . '/cform/cekkode'); ?>',
            dataType: "json",
            success: function(data) {
                if (data == 1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                } else {
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function() {
                swal('Error :)');
            }
        });
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>