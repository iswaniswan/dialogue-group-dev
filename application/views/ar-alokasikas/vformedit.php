<style type="text/css">
    .select2-results__options{
        font-size:14px !important;
    }
    .select2-selection__rendered {
      font-size: 12px;
  }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Area</label>
                            <input type="hidden" name="id" id="id" value="<?= $data->i_alokasi ?>"/>
                            <div class="col-md-3">
                                <select class="form-control select2 input-sm" disabled>
                                    <option value="" selected><?= $data->e_bagian_name ?></option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm" name="i_document" value="<?= $data->i_alokasi_id ?>" readonly="" autocomplete="off">                                
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm date" name="d_alokasi" value="<?= date('d-m-Y', strtotime($data->d_alokasi)) ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="id_area" id="id_area" class="form-control select2 input-sm" disabled>
                                    <option value=""><?= $data->e_area ?></option>
                                </select>                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-4">Nomor Voucher</label>
                            <label class="col-md-4">Tanggal Voucher</label>
                            <label class="col-md-4">Bank</label>                            

                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" value="<?= $data->i_rv_id ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" value="<?= $data->d_rv ?>" readonly="">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" value="<?= $data->e_bank_name ?>" readonly="">
                            </div>                            
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-6">Customer</label>
                            <label class="col-md-6">Jumlah Bayar</label>                            

                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" value="<?= $data->e_customer_name ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" value="Rp. <?= number_format($data->v_jumlah, 0, ",", ".") ?>" readonly="">                                
                            </div>                           
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <div class="col">
                                    <button type="submit" id="submit" class="btn btn-success btn-block btn-sm">
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;Update
                                    </button>&nbsp;
                                </div>
                                <?php /*
                                <div class="col">
                                    <button type="button" id="addrow" class="btn btn-info btn-block btn-sm">
                                        <iclass="fa fa-plus"></i>&nbsp;&nbsp;Item
                                    </button>&nbsp;
                                </div>
                                */ ?>
                            <?php } ?>
                            
                            <?php if ($data->i_status == '1') {?>
                                <div class="col">
                                    <button type="button" id="send" class="btn btn-primary btn-block btn-sm">
                                        <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send
                                    </button>&nbsp;
                                </div>
                                <div class="col">
                                    <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm">
                                        <i class="fa fa-trash"></i>&nbsp;&nbsp;Delete
                                    </button>&nbsp;
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;">
                                        <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                                    </button>
                                </div>
                            <?php }elseif($data->i_status=='2') {?>
                                <div class="col-sm-6">
                                    <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm">
                                        <i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel
                                    </button>&nbsp;
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-inverse btn-block btn-sm" 
                                            onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;">
                                        <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                                    </button>&nbsp;
                                </div>                              
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $i = 0; if ($datadetail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Alokasi</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="15%;">No. Nota</th>
                            <th class="text-center" width="11%;">Tgl. Nota</th>
                            <th class="text-center" width="12%;">Nilai</th>
                            <th class="text-center" width="12%;">Bayar</th>
                            <!-- <th class="text-center" width="12%;">Sisa</th>
                            <th class="text-center" width="12%;">Lebih</th> -->
                            <th class="text-center" width="17%;">Keterangan</th>
                            <!-- <th class="text-center" width="3%">Act</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datadetail as $item) { $i++; ?>
                            <tr id="tr<?= $i; ?>">
                                <input type="hidden" name="items[<?= $i ?>][id]" value="<?= $item->i_alokasi_item ?>"/>
                                <td class="text-center">
                                    <spanx id="snum<?= $i; ?>"><?= $i; ?></spanx>
                                </td>
                                <td><?= $item->i_document ?></td>
                                <td><?= $item->d_document ?></td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_nilai, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_jumlah, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                <?php /*
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_sisa, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                        </div>
                                        <span><?= number_format($item->v_lebih, 0, ",", ".") ?></span>
                                    </div>
                                </td>
                                */ ?>
                                <td>
                                    <select class="form-control input-sm input-select2-eremark" name="items[<?= $i ?>][eremark]" id="eremark<?= $i ?>">
                                        <option value="<?= $item->e_remark ?>"><?= $item->e_remark ?></option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <?php /*
                    <tbody>
                    <?php foreach ($datadetail as $item) { $i++; ?>
                        <tr id="tr<?= $i; ?>">
                            <td class="text-center"><spanx id="snum<?= $i ?>"><?= $i ?></spanx></td>
                            <td>
                                <select data-nourut="<?= $i ?>" id="idnota<?= $i ?>" class="form-control input-sm" name="items[<?= $i ?>][id_nota]" onchange="get_detail_nota(<?= $i ?>);">
                                    <option value="<?= $item->id_nota ?>" selected><?= $item->i_document ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="text" readonly class="form-control input-sm" name="items[<?= $i ?>][dnota]" id="dnota<?= $i ?>" value="<?= $item->d_document ?>"/>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                    </div>
                                    <input type="text" readonly class="form-control input-sm text-left" name="items[<?= $i ?>][vnilai]" id="vnilai<?= $i ?>" value="<?= $item->v_sisa ?>"/>
                                </div>                    
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                    </div>
                                    <input type="text" id="vbayar<?= $i ?>" class="form-control text-left input-sm inputitem"
                                        autocomplete="off" name="items[<?= $i ?>][vbayar]" value="<?= $item->v_jumlah ?>">
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                    </div>
                                    <input type="text" readonly class="form-control input-sm text-left" 
                                        name="items[<?= $i ?>][vsisa]" id="vsisa<?= $i ?>" value="<?= $item->v_sisa ?>"/>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                                    </div>
                                    <input type="text" readonly class="form-control input-sm text-left" 
                                        name="items[<?= $i ?>][vlebih]" id="vlebih<?= $i ?>" value="<?= $item->v_lebih ?>"/>
                                </div>
                            </td>
                            <td>
                                <input type="hidden" class="form-control input-sm" name="items[<?= $i ?>][groupfaktur]" id="groupfaktur<?= $i ?>" value=""/>
                                <select class="form-control input-sm" name="items[<?= $i ?>][eremark]" id="eremark<?= $i ?>">
                                    <option value="<?= $item->e_remark ?>"><?= $item->e_remark ?></option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    */ ?>

                    <?php /*
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="6">Jumlah Referensi :</td>
                            <td>
                                <input type="text" id="vjumlah" name="vjumlah" class="form-control input-sm text-right" value="<?= number_format($data->v_jumlah);?>" readonly>
                                <input type="hidden" id="vjumlahsisa" name="vjumlahsisa" class="form-control input-sm text-right" value="" readonly>
                                <input type="hidden" id="vjumlahlebih" name="vjumlahlebih" class="form-control input-sm text-right" value="<?= number_format($data->v_jumlah);?>" readonly>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    */ ?>
                </table>
            </div>
        </div>
    </div>
    <?php }else{ ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
<input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        /*----------  Load Form Validation  ----------*/        
        $('#cekinputan').validate({
            /*rules : {
                ddocument : {
                    australianDate : true
                }
            },*/
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('#ialokasi').mask('SSS-0000-000000S');        
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);
        hitung();

        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idnota'+ i).select2({
                placeholder: 'Cari Nota',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder.'/cform/referensi/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query   = {
                            q          : params.term,
                            idcustomer : $('#idcustomer').val(),
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
            })
        }
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/    
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#ialokasi').val($('#ialokasiold').val());
        }else{
            $.ajax({
                type: "post",
                data: {
                    'tgl' : $('#ddocument').val(),
                    'ibagian' : $('#ibagian').val(),
                },
                url: '<?= base_url($folder.'/cform/number'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#ialokasi').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }
    }   

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ialokasi").attr("readonly", false);
        }else{
            $("#ialokasi").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#ialokasi" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#ialokasi').val() != $('#ialokasiold').val())) {
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

    /*----------  TAMBAH NOTA  ----------*/
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatay tbody tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no+1}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idnota${i}" class="form-control input-sm" name="idnota${i}" onchange="getdetail(${i});"></select></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm" name="dnota${i}" id="dnota${i}"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vnilai${i}" id="vnilai${i}" value="0"/></td>`;
        cols += `<td><input type="text" id="vbayar${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="vbayar${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); reformat(this); hitung();"></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vsisa${i}" id="vsisa${i}" value="0"/></td>`;
        cols += `<td><input type="text" readonly class="form-control input-sm text-right" name="vlebih${i}" id="vlebih${i}" value="0"/></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Jika Ada!"/><input type="text" class="form-control input-sm" name="groupfaktur${i}" id="groupfaktur${i}" value=""/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatay").append(newRow);
        $('#idnota'+ i).select2({
            placeholder: 'Cari Nota',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        idcustomer : $('#idcustomer').val(),
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
        })
    });    
    
    /*----------  HAPUS TR  ----------*/    
    $("#tabledatay").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        obj = $('#tabledatay tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });        
        hitung();
    })

    /*----------  GET DETAIL  ----------*/    
    function getdetail(id){
        $.ajax({
            type: "post",
            data: {
                'idnota'    : $('#idnota'+id).val(),
                'idcustomer': $('#idcustomer').val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
            dataType: "json",
            success: function (data) {
                if(typeof data[0] != 'undefined'){
                    ada = false;
                    for(var i = 1; i <= $('#jml').val(); i++){
                        if(($('#idnota'+id).val() == $('#idnota'+i).val()) && (i!=id)){
                            swal ("Maaf :(","No. Nota : "+data[0].i_document+" sudah ada !!!!!","error");
                            $("#tabledatay tbody tr td #idnota"+id).each(function() {
                                $(this).closest("tr").remove();
                            });
                            ada = true;
                            break;
                        }else{
                            ada = false;     
                        }
                    }
                    if(!ada){
                        $('#dnota'+id).val(data[0].d_document);
                        $('#groupfaktur'+id).val(data[0].groupfaktur);
                        $('#vnilai'+id).val(formatcemua(data[0].v_sisa));
                        if (parseInt(formatulang($('#vjumlahsisa').val())) > parseInt(formatulang(data[0].v_sisa))) {
                            $('#vbayar'+id).val(formatcemua(data[0].v_sisa));
                        }else if ((parseInt(formatulang($('#vjumlahsisa').val())) < parseInt(formatulang(data[0].v_sisa))) && (parseInt(formatulang($('#vjumlahsisa').val())) > 0)) {
                            $('#vbayar'+id).val(formatcemua($('#vjumlahsisa').val()));
                        }else{
                            $('#vbayar'+id).val(0);
                        }
                        $('#vbayar'+id).focus();
                        hitung();
                    }else{
                        $('#idnota'+id).html('');
                        $('#idnota'+id).val('');
                    }
                }else{
                    swal('Data tidak ada!');
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }

    /*----------  HITUNG NILAI  ----------*/
    function hitung(){  
        return;      
        var vjmlbyr     = parseFloat(formatulang($('#vjumlah').val()));
        var vlebihitem  = vjmlbyr;
        var vjmlsisa    = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if(typeof $('#idnota'+i).val() != 'undefined'){
                vnota = parseFloat(formatulang($('#vnilai'+i).val()));
                if (!isNaN(parseFloat($('#vbayar'+i).val()))){
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                }else{
                    vjmlitem = 0;
                }
                vsisaitem = vnota - vjmlitem;
                if (vsisaitem < 0) {
                    swal("Maaf :(","Jumlah bayar tidak bisa lebih besar dari nilai nota !!!!!","error");
                    $('#vbayar'+i).val(0);
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                    vsisaitem = parseFloat(formatulang($('#vnilai'+i).val()));
                }
                vlebihitem = vlebihitem - vjmlitem;
                if (vlebihitem < 0) {
                    vlebihitem = vlebihitem + vjmlitem;
                    vsisaitem = vnota - vlebihitem;
                    swal("Maaf :(","Jumlah item tidak bisa lebih besar dari nilai bayar !!!!!","error");
                    $('#vbayar'+i).val(formatcemua(vlebihitem));
                    vjmlitem = parseFloat(formatulang($('#vbayar'+i).val()));
                    vlebihitem = 0;
                }
                vjmlsisa += vjmlitem; 
                $('#vsisa'+i).val(formatcemua(vsisaitem));
                $('#vlebih'+i).val(formatcemua(vlebihitem));
            }
        }
        $("#vjumlahsisa").val(formatcemua(vjmlbyr-vjmlsisa));
        $("#vjumlahlebih").val(formatcemua(vlebihitem));
    }
    /*----------  END HITUNG NILAI  ----------*/    

    /*----------  VALIDASI UPDATE DATA  ----------*/    
    $( "#submit" ).click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                $("#tabledatay tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val()=='' || $(this).val()==null) {
                            swal('Maaf :(','No. Nota tidak boleh kosong!','error');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                            swal('Maaf :(','Jumlah Bayar Tidak Boleh Kosong Atau 0!','error');
                            ada = true;
                        }
                    });
                });
                if (!ada) {
                    swal({   
                        title: "Update Data Ini?",   
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Update!",   
                        closeOnConfirm: false 
                    }, function(){
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder.'/cform/update/'); ?>',
                            dataType: "json",
                            success: function (data) {
                                if (data.sukses==true) {
                                    swal("Sukses!", "No Dokumen : "+data.kode+", Berhasil Diupdate :)", "success"); 
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                }else if (data.sukses=='ada') {
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Sudah Ada :(", "error");   
                                }else{
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Diupdate :(", "error"); 
                                }
                            },
                            error: function () {
                                swal("Maaf", "Data Gagal Diupdate :(", "error");
                            }
                        });
                    });
                }else{
                    return false;
                }
            }
        }
        return false;     
    })

    const OPSI_KETERANGAN = [
        {id: 'Retur', text: 'Retur'},
        {id: 'Biaya Promo', text: 'Biaya Promo'},
        {id: 'Kurang Bayar', text: 'Kurang Bayar'},
        {id: 'Cicil', text: 'Cicil'},
        {id: 'Pembulatan', text: 'Pembulatan'},
        {id: 'Lebih Bayar', text: 'Lebih Bayar'},
        {id: 'Biaya Ekspedisi', text: 'Biaya Ekspedisi'},
        {id: 'Biaya Administrasi', text: 'Biaya Administrasi'},
    ];

    $(document).ready(function() {

        $('.input-select2-eremark').each(function() {            

            const eValue = $(this).val();

            $(this).select2().empty().trigger('change');

            for (const opsi of OPSI_KETERANGAN) {
                let option = `<option value="${opsi.id}">${opsi.text}</option>`;
                $(this).append(option);
            }

            $(this).select2({
                placeholder: "Pilih Keterangan",
                allowClear: true,
                width: "100%",
            }).val(eValue).trigger("change");
        });
        
    });

</script>