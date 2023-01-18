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
                <div id="pesan"></div>
                <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-md-2">Kode Kas</label>
                        <label class="col-md-4">Nama Kas</label>
                        <label class="col-md-2">Jenis Kas</label>
                        <label class="col-md-4">COA</label>
                        <div class="col-sm-2">
                            <input type="text" name="ijenis" class="form-control" maxlength="7" onkeyup="gede(this)" value="<?= $data->i_kode_kas; ?>" onblur="checklength(this)" readonly>
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ejenisvoucher" class="form-control" maxlength="100"  value="<?= $data->e_kas_name; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                           <select name="jeniskas" id="jeniskas" class="form-control select2" onchange="lihatrinci(this.value);" disabled="">
                                <option value="<?=$data->i_kas_type;?>"><?=$data->e_kas_type_name;?></option> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="coa" id="coa" class="form-control select2" disabled="">
                                <option value="<?= $data->i_coa; ?>"><?= $data->e_coa_name; ?></option>
                           </select>    
                        </div>
                    </div>
                </div>
                <?php $d = 'disabled="true"';?>
                <div class="col-md-12">
                     <div class="form-group row">
                        <label class="col-md-4">Nama Bank</label>
                        <label class="col-md-4">Nama Rekening</label>
                        <label class="col-md-4">Nomor Rekening</label>
                        <div class="col-sm-4">
                           <select name="jenisbank" id="jenisbank" class="form-control select2" <?php echo $d;?>>
                                <option value="<?= $data->i_bank; ?>"><?= $data->e_bank_name; ?></option>
                           </select>    
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="namarek" name="namarek" class="form-control" readonly value="<?=$data->e_nama_rekening;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="norek" name="norek" class="form-control" maxlength="50" value="<?= $data->e_nomor_rekening; ?>" <?php echo $d;?>>
                        </div>
                        <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                        </div>
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
});

function checklength(el){
    if(el.value.length != 7){
        swal("Kode Harus 7 Karakter");
    }
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

    function lihatrinci(jenis) {
        if (jenis != "01") {
            $("#jenisbank").attr("disabled", true);
            $("#norek").attr("disabled", true);
            $("#jenisbank").val("");
            $("#jenisbank").html("");
            $("#norek").val("");
        } else {
            $("#jenisbank").attr("disabled", false);
            $("#norek").attr("disabled", false);
            $("#jenisbank").val("");
            $("#jenisbank").html("");
            $("#norek").val("");
            //$("#isjkeluar").attr("hidden", false);
        }
    }

</script>
