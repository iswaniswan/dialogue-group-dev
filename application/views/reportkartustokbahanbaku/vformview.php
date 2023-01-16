<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div> 
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="ikodemaster" id="ikodemaster" class="form-control date" value="<?= $kodemaster;?>"disabled = 't'>
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $gudang->e_nama_master;?>"disabled = 't'>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Date To</label>
                        <label class="col-md-8">Date From</label>
                        <div class="col-sm-4">
                            <input type="text" name="dfrom" id="dfrom" class="form-control" value="<?= $dfrom;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?= $dto;?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikodebarang" id="ikodebarang" class="form-control" value="<?= $barang->e_material_name;?>" readonly>
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style width="2%">No</th>
                                    <th>No Dokumen</th>
                                    <th>Tanggal Dokumen</th>
                                    <th>Saldo Awal</th>
                                    <th>Quantity Masuk</th>
                                    <th>Quantity Keluar</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;

                                    $masuk = $row->masuk;
                                    $keluar = $row->keluar;

                                    if ($i == 1) {
                                        $saldoawal =  $row->saldoawal;
                                        $saldoakhir = $row->saldo;
                                    } else {
                                        $saldoawal  = $lastsaldo;
                                        $saldoakhir = $saldoawal + $masuk - $keluar;
                                    }

                                ?>
                                <tr>
                                <td>
                                    <?= $i; ?>
                                </td>
                                <td>
                                   <?= $row->nodok;?>
                                </td>
                                <td>
                                    <?= $row->tgldok;?>
                                </td>
                                <td>
                                   <?= $saldoawal; ?>
                                </td>
                                <td>
                                    <?= $masuk; ?>
                                </td>
                                <td>
                                    <?= $keluar; ?>
                                </td>
                                <td>
                                    <?= $saldoakhir?>
                                </td>
                                <? $lastsaldo = $saldoakhir; }?>
                                </tr>
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