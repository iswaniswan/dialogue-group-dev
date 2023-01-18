<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
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
                        <label class="col-md-3">Jenis Debet</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="ibagian" id="ibagian">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" class="form-control" name="id" id="id" readonly value="<?= $data->id; ?>">
                            <input type="hidden" class="form-control" name="ikodeold" id="ikodeold" value="<?= $data->i_document; ?>">
                            <input type="hidden" class="form-control" name="istatus" id="istatus" readonly value="<?= $data->i_status; ?>">
                            <div class="input-group">
                                <input type="text" name="ialokasidebet" id="ialokasidebet" readonly autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control date" name="dalokasidebet" id="dalokasidebet" readonly value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="ijenisdebet" id="ijenisdebet">
                                <option value="<?= $data->i_jenis_debet; ?>"><?= $data->e_jenis_debet_name; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-5">No Debet</label>
                        <label class="col-md-3">Tanggal Debet</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="isupplier" id="isupplier" onchange="getdebet(this.value);">
                                <option value="<?= $data->id_supplier.'|'.$data->i_supplier; ?>"><?= $data->e_supplier_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="idebet" id="idebet" class="form-control select2" onchange="getjenisfaktur(this.value);">
                                <option value="<?=$data->id_document_debet;?>"><?=$data->i_document_debet;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="ddebet" id="ddebet" placeholder="Tanggal Referensi" value="<?=$data->d_document_debet;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Jenis Faktur</label>
                        <label class="col-md-5">No Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ijenisfaktur" id="ijenisfaktur" class="form-control select2" onchange="getreferensi(this.value);">
                                <option value="<?= $data->i_jenis_faktur;?>"><?= $data->e_jenis_faktur_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" onchange="getitem(this.value);">
                                <option value="<?= $data->id_document_reff; ?>"><?= $data->i_document_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dreferensi" id="dreferensi" value="<?= $data->d_document_reff; ?>" placeholder="Tanggal Referensi" readonly>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-4">Sisa Hutang</label>
                        <label class="col-md-4">Jumlah Debet</label>
                        <label class="col-md-4">Sisa Debet</label>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vsisa" id="vsisa" class="form-control" placeholder="Nominal Sisa Hutang" value="" readonly>
                            <input type="hidden" name="vsisaold" id="vsisaold" class="form-control" placeholder="Nominal Sisa Hutang" value="<?= $data->v_sisa;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vbayar" id="vbayar" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?= $data->v_total_debet;?>" readonly>
                            <input type="hidden" name="vbayarold" id="vbayarold" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?= $data->v_sisa_debet_awal;?>" readonly>
                            <input type="hidden" name="vbayarnow" id="vbayarnow" class="from-control" placeholder="Nominal Bayar Sekarang" value="<?= $data->v_bayar;?>">
                            <input type="hidden" name="vbayarfaktur" id="vbayarfaktur" class="form-control" value="<?= $data->v_nota; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vsisadebet" id="vsisadebet" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?=$data->v_sisa;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="eremark" id="eremark" class="form-control" value="" placeholder="Isi Keterangan Jika Ada!"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <input type="hidden" name="jml" id="jml" value="0"> -->
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;width:5%">No</th>
                        <th style="text-align:center;width:20%">Nomor Nota</th>
                        <th style="text-align:center;width:15%">Tanggal Nota</th>
                        <th style="text-align:center;width:15%">Nilai Nota</th>
                        <th style="text-align:center;width:15%">Jumlah Bayar</th>
                        <th style="text-align:center;width:30%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php            
                    if($detail){
                        $i = 0;
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>     
                                <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td> 
                                <td>  
                                    <input type="hidden" class="form-control" id="idnota<?=$i;?>" name="idnota<?=$i;?>"value="<?= $row->id_nota; ?>" readonly>
                                    <input type="hidden" class="form-control" id="idppap<?=$i;?>" name="idppap<?=$i;?>"value="<?= $row->id_document_reff; ?>" readonly>
                                    <input type="text" class="form-control" id="inota<?=$i;?>" name="inota<?=$i;?>"value="<?= $row->i_nota; ?>" readonly>
                                </td>
                                <td>
                            
                                    <input type="text" class="form-control" id="d_nota<?=$i;?>" name="d_nota<?=$i;?>"value="<?= $row->d_nota; ?>" readonly>
                                </td>                            
                                <td style="text-align: right;">  
                                    <input type="text" style="text-align:right;" class="form-control" id="vnilai<?=$i;?>" name="vnilai<?=$i;?>"value="<?= $row->v_nota; ?>" readonly>
                                </td>
                                <td  style="text-align: right;">  
                                    <input type="text" style="text-align:right;" class="form-control" id="vbayarnota<?=$i;?>" name="vbayarnota<?=$i;?>"value="<?= $row->v_nota_bayar; ?>" onkeyup="getjumlahbayar(<?=$i;?>);">
                                    <input type="hidden" class="form-control" id="vbayarnotaold<?=$i;?>" name="vbayarnotaold<?=$i;?>"value="<?= $row->v_nota_bayar; ?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>">
                                </td>                    
                            </tr>   
                         <? } ?>   
                         <input type="hidden" name="jml" id="jml" value="<?=$i;?>">                   
                 <?}?>      
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
        getsisanota();
        //untuk format membuat nomor dokumen
        $('#ialokasidebet').mask('SS-0000-000000S');
    });

    $('#ijenisdebet').select2({
        placeholder : "Pilih Jenis Debet",
        allowClear  : true,
        ajax : {
            url: '<?= base_url($folder.'/cform/bacajenisdebet'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
        }
    }).change(function(event){
        $("#isupplier").attr("disabled", false);
        $("#isupplier").val("");
        $("#isupplier").html("");
        $("#ijenisfaktur").val("");
        $("#ijenisfaktur").html("");
        $("#ireferensi").val("");
        $("#ireferensi").html("");
        $("#dreferensi").val("");
        $("#idebet").val("");
        $("#idebet").html("");
        $("#ddebet").val("");
        $("#tabledatax tr:gt(0)").remove();
        $("#jml").val(0);
        $("#vbayar").val(0);
        $("#vsisadebet").val("");
        $("#vsisa").val("");
        $("#submit").attr("disabled", true);
    });

    //getsupplier
    $('#isupplier').select2({
        placeholder : 'Pilih Supplier',
        width : '100%',
        allowClear : true,
        ajax : {
            url : '<?= base_url($folder.'/cform/bacasupplier'); ?>',
            dataType : 'json',
            delay : 250,
            data : function (params){
                var query = {
                    q : params.term,
                    ijenis : $('#ijenisdebet').val(),
                }
                return query;
            },
            processResults : function (data){
                return{
                    results : data
                };
            },

            cache : false
        }
    }).change(function(event) {
        $("#ijenisfaktur").val("");
        $("#ijenisfaktur").html("");
        $("#ireferensi").val("");
        $("#ireferensi").html("");
        $("#dreferensi").val("");
        $("#idebet").val("");
        $("#idebet").html("");
        $("#ddebet").val("");
        $("#tabledatax tr:gt(0)").remove();
        $("#jml").val(0);
        $("#vbayar").val(0);
        $("#vsisadebet").val("");
        $("#vsisa").val("");
    });

function getsisanota(){
    var vtotnota  = 0;
    var vtotbayar = 0;
    var sisahutang = 0;

    for(var i=1; i<=$('#jml').val(); i++){
        vnota      = formatulang($('#vnilai'+i).val());
        vnotabayar = formatulang($('#vbayarnota'+i).val());
        vtotnota += parseFloat(vnota);
        vtotbayar += parseFloat(vnotabayar); 

        sisahutang = vtotnota - vtotbayar;
        $('#vsisa').val(sisahutang);
    }
}

    //untuk menampilkan nomor referensi dari debet note
function getdebet(isupplier){
    var ijenis = $('#ijenisdebet').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getdebet');?>",
        data:{
               'isupplier': isupplier,
               'ijenis'   : ijenis,
            },
        dataType: 'json',
        success: function(data){
            $("#idebet").html(data.kop);
            if (data.kosong=='kopong'){
                $("#submit").attr("disabled", true);
            }else{
                $("#idebet").attr("disabled", false);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}  

//untuk menampilkan jenis faktur
function getjenisfaktur(id){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenisfaktur');?>",
        data:{
               'isupplier'   : $('#isupplier').val(),
               'ijenisdebet' : $('#ijenisdebet').val(),
            },
        dataType: 'json',
        success: function(data){
            $("#ijenisfaktur").html(data.kop);
            if (data.kosong=='kopong'){
                $("#submit").attr("disabled", true);
            }else{
                $("#ijenisfaktur").attr("disabled", false);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
    getjumdebet(id);
}

//menampilkan data item sesuai dengan nomor referensi yang dipilih
function getjumdebet(id) {   
    $("#submit").attr("disabled", false);
    $.ajax({
        type: "post",
        data: {
            'id'   : id,
            'isupplier'     : $('#isupplier').val(),
            'ijenis'        : $('#ijenisdebet').val(),
        },
        url: '<?= base_url($folder.'/cform/getjumdebet'); ?>',
        dataType: "json",
        success: function (data) {
            var ddebet      = data['head']['d_document'];
            var jumlahbayar = data['head']['v_sisa'];

            $('#ddebet').val(ddebet);
          //  $('#vsisa').val((sisa));
          //  $('#vsisaold').val(sisahutang);
            $('#vbayar').val((jumlahbayar));
            $('#vbayarold').val(jumlahbayar);
        }
    });
}

//untuk menampilkan referensi
function getreferensi(){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
        data:{
               'isupplier'   : $('#isupplier').val(),
               'ijenisfaktur' : $('#ijenisfaktur').val(),
            },
        dataType: 'json',
        success: function(data){
            $("#ireferensi").html(data.kop);
            if (data.kosong=='kopong'){
                $("#submit").attr("disabled", true);
            }else{
                $("#ireferensi").attr("disabled", false);
            }
        },
        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    });
}

    //approve 
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });


    //untuk me-generate running number
    function number() {
            $.ajax({
                type: "post",
                data: {
                    'tgl' : $('#dalokasidebet').val(),
                    'ibagian' : $('#ibagian').val(),
                },
                url: '<?= base_url($folder.'/cform/number'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#ialokasidebet').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
    }

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $( "#dalokasidebet" ).change(function() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ialokasidebet').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    //mengecek nomor dokumen apakah sudah ada atau belum
    $( "#ialokasidebet" ).keyup(function() {
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
            $("#ialokkasidebet").attr("readonly", false);
        }else{
            $("#ialokasidebet").attr("readonly", true);
            // $("#ikasbankkeluarap").val("<?= $number;?>");
        }
    });

    //remove data table jika nomor referensi di ubah
    function removeBody(){
        var tbl = document.getElementById("tabledatax");    // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
    }

    //menampilkan data item sesuai dengan nomor referensi yang dipilih

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

  //menampilkan data item sesuai dengan nomor referensi yang dipilih
function getitem(id) {   
    $("#submit").attr("disabled", false);
    $("#detail").attr("hidden", false);
    var isupplier = $('#isupplier').val();
    var ijenis = $('#ijenisfaktur').val();

    removeBody();       
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

            jumbayar = formatulang($('#vbayar').val());
            hutang = sisahutang - formatulang($('#vbayar').val());

            $('#dreferensi').val(dkas);
            $('#vsisa').val(sisahutang);
            $('#vsisaold').val(sisahutang);
            $('#vbayarfaktur').val(sisahutang);
            $('#jml').val(data['detail'].length);
            for (let a = 0; a < data['detail'].length; a++) {
                var no = a+1;
                var inota   = data['detail'][a]['i_nota'];
                var dnota   = data['detail'][a]['d_nota'];
                var vtotal  = data['detail'][a]['total'];
                var vsisa   = data['detail'][a]['sisa'];
                var idppap  = data['detail'][a]['id_ppap'];
                var idnota  = data['detail'][a]['id_nota'];
                
                var x = $('#jml').val();

                var cols   = "";
                var newRow = $("<tr>");

                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                cols += '<td><input readonly class="form-control" type="text" id="inota'+no+'" name="inota'+no+'" value="'+inota+'">';
                cols += '<input readonly style="width:150px;" class="form-control" type="hidden" id="idnota'+no+'" name="idnota'+no+'" value="'+idnota+'">';
                cols += '<input readonly style="width:150px;" class="form-control" type="hidden" id="idppap'+no+'" name="idppap'+no+'" value="'+idppap+'"></td>';
                cols += '<td><input readonly class="form-control" type="text" id="dnota'+no+'" name="dnota'+no+'" value="'+dnota+'"></td>'; 
                cols += '<td><input readonly class="form-control" type="text" id="vnilai'+no+'" style="text-align:right;" name="vnilai'+no+'" value="'+vsisa+'"></td>'; 
                cols += '<td><input class="form-control" type="text" name="vbayarnota'+no+'" style="text-align:right;" id="vbayarnota'+no+'" value="0" placeholder="0" onkeyup="getjumlahbayar('+no+');">';
                cols += '<input type="hidden" id="selisih'+no+'"><input class="form-control" type="hidden" name="vbayarnotaold'+no+'" id="vbayarnotaold'+no+'" value="'+vtotal+'"></td>';
                cols += '<td><input class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
                newRow.append(cols);
                $("#tabledatax").append(newRow);
               
            }
           // getjumlahbayar();
        },
        error: function () {
            swal('Error :)');
        }
    });
    xx = $('#jml').val();
}  

function getjumlahbayar(id){
    var vsisa = 0;
    var vbayarnow = 0;
    //var jml = $('#jml').val();

    //for(var i=1; i<=jml ;i++){
        vsisaold      = formatulang($('#vsisaold').val());
        vbayarnota    = formatulang($('#vbayarnota'+id).val());
        vbayarnotaold = formatulang($('#vbayarnotaold'+id).val());

        vbayarnow += parseFloat(vbayarnota);
        vsisa = parseFloat(vsisaold) - parseFloat(vbayarnow);
        $('#vsisa').val(vsisa);

        var vbayarold = formatulang($('#vbayarold').val());
        vbayar = parseFloat(vbayarold) - vbayarnow;
        $('#vsisadebet').val(vbayar);

        valnilai(id);
    //}
    $('#vbayarfaktur').val(vbayarnow);
}

function valnilai(id){
    var vnota = formatulang($('#vnilai'+id).val());
    var vbayar = formatulang($('#vbayarnota'+id).val());

    if(parseFloat(vbayar) > parseFloat(vnota)){
        swal("Jumlah bayar tidak boleh melebihi nilai nota");
        $('#vbayarnota'+id).val(0);
        getjumlahbayar();
    }

    var jumbayar = $('#vsisadebet').val();
    if(parseFloat(jumbayar) < 0){
        swal("Jumlah bayar tidak boleh nol atau lebih kecil dari nol");
        $('#vbayarnota'+id).val(0);
        $('#vsisadebet').val(0);
        $('#submit').attr("disabled", true);
    }else{
        $('#submit').attr("disabled", false);
    }
}

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
    if($('#ijenisfaktur').val() == '' || $('#ijenisdebet').val() == null || $('#ibagian').val() == null){
        swal("Data Masih Kosong");
        return false;
    }else{
        return true;
    }
}    
</script>