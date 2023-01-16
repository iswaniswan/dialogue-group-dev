<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
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
                            <input readonly type="text" name="icoa" id="icoa" class="form-control" required="" onkeyup="gede(this)"  value="<?= $data->i_coa; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ecoaname" id="ecoaname" class="form-control" maxlength="30"  value="<?= $data->e_coa_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                           <select name="icoagroup" id="icoagroup" class="form-control select2" disabled>
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
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
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
 });

function checklength(el){
    if(el.value.length != 9){
        swal("Kode Harus 9 Karakter");
    }
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
