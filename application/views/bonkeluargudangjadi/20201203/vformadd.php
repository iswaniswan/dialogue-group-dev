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
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-5">Tujuan</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" onchange="return gettujuan(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dsj" name="dsj" class="form-control date"  required="" readonly value="<?= date('d-m-Y');?>">
                        </div>
                        <div class="col-sm-5">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden="true"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> 
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>                           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <textarea type="text" id= "eremark "name="eremark" class="form-control" value=""></textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0" readonly>
                        <div class="panel-body table-responsive">
                            <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" hidden="true">
                                <thead>
                                    <tr>
                                        <th style="text-align:center; width:5%">No</th>
                                        <th style="text-align:center; width:12%">Kode Barang</th>
                                        <th style="text-align:center; width:30%">Nama barang</th>
                                        <th style="text-align:center; width:12%">Warna</th>
                                        <th style="text-align:center; width:8%">Quantity</th>
                                        <th style="text-align:center; width:30%">Keterangan</th>
                                        <th style="text-align:center; width:5%">Action</th>
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
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    $("#send").attr("disabled", true);
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#send").attr("disabled", false);
});

function getenabledsend() {
    $('#send').attr("disabled", true);
    swal("Data Berhasil Dikirim!", {
        buttons: false,
        timer: 1500,
    });
}

$(document).ready(function () {
    $('#ibagian').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
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

function gettujuan(ibagian){
    $("#addrow").attr("hidden", false);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/tujuan');?>",
        data: "ibagian=" + ibagian,
        dataType: 'json',
        success: function (data) {
            $("#itujuan").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
                $('#itujuan').attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $('#itujuan').attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    })
}

var counter = 0;

    $("#addrow").on("click", function () {
        $("#tabledata").attr("hidden", false);
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
                
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
        cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct[]" onchange="getproduct('+ counter + ');"></td>';
        cols += '<td><input type="hidden" readonly id="icolor'+ counter + '" class="form-control" name="icolor[]"><input type="text" id="ecolor'+ counter + '" class="form-control" name="ecolor[]" readonly></td>'; 
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" onkeyup="cekval(this.value); reformat(this); cekstock('+counter+');"><input type="hidden" id="nstock'+ counter + '" class="form-control" name="nstock[]"></td>';                 
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#eproduct'+ counter).select2({
        
        placeholder: 'Pilih Product',
        templateSelection: formatSelection,
        allowClear: true,
        type: "POST",
        ajax: {          
          url: '<?= base_url($folder.'/cform/dataproduct/'); ?>',
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
    });

    function getproduct(id){
        var eproduct = $('#eproduct'+id).val();
        var ibagian  = $('#ibagian').val();
        $.ajax({
        type: "post",
        data: {
            'eproduct': eproduct,
            'ibagian' : ibagian
        },
        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product_motif);
            $('#ecolor'+id).val(data[0].e_color_name);
            $('#icolor'+id).val(data[0].i_color);

            ada=false;
            var a = $('#iproduct'+id).val();
            var e = $('#eproduct'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#iproduct'+i).val()) && (i!=id)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                $('#iproduct'+id).val(data[0].i_product_motif);
                $('#ecolor'+id).val(data[0].e_color_name);
                $('#icolor'+id).val(data[0].i_color);
                $('#nstock'+id).val(data[0].n_quantity_stock);
            }else{
                $('#iproduct'+id).html('');
                $('#iproduct'+id).val('');
                $('#eproduct'+id).val('');
                $('#eproduct'+id).html('');
                $('#ecolor'+id).val('');
                $('#ecolor'+id).html('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function cekstock(id){
        var qty   = $('#nquantity'+id).val();
        var stock = $('#nstock'+id).val();
        //alert(stock);

        if(qty > stock){
            swal("Quantity kirim melebihi stock. Stock = "+stock);
        }
    }

    function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        
    }else{
      swal('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }

  function cek() {
        var ibagian = $('#ibagian').val();
        var itujuan = $('#itujuan').val();
        var dsj     = $('#dsj').val();

        var jml = $('#jml').val();
        for(i=0;i<=jml;i++){
            var nqty = $('#nquantity'+i).val();
            if (ibagian =='' || ibagian == null || itujuan =='' || itujuan == null) {
                swal('Data Header Belum Lengkap !!');
                return false;
            }else if(nqty == '0'){
                swal('Quantity tidak boleh 0 !!');
                return false;
            }else {
                return true;
            }
        }
    }

    $(document).ready(function(){
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
</script>
