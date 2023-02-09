<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Bagian Pengirim</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>"><?= $key->e_bagian_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" required="" id="ibbm" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?php // $number;?>)</span><br> -->
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ipengirim" required="" id="ipengirim" class="form-control select2" data-placeholder="Pilih Pengirim">
                                <option value=""></option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen Reff</label>
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" required="" class="form-control input-sm select2"></select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php foreach ($jenisbarang as $jenis) { ?>
                                    
                                    <?php /** Tampilkan hanya opsi bagus */ ?>
                                    <?php if ($jenis->id != 1) continue;  ?>

                                    <option value="<?= $jenis->id;?>">
                                        <?= $jenis->e_jenis_name;?>
                                    </option>

                                <?php } ?>
                            </select>
                        </div>  
                        <div class="col-sm-6">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                        <div class="col-sm-4 d-none">
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
<div class="white-box" id="detail" hidden="true">
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="table-responsive">
        <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
            <thead>
                <tr>
                    <th class="text-center" width="3%">No</th>
                    <th class="text-center" width="10%">Kode</th>
                    <th class="text-center" width="30%">Nama Barang</th>
                    <th class="text-center" width="12%">Warna</th>
                    <th class="text-center" width="8%">Qty Kirim</th>
                    <th class="text-center" width="10%">Qty Terima</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    
    $(document).ready(function () {
        $('#ibbm').mask('SSS-0000-0000S');
        number();
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);

        $('#ipengirim').select2({
            placeholder: 'Pilih Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/pengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ibagian    : $('#ibagian').val(),
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
        }).change(function(event) {
            $('#ireff').val('');
            $('#ireff').html('');
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#ireff').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ipengirim  : $('#ipengirim').val(),
                        ibagian    : $('#ibagian').val(),
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

            /*----------  GET DATA DETAIL AFTER CHANGE REFERENSI  ----------*/
            
            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id' : $(this).val(),
                    'ibagian' : $('#ibagian').val(),
                    'ipengirim' : $('#ipengirim').val(),
                },
                url: '<?= base_url($folder.'/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    createDetailBarang(data);
                },
                error: function () {
                    swal('Ada kesalahan :(');
                }
            })
        });
    });

    /*----------  CEK SALDO  ----------*/ 
    function ceksaldo(i) {
        if (parseFloat($('#npemenuhan'+i).val()) > parseFloat($('#nquantity'+i).val())) {
            swal('Qty terima tidak boleh lebih dari qty sisa!!!');
            $('#npemenuhan'+i).val($('#nquantity'+i).val());
        }
    }

    /*----------  NOMOR DOKUMEN  ----------*/    

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
                $('#ibbm').val(data);
            },
            error: function () {
                swal('Error :(');
            }
        });
    }

    /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/
    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ibbm").attr("readonly", false);
        }else{
            $("#ibbm").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/    

    $( "#ibbm" ).keyup(function() {
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
                swal('Error :(');
            }
        });
    });

    /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/    

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT BAGIAN PEMBUAT DAN TANGGAL DOKUMEN DIRUBAH  ----------*/
    
    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    
    $('#submit').click(function(event) {
        if($("#jml").val()==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        // }else{
        //     for (var i = 0; i < $("#jml").val(); i++) {
        //         if($("#npemenuhan"+i).val()=='' || $("#npemenuhan"+i).val()==null || $("#npemenuhan"+i).val()==0){
        //             swal('Jumlah Pemenuhan Harus Lebih Besar Dari 0!');
        //             return false;
        //         }
        //     }
        }
    });

    /*----------  KONDISI SETELAH MENEKAN TOMBOL SIMPAN  ----------*/    

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    const createDetailBarang = (data) => {
        // console.log(data);
        const count = data.length;
        $('#jml').val(count);

        data.map(({product, bundling}, index) => {
            let cols = "";
            let newRow = $('<tr>');

            cols += `<td class="text-center">${index + 1}</td>`;
            cols += `<td>
                        <input class="form-control input-sm" readonly type="text" id="iproduct${index}" name="iproduct${index}" value="${product?.i_product}">
                        <input type="hidden" id="idproduct${index}" name="idproduct${index}" value="${product?.id_product}">
                        <input type="hidden" id="id_document_reff${index}" name="id_document_reff${index}" value="${product?.id_item}">
                    </td>`;
            cols += `<td>
                        <input class="form-control input-sm" readonly type="text" id="eproduct${index}" name="eproduct${index}" value="${product?.e_product}">
                    </td>`;

            cols += `<td>
                        <input readonly class="form-control input-sm" type="text" id="ecolor${index}" name="ecolor${index}" value="${product?.e_color_name}">
                    </td>`;

            cols += `<td>
                        <input readonly class="form-control input-sm text-right" type="text" id="nquantity${index}" name="nquantity${index}" value="${product?.n_quantity_sisa}">
                    </td>`;

            cols += `<td>
                        <input class="form-control input-sm text-right" 
                            type="number" 
                            id="npemenuhan${index}" 
                            name="npemenuhan${index}" 
                            value="${product?.n_quantity_sisa}" 
                            placeholder="0" onkeyup="ratioTerimaItemBundling(this)"
                            data-index="${index}">
                    </td>`;

            cols += `<td>
                        <input type="text" class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="eremark${index}">
                    </td>`;

            newRow.append(cols);

            $('#tabledatax').append(newRow);

            if (bundling.length <= 0) {
                return;
            }

            cols = `<td class="text-center">
                        <i class="fa fa-hashtag fa-lg"></i>
                    </td>
                    <td colspan="7">
                        <b>Bundling Produk</b>
                    </td>`;
                    
            newRow = $('<tr class="th1 bold table-active">');

            newRow.append(cols);

            $('#tabledatax').append(newRow);

            // <!-- if data bundling -->
            let rootProductIndex = index;

            bundling.map((obj, index) => {
                let cols = "";
                let newRow = $('<tr>');

                cols += `<td class="text-center"><spanx>${index + 1}</spanx></td>`;

                cols += `<td>${obj?.i_product_base}</td>`;
                
                cols += `<td  class="d-flex justify-content-between"><span>${obj?.e_product_basename}</span></td>`;

                cols += `<td>${obj?.e_color_name}</td>`;

                cols += `<td class="text-right">
                            <input data-children="bundling_kirim" 
                            class="form-control input-sm"
                            value="${obj?.n_quantity_bundling}" readonly>
                        </td>`;

                cols += `<td>
                            <input class="form-control input-sm text-right" type="number" 
                                id="bundling_terima${index}" 
                                name="bundling_terima${index}" 
                                value="${obj?.n_quantity_bundling}" 
                                placeholder="0" 
                                data-children="bundling_terima${rootProductIndex}"
                                readonly>
                        </td>`;

                // cols += `<td>
                //             <input type="text" class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="bundling_eremark${index}">
                //         </td>`;
                                                
                newRow.append(cols);

                $('#tabledatax').append(newRow);
            });

        });
    }

    const calcRatio = (a, b, c) => a / b * c;

    function ratioTerimaItemBundling(e) {
        const index = $(e).attr('data-index');
        const value = $(e).val();

        let elementQtyKirim = $(`#nquantity${index}`);
        const qtyKirim = elementQtyKirim.val();
        
        let allBundling = $(`*[data-children="bundling_terima${index}"]`);

        allBundling.each(function() {

            let elementQtyBundling = $(this).closest('tr').find('input[data-children="bundling_kirim"]');
            const qtyBundling = $(elementQtyBundling).val();

            const newValue = calcRatio(qtyBundling, qtyKirim, value);
            $(this).val(newValue);
        })
        
    }

</script>