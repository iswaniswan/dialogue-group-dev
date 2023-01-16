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
                <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-2">Kode Customer</label>
                        <label class="col-md-4">Nama Customer</label>
                        <label class="col-md-6">Alamat</label>                       
                        <div class="col-sm-2">
                           <input type="text" name="icustomer" id="icustomer" class="form-control" autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="" placeholder="Kode Customer">
                            <span id="cek" hidden="true"> 
                                    <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div> 
                        <div class="col-sm-4">
                            <input type="text" name="ecustomername" class="form-control" required="" value="" onkeyup="gede(this);" placeholder="Nama Customer">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" onkeyup="ceklis();" name="ecustomeraddress" id="ecustomeraddress" class="form-control" placeholder="Alamat Customer"></textarea>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Alamat Kirim <input type="checkbox" class="mt-2 ml-2" onclick="ceklis();" name="sama_dengan1"> (Ceklis apabila sama dengan alamat utama)</label>                    
                        <label class="col-md-6">Alamat Penagihan <input type="checkbox" class="mt-2 ml-2" onclick="ceklis();" name="sama_dengan2"> (Ceklis apabila sama dengan alamat utama)</label></label>
                        <div class="col-sm-6">
                            <textarea type="text" onkeyup="ceklis();" name="e_shipping_address" id="e_shipping_address" class="form-control" placeholder="Alamat Kirim"></textarea>
                        </div>                        
                        <div class="col-sm-6">
                            <textarea type="text" onkeyup="ceklis();" name="e_billing_address" id="e_billing_address" class="form-control" placeholder="Alamat Penagihan"></textarea>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Kota</label>
                        <label class="col-md-1">Kode Pos</label>
                        <label class="col-md-2">Telepon</label>
                        <label class="col-md-3">FAX</label>                          
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" onchange="return getcity(this.value);">
                                <option value="">Pilih Area</option>
                                <?php foreach($area as $row){?>
                                    <option value="<?=$row->id_area;?>"><?=$row->e_area;?></option>
                                <?php } ?>
                            </select>
                        </div>                   
                        <div class="col-sm-3">
                            <select name="ecity" id="ecity" class="form-control select2" disabled="true" placholder="Kota Customer">
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="epostalcode" class="form-control" maxlength="5" onkeypress="return angka(event)" value="" placeholder="Kode Pos">
                        </div>   
                        <div class="col-sm-2">
                            <input type="text" name="ecustomerphone" class="form-control" onkeypress="return angka(event)" maxlength="15" value="" placeholder="Nomor Telephone">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ecustomerfax" class="form-control" maxlength="15" value="" placeholder="FAX">
                        </div>
                       
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-3">Contact Person </label>  
                        <label class="col-md-1">Konsinyasi</label> 
                        <label class="col-md-2">TOP</label> 
                        <label class="col-md-1">Discount 1</label> 
                        <label class="col-md-1">Discount 2</label> 
                        <label class="col-md-1">Discount 3</label> 
                        <label class="col-md-3">Kode Harga</label> 
                         <div class="col-sm-3">
                            <input type="text" name="ecustomercontact" class="form-control" value="" placeholder="Contact Person">
                        </div> 
                        <div class="col-sm-1">
                            <input type="checkbox" class="form-check-input"  name="fcustomerkonsinyasi">
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" name="ncustomertop" class="form-control" value="0" placeholder="TOP">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount1" class="form-control" value="0">
                        </div>  
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount2" class="form-control" value="0">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount3" class="form-control" value="0">
                        </div>
                        <div class="col-sm-3">
                             <select name="iharga" id="iharga" class="form-control select2">
                                <option value="">Pilih Kode Harga</option>
                                <?php foreach($harga as $row){?>
                                    <option value="<?=$row->id;?>"><?=$row->e_harga;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-1">PKP</label> 
                        <label class="col-md-2">NPWP</label>
                        <label class="col-md-3">Nama NPWP</label>
                        <label class="col-md-6">Alamat NPWP</label>
                        <div class="col-sm-1">
                            <input type="checkbox" class="form-check-input" name="fcustomerpkp" id="fcustomerpkp" onclick="wajibnpwp(this.value);">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="icustomernpwp" id="icustomernpwp" class="form-control" value="" placeholder="Nomor NPWP">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ecustomernpwpname" id="ecustomernpwpname" class="form-control" value="" placeholder="Nama NPWP">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="icustomerpwpaddress" id="icustomerpwpaddress" class="form-control" placeholder="Alamat NPWP"></textarea>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-3">Nomor Rekening </label>
                        <label class="col-md-4">Nama Rekening</label>                       
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control select2">
                                <option value="">Pilih Bank</option>
                                <?php foreach($bank as $row){?>
                                        <option value="<?=$row->i_bank;?>"><?=$row->e_bank_name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="inorekening" id="inorekening" class="form-control"  value="" placeholder="Nomor Rekening">
                        </div>                   
                        <div class="col-sm-4">
                            <input type="text" name="enamarekening" id="enamarekening" class="form-control" value="" placeholder="Nama Rekening">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Kategori Partner</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-3">Level Perusahaan</label>
                        <label class="col-md-3">Kepala Partner Group</label>
                        <div class="col-sm-3">
                            <select name="igroup" id="igroup" class="form-control select2" onchange="return cklevel();">
                                <option value="">Pilih Kategori Partner</option>
                                <?php foreach ($i_group as $igroup):?>
                                    <option value="<?php echo $igroup->i_supplier_group;?>">
                                        <?php echo $igroup->e_supplier_group_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="itypeindustry" class="form-control select2">
                                <option value="">Pilih Jenis Partner</option>
                                <?php foreach ($typeindustry as $itypeindustry):?>
                                    <option value="<?php echo $itypeindustry->i_type_industry;?>">
                                        <?php echo $itypeindustry->e_type_industry_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ilevelcompany" id="ilevelcompany" class="form-control select2" onchange="getpusat(this.value);" disabled="true">
                                <option value="">Level Perusahaan</option>
                                <?php foreach ($levelcompany as $ilevelcompany):?>
                                    <option value="<?php echo $ilevelcompany->i_level;?>">
                                        <?php echo $ilevelcompany->e_level_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikepalapusat" id="ikepalapusat" class="form-control select2" disabled>
                            </select>
                        </div>
                    </div>   
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group">
                            <span style="color: #8B0000"><b>NOTE :</b></span><br>
                            <span style="color: #8B0000">* Standar Kode terdiri dari 5 (lima) kombinasi huruf dan angka</span><br>
                            <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama </span><br>
                            <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada kode sebelumnya</span><br><br>
                            <span style="color: #8B0000"><b>* Contoh : AA001, AA002, dst</span>
                        </div>
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
    });

    function ceklis() {
        var alamat_utama = $('#ecustomeraddress').val();
        var cek_alamat_kirim = $('input[name="sama_dengan1"]:checked').length > 0;
        var cek_alamat_tagihan = $('input[name="sama_dengan2"]:checked').length > 0;
        if(cek_alamat_kirim===true){
            $('#e_shipping_address').val(alamat_utama);
        }

        console.log(cek_alamat_tagihan);

        if(cek_alamat_tagihan===true){
            $('#e_billing_address').val(alamat_utama);
        }
    }

    function cklevel(){
        $("#ilevelcompany").attr("disabled", false);
    }

    function getpusat(id){
        var isuppliergroup = $('#igroup').val();
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

    function getcity(){
        $("#ecity").attr("disabled", false);
        var iarea = $('#iarea').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getcity');?>",
            data:{
                'iarea' : iarea,
            }, 
            dataType: 'json',
            success: function (data) {
                $("#ecity").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#ecity").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        });
    }

    function huruf(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if ((charCode < 65 || charCode > 90)&&(charCode < 97 || charCode > 122)&&charCode>32)
            return false;
        return true;
    }

    function wajibnpwp(id){
        if ($('#fcustomerpkp').is(':checked')) {
            $("#icustomernpwp").prop('required',true);
            $("#ecustomernpwpname").prop('required',true);
            $("#icustomerpwpaddress").prop('required',true);
            swal("Kolom NPWP Dan Nama NPWP WAJIB DIISI");
        } else {
            $("#icustomernpwp").prop('required',false);
            $("#ecustomernpwpname").prop('required',false);
            $("#icustomerpwpaddress").prop('required',false);
        }
    }

    $("#icustomer").keyup(function() {
        var kode = $(this).val();
        $.ajax({
            type: "post",
            data: {
                'kode' : kode,
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#cek").attr("hidden", false);
                    $("#submit").attr("disabled", true);
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

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    }); 

    function validasi(){
        var icustomer       = $('icustomer').val();
        var ecustomername   = $('ecustomername').val();
        var igroup          = $('#igroup').val();
        var ilevelcompany   = $('#ilevelcompany').val(); 
        var ibank           = $('#ibank').val(); 
        var inorekening     = $('#inorekening').val(); 
        var enamarekening   = $('#enamarekening').val();  
        var iharga          = $('#iharga').val();   
        //alert (ibank);
        if(icustomer == '' || ecustomername == '' || igroup == '' || igroup == null || igroup == '' || igroup == null || ilevelcompany == '' || ilevelcompany == null || iharga
             == '' || iharga == null){
            swal("Data Belum Lengkap");
            return false;

         } //else if(ibank != '' || ibank != null){
        //     //alert(ibank);
        //     if(inorekening == '' || enamarekening == ''){
        //         //alert(inorekening);
        //         swal("Data Rekening dan Nama Rekening harus diisi");
        //         return false;
        //     }else{
        //         return true;
        //     }
        // }
        else{
                return true;    
        }
    }
</script>
