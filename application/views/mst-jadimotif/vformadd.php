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
                    <div class="form-group">
                        <label class="col-md-12">Kode Barang</label>
                        <div class="col-sm-12">
                            <select name="iproduct" id="iproduct" class="form-control select2" onchange="kodebarang(this.value);">                          
                        </select>
                        </div>
                    </div>      
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-12">
                            <input type="text" name="eproductmotifname" id="eproductmotifname" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Quantity</label>
                        <div class="col-sm-12">
                            <input type="text" name="nquantity" class="form-control" value="" >
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Motif/Warna</label>
                        <div class="col-sm-12">
                            <select name="icolor" id="icolor" id="icolor" class="form-control select2" >
                        </select>
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
                $('#eproductmotifname').val(data[0].e_product_basename);             
            },
            error: function () {
                alert('Error :)');
            }
        });
}

$(document).ready(function () {
        $('#icolor').select2({
        placeholder: 'Pilih Nama Motif/Warna',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/getkodemotifwarna'); ?>',
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
        var kode = $('#icolor').text();
        kode = kode.split("-");
        $('#iproductmotif').val(kode[1]);
     });
});

function kodemotif(icolor) {
        var icolor = $('#icolor').val();
        $.ajax({
            type: "post",
            data: {
                'icolor': icolor
            },
            url: '<?= base_url($folder.'/cform/getkodemotif'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iproductmotif').val(data[0].i_kode_color);
            },
            error: function () {
                alert('Error :)');
            }
        });
}

/*function kodemotif(sel){
    var x = document.getElementById("iproduct").value;
    var y = sel.value;
    var y1 = y.split(",");
    var y2 = y1[1];
    document.getElementById("iproductmotif").value = x+y2;
}*/
</script>
