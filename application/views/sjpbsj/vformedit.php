<?php 
include ("php/fungsi.php");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal SPB</label>
                        <div class="col-sm-5">
                            <input readonly id="ispb" name="ispb" type="text" class="form-control" value="">
                        </div>
                        <div class="col-sm-3">
                            <input readonly id="dspb" name="dspb" class="form-control date" value="<?= $header->d_sjpb_receive; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                            <div class="col-sm-5">
                                <input readonly id="eareaname" name="eareaname" class="form-control" value="PUSAT BABY">
                                <input type="hidden" id="iarea" name="iarea" class="form-control" value="PB">
                                <input id="istorelocation" name="istorelocation" type="hidden" class="form-control" value="00">
				            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-5">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?= $header->e_customer_name; ?>">
                            <input type="hidden" id="icustomer" name="icustomer" class="form-control" value="<?= $header->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-6">
                            <input type="text" readonly class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PO</label>
                        <div class="col-sm-6">
                            <input readonly id="ispbpo" name="ispbpo" class="form-control" value="" maxlength="30">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Konsinyasi</label>
                        <div class="col-sm-3">
                            <div class="form-check has-error">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbconsigment" name="fspbconsigment" class="custom-control-input" checked value="on">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">SPB Lama</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <input readonly id="ispbold" name="ispbold" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-5">
                            <input maxlength="3" id="nspbtoplength" name="nspbtoplength" readonly class="form-control" value="36">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check has-error">
                                <label class="custom-control custom-checkbox">
                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" checked value="on">
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Stock Daerah</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>

                        <div class="col-sm-5">
                            <input readonly id="esalesmanname" name="esalesmanname" class="form-control" value="KONSINYASI-TL">
                            <input id="isalesman" name="isalesman" type="hidden" class="form-control" value="TL">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Stock Daerah</label>
                        <div class="col-sm-5">
                            <input id="isj" name="isj" readonly class="form-control" value="">
                        </div>
                        <div class="col-sm-3">
                            <input readonly id="dsj" name="dsj" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-5">
                            <input type="text" id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" readonly class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Kelompok Harga</label>
                        <div class="col-sm-5">
                            <input readonly id="epricegroupname" name="epricegroupname" class="form-control" value="Harga Luar Pulau">
                            <input id="ipricegroup" name="ipricegroup" type="hidden" class="form-control" value="03">
                            <input id="i_kode_harga" name="i_kode_harga" type="hidden" class="form-control" value="<?= $i_kode_harga; ?>">
                            <input id="i_sjpb" name="i_sjpb" type="hidden" class="form-control" value="<?= $i_sjpb; ?>">
                            <input id="pilihan" name="pilihan" type="hidden" class="form-control" value="<?= $pilihan; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vspb" name="vspb" class="form-control" value="<?= $nilaikotor->nilaikotor; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 1</label>
                        <?php
                            if($pilihan == 'biasa'){                               
                                $diskon1persen = ($nilaikotor->nilaikotor * 0.01);
                                $nilaibersih = ($nilaikotor->nilaikotor - $diskon1persen);
                            }else{
                                $diskon1persen = 0;
                                $nilaibersih = $nilaikotor->nilaikotor;
                            }
						?>
                        <div class="col-sm-4">
                            <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control" value="<?php if($pilihan == 'biasa'){ echo number_format(1, 2); }else{ echo number_format(0, 2); } ?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <input id="vcustomerdiscount1" name="vcustomerdiscount1" class="form-control" value="<?= number_format($diskon1persen, 0); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 2</label>
                        <div class="col-sm-4">
                            <input readonly id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control" value="<?= number_format(0, 2); ?>">
                        </div>
                        <div class="col-sm-5">
                            <input readonly id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control" value="<?= number_format(0, 0); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount 3</label>
                        <div class="col-sm-4">
                            <input readonly id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control" value="<?= number_format(0, 2); ?>">
                        </div>
                        <div class="col-sm-5">
                            <input readonly id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control" value="<?= number_format(0, 0); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-6">
                            <input id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" value="<?= number_format($diskon1persen, 0); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Bersih</label>
                        <div class="col-sm-6">
                            <input readonly id="vspbbersih" name="vspbbersih" class="form-control" value="<?= number_format($nilaibersih, 0); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Discount Total (Realisasi) </label>
                        <div class="col-sm-6">
                            <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" readonly value="<?= number_format(0, 0); ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai SPB (Realisasi)</label>
                        <div class="col-sm-6">
                            <input id="vspbafter" name="vspbafter" readonly class="form-control" value="<?= number_format(0, 0); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-5">
                            <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control" value="">
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table table-bordered" width="100%;" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 7%;">No</th>
                                    <th style="text-align: center; width: 20%;">No SJ</th>
                                    <th style="text-align: center; width: 20%;">No Nota</th>
                                    <th style="text-align: center; width: 15%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center; width: 7%;">Motif</th>
                                    <th style="text-align: center; width: 15%;">Harga Sebelum</th>
                                    <th style="text-align: center; width: 15%;">Harga Sesudah</th>
                                    <th style="text-align: center; width: 10%;">Jumlah Pesan</th>
                                    <th style="text-align: center; width: 10%;">Jumlah Pemenuhan</th>
                                    <th style="text-align: center; width: 15%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach($detail as $row){
                                        $i++;
                                        $pangaos=number_format($row->bersih,2);
                                        $total=$row->bersih*$row->n_receive;
                                        $total=number_format($total,2);
                                        $v_product_retail = number_format($row->v_product_retail, 2);
                                        if ($sj_nota){
                                            $isj = $sj_nota->i_sj;
                                            $nota = $sj_nota->i_nota;
                                        }else{

                                        }
                                    ?>
                                        <tr>
                                            <td>
                                                <input style="text-align:center;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="isj<?=$i;?>" name="isj<?=$i;?>" value="<?= $isj; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="nota<?=$i;?>" name="nota<?=$i;?>" value="<?= $nota; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                            </td>
                                            <td>
                                                <input readonly type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                            </td>
                                            <td>
                                                <input readonly type="text" class="form-control" id="motif<?=$i;?>" name="motif<?=$i;?>" value="00">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>" value="<?= $v_product_retail; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly type="text" id="pangaos<?=$i;?>" name="pangaos<?=$i;?>" value="<?= $pangaos; ?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="nreceive<?=$i;?>" name="nreceive<?=$i;?>" value="<?= $row->n_receive; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="nreceive<?=$i;?>" name="nreceive<?=$i;?>" value="<?= $row->n_receive; ?>" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" type="text" id="total<?=$i;?>" name="total<?=$i;?>" value="<?= $total; ?>" readonly>
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
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
 $(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
 });
</script>
