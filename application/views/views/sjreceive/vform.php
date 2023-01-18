<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <div class="col-sm-6">
                            <input id="isj" name="isj" class="form-control" required="" readonly value="<?= $isi->i_sj;?>">
                            <input type="hidden" readonly id="n_spb_toplength" name="n_spb_toplength" value="<?= $n_spb_toplength; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dsjx" name="dsjx" class="form-control" required="" readonly value="<?= $isi->dsj;?>">
                            <input type="hidden" id= "dsj" name="dsj" class="form-control" required="" readonly value="<?= $isi->d_sj;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-6">SJ Lama</label><label class="col-md-6">Nilai SJ</label>
                        <div class="col-sm-6">
                            <input id="isjold" name="isjold" class="form-control" value="<?= $isi->i_sj_old; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "vsjnetto" name="vsjnetto" class="form-control" readonly value="<?= number_format($isi->v_nota_netto);?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" onclick="return dipales();" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div> 
                <div class="col-md-6">                         
                    <div class="form-group row">
                        <label class="col-md-6">No SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
                            <input id="ispb" name="ispb" class="form-control" required="" readonly value="<?= $isi->i_spb;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dspbx" name="dspbx" class="form-control" required="" readonly value="<?= $isi->dspb;?>">
                            <input type="hidden" id= "dspb" name="dspb" class="form-control" required="" readonly value="<?= $isi->d_spb;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nama Toko</label>
                        <div class="col-sm-12">
                            <input id= "ecustomername" name="ecustomername" class="form-control" required="" readonly value="<?= $isi->e_customer_name;?>">
                            <input id="icustomer" name="icustomer" type="hidden" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group has-error has-feedback row">
                        <label class="col-md-5">Tanggal Terima</label><label class="col-md-7">Keterangan</label>
                        <div class="col-sm-5">
                            <input id="ddkb" name="ddkb" type="hidden" value="<?= $isi->d_dkb; ?>">
                            <input placeholder="Terima Toko" id="dsjreceive" name="dsjreceive" class="form-control date" required="" readonly onchange="cektanggal(this.value);">
                            <span class="help-block">Tanggal Receive tidak boleh lebih dari 3 hari kerja sebelum hari ini!!!</span>
                        </div>
                        <div class="col-sm-7">
                            <input placeholder="Isi Keterangan" id="eremark" name="eremark" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode Barang</th>
                                <th style="text-align: center; width: 40%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Qty Pesan</th>
                                <th style="text-align: center;">Qty Kirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    /*$thnbl = substr($isi->d_sj, 0,4).substr($isi->d_sj, 5,2);
                                    $nilai=number_format($row->v_unit_price,2);
                                    $jujum=number_format($row->n_order,0);
                                    $ntot =number_format($row->v_unit_price*$row->n_order,2);
                                    $f_spb_stockdaerah = $this->mmaster->cekdaerah($ispb);
                                    if($f_spb_stockdaerah=='f'){
                                        $query = $this->mmaster->stock1($thnbl, $row->i_product, $row->i_product_grade);
                                    }else{
                                        $query = $this->mmaster->stock2($thnbl, $row->i_product, $row->i_product_grade, $istore);
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
                                    if($stock > $row->n_qty){
                                        $stock = $row->n_qty;
                                    }
                                    if($stock < 0){
                                        $stock=0;
                                    }
                                    $vtot  = $row->harga * $stock;
                                    $stock = number_format($stock);*/
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="motif<?=$i;?>" name="motif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                    </td>
                                    <td>
                                        <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" readonly type="text" id="emotifname<?=$i;?>" name="emotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                        <input type="hidden" id="vproductmill$i" name="vproductmill$i" value="<?= $row->v_unit_price;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="norder$i" name="norder$i" value="<?= $row->n_qty;?>">
                                    </td>
                                    <td>
                                        <input style="text-align: right;" class="form-control" readonly id="ndeliver$i" name="ndeliver$i" value="<?= $row->n_deliver;?>"">
                                        <input type="hidden" id="ntmp$i" name="ntmp$i" value="<?= $row->n_deliver;?>">
                                        <!-- <input type="hidden" id="ndeliverhidden$i" name="ndeliverhidden$i" value="<?= $stock;?>">
                                            <input type="hidden" id="vtotal$i" name="vtotal$i" value=""> -->
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
        showCalendar('.date', 3, 0);
    });

    /*function cektanggal() {
        var ddkb = $('#ddkb').val();
        var dsjreceive = $('#dsjreceive').val();

        if (dsjreceive<ddkb && (ddkb!=''||ddkb!=null)) {
            swal('Tidak boleh lebih kecil dari tanggal DKB!');
            $('#dsjreceive').val('');
        }
    }*/

    function cektanggal(dreceive) {
        var dsj  = $('#ddkb').val();
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
            swal('Tidak boleh lebih kecil dari tanggal DKB!');
            $('#dreceive').val('');
            return false;
        }
    }

    function dipales(){
        if(document.getElementById("dsjreceive").value==''){
            swal('Tanggal terima belum diisi!');
            return false;
        }
    }
</script>