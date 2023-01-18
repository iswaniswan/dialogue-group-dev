<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Nomor Promo</label>
                            <label class="col-md-5">Nama Promo</label>
                            <label class="col-md-4">Jenis Promo</label>                            
                            <div class="col-sm-3">
                                <input type="hidden" name="id" id="id" class="form-control" value="<?= $data->id; ?>">
                                <input type="text" name="ipromo" id="ipromo" autocomplete="off" class="form-control"required="" maxlength="15" value="<?= $data->i_promo; ?>" readonly>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="epromo" id="epromo" onkeyup="gede(this); clearname(this);" class="form-control" value="<?= $data->e_promo; ?>" maxlength="300" readonly>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="ejenis" id="ejenis" onkeyup="gede(this); clearname(this);" class="form-control" value="<?= $data->e_jenispromo; ?>" maxlength="100" readonly>
                            </div>
                        </div>       
                        <div class="form-group row">
                            <label class="col-md-3">Jumlah Promo (%)</label>
                            <label class="col-md-9">Periode Berlaku</label>
                            <div class="col-sm-3">
                                <input type="text" name="njumlah" id="njumlah" class="form-control" value="<?= $data->n_jumlahpromo; ?>" readonly>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="dperiode" id="dperiode" class="form-control" value="<?= date("d-m-Y",strtotime($data->d_berlaku))?>" readonly>
                            </div>
                        </div>  
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
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
        <div class="m-b-0">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Warna</th>
                        <th class="text-center">Diskon (%)</th>
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
                        <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                        <td>  
                            <input style="width:120px" type="hidden" class="form-control" id="idproduct<?=$i;?>" name="idproduct[]" value="<?= $row->id_product; ?>" readonly>
                            <input style="width:200px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_product_base; ?>" readonly>
                        </td>
                        </td>
                        <td>
                            <input style="width:400px" type="text" class="form-control" id="eproduct<?=$i;?>" name="eproduct[]"value="<?= $row->e_product_basename; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:120px" type="hidden" class="form-control" id="idcolor<?=$i;?>" name="idcolor[]" value="<?= $row->id_color; ?>" readonly>
                            <input style="width:200px" type="text" class="form-control" id="ecolor<?=$i;?>" name="ecolor[]" value="<?= $row->e_color_name; ?>" readonly>
                        </td>
                        <td>
                            <input style="width:150px" type="text" class="form-control" id="ndiskon<?=$i;?>" name="ndiskon[]" value="<?= $row->n_diskon; ?>" readonly> 
                        </td>                    
                    </tr>                       
                    <?}
                }?>        
                <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                </tbody>         
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        showCalendar('.date');
    });
</script>