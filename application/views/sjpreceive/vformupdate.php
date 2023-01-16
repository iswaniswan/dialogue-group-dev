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
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No. SJP</label><label class="col-md-6">Tanggal SJP</label>
                        <div class="col-sm-6">
                            <input readonly id="isj" name="isj" class="form-control" value="<?= $isi->i_sjp; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="dsj" name="dsj" class="form-control" value="<?= $isi->dsjp; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal Terima</label><label class="col-md-4">Nilai Kirim</label><label class="col-md-4">Nilai Terima</label>
                        <div class="col-sm-4">
                            <input readonly id="dreceive" name="dreceive" onchange="cektanggal();" class="form-control date" value="<?= $isi->d_sjp_receive; ?>">
                            <input hidden id="dreceivex" name="dreceivex" value="<?= date('d-m-Y'); ?>"> 
                        </div>
                        <div class="col-sm-4"> 
                            <input readonly style="text-align:right;"  id="vsj" name="vsj" class="form-control" value="<?= number_format($isi->v_sjp); ?>">
                            <input type="hidden" name="jml" id="jml" class="form-control" value="<? if($jmlitem) echo $jmlitem; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly style="text-align:right;" readonly id="vsjrec" name="vsjrec" class="form-control" value="<?php if($isi->v_sjp > 0){ echo number_format($isi->v_sjp); }; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                            <?php if(($jmlitem!=0) || ($jmlitem!='')){ ?>
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input" checked>
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">&nbsp;&nbsp;Check All</span>
                                </label>
                                <?php
                            } ?> 
                        </div>
                    </div>
                </div>
                <div class="col-md-6">                        
                    <div class="form-group row">
                        <label class="col-md-6">No. SPMB</label><label class="col-md-6">Tanggal SPMB</label>
                        <div class="col-sm-6">
                            <input readonly id="ispmb" name="ispmb" class="form-control" value="<?= $isi->i_spmb; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="dspmb" name="dspmb" class="form-control" value="<?= $isi->dspmb; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">SJ Lama</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" type="hidden" class="form-control"  value="<?= $isi->i_area; ?>">
                            <input id="istore" name="istore" type="hidden" class="form-control" value="<?= $isi->i_store; ?>">
                            <input id="istorelocation" name="istorelocation" type="hidden" value="<?= $isi->i_store_location; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="isjold" name="isjold" class="form-control" value="<?= $isi->i_sjp_old; ?>">
                        </div>
                    </div> 
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%;" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode Barang</th>
                                <th style="text-align: center; width: 35%;">Nama Barang</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center;">Jumlah Retur</th>
                                <th style="text-align: center;">Jumlah Terima</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    if($row->n_quantity_receive=='' || $row->n_quantity_receive==0){
                                        $jmlterima = $row->n_quantity_deliver;
                                    }
                                    if($row->n_quantity_receive==''){
                                        $row->n_quantity_receive==0;
                                    }
                                    $vtotal = $row->v_unit_price*$row->n_quantity_deliver;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" readonly class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                    </td>
                                    <td>
                                        <input readonly class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                        <input class="form-control" readonly type="hidden" id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $row->e_remark; ?>">
                                        <input class="form-control" type="hidden" id="vproductmill<?=$i;?>" name="vproductmill<?=$i;?>" value="<?= $row->v_unit_price; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly style="text-align:right;" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $row->n_quantity_deliver; ?>">
                                        <input type="hidden" id="norder<?=$i;?>" name="norder<?=$i;?>" value="<?= $row->n_quantity_order; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" style="text-align:right;" id="nreceive<?=$i;?>" name="nreceive<?=$i;?>" value="<?= $jmlterima; ?>" onkeypress="return hanyaAngka(event);" autocomplete="off" onkeyup="ngetang();">
                                        <input type="hidden" id="ntmp<?=$i;?>" name="ntmp<?=$i;?>" value="<?= $row->n_quantity_receive; ?>">
                                        <input readonly type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="<?= $vtotal; ?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick='ngetang()'>
                                    </td>
                                </tr>
                            <?php  } ?>
                            <input type="hidden" readonly name="jml" id="jml" value="<?= $i;?>">
                        <?php } ?>
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
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date', 0, 5);
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        ngetang();
    });

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nreceive"+brs).val();
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
        }
        $("#vsjrec").val(formatcemua(tot));
    }

    function cektanggal(){
        dsj=document.getElementById('dsj').value;
        dreceive=document.getElementById('dreceive').value;
        dreceivex=document.getElementById('dreceivex').value;
        dtmp=dsj.split('-');
        thnsj=dtmp[2];
        blnsj=dtmp[1];
        hrsj =dtmp[0];
        if(dreceive!=''){
            dtmp=dreceive.split('-');
            thnrec=dtmp[2];
            blnrec=dtmp[1];
            hrrec =dtmp[0];
            if( thnsj>thnrec ){
                swal('Tanggal terima tidak boleh lebih kecil dari tanggal kirim !!!');
                document.getElementById('dreceive').value='';
            }else if( thnsj==thnrec ){
                if( blnsj>blnrec ){
                    swal('Tanggal terima tidak boleh lebih kecil dari tanggal kirim !!!');
                    document.getElementById('dreceive').value='';
                }else if( blnsj==blnrec ){
                    if( hrsj>hrrec ){
                        swal('Tanggal terima tidak boleh lebih kecil dari tanggal kirim !!!');
                        document.getElementById('dreceive').value='';
                    }
                }
            }
        }
        dtmp=dreceive.split('-');
        thndkb=dtmp[2];
        blndkb=dtmp[1];
        hrdkb =dtmp[0];
        dtmp=dreceivex.split('-');
        thndkbx=dtmp[2];
        blndkbx=dtmp[1];
        hrdkbx =dtmp[0];
        if( thndkb>thndkbx ){
            swal('Tanggal Receive tidak boleh lebih besar dari hari ini !!!');
            document.getElementById('dreceive').value='';
        }else if( thndkb==thndkbx ){
            if( blndkb>blndkbx ){
                swal('Tanggal Receive tidak boleh lebih besar dari hari ini !!!');
                document.getElementById('dreceive').value='';
            }else if( blndkb==blndkbx ){
                if( hrdkb>hrdkbx ){
                    swal('Tanggal Receive tidak boleh lebih besar dari hari ini !!!');
                    document.getElementById('dreceive').value='';
                }
            }
        }

    }

    function dipales(){
        var a = $('#jml').val();
        if((document.getElementById("iarea").value!='') && (document.getElementById("ispmb").value!='') && (document.getElementById("dreceive").value!='') ){
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("ndeliver"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>