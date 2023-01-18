<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                  <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">Bagian</label>
                        <label class="col-md-7">Nomor SJ Masuk</label>                        
                        <div class="col-sm-5">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <?php foreach($kodemaster as $ibagian): ?>
                                <option value="<?php echo $ibagian->i_departement;?>" 
                                <?php if($ibagian->i_departement==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                <?php echo $ibagian->e_departement_name;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                        <div class="col-sm-6">
                         <input type="text" id="isjkm" name="isjkm" class="form-control" value="<?=$data->i_sj;?>" readonly>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                    <label class="col-md-12">Nomor Referensi</label>
                        <div class="col-sm-11">
                            <?php if ($ireferensi) {
                                $ireff = '';
                                foreach ($ireferensi as $kuy) {
                                    $ireff = $ireff."".$kuy->i_sj_reff." - ";
                                }
                            }?>
                            <textarea readonly=""  class="form-control text-left" required><?php if($ireff!=''){ echo substr($ireff, 0, -2);} ?></textarea>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?= $data->e_remark;?>" readonly>
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal SJ Masuk</label>
                        <label class="col-md-8">Supplier</label>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?=$data->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="hidden" id="esupplier" name="esupplier" class="form-control" value="<?=$data->i_supplier;?>" readonly>
                            <input type="text" id="supplier" name="supplier" class="form-control" readonly value="<?=$data->e_supplier_name;?>">
                            <input type="hidden" id="inodoksup" name="inodoksup" class="form-control" value="<?= $data->e_no_dok_supplier;?>">
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Type Makloon</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="itypemakloon" name="itypemakloon" class="form-control" readonly value="<?=$data->i_type_makloon;?>">
                            <input type="text" id="etypemakloon" name="etypemakloon" class="form-control" readonly value="<?=$data->e_type_makloon;?>"> 
                        </div>
                    </div>                     
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered"  cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="8">No</th>
                                    <th width="10%">Nomor Referensi</th>
                                    <th width="30%">Kode Barang ( Keluar )</th>
                                    <th width="5%">Satuan</th>
                                    <th width="5%">Qty</th>
                                    <th width="30%">Kode Barang ( Masuk )</th>
                                    <th width="5%">Satuan</th>
                                    <th width="5%">Qty</th>
                                    <th width="15%">Keterangan</th>.
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter = 0; 
                                if ($detail){
                                    foreach ($detail as $row) {
                                        $counter++;?>
                                        <tr>
                                            <td>
                                                <?= $counter;?>
                                            <td>
                                                <?= $row->i_reff;?>
                                            </td>
                                            <td>
                                                <?= $row->nama_material_keluar;?>
                                            </td>
                                            <td>
                                                <?= $row->nama_satuan_keluar;?>
                                            </td>
                                            <td>
                                                <?= $row->qty_keluar;?>
                                            </td>
                                            <td>
                                                <?= $row->nama_material_masuk;?>
                                            </td>
                                            <td>
                                                <?= $row->nama_satuan_masuk;?>
                                            </td>
                                            <td>
                                                <?= $row->qty_masuk;?>
                                            </td>
                                            <td>
                                                <?= $row->e_remark;?>
                                            </td>
                                        </td>
                                    </tr>
                                <?php }  
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
     $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });
</script>