<link href="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $data->i_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm date" name="ddocument" id="ddocument" value="<?= date("d-m-Y", strtotime($data->d_document)); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" >
                                <option value="<?= $data->i_bagian; ?>"><?= $data->e_bagian_name; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" onclick="tambah($('#jml').val());" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
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
            <div style="width:1200px; height:auto; overflow:auto;">
                <div class="table-responsive" style="width:1400px;">
                    <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" >No</th>
                                <th class="text-center" >Tanggal</th>
                                <th class="text-center" >Nama Barang</th>
                            <!-- <th class="text-center" width="15%">Konversi ke Set</th>-->
                                <th class="text-center" >Qty</th>
                                <th class="text-center" >Kategori Unit</th></th></th>
                                <th class="text-center" >Unit Jahit</th></th>
                                <th class="text-center" >Group</th></th></th>
            
                                <th class="text-center" >Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 0;
                            foreach ($detail as $key) {
                                $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                    <td><input type="text" id="tanggal<?= $i ;?>" name="tanggal[]" class="form-control input-sm date" value="<?= date("d-m-Y", strtotime($key->d_schedule));?>" size="4"></td>
                                    <td><select data-nourut="<?= $i ;?>" id="ibarang<?= $i ;?>" class="form-control input-sm id" name="ibarang[]" ><option value="<?= $key->id_product_wip.'|'.$key->i_color;?>"><?= $key->i_product_wip.'-'.$key->e_product_wipname.'-'.$key->e_color_name;?></option></select></td>
                                    <td><input type="text" id="nqty<?= $i ;?>" name="nqty[]" size="4" value="<?= $key->n_quantity_wip;?>"></td>
                                    <td><select data-nourut="<?= $i ;?>" name="ikategori[]" id="ikategori<?= $i ;?>" class="form-control input-sm id" ><option value="<?= $key->id_kategori_jahit; ?>"><?= $key->e_nama_kategori; ?></option></select></td>
                                    <td><select data-nourut="<?= $i ;?>" name="iunit[]" id="iunit<?= $i ;?>" class="form-control input-sm id" ><option value="<?= $key->id_unit_jahit; ?>"><?= $key->e_nama_unit; ?></option></select></td>
                                    <td><input type="text" id="eremark<?= $i ;?>" name="eremark[]" value="<?= $key->e_remark;?>"></td>
                                    <td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                </tr>
                            <?php } ?>
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
            placeholder: 'Cari Bagian',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getbagian'); ?>',
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

    })
    
        $("#ddocument").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
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
                cache: true
            }
        });

        $('#ikategori'+ i).select2({
            placeholder: 'Cari Kategori Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkategori'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(){
            $("#iunit"+i).attr("disabled", false);
        });

        //var kategori = $('#ikategori'+i).val();

        $('#iunit'+ i).select2({
            placeholder: 'Cari Unit Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getunit'); ?>',
                dataType: 'json',
                delay: 250,          
                data: function (params) {
                    var query = {
                        q: params.term,
                        kategori : $('#ikategori'+i).val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
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
        cols += `<td><input data-nourut="${i}" type="text" id="tanggal${i}" name="tanggal[]" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?= date('d-m-Y'); ?>"></td>`;
        cols += `<td><select data-nourut="${i}" id="ibarang${i}" class="form-control input-sm id" name="ibarang[]" ></select></td>`;
        cols += `<td><input data-nourut="${i}" type="text" id="nqty${i}" name="nqty[]" size="4" ></td>`;
        cols += `<td><select data-nourut="${i}" name="ikategori[]" id="ikategori${i}" class="form-control input-sm id" ></select></td>`;
        cols += `<td><select data-nourut="${i}" name="iunit[]" id="iunit${i}" class="form-control input-sm id" ></select></td>`;
        cols += `<td><input data-nourut="${i}" type="text" id="eremark${i}" name="eremark[]"></td>`;
        cols += `<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        /* $('.swit'+i).swit(i); */

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
                cache: true
            }
        });

        $('#ikategori'+ i).select2({
            placeholder: 'Cari Kategori Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkategori'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(){
            $("#iunit"+i).attr("disabled", false);
        });

        //var kategori = $('#ikategori'+i).val();

        $('#iunit'+ i).select2({
            placeholder: 'Cari Unit Jahit',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getunit'); ?>',
                dataType: 'json',
                delay: 250,          
                data: function (params) {
                    var query = {
                        q: params.term,
                        kategori : $('#ikategori'+i).val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>