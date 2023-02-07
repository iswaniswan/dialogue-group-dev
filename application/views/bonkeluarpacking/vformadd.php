<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
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
                                <input type="text" name="ibonk" id="dokumenbon" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y");?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">                                                      
                                <?php $group = ""; foreach ($tujuan as $row) { ?>
                                    <?php if ($group != $row->name) { ?>
                                        </optgroup>
                                        <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                    <?php } ?>
                                    <?php $grup = $row->name; 
                                        /** default company select */
                                        $selected = ($row->id_company == $this->session->userdata('id_company')) ? 'selected' : '' ?>
                                        <option value="<?= $row->id_bagian ?>" <?= $selected ?>><?= $row->e_bagian_name; ?>
                                        </option>
                                <?php } ?>
                                
                            </select>
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang Keluar</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                        <div class="col-sm-9">
                            <textarea id= "eremark" placeholder="Isi Keterangan Jika Ada!!!" name="eremark" class="form-control"></textarea>
                        </div>
                    </div>                   
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2" ></i>Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>              
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="row">
        <div class="col-sm-11">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-1" style="text-align: right;">
            <?= $doc; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 15%;">Kode Barang</th>
                            <th class="text-center" style="width: 27%;">Nama Barang Jadi</th>
                            <th class="text-center" style="width: 15%;">Warna</th>
                            <th class="text-center" style="width: 10%;">Stock</th>
                            <th class="text-center" style="width: 10%;">Quantity</th>
                            <th class="text-center" style="width: 30%;">Keterangan</th>
                            <th class="text-center" style="width: 5%;">Action</th>
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
<input type="hidden" name="jml_item" id="jml_item" value="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        // $('#dokumenbon').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date', 1830, 0);
        number();
    });

    $('#ibagian, #dbonk').change(function(event) {
        number();
    });

    $( "#dokumenbon" ).keyup(function() {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dbonk').val(),
                'ibagian' : $('#ibagian').val(),
                'itujuan' : $('#itujuan').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#dokumenbon').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });

        clearDetailBarang();
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#dokumenbon").attr("readonly", false);
        }else{
            $("#dokumenbon").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
    
    var counter = 0;

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr').length;  
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }

        let valid = isDataValid();
        if (!valid) {
            return false;
        }

        $('#jml').val(counter);
        var newRow = $("<tr class='no tr" + counter + "'>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct'+ counter + '" class="form-control" name="idproduct[]"><input type="text" readonly id="iproduct'+ counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" data-placeholder="Pilih Barang" id="eproduct'+ counter + '" class="form-control select2" name="eproduct'+ counter + '" onchange="getproduct('+ counter + '); getstok('+ counter +'); "><option value=""></option></select></td>';
        cols += '<td><input type="hidden" id="idcolorproduct'+ counter + '" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct'+ counter + '" class="form-control input-sm" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input type="text" readonly class="form-control input-sm text-right" id="stok'+ counter +'" name="stok'+ counter +'"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control input-sm text-right inputitem" name="nquantity[]" value="0" onkeyup="hetang('+counter+')"></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control input-sm" placeholder="Keterangan..." name="edesc[]"></td>';
        cols += '<td class="text-center"><button data-urut="' + counter + '" type="button" onclick="tambah_material(' + counter + ');" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i data-urut="' + counter + '" id="addlist' + counter + '"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i></button><button type="button" data-i = "' + counter + '" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax tr:first").after(newRow);
        var newRow1 = $(
            `<tr class="table-active tr_second${counter}">
                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                <td colspan="7"><b>Bundling Produk</b></td>
            </tr>`);
        $(newRow1).insertAfter(`#tabledatax .tr${counter}`);
        // $("#tabledatax").append(newRow);
        restart();

        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            width:"100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/dataproduct?itujuan='); ?>' + getItujuan(),
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

    function formatSelection(val) {
        return val.name;
    }

    function tambah_material(i) {
        var ii = parseInt($('#jml_item').val()) + 1;
        var col = "";
        $('#jml_item').val(ii);
        var newRow = $("<tr class='no tr_bundling" + i + "'>");
        col += `
        <td class="text-center"><i class="fa fa-check-circle-o fa-lg text-success" aria-hidden="true"></i></td>
        <td colspan="3">
            <select type="text" data-placeholder="Pilih Barang" 
                id="eproduct_bundle${i}${ii}" class="form-control" name="eproduct_bundle${i}${ii}"
                onchange="getStockBundle(this, n_stok_bundle_${i}_${ii})">
                <option value=""></option>
            </select>
        </td>
        <td><input type="text" id="n_stok_bundle_${i}_${ii}" class="form-control text-right input-sm" name="n_stok_bundle${i}${ii}" value="0" readonly></td>
        <td><input type="text" id="n_qty_bundle_${i}_${ii}" class="form-control text-right input-sm" name="n_qty_bundle${i}${ii}" value="0" 
                onblur=\'if(this.value==""){this.value="0";}\' 
                onfocus=\'if(this.value=="0"){this.value="";}\' 
                onkeyup="validasiStockBundle(this, n_stok_bundle_${i}_${ii});">
        </td>
        <td></td>
        <td class="text-center"><button type="button" title="Delete" data-b = "${i}" class="ibtnDelBundling btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
        `;
        newRow.append(col);
        $(newRow).insertAfter("#tabledatax .tr_second" + i);

        $(`#eproduct_bundle${i}${ii}`).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            width: "100%",
            ajax: {
                url: '<?= base_url($folder . '/cform/dataproduct?itujuan='); ?>' + getItujuan(),
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        // $(`<tr class="table-active tr_second${i}">
        //         <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
        //         <td colspan="6"><b>Bundling Produk</b></td>
        //         <td class="text-center"><button type="button" data-i = "${i}" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
        //     </tr>
        //     `).insertAfter("#tabledatax .tr" + i);
    }

    function restart() {
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function(key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function getproduct(id){
        ada=false;
        // var a = $('#eproduct'+id).val();
        // var x = $('#jml').val();
        // for(i=1;i<=x;i++){
        //     if((a == $('#eproduct'+i).val()) && (i!=x)){
        //         swal ("Kode Barang sudah ada !!!!!");            
        //         ada=true;            
        //         break;        
        //     }else{            
        //         ada=false;             
        //     }
        // }

        const ids = new Set();

        let items = $('#tabledatax').find('.form-control.select2');

        items.each(function() {
            let value = $(this).val();

            if (value == null || value === undefined || value == '') {
                return false;
            }

            if (ids.has(value)) {
                swal ("Kode Barang sudah ada !!!!!");            
                ada=true;
                return false;
            }            

            ids.add(value);
        })

        if(!ada){
            var eproduct = $('#eproduct'+id).val();            
            $.ajax({
                type: "post",
                data: {
                    'eproduct'  : eproduct
                },
                url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#idproduct'+id).val(data[0].id_product);
                    $('#iproduct'+id).val(data[0].i_product_base);
                    $('#idcolorproduct'+id).val(data[0].id_color);
                    $('#ecolorproduct'+id).val(data[0].e_color_name);
                    $('#nquantity'+id).focus();                  
                },
                error: function (error) {
                    console.log(error);
                    // swal('Error :)');
                }
            });
        }else{
            $('#idproduct'+id).html('');
            $('#iproduct'+id).html('');
            $('#eproduct'+id).html('');
            $('#idcolorproduct'+id).html('');
            $('#ecolorproduct'+id).html('');
            $('#idproduct'+id).val('');
            $('#iproduct'+id).val('');
            $('#eproduct'+id).val('');
            $('#idcolorproduct'+id).val('');
            $('#ecolorproduct'+id).val('');
        }
    }

    function getstok(id){
        var itujuan = getItujuan();
        var idproduct = $('#eproduct'+id).val();
        var ibagian = $('#ibagian').val();

        $.ajax({
            type: "post",
            data: {
                'idproduct'  : idproduct,
                'ibagian'    : ibagian,
                'itujuan' : itujuan
            },
            url: '<?= base_url($folder.'/cform/getstok'); ?>',
            dataType: "json",
            success: function (data) {
                const saldoAkhir = data?.saldo_akhir ?? 0;
                $('#stok'+id).val(saldoAkhir);
            },
            error: function () {
                // swal('Error :)');
                console.log('clear select2');
            }
        });
    }

    function getStockBundle(element, target)
    {
        var itujuan = getItujuan();
        var idproduct = $(element).val();
        var ibagian = $('#ibagian').val();

        $.ajax({
            type: "post",
            data: {
                'idproduct'  : idproduct,
                'ibagian'    : ibagian,
                'itujuan' : itujuan
            },
            url: '<?= base_url($folder.'/cform/getstok'); ?>',
            dataType: "json",
            success: function (data) {
                const saldoAkhir = data?.saldo_akhir ?? 0;
                $(target).prop('readonly', false);
                $(target).val(saldoAkhir);
                $(target).prop('readonly', true);
            }
        });
    }

    function hetang(params) {
        var qty = parseFloat($('#nquantity'+params).val());
        var stok = parseFloat($('#stok'+params).val());
        if (qty > stok) {
            swal("Maaf :(", "Quantity Kirim tidak boleh lebih besar dari Stok = "+stok,"error");
            $('#nquantity'+params).val(stok);
        }
    }

    function validasiStockBundle(element, target){
        const stock = $(target).val();
        const request = $(element).val();

        if(parseFloat(request)>parseFloat(stock)){
            swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stock);
            $(element).val(stock); 
        }

        if(parseFloat(request) == 0 && parseFloat(request) == ''){
            swal('Quantity Tidak Boleh 0 atau Kosong');
            $(element).val(stock);
        }
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        $('#jml').val(counter);
        del();
        let no = $(this).data('i');
        $(`.tr_second${no}`).closest("tr").remove();
        $(`.tr_bundling${no}`).closest("tr").remove();
    });

    $("#tabledatax").on("click", ".ibtnDelBundling", function(event) {
        $(this).closest("tr").remove();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function validasi(id){
        var jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            var nquantity    =document.getElementById("nquantity"+i).value;
            var stok         =document.getElementById("stok"+i).value;
            if(parseFloat(nquantity)>parseFloat(stok)){
                swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                document.getElementById("nquantity"+i).value=stok;
                break;
            }
            if(parseFloat(nquantity) == 0 && parseFloat(nquantity) == ''){
                swal('Quantity Tidak Boleh 0 atau Kosong');
                document.getElementById("nquantity"+i).value=stok;
                break;
            }
        }
    }

    function konfirm() {
        let valid = isDataValid();
        if (!valid) {
            return false;
        }

        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
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
    }    

    function clearDetailBarang() {
        // trigger click delete button
        const allButton = $("body .ibtnDel");
        allButton.each(function() {
            $(this).trigger('click');
            counter--;
            counterx--;
            $('#jml').val(counter);
        })
    }

    function getItujuan() {
        return $('#itujuan').val()
    }

    function setIdProduct(counter) {
        const value = $('#eproduct' + counter).val();
        setTimeout(() => {
            $('#idproduct' + counter).val(value);
        }, 300)        
    }

    function cekBarisKosong() {
        let kosong = false;

        let items = $('#tabledatax').find('.form-control.select2');

        items.each(function() {
            let value = $(this).val();

            if (value == null || value === undefined || value == '') {
                swal('Isi dulu yang masih kosong!!');
                kosong = true;
            }
        });

        return kosong;
    }

    function cekQuantityKosong() {
        let zeroQty = false;

        let qtys = $('#tabledatax').find('input[name="nquantity[]"]');

        qtys.each(function() {
            let value = $(this).val();            

            if (value === '' || parseFloat(value) <= 0) {
                swal('Quantity tidak valid !!');
                zeroQty = true;
            }
        })

        return zeroQty;
    }

    function isDataValid() {   
        let valid = true;
        if (cekBarisKosong()) {
            valid = false;
        }

        if (cekQuantityKosong()) {
            valid = false;
        }
        return valid;
    }
    
</script>