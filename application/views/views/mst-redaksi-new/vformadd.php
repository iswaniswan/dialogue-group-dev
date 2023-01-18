 <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kode Barang</label>
                        <label class="col-md-9">Nama Barang</label>
                        
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="iproduct" id="iproduct" readonly="" class="form-control input-sm">
                                <input type="hidden" id="idproduct" name="idproduct"  required="" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <select name="product" id="product" class="form-control select2">
                            </select>
                            <input type="hidden" id="icolor" name="icolor"  required="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea id="eremarkh" placeholder="Isi Keterangan Jika Ada!!!" name="eremarkh" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
            </div>
        </div>
    </div>
</div>

<div class="white-box" id="detail">
    <div class="row">
        <div class="col-sm-2">
            <h3 class="box-title m-b-0">Panel & Redaksi </h3> 
        </div>
        <div class="col-sm-9">
            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
        </div>
        <div class="col-sm-1" style="text-align: right;">
            <?= $doc; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="30%;">Jenis Kain</th>
                            <th class="text-center" width="15%;">Bagian Panel</th>
                            <th class="text-center" width="15%;">Kode Panel</th>
                            <th class="text-right" width="8%;">Qty Penyusun</th>
                            <th class="text-right" width="8%;">Panjang <sup>cm</sup></th>
                            <th class="text-right" width="8%;">Lebar <sup>cm</sup></th>
                            <th class="text-center" width="4%;">Print</th>
                            <th class="text-center" width="4%;">Bordir</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center" width="3%;">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        $('#product').select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
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
        }).change(function(event){

            var produk = $(this).val().split("-");
            $("#idproduct").val(produk[0]);
            $("#iproduct").val(produk[1]);
            $("#ecolor").val(produk[3]);
            $("#icolor").val(produk[2]);

            var jml = $("#jml").val();
            if(jml > 0){
                for(i=1;i<=jml;i++){
                    $("#ipanel"+i).val(produk[1]);
                    $("#ebagian"+i).val('');
                }
            }

        })

        // showCalendar('.date', 1830, 0);
    });

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
     
    /**
    * Tambah Item
    */
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        //alert("tes");
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $('<tr id="tr'+i+'">');
        var ipanel = $("#iproduct").val();
        var cols   = "";
        cols += '<td class="text-center"><spanx id="snum'+i+'">'+no+'</spanx></td>';
        cols += '<td><select data-nourut="'+ i +'" id="imaterial'+ i +'" class="form-control input-sm" name="imaterial'+ i +'"></select></td>';
        cols += '<td><input data-nourut="'+ i +'" class="form-control qty input-sm" autocomplete="off" type="text" name="ebagian'+ i +'" id="ebagian'+ i +'" style="text-transform: uppercase"></td>';
        cols += '<td>< hidden input class="form-control input-sm" readonly type="text" id="ipanel'+ i +'" name="ipanel'+ i +'" value="'+ ipanel +'"></td>';
        cols += `<td><input type="text" id="n_qty_penyusun${i}" class="form-control text-right input-sm" autocomplete="off" name="n_qty_penyusun${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_panjang_cm${i}" class="form-control text-right input-sm" autocomplete="off" name="n_panjang_cm${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="n_lebar_cm${i}" class="form-control text-right input-sm" autocomplete="off" name="n_lebar_cm${i}" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += '<td class="text-center"><input type="checkbox" id="print'+ i +'" name="print'+ i +'"></td>';
        cols += '<td class="text-center"><input type="checkbox" id="bordir'+ i +'" name="bordir'+ i +'"></td>';
        cols += '<td><input class="form-control input-sm" type="text" name="eremark'+ i +'" id="eremark'+ i +'" placeholder="Isi keterangan jika ada!"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        cols += `</tr>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#imaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama WIP',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
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
        });
        // change(function(event) {
        //     /**
        //      * Cek Barang Sudah Ada
        //      * Get Harga Barang
        //      */
        //     var z = $(this).data('nourut');
        //     var ada = true;
        //     for(var x = 1; x <= $('#jml').val(); x++){
        //         if ($(this).val()!=null) {
        //             if((($(this).val()) == $('#imaterial'+x).val()) && (z!=x)){
        //                 swal ("kode barang tersebut sudah ada !!!!!");
        //                 ada = false;
        //                 break;
        //             }
        //         }
        //     }
        //     if (!ada) {                
        //         $(this).val('');
        //         $(this).html('');
        //     }
        // });
        $('#ebagian'+i).keyup(function event(){
            var id = $(this).data('nourut');
            var ebagian = $("#ebagian" + id).val(); 
            var imaterial = $("#imaterial" + id).text(); 
            const myArray = imaterial.split("-");
            if(ebagian == ""){
                $("#ipanel" + id).val(ipanel);    
            }
            else {
            var matches = ebagian.match(/\b(\w)/g);
            var bagian = matches.join('');
            var upper  = bagian.toUpperCase();
            var ipanel = $("#iproduct").val();
            $("#ipanel" + id).val(ipanel + '_'+ myArray[0].trim()+ '_' + upper);
            }
        });
        $('#ijenis'+ i).select2();
    });

    /**
     * Hapus Detail Item
     */
    
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
                        swal('Material tidak boleh kosong!');
                        ada = true;
                    }
                });

                for(i=1;i<=jml;i++){
                var cek         = document.getElementById("n_qty_penyusun"+i);
                var nquantity   = document.getElementById("n_qty_penyusun"+i).value;
                if(cek){
                    if (nquantity=='' || nquantity==null || nquantity== 0) {
                        swal('Quantity Penyusun Tidak Boleh Kosong atau 0!');
                        ada = true ;
                        break;
                        }    
                    }
                }
                
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
        
    }
</script>