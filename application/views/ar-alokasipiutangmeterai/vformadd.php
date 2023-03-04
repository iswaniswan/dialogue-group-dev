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
                    <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-4">Bagian Pembuat</label>
                            <label class="col-md-4">Nomor Dokumen</label>
                            <label class="col-md-4">Tanggal Dokumen</label>
                            
                            <div class="col-md-4">
                                <select name="ibagian" id="ibagian" onchange="number();" class="form-control select2">
                                    <?php foreach ($bagian as $row) { ?>
                                        <?php /** default bagian adalah AR */ ?>
                                        <?php $selected = $row->i_type == '14' ? "selected" : ''; ?>
                                        <option value="<?= $row->id; ?>" <?= $selected ?>><?= $row->e_bagian_name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="i_document" id="i_document"
                                        class="form-control input-sm" value="" readonly="" autocomplete="off">                                
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="d_document" name="d_document" class="form-control input-sm date" 
                                        required="" readonly value="<?= date("d-m-Y"); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Nomor Voucher</label>
                            <label class="col-md-3">Tanggal Voucher</label>
                            <label class="col-md-3">Bank</label>
                            <label class="col-md-3">Area</label>

                            <div class="col-md-3">
                                <input type="hidden" name="i_rv" id="i_rv" class="" value="<?= $data->i_rv ?>" readonly>
                                <input type="hidden" name="i_rv_item" id="i_rv_item" class="" value="<?= $data->i_rv_item ?>" readonly>
                                <input type="text" name="i_rv_id_id" id="i_rv_id_id"
                                    class="form-control input-sm" value="<?= $data->i_rv_id ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="d_bukti" id="d_bukti"
                                        class="form-control input-sm" value="<?= $data->d_bukti ?>" readonly="" autocomplete="off">                                
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="e_bank_name" id="e_bank_name"
                                        class="form-control input-sm" value="<?= $data->e_coa_name ?>" readonly="" autocomplete="off">                                
                            </div>
                            <div class="col-md-3">
                                <select name="id_area" id="id_area" class="form-control select2">
                                    <?php foreach ($all_area as $area)  { ?>
                                        <option value="<?= $area->id; ?>"><?="[" . $area->i_area . "] - " . $area->e_area; ?>
                                        </option>
                                    <?php } ?>
                                </select>                           
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group row">                            
                            <label class="col-md-6">Customer</label>
                            <label class="col-md-6">Jumlah</label>                            
                            <div class="col-md-6">
                                <select name="id_customer" id="id_customer" class="form-control select2"></select>                                
                            </div>
                            <div class="col-md-6">
                            <input type="hidden" name="jml" id="jml" value="0">
                                <input type="hidden" id="vlebih" name="v_lebih" value="0">
                                <input type="hidden" id="vsisa" name="vsisa" value="<?= $data->v_rv_saldo; ?>">
                                <input type="text" name="v_jumlah" id="vjumlah"
                                        class="form-control input-sm" value="Rp. <?= number_format($data->v_rv_saldo, 0, ".", ",") ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <button type="button" id="submit" class="btn btn-success btn-block btn-sm">
                                    <i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan
                                </button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder;?>/cform/indexx/<?= $dfrom."/".$dto;?>','#main')">
                                    <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                                </button>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="addrow" class="btn btn-info btn-block btn-sm">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Item
                                </button>&nbsp;
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="send" hidden="true" class="btn btn-primary btn-block btn-sm">
                                    <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="jml" id="jml" value ="0">
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
                            <th class="text-center" width="12%;">Sisa</th>
                            <th class="text-center" width="12%;">Lebih</th>
                            <th class="text-center" width="17%;">Keterangan</th>
                            <th class="text-center" width="3%">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <?php /*
                    <tfoot>
                        <tr>
                            <td class="text-right" colspan="6">Jumlah Referensi :</td>
                            <td>
                                <input type="text" id="vjumlah" name="vjumlah" class="form-control input-sm text-right" value="<?= number_format(@$data->v_sisa);?>" readonly>
                                <input type="text" id="vjumlahsisa" name="vjumlahsisa" class="form-control input-sm text-right" value="<?= number_format(@$data->v_sisa);?>" readonly>
                                <input type="text" id="vjumlahlebih" name="vjumlahlebih" class="form-control input-sm text-right" value="0" readonly>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    */?>
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        /*----------  Load Form Validation  ----------*/        
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        // $('#ialokasi').mask('SSS-0000-000000S');        
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);
        number();    
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/    
    // function number() {
    //     $.ajax({
    //         type: "post",
    //         data: {
    //             'tgl' : $('#ddocument').val(),
    //             'ibagian' : $('#ibagian').val(),
    //         },
    //         url: '<?= base_url($folder.'/cform/number'); ?>',
    //         dataType: "json",
    //         success: function (data) {
    //             $('#ialokasi').val(data);
    //         },
    //         error: function () {
    //             swal('Error :)');
    //         }
    //     });
    // }   
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#d_document').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder . '/cform/generate_nomor_dokumen'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_document').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    /** dropdown customer */
    $('#id_customer').select2({
        placeholder: 'Cari Customer',
        allowClear: true,
        width: "100%",
        type: "POST",
        ajax: {
            url: '<?= base_url($folder.'/cform/get_all_customer/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query   = {
                    q          : params.term,
                    id_area : $('#id_area').val(),
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
    }).change(function() {
        $('.ibtnDel').each(function() {
            $(this).trigger('click');
        })
    });

    /** dropdown area */
    $('#id_area').change(function() {
        $('#id_customer').val('').trigger('change');
    })

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
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

    /*----------  TAMBAH NOTA  ----------*/
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatay tbody tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no+1}</spanx></td>`;
        cols += `<td>
                    <select data-nourut="${i}" id="idnota${i}" class="form-control input-sm" name="items[${i}][id_nota]" onchange="get_detail_nota(${i});"></select>
                </td>`;
        cols += `<td>
                    <input type="text" readonly class="form-control input-sm" name="items[${i}][dnota]" id="dnota${i}"/>
                </td>`;
        cols += `<td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                        </div>
                        <input type="text" readonly class="form-control input-sm text-left input-nilai" name="items[${i}][vnilai]" id="vnota${i}" value="0"/>
                    </div>                    
                </td>`;
        cols += `<td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                        </div>
                        <input autocomplete="off" class="form-control form-control-sm text-right" type="text" id="vjumlah${i}" name="items[${i}][vjumlah]" value="0" 
                                onkeydown="reformat(this);hetang();" 
                                onkeyup="onlyangka(this); reformat(this); 
                                hetang();" 
                                onpaste="return false;" 
                                onblur=\"if(this.value==''){this.value='0';hetang();}\" 
                                onfocus=\"if(this.value=='0'){this.value='';}\">
                    </div>
                </td>`;
        cols += `<td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                        </div>
                    <input type="hidden" id="vsisa${i}" name="vsisa${i}" value="0">
                    <input type="text" readonly class="form-control input-sm text-left input-sisa" name="items[${i}][vsesa]" id="vsesa${i}" value="0"/>
                    </div>
                </td>`;
        cols += `<td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="padding: 0px 5px">Rp.</span>
                        </div>
                    <input type="text" readonly class="form-control input-sm text-left input-lebih" name="items[${i}][vlebih]" id="vlebih${i}" value="0"/>
                    </div>
                </td>`;
        cols += `<td>
                    <input type="hidden" class="form-control input-sm" name="items[${i}][groupfaktur]" id="groupfaktur${i}" value="" required/>
                    <select class="form-control input-sm" name="items[${i}][eremark]" id="eremark${i}"></select>
                </td>`;
        cols += `<td class="text-center">
                    <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                </td>`;

        newRow.append(cols);

        $("#tabledatay").append(newRow);
        $('#idnota'+ i).select2({
            placeholder: 'Cari Nota',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/get_nota/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q : params.term,
                        id_customer : $('#id_customer').val(),
                        id_area: $('#id_area').val()
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
        });

        for (const opsi of OPSI_KETERANGAN) {
            let option = `<option value="${opsi.id}">${opsi.text}</option>`;
            $('#eremark'+i).append(option);
        }

        $('#eremark'+i).select2({
            placeholder: "Pilih Keterangan",
            allowClear: true,
            width: "100%",
        }).val("").trigger("change");

    });    
    
    /*----------  HAPUS TR  ----------*/    
    $("#tabledatay").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        obj = $('#tabledatay tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });        
        hetang();
    })

    /*----------  GET DETAIL  ----------*/    
    function get_detail_nota(id){
        /** validasi nota sudah dipilih */
        const selectedValue = $('#idnota'+id).val();
        for (let i = 1; i <= $("#jml").val(); i++) {
            if (i==id) {
                continue;
            }
            const selectValue = $('#idnota'+i).val();
            if (selectValue != null && selectValue !== undefined && selectValue == selectedValue) {
                swal('Nota sudah ada');
                setTimeout(function() {
                    $('#idnota'+id).closest('tr').find('.ibtnDel').trigger('click');
                }, 1500)                
                return;
            }            
        }        

        $.ajax({
            type: "post",
            data: {
                'id_nota'    : $('#idnota'+id).val(),
                'id_customer': $('#id_customer').val(),
            },
            url: '<?= base_url($folder.'/cform/get_detail_nota'); ?>',
            dataType: "json",
            success: function (data) {                
                if(typeof data[0] != 'undefined'){
                    $("#d_nota" + id).val(data[0]['d_nota']);
                    $("#dnota" + id).val(data[0]['dnota']);
                    $("#vsesa" + id).val(formatcemua(data[0]['v_meterai_sisa']));
                    $("#vsisa" + id).val(formatcemua(data[0]['v_meterai_sisa']));
                    $("#vnota" + id).val(formatcemua(data[0]['v_meterai_sisa']));
                    tmp = formatulang($("#vjumlah").val());
                    /** replace Rp */
                    tmp = tmp.replaceAll("Rp. ", "");
                    
                    jml = $("#jml").val();

                    if (tmp > 0) {
                        tmp = parseFloat(tmp);
                        sisa = 0;
                        jumasal = tmp;
                        jumall = jumasal;
                        bay = 0;

                        for (x = 1; x <= jml; x++) {
                            if (typeof $("#vjumlah" + x).val() !== 'undefined') {
                                if ($("#vjumlah" + x).val() == '') {
                                    jum = parseFloat(formatulang($("#vsisa" + x).val()));
                                } else {
                                    jum = parseFloat(formatulang($("#vjumlah" + x).val()));
                                }
                                jumall = jumall - jum;
                                // console.log(jumall);
                                if (jumall > 0) {
                                    $("#vlebih").val(formatcemua(jumall));
                                    if (x == id) {
                                        $("#vjumlah" + id).val(formatcemua(data[0]['v_meterai_sisa']));
                                        by = parseFloat(formatulang($("#vjumlah" + id).val()));
                                        bay = jumasal - by;
                                        sis = parseFloat(formatulang($("#vsisa" + id).val()));;
                                        $("#vlebih" + id).val(formatcemua(bay));
                                    }
                                    sisa = sisa + jum;
                                } else {
                                    $("#vlebih").val('0');
                                    $("#vlebih" + id).val('0');
                                    $("#vjumlah" + id).val(formatcemua(jumasal - sisa));
                                    $("#vlebih" + id).val('0');
                                }
                            }
                        }
                    }
                    hetang();                  
                }else{
                    swal('Data tidak ada!');
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }

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
                    const nota = $(this).find('select');
                    if (nota.val()=='' || nota.val()==null) {
                        swal('Maaf :(','No. Nota tidak boleh kosong!','error');
                        ada = true;
                    }
                    const bayar = $(this).find(".input-nilai");
                    if (bayar.val()=='' || bayar.val()==null || bayar.val()==0) {
                        swal('Maaf :(','Jumlah Bayar Tidak Boleh Kosong Atau 0!','error');
                        ada = true;
                    }
                });
                if (!ada) {
                    swal({   
                        title: "Simpan Data Ini?",   
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Simpan!",   
                        closeOnConfirm: false 
                    }, function(){
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder.'/cform/simpan/'); ?>',
                            dataType: "json",
                            success: function (data) {
                                if (data.sukses==true) {
                                    $('#id').val(data.id);
                                    swal("Sukses!", "No Dokumen : "+data.kode+", Berhasil Disimpan :)", "success"); 
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    // $("#send").attr("hidden", false);
                                }else if (data.sukses=='ada') {
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Sudah Ada :(", "error");   
                                }else{
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Disimpan :(", "error"); 
                                }
                            },
                            error: function () {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
                }else{
                    return false;
                }
            }
        }
        return false;     
    });

    function calculateBayar(eBayar, eNilai, eSisa, eLebih) {
        let vJumlah = $('#v_jumlah').val().replaceAll("Rp.", "");
        vJumlah = currencyTextToNumber(vJumlah);

        let totalBayar = 0;
        $('.input-bayar').each(function() {
            let _bayar = $(this).val();
            _bayar = currencyTextToNumber(_bayar);
            totalBayar += _bayar;
        });
        
        let vAvailable = vJumlah - totalBayar;
        $('#v_sisa').val(vAvailable);

        let vNilai = $(eNilai).val();
        vNilai = currencyTextToNumber(vNilai);

        let vBayar = $(eBayar).val();
        vBayar = currencyTextToNumber(vBayar);        

        let vSisa = vNilai - vBayar;
        let vLebih = vAvailable;
        
        if (vBayar > vNilai) {
            vSisa = 0;
        }

        $(eBayar).val(formatRupiah(vBayar.toString()));
        $(eSisa).val(formatRupiah(vSisa.toString()));
        $(eLebih).val(formatRupiah(vLebih.toString()));        
    }

    const OPSI_KETERANGAN = [
        {id: "Retur", text: "Retur"},
        {id: "Biaya Promo", text: "Biaya Promo"},
        {id: "Kurang Bayar", text: "Kurang Bayar"},
        // {id: "Cicil", text: "Cicil"},
        // {id: "Pembulatan", text: "Pembulatan"},
        // {id: "Lebih Bayar", text: "Lebih Bayar"},
        {id: "Biaya Ekspedisi", text: "Biaya Ekspedisi"},
        {id: "Biaya Administrasi", text: "Biaya Administrasi"},
    ];

    function hetang() {
        const eJumlah = $("#vjumlah");
        let vJumlah = eJumlah.val().toString();
        vJumlah = vJumlah.replaceAll("Rp. ", "");
        var vjmlbyr = parseFloat(formatulang(vJumlah));
        
        var vlebihitem = vjmlbyr;
        for (a = 1; a <= $('#jml').val(); a++) {
            if (typeof $("#vjumlah" + a).val() !== 'undefined') {
                vnota = parseFloat(formatulang($("#vsisa" + a).val()));
                vjmlitem = parseFloat(formatulang($("#vjumlah" + a).val()));
                /* if (vjmlitem == 0) {
                    bbotol();
                } */
                vsisaitem = vnota - vjmlitem;
                if (vsisaitem < 0) {
                    swal('"Jumlah bayar tidak bisa lebih besar dari nilai nota !!!!!"');                    
                    $("#vjumlah" + a).val(0);
                    vjmlitem = parseFloat(formatulang($("#vjumlah" + a).val()));
                    vsisaitem = parseFloat(formatulang($("#vsisa" + a).val()));
                }
                vlebihitem = vlebihitem - vjmlitem;
                if (vlebihitem < 0) {
                    vlebihitem = vlebihitem + vjmlitem;
                    vsisaitem = vnota - vlebihitem;
                    swal("Jumlah item tidak bisa lebih besar dari nilai bayar !!!!!");
                    $("#vjumlah" + a).val(formatcemua(vlebihitem));
                    vjmlitem = parseFloat(formatulang($("#vjumlah" + a).val()));
                    vlebihitem = 0;
                }
                $("#vsesa" + a).val(formatcemua(vsisaitem));
                $("#vlebih" + a).val(formatcemua(vlebihitem));
            }
        }
        $("#vlebih").val(formatcemua(vlebihitem));
    }

    function onlyangka(x) {
        x.value = x.value.replace(/[^\d.-]/g, '');
    }

    function reformat(input) {
        /* var num = input.value.replace(/\,/g, ""); */
        var num = input.value.replace(/[^\d.-]/g, '');
        if (!isNaN(num)) {
            if (num.indexOf(".") > -1) {
                num = num.split(".");
                num[0] = num[0]
                    .toString()
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/^[\,]/, "");
                if (num[1].length > 2) {
                    alert("maksimum 2 desimal !!!");
                    num[1] = num[1].substring(0, num[1].length - 1);
                }
                input.value = num[0] + "." + num[1];
            } else {
                input.value = num
                    .toString()
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/^[\,]/, "");
            }
        } else {
            alert("input harus numerik !!!");
            input.value = input.value.substring(0, input.value.length - 1);
        }
    }

    function formatulang(a) {
        var s = a.replace(/\,/g, "");
        return s;
    }

    function formatcemua(input) {
        var num = input.toString();
        if (!isNaN(num)) {
            if (num.indexOf(".") > -1) {
                num = num.split(".");
                num[0] = num[0]
                    .toString()
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/^[\,]/, "");
                if (num[1].length > 2) {
                    while (num[1].length > 2) {
                        num[1] = num[1].substring(0, num[1].length - 1);
                    }
                }
                input = num[0];
            } else {
                input = num
                    .toString()
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/(?=\d*\.?)(\d{3})/g, "$1,")
                    .split("")
                    .reverse()
                    .join("")
                    .replace(/^[\,]/, "");
            }
        }
        return input;
    }

    
    
</script>