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
                        <label class="col-md-12">Kode Warna Barang Jadi</label>
                        <div class="col-sm-6">
                            <input type="text" name="iproductcolor" class="form-control" maxlength="5"  value="<?= $data->i_product_color; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Barang Jadi</label>
                        <div class="col-sm-6">                          
                        <select name="iproductmotif" class="form-control select2">
                            <option value="">Pilih Barang Jadi</option>
                            <?php foreach($productmotif as $iproductmotif): ?>
                            <option value="<?php echo $iproductmotif->i_product_motif;?>" 
                            <?php if($iproductmotif->i_product_motif==$data->i_product_motif) { ?> selected="selected" <?php } ?>>
                            <?php echo $iproductmotif->i_product_motif.'-'.$iproductmotif->e_product_motifname;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                    </div>   
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus" ></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
               <!--<input type="text" name="jml" id="jml" value="0">-->
                </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Warna</th> 
                            <th width="30%">Action</th>                           
                        </tr>
                    </thead>
                    <tbody>
                        <?$i = 0;
                            foreach ($data2 as $row) {
                            $i++;?>
                            <tr>
                            <td class="col-sm-1" >  
                                <input type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly> 
                                <input type="text" id="icolor2<?=$i;?>" name="icolor2<?=$i;?>"value="<?= $row->i_color.'-'.$row->e_color_name; ?>" readonly>                          
                            </td>                            
                            <td>
                                <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                            </td>
                             </tr>                            
                            <?}?>
                    </tbody>
                        <input type="hidden" name="jml" id="jml" value="<?php echo $i; ?>">
                </table>
            </div>            
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
    $(".select2").select2();
    });
    var counter = document.getElementById("jml").value;
    $("form").submit(function (event) {
        event.preventDefault();
    });
    

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>"); 
        var cols = "";

        if(cols =! ""){   
            //counter +=1;
        document.getElementById("jml").value = counter;  
        cols += '<td><select  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

        }
        
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#icolor'+ counter).select2({
        placeholder: 'Pilih Warna',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/warnajadi'); ?>',
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