<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
                <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-2">Kode Customer</label>
                        <label class="col-md-4">Nama Customer</label>
                        <label class="col-md-6">Alamat</label>                        
                        <div class="col-sm-2">
                            <input type="hidden" name="id" class="form-control" value="<?= $data->id?>">
                            <input type="text" name="icustomer" id="icustomer" class="form-control" autocomplete="off" required="" maxlength="15" onkeyup="gede(this);clearcode(this)" value="<?= $data->i_customer; ?>" readonly>
                        </div> 
                        <div class="col-sm-4">
                            <input type="text" name="ecustomername" class="form-control" required="" value="<?= $data->e_customer_name; ?>" onkeyup="gede(this);clearname(this);" readonly>
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="ecustomeraddress" id="ecustomeraddress" class="form-control" readonly><?= $data->e_customer_address; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Alamat Kirim</label>                    
                        <label class="col-md-6">Alamat Penagihan</label>
                        <div class="col-sm-6">
                            <textarea type="text" name="e_shipping_address" id="e_shipping_address" class="form-control" readonly placeholder="Alamat Kirim"><?= $data->e_shipping_address; ?></textarea>
                        </div>                        
                        <div class="col-sm-6">
                            <textarea type="text" name="e_billing_address" id="e_billing_address" class="form-control" readonly placeholder="Alamat Penagihan"><?= $data->e_billing_address; ?></textarea>
                        </div>                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>
                        <label class="col-md-3">Kota</label>
                        <label class="col-md-1">Kode Pos</label>
                        <label class="col-md-2">Telepon</label>
                        <label class="col-md-2">FAX</label>    
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" disabled="">
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
                            <select name="ecity" id="ecity" class="form-control select2" disabled="">
                                <option value="<?=$data->id_city;?>"><?=$data->e_city_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="epostalcode" class="form-control" maxlength="5" onkeypress="return angka(event)" value="<?= $data->e_customer_postalcode?>" readonly>
                        </div>   
                        <div class="col-sm-2">
                            <input type="text" name="ecustomerphone" class="form-control" onkeypress="return angka(event)" maxlength="15" value="<?= $data->e_customer_phone; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ecustomerfax" class="form-control" maxlength="15" value="<?= $data->e_customer_fax; ?>" readonly>
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
                            <input type="text" name="ecustomercontact" class="form-control" value="<?= $data->e_customer_contact; ?>" readonly>
                        </div> 
                        <div class="col-sm-1">
                             <?php $check= $data->f_customer_konsinyasi;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked name=fcustomerkonsinyasi disabled>";
                                } else {
                                   echo "<input type=checkbox name=fcustomerkonsinyasi disabled>"; 
                                }
                            ?>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" name="ncustomertop" class="form-control" value="<?= $data->n_customer_toplength; ?>" readonly>
                        </div> 
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount1" class="form-control" value="<?= $data->v_customer_discount; ?>" readonly>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount2" class="form-control" value="<?= $data->v_customer_discount2; ?>" readonly>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" name="ncustomerdiscount3" class="form-control" value="<?= $data->v_customer_discount3; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="iharga" id="iharga" class="form-control select2" disabled="">
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
                                    echo "<input type=checkbox checked=checked name=fcustomerpkp id=fcustomerpkp onclick=wajibnpwp(this.value); disabled>";
                                } else {
                                   echo "<input type=checkbox name=fcustomerpkp id=fcustomerpkp onclick=wajibnpwp(this.value); disabled>"; 
                                }
                            ?>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="icustomernpwp" id="icustomernpwp" class="form-control" value="<?=$data->i_customer_npwp?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ecustomernpwpname" id="ecustomernpwpname" class="form-control" onkeypress="return huruf(event)" value="<?= $data->e_customer_npwp?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="icustomerpwpaddress" id="icustomerpwpaddress" class="form-control" readonly><?= $data->i_customer_npwp_address?></textarea>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-3">Nomor Rekening </label>
                        <label class="col-md-4">Nama Rekening</label>                       
                        <div class="col-sm-3">
                            <select name="ibank" id="ibank" class="form-control select2" disabled>
                                <?php foreach($bank as $ibank): ?>
                                    <option value="<?php echo $ibank->i_bank;?>" 
                                <?php if($ibank->i_bank==$data->i_bank) { ?> selected="selected" <?php } ?>>
                                <?php echo $ibank->e_bank_name;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="inorekening" class="form-control"  value="<?=$data->i_no_rekening?>" readonly>
                        </div>                   
                        <div class="col-sm-4">
                            <input type="text" name="enamarekening" class="form-control" value="<?=$data->e_nama_rekening?>"readonly>
                        </div>                                           
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Kategori Partner</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-3">Level Perusahaan</label>
                        <label class="col-md-3">Kepala Partner Group</label>
                        <div class="col-sm-3">
                            <select name="igroup" id="igroup" class="form-control select2" disabled>
                                <?php foreach($customergroup as $igroup){ ?>
                                    <option value="<?php echo $igroup->i_supplier_group;?>" 
                                <?php if($igroup->i_supplier_group==$data->i_group_code) { ?> selected="selected" <?php } ?>>
                                <?php echo $igroup->e_supplier_group_name;?></option>
                                <?php } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                           <select name="itypeindustry" id="itypeindustry" class="form-control select2" disabled>
                                <?php foreach ($typeindustry as $itypeindustry){?>
                                    <option value="<?php echo $itypeindustry->i_type_industry;?>"
                                        <?php if($itypeindustry->i_type_industry==$data->i_type_industry) { ?> selected="selected" <?php } ?>>
                                        <?php echo $itypeindustry->e_type_industry_name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">                        
                            <select name="ilevelcompany" id="ilevelcompany" class="form-control select2" disabled>
                                <?php foreach ($levelcompany as $ilevelcompany){?>
                                    <option value="<?php echo $ilevelcompany->i_level;?>" <?php if($ilevelcompany->i_level==$data->i_level) { ?> selected="selected" <?php } ?>>
                                        <?php echo $ilevelcompany->e_level_name;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <?php if($data->i_level == 'PLV00'){?>
                                <select disabled name="ikepalapusat" id="ikepalapusat" class="form-control select2" disabled>
                                </select>
                            <?}else{?>
                                <select name="ikepalapusat" id="ikepalapusat" class="form-control select2" disabled>
                                   <!--  <option value="<?=$data->i_kepala_pusat?>"><?=$data->i_pusat;?></option> -->
                                    <?php foreach($kepalapusat as $row){?>
                                        <?php if ($row->i_kepala_pusat == $data->i_kepala_pusat) { ?>
                                                <option value="<?= $row->i_kepala_pusat ;?>" selected><?=$row->e_pusat;?></option>
                                        <?php } else { ?>
                                                <option value="<?= $row->i_kepala_pusat ;?>"><?=$row->e_pusat;?></option>
                                        <?php } ?>
                                    
                                    <?php } ?> 
                                </select>
                            <?}?>
                        </div>
                    </div>   
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
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
</script>
