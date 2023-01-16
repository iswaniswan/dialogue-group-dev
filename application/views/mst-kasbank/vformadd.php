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
                        <label class="col-md-2">Kode Kas</label>
                        <label class="col-md-4">Nama Kas</label>
                        <label class="col-md-2">Jenis Kas</label>
                        <label class="col-md-4">CoA</label>
                        <div class="col-sm-2">
                            <input type="text" name="ijenis" id="ikas" class="form-control" onkeyup="gede(this)" value="" maxlength="15" placeholder="Kode Kas / Bank">
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ejenisvoucher" class="form-control" onkeyup="gede(this);" value="" placeholder="Nama Kas / Bank">
                        </div>
                        <div class="col-sm-2">
                           <select name="jeniskas" id="jeniskas" class="form-control select2" onchange="lihatrinci(this.value);"> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="coa" id="coa" class="form-control select2" disabled="true">
                           </select>    
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Nama Bank</label>
                        <label class="col-md-4">Nama Pemilik Rekening</label>
                        <label class="col-md-4">Nomor Rekening</label>
                        <div class="col-sm-4">
                           <select name="jenisbank" id="jenisbank" class="form-control select2" disabled="true">
                           </select>    
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="namarek" name="namarek" class="form-control" value="" disabled="" placeholder="Nama Pemilik Rekening">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="norek" name="norek" class="form-control" value="" disabled="" placeholder="Nomor Rekening">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Kas / Bank terdiri dari 7 (tujuh) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Kas / Bank</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Kas / Bank sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : KS00001, KS00002, dst</span>
                    </div>
                </div>
                </form>  
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();

     $('#jenisbank').select2({
        placeholder: 'Pilih Bank Jika Jenis adalah Bank',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/jenisbank'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        }
    });

    $('#jeniskas').select2({
        placeholder: 'Pilih Jenis Kas',
        allowClear: true,
        ajax: {
        url: '<?= base_url($folder.'/cform/jeniskas'); ?>',
        dataType: 'json',
        delay: 250,          
        processResults: function (data) {
            return {
            results: data
            };
        },
        cache: true
        }
    });

    $('#coa').select2({
        placeholder: 'Pilih CoA',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/coa'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                    kas : $('#jeniskas').val(),
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: false
        }
    });

    $("#ikas").keyup(function(){
        var kode = $("#ikas").val();
        $.ajax({
            type : "POST",
            data :{
                'kode' : kode,
            },
            url : '<?= base_url($folder.'/cform/cekkode');?>',
            dataType : "json",
            success : function (data){
                if(data == 1){
                    $("#cek").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#cek").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error : function(){
                swal("Error :)");
            }
        });
    });

    $("#ikas").focus();
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function lihatrinci(jenis) {
    if (jenis == "01") {
        $("#jenisbank").attr("disabled", true);
        $("#norek").attr("disabled", true);
        $("#namarek").attr("disabled",true);
        $("#coa").attr("disabled", false);
        $("#jenisbank").val("");
        $("#jenisbank").html("");
        $("#norek").val("");
        $("#coa").val("");
        $("#coa").html("");
    } else {
        $("#jenisbank").attr("disabled", false);
        $("#norek").attr("disabled", false);
        $("#namarek").attr("disabled", false);
        $("#coa").attr("disabled", false);
        $("#jenisbank").val("");
        $("#jenisbank").html("");
        $("#norek").val("");
        $("#coa").val("");
        $("#coa").html("");
    }
}

</script>
