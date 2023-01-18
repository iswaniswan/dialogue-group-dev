<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4">Gudang</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getkategori(this.value);">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Dari Tanggal</label>
                        <label class="col-md-8">Sampai Tanggal</label>
                        <div class="col-sm-4">
                            <input type="text" id= "dfrom" name="dfrom" class="form-control date"  readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                         <div class="col-sm-4">
                            <input type="text" id= "dto" name="dto" class="form-control date"  readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>                    
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Kategori Barang</label>
                        <div class="col-sm-6">
                            <select name="ikelompok" id="ikelompok" class="form-control select2" onchange="getjenis(this.value);">
                               
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Jenis Barang</label>
                        <div class="col-sm-6">
                            <select name="jnsbarang" id="jnsbarang" class="form-control select2" >
                               
                            </select>
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
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
$('#ikodemaster').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
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

function getkategori(ikodemaster) {
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkategori');?>",
        data:"ikodemaster="+ikodemaster,
        dataType: 'json',
        success: function(data){
            $("#ikelompok").html(data.kop);
            getjenis();
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function getjenis($ikelompok) {
    //var ikodemaster = $('#ikodemaster').val();
    var ikelompok = $('#ikelompok').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenis');?>",
        data:{
                'ikelompok':ikelompok,
               // 'ikodemaster':ikodemaster,
        },
        dataType: 'json',
        success: function(data){
            $("#jnsbarang").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}
</script>