<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
                            <textarea id= "eremark" name="eremark" class="form-control" value=""><?=$data->e_remark;?></textarea>
                        </div>
                    </div>         
                </div>                            
                <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <th style="text-align: center;" width="5%">No</th>
                                <th style="text-align: center;" width="10%">Kode Barang</th>
                                <th style="text-align: center;" width="35%">Nama Barang</th>
                                <th style="text-align: center;" width="14%">Warna</th>
                                <th style="text-align: center;" width="5%">Quantity</th>
                                <th style="text-align: center;" width="15%">Keterangan</th>
                                <th style="text-align: center;" width="5%">Action</th>
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
                                    <input  style="text-align: center;" class="form-control" type="hidden" id="nquantitykeluar<?=$i;?>" name="nquantitykeluar<?=$i;?>" value="<?= $row->n_quantity_keluar; ?>" readonly>
                                    <input class="form-control" type="text" id="nquantitymasuk<?=$i;?>" name="nquantitymasuk<?=$i;?>" value="<?= $row->n_quantity_masuk; ?>" onkeyup="valid(this.value);">
                                </td>                              
                                <td>
                                    <input class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" >
                                </td>
                                <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
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

    $("#send").on("click", function () {
        var kode = $("#ibonm").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });
});

$("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
});

function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}    

    function getenabledsend() {
        $('#send').attr("disabled", true);
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        swal('Berhasil Di Send');
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

function valid(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var qtykeluar = $('#nquantitykeluar'+i).val();
        var qtymasuk = $('#nquantitymasuk'+i).val();
        if(parseFloat(qtykeluar)<parseFloat(qtymasuk) ){
            swal("quantity lebih")
            $('#nquantitymasuk'+i).val('');
        }
    }
}
</script>