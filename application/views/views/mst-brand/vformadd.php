<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Brand</label>
                            <label class="col-md-8">Nama Brand</label>
                            <div class="col-sm-4">
                                <input type="text" name="ibrand" autocomplete="off" id="ibrand" class="form-control" placeholder="Standar 7 Digit" onkeyup="gede(this); clearcode(this);" maxlength="15"  value="" required="">
                                <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="ebrandname" id="ebrandname" maxlength="100" placeholder="Nama Brand Maksimal 100 Karakter " class="form-control" onkeyup="gede(this); clearname(this);" value="" required="">
                            </div>                            
                        </div>                 
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="note">&nbsp;&nbsp;<b>NOTE :</b></span><br>
                            <span class="note">&nbsp;&nbsp;* Standar Kode terdiri dari 7 (tujuh) kombinasi huruf dan atau angka</span><br>
                            <!-- <span class="note">&nbsp;&nbsp;* Susunan huruf dapat diambil dari singkatan Nama</span><br>
                            <span class="note">&nbsp;&nbsp;* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Kode sebelumnya</span><br><br> -->
                            <span class="note">&nbsp;&nbsp;<b>Contoh : ABC0001</b></span>
                        </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $( "#ibrand" ).focus();
    });

    $( "#ibrand" ).keyup(function() {
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
