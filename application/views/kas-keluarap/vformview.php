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
                        <label class="col-md-3">Jenis Faktur</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="ibagian" id="ibagian" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                                <input type="text" id="ikasbankkeluarap" name="ikasbankkeluarap" class="form-control" value="<?=$data->i_kasbank_keluarap;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="dkasbankkeluarap" id="dkasbankkeluarap" readonly value="<?= $data->d_kasbank_keluarap; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="ijenis" id="ijenis">
                                <option value="<?=$data->i_jenis_faktur;?>"><?=$data->e_jenis_faktur_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-5">No Referensi</label>
                        <label class="col-md-3">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" name="isupplier" id="isupplier" onchange="getref(this.value);" disabled="true">
                                <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="irefferensi" id="irefferensi" class="form-control select2" onchange="getitem(this.value);" disabled="true">
                                <option value="<?=$data->i_referensi;?>"><?=$data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" name="dreferensi" id="dreferensi" placeholder="Tanggal Referensi" value="<?=$data->d_ppap;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Kas/Bank</label>
                        <label class="col-md-8">Bank</label>
                        <div class="col-sm-4">
                            <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="getbank(this.value);" disabled="true">
                                <option value="<?=$data->i_kode_kas;?>"><?=$data->e_kas_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="ibank" id="ibank" class="form-control" value="<?=$data->i_bank;?>" placeholder="Nama Bank" readonly>
                            <input type="text" name="ebank" id="ebank" class="form-control" value="<?=$data->e_bank_name;?>" placeholder="Nama Bank" readonly>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-4">Sisa Hutang</label>
                        <label class="col-md-8">Jumlah Bayar</label>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vsisa" id="vsisa" class="form-control" placeholder="Nominal Sisa Hutang" value="<?= 'Rp. '.number_format($data->v_sisa, 2);?>" readonly>
                            <input type="hidden" name="vsisaold" id="vsisaold" class="form-control" placeholder="Nominal Sisa Hutang" value="<?=$data->v_sisa;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" style="text-align:right;" name="vbayar" id="vbayar" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?= 'Rp. '.number_format($data->v_bayar, 2);?>" readonly>
                            <input type="hidden" name="vbayarold" id="vbayarold" class="form-control" placeholder="Nominal Jumlah Bayar" value="<?=$data->v_bayar;?>" readonly>
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
                                <?php echo $row->i_nota; ?>                             
                                </td> 
                                <td style="text-align: center;">
                                    <?php echo $row->d_nota; ?>
                                </td> 
                                <td  style="text-align: right;">  
                                    <?php echo 'Rp. '. number_format($row->v_nota,2); ?>                     
                                </td> 
                                <td  style="text-align: right;"> 
                                    <?php echo 'Rp. '.number_format($row->v_nota_bayar,2); ?> 
                                    <input type="hidden" class="form-control" id="vbayarnota<?=$i;?>" name="vbayarnota<?=$i;?>"value="<?= number_format($row->v_nota_bayar); ?>">
                                </td> 
                                <td>  
                                    <?php echo $row->e_remark; ?>
                                </td>
                            </tr>
                         <?   } ?>                           
                 <?}?>
                 <input type="hidden" name="jml" id="jml" value="<?=$i;?>"> 
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        sisahutang();
    });

    function sisahutang(){
        var vbayar  = formatulang($('#vbayarold').val());
        var jml     = $('#jml').val();

        for(var i=1; i<=jml ;i++){
            var vbayarnota    = formatulang($('#vbayarnota'+i).val());
            vsisa = parseFloat(vbayarnota) - parseFloat(vbayar);
            //alert(vbayarnota);
            $('#vsisa').val(formatRupiah(vsisa));
        }
    }
</script>