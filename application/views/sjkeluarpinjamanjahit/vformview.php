<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Perkiraan Kembali</label>  
                        <div class="col-md-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                         <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" class="form-control" value="<?= $data->i_document;?>" readonly>
                            </div>                        
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm"  required="" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="dback" name="dback" class="form-control input-sm" onchange="number();" required="" readonly value="<?= $data->d_back;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="" disabled="">
                                <option value="<?= $data->id_partner.'|'.$data->i_partner; ?>"><?= $data->epartner; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" class="form-control select2" disabled="">
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                             <textarea id="eremarkh" name="eremarkh" class="form-control" readonly><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                       <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Barang</th>
                        <th class="text-center" style="width: 13%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                     <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                                <td>                                    
                                   <?= $row->i_product;?>
                                </td>
                                <td>
                                   <?= $row->e_product;?>
                                </td>
                                <td>
                                   <?= $row->e_color_name;?>
                                </td>
                                <td>
                                    <?= $row->n_quantity;?>
                                </td>
                                <td>
                                    <?= $row->e_remark;?>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php } ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });
</script>