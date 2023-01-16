<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Jenis Faktur</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="ibagian" id="ibagian">
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
                                <input type="text" name="ikasbankkeluarap" id="ikasbankkeluarap" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                           </div>
                        <div class="col-sm-2">
                            <input class="form-control date" name="dkasbankkeluarap" id="dkasbankkeluarap" readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="ijenis" id="ijenis">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-5">No Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="isupplier" id="isupplier" onchange="getref(this.value);" disabled>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="irefferensi" id="irefferensi" class="form-control select2" onchange="getitem(this.value);" disabled="true">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dreferensi" id="dreferensi" value="" placeholder="Tanggal Referensi" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Kas/Bank</label>
                        <label class="col-md-8">Bank</label>
                        <div class="col-sm-4">
                            <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="getbank(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="ibank" id="ibank" class="form-control" value="" placeholder="Nama Bank" readonly>
                            <input type="text" name="ebank" id="ebank" class="form-control" value="" placeholder="Nama Bank" readonly>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-4">Sisa Hutang</label>
                        <label class="col-md-8">Jumlah Bayar</label>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vsisa" id="vsisa" class="form-control" placeholder="Nominal Sisa Hutang" value="" readonly>
                            <input type="hidden" name="vsisaold" id="vsisaold" class="form-control" placeholder="Nominal Sisa Hutang" value="" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vbayar" id="vbayar" class="form-control" placeholder="Nominal Jumlah Bayar" value="" readonly>
                            <input type="hidden" name="vbayarold" id="vbayarold" class="form-control" placeholder="Nominal Jumlah Bayar" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="eremark" id="eremark" class="form-control" value="" placeholder="Isi Keterangan Jika Ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm" hidden> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Nomor Nota</th>
                        <th style="text-align:center;">Tanggal Nota</th>
                        <th style="text-align:center;">Nilai Nota</th>
                        <th style="text-align:center;">Jumlah Bayar</th>
                        <th style="text-align:center;">Keterangan</th>
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

    //untuk format membuat nomor dokumen
    $('#ikasbankkeluarap').mask('SSSS-0000-000000S');

    //memanggil function untuk penomoran dokumen
    number();

    $('#ijenis').select2({
        placeholder:"Pilih Jenis Faktur",
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/bacajenisfaktur'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
        }
    }).change(function(event) {             
            $("#isupplier").attr("disabled", false);
            $("#isupplier").val("");
            $("#isupplier").html("");
            $("#irefferensi").val("");
            $("#irefferensi").html("");
            $("#submit").attr("disabled", true);
    });

    //untuk menampilkan daftar supplier yang hanya ada di permintaan pembayaran ap
    $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/bacasupplier'); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                var query = {
                    q: params.term,
                    ijenis : $('#ijenis').val(),
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
    }).change(function(event) {
        $("#irefferensi").val("");
        $("#irefferensi").html("");   
    });

    $('#irefferensi').select2({
        placeholder: 'Pilih No Referensi',
    });

    //untuk menampilkan daftar kas/bank sesuai dengan yang ada di master akunting (kas/bank)
    $('#ikasbank').select2({
        placeholder: 'Pilih Kas/Bank',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/kasbank'); ?>',
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

//untuk me-generate running number
function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dkasbankkeluarap').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ikasbankkeluarap').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
}

//menyesuaikan periode di running number sesuai dengan tanggal dokumen
$( "#dkasbankkeluarap" ).change(function() {
    $.ajax({
        type: "post",
        data: {
            'tgl' : $(this).val(),
        },
        url: '<?= base_url($folder.'/cform/number'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ikasbankkeluarap').val(data);
        },
        error: function () {
            swal('Error :)');
        }
    });
});

//mengecek nomor dokumen apakah sudah ada atau belum
$( "#ikasbankkeluarap" ).keyup(function() {
    $.ajax({
        type: "post",
        data: {
            'kode' : $(this).val(),
        },
        url: '<?= base_url($folder.'/cform/cekkode'); ?>',
        dataType: "json",
        success: function (data) {
            if (data==1) {
                $(".notekode").attr("hidden", false);
                $("#submit").attr("disabled", true);
            }else{
                $(".notekode").attr("hidden", true);
                $("#submit").attr("disabled", false);
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
});

//untuk membuat nomor dokumen manual atau otomatis
$('#ceklis').click(function(event) {
    if($('#ceklis').is(':checked')){
        $("#ikasbankkeluarap").attr("readonly", false);
    }else{
        $("#ikasbankkeluarap").attr("readonly", true);
       // $("#ikasbankkeluarap").val("<?= $number;?>");
    }
});

//untuk menampilkan nomor referensi dari permintaan pembayaran ap
function getref(isupplier){
    var ijenis = $('#ijenis').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
        data:{
               'isupplier': isupplier,
               'ijenis'   : ijenis,
            },
        dataType: 'json',
        success: function(data){
            $("#irefferensi").html(data.kop);
            if (data.kosong=='kopong'){
                $("#submit").attr("disabled", true);
            }else{
                $("#irefferensi").attr("disabled", false);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}

//remove data table jika nomor referensi di ubah
function removeBody(){
    var tbl = document.getElementById("tabledatax");    // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}

// $('#ijenis').change(function(){
//     removeBody();
// });

//menampilkan data item sesuai dengan nomor referensi yang dipilih
function getitem(id) {   
    $("#submit").attr("disabled", false);
    $("#detail").attr("hidden", false);
    var isupplier = $('#isupplier').val();
    var ijenis = $('#ijenis').val();

    $("#tabledatax tbody").remove();       
    $.ajax({
        type: "post",
        data: {
            'irefferensi'   : id,
            'isupplier'     : isupplier,
            'ijenis'        : ijenis,
        },
        url: '<?= base_url($folder.'/cform/getitem'); ?>',
        dataType: "json",
        success: function (data) {
            var dkas  = data['head']['d_ppap'];
            var sisahutang = data['head']['v_sisa'];

            var sisa = 0;
            $('#dreferensi').val(dkas);
            $('#vsisa').val((sisa));
            $('#vsisaold').val(sisahutang);
            $('#vbayar').val((sisahutang));
            $('#vbayarold').val(sisahutang);


            $('#jml').val(data['detail'].length-1);
            for (let a = 0; a < data['detail'].length; a++) {
                var no = a;
                var inota   = data['detail'][a]['i_nota']
                var dnota   = data['detail'][a]['d_nota'];
                var vtotal  = data['detail'][a]['total'];
                var vsisa  = data['detail'][a]['sisa'];
                var idppap  = data['detail'][a]['id_ppap'];
                var idnota  = data['detail'][a]['id_nota'];

                var cols        = "";
                var newRow = $("<tr>");

                cols += '<td style="text-align:center;">'+(no+1)+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                cols += '<td><input readonly class="form-control" type="text" id="inota'+no+'" name="inota'+no+'" value="'+inota+'"><input readonly style="width:150px;" class="form-control" type="hidden" id="idnota'+no+'" name="idnota'+no+'" value="'+idnota+'"><input readonly style="width:150px;" class="form-control" type="hidden" id="idppap'+no+'" name="idppap'+no+'" value="'+idppap+'"></td>';
                cols += '<td><input readonly class="form-control" type="text" id="dnota'+no+'" name="dnota'+no+'" value="'+dnota+'"></td>'; 

                // cols += '<td><input readonly class="form-control" type="text" id="vnilai'+no+'" style="text-align:right;" name="vnilai'+no+'" value="'+vsisa+'"></td>'; 
                // cols += '<td><input class="form-control" type="text" name="vbayarnota'+no+'" style="text-align:right;" id="vbayarnota'+no+'" value="'+vsisa+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="angkahungkul(this); reformat(this); hetang('+no+'); cek_nilai('+no+');""><input type="hidden" id="selisih'+no+'"><input class="form-control" type="hidden" name="vbayarnotaold'+no+'" id="vbayarnotaold'+no+'" value="'+vtotal+'"></td>';


                cols += `<td><input readonly class="form-control input-sm text-right" type="text" id="v_nilai_reff${no}" name="v_nilai_reff${no}" value="${formatcemua(data['detail'][a]['sisa'])}"></td>`; 
                cols += `<td><input class="form-control input-sm text-right" type="text" id="v_nilai${no}" name="v_nilai${no}" onblur=\'if(this.value==""){this.value="0";};cek_nilai(${no})\' onfocus=\'if(this.value=="0"){this.value="";}\' value="${formatcemua(data['detail'][a]['sisa'])}" onkeyup="angkahungkul(this); reformat(this); hetang(${no}); cek_nilai(${no});"></td>`; 

                cols += '<td><input class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
                newRow.append(cols);
                //cek_nilai(no);
                $("#tabledatax").append(newRow);
                
               
            }
            //getjumlahbayar();
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}  

    /*----------  Cek Nilai Jika Lebih  ----------*/
    function hetang(i) {
        if (parseInt(formatulang($('#v_nilai'+i).val())) > parseInt(formatulang($('#v_nilai_reff'+i).val()))) {
            swal('Maaf','Jumlah Nilai Tidak Boleh Lebih Besar Dari Nilai Referensi = Rp. '+$("#v_nilai_reff"+i).val()+' !','error');
            $('#v_nilai'+i).val($('#v_nilai_reff'+i).val());
            cek_nilai(i);
        }
    }

    /*----------  Hitung Total Nilai  ----------*/      
    function cek_nilai(i){
        total = 0;
        for (var i = 0; i <= $('#jml').val(); i++) {
            var jumlah = formatulang($('#v_nilai'+i).val());
            total += parseFloat(jumlah);
        }
        $('#vbayar').val(formatcemua(total));
        sisahutang = $('#vsisaold').val() - total;
        $('#vsisa').val(formatcemua(sisahutang))
    }

//mengubah jumlah bayar di item 
// function getjumlahbayar(){
//     var vsisa     = 0;
//     var jml = $('#jml').val();

//     for(var i=1; i<=jml ;i++){
//         var vbayarnota    = formatulang($('#vbayarnota'+i).val());
//         var vbayarnotaold = formatulang($('#vbayarnotaold'+i).val());
//         vbayarnow = parseFloat(vbayarnotaold) - parseFloat(vbayarnota);
//         vsisa = vsisa + vbayarnow;
//         valnilai(i);
//     }
    
//     var vbayarold = $('#vbayarold').val();
//     vbayar = parseFloat(vbayarold) - vsisa;

//     $('#vbayar').val(formatRupiah(vbayar));
//     $('#vsisa').val(formatRupiah(vsisa));  
// }

// function valnilai(id){
//     var vnota = formatulang($('#vnilai'+id).val());
//     var vbayar = formatulang($('#vbayarnota'+id).val());

//     if(parseFloat(vbayar) > parseFloat(vnota)){
//         swal("Jumlah bayar tidak boleh melebihi nilai nota");
//         $('#vbayarnota'+id).val(vnota);
//         getjumlahbayar();
//     }

//     var jumbayar = $('#vbayar').val();
//     if(parseFloat(jumbayar) <= 0){
//         swal("Jumlah bayar tidak boleh nol atau lebih kecil dari nol");
//         $('#submit').attr("disabled", true);
//     }else{
//         $('#submit').attr("disabled", false);
//     }
// }


//mengambil format rupiah untuk nilai bayar dll, 
function formatRupiah(angka, prefix){
	var	number_string = angka.toString(),
	split	= number_string.split(','),
	sisa 	= split[0].length % 3,
	rupiah 	= split[0].substr(0, sisa),
	ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
		
    if (ribuan) {
    	separator = sisa ? ',' : '';
    	rupiah += separator + ribuan.join(',');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

//untuk menampilkan nama bank jika jenis kas yang dipilih adalah bank
function getbank(id){
    $.ajax({
        type : "POST",
        data : {
            'ikodekas' : id,
        },
        url  : '<?= base_url($folder.'/cform/getbank'); ?>',
        dataType: "json",
        success : function(data){
            var ibank = data['head']['i_bank'];
            var ebank = data['head']['e_bank_name'];

            $('#ibank').val(ibank);
            $('#ebank').val(ebank);
        },
        error : function(){
            swal('Error :)');
        }
    });
}

$('#send').click(function(event) {
    statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
});

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#send").attr("hidden", false);
});

function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        
    }else{
        swal('input harus numerik !!!');
        input = input.substring(0,input.length-1);
     }
}

function validasi(){
    var s=0;
    var ikasbank = $('#ikasbank').val();
    var i = document.getElementById("jml").value;
    var maxpil = 1;
    var jml = $("input[type=checkbox]:checked").length;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    })
    if(ikasbank == '' || ikasbank == null){
        swal("Data Masih Kosong");
        return false;
    }else{
        return true;
    }
}    
</script>