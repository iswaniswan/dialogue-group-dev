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
                        <label class="col-md-4">Kode Grup CoA</label>
                        <label class="col-md-5">Nama Grup CoA</label>
                        <label class="col-md-3">Tipe CoA</label>
                        <div class="col-sm-4">
                            <input type="text" name="iledger" id="iledger" class="form-control input-sm" required="" maxlength="10" onkeyup="gede(this)" value="<?= $data->i_coa_ledger; ?>" placeholder="Kode Group Coa (Exp: 110-12)">
                            <input class="form-control" type="hidden" name="ikodeold" id="ikodeold" value = "<?=$data->i_coa_ledger;?>" readonly>
                            <input class="form-control" type="hidden" name="id" id="id" value = "<?=$data->id;?>" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" name="eledger" class="form-control" required=""  onkeyup="gede(this)" value="<?= $data->e_coa_ledger_name; ?>" placeholder="Nama Group Coa">
                        </div>
                        <div class="col-sm-3">
                           <select name="icoatype" id="icoatype" class="form-control select2">
                                <option value="<?=$data->id_coa_type;?>"><?=$data->e_coa_type_name;?></option>
                            </select>
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
                        <span style="color: #8B0000">* Standar Kode Grup CoA mengikuti standar dari grup / akunting grup</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : 110-40000, 110-80000, dst</span>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
 $(document).ready(function () {
    $(".select2").select2();
    $('#iledger').mask('000-000000');
    $('#icoatype').select2({
        placeholder: 'Pilih Tipe CoA',
        allowClear: true,
        ajax: {
        url: '<?= base_url($folder.'/cform/typecoa'); ?>',
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

    $( "#iledger" ).keyup(function() {
        var kode = $('#iledger').val();
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
    });

    $(document).ready(function () {
        $( "#iledger" ).focus();
    });
 });

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
</script>
