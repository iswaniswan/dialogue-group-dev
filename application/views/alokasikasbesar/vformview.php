<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="row">
                        <label class="col-md-12">No Kas</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ipp" name="ipp" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_kb;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Kas</label>
                        <div class="col-sm-12">
                        <input type="text" name="dkb" id="dkb" class="form-control date" value="<?= $data->d_kb; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-12">
                        <input type="text" id = "vkb" name="vkb" class="form-control"  value="<?= $data->v_kb; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Batas Pemenuhan</label>
                        <div class="col-sm-12">
                            <input type="text" id = "dpemenuhan "name="dpemenuhan" class="form-control date" 
                            value="">
                        </div>
                    </div>
                    <!-- <div class="form-group">
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
                    </div>  -->
                </div>
                
                    </div>
                            <div class="panel-body table-responsive">
                                <!-- <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                             <th>No</th> 
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <label class="col-md-12">Jumlah Data</label>
                                        <input style ="width:50px"type="text" name="jml" id="jml" value="0">
                                    </tbody>
                                </table> -->
                            </div>
                            </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // var counter = 0;

  var counter = document.getElementById("jml").value;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");

        var cols = "";
        if(cols =! ""){       
            document.getElementById("jml").value = counter;  
            cols += '<td><select  type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" onchange="getmaterial('+ counter + ');"></td>';
            cols += '<td><input type="text" id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '"></td>';
            cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" id="qty'+ counter + '" class="form-control" name="qty'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            cols += '<td><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';

        }
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#imaterial'+ counter).select2({
        placeholder: 'Pilih Material',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial'); ?>',
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
 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
 });
// $(document).ready(function () {
    // var counter = 0;

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });

    // $("#tabledata").on("click", ".ibtnDel", function (event) {
    //     $(this).closest("tr").remove();       
    //     counter -= 1
    // });
});

    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });
    function getmaterial(id){
        var imaterial = $('#imaterial'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_material': imaterial
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ematerialname'+id).val(data[0].e_material_name);
            $('#esatuan'+id).val(data[0].e_satuan);
            $('#isatuan'+id).val(data[0].i_satuan);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>