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
                        <label class="col-md-4">Bulan</label>
                        <label class="col-md-8">Tahun</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" disabled="" readonly>
                                <option value=""></option>
                                <option value='01' <?php if ($bulan=='01') {
                                    echo "selected";} ?>>Januari</option>
                                <option value='02' <?php if ($bulan=='02') {
                                    echo "selected";} ?>>Februari</option>
                                <option value='03' <?php if ($bulan=='03') {
                                    echo "selected";} ?>>Maret</option>
                                <option value='04' <?php if ($bulan=='04') {
                                    echo "selected";} ?>>April</option>
                                <option value='05' <?php if ($bulan=='05') {
                                    echo "selected";} ?>>Mei</option>
                                <option value='06' <?php if ($bulan=='06') {
                                    echo "selected";} ?>>Juni</option>
                                <option value='07' <?php if ($bulan=='07') {
                                    echo "selected";} ?>>Juli</option>
                                <option value='08' <?php if ($bulan=='08') {
                                    echo "selected";} ?>>Agustus</option>
                                <option value='09' <?php if ($bulan=='09') {
                                    echo "selected";} ?>>September</option>
                                <option value='10' <?php if ($bulan=='10') {
                                    echo "selected";} ?>>Oktober</option>
                                <option value='11' <?php if ($bulan=='11') {
                                    echo "selected";} ?>>November</option>
                                <option value='12' <?php if ($bulan=='12') {
                                    echo "selected";} ?>>Desember</option>
                            </select>
                            <input type="hidden" name="dbulan" id="dbulan" class="form-control" value="<?= $bulan;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dtahun" id="dtahun" class="form-control" value="<?= $tahun;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> 
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label>Tanggal SO</label>
                            <input type="text" name="dso" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="2%">No</th>
                                    <th width="20%">Kode Barang WIP</th>
                                    <th width="50%">Nama Barang WIP</th>
                                    <th width="20%">Kode Barang Bahan Baku</th>
                                    <th width="50%">Nama Barang Bahan Baku</th>
                                    <th>Stok Opname</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($data as $row) {
                                        $i++;
                                ?>
                                <tr>
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row["i_product"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row["e_product_name"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row["i_material"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:600px"type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row["e_material_name"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="stokopname<?=$i;?>" name="stokopname<?=$i;?>"value="<?= $row["so"]; ?>" 
                                    onfocus="if(this.value=='0'){this.value='';}" onkeyup="cekselisih();">
                                </td>
                                </tr>
                                <?}?>
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
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
});

//var counter = 0;
var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        
        counter++;
        document.getElementById("jml").value = counter;
        var ikodemaster = $("#ikodemaster").val();
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
                
        var cols = "";

        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols +='<td></td>';
        cols +='<td></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial'+counter+'"></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname'+counter+'" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="stokopname'+ counter + '" class="form-control" name="stokopname'+counter+'" value="0" /></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#ematerialname'+ counter).select2({
        
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        type: "POST",
        ajax: {          
          url: '<?= base_url($folder.'/cform/datamaterial/'); ?>',
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
                    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#esatuan'+id).val(data[0].i_satuan);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
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