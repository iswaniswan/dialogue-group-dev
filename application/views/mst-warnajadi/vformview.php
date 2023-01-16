<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
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
                            <select name="iproductmotif" class="form-control select2" readonly>
                            <option value="">Pilih Barang Jadi</option>
                            <?php foreach($productmotif as $iproductmotif): ?>
                            <option value="<?php echo $iproductmotif->i_product_motif;?>" 
                            <?php if($iproductmotif->i_product_motif==$data->i_product_motif) { ?> selected="selected" <?php } ?>>
                            <?php echo $iproductmotif->i_product_motif.'-'.$iproductmotif->e_product_motifname;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                    </div> 
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Warna</th>                          
                        </tr>
                    </thead>
                    <tbody>
                        <?$i = 0;
                            foreach ($data2 as $row) {
                            $i++;?>
                            <tr>
                            <td class="col-sm-1" >  
                                <?php echo $row->e_color_name; ?>
                            </td>   
                             </tr>                            
                            <?}?>
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
</script>