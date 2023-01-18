<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">NO BonK</label>
                        <div class="col-sm-12">
                            <input type="text" id = "nobonk" name="nobonk" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_bonk;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal BonM</label>
                        <div class="col-sm-12">
                        <input type="text" name="dbonk" id="dbonk" class="form-control date" value="<?= $data->d_bonk; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $data->e_remark; ?>">
                        </div>
                    </div>
                    
                    <!-- <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                        </div>
                    </div>  -->
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-7">
                            <input type="text" id = "enamamaster" name="enamamaster" class="form-control" value="<?= $data->e_nama_master;?>"readonly>
                            <input type="hidden" id = "ikodemaster" name="ikodemaster" class="form-control" value="<?= $data->i_kode_master;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis keluar</label>
                        <div class="col-sm-7">
                            <input type="text" id = "enamajenis" name="enamajenis" class="form-control" value="<?= $data->e_jenis_keluar;?>"readonly>
                            <input type="hidden" id = "jnskeluar" name="jnskeluar" class="form-control" value="<?= $data->i_jenis_keluar;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tujuan</label>
                        <div class="col-sm-7">
                            <input type="text" id = "enamajenis" name="enamajenis" class="form-control" value="<?= $data->e_tujuan;?>"readonly>
                            <input type="hidden" id = "itujuan" name="itujuan" class="form-control" value="<?= $data->i_tujuan_kirim;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tujuan kirim</label>
                        <div class="col-sm-7">
                            <input type="hidden" id = "itujuankirim" name="itujuankirim" class="form-control" value="<?= $data->i_tujuan_kirim;?>"readonly>
                            <input type="text" id = "etujuan" name="etujuan" class="form-control" value="<?= $data->tujuan;?>"readonly>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Satuan Konversi</th>
                                    <th>Qty</th>
                                    <th>Qty Konversi</th>
                                    <!-- <th>Keterangan</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($data2 as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td class="col-sm-1">
                                    <input style ="width:40px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:160px"type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="hidden" id="iunit<?=$i;?>" name="iunit<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly >
                                    <input style ="width:150px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="text" id="iunitkonv<?=$i;?>" name="iunitkonv<?=$i;?>"value="<?= $row->i_convertion; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_qty; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="qtykonv<?=$i;?>" name="qtykonv<?=$i;?>"value="<?= $row->n_qty_unit_first; ?>" >
                                    <input style ="width:70px"type="hidden" id="fkonv<?=$i;?>" name="fkonv<?=$i;?>"value="0" >
                                </td>

                                
                                <td>
                                <!-- <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> -->
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
function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }
$(document).ready(function () {
    
    // var counter = 0;

  var counter = document.getElementById("jml").value;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");

        var cols = "";
        if(cols =! ""){       
        document.getElementById("jml").value = counter;  
        cols += '<td><input type="text" id="no'+ counter + '" type="text" class="form-control" name="no' + counter + '" value="'+counter+'"></td>';
        cols += '<td><select type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" value="" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '" value=""></td>';
        cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="esatuankonv'+ counter + '" class="form-control" name="esatuankonv'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantitykonv'+ counter + '" class="form-control" name="nquantitykonv'+ counter + '" value="" onkeyup="cekqty('+counter+');"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="hidden" id="fkonv'+ counter + '" class="form-control" name="fkonv'+ counter + '" value = "0";></td>';

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
// $(document).ready(function () {
    // var counter = 0;

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });

    // $("#tabledata").on("click", ".ibtnDel", function (event) {
    //     $(this).closest("tr").remove();       
    //     counter -= 1
    // });
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