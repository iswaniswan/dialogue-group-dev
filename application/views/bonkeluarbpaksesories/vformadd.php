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
            <div class="form-group">
                <label class="col-md-12">Tujuan Kirim</label>
                <div class="col-sm-12">
                    <select name="itujuankirim" id="itujuankirim" class="form-control select2" onchange="get(this.value);">
                        <option value="">Pilih Gudang</option>
                        <?php foreach ($kodemaster as $itujuankirim):?>
                        <option value="<?php echo $itujuankirim->i_kode_master;?>">
                            <?= $itujuankirim->i_kode_master." - ".$itujuankirim->e_nama_master;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12">Tanggal Bon</label>
                <div class="col-sm-7">
                    <input type="text" id= "dbonk" name="dbonk" class="form-control date"  readonly value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"> 
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>  
                </div>
            </div>
            <input type="text" name="jml" id="jml" value ="0">
            </div>
            <div class="col-md-6">                 
                <div class="form-group">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                    <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                    </div>
                </div>
            </div>            
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th width="20%">Kode Barang</th>
                                <th>Nama barang</th>
                                <th>Satuan</th>
                                <th>Qty</th>                                
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
    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td><input readonly style=width:40px; id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" value="" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '" value="" readonly></td>';
        cols += '<td><input type="text" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '"><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" readonly></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#imaterial'+ counter).select2({
        placeholder: 'Pilih Material',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial'); ?>',
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function getmaterial(id){
        var imaterial = $('#imaterial'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_material': imaterial
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ematerialname'+id).val(data[0].e_material_name);
            $('#esatuan'+id).val(data[0].e_satuan);
            $('#isatuan'+id).val(data[0].i_satuan);
            $('#esatuankonv'+id).val(data[0].i_convertion);
        },
        error: function () {
            alert('Error :)');
        }
    });
    } 

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=input]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });

    if (document.getElementById('itujuankirim').value=='') {
        swal("Maaf Tolong Pilih Tujuan Kirim!");
        return false;
    }else if(document.getElementById('dbonk').value=='') {
        swal("Maaf Tolong Pilih Date!");
        return false;
    }else {
        return true
    }
}       
</script>