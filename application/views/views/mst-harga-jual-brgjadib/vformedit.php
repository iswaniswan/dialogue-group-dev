<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="white-box">
                <div id="pesan"></div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="clear: both" class="table table-bordered table-striped" id="user">
                            <tbody>
                                <tr>
                                    <td width="25%">Kelompok Barang</td>
                                    <td width="25%"><b><?= $data->kelompok; ?></b></td>
                                    <td width="25%">Tipe Barang</td>
                                    <td width="25%"><b><?= $data->type; ?></b></td>
                                </tr>
                                <tr>
                                    <td width="25%">Kode Barang</td>
                                    <td width="25%"><b><?= $data->i_product_motif; ?></b></td>
                                    <td width="25%">Nama Barang</td>
                                    <td width="25%"><b><?= ucwords(strtolower($data->e_product_basename)); ?></b></td>
                                </tr>
                                <tr>
                                    <?php if ($data->f_status_aktif == 't') {
                                        $status = 'Aktif';
                                    }else{
                                        $status = 'Tidak Aktif';
                                    }?>
                                    <td width="25%">Status Keaktifan Barang</td>
                                    <td width="25%"><b><?= $status; ?></b></td>
                                    <td width="25%">Berlaku Mulai</td>
                                    <td width="25%"><b><input type="text" name="dmulai" id="dmulai" class="form-control date" readonly value="<?= $data->d_mulai; ?>" required="" onchange="cektanggal(this.value);"></b></td>
                                </tr>
                                <tr>
                                    <td width="25%">Harga Barang</td>
                                    <td width="25%"><b><input type="text" name="harga" id="harga" class="form-control" required="" value="<?= number_format($data->v_price); ?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this)"></b></td>
                                    <td width="25%">Berlaku Sampai</td>
                                    <td width="25%"><b>
                                        <input type="text" name="dberlaku" id="dberlaku" class="form-control date" readonly value="<?= $data->d_berlaku; ?>" required="" onchange="cektanggal(this.value);">
                                        <input type="hidden" name="dberlakuold" id="dberlakuold" class="form-control date" readonly value="<?= $data->d_berlaku_old; ?>"></b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" name="i_price" name="i_price" class="form-control" required="" value="<?= $data->i_price; ?>"readonly>
                        <input type="hidden" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->i_product_motif; ?>" readonly>
                        <input type="hidden" name="aktif" id="aktif" class="form-control" required="" value="<?= $data->f_status_aktif; ?>" readonly>
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#harga').val(formatcemua($('#harga').val()));
    });

    function cektanggal(id){
        berlaku  = $('#dberlaku').val();
        berlakuold = $('#dberlakuold').val();
        if (berlakuold != "") {
            if (berlaku < berlakuold) {
                swal('Harga Barang Sampai Tanggal Tersebut Sudah Tersedia');
                $('#dberlaku').val("");
            }
        }  
    }
</script>
<!-- <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <input type="hidden" name="i_price" name="i_price" class="form-control" required="" value="<?= $data->i_price; ?>"readonly>
                <div class="col-md-10">
                    <div class="form-group row">
                        <label class="col-md-3">Kelompok Barang</label> 
                        <label class="col-md-3">Tipe Barang</label>
                        <label class="col-md-6">Status Keaktifan Barang</label>  
                        <div class="col-sm-3">
                           <input type="text" name="enamakelompok" name="enamakelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->kelompok; ?>"readonly>
                       </div>
                       <div class="col-sm-3">
                        <input type="text" name="etypename" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->type; ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                     <select name="aktif" id="aktif" class="form-control select2">
                        <option value="t" <?php if($data->f_status_aktif == 't'){?> selected <?php } ?>>Aktif</option>
                        <option value="f" <?php if($data->f_status_aktif == 'f'){?> selected <?php } ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="form-group row"> 
                <label class="col-md-2">Kode Barang</label>
                <label class="col-md-3">Nama Barang</label>
                <label class="col-md-2">Warna</label>
                <label class="col-md-2">Harga</label>
                <label class="col-md-3">Tanggal Berlaku</label>
                <div class="col-sm-2">
                    <input type="text" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->i_product_motif; ?>" readonly>
                </div>                         
                <div class="col-sm-3">
                    <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->e_product_basename; ?>"readonly>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="warna" id="warna" class="form-control" required="" value="<?= $data->e_color_name; ?>"readonly>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="harga" id="harga" class="form-control" required="" value="<?= $data->v_price; ?>" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this)">
                </div>
                <div class="col-sm-2">
                   <input type="text" name="dberlaku" id="dberlaku" class="form-control date" readonly value="<?= $data->d_berlaku; ?>" required="" onchange="cektanggal(this.value);">
                   <input type="hidden" name="dberlakuold" id="dberlakuold" class="form-control date" readonly value="<?= $data->d_berlaku_old; ?>">
               </div>
           </div>

           <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i
                    class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </form>
</div>
</div>
</div> -->
<!-- <script>

    function cektanggal(id){
        berlaku  = $('#dberlaku').val();
        berlakuold = $('#dberlakuold').val();

        if (berlakuold != "") {
        //berlakuold = getTanggal(berlakuold);
        if (berlaku < berlakuold) {
           swal('Harga Barang Sampai Tanggal Tersebut Sudah Tersedia');
           $('#dberlaku').val("");
       }
   }  
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
    harga  =  formatcemua($('#harga').val());
    $('#harga').val(harga);
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

</script> -->