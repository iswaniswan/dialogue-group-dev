<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
        <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">No Faktur Pajak</label>
                        <div class="col-sm-3">
                            <input id="istart" name="istart" value="" maxlength=6>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Date From</label>
                        <div class="col-sm-6">
                            <input readonly name="dfrom" id="dfrom" class="form-control date" value="" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Date To</label>
                        <div class="col-sm-6">
                            <input readonly name="dto" id="dto" class="form-control date" value= "" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Seri Pajak Awal</label>
                        <div class="col-sm-3">
                            <?
                                if($company == 1){?>
                                    <input type="text" id="iseripajak" name="iseripajak" value="010.003-20.992" maxlength=19>
                               <? } else if ($company == 2){?>
                                    <input type="text" id="iseripajak" name="iseripajak" value="010.000.16.9995" maxlength=19>
                               <? } else if ($company == 3){?>
                                    <input type="text" id="iseripajak" name="iseripajak" value="010.003.20.56525584" maxlength=19>
                               <? } else if ($company == 7){?>
                                    <input type="text" id="iseripajak" name="iseripajak" value="010.001.16.091" maxlength=19>
                               <? } else if ($company == 8){?>
                                    <input type="text" id="iseripajak" name="iseripajak" value="010.002.20.194" maxlength=19>
                               <? }
                            ?>
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Nota From</label>
                        <div class="col-sm-6">
                            <select name="notafrom" id="notafrom" class="form-control select2" required="">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Nota To</label>
                        <div class="col-sm-6">
                            <select name="notato" id="notato" class="form-control select2" required="">
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
   $(".select2").select2();
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
        showCalendar('.date');
});

$(document).ready(function () {
        $('#notafrom').select2({
        placeholder: 'Pilih Nota',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datanotafrom/'); ?>',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var dfrom = $("#dfrom").val();
            var dto   = $("#dto").val();
            var to    = $("#notato").val();
            var query   = {
                q       : params.term,
                dfrom : dfrom,
                dto   : dto,
                to    : to
            }
            return query;
          },
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
});

$(document).ready(function () {
        $('#notato').select2({
        placeholder: 'Pilih Nota',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datanotato/'); ?>',
          dataType: 'json',
          delay: 250,
          data: function (params) {
            var dfrom = $("#dfrom").val();
            var dto   = $("#dto").val();
            var from    = $("#notafrom").val();
            var query   = {
                q       : params.term,
                dfrom   : dfrom,
                dto     : dto,
                from    : from
            }
            return query;
          },
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
});
</script>
