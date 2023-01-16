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
                        <label class="col-md-7">Bagian</label>
                        <label class="col-md-5">Tanggal SJ</label>
                        <div class="col-sm-7">
                            <select name="ibagian" id="ibagian" class="form-control select2" onchange="getstore();">
                                <option value="" selected>-- Pilih Bagian --</option>
                                <?php foreach ($kodemaster as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?=$ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                            <input type="hidden" id="ilokasi" name="ilokasi" class="form-control" value="<?=$lokasi;?>">
                        </div>
                         <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-md-7">Nomor Memo</label>
                        <label class="col-md-5">Tanggal Memo</label>
                        <div class="col-sm-7">                            
                            <select name="i_memo" id="i_memo" class="form-control select2" onchange="getdataitemmemo(this.value);" disabled="true"> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>               
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Department/Partner</label>
                        <div class="col-sm-6">
                            <select name="edept" id="edept" class="form-control select2" onchange="getmemobaru(this.value);">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>  
                                    <th>Quantity Permintaan</th>
                                    <th>Quantity</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
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
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $("#send").attr("disabled", true);
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
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

    function getstore() {
        var gudang = $('#ibagian').val();
        //alert(gudang);
        $('#istore').val(gudang);

        if (gudang == "") {
            //$("#addrow").attr("hidden", true);
        } else {
            //$("#addrow").attr("hidden", false);
        }    
    }

    $(document).ready(function () {
        $('#edept').select2({
        placeholder: 'Pilih Department/Partner',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/getpic'); ?>',
          dataType: 'json',
          delay: 250,          
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      })
    });

    function getmemobaru(ipelanggan) {        
        $("#i_memo").attr("disabled", false);
        var i_memo = $('#i_memo').val();
            $('#i_memo').html('');
            $('#i_memo').val('');
               
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getmemobaru');?>",
            data:{
                'ipelanggan': ipelanggan,
            },
            dataType: 'json',
            success: function(data){
                $("#i_memo").html(data.kop);
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

    function getdataitemmemo(i_memo) {
        $("#jml").val(0); 
        var i_memo = $('#i_memo').val();
        var ipelanggan = $('#ipelanggan').val();
        $.ajax({
            type: "post",
            data: {
                'i_memo': i_memo,
            },
            url: '<?= base_url($folder.'/cform/getdataitemmemo'); ?>',
            dataType: "json",
            success: function (data) {  
                $('#jml').val(data['jmlitem']);
                $("#tabledata tbody").remove();
                $("#tabledata").attr("hidden", false);
                for (let a = 0; a < data['jmlitem']; a++) {
                    $('#dmemo').val(data['dataitem'][a]['d_pp']);
                    var no = a+1;
                    var iproduct            = data['dataitem'][a]['i_material'];
                    var eproduct            = data['dataitem'][a]['e_material_name'];
                    var nquantity           = data['dataitem'][a]['n_qty'];
                    var isatuan             = data['dataitem'][a]['i_satuan_code'];
                    var esatuan             = data['dataitem'][a]['e_satuan'];
                    var edesc               = data['dataitem'][a]['e_remark'];
                    var nquantitytlahretur  = data['dataitem'][a]['n_qty'];
                    var nquantitysisa       = data['dataitem'][a]['n_qty'];
                   
                    var cols        = "";
                    var newRow = $("<tr>");
                    
                    cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';     
                    cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct[]" value="'+iproduct+'"></td>';
                    cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct'+a+'" name="eproduct[]" value="'+eproduct+'"></td>'; 
                    cols += '<td><input style="width:100px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity[]" value="'+nquantity+'" readonly></td>';
                    cols += '<td><input style="width:100px;" class="form-control" type="text" id="nquantitysj'+a+'" name="nquantitysj[]" value="" onkeyup="validasi('+a+'); reformat(this);"></td>';
                    cols += '<td><input style="width:100px;" class="form-control" type="hidden" id="isatuan'+a+'" name="isatuan[]" value="'+isatuan+'"><input style="width:80px;" class="form-control" type="text" id="esatuan'+a+'" name="esatuan[]" value="'+esatuan+'" readonly></td>';
                    cols += '<td><input style="width:300px;" class="form-control" type="text" id="edesc'+a+'" name="edesc[]" value="'+edesc+'"></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);
                }
                //var a = $('#jml').val();

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledata").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();       
                });
                max_tgl();
            },
            error: function () {
                alert('Error :)');
            }
        });
        xx = $('#jml').val();
    } 

    function max_tgl() {
      $('#dsjk').datepicker('destroy');
      $('#dsjk').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dmemo').value,
      });
    }
    $('#dsjk').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: "dd-mm-yyyy",
      todayBtn: "linked",
      daysOfWeekDisabled: [0],
      startDate: document.getElementById('dmemo').value,
    });

    function validasi(id){
        //alert("tes");
        jml=document.getElementById("jml").value;
        for(i=0;i<=jml;i++){
            pp  = $('#nquantity'+i).val();
            qty = $('#nquantitysj'+i).val();
           // alert(qty);
            if(parseFloat(qty)>parseFloat(pp)){
                swal('Jumlah Quantity Melebihi Quantity Permintaan');
                document.getElementById("nquantitysj"+i).value='';
                break;
            }else if(parseFloat(qty)=='0' || parseFloat(qty)== null){
                swal('Jumlah Quantity tidak boleh kosong')
                document.getElementById("nquantitysj"+i).value='';
                break;
            }
        }
    }

    function cek() {
        var ibagian = $('#ibagian').val();
        var dsjk    = $('#dsjk').val();
        var istore  = $('#istore').val();
        var i_memo  = $('#i_memo').val();
        var dmemo   = $('#dmemo').val();
        var edept   = $('#edept').val();
        var jml = $('#jml').val();

        if (ibagian == '' || ibagian == null || dsjk == null || dsjk == '' || istore == '' || i_memo == '' || i_memo == null || dmemo == '' || edept == '' || edept == null) {
            swal('Data Header Belum Lengkap !!');
            return false;
        }else{
            for (i=0; i<=jml; i++){  
                if($("#nquantitysj"+i).val() == '' || $("#nquantitysj"+i).val() == null){
                    swal('Quantity Harus Diisi!');
                    return false;                    
                } else {
                    return true;
                } 
            }
        }
    }
</script>