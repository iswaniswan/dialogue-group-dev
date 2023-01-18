<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?=$title;?> <a href="#"
                    onclick="show('<?=$folder;?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-7">
                   <div class="form-group row">
                        <label class="col-md-4">Kode Barang</label>
                        <label class="col-md-8">Nama Barang</label>
                       
                        <div class="col-sm-4">
                            <input type="text" id = "iproduct" name="iproduct" class="form-control" required="" value="<?=$data->i_product;?>"readonly>
                        </div>
                        <div class="col-sm-7">
                            <input type="text" id = "eproduct" name="eproduct" class="form-control" required="" value="<?=$data->e_product_basename;?>"readonly>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-md-12">Harga</label>
                        <div class="col-sm-5">
                            <input type="text" id = "vprice" name="vprice" class="form-control" required=""  value="<?=$data->v_price;?>">
                        </div>
                    </div>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select").select();
    showCalendar('.date');
});

$(document).ready(function () {
    // var counter = 0;
var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var ikodemaster     = $("#ikodemaster").val();
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        if(cols =! ""){

            cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
            cols += '<td><input type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
            cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
            cols += '<td><input type="text" id="vtoset'+ counter + '" class="form-control" name="vtoset[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" id="vgelar'+ counter + '" class="form-control" name="vgelar[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" id="vset'+ counter + '" class="form-control" name="vset[]"/></td>';
            cols += '<td><input type="checkbox"  value="cek" id="fbis'+ counter + '" name="fbis[]" /></td>';
            cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        }

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#ematerialname'+ counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?=base_url($folder . '/cform/datamaterial');?>',
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
        // $('#jml').val(counter);
        // del();
         // counter -= 1
         // document.getElementById("jml").value = counter;
    });

    // function del() {
    //     obj=$('#tabledata tr').find('spanx');
    //     $.each( obj, function( key, value ) {
    //         id=value.id;
    //         $('#'+id).html(key+1);
    //     });
    // }
});

function getmaterial(id){
    var ematerialname = $('#ematerialname'+id).val();
    $.ajax({
    type: "post",
    data: {
        'ematerialname': ematerialname
    },
    url: '<?=base_url($folder . '/cform/getmaterial');?>',
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
                url: '<?=base_url($folder . '/cform/getdetailbar');?>',
                dataType: "json",
                success: function (data) {
                    $('#imaterial'+id).val(data[0].i_material);
                    //$('#ematerialname'+id).val(data[0].e_material_name);
                    $('#esatuan'+id).val(data[0].e_satuan);
                    $('#esatuan'+id).val(data[0].i_satuan);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            $('#ematerialname'+id).val('');
            $('#esatuan'+id).val('');
            $('#esatuan'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}

 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
 });
</script>