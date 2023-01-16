<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select id="iarea" name="iarea" class="form-control select2"></select>
                            <input type="hidden" id="eareaname" name="eareaname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Bukti</label>
                            <div class="col-sm-3">
                                <input readonly class="form-control date" id="dbukti" name="dbukti">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">No Bukti</label>
                        <div class="col-sm-6">
                            <input type="text" id="ibukti" class="form-control" name="ibukti" value="" maxlength=13>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Uraian</label>
                        <div class="col-sm-6">
                            <select name="iikhptype" id="iikhptype" class="form-control select2" onchange="getcoa(this.value)"></select>
							<input type="hidden" name="icoa" id="icoa" value="" class="form-control">
							<input type="hidden" name="ecoaname" id="ecoaname" value="" class="form-control">
							<input type="hidden" name="eikhptypename" id="eikhptypename" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Terima Tunai</label>
                        <div class="col-sm-6">
                            <input type="text" id="vterimatunai" name="vterimatunai" class="form-control" value="" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Terima Giro</label>
                        <div class="col-sm-6">
                            <input type="text" id="vterimagiro" name="vterimagiro" class="form-control" value="" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keluar Tunai</label>
                        <div class="col-sm-6">
                            <input type="text" id="vkeluartunai" name="vkeluartunai" class="form-control" value="" onkeyup="reformat(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keluar Giro</label>
                        <div class="col-sm-6">
                            <input type="text" id="vkeluargiro" name="vkeluargiro" class="form-control" value="" onkeyup="reformat(this);">
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
        $('.select2').select2();
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

    $(document).ready(function () {
    $('#iikhptype').select2({
    placeholder: 'Pilih Uraian',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/datauraian'); ?>',
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
      var kode = $('#iikhptype').text();
      kode = kode.split("-");
      $('#eikhptypename').val(kode[1]);
    });
    });

    function getcoa(id){
        var iikhp = $('#iikhptype').val();
        $.ajax({
        type: "post",
        data: {
            'i_ikhp_type': iikhp
        },
        url: '<?= base_url($folder.'/cform/getcoa/'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iikhptype').val(data[0].i_ikhp_type);
            $('#eikhptypename').val(data[0].e_ikhp_typename);
            $('#icoa').val(data[0].i_coa);
            $('#ecoaname').val(data[0].e_coa_name);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function dipales(){
		if((document.getElementById("dbukti").value == '') || (document.getElementById("eikhptypename").value == '') || (document.getElementById("iarea").value == '') ||
			( (document.getElementById("vterimatunai").value=='') && (document.getElementById("vterimagiro").value=='') && 
			(document.getElementById("vkeluartunai").value=='') && (document.getElementById("vkeluargiro").value=='') )){
			alert("Data Header belum lengkap !!!");
		}else{			
			document.getElementById("login").disabled=true;
		}
	}

	function tesss(){
		document.getElementById("iikhpkeluar").value="";
		document.getElementById("eikhpkeluarname").value="";
		document.getElementById("login").disabled=false;
		document.getElementById("pesan").innerHTML='';
	}
</script>