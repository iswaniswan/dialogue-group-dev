<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Supplier</label>    
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" onchange="number();" required="">
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
                                <input type="text" name="inoteretur" id="inoteretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>     
                        <div class="col-sm-2">
                            <input id="dnoteretur" name="dnoteretur" class="form-control input-sm date" onchange="number();" value="<?= date("d-m-Y"); ?>" readonly>
                        </div>  
                         <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2"> 
                            </select>
                            <input type="hidden" id="esupplier" name="esupplier" class="form-control" value="" readonly>
                        </div>      
                    </div> 
                    <div class="form-group row">  
                        <label class="col-md-3">Nomor Referensi</label> 
                        <label class="col-md-2">Tanggal Referensi</label>
                        <label class="col-md-3">No Faktur Supplier</label> 
                        <label class="col-md-2">No Faktur</label> 
                        <label class="col-md-2">Tanggal Faktur</label>   
                        <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" onchange="getdetailreff(this.value);" disabled="true"> 
                            </select>
                            <input type="hidden" id="ibagianreff" name="ibagianreff" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control" value="" placeholder="<?= date('d-m-Y'); ?>"" readonly>   
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ifaksup" id="ifaksup" class="form-control" value="" placeholder="No. Faktur Supplier">   
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ifakpajak" id="ifakpajak" class="form-control" value="" readonly placeholder="No. Faktur Pajak">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dfakpajak" id="dfakpajak" class="form-control" value="" readonly placeholder="<?= date('d-m-Y'); ?>">   
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Total Retur</label>
                        <label class="col-md-2">Total DPP</label>    
                        <label class="col-md-7">Total PPN</label>
                        <div class="col-sm-3">                           
                            <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="0" placeholder="0" readonly>
                        </div>    
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="0" placeholder="0" readonly>
                        </div>
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="0" placeholder="0" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0"> 
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
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Harga Total</th>
                        <th class="text-center">DPP</th>
                        <th class="text-center">PPN</th>
                    </tr> 
                </thead>
                <tbody>                    
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#inoteretur').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();
    });

    $( "#inoteretur" ).keyup(function() {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl'     : $('#dnoteretur').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#inoteretur').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#inoteretur").attr("readonly", false);
        }else{
            $("#inoteretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $(document).ready(function () {
        $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/supplier/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireferensi").attr("disabled", false);
            $("#ireferensi").val("");
            $("#ireferensi").html("");
        });

        $('#ireferensi').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        isupplier : $('#isupplier').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    function getdetailreff(ireferensi){
        var ireferensi = $('#ireferensi').val();
        var isupplier = $('#isupplier').val();        
        $.ajax({
            type: "post",
            data: {
                'ireferensi': ireferensi,
                'isupplier' : isupplier
            },
            url: '<?= base_url($folder.'/cform/getdetailreff'); ?>',
            dataType: "json",
            success: function (data) {
                var dreferensi= data['head']['d_document']; 
                var ifakpajak = data['head']['i_faktur'];
                var dfakpajak = data['head']['d_faktur'];
                var esupplier = data['head']['e_supplier_name'];
                var bagianreff= data['head']['i_bagian'];
                $('#dreferensi').val(dreferensi);
                $('#ifakpajak').val(ifakpajak);
                $('#dfakpajak').val(dfakpajak);
                $('#esupplier').val(esupplier);
                $('#ibagianreff').val(bagianreff);
                $('#jml').val(data['dataitem'].length);
                $("#tabledatax tbody").remove();
                $("#tabledatax").attr("hidden", false);
                for (let a = 0; a < data['dataitem'].length; a++) {
                    var no = a+1;
                    var idmaterial   = data['dataitem'][a]['id_material'];
                    var imaterial    = data['dataitem'][a]['i_material'];
                    var ematerial    = data['dataitem'][a]['e_material_name'];
                    var nquantity    = data['dataitem'][a]['n_quantity'];
                    var vprice       = data['dataitem'][a]['v_price'];
                    var isatuan      = data['dataitem'][a]['i_satuan_code'];
                    var cols         = "";
                    var newRow = $("<tr>");
                    
                    cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'">';
                    cols += '<input style="width:40px;"  type="hidden" id="isatuan'+a+'" name="isatuan'+a+'" value="'+isatuan+'">';
                    cols += '<input type="hidden" id="idmaterial'+a+'" name="idmaterial'+a+'" value="'+idmaterial+'"></td>';
                    cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                    cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+ematerial+'"></td>';
                    cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+nquantity+'"></td>';
                    cols += '<td><input style="width:150px;" class="form-control" type="text" id="vprice'+a+'" name="vprice'+a+'" value="'+vprice+'" readonly></td>';
                    cols += '<td><input style="width:150px;" class="form-control" type="text" id="vpricetotal'+a+'" name="vpricetotal'+a+'" value="" readonly></td>';
                    cols += '<td><input style="width:100px;" class="form-control" type="text" id="dpp'+a+'" name="dpp'+a+'" value="" readonly></td>';
                    cols += '<td><input style="width:100px;" class="form-control" type="text" id="ppn'+a+'" name="ppn'+a+'" value="" readonly></td>';
                    // cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'" onclick="hitungnilai('+a+');"></td>';
               
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                    } 
                    hitung();            

                    $("#tabledatax").on("click", ".ibtnDel", function (event) {
                        $(this).closest("tr").remove();       
                    });
                },
                error: function () {
                    alert('Error :)');
                }
        });
    }

    function hitung() {
      var jml = $('#jml').val();
      var tot = 0;
      var dpp = 0;
      var ppn = 0;

      var totakhir = 0;
      var totakhirdpp   = 0;
      var totakhirppn   = 0;

      for (var i = 0; i < jml; i++) {
        var hrg = $('#vprice' + i).val();
        var qty = $('#nquantity' + i).val();

        total= qty*hrg;
        $('#vpricetotal' + i).val(formatcemua(total));
        dpp = (100/110 * parseFloat(total));
        $('#dpp' + i).val(formatcemua(dpp));
        ppn = (0.1 * dpp);
        $('#ppn' + i).val(formatcemua(ppn));

      totakhir    += parseFloat(formatulang($('#vpricetotal' + i).val()));
      totakhirdpp += parseFloat(formatulang($('#dpp' + i).val()));
      totakhirppn += parseFloat(formatulang($('#ppn' + i).val()));
      }
      
      $('#vtotalfa').val(formatcemua(totakhir));
      $('#vtotaldpp').val(formatcemua(totakhirdpp));
      $('#vtotalppn').val(formatcemua(totakhirppn));
    }

    // function hitungnilai(a) {
    //     var totalretur = formatulang($('#vtotalfa').val());
    //     var totaldpp   = formatulang($('#vtotaldpp').val());
    //     var totalppn   = formatulang($('#vtotalppn').val());
    //     // if (document.getElementById('cek' + a).checked == true) {
    //         var totalr = formatulang($('#vpricetotal' + a).val());
    //         totakhir   = parseFloat(totalretur) + parseFloat(totalr);

    //         var dpp    = formatulang($('#dpp' + a).val());
    //         totakhirdpp= parseFloat(totaldpp) + parseFloat(dpp);

    //         var ppn     = formatulang($('#ppn' + a).val());
    //         totakhirppn = parseFloat(totalppn) + parseFloat(ppn);
    //     // } else {
    //     //     var totalr = formatulang($('#vpricetotal' + a).val());
    //     //     totakhir   = parseFloat(totalretur) - parseFloat(totalr);

    //     //     var dpp     = formatulang($('#dpp' + a).val());
    //     //     totakhirdpp = parseFloat(totaldpp) - parseFloat(dpp);

    //     //     var ppn     = formatulang($('#ppn' + a).val());
    //     //     totakhirppn = parseFloat(totalppn) - parseFloat(ppn);
    //     // }
    //     $('#vtotalfa').val(formatcemua(totakhir));
    //     $('#vtotaldpp').val(formatcemua(totakhirdpp));
    //     $('#vtotalppn').val(formatcemua(totakhirppn));
    // }

    $( "#submit" ).click(function(event) {
        var textinputs = document.querySelectorAll('input[type=checkbox]'); 
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else if (textinputs.length == empty.length) {
            swal("Pilih Minimal 1 Retur Pembelian!");
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
</script>
