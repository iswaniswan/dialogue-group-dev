
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                               <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="ireturold" id="ireturold" value="<?= $data->i_retur_beli;?>">
                                <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_retur_beli;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b>* No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_retur)); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Nomor Referensi</label><!-- dari Nota Pembelia -->
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getnota(this.value);" disabled>
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ifaktur" id="ifaktur" class="form-control select2" onchange="get(this.value);">
                                <option value="<?=$data->id_btb;?>"><?=$data->i_btb;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dnota" name="dnota" class="form-control" value=<?=$data->d_btb; ?> readonly>
                        </div>
                    </div>
                    <div class="form-group">    
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id="eremark" name="eremark" class="form-control" value="" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
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
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {

                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <input type="text" value="<?= $row->i_material;?>" readonly id="imaterial<?=$i;?>" class="form-control input-sm" name="imaterial<?=$i;?>">
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->e_material_name;?>" readonly id="ematerial<?=$i;?>" class="form-control input-sm" name="ematerial<?=$i;?>">
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->i_satuan_code;?>" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>">
                                    <input type="text" value="<?= $row->e_satuan_name;?>" readonly id="esatuan<?=$i;?>" class="form-control input-sm" name="esatuan<?=$i;?>">
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity_btb;?>" id="nquantitybtb<?=$i;?>" class="form-control text-right input-sm inputitem" name="nquantitybtb<?=$i;?>" readonly>
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity_retur;?>" id="nquantity<?=$i;?>" class="form-control text-right input-sm" autocomplete="off" name="nquantity<?=$i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="validasi(<?=$i?>); gettotal(this);">
                                </td>
                                <td>
                                    <input type="text" id="edesc<?=$i;?>" class="form-control input-sm" value="<?= $row->e_remark;?>" name="edesc<?=$i;?>">
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#iretur').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
    });

    $( "#iretur" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#iretur').val()!=$('#ireturold').val())) {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'ibagian' : $('#ibagian').val(),
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

    $('#ibagian').change(function(event) {
        number();
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iretur").attr("readonly", false);
        }else{
            $("#iretur").attr("readonly", true);
            $("#iretur").val("<?= $id;?>");
            number();
        }
    });

    $('#ibagian').change(function(event) {
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

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    function gettotal(id){
        var hasiltotal = 0;
        jml = document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty     = $('#nquantity'+i).val();
            harga   = $('#vunitprice'+i).val();
            vtotal  = qty * harga;
            hasiltotal = hasiltotal + vtotal;
        }
        $('#vtotal').val(hasiltotal);
    }

    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtysj   =document.getElementById("nquantitybtb"+i).value;
            qtyretur=document.getElementById("nquantity"+i).value;
       // alert(qtyretur);
            if(parseFloat(qtyretur)>parseFloat(qtysj)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah BTB');
                document.getElementById("nquantity"+i).value=0;
                break;
            }else if(parseFloat(qtyretur)=='0'){
                swal('Jumlah Retur tidak boleh kosong')
                document.getElementById("nquantity"+i).value='';
                break;
            }
        }
    }

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Data item ada yang salah!');
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
</script>