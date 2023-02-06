<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
   <div class="col-lg-12">
      <div class="panel panel-info">
         <div class="panel-heading">
            <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
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
                        <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                     </select>
                     <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                  </div>
                  <div class="col-sm-4">
                     <div class="input-group">
                        <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BONM-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                        <span class="input-group-addon">
                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                        </span>
                     </div>
                     <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                     <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                  </div>
                  <div class="col-sm-4">
                     <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-md-4">Pengirim</label>
                  <label class="col-md-4">Nomor Referensi</label>
                  <label class="col-md-4">Tanggal Referensi</label>
                  <div class="col-sm-4">
                     <select name="ipengirim" id="ipengirim" class="form-control select2">
                        <option value="<?= $data->id_bagian_pengirim; ?>">
                            <?= $data->e_bagian_pengirim; ?>
                        </option>
                     </select>
                  </div>
                  <div class="col-sm-4">
                     <select name="ireff" id="ireff" class="form-control select2">
                        <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                     </select>
                  </div>
                  <div class="col-sm-4">
                     <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" readonly>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-md-12">Keterangan</label>
                  <div class="col-sm-12">
                     <textarea id= "eremark" name="eremark" class="form-control"><?= $data->e_remark; ?></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <div class="col-sm-12">
                     <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                     <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2"><i class="fa fa-save mr-2" ></i>Update</button>
                     <?php } ?>
                     <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                     <?php if ($data->i_status == '1') {?>
                     <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                     <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                     <?php }elseif($data->i_status=='2') {?>
                     <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                     <?php } ?>
                  </div>
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
   </div>
   <div class="col-sm-12">
      <div class="table-responsive">
         <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
            <thead>
               <tr>
                  <th class="text-center" width="3%;">No</th>
                  <th class="text-center" width="12%;">Kode Barang</th>
                  <th class="text-center" width="25%;">Nama Barang</th>
                  <th class="text-center" width="12%;">Quantity</th>
                  <th class="text-center" width="12%;">Quantity Retur</th>
                  <th class="text-center">Keterangan</th>
               </tr>
            </thead>
            <tbody>
               <?php
                  if($datadetail){
                  $i = 0;
                  $lastproduct = ''; 
                  foreach($datadetail as $row){
                      $i++;                             
                  ?>
               <tr>
                  <td class="text-center"><?= $i;?>
                     <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                  </td>
                  <td>  
                     <input type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]"value="<?= $row->id_product; ?>" readonly>
                     <input type="text" class="form-control input-sm" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product_base; ?>" readonly>
                  </td>
                  <td>
                     <input type="text" class="form-control input-sm" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                  </td>
                  <td>
                     <input type="text" class="form-control input-sm text-right" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_quantity_reff; ?>" readonly> 
                  </td>
                  <td>
                     <input type="text" class="form-control input-sm text-right inputitem" id="nquantitymasuk<?=$i;?>" name="nquantitymasuk[]" value="<?= $row->n_quantity; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' onkeyup="validasi(<?=$i;?>);">
                  </td>
                  <td>
                     <input type="text" class="form-control input-sm" id="edesc<?=$i;?>" name="edesc[]"value="<?=$row->e_remark;?>">
                  </td>
               </tr>
               <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
               <?php }
                  }?>        
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
    max();
    showCalendar('.date');

    $('#idocument').mask('SSS-0000-000000S');
    //memanggil function untuk penomoran dokumen
    //number();
    // $('#ipengirim').select2({
    //     placeholder: 'Pilih Bagian Pengirim',
    //     allowClear: false,
        // ajax: {
        //     url: '<?= base_url($folder.' / cform / bagianpengirim '); ?>',
        //     dataType: 'json',
        //     delay: 250,
        //     data: function (params) {
        //         var query = {
        //             q: params.term,
        //         }
        //         return query;
        //     },
        //     processResults: function (data) {
        //         return {
        //             results: data
        //         };
        //     },
        //     cache: false
        // }
    // }).change(function (event) {
    //     $("#tabledatax tr:gt(0)").remove();
    //     $("#jml").val(0);
    //     $("#ireff").val("");
    //     $("#ireff").html("");
    // });

    $('#ipengirim').select2({
        placeholder: 'Pilih Bagian Pengirim',
        allowClear: false,
    })

    $('#ireff').select2({
        placeholder: 'Pilih Referensi',
        allowClear: false,
    })

    // $('#ireff').select2({
    //     placeholder: 'Pilih Referensi',
    //     allowClear: false,
        // ajax: {
        //     url: '<?= base_url($folder.' / cform / referensi '); ?>',
        //     dataType: 'json',
        //     delay: 250,
        //     data: function (params) {
        //         var query = {
        //             q: params.term,
        //             iasal: $('#ipengirim').val(),
        //         }
        //         return query;
        //     },
        //     processResults: function (data) {
        //         return {
        //             results: data
        //         };
        //     },
        //     cache: false
        // }
    // }).change(function (event) {
    //     $("#tabledatax tr:gt(0)").remove();
    //     $("#jml").val(0);
    // });
});

$("#idocument").keyup(function () {
    $.ajax({
        type: "post",
        data: {
            'kode': $(this).val(),
            'ibagian': $("#ibagian").val(),
        },
        url: '<?= base_url($folder.' / cform / cekkode '); ?>',
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
        $("#idocument").attr("readonly", false);
    } else {
        $("#idocument").attr("readonly", true);
    }
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$('#send').click(function (event) {
    statuschange('<?= $folder;?>', $('#id').val(), '2', '<?= $dfrom."', '".$dto;?>');
});
$('#cancel').click(function (event) {
    statuschange('<?= $folder;?>', $('#id').val(), '1', '<?= $dfrom."', '".$dto;?>');
});
$('#hapus').click(function (event) {
    statuschange('<?= $folder;?>', $('#id').val(), '5', '<?= $dfrom."', '".$dto;?>');
});

//untuk me-generate running number
function number() {
    $.ajax({
        type: "post",
        data: {
            'tgl': $('#ddocument').val(),
            'ibagian': $('#ibagian').val(),
        },
        url: '<?= base_url($folder.' / cform / number '); ?>',
        dataType: "json",
        success: function (data) {
            $('#idocument').val(data);
        },
        error: function () {
            swal('Error :)');
        }
    });
}

function getdataitem(ireff) {
    var idreff = $('#ireff').val();
    var ipengirim = $('#ipengirim').val();
    $.ajax({
        type: "post",
        data: {
            'idreff': idreff,
            'ipengirim': ipengirim,
        },
        url: '<?= base_url($folder.' / cform / getdataitem '); ?>',
        dataType: "json",
        success: function (data) {
            $('#jml').val(data['jmlitem']);
            $("#tabledatax tbody").remove();
            $("#detail").attr("hidden", false);

            var dref = data['datahead']['d_document'];
            $("#dreferensi").val(dref);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a + 1;
                var idproduct = data['dataitem'][a]['id_product'];
                var iproduct = data['dataitem'][a]['i_product_base'];
                var eproduct = data['dataitem'][a]['e_product_basename'];
                var nquantity = data['dataitem'][a]['n_quantity_product'];
                var nquantitysisa = data['dataitem'][a]['n_sisa'];
                var idcolor = data['dataitem'][a]['id_color'];
                var icolor = data['dataitem'][a]['i_color'];
                var ecolor = data['dataitem'][a]['e_color_name'];

                var cols = "";
                var newRow = $("<tr>");
                cols += '<td style="text-align:center;">' + no + '<input class="form-control" readonly type="hidden" id="baris' + a + '" name="baris' + a + '" value="' + no + '"></td>';
                cols += '<td><input readonly style="width:200px;" class="form-control" type="text" id="iproduct' + a + '" name="iproduct[]" value="' + iproduct + '"><input readonly style="width:100px;" class="form-control" type="hidden" id="idproduct' + a + '" name="idproduct[]" value="' + idproduct + '"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct' + a + '" name="eproduct' + a + '" value="' + eproduct + '"></td>';
                cols += '<td><input style="width:40px;" type="hidden" id="idcolor' + a + '" name="idcolor[]" value="' + idcolor + '"><input style="width:40px;" type="hidden" id="icolorpro' + a + '" name="icolorpro[]" value="' + icolor + '"><input style="width:120px;" class="form-control" type="text" id="ecolor' + a + '" readonly name="ecolor' + a + '" value="' + ecolor + '"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="nquantity' + a + '" name="nquantity[]" value="' + nquantity + '"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="nquantitysisa' + a + '" name="nquantitysisa[]" value="' + nquantitysisa + '"></td>';
                cols += '<td><input style="width:100px;" class="form-control inputitem" type="text" id="nquantitymasuk' + a + '" name="nquantitymasuk[]" value="' + nquantitysisa + '" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'  onkeyup="validasi(' + a + ')"></td>';
                cols += '<td><input style="width:500px;" class="form-control" type="text" id="edesc' + a + '" name="edesc[]" value="" placeholder="Isi keterangan jika ada!"></td>';

                newRow.append(cols);
                $("#tabledatax").append(newRow);
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
        $("#tabledatax tbody tr").each(function () {
            $(this).find("td .inputitem").each(function () {
                if ($(this).val() == '' || $(this).val() == null || $(this).val() == 0) {
                    swal('Quantity Tidak Boleh Kosong Atau 0!');
                    ada = true;
                }
            });
        });
        if (!ada) {
            return true;
        } else {
            return false;
        }
    }
})
</script>