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
                        <label class="col-md-4">Kode Warna</label>
                        <label class="col-md-8">Nama Warna</label>
                        <div class="col-sm-4">
                            <input type="text" name="icolor" id="icolor" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_color; ?>">
                            <input type="hidden" name="icolorold" id="icolorold" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_color; ?>">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->id; ?>">
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="ecolorname" id="ecolorname" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_color_name; ?>">
                        </div>
                    </div>                            
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            &nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Warna Barang terdiri dari 5 (lima) angka</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : M0001, K0002, dst</span>
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

$( "#icolor" ).keyup(function() {
    var kode = $(this).val().replace(/[^a-zA-Z0-9]/g, '');
    $('#icolor').val(kode);
    var icolorold = $('#icolorold').val();
    $.ajax({
        type: "post",
        data: {
            'kode' : kode,
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function (data) {
            if (data==1 && kode != icolorold) {
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

$( "#ecolorname" ).keyup(function() {
    var name = $(this).val().replace(/[^\w\s]/gi,'');
    $('#ecolorname').val(name);
});

$(document).ready(function () {
    $( "#icolor" ).focus();
});


 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
