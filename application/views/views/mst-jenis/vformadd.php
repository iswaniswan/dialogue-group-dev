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
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Sub Kategori Barang</label>
                        <label class="col-md-3">Nama Sub Kategori Barang</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Group Barang</label>
                        <div class="col-sm-3">
                            <input type="text" name="itypecode" id="itypecode" placeholder="Kode Sub Kategori Barang"
                                class="form-control" maxlength="15" required="" onkeyup="gede(this);" value=""  autocomplete="off">
                            <span id="cek" hidden="true">
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font>
                            </span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="etypename" id="etypename" placeholder="Nama Sub Kategori Barang"
                                class="form-control" value="" required="" onkeyup="gede(this);"  autocomplete="off">
                        </div>
                        <div class="col-sm-3">
                            <select name="ikelompok" id="ikelompok" class="form-control select2"
                                onchange="getjenis(this.value)">
                                <option value="">Pilih Kategori Barang</option>
                                <?php foreach ($kelompok as $ikelompok):?>
                                <option value="<?php echo $ikelompok->i_kode_kelompok;?>">
                                    <?php echo $ikelompok->e_nama_kelompok;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="igroupbrg" id="igroupbrg" class="form-control" readonly>
                            <input type="text" name="egroupbrg" id="egroupbrg" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i
                                    class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick='show("<?= $folder;?>/cform","#main")'><i
                                    class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Jenis Barang terdiri dari 7 (tujuh) kombinasi huruf
                            dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Jenis
                            Barang</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan
                            nomor urutan terakhir pada Jenis Barang sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : MIC0001, PRL0002, HND0003, dst</b></span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    $(".select2").select2();
});

$("#itypecode").keyup(function() {
    var kode = $('#itypecode').val();

    $.ajax({
        type: "post",
        data: {
            'kode': kode,
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function(data) {
            if (data == 1) {
                $("#cek").attr("hidden", false);
                $("#submit").attr("disabled", true);
            } else {
                $("#cek").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function() {
            swal('Error :)');
        }
    });
});

$(document).ready(function() {
    $("#itypecode").focus();
});

function getjenis(ikelompokbrg) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkelompok');?>",
        data: "ikelompokbrg=" + ikelompokbrg,
        dataType: 'json',
        success: function(data) {
            $("#igroupbrg").val(data.igroup);
            $("#egroupbrg").val(data.egroupname);
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