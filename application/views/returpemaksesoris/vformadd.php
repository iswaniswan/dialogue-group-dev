<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Nomor Referensi</label><!-- dari Nota Pembelia -->
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);">
                            </select>
                            <input type="hidden" name="esupplier" id="esupplier" class="form-control" value="">
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="get(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"></textarea>
                            <input class="form-control" type="hidden" id="vtotal" name="vtotal" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail" hidden="true">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Barang</th>
                        <th style="text-align:center;">Nama Barang</th>
                        <th style="text-align:center;">Satuan</th>
                        <th style="text-align:center;">Qty BTB</th>
                        <th style="text-align:center;">Qty Retur</th>
                        <th style="text-align:center;">Keterangan</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
         $("input").attr("disabled", true);
         $("select").attr("disabled", true);
         $("#submit").attr("disabled", true);
         $("#send").attr("hidden", false);
    });

    $(document).ready(function(){
        $('.select2').select2();
        showCalendar('.date');

        $("#submit").attr("disabled", true);
        $("#ifaktur").attr("disabled", true);
        $('#iretur').mask('SSS-0000-000000S');

        $('#isupplier').select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/bacasupplier'); ?>',
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
        number();
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $( "#iretur" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ikodemaster').val(),
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iretur").attr("readonly", false);
        }else{
            $("#iretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $('#ifaktur').on('change',function(){
        $("#submit").prop("disabled",false);
    });

    function getstore() {
        var gudang = $('#ikodemaster').val();
        if (gudang == "") {
            $("#ifaktur").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
        }
    }

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dretur').val(),
                'ibagian' : $('#ikodemaster').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iretur').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getnota(id){
         $("#ifaktur").attr("disabled", false);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getnota');?>",
            data:{
                'isupplier': id
            },
            dataType: 'json',
            success: function(data){
                $("#ifaktur").html(data.kop);
                $("#esupplier").val(data.esupplier);
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function removeBody(){
        var tbl = document.getElementById("tabledatax");    // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
    }

    function get(id) {   
        $("#detail").attr("hidden", false);
        var isupplier = $('#isupplier').val();
        var ibagian = $('#ikodemaster').val();
        removeBody();       
        $.ajax({
            type: "post",
            data: {
                'idnota' : id,
                'isupplier' : isupplier,
                'ibagian' : ibagian,
            },
            url: '<?= base_url($folder.'/cform/getmemo'); ?>',
            dataType: "json",
            success: function (data) {
                var dnota               = data['head']['d_nota'];
                $('#dnota').val(dnota);
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var isj                 = data['detail'][a]['i_sj_supplier'];
                    var ibtb                = data['detail'][a]['id_btb'];
                    var inota               = data['detail'][a]['id_nota'];
                    var produk              = data['detail'][a]['i_material'];
                    var namaproduk          = data['detail'][a]['e_material_name'];
                    var qtyorder            = data['detail'][a]['n_quantity'];
                    var esatuan             = data['detail'][a]['e_satuan_name'];
                    var isatuan             = data['detail'][a]['i_satuan_code'];
                    var vprice              = data['detail'][a]['v_price'];
                    var x = $('#jml').val();
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input style="width:100px;" class="form-control" readonly type="hidden" id="inota'+zz+'" name="inota'+zz+'" value="'+inota+'"><input style="width:100px;" class="form-control" readonly type="hidden" id="isj'+zz+'" name="isj'+zz+'" value="'+isj+'"><input style="width:100px;" class="form-control" readonly type="hidden" id="ibtb'+zz+'" name="ibtb'+zz+'" value="'+ibtb+'"></td>';
                    cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+produk+'"></td>';
                    cols += '<td><input style="width:400px;" class="form-control" readonly type="text" id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+namaproduk+'"></td>';
                    cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="esatuan'+zz+'" name="esatuan'+zz+'" value="'+esatuan+'"><input readonly style="width:70px;"  type="hidden" id="isatuan'+zz+'" name="isatuan'+zz+'" value="'+isatuan+'"></td>';
                    cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="qty'+zz+'" name="qty'+zz+'" value="'+qtyorder+'"><input readonly style="width:90px;" class="form-control" type="hidden" id="vunitprice'+zz+'" name="vunitprice'+zz+'" value="'+vprice+'"></td>';
                    cols += '<td><input style="width:100px;" class="form-control" type="text" id="qtyretur'+zz+'" name="qtyretur'+zz+'" placeholder="0" onkeyup="validasi('+zz+'); reformat(this);gettotal(this);"></td>';
                    cols += '<td><input class="form-control" style="width:300px;"  type="text" id="edesc'+zz+'" name="edesc'+zz+'" value="" placeholder="Isi keterangan jika ada!"></td>';
                    cols += '<td><input type="checkbox" name="cek'+zz+'" value="cek" id="cek'+zz+'" onclick="gettotal(this);"></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
                // max_tgl();
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    }   

    function gettotal(id){
        var hasiltotal = 0;
        jml = document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty     = $('#qtyretur'+i).val();
            harga   = $('#vunitprice'+i).val();
            vtotal  = qty * harga;
            hasiltotal = hasiltotal + vtotal;
        }
        $('#vtotal').val(hasiltotal);
    }


    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtysj   =document.getElementById("qty"+i).value;
            qtyretur=document.getElementById("qtyretur"+i).value;
            if(parseFloat(qtyretur)>parseFloat(qtysj)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah BTB');
                document.getElementById("qtyretur"+i).value=0;
                break;
            }else if(parseFloat(qtyretur)=='0'){
                swal('Jumlah Retur tidak boleh kosong')
                document.getElementById("qtyretur"+i).value='';
                break;
            }
        }
    }

    function max_tgl() {
      $('#dretur').datepicker('destroy');
      $('#dretur').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dnota').value,
      });
    }
    $('#dretur').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: "dd-mm-yyyy",
      todayBtn: "linked",
      daysOfWeekDisabled: [0],
      startDate: document.getElementById('dnota').value,
    });

    function cek() {
        var dretur = $('#dretur').val();ifaktur
        var ikodemaster = $('#ikodemaster').val();
        var ifaktur = $('#ifaktur').val();
        var jml = $('#jml').val();
        var qty = []; 

        if (dretur == '' || dretur == null || ikodemaster == '' || ikodemaster == null || ifaktur == '' || ifaktur == null) {
            swal('Data Header Belum Lengkap !!');
            return false;
        }else{
            var jumlah = 0;
            for (i=1; i<=jml; i++){
                qty2 = parseInt($('#qtyretur'+i).val());
                jumlah = jumlah + qty2;
            }             
            if (jumlah == 0) {
                swal("Barang Retur Harus Di Isi");
                return false;
            } else {
                return true;
            } 
        }
    }   
</script>