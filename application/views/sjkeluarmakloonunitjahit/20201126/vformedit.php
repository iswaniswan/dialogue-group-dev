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
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Unit jahit</label><label class="col-md-4">No SJ Keluar Makloon</label><label class="col-md-4">Tanggal SJ Keluar Makloon</label>
                        <div class="col-sm-4">
                            <input type="text" id="esubbagian" name="esubbagian" class="form-control" value="<?=$isi->e_departement_name;?>" readonly>
                            <input type="hidden" id="isubbagian" name="isubbagian" class="form-control" value="<?=$isi->i_kode_master;?>">
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?=$isi->i_kode_master;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="isj" name="isj" class="form-control" value="<?= $isi->i_sj;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?= date('d-m-Y', strtotime($isi->d_sj));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-8">No Forecast</label><label class="col-md-4">Tanggal Forecast</label>
                        <div class="col-sm-8">
                            <input type="text" id= "iforecast" name="iforecast" class="form-control" maxlength="16" readonly value="<?=$isi->i_forecast;?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id= "dforecast" name="dforecast" class="form-control" readonly value="<?= date('d-m-Y', strtotime($isi->d_forecast));?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php
                                if($isi->f_sj_cancel != 't'){?>
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                    &nbsp;&nbsp; 
                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                                    &nbsp;&nbsp;
                                <?}?>
                            <button type="button" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-inverse btn-rounded btn-sm" ><i  class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-8">Makloon Unit Jahit</label><label class="col-md-4">Tanggal Pengembalian</label>
                        <div class="col-sm-8">
                            <input type="text" id="eunitjahit" name="eunitjahit" class="form-control" value="<?= $isi->e_supplier_name;?>" readonly>
                            <input type="hidden" id="iunitjahit" name="iunitjahit" class="form-control" value="<?= $isi->i_unit_jahit;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control date" value="<?= date('d-m-Y', strtotime($isi->d_back));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "edesc" name="edesc" class="form-control" value="<?=$isi->e_remark;?>">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center;">No</th>
                                <th style="text-align: center;">Kode Barang WIP</th>
                                <th style="text-align: center;">Nama Barang WIP</th>  
                                <th style="text-align: center;">Warna</th>  
                                <th style="text-align: center;">Qty WIP</th>
                                <th style="text-align: center;">Kode Barang Material</th>  
                                <th style="text-align: center;">Nama Barang Material</th>  
                                <th style="text-align: center;">Qty Material</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                    <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?=$jmlitem;?>">
                </div>
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
        $("#addrow").attr("disabled", true);
    });

    function getenabledsend() {
        $('#send').attr("disabled", true);
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        swal("Data Berhasil di Kirim");
    }

    $(document).ready(function(){
        $("#send").on("click", function () {
             var kode = $("#isj").val();
             $.ajax({
                 type: "POST",
                 url: "<?= base_url($folder.'/cform/send'); ?>",
                 data: {
                          'kode'  : kode,
                         },
                 dataType: 'json',
                 delay: 250, 
                 success: function(data) {
                     return {
                     results: data
                     };
                 },
                  cache: true
             });
         });
    });
getdetailsj();
    $(document).ready(function () {
        $("#addrow").on("click", function () {
            var counter = $('#jml').val();
            //alert (counter);
            counter++;
            $("#tabledata").attr("hidden", false);
            document.getElementById("jml").value = counter;
            count=$('#tabledata tr').length;
            var newRow = $("<tr>");

            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris[]" value="'+counter+'"></td>';
            cols += '<td><input style="width:150px;" type="text" readonly  id="iproductwip'+ counter + '" type="text" class="form-control" name="iproductwip[]"></td>';
            cols += '<td><select style="width:300px;" type="text" id="eproductwip'+ counter + '" class="form-control select2" name="eproductwip[]" onchange="getwip('+ counter + ');"></td>';
            cols += '<td><input style="width:250px;" type="text" readonly  id="ecolor'+ counter + '" type="text" class="form-control" name="ecolor[]"><input type="hidden" readonly  id="icolor'+ counter + '" type="text" class="form-control" name="icolor[]"></td>';
            cols += '<td><input style="text-align: right;width:100px;" type="text" id="nquantitywip'+ counter + '" class="form-control" name="nquantitywip[]" value="0" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';

            cols += '<td><input style="width:150px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
            cols += '<td><input style="width:350px;" type="text" readonly id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]"></td>';
            cols += '<td><input style="text-align:right;width:100px;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="0" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';
            cols += '<td><input style="width:300px;" type="text" id="eremark'+ counter + '" class="form-control" name="eremark[]" value=""/></td>';
            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#eproductwip'+counter).select2({
            placeholder: 'Pilih Product WIP',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder);?>/cform/getproductwip/',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
          });
        });

        function formatSelection(val) {
            return val.name;
        }

        $("#tabledata").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
        });
    });

    function getwip(id){
        var eproductwip = $('#eproductwip'+id).val();
        var dsjk        = $('#dsjk').val();

        var fields = eproductwip.split('|');
        var iproductwip = fields[0];
        var icolor = fields[1];

        $.ajax({
            type: "post",
            data: {
                'iproductwip': iproductwip,
                'icolor': icolor,
                'dsjk'  : dsjk
            },
            url: '<?= base_url($folder.'/cform/getwip'); ?>',
            dataType: "json",
            success: function (data) {
                var i_product       = data['isi']['i_product'];
                var e_color_name    = data['isi']['e_color_name'];
                var i_color         = data['isi']['i_color'];
                var v_price         = '';
                var statusharga     = data['isi']['f_status_aktif'];

                if(statusharga == 'f'){
                    swal("Harga makloon produk : "+i_product+" pada tanggal "+dsjk+" sudah tidak aktif. Aktifkan terlebih dahulu harga makloon dari produk yang dipilih.");
                    v_price = 0;
                    $("#submit").attr("disabled", true);
                    $("#addrow").attr("disabled", true);
                }else{
                    v_price = data['isi']['v_price'];
                }

                $('#iproductwip'+id).val(i_product);
                $('#ecolor'+id).val(e_color_name);
                $('#icolor'+id).val(i_color);
                $('#vprice'+id).val(v_price);
                
                ada=false;
                var a   = $('#iproductwip'+id).val();
                var e   = $('#icolor'+id).val();
                var en  = $('#ecolor'+id).val();
                var jml = $('#jml').val();
               
                for(i=1;i<=jml;i++){
                    if((a == $('#iproductwip'+i).val()) && (e == $('#icolor'+i).val()) && (i!=id)){
                        swal("kode : "+a+" Warna : "+en+" sudah ada !!!!!");
                        ada=true;
                        break;
                    }else{
                        ada=false;     
                    }
                }

                if(!ada){
                    $('#iproductwip'+id).val(i_product);
                    $('#ecolor'+id).val(e_color_name);
                    $('#icolor'+id).val(i_color);
                    $('#vprice'+id).val(v_price);
                    $('#eproductwip'+id).attr("disabled", true);
                    var counter = $('#jml').val();
                    var jmldetail = data['detail'].length;
                    $('#jml').val((jml-1)+jmldetail);
                    for (let a = 0; a < data['detail'].length; a++) {
                        var zz = a+1;
                                            
                        var i_product       = data['detail'][a]['i_product'];
                        var i_color         = data['detail'][a]['i_color'];
                        var i_material      = data['detail'][a]['i_material'];
                        var e_material_name = data['detail'][a]['e_material_name'];

                        if (zz==1) {
                            $('#imaterial'+id).val(i_material);
                            $('#ematerialname'+id).val(e_material_name);
                        } else {
                            var cols        = "";
                            var newRow = $("<tr>");        
                            cols += '<td style="text-align: center;" colspan="5"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" id="iproductwip'+ counter + '" type="text" class="form-control" name="iproductwip[]" value="'+i_product+'"><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'"/><input style="text-align: right;" type="hidden" id="nquantitywip'+ counter + '" class="form-control" name="nquantitywip[]" value="0" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';

                            cols += '<td><input type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]" value="'+i_material+'"></td>';
                            cols += '<td><input type="text" readonly id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" value="'+e_material_name+'"></td>';
                            cols += '<td><input style="text-align: right;" type="text" id="nquantity'+ counter + '" class="form-control"  onfocus="if(this.value==\'0\'){this.value=\'\';}" name="nquantity[]" value="0" onkeyup="cekval(this.value); reformat(this); "/></td>';    
                            cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark[]"></td>';
                            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                            newRow.append(cols);
                            $("#tabledata").append(newRow);
                        }
                        counter++;
          
                    }
                }else{
                    $('#iproductwip'+id).val('');
                    $('#eproductwip'+id).val('');
                    $('#ecolor'+id).val('');
                    $('#icolor'+id).val('');
                    $('#vprice'+id).val('');
                    $('#iproductwip'+id).html('');
                    $('#eproductwip'+id).html('');
                    $('#ecolor'+id).html('');
                    $('#icolor'+id).html('');
                    $('#vprice'+id).html('');
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getdetailsj() {
        removeBody();
        var isj = $('#isj').val();
        var gudang = $('#istore').val();
        $.ajax({
            type: "post",
            data: {
                'isj': isj,
                'gudang': gudang
            },
            url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                var lastproduct ='';
                var lastcolor ='';
                for (let a = 0; a < data['detail'].length; a++) {
                    var counter = a+1;
                    count=$('#tabledata tr').length;                   
                    var i_product           = data['detail'][a]['i_product'];
                    var e_product_name      = data['detail'][a]['e_namabrg'];
                    var i_color             = data['detail'][a]['i_color'];
                    var e_color             = data['detail'][a]['e_color_name'];
                    var n_quantity_product  = data['detail'][a]['n_quantity_wip'];
                    var v_price             = data['detail'][a]['v_price'];
                    var i_material          = data['detail'][a]['i_material'];
                    var e_material_name     = data['detail'][a]['e_material_name'];
                    var n_quantity_material = data['detail'][a]['n_quantity'];
                    var e_remark = data['detail'][a]['e_remark'];

                    var cols        = "";
                    var newRow = $("<tr>");

                    if (lastproduct == i_product && lastcolor == i_color) {
                        cols += '<td style="text-align: center;width:70px;" colspan="5"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]" value="'+i_product+'"><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'" /><input style="width:100px;" readonly type="hidden" id="qtybarang'+ counter + '" class="form-control" name="qtybarang[]" value="'+n_quantity_product+'" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';
                    } else {
                        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td><input style="width:150px;" type="text" readonly  id="iproductwip'+ counter + '" type="text" class="form-control" name="iproductwip[]" value="'+i_product+'"></td>';
                        cols += '<td><input style="width:300px;" type="text" id="eproductwip'+ counter + '" class="form-control" name="eproductwip[]" value="'+e_product_name+'" readonly></td>';
                        cols += '<td><input style="width:250px;" type="text" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]" value="'+e_color+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'" /></td>';
                        cols += '<td><input style="text-align:right;width:100px;" type="text" id="nquantitywip'+ counter + '" class="form-control" name="nquantitywip[]" value="'+n_quantity_product+'" onfocus="if(this.value==\'0\'){this.value=\'\';}"/><input type="hidden" readonly  id="vprice'+ counter + '" class="form-control" name="vprice[]" value="'+v_price+'"></td>';
                    }
                    
                    cols += '<td><input style="width:150px;" type="text" readonly id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]" value="'+i_material+'"></td>';
                    cols += '<td><input style="width:350px;" type="text" readonly id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" value="'+e_material_name+'"></td>';
                    cols += '<td><input style="text-align:right;width:100px;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this); " value="'+n_quantity_material+'"/></td>';    
                    cols += '<td><input style="width:300px;" type="text"  id="eremark'+ counter + '" class="form-control" name="eremark[]" value="'+e_remark+'"></td>';
                    cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

                    lastcolor = i_color;
                    lastproduct = i_product;
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
      
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
    }
    

    function cekval(input){
        var jml   = counter;
        var num = input.replace(/\,/g,'');
        if(!isNaN(num)){
        }else{
            alert('input harus numerik !!!');
            input = input.substring(0,input.length-1);
        }
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function cek() {
        var dsjk        = $('#dsjk').val();
        var isj         = $('#isj').val();
        var iforecast   = $('#iforecast').val();
        var dforecast   = $('#dforecast').val();
        var istore      = $('#istore').val();
        var dback       = $('#dback').val();
        var isubbagian  = $('#isubbagian').val();
        var iunitjahit  = $('#iunitjahit').val();


        if (isj == '' || dsjk == '' || iforecast == '' || dforecast == '' || istore == '' || dback == '' || isubbagian == '' || iunitjahit == '') {
            swal('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }

    function hapusdetail(isj,iproduct,imaterial,icolor) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'isj'       : isj,
                        'iproduct'  : iproduct,
                        'imaterial' : imaterial,
                        'icolor'    : icolor
                    },
                    url: '<?= base_url($folder.'/cform/deletedetail'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $sj.'/'.$gudang;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }
</script>