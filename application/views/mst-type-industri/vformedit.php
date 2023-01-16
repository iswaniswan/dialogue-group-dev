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
            <div class="col-md-7">
                    <div class="form-group row">
                        <label class="col-md-4">Kode Jenis Partner</label>
                        <label class="col-md-8">Nama Jenis Partner</label>
                        <div class="col-sm-4">
                            <input type="text"  id="itype" name="itype" class="form-control" required="" autocomplete="off" maxlength="15" onkeyup="gede(this);clearcode(this);" value="<?= $data->i_type_industry; ?>">

                            <input type="hidden"  id="olditype" name="olditype" class="form-control" required="" maxlength="15" onkeyup="gede(this)" value="<?= $data->i_type_industry; ?>" readonly>

                             <span id="cek" hidden="true"> 
                                    <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                         <div class="col-sm-7">
                            <input type="text" name="etype" class="form-control" required="" onkeyup="gede(this)clearname(this);" value="<?= $data->e_type_industry_name; ?>">
                        </div>
                    </div>                                 
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                              <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode terdiri dari 7 (tujuh) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama </span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada kode sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : JNP0001, JNP0002, dst</span>
                    </div>
                </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/bootstrap-tokenfield.js"></script>
<script src="<?= base_url(); ?>assets/js/bootstrap-tokenfield.min.js"></script>
<link href="<?= base_url(); ?>assets/css/bootstrap-tokenfield.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/bootstrap-tokenfield.min.css" rel="stylesheet">
<script>
$(document).ready(function () {
    $(".select2").select2();
    $( "#itype" ).focus();
});

$("#itype" ).keyup(function() {
        var kode = $(this).val();
        var kodeold = $("#olditype").val();
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

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
