<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                <option value="<?= $row->id;?>">
                                    <?= $row->e_bagian_name;?>
                                </option>
                                <?php endforeach; 
                                    } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="i_document_retur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BBM-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span id="notekode" class="notekode">Format : (<?= $number;?>)</span><br>
                            <span id="notekode" class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?=date('d-m-Y');?>"readonly >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2"><i class="fa fa-save mr-2" ></i>Simpan</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col">
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail" hidden="true">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="12%;">Kode Barang</th>
                        <th class="text-center" width="25%;">Nama Barang</th>
                        <th class="text-center" width="12%;">Quantity Kirim</th>
                        <th class="text-center" width="12%;">Quantity Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    // $('#i_document_retur').mask('SSS-0000-000000S');
    //memanggil function untuk penomoran dokumen
    number();
});

$('#ibagian, #ddocument').change(function (event) {
    // $('#ipengirim').val(null).trigger('change');
    number();
});

const mergeLabel = (data) => {
    let _data = data.reduce((result, item) => {
        if (result[item.name]) {
            result[item.name].children.forEach((e) => {
                if (e.id !== item.id) {
                    return result[item.name].children.push({
                        id:item.id, text:item.text
                    });
                }
            })
            return result;
        }

        (result[item.name] ??= { text:item.name, children: [] }).children.push({
            id:item.id, text:item.text
        })
        
        return result;
    }, {});

    console.log(Object.values(_data));
    return Object.values(_data);
}

$(document).ready(function () {
    $('#ipengirim').select2({
        placeholder: 'Pilih Bagian Pengirim',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/bagianpengirim'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                    ibagian: $('#ibagian').val()
                }
                return query;
            },
            processResults: function (result) {
                const data = mergeLabel(result);
                return {
                    results: data
                };
            },
            cache: false
        }
    }).change(function (event) {
        $("#tabledatax tr:gt(0)").remove();
        $("#jml").val(0);
        $("#ireff").val("");
        $("#ireff").html("");
    });

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
                    iasal: $('#ipengirim').val(),
                    itujuan: $('#ibagian').val()
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
    }).change(function (event) {
        $("#tabledatax tr:gt(0)").remove();
        $("#jml").val(0);
    });
});

//untuk me-generate running number
function number() {
    $.ajax({
        type: "post",
        data: {
            'tgl': $('#ddocument').val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/generate_nomor_dokumen'); ?>',
        dataType: "json",
        success: function (data) {
            $('#i_document_retur').val(data);
        },
        error: function () {
            swal('Error :)');
        }
    });
}

$("#i_document_retur").keyup(function () {
    $.ajax({
        type: "post",
        data: {
            'kode': $(this).val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function (data) {
            if (data == 1) {
                $(".notekode").attr("hidden", false);
                $("#submit").attr("disabled", true);
            } else {
                $(".notekode").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
});

$("#ipengirim").change(function () {
    $('#ireff').attr("disabled", false);
});

$('#ceklis').click(function (event) {
    if ($('#ceklis').is(':checked')) {
        $("#i_document_retur").attr("readonly", false);
    } else {
        $("#i_document_retur").attr("readonly", true);
    }
});

$('#send').click(function (event) {
    statuschange('<?= $folder;?>', $('#id').val(), '2', '<?= $dfrom."', '".$dto;?>');
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("hidden", false);
});

function getdataitem(ireff) {
    var idreff = $('#ireff').val();
    var ipengirim = $('#ipengirim').val();
    $.ajax({
        type: "post",
        data: {
            'idreff': idreff,
            'ipengirim': ipengirim,
        },
        url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
        dataType: "json",
        success: function (data) {
            $('#jml').val(data['jmlitem']);
            $("#tabledatax tbody").remove();
            $("#detail").attr("hidden", false);

            var dref = data['datahead']['d_document'];
            $("#dreferensi").val(dref);
            if (data['dataitem'].length > 0) {
                for (let a = 0; a < data['jmlitem']; a++) {
                    var no = a + 1;
                    var idproduct = data['dataitem'][a]['id_product'];
                    var iproduct = data['dataitem'][a]['i_product_base'];
                    var eproduct = data['dataitem'][a]['e_product_basename'];
                    var nquantity = data['dataitem'][a]['n_quantity'];
                    var nquantitysisa = data['dataitem'][a]['n_sisa_retur'];
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align:center;">' + no + '<input readonly type="hidden" id="baris' + a + '" name="baris' + a + '" value="' + no + '"></td>';
                    cols += '<td><input readonly class="form-control input-sm" type="text" id="iproduct' + a + '" name="iproduct[]" value="' + iproduct + '"><input readonly type="hidden" id="idproduct' + a + '" name="idproduct[]" value="' + idproduct + '"></td>';
                    cols += '<td><input readonly class="form-control input-sm" type="text" id="eproduct' + a + '" name="eproduct' + a + '" value="' + eproduct + '"></td>';
                    cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantity' + a + '" name="nquantity[]" value="' + nquantity + '"></td>';
                    cols += '<td><input class="form-control input-sm inputitem text-right" type="text" id="nquantitymasuk' + a + '" name="nquantitymasuk[]" value="0" autocomplete="off" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'  onkeyup="validasi(' + a + ')"></td>';
                    cols += '<td><input class="form-control input-sm" type="text" id="edesc' + a + '" name="edesc[]" value="" placeholder="Isi keterangan jika ada!"></td>';

                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
            }
            max();
            //var a = $('#jml').val();

            function formatSelection(val) {
                return val.name;
            }

            $("#tabledatax").on("click", ".ibtnDel", function (event) {
                $(this).closest("tr").remove();
            });

        },
        error: function () {
            alert('Error :)');
        }
    });
}

function validasi(i) {
    nquantityma = document.getElementById("nquantity" + i).value;
    nquantitymasuk = document.getElementById("nquantitymasuk" + i).value;
    if (parseFloat(nquantitymasuk) > parseFloat(nquantityma)) {
        swal('Quantity Retur Tidak Boleh Lebih Dari Quantity');
        document.getElementById("nquantitymasuk" + i).value = nquantityma;
    }
}

function max() {
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
});

$("#submit").click(function (event) {
    ada = false;
    if ($('#jml').val() == 0) {
        swal('Isi item minimal 1!');
        return false;
    } else {
        let qty = 0;
        $("#tabledatax tbody tr").each(function () {
            $(this).find("td .inputitem").each(function () {
                if ($(this).val() != '' || $(this).val() != null) {
                    qty += parseFloat($(this).val());
                }
            });
        });
        if (qty>0) {
            return true;
        } else {
            swal('Quantity Tidak Boleh Kosong Atau 0 Semua!');
            return false;
        }
    }
})
</script>