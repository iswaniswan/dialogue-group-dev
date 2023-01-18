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
                <?php 
                    if($isi->d_sj){
                        if($isi->d_sj!=''){
                            $tmp=explode("-",$isi->d_sj);
                            $hr=$tmp[2];
                            $bl=$tmp[1];
                            $th=$tmp[0];
                            $isi->d_sj=$hr."-".$bl."-".$th;
                        }
                   }

                    if($isi->d_spb){
                        if($isi->d_spb!=''){
                            $tmp=explode("-",$isi->d_spb);
                            $hr=$tmp[2];
                            $bl=$tmp[1];
                            $th=$tmp[0];
                            $isi->d_spb=$hr."-".$bl."-".$th;
                        }
                    }
                ?>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-1">No SJ</label>
                        <div class="col-sm-2">
                            <input id="isj" name="isj" class="form-control" required="" readonly
                                value="<?= $isi->i_sj;?>">
                        </div>
                        <div class="col-sm-2">
                            <input id="dsj" name="dsj" class="form-control" required="" readonly
                                value="<?= $isi->d_sj;?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">Area</label>
                        <div class="col-sm-4">
                            <input id="eareaname" name="eareaname" class="form-control" required="" readonly
                                value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?php echo $isi->i_area; ?>">
                            <input id="istore" name="istore" type="hidden" value="<?php echo $istore; ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">SPB</label>
                        <div class="col-sm-2">
                            <input readonly id="ispb" class="form-control" name="ispb"
                                value="<?php if($isi->i_spb) echo $isi->i_spb; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input id="dspb" name="dspb" class="form-control" required="" readonly
                                value="<?= $isi->d_spb; ?>">
                            <input type="hidden" id="vsjgross" name="vsjgross" class="form-control" required="" readonly
                                value="<?= $vsjgross; ?>">
                            <input type="hidden" id="nsjdiscount1" name="nsjdiscount1" class="form-control" required=""
                                readonly value="<?= $nsjdiscount1; ?>">
                            <input type="hidden" id="nsjdiscount2" name="nsjdiscount2" class="form-control" required=""
                                readonly value="<?= $nsjdiscount2; ?>">
                            <input type="hidden" id="nsjdiscount3" name="nsjdiscount3" class="form-control" required=""
                                readonly value="<?= $nsjdiscount3;?>">
                            <input type="hidden" id="vsjdiscount1" name="vsjdiscount1" class="form-control" required=""
                                readonly value="<?php if($vsjdiscount1) {echo $vsjdiscount1;}else{echo '0';} ?>">
                            <input type="hidden" id="vsjdiscount2" name="vsjdiscount2" class="form-control" required=""
                                readonly value="<?php if($vsjdiscount2) {echo $vsjdiscount2;}else{echo '0';}?>">
                            <input type="hidden" id="vsjdiscount3" name="vsjdiscount3" class="form-control" required=""
                                readonly value="<?php if($vsjdiscount3) {echo $vsjdiscount3;}else{echo '0';}?>">
                            <input type="hidden" id="vsjdiscounttotal" name="vsjdiscounttotal" class="form-control"
                                required="" readonly
                                value="<?php if($vsjdiscounttotal) {echo $vsjdiscounttotal;}else{echo '0';} ?>">
                            <input type="hidden" id="" name="fspbconsigment" class="form-control"
                                required="" readonly value="<?= $fspbconsigment; ?>">
                            <input type="hidden" id="fplusppn" name="fplusppn" class="form-control"
                                required="" readonly value="<?= $fplusppn; ?>">
                            <input type="hidden" id="icustomer" name="icustomer" class="form-control"
                                required="" readonly value="<?= $icustomer; ?>">
                            <input type="hidden" id="isalesman" name="isalesman" class="form-control"
                                required="" readonly value="<?= $isalesman; ?>">
                            <input type="hidden" id="ntop" name="ntop" class="form-control"
                                required="" readonly value="<?= $ntop; ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">SJ Lama</label>
                        <div class="col-sm-2">
                            <input id="isjold" readonly name="isjold" class="form-control"
                                value="<?= $isi->i_sj_old; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input id="vsjnetto" name="vsjnetto" readonly class="form-control"
                                value="<?= number_format($isi->v_nota_netto); ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">Nama Toko</label>
                        <div class="col-sm-4">
                            <input id="ecustomername" readonly name="ecustomername" class="form-control"
                                value="<?= $ecustomername; ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">Tgl Terima</label>
                        <div class="col-sm-4">
                            <input id="dsjreceive" readonly name="dsjreceive" class="form-control"
                                value="<?= $isi->d_sj_receive; ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <label class="col-md-1">Keterangan</label>
                        <div class="col-sm-4">
                            <input id="eremark" readonly name="eremark" class="form-control"
                                value="<?= $isi->e_sj_receive; ?>">
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------------------->
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm"
                                onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$areax;?>","#main")'>
                                <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>

                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode</th>
                                <th style="text-align: center; width: 35%;">Nama Barang</th>
                                <th style="text-align: center; width: 30%;">Motif</th>
                                <th style="text-align: center;">Jml Ord</th>
                                <th style="text-align: center;">Jml Krm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php               
                                $i=0;
                                if($detail){
                                    foreach($detail as $row){
                                        $query  = $this->db->query(" SELECT f_spb_stockdaerah FROM tm_spb
																     WHERE i_spb='$ispb' AND i_area='$iarea'",false);
                                        if ($query->num_rows() > 0){
                                            foreach($query->result() as $qq){
                                                $stockdaerah=$qq->f_spb_stockdaerah;
                                            }
                                        }

                                        if($stockdaerah=='f'){
                                            $query=$this->db->query(" SELECT n_quantity_stock AS qty FROM tm_ic
                                                                      WHERE i_product='$row->i_product'
                                                                      AND i_product_motif='$row->i_product_motif'
                                                                      AND i_product_grade='$row->i_product_grade'
                                                                      AND i_store='AA' AND i_store_location='01' AND i_store_locationbin='00'",false);
                                        }else{
                                            $query=$this->db->query(" SELECT n_quantity_stock AS qty FROM tm_ic
                                                                      WHERE i_product='$row->i_product'
                                                                      AND i_product_motif='$row->i_product_motif'
                                                                      AND i_product_grade='$row->i_product_grade'
                                                                      AND i_store='$istore' AND i_store_location='00' AND i_store_locationbin='00'",false);
                                        }
                        
                                        if ($query->num_rows() > 0){
                                            foreach($query->result() as $tt){
                                                if($tt->qty>=0){
                                                    $stock=$tt->qty+$row->n_deliver;
                                                }else{
                                                    $stock=$row->n_deliver;
                                                }
                                            }
                                        }else{
                                            $stock=0;
                                        }

                                        if($stock>$row->n_qty)$stock=$row->n_qty;
                                        if($stock<0)$stock=0;
                                        
                                        $vtot   = $row->harga*$stock;
                                        $stock  = number_format($stock);
                                        $i++;
                            ?>
                            
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $i;?>
                                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>"
                                                    name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>"
                                                    value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>"
                                                    value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly id="eproductname<?= $i;?>"
                                                    name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input readonly class="form-control" id="emotifname<?= $i;?>"
                                                    name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                <input type="hidden" style="text-align: right;" readonly class="form-control"
                                                    width:85px; id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>"
                                                    value="<?= $row->v_unit_price;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>"
                                                    value="<?= $row->n_qty;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>"
                                                    value="<?= $row->n_deliver;?>" onkeyup="hitungnilai(<?= $i;?>)">
                                                <input type="hidden" readonly class="form-control" id="ntmp<?= $i;?>"
                                                    name="ntmp<?= $i;?>" value="<?= $row->n_deliver;?>">
                                                <input type="hidden" readonly class="form-control" id="ndeliverhidden<?= $i;?>"
                                                    name="ndeliverhidden<?= $i;?>" value="<?= $stock;?>">
                                                <input style="text-align: right;" type="hidden" readonly class="form-control"
                                                    id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="">
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