<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                   <div class="form-group row">
                        <label class="col-md-2">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-6">Keterangan</label>                            
                        <div class="col-sm-2">
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian;?>" readonly>   
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control" value="<?= $bagian->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" name="idocument" id="i_so" class="form-control" value="<?= $idocument;?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                             <input type="text" name="ddocument" id="ddocument" class="form-control" value="<?= $ddocument;?>" readonly>   
                        </div>
                        <div class="col-sm-6"> 
                             <textarea name="eremarkh" id="eremarkh" class="form-control"></textarea>   
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">  
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>  
                        <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledatax" class="table color-table success-table table-bordered class" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="47%">Nama Barang</th>
                                    <th width="10%">Warna</th>
                                    <th width="10%">Jumlah SO</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    if($datadetail){
                                        foreach ($datadetail as $key) {
                                        $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                    <td>
                                        <select data-nourut="<?= $i ;?>" id="idproduct<?= $i ;?>" class="form-control select2 input-sm" name="idproduct<?= $i ;?>" >
                                            <option value="<?= $key['id'].'|'.$key['e_color_name']?>"><?= $key["i_product_base"].' - '.$key["e_product_basename"].' ('.$key["e_color_name"].')';?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input readonly type="hidden" id="idcolor<?= $i ;?>" class="form-control input-sm inputitem" autocomplete="off" name="idcolor<?= $i ;?>" 
                                        value="<?= $key['id_color'] ?>">
                                        <input readonly type="text" id="e_color<?= $i ;?>" class="form-control input-sm inputitem" autocomplete="off" name="e_color<?= $i ;?>" 
                                        value="<?= $key['e_color_name'] ?>"></td>
                                    <td><input type="text" id="nquantity<?= $i ;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i ;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["n_quantity"];?>" onkeyup="angkahungkul(this);"></td>
                                    <td><input type="text" class="form-control input-sm" name="eremark<?= $i ;?>" id="eremark<?= $i ;?>" value="<?= $key["e_remark"];?>" placeholder="Isi keterangan jika ada!"/></td>
                                    <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                </tr>
                                <?php 
                                        }
                                    } 
                                ?> 
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        for (var i = 1; i <= $('#jml').val(); i++) {
                $('#idproduct'+ i).select2({
                    placeholder: 'Cari Kode / Nama Barang',
                    allowClear: true,
                    width: "100%",
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder.'/cform/barang/'); ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var query   = {
                                q         : params.term,
                                ibagian   : $('#ibagian').val(),
                                ddocument : $('#ddocument').val(),
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
                    var kode = $(this).val().split("|");

                    var z = $(this).data('nourut');
                    var ada = true;
                    for(var x = 1; x <= $('#jml').val(); x++){
                        if ($(this).val()!=null) {
                            if(($(this).val() == $('#idproduct'+x).val()) && (z!=x)){
                                swal ("kode barang tersebut sudah ada !!!!!");
                                ada = false;
                                break;
                            } else {
                                $('#e_color'+z).val(kode[1]);
                            }
                        }
                    }
                    if (!ada) {                
                        $(this).val('');
                        $(this).html('');
                    }else{
                        $('#nquantity'+z).focus();
                    }
                });
        }

        $("#send").click(function(event) {
          statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
        });        
    });

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
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

    /**
     * After Submit
     */

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++; 
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
         cols += `<td><input type="text" id="e_color${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="e_color${i}" readonly></td>`;
        cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct'+ i).select2({
            placeholder: 'Cari Kode / Nama Barang',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/barang/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q         : params.term,
                        ibagian   : $('#ibagian').val(),
                        ddocument : $('#ddocument').val(),
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
            var kode = $(this).val().split("|");

            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if(($(this).val() == $('#idproduct'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    } else {
                        $('#e_color'+z).val(kode[1]);
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                $('#nquantity'+z).focus();
            }
        });
    });

    /**
     * Hapus Detail Item
    */

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(i);
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });
</script>