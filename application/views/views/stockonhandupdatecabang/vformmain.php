<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-md-12">Periode</label>
                        <div class="col-sm-7">
                            <select name="bulan" id="bulan" class="form-control" required="">
                                <option value="">-- Pilih Bulan --</option>
                                <option value='01' <?php if (date('m')=='01') {echo "selected";}?>>Januari</option>
                                <option value='02' <?php if (date('m')=='02') {echo "selected";}?>>Pebruari</option>
                                <option value='03' <?php if (date('m')=='03') {echo "selected";}?>>Maret</option>
                                <option value='04' <?php if (date('m')=='04') {echo "selected";}?>>April</option>
                                <option value='05' <?php if (date('m')=='05') {echo "selected";}?>>Mei</option>
                                <option value='06' <?php if (date('m')=='06') {echo "selected";}?>>Juni</option>
                                <option value='07' <?php if (date('m')=='07') {echo "selected";}?>>Juli</option>
                                <option value='08' <?php if (date('m')=='08') {echo "selected";}?>>Agustus</option>
                                <option value='09' <?php if (date('m')=='09') {echo "selected";}?>>September</option>
                                <option value='10' <?php if (date('m')=='10') {echo "selected";}?>>Oktober</option>
                                <option value='11' <?php if (date('m')=='11') {echo "selected";}?>>November</option>
                                <option value='12' <?php if (date('m')=='12') {echo "selected";}?>>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="tahun" id="tahun" class="form-control" required="">
                                <option value="">-- Pilih Tahun --</option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>" <?php if ($i==$tahun2) {echo "selected";}?>><?= $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area Store - Lokasi</label>
                        <div class="col-sm-12">
                            <select id="istore" name="istore" class="form-control select2" required="" onchange="getstore(this.value);">
                                <option value=""></option>
                            </select>
                            <input type="hidden" id="istorelocation" name="istorelocation" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        showCalendar('.date');
        $('#bulan').select2({
            placeholder: 'Pilih Bulan'
        });
        $('#tahun').select2({
            placeholder: 'Pilih Tahun'
        });

        $('#istore').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datastore'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    function getstore(istore){
        $.ajax({
            type: "post",
            data: {
                'istore'  : istore,
            },
            url: '<?= base_url($folder.'/cform/detailstore'); ?>',
            dataType: "json",
            success: function (data) {
                $('#istorelocation').val(data[0].i_store_location);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $( "#submit" ).click(function() {
        if ($('#bulan').val()=='') {
            swal('Pilih Bulan Terlebih Dahulu!!!');
            return false;
        }

        if ($('#tahun').val()=='') {
            swal('Pilih Tahun Terlebih Dahulu!!!');
            return false;
        }

        if ($('#istore').val()=='') {
            swal('Store Harus Dipilih!!!');
            return false;
        }

        if ($('#istore').val()=='AA') {
            var tit = "Update Stock On Hand Pusat?"
        }else{
            var tit = "Update Stock On Hand Cabang?"
        }

        swal({   
            title: tit,   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, update!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'bulan'          : $('#bulan').val(),
                        'tahun'          : $('#tahun').val(),
                        'istore'         : $('#istore').val(),
                        'istorelocation' : $('#istorelocation').val(),
                    },
                    url: '<?= base_url($folder.'/cform/update'); ?>',
                    dataType: "json",
                    success: function (data) {
                        if (data=='1') {                        
                            swal("Berhasil!", "Stok Terupdate :)", "success");
                        }else{
                            swal("Berhasil!", "Stok Sudah Sesuai :)", "success");
                        }
                        show('<?= $folder;?>/cform','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal diupdate :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan update stok :)", "error");
            } 
        });
    });
</script>
