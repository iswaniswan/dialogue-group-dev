<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #ddd;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>        
            <div class="panel-body table-responsive">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Batasan Pemenuhan</label>
                        <label class="col-md-2">Jenis Pembelian</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?=$data->bagian_pembuat;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_op;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dop" name="dop" class="form-control input-sm date" required="" readonly value="<?= $data->d_op; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbp" name="dbp" class="form-control input-sm date"  required="" readonly value="<?=$data->d_deliv;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="jenis" name="jenis" class="form-control input-sm" required="" readonly value="<?= $data->jenis_pembelian; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <!-- <label class="col-md-3">Gudang</label>
                        <label class="col-md-3">No Referensi</label> -->
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">Importance Status</label>
                        <label class="col-md-6">Keterangan</label>
                        <!-- <div class="col-sm-3">
                           <input type="hidden" name="igudang" id="igudang" class="form-control"value="<?= $data->i_bagian ?>" readonly required>
                           <input type="text" name="egudang" id="egudang" class="form-control input-sm"value="<?= $data->gudang ?>" readonly required>
                       </div>
                       <div class="col-sm-3">
                           <input type="text" name="ipp" id="ipp" class="form-control input-sm" value="<?= $data->i_pp ?>" readonly required>
                       </div> -->
                       <div class="col-sm-3">
                        <input type="hidden" id="isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier;?>" readonly> 
                        <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_supplier." - ".$data->e_supplier_name;?>" readonly>
                        <input type="hidden" id="esuppliername" name="esuppliername" value="<?=$data->e_supplier_name;?>">
                        <input type="hidden" id="ntop" name="ntop" value="<?=$data->n_top;?>">
                        <?php if($data->i_type_pajak == 'I'){
                            $fppn = 't';
                        }else if($data->i_type_pajak == 'E'){
                            $fppn = 'f';
                        }
                        ?>
                        <input type="hidden" id="itypepajak" name="itypepajak" value="<?=$data->i_type_pajak;?>">
                        <input type="hidden" id="fppn" name="fppn" value="<?=$fppn;?>">
                        <input type="hidden" id="ndiskon" name="ndiskon" value="<?=$data->n_diskon;?>">
                        <input type="hidden" id="fpkp" name="fpkp" value="<?=$data->f_pkp;?>">
                    </div>
                    <div class="col-sm-3">
                        <select name="importantstatus" id="importantstatus" class="form-control select2" disabled="true"> 
                            <option value="<?=$data->i_status_op;?>"><?=$data->e_status_op;?></option>
                        </select>    
                    </div>
                    <div class="col-sm-6">
                        <textarea type="text" name="eremark" id="eremark" class="form-control input-sm" value="" readonly><?=$data->e_remark;?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?= $dfrom; ?>/<?= $dto; ?>","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" style="white-space: nowrap" cellpadding="9" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode</th>
                        <th>Nama Material</th>
                        <th>Kode Material Supplier</th>
                        <th class="text-right">Qty</th>  
                        <th>Satuan</th>
                        <th class="text-right">Harga Lama</th>                        
                        <th class="text-right">Harga Baru Exc</th>
                        <th class="text-right">Harga Baru Inc</th>
                        <th class="text-right">Total</th>
                        <th>Keterangan</th>                           
                    </tr>
                </thead>
                <tbody>  
                    <?php            
                    if($data2){
                        $i = 0;
                        $group = "";
                        $no = 0;
                        foreach($data2 as $row){
                            $i++;
                            $no++;
                            $total = $row->n_quantity*$row->v_price_ppn;
                            if($group==""){ ?>
                                <tr class="pudding">
                                <td colspan="11">Nomor PP : <b><?= $row->i_pp;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp;?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name;?> )</td>
                                </tr>
                            <?php } else {
                                    if($group!=$row->id_pp){ ?>
                                    <tr class="pudding">
                                        <td colspan="11">Nomor PP : <b><?= $row->i_pp;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp;?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name;?> )</td>
                                    </tr>
                                    <?php $no = 1; }
                                }
                                $group = $row->id_pp                            
                            
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?= $i;?> 
                                </td>     
                                <td>
                                    <?php echo $row->i_material; ?>                             
                                </td> 
                                <td>  
                                    <?php echo $row->e_material_name; ?>
                                </td>  
                                <td>
                                    <?php echo $row->i_material_supplier; ?>                             
                                </td> 
                                <td class="text-right">  
                                    <?php echo $row->n_quantity; ?>                     
                                </td> 
                                <td> 
                                    <?php echo $row->e_satuan_name; ?> 
                                </td> 
                                <td class="text-right">  
                                    <?php echo number_format($row->v_price_default,4); ?>
                                </td>
                                <td class="text-right">  
                                    <?php echo number_format($row->v_price,4); ?>
                                </td>
                                <td class="text-right">  
                                    <?php echo number_format($row->v_price_ppn,4); ?>
                                </td>
                                <td class="text-right">  
                                    <?php echo number_format($total,4); ?>  
                                </td>
                                <td>  
                                    <?php echo $row->remark; ?>                           
                                </td> 
                            </tr>
                        <?  } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="white-box" id="detail2">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Total Barang *khusus multi pp</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" style="white-space: nowrap" cellpadding="9" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th class="text-right">Qty</th>  
                        <th class="text-right">Min Order</th>  
                        <th>Satuan</th>
                        <th class="text-right">Harga Lama</th>
                        <th class="text-right">Harga Baru Exc</th>
                        <th class="text-right">Harga Baru Inc</th>
                        <th class="text-right">Total</th>                         
                    </tr>
                </thead>
                <tbody>  
                    <?php            
                    $urut = 0;
                    $total_semua = 0;
                    if($datatotal){
                        foreach($datatotal as $row){
                            $urut++;
                            $total = $row->n_sisa* $row->v_price_ppn;
                            $total_semua += $total;
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?= $urut;?> 
                                </td>     
                                <td>
                                    <?php echo $row->i_material; ?>                             
                                </td> 
                                <td>  
                                    <?php echo $row->e_material_name; ?>
                                </td> 
                                <td class="text-right">  
                                    <span><?php echo $row->n_sisa; ?></span>                 
                                </td> 

                                <td class="text-right">  
                                    <span><?php echo $row->n_min_order; ?></span>                 
                                </td> 

                                <td> 
                                    <?php echo $row->e_satuan_name; ?> 
                                </td> 
                                <td class="text-right">  
                                    <?php echo number_format($row->v_price_default,4); ?>
                                </td>
                                <td class="text-right">  
                                    <?php echo number_format($row->v_price,4); ?>
                                </td>
                                <td class="text-right">  
                                    <?php echo number_format($row->v_price_ppn,4); ?>
                                </td>
                                <td class="text-right">  
                                    <?php echo number_format($total,4); ?>  
                                </td>
                            </tr>
                        <?php  } ?>
                        <tr>
                            <td colspan="9"></td>
                            <td colspan="1" class="text-right"> <?php echo number_format($total_semua,4); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
        fixedtable($('.table'));
    });
</script>