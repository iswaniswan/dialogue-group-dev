<link href="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-9">PPN</label>
                        <div class="col-sm-3">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm" name="ppn" id="ppn" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button"  id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">

<div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kategori</label>
                        <label class="col-md-9">Sub Kategori</label>
                        
                        <div class="col-sm-3">
                            <select name="kategori" id="kategori" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="subkategori" id="subkategori" class="form-control select2">
                            </select>
                        </div>
                    </div>
                </div>
                <br><br>
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Material</h3>
    </div>
    <div class="col-sm-12">
        <div style="height:auto; overflow:auto; max-height: 400px;">
            <div class="table-responsive" style="width:1400px; ">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="95%">
                    <thead>
                        <tr>
                            <th style="text-align:center;">No</th>                          
                            <th style="text-align:center;">Barang</th>
                            <th style="text-align:center;">Satuan dari Supplier</th>                           
                            <th style="text-align:center;">Harga Konversi</th>                         
                            <th style="text-align:center;">Minimal Order</th>
                            <th style="text-align:center;">Tgl Berlaku</th>
                            <th style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<script src="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    
    $(document).ready(function () {
        
        $('#ibagian').select2({
            // placeholder: 'Cari Bagian',
            // allowClear: true,
            // ajax: {
            //     url: '<?= base_url($folder.'/cform/getbagian'); ?>',
            //     dataType: 'json',
            //     delay: 250,          
            //     processResults: function (data) {
            //         return {
            //             results: data
            //         };
            //     },
            //     cache: true
            // }
        });

        $('#ireff').select2({});

        var supplier = '';

        $('#isupplier').select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/supplier'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        }).change(function(){
            var ppn = '';
            var data = $(this).select2('data');
            data = data[0];
            if(data.ppn = 'I'){
                ppn = 'Include';
            }
            else if(data.ppn = 'E'){
                ppn = 'Exclude';
            }
            document.getElementById('ppn').value = ppn;
            supplier = data.id;

            $("#tabledatax tbody").remove();
        });

        $("#isupplier").change(function(){
            isupplier = $('#isupplier').val();
        });

        $('#kategori').select2({
            placeholder: 'Cari Kategori',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/kategori'); ?>',
                dataType: 'json',
                delay: 250,
                data:{
                        'isupplier': isupplier,
                    },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            cache: false
            }
        }).change(function(){
            $("#tabledatax tbody").remove();
        });

        $('#subkategori').select2({
            placeholder: 'Cari Sub Kategori',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/subkategori/'); ?>',
                dataType: 'json',
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
            cache: true
            }
        }).change(function(){
            $("#tabledatax tbody").remove();
        });
    
    })

    //     function getdataitem() {
            
    //     var idreff = $('#isupplier').val();
    //     var kategori = $('#kategori').val();
    //     var subkategori = $('#subkategori').val();
    //     var ppn = $('#ppn').val();

    //     if(ppn = 'iya'){
    //         ppn = "checked";
    //     }

    //     var cek =  $('#jml').val();
    //     if(cek > 0){
    //     $("#tabledatax tbody").remove();
    //     }
    //     // var ipengirim = $('#ipengirim').val();
    //     if (idreff) {
    //         $.ajax({
    //             type: "post",
    //             data: {
    //                 'idreff': idreff,
    //                 'kategori': kategori,
    //                 'subkategori': subkategori,
    //             },
    //             url: '<?= base_url($folder . '/cform/getdataitem'); ?>',
    //             dataType: "json",
    //             success: function(data) {

    //                 $('#jml').val(data['jmlitem']);
    //                 console.log(data);

    //                 i = 0;
    //                 for (let a = 0; a < data['jmlitem']; a++) {
    //                     i++;
    //                     //var no = a+1;
    //                     //count=$('#tabledatax tr').length;

    //                     var no     = $('#tabledatax tr').length;
    //                     var newRow = $("<tr>");
    //                     var cols   = "";
    //                     cols += '<td style="text-align: center;">'+ no +'<input type="hidden" class="form-control input-sm" readonly id="baris'+ a +'" name="baris'+ a +'" value="'+ a +'"></td>';
    //                     cols += '<td class="col-sm-1"><input style="width:400px;"  type="text" id="namabrg'+ a +'" class="form-control input-sm" name="namabrg'+ a +'"value="' + data['dataitem'][a]['i_material'] + ' - ' + data['dataitem'][a]['e_material_name'] + '"readonly></td>';
    //                     cols += '<td class="col-sm-1"><input style="width:150px;" type="hidden" id="ippn'+ a +'" class="form-control input-sm" name="ippn'+ a +'" value="' + data['dataitem'][a]['i_type_pajak'] + '" readonly><input type="checkbox" id="eppn" name="eppn" '+ ppn +' ></td>';
    //                     cols += '<td class="col-sm-2"><select style="width:200px;" type="text"  class="form-control input-sm select2" name="isatuansupplier'+ a +'" id="isatuansupplier'+ a +'" ></select></td>';
    //                     cols += '<td class="col-sm-1"><input style="width:200px;"  type="text" id="hargakonversi'+ a +'" class="form-control input-sm" name="hargakonversi'+ a +'" value="" readonly><select name="konversiharga'+ a +'" id="konversiharga'+ a +'" style="display:none"> </select><select name="angkafaktor'+ a +'" id="angkafaktor'+ a +'" style="display:none"> </select></td>';
    //                     cols += '<td class="col-sm-1"><input type="hidden" id="isatuanperusahaan'+ a +'" class="form-control input-sm" name="isatuanperusahaan'+ a +'"value="' + data['dataitem'][a]['$row->i_satuan_code'] + '"readonly><input type="hidden" id="satuanawal'+ a +'" class="form-control input-sm" name="satuanawal'+ a +'"value="' + data['dataitem'][a]['i_satuan_code'] + '"readonly><input style="width:200px;"  type="text" id="esatuankonversi'+ a +'" class="form-control input-sm" name="esatuankonversi'+ a +'" value="' + data['dataitem'][a]['e_satuan_name'] + '"readonly></td>';
    //                     cols += '<td class="col-sm-1"><input style="width:150px;"  type="text" id="norder'+ a +'" class="form-control input-sm" name="norder'+ a +'" value="" placeholder="0" ></td>';
    //                     cols += '<td class="col-sm-1"><input style="width:150px;"  type="text" id="dberlaku'+ a +'" class="form-control input-sm date" name="dberlaku'+ a +'"value=""readonly ></td>';
    //                     cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

    //                     newRow.append(cols);
    //                     $("#tabledatax").append(newRow);

    //                     $('#isatuansupplier'+a).select2({});
    //                 }

    //                 function formatSelection(val) {
    //                     return val.name;
    //                 }

    //                 $("#tabledatax").on("click", ".ibtnDel", function(event) {
    //                     $(this).closest("tr").remove();
    //                 });
    //             },
    //             error: function() {
    //                 alert('Error :)');
    //             }
    //         });
    //     }
    // }

        var isubkategori = "";
        var ikategori = "";
        var isupplier = "";

        $("#subkategori").change(function(){
            isubkategori = $('#subkategori').val();
        });

        $("#kategori").change(function(){
            ikategori = $('#kategori').val();
        });
        
        $("#isupplier").change(function(){
            isupplier = $('#isupplier').val();
        });

    var i = $("#jml").val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);

        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td style="text-align: center;">'+ no +'<input type="hidden" class="form-control input-sm" readonly id="baris'+ i +'" name="baris'+ i +'" value="'+ i +'"></td>';
        cols += '<td size="6"><select id="namabrg'+ i +'" class="form-control input-sm" name="namabrg'+ i +'" ></select></td>';
        cols += '<td size="12"><select class="form-control input-sm" name="isatuansupplier'+ i +'" id="isatuansupplier'+ i +'" ></select></td>';
        cols += '<td class="col-sm-1"><input style="width:200px;"  type="text" id="hargakonversi'+ i +'" class="form-control input-sm" name="hargakonversi'+ i +'" ></td>';
        cols += '<td class="col-sm-1"><input style="width:150px;"  type="text" id="norder'+ i +'" class="form-control input-sm" name="norder'+ i +'" value="" placeholder="0" ></td>';
        cols += '<td size="1"><input style="width:150px;"  type="text" id="dberlaku'+ i +'" class="form-control input-sm date" name="dberlaku'+ i +'"value="" ></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $("#dberlaku"+i).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#namabrg'+ i).select2({
            placeholder: 'Cari Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: "<?php echo site_url($folder.'/Cform/getmaterial');?>",
                data:{
                        'isubkategori': isubkategori,
                        'ikategori': ikategori,
                        'isupplier': isupplier,
                    },
                dataType: 'json',
                processResults: function (data) {
                return {
                            results: data
                        };
                },
                cache: true

            }
        }).change(function(){
            var data = $(this).select2('data');
            data = data[0];
            var o = new Option(data.satuanname, data.satuancode);
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html(data.satuanname);
            $("#isatuansupplier" + no).append(o);
        });

        $('#isatuansupplier'+ i).select2({
            placeholder: 'Cari Kode / Nama Produk',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/satuan/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(){
            // var data = $(this).select2('data');
            // data = data[0];
            // document.getElementById('ikategori'+i).value = data.ikelompok;
            // document.getElementById('kategori'+i).value = data.kelompok;
        });

        $('#hargakonversi'+i).mask('#.##0', {reverse: true});
        

    });
    
    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        /* alert(i); */
        /* $('#jml').val(i); */
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });

    

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select .id").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Jml harus lebih besar dari 0 !');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
    }) 

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>