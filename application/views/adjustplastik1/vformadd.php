<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Gudang</label>
                        <label class="col-md-4">Tanggal Adjusment</label>
                        <div class="col-sm-8">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                    <option value="" selected>-- Pilih Gudang --</option>
                                    <?php foreach ($kodemaster as $ikodemaster):?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>">
                                        <?=$ikodemaster->e_nama_master;?></option>
                                    <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dadjus" name="dadjus" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                        </div>
                    </div>
                     <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                <div class="col-md-6">
                    <label class="col-md-6">Jenis Barang</label>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <select name="ejenisbarang" id="ejenisbarang" class="form-control select2" onchange="setjenisbarang();">
                                <option value="JBR" selected>-- Semua Jenis --</option>
                                <?php foreach ($jenisbarang as $jenisbarang):?>
                                <option value="<?php echo $jenisbarang->i_type_code;?>"><?= $jenisbarang->e_type_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="ijenisbarang" name="ijenisbarang" class="form-control" value="JBR">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th width="35%">Nama barang</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#send").attr("disabled", false);
});

var counter = 0;
     $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" readonly id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial' + counter + '" value=""></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname'+ counter + '" value="" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="number" id="nquantity'+ counter + '" class="form-control" placeholder="0" name="nquantity'+ counter + '" value="0"/></td>';
        cols += '<td><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '" value=""/><input type="hidden" id="namabarang'+ counter + '" class="form-control" name="namabarang'+ counter + '" /></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        // $('#ikodemaster').attr("disabled", true);
        var ijenisbarang = $('#ijenisbarang').val();
        alert (ijenisbarang);
        var istore = $('#istore').val();
        $('#ematerialname'+ counter).select2({
        
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        type: "POST",
        ajax: {          
          url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+istore+'/'+ijenisbarang,
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

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

$(document).ready(function(){
    $("#send").attr("disabled", true);
    $("#send").on("click", function () {
        var kode = $("#kode").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

function getenabledsend() {
    $('#send').attr("disabled", true);
}

function setjenisbarang() {
    var ejenisbarang = $('#ejenisbarang').val();
    $('#ijenisbarang').val(ejenisbarang);     
}

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        
    }else{
      alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }

  function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }

    function getjenisbarang(id){
        var ejenisbarang = $('#ejenisbarang'+id).val();
        //alert(ejenisbarang);
        $.ajax({
            type: "post",
            data: {
                'ejenisbarang': ejenisbarang
            },
            url: '<?= base_url($folder.'/cform/getjenisbarang'); ?>',
            dataType: "json",
            success: function (data) {
                ada=false;
                var a = $('#ejenisbarang'+id).val();
                var jml = $('#jml').val();
                for(i=1;i<=jml;i++){
                    if((a == $('#ijenisbarang'+i).val()) && (i!=jml)){
                        swal ("kode : "+a+" sudah ada !!!!!");
                        ada=true;
                        break;
                    }else{
                        ada=false;     
                    }
                }
                if(!ada){
                    $('#ijenisbarang'+id).val(data[0].i_type_code);
                }else{
                    $('#ijenisbarang'+id).val('');
                    $('#ejenisbarang'+id).val('');
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getmaterial(id){
        var imaterial = $('#ematerialname'+id).val();
        $.ajax({
                type: "post",
                data: {
                    'i_material': imaterial
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    ada=false;
                    var a = $('#ematerialname'+id).val();
                    var jml = $('#jml').val();
                    for(i=1;i<=jml;i++){
                        if((a == $('#imaterial'+i).val()) && (i!=jml)){
                            swal ("kode : "+a+" sudah ada !!!!!");
                            ada=true;
                            break;
                        }else{
                            ada=false;     
                        }
                    }
                    if(!ada){
                        $('#imaterial'+id).val(data[0].i_material);
                        $('#namabarang'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#isatuan'+id).val(data[0].i_satuan_code);
                    }else{
                        $('#imaterial'+id).html('');
                        $('#ematerialname'+id).html('');
                        $('#ematerialname'+id).val('');
                        $('#namabarang'+id).val('');
                        $('#isatuan'+id).val('');
                        $('#esatuan'+id).val('');
                        // $('#esatuan'+id).val('');
                    }
                },
                error: function () {
                    alert('Error :)');
                }
            });
    }
    

    function cek() {
        var dadjus = $('#dadjus').val();

        if (dadjus == '') {
            alert('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }

    function getstore() {
        var gudang = $('#ikodemaster').val();
        //alert(gudang);
        $('#istore').val(gudang);

        if (gudang == "") {
            $("#addrow").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
            $("#ikodemaster").attr("disabled", true);
        }
        
    }
</script>