<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>         
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label >Gudang</label>
                            <input type="text" readonly id="gudang" name="gudang" class="form-control" maxlength="" value="<?php echo $head->e_nama_master;?>">
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master;?>">
                        </div>
                        <div class="col-sm-4">
                            <label >Nomor Adjusment</label>
                            <input type="text" readonly id="i_adjus" name="i_adjus" class="form-control" maxlength="" value="<?php echo $head->i_adjustment;?>">
                        </div>
                        <div class="col-sm-4">
                             <label >Tanggal</label>
                             <input type="text" id="dadjus" name="dadjus" class="form-control date" value="<?php echo $head->tanggal;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                        <?php if (($head->i_status == '2')  && ($i_departement == '11' && $i_level == '6' || $i_departement == '1' && $i_level == '1'))  { ?>
                                <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                                <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        <?php } ?>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>              
                    </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Kode Barang</th>
                                    <th width="40%">Nama barang</th>
                                    <th width="10%">Qty</th>
                                    <th width="10%">Satuan</th>
                                    <th width="20%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td>
                                    <spanx id="snum<?=$i;?>"><?=$i;?></spanx>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:450px" class="form-control" type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                </td>                                 
                                <td class="col-sm-1">
                                    <input style ="width:50px" class="form-control" type="text"  id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_qty; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan_code; ?>" readonly >
                                    <input style ="width:100px" class="form-control" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                </td>

                                <td class="col-sm-1">
                                      <input style ="width:200px" class="form-control" type="text" id="e_remark<?=$i;?>" name="e_remark<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
                                </td>
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
function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
}
function getenabledappr2() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function setjenisbarang() {
    var ejenisbarang = $('#ejenisbarang').val();
    $('#ijenisbarang').val(ejenisbarang);     
}

function cek() {
    var dadjus = $('#dadjus').val();

    if (dadjus == '') {
        alert('Data Header Belum Lengkap !!');
        return false;
    } else {
        return true;
    }
}

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }

$(document).ready(function () {
    
    // var counter = 0;
   $('#change').attr("disabled", false);
   $("#change").on("click", function () {
        var kode = $("#i_adjus").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
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

   $("#reject").on("click", function () {
        var kode = $("#i_adjus").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
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


    $("form").submit(function(event) {
        event.preventDefault();
        $('#change').attr("disabled", true);
        $('#reject').attr("disabled", true);
        $('#submit').attr("disabled", true);
    });
});

</script>