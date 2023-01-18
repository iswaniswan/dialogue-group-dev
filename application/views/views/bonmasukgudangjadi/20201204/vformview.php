<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Gudang Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">No Referensi</label>
                        <div class="col-sm-3">
                            <input type="text" name="ekodemaster" id="ekodemaster" class="form-control" value="<?=$data->e_departement_name;?>" readonly>
                            <input type="hidden" name="ikodemaster" id="ikodemaster" class="form-control" value="<?=$data->i_kode_master;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ibonm" name="ibonm" class="form-control" value="<?=$data->i_bonm;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonm" name="dbonm" class="form-control" value="<?=$data->d_bonm;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ireferensi" name="ireferensi" class="form-control" value="<?=$data->i_referensi;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Referensi</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control" value="<?=$data->d_bonk?>" readonly>
                        </div>
                        <div class="col-sm-9">
                            <textarea id= "eremark" name="eremark" class="form-control" value="" disabled><?=$data->e_remark;?></textarea>
                        </div>
                    </div>         
                </div>                              
                <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;" width="5%">No</th>
                                    <th style="text-align: center;" width="10%">Kode Barang</th>
                                    <th style="text-align: center;" width="35%">Nama Barang</th>
                                    <th style="text-align: center;" width="14%">Warna</th>
                                    <th style="text-align: center;" width="8%">Quantity Keluar</th>
                                    <th style="text-align: center;" width="8%">Quantity Masuk</th>
                                    <th style="text-align: center;" width="15%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                             <?php $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?=$i;?>
                                        <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>" readonly >
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_basename; ?>" readonly >
                                    </td>                                 
                                    <td>
                                        <input class="form-control" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>" value="<?= $row->i_color; ?>" >
                                        <input class="form-control" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $row->e_color_name; ?>" readonly>
                                    </td>                               
                                    <td>
                                        <input class="form-control" type="text" id="nquantitykeluar<?=$i;?>" name="nquantitykeluar<?=$i;?>" value="<?= $row->n_quantity_keluar; ?>" readonly>
                                    </td> 
                                    <td>
                                        <input class="form-control" type="text" id="nquantitymasuk<?=$i;?>" name="nquantitymasuk<?=$i;?>" value="<?= $row->n_quantity_masuk; ?>" readonly>
                                    </td>                              
                                    <td>
                                        <input class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
});

function getstore() {
        var gudang = $('#ikodemaster').val();
        if (gudang == "") {
            $("#ibonmk").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#ibonmk").attr("disabled", false);
        }
        
        $('#ibonmk').html('');
        $('#ibonmk').val('');
}

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
    // counter -= 1
    // document.getElementById("jml").value = counter;
});

function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}    

function cek() {
    var dbonm = $('#dbonm').val();
    var jml = $('#jml').val();

    var qty1 = 0;
    var qty2 = 0;

    var qty = []; 

    if (dbonm == '') {
        alert('Data Header Belum Lengkap !!');
        return false;
    } else {
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            qty1 = parseInt($('#nquantitykeluar'+i).val());
            qty2 = parseInt($('#nquantitymasuk'+i).val());

            if (qty2 > qty1) {
                qty.push("lebih");
            } else {
                qty.push("ok");
            }
            jumlah = jumlah + qty2;
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
    }
}
</script>