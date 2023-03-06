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
                        <label class="col-md-3">Keterangan</label>
                       
                        <div class="col-md-3">
                            <select name="id_bagian" id="id_bagian" class="form-control select2">
                            <?php foreach ($bagian as $row):?>
                                <?php /** default select bagian dengan type 12 / PACKING */ ?>
                                <?php $selected = $row->i_type == '12' ? 'selected' : ''; ?>
                                <option value="<?= $row->id;?>" <?= $selected ?>>
                                    <?= $row->e_bagian_name;?>
                                </option>
                            <?php endforeach; ?>                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="i_document" id="i_document" readonly="" autocomplete="off" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="d_document" name="d_document" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y");?>">
                        </div>                        
                        <div class="col-md-3">
                            <textarea id= "e_remark" placeholder="Isi Keterangan Jika Ada!!!" name="e_remark" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();">
                                <i class="fa fa-save mr-2" ></i>Simpan
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm mr-2">
                                <i class="fa fa-plus mr-2"></i>Item
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>              
                        </div>
                        <div class="col">
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>                        
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

    $('#id_bagian').change(function(event) {
        number();
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'id_bagian' : $('#id_bagian').val(),
            },
            url: '<?= base_url($folder.'/cform/generate_nomor_dokumen'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_document').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });

        clearDetailBarang();
    }

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
        var i_product = $('#i_product'+counterx).val();
        count=$('#tabledatax tr').length;  

        let valid = isDataValid();
        if (!valid) {
            return false;
        }

        $('#jml').val(counter);
        var newRow = $("<tr class='no tr" + counter + "'>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += `<td>
                    <input type="text" readonly id="i_product${counter}" class="form-control input-sm">
                </td>`;
        cols += `<td>
                    <select type="text" data-placeholder="Pilih Barang" 
                        id="eproduct${counter}"
                        name="items[${counter}][id_product]"
                        class="form-control select2"
                        onchange="getproduct(${counter}); getstok(${counter}); ">
                            <option value=""></option>
                    </select>
                </td>`;
        cols += `<td>
                    <input type="text" readonly id="ecolorproduct${counter}" class="form-control input-sm">
                </td>`;
        cols += `<td>
                    <input type="text" readonly class="form-control input-sm text-right" id="stok${counter}">
                </td>`;
        cols += `<td>
                    <input type="number" id="n_quantity${counter}" name="items[${counter}][n_quantity]" 
                        class="form-control input-sm text-right inputitem"
                        value="0" onkeyup="hetang(${counter})">
                </td>`;
        cols += `<td>
                    <input type="text" id="e_remark'+ counter + '" name="items[${counter}][e_remark]" 
                        class="form-control input-sm" placeholder="Keterangan...">
                </td>`;
        cols += `<td class="text-center">
                    <button data-urut="' + counter + '" type="button" onclick="tambah_material(' + counter + ');" title="Tambah List" 
                        class="btn btn-sm btn-circle btn-info d-none">
                        <i data-urut="' + counter + '" id="addlist' + counter + '"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                    </button>
                    <button type="button" data-i="${counter}" title="Delete" class="ibtnDel btn btn-circle btn-danger">
                        <i class="ti-close"></i>
                    </button>
                </td>`;

        newRow.append(cols);
        $("#tabledatax tr:first").after(newRow);
        var newRow1 = $(
            `<tr class="table-active tr_second${counter}">
                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                <td colspan="7"><b>Bundling Produk</b></td>
            </tr>`);
        // $(newRow1).insertAfter(`#tabledatax .tr${counter}`);
        // $("#tabledatax").append(newRow);
        restart();

        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            width:"100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/dataproduct?id_bagian='); ?>' + $('#id_bagian').val(),
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

    /** disable fungsi tambah bundling product */
    function tambah_material(i) {
        return;
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
                    $('#id_product'+id).val(data[0].id_product);
                    $('#i_product'+id).val(data[0].i_product_base);
                    $('#idcolorproduct'+id).val(data[0].id_color);
                    $('#ecolorproduct'+id).val(data[0].e_color_name);
                    $('#n_quantity'+id).focus();                  
                },
                error: function (error) {
                    console.log(error);
                    // swal('Error :)');
                }
            });
        }else{
            $('#id_product'+id).html('');
            $('#i_product'+id).html('');
            $('#eproduct'+id).html('');
            $('#idcolorproduct'+id).html('');
            $('#ecolorproduct'+id).html('');
            $('#id_product'+id).val('');
            $('#i_product'+id).val('');
            $('#eproduct'+id).val('');
            $('#idcolorproduct'+id).val('');
            $('#ecolorproduct'+id).val('');
        }
    }

    function getstok(id){
        var id_product = $('#eproduct'+id).val();
        var id_bagian = $('#id_bagian').val();

        $.ajax({
            type: "post",
            data: {
                'id_product': id_product,
                'id_bagian': id_bagian,
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
        var id_product = $(element).val();
        var id_bagian = $('#id_bagian').val();

        $.ajax({
            type: "post",
            data: {
                'id_product'  : id_product,
                'id_bagian'    : id_bagian,
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
        var qty = parseFloat($('#n_quantity'+params).val());
        var stok = parseFloat($('#stok'+params).val());
        if (qty > stok) {
            swal("Maaf :(", "Quantity Kirim tidak boleh lebih besar dari Stok = "+stok,"error");
            $('#n_quantity'+params).val(stok);
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
            var n_quantity    =document.getElementById("n_quantity"+i).value;
            var stok         =document.getElementById("stok"+i).value;
            if(parseFloat(n_quantity)>parseFloat(stok)){
                swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                document.getElementById("n_quantity"+i).value=stok;
                break;
            }
            if(parseFloat(n_quantity) == 0 && parseFloat(n_quantity) == ''){
                swal('Quantity Tidak Boleh 0 atau Kosong');
                document.getElementById("n_quantity"+i).value=stok;
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

    function setid_product(counter) {
        const value = $('#eproduct' + counter).val();
        setTimeout(() => {
            $('#id_product' + counter).val(value);
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

        let qtys = $('#tabledatax').find('input[name="n_quantity[]"]');

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