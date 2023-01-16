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
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="ikodemaster" id="ikodemaster" class="form-control date" value="<?= $kodemaster;?>">
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control date" value="<?= $gudang->e_nama_master;?>"disabled = 't'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-6">Tanggal SO</label>
                        <!-- <label class="col-md-8">Date to</label> -->
                        <div class="col-sm-4">
                            <input type="text" name="dso" id="dso" class="form-control" value="<?= $dso;?>" readonly>
                        </div>
                       <!--  <div class="col-sm-4">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?= $dto;?>" readonly>
                        </div> -->
                    </div>
                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>   
                       <!--  <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button>   -->
                        <!-- <button type="button" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approve","#main")'><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button> -->
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
               <!--  <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ekodebrg" id="ekode" class="form-control date" value="<?= $barang->e_material_name;?>"disabled = 't'>
                        </div>
                    </div>
                </div> -->
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Satuan</th>
                                    <th>Saldo Awal</th>
                                    <th>Saldo Akhir</th>
                                    <th>Stok Opname</th>
                                    <th>Selisih</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $gudang = '';
                                    foreach ($data2 as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row["kode"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:450px"type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row["barang"];?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:90px" type="text" class="form-control" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row["satuan"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row["saldoawal"]; ?>"readonly >
                                </td>
                                
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="saldoakhir<?=$i;?>" name="saldoakhir<?=$i;?>"value="<?= $row["saldoakhir"]; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="number" class="form-control" id="stokopname<?=$i;?>" name="stokopname<?=$i;?>"value="<?= $row["so"]; ?>" 
                                    onfocus="if(this.value=='0'){this.value='';}" onkeyup="cekselisih();">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="<?= $row["selisih"]; ?>" readonly>
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
$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
     $("#download").attr("disabled", true);
     $("#upload").attr("disabled", true);
});

function cekselisih(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var saldoakhir = $('#saldoakhir'+i).val();
        var stokopname = $('#stokopname'+i).val();

        total = stokopname-Math.abs(saldoakhir);
        $('#selisih'+i).val(formatcemua(total));

    }
}

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
        cols += '<td><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial'+counter+'"></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname'+counter+'" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+counter+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+counter+'" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" readonly id="saldoawal'+ counter + '" class="form-control" name="saldoawal'+counter+'" value="0" onkeyup="cekval(this.value); reformat(this); "/></td>';    
        cols += '<td><input type="text" readonly id="saldoakhir'+ counter + '" class="form-control" name="saldoakhir'+counter+'"value="0" onkeyup="cekval(this.value); reformat(this);"/></td>';         
        cols += '<td><input type="number" onfocus="if(this.value=="0"){this.value="";}" id="stokopname'+ counter + '" class="form-control" name="stokopname'+counter+'" value="0" onkeyup="cekselisih();"/></td>';
         cols += '<td><input type="text" readonly id="selisih'+ counter + '" class="form-control" name="selisih'+counter+'"value="0" onkeyup="cekval(this.value); reformat(this);"/></td>';
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
                if((a == $('#imaterial'+i).val()) && (i!=id)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                $('#imaterial'+id).val(data[0].i_material);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan);
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
                $('#isatuan'+id).val('');
                $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}


</script>