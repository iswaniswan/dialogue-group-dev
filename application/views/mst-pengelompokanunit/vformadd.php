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
                        <label class="col-md-12">Kode Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" name="ikelompokunit" class="form-control" value="" required="" maxlength="5" onkeyup="gede(this)">
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="col-md-12">Nama Kelompok</label>
                        <div class="col-sm-6">
                            <input type="text" name="namakelompok" class="form-control" maxlength="60"  value="" >
                        </div>
                    </div>   
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
                <input type="hidden" name="jml" id="jml">
                </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                    <thead>
                        <tr>
                            <th width="30%">Unit Jahit</th>
                            <th width="30%">Unit Packing</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>            
        </form>
    </div>
</div>
<script>
    
    $("form").submit(function (event) {
        event.preventDefault();
    });
    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
         $("#tabledata").attr("hidden", false);
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");

        var cols = "";

         cols += '<td><select  type="text" id="iunitjahit'+ counter + '" class="form-control" name="iunitjahit'+ counter + '"></td>';
        cols += '<td><select  type="text" id="iunitpacking'+ counter + '" class="form-control" name="iunitpacking'+ counter + '" ></td>';
        
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
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