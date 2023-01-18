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
            <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Group Barang</label>
                        <label class="col-md-8">Nama Group Barang</label>
                        <div class="col-sm-4">
                            <input type="text" name="igroupbrg" id="igroupbrg" class="form-control"  placeholder="Kode Group Barang" required="" onkeyup="gede(this);" value="<?= $data->i_kode_group_barang; ?>" readonly>
                            <input type="hidden" name="igroupbrgold" id="igroupbrgold" class="form-control" required="" value="<?= $data->i_kode_group_barang; ?>" readonly>
                            <input type="hidden" name="id" id="id" class="form-control" required="" value="<?= $data->id; ?>" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="egroupname" class="form-control" required="" placeholder="Nama Group Barang" onkeyup="gede(this)" value="<?= $data->e_nama_group_barang; ?>">
                        </div>
                    </div>                            
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
$( "#igroupbrg" ).keyup(function() {
    var kode = $(this).val().replace(/[^a-zA-Z0-9]/g, '');
    var kodeold = $('#igroupbrgold').val();
    $('#igroupbrg').val(kode);
    if (kode.length==7) {
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data == 1 && kodeold!=kode) {
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
    }else{
        $("#cek").attr("hidden", true);
        $("#submit").attr("disabled", false);
    }
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $( "#igroupbrg" ).focus();
});
</script>
