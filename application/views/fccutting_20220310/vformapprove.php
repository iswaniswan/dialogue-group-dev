<style type="text/css">
.select2-results__options {
    font-size: 14px !important;
}

.select2-selection__rendered {
    font-size: 12px;
}

.pudding {
    padding-left: 3px;
    padding-right: 3px;
    font-size: 14px;
    background-color: #e1f1e4;
}

.font-11{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 11px;  
    height: 20px;  
}
.font-12{
    padding-left: 3px;
    padding-right: 3px;
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
                        class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp;
                        <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-3">Tanggal Dokumen</label>
                            <label class="col-md-3">Periode Forecast Produksi</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                    <?php if ($bagian) {
                                        foreach ($bagian as $row):?>
                                            <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                                <?= $row->e_bagian_name;?>
                                            </option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                                <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                    <input type="hidden" name="idocumentold" id="ifccuttingold" value="<?= $data->i_document;?>">
                                    <input type="text" name="idocument" required="" id="ifccutting" readonly=""
                                        autocomplete="off" onkeyup="gede(this);" 
                                        maxlength="25" class="form-control input-sm" value="<?= $data->i_document;?>"
                                        aria-label="Text input with dropdown button">
                                </div>
                                <span class="notekode">Format : (<?= $number;?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date"
                                    required="" readonly value="<?= $data->d_document;?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="hidden" id="idforecast" name="idforecast" required="" value="<?= $data->id_referensi; ?>">
                                <input type="hidden" id="iperiode" name="iperiode" required="" value="<?= $data->tahun.$data->bulan; ?>">
                                <input type="text" class="form-control input-sm" readonly
                                    value="<?= $this->fungsi->mbulan($data->bulan).' '.$data->tahun; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremark" name="eremark" class="form-control"
                                    placeholder="Isi keterangan jika ada!" readonly><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                               <div class="col-sm-12">
                                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                                    <button type="button" class="btn btn-warning btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','3','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;Change Requested</button>&nbsp;
                                    <button type="button" class="btn btn-danger btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times"></i>&nbsp;&nbsp;Reject</button>&nbsp;
                                    <button type="button" class="btn btn-success btn-rounded btn-sm" onclick="statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
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
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Item</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8"
                    cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th width="10%;">Kode Material</th>
                            <th width="20%;">Nama Material</th>
                            <th class="text-right" width="7%;">Panjang Gelar</th>
                            <th class="text-right" width="7%;">Set</th>
                            <th class="text-right" width="7%;">Jumlah Gelar</th>
                            <th class="text-right" width="8%;">Panjang Kain (m)</th>
                            <th class="text-right" width="13%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; $group = ""; foreach ($datadetail as $key) { 
                            $i++; $no++; 
                            if($group==""){ ?>
                        <tr class="pudding">
                            <td colspan="6">Barang WIP : <b><?= $key->i_product_wip;?> &nbsp;<?= ucwords(strtolower($key->e_product_basename));?>&nbsp;<?= ucwords(strtolower($key->e_color_name));?></b></td>
                            <td class="text-right">Qty : <b><?= $key->n_quantity;?></b></td>
                            <td></td>
                        </tr>
                        <?php 
                            }else{
                                if($group!=$key->id_product_base){?>
                        <tr class="pudding">
                            <td colspan="6">Barang WIP : <b><?= $key->i_product_wip;?> &nbsp;<?= ucwords(strtolower($key->e_product_basename));?>&nbsp;<?= ucwords(strtolower($key->e_color_name));?></b></td>
                            <td class="text-right">Qty : <b><?= $key->n_quantity;?></b></td>
                            <td></td>
                        </tr>
                        <?php $no = 1; 
                                }
                            }
                            $group = $key->id_product_base;
                            ?>
                        <tr>
                            <td class="text-center"><?=$no;?></td>
                            <td><?= $key->i_material;?></td>
                            <td><?= ucwords(strtolower($key->e_material_name));?></td>
                            <td class="text-right"><?= number_format($key->v_gelar);?></td>
                            <td class="text-right"><?= number_format($key->v_set);?></td>
                            <td class="text-right"><?= number_format($key->jml_gelar);?></td>
                            <td class="text-right"><?= number_format($key->p_kain);?></td>  
                            <td><input type="text" class="form-control input-sm" name="e_remark<?=$i;?>" value="<?= $key->e_remark;?>" placeholder="Keterangan" readonly></td>

                            <input type="hidden" name="id_product_base<?=$i;?>" value="<?= $key->id_product_base;?>">
                            <input type="hidden" name="nilai_base<?=$i;?>" value="<?= $key->n_quantity;?>">
                            <input type="hidden" name="id_material<?=$i;?>" value="<?= $key->id_material;?>">
                            <input type="hidden" name="v_gelar<?=$i;?>" value="<?= $key->v_gelar;?>">
                            <input type="hidden" name="v_set<?=$i;?>" value="<?= $key->v_set;?>">
                            <input type="hidden" name="jml_gelar<?=$i;?>" value="<?= $key->jml_gelar;?>">
                            <input type="hidden" name="p_kain<?=$i;?>" value="<?= $key->p_kain;?>">
                        </tr>
                        <?php 
                        } ?>
                    </tbody>
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
    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">

</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
/*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/
$(document).ready(function() {
    hetang();
    /*----------  Load Form Validation  ----------*/
    $('#cekinputan').validate({
        errorClass: "my-error-class",
        validClass: "my-valid-class"
    });

    $('#ifccutting').mask('SS-0000-000000S');
    $('.select2').select2();
    /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
    showCalendar('.date', 0);
});

/*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/
$('#ibagian, #ddocument').change(function(event) {
    number();
});

/*----------  RUNNING NUMBER DOKUMEN  ----------*/
function number() {
    if (($('#ibagian').val() == $('#ibagianold').val())) {
        $('#ifccutting').val($('#ifccuttingold').val());
    } else {
        $.ajax({
            type: "post",
            data: {
                'tgl': $('#ddocument').val(),
                'ibagian': $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function(data) {
                $('#ifccutting').val(data);
            },
            error: function() {
                swal('Error :)');
            }
        });
    }
}

/*----------  UPDATE STATUS DOKUMEN  ----------*/
$('#send').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '2', '<?= $dfrom."','".$dto;?>');
});

$('#cancel').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '1', '<?= $dfrom."','".$dto;?>');
});

$('#hapus').click(function(event) {
    statuschange('<?= $folder;?>', $('#id').val(), '5', '<?= $dfrom."','".$dto;?>');
});

/*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/
$('#ceklis').click(function(event) {
    if ($('#ceklis').is(':checked')) {
        $("#ifccutting").attr("readonly", false);
    } else {
        $("#ifccutting").attr("readonly", true);
        $("#ada").attr("hidden", true);
        number();
    }
});

/*----------  CEK NO DOKUMEN  ----------*/
$("#ifccutting").keyup(function() {
    $.ajax({
        type: "post",
        data: {
            'kode': $(this).val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function(data) {
            if (data == 1 && ($('#ifccutting').val() != $('#ifccuttingold').val())) {
                $("#ada").attr("hidden", false);
                $("#submit").attr("disabled", true);
            } else {
                $("#ada").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function() {
            swal('Error :)');
        }
    });
});

function hetang(i){
    let nilai_mutasi = parseFloat($('#nilai_mutasi'+i).val());
    let nilai_estimasi = parseFloat($('#nilai_estimasi'+i).val());
    let nilai_kebutuhan = parseFloat($('#nilai_kebutuhan'+i).val());
    let nilai_op_sisa = parseFloat($('#nilai_op_sisa'+i).val());

    let stock_estimasi = nilai_mutasi - nilai_estimasi;
    if (stock_estimasi < 0) {
        stock_estimasi = 0;
    }
    let budgeting = Math.abs(stock_estimasi) - Math.abs(nilai_kebutuhan) + Math.abs(nilai_op_sisa);
    
    // let budgeting = Math.abs(nilai_mutasi) - Math.abs(nilai_estimasi) - Math.abs(nilai_kebutuhan) - Math.abs(nilai_op_sisa);
    let up = budgeting * (parseFloat($('#up'+i).val())/100);
    $('#nilai_budgeting'+i).val(Math.round( (Math.abs(budgeting)+Math.abs(up)) *1000)/1000);
}

/*----------  VALIDASI UPDATE DATA  ----------*/
$("#submit").click(function(event) {
    var valid = $("#cekinputan").valid();
    if (valid) {
        ada = false;
        if ($('#jml').val() == 0) {
            swal('Isi item minimal 1!');
            return false;
        } else {
            // for (var i = 1; i <= $('#jml_item').val(); i++) {
            //     if (parseInt($('#nilai_budgeting' + i).val()) == 0 || parseInt($('#nilai_budgeting' + i)
            //     .val()) == null) {
            //         swal("Maaf :(", "Nilai Budgeting harus lebih besar dari 0!", "error");
            //         ada = true;
            //         return false;
            //     }
            // }
            // if (!ada) {
                swal({
                    title: "Update Data Ini?",
                    text: "Anda Dapat Membatalkannya Nanti",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonColor: 'LightSeaGreen',
                    confirmButtonText: "Ya, Update!",
                    closeOnConfirm: false
                }, function() {
                    $.ajax({
                        type: "POST",
                        data: $("form").serialize(),
                        url: '<?= base_url($folder.'/cform/update/'); ?>',
                        dataType: "json",
                        success: function(data) {
                            if (data.sukses == true) {
                                swal("Sukses!", "No Dokumen : " + data.kode +
                                    ", Berhasil Diupdate :)", "success");
                                $("input").attr("disabled", true);
                                //$("select").attr("disabled", true);
                                $("#submit").attr("disabled", true);
                                $("#addrow").attr("disabled", true);
                                $("#send").attr("hidden", false);
                            } else if (data.sukses == 'ada') {
                                swal("Maaf :(", "No Dokumen : " + data.kode +
                                    ", Sudah Ada :(", "error");
                            } else {
                                swal("Maaf :(", "No Dokumen : " + data.kode +
                                    ", Gagal Diupdate :(", "error");
                            }
                        },
                        error: function() {
                            swal("Maaf", "Data Gagal Diupdate :(", "error");
                        }
                    });
                });
            // } else {
            //     swal('Maaf :(', 'Total Jumlah Retur harus lebih besar dari 0 !', 'error');
            //     return false;
            // }
        }
    }
    return false;
})
</script>
