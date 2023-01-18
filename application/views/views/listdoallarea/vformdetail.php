<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Nomor OP</label><label class="col-md-4">Nomor DO</label><label class="col-md-4">Tanggal DO</label>
                        <div class="col-sm-4">
                            <input id="iop" name="iop" class="form-control"  value="<?= $isi->i_op;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id= "ido" name="ido" class="form-control" value="<?= $isi->i_do;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id= "ddo" name="ddo" class="form-control" value="<?= date('d-m-Y', strtotime($isi->d_do));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Area</label><label class="col-md-4">Pemasok</label><label class="col-md-4">Nilai Kotor</label>
                        <div class="col-sm-4">
                            <input id="eareaname" name="eareaname" class="form-control"  value="<?= $isi->e_area_name; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id= "esuppliername" name="esuppliername" class="form-control" value="<?= $isi->e_supplier_name; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id= "vdogross" name="vdogross" class="form-control" value="<?= number_format($isi->v_do_gross); ?>" readonly>
                        </div>
                    </div>    
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iperiode;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center; width: 5%;">Ket</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Qty Kirim</th>
                                <th style="text-align: center;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php               
                            $i=0;
                            if($detail){
                                foreach($detail as $row){ 
                                    $i++;
                                    $pangaos=number_format($row->v_product_mill,2);
                                    $total=$row->v_product_mill*$row->n_deliver;
                                    $total=number_format($total,2);
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                    </td>
                                    <td>
                                        <input style="font-size: 12px;" class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                    </td>
                                    <td>
                                        <input style="font-size: 12px;" class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                    </td>
                                    <td>
                                        <input style="font-size: 12px;" class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>" readonly>
                                    </td>
                                    <td>
                                        <input style="font-size: 12px; text-align: right;" readonly class="form-control" width:85px;"  id="vproductretail<?= $i;?>" name="vproductretail<?= $i;?>" value="<?= $pangaos;?>">
                                    </td>
                                    <td>
                                        <input style="font-size: 12px; text-align: right;" class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>" readonly>
                                    </td> 
                                    <td>
                                        <input style="font-size: 12px; text-align: right;" readonly class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $total;?>">
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                        <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>