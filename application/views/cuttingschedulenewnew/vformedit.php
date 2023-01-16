<link href="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-6">Bagian Pembuat</label>
                        <div class="col-sm-3">
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id; ?>">
                            <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm date" name="ddocument" id="ddocument" value="<?= date("d-m-Y", strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" >
                                <?php if ($bagian) {
                                foreach ($bagian as $row):?>
                                    <option value="<?= $row->i_bagian;?>" <?php if($row->i_bagian == $data->i_bagian){ echo "selected";} ?>>
                                        <?= $row->e_bagian_name;?>
                                    </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Periode</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm date" name="periode" id="periode" value="<?= $data->i_periode; ?>">
                        </div>
                        <div class="col-sm-3">
                            <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1'|| $data->i_status == '3') { ?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php } elseif ($data->i_status == '2') { ?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                            <!-- <button type="button" onclick="tambah($('#jml').val());" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($detail) {?>
    <div class="white-box" id="detail">
        <div class="col-sm-5">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-12">
        <div style="height:auto; overflow:scroll;">
            <div class="table-responsive" style="width:1200px;">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="90%">
                    <thead>
                        <tr>
                            <th class="text-center" >No</th>
                            <th class="text-center" width="10%">Tanggal Pengerjaan</th>
                            <th class="text-center" width="20%">Nama Barang</th>
                        <!-- <th class="text-center" width="15%">Konversi ke Set</th>-->
                            <th class="text-center" >Progress</th>
                            <th class="text-center" >FC Cutting</th>
                            <!-- <th class="text-center" >FC Produksi</th>
                            <th class="text-center" >Kondisi Stock<br>Persiapan<br> Cutting</th> -->
                            <th class="text-center" >Keterangan</th>
        
                            <th class="text-center" >Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach($detail as $key){
                            $i++;
                        ?>
                        <tr>
                            <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                            <td><input type="text" id="tanggal<?= $i ;?>" name="tanggal[]" class="form-control input-sm date" value="<?= date("d-m-Y", strtotime($key->d_schedule));?>" placeholder="<?= date('d-m-Y'); ?>" ></td>
                            <td><select data-nourut="<?= $i ;?>" id="ibarang<?= $i ;?>" class="form-control input-sm id" name="ibarang[]" ><option value="<?= $key->id_product_wip;?>"><?= $key->i_product_wip.'-'.$key->e_product_wipname.'-'.$key->e_color_name;?></option></select></td>
                            <td><input type="text" id="progress<?= $i ;?>" name="progress[]" value="<?= $key->e_progress;?>"></td>
                            <td><input type="text" id="nfccutting<?= $i ;?>" name="nfccutting[]" size="2" value="<?= $key->n_fc_cutting;?>"></td>
                            <!-- <td><input type="text" id="nfcproduksi<?= $i ;?>" name="nfcproduksi[]" size="2" value="<?= $key->n_fc_perhitungan;?>"></td>
                            <td><input type="text" id="nkondisi<?= $i ;?>" name="nkondisi[]" size="2" value="<?= $key->n_kondisi_stock;?>"></td> -->
                            <td><input type="text" id="eremark<?= $i ;?>" name="eremark[]" value="<?= $key->e_remark;?>"></td>
                            <td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
    <input type="hidden" name="jmlreal" id="jmlreal">
<?php } ?>
</form>
<script src="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script>
    $(document).ready(function () {
        
        $('#ibagian').select2({
            // placeholder: 'Cari Bagian',
            // allowClear: true,
            // ajax: {
            //     url: '<?= base_url($folder.'/cform/getbagian'); ?>',
            //     dataType: 'json',
            //     delay: 250,          
            //     processResults: function (data) {
            //         return {
            //             results: data
            //         };
            //     },
            //     cache: true
            // }
        });
    })
    
        $("#ddocument").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $("#periode").datepicker({
            autoclose: true,
            todayHighlight: true,
            minViewMode: "months",
            format: "yyyymm",
            todayBtn: "linked",
        });

    for (var i = 1; i <= $('#jml').val(); i++) {

        $("#tanggal"+i).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#ibarang'+ i).select2({
            placeholder: 'Cari Kode / Nama Produk',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/productwip/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                // success: function (data) {
                //     document.getElementById('kategori'+i).value = JSON.stringify(data[2]);
                //     document.getElementById('progress'+i).value = JSON.stringify(data[3]);
                //     document.getElementById('nfccutting'+i).value = JSON.stringify(data[4]);
                //     document.getElementById('nfcproduksi'+i).value = JSON.stringify(data[5]);
                //     document.getElementById('nkondisi'+i).value = JSON.stringify(data[6]);
                //     document.getElementById('npersiapan'+i).value = JSON.stringify(data[7]);
                // },
                cache: true
            }
        }).change(function(){
            var data = $(this).select2('data');
            data = data[0];
            document.getElementById('kategori'+i).value = data.kelompok;
            document.getElementById('progress'+i).value = data.progress;
            document.getElementById('nfccutting'+i).value = data.cutting;
            document.getElementById('nfcproduksi'+i).value = data.perhitungan;
            document.getElementById('nkondisi'+i).value = data.kondisi;
            document.getElementById('npersiapan'+i).value = data.persiapan;
        });
    }

    

    var i = $("#jml").val();

    function tambah(jml) {
        let i = parseInt(jml)+1;
        $("#jml").val(i);

        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><input type="text" id="tanggal${i}" name="tanggal[]" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?= date('d-m-Y'); ?>"></td>`;
        cols += `<td><select data-nourut="${i}" id="ibarang${i}" class="form-control input-sm id" name="ibarang[]" ></select></td>`;
        cols += `<td><input type="text" id="kategori${i}" name="kategori[]" ><input type="hidden" name="ikategori[]"></td>`;
        cols += `<td><input type="text" id="progress${i}" name="progress[]" ></td>`;
        cols += `<td><input type="text" id="nfccutting${i}" name="nfccutting[]" size="2" ></td>`;
        // cols += `<td><input type="text" id="nfcproduksi${i}" name="nfcproduksi[]" size="2" ></td>`;
        // cols += `<td><input type="text" id="nkondisi${i}" name="nkondisi[]" size="2" ></td>`;
        cols += `<td><input type="text" id="npersiapan${i}" name="npersiapan[]" size="2" ></td>`;
        cols += `<td><input type="text" id="eremark${i}" name="eremark[]"></td>`;
        cols += `<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        /* $('.swit'+i).swit(i); */
        $("#iunit"+i).attr("disabled", true);

        $("#tanggal"+i).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#ibarang'+ i).select2({
            placeholder: 'Cari Kode / Nama Produk',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/productwip/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                // success: function (data) {
                //     document.getElementById('kategori'+i).value = JSON.stringify(data[2]);
                //     document.getElementById('progress'+i).value = JSON.stringify(data[3]);
                //     document.getElementById('nfccutting'+i).value = JSON.stringify(data[4]);
                //     document.getElementById('nfcproduksi'+i).value = JSON.stringify(data[5]);
                //     document.getElementById('nkondisi'+i).value = JSON.stringify(data[6]);
                //     document.getElementById('npersiapan'+i).value = JSON.stringify(data[7]);
                // },
                cache: true
            }
        }).change(function(){
            var data = $(this).select2('data');
            data = data[0];
            document.getElementById('kategori'+i).value = data.kelompok;
            document.getElementById('progress'+i).value = data.progress;
            document.getElementById('nfccutting'+i).value = data.cutting;
            document.getElementById('nfcproduksi'+i).value = data.perhitungan;
            document.getElementById('nkondisi'+i).value = data.kondisi;
            document.getElementById('npersiapan'+i).value = data.persiapan;
        });
        
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        /* alert(i); */
        /* $('#jml').val(i); */
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select .id").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Jml harus lebih besar dari 0 !');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
    }) 

    $('#send').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '2', '<?= $dfrom . "','" . $dto; ?>');
        
    });
    $('#cancel').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '1', '<?= $dfrom . "','" . $dto; ?>');
        
    });
    $('#hapus').click(function(event) {
        statuschange('<?= $folder; ?>', $('#id').val(), '5', '<?= $dfrom . "','" . $dto; ?>');
        
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>