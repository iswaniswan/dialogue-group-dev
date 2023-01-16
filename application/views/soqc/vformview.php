<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                   <div class="form-group row">
                        <label class="col-md-2">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-6">Keterangan</label>                            
                        <div class="col-sm-2">
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control" value="<?= $datahead->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $datahead->id;?>" readonly="">
                            <input type="text" name="idocument" id="idocument" class="form-control" value="<?= $datahead->i_document;?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" name="ddocument" id="ddocument" class="form-control" value="<?= $datahead->d_document;?>" readonly>   
                        </div>
                        <div class="col-sm-6"> 
                            <textarea name="eremarkh" id="eremarkh" class="form-control" readonly><?= $datahead->e_remark;?></textarea>   
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="37%">Nama Barang</th>
                                    <th width="10%">Skin</th>
                                    <th width="10%">Status Packing</th>
                                    <th width="10%">Jumlah SO</th>
                                    <th width="20%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    if($datadetail){
                                        foreach ($datadetail as $key) {
                                        $i++;
                                ?>
                                <tr>
                                    <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                                    <td><?= $key["i_product_base"].' - '.$key["e_product_basename"] ?></td>
                                    <td><?= $key['id_material'].' - '.$key["e_material_name"] ?></td>
                                    <td><?= $key["status_barang"];?></td>
                                    <td><?= $key["n_quantity"];?></td>
                                    <td><?= $key["e_remark"];?></td>
                                </tr>
                                <?php 
                                    }
                                } 
                                ?> 
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function approve() {
        statuschange('<?= $folder."','".$id;?>','6','<?= $dfrom."','".$dto;?>');   
    }
</script>