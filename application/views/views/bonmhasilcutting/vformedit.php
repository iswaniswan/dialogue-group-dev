<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="row">
                        <label class="col-md-12">NO BonM</label>
                        <div class="col-sm-12">
                            <input type="text" id = "nobonm" name="nobonm" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_bonm;?>"readonly>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12">NO BonM Manual</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ibonmanual" name="ibonmanual" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_bonm_manual;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Sumber</label>
                        <div class="col-sm-7">
                            <input type="text" id = "esumber" name="esumber" class="form-control" value="<?= $data->e_sumber;?>"readonly>
                            <input type="hidden" id = "isumber" name="isumber" class="form-control" value="<?= $data->i_sumber;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Masuk</label>
                        <div class="col-sm-7">
                            <input type="text" id = "enamajenis" name="enamajenis" class="form-control" value="<?= $data->e_jenis_masuk;?>"readonly>
                            <input type="hidden" id = "itranstype" name="itranstype" class="form-control" value="<?= $data->i_jenis_masuk;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal BonM</label>
                        <div class="col-sm-12">
                        <input type="text" name="dbonm" id="dbonm" class="form-control date" value="<?= $data->d_bonm; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $data->e_desc; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                        </div>
                    </div> 
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <!-- <th>No</th> -->
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Warna</th>
                                    <th>Keterangan</th>
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
                                    <input style ="width:160px"type="text" id="kodebrg<?=$i;?>" name="kodebrg<?=$i;?>"value="<?= $row->kode_brg; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px"type="text" id="namabrg<?=$i;?>" name="namabrg<?=$i;?>"value="<?= $row->nama_brg; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_qty; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="hidden" id="iunit<?=$i;?>" name="iunit<?=$i;?>"value="<?= $row->i_color; ?>" readonly >
                                    <input style ="width:150px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px"type="text" id="eremark<?=$i;?>" name="e_remark<?=$i;?>"value="<?= $row->e_desc; ?>" >
                                </td>
                                <td>
                                <!-- <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> -->
                                </td>
                                </tr>
                                <?php } ?>
                                <label class="col-md-12">Jumlah Data</label>
                                <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>"readonly>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
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
            cols += '<td><select type="text" id="kodebrg'+ counter + '" class="form-control" name="kodebrg'+ counter + '" value="" onchange="getbrg('+ counter + ');"></td>';
            cols += '<td><input type="text" id="enamabrg'+ counter + '" type="text" class="form-control" name="enamabrg' + counter + '" value=""></td>';
            cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><select type="text" id="namawarna'+ counter + '" class="form-control" name="namawarna'+ counter + '" value="" onchange="getwarna('+ counter + ');"></td>';
            cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '" value=""/></td>';
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            cols += '<td><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '" onkeyup="cekval(this.value);"/></td>';

        }
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#kodebrg'+ counter).select2({
        placeholder: 'Pilih Barang',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/databrg'); ?>',
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
      $('#namawarna'+ counter).select2({
        placeholder: 'Pilih Warna',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datawarna'); ?>',
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
    function getbrg(id){
        var kodebrg = $('#kodebrg'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_kodebrg': kodebrg
        },
        url: '<?= base_url($folder.'/cform/getbrg'); ?>',
        dataType: "json",
        success: function (data) {
            $('#enamabrg'+id).val(data[0].e_namabrg);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    function getwarna(id){
        var namawarna = $('#namawarna'+id).val();
        $.ajax({
        type: "post",
        data: {
            'nama': namawarna
        },
        url: '<?= base_url($folder.'/cform/getwarna'); ?>',
        dataType: "json",
        success: function (data) {
            $('#icolor'+id).val(data[0].i_color);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>