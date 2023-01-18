<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">NO Bon</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ibonk" name="ibonk" class="form-control" value="<?= $data->i_bonk;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Bon</label>
                        <div class="col-sm-12">
                        <input type="text" name="dbonk" id="dbonk" class="form-control date" value="<?= $data->d_bonk; ?>" readonly>
                        </div>
                    </div>
               
                <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i
                                    class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah
                            </button>
                        </div>
                </div> 
                 </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Tujuan Kirim</label>
                        <div class="col-sm-12">
                            <input type="hidden" id = "itujuankirim" name="itujuankirim" class="form-control"  value="<?= $data->i_tujuan_kirim; ?>">
                            <input type="text" id = "etujuankirim" name="etujuankirim" class="form-control"  value="<?= $data->e_nama_master; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $data->e_remark; ?>">
                        </div>
                    </div>
                                       
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td class="col-sm-1">
                                    <input style ="width:40px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:160px"type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly >
                                    <input style ="width:150px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly class="form-control">
                                </td> 
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_qty; ?>" class="form-control">
                                </td>   
                                <td>
                                    <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> 
                                </td>
                                </tr>
                                <?php } ?>
                                <label class="col-md-12">Jumlah Data</label>
                                <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {

  var counter = document.getElementById("jml").value;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");

        var cols = "";
        if(cols =! ""){       
        document.getElementById("jml").value = counter;  
        cols += '<td><input readonly style=width:40px; id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" value="" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" style=width:400px; id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '" value="" readonly></td>';
        cols += '<td><input type="text" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '"><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" readonly></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

        }
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

 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
    //  $("select").attr("disabled", true);
    //  $("#submit").attr("disabled", true);
 });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });
});

    $(document).ready(function () {
        $(".select").select();
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
</script>