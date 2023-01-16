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
                        <label class="col-md-2">Kode CoA</label>
                        <label class="col-md-4">Nama CoA</label>
                        <label class="col-md-3">Grup CoA</label>
                        <label class="col-md-3">Tipe CoA</label>
                        <div class="col-sm-2">
                            <input type="text" name="icoa" id="icoa"  class="form-control input-sm" required="" maxlength="10" onkeyup="gede(this)" value="<?= $data->i_coa; ?>">
                            <input class="form-control" type="hidden" name="id" id="id" value = "<?=$data->id;?>" readonly>
                            <input class="form-control" type="hidden" name="ikodeold" id="ikodeold" value = "<?=$data->i_coa;?>" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ecoaname" id="ecoaname" class="form-control" onkeyup="gede(this)" value="<?= $data->e_coa_name; ?>">
                        </div>
                        <div class="col-sm-3">
                           <select name="icoagroup" id="icoagroup" class="form-control select2" onchange="gettype(this.value);">
                                <option value="<?=$data->i_coa_ledger;?>"><?=$data->e_coa_ledger_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="icoatype" id="icoatype" class="form-control" value="<?=$data->id_coa_type;?>">
                            <input type="text" name="ecoatype" id="ecoatype" class="form-control" value="<?=$data->e_coa_type_name;?>" readonly>
                        </div>
                    </div>                     
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
                    </div>   
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Kode CoA diisi sesuai dengan standar dari Akunting</span><br>
                    </div>
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
    $('#icoa').mask('000-000000');
    $('#icoagroup').select2({
        placeholder: 'Pilih Group CoA',
        allowClear: true,
        ajax: {
        url: '<?= base_url($folder.'/cform/groupcoa'); ?>',
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


    $( "#icoa" ).keyup(function() {
        var kode = $('#icoa').val();
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
        $( "#icoa" ).focus();
    });
 });

 function gettype(id){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/gettype');?>",
        data:"id="+id,
        dataType: 'json',
        success: function(data){
            $("#icoatype").val(data.icoatype);
            $("#ecoatype").val(data.ecoatype);
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    });
}

function cek() {
    var coa = $("#icoa").val();
    var nama = $("#ecoaname").val();
    var group = $("#icoagroup").val();
    var type = $("#icoatype").val();

    if (coa!='' || nama!='' || group!='' || type!='') {
        return true;
    } else {
        swal('Data Header Tidak Lengkap');
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
