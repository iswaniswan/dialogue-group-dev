<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row ">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">No Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="number();">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" value="<?=$data->id?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                            <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" class="form-control input-sm" value="<?=$data->i_retur_beli;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control date" value="<?= $data->d_retur; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">No Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);" disabled="true">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                            <input type="hidden" name="esupplier" id="esupplier" class="form-control" value="<?=$data->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="get(this.value);" disabled="true">
                                <option value="<?=$data->id_btb;?>"><?=$data->i_btb;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" value="<?=$data->d_btb;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                            <input class="form-control" type="hidden" id="vtotal" name="vtotal" value="<?=$data->v_total;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
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
                    </tr>
                </thead>
                <tbody>
                <?$i = 0;
                        foreach ($detail as $row) {
                        $i++;?>
                        <tr>
                            <td style="text-align: center;"><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                <input style ="width:150px" type="hidden" class="form-control" id="isj<?=$i;?>" name="isj<?=$i;?>"value="<?= $row->i_sj_supplier;?>" readonly >
                                <input style ="width:150px" type="hidden" class="form-control" id="iditem<?=$i;?>" name="iditem<?=$i;?>"value="<?= $row->id;?>" readonly >
                            </td>
                            <td>
                                <input style ="width:150px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                            </td>
                            <td>
                                <input style ="width:450px" type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                            </td>
                            <td>
                                <input type="text" style="width:100px" class="form-control" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan_name; ?>" readonly >
                                <input type="hidden" class="form-control" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan_code; ?>" readonly >
                            </td>
                            <td>
                                <input type="text" style="width:100px" class="form-control" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_quantity_btb; ?>" readonly >
                            </td>
                            <td>
                                <input type="text" style="width:100px" class="form-control" id="qtyretur<?=$i;?>" name="qtyretur<?=$i;?>" value="<?= $row->n_quantity_retur; ?>"  onkeyup="validasi(<?=$i?>); gettotal(this);">
                                <input type="hidden" class="form-control" id="vunitprice<?=$i;?>" name="vunitprice<?=$i;?>" value="<?= $row->v_price; ?>"readonly >
                            </td>
                            <td>
                                <input type="text" style="width:200px" class="form-control" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>">
                            </td>
                        </tr>
                        <?}?>
                        <input type="hidden" name="jml" id="jml" value="<?=$jmlitem;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>

<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('.select2').select2();
        showCalendar('.date');

        $("#ifaktur").attr("disabled", true);

        $('#iretur').mask('SSS-0000-000000S');

        $('#ikodemaster').select2({
            placeholder: 'Pilih Bagian Gudang',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/bacagudang'); ?>',
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

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
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
            //number();
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

    function gettotal(id){
        var hasiltotal = 0;
        var jml=document.getElementById("jml").value;
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
           // alert(qtyretur);
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

        if (dretur == '' || dretur == null && ikodemaster == '' || ikodemaster == null && ifaktur == '' || ifaktur == null) {
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

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", true);
        $("#cancel").attr("disabled", true);
    });
</script>