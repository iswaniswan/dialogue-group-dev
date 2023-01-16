<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label><label class="col-md-9">Date To</label>
                        <div class="col-sm-3">
                            <input readonly name="dfrom" id="dfrom" class="form-control date" required="">
                        </div>
                        <div class="col-sm-3">
                            <input readonly name="dto" id="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-3">
                            <select id="istore" name="istore" class="form-control select2">
                            <option value=""></option>
                                <?php if ($gudang) {
                                    foreach ($gudang as $key) { ?>
                                        <option value="<?= $key->i_store;?>"><?= $key->i_store." - ".$key->e_store_name." - ".$key->e_store_locationname;?></option> 
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
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
    $('.select2').select2({
        placeholder: 'Cari Berdasarkan Kode / Nama'
    });
    showCalendar('.date');
});
</script>