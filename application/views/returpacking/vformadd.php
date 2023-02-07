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
                                <input type="text" name="iretur" id="i_retur_wip" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dretur" name="dretur" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y") ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="number();">                                
                                <?php $group = ""; foreach ($tujuan as $row) { ?>
                                    <?php if ($group != $row->name) { ?>
                                        </optgroup>
                                        <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                    <?php } ?>
                                    
                                    <?php $group = $row->name;
                                        /** default company select */
                                        $selected = ($row->id_company == $this->session->userdata('id_company')) ? 'selected' : '' ?>

                                        <option value="<?= $row->id_bagian ?>" <?= $selected ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php } ?>                                
                            </select>
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>                   
                    <div class="row">
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
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <!-- <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>Item</button>
                </div>
            </div>
        </div> -->
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center" style="width: 26%;">Nama Barang Jadi</th>
                        <th class="text-center" style="width: 8%;">Warna</th>
                        <th class="text-center" style="width: 8%;">Stok</th>
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
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function () {
        // $('#i_retur_wip').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();
    });

    $('#ibagian, #dretur').change(function(event) {
        number();
    });
    
    $( "#i_retur_wip" ).keyup(function() {
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
            error: function (error) {
                console.log('error ' + error);
                // swal('Error :)');
            }
        });
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dretur').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#i_retur_wip').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });

        clearDetailBarang();
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#i_retur_wip").attr("readonly", false);
        }else{
            $("#i_retur_wip").attr("readonly", true);
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
        // console.log(iproduct + '||' + count);
        // if ((iproduct=='' || iproduct==null || iproduct === undefined) && (count>1)) {
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
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct'+ counter + '" class="form-control" name="idproduct[]"><input type="text" readonly id="iproduct'+ counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control select2" name="eproduct'+ counter + '" onchange="getproduct('+ counter + ');"></select></td>';
        cols += '<td><input type="hidden" id="idcolorproduct'+ counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct'+ counter + '" class="form-control input-sm" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity_stok'+ counter + '" value="" class="form-control input-sm" readonly></td>';
        cols += '<td><input type="number" id="nquantity'+ counter + '" class="form-control text-right input-sm" name="nquantity[]" value="" placeholder="0" onkeyup="hetang('+ counter + ');"></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control input-sm" name="edesc[]" placeholder="Isi keterangan jika ada!"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            width:"100%",
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

    function hetang(params) {
        var qty = parseFloat($('#nquantity'+params).val());
        var stok = parseFloat($('#nquantity_stok'+params).val());
        if (qty > stok) {
            swal("Maaf :(", "Quantity Kirim tidak boleh lebih besar dari Stok = "+stok,"error");
            $('#nquantity'+params).val(stok);
        }
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

    function formatSelection(val) {
        return val.name;
    }  

    function getproduct(id){
        ada=false;
        // var a = $('#eproduct'+id).val();
        // var x = $('#jml').val();
        // console.log(id);
        // console.log(x);
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
                    'eproduct'  : eproduct,
                    'ibagian' : $('#ibagian').val(),
                    'itujuan': getItujuan()
                },
                url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    if (!data[0]?.id_product) {
                        // swal('Tidak ada data');
                        return false;
                    }
                    $('#idproduct'+id).val(data[0].id_product);
                    $('#iproduct'+id).val(data[0].i_product_base);
                    $('#idcolorproduct'+id).val(data[0].id_color);
                    $('#ecolorproduct'+id).val(data[0].e_color_name);
                    $('#nquantity_stok'+id).val(data[0].n_saldo_akhir);
                    // $('#nquantity'+id).focus();
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

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        counter--;
        counterx--;
        $(this).closest("tr").remove();
        $('#jml').val(counter);
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function konfirm() {
        let valid = isDataValid();
        if (!valid) {
            return false;
        }

        var jml = $('#jml').val();
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#itujuan').val()!='' || $('#itujuan').val())) {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=jml;i++){
                    if($("#iproduct"+i).val()=='' || $("#eproductname"+i).val()=='' || $("#nquantity"+i).val()==''){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
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