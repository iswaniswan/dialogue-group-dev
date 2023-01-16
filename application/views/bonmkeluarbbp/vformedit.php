<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">No BonK</label>
                        <label class="col-md-4">Tanggal BonM</label>
                        <div class="col-sm-4">
                            <input type="text" id = "enamamaster" name="enamamaster" class="form-control" value="<?= $data->e_nama_master;?>"readonly>
                            <input type="hidden" id = "ikodemaster" name="ikodemaster" class="form-control" value="<?= $data->i_kode_master;?>"readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id = "nobonk" name="nobonk" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_bonk;?>"readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dbonk" id="dbonk" class="form-control date" value="<?= $data->d_bonk; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $data->e_remark; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Jenis keluar</label>
                        <div class="col-sm-6">
                            <input type="text" id = "enamajenis" name="enamajenis" class="form-control" value="<?= $data->e_jenis_keluar;?>"readonly>
                            <input type="hidden" id = "jnskeluar" name="jnskeluar" class="form-control" value="<?= $data->i_jenis_keluar;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Tujuan</label>
                        <label class="col-md-6">Tujuan kirim</label>
                        <div class="col-sm-6">
                            <input type="text" id = "enamajenis" name="enamajenis" class="form-control" value="<?= $data->e_tujuan;?>"readonly>
                            <input type="hidden" id = "itujuan" name="itujuan" class="form-control" value="<?= $data->i_tujuan_kirim;?>"readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" id = "itujuankirim" name="itujuankirim" class="form-control" value="<?= $data->i_tujuan_kirim;?>"readonly>
                            <input type="text" id = "etujuan" name="etujuan" class="form-control" value="<?= $data->tujuan;?>"readonly>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="10%">Kode Barang</th>
                                    <th width="30%">Nama barang</th>
                                    <th>Satuan</th>
                                    <th>Satuan Konversi</th>
                                    <th>Qty</th>
                                    <th>Qty Konversi</th>
                                    <th>Keterangan</th> 
                                    <th>Bis Bisan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($data2 as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:160px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_material; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:300px" class="form-control" type="text" id="ematerialname<?=$i;?>" name="ematerialname[]"value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan[]"value="<?= $row->i_satuan; ?>" readonly >
                                    <input style ="width:150px" class="form-control" type="text" id="esatuan<?=$i;?>" name="esatuan[]"value="<?= $row->e_satuan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input class="form-control" type="text" id="esatuankonv<?=$i;?>" name="esatuankonv[]" value="<?= $row->i_convertion; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px" type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]"value="<?= $row->n_qty; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px" type="text" class="form-control" id="nquantitykonv<?=$i;?>" name="nquantitykonv[]"value="<?= $row->n_qty_unit_first; ?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>" >
                                </td>   
                                <td class="col-sm-1">
                                    <select style ="width:80px" name="fbisbisan[]" class="form-control select2">
                                        <option value="f" <?php if($row->f_bisbisan =='f') { ?> selected <?php } ?> >Tidak</option>
                                        <option value="t" <?php if($row->f_bisbisan =='t') { ?> selected <?php } ?> >Ya</option> 
                                    </select> 
                                </td>                             
                                <td>
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                                </tr>
                                <?php } ?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
});

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

}

$(document).ready(function () {
    // var counter = 0;
  var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var ikodemaster     = $("#ikodemaster").val();
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        if(cols =! ""){       
        
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan[]" value="" readonly onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan[]" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="esatuankonv'+ counter + '" class="form-control" name="esatuankonv[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantitykonv'+ counter + '" class="form-control" name="nquantitykonv[]" value="" onkeyup="cekqty('+counter+');"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols +='<td><select style="width:80px;" class="form-control select2" type="text" id="fbisbisan'+ counter +'" class="form-control select2" name="fbisbisan[]"><option value="t">Ya</option><option value="f">Tidak</option></select></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        }

        var ikodemaster = $('#ikodemaster').val();
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ematerialname'+ counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+ikodemaster,
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
        // counter -= 1
        // document.getElementById("jml").value = counter;
    });
});

function getmaterial(id){
        var ematerialname = $('#ematerialname'+id).val();
        $.ajax({
        type: "post",
        data: {
            'ematerialname': ematerialname
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function (data) {
            $('#imaterial'+id).val(data[0].i_material);

            $('#esatuan'+id).val(data[0].e_satuan);
            $('#isatuan'+id).val(data[0].i_satuan);

            ada=false;
            var a = $('#imaterial'+id).val();
            var e = $('#ematerialname'+id).val();
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
                var imaterial    = $('#imaterial'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'imaterial'  : imaterial,
                    },
                    url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#esatuan'+id).val(data[0].i_satuan);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#ematerialname'+id).val('');
                $('#esatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}
</script>