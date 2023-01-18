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
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Kode Supplier</label>
                        <label class="col-md-4">Nama Supplier</label>
                        <label class="col-md-4">Alamat</label>
                        <label class="col-md-2">Kota</label>
                        <div class="col-sm-2">
                            <input type="hidden" name="id" id="id" class="form-control input-sm " value="<?= $data->id?>" >
                            <input type="text" name="isupplier" id="isupplier" class="form-control input-sm " autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="<?= $data->i_supplier?>">
                            <input type="hidden" name="isupplierold" id="isupplierold" class="form-control input-sm " autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="<?= $data->i_supplier?>">
                            <span id="cek" hidden="true"> 
                                    <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>                        
                        <div class="col-sm-4">
                            <input type="text" name="isuppliername" class="form-control input-sm " value="<?= $data->e_supplier_name?>" onkeyup="gede(this);clearname(this);">
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="isupplieraddres" class="form-control input-sm " required onkeyup="gede(this)"><?= $data->e_supplier_address; ?></textarea>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isuppliercity" class="form-control input-sm " required value="<?= $data->e_supplier_city?>" onkeyup="gede(this)">
                        </div>
                    </div>
                    <div class="form-group row">       
                        <label class="col-md-1">Kode Pos</label>                 
                        <label class="col-md-2">Telepon</label>
                        <label class="col-md-2">FAX</label>     
                        <label class="col-md-1"></label>                  
                        <label class="col-md-3">Nama Pemilik </label>
                        <label class="col-md-1">Diskon %</label>
                        <label class="col-md-2">TOP</label>
                        <div class="col-sm-1">
                            <input type="text" name="isupplierpostalcode" id="isupplierpostalcode" class="form-control input-sm "  maxlength="5" onkeypress="return angka(event)" value="<?= $data->e_supplier_postalcode; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isupplierphone" id="isupplierphone" class="form-control input-sm " required value="<?= $data->e_supplier_phone; ?>" >
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isupplierfax" class="form-control input-sm " value="<?= $data->e_supplier_fax; ?>" >
                        </div> <div class="col-sm-1"></div>
                        <div class="col-sm-3">
                            <input type="text" name="esupplierownername" class="form-control input-sm " required value="<?= $data->e_supplier_ownername; ?>" onkeyup="gede(this)" >
                        </div> 
                        <div class="col-sm-1">
                            <input type="text" name="isupplierdiskon" id="isupplierdiskon" class="form-control input-sm " onkeypress="return angkahungkul(this);" value="<?= $data->n_diskon; ?>">
                        </div>
                        <div class="col-sm-1"> 
                            <input type="text" name="isuppliertoplength" id="isuppliertoplength" class="form-control input-sm " onkeypress="return hanyaAngka(this);" required="" maxlength="3" value="<?= $data->n_supplier_toplength; ?>">
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-1">PKP</label>   
                        <label class="col-md-2">NPWP</label>
                        <label class="col-md-3">Nama NPWP</label>
                        <label class="col-md-6">Alamat NPWP</label> 
                        <?php 
                            $check = '';
                            if($data->f_pkp == 't') { $check = 'checked';}
                        ?>                       
                        <div class="col-sm-1">
                             <input type="checkbox" class="form-check-input"  name="isupplierpkp" id="isupplierpkp" onclick="wajibnpwp(this.value);" <?php echo $check;?>>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="isuppliernpwp" id="isuppliernpwp" class="form-control input-sm " value="<?= $data->i_supplier_npwp; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="esuppliernpwpname" id="esuppliernpwpname" class="form-control input-sm " onkeyup="gede(this)"  value="<?= $data->e_npwp_name; ?>">
                        </div>    
                        <div class="col-sm-6">
                            <textarea type="text" name="isuppliernpwpaddress" id="isuppliernpwpaddress" class="form-control input-sm " onkeyup="gede(this)"><?= $data->e_npwp_address; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-3">Nomor Rekening </label>
                        <label class="col-md-4">Nama Rekening</label>
                        <label class="col-md-2">Include/Exclude</label> 
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control input-sm  select2">
                                <option value="">Pilih Bank</option>
                                <?php foreach($bank as $row){?>
                                        <!-- <option value="<?=$row->i_bank;?>"><?=$row->e_bank_name;?></option> -->
                                        <?php if ($row->i_bank == $data->i_bank) { ?>
                                                <option value="<?= $row->i_bank ;?>" selected><?=$row->e_bank_name;?></option>
                                        <?php } else { ?>
                                                <option value="<?= $row->i_bank ;?>"><?=$row->e_bank_name;?></option>
                                        <?php } ?>
                                <?php } ?>
                            </select>
                        </div> 
                        <div class="col-sm-3">
                            <input type="text" name="inorekening" class="form-control input-sm " value="<?= $data->i_no_rekening; ?>">
                        </div>                   
                        <div class="col-sm-4">
                            <input type="text" name="enamarekening" class="form-control input-sm " value="<?= $data->e_nama_rekening; ?>" onkeyup="gede(this)" >
                        </div>
                        <div class="col-sm-2">
                            <select name="ftipepajak" id="ftipepajak" class="form-control input-sm  select2">
                                <option value="">Pilih Include/Exclude</option>
                                <?php foreach($typepajak as $row){?>
                                        <?php if ($data->i_type_pajak == $row->i_type_pajak) { ?>
                                            <option value="<?=$row->i_type_pajak;?>" selected><?=$row->e_type_pajak_name;?></option>
                                        <?php } else { ?>
                                            <option value="<?=$row->i_type_pajak;?>"><?=$row->e_type_pajak_name;?></option>
                                        <?php } ?>
                                <?php } ?>
                            </select>                          
                        </div>
                    </div>
                    <div class="form-group row">    
                        <label class="col-md-3">Kategori Partner</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-3">Level Perusahaan</label>
                        <label class="col-md-3">Kepala Partner Group</label>
                        <div class="col-sm-3">
                            <select name="isuppliergroup" id="isuppliergroup" class="form-control input-sm  select2" onchange="getjenissupplier(this.value);getjenisindustry(this.value);getpusat(this.value);">
                                <option value="">Pilih Kategori Partner</option>
                                <?php foreach ($suppliergroup as $row):?>
                                    <?php if ($row->i_supplier_group == $data->i_supplier_group) { ?>
                                            <option value="<?= $row->i_supplier_group ;?>" selected><?=$row->e_supplier_group_name;?></option>
                                    <?php } else { ?>
                                            <option value="<?= $row->i_supplier_group ;?>"><?=$row->e_supplier_group_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>                        
                        <div class="col-sm-3">
                            <select name="itypeindustry" id="itypeindustry" class="form-control input-sm  select2">
                               <option value="">Pilih Jenis</option>
                                <?php foreach ($typeindustry as $row):?>
                                    <?php if ($row->i_type_industry == $data->i_type_industry) { ?>
                                            <option value="<?= $row->i_type_industry ;?>" selected><?=$row->e_type_industry_name;?></option>
                                    <?php } else { ?>
                                            <option value="<?= $row->i_type_industry ;?>"><?=$row->e_type_industry_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ilevelcompany" id="ilevelcompany" class="form-control input-sm  select2" onchange="getpusat(this.value);">
                               <!--  <option value="">Level Perusahaan</option> -->
                                <?php foreach ($levelcompany as $row):?> 
                                     <?php if ($data->i_level == $row->i_level) { ?>
                                       <option value="<?php echo $row->i_level;?>" selected><?php echo $row->e_level_name;?></option>
                                    <?php } else { ?>
                                       <option value="<?php echo $row->i_level;?>"><?php echo $row->e_level_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <?php if($data->i_level == 'PLV00'){?>
                                <select disabled name="ikepalapusat" id="ikepalapusat" class="form-control input-sm  select2">
                                </select>
                            <?}else{?>
                                <select name="ikepalapusat" id="ikepalapusat" class="form-control input-sm  select2">
                                   <!--  <option value="<?=$data->i_kepala_pusat?>"><?=$data->i_pusat;?></option> -->
                                    <?php foreach($kepalapusat as $row): ?>
                                        <?php if ($row->i_kepala_pusat == $data->i_kepala_pusat) { ?>
                                                <option value="<?= $row->i_kepala_pusat ;?>" selected><?=$row->e_pusat;?></option>
                                        <?php } else { ?>
                                                <option value="<?= $row->i_kepala_pusat ;?>"><?=$row->e_pusat;?></option>
                                        <?php } ?>
                                    
                                    <?php endforeach; ?> 
                                </select>
                            <?}?>
                        </div>                        
                    </div>   
                    <div class="form-group row">
                        <label class="col-md-3">Jenis Makloon</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Jenis Pembelian</label>
                        <label class="col-md-3">Internal/Eksternal</label>
                        <div class="col-sm-3">
                            <select name="ijenismakloon[]" id="ijenismakloon" class="form-control input-sm  select2" multiple="multiple"> 
                                <?php if ($makloon) {
                                    foreach ($makloon as $kuy) {?>
                                        <option value="<?= $kuy->i_type_makloon;?>" selected><?= $kuy->e_type_makloon_name;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ikategoriproduk[]" id="ikategoriproduk"  multiple="multiple" class="form-control input-sm  select2">
                                <?php if ($kategori) {
                                    foreach ($kategori as $kuy) {?>
                                        <option value="<?= $kuy->i_kode_kelompok;?>" selected><?= $kuy->e_nama_kelompok;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>  
                        <div class="col-sm-3">                        
                            <select name="jenis_pembelian" id="jenis_pembelian"  class="form-control input-sm  select2">
                                <option value="credit" <?php if ($data->jenis_pembelian=='credit') { ?> selected <?php } ?>>CREDIT</option>
                                <option value="cash" <?php if ($data->jenis_pembelian=='cash') { ?> selected <?php } ?>>CASH</option>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="inter_exter" id="inter_exter"  class="form-control input-sm select2" data-placeholder="Select Internal/Eksternal">
                                <option value=""></option>
                                <?php if ($jahit->num_rows()>0) {
                                    foreach ($jahit->result() as $key) {?>
                                        <option value="<?= $key->id;?>" <?php if ($key->id==$data->id_kategori_jahit) { ?> selected <?php } ?>><?= $key->e_nama_kategori;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div>            
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>                               
                </div>  
            </div> <!-- end row -->
            <div class="row">
                <div class="col-md-6">
                     <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode terdiri dari 5 (lima) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama </span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada kode sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : SA001, SA002, dst</span>
                    </div>
                </div>
            </div>

            </div>                     
            </form>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
    $(".select2").select2();

    $('#ikategoriproduk').select2({
            placeholder: 'Pilih Kelompok Barang',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkategoriproduct2'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
    });

    $('#ijenismakloon').select2({
            placeholder: 'Pilih Makloon',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getmakloon2'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
    });
 });


 function wajibnpwp(id){
    if ($('#isupplierpkp').is(':checked')) {
        $("#isuppliernpwp").prop('required',true);
        $("#esuppliernpwpname").prop('required',true);
        $("#isuppliernpwpaddress").prop('required',true);
        swal("Kolom NPWP Dan Nama NPWP WAJIB DIISI");
    } else {
        $("#isuppliernpwp").prop('required',false);
        $("#esuppliernpwpname").prop('required',false);
        $("#isuppliernpwpaddress").prop('required',false);
    }

 }

 function getpusat(id){
    var isuppliergroup = $('#isuppliergroup').val();
    var ilevelcompany  = $('#ilevelcompany').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getpusat');?>",
        data:{
            'isuppliergroup' : isuppliergroup,
            'ilevelcompany'  : ilevelcompany
        }, 
        dataType: 'json',
        success: function (data) {
            $("#ikepalapusat").html(data.kop);
            if (data.kosong == 'kopong') {
            } else {
                $("#ikepalapusat").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });

    if(ilevelcompany == "PLV00"){
        $("#ikepalapusat").attr("disabled", true);
    }else{
        $("#ikepalapusat").attr("disabled", false);
    }
}

function getkategoriproduct(id){

    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkategoriproduct');?>",
        data:{
            'ijenismakloon' : id
        }, 
        dataType: 'json',
        success: function (data) {
            $("#ikategoriproduk").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#ikategoriproduk").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

function getjenisindustry(id){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getjenisindustry');?>",
        data:{
            'isuppliergroup' : id
        }, 
        dataType: 'json',
        success: function (data) {
            $("#itypeindustry").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
                $("#itypeindustry").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

function getkategoripembelian(){
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getkategoripembelian');?>",
        data:{
        }, 
        dataType: 'json',
        success: function (data) {
            $("#ikategoriproduk").html(data.kop);
            if (data.kosong == 'kopong') {
                $("#submit").attr("disabled", true);
            } else {
                $("#submit").attr("disabled", false);
            }
        },

        error: function (XMLHttpRequest) {
            alert(XMLHttpRequest.responseText);
        }
    });
}

$(document).ready(function () {
    var isuppliergroup    = $('#isuppliergroup').val();
    if (isuppliergroup != 'KTG02') {
         $("#ijenismakloon").attr("disabled", true);
    }
  
});

function checklength(el){
    if(el.value.length != 7){
        swal("Kode Supplier Harus 7 Karakter");
    }
}

$(document).ready(function () {  
    $('#ijenismakloon').select2({
               placeholder: 'Pilih Partner',
               allowClear: true,
               ajax: {
                   url: '<?= base_url($folder.'/cform/jenismakloon/'); ?>',
                   dataType: 'json',
                   delay: 250,
                   data: function (params) {
                       var query = {
                           q: params.term,
                       }
                       return query;
                   },
                   processResults: function (data) {
                       return {
                           results: data
                       };
                   },
                   cache: false
               }
          });
    });

    function angka(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function huruf(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode < 65 || charCode > 90)&&(charCode < 97 || charCode > 122)&&charCode>32)
            return false;
        return true;
    }

    function getjenissupplier(id) {     
        var tes = document.getElementById("isuppliergroup").value; 
        if(tes == "KTG02"){
          $('#ijenismakloon').attr("disabled", false);
          $('#add_row').attr("hidden", false);
        }else{
          $('#ijenismakloon').attr("disabled", true);
          $('#ijenismakloon').val("");
          $('#ijenismakloon').html("");
          // $("#ikategoriproduk").attr("disabled", false);
          // $('#ikategoriproduk').val("");
          // $('#ikategoriproduk').html("");
          // $('#add_row').attr("hidden", true);
          // $("#table_data").find("tr:not(:nth-child(1))").remove();
        }
    }

    function valstock(){        
       var ijenispembelian    = $('#ijenispembelian').val();
       var isuppliertoplength = $('#isuppliertoplength').val();
        if(ijenispembelian == 0){
            if(isuppliertoplength > 0){
                swal("Tidak boleh Lebih Besar atau Lebih Kecil dari 0 ( nol ) ")
                document.getElementById("isuppliertoplength").value=0;
            }
        }else if(ijenispembelian == 1){
            if(isuppliertoplength <= 0){
                swal("Tidak Boleh 0 dan Harus Lebih Besar dari 0 ");
                document.getElementById("isuppliertoplength").value=1;
            }
        }
        
}

    function validasi(){
        var isupplier   = $('isupplier').val();
        var isuppliername   = $('isuppliername').val();
        var ftipepajak      = $('#ftipepajak').val();
        var isuppliergroup  = $('#isuppliergroup').val();    
        var ikategoriproduk = $('#ikategoriproduk').val();
        var ilevelcompany   = $('#ilevelcompany').val();
        
        // if(isupplier == '' || isuppliername == '' || ftipepajak == '' || ftipepajak == null || isuppliergroup == '' || isuppliergroup == null || ikategoriproduk == '' || ikategoriproduk == null || ilevelcompany == '' || ilevelcompany == null){
        //     swal("Data Belum Lengkap");
        //     return false;
        if(isupplier == '' || isuppliername == '' || ftipepajak == '' || ftipepajak == null || ilevelcompany == '' || ilevelcompany == null || isuppliergroup == '' || isuppliergroup == null){
            swal("Kode, Nama, Include/Exlude, Kategori, Level Perusahaan,  Harus Di Isi");
            return false;
        }else{
            if($('#isupplierpkp').is(':checked')) {
                if ($('isuppliername').val() == '') {
                    swal("Nomor NPWP wajib diisi");
                    return false;
                }else  if ($('esuppliernpwpname').val() == '') {
                    swal("Nama NPWP wajib diisi");
                    return false;
                }else{
                    return true;
                }
            } else {
                return true    
            }
            
        }
    }

    $("#isupplier").keyup(function() {
        var kode = $(this).val();
        var kodeold = $("#isupplierold").val();
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
                'kodeold' : kodeold
            },
            url: '<?= base_url($folder.'/cform/cekkodeedit'); ?>',
            dataType: "json",
            success: function (data) {
                /*var x = parseInt(kode.length)-1;*/
                if (data==1) {
                    $("#cek").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                    /*$('#ibrand').val(kode.substring(0, x));*/
                }else{
                    $("#cek").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });
</script>
