<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-4">Gudang Pembuat</label>
                        <label class="col-md-5">No Referensi</label>
                        <label class="col-md-3">Tanggal Bon Masuk</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                <option selected="selected">-- Pilih Gudang Pembuat--</option>
                                <?php foreach ($bagianpembuat as $ibagian):?>
                                    <option value="<?php echo $ibagian->i_departement;?>"><?= $ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                        <div class="col-sm-5">
                            <select required="" id="ibonmk" name="ibonmk" class="form-control" disabled="" onchange="getdetailbonmk();max_tgl(this.value);"></select>
                            <input type="hidden" id="dbonk" name="dbonk" class="form-control" value="">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonm" name="dbonm" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control"></textarea>
                        </div>
                    </div>         
                </div>                              
                <input type="hidden" name="jml" id="jml" value ="0">
                <div class="panel-body table-responsive">
                    <table id="tabledata" hidden="true" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center;" width="5%">No</th>
                                <th style="text-align: center;" width="10%">Kode Barang</th>
                                <th style="text-align: center;" width="35%">Nama Barang</th>
                                <th style="text-align: center;" width="14%">Warna</th>
                                <th style="text-align: center;" width="8%">Quantity</th>
                                <th style="text-align: center;" width="15%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("disabled", false);
});

function getstore() {
        var gudang = $('#ikodemaster').val();
        if (gudang == "") {
            $("#ibonmk").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#ibonmk").attr("disabled", false);
        }
        
        $('#ibonmk').html('');
        $('#ibonmk').val('');
}

function max_tgl(val) {
  $('#dbonm').datepicker('destroy');
  $('#dbonm').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dbonk').value,
  });
}

$('#dbonm').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dbonk').value,
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $('#ikodemaster').select2({
        placeholder: 'Pilih Gudang Pembuat',
    });

    $('#ibonmk').select2({
        placeholder: 'Cari No. Referensi',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getbonmk/'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var gudang           = $('#istore').val();
                var query = {
                    q: params.term,
                    gudang: gudang
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
    });

    $("#send").attr("disabled", true);
    $("#send").on("click", function () {
        var kode = $("#kode").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    swal('Berhasil Di Send');
}

function getdetailbonmk() {
    $("#tabledata").attr("hidden", false);
    var ibonmk = $('#ibonmk').val();
    var gudang = $('#istore').val();
    $.ajax({
        type: "post",
        data: {
            'ibonmk': ibonmk,
            'gudang': gudang
        },
        url: '<?= base_url($folder.'/cform/getdetailbonmk'); ?>',
        dataType: "json",
        success: function (data) {
            $('#jml').val(data['detail'].length);
            $('#dbonk').val(data['head']['d_bonk']);
            var gudang = $('#istore').val();
            for (let a = 0; a < data['detail'].length; a++) {
                var zz = a+1;
                var iproduct        = data['detail'][a]['i_product'];
                var eproduct        = data['detail'][a]['e_product_basename'];
                var icolor          = data['detail'][a]['i_color'];
                var ecolor          = data['detail'][a]['e_color_name'];
                var nquantitykeluar = data['detail'][a]['n_quantity'];

                var cols        = "";
                var newRow = $("<tr>");
                cols += '<td style="text-align:center;">'+zz+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+zz+'"></td>'; 
                cols += '<td><input class="form-control" readonly id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+iproduct+'"></td>';
                cols += '<td><input class="form-control" readonly id="eproduct'+zz+'" name="eproduct'+zz+'" value="'+eproduct+'"></td>';
                cols += '<td><input type="hidden" class="form-control" id="icolor'+zz+'" name="icolor'+zz+'" value="'+icolor+'"><input readonly class="form-control" id="ecolor'+zz+'" name="ecolor'+zz+'" value="'+ecolor+'"></td>';
                cols += '<td><input class="form-control" id="nquantitymasuk'+zz+'" name="nquantitymasuk'+zz+'" value=""  placeholder="0"  onkeyup="valid(this.value);"><input type="hidden" class="form-control" readonly id="nquantitykeluar'+zz+'" name="nquantitykeluar'+zz+'" value="'+nquantitykeluar+'"></td>';               
                cols += '<td><input type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                newRow.append(cols);
                $("#tabledata").append(newRow);  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}

$("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
    // counter -= 1
    // document.getElementById("jml").value = counter;
});

function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}    

function cek() {
    var dbonm = $('#dbonm').val();
    var jml = $('#jml').val();

    var qty1 = 0;
    var qty2 = 0;

    var qty = []; 

    if (dbonm == '') {
        alert('Data Header Belum Lengkap !!');
        return false;
    } else {
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            qty1 = parseInt($('#nquantitykeluar'+i).val());
            qty2 = parseInt($('#nquantitymasuk'+i).val());

            if (qty2 > qty1) {
                qty.push("lebih");
            } else {
                qty.push("ok");
            }
            jumlah = jumlah + qty2;
        }
        var found = qty.find(element => element == "lebih");
             
        if (found == "lebih") {
            alert("Jumlah Barang Masuk Melebihi Jumlah Sisa Barang Keluar");
            return false;
        } else if (jumlah == 0) {
            alert("Barang Masuk Harus Di Isi");
            return false;
        } else {
            return true;
        }
    }
}

function valid(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var qtykeluar = $('#nquantitykeluar'+i).val();
        var qtymasuk = $('#nquantitymasuk'+i).val();
        if(parseFloat(qtykeluar)<parseFloat(qtymasuk) ){
            swal("quantity lebih")
            $('#nquantitymasuk'+i).val('');
        }
    }
}
</script>