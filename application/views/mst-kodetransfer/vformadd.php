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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Pelanggan (Transfer)</label>
                        <label class="col-md-6">Pelanggan</label>
                        <div class="col-sm-6">
                            <input type="text" name="icustomertransfer" class="form-control" maxlength="7" value="" onblur="checklength(this)">
                        </div>
                        <div class="col-sm-6">
                        <select name="icustomer" class="form-control select2">
                            <option value="">Pilih Pelanggan</option>
                            <?php foreach ($pelanggan as $icustomer):?>
                                <option value="<?php echo $icustomer->i_customer;?>">
                                    <?php echo $icustomer->i_customer."-".$icustomer->e_customer_name;?></option>
                            <?php endforeach; ?>
                        </select>  
                        </div>
                    </div>                               
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
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
</script>