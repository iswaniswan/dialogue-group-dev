
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-file-excel-o"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div> -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label><label class="col-md-9">Date To</label>
                        <div class="col-sm-3">
                            <input type="text" id= "dfrom" name="dfrom" class="form-control date" required value="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dto" name="dto" class="form-control date" required value="<?= date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" class="form-control select2" required="">
                                <?php if ($area) { ?>
                                    <option value="NA">NA - NASIONAL</option>
                                    <?php foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <a href="#" id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export Excel</button></a>
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
        $('.select2').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama'
        });
        showCalendar('.date');
    });

    $("#href").click(function() {
        var dfrom = $('#dfrom').val();
        var dto   = $('#dto').val();
        var iarea = $('#iarea').val();
        if (dfrom=='' || dto==''||iarea=='') {
            swal('Isi form yang masih kosong!');
            return false;
        }
        var abc = "<?= site_url($folder.'/cform/export/'); ?>"+dfrom+'/'+dto+'/'+iarea;
        $("#href").attr("href",abc);
    });
</script>