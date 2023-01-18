<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label> 
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                            <input type="text" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->i_product; ?>" readonly>
                        </div>                         
                        <div class="col-sm-6">
                            <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->e_product; ?>"readonly>
                        </div>    
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-3">Kategori Barang</label>  
                        <label class="col-md-3">Tanggal Berlaku</label>
                        <label class="col-md-3">Include PPN</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="text" name="ekodejenis" id="ekodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_type_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodekelompok" name="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>"readonly>
                             <input type="text" name="enamakelompok" name="enamakelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>"readonly>
                        </div>
                        <div class="col-sm-3">
                             <input type="text" name="dberlaku" id="dberlaku" class="form-control date" readonly value="<?= $data->d_berlaku; ?>">
                        </div>
                        <div class="col-sm-3">
                             <select name="itipe" id="itipe" class="form-control select2" disabled="">
                                <option value="">Pilih Include PPN</option>
                                <option value="I" <?php if($data->i_type_pajak =='I') { ?> selected <?php } ?> >Ya</option>
                                <option value="E" <?php if($data->i_type_pajak =='E') { ?> selected <?php } ?> >Tidak</option> 
                            </select>  
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Harga Eks</label>
                        <label class="col-md-3">Satuan Eks</label>
                        <label class="col-md-3">Harga Int</label>
                        <label class="col-md-3">Satuan Int</label>
                        <div class="col-sm-3">
                            <input type="text" name="hargaeks" id="hargaeks" class="form-control" required="" value="<?= 'Rp. '.number_format($data->v_price_eks, 2); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                             <select name="isatuaneks" id="isatuaneks" class="form-control select2" disabled="">
                                <option value="<?= $data->i_satuan_code_eks;?>"><?= $data->e_satuan_name_eks; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="hargaint" id="hargaint" class="form-control" required="" value="<?= 'Rp. '.number_format($data->v_price_int, 2); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="isatuanint" id="isatuanint" class="form-control" value="<?= $data->i_satuan_code_int; ?>" readonly>
                            <input type="text" name="isatuanint" id="isatuanint" class="form-control" value="<?= $data->e_satuan_name_int; ?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Status</label>
                        <div class="col-sm-3">
                            <select name="status" class="form-control select2" disabled="">
                                <option value="">Pilih Status</option>
                                <option value="t" <?php if($data->f_status =='t') { ?> selected <?php } ?> >Aktif</option>
                                <option value="f" <?php if($data->f_status =='f') { ?> selected <?php } ?> >Tidak Aktif</option> 
                            </select> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8"> 
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$idtypemakloon;?>","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function konversi(){
     var satuan_awal = $('#satuan').val();
     var satuan_akhir = $('#isatuan').val();
     var harga = $('#harga').val();
     var konversiharga = $('#konversiharga').val();
   
      $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getrumus');?>",
            data:{
                'satuan_awal': satuan_awal,
                'satuan_akhir': satuan_akhir,
                'harga': harga,
            },
            dataType: 'json',
            success: function(data){
                $('#konversiharga').html(data.kop);
                if (data.kosong=='kopong') {
                    swal("konversi satuan kosong");
                }else {
                    getangfaktor();
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
}

function getangfaktor() {
        var satuan_awal = $('#satuan').val();
        var satuan_akhir = $('#isatuan').val();
       $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getrumuss');?>",
            data:{
                'satuan_awal': satuan_awal,
                'satuan_akhir': satuan_akhir,
            },
            dataType: 'json',
            success: function(data){
                $('#angkafaktor').html(data.kop);
                if (data.kosong=='kopong') {
                    alert("rumus kosong");
                }else {
                    cekkonversi();
                }
            },
            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
}

function cekkonversi(){
    var satuan_awal = $('#isatuan').val();
    var satuan_akhir = $('#satuan').val();
    var harga = $('#harga').val();
    var konversiharga = $('#konversiharga').val();
    var angkafaktor = $('#angkafaktor').val();


    if(konversiharga == '1'){
        total=harga*angkafaktor;
        document.getElementById('hargakonversi').value = formatcemua(total);
    }else if(konversiharga == '2'){
        total=harga/angkafaktor;
        document.getElementById('hargakonversi').value = formatcemua(total);
    }else if(konversiharga == '3'){
        total=harga+angkafaktor;
        document.getElementById('hargakonversi').value = formatcemua(total);
    }else if(konversiharga == '4'){
        total=harga-angkafaktor;
        document.getElementById('hargakonversi').value = formatcemua(total);
    }else{
        swal("rumus kosong");
    }
}

</script>
