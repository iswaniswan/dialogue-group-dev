<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>                
                    <div class="col-md-12">
                        <div class="form-group row">
                        <label class="col-md-2">No NIK</label>
                        <label class="col-md-5">Nama</label>
                        <label class="col-md-5">No KTP</label>
                            <div class="col-sm-2">
                                <input type="text" name="enik" id="enik" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="12" value = "" placeholder="Isi No NIK">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="enamakaryawan" id="enamakaryawan" class="form-control" onkeyup="gede(this)" value="" required="" placeholder="Isi Nama Lengkap Karyawan">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="ektp" id="ektp" class="form-control" required="" onkeypress = "return hanyaAngka(event)" maxlength="16" value="" placeholder="Isi No KTP">
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-md-3">No Telp</label>
                            <label class="col-md-3">Kota</label>
                            <label class="col-md-6">Alamat</label>
                            <div class="col-sm-3">
                                <input type="text" name="etelp" id="etelp" class="form-control" required="" maxlength="12" value="" placeholder="Isi Nomor Telephone" >
                            </div>
                            <div class="col-sm-3">
                                <input type="text" id="ekota" name="ekota" class="form-control" onkeyup="gede(this)" value="" placeholder="Isi Kota Domisili">
                            </div>
                            <div class="col-sm-6">
                                <textarea type="text" name="ealamat" id="ealamat" class="form-control" onkeyup="gede(this)" required="" placeholder="Isi alamat lengkap!"></textarea>
                            </div>
                        </div>  
                        <div class="form-group row">
                            <label class="col-md-4">Perusahaan</label>
                            <label class="col-md-3">Departemen</label>
                            <label class="col-md-3">Level Jabatan</label>
                            <div class="col-sm-4">
                                <?php
                                    if ($company) {
                                        foreach ($company->result() as $row) {?>
                                            <input type="hidden" id="company" name="company" class="form-control" value="<?=$row->id;?>" readonly>
                                            <input type="text" id="ecompany" name="ecompany" class="form-control" value="<?=$row->name;?>" readonly>
                                <?php 
                                        } 
                                    }
                                ?>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control select2" name ="departement" id = "departement" required="">
                                <option value = "">---</option>
                                <?php
                                    if ($dept) {
                                        foreach ($dept->result() as $riw) {?>
                                            <option value="<?php echo $riw->i_departement; ?>"><?php echo $riw->e_departement_name; ?></option>
                                <?php 
                                        } 
                                    }
                                ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control select2" name ="elevel" id = "elevel" required="">
                                <option value = "">---</option>
                                <?php
                                    if ($level) {
                                        foreach ($level->result() as $riw) {?>
                                            <option value="<?php echo $riw->i_level; ?>"><?php echo $riw->e_level_name; ?></option>
                                <?php 
                                        } 
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-5">
                                <button type="submit" id = "submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
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
 });

 $(document).ready(function () {
    $(".select2").select2();
    
    $('.select2').select2({
        placeholder: 'Cari Kode / Nama'
    });
 });

 function checklength(el){
    if(el.value.length != 7){
        swal("Kode Harus 7 Karakter");
    }
 }

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