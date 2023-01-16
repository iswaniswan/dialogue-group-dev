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
                        <label class="col-md-12">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikelompokunit" class="form-control" maxlength="5"  value="<?= $data->i_kelompok_unit; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" name="namakelompok" class="form-control" maxlength="60"  value="<?= $data->nama_kelompok; ?>">
                        </div>
                    </div>   
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table table-bordered" cellspacing="0" >
                    <thead>
                        <tr>               
                            <th>Unit Jahit</th>
                            <th>Unit Packing</th> 
                            <th>Action</th>                           
                        </tr>
                    </thead>
                    <tbody>
                    <?$i = 0;
                        foreach ($data2 as $row) {
                    $i++;?>
                    <tr>                                                   
                        <td class="col-sm-1" >  
                            <input style ="width:200px" type="hidden" id="iunitjahit<?=$i;?>" name="iunitjahit<?=$i;?>"value="<?= $row->i_unit_jahit; ?>">
                            <input style ="width:200px" type="text" id="iunitjahitt<?=$i;?>" name="iunitjahitt<?=$i;?>"value="<?= $row->e_unitjahit_name; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:200px" type="hidden" id="iunitpacking<?=$i;?>" name="iunitpacking<?=$i;?>"value="<?= $row->i_unit_packing; ?>">
                            <input style ="width:200px" type="text" id="unitpackingg<?=$i;?>" name="unitpackingg<?=$i;?>"value="<?= $row->e_nama_packing; ?>" readonly>
                        </td>
                        <td>
                             <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                        </td>
                    </tr>    
                    <?}?>

                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </tbody>
                    </table>
                </div>    
        </form>
    </div>
</div>
<script>
    
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
    var counter = document.getElementById("jml").value ;
    $("#addrow").on("click", function () {
        counter++;  
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");          
        var cols = "";

        /*if(cols = ""){      
        document.getElementById("jml").value = counter;            
            cols += '<td><select  type="text" id="iunitjahit'+ counter + '" class="form-control" name="iunitjahit'+ counter + '"></td>';
            cols += '<td><select  type="text" id="iunitpacking'+ counter + '" class="form-control" name="iunitpacking'+ counter + '" ></td>';
            
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

        }else{     
        document.getElementById("jml").value = counter+1;
        //counter++; 
            cols += '<td><select  type="text" id="iunitjahit'+ counter + '" class="form-control" name="iunitjahit'+ counter + '"></td>';
            cols += '<td><select  type="text" id="iunitpacking'+ counter + '" class="form-control" name="iunitpacking'+ counter + '" ></td>';
            
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>'; 
        }*/
        if(cols =! ""){   
            //counter +=1;
        document.getElementById("jml").value = counter;  
            cols += '<td><select  type="text" id="iunitjahit'+ counter + '" class="form-control" name="iunitjahit'+ counter + '"></td>';
            cols += '<td><select  type="text" id="iunitpacking'+ counter + '" class="form-control" name="iunitpacking'+ counter + '" ></td>';
            
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>'; 

        }
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#iunitjahit'+ counter).select2({
        placeholder: 'Pilih Unit Jahit',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/unitjahit'); ?>',
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
      
       $('#iunitpacking'+ counter).select2({
        placeholder: 'Pilih Unit Packing',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/unitpacking'); ?>',
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
</script>