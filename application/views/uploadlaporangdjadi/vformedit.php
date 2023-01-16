<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                   <div class="form-group row">
                            <label class="col-md-4">Pembuat Dokumen</label>
                            <label class="col-md-2">Bulan</label>
                            <label class="col-md-2">Tahun</label>
                            <div class="col-md-4"></div>
                            
                            <div class="col-sm-4">
                                <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $bagian->i_bagian;?>" readonly>   
                                <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control input-sm" value="<?= $bagian->e_bagian_name;?>" readonly>
                            </div>

                            <div class="col-sm-2"> 
                                <input type="text" name="bulan" id="bulan" class="form-control input-sm" value="<?= $bulan;?>" readonly>   
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="tahun" id="tahun" class="form-control input-sm" value="<?= $tahun;?>" readonly>   
                            </div>
                    </div>  

                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                        <!-- <?php //if (($customer->tahun.$customer->ibulan) > date('Ym')) { 
                            /* if($head) {
                                if ($head->i_status == '1' || $head->i_status == '3') {
                                    echo '<button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>'. '     ';
                                    echo '<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>'. '     ';
                                    echo '<button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>'. '     ';
                                }
                            } else {
                                echo '<button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>'. '     ';
                                echo '<button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>'. '     ';
                                echo '<button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>'. '     ';
                            } */
                        ?> -->
                       <!--  <button type="button" id="upload" class="btn btn-outline-info btn-rounded btn-sm"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button>   -->
                        <!-- <button type="button" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approve","#main")'><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button> -->
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2"><i class="fa fa-save mr-2" ></i>Update</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Tambah</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
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
                                    <th width="62%">Nama Barang</th>
                                    <th width="15%">Warna</th>
                                    <th width="10%">Saldo Awal</th>
                                    <th width="10%">Act</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($datadetail as $key) {
                                    $i++;
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?= $i ;?>"><?= $i ;?></spanx>
                                        <input type="hidden" id="id<?= $i ;?>" class="form-control input-sm" name="id<?= $i ;?>" value="<?= $key["id"];?>">
                                    </td>
                                    <td>
                                        <select data-z="<?= $i ;?>" id="id_product_base<?= $i ;?>" class="form-control select2 input-sm" name="id_product_base<?= $i ;?>" >
                                            <option value="<?= $key['id_product_base']?>"><?= $key["i_product_base"];?> - <?= $key["e_product_basename"];?></option>
                                        </select>
                                        <input type="hidden" id="i_product_base<?= $i ;?>" class="form-control input-sm" name="i_product_base<?= $i ;?>" value="<?= $key["i_product_base"];?>">
                                    </td>
                                   
                                    <td>
                                        <input type="text" id="e_color_name<?= $i ;?>" readonly class="form-control text-right input-sm" autocomplete="off" name="e_color_namecolor<?= $i ;?>" value="<?= $key["e_color_name"];?>">
                                        <input type="hidden" id="i_color<?= $i ;?>" readonly class="form-control text-right input-sm" autocomplete="off" name="i_color<?= $i ;?>" value="<?= $key["i_color"];?>">
                                    </td>
                                    <td><input type="text" id="n_saldo_awal<?= $i ;?>" class="form-control text-right input-sm" autocomplete="off" name="n_saldo_awal<?= $i ;?>" value="<?= $key["n_saldo_awal"];?>"></td>
                                    
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
    
    var jml = $('#jml').val();

    $('.select2').select2();

    for (i = 1; i <= jml; i++) {
            $('#id_product_base'+ i).select2({
                placeholder: 'Cari Kode / Nama Barang Jadi',
                // allowClear: true,
                width: "80%",
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
                var z = $(this).data('z');
                var ada = true;
                for(var x = 1; x <= jml; x++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#id_product_base'+x).val()) && (z!=x)){
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

                var idproduct = $(this).val();
                console.log(z);

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url($folder.'/cform/productcolor/'); ?>',
                    dataType: 'json',
                    data: {
                            q         : idproduct,
                        },
                    success: function(data) {
                        var cek = data;
                        console.log(cek[0].icolor);

                        $('#i_product_base' + z).val( parseInt(cek[0].iproductbase ));

                        $('#i_color' + z).val( parseInt(cek[0].icolor ));

                        $('#e_color_name' + z).val(cek[0].colorname);
                    }
                });
                
            });
    } 

});

$( "#submit" ).click(function(event) {
    ada = false;
    if (jml==0) {
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

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#send").attr("hidden", false);
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

var i = jml;
    $("#addrow").on("click", function () {
        i++; 
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="id_product_base${i}" class="form-control input-sm" name="id_product_base${i}" ></select><input type="hidden" id="i_product_base${i}" class="form-control input-sm" readonly name="i_product_base${i}" ></td>`;
        cols += `<td><input type="text" id="e_color_name${i}" readonly class="form-control text-right input-sm" autocomplete="off" name="e_color_name${i}" ><input type="hidden" id="i_color${i}" readonly class="form-control text-right input-sm" autocomplete="off" name="i_color${i}" ></td>`;

        cols += `<td><input type="text" id="n_saldo_awal${i}" class="form-control text-right input-sm" autocomplete="off" name="n_saldo_awal${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#id_product_base'+ i).select2({
                placeholder: 'Cari Kode / Nama Barang Jadi',
                // allowClear: true,
                width: "80%",
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
                var z = $(this).data('z');
                var ada = true;
                for(var x = 1; x <= jml; x++){
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#id_product_base'+x).val()) && (z!=x)){
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

                var idproduct = $(this).val();
                console.log(z);

                $.ajax({
                    type: 'POST',
                    url: '<?= base_url($folder.'/cform/productcolor/'); ?>',
                    dataType: 'json',
                    data: {
                            q         : idproduct,
                        },
                    success: function(data) {
                        var cek = data;
                        console.log(cek[0].icolor);

                        $('#i_product_base' + z).val( parseInt(cek[0].iproductbase ));

                        $('#i_color' + z).val( parseInt(cek[0].icolor ));

                        $('#e_color_name' + z).val(cek[0].colorname);
                    }
                });
                
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