<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i>  <?= $title; ?> <a href="#"
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
                       
                        <input type="hidden" id="id" name="id" value="<?= $data->id ?>" />
                        <div class="col-md-3">
                            <select name="id_bagian" id="id_bagian" class="form-control select2">
                            <?php foreach ($bagian as $row):?>
                                <?php /** default select bagian dengan type 12 / PACKING */ ?>
                                <?php $selected = $row->id == $data->id_bagian ? 'selected' : ''; ?>
                                <option value="<?= $row->id;?>" <?= $selected ?>>
                                    <?= $row->e_bagian_name;?>
                                </option>
                            <?php endforeach; ?>                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="i_document" id="i_document" readonly="" autocomplete="off" class="form-control input-sm" value="<?= $data->i_document ?>" aria-label="Text input with dropdown button">
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="d_document" name="d_document" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y", strtotime($data->d_document));?>">
                        </div>                        
                        <div class="col-md-3">
                            <textarea id= "e_remark" placeholder="Isi Keterangan Jika Ada!!!" name="e_remark" class="form-control"><?= $data->e_remark ?></textarea>
                        </div>
                    </div>
                                 
                    <div class="form-group row">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                            <div class="col">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2" ></i>Update</button>
                            </div>
                        <?php } ?>
                        <?php if($data->i_status == '2'){?>
                            <div class="col d-none">
                                <button type="button" id="addrow" class="btn btn-info btn-block btn-sm mr-2" hidden="true"><i class="fa fa-plus mr-2"></i>Item</button>
                            </div>
                        <?php }else{?>
                            <div class="col">
                                <button type="button" id="addrow" class="btn btn-info btn-block btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                            </div>
                        <?php }?>
                            <div class="col">
                                <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            </div>
                        <?php if ($data->i_status == '1') {?>
                            <div class="col">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            </div>
                            <div class="col">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            </div>
                        <?php }elseif($data->i_status=='2') {?>
                            <div class="col">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            </div>
                        <?php } ?>
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
                    <?php $i=1; foreach ($detail as $item) { ?>
                        <tr>
                            <td class="text-center"><spanx id="snum<?= $i ?>"><?= $i ?></spanx></td>
                            <td><input type="text" readonly id="i_product<?= $i ?>" value="<?= $item->i_product_base ?>" class="form-control input-sm"></td>
                            <td>
                                <select type="text" data-placeholder="Pilih Barang" 
                                    id="eproduct<?= $i ?>"
                                    name="items[<?= $i ?>][id_product]"
                                    class="form-control select2 input-product"
                                    value="<?= $item->id_product_base ?>"
                                    onchange="getproduct(<?= $i ?>); getstok(<?= $i ?>); ">
                                        <option value="<?= $item->id_product_base ?>" selected><?= $item->e_product_basename ?></option>
                                </select>
                            </td>
                            <td><input type="text" readonly id="ecolorproduct<?= $i ?>" value="<?= $item->e_color_name ?>" class="form-control input-sm"></td>
                            <td><input type="text" readonly class="form-control input-sm text-right" id="stok<?= $i ?>"></td>
                            <td>
                                <input type="number" id="n_quantity<?= $i ?>" name="items[<?= $i ?>][n_quantity]" 
                                    class="form-control input-sm text-right inputitem"
                                    value="<?= $item->n_quantity ?>" onkeyup="hetang(<?= $i ?>)">
                            </td>
                            <td>
                                <input type="text" id="e_remark'+ counter + '" name="items[<?= $i ?>][e_remark]" value="<?= $item->e_remark ?>"
                                    class="form-control input-sm" placeholder="Keterangan...">
                            </td>
                            <td class="text-center">
                                <button data-urut="<?= $i ?>" type="button" onclick="tambah_material(<?= $i ?>);" title="Tambah List" 
                                    class="btn btn-sm btn-circle btn-info d-none">
                                    <i data-urut="<?= $i ?>" id="addlist<?= $i ?>"  class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                                </button>
                                <button type="button" data-i="<?= $i ?>" title="Delete" class="ibtnDel btn btn-circle btn-danger">
                                    <i class="ti-close"></i>
                                </button>
                            </td>
                        </tr>
                    <?php $i++; } ?>
                    </tbody>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i ?>">
                </table>
            </div>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
   $(document).ready(function () {
        // $('#dokumenbon').mask('SSS-0000-000000S');
        $('.select2').select2({
            width : '100%',
        });
        showCalendar('.date');
        // $('#ibagian').select2({
        //     placeholder: 'Pilih Bagian',
        //     width: '100%',
        //     allowClear: true,
        //     ajax: {
        //         url: '<?= base_url($folder.'/cform/bagian'); ?>',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             var query = {
        //                 q: params.term,
        //                 ibagian : $('#xbagian').val(),
        //             }
        //             return query;
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: data,
        //             };
        //         },
        //         cache: false
        //     }
        // });

        var jmls = $('#jml').val();
        console.log(jmls);
        for(s=1;s<= jmls;s++){
            getstok(s);
        }
    });

    
    
    // $( "#dokumenbon" ).keyup(function() {
    //     $.ajax({
    //         type: "post",
    //         data: {
    //             'kode' : $(this).val(),
    //             'ibagian' : $('#ibagian').val(),
    //         },
    //         url: '<?= base_url($folder.'/cform/cekkode'); ?>',
    //         dataType: "json",
    //         success: function (data) {
    //             if (data==1 && ($('#dokumenbon').val()!=$('#ibonkold').val())) {
    //                 $("#ada").attr("hidden", false);
    //                 $("#submit").attr("disabled", true);
    //             }else{
    //                 $("#ada").attr("hidden", true);
    //                 $("#submit").attr("disabled", false);
    //             }
    //         },
    //         error: function () {
    //             swal('Error :)');
    //         }
    //     });
    // });

    // $('#ceklis').click(function(event) {
    //     if($('#ceklis').is(':checked')){
    //         $("#dokumenbon").attr("readonly", false);
    //     }else{
    //         $("#dokumenbon").attr("readonly", true);
    //         $("#ada").attr("hidden", true);
    //         $("#dokumenbon").val($("#ibonkold").val());
    //         /*number();*/
    //     }
    // });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dbonk').val(),
                'ibagian' : $('#ibagian').val(),
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
        <td></td>
        <td class="text-center"><button type="button" title="Delete" data-b = "${i}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="fa fa-lg fa-minus-circle" aria-hidden="true"></i></td>
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

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {

        let allInputProduct = $('.input-product');
        let counterUndefined = 0; 
        allInputProduct.each(function() {
            if ($(this).val() === undefined) {
                counterUndefined++;
            }
        })

        console.log(counterUndefined);

        if (counterUndefined > 1) {
            swal('Isi dulu yang masih kosong!!');
            return false;
        }

        counter++;
        $("#tabledatax").attr("hidden", false);
        var id_product = $('#eproduct'+counterx).val();
        
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
                        class="form-control select2 input-product"
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
            width: "100%",
            allowClear: true,
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

    function getproduct(id){
        ada=false;
        var a = $('#eproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#eproduct'+i).val()) && (i!=x)){
                swal ("Kode Barang sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

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
                error: function () {
                    swal('Error :)');
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
                console.log(data);
                const saldoAkhir = data?.saldo_akhir ?? 0;
                $(target).prop('readonly', false);
                $(target).val(saldoAkhir);
                $(target).prop('readonly', true);
            },
            error: function(err) {
                console.log(err);
            }
        });
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

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function hapus_tr(i) {
        $(`.th${i}`).closest("tr").remove();
        $(`.td${i}`).closest("tr").remove();
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
                $(this).find("td .inputitem").each(function() {
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

    function updateRowSaldo(id, data) {
        $('html #stok'+id).prop('readonly', false);
        $('html #stok'+id).val(data.saldo_akhir);
        $('html #stok'+id).prop('readonly', true);
    }

</script>