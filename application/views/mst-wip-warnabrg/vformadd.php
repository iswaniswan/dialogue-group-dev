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
                        <label class="col-md-12">Nama Barang</label>
                        <div class="col-sm-6">                          
                        <select name="ikodebrg" class="form-control select2">
                            <option value="">Pilih Nama Barang</option>
                            <?php foreach($wip_barang as $ikodebrg): ?>
                            <option value="<?php echo $ikodebrg->i_kodebrg;?>">
                            <?php echo $ikodebrg->i_kodebrg.'-'.$ikodebrg->e_namabrg;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                    </div>   
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus" ></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
                </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                    <thead>
                        <tr>
                            <th width="30%">Warna</th> 
                            <th>Action</th>                           
                        </tr>
                    </thead>
                    <tbody>                                       
                    </tbody>
                        <input type="hidden" name="jml" id="jml" value="">
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
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
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
          url: '<?= base_url($folder.'/cform/color'); ?>',
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