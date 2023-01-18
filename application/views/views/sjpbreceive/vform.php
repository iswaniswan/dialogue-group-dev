<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-external-link"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-4">                       
                    <div class="form-group row">
                        <label class="col-md-6">No. SJPB</label><label class="col-md-6">Tanggal SJPB</label>
                        <div class="col-sm-6">
                            <input readonly id="isj" name="isj" class="form-control" value="<?= $isi->i_sjpb;?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id= "dsj" name="dsj" class="form-control" value="<?= $isi->dsjpb;?>">
                        </div>
                    </div>                  
                    <div class="form-group row">
                        <label class="col-md-6">No. SJP</label><label class="col-md-6">Tanggal SJP</label>
                        <div class="col-sm-6">
                            <input readonly id="isjp" name="isjp" class="form-control" value="<?= $isi->i_sjp;?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id= "dsjp" name="dsjp" class="form-control" value="<?= $isi->dsjp;?>">
                        </div>
                        <input hidden id="ibapb" name="ibapb" value="<?= $isi->i_bapb; ?>">
                        <input hidden id="dbapb" name="dbapb" value="<?= $isi->d_bapb; ?>">
                    </div>             
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>                               
                        </div>
                    </div>
                </div>                
                <div class="col-md-8"> 
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Pelanggan</label>
                        <div class="col-sm-6">
                            <input class="form-control" readonly="" name="eareaname" id="eareaname" value="<?= $isi->e_area_name;?>">
                            <input type="hidden" name="iarea" id="iarea" value="<?= $isi->i_area ;?>">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" id="ecustomername" name="ecustomername" value="<?= $isi->e_customer_name ;?>" readonly>
                            <input type="hidden" name="icustomer" id="icustomer" value="<?= $isi->i_customer ;?>">
                        </div>
                    </div> 
                    <div class="form-group has-error has-feedback row">
                        <label class="col-md-4">Tanggal Terima</label><label class="col-md-4">Nilai Terima</label><label class="col-md-4">Nilai Kirim</label>
                        <div class="col-sm-4">
                            <input readonly id="dreceive" name="dreceive" class="form-control date" value="<?= $isi->d_sjpb_receive; ?>" onchange="cektanggal(this.value);">
                        </div>
                        <div class="col-sm-4">
                            <input readonly id="vsjpbrec" name="vsjpbrec" class="form-control" value="<?= number_format($isi->v_sjpb_receive); ?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly id="vsjpb" name="vsjpb" class="form-control" value="<?= number_format($isi->v_sjpb); ?>">
                        </div>
                    </div> 
                </div>
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 15%;">Kode</th>
                                <th style="text-align: center; width: 40%;">Nama Barang</th>
                                <th style="text-align: center;">Jumlah Kirim</th>
                                <th style="text-align: center;">Jumlah Terima</th>
                                <th style="text-align: center; width: 20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i ++;
                                    if($row->n_receive=='' || $row->n_receive==0) {
                                        $jmlterima = $row->n_deliver;
                                    }
                                    if($row->n_receive=='') {
                                        $row->n_receive=0;
                                    }                                        
                                    $vtotal = $row->v_unit_price * $row->n_deliver;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                        <input type="hidden" readonly class="form-control"  id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                        <input type="hidden" readonly class="form-control"  id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" style="text-align: right;" readonly="" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>">
                                    </td>
                                    <td>
                                        <input class="form-control" style="text-align: right;" onkeypress="return hanyaAngka(event);" onkeyup="ngetang();" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $jmlterima;?>">
                                        <input type="hidden" id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_receive;?>">
                                        <input type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" checked onclick='ngetang()'>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
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
    $(document).ready(function () {
        showCalendar('.date', 3, 0);
        ngetang();
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function cektanggal(dreceive) {
        var dsj  = $('#dsj').val();
        dtmprec  = dreceive.split('-');
        thnrec   = dtmprec[2];
        blnrec   = dtmprec[1];
        hrrec    = dtmprec[0];
        dtmp     = dsj.split('-');
        thnsj    = dtmp[2];
        blnsj    = dtmp[1];
        hrsj     = dtmp[0];
        tglreceive = thnrec+blnrec+hrrec;
        tglsj      = thnsj+blnsj+hrsj;
        if (tglreceive<tglsj) {
            swal("Tanggal terima tidak boleh lebih kecil dari Tanggal SJ !!!");
            $('#dreceive').val('');
            return false;
        }
    }

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nreceive"+brs).val();
            hrg  = formatulang($("#vunitprice"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
        }
        $("#vsjpbrec").val(formatcemua(tot));
    }

    function dipales(a){
        if((document.getElementById("iarea").value!='') && (document.getElementById("isj").value!='') && (document.getElementById("dreceive").value!='') ){
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nreceive"+i).value=='')){
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