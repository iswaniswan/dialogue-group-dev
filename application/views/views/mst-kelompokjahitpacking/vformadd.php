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
                        <label class="col-md-12">Nama Kelompok Unit Jahit dan Packing</label>
                        <div class="col-sm-12">
                            <input type="text" name="enamajahitpacking" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div>   
                    <div class="form-group">
                        <label class="col-md-12">Kode Unit Jahit</label>
                        <div class="col-sm-12">
                        <select name="iunitjahit" id="iunitjahit" class="form-control select2" onchange="kodejahit(this.value);">
                           <!-- <option value="">Pilih Kode Unit Packing</option>
                            <?php foreach ($unit_jahit as $iunitjahit):?>
                                <option value="<?php echo $iunitjahit->i_unit_jahit;?>">
                                    <?php echo $iunitjahit->i_unit_jahit;?></option>
                            <?php endforeach; ?>-->
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Unit Jahit</label>
                        <div class="col-sm-12">
                        <input type="text" name="eunitjahitname" id="eunitjahitname" class="form-control" maxlength="30"  value="" readonly>
                        <!--<select name="eunitjahitname" class="form-control select2">
                            <option value="">Pilih Nama Unit Jahit</option>
                            <?php foreach ($unit_jahit as $eunitjahitname):?>
                                <option value="<?php echo $eunitjahitname->e_unitjahit_name;?>">
                                    <?php echo $eunitjahitname->e_unitjahit_name;?></option>
                            <?php endforeach; ?>
                        </select>-->
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Kode Unit Packing</label>
                        <div class="col-sm-12">                            
                        <select name="iunitpacking" id="iunitpacking" class="form-control select2" onchange="kodeunit(this.value);">
                            <!--<option value="">Pilih Kode Unit Packing</option>
                            <?php foreach ($unit_packing as $iunitpacking):?>
                                <option value="<?php echo $iunitpacking->i_unit_packing;?>">
                                    <?php echo $iunitpacking->i_unit_packing;?></option>
                            <?php endforeach; ?>-->
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Unit Packing</label>
                        <div class="col-sm-12">
                        <input type="text" name="enamapacking" id="enamapacking" class="form-control" maxlength="30"  value="" readonly>
                        <!--<select name="enamapacking" class="form-control select2">
                            <option value="">Pilih Nama Unit Packing</option>
                            <?php foreach ($unit_packing as $enamapacking):?>
                                <option value="<?php echo $enamapacking->e_nama_packing;?>">
                                    <?php echo $enamapacking->e_nama_packing;?></option>
                            <?php endforeach; ?>
                        </select>-->
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
        $('#iunitpacking').select2({
        placeholder: 'Pilih Kode Unit Packing',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/getkodepacking'); ?>',
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
        var kode = $('#iunitpacking').text();
        kode = kode.split("-");
        $('#enamapacking').val(kode[1]);
     });

      $('#iunitjahit').select2({
        placeholder: 'Pilih Kode Unit Jahit',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/getkodejahit'); ?>',
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
        var kode = $('#iunitjahit').text();
        kode = kode.split("-");
        $('#eunitjahitname').val(kode[1]);
     });
});

 function kodeunit(iunitpacking) {
        var iunitpacking = $('#iunitpacking').val();
        $.ajax({
            type: "post",
            data: {
                'iunitpacking': iunitpacking
            },
            url: '<?= base_url($folder.'/cform/getkodeunit'); ?>',
            dataType: "json",
            success: function (data) {
                $('#enamapacking').val(data[0].e_nama_packing);
            },
            error: function () {
                alert('Error :)');
            }
        });
}

function kodejahit(iunitjahit) {
        var iunitjahit = $('#iunitjahit').val();
        $.ajax({
            type: "post",
            data: {
                'iunitjahit': iunitjahit
            },
            url: '<?= base_url($folder.'/cform/getkodejahit2'); ?>',
            dataType: "json",
            success: function (data) {
                $('#eunitjahitname').val(data[0].e_unitjahit_name);
            },
            error: function () {
                alert('Error :)');
            }
        });
}

</script>
