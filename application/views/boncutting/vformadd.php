 <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
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
                                <input type="text" name="ibonk" id="ibonk" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date"  required="" readonly value="<? echo date("d-m-Y");?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang</label>                       
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea id= "eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2"></i>Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2" onclick="getproduct($('#jml').val());"><i class="fa fa-plus mr-2"></i>Item</button>
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>                            
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
            </div>
        </div>
    </div>

<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
   <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th style="width: 45%;">Nama Barang</th>
                        <th class="text-right" style="width: 10%;">QTY Kirim</th>
                        <th>Keterangan</th>
                        <th class="text-center" style="width: 5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date',null,0);
        number();
    });
     
    /**
    * Tambah Item
    */

    

    function getproduct(i){
        i=parseInt(i)+1;
        $("#jml").val(i);
        var no = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        // cols += `<td>
        //             <input type="text" id="bagianpanel${i}" class="form-control text-right input-sm inputitem" readonly>
        //         </td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}"></select><input type="hidden" id="idpanel${i}" name="idpanel[]"></td>`;
        cols += `<td>
                    <input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);">
                    <input type="hidden" id="nquantity_awal${i}" name="nquantity_awal[]" value="0">
                </td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark[]" id="eremark${i}" placeholder="Isi keterangan jika ada!"/><input type="hidden" name="vprice${i}" id="vprice${i}" value="0"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct' + i).select2({
            placeholder: 'Cari Kode / Nama Panel',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
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
            var z = $(this).data('nourut');
            var ada = true;
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if ((($(this).val()) == $('#idproduct' + x).val()) && (z != x)) {
                        swal("Kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        $(this).empty();
                        break;
                    }
                }
            }
            var produk = $(this).val();
            $('#idpanel' + i).val(produk);
            getqty(z);
        });
    }

    function getqty(id){
        var idpanel = $('#idproduct'+id).val();
        var ibagian = $('#itujuan').val();
        var jml = $('#jml').val();

        $.ajax({
                    type: "post",
                    data: {
                        'id': idpanel,
                        'bagian': ibagian,
                    },
                    url: '<?= base_url($folder . '/cform/getqty'); ?>',
                    dataType: "json",
                    success: function(data) {
                        $('#nquantity_awal' + id).val(data.n_saldo_akhir);
                    },
                    error: function() {
                        swal('Data kosong : (');
                    }
                });
    }

    function cekqty(id){
        var qtyawal = parseFloat($('#nquantity_awal' + id).val());
        var qty = parseFloat($('#nquantity' + id).val());

        console.log(qtyawal + ' - ' + qty);

        if(qty > qtyawal){
            swal('QTY Tidak Boleh Lebih Besar Dari Saldo Akhir '+ qtyawal);
            $('#nquantity'+id).val(0);
        }
    }

    /**
    * Hapus Detail Item
    */    
    function hapusdetail(x) {
        $("#tabledatax tbody").each(function() {
            $("tr.del"+x).remove();
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        // let i = $("#jml").val();

        // $('#jml').val(i);
        del();
    });

    function del() {
        obj=$('#tabledatax tr').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    }

     //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dbonk').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ibonk').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ibonk").attr("readonly", false);
        }else{
            $("#ibonk").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $( "#ibonk" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
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
    }

    function kalikan(){
        var id = $("#nquantity"+id).data('noqty');
        var qtyparent = $("#nquantity"+id).val();
        var limiter = $("#limiter"+id).val();
        var penyusun = $(".del"+id+" .npenyusun").val();
        // console.log(penyusun);
        // $("#nqty"+id).val(qtyparent)

        var jml = $("#jml").val();
        for(i=1; i<= jml; i++){
            var product = $("#idproduct"+i).val();
            var qtywip = $("#nquantity"+i).val();
            // console.log(product + " / " + qtywip);
            var x = 1;

            $("#tabledatax tbody tr td .inputqty_"+product).each(function() {
                    var npenyusun = $(".material_"+product+"_"+x).val();
                    // console.log(product + " / " + qtywip + " / " +npenyusun); 
                    var kali = parseFloat(qtywip) * parseFloat(npenyusun);

                    $(".sisa_"+product+"_"+x).val(kali);
                   x++;
            });
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

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
</script>