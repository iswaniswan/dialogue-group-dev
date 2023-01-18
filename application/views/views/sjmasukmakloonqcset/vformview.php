<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main');"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-2">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Surat Jalan Dari Supplier</label>
                        <label class="col-md-2">Tanggal Supplier</label>
                        <div class="col-sm-2">
                           <input type="text" readonly="" class="form-control input-sm" name="ibagian" id="ibagian" value="<?= $data->e_bagian_name;?>">
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="isjold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= $data->d_document;?>" readonly>
                        </div>
                         <div class="col-sm-3">
                            <input type="text" id="idocumentsup" name="idocumentsup" class="form-control input-sm" required="" value="<?= $data->i_document_supplier;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsupplier" name="dsupplier" class="form-control input-sm date" required="" value="<?= $data->d_supplier;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Partner</label>
                        <label class="col-sm-2">Tipe Makloon</label>
                        <label class="col-md-7">Keterangan</label>
                        <div class="col-sm-3">
                             <input type="text" readonly="" class="form-control input-sm" name="ipartner" id="ipartner" value="<?= $data->e_supplier_name;?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" readonly="" class="form-control input-sm" name="itype" id="itype" value="<?= $data->e_type_makloon_name;?>">
                            <input type="hidden" name="itypeold" id="itypeold" value="<?= $data->id_type_makloon;?>">
                        </div>
                        <div class="col-sm-7">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250" readonly=""><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
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
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">No Dokumen Keluar</th>
                        <th class="text-center" style="width: 35%;">Nama Panel</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty Kirim</th>
                        <th class="text-center" style="width: 10%;">Qty Terima</th>
                        <th class="text-center" style="width: 15%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {
                        $i++;
                        ?>
                    <tr>
                     <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                     <td style="text-align: center">
                        <?=  $key->i_document ;?>                        
                    </td>
                    <td class="d-flex justify-content-between">
                        <span>
                            <?= $key->i_panel ;?>
                        </span>
                        <span>
                            <?= $key->e_marker_name ;?>
                        </span>
                    </td>
                    <td>
                       <?= $key->e_color_name ;?>
                    </td>
                    <td>
                        <?= $key->keluarfull ;?>                        
                    </td>
                    <td>
                        <?= $key->masuk ;?>                    
                    </td>
                    <td>
                        <?= $key->e_remark ;?>                            
                    </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script type="text/javascript">
    
    function konfirm() {
        var jml = $('#jml').val();
        var qty = 0;
        var sisa = 0;
        for(i=1;i<=jml;i++){

            qty = qty+parseFloat($('#nquantity'+i).val());
            sisa = sisa+parseFloat($('#sisa'+i).val());
            
            
        }
        if(qty > sisa){
            swal('Jumlah Terima Melebihi Jumlah Keluar');
            return false;
        }else{
            //statuschange('<?= $folder."','".$data->id;?>','6','<?= $dfrom."','".$dto;?>');
            return true;
        } 
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#cr").attr("disabled", true);
        $("#rj").attr("disabled", true);
    });
</script>