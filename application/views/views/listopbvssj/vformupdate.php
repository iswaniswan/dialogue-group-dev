<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> &nbsp;UPDATE <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom.'/'.$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-rotate-left"></i> Kembali</a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal Order</label><label class="col-md-6">No Order</label>
                        <div class="col-sm-6">
                            <input readonly class="form-control date" id="dorderpb" required="" name="dorderpb" value="<?= date('d-m-Y', strtotime($isi->d_orderpb)); ?>">
                            <input readonly type="hidden" required="" id="borderpb" name="borderpb" value="<?= date('m', strtotime($isi->d_orderpb)); ?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly="" class="form-control" id="iorderpb" name="iorderpb" value="<?php if($iorderpb) echo $iorderpb; ?>">
                            <input type="hidden" id="xiorderpb" name="xiorderpb" value="<?php if($iorderpb) echo $iorderpb; ?>" maxlength=7>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">SPG</label>
                        <div class="col-sm-6">
                            <input id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>" readonly>
                            <input type="hidden" id="iarea" name="iarea" class="form-control" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="espgname" name="espgname" class="form-control" value="<?= $isi->e_spg_name; ?>" readonly>
                            <input type="hidden" id="ispg" name="ispg" class="form-control" value="<?= $isi->i_spg; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="button" onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom.'/'.$dto;?>','#main'); return false;" class="btn btn-inverse btn-rounded btn-sm" ><i  class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No</th>
                                    <th style="text-align: center; width: 12%;">Kode Barang</th>
                                    <th style="text-align: center; width: 35%;">Nama Barang</th>
                                    <th style="text-align: center;">Order</th>
                                    <th style="text-align: center;">Stock</th>
                                    <th style="text-align: center; width: 30%;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) {
                                        $i++;
                                        ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $i;?>
                                                <input readonly type="hidden" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" style="text-align:right;" onkeypress="return hanyaAngka(event);" id="nquantityorder<?= $i;?>" name="nquantityorder<?= $i;?>" value="<?= $row->n_quantity_order;?>" onkeyup="hitungnilaiorder();">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" style="text-align:right;" onkeypress="return hanyaAngka(event);" id="nquantitystock<?= $i;?>" name="nquantitystock<?= $i;?>" value="<?= $row->n_quantity_stock;?>" onkeyup="hitungnilaistock();">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
            </form>
        </div>
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