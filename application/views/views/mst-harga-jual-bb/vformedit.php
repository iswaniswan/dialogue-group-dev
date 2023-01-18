<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> &nbsp;<?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Tanggal Berlaku</label>
                        <label class="col-md-3">Kategori Barang</label>      
                        <label class="col-md-3">Jenis Barang</label> 
                        <label class="col-md-4">Kode Barang</label> 
                        <div class="col-sm-2">
                            <input type="hidden" name="dfrom" id="dfrom" class="form-control" readonly value="<?=$dfrom;?>">
                            <input type="text" name="dberlaku" id="dberlaku" class="form-control date" readonly value="<?= $data->d_berlaku; ?>">
                            <input type="hidden" name="dberlakusebelum" id="dberlakusebelum" class="form-control date" readonly value="<?= date("d-m-Y",strtotime($data->d_berlaku));?>">
                            <input type="hidden" name="dakhirsebelum" id="dakhirsebelum" class="form-control date" readonly value="<?= date('Y-m-d', strtotime('-1 days', strtotime( $dberlaku )));?>">
                            <input type="hidden" name="id" id="id" class="form-control" value="<?=$data->id;?>">
                        </div>  
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodekelompok" name="ikodekelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_kode_kelompok; ?>"readonly>
                            <input type="text" name="enamakelompok" name="enamakelompok" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_nama_kelompok; ?>"readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodejenis" id="ikodejenis" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->i_type_code; ?>" readonly>
                            <input type="text" name="ekodejenis" id="ekodejenis" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_type_name; ?>" readonly>
                        </div>  
                        <div class="col-sm-3">
                            <input type="hidden" name="kodebrg" id="kodebrg" class="form-control" required="" value="<?= $data->id_material; ?>" readonly>
                            <input type="text" name="ikodebrg" id="ikodebrg" class="form-control" required="" value="<?= $data->i_material; ?>" readonly>
                        </div> 
                    </div>
                    <div class="form-group row">                       
                        <label class="col-md-5">Nama Barang</label>  
                        <label class="col-md-3">Kode Harga</label>                                             
                        <label class="col-md-3">Harga</label>
                        <div class="col-sm-5">
                            <input type="text" name="namabrg" id="namabrg" class="form-control" required="" value="<?= $data->e_material_name; ?>"readonly>
                        </div>  
                        <div class="col-sm-3">
                            <input type="hidden" name="ikodeharga" id="ikodeharga" class="form-control" required="" value="<?= $data->id_harga_kode; ?>"readonly>
                            <input type="text" name="ekodeharga" id="ekodeharga" class="form-control" required="" value="<?= $data->e_harga; ?>"readonly>
                        </div>  
                        <div class="col-sm-3">
                            <input type="text" name="harga" id="harga" class="form-control" required="" value="<?= $data->v_price; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8"> 
                            <button type="button" id="save" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            &nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                    <div class="form-group">
                    <span style="color: #8B0000"><b>Note : </b>Jika akan mengubah tanggal berlaku, maka tanggal berlaku tidak boleh sama dengan tanggal berlaku sebelumnya</span>
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
    $("#save").attr("disabled", true);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$('#dberlaku').on('change',function(){
    swal("Tanggal berlaku telah diubah!");
});

function dipales(){
    var kodebrg         = $('#kodebrg').val();
    var ikodeharga      = $('#ikodeharga').val();
    var harga           = $('#harga').val();
    var dberlaku        = $('#dberlaku').val();
    var dberlakusebelum = $('#dberlakusebelum').val();
    var dakhirsebelum   = $('#dakhirsebelum').val();
    var dfrom           = $('#dfrom').val();
    var id              = $('#id').val();

    swal({   
            title: "Ubah Tanggal Berlaku ?",   
            text: "Mengubah tanggal berlaku!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ubah, data yang sudah ada!",   
            cancelButtonText: "Simpan, sebagai data baru!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                            'kodebrg'        : kodebrg,
                            'ikodeharga'     : ikodeharga,
                            'harga'          : harga,
                            'dberlaku'       : dberlaku,
                            'dakhirsebelum'  : dakhirsebelum,
                            'dfrom'          : dfrom,
                            'dberlakusebelum': dberlakusebelum,
                            'id'             : id,
                    },
                    url: '<?= base_url($folder.'/cform/ubahtanggalberlaku'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#save").attr("disabled", true);
                        swal("Ubah!", "Data berhasil diubah :)", "success");
                        show('<?= $folder;?>/cform/edit/<?=$id;?>/<?=$kodebrg?>/<?=$dberlaku?>/<?=$dfrom?>/','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal diubah :(", "error");
                    }
                });
            } else {     
                $.ajax({
                    type: "post",
                    data: {
                            'kodebrg'        : kodebrg,
                            'ikodeharga'     : ikodeharga,
                            'harga'          : harga,
                            'dberlaku'       : dberlaku,
                            'dakhirsebelum'  : dakhirsebelum,
                            'dfrom'          : dfrom,
                            'dberlakusebelum': dberlakusebelum,
                            'id'             : id
                    },
                    url: '<?= base_url($folder.'/cform/inserttanggalberlaku'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $("input").attr("disabled", true);
                        $("select").attr("disabled", true);
                        $("#save").attr("disabled", true);
                        swal("Ubah!", "Data berhasil disimpan :)", "success");
                        show('<?= $folder;?>/cform/edit/<?=$id;?>/<?=$kodebrg?>/<?=$dberlaku?>/<?=$dfrom?>/','#main');  
                    },
                    error: function () {
                        swal("Maaf", "Data gagal disimpan :(", "error");
                    }
                });
            } 
    });
}
</script>