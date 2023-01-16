<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Gudang</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" readonly>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_nama_master;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "isj" name="isj" class="form-control"  value="<?php echo $data->i_sj;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dsj" name="dsj" class="form-control date"  value="<?=$data->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <option value="<?=$data->i_tujuan;?>"><?=$data->e_departement_name;?></option>
                                <?php foreach($tujuan as $key){?>
                                    <option value="<?=$key->i_departemen;?>"><?=$key->e_nama_master;?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                     <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id= "eremark "name="eremark" class="form-control"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center; width:5%">No</th>
                                <th style="text-align:center; width:12%">Kode Barang</th>
                                <th style="text-align:center; width:30%">Nama barang</th>
                                <th style="text-align:center; width:12%">Warna</th>
                                <th style="text-align:center; width:8%">Quantity</th>
                                <th style="text-align:center; width:30%">Keterangan</th>
                                <th style="text-align:center; width:5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;?>
                        <tr>
                        <td style="text-align: center;"><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product; ?>"  readonly >
                            </td>
                            <td>
                                <input type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_basename; ?>" class="form-control" readonly >
                            </td> 
                            <td>
                                <input class="form-control" type="hidden" id="icolor<?=$i;?>" name="icolor[]"value="<?= $row->i_color; ?>" >
                                <input class="form-control" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>
                            </td>   
                            <td>
                                <input class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]"value="<?= $row->n_quantity; ?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]" value="<?= $row->e_remark; ?>">
                            </td>
                            <td>
                                <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        <?}?>
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#send").on("click", function () {
        var kode = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

function getenabledsend() {
    $('#send').attr("disabled", false);
    swal({
        title  : "Kirim Dokumen",  
        text   : "Data Berhasil Dikirim!",
        buttons: false,
        timer  : 1500,
    });
}

//var counter = 0;
var counter = $('#jml').val();
$("#addrow").on("click", function () {
    $("#tabledata").attr("hidden", false);
    counter++;
    document.getElementById("jml").value = counter;
    count=$('#tabledata tr').length;
    var newRow = $("<tr>");
            
    var cols = "";
    cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
    cols += '<td><input style="width:150px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
    cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct[]" onchange="getproduct('+ counter + ');"></td>';
    cols += '<td><input type="hidden" readonly id="icolor'+ counter + '" class="form-control" name="icolor[]"><input type="text" id="ecolor'+ counter + '" class="form-control" name="ecolor[]" readonly></td>'; 
    cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" onkeyup="cekval(this.value); reformat(this);"></td>';                 
    cols += '<td><input style="width:200px" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
    cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
    newRow.append(cols);
    $("#tabledata").append(newRow);
   
    $('#eproduct'+ counter).select2({
    
        placeholder: 'Pilih Product',
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

function getproduct(id){
    var eproduct = $('#eproduct'+id).val();
    $.ajax({
        type: "post",
        data: {
            'eproduct': eproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product_motif);
            $('#ecolor'+id).val(data[0].e_color_name);
            $('#icolor'+id).val(data[0].i_color);
            ada=false;
            var a = $('#iproduct'+id).val();
            var e = $('#eproduct'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#iproduct'+i).val()) && (i!=id)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                $('#iproduct'+id).val(data[0].i_product_motif);
                $('#ecolor'+id).val(data[0].e_color_name);
                $('#icolor'+id).val(data[0].i_color);
            }else{
                $('#iproduct'+id).html('');
                $('#iproduct'+id).val('');
                $('#eproduct'+id).val('');
                $('#eproduct'+id).html('');
                $('#ecolor'+id).val('');
                $('#ecolor'+id).html('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function cekval(input){
    var jml   = counter;
    var num = input.replace(/\,/g,'');
    if(!isNaN(num)){
    
    }else{
      swal('input harus numerik !!!');
      input = input.substring(0,input.length-1);
    }
}

function cek() {
    var ibagian = $('#ibagian').val();
    var itujuan = $('#itujuan').val();
    var dsj     = $('#dsj').val();
    var jml = $('#jml').val();
    for(i=0;i<=jml;i++){
        var nqty = $('#nquantity'+i).val();
        if (ibagian =='' || ibagian == null || itujuan =='' || itujuan == null) {
            swal('Data Header Belum Lengkap !!');
            return false;
        }else if(nqty == '0'){
            swal('Quantity tidak boleh 0 !!');
            return false;
        }else {
            return true;
        }
    }
}
</script>