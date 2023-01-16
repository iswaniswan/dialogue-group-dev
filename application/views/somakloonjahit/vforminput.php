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
                        <label class="col-md-6">Partner Jahit</label>
                        <!-- <label class="col-md-8">Date to</label> -->
                        <div class="col-sm-6">
                            <input type="hidden" name="partner" id="partner" class="form-control" value="<?= $partner->i_unit_jahit;?>" readonly>   
                            <input type="text" name="epartner" id="epartner" class="form-control" value="<?= $partner->e_unitjahit_name;?>" readonly>
                        </div>
                       <!--  <div class="col-sm-4">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?= $dto;?>" readonly>
                        </div> -->
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
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index2","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
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
                                    <th>Warna</th>
                                    <th>Saldo Awal</th>
                                    <th>Saldo Akhir</th>
                                    <th>Stok Opname</th>
                                    <th>Selisih</th> 
                                    <th>Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                               
                                    foreach ($data2 as $row) {
                                    $i++;
                                    $so = $row["so"];
                                    $sa = $row["saldoakhir"];
                                    $selisih = $so-abs($sa);
                                ?>
                                <tr>
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" class="form-control" id="iwip<?=$i;?>" name="iwip<?=$i;?>"value="<?= $row["kodewip"]; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px"type="text" class="form-control" id="ewip<?=$i;?>" name="ewip<?=$i;?>"value="<?= $row["barangwip"];?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="text" class="form-control" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $row["ecolor"]; ?>" readonly >
                                    <input style ="width:90px" type="hidden" class="form-control" id="icolor<?=$i;?>" name="icolor<?=$i;?>" value="<?= $row["icolor"]; ?>" readonly >
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
                                <td class="col-sm-1" colspan="1">
                                    <input style ="width:100px"type="text" class="form-control" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="<?= $selisih; ?>" readonly>
                                </td>
                                <td class="col-sm-1" colspan="1">
                                    
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
     // $("input").attr("disabled", true);
     // $("#submit").attr("disabled", true);
     // $("#addrow").attr("disabled", true);
     // $("#download").attr("disabled", true);
     // $("#upload").attr("disabled", true);
});

function cekselisih(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var saldoakhir = Number($('#saldoakhir'+i).val());
        var stokopname = Number($('#stokopname'+i).val());

        total = stokopname-Math.abs(saldoakhir);
        $('#selisih'+i).val(total); 

    }
}

//var counter = 0;
var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
                
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip'+ counter + '" value=""></td>';
        cols += '<td><select type="text" id="ewip'+ counter + '" class="form-control" name="ewip'+ counter + '" onchange="getwip('+ counter + ');"></td>';
         cols += '<td><input type="text" style="width:150px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" readonly id="saldoawal'+ counter + '" class="form-control" name="saldoawal'+ counter + '" value="0" onkeyup="cekval(this.value); reformat(this); "/></td>';    
        cols += '<td><input type="text" readonly id="saldoakhir'+ counter + '" class="form-control" name="saldoakhir'+ counter + '"value="0" onkeyup="cekval(this.value); reformat(this);"/></td>';         
        cols += '<td><input type="number" onfocus="if(this.value==\'0\'){this.value=\'\';}" id="stokopname'+ counter + '" class="form-control" name="stokopname'+ counter + '" value="0" onkeyup="cekselisih();"/></td>';
         cols += '<td><input type="text" readonly id="selisih'+ counter + '" class="form-control" name="selisih'+ counter + '" value="0" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ewip'+ counter).select2({
            placeholder: 'Pilih WIP',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {          
              url: '<?= base_url($folder.'/cform/datawip/'); ?>',
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

function getwip(id){
        var ewip = $('#ewip'+id).val();

        var fields = ewip.split('|');
        var iwip = fields[0];
        var icolor = fields[1];
        $.ajax({
        type: "post",
        data: {
            'iwip': iwip,
            'icolor': icolor
        },
        url: '<?= base_url($folder.'/cform/getwip'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iwip'+id).val(data[0].i_product);
            $('#ecolor'+id).val(data[0].e_color_name);
            $('#icolor'+id).val(data[0].i_color);
            ada=false;
            var a = $('#iwip'+id).val();
            var e = $('#icolor'+id).val();
            var jml = $('#jml').val();
            //alert(a+ " "+ e);
            for(i=1;i<=jml;i++){
                if((a == $('#iwip'+i).val()) && (e == $('#icolor'+i).val()) && (i!=id)){
                    swal("kode : "+a+" Dan warna sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            //alert(ada);
            if(!ada){
                $('#iwip'+id).val(data[0].i_product);
                $('#ecolor'+id).val(data[0].e_color_name);
                $('#icolor'+id).val(data[0].i_color);
            }else{
                $('#iwip'+id).html('');
                $('#iwip'+id).val('');
                $('#ewip'+id).html('');
                $('#ewip'+id).val('');
                $('#ecolor'+id).html('');
                $('#ecolor'+id).val('');
                $('#icolor'+id).val('');
                $('#icolor'+id).html('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}


</script>