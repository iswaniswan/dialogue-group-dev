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
                                    <th width="37%">Nama Barang</th>
                                    <th width="10%">Skin</th>
                                    <th width="10%">Status Packing</th>
                                    <th width="10%">Jumlah SO</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                        $i++;
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
                                // $('#e_color'+z).val(kode[1]);
                                $('#idcolor'+z).val(kode[2]);
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
            // $("#tabledatax tbody tr").each(function() {
            //     $(this).find("td select").each(function() {
            //         if ($(this).val()=='' || $(this).val()==null) {
            //             swal('Kode barang tidak boleh kosong!');
            //             ada = true;
            //         }
            //     });
            // });
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
        var newRow = $('<tr id="tr'+i+'">');
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" name="idproduct${i}" class="form-control input-sm" ></select></td>`;
        //  cols += `<td><input type="text" id="e_color${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="e_color${i}" readonly>`;
        cols += `<td><select data-urut="${i}" id="skin${i}" name="skin${i}" class="form-control input-sm" ></select></td>`;
         cols += `<td><input type="hidden" id="idcolor${i}" name="idcolor${i}" class="form-control text-right input-sm inputitem" autocomplete="off" readonly>`;
         cols += `<select id="statusbarang${i}" name="statusbarang${i}"  class="form-control input-sm" required>
                <option value="Sudah Packing">Sudah Packing</option>
                <option value="Belum Packing">Belum Packing</option>
         </select></td>`;
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
            var mm = $(this).val();
            var z = $(this).data('nourut');

            if (mm != null){
                var kode = $(this).val().split("|");
                // $('#skin'+z).val('');
                // $('#skin'+z).html('');
                var ada = true;
                // var m = $('#idproduct'+x).val();
                for(var x = 1; x <= $('#jml').val(); x++){
                    if ($(this).val()!=null) {
                        if(($(this).val() == $('#idproduct'+x).val()) && (z!=x)){
                            swal ("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        } else {
                            // $('#e_color'+z).val(kode[1]);
                            $('#idcolor'+z).val(kode[2]);
                            $('#skin'+z).prop('disabled', true);
                // $('#statusbarang'+z).prop('disabled', true);

                                    
                        }
                    }
                    // else if (mm==null){
                    //         $('#skin'+z).prop('disabled', false);
                    // }

                }

                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                }else{
                    $('#nquantity'+z).focus();
                }
            }else {
                $('#skin'+z).prop('disabled', false);
            }
            
        });


        $('#skin'+ i).select2({
            placeholder: 'Cari Kode / Nama Barang',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/barangskin/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q         : params.term,
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
            //  */
            var z = $(this).data('urut');

            $('#idproduct'+z).val(null);
            $('#idproduct'+z).html(null);
            $('#idcolor'+z).val(null);
            $('#idcolor'+z).html(null);

            // var m = $(this).val();
// alert(m);
            var ada = true;

            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if(($(this).val() == $('#skin'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
            // $('#statusbarang'+z).prop('disabled', true);
                        break;
                    } else {
                        // $('#e_color'+z).val(kode[1]);
                        // $('#idcolor'+z).val(kode[2]);
                         $('#idproduct'+z).prop('disabled', true);
                                // alert(kode[0]);
                    }
                }

                if ($(this).val()==null){
                         $('#idproduct'+z).prop('disabled', false);
                }
            }
            // $('#statusbarang'+z).prop('disabled', true);
            
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                // $('#nquantity'+z).focus();
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