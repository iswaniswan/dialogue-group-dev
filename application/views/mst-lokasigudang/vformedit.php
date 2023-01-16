<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">Kode Lokasi Unit</label>
                            <label class="col-md-8">Nama Lokasi Unit Kerja</label>
                            <div class="col-sm-4">
                                <input type="hidden" name="id" class="form-control" maxlength="2" value="<?= $data->id; ?>" readonly>
                                <input type="text" name="ikodelokasi" id="ikodelokasi" class="form-control" required="" maxlength="2" value="<?= $data->i_lokasi; ?>" onkeyup="gede(this); clearcode(this);">
                                <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="enamalokasi" id="enamalokasi" class="form-control" onkeyup="gede(this); clearname(this);" required="" maxlength="50" value="<?= $data->e_lokasi_name; ?>">
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
                            <span class="note">&nbsp;&nbsp;* Standar Kode terdiri dari 2 (dua) kombinasi huruf dan atau angka</span><br>
                            <!-- <span class="note">&nbsp;&nbsp;* Susunan huruf dapat diambil dari singkatan Nama</span><br>
                            <span class="note">&nbsp;&nbsp;* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Kode sebelumnya</span><br><br> -->
                            <span class="note">&nbsp;&nbsp;<b>Contoh : AA atau A1 atau 11 </b></span>
                        </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $( "#enamalokasi" ).focus();
    });

    $( "#ikodelokasi" ).keyup(function() {
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