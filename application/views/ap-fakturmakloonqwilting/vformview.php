<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> 
                <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>  
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-4">Bagian Pembuat</label>
                    <label class="col-md-4">Nomor Dokumen</label>
                    <label class="col-md-2">Tanggal Dokumen</label>
                    <label class="col-md-2">Tgl Terima Faktur</label> 
                    <div class="col-sm-4">
                        <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled> 
                            <?php if ($bagian) {
                                foreach ($bagian as $row):?>
                                    <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                        <?= $row->e_bagian_name;?>
                                    </option>
                                <?php endforeach; 
                            } ?>
                        </select>
                        <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <input type="text" name="inota" id="inota" readonly="" class="form-control" value="<?= $data->i_document;?>">
                        </div>
                    </div> 
                        <div class="col-sm-2">
                            <input type="text" name="dnota" id="dnota" class="form-control" value="<?= $data->d_document; ?>" readonly="" onchange="max_tgl(this.value);">
                    </div>       
                    <div class="col-sm-2">
                        <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control" value="<?= $data->d_terima_faktur; ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">Partner</label>
                    <label class="col-md-4">Nomor Referensi</label>
                    <label class="col-md-2">Tanggal Referensi</label>
                    <label class="col-md-2">Tgl Jatuh Tempo</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" id="ipartner" name="ipartner" disabled>
                            <option value="<?= $data->i_partner; ?>"><?= $data->e_supplier_name; ?></option>
                        </select>
                        <input type="hidden" class="form-control" id="itypepajak" name="itypepajak" value="<?= $data->i_type_pajak ;?>" readonly>
                        <input type="hidden" class="form-control" id="fpkp" name="fpkp" value="<?= $data->f_pkp ;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control select2" id="ireferensi" name="ireferensi" disabled="true" onchange="getdataitem(this.value);">
                            <option value="<?= $data->id_referensi; ?>"><?= $data->i_document_referensi; ?></option>
                           </select>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="dreferensi" name="dreferensi" value="<?= $data->d_document_referensi; ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="djatuhtempo" name="djatuhtempo" value="<?= $data->d_jatuh_tempo; ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">No Faktur Supplier</label>
                    <label class="col-md-2">Tanggal Faktur</label>
                    <label class="col-md-3">No Pajak</label>
                    <label class="col-md-4">Tanggal Pajak</label>
                    <div class="col-sm-3">
                        <input type="text" name="ifaktursupp" id="ifaktursupp" class="form-control" value="<?= $data->i_nota_supplier; ?>" placeholder="Nomor Faktur Supplier" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dfaktursup" id="dfaktursup" class="form-control date" value="<?= $data->d_nota_supplier; ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="ipajak" id="ipajak" class="form-control" value="<?= $data->i_pajak; ?>" placeholder="Nomor Pajak" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dpajak" id="dpajak" class="form-control date" value="<?= $data->d_pajak; ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row"> 
                    <label class="col-md-3">Diskon (Rp.)</label>     
                    <label class="col-md-3">Jml Dis Reg</label>
                    <label class="col-md-3">Nilai Total DPP</label>
                    <label class="col-md-3">Nilai Total PPN</label>   
                    <div class="col-sm-3">
                        <input type="text" name="vdiskon" id="vdiskon" class="form-control" placeholder="0" value="<?= $data->v_total_discount; ?>"
                        onkeyup="hitungdiskon()" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotaldis" id="vtotaldis" class="form-control" value="<?= $data->n_total_discount; ?>" readonly>
                        <input type="hidden" name="diskonsup" id="diskonsup" class="form-control" value="" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="<?= $data->v_total_dpp; ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="<?= $data->v_total_ppn; ?>" readonly>
                    </div>   
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Jumlah Nilai Bruto</label>
                    <label class="col-md-3">Jumlah Nilai Netto</label>
                    <label class="col-md-6">Jumlah Total</label> 
                    <div class="col-sm-3">
                       <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control" value="<?= $data->v_total_bruto; ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotalnetto" id="vtotalnetto" class="form-control" value="<?= $data->v_total_netto; ?>" readonly>
                        <input type="hidden" name="vtotalneto" id="vtotalneto" class="form-control" value="<?= $data->v_total_netto; ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                       <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="<?=$data->v_total;?>" readonly>
                    </div>         
                </div>
                <div class="form-group">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea class="form-control input-sm" id="eremark" name="eremark" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        <?php if($data->i_status=='11' && $ada == true) {?>
                            <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                        <?php }else{
                            
                        } ?>
                    </div>
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
            <table id="tabledatax" class="table color-table dark-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No SJ Masuk</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($detail){
                        $i = 0;
                        foreach($detail as $row){
                            $i++;
                    ?>
                    <tr>
                        <td>
                          <?php echo $i;?>
                        </td>
                        <td>
                            <input type="hidden" class="form-control" id="idreffitem<?=$i;?>" name="idreffitem<?=$i;?>" value="<?= $row->id_referensi_item; ?>" readonly>
                            <input style="width:200px;" type="text" class="form-control" id="ireffitem<?=$i;?>" name="ireffitem<?=$i;?>" value="<?= $row->i_document; ?>" readonly>
                        </td>
                        <td>
                            <input type="hidden" class="form-control" id="idproductwip<?=$i;?>" name="idproductwip<?=$i;?>" value="<?= $row->id_product_wip; ?>" readonly>
                            <input style="width:350px;" type="text" class="form-control" id="iproductwip<?=$i;?>" name="iproductwip<?=$i;?>" value="<?= $row->i_product_wip.' - '.$row->e_product_wipname; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:120px;text-alignment:right;" type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_quantity; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:180px;text-alignment:right;" type="text" class="form-control" id="vprice<?=$i;?>" name="vprice<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:180px;text-alignment:right;" type="text" class="form-control" id="vtotalitem<?=$i;?>" name="vtotalitem<?=$i;?>" value="<?= $row->v_total; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:250px;" type="text" class="form-control" id="edesc<?=$i;?>" name="edesc<?=$i;?>" value="<?=$row->e_remark;?>" placeholder="Isi keterangan jika ada!" readonly>
                        </td>
                    </tr>
                    <?}}?>
                </tbody>
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });
</script>