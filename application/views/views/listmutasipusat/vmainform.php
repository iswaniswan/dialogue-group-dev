<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-3">
                    <div class="form-group row">
                    <label class="col-md-12">Periode</label>
                        <div class="col-sm-7">
                            <select name="bulan" id="bulan" class="form-control" required="">
                                <option value="">-- Pilih Bulan --</option>
                                <option value='01' <?php if (date('m')=='01') {echo "selected";}?>>Januari</option>
                                <option value='02' <?php if (date('m')=='02') {echo "selected";}?>>Februari</option>
                                <option value='03' <?php if (date('m')=='03') {echo "selected";}?>>Maret</option>
                                <option value='04' <?php if (date('m')=='04') {echo "selected";}?>>April</option>
                                <option value='05' <?php if (date('m')=='05') {echo "selected";}?>>Mei</option>
                                <option value='06' <?php if (date('m')=='06') {echo "selected";}?>>Juni</option>
                                <option value='07' <?php if (date('m')=='07') {echo "selected";}?>>Juli</option>
                                <option value='08' <?php if (date('m')=='08') {echo "selected";}?>>Agustus</option>
                                <option value='09' <?php if (date('m')=='09') {echo "selected";}?>>September</option>
                                <option value='10' <?php if (date('m')=='00') {echo "selected";}?>>Oktober</option>
                                <option value='11' <?php if (date('m')=='01') {echo "selected";}?>>November</option>
                                <option value='12' <?php if (date('m')=='02') {echo "selected";}?>>Desember</option>
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
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="istore" id="istore" class="form-control select2" required="" onchange="get(this.value);">
                                <option value=""></option>
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_store;?>"><?php echo $key->i_store." - ".$key->e_store_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input type="hidden" name="istorelocation" id="istorelocation" class="form-control" value="">
                            <input type="hidden" name="iarea" id="iarea" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-sm"> <i class="fa fa-external-link"></i>&nbsp;&nbsp;Detail</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-md-12">Group</label>
                        <div class="col-sm-12">
                            <select name="iproductgroup" id="iproductgroup" class="form-control select2" required="">
                                <option value="ALL">ALL</option>
                                <?php if ($group) {
                                    foreach ($group as $key) { ?>
                                        <option value="<?= $key->i_product_group;?>"><?= $key->e_product_groupname;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Status</label>
                        <div class="col-sm-12">
                            <select name="iproductstatus" id="iproductstatus" class="form-control select2" required="">
                                <option value="ALL">ALL</option>
                                <?php if ($status) {
                                    foreach ($status as $key) { ?>
                                        <option value="<?= $key->i_product_status;?>"><?= $key->e_product_statusname;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#bulan').select2({
            placeholder: 'Pilih Bulan'
        });
        $('#tahun').select2({
            placeholder: 'Pilih Tahun'
        });
        $('#istore').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama'
        });
    });

    function get(id) {        
        $.ajax({
            type: "post",
            data: {
                'istore' : id,
            },
            url: '<?= base_url($folder.'/cform/getstore'); ?>',
            dataType: "json",
            success: function (data) {
                var istorelocation   = data['isi']['i_store_location'];
                $('#istorelocation').val(istorelocation);
                var iarea   = data['isi']['i_area'];
                $('#iarea').val(iarea);
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    }
</script>