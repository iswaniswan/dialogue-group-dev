<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
               <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" name="ibonk" id="dokumenbon" value="<?= $data->i_keluar_qc;?>" class="form-control input-sm" readonly>                                
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm" required="" readonly value="<?= $data->d_keluar_qc;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled="">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->id_bagian;?>" <?php if ($row->id_bagian==$data->i_tujuan) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name ?> - <?= $row->name ?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Barang Keluar</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2" disabled=''>
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row):?>
                                        <option value="<?= $row->id;?>" <?php if ($row->id==$data->id_jenis_barang_keluar) {?> selected <?php } ?>>
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-9">
                            <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark;?></textarea>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <div class="col">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-warning btn-block btn-sm mr-2" onclick="statuschange('<?= $folder."','".$data->id;?>','1','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-pencil-square-o mr-2"></i>Change Requested</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger btn-block btn-sm mr-2"  onclick="statuschange('<?= $folder."','".$data->id;?>','4','<?= $dfrom."','".$dto;?>');"> <i class="fa fa-times mr-2"></i>Reject</button>
                        </div>
                        <div class="col">
                            <button type="button" id="approve" class="btn btn-success btn-block btn-sm mr-2"> <i class="fa fa-check-square-o mr-2"></i>Approve</button>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="row">
        <div class="col-sm-11">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-1" style="text-align: right;">
            <?= $doc; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th style="width: 15%;">Kode Barang</th>
                            <th style="width: 27%;">Nama Barang Jadi</th>
                            <th style="width: 15%;">Warna</th>
                            <th class="text-right" style="width: 10%;">Stock</th>
                            <th class="text-right" style="width: 10%;">Quantity</th>
                            <th style="width: 30%;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>                   
                    <?php 
                        $i = 0;
                        if ($detail) {
                            foreach ($detail as $row) {
                                $i++;?>
                                <tr>
                                    <td class="text-center">
                                        <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                    </td>
                                    <td>
                                        <?= $row->i_product_base;?>
                                    </td>
                                    <td>
                                        <?= $row->e_product_basename;?>
                                    </td>
                                    <td>
                                        <?= $row->e_color_name;?>
                                    </td>
                                    <td class="text-right">
                                        <?= $row->saldo_akhir;?>
                                    </td>
                                    <td class="text-right">
                                        <?= $row->n_quantity_product;?>
                                    </td>
                                    <td>
                                        <?= $row->e_remark;?>
                                    </td>
                                </tr>
                                <tr class="th<?= $i; ?> bold table-active">
                                    <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                    <td colspan="7"><b>Bundling Produk</b></td>
                                </tr>
                                <?php $o = 1; foreach($bundling as $b) {
                                    if($b->id_keluar_qc_item == $row->id) { 
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <spanx id="snum<?= $i; ?>"><?= $o; ?></spanx>
                                            </td>
                                            <td>
                                                <?= $b->i_product_base; ?>
                                            </td>
                                            <td  class="d-flex justify-content-between">
                                                <span>
                                                    <?= $b->e_product_basename; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= $b->e_color_name; ?>
                                            </td>
                                            <td></td>
                                            <td class="text-right">
                                                <?= $b->n_quantity_bundling; ?>
                                            </td>
                                            <td>
                                                <?= $b->e_remark; ?>
                                            </td>
                                        </tr>
                                <?php
                                        } $o++;
                                    } ?>
                            <?php } 
                        }?>
                        <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width : '100%',
        });
    });

    function getrequest(){
        
    }

    $('#approve').click(function(event) {
        ada = false;
        if (!ada) {
            statuschange('<?= $folder;?>',$('#id').val(),'6','<?= $dfrom."','".$dto;?>');
        }else{
            return false;
        }
    });
</script>
