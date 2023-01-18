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
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Series</label>
                        <label class="col-md-4">Nama Series</label>
                        <label class="col-md-5">Brand</label>
                        <div class="col-sm-3">
                            <input type="text" name="istyle" id="istyle" autocomplete="off" class="form-control"
                                placeholder="Standar 7 Digit" required="" maxlength="15"
                                onkeyup="gede(this); clearcode(this);" value="">
                            <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="estylename" id="estylename" onkeyup="gede(this); clearname(this);"
                                class="form-control" value="" placeholder="Nama Seri Maksimal 100 Karakter "
                                maxlength="100">
                        </div>
                        <div class="col-sm-5">
                            <select name="ibrand" id="ibrand" class="form-control select2">
                                <option value="">Pilih Kelompok Barang</option>
                                <?php foreach ($brand as $ibrand):?>
                                <option value="<?php echo $ibrand->id;?>">
                                    <?php echo $ibrand->e_brand_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i
                                    class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick="show('<?= $folder;?>/cform','#main')"> <i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="note">&nbsp;&nbsp;<b>NOTE :</b></span><br>
                        <span class="note">&nbsp;&nbsp;* Standar Kode terdiri dari 7 (tujuh) kombinasi huruf dan atau
                            angka</span><br>
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
$(document).ready(function() {
    $("#istyle").focus();
    $(".select2").select2();
});

$("#istyle").keyup(function() {
    $.ajax({
        type: "post",
        data: {
            'kode': $(this).val(),
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function(data) {
            if (data == 1) {
                $(".notekode").attr("hidden", false);
                $("#submit").attr("disabled", true);
            } else {
                $(".notekode").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function() {
            swal('Error :)');
        }
    });
});

function getbrand() {
    $.ajax({
        url: "<?php echo site_url($folder.'/cform/getbrand');?>",
        dataType: 'json',
        success: function(data) {
            console.log(data);
        },

        error: function(XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }

    })
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>