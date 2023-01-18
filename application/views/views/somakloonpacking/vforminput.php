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
                        <label class="col-md-6">Tanggal SO</label>
                        <div class="col-sm-4">
                            <input type="text" name="dso" id="dso" class="form-control" value="<?= $dso;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>   
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No</th>
                                    <th style="text-align: center;">Kode Barang</th>
                                    <th style="text-align: center;">Nama Barang</th>
                                    <!-- <th style="text-align: center;">Kode Barang BB</th>
                                    <th style="text-align: center;">Nama Barang BB</th> -->
                                    <th style="text-align: center;">Warna</th>
                                    <th style="text-align: center;">Saldo Awal</th>
                                    <th style="text-align: center;">Saldo Akhir</th>
                                    <th style="text-align: center;">Stok Opname</th>
                                    <th style="text-align: center;">Selisih</th> 
                                    <th style="text-align: center;">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                               
                                    foreach ($data as $row) {
                                    $i++;
                                     $so = $row["so"];
                                     $sa = $row["saldoakhir"];
                                     $selisih = $so-abs($sa);
                                ?>
                                <tr>
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td>
                                    <input style="width:100px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row["kodebarang"]; ?>" readonly >
                                </td>
                                <td>
                                    <input style="width:300px" type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row["namabarang"];?>" readonly >
                                </td>
                                <!-- <td>
                                    <input style="width:100px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row["kode"];?>" readonly >
                                </td> -->
                                 <!-- <td>
                                    <input style="width:300px" type="text" class="form-control" id="kodewip<?=$i;?>" name="barang<?=$i;?>"value="<?= $row["barang"]; ?>" readonly >
                                </td> -->
                                <td>
                                    <input style="width:120px" type="text" class="form-control" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $row["ecolor"]; ?>" readonly >
                                    <input type="hidden" class="form-control" id="icolor<?=$i;?>" name="icolor<?=$i;?>" value="<?= $row["icolor"]; ?>" readonly >
                                </td>
                                <td>
                                    <input style="width:80px" type="text" class="form-control" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row["saldoawal"]; ?>"readonly >
                                </td>
                                
                                <td>
                                    <input style="width:80px" type="text" class="form-control" id="saldoakhir<?=$i;?>" name="saldoakhir<?=$i;?>"value="<?= $row["saldoakhir"]; ?>" readonly>
                                </td>
                                <td>
                                    <input style="width:80px" type="number" class="form-control" id="stokopname<?=$i;?>" name="stokopname<?=$i;?>"value="<?= $row["so"]; ?>" 
                                    onfocus="if(this.value=='0'){this.value='';}" onkeyup="cekselisih();">
                                </td>
                                <td>
                                    <input style="width:80px" type="text" class="form-control" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="<?= $selisih; ?>" readonly>
                                </td>
                                </tr>
                                <?php } ?> 
                                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
                
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct'+counter+'" value=""></td>';
        cols += '<td><select type="text" id="eproductname'+ counter + '" class="form-control" name="eproductname'+counter+'" onchange="getdetailproduct('+ counter + ');"></td>';
        /*cols += '<td><input type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]" value=""></td>';
        cols += '<td><select type="text" id="ematerial'+ counter + '" class="form-control" name="ematerial[]" onchange="getmaterial('+ counter + ');"></td>';*/
        cols += '<td><input type="text" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor'+counter+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+counter+'" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" readonly id="saldoawal'+ counter + '" class="form-control" name="saldoawal'+counter+'" value="0" onkeyup="cekval(this.value); reformat(this); "/></td>';    
        cols += '<td><input type="text" readonly id="saldoakhir'+ counter + '" class="form-control" name="saldoakhir'+counter+'"value="0" onkeyup="cekval(this.value); reformat(this);"/></td>';         
        cols += '<td><input type="number" onfocus="if(this.value==\'0\'){this.value=\'\';}" id="stokopname'+ counter + '" class="form-control" name="stokopname'+counter+'" value="0" onkeyup="cekselisih();"/></td>';
        cols += '<td><input type="text" readonly id="selisih'+ counter + '" class="form-control" name="selisih'+counter+'"value="0" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#eproductname'+ counter).select2({
            placeholder: 'Pilih Kode Produk',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {          
              url: '<?= base_url($folder.'/cform/dataproduct/'); ?>',
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

    function getdetailproduct(id){
        var eproductname = $('#eproductname'+id).val();
        $('#ematerial'+ counter).select2({
            placeholder: 'Pilih Kode Barang',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {          
              url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+eproductname,
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

        ada=false;
        
        var fields = eproductname.split('|');
        var iproduct = fields[0];
        var icolor = fields[1];

        var a = eproductname;
        var x = $('#jml').val();
        for(i=1;i<=x;i++){   
            if((a == $('#eproductname'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var eproductname = $('#eproductname'+id).val();
           
            $.ajax({
                type: "post",
                data: {
                    'eproductname'  : eproductname,
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#iproduct'+id).val(data[0].i_product);
                    $('#ecolor'+id).val(data[0].e_color_name);
                    $('#icolor'+id).val(data[0].i_color);
                    ada=false;
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
            $('#icolor'+id).html('');
            $('#icolor'+id).val('');
            $('#ecolor'+id).html('');
            $('#ecolor'+id).val('');
        }
    }

    function getmaterial(id){
        var ematerial = $('#ematerial'+id).val();
        ada=false;
        
        var fields = ematerial.split('|');
        var imaterial = fields[0];

        var a = ematerial;
        var x = $('#jml').val();
        for(i=1;i<=x;i++){   
            if((a == $('#ematerial'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var ematerial = $('#ematerial'+id).val();
           
            $.ajax({
                type: "post",
                data: {
                    'ematerial'  : ematerial,
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#imaterial'+id).val(data[0].i_material);
                    ada=false;
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#imaterial'+id).html('');
            $('#imaterial'+id).val('');
        }
    }

</script>