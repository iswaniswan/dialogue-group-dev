<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
        <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>

            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/export'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="form-group row">
                        <label class="col-md-12">Periode (Bulan / Tahun)</label>
                        <div class="col-sm-3">
                            <input type="hidden" id="iperiode" name="iperiode">
                            <select name="iperiodebl" id="iperiodebl" class="form-control" required="" onmouseup="buatperiode()">
                            <option></option>
							<option value='01'>Januari</option>
							<option value='02'>Pebruari</option>
							<option value='03'>Maret</option>
							<option value='04'>April</option>
							<option value='05'>Mei</option>
							<option value='06'>Juni</option>
							<option value='07'>Juli</option>
							<option value='08'>Agustus</option>
							<option value='09'>September</option>
							<option value='10'>Oktober</option>
							<option value='11'>November</option>
							<option value='12'>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                        <select name="iperiodeth" id="iperiodeth" class="form-control" required="" onmouseup="buatperiode()">
                            <option></option>
                            <?php 
                               $tahun1 = date('Y')-3;
                               $tahun2 = date('Y');
                               for($i=$tahun1;$i<=$tahun2;$i++)
                               {
                                  echo "<option value='$i'>$i</option>";
                               }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-3">
                            <select id="iarea" name="iarea" class="form-control select2"></select>
			                <input type="hidden" id="eareaname" name="eareaname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                        <a id="href"><button type="button" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
   $(".select2").select2();
   showCalendar('.date');
});

$(document).ready(function () {
    $('#iarea').select2({
    placeholder: 'Pilih Area',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/dataarea'); ?>',
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
      var kode = $('#iarea').text();
      kode = kode.split("-");
      $('#eareaname').val(kode[1]);
    });
});

function buatperiode(){
      periode=document.getElementById("iperiodeth").value+document.getElementById("iperiodebl").value;
      //alert(periode);
	  document.getElementById("iperiode").value=periode;
}

$('#iperiodebl, #iperiodeth, #iarea').on('change',function(){
    var iperiodebl = $('#iperiodebl').val();
    var iperiodeth = $('#iperiodeth').val();
    var iarea = $('#iarea').val();
    $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+iperiodeth+iperiodebl +'/' +iarea);
});
</script>
