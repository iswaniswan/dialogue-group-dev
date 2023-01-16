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
                        <label class="col-md-6">Tanggal Nota</label><label class="col-md-6">No Nota</label>
                        <div class="col-sm-6">
                            <input id= "dnota" name="dnota" class="form-control" required="" readonly value="<?= $isi->d_sj;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="inotaold" name="inotaold" readonly class="form-control" value="<?= $isi->i_nota ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">No SPB</label><label class="col-md-6">Tanggal SPB</label>
                        <div class="col-sm-6">
                            <input id="ispb" name="ispb" class="form-control" readonly value="<?= $isi->i_spb;?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "dspbx" name="dspbx" class="form-control" required="" readonly value="<?= $isi->d_spb;?>">
                            <input type="hidden" id= "dspb" name="dspb" class="form-control" required="" readonly value="<?= $isi->d_spb;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                            <input type="hidden" readonly id="spbold" name="spbold" value="<?php echo $isi->i_spb_old; ?>"readonly >
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input id= "ecustomername" name="ecustomername" class="form-control" required="" readonly value="<?= $isi->e_customer_name;?>">
                            <input id="icustomer" name="icustomer" type="hidden" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Konsinyasi</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Masalah</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fcicil" name="fcicil" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Cicil</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-12">
                            <input type="text" id="ispbpo" name="ispbpo" class="form-control" maxlength="30" readonly value="<?= $isi->i_spb_po; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Insentif</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <span class="custom-control-description">Nota Lama&nbsp;&nbsp;</span>
                                    <input class="form-control" name="inotaold" id="inotaold" type="text" readonly="" value="<?= $isi->i_nota_old; ?>">
                                </label>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <label class="custom-control custom-checkbox">
                                    <span class="custom-control-description">TOP&nbsp;&nbsp;</span>
                                    <input class="form-control" name="nspbtoplength" id="nspbtoplength" type="text" readonly="" value="<?= $isi->n_spb_toplength; ?>">
                                </label>
                            </div>
                        </div>
                        <?php 
                        $tmp = explode("-", $isi->d_sj);
                        $det    = $tmp[2];
                        $mon    = $tmp[1];
                        $yir    = $tmp[0];
                        $dsj    = $yir."/".$mon."/".$det;
                        if(substr($isi->i_sj,8,2)=='00'){
                            $topnya=$isi->n_spb_toplength;
                        }else{
                            $topnya=$isi->n_spb_toplength;
                        }
                        $dudet  = dateAdd("d",$topnya,$dsj);
                        $dudet  = explode("-", $dudet);
                        $det1   = $dudet[2];
                        $mon1   = $dudet[1];
                        $yir1   = $dudet[0];
                        $dudet  = $det1."-".$mon1."-".$yir1;
                        ?>
                        <div class="col-md-5">
                            <div class="form-check">
                                <label class="custom-control" >
                                    <span class="custom-control-description">Jatuh Tempo&nbsp;&nbsp;</span>
                                    <input class="form-control" name="djatuhtempo" id="djatuhtempo" type="text" readonly="" value="<?= $dudet; ?>">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Surat Jalan</label><label class="col-md-6">Tanggal Surat Jalan</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="fspbstokdaerah" name="fspbstokdaerah" class="form-control" maxlength="7" value="">
                            <input type="text" id="isj" name="isj" class="form-control" maxlength="15" value="<?php echo $isi->i_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" id="dsj" name="dsj" class="form-control date" value="<?php echo $isi->d_sj; ?>" readonly>
                            <input type="text" id="dsx" name="dsx" class="form-control date" value="<?php echo $isi->d_sj; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai SPB (Realisasi)</label>
                        <div class="col-sm-12">
                            <input id="vspbafter" name="vspbafter" readonly class="form-control" required="" onkeyup="hitungdiscount();reformat(this);" value="<?= number_format($isi->v_nota_netto);?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input readonly id="eremark" name="eremark" value="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            &nbsp;&nbsp;<button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6"> 
                    <?php 
                    $enin=number_format($isi->v_spb);
                    ?>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-12">
                            <input type="text" id="vspb" name="vspb" class="form-control" required="" readonly value="<?= $enin; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                        <div class="col-sm-6">
                            <input readonly id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_nota_discount1; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discount1); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                        <div class="col-sm-6">
                            <input readonly id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_nota_discount2; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" required=""
                            readonly value="<?= number_format($isi->v_nota_discount2); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                        <div class="col-sm-6">
                            <input readonly id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_nota_discount3; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" required="" readonly value="<?= number_format($isi->v_nota_discount3); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Discount 4</label><label class="col-md-6">Nilai Discount 4</label>
                        <div class="col-sm-6">
                            <input readonly id="ncustomerdiscount4" name="ncustomerdiscount4" class="form-control" required="" onkeypress="return hanyaAngka(event);" onkeyup="formatcemua(this.value); editnilai();" value="<?= $isi->n_nota_discount4; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="vcustomerdiscount4" name="vcustomerdiscount4" class="form-control" required=""
                            readonly value="<?= number_format($isi->v_nota_discount4); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-12">
                            <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" required="" 
                            value="<?= number_format($isi->v_nota_discounttotal); ?>">
                        </div>
                    </div>
                    <?php 
                    $tmp=$isi->v_spb-$isi->v_spb_discounttotal;
                    ?>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Bersih</label>
                        <div class="col-sm-12">
                            <input id="vspbbersih" name="vspbbersih" class="form-control" required="" readonly value="<?= number_format($tmp); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount Total (Realisasi)</label>
                        <div class="col-sm-12">
                            <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" required="" onkeyup="hitungnilai();reformat(this);"
                            readonly value="<?= number_format($isi->v_nota_discounttotal); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kotor (Realisasi)</label>
                        <div class="col-sm-12">
                            <input readonly id="vnotagross" name="vnotagross" class="form-control" required="" onkeyup="hitungdiscount();reformat(this);" value="<?= number_format($isi->v_nota_gross);?>">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%;" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode Barang</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Jumlah Kirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    $harga  =number_format($row->v_unit_price,2);
                                    $ndeliv =number_format($row->n_deliver,0);
                                    ?>
                                    <tr>
                                        <td>
                                            <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
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
                                        </td>
                                        <td>
                                            <input class="form-control" style="text-align:right;" readonly type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>" value="<?= $harga; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" style="text-align:right;" readonly type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>" value="<?= $ndeliv; ?>">
                                            <input class="form-control" style="text-align:right;" readonly type="hidden" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>" value="">
                                        </td>
                                    </tr>
                                <?php  } ?>
                                <input type="hidden" readonly name="jml" id="jml" value="<?= $i;?>">
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
            $('.select2').select2();
            showCalendar('.date');
    });
</script>