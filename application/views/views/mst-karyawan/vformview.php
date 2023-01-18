<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">                
                <div class="col-md-12">
                        <div class="form-group row">
                        <label class="col-md-2">No NIK</label>
                        <label class="col-md-5">Nama</label>
                        <label class="col-md-5">No KTP</label>
                            <div class="col-sm-2">
                                <input type="hidden" name="id" id="id" class="form-control" required="" value = "<?= $isi->id;?>" >
                                <input type="text" name="enik" id="enik" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="12" value = "<?= $isi->e_nik;?>" readonly>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="enamakaryawan" id="enamakaryawan" class="form-control" maxlength="40" onkeyup="gede(this)" value="<?= $isi->e_nama_karyawan;?>" required="" readonly>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="ektp" id="ektp" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="16" value="<?= $isi->e_no_ktp;?>" readonly>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-md-3">No Telp</label>
                            <label class="col-md-3">Kota</label>
                            <label class="col-md-6">Alamat</label>
                            <div class="col-sm-3">
                                <input type="text" name="etelp" id="etelp" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="12" value="<?= $isi->e_telpon;?>" readonly>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ekota" id="ekota" class="form-control" required="" value="<?= $isi->e_kota;?>" readonly>
                            </div>
                            <div class="col-sm-6">
                                <textarea type="text" name="ealamat" id="ealamat" class="form-control" maxlength="100" onkeyup="gede(this)" required="" readonly><?= $isi->e_alamat;?></textarea>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-4">Perusahaan</label>
                            <label class="col-md-3">Departemen</label>
                            <label class="col-md-3">Level Jabatan</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" name ="company" id = "company" required="" disabled="true">
                                <option value = "">---</option>
                                <?php
                                    if ($company) {
                                        foreach ($company->result() as $row) {?>
                                            <option value="<?php echo $row->id; ?>" <?php if($isi->id_company == $row->id){ echo 'selected'; }?>><?php echo $row->name; ?></option>
                                <?php 
                                        } 
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control select2" name ="departement" id = "departement" required="" disabled="true">
                                <option value = "">---</option>
                                <?php
                                    if ($dept) {
                                        foreach ($dept->result() as $riw) {?>
                                            <option value="<?php echo $riw->i_departement; ?>" <?php if($isi->i_departement == $riw->i_departement){ echo 'selected'; }?>><?php echo $riw->e_departement_name; ?></option>
                                <?php 
                                        } 
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control select2" name ="level" id = "level" required="" disabled="true">
                                <option value = "<?=$isi->i_level;?>"><?=$isi->e_level_name;?></option>
                                <?php
                                    if ($level) {
                                        foreach ($level->result() as $riw) {?>
                                            <option value="<?php echo $riw->i_level; ?>" <?php if($isi->i_level == $riw->i_level){ echo 'selected'; }?>><?php echo $riw->e_level_name; ?></option>
                                <?php 
                                        } 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-5">
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
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