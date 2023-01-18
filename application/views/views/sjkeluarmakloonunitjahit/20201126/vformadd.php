<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Unit jahit</label><label class="col-md-4">Tanggal SJ Unit Jahit</label>
                        <div class="col-sm-8">
                            <select name="idepartement" id="idepartement" class="form-control select2" onchange="getmakloonjahit(this.value);">
                                <option value="" selected>-- Pilih Departemen Pembuat --</option>
                                <?php foreach ($departement as $key):?>
                                <option value="<?php echo $key->i_departement;?>"><?=$key->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                         <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-8">No Forecast</label><label class="col-md-4">Tanggal Forecast</label>
                        <div class="col-sm-8">
                            <input type="text" id= "iforecast" name="iforecast" class="form-control" maxlength="16" required value="">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id= "dforecast" name="dforecast" class="form-control date" required value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                            <button disabled type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                                    &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>  &nbsp;&nbsp;               
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-8">Makloon Unit Jahit</label><label class="col-md-4">Tanggal Pengembalian</label>
                        <div class="col-sm-8">
                            <select name="iunitjahit" id="iunitjahit" class="form-control select2" disabled onchange="getdiskon(this.value);">
                            </select>
                            <input type="hidden" id= "pkp" name="pkp" class="form-control" value="">
                            <input type="hidden" id= "ndiscount" name="ndiscount" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dback" name="dback" class="form-control date" required value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "edesc" name="edesc" class="form-control" value="">
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" hidden="true">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
 var counter = 0;
    
    $("#addrow").on("click", function () {
        var counter = $('#jml').val();
        counter++;
        $("#tabledata").attr("hidden", false);
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td style="width:70%;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris[]" value="'+counter+'"></td>';
        cols += '<td><input style="width:150px;" type="text" readonly  id="iproductwip'+ counter + '" type="text" class="form-control" name="iproductwip[]"></td>';
        cols += '<td><select style="width:300px;" type="text" id="eproductwip'+ counter + '" class="form-control select2" name="eproductwip[]" onchange="getwip('+ counter + ');"></td>';
        cols += '<td><input style="width:250px;" type="text" readonly  id="ecolor'+ counter + '" type="text" class="form-control" name="ecolor[]"><input type="hidden" readonly  id="icolor'+ counter + '" type="text" class="form-control" name="icolor[]"></td>';
        cols += '<td><input style="text-align: right;width:100px;" type="text" id="nquantitywip'+ counter + '" class="form-control" name="nquantitywip[]" value="0" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/><input type="hidden" readonly  id="vprice'+ counter + '" type="text" class="form-control" name="vprice[]"></td>';

        cols += '<td><input style="width:150px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
        cols += '<td><input style="width:350px;" type="text" readonly id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]"></td>';
        cols += '<td><input style="text-align: right;width:100px;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="0" onkeyup="cekval(this.value); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}"/></td>';
        cols += '<td><input style="width:300px;" type="text" id="eremark'+ counter + '" class="form-control" name="eremark[]" value=""/></td>';
        cols += '<td><button style="width:70px;" type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);

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

    function getmakloonjahit(id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getmakloonjahit');?>",
            data: "id=" + id,
            dataType: 'json',
            success: function (data) {
                $("#iunitjahit").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                    $("#addrow").attr("hidden", false);
                    $("#iunitjahit").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        });
    }

    function getdiskon(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getdiskonsupplier');?>",
            data: "id=" + id,
            dataType: 'json',
            success: function (data) {
                $('#pkp').val(data['isi']['f_supplier_pkp']);
                $('#ndiscount').val(data['isi']['v_diskon']);
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        });
    }

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
    

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
    }else{
        alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }

  function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    $('#nquantity'+counter).val(vjumlah);

  }

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function gettujuankirim(itujuan) {
        /*alert(iarea);*/
        $("#itujuan2").attr("hidden", false);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/gettujuankirim');?>",
            data:"itujuan="+itujuan,
            dataType: 'json',
            success: function(data){
                $("#itujuankirim").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                swal(XMLHttpRequest.responseText);
            }

        })
    }

    function cek() {
        var dsjk        = $('#dsjk').val();
        var iforecast   = $('#iforecast').val();
        var dforecast   = $('#dforecast').val();
        var istore      = $('#idepartement').val();
        var dback       = $('#dback').val();
        var isubbagian  = $('#idepartement').val();

        if (dsjk == '' || iforecast == '' || dforecast == '' || istore == '' || dback == '' || isubbagian == '') {
            swal('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }

    function getenabledsend() {
        $('#send').attr("disabled", false);
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        swal("Data Berhasil di Kirim");
    }

    $(document).ready(function(){
        $("#send").on("click", function () {
             var kode = $("#kode").val();
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $('#send').attr("disabled", false);
    });
</script>
