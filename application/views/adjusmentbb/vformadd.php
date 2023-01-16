<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-md-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="iadjustmenbb" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>

                        <div class="col-md-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" onchange="number();" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-md-10">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
            </div>
        </div>
    </div>
</div>

<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <div class="form-group row">
                <label class="col-md-5">Kategori Barang</label>
                <label class="col-md-6">Jenis Barang</label>
                <label class="col-md-1"></label>
                <div class="col-sm-5">
                    <select class="form-control select2" name="ikategori" id="ikategori">
                        <option value="all">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select class="form-control select2" name="ijenis" id="ijenis">
                        <option value="all">Semua Jenis</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div>
            </div>
        </div>
    </div>
   <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                    <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 45%;">Barang</th>
                        <th class="text-center" style="width: 10%;">Jml</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        number();

        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikategori : $('#ikategori').val(),
                        ibagian   : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });


    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

     
      /**
     * Tambah Item
     */

    var i = 0;
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+i+'">'+no+'</spanx></td>';
        cols += '<td><select data-nourut="'+i+'" id="imaterial'+i+ '" class="form-control input-sm" name="imaterial[]"></td>';
        cols += '<td><input type="text" id="nquantity'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td><input type="text" id="eremark'+i+'" class="form-control input-sm" name="eremark[]" placeholder="Isi keterangan jika ada!"/><input type="hidden" id="ikode'+i+'" name="ikode[]"/></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#imaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ikategori  : $('#ikategori').val(),
                        ijenis     : $('#ijenis').val(),
                        ibagian    : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event){
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#imaterial'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                $('#nquantity'+z).focus();
            }
        });
    });  

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(i);
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    }



    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty   =document.getElementById("nquantity"+i).value;
            if(parseFloat(qty)=='0'){
                swal('Jumlah Quantity tidak boleh kosong')
                document.getElementById("nquantity"+i).value='';
                break;
            }
        }
    }

    function cek() {
        var dadjus  = $('#dadjus').val();
        var ibagian = $('#ibagian').val();
        var jml     = $('#jml').val();

        if (dadjus == '' || ibagian == '' ||ibagian == null || jml == '0') {
            swal('Data Header Belum Lengkap !!');
            return false;
        } else {
               for (i=1; i<=jml; i++){  
                if($("#imaterial"+i).val() == '' || $("#nquantity"+i).val() == ''){
                    swal('Data Item Belum Lengkap!');
                    return false;                    
                } else {
                    return true;
                } 
            }
        }
    }

     //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddocument').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iadjustmenbb').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iadjustmenbb").attr("readonly", false);
        }else{
            $("#iadjustmenbb").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $( "#iadjustmenbb" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    function getwip(id){
            var ewip = $('#ewip'+id).val();

            var fields = ewip.split('|');
            var iwip = fields[0];
            var icolor = fields[1];
            $.ajax({
            type: "post",
            data: {
                    'iwip': iwip,
                    'icolor': icolor
            },
            url: '<?= base_url($folder.'/cform/getwip'); ?>',
            dataType: "json",
            success: function (data) {
                var id_product      = data['head']['id_product_wip'];
                var i_product       = data['head']['i_product_wip'];
                var e_color_name    = data['head']['e_color_name'];
                var id_color        = data['head']['id_color'];
                var i_color         = data['head']['i_color'];
                $('#idwip'+id).val(id_product);
                $('#iwip'+id).val(i_product);
                $('#ecolor'+id).val(e_color_name);
                $('#idcolor'+id).val(id_color);
                $('#icolor'+id).val(i_color);
                
                ada=false;
                var a   = $('#iwip'+id).val();
                var e   = $('#icolor'+id).val();
                var en  = $('#ecolor'+id).val();
                var jml = $('#jml').val();
                //alert(a+ " "+ e);
                for(i=1;i<=jml;i++){
                    if((a == $('#iwip'+i).val()) && (e == $('#icolor'+i).val()) && (i!=id)){
                        swal("kode : "+a+" Warna : "+en+" sudah ada !!!!!");
                        ada=true;
                        break;
                    }else{
                        ada=false;     
                    }
                }

                if(!ada){
                    $('#idwip'+id).val(id_product);
                    $('#iwip'+id).val(i_product);
                    $('#ecolor'+id).val(e_color_name);
                    $('#idcolor'+id).val(id_color);
                    $('#icolor'+id).val(i_color);
                    $('#ewip'+id).attr("disabled", true);
                    var counter = $('#jml').val();
                    var jmldetail = data['detail'].length;
                    $('#jml').val((jml-1)+jmldetail);
                    for (let a = 0; a < data['detail'].length; a++) {
                        var zz = a+1;
                                            
                        var i_product       = data['detail'][a]['i_product_wip'];
                        var id_product      = data['detail'][a]['id_product_wip'];
                        var i_color         = data['detail'][a]['i_color'];
                        var id_color        = data['detail'][a]['id_color'];
                        var id_material     = data['detail'][a]['id_material'];
                        var i_material      = data['detail'][a]['i_material'];
                        var e_material_name = data['detail'][a]['e_material_name'];

                        if (zz==1) {
                            $('#idmaterial'+id).val(id_material);
                            $('#imaterial'+id).val(i_material);
                            $('#ematerialname'+id).val(e_material_name);
                            $('#idwip'+id).val(id_product);
                            $('#delete'+id).val(id_product);
                            $('#delete'+id).closest('tr').addClass(id_product);
                        } else {
                            var cols        = "";
                            var newRow = $('<tr class="'+id_product+'">');        
                            cols += '<td style="text-align: center;" colspan="5"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" id="idwip'+ counter + '" type="text" class="form-control" name="idwip[]" value="'+id_product+'"><input style="width:100px;" type="hidden" id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]" value="'+i_product+'"><input type="hidden" id="idcolor'+ counter + '" class="form-control" name="idcolor[]" value="'+id_color+'"><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'"><input style="width:100px;" type="hidden" id="qtybarang'+ counter + '" class="form-control" name="qtybarang[]"></td>';

                            cols += '<td><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]" value="'+i_material+'"><input style="width:100px;" type="hidden" readonly  id="idmaterial'+ counter + '" type="text" class="form-control" name="idmaterial[]" value="'+id_material+'"></td>';
                            cols += '<td><input style="width:400px;" type="text" readonly id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" value="'+e_material_name+'"></td>';
                            cols += '<td><input style="width:100px;" type="number" id="qtybahan'+ counter + '" class="form-control" name="qtybahan[]" value="" placeholder="0" onkeyup="reformat(this); "></td>';    
                            cols += '<td><input style="width:400px;" type="text"  id="edesc'+ counter + '" class="form-control" name="edesc[]"><button type="button" style="display: none;" title="Delete" class="ibtnDel btn btn-circle btn-danger"  value="'+id_product+'"><i class="ti-close"></i></button></td>';
                            // cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                        //swal(zz+ " "+ counter);
                        counter++;          
                    }
                }else{
                    $('#idwip'+id).val('');
                    $('#iwip'+id).val('');
                    $('#ewip'+id).val('');
                    $('#ecolor'+id).val('');
                    $('#idcolor'+id).val('');
                    $('#icolor'+id).val('');
                    $('#idwip'+id).html('');
                    $('#iwip'+id).html('');
                    $('#ewip'+id).html('');
                    $('#ecolor'+id).html('');
                    $('#idcolor'+id).html('');
                    $('#icolor'+id).html('');
                }                
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                // $(this).find("td .inputitem").each(function() {
                //     if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                //         swal('Quantity Tidak Boleh Kosong Atau 0!');
                //         ada = true;
                //     }
                // });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
        
    }
</script>