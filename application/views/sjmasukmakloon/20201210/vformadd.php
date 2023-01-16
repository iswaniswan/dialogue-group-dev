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
                        <label class="col-md-6">Bagian</label>
                        <label class="col-md-6">Tanggal SJ Masuk</label>
                        <div class="col-sm-6">
                           <select name="ibagian" id="ibagian" class="form-control select2" >
                                <?php foreach ($kodemaster as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?=$ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="kelompokbrg" name="kelompokbrg" class="form-control" value="<?= $kelompokbrg; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="col-md-12">Nomor Referensi</label>
                        <div class="col-sm-11">
                            <select name="ireff" id="ireff" multiple="multiple" class="form-control select2">
                            </select>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div> 
                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button> 
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button> 
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Supplier</label>                        
                        <div class="col-sm-12">
                            <select nama="isupplier" id="isupplier" class="form-control select2" onchange="getreff(this.value);">
                            <option value="" selected>-- Pilih Supplier --</option>
                            <?php foreach ($supplier as $isupplier):?>
                                <option value="<?php echo $isupplier->i_supplier;?>"> <?= $isupplier->e_supplier_name;?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                            <input type="hidden" id="esupplier" name="esupplier" class="form-control" readonly>
                            <input type="hidden" id="supplier" name="supplier" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Type Makloon</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="itypemakloon" name="itypemakloon" class="form-control" readonly>
                            <input type="text" id="etypemakloon" name="etypemakloon" class="form-control" readonly>
                        </div>
                    </div>                  
                    <input type="hidden" name="jml" id="jml" value ="0">
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Referensi</th>
                                    <th>Kode Barang ( Keluar )</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Kode Barang ( Masuk )</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>.
                                    <th>Action</th>
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

<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#send").attr("disabled", false);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    $("#send").attr("disabled", true);
});

function getenabledsend() {
    swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
    $('#send').attr("disabled", true);
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

function getreff(id){
    $.ajax({
        placeholder: 'Cari No. SJ Keluar Makloon',
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getsjkm');?>",
        data:{
            'isupplier' : id,
        }, 
        dataType: 'json',
        success: function (data) {
            $("#ireff").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#ireff").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
} 

  function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    $('#nquantity'+counter).val(vjumlah);

  }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getstore() {
        var gudang = $('#ikodemaster').val();

        if (gudang == "") {
            $("#ireff").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#ireff").attr("disabled", false);
        }
        
        $('#ireff').html('');
        $('#ireff').val('');
    }

    $("#ireff").change(function() {
        $('#ireff').val($(this).val());
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        var gudang = $('#istore').val();
        $.ajax({
            type: "post",
            data: {
                //'isjkm': sjkm,
                'gudang': gudang,
                'isjkm'  :  $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailsjkm'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledata').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                var isupplier       = data['head']['i_supplier'];
                var esupplier       = data['head']['e_supplier_name'];
                var itypemakloon    = data['head']['i_type_makloon'];
                var etypemakloon    = data['head']['e_type_makloon'];
                $('#supplier').val(isupplier);
                $('#esupplier').val(esupplier);
                $('#itypemakloon').val(itypemakloon);
                $('#etypemakloon').val(etypemakloon);

                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var isjkm          = data['detail'][a]['i_sj'];
                    var i_material     = data['detail'][a]['i_material'];
                    var e_material     = data['detail'][a]['e_material_name'];
                    var n_qty          = data['detail'][a]['n_pemenuhan'];
                    var n_qtyy         = data['detail'][a]['n_pemenuhan2'];
                    var i_satuan       = data['detail'][a]['i_satuan'];
                    var e_satuan       = data['detail'][a]['e_satuan'];
                    var v_price        = data['detail'][a]['v_price'];
                    var namabarang     = i_material + ' - ' + e_material;

                    var cols   = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" readonly id="i_material'+zz+'" name="i_material'+zz+'" value="'+i_material+'"></td>';
                    cols += '<td style="text-align: center"><input style="width:200px;" type="text" class="form-control" readonly id="ireferensi'+zz+'" name="ireferensi'+zz+'" value="'+isjkm+'"></td>';
                    cols += '<td><input style="width:400px;" class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+namabarang+'" value="'+namabarang+'"></td>';
                    cols += '<td><input style="width:100px;" readonly class="form-control" style="text-align:left;" id="n_qty'+zz+'" name="n_qty'+zz+'" value="'+n_qty+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:left;" id="i_satuan'+zz+'" name="i_satuan'+zz+'" value="'+i_satuan+'"><input style="width:100px;" readonly class="form-control" style="text-align:left;" id="e_satuan'+zz+'" name="e_satuan'+zz+'" value="'+e_satuan+'"><input type="hidden" class="form-control" style="text-align:left;" id="v_price'+zz+'" name="v_price'+zz+'" value="'+v_price+'"></td>';

                    cols += '<td><select type="text" style=width:320px id="i_2material'+zz+'" class="form-control" name="i_2material'+ zz + '" value="" onchange="getmaterial('+ zz + ');"></td>';
                    cols += '<td><input type="text" style="width:100px;" id="n_2qty'+ zz + '" class="form-control" placeholder="0" name="n_2qty'+ zz + '" value="" onkeyup="validasi(this.value); reformat(this);"/><input type="hidden" style="width:100px;" readonly class="form-control" style="text-align:left;" id="n_qtyy'+zz+'" name="n_qtyy'+zz+'" value="'+n_qtyy+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:left;" id="i_2satuan'+zz+'" name="i_2satuan'+zz+'" value=""><input style="width:100px;" readonly class="form-control" style="text-align:left;" id="e_2satuan'+zz+'" name="e_2satuan'+zz+'" value=""></td>';
                    cols += '<td><input style="width:300px;" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';

                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="setaction('+zz+');"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    var i_material = $('#i_material'+zz).val();
                    var ireferensi = $('#ireferensi'+zz).val();
                    var kelompokbrg= $('#kelompokbrg').val();
                    $('#i_2material'+zz).select2({
                        placeholder: 'Pilih Material',
                        allowClear: true,
                        ajax: {
                            url: '<?= base_url($folder);?>/cform/datamaterial/'+i_material+'/'+ireferensi+'/'+kelompokbrg,
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
                }
            },
            error: function () {
                swal('Data Kosong :)');
            }
        });
        xx = $('#jml').val();
    });
    
    function getmaterial(id){
        var imaterial = $('#i_2material'+id).val();
        $.ajax({
            type: "post",
            data: {
                'i_material': imaterial
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#e_2satuan'+id).val(data[0].e_satuan);
                $('#i_2satuan'+id).val(data[0].i_satuan_code);
                $('#chk'+id).prop("checked", true);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function setaction(id) {
        if($('#chk'+id).prop('checked')) {

        } else {
            $('#i_2satuan'+id).val("");
            $('#e_2satuan'+id).val("");
            $('#i_2material'+id).val("").change();
            $('#n_2qty'+id).val("");
        }
    }

    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtypp   =document.getElementById("n_2qty"+i).value;
            qtypm   =document.getElementById("n_qtyy"+i).value;
            if(parseFloat(qtypp)>parseFloat(qtypm)){
                swal('Jumlah Quantity Melebihi SJ Keluar');
                document.getElementById("n_2qty"+i).value='';
                break;
            }else if(parseFloat(qtypp)=='0'){
                swal('Jumlah Quantity tidak boleh kosong')
                document.getElementById("n_2qty"+i).value='';
                break;
            }
        }
    }

    function cek() {
        var dsjk = $('#dsjk').val();
        var isjkm = $('#ireff').val();
        var istore = $('#istore').val();

        if (dsjk == '' || isjkm == null || istore == '') {
            swal('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }
</script>