<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>


            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-12">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Tgl SJ</label>
                        <div class="col-sm-12">
                            <input type="date" name="tglsj" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="text" name="iarea" class="form-control" value='<?= @$area->i_area ?>' hidden>
                            <input type="text" class="form-control" value='<?= @$area->i_area." - ".@$area->e_area_name ?>' readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">SPB</label>
                        <div class="col-sm-12">
                            <select class="form-control select2" name="ispb">
                                <?php foreach($spb as $r){ ?>
                                    <option value='<?= $r->i_spb ?>'><?= $r->i_spb.' | '.date('d-M-Y',strtotime($r->d_spb)).' | '.$r->i_area.' - '.$r->e_area_name.' | '.$r->e_customer_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai</label>
                        <div class="col-sm-12">
                            <input type="number" name="vsjnetto" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Toko</label>
                        <div class="col-sm-12">
                            <input type="text" name="vsjnetto" class="form-control" maxlength="50" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                    </form>
                </div>
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
        $(".select2").select2();
        showCalendar('.date');
    });
</script>