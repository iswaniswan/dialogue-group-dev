<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-3">
                            <input type="text" name="ebagian" id="ebagian" class="form-control input-sm" value="<?= $data->e_bagian;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id ;?>" readonly>
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>" readonly>
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ispbb" id="ispbb" class="form-control input-sm" value="<?= $data->i_spbb;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dspbb" id="dspbb" class="form-control input-sm" value="<?= $data->d_spbb;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Permintaan Dari</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="itujuan" id="itujuan" class="form-control input-sm" value="<?= $data->i_bagian_tujuan;?>" readonly>
                            <input type="text" name="etujuan" id="etujuan" class="form-control input-sm" value="<?= $data->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-9">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" readonly="" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
    <div class="white-box" id="detail">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 10%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Material</th>
                        <th class="text-center" style="width: 8%;">Satuan</th>
                        <th class="text-center" style="width: 8%;">Jml SPBB</th>
                        <th class="text-center" style="width: 10%;">Panjang Kain</th>
                        <th class="text-center" style="width: 10%;">Panjang Kain Sisa</th>
                        <th class="text-center" style="width: 8%;">Jml Keluar</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                     $group = '';
                     foreach ($datadetail as $key) {?>
                        <tr>
                            <?php if($group==""){?>
                                <td colspan="9"><?= $key->e_product_wipname.'-'.$key->e_color_name;?></td>
                            <?php }else{ 
                                if($group!=$key->id_product_wip){?>
                                <td colspan="9"><?= $key->e_product_wipname.'-'.$key->e_color_name;?></td>
                            <?php $i = 1;}
                            }?>
                        </tr>
                        <?php $group = $key->id_product_wip; ?>
                        <tr>
                            <td class="text-center"><?= $i+1;?></td>
                            <td><?= $key->i_material;?></td>
                            <td><?= $key->e_material_name;?></td>
                            <td><?= $key->e_satuan_name;?></td>
                            <td class="text-right"><?= $key->qtywip;?></td>
                            <td class="text-right"><?= $key->n_panjang_kain;?></td>
                            <td class="text-right"><?= $key->n_panjang_kain_sisa;?></td>
                            <td class="text-right"><?= $key->qtymaterial;?></td>
                            <td><?= $key->e_remark;?></td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>