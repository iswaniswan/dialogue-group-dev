<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="form-group">
                        <label class="col-md-12">Unit Jahit</label>
                        <div class="col-sm-12">
                        <input type="text" name="ikodeunit" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->kode_unit_jahit; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kelompok Barang</label>
                        <div class="col-sm-12">
                        <input id="ikode" name="ikode" class="form-control" 
                                value="<?=$data->nama;?>"readonly>
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
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                            
                        </div>
                    </div>
                    
            </div>
            
            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th width="20%">Nama Barang</th>
                                            <th>Harga</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?$i = 0;
                                        // var_dump($data2);
                                        foreach ($data2 as $row) {
                                        $i++;
                                        ?>
                                        <tr>
                                        <td class="col-sm-4">
                                            <input type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->kode_brg; ?>" >
                                        </td>
                                        <td class="col-sm-4">
                                            <input type="text" name="eproductbasename<?=$i;?>" id="eproductbasename<?=$i;?>"  class="form-control" value="<?= $row->nama_brg; ?>"/>
                                        </td>
                                        <td class="col-sm-4">
                                        <input type="text" name="harga<?=$i;?>" id="harga<?=$i;?>"  class="form-control" value="<?= $row->harga; ?>"/>
                                            <!-- <input type="text" name="harga" id="harga"  class="form-control" value="<#?= $data2->harga; ?>"/> -->
                                        </td>
                                        <td>
                                         <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> 
                                        </td>
                                        </tr>
                                        
                                        <?}?>
                                        <input type="text" name="jml" id="jml" value="<?= $i; ?>">
                                    </tbody>
                                </table>
                            </div>
            </form>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
var counter = 0;
var counter2 = 0; 

$("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
    
        cols += '<td><select  type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getproductname('+ counter + ');"></td>';
        cols += '<td><input type="text" id="eproductbasename'+ counter + '" type="text" class="form-control" name="eproductbasename' + counter + '"></td>';
        cols += '<td><input type="text" id="harga'+ counter + '" class="form-control" name="harga'+ counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#iproduct'+ counter).select2({
        placeholder: 'Pilih Product',
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
      
    });
    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });
function getjenis(ikode2) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getjenis');?>",
            data:"ikode2="+ikode2,
            dataType: 'json',
            success: function(data){
                $("#ikode2").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }
    function getproductname(id){
        var iproduct = $('#iproduct'+id).val();
        $.ajax({
        type: "post",
        data: {
            'iproduct': iproduct
        },
        url: '<?= base_url($folder.'/cform/getproductname'); ?>',
        dataType: "json",
        success: function (data) {
            $('#eproductbasename'+id).val(data[0].e_product_basename);
            //$('#vunitprice'+id).val(data[0].v_product_mill);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>