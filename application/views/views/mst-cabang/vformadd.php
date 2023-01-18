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
                        <label class="col-md-6">Kode Cabang</label>
                        <label class="col-md-6">Nama Cabang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ibranch" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="" onblur="checklength(this)">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="ebranchname" class="form-control"  value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Kode Area</label>
                        <label class="col-md-6">Inisial</label>
                        <div class="col-sm-6">
                            <input type="text" name="ecodearea" class="form-control" required="" maxlength="7" onkeyup="gede(this)" value="">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="einitial" id="einitial" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 1</label>
                        <label class="col-md-6">Discount 2</label>
                        <div class="col-sm-6">
                            <input type="text" name="ncustomerdiscount1" id="ncustomerdiscount1" class="form-control" maxlength="4"  value="0" >
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="ncustomerdiscount2" id="ncustomerdiscount2" class="form-control" maxlength="4"  value="0" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>                      
                        </div>
                    </div>
                </div>
                <div class="col-md-6">                    
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">Kota</label>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer"  class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="ecity" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="ebranchaddress" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Discount 3</label>
                        <div class="col-sm-6">
                            <input type="text" name="ncustomerdiscount3" id="ncustomerdiscount3" class="form-control" maxlength="4"  value="0" >
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

//  function kodepelanggan(icustomer) {
//         var icustomer = $('#icustomer').val();
//         $.ajax({
//             type: "post",
//             data: {
//                 'icustomer': icustomer
//             },
//             url: '<?= base_url($folder.'/cform/getkodepelanggan'); ?>',
//             dataType: "json",
//             success: function (data) {
//                 $('#einitial').val(data[0].i_customer);
//             },
//             error: function () {
//                 alert('Error :)');
//             }
//         });
// }

$(document).ready(function () {
    $('#icustomer').select2({
    placeholder: 'Pilih Pelanggan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/getkode'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

function checklength(el){
    if(el.value.length != 7){
        swal("Kode Harus 7 Karakter");
    }
}
</script>
