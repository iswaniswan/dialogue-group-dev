<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$isupplier;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                
                <div id="pesan"></div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-8">No Pajak</label><label class="col-md-4">Tanggal Pajak</label>
                        <div class="col-sm-8">
                            <input readonly type="text" name="ipajak" id="ipajak" class="form-control" value="<?=$head->i_pajak?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dpajak" name="dpajak" class="form-control" value="<?= date('d-m-Y', strtotime($head->d_pajak));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">No TTB</label><label class="col-md-8">NPWP</label>
                        <div class="col-sm-4">
                            <input readonly type="text" name="ibtb" id="ibtb" class="form-control" value="<?=$head->i_btb?>">
                        </div>
                        <div class="col-sm-8">
                            <input readonly type="text" name="isuppliernpwp" id="isuppliernpwp" class="form-control" value="<?=$head->i_supplier_npwp?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-8">No Faktur</label><label class="col-md-4">Tanggal Faktur</label>
                        <div class="col-sm-8">
                            <input type="text" id= "ifaktur" name="ifaktur" class="form-control" readonly value="<?=$head->i_nota;?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id= "dfaktur" name="dfaktur" class="form-control date" readonly value="<?= date('d-m-Y', strtotime($head->d_nota));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nama</label><label class="col-md-6">Alamat</label>
                        <div class="col-sm-6">
                            <input type="text" id= "esuppliername" name="esuppliername" class="form-control" readonly value="<?=$head->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "esupplieraddress" name="esupplieraddress" class="form-control" readonly value="<?=$head->e_supplier_address;?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-8">No SJ</label><label class="col-md-4">Tanggal SJ</label>
                        <div class="col-sm-8">
                            <input type="text" id= "isj" name="isj" class="form-control" readonly value="<?=$head->i_sj;?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly type="text" id= "dsj" name="dsj" class="form-control date" readonly value="<?= date('d-m-Y', strtotime($head->d_sj));?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Total Diskon</label><label class="col-md-3">Total PPNBM</label><label class="col-md-3">Total PPN</label><label class="col-md-3">Total DPP</label>
                        <div class="col-sm-3">
                            <input readonly type="text" name="vtotaldisc" id="vtotaldisc" class="form-control" value="<?= number_format($head->v_total_diskon);?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly type="text" name="totppnbm" id="totppnbm" class="form-control" value="<?= number_format($head->ppnbm);?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "totppn" name="totppn" class="form-control" readonly value="<?= number_format($head->ppn);?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "totdpp" name="totdpp" class="form-control" readonly value="<?= number_format($head->dpp);?>">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 12%;">Kode Barang</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>  
                                <th style="text-align: center; width: 10%;">Jumlah</th>  
                                <th style="text-align: center; width: 10%;">Harga</th>
                                <th style="text-align: center; width: 10%;">DPP</th>  
                                <th style="text-align: center; width: 10%;">PPN</th>  
                            </tr>
                        </thead>
                        <tbody>
                        <?php               
                        $i=0;
                        if($detail!=''){
                            foreach($detail as $row){ 
                                $i++;
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                </td>
                                <td>
                                    <input readonly class="form-control" id="imaterial<?= $i;?>" name="imaterial<?= $i;?>" value="<?= $row->i_material;?>">
                                </td>
                                <td>
                                    <input class="form-control" readonly id="ematerialname<?= $i;?>" name="ematerialname<?= $i;?>" value="<?= $row->e_material_name;?>">
                                </td>
                                <td>
                                    <input style="text-align: right;" class="form-control" readonly id="nquantity<?= $i;?>" name="nquantity<?= $i;?>" value="<?= $row->n_quantity;?>">
                                </td>
                                <td>
                                    <input style="text-align: right;" class="form-control" readonly id="vprice<?= $i;?>" name="vprice<?= $i;?>" value="<?= number_format($row->v_price);?>">
                                </td>
                                <td>
                                    <input style="text-align: right;" class="form-control" readonly type="text" id="vdpp<?= $i;?>" name="vdpp<?= $i;?>" value="<?= number_format($row->v_dpp);?>">
                                </td>
                                <td>
                                    <input style="text-align: right;" class="form-control"  readonly id="vppn<?= $i;?>" name="vppn<?= $i;?>" value="<?= number_format($row->v_ppn);?>">
                                </td>
                            </tr>
                            <?php 
                            }   
                        }?>
                            <input style ="width:50px"type="hidden" name="jml" id="jml" value="">
                        </tbody>
                    </table>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    $('.select2').select2();
});
</script>