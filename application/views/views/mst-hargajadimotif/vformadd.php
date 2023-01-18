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
                        <label class="col-md-6">Kode Barang</label>
                        <label class="col-md-6">Kode Barang+Motif</label>
                        <div class="col-sm-6">                            
                            <select name="iproduct" id="iproduct" onchange="kodebarang(this.value);" class="form-control select2">
                             </select>
                        </div>
                        <div class="col-sm-6">
                             <input type="text" name="iproductmotif" id="iproductmotif" class="form-control" readonly>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Harga</label>
                        <div class="col-sm-12">
                            <input type="text" name="vprice" id="vprice" onkeypress="return angka(event)" class="form-control">
                        </div>
                    </div>        
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
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

function kodebarang(iproduct) {
        var iproduct = $('#iproduct').val();
        $.ajax({
            type: "post",
            data: {
                'iproduct': iproduct
            },
            url: '<?= base_url($folder.'/cform/getkodebarang'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iproductmotif').val(data[0].i_product_motif);
                $('#vprice').val(data[0].v_unitprice);
            },
            error: function () {
                alert('Error :)');
            }
        });
}

$(document).ready(function () {
        $('#iproduct').select2({
        placeholder: 'Pilih Kode Barang',
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
      }).on("change", function(e) {
        var kode = $('#iproduct').text();
        kode = kode.split("-");
        $('#eproductmotifname').val(kode[1]);
     });
});

 function angka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>