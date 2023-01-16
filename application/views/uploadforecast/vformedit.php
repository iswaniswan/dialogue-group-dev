<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-6">
                        <!-- <label class="col-md-12">Nama Customer</label> -->
                        <div class="form-group">
                        <label class="col-md-12">Kode Customer</label>
                            <div class="col-sm-12">
                                <input type="text" id = "periode" name="periode" class="form-control" value="<?= $data->periode;?>" readonly>
                            </div>
                        </div>
                    <div class="form-group">
                        <label class="col-md-12">Periode</label>
                        <div class="col-sm-12">
                        <input type="text" id = "ecustomername" name="ecustomername" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->e_customer_name;?>"readonly>
                            <input type="hidden" id = "icustomer" name="icustomer" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_customer;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Periode</label>
                        <div class="col-sm-12">
                            <input type="text" id = "periode" name="periode" class="form-control" value="<?= $data->periode;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Product</label>
                        <div class="col-sm-12">
                            <input type="text" id = "iproduct" name="iproduct" class="form-control" value="<?= $data->i_product;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i
                          class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" id="button" class="btn btn-info btn-rounded btn-sm"><i
                          class=""></i>&nbsp;&nbsp;Keluar</button>
                    </div>
                </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Nama Product</label>
                        <div class="col-sm-12">
                            <input type="text" id = "eproduct" name="eproduct" class="form-control" value="<?= $data->e_product_motifname;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Warna</label>
                        <div class="col-sm-12">
                            <input type="hidden" id = "icolor" name="icolor" class="form-control" value="<?= $data->i_color;?>" readonly>
                            <input type="text"   id = "ecolor" name="ecolor" class="form-control" value="<?= $data->e_color_name;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Quantity</label>
                        <div class="col-sm-12">
                            <input type="text" id = "qty" name="qty" class="form-control" value="<?= $data->n_quantity;?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jumlah Terkirim</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ndeliver" name="ndeliver" class="form-control" value="<?= $data->n_sisa;?>" readonly>
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
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <!-- <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr> -->
                                    </thead>
                                    <tbody>
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
    // var counter = 0;

  var counter = document.getElementById("jml").value;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var ikodemaster     = $("#ikodemaster").val();
        var newRow = $("<tr>");

        var cols = "";
        if(cols =! ""){       
            document.getElementById("jml").value = counter;  
            cols += '<td><input style="width:40px;" readonly type="text" id="no'+counter+'" name="no'+counter+'" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
            cols += '<td><select  type="text" id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '" onchange="getmaterial('+ counter + ');"></td>';
            cols += '<td><input type="text" readonly id="ematerialname'+ counter + '" type="text" class="form-control" name="ematerialname' + counter + '"></td>';
            cols += '<td><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
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
          url: '<?= base_url($folder.'/cform/datamaterial'); ?>'+ikodemaster,
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
                    url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
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
</script>