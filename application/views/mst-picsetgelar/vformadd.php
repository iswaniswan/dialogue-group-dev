<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus fa-lg"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-8">Nama PIC</label>
                        <label class="col-md-2 text-center">PIC Cutting</label>
                        <label class="col-md-2 text-center">PIC Gelar</label>
                        <div class="col-sm-8">
                            <input autofocus type="text" name="e_pic_name" autocomplete="off" id="ikodelokasi" class="form-control" placeholder="Nama PIC .." required="" value="">
                        </div>
                        <div class="col-sm-2">
                            <input type="checkbox" name="f_cutting" id="f_cutting" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <input type="checkbox" name="f_gelar" id="f_gelar" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"> <i class="fa fa-lg fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform','#main')"> <i class="fa fa-lg fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
</script>