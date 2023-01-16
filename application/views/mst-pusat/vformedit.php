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
                <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-2">Kode Customer</label>
                        <label class="col-md-4">Nama Customer</label>
                        <label class="col-md-6">Alamat</label>                       
                        <div class="col-sm-2">
                            <input type="hidden" name="id" class="form-control" value="<?= $data->id?>">
                            <input type="text" name="icustomer" id="icustomer" class="form-control" autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="<?= $data->i_customer; ?>">
                            <span id="cek" hidden="true"> 
                                    <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div> 
                        <div class="col-sm-4">
                            <input type="text" name="ecustomername" class="form-control" required="" value="<?= $data->e_customer_name; ?>" onkeyup="gede(this);clearname(this);">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" onkeyup="ceklis();" name="ecustomeraddress" id="ecustomeraddress" class="form-control"><?= $data->e_customer_address; ?></textarea>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Alamat Kirim <input type="checkbox" class="mt-2 ml-2" onclick="ceklis();" name="sama_dengan1"> (Ceklis apabila sama dengan alamat utama)</label>                    
                        <label class="col-md-6">Alamat Penagihan <input type="checkbox" class="mt-2 ml-2" onclick="ceklis();" name="sama_dengan2"> (Ceklis apabila sama dengan alamat utama)</label></label>
                        <div class="col-sm-6">
                            <textarea type="text" onkeyup="ceklis();" name="e_shipping_address" id="e_shipping_address" class="form-control" placeholder="Alamat Kirim"><?= $data->e_shipping_address; ?></textarea>
                        </div>                        
                        <div class="col-sm-6">
                            <textarea type="text" onkeyup="ceklis();" name="e_billing_address" id="e_billing_address" class="form-control" placeholder="Alamat Penagihan"><?= $data->e_billing_address; ?></textarea>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Kota</label>
                        <label class="col-md-1">Kode Pos</label>
                        <label class="col-md-2">Telepon</label>
                        <label class="col-md-2">FAX</label>   
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" onchange="return getcity(this.value);">
                                <option value="">Pilih Area</option>
                                <?php foreach($area as $row){?>
                                    <?php if ($row->id_area == $data->id_area) { ?>
                                        <option value="<?= $row->id_area ;?>" selected><?=$row->e_area;?></option>
                                    <?php } else { ?>
                                        <option value="<?= $row->id_area ;?>"><?=$row->e_area;?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>       
                        <div class="col-sm-3">
                            <select name="ecity" id="ecity" class="form-control select2">
                                <option value="">Pilih Kota</option>
                                <?php foreach($city as $row){?>
                                    <?php if ($row->id == $data->id_city) { ?>
                                        <option value="<?= $row->id ;?>" selected><?=$row->e_city_name;?></option>
                                    <?php } else { ?>
                                        <option value="<?= $row->id ;?>"><?=$row->e_city_name;?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>             
                        <div class="col-sm-1">
                            <input type="text" name="epostalcode" class="form-control" maxlength="5" onkeypress="return angka(event)" value="<?= $data->e_customer_postalcode?>">
                        </div>   
                        <div class="col-sm-2">
                            <input type="text" name="ecustomerphone" class="form-control" onkeypress="return angka(event)" maxlength="15" value="<?= $data->e_customer_phone; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ecustomerfax" class="form-control" maxlength="15" value="<?= $data->e_customer_fax; ?>">
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
                            <input type="text" name="ecustomercontact" class="form-control" value="<?= $data->e_customer_contact; ?>">
                        </div> 
                        <div class="col-sm-1">
                             <?php $check= $data->f_customer_konsinyasi;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked name=fcustomerkonsinyasi>";
                                } else {
                                   echo "<input type=checkbox name=fcustomerkonsinyasi>"; 
                                }
                            ?>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" name="ncustomertop" class="form-control" value="<?= $data->n_customer_toplength; ?>" >
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount1" class="form-control" value="<?= $data->v_customer_discount; ?>">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount2" class="form-control" value="<?= $data->v_customer_discount2; ?>">
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount3" class="form-control" value="<?= $data->v_customer_discount3; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="iharga" id="iharga" class="form-control select2">
                                <option value="">Pilih Harga</option>
                                <?php foreach($harga as $row){?>
                                    <?php if ($row->id == $data->id_harga_kode) { ?>
                                        <option value="<?= $row->id ;?>" selected><?=$row->e_harga;?></option>
                                    <?php } else { ?>
                                        <option value="<?= $row->id ;?>"><?=$row->e_harga;?></option>
                                    <?php } ?>
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
                            <?php $check= $data->f_pkp;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked name=fcustomerpkp id=fcustomerpkp onclick=wajibnpwp(this.value);>";
                                } else {
                                   echo "<input type=checkbox name=fcustomerpkp id=fcustomerpkp onclick=wajibnpwp(this.value);>"; 
                                }
                            ?>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="icustomernpwp" id="icustomernpwp" class="form-control" value="<?=$data->i_customer_npwp?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ecustomernpwpname" id="ecustomernpwpname" class="form-control" onkeypress="return huruf(event)" value="<?= $data->e_customer_npwp?>">
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="icustomerpwpaddress" id="icustomerpwpaddress" class="form-control"><?= $data->i_customer_npwp_address?></textarea>
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
                                    <?php if ($row->i_bank == $data->i_bank) { ?>
                                        <option value="<?= $row->i_bank ;?>" selected><?=$row->e_bank_name;?></option>
                                    <?php } else { ?>
                                        <option value="<?= $row->i_bank ;?>"><?=$row->e_bank_name;?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="inorekening" id="inorekening" class="form-control"  value="<?=$data->i_no_rekening?>">
                        </div>                   
                        <div class="col-sm-4">
                            <input type="text" name="enamarekening" id="enamarekening" class="form-control" value="<?=$data->e_nama_rekening?>">
                        </div>                                           
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Kategori Partner</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-3">Level Perusahaan</label>
                        <label class="col-md-3">Kepala Partner Group</label>
                        <div class="col-sm-3">
                            <select name="igroup" id="igroup" class="form-control select2" >
                                <?php foreach($customergroup as $igroup): ?>
                                    <option value="<?php echo $igroup->i_supplier_group;?>" 
                                <?php if($igroup->i_supplier_group==$data->i_group_code) { ?> selected="selected" <?php } ?>>
                                <?php echo $igroup->e_supplier_group_name;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                           <select name="itypeindustry" id="itypeindustry" class="form-control select2">
                                <?php foreach ($typeindustry as $itypeindustry):?>
                                    <option value="<?php echo $itypeindustry->i_type_industry;?>"
                                        <?php if($itypeindustry->i_type_industry==$data->i_type_industry) { ?> selected="selected" <?php } ?>>
                                        <?php echo $itypeindustry->e_type_industry_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ilevelcompany" id="ilevelcompany" class="form-control select2">
                                <?php foreach ($levelcompany as $ilevelcompany):?>
                                    <option value="<?php echo $ilevelcompany->i_level;?>" <?php if($ilevelcompany->i_level==$data->i_level) { ?> selected="selected" <?php } ?>>
                                        <?php echo $ilevelcompany->e_level_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <?php if($data->i_level == 'PLV00'){?>
                                <select disabled name="ikepalapusat" id="ikepalapusat" class="form-control select2">
                                </select>
                            <?}else{?>
                                <select name="ikepalapusat" id="ikepalapusat" class="form-control select2">
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
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
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

    function cklevel(){
        $("#ilevelcompany").attr("disabled", false);
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

    function getcity(){
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
        var icustomer       = $('#icustomer').val();
        var igroup          = $('#igroup').val();
        var ilevelcompany   = $('#ilevelcompany').val();   
        var ibank           = $('#ibank').val(); 
        var inorekening     = $('#inorekening').val(); 
        var enamarekening   = $('#enamarekening').val();  
        
        if(icustomer == '' || igroup == '' || igroup == null || ilevelcompany == '' || ilevelcompany == null){
            swal("Data Belum Lengkap");
            return false;
        // }else if(ibank != '' || ibank != null){
        //     //alert(ibank);
        //     if(inorekening == '' || enamarekening == ''){
        //         //alert(inorekening);
        //         swal("Data Rekening dan Nama Rekening harus diisi");
        //         return false;
        //     }else{
        //         return true;
        //     }
        }else{
                return true;    
        }
    }

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
</script>
