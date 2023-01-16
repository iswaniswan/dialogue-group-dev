<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Area</label>
                            <label class="col-md-8">Nama Area</label>
                            <div class="col-sm-4">
                                <input type="hidden" readonly="" name="id" value="<?= $data->id; ?>">
                                <input type="text" name="iarea" id="iarea" class="form-control" required="" maxlength="15" onkeyup="gede(this); clearcode(this);" value="<?= $data->i_area; ?>">
                                <span class="notekode" hidden="true"><b>* Kode Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="earea" id="earea" maxlength="100" class="form-control" required="" onkeyup="gede(this); clearname(this);" value="<?= $data->e_area; ?>">
                            </div>
                        </div>                       
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-12">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="note">&nbsp;&nbsp;<b>NOTE :</b></span><br>
                            <span class="note">&nbsp;&nbsp;* Standar Kode terdiri dari 2 (dua) angka</span><br>
                            <span class="note">&nbsp;&nbsp;<b>Contoh : 00</b></span>
                        </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $( "#earea" ).focus();
    });

    $( "#iarea" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>
