<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>


            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Kode Bank</label>
                        <label class="col-md-6">CoA</label>
                        <div class="col-sm-6">
                            <input type="text" name="ibank" class="form-control" required="" maxlength="2" onkeyup="gede(this)" value="" onblur="checklength(this)">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="icoa" class="form-control" value="" >
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nama Bank</label>
                        <label class="col-md-6">Nama Rekening Bank</label>
                        <div class="col-sm-6">
                            <input type="text" name="ebankname" class="form-control" value="" >
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="ebankrekening" class="form-control"  value="" >
                        </div>
                    </div>  
                </form>
            </div>
        </div>
        

    </div>
</div>
</div>

<script>
   $(document).ready(function () {
    $(".select2").select2();
});

   function checklength(el){
    if(el.value.length != 2){
        swal("Kode Harus 2 Karakter");
    }
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
