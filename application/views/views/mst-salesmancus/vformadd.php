<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Area</label>
                        <label class="col-md-4">Salesman</label>
                        <!-- <label class="col-md-4">Brand</label> -->
                        <label class="col-md-4">Periode</label>
                        <div class="col-sm-4">
                            <select name="iarea" id="iarea" class="form-control">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="isalesman" id="isalesman" class="form-control">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="bulan" id="bulan" class="form-control select2">
                                <option value='01' <?php if(date('m')=='01'){?> selected <?php } ?>>Januari</option>
                                <option value='02' <?php if(date('m')=='02'){?> selected <?php } ?>>Pebruari</option>
                                <option value='03' <?php if(date('m')=='03'){?> selected <?php } ?>>Maret</option>
                                <option value='04' <?php if(date('m')=='04'){?> selected <?php } ?>>April</option>
                                <option value='05' <?php if(date('m')=='05'){?> selected <?php } ?>>Mei</option>
                                <option value='06' <?php if(date('m')=='06'){?> selected <?php } ?>>Juni</option>
                                <option value='07' <?php if(date('m')=='07'){?> selected <?php } ?>>Juli</option>
                                <option value='08' <?php if(date('m')=='08'){?> selected <?php } ?>>Agustus</option>
                                <option value='09' <?php if(date('m')=='09'){?> selected <?php } ?>>September</option>
                                <option value='10' <?php if(date('m')=='10'){?> selected <?php } ?>>Oktober</option>
                                <option value='11' <?php if(date('m')=='11'){?> selected <?php } ?>>November</option>
                                <option value='12' <?php if(date('m')=='12'){?> selected <?php } ?>>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <select name="tahun" id="tahun" class="form-control select2">
                                    <?php 
                                        $tahun1 = date('Y')-3;
                                        $tahun2 = date('Y');
                                        for($i=$tahun1;$i<=$tahun2;$i++){?>
                                            <option value='<?= $i;?>' <?php if($i==$tahun2){?> selected <?php } ?>><?= $i;?></option>
                                        <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-sm-4">
                            <select name="ibrand" id="ibrand" class="form-control">
                            </select>
                        </div> -->
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Customer</label>
                        <!-- <label class="col-md-4">Periode</label> -->
                        <div class="col-sm-12">
                            <select name="icustomer[]" multiple id="icustomer" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return validasi();"> <i class="fa fa-save mr-2"></i>Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/area'); ?>',
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

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/customer'); ?>',
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

        $('#isalesman').select2({
            placeholder: 'Pilih Salesman',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/salesman'); ?>',
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

        /* $('#ibrand').select2({
            placeholder: 'Pilih Brand',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/brand'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        }); */
    });

    function validasi() {
        if ($('#iarea').val() == '' || $('#iarea').val() == null && $('#icustomer').val() == '' || $('#icustomer').val() == null && $('#isalesman').val() == '' || $('#isalesman').val() == null && $('#ibrand').val() == '' || $('#ibrand').val() == null && $('#bulan').val() == '' || $('#bulan').val() == null && $('#tahun').val() == '' || $('#tahun').val() == null) {
            swal("Data Masih Belum Lengkap");
            return false;
        } else{ 
            return true;
        }
    }
</script>