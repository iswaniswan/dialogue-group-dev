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
                        <label class="col-md-6">Nomor KS</label>
                        <label class="col-md-6">Tanggal KS</label>
                        <div class="col-sm-6">
                            <?php 
                            $tmp=explode("-",$isi->d_ic_convertion);
                            $th=$tmp[0];
                            $bl=$tmp[1];
                            $hr=$tmp[2];
                            $dicconvertion=$hr."-".$bl."-".$th;
                            ?>
                            <input id="iicconvertion" name="iicconvertion" class="form-control" required="" readonly value="<?= $isi->i_refference;?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dicconvertion" name="dicconvertion" class="form-control date" required="" readonly value="<?= $dicconvertion;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>">
                            <input type="hidden" id="iarea" name="iarea" class="form-control" value="<?= $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iperiode;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <?php $a = 0;?>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabeldata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 8%;">No</th>
                                    <th style="text-align: center; width: 25%;">Kode</th>
                                    <th style="text-align: center;">Nama Barang</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php
                                    if ($detail) {
                                        $a = 0;
                                        foreach ($detail as $row) {
                                            $a++;?>
                                            <tr>
                                                <td style="text-align: center;">
                                                    <input type="text" readonly class="form-control" readonly id="baris<?= $a;?>" name="baris<?= $a;?>" value="<?= $a;?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="iproduct<?= $a;?>" name="iproduct<?= $a;?>" value="<?= $row->i_product; ?>">
                                                </td>
                                                <td>
                                                    <input class="form-control" readonly type="text" id="eproductname<?= $a;?>" name="eproductname<?= $a;?>" value="<?= $row->e_product_name; ?>">
                                                    <input type="hidden" id="iproductmotif<?= $a;?>" name="iproductmotif<?= $a;?>" value="<?= $row->i_product_motif; ?>">
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                    <input type="hidden" name="jml" id="jml" value="<?php echo $a;?>">
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    
</script>