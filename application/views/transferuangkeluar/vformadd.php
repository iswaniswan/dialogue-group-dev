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
                        <label class="col-md-12">Bukti transfer</label>
                        <div class="col-sm-5">
                            <input id="ikuk" name="ikuk" class="form-control" maxlength="15">
                        </div>
                        <div class="col-sm-3">
                            <input readonly id="dkuk" name="dkuk" class="form-control date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pemasok</label>
                        <div class="col-sm-6">
                            <select id="isupplier" class="form-control select2" name="isupplier"></select>
                            <input type="hidden" name="esuppliername" id="esuppliername" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nama Bank</label>
                        <div class="col-sm-6">
                            <select readonly name="ebankname" id="ebankname" class="form-control">
						    	<option selected>BCA Bandung</option>
						    	<option selected>BCA Jakarta</option>
						    	<option selected>Permata Bandung</option>
						    	<option selected>BRI BAndung</option>
						    	<option selected>Pilih Bank</option>
						    </select>
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
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-6">
                            <input id="eremark" name="eremark" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-6">
                            <input id="vjumlah" name="vjumlah" class="form-control" placeholder="0" value="" onkeyup="reformat(this);hetang(this);">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Sisa</label>
                        <div class="col-sm-6">
                            <input type="text" id="vsisa" name="vsisa" class="form-control" placeholder="0" value="">
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
    $('#isupplier').select2({
    placeholder: 'Pilih Supplier',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/datasupplier'); ?>',
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
      var kode = $('#isupplier').text();
      kode = kode.split("-");
      $('#esuppliername').val(kode[1]);
    });
    });

    function dipales(){
		if((document.getElementById("ikuk").value=='')||(document.getElementById("dkuk").value=='')){
			alert("Data Belum Lengkap !!!");
		}else{			
			document.getElementById("login").disabled=true;
		}
    }
	function hetang(){
		document.getElementById("vsisa").value=document.getElementById("vjumlah").value;
	}
</script>