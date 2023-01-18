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
                        <label class="col-md-12">Jurnal</label>
                        <div class="col-sm-6">
                            <input id="ijurnal" class="form-control" name="ijurnal" value="" maxlength=13>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal</label>
                            <div class="col-sm-3">
                                <input type="hidden" class="form-control"name="periode" id="periode" value="<?php if($periode) echo $periode; ?>">
                                <input readonly class="form-control date" id="djurnal" name="djurnal">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select id="iarea" name="iarea" class="form-control select2"></select>
                            <input type="hidden" id="eareaname" name="eareaname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-6">
                            <input type="text" id="edescription" name="edescription" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Debet</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vdebet" name="vdebet" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Kredit</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vkredit" name="vkredit" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>CoA</th>
                                <th>Keterangan</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="pesan"></div>
                <input type="hidden" name="jml" id="jml" value="0">
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

    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        
        cols+='<td><select style="width:200px;" type="text" id="icoa'+counter+'" name="icoa'+counter+'"  class="form-control select2" value="" onchange="getcoa('+counter+')"></select></td>';
        cols+='<td><input readonly readonly type="text" id="ecoaname'+counter+'" name="ecoaname'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input type="text" id="vdebet'+counter+'" name="vdebet'+counter+'"  class="form-control" value="" onkeyup="hitung();reformat(this);"></td>';
        cols+='<td><input type="text" id="vkredit'+counter+'" name="vkredit'+counter+'"  class="form-control" value="" onkeyup="hitung();reformat(this);"></td>';
        cols+='<td><input type="button" class="ibtnDel btn btn-md btn-danger " value="Delete"></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
            counter -= 1
            document.getElementById("jml").value = counter;
        });
       
        $('#icoa'+ counter).select2({
        placeholder: 'Pilih Kode CoA',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datacoa/'); ?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
        });
        });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });

    function getcoa(id){
        var icoa = $('#icoa'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_coa': icoa
        },
        url: '<?= base_url($folder.'/cform/getcoa/'); ?>',
        dataType: "json",
        success: function (data) {
            $('#icoa'+id).val(data[0].i_coa);
            $('#ecoaname'+id).val(data[0].e_coa_name);
            //hitungnilai();
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function hitung(){
        brs=document.getElementById("jml").value;
        vdebet =0;
        vkredit=0;
        for(i=1;i<=brs;i++){
           debet=formatulang(document.getElementById("vdebet"+i).value);
           kredit=formatulang(document.getElementById("vkredit"+i).value);
           vdebet = vdebet+parseFloat(debet);
           vkredit= vkredit+parseFloat(kredit);
        }
        document.getElementById("vdebet").value=formatcemua(vdebet);
        document.getElementById("vkredit").value=formatcemua(vkredit);
    }
  function hitungnilaistock(){
    jml=document.getElementById("jml").value;
	for(i=1;i<=jml;i++){
        qty=document.getElementById("nquantitystock"+i).value;
        if (isNaN(parseFloat(qty))){
          alert("Input harus numerik");
          document.getElementById("nquantitystock"+i).value='0';
          break;
        }
    }
  }

  function dipales(a){
    if((document.getElementById("ijurnal").value!='') &&
      (document.getElementById("djurnal").value!='') &&
      (document.getElementById("iarea").value!='') &&
      (document.getElementById("vdebet").value!='0') &&
      (document.getElementById("vkredit").value!='0')
      ) {

      if(a==0){
         alert('Isi data item minimal 2 !!!');
      }else if(document.getElementById("vdebet").value!=document.getElementById("vkredit").value){
         alert('Nilai debet harus sama dengan nilai kredit !!!');
      }else{
         if(parsefloat(formatulang(document.getElementById("vdebet").value))==parsefloat(formatulang(document.getElementById("vkredit").value))){
            document.getElementById("login").disabled=true;
         }else{
            alert("Debet kredit belum sama !!!!!!");
         }
      }
    }else{
         alert('Data header masih ada yang salah !!!');
    }
  }

  function clearitem(){
    document.getElementById("detailisi").innerHTML='';
    document.getElementById("pesan").innerHTML='';
    document.getElementById("jml").value='0';
    document.getElementById("login").disabled=false;
  }
</script>