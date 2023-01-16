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
            <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Satuan Barang</label>
                        <label class="col-md-8">Nama Satuan Barang</label>
                        <div class="col-sm-4">
                            <input type="text" name="isatuancode" id="isatuancode" class="form-control" required="" placeholder="Kode Satuan Barang" onkeyup="gede(this)" value="" autocomplete="off">
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="esatuan" id="esatuan" class="form-control" placeholder="Nama Satuan Barang" maxlength="30" onkeyup="gede(this)" required="" value="">
                        </div>
                    </div>                                                   
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>   
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Satuan Barang diambil berdasarkan singkatan dari Nama Satuan Barang</span><br><br>
                        <span style="color: #8B0000"><b>Contoh :</b></span><br>
                        <span style="color: #8B0000">* Kilogram   = KG</span><br>
                        <span style="color: #8B0000">* Centimeter = CM</span><br>
                        <span style="color: #8B0000">* Pieces     = PC</span><br>
                        <span style="color: #8B0000">* dst</span><br>
                    </div>
                </form>
            </div>
        </div>
 

        </div>
    </div>
</div>

<script>
$( "#isatuancode" ).keyup(function() {
    var kode = $(this).val().replace(/[^a-zA-Z0-9]/g, '');
    $('#isatuancode').val(kode);
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#cek").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#cek").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
});

$( "#esatuan" ).keyup(function() {
    var name = $(this).val().replace(/[^\w\s]/gi,'');
    $('#esatuan').val(name);
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $( "#isatuancode" ).focus();
});
</script>
