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
               <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Bank</label>
                        <label class="col-md-5">Nama Bank</label>
                        <label class="col-md-4">Jenis Bank</label>
                        <div class="col-sm-3">
                            <input type="text" name="ibank" id="ibank" class="form-control" required="" onkeyup="gede(this)" value="<?=$data->i_bank;?>" placeholder="Kode Bank (Exp : 014)">
                            <input class="form-control" type="hidden" name="ikodeold" id="ikodeold" value = "<?=$data->i_bank;?>" readonly>
                            <input class="form-control" type="hidden" name="id" id="id" value = "<?=$data->id;?>" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="ebankname" class="form-control" value="<?=$data->e_bank_name;?>" onkeyup="gede(this)" placeholder="Nama Bank (Exp: BCA)">
                        </div>
                        <div class="col-sm-4">
                            <select name="jenis" id="jenis" class="form-control select2">
                               <option value="<?=$data->i_jenis;?>"><?=$data->e_jenis_name;?></option>
                            </select>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Bank terdiri dari 3 (tiga) angka sesuai dengan yang telah distandarkan oleh Bank Indonesia (BI)</span><br><br>

                        <span style="color: #8B0000"><b>Contoh : </b></span><br>
                        <span style="color: #8B0000">* 002 (untuk BRI) </span><br>
                        <span style="color: #8B0000">* 014 (untuk BCA) </span><br>
                        <span style="color: #8B0000">* dst </span><br>
                    </div>
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

    $('#jenis').select2({
        placeholder: 'Pilih Jenis Bank',
        allowClear: true,
        ajax: {
        url: '<?= base_url($folder.'/cform/jenisbank'); ?>',
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

    $( "#ibank" ).keyup(function() {
        var kode = $('#ibank').val();
        var kodeold = $('#ikodeold').val();
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && kodeold!=kode) {
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
        $(document).ready(function () {
            $( "#ibank" ).focus();
        });
    });
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
