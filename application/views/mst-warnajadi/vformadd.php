<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                   
                    <div class="form-group">
                        <label class="col-md-6">Barang Jadi</label>
                        <div class="col-sm-3">
                            <select name="iproductmotif" class="form-control select2">
                            <option value="">Pilih Barang Jadi</option>
                             <?php foreach ($productmotif as $iproductmotif):?>
                                <option value="<?php echo $iproductmotif->i_product_motif;?>">
                                    <?php echo $iproductmotif->i_product_motif.'-'.$iproductmotif->e_product_motifname;?></option>
                            <?php endforeach; ?>         
                        </select>
                        </div>
                    </div>  
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
                    <input type="hidden" name="jml" id="jml" >
                </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="50%" hidden="true">
                    <thead>
                        <tr>
                            <th width="30%">Warna</th>                            
                            <th width="30%">Action</th>
                        </tr>
                    </thead>
                    
                </table>
            </div>            
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
    $(".select2").select2();
    });

    $("form").submit(function (event) {
        event.preventDefault();
    });
    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        $("#tabledata").attr("hidden", false);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td><select  type="text" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"></td>';
        
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
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

function angka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>