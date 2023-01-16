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
                <div class="col-md-6">  
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Schedule</label><label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" name="dschedule" class="form-control date" maxlength="5"  value="" readonly>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" name="eremarkh" class="form-control" maxlength="60"  value="" >
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>               
                        <input type="hidden" name="jml" id="jml">
                    </div>
                </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                    <thead>
                        <tr>
                            <th style width="5%">No</th>
                            <th style width="10%">Kode Barang</th>
                            <th style width="20%">Nama Barang</th>
                            <th style width="10%">Kode Material</th>
                            <th style width="20%">Nama Material</th>
                            <th style width="10%">Warna</th>
                            <!-- <th style width="10%">Satuan</th> -->
                            <th style width="5">Jumlah Grade A</th>
                            <th style width="5">Jumlah Grade B</th>
                            <th style width="20%">Keterangan</th>
                            <th style width="5%">Action</th>
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
        cols += '<td><input class="form-control" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'">';
        // cols += '<td><select  type="text" id="iproduct'+ counter + '" class="form-control select2" name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"></td>';
        // cols += '<td><input type="text" readonly  id="eproductname'+ counter + '" type="text" class="form-control" name="eproductname' + counter + '"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct'+ counter +'"><input style="width:100px;" type="hidden" readonly  id="eproduct'+ counter + '" type="text" class="form-control" name="eproduct'+ counter +'"></td>';
        cols += '<td><select type="text" id="eproductname'+ counter + '" class="form-control select2" name="eproductname'+ counter +'" onchange="getproduct('+ counter + ');"></select></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial'+ counter +'"><input style="width:100px;" type="hidden" readonly  id="eproduct'+ counter + '" type="text" class="form-control" name="eproduct'+ counter +'"></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control select2" name="ematerialname'+ counter +'" onchange="getmaterial('+ counter + ');"></select></td>';
        cols += '<td><input type="text" readonly  id="icolorname'+ counter + '" type="text" class="form-control" name="icolorname' + counter + '"><input type="hidden" readonly  id="icolor'+ counter + '" type="text" class="form-control" name="icolor' + counter + '"></td>';
        // cols += '<td><input type="text" readonly  id="isatuan'+ counter + '" type="text" class="form-control" name="isatuan' + counter + '"><input type="hidden" readonly  id="esatuan'+ counter + '" type="text" class="form-control" name="esatuan' + counter + '"></td>';
        cols += '<td><input type="text" id="nqtya'+ counter + '" type="text" class="form-control" name="nqtya' + counter + '"></td>';
        cols += '<td><input type="text" id="nqtyb'+ counter + '" type="text" class="form-control" name="nqtyb' + counter + '"></td>';
        cols += '<td><input type="text" id="eremark'+ counter + '" type="text" class="form-control" name="eremark' + counter + '"></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#eproductname'+ counter).select2({
            placeholder: 'Pilih Kode Barang',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/product'); ?>',
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

    function getmet(){
        
    }

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });  

    $(document).ready(function () {
      $('.select2').select2();
      showCalendar('.date');
    });

    function getproduct(id) {
        var iproduct = $('#eproductname'+id).val();
        $('#ematerialname'+counter).select2({
            placeholder: 'Pilih Kode Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/material/'); ?>'+iproduct,
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

        $.ajax({
            type: "POST",
            data:"i_product="+iproduct,
            url: "<?php echo site_url($folder.'/Cform/getproduct');?>",
            dataType: 'json',
            success: function(data){
                $('#iproduct'+id).val(data[0].i_product);
                $('#eproduct'+id).val(data[0].e_product_namewip);
                $('#icolorname'+id).val(data[0].e_color_name);
                $('#icolor'+id).val(data[0].i_color);

                ada=false;
                var a = $('#iproduct'+id).val();
                var e = $('#eproductname'+id).val();
                var jml = $('#jml').val();
                for(i=1;i<=jml;i++){
                  if((a == $('#iproduct'+i).val()) && (i!=jml)){
                    swal ("Kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                  }else{
                    ada=false;     
                  }
                }
                if(!ada){
                    var iproduct    = $('#iproduct'+id).val();
                    $.ajax({
                        type: "post",
                        data: {
                            'iproduct'  : iproduct,
                        },
                        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                        dataType: "json",
                        success: function (data) {
                            $('#eproductname'+id).val(data[0].e_product_namewip);
                            $('#icolorname'+id).val(data[0].e_color_name);
                            $('#icolor'+id).val(data[0].i_color);
                        },
                    });
                }else{
                    $('#iproduct'+id).html('');
                    $('#eproductname'+id).val('');
                    $('#icolorname'+id).val('');
                    $('#icolor'+id).val('');
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function getmaterial(id){
        var ematerialname = $('#ematerialname'+id).val();
        $.ajax({
        type: "post",
        data: {
            'ematerialname': ematerialname
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function (data) {
            $('#imaterial'+id).val(data[0].i_material);

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
                    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
        });
    }
</script>