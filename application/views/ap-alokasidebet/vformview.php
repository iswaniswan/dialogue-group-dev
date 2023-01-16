<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-view"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Jenis Debet</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="ibagian" id="ibagian" disabled="true">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                                <input type="text" id="ikasbankkeluarap" name="ikasbankkeluarap" class="form-control" value="<?=$data->i_document;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dkasbankkeluarap" id="dkasbankkeluarap" readonly value="<?= $data->d_document; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="ijenisdebet" id="ijenisdebet" disabled="true">
                                <option value="<?=$data->i_jenis_debet;?>"><?=$data->e_jenis_debet_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-5">No Debet</label>
                        <label class="col-md-3">Tanggal Debet</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="isupplier" id="isupplier" onchange="getref(this.value);" disabled="true">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="idebet" id="idebet" class="form-control select2" onchange="getitem(this.value);" disabled="true">
                                <option value="<?=$data->id_document_debet;?>"><?=$data->i_document_debet;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="ddebet" id="ddebet" placeholder="Tanggal Referensi" value="<?=$data->d_document_debet;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Jenis Faktur</label>
                        <label class="col-md-5">No Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ijenisfaktur" id="ijenisfaktur" class="form-control select2" onchange="getbank(this.value);" disabled="true">
                                <option value="<?=$data->i_jenis_faktur;?>"><?=$data->e_jenis_faktur_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" onchange="getitem(this.value);" disabled="true">
                                <option value="<?=$data->id_document_reff;?>"><?=$data->i_document_reff;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dreferensi" id="dreferensi" value="<?=$data->d_document_reff;?>" placeholder="Tanggal Referensi" readonly>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-4">Sisa Hutang</label>
                        <label class="col-md-4">Jumlah Debet</label>
                        <label class="col-md-4">Sisa Debet</label>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vsisa" id="vsisa" class="form-control" placeholder="Nominal Sisa Hutang" value="<?= $data->v_sisa;?>" readonly>
                            <input type="hidden" name="vsisaold" id="vsisaold" class="form-control" placeholder="Nominal Sisa Hutang" value="<?=$data->v_sisa;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vbayar" id="vbayar" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?= $data->v_total_debet;?>" readonly>
                            <input type="hidden" name="vbayarold" id="vbayarold" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?=$data->v_bayar;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vsisadebet" id="vsisadebet" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?= $data->v_sisa;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" name="eremark" id="eremark" class="form-control" value="" placeholder="Isi Keterangan Jika Ada!" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?=$dfrom;?>/<?=$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
                        <th style="text-align:center;width:5%">No</th>
                        <th style="text-align:center;width:15%">Kode Nota</th>
                        <th style="text-align:center;width:15%">Tanggal Nota</th>
                        <th style="text-align:center;width:15%">Nilai Nota</th>
                        <th style="text-align:center;width:15%">Jumlah Bayar</th>
                        <th style="text-align:center;width:35%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php            
                    if($detail){
                        $i = 0;
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td style="text-align: center;">
                                    <?= $i;?> 
                                </td>     
                                <td>
                                    <input type="text" name="inota<?=$i;?>" id="inota<?=$i;?>" class="form-control" readonly value="<?php echo $row->i_nota; ?>">                             
                                </td> 
                                <td>
                                    <input type="text" style="text-align: center;" name="dnota<?=$i;?>" id="dnota<?=$i;?>" class="form-control" readonly value="<?php echo $row->d_nota; ?>">
                                </td> 
                                <td>  
                                    <input type="text" style="text-align: right;" name="vnota<?=$i;?>" id="vnota<?=$i;?>" class="form-control" readonly value="<?php echo $row->v_nota; ?>">                     
                                </td> 
                                <td> 
                                    <input type="text" style="text-align: right;" name="vnotabayar<?=$i;?>" id="vnotabayar<?=$i;?>" class="form-control" readonly value="<?php echo $row->v_nota_bayar; ?>">
                                </td> 
                                <td>  
                                    <input type="text" name="edesc<?=$i;?>" id="edesc<?=$i;?>" class="form-control" readonly value="<?php echo $row->e_remark; ?>">
                                </td>
                            </tr>
                         <?   } ?>       
                         <input type="hidden" name="jml" id="jml" value="<?=$i;?>">                    
                 <?}?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
$(document).ready(function () {
    $('.select2').select2();
    getsisanota();
});

function getsisanota(){
    var vtotnota  = 0;
    var vtotbayar = 0;
    var sisahutang = 0;

    for(var i=1; i<=$('#jml').val(); i++){
        vnota      = formatulang($('#vnota'+i).val());
        vnotabayar = formatulang($('#vnotabayar'+i).val());
        vtotnota += parseFloat(vnota);
        vtotbayar += parseFloat(vnotabayar); 

        sisahutang = vtotnota - vtotbayar;
        $('#vsisa').val(sisahutang);
    }
}
</script>