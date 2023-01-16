<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>         
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">No Konversi </label>
                        <label class="col-md-4">Tanggal Konversi</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" value="<?php echo $head->e_nama_master?>" readonly>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="nokonversi" name="nokonversi" class="form-control" value="<?php echo $head->i_konv?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dkonversi" name="dkonversi" class="form-control date" value="<?php echo $head->d_konv?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-4">No Pengeluaran Pinjaman</label>
                        <div class="col-sm-8">
                            <input type="text" id="ibonmk" name="ibonmk" class="form-control" value="<?php echo $head->i_reff?>" readonly>
                        </div>
                    </div>    
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Partner</label>
                         <input type="text" id="partner" name="partner" class="form-control" value="<?php echo $head->partner?>" readonly>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark?>">
                        </div>
                    </div>                  
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Kode Barang</th>
                                    <th width="35%">Nama Barang</th>
                                    <th width="7%">Qty Keluar</th>
                                    <th width="8%">Qty Belum Kembali</th>
                                    <th width="8%">Qty Masuk</th>
                                    <th width="8%">Satuan</th>
                                    <th width="15%">Keterangan</th>.
                                    <th width="5%">Pilih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td class="col-sm-1">
                                    <input style ="width:40px" type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px"type="text" id="i_material<?=$i;?>" name="i_material<?=$i;?>" value="<?= $row->i_material; ?>" readonly >
                                </td>
                            
                                <td class="col-sm-1">
                                    <input style ="width:380px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                </td>                                 
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="nquantity<?=$i;?>" readonly name="nquantity<?=$i;?>" value="<?= $row->n_qty; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="sisa<?=$i;?>" readonly name="sisa<?=$i;?>" value="<?= $row->sisa+$row->qty2; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="n_qty<?=$i;?>" name="n_qty<?=$i;?>" value="<?= $row->qty2; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" type="hidden" id="i_satuan<?=$i;?>" name="i_satuan<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly >
                                    <input style ="width:150px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                </td>

                                <td class="col-sm-1">
                                      <input style ="width:150px" type="text" id="e_remark<?=$i;?>" name="e_remark<?=$i;?>"value="<?= $row->e_remark2; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input type="checkbox" id="chk<?=$i;?>" name="chk<?=$i;?>" checked >
                                </td>

                                <!-- <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger" value="">Delete</button>
                                </td> -->
                                
                                <!-- <td>
                                <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                                </td> -->
                                </tr>
                                <?php } ?>
                                <!-- <label class="col-md-12">Jumlah Data</label> -->
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
function cek() {
        var dkonversi = $('#dkonversi').val();

        var jml = $('#jml').val();

        var qty1 = 0;
        var qty2 = 0;
        var validasi = 0;
        var qty = []; 

        if (dkonversi == '') {
            alert('Data Header Belum Lengkap !!');
            return false;
        } else {
            var jumlah = 0;
            for (i=1; i<=jml; i++){
                qtyawal = parseInt($('#nquantity'+i).val());
                qtysisa = parseInt($('#sisa'+i).val());
                qty_baru = parseInt($('#n_qty'+i).val());
                if ($('#chk'+i).prop('checked')) {
                    if (qty_baru > qtysisa) {
                        qty.push("lebih");
                    } else {
                        qty.push("ok");
                    }
                    jumlah = jumlah + qty_baru;
                }
            }
            var found = qty.find(element => element == "lebih");
                 
            if (found == "lebih") {
                alert("Jumlah Barang Masuk Melebihi Jumlah Sisa Barang Keluar");
                return false;
            } else if (jumlah == 0) {
                alert("Barang Masuk Harus Di Isi");
                return false;
            } else {
                return true;
            }
            
            //return true;
        }
}

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }
$(document).ready(function () {
    
    // var counter = 0;

  var counter = document.getElementById("jml").value;


    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        // counter -= 1
        // document.getElementById("jml").value = counter;
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $(".ibtnDel").attr("disabled", true);
 });

});

    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });
    function getmaterial(id){
        var imaterial = $('#imaterial'+id).val();
        // var jumlah = $('#jml').val();
        // var ada = false;
        // for(i=1;i<=jumlah-1;i++){
        //     var oldmaterial = $('#imaterial'+i).val();
        //     if(imaterial == oldmaterial){
        //         swal({
        //             type  : 'warning',
        //             title : '',
        //             text  : 'Material : '+imaterial+' sudah ada !!'
        //         });
        //         imaterial.val("");
        //         ada = true;
        //         break;
        //     }else{
        //         ada = false;
        //     }
        //}
        
        //if (ada == false) {
            $.ajax({
                type: "post",
                data: {
                    'i_material': imaterial
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#ematerialname'+id).val(data[0].e_material_name);
                    $('#esatuan'+id).val(data[0].e_satuan);
                    $('#isatuan'+id).val(data[0].i_satuan);
                    $('#esatuankonv'+id).val(data[0].i_convertion);
                },
                error: function () {
                    alert('Error :)');
                }
            });
        //}
        
    }

    function getstore() {
        var gudang = $('#ikodemaster').val();
        //alert(gudang);
        $('#istore').val(gudang);

        if (gudang == "") {
            $("#addrow").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
        }
        
    }
</script>