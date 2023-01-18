<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/updatedetail'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-6">Nama Barang</label>
                        <label class="col-md-3">Brand</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="id" id="id" value="<?=$data->id;?>">
                            <input type="hidden" name="ikodebrgold" id="ikodebrgold" value="<?= $data->i_product_wip;?>">
                            <input type="text" required="" name="ikodebrg" id="ikodebrg" autocomplete="off" class="form-control input-sm" onkeyup="gede(this); clearcode(this);" maxlength="7" value="<?= $data->i_product_wip;?>" placeholder="Harus 7 Digit">
                            <span class="notekode" hidden="true"><b> * Kode Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" placeholder="Harus Diisi!!!" name="enamabrg" class="form-control input-sm" maxlength="300" required="" value="<?= $data->e_product_wipname;?>" onkeyup="gede(this);">
                        </div>
                        <div class="col-sm-3">
                            <select name="ibrand" id="ibrand" class="form-control select2" required="">
                                <?php if ($brand) {
                                    foreach ($brand as $ibrand):?>
                                        <option value="<?= $ibrand->i_brand;?>" <?php if ($ibrand->i_brand==$data->i_brand) {?> selected <?php } ?>> <?= $ibrand->e_brand_name;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Group Barang</label>
                        <label class="col-md-6">Kategori Barang</label>
                        <div class="col-sm-6">
                            <select name="igroupbrg" id="igroupbrg" class="form-control select2" required="">
                                <option value="<?= $data->i_kode_group_barang;?>"><?= $data->e_nama_group_barang;?></option>
                            </select>
                        </div>  
                        <div class="col-sm-6">
                            <select name="ikelompok" id="ikelompok" class="form-control select2" required="">
                                <option value="<?= $data->i_kode_kelompok;?>"><?= $data->e_nama_kelompok;?></option>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Status Produksi</label>
                        <label class="col-md-6">Jenis Satuan</label>
                        <div class="col-sm-6">
                            <select name="istatusproduksi" id="istatusproduksi" class="form-control select2">
                                <?php if ($statusproduksi) {
                                    foreach ($statusproduksi as $istatusproduksi):?>
                                        <option value="<?= $istatusproduksi->i_status_produksi;?>"><?= $istatusproduksi->e_status_produksi;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>   
                        <div class="col-sm-6">
                            <select name="isatuan" id="isatuan" class="form-control select2">
                                <?php if ($satuan_barang) {
                                    foreach ($satuan_barang as $isatuan):?>
                                        <option value="<?= $isatuan->i_satuan_code;?>"><?= $isatuan->e_satuan;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>   
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Supplier Utama</label>
                        <div class="col-sm-12">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                                <option value="<?= $data->i_supplier;?>"><?= $data->e_supplier_name;?></option>
                            </select>        
                        </div> 
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea name="edeskripsi" class="form-control" placeholder="Keterangan"><?= $data->e_remark;?></textarea>
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi()"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>     
                </div>   
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Sub Kategori Barang</label>
                        <label class="col-md-6">Style</label>
                        <div class="col-sm-6">
                            <select name="ijenisbrg" id="ijenisbrg" class="form-control select2">
                                <option value="<?= $data->i_type_code;?>"><?= $data->e_type_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="istyle" id="istyle" class="form-control select2">
                                <?php foreach ($style as $istyle):?>
                                    <option value="<?= $istyle->i_style;?>" <?php if ($istyle->i_style==$data->i_style) {?> selected <?php } ?>><?= $istyle->e_style_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Panjang</label>
                        <label class="col-md-3">Lebar</label>
                        <label class="col-md-3">Tinggi</label>
                        <label class="col-md-3">Satuan</label>
                        <div class="col-sm-3">
                            <input type="text" name="npanjang" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_panjang;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="nlebar" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_lebar;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ntinggi" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_tinggi;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" name="isatuanukuran" id="isatuanukuran" class="form-control input-sm" maxlength="30"  value="<?= $data->i_satuan_ukuran;?>" readonly="">
                            <input type="text" name="esatuanukuran" id="esatuanukuran" class="form-control input-sm" maxlength="30"  value="<?= $data->e_satuan_name;?>" readonly="">
                        </div>       
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Berat</label>
                        <label class="col-md-3">Satuan Berat</label> 
                        <label class="col-md-6">Warna</label> 
                        <div class="col-sm-3">
                            <input type="text" name="nberat" class="form-control input-sm" maxlength="30" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $data->n_berat;?>" onkeyup="angkahungkul(this);">
                        </div>
                        <div class="col-sm-3">
                            <select name="isatuanberat" id="isatuanberat" class="form-control select2">
                                <?php if ($satuan_berat) {                                        
                                    foreach ($satuan_berat as $isatuanberat):?>
                                        <option value="<?= $isatuanberat->i_satuan_code;?>" <?php if ($isatuanberat->i_satuan_code==$data->i_satuan_berat) {?> selected <?php } ?>><?= $isatuanberat->e_satuan;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="icolor[]" id="icolor" multiple="multiple" class="form-control select2" data-placeholder="Pilih Warna">
                                <?php if ($color) {
                                    foreach ($color->result() as $row):?>
                                        <option value="<?= $row->i_color;?>" selected><?= $row->e_color_name;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>     
                </div>
            </div>
        </div>
    </div>
</div>
<?php $i = 0;?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3><br>
        <button type="button" id="addrow" class="btn btn-info btn-sm" hidden=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button><br><br>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:40%;">Material</th>
                        <th class="text-center" style="width:20%;">Bagian</th>
                        <th class="text-center" style="width:20%;">Satuan</th>
                        <th class="text-center" style="width:15%;">Qty</th>
                        <th class="text-center" style="width:5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0; 
                    if($datadetail){
                        foreach ($datadetail as $key) { $i++; ?>
                        <tr>
                            <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                            <td>
                                <select data-nourut="<?= $i ;?>" class="form-control select2" id="imaterial<?=$i;?>" name="imaterial[]">
                                    <option value="<?=$key->id_material;?>"><?= $key->i_material.' - '.$key->e_material_name;?></option>
                                </select>
                                <input type="hidden" id="idproductwip<?=$i;?>" name="idproductwip[]" class="form-control" value="<?=$key->id_product_wip;?>">
                            </td>
                            <td>
                                <input type="text" id="esatuanmaterial<?=$i;?>" name="esatuanmaterial[]" class="form-control input-sm" value="<?= $key->e_satuan_name;?>">
                            </td>
                            <td>
                                <input type="text" id="bagian<?=$i;?>" name="bagian[]" class="form-control input-sm" value="<?= $key->bagian;?>">
                            </td>
                            <td class="text-right">
                                <input type="text" id="nquantity<?=$i;?>" name="nquantity[]" class="form-control input-sm text-right" value="<?= $key->n_quantity;?>">
                            </td>
                            <td class="text-center">
                                <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                            </td>
                        </tr>
                    <?php } 
                    }?>
                <input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>

<script>
    var i = $('#jml').val(); 
    $("#addrow").on("click", function () {
        i++;
        var no     = $('#tabledatax tr').length;
        $("#jml").val(i);
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+i+'">'+no+'</spanx></td>';
        cols += '<td><select data-nourut="<?= $i ;?>" id="imaterial'+i+ '" class="form-control input-sm" name="imaterial[]" onchange="getsatuanmaterial('+i+');"></select></td>';
        cols += '<td><input type="text" id="bagian'+i+'" name="bagian[]" class="form-control input-sm" value="" placeholder="Bagian"></td>';
        cols += '<td><input type="hidden" id="isatuanmaterial'+i+'" name="isatuanmaterial[]" class="form-control input-sm" value="" placeholder="Satuan Barang"><input type="text" id="esatuan'+i+'" name="esatuan[]" class="form-control input-sm" value="" placeholder="Satuan Barang"></td>';
        cols += '<td><input type="text" id="nquantity'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#imaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: '100%',
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
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
        }).change(function(event) {
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var ada = true;
            var z = $(this).data('nourut');
            for(var x = 0; x < $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#imaterial'+x).val()) && (z!=x)){
                        swal ("kode material tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
            }
        });
    });

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        $('#jml').val(i);
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getsatuanmaterial(id){
        var idmaterial = $('#imaterial'+id).val();
        $.ajax({
            type: "post",
            data: {
                'idmaterial' : idmaterial,
            },
            url: '<?= base_url($folder.'/cform/getsatuanmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isatuan'+id).val(data[0].i_satuan_code);
                $('#esatuan'+id).val(data[0].e_satuan_name);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $(".select2").select2();
        $('#igroupbrg').select2({
            placeholder: 'Pilih Group',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getgroup'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })

        $('#ikelompok').select2({
            placeholder: 'Pilih Kelompok',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        igroup : $('#igroupbrg').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenisbrg').val('');
            $('#ijenisbrg').html('');
        });

        $('#ijenisbrg').select2({
            placeholder: 'Pilih Sub Kategori Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getjenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikelompok : $('#ikelompok').val(),
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
        })

        $('#isupplier').select2({
            placeholder: 'Pilih Supplier Utama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/supplier'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })

        $('#icolor').select2({
            placeholder: 'Pilih Color',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcolor'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })
        
        $( "#ikodebrg" ).keyup(function() {
            $.ajax({
                type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && $('#ikodebrg').val() != $('#ikodebrgold').val()) {
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
    
    $('#isatuan').change(function(event) {
        $.ajax({
            type: "post",
            data: {
                'isatuan': $(this).val()
            },
            url: '<?= base_url($folder.'/cform/getsatuan'); ?>',
            dataType: "json",
            
            success: function (data) {
                $('#isatuanukuran').val(data[0].i_satuan_code); 
                $('#esatuanukuran').val(data[0].e_satuan); 
            },
            error: function () {
                alert('Error :)');
            }
        });
    })

    for(var i = 1; i <= $('#jml').val(); i++){
        $('#imaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: '100%',
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
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
        }).change(function(event) {
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var ada = true;
            var z = $(this).data('nourut');
            for(var x = 0; x < $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#imaterial'+x).val()) && (z!=x)){
                        swal ("kode material tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
            }
        });
    }
});

    function validasi(){
        var igroupbrg = $('#igroupbrg').val();
        var ikelompok = $('#ikelompok').val();
        var ijenisbrg = $('#ijenisbrg').val();
        var ikodebrg  = $('#ikodebrg').val();
        var enamabrg  = $('#enamabrg').val();
        var isatuan   = $('#isatuan').val();
        if (igroupbrg == '' || igroupbrg == null) {
            swal('Group Barang Belum dipilih');
            return false;
        }else  if (ikelompok == '' || ikelompok == null) {
           swal('Kategori Barang Belum dipilih');
           return false;
       }else  if (ijenisbrg == '' || ijenisbrg == null) {
           swal('Jenis Barang Belum dipilih');
           return false;
       }else  if (enamabrg == '') {
           swal('Nama Barang Belum diisi');
           return false;
       }else  if (isatuan == '' || isatuan == null) {
           swal('Jenis Satuan Belum dipilih');
           return false;
       }else{
        return true;
    }
}
</script>
