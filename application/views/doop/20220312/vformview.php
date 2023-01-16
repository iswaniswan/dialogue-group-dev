<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Jenis SPB</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" value="<?= $data->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="text" class="form-control input-sm" value="<?= $data->i_document;?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?=$data->e_type_name;?>">
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-4">Customer</label>  
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>   
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?=$data->e_area;?>">
                        </div> 
                        <div class="col-sm-4">
                            <input type="text" class="form-control input-sm" readonly value="<?=$data->e_customer_name;?>">
                        </div> 
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm" readonly value="<?=$data->i_referensi;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control input-sm" readonly value="<?=$data->d_referensi;?>">
                        </div> 
                    </div>     
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>   
                        <div class="col-sm-12">
                           <textarea id="eremark" name="eremark" class="form-control" readonly><?= $data->e_remark; ?></textarea>
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="12%;">Kode</th>
                        <th class="text-center" width="30%;">Nama Barang</th>
                        <th class="text-center">Saldo</th>
                        <th class="text-center">Qty SPB</th>
                        <th class="text-center">Qty Sisa</th>
                        <th class="text-center">Qty SJ</th>
                        <th class="text-center" width="20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($datadetail){
                        $i = 0;
                        foreach($datadetail as $row){
                            $i++;                             
                    ?>
                    <tr>   
                        <td class="text-center">
                            <?= $i;?>
                        </td> 
                        <td>  
                            <?= $row->i_product_base; ?>
                        </td>
                        <td>
                            <?= $row->e_product_basename; ?>
                        <td class="text-right">
                            0
                        </td>              
                        <td class="text-right">
                            <?= $row->nquantity_permintaan; ?>
                        </td>
                        <td class="text-right">
                            <?= $row->nquantity_pemenuhan; ?>
                        </td>
                        <td class="text-right">
                            <?= $row->n_quantity; ?>
                        <td>
                            <?=$row->e_remark;?>
                        </td>                                            
                    </tr>                       
                    <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    <?php }
                    }?>        
                </tbody>
            </table>
        </div>
    </div>
</div>