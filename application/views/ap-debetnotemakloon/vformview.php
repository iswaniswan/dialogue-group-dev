<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
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
                                <input type="text" name="idocument" id="idocument" readonly="" onkeyup="gede(this);" placeholder="DN-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dnoteretur" name="dnoteretur" class="form-control input-sm date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_document)); ?>"  >
                        </div>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" disabled=""> 
                                <option value="<?php echo $data->id_supplier;?>"><?= $data->e_supplier_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label> 
                        <label class="col-md-2">Tanggal Referensi</label>
                        <label class="col-md-3">No Faktur Supplier</label> 
                        <label class="col-md-2">No Faktur</label> 
                        <label class="col-md-2">Tanggal Faktur</label> 
                        <div class="col-sm-3">  
                            <select name="ireferensi" id="ireferensi" class="form-control select2" disabled=""> 
                                <option value="<?php echo $data->id_document_reff.'|'.$data->i_bagian_referensi;?>"><?= $data->i_document_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control" value="<?php echo $data->d_referensi?>" readonly>   
                        </div>
                        <div class="col-sm-3"> 
                            <input type="text" name="ifaksup" id="ifaksup" class="form-control" value="<?php echo $data->i_faktur_supplier?>" readonly>   
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" name="ifakpajak" id="ifakpajak" class="form-control" value="<?php echo $data->i_faktur_pajak?>" readonly>   
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" name="dfakpajak" id="dfakpajak" class="form-control" value="<?php echo $data->d_pajak?>" readonly>   
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Total Retur</label>    
                        <label class="col-md-2">Total PPN</label>
                        <label class="col-md-7">Total DPP</label>
                        <div class="col-sm-3">                           
                            <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="<?php echo $data->v_total?>" readonly>
                        </div>
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="<?php echo $data->v_total_ppn?>" readonly>
                        </div>
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="<?php echo $data->v_total_dpp?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!" disabled><?= $data->e_remark;?></textarea> 
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Harga Total</th>
                        <th class="text-center">DPP</th>
                        <th class="text-center">PPN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                        foreach ($datadetail as $row) {
                        $i++;
                    ?>
                    <tr>
                        <td class="text-center"><?= $i; ?></td>
                        <td><?= $row->i_material; ?></td>
                        <td><?= $row->e_material_name; ?></td>
                        <td><?= $row->n_quantity; ?></td> 
                        <td><?= $row->v_price; ?></td>
                        <td><?= $row->v_price_total; ?></td>
                        <td><?= $row->v_dpp; ?></td>
                        <td><?= $row->v_ppn; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
    })
</script>