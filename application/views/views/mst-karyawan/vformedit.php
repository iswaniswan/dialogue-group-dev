<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                
                <div class="col-md-12">
                        <div class="form-group row">
                        <label class="col-md-2">No NIK</label>
                        <label class="col-md-5">Nama</label>
                        <label class="col-md-5">No KTP</label>
                            <div class="col-sm-2">
                                <input type="hidden" name="id" id="id" class="form-control" required="" value = "<?= $isi->id;?>" >
                                <input type="text" name="enik" id="enik" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="12" value = "<?= $isi->e_nik;?>" >
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="enamakaryawan" id="enamakaryawan" class="form-control" maxlength="40" onkeyup="gede(this)" value="<?= $isi->e_nama_karyawan;?>" required="" >
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="ektp" id="ektp" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="16" value="<?= $isi->e_no_ktp;?>" >
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-md-3">No Telp</label>
                            <label class="col-md-3">Kota</label>
                            <label class="col-md-6">Alamat</label>
                            <div class="col-sm-3">
                                <input type="text" name="etelp" id="etelp" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="12" value="<?= $isi->e_telpon;?>" >
                            </div>
                            <div class="col-sm-3">
                                <input type="text" name="ekota" id="ekota" class="form-control" required="" value="<?= $isi->e_kota;?>" >
                            </div>
                            <div class="col-sm-6">
                                <textarea type="text" name="ealamat" id="ealamat" class="form-control" maxlength="100" onkeyup="gede(this)" required="" ><?= $isi->e_alamat;?></textarea>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-4">Perusahaan</label>
                            <label class="col-md-3">Departemen</label>
                            <label class="col-md-3">Level Jabatan</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" name ="ecompany" id = "ecompany" required="" disabled="true">
                                <?php
                                    if ($company) {
                                        foreach ($company->result() as $row) {?>
                                            <option value="<?php echo $row->id; ?>" <?php if($isi->id_company == $row->id){ echo 'selected'; }?>><?php echo $row->name; ?></option>
                                <?php 
                                        } 
                                    }
                                ?>
                                </select>
                                <input type="hidden" name="company" id="company" class="form-control" required="" value="<?= $isi->id_company;?>" >
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control select2" name ="departement" id = "departement" required="">
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
                                <select class="form-control select2" name ="level" id = "level" required="">
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
                                <button type="submit" id = "submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
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
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#textarea").attr("disabled", true);
    });

    $(document).ready(function () {
        $(".select2").select2();

        $('.select2').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama'
        });
    });

    function validasi(){
        var ektp            = $('#ektp').val();
        var enik            = $('#enik').val();
        var ealamat         = $('#ealamat').val();
        var enamakaryawan   = $('#enamakaryawan').val();
        var etelp           = $('#etelp').val();
        var company         = $('#company').val();

        if (ektp == '' || ektp == null) {
            swal('No KTP Belum diisi');
            return false;
        }else  if (enamakaryawan == '' || enamakaryawan == null) {
             swal('Nama Karyawan Belum diisi');
            return false;
        }else  if (enik == '' || enik == null) {
             swal('No NIK Belum diisi');
            return false;
        }else  if (company == '' || company == null) {
             swal('Perusahaan Belum dipilih');
            return false;
        }else  if (etelp == '') {
             swal('No Tlp Belum diisi');
            return false;
        }else  if (ealamat == '' || ealamat == null) {
             swal('Alamat Belum diisi');
            return false;
        }else{
            return true;
        }
    }
</script>