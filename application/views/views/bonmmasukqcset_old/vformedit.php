<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal</label>
                        <label class="col-md-4">Referensi</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                            <input type="hidden" id="idocumentold" name="idocumentold" class="form-control" value="<?= $data->i_document;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BONM-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);"> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-3">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-5">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);"> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2" ></i>Update</button>
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            <?php } ?>
                        </div>           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php $i = 0; if ($datadetail) {?>
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
                        <th style="text-align:center;">Quantity Keluar</th>
                        <th style="text-align:center;">Quantity Sisa</th>
                        <th style="text-align:center;">Quantity Masuk</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php $z = 0; $group = ""; foreach ($datadetail as $key) { $i++; 
                        if($group!=$key->id_product_wip){
                            $z++;
                        }
                        ?>
                            <?php if($group==""){?>
                                <tr id="tr<?= $z;?>">
                                    <td colspan="3">
                                        <input readonly data-nourut="<?= $z ;?>" id="iproduct<?= $z ;?>" type="text" class="form-control input-sm" name="iproduct<?= $z ;?>" value="<?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>">
                                        <input type="hidden" name="idproduct<?=$z;?>" id="idproduct<?=$z;?>" class="form-control" value="<?= $key->id_product_wip;?>">
                                    </td>
                                    <td>
                                      <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywip<?= $z ;?>" id="nquantitywip<?= $z ;?>" value="<?= $key->n_quantity_wip_cutting;?>">
                                    </td>
                                    <td>
                                      <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipsisa<?= $z ;?>" id="nquantitywipsisa<?= $z ;?>"  value="<?= $key->n_quantity_wip_sisa;?>">
                                    </td>
                                    <td>
                                      <input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipmasuk<?= $z ;?>" id="nquantitywipmasuk<?= $z ;?>" value="<?= $key->n_quantity_wip_masuk;?>" onkeyup="angkahungkul(this);validasi(<?=$z;?>)">
                                    </td>
                                    <td></td>
                                </tr>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                    <tr id="tr<?= $z;?>">
                                        <td colspan="3">
                                            <input readonly data-nourut="<?= $z ;?>" id="iproduct<?= $z ;?>" type="text" class="form-control input-sm" name="iproduct<?= $z ;?>" value="<?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>">
                                            <input type="hidden" name="idproduct<?=$z;?>" id="idproduct<?=$z;?>" class="form-control" value="<?= $key->id_product_wip;?>">
                                        </td>
                                        <td>
                                          <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywip<?= $z ;?>" id="nquantitywip<?= $z ;?>" value="<?= $key->n_quantity_wip_cutting;?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipsisa<?= $z ;?>" id="nquantitywipsisa<?= $z ;?>" value="<?= $key->n_quantity_wip_sisa;?>">
                                        </td>
                                        <td>
                                          <input class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitywipmasuk<?= $z ;?>" id="nquantitywipmasuk<?= $z ;?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_quantity_wip_masuk;?>" onkeyup="angkahungkul(this);validasi(<?=$z;?>)">
                                        </td>
                                        <td></td>
                                    </tr>
                                <?php //$i = 1;
                            }
                            }?>
                        <?php $group = $key->id_product_wip; ?>
                        <tr class="del<?= $i;?>">
                            <td class="text-center"><?= $i ;?></td>
                            <td>
                                <input type="hidden" name="idproductwip[]" id="idproductwip<?=$i;?>" value="<?= $key->id_product_wip;?>">
                                <input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial<?=$i;?>" value="<?= $key->id_material;?>">
                                <input class="form-control input-sm" readonly type="text" value="<?= $key->i_material;?>">
                            </td>
                            <td>
                                <input class="form-control input-sm" readonly type="text" value="<?= $key->e_material_name;?>">
                            </td>
                            <td>
                                <input readonly type="text" class="form-control qty text-right input-sm inputitem" autocomplete="off" name="nquantitybahan[]" id="nquantitybahan<?=$i;?>" value="<?= $key->n_quantity_cutting;?>">
                            </td>
                            <td>
                                <input readonly type="text" class="form-control qty text-right input-sm inputitem" autocomplete="off" name="nquantitybahansisa[]" id="nquantitybahansisa<?=$i;?>" value="<?= $key->n_quantity_sisa;?>">
                            </td>
                            <td>
                                <input type="text" class="form-control qty text-right input-sm inputitem" autocomplete="off" name="nquantitybahanmasuk[]" id="nquantitybahanmasuk<?=$i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key->n_quantity_masuk;?>" onkeyup="angkahungkul(this);validasi(<?=$i;?>)">
                            </td>
                            <td colspan="2">
                                <input type="text" class="form-control input-sm" name="edesc[]" id="edesc<?=$i;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!">
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>         
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
</form>
<?php } ?>
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
        //number();
        /* max_tgl(); */

        /* $('#ipengirim').select2({
            placeholder: 'Pilih Bagian Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagianpengirim'); ?>',
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
        }); */

        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iasal : $('#ipengirim').val(),
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
                'ibagian' : $("#ibagian").val(),
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

    $("#ipengirim").change(function(){
        $('#ireff').attr("disabled", false);
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
        }
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
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

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        var ipengirim = $('#ipengirim').val();

            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                    'ipengirim' : ipengirim,
                },
                url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
                dataType: "json",
                success: function (data) {  
                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    var dref =  data['datahead']['d_document'];
                    $("#dreferensi").val(dref);
                    group = "";
                    i = 0;
                    for (let a = 0; a < data['jmlitem']; a++) {
                        var no = a+1;
                        var idproduct         = data['dataitem'][a]['id_product_wip'];
                        i++;
                        var newRow = $('<tr id="tr'+a+'">');
                        var cols   = "";
                        if(group == ""){
                            cols += '<td colspan="3"><input type="text" id="iproduct'+a+'" class="form-control" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                            cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip']+'" onkeyup="angkahungkul(this);" readonly></td>';
                            cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="angkahungkul(this);" readonly></td>';
                            cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                            cols += '<td></td>';
                        }else{
                            if(group != idproduct){
                                cols += '<td colspan="3"><input type="text" id="iproduct'+a+'" class="form-control" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                                cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip']+'" onkeyup="angkahungkul(this);" readonly></td>';
                                cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="angkahungkul(this);" readonly></td>';
                                cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                                cols += '<td></td>';
                                i = 1;
                            }
                        }
                        cols += '</tr>';
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                        group = idproduct;
                        var newRow1 = $('<tr class="del'+i+'">');
                        cols += '<td class="text-center">'+i+'</td>';
                        cols += '<td><input type="hidden" name="idproductwip[]" id="idproductwip'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'">';
                        cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial'+a+'" value="'+data['dataitem'][a]['id_material']+'">';
                        cols += '<input style="width:120px;" class="form-control input-sm" readonly type="text" value="'+data['dataitem'][a]['i_material']+'"></td>';
                        cols += '<td><input style="width:350px;" class="form-control input-sm" readonly type="text " value="'+data['dataitem'][a]['e_material_name']+'"></td>';
                        cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahan[]" id="nquantitybahan'+a+'" readonly onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity']+'" onkeyup="validasi('+a+');"></td>';
                        cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahansisa[]" id="nquantitybahansisa'+a+'" readonly onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_sisa']+'" onkeyup="validasi('+a+');"></td>';
                        cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahanmasuk[]" id="nquantitybahanmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_sisa']+'" onkeyup="validasi('+a+');"></td>';
                        cols += '<td colspan="2"><input style="width:250px;" class="form-control input-sm" type="text" name="edesc[]" id="edesc'+a+'" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                        newRow1.append(cols);
                        $('#nquantitywipmasuk'+i).focus();
                        $(newRow1).insertAfter("#tabledatax #tr"+a);
                        //a++;
                    }

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledatax").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();       
                });
                /* max_tgl(); */
            },
            error: function () {
                alert('Error :)');
            }
        });
    } 

    /* function max_tgl(val) {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
          autoclose: true,
          todayHighlight: true,
          format: "dd-mm-yyyy",
          todayBtn: "linked",
          daysOfWeekDisabled: [0],
          startDate: document.getElementById('dreferensi').value,
        });
    }

    $('#ddocument').datepicker({
      autoclose: true,
      todayHighlight: true,
      format: "dd-mm-yyyy",
      todayBtn: "linked",
      daysOfWeekDisabled: [0],
      startDate: document.getElementById('dreferensi').value,
    }); */

    function validasi(id){
        // for(i=0;i<=jml-1;i++){
            nquantityma            = $("#nquantitywipsisa"+id).val();
            nquantitymasuk         = $("#nquantitywipmasuk"+id).val();
            nquantitymaterial      = $("#nquantitybahansisa"+id).val();
            nquantitymasukmaterial = $("#nquantitybahanmasuk"+id).val();
            
            if(parseFloat(nquantitymasuk)>parseFloat(nquantityma)){
                swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
                $("#nquantitywipmasuk"+id).val(nquantityma);
            }

            if (parseFloat(nquantitymasukmaterial)>parseFloat(nquantitymaterial)){
                swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
                $("#nquantitybahanmasuk"+id).val(nquantitymaterial);
            }

            if(parseFloat(nquantitymasuk) == '0'){
                swal('Quantity Tidak Boleh 0 atau Kosong');
                $("#nquantitywipmasuk"+id).val(nquantityma);
            } 
            if(parseFloat(nquantitymasukmaterial) == '0'){
                swal('Quantity Tidak Boleh 0 atau Kosong');
                $("#nquantitybahanmasuk"+id).val(nquantitymaterial);
            }
        // }
    }

    function konfirm(){
        if($('#ibagian').val()=='' || $('#ibagian').val()==null && $('#idocument').val()=='' || $('#idocument').val()==null){
            swal ('Data Header Belum Lengkap. Silahkan lengkapi terlebih dahulu');
            return false
        }
        if($('#jml').val() == '0'){
            swal ('Data Item Masih Kosong');
            return false;
        }
    }
</script>
