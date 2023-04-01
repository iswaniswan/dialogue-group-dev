<style>
    .dropify-wrapper {
        height: 86px !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus fa-lg mr-2"></i>
                <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                    <?= $title_list; ?>
                </a>
            </div>
            <div class="panel-body">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Kode Barang</label>
                        <label class="col-md-8">Nama Barang</label>

                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="iproduct" id="iproduct" readonly=""
                                    class="form-control input-sm">
                                <input type="hidden" id="idproduct" name="idproduct" required="" readonly>
                                <input type="hidden" id="idmarker" name="idmarker" required="" readonly>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-9">
                                    <select name="product" id="product" class="form-control select2"></select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="marker" id="marker" class="form-control select2"></select>
                                </div>
                            </div>
                            <input type="hidden" id="icolor" name="icolor" required="" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-8">Keterangan</label>
                        <label class="col-md-4 notekode">Upload File Formatnya .xls (Optional)</label>
                        <div class="col-sm-8">
                            <textarea id="eremarkh" rows="4" placeholder="Isi Keterangan Jika Ada!!!" name="eremarkh"
                                class="form-control"></textarea>
                        </div>
                        <div class="col-sm-4">
                            <input type="file" id="input-file-now" name="userfile" class="dropify" />
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"
                                onclick="return konfirm();"><i class="fa fa-save fa-lg mr-2"></i>Simpan</button>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" id="addrow" class="btn btn-info btn-block btn-sm"><i
                                    class="fa fa-plus fa-lg mr-2"></i>Item</button>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-inverse btn-block btn-sm"
                                onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"><i
                                    class="ti-arrow-circle-left fa-lg mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-2">&nbsp;</div>
                        <div class="col-sm-2">
                            <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i
                                    class="ti-upload fa-lg mr-2"></i>Upload Data</button>
                        </div>
                        <div class="col-sm-2">
                            <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i
                                    class="ti-download fa-lg mr-2"></i>Download Template</button> </a>
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
                        <table id="tabledatax" class="table color-table success-table table-bordered class"
                            cellpadding="8" cellspacing="1" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="3%;">No</th>
                                    <th class="text-center" width="30%;">Material</th>
                                    <th class="text-center" width="15%;">Bagian Panel</th>
                                    <th class="text-center" width="15%;">Kode Panel</th>
                                    <th class="text-center" width="8%;">Qty Penyusun</th>
                                    <th class="text-center" width="8%;">Panjang <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Lebar <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Panjang <br>Gelaran <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Lebar <br>Gelaran <sup>cm</sup></th>
                                    <th class="text-center" width="8%;">Hasil <br>Gelaran <sup>set</sup></th>
                                    <th class="text-center" width="8%;">Efficiency <br> Marker <sup>%</sup></th>
                                    <th class="text-center" width="4%;">Print</th>
                                    <th class="text-center" width="4%;">Bordir</th>
                                    <th class="text-center" width="4%;" hidden>Khusus <br>Pengadaan</th>
                                    <th class="text-center" width="30%;">Makloon</th>
                                    <th class="text-center" width="3%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <input type="hidden" name="jml" id="jml" value="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function () {
        $('.dropify').dropify();
        $('.select2').select2();
        $('#marker').attr('disabled', true);
        $('#product').select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/product/'); ?>',
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
                cache: true
            }
        }).change(function (event) {

            var produk = $(this).val()?.split("-");
            if (produk) {
                $("#idproduct").val(produk[0]);
                $("#iproduct").val(produk[1]);
                $("#ecolor").val(produk[3]);
                $("#icolor").val(produk[2]);
            }

            var jml = $("#jml").val();
            if (jml > 0) {
                for (i = 1; i <= jml; i++) {
                    $("#ipanel" + i).val(produk[1]);
                    $("#ebagian" + i).val('');
                }
            }
            if (produk) {
                $('#marker').empty();
                $('#marker').attr('disabled', false);
            } else {
                $('#marker').attr('disabled', true);
            }
        })

        $('#marker').select2({
            placeholder: 'Cari Nama Marker',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/marker/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        i_color: $("#icolor").val(),
                        id_product_wip: $("#idproduct").val()
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
        }).change(function (event) {

            var marker = $(this).val();
            $("#idmarker").val(marker);
        })

        // showCalendar('.date', 1830, 0);
        $("#upload").on("click", function () {
            var idproduct = $('#idproduct').val();
            var idmarker = $('#idmarker').val();
            if (idproduct.length > 0 && idmarker.length > 0) {
                var formData = new FormData();
                formData.append('userfile', $('input[type=file]')[0].files[0]);
                formData.append('idproduct', idproduct);
                formData.append('idmarker', idmarker);
                $.ajax({
                    type: "POST",
                    url: "<?= base_url($folder . '/cform/load'); ?>",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: false,
                    success: function (data) {
                        var json = JSON.parse(data);
                        var sama = json.sama;
                        var status = json.status;
                        var detail = json.datadetail;
                        console.log(json);
                        if(status == 'berhasil') {
                            swal({
                                title: "Success!",
                                text: "File Success Diupload :)",
                                type: "success",
                                showConfirmButton: true,
                                closeOnConfirm: false
                            }, function() {
                                if(detail.length > 0) {
                                    let cols = '';
                                    detail.map((data) => {
                                        cols += `Kode material (${data.i_material}) baris cell no ${data.baris_excel}\n`;
                                    })
                                    swal({
                                        title: "Terdapat data yang tidak tersimpan!",
                                        text: cols,
                                        type: "warning",
                                        showConfirmButton: true,
                                    });
                                } else {
                                    swal.close();
                                }
                            });
                        } else if (status == 'gagal id_product tidak cocok') {
                            swal({
                                title: "Failed!",
                                text: detail,
                                type: "error",
                                showConfirmButton: true,
                                closeOnConfirm: false
                            })
                        } else {
                            swal({
                                title: "Failed!",
                                text: detail,
                                type: "error",
                                showConfirmButton: true,
                                closeOnConfirm: false
                            })
                        }
                        // if (sama == true) {
                        //     if (status == 'berhasil') {
                        //         // console.log(detail);
                        //         swal({
                        //             title: "Success!",
                        //             text: "File Success Diupload :)",
                        //             type: "success",
                        //             showConfirmButton: false,
                        //             timer: 1500
                        //         });
                        //         if (json.detail.length > 0) {
                        //             clear_table();
                        //             $('.n_fc_jahit').text(formatcemua(json.n_quantity));
                        //             $('.n_fc_jahit_sisa').text(formatcemua(json.n_quantity_sisa));
                        //             $('.n_fc_jahit_urai').text(formatcemua(json.n_quantity_urai));
                        //             $('#jml').val(json.detail.length);
                        //             var group = '';
                        //             var no = 1;
                        //             var newRow = $("<tbody>");
                        //             for (let i = 0; i < json.detail.length; i++) {
                        //                 var cols = "";
                        //                 // var n_quantity_sisa = parseFloat(data['detail'][i]['n_quantity']) - parseFloat(data['detail'][i]['n_quantity_uraian']);
                        //                 if (group == '') {
                        //                     cols += `<tr class="table-active">
                        //                             <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${json.detail[i].grup}"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                        //                             <td colspan="7">${json.detail[i].e_type_name}</td>
                        //                         </tr>`;
                        //                 } else {
                        //                     if (group != json.detail[i].grup) {
                        //                         cols += `<tr class="table-active">
                        //                             <td class="text-center"><a href="#" class="toggler" data-icon-name="fa-eye-slash" data-prod-cat="${json.detail[i].grup}"><i class="fa fa-lg fa-eye-slash text-success"></i></a></td>
                        //                             <td colspan="7">${json.detail[i].e_type_name}</td>
                        //                         </tr>`;
                        //                         no = 1;
                        //                     }
                        //                 }
                        //                 group = json.detail[i].grup;
                        //                 cols += `<tr class="${json.detail[i].grup}" style="display:none">
                        //                         <td class="text-center">${no}</td>
                        //                         <td>
                        //                             <input type="hidden" id="idproduct${i}" name="idproduct${i}" value="${json.detail[i].id}">
                        //                             <input class="form-control input-sm" readonly type="text" id="iproduct${i}" name="iproduct${i}" value="${json.detail[i].i_product_wip}">
                        //                         </td>
                        //                         <td>
                        //                             <input class="form-control input-sm" readonly type="text" id="e_product_name${i}" name="e_product_name${i}" value="${json.detail[i].e_product_name}">
                        //                         </td>
                        //                         <td>
                        //                             <input readonly class="form-control input-sm" type="text" id="e_color_name${i}" name="e_color_name${i}" value="${json.detail[i].e_color_name}">
                        //                         </td>
                        //                         <td>
                        //                             <input readonly class="form-control input-sm text-right" type="text" id="n_fcjahit${i}" name="n_fcjahit${i}" placeholder="0" value="${json.detail[i].n_quantity}">
                        //                         </td>
                        //                         <td>
                        //                             <input readonly class="form-control input-sm text-right" type="text" id="n_fcjahit_sisa${i}" name="n_fcjahit_sisa${i}" placeholder="0" value="${json.detail[i].n_quantity_sisa}">
                        //                         </td>
                        //                         <td>
                        //                             <input class="form-control input-sm text-right" type="number" id="n_uraian_jahit${i}" name="n_uraian_jahit${i}" placeholder="0" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="cekvalidasi(${i});" onkeypress="return event.charCode >= 48 && event.charCode <= 57;" value="${json.detail[i].n_quantity_urai}">
                        //                             <input type="hidden" value="${json.detail.length}" id="jmlrow">
                        //                         </td>
                        //                         <td>
                        //                             <input class="form-control input-sm" type="text" id="e_remark${i}" name="e_remark${i}" value="${json.detail[i].keterangan}" placeholder="Isi keterangan jika ada!">
                        //                         </td>
                        //                     </tr>`;
                        //                 newRow.append(cols);
                        //                 $("#tabledatay").append(newRow);
                        //                 fixedtable($('#tabledatay'));
                        //                 no++;
                        //             }
                        //             $(".toggler").click(function (e) {
                        //                 e.preventDefault();
                        //                 $('.' + $(this).attr('data-prod-cat')).toggle();
                        //                 // $(this).addClass('active');

                        //                 //Remove the icon class
                        //                 if ($(this).find('i').hasClass('fa-eye')) {
                        //                     //then change back to the original one
                        //                     $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                        //                 } else {
                        //                     //Remove the cross from all other icons
                        //                     $('.faq-links').each(function () {
                        //                         if ($(this).find('i').hasClass('fa-eye')) {
                        //                             $(this).find('i').removeClass('fa-eye').addClass($(this).data('icon-name'));
                        //                         }
                        //                     });

                        //                     $(this).find('i').addClass('fa-eye').removeClass($(this).data('icon-name'));
                        //                 }
                        //             });
                        //         }
                        //     } else {
                        //         swal({
                        //             title: "Gagal!",
                        //             text: "File Gagal Diupload :)",
                        //             type: "error",
                        //             showConfirmButton: false,
                        //             timer: 1500
                        //         });
                        //     }
                        // } else {
                        //     swal({
                        //         title: "Maaf!",
                        //         text: "Referensi yang dipilih tidak sama dengan referensi yang di download :)",
                        //         type: "info",
                        //         showConfirmButton: false,
                        //         timer: 1500
                        //     });
                        // }
                    },
                });
            } else {
                swal({
                    title: "Maaf!",
                    text: "Barang dan Marker tidak boleh kosong :)",
                    type: "info",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function (event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
    });

    /**
    * Tambah Item
    */
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        //alert("tes");
        i++;
        $("#jml").val(i);
        var no = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr' + i + '">');
        var ipanel = $("#iproduct").val();
        var cols = "";
        cols += '<td class="text-center"><spanx id="snum' + i + '">' + no + '</spanx></td>';
        cols += '<td><select data-nourut="' + i + '" id="imaterial' + i + '" class="form-control input-sm wajib" name="imaterial' + i + '"></select></td>';
        cols += '<td><input data-nourut="' + i + '" class="form-control qty input-sm" autocomplete="off" type="text" name="ebagian' + i + '" id="ebagian' + i + '" style="text-transform: uppercase"></td>';
        cols += '<td><input class="form-control input-sm" readonly type="text" id="ipanel' + i + '" name="ipanel' + i + '" value="' + ipanel + '"></td>';
        cols += `<td><input type="text" id="n_qty_penyusun${i}" class="form-control text-right input-sm" autocomplete="off" name="n_qty_penyusun${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_panjang_cm${i}" class="form-control text-right input-sm" autocomplete="off" name="n_panjang_cm${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_lebar_cm${i}" class="form-control text-right input-sm" autocomplete="off" name="n_lebar_cm${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_pg_cm${i}" class="form-control text-right input-sm" autocomplete="off" name="n_pg_cm${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_lg_cm${i}" class="form-control text-right input-sm" autocomplete="off" name="n_lg_cm${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_hg_set${i}" class="form-control text-right input-sm" autocomplete="off" name="n_hg_set${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_efficiency${i}" class="form-control text-right input-sm" autocomplete="off" name="n_efficiency${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += '<td class="text-center"><input type="checkbox" id="print' + i + '" name="print' + i + '"></td>';
        cols += '<td class="text-center"><input type="checkbox" id="bordir' + i + '" name="bordir' + i + '"></td>';
        cols += '<td class="text-center" hidden><input type="checkbox" id="f_khusus_pengadaan' + i + '" name="f_khusus_pengadaan' + i + '"></td>';
        cols += '<td><select data-nourut="' + i + '" id="imaterialmakloon' + i + '" class="form-control input-sm" name="imaterialmakloon' + i + '"></select></td>';
        cols += '<td hidden><input class="form-control input-sm" type="text" name="eremark' + i + '" id="eremark' + i + '" placeholder="Isi keterangan jika ada!"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        cols += `</tr>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#imaterial' + i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        idmarker: $('#idmarker').val(),
                        idproduct: $('#idproduct').val(),
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

        $('#imaterialmakloon' + i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        idmarker: $('#idmarker').val(),
                        idproduct: $('#idproduct').val(),
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
        // change(function(event) {
        //     /**
        //      * Cek Barang Sudah Ada
        //      * Get Harga Barang
        //      */
        //     var z = $(this).data('nourut');
        //     var ada = true;
        //     for(var x = 1; x <= $('#jml').val(); x++){
        //         if ($(this).val()!=null) {
        //             if((($(this).val()) == $('#imaterial'+x).val()) && (z!=x)){
        //                 swal ("kode barang tersebut sudah ada !!!!!");
        //                 ada = false;
        //                 break;
        //             }
        //         }
        //     }
        //     if (!ada) {                
        //         $(this).val('');
        //         $(this).html('');
        //     }
        // });
        $('#ebagian' + i).keyup(function event() {
            var id = $(this).data('nourut');
            var ebagian = $("#ebagian" + id).val();
            var imaterial = $("#imaterial" + id).text();
            const myArray = imaterial.split("-");
            if (ebagian == "") {
                $("#ipanel" + id).val(ipanel);
            }
            else {
                // var matches = ebagian.match(/\b(\w)/g);
                // var bagian = matches.join('');
                var upper = ebagian.toUpperCase();
                var ipanel = $("#iproduct").val();
                $("#ipanel" + id).val(ipanel + '_' + myArray[0].trim() + '_' + upper);
            }
        });
        $('#ijenis' + i).select2();
    });

    /**
     * Hapus Detail Item
     */

    $("#tabledatax").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        //$('#jml').val(i);
        del();
    });

    function del() {
        obj = $('#tabledatax tr:visible').find('spanx');
        $.each(obj, function (key, value) {
            id = value.id;
            $('#' + id).html(key + 1);
        });
    }

    function export_data()
    {
        var idproduct = $('#idproduct').val();
        var idmarker = $('#idmarker').val();
        var dfrom = <?= $dfrom; ?>;
        var dto = <?= $dto; ?>;
        if (idproduct == '' || idmarker == '') {
            swal('Product dan Marker Harus Dipilih Terlebih Dahulu!!!');
            return false;
        } else {
            $('#href').attr('href', '<?php echo site_url($folder . '/cform/export/' . $dfrom . '/' . $dto . '/'); ?>' + idproduct + '/' + idmarker);
            return true;
        }
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if (jml == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            $("#tabledatax tbody tr").each(function () {
                $(this).find("td select .wajib").each(function () {
                    if ($(this).val() == '' || $(this).val() == null) {
                        swal('Material tidak boleh kosong!');
                        ada = true;
                    }
                });

                for (i = 1; i <= jml; i++) {
                    var cek = document.getElementById("n_qty_penyusun" + i);
                    var nquantity = document.getElementById("n_qty_penyusun" + i).value;
                    if (cek) {
                        if (nquantity == '' || nquantity == null || nquantity == 0) {
                            swal('Quantity Penyusun Tidak Boleh Kosong atau 0!');
                            ada = true;
                            break;
                        }
                    }
                }

            });
            if (!ada) {
                return true;
            } else {
                return false;
            }
        }

    }
</script>