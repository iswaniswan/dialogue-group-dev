<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom.'/'.$dto.'/'.$gudang.'/' ;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <div class="col-md-6"><!-- START -->
                    <div class="form-group row">
                        <label class="col-md-6">No PP</label><label class="col-md-6">Tgl PP</label>
                        <div class="col-md-6">
                            <input type="text" name="ipp" id="ipp" class="form-control" value = "<?= $head->i_pp;?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="dpp" id="dpp" class="form-control" value="<?= $head->d_pp;?>" disabled>
                        </div>
                    </div>  

                    <div class="form-group row">
                        <label class="col-md-6">No OP</label><label class="col-md-6">Tgl OP</label>
                        <div class="col-md-6">
                            <input type="text" name="iop" id="iop" class="form-control" value="<?= $head->i_op;?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="dop" id="dop" class="form-control" value="<?= $head->d_op;?>" disabled>
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-md-12">
                            <textarea class="form-control" disabled><?= $head->e_remark;?></textarea>
                        </div>
                    </div>  

                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$gudang."/";?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>

                </div><!-- END COLUMN 1-->
                    
                <?php 
                $counter = 0; 
                if ($detail) {?>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 3%;">No</th>
                                        <th class="text-center" style="width: 10%;">Kode Material</th>
                                        <th class="text-center" style="width: 37%;">Nama Material</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center" style="width: 10%;">Jumlah PP</th>
                                        <th class="text-center" style="width: 10%;">Jumlah OP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($detail as $row) {
                                        $counter++;?>
                                        <tr>
                                            <td class="text-center">
                                                <spanx id="snum<?= $counter;?>"><?= $counter;?></spanx>
                                            </td>
                                            <td>
                                                <input value="<?= $row->i_material;?>" readonly="" type="text" id="imaterial<?= $counter;?>" class="form-control" name="imaterial<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->e_material_name;?>" readonly="" type="text" readonly id="ematerialname<?= $counter;?>" class="form-control" name="ematerialname<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->e_satuan;?>" readonly="" type="text" readonly id="esatuan<?= $counter;?>" class="form-control" name="esatuan<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->n_order;?>" type="text" id="norder<?= $counter;?>" class="form-control text-right" name="norder<?= $counter;?>" readonly>
                                            </td>
                                            <td>
                                                <input value="<?= $row->n_deliver;?>" type="text" id="ndeliver<?= $counter;?>" class="form-control text-right" name="ndeliver<?= $counter;?>" readonly>
                                            </td>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php } ?>
                <input type="hidden" name="jml" id="jml" readonly value="<?= $counter;?>">
            </div>
        </div><!-- END Panel Info -->
    </div>
</div>