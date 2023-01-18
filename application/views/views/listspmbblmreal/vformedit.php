<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <dir id="pesan"></dir>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-1">No SPMB</label>
                        <div class="col-sm-2">
                            <input id="ispmb" name="ispmb" class="form-control" required="" readonly
                                value="<?= $isi->i_spmb;?>">
                        </div>
                        <div class="col-sm-2">
                            <input id="dspmb" name="dspmb" class="form-control" required="" readonly
                                value="<?= $isi->d_spmb;?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">Area</label>
                        <div class="col-sm-4">
                            <input id="eareaname" name="eareaname" class="form-control" required="" readonly
                                value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?php echo $isi->i_area; ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">Keterangan</label>
                        <div class="col-sm-4">
                            <textarea readonly id="eremark" class="form-control" name="eremark">
                                <?php echo $isi->e_remark ;?>
                            </textarea>
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">SPMB Lama</label>
                        <div class="col-sm-2">
                            <input readonly id="ispmbold" class="form-control" name="ispmbold"
                                value="<?= $isi->i_spmb_old; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick='show("<?= $folder;?>/cform/view/<?= $iarea;?>","#main")'>
                                <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>

                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="50%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 8%;">Kode</th>
                                <th style="text-align: center; width: 20%;">Nama Barang</th>
                                <th style="text-align: center; width: 5%;">Jml Ord</th>
                                <th style="text-align: center; width: 5%;">Jml Acc</th>
                                <th style="text-align: center; width: 5%;">Jml Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php               
                                $i=0;
                                if($detail){
                                    foreach($detail as $row){
                                        $query = $this->db->query("select a.i_spmb, a.d_spmb, a.i_area, b.i_product, b.e_product_name, b.n_order,
                                                                    b.n_deliver, b.n_saldo
                                                                    from tm_spmb a, tm_spmb_item b
                                                                    where a.i_spmb=b.i_spmb and a.i_area=b.i_area
                                                                    and not a.i_approve2 is null and not a.i_store is null
                                                                    and b.n_deliver<b.n_saldo and a.i_area='$iarea'
                                                                    order by a.i_spmb");
                                        $jmlitem = $query->num_rows(); 
                                        $i++;
                            ?>
                            
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $i;?>
                                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>"
                                                    name="baris<?= $i;?>" value="<?= $i;?>">
                                            </td>
                                            <td>
                                                <?= $row->i_product;?>
                                                <!-- <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>"
                                                    value="<#?= $row->i_product;?>"> -->
                                            </td>
                                            <td>
                                                <?= $row->e_product_name;?>
                                                <!-- <input class="form-control" readonly id="eproductname<#?= $i;?>"
                                                    name="eproductname<#?= $i;?>" value="<#?= $row->e_product_name;?>"> -->
                                            </td>
                                            <td>
                                            <?= $row->n_order;?>
                                                <!-- <input readonly class="form-control" id="norder<#?= $i;?>" name="norder<#?= $i;?>"
                                                    value="<#?= $row->n_order;?>"> -->
                                            </td>
                                            <td>
                                                <?= $row->n_acc;?>
                                                <!-- <input readonly class="form-control" id="nacc<#?= $i;?>" name="nacc<#?= $i;?>"
                                                    value="<#?= $row->n_acc;?>" onkeyup="hitungnilai(this.value,$jmlitem)"> -->
                                            </td>
                                            <td>
                                                <input style="text-align: right;" type="hidden" readonly class="form-control"
                                                    id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="">
                                                <?= $row->n_saldo ;?>
                                                <!-- <input readonly class="form-control" id="nsaldo<#?= $i;?>"
                                                    name="nsaldo<#?= $i;?>" value="<#?= $row->n_saldo ;?>"> -->
                                            </td>
                                        </tr>
                            <?php   
                                    }
                                } //END DETAIL 
                            ?>
                        </tbody>
                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function() {
    //hitungnilai();
    /*$('.select2').select2();*/
    showCalendar('.date');
});

function dipales(a) {
    if ((document.getElementById("dreceive").value != '') &&
        (document.getElementById("iarea").value != '')) {
        if (a == 0) {
            swal('Isi data item minimal 1 !!!');
            return false;
        } else {
            for (i = 1; i <= a; i++) {
                if ((document.getElementById("iproduct" + i).value == '') || (document.getElementById("eproductname" +
                        i).value == '') || (document.getElementById("nreceive" + i).value == '')) {
                    swal('Data item masih ada yang salah !!!');
                    return false;
                } else {
                    return true;
                }
            }
        }
    } else {
        swal('Data header masih ada yang salah !!!');
        return false;
    }
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#refresh").attr("disabled", true);
});

function hitungnilai(brs) {
    var tot = 0;
    ord = document.getElementById("ndeliver" + brs).value;
    psn = document.getElementById("norder" + brs).value;
    if (isNaN(parseFloat(ord))) {
        alert("Input harus numerik");
    } else {
        if ((parseFloat(psn)) < (parseFloat(ord))) {
            alert('Jumlah kirim tidak boleh lebih besar dari jumlah pesan !!!');
            document.getElementById("ndeliver" + brs).value = '0';
        } else {
            hrg = formatulang(document.getElementById("vproductmill" + brs).value);
            qty = formatulang(ord);
            vhrg = parseFloat(hrg) * parseFloat(qty);
            document.getElementById("vtotal" + brs).value = formatcemua(vhrg);
            jml = parseFloat(document.getElementById("jml").value);
            for (i = 1; i <= jml; i++) {
                if (document.getElementById("chk" + i).value == 'on') {
                    tot += parseFloat(formatulang(document.getElementById("vtotal" + i).value));
                }
            }
            document.getElementById("vsj").value = formatcemua(tot);
        }
    }
}

function cektanggal() {
    dspb = $('#dreceive').val();
    bspb = $('#breceive').val();
    dtmp = dspb.split('-');
    per = dtmp[2] + dtmp[1] + dtmp[0];
    bln = dtmp[1];
    if ((bspb != '') && (dspb != '')) {
        if (bspb != bln) {
            swal("Tanggal Terima sj tidak boleh dalam bulan yang berbeda !!!");
            $("#dreceive").val('');
        }
    }

    dsj = $('#dsj').val();
    dsjec = $('#dreceive').val();
    sj = dsj.split('-');
    tglsj = sj[2] + sj[1] + sj[0];
    rc = dsjec.split('-');
    tglrec = rc[2] + rc[1] + rc[0];
    if (tglrec < tglsj) {
        swal("Tanggal sj Receive tidak boleh lebih kecil dari tanggal sj !!!");
        $("#dreceive").val('');
    }
}
</script>