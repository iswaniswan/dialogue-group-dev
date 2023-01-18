<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Bagian Pembuat</label>
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2">
                                <option value="">-- Pilih Bagian --</option>
                                <?php foreach ($area as $ikodemaster):?>
                                <option value="<?php echo $ikodemaster->i_sub_bagian;?>">
                                    <?= $ikodemaster->i_sub_bagian." - ".$ikodemaster->e_sub_bagian;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Dokumen Dari Makloon</label>
                        <label class="col-md-4">Tanggal SJ Masuk</label>
                        <div class="col-sm-6">
                            <input type="text" id="nodok "name="nodok" class="form-control" maxlength="30" value="">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dmasuk" name="dmasuk" class="form-control date"  required="" readonly value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i
                                    class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"> 
                                    <!-- disabled="" -->
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                            
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml" readonly>
                    
                            <!-- <div class="panel-body table-responsive"> -->
                                <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%" >
                                    <thead>
                                        <tr>
                                            <th width="3%">No</th>
                                            <th>SJ Keluar</th>
                                            <th>Kode Barang WIP</th>
                                            <th>Nama Barang</th>
                                            <th>Warna</th>
                                            <th>Kode Barang Jadi</th>
                                            <th>Qty Belum Kembali</th>
                                            <th>Qty Masuk</th>
                                            <th>Keterangan</th>
                                            <th>Pilih</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                           <!--  </div> -->
                            </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
$(document).ready(function(){
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
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    swal('Berhasil Di Send');
}

function cek() {
    var tujuan = $('#ikodemaster').val();
    var dmasuk = $('#dmasuk').val();

    if (tujuan == "" && dmasuk == "") {
        swal("Data Header Belum Lengkap");
        return false;
    } else {
        return true;
    }
}
function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        for(j=1;j<=jml;j++){
           if(document.getElementById("nquantity"+j).value=='')
              document.getElementById("nquantity"+j).value='0';
             var jml    = counter;
             var totdis    = 0;
             var totnil = 0;
             var hrg    = 0;
             var ndis1  = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
             var ndis2  = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
             var ndis3  = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
             
             var vdis1  = 0;
             var vdis2  = 0;
             var vdis3  = 0;
             for(i=1;i<=jml;i++){
            document.getElementById("ndeliver"+i).value=document.getElementById("nquantity"+i).value;
                vprod=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                nquan=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
               var hrgtmp  = vprod*nquan;
                hrg        = hrg+hrgtmp;
             }
             
             vdis1=vdis1+((hrg*ndis1)/100);
             vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
             vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
             vdistot = vdis1+vdis2+vdis3;
             vhrgreal= hrg-vdistot;
             
             document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
             
             document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
             
             document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
             document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
             document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
             document.getElementById("vttbgross").value=formatcemua(hrg);
          }
    }else{
        alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }
    var counter = 0;
    // <th>SJ Keluar</th>
    // <th>Nama Barang (WIP)</th>
    // <th>Warna</th>
    // <th>Barang Jadi</th>
    // <th>Qty Belum Kembali</th>
    // <th>Qty Masuk</th>
    // <th>Keterangan</th>
    // <th>Pilih</th> 
    $("#addrow").on("click", function () {
        var counter = $('#jml').val();
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");        
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value=""></td>';
        cols += '<td><select style="width:350px;" type="text" id="ereff'+ counter + '" class="form-control" name="ereff[]" onchange="getreff('+ counter + ');"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]" value=""></td>';
        cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]" value=""></td>';
        cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]" /></td>';

        cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
        cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value=""/></td>';
        cols += '<td><input style="width:100px;"type="text" id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="0" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this);validasi('+counter+'); "/></td>';        
        cols += '<td><input style="width:400px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ereff'+ counter).select2({
            placeholder: 'Pilih WIP',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {          
              url: '<?= base_url($folder.'/cform/datareff/'); ?>',
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

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    
    function getenabled(kode) {
        $('#addrow').attr("disabled", false);
        $('#ikodemaster').attr("disabled", true);
    }
    
    function getreff(id){
            var ereff = $('#ereff'+id).val();

            // var fields = ewip.split('|');
            // var iwip = fields[0];
            // var icolor = fields[1];
            $.ajax({
            type: "post",
            data: {
                'ereff': ereff
            },
            url: '<?= base_url($folder.'/cform/getreff'); ?>',
            dataType: "json",
            success: function (data) {
                var ireff = data['head']['i_sj'];
                //swal(i_product+ e_color_name+ i_color);
                $('#ireff'+id).val(ireff);
                
                ada=false;
                var a = $('#ireff'+id).val();
                var jml = $('#jml').val();
                for(i=1;i<=jml;i++){
                    if((a == $('#ireff'+i).val()) && (i!=id)){
                        swal("Nomor Refferensi SJ : "+a+" sudah ada !!!!!");
                        ada=true;
                        break;
                    }else{
                        ada=false;     
                    }
                }

                if(!ada){
                    $('#ireff'+id).val(ireff);
                    $('#ereff'+id).attr("disabled", true);
                    var counter = $('#jml').val();
                    var jmldetail = data['detail'].length;
                    $('#jml').val((jml-1)+jmldetail);
                    for (let a = 0; a < data['detail'].length; a++) {
                        var zz = a+1;
                        var i_sj          = data['detail'][a]['i_sj'];                    
                        var i_wip           = data['detail'][a]['i_wip'];
                        var i_color         = data['detail'][a]['i_color'];
                        var i_product       = data['detail'][a]['i_product'];
                        var e_namabrg       = data['detail'][a]['e_namabrg'];
                        var e_color_name    = data['detail'][a]['e_color_name'];
                        var n_sisa          = data['detail'][a]['n_sisa'];

                        if (zz==1) {
                            $('#iwip'+id).val(i_wip);
                            $('#ewip'+id).val(e_namabrg);
                            $('#icolor'+id).val(i_color);
                            $('#ecolor'+id).val(e_color_name);
                            $('#iproduct'+id).val(i_product);
                            $('#qtysisa'+id).val(n_sisa);
                            $('#qtymasuk'+id).val(n_sisa);
                        } else {
                            var cols        = "";
                            var newRow = $("<tr>");        
                            cols += '<td style="text-align: center;" colspan="2"><spanx id="snum'+counter+'"></spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input style="width:100px;" type="hidden" readonly  id="ireff'+ counter + '" type="text" class="form-control" name="ireff[]" value="'+i_sj+'"></td>';
                            cols += '<td><input style="width:100px;" type="text" readonly  id="iwip'+ counter + '" type="text" class="form-control" name="iwip[]"  value="'+i_wip+'"></td>';
                            cols += '<td><input style="width:400px;" type="text" readonly  id="ewip'+ counter + '" type="text" class="form-control" name="ewip[]"  value="'+e_namabrg+'"></td>';
                            cols += '<td><input style="width:140px;" type="text" style="width:120px;" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor[]"  value="'+e_color_name+'"/><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor[]"  value="'+i_color+'"/></td>';

                            cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"  value="'+i_product+'"></td>';
                            cols += '<td><input style="width:100px;"type="text" id="qtysisa'+ counter + '" readonly class="form-control" name="qtysisa[]" value="'+n_sisa+'"/></td>';
                            cols += '<td><input style="width:100px;"type="text" id="qtymasuk'+ counter + '" class="form-control" name="qtymasuk[]" value="'+n_sisa+'" onfocus="if(this.value==\'0\'){this.value=\'\';}" onkeyup="cekval(this.value); reformat(this);validasi('+counter+');" /></td>';
                            cols += '<td><input style="width:400px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
                            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                            newRow.append(cols);
                            $("#tabledata").append(newRow);
                        }
                        counter++;
          
                    }
                }else{
                    $('#ireff'+id).val('');
                    $('#ereff'+id).val('');
                    $('#ireff'+id).html('');
                    $('#ereff'+id).html('');
                }

                
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtysisa  =document.getElementById("qtysisa"+i).value;
            qtymasuk =document.getElementById("qtymasuk"+i).value;
            if(parseFloat(qtymasuk)>parseFloat(qtysisa)){
                swal('Jumlah Masuk Tidak Boleh Lebih dari Jumlah Keluar');
                document.getElementById("qtymasuk"+i).value=qtysisa;
                break;
          }
        }
    }
</script>