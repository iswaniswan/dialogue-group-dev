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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Kategori Partner</label>
                        <label class="col-md-8">Nama Kategori Partner</label>
                        <div class="col-sm-4">
                            <input type="text" id="isuppliergroup" name="isuppliergroup" class="form-control" autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this);" value="<?= $data->i_supplier_group; ?>">
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id; ?>">
                            <span id="cek" hidden="true"> 
                                    <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                            <input type="hidden" id="oldisuppliergroup" name="oldisuppliergroup" class="form-control" required="" maxlength="15" onkeyup="gede(this)" value="<?= $data->i_supplier_group; ?>">
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="isuppliergroupname" class="form-control" required="" onkeyup="gede(this);clearname(this);" maxlength="30" value="<?= $data->e_supplier_group_name; ?>">
                        </div>
                    </div>            
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return check();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode terdiri dari 5 (lima) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama </span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Kode sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : KTG01, KTG02, dst</span>
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
    $( "#isuppliergroup" ).focus();
});

    $("#isuppliergroup" ).keyup(function() {
        var kode = $(this).val();
        var kodeold = $("#oldisuppliergroup").val();
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
                'kodeold' : kodeold,
            },
            url: '<?= base_url($folder.'/cform/cekkodeedit'); ?>',
            dataType: "json",
            success: function (data) {
                /*var x = parseInt(kode.length)-1;*/
                if (data==1) {
                    $("#cek").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                    /*$('#ibrand').val(kode.substring(0, x));*/
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
        
    $("#isuppliergroup").keyup(function() {
        var raw_text =   $("#isuppliergroup").val();
        var return_text = raw_text.replace(/[^a-zA-Z0-9-]/g,'');
        $("#isuppliergroup").val(return_text);
    });

function check(){
    var el = $("#isuppliergroup").val();
    //alert(el.length);
    if(el.length > 7){
        swal("Kode Max 7 Karakter");
        return false;
    }   
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
