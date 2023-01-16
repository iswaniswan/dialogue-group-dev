<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Kas/Bank</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PKK-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?=date('d-m-Y');?>"readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikasbank" id="ikasbank" class="form-control select2"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-2">Total Nilai</label>   
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control select2" disabled> 
                            </select>
                        </div>   
                        <div class="col-sm-3">
                            <select name="epartnertype" id="epartnertype" class="form-control select2" onchange=" return getpartner();"> 
                                <option value="">Pilih Jenis Partner</option>
                                <option value="customer">Customer</option>
                                <option value="pic">PIC</option>
                            </select>
                        </div>   
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" multiple="multiple" onchange="return getitempartner(this.value);" disabled>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="vnilai" id="vnilai" readonly value="0">
                        </div>                     
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail" >
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Partner</th>
                        <th style="text-align:center;">Nama Partner</th>
                        <th style="text-align:center;">Nilai</th>
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
    $(document).ready(function () {
        $('.select2').select2();
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
        
        $('#idocument').mask('SSS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();

        $('#ikasbank').select2({
            placeholder: 'Pilih Kas/Bank',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kasbank'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
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
        }).change(function(event) {
            //customer();
            $("#ibank").attr("disabled", false);
            $("#ipartner").attr("disabled", false);
            $("#ipartner").val("");
            $("#ipartner").html("");
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);

        });

        $('#ibank').select2({
            placeholder: 'Pilih Bank',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bank'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikasbank : $('#ikasbank').val(),
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

    $( "#idocument" ).keyup(function() {
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
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $( "#ddocument" ).change(function() {
        number();
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    //untuk me-generate running number
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
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getpartner(){
        $("#tabledatax tbody").remove();
        var epartnertype = $('#epartnertype').val();
        $.ajax({
            type: "POST",
            data: {
                    'epartnertype': epartnertype,
            },
            url: "<?php echo site_url($folder.'/Cform/getpartner');?>",
            //data:"ikasbank="+ikasbank,
            dataType: 'json',
            success: function(data){

                $("#ipartner").html(data.kop);
                //getcustomer('ALCUS');
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ipartner").attr("disabled", false);
                    
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
    }

    function getitempartner(ipartner) {   
        var ipartner     = $('#ipartner').val();
        //alert(ipartner);
        var epartnertype = $('#epartnertype').val();
        $('#vnilai').val('0');
        if(ipartner != '' && ipartner != null){
            $.ajax({
                type: "post",
                data: {
                    'ipartner'    : ipartner,
                    'epartnertype': epartnertype,
                },
                url: '<?= base_url($folder.'/cform/getitempartner'); ?>',
                dataType: "json",
                success: function (data) {  
                   // $("#ipartner").select2("val", "ALL");
                    $('#jml').val(data['dataitem'].length);
                    $("#tabledatax tbody").remove();
                    //$("#tabledatax").attr("hidden", false);
                    for (let a = 0; a < data['dataitem'].length; a++) {
                        var no = a+1;
                        var id         = data['dataitem'][a]['id'];
                        var ipartner   = data['dataitem'][a]['i_partner'];
                        var epartner   = data['dataitem'][a]['e_partner_name'];
                        
                        var cols       = "";
                        var newRow = $("<tr>");
                        
                        cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                        cols += '<td><input readonly class="form-control" type="hidden" id="idpartner'+no+'" name="idpartner'+no+'" value="'+id+'"><input readonly style="width:150px;" class="form-control" type="text" id="ipartner'+no+'" name="ipartner'+no+'" value="'+ipartner+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="epartner'+no+'" name="epartner'+no+'" value="'+epartner+'"></td>'; 
                        cols += '<td><input style="width:200px;" class="form-control" type="text" id="v_nilai'+no+'" name="v_nilai'+no+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>'; 
                        cols += '<td><input style="width:400px;" class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
                        cols +='<td><input type="checkbox" name="cek'+no+'" value="checked" id="cek'+no+'" onchange="cek_value('+no+');"></td>';
                       
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                    }
                    $('#tabledatax').DataTable({
                        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                        "displayLength": 10,
                        "paging" : false,
                        destroy: true,
                    });                          
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
             $("#tabledatax tbody").remove();
        }
        if (ipartner != null) {
            if (ipartner.indexOf('ALL') !== -1) {
                $("#ipartner option:selected").select2().removeAttr("selected");
                $("#ipartner option[value='ALL']").select2().attr("selected","selected");
                $('#ipartner').val('ALL');
           }
       }
        
    } 

    // function cek_nilai(i){
    //     total = 0;
    //     for (var i = 1; i <= $('#jml').val(); i++) {
    //         var jumlah = formatulang($('#v_nilai'+i).val());
    //     //alert(jumlah);
    //         total += parseFloat(jumlah);
    //     }
    //     $('#vnilai').val(total);
    // }

    function cek_value(i){
        var vtotal = formatulang(document.getElementById('vnilai').value);
        if (document.getElementById('cek' + i).checked == true) {
            $('#v_nilai'+i).attr("readonly", true);
            var v_nilai = formatulang($('#v_nilai' + i).val());
//alert(v_nilai);
            totakhir = parseFloat(vtotal) + parseFloat(v_nilai);
        } else {
            $('#v_nilai'+i).attr("readonly", false);
            var v_nilai = formatulang($('#v_nilai' + i).val());
            totakhir = parseFloat(vtotal) - parseFloat(v_nilai);
        }
        document.getElementById('vnilai').value = formatcemua(totakhir);
    }

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ikasbank').val()!='' || $('#ikasbank').val()) && ($('#ipartner').val()!='' || $('#ipartner').val()) ) {
            if ($('#jml').val()==0) {
                swal('Data Item Masih Kosong!');
                return false;
            }else{
                if ($("#tabledatax input:checkbox:checked").length > 0){
                    return true;
                }else{
                    swal('Pilih data minimal satu!');
                    return false;
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });    
</script>