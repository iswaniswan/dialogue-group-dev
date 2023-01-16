<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>        
        <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>                
                   <div class="form-group row">
                        <label class="col-md-4">Kode Sub Departemen</label>
                        <label class="col-md-8">Nama Sub Departemen</label>
                        <div class="col-sm-4">
                            <input type="text" name="isubbagian" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_sub_bagian; ?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="enama" class="form-control" value="<?= $data->e_sub_bagian; ?>" readonly>
                        </div>
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-6">Departemen</label>
                        <div class="col-sm-6">
                          <select name="idepartemen" class="form-control select2" readonly>
                            <option value="">Pilih Gudang</option>
                            <?php foreach ($dept as $idepartemen):?>
                                <option value="<?php echo $idepartemen->i_kode;?>"
                                    <?php if($idepartemen->i_kode==$data->i_kode) { ?> selected="selected" <?php } ?>>
                                    <?php echo $idepartemen->e_nama;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>                                 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });
</script>