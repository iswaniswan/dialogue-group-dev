<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                   <div class="form-group row">
                            <label class="col-md-4">Pembuat Dokumen</label>
                            <label class="col-md-4">Customer</label>
                            <label class="col-md-2">Bulan</label>
                            <label class="col-md-2">Tahun</label>
                            
                            <div class="col-sm-4">
                                <input type="hidden" name="id" id="id" class="form-control" value="<?php if($head) echo $head->id;?>" readonly> 
                                <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian;?>" readonly>   
                                <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name;?>" readonly>
                            </div>

                            <div class="col-sm-4">
                                <input type="hidden" name="icustomer" id="icustomer" class="form-control" value="<?= $customer->id;?>" readonly>   
                                <input type="text" name="e_customer_name" id="e_customer_name" class="form-control input-sm" value="<?= $customer->e_customer_name;?>" readonly>
                            </div>

                            <div class="col-sm-2">
                                 <input type="hidden" name="ibulan" id="ibulan" class="form-control" value="<?= $customer->ibulan;?>" readonly>   
                                 <input type="text" name="bulan" id="bulan" class="form-control input-sm" value="<?= $customer->bulan;?>" readonly>   
                            </div>
                            <div class="col-sm-2">
                             <input type="text" name="tahun" id="tahun" class="form-control input-sm" value="<?= $customer->tahun;?>" readonly>   
                            </div>
                    </div>  

                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                        <?php //if (($customer->tahun.$customer->ibulan) > date('Ym')) { 
                            if($head) {
                                if ($head->i_status == '1' || $head->i_status == '3') {
                                    echo '<button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>'. '     ';
                                    echo '<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>'. '     ';
                                    echo '<button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>'. '     ';
                                }
                            } else {
                                echo '<button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>'. '     ';
                                echo '<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>'. '     ';
                                echo '<button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>'. '     ';
                            }
                        ?>
                       <!--  <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button>   -->
                        <!-- <button type="button" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approve","#main")'><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button> -->
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
               <!--  <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Barang</label>
                        <div class="col-sm-6">
                            <input type="text" name="ekodebrg" id="ekode" class="form-control date" value="<?= $barang->e_material_name;?>"disabled = 't'>
                        </div>
                    </div>
                </div> -->
        </div>
                        </div>
                        </div>
                        </div>

                <div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="tabledatax" class="table color-table success-table table-bordered class" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="35%">Nama Barang</th>
                                    <th width="15%">Kategori Penjualan</th>
                                    <th width="10%" class="text-right">Harga</th>
                                    <th width="15%" class="text-right">Rata<sup>2</sup> OP (3 bln)</th>
                                    <th width="10%" class="text-right">Jumlah FC</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%">Act</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($datadetail as $key) {
                                    $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                    <td>
                                        <select data-nourut="<?= $i ;?>" id="idproduct<?= $i ;?>" class="form-control select2 input-sm" name="idproduct<?= $i ;?>" >
                                            <option value="<?= $key['id_product_base']?>"><?= $key["i_product_base"].' - '.$key["e_product_basename"].' - '.$key["e_color_name"];?></option>
                                        </select>
                                    </td>
                                    <td><input type="text" id="category_penjualan<?= $i ;?>" class="form-control input-sm" readonly name="category_penjualan<?= $i ;?>" value="<?= $key["e_class_name"];?>"></td>
                                    <td><input type="text" id="price<?= $i ;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="price<?= $i ;?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key["v_harga"];?>" onkeyup="angkahungkul(this);"></td>
                                    <td><input type="text" id="rata<?= $i ;?>" class="form-control text-right input-sm" autocomplete="off" name="rata<?= $i ;?>" readonly value="<?= $key["n_rata2"];?>"></td>
                                    <td><input type="text" id="nquantity<?= $i ;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity<?= $i ;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["n_quantity"];?>" onkeyup="angkahungkul(this);"></td>
                                    <td><input type="text" class="form-control input-sm" name="eremark<?= $i ;?>" id="eremark<?= $i ;?>" value="<?= $key["e_remark"];?>" placeholder="Isi keterangan jika ada!"/></td>
                                    <td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                </tr>
                                <?php } 
                            ?> 
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function () {
    for (var i = 1; i <= $('#jml').val(); i++) {
            $('#idproduct'+ i).select2({
                placeholder: 'Cari Kode / Nama Barang Jadi',
                /* allowClear: true,
                width: "100%", */
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
            }).change(function(event) {
                var z = $(this).data('nourut');
                var ada = true;
                for(var x = 1; x <= $('#jml').val(); x++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                            swal ("kode barang tersebut sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                }
            });
    }
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
            $(this).find("td .inputitem").each(function() {
                if ($(this).val()=='' || $(this).val()==null || $(this).val()=='0') {
                    swal('Quantity & Harga Tidak Boleh Kosong Atau 0!');
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

$('#send').click(function(event) {
    statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
});

function cekselisih(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var saldoakhir = Number($('#saldoakhir'+i).val());
        var stokopname = Number($('#stokopname'+i).val());

        total = stokopname-Math.abs(saldoakhir);
        $('#selisih'+i).val(total); 

    }
}

var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++; 
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
        cols += `<td><input type="text" id="category_penjualan${i}" class="form-control input-sm" readonly name="category_penjualan${i}"></td>`;
        cols += `<td><input type="text" id="price${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="price${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" id="rata${i}" readonly class="form-control text-right input-sm" autocomplete="off" name="rata${i}" value="0"></td>`;
        cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct'+ i).select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
            /* allowClear: true,
            width: "100%", */
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
        }).change(function(event) {
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                $.ajax({
                    type: "POST",
                    data:{
                        id:$(this).val(),
                        tahun : $('#tahun').val(),
                        bulan : $('#ibulan').val(),
                    },
                    url: '<?= base_url($folder.'/cform/get_product_detail/'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#category_penjualan'+z).val(data[0]['e_class_name']);
                        $('#price'+z).val(data[0]['v_unitprice']);
                        $('#rata'+z).val(data[0]['n_rata2']);
                        $('#nquantity'+z).focus();
                    },
                    error: function () {
                        swal("Maaf", "Data Gagal Disimpan :(", "error");
                    }
                });
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