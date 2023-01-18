<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                  <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-5">No SJ</label>
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled=""> 
                                <option value="" selected>Pilih Gudang</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_sub_bagian == $data->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_sub_bagian;?>" selected><?= $ikodemaster->e_sub_bagian;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_sub_bagian;?>"><?= $ikodemaster->e_sub_bagian;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" id= "isj "name="isj" class="form-control" value="<?php echo $data->i_sj;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark" name="eremark" class="form-control" value="<?php echo $data->e_remark;?>" readonly>
                        </div>
                    </div>
            </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal</label>
                        <label class="col-md-8">Tujuan</label>
                        <div class="col-sm-4">
                            <input type="text" id= "dsj "name="dsj" class="form-control" value="<?php echo $data->d_sj;?>"  readonly>
                        </div>
                        <div class="col-sm-6">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled=""> 
                                <option value="" selected>Pilih Tujuan</option>
                                <?php foreach ($tujuan as $itujuan):?>
                                    <?php if ($itujuan->i_sub_bagian == $data->i_tujuan) { ?>
                                    <option value="<?php echo $itujuan->i_sub_bagian;?>" selected><?= $itujuan->e_sub_bagian;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $itujuan->i_sub_bagian;?>"><?= $itujuan->e_sub_bagian;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                   
                </div>    
                <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang WIP</th>
                            <th>Nama Barang WIP</th>
                            <th>Warna</th>
                            <th>Kode Barang BB</th>
                            <th>Nama Barang BB</th>
                           <!--  <th>Warna</th> -->
                            <th>Jumlah</th>
                            <th>Keterangan</th>
                    </thead>
                    <tbody>
                       <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;?>
                        <tr>
                            <td style="text-align: center;"><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:100px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>"  readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:350px"type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_namabrg; ?>" class="form-control" readonly >
                            </td> 
                            <td class="col-sm-1">
                                <input style ="width:80px" class="form-control" type="hidden" id="icolorproduct<?=$i;?>" name="icolorproduct<?=$i;?>"value="<?= $row->i_color; ?>" >
                                <input style ="width:80px" class="form-control" type="text" id="ecolorproduct<?=$i;?>" name="ecolorproduct<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>
                            </td>                  
                            <td class="col-sm-1">
                                <input style ="width:100px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>"  readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:350px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" class="form-control" readonly >
                            </td>   
                             <td class="col-sm-1">
                                <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:150px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
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
    </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();
 });

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
    $('#ikodemaster').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

$(document).ready(function () {
    $('#itujuan').select2({
    placeholder: 'Pilih Tujuan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/tujuan'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        $("#ematerial").attr("disabled", true);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
       cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct' + counter + '"></td>';
        cols += '<td><select style="width:350px;" type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" id="icolorproduct'+ counter + '" class="form-control" name="icolorproduct'+ counter + '"><input style="width:100px;" type="text" readonly id="ecolorproduct'+ counter + '" class="form-control" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '"></td>';
        cols += '<td><select style="width:350px;" type="text" id="ematerial'+ counter + '" class="form-control" name="ematerial'+ counter + '"onchange="getmaterial('+ counter + ');"</td>';
        // cols += '<td><input type="hidden" id="icolormaterial'+ counter + '" class="form-control" name="icolormaterial'+ counter + '"><input style="width:100px;" type="text" readonly id="ecolormaterial'+ counter + '" class="form-control" name="ecolormaterial'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" style="width:100px;"class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input style="width:200px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '"/></td>';
         cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#eproduct'+ counter).select2({
        placeholder: 'Pilih Kode Barang',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/dataproduct'); ?>',
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
        counter -= 1
        document.getElementById("jml").value = counter;

    });

function getproduct(id){
    var eproduct = $('#eproduct'+id).val();
        $('#ematerial'+ counter).select2({
            placeholder: 'Pilih Kode Barang',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+eproduct,
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
        
        $.ajax({
        type: "post",
        data: {
            'eproduct': eproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product);
            $('#icolorproduct'+id).val(data[0].i_color);
            $('#ecolorproduct'+id).val(data[0].e_color_name);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getmaterial(id){
    var ematerial = $('#ematerial'+id).val();
    $.ajax({
    type: "post",
    data: {
        'ematerial': ematerial
    },
    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
    dataType: "json",
    success: function (data) {
        $('#imaterial'+id).val(data[0].i_material);

        ada=false;
        var a = $('#imaterial'+id).val();
        var e = $('#ematerial'+id).val();
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
                    $('#ematerial'+id).val(data[0].e_material_name);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            $('#imaterial'+id).val('');
            $('#ematerial'+id).html('');
            $('#ematerial'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}

function validasi(){
    var gudang   = $('#ikodemaster').val();
    var itujuan  = $('#itujuan').val();

    if (gudang == '' || gudang == null || itujuan == '' || itujuan == null) {
        swal('Data header Belum Lengkap');
        return false;
    } else {
        return true;
    }
}
</script>