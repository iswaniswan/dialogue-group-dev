<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-md-12">
        <div class="white-box">
            <div id="pesan"></div>
            <section>
                <div class="sttabs tabs-style-bar">
                    <nav>
                        <ul>
                            <li><a href="#section-bar-1" class="sticon ti-info-alt "><span>Detail Pelanggan Baru</span></a></li>
                            <li><a href="#section-bar-2" class="sticon ti-home"><span>SPB Baru</span></a></li>
                        </ul>
                    </nav>
                    <div class="content-wrap">
                        <section id="section-bar-1">
                            <div class="row">                              
                                <div class="col-md-12">                                
                                    <div class="panel panel-info">
                                        <div class="panel-heading"> <i class="fa fa-plus"></i> &nbsp; <?= "Detail Pelanggan Baru"; ?> <a href="#"
                                            onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
                                        </div>
                                        <div class="panel-wrapper collapse in" aria-expanded="true">
                                            <div class="panel-body">
                                                <div class="form-body">
                                                    <div class="form-group row has-error">
                                                        <label class="col-md-3">Area <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <input readonly type="text" name="eareaname" id="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>">
                                                            <input readonly type="hidden" name="iarea" id="iarea" class="form-control" value="<?= $isi->i_area; ?>">
                                                        </div>
                                                        <label class="col-md-3">Sales <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <input readonly type="text" name="esalesmanname" id="esalesmanname" class="form-control" value="<?= $isi->e_salesman_name; ?>">
                                                            <input type="hidden" name="isalesman" id="isalesman" value="<?= $isi->i_salesman; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3">Tanggal Survey <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <input type="text" readonly id= "dsurvey" name="dsurvey" class="form-control date"  value="<?= $isi->d_survey; ?>">
                                                        </div>
                                                        <label class="col-md-3">Periode Kunjungan <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-1">
                                                            <input type="text" readonly id= "nvisitperiod" name="nvisitperiod" class="form-control"  value="<?= $isi->n_visit_period; ?>">
                                                        </div>
                                                    </div>                                                    
                                                    <div class="form-group row">                                                        
                                                        <label class="col-md-3">Kota <span style="color: #8B0000">*</span></label>                                                        
                                                        <div class="col-sm-3">                                                            
                                                            <input readonly type="text" name="ecity" id="ecity" class="form-control" value="<?= $isi->e_customer_kota1; ?>">                                                      
                                                            <input readonly type="hidden" name="icity" id="icity" class="form-control" value="<?= $isi->i_city; ?>">                                                      
                                                        </div>                                                        
                                                        <label class="col-md-3">Kriteria Pelanggan <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">                                                           
                                                            <div class="form-check has-error">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkcriterianew" name="chkcriterianew" class="custom-control-input" <?php if($isi->f_customer_new=='t') { echo 'checked  value="on"'; }else{ echo 'value=""';} ?> >
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Pelanggan Baru / New</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkcriteriaupdate" name="chkcriteriaupdate" class="custom-control-input"
                                                                    <?php if($isi->f_customer_new=='f') {
                                                                        echo 'checked  value="on"';
                                                                    }else{
                                                                        echo 'value=""';
                                                                    } ?>>
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Pelanggan Lama / UpDate</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h3 class="box-title">Data Toko / Pelanggan</h3>
                                                    <hr class="m-t-0 m-b-40">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Nama Toko / Pelanggan <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" id="ecustomername" name="ecustomername" maxlength="50" class="form-control" value="<?= $isi->e_customer_name; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Alamat Toko</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" class="form-control" name="ecustomeraddress" id="ecustomeraddress" value="<?= $isi->e_customer_address; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Penanda Toko</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" id="ecustomersign" name="ecustomersign" class="form-control" value="<?= $isi->e_customer_sign; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">RT / RW / Kode Pos</label>
                                                                <div class="col-md-2">
                                                                    <input readonly type="text" class="form-control" name="ert1" id="ert1" placeholder="RT" maxlength='2' value="<?= $isi->e_rt1; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input readonly type="text" class="form-control" name="erw1" id="erw1" placeholder="RW" maxlength='2' value="<?= $isi->e_rt2; ?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input readonly type="text" class="form-control" name="epostal1" id="epostal1" placeholder="Kode Pos" maxlength='5' value="<?= $isi->e_postal1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Telepon</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" id="ecustomerphone" name="ecustomerphone" class="form-control" maxlength="20" value="<?= $isi->e_customer_phone; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Fax</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" id="efax1" name="efax1" class="form-control" maxlength="20" value="<?= $isi->e_fax1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Yang Dihubungi</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" id="ecustomercontact" name="ecustomercontact" maxlength="30" class="form-control" value="<?= $isi->e_customer_contact; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jabatan</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" class="form-control" name="ecustomercontactgrade" id="ecustomercontactgrade"  maxlength='30' value="<?= $isi->e_customer_contactgrade; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Mulai Usaha</label>
                                                                <div class="col-md-2">
                                                                    <input readonly type="text" id="ecustomermonth" name="ecustomermonth" placeholder="Bulan" maxlength="2" class="form-control" value="<?= $isi->e_customer_month; ?>">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input readonly type="text" id="ecustomeryear" name="ecustomeryear" placeholder="Tahun" maxlength="4" class="form-control" value="<?= $isi->e_customer_year; ?>">
                                                                </div>
                                                                <label class="control-label col-md-3">(Bulan / Tahun)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Tahun</label>
                                                                <div class="col-md-8">
                                                                    <input readonly type="text" class="form-control" name="ecustomerage" maxlength="4" id="ecustomerage" value="<?= $isi->e_customer_age; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Status Toko</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" name="eshopstatus" id="eshopstatus" value="<?php echo $isi->e_shop_status; ?>" readonly>
                                                                    <input type="hidden" id="ishopstatus" name="ishopstatus" class="form-control" value="<?php echo $isi->i_shop_status; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Luas Fisik Toko</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" id="nshopbroad" name="nshopbroad" maxlength="9" class="form-control" value="<?php echo $isi->n_shop_broad; ?>" readonly>
                                                                </div>
                                                                <label class="control-label col-md-4">M2 * Total Luas Toko</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kelurahan</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerkelurahan1" name="ecustomerkelurahan1" maxlength="30" class="form-control" value="<?php echo $isi->e_customer_kelurahan1; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kecamatan</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" name="ecustomerkecamatan1" id="ecustomerkecamatan1"value="<?php echo $isi->e_customer_kecamatan1; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kabupaten / Kodya</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerkota1" name="ecustomerkota1" maxlength="30" class="form-control" value="<?php echo $isi->e_customer_kota1; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Provinsi</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" name="ecustomerprovinsi1" id="ecustomerprovinsi1" value="" maxlength='30' value="<?php echo $isi->e_customer_provinsi1; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h3 class="box-title">DATA PEMILIK / PENGURUS TOKO / PELANGGAN</h3>
                                                    <hr class="m-t-0 m-b-40">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Nama Pemilik <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerowner" name="ecustomerowner" maxlength="50" class="form-control"value="<?php echo $isi->e_customer_owner; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">TTL / Umur</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="ecustomerownerttl" id="ecustomerownerttl" value="<?php echo $isi->e_customer_ownerttl; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" class="form-control" name="ecustomerownerage" id="ecustomerownerage" placeholder="Umur" maxlength='3'value="<?php echo $isi->e_customer_ownerage; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">NIK <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="inik" name="inik" maxlength="20" class="form-control" value="<?php echo $isi->i_nik; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Status</label>
                                                                <div class="col-md-8">
                                                                <input type="text" id="emarriage" name="emarriage" class="form-control" value="<?php echo $isi->e_marriage; ?>" readonly>
                                                                    <input type="hidden" id="imarriage" name="imarriage" class="form-control" value="<?php echo $isi->i_marriage; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jenis Kelamin</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ejeniskelamin" name="ejeniskelamin" class="form-control" value="<?php echo $isi->e_jeniskelamin; ?>" readonly>
                                                                    <input type="hidden" id="ijeniskelamin" name="ijeniskelamin" class="form-control" value="<?php echo $isi->i_jeniskelamin; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Agama</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ereligion" name="ereligion" class="form-control" value="<?php echo $isi->e_religion; ?>" readonly>
                                                                    <input type="hidden" id="ireligion" name="ireligion" class="form-control" value="<?php echo $isi->i_religion; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">  
                                                            <label class="control-label col-md-2"></label>
                                                            <div class="form-check">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkidemtoko1" name="chkidemtoko1" class="custom-control-input">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Sama Dengan Alamat Toko</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Alamat Rumah</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerowneraddress" maxlength="200" name="ecustomerowneraddress" class="form-control" value="<?php echo $isi->e_customer_owneraddress; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">RT / RW / Kode Pos</label>
                                                                <div class="col-md-2">
                                                                    <input type="text" class="form-control" name="ert2" id="ert2" placeholder="RT" maxlength='3' value="<?php echo $isi->e_rt2; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" class="form-control" name="erw2" id="erw2" placeholder="RW" maxlength='3' value="<?php echo $isi->e_rw2; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" class="form-control" name="epostal2" id="epostal2" placeholder="Kode Pos" maxlength='5' value="<?php echo $isi->e_postal2; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Telepon / HP</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" id="ecustomerownerphone" name="ecustomerownerphone" placeholder="Telepon" class="form-control" maxlength="15" value="<?php echo $isi->e_customer_ownerphone; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" id="ecustomerownerhp" name="ecustomerownerhp" placeholder="Hp" class="form-control" maxlength="15" value="<?php echo $isi->e_customer_ownerhp; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Fax / E-mail</label>
                                                                <div class="col-md-3">
                                                                    <input type="text" id="ecustomerownerfax" name="ecustomerownerfax" placeholder="Fax" class="form-control" maxlength="20" value="<?php echo $isi->e_customer_ownerfax; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="text" id="ecustomermail" name="ecustomermail" placeholder="E-mail" class="form-control" maxlength="30" value="<?php echo $isi->e_customer_mail; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Nama Suami / Istri</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerownerpartner" name="ecustomerownerpartner" maxlength="50" class="form-control" value="<?php echo $isi->e_customer_ownerpartner; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">TTL / Umur</label>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="ecustomerownerpartnerttl" id="ecustomerownerpartnerttl" value="" maxlength='50' value="<?php echo $isi->e_customer_ownerpartnerttl; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" class="form-control" name="ecustomerownerpartnerage" id="ecustomerownerpartnerage" placeholder="Umur" maxlength='3' value="<?php echo $isi->e_customer_ownerpartnerage; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kelurahan</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerkelurahan2" name="ecustomerkelurahan2" maxlength="50" class="form-control" value="<?php echo $isi->e_customer_kelurahan2; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kecamatan</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" name="ecustomerkecamatan2" id="ecustomerkecamatan2" value="" maxlength='50' value="<?php echo $isi->e_customer_kecamatan2; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kabupaten / Kodya</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomerkota2" name="ecustomerkota2" maxlength="50" class="form-control" value="<?php echo $isi->e_customer_kota2; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Provinsi</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control" name="ecustomerprovinsi2" id="ecustomerprovinsi2" value="" maxlength='50' value="<?php echo $isi->e_customer_provinsi2; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">  
                                                            <label class="control-label col-md-2"></label>
                                                            <div class="form-check">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkidemtoko2" name="chkidemtoko2" class="custom-control-input">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Sama Dengan Alamat Toko</span>
                                                                </label>
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkidemtoko3" name="chkidemtoko3" class="custom-control-input">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Sama Dengan Alamat Rumah</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Alamat Kirim</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomersendaddress" name="ecustomersendaddress" maxlength="200" class="form-control" value="<?php echo $isi->e_customer_sendaddress; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">RT / RW / Kode Pos</label>
                                                                <div class="col-md-2">
                                                                    <input type="text" class="form-control" name="ert3" id="ert3" placeholder="RT" maxlength='3' value="<?php echo $isi->e_rt3; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text" class="form-control" name="erw3" id="erw3" placeholder="RW" maxlength='3' value="<?php echo $isi->e_rw3; ?>" readonly>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" class="form-control" name="epostal3" id="epostal3" placeholder="Kode Pos" maxlength='5' value="<?php echo $isi->e_postal3; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Telepon</label>
                                                                <div class="col-md-8">
                                                                    <input type="text" id="ecustomersendphone" name="ecustomersendphone" class="form-control" maxlength="20" value="<?php echo $isi->e_customer_sendphone; ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">                                        
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Lokasi Bisa Dilalui Oleh</label>
                                                                <div class="col-md-8"> 
                                                                 <input type="text" id="etraversed" name="etraversed" class="form-control" value="<?php echo $isi->e_traversed; ?>" readonly>
                                                                 <input type="hidden" id="itraversed" name="itraversed" class="form-control" value="<?php echo $isi->i_traversed; ?>" readonly>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="row">
                                                <div class="col-md-12">  
                                                    <label class="control-label col-md-2"></label>
                                                    <div class="form-check">
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="fparkir" name="fparkir" class="custom-control-input"
                                                            <?php if($isi->f_parkir=='t') {
                                                                    echo 'checked  value="on"';
                                                                }else{
                                                                    echo 'value=""';
                                                                } ?>>
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description">Ada Biaya Retribusi Parkir</span>
                                                        </label>
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="fkuli" name="fkuli" class="custom-control-input"
                                                            <?php if($isi->f_kuli=='t') {
                                                                    echo 'checked  value="on"';
                                                                }else{
                                                                    echo 'value=""';
                                                                } ?>>
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description">Ada Biaya Kuli</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Ekspedisi Toko 1</label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="eekspedisi1" name="eekspedisi1" maxlength="50" class="form-control" value="<?php echo $isi->e_ekspedisi1; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Ekspedisi Toko 2</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="eekspedisi2" id="eekspedisi2" value="<?php echo $isi->e_ekspedisi2; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Kabupaten / Kodya</label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="ecustomerkota3" name="ecustomerkota3" maxlength="50" class="form-control" value="<?php echo $isi->e_customer_kota3; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Provinsi</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="ecustomerprovinsi3" id="ecustomerprovinsi3" value="" maxlength='50' value="<?php echo $isi->e_customer_provinsi3; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">No NPWP <span style="color: #8B0000">*</span></label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="ecustomernpwp" name="ecustomernpwp" maxlength="16" class="form-control" value="<?php echo $isi->e_customer_pkpnpwp; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Nama NPWP</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="ecustomernpwpname" id="ecustomernpwpname" value="" maxlength='50' value="<?php echo $isi->e_customer_npwpname; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-2">Alamat NPWP</label>
                                                        <div class="col-md-10">
                                                            <input type="text" id="ecustomernpwpaddress" name="ecustomernpwpaddress" class="form-control" maxlength="200" value="<?php echo $isi->e_customer_npwpaddress; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="box-title">KUALIFIKASI PELANGGAN</h3>
                                            <hr class="m-t-0 m-b-40">
                                            <div class="row">
                                                <div class="col-md-6">  
                                                </div>
                                                <div class="col-md-6">  
                                                    <label class="control-label col-md-4"></label>
                                                    <div class="form-check">
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="chkkontrabon" name="chkkontrabon" class="custom-control-input">
                                                            <span class="custom-control-indicator"></span>
                                                            <span class="custom-control-description">Kontra Bon</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">Tipe Pelanggan <span style="color: #8B0000">*</span></label>
                                                        <div class="col-md-8">
                                                         <input type="text" id="ecustomerclass" name="ecustomerclass" class="form-control" value="<?php echo $isi->e_customer_classname; ?>" readonly>
                                                         <input type="hidden" id="icustomerclass" name="icustomerclass" class="form-control" value="<?php echo $isi->i_customer_class; ?>" readonly>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Jadwal Kontra Bon</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="ekontrabonhari" id="ekontrabonhari" value="<?php echo $isi->e_kontrabon_hari; ?>" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="ekontrabonjam1" id="ekontrabonjam1" placeholder="Jam" maxlength='5' value="<?php echo $isi->e_kontrabon_jam1; ?>" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <input type="text" class="form-control" name="ekontrabonjam2" id="ekontrabonjam2" placeholder="Jam" maxlength='5' value="<?php echo $isi->e_kontrabon_jam2; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4">Pola Pembayaran <span style="color: #8B0000">*</span></label>
                                                    <div class="col-md-8">
                                                     <input type="text" id="epaymentmethod" name="epaymentmethod" class="form-control" value="<?php echo $isi->e_paymentmethod; ?>" readonly>
                                                     <input type="hidden" id="ipaymentmethod" name="ipaymentmethod" class="form-control" value="<?php echo $isi->i_paymentmethod; ?>" readonly>
                                                 </div>
                                             </div>
                                         </div>                      
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Jadwal Tagih</label>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="etagihhari" id="etagihhari" value="<?php echo $isi->e_tagih_hari; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="etagihjam1" id="etagihjam1" placeholder="Jam" maxlength='5' value="<?php echo $isi->e_tagih_jam1; ?>" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="etagihjam2" id="etagihjam2" placeholder="Jam" maxlength='5' value="<?php echo $isi->e_tagih_jam2; ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">I. Nama Bank</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerbank1" maxlength="25" name="ecustomerbank1" class="form-control" value="<?php echo $isi->e_customer_bank1; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">No. A/C</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerbankaccount1" maxlength="25" name="ecustomerbankaccount1" class="form-control" value="<?php echo $isi->e_customer_bankaccount1; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Atas Nama</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerbankname1" maxlength="50" name="ecustomerbankname1" class="form-control" value="<?php echo $isi->e_customer_bankname1; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">II. Nama Bank</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerbank2" maxlength="25" name="ecustomerbank2" class="form-control" value="<?php echo $isi->e_customer_bank2; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">No. A/C</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerbankaccount2" maxlength="25" name="ecustomerbankaccount2" class="form-control" value="<?php echo $isi->e_customer_bankaccount2; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Atas Nama</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerbankname2" maxlength="50" name="ecustomerbankname2" class="form-control" value="<?php echo $isi->e_customer_bankname2; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nama Kompetitor 1</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ekompetitor1" name="ekompetitor1" maxlength="20" class="form-control" value="<?php echo $isi->e_kompetitor1; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nama Kompetitor 2</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ekompetitor2" name="ekompetitor2" maxlength="20" class="form-control" value="<?php echo $isi->e_kompetitor2; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Nama Kompetitor 3</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ekompetitor3" name="ekompetitor3" maxlength="20" class="form-control" value="<?php echo $isi->e_kompetitor3; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">TOP (Hari) <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-5">
                                                <input type="text" id="ncustomertoplength" name="ncustomertoplength" maxlength="3" placeholder="Hari" class="form-control" value="<?php echo $isi->n_spb_toplength; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Discount (%) <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-5">
                                                <input type="text" id="ncustomerdiscount" name="ncustomerdiscount" maxlength="3" placeholder="%" class="form-control"value="<?php echo $isi->n_customer_discount; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Waktu Untuk Menghubungi</label>
                                            <div class="col-md-6">
                                                <input type="text" id="icall" name="icall" class="form-control" value="<?php echo $isi->i_call; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="box-title">LAIN-LAIN</h3>
                                <hr class="m-t-0 m-b-40">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Group Pelanggan <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomergroup" name="ecustomergroup" class="form-control" value="<?php echo $isi->e_customer_groupname; ?>" readonly>
                                                <input type="hidden" id="icustomergroup" name="icustomergroup" class="form-control" value="<?php echo $isi->i_customer_group; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">PLU Group</label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerplugroup" name="ecustomerplugroup" class="form-control" value="<?php echo $isi->e_customer_plugroupname; ?>" readonly>
                                                <input type="hidden" id="icustomerplugroup" name="icustomerplugroup" class="form-control" value="<?php echo $isi->i_customer_plugroup; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Tipe Produk <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerproducttype" name="ecustomerproducttype" class="form-control" value="<?php echo $isi->e_customer_producttypename; ?>" readonly>
                                                <input type="hidden" id="icustomerproducttype" name="icustomerproducttype" class="form-control" value="<?php echo $isi->i_customer_producttype; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Produk Khusus <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerspecialproduct" name="ecustomerspecialproduct" class="form-control" value="<?php echo $isi->e_customer_specialproductname; ?>" readonly>
                                                <input type="hidden" id="icustomerspecialproduct" name="icustomerspecialproduct" class="form-control" value="<?php echo $isi->i_customer_specialproduct; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Status Pelanggan <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerstatus" name="ecustomerstatus" class="form-control" value="<?php echo $isi->e_customer_statusname; ?>" readonly>
                                                <input type="hidden" id="icustomerstatus" name="icustomerstatus" class="form-control" value="<?php echo $isi->i_customer_status; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Tingkat Pelanggan <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomergrade" name="ecustomergrade" class="form-control" value="<?php echo $isi->e_customer_gradename; ?>" readonly>
                                                <input type="hidden" id="icustomergrade" name="icustomergrade" class="form-control" value="<?php echo $isi->i_customer_grade; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Jenis Pelayanan <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomerservice" name="ecustomerservice" class="form-control" value="<?php echo $isi->e_customer_servicename; ?>" readonly>
                                                <input type="hidden" id="icustomerservice" name="icustomerservice" class="form-control" value="<?php echo $isi->i_customer_service; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">Cara Penjualan <span style="color: #8B0000">*</span></label>
                                            <div class="col-md-8">
                                                <input type="text" id="ecustomersalestype" name="ecustomersalestype" class="form-control" value="<?php echo $isi->e_customer_salestypename; ?>" readonly>
                                                <input type="hidden" id="icustomersalestype" name="icustomersalestype" class="form-control" value="<?php echo $isi->i_customer_salestype; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-2">Keterangan</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" name="ecustomerrefference" id="ecustomerrefference" value="<?php echo $isi->e_customer_refference; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">  
                                    <label class="control-label col-md-5"></label>
                                    <div class="form-check">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" id="chkkontrabon" name="chkkontrabon" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Approve</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-5 col-sm-8">
                                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                        &nbsp;&nbsp;
                                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="section-bar-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
                            </div>
                            <div class="panel-body table-responsive">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-12">Tanggal SPB</label>
                                        <div class="col-sm-12">
                                            <input type="text" id= "dspb" name="dspb" class="form-control"  value="<?= $isispb->d_spb;?>" readonly>
                                            <input id="ispb" name="ispb" type="hidden" value="<?= $isispb->i_spb;?>" readonly>
                                            <input id="iperiode" name="iperiode" type="hidden" value="">
                                            <input id="dspbsys" name="dspbsys" type="hidden" value=""></td>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12">PO</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="ispbpo" name="ispbpo" class="form-control" maxlength="10"  value="<?= $isispb->i_spb_po;?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-8">SPB Lama</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="text" value="" id="ispbold" name="ispbold"  value="<?= $isispb->i_spb_old;?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" 
                                                    <?php if($isispb->f_spb_stockdaerah=='t') {
                                                            echo 'checked  value="on"';
                                                        }else{
                                                            echo 'value=""';
                                                        } ?> onclick="pilihstockdaerah(this.value)">
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description">Stock Daerah</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12">PKP</label>
                                        <div class="col-sm-12">
                                            <input type="text" readonly id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" maxlength="30"  value="<?= $isispb->e_customer_pkpnpwp;?>" readonly>
                                            <input id="fspbplusppn" name="fspbplusppn" type="hidden" value="<?= $isispb->f_spb_plusppn;?>" readonly>
                                            <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden" value="<?= $isispb->f_spb_plusdiscount;?>" readonly>
                                            <input id="fspbpkp" name="fspbpkp" type="hidden" value="<?= $isispb->f_spb_pkp;?>" readonly>
                                            <input id="fcustomerfirst" name="fcustomerfirst" type="hidden" value="on">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12">Keterangan</label>
                                        <div class="col-sm-12">
                                            <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control" value="<?=$isispb->emark1?>" readonly>
                                        </div>
                                    </div>
                                    <?php 
									if($nilaiorderspb==$isispb->v_spb){
										$norderspbbefore= $isispb->v_spb;
										$disc1parsing	= explode(".",$isispb->n_spb_discount1,strlen($isispb->n_spb_discount1));
										$disc1		= ($norderspbbefore * $disc1parsing[0])/100;
										$disc1parsing2	= explode(".",$isispb->n_spb_discount2,strlen($isispb->n_spb_discount2));
										$disc2		= ($norderspbbefore * $disc1parsing2[0])/100;
										$disc1parsing3	= explode(".",$isispb->n_spb_discount3,strlen($isispb->n_spb_discount3));
										$disc3		= ($norderspbbefore * $disc1parsing3[0])/100;			
										$norderspbafter	= ($isispb->v_spb - (($disc1+$disc2+$disc3)));
									}elseif($isispb->v_spb_after<$nilaiorderspb){
										$norderspbbefore= $nilaiorderspb;
										$disc1parsing	= explode(".",$isispb->n_spb_discount1,strlen($isispb->n_spb_discount1));
										$disc1		= ($norderspbbefore * $disc1parsing[0])/100;
										$disc1parsing2	= explode(".",$isispb->n_spb_discount2,strlen($isispb->n_spb_discount2));
										$disc2		= ($norderspbbefore * $disc1parsing2[0])/100;
										$disc1parsing3	= explode(".",$isispb->n_spb_discount3,strlen($isispb->n_spb_discount3));
										$disc3		= ($norderspbbefore * $disc1parsing3[0])/100;
										$norderspbafter	= ($nilaiorderspb - (($disc1+$disc2+$disc3)));
									}else{
										$norderspbbefore= $nilaiorderspb;
										$disc1parsing	= explode(".",$isispb->n_spb_discount1,strlen($isispb->n_spb_discount1));
										$disc1		= ($norderspbbefore * $disc1parsing[0])/100;
										$disc1parsing2	= explode(".",$isispb->n_spb_discount2,strlen($isispb->n_spb_discount2));
										$disc2		= ($norderspbbefore * $disc1parsing2[0])/100;
										$disc1parsing3	= explode(".",$isispb->n_spb_discount3,strlen($isispb->n_spb_discount3));
										$disc3		= ($norderspbbefore * $disc1parsing3[0])/100;
										$norderspbafter	= ($nilaiorderspb - (($disc1+$disc2+$disc3)));
									}
									?>
                                    <div class="form-group row">
                                        <label class="col-md-12">Kelompok Harga <span style="color: #8B0000">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="epricegroup" id="epricegroup" class="form-control" value="<?php echo $isi->e_price_groupname; ?>">
                                            <input type="hidden" name="ipricegroup" id="ipricegroup" class="form-control" value="<?php echo $isi->i_price_group; ?>">
                                            <input type="hidden" name="nline" id="nline" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-12">Nilai Kotor</label>
                                        <div class="col-sm-12">
                                            <input type="text" id="vspb" name="vspb" class="form-control"="" readonly value="<?php echo number_format($norderspbbefore); ?>">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-6"> 
                                        <div class="form-group row">
                                            <label class="col-md-6">Discount 1</label><label class="col-md-6">Nilai Discount 1</label>
                                            <div class="col-sm-6">
                                            <input readonly id ="ncustomerdiscount1"name="ncustomerdiscount1"value="<?php echo $isispb->n_spb_discount1; ?>" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input id= "vcustomerdiscount1" name="vcustomerdiscount1" class="form-control"="" readonly value="<?php echo $isispb->v_spb_discount1; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-6">Discount 2</label><label class="col-md-6">Nilai Discount 2</label>
                                            <div class="col-sm-6">
                                                <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control"="" value="<?php echo $isispb->n_spb_discount2; ?>" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control"="" value="<?php echo $isispb->v_spb_discount2; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-6">Discount 3</label><label class="col-md-6">Nilai Discount 3</label>
                                            <div class="col-sm-6">
                                                <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control"="" value="<?php echo $isispb->n_spb_discount3; ?>" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control"=""
                                                value="<?php echo $isispb->v_spb_discount3; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-12">Discount Total</label>
                                            <div class="col-sm-12">
                                                <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control"="" 
                                                value="<?php echo number_format($isispb->v_spb_discounttotal); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-12">Nilai Bersih</label>
                                            <div class="col-sm-12">
                                                <input id="vspbbersih" name="vspbbersih" class="form-control"="" 
                                                value="<?php echo number_format($norderspbafter); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-12">Discount Total (Realisasi)</label>
                                            <div class="col-sm-12">
                                                <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control"="" 
                                                readonly value="0">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-12">Nilai SPB (Realisasi)</label>
                                            <div class="col-sm-12">
                                                <input id="vspbafter" name="vspbafter" class="form-control"="" 
                                                readonly value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body table-responsive">
                                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 4%;">No</th>
                                                    <th style="text-align: center; width: 10%;">Kode Barang</th>
                                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                                    <th style="text-align: center;">Motif</th>
                                                    <th style="text-align: center; width: 10%;">Harga</th>
                                                    <th style="text-align: center;">Qty Pesan</th>
                                                    <th style="text-align: center;">Qty Pemenuhan</th>
                                                    <th style="text-align: center;">Total</th>
                                                    <th style="text-align: center;">Ket</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php 				
									            $i=0;
									            foreach($isidetail as $row){
                                                    $i++;
										            $pangaos=number_format($row->v_unit_price);
										            $hrgnew=number_format($row->hrgnew,2);
										            $total=$row->v_unit_price*$row->n_order;
                                                    $total=number_format($total,2);
                                            ?>
                                            <tr>
                                                <td style="text-align: center;"><?=$i;?>
                                                    <input readonly type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                                    <input type="hidden"  class="form-control" id="motif<?=$i; ?>" name="motif<?=$i; ?>" value="<?php echo $row->i_product_motif; ?>">
                                                </td>
                                                <td> 
                                                    <input readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product; ?>">
                                                </td>
                                                <td> 
                                                    <input readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product_name; ?>">
                                                </td>
                                                <td> 
                                                    <input readonly  type="text" class="form-control" id="emotifname<?=$i; ?>" name="emotifname<?=$i; ?>" readonly value="<?php echo $row->e_product_motifname; ?>">
                                                </td>
                                                <td> 
                                                    <input readonly  type="text" class="form-control" id="vproductretail<?=$i; ?>" name="vproductretail<?=$i; ?>" value="<?php echo $pangaos; ?>">
                                                    <input readonly  type="hidden" class="form-control" id="hrgnew<?=$i; ?>" name="hrgnew<?=$i; ?>" value="<?php echo $hrgnew; ?>">
                                                </td>
                                                <td> 
                                                    <input align:center;" type="text" class="form-control" readonly id="norder<?=$i; ?>" name="norder<?=$i; ?>" value="<?php echo $row->n_order; ?>">
                                                </td>
                                                <td> 
                                                    <input align:center;" type="text" class="form-control" id="ndeliver<?=$i; ?>" name="ndeliver<?=$i; ?>" readonly value="<?php echo $row->n_deliver; ?>" onkeyup="pembandingnilai(<?php if(isset($i)) echo $i; ?>); sisa(<?php if(isset($i)) echo $i; ?>); hitungnilai(this.value); ">
                                                </td>
                                                <td> 
                                                    <input readonly type="text" class="form-control" id="vtotal<?=$i; ?>" name="vtotal<?=$i; ?>" value="<?php echo $total; ?>">
                                                </td>
                                                <td> 
                                                    <input readonly type="text" class="form-control" id="eremark<?=$i; ?>" name="eremark<?=$i; ?>" value="<?php echo $row->ket; ?>">
                                                </td>
                                            </tr>
                                            <?}?>
                                            </tbody>
                                        </table>
                                    </div>
								</div>
								<input type="hidden" name="jml" id="jml" value="<?= $i;?>">
	  					</div>
						</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
</form>
<script>
  (function() {
        [].slice.call(document.querySelectorAll('.sttabs')).forEach(function(el) {
            new CBPFWTabs(el);
        });
    })();

    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="iproductstatus'+xx+'" name="iproductstatus'+xx+'" value=""></td>';
        cols += '<td><select  type="text" id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getharga('+xx+');"><input type="hidden" id="iproductgroup'+xx+'" name="iproductgroup'+xx+'" value=""></td>';
        cols += '<td><input type="text" id="eproductname'+xx+'" type="text" class="form-control" name="eproductname'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="emotifname'+xx+'" type="text" class="form-control" name="emotifname'+xx+'" readonly></td>';
        cols += '<td><input type="text" id="vproductretail'+xx+'" class="form-control" name="vproductretail'+xx+'"/ readonly></td>';
        cols += '<td><input type="text" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event)" onkeyup="hitungnilai(this.value,'+xx+')" autocomplete="off"></td>';
        cols += '<td><input type="text" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" onkeyup="cekval(this.value); reformat(this);"/ readonly></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        /*$('#iproduct'+xx).select2({
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var kdharga     = $('#ipricegroup').val();
                    var query   = {
                        q       : params.term,
                        kdharga : kdharga
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
        });*/
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    $(document).ready(function () {
       // $('.select2').select2();
        showCalendar('.date');

        $('#chkcriterianew').click(function (event) {
            if (this.checked) {
                $("#chkcriteriaupdate").attr("checked", false);
            } else {             
                $("#chkcriteriaupdate").attr("checked", true);
            }
        });

        $('#chkcriteriaupdate').click(function (event) {
            if (this.checked) {
                $("#chkcriterianew").attr("checked", false);
            } else {             
                $("#chkcriterianew").attr("checked", true);
            }
        });

        $('#chkidemtoko1').click(function (event) {
            if (this.checked) {
                $("#ecustomerowneraddress").val($("#ecustomeraddress").val());
                $("#ert2").val($("#ert1").val());
                $("#erw2").val($("#erw1").val());
                $("#epostal2").val($("#epostal1").val());
                $("#ecustomerkelurahan2").val($("#ecustomerkelurahan1").val());
                $("#ecustomerkecamatan2").val($("#ecustomerkecamatan1").val());
                $("#ecustomerkota2").val($("#ecustomerkota1").val());
                $("#ecustomerprovinsi2").val($("#ecustomerprovinsi1").val());
                $("#ecustomerownerphone").val($("#ecustomerphone").val());
                $("#ecustomerownerfax").val($("#efax1").val());
            } else {             
                $("#ecustomerowneraddress").val('');
                $("#ert2").val('');
                $("#erw2").val('');
                $("#epostal2").val('');
                $("#ecustomerkelurahan2").val('');
                $("#ecustomerkecamatan2").val('');
                $("#ecustomerkota2").val('');
                $("#ecustomerprovinsi2").val('');
                $("#ecustomerownerphone").val('');
                $("#ecustomerownerfax").val('');
            }
        });

        $('#chkidemtoko2').click(function (event) {
            if (this.checked) {
                $("#chkidemtoko3").attr("checked", false);
                $("#ecustomersendaddress").val($("#ecustomeraddress").val());
                $("#ert3").val($("#ert1").val());
                $("#erw3").val($("#erw1").val());
                $("#epostal3").val($("#epostal1").val());
                $("#ecustomerkota3").val($("#ecustomerkota1").val());
                $("#ecustomerprovinsi3").val($("#ecustomerprovinsi1").val());
                $("#ecustomersendphone").val($("#ecustomerphone").val());
            } else {             
                $("#ecustomersendaddress").val('');
                $("#ert3").val('');
                $("#erw3").val('');
                $("#epostal3").val('');
                $("#ecustomerkota3").val('');
                $("#ecustomerprovinsi3").val('');
                $("#ecustomersendphone").val('');
            }
        });

        $('#chkidemtoko3').click(function (event) {
            if (this.checked) {
                $("#chkidemtoko2").attr("checked", false);
                $("#ecustomersendaddress").val($("#ecustomerowneraddress").val());
                $("#ert3").val($("#ert2").val());
                $("#erw3").val($("#erw2").val());
                $("#epostal3").val($("#epostal2").val());
                $("#ecustomerkota3").val($("#ecustomerkota2").val());
                $("#ecustomerprovinsi3").val($("#ecustomerprovinsi2").val());
                $("#ecustomersendphone").val($("#ecustomerownerphone").val());
            } else {             
                $("#ecustomersendaddress").val('');
                $("#ert3").val('');
                $("#erw3").val('');
                $("#epostal3").val('');
                $("#ecustomerkota3").val('');
                $("#ecustomerprovinsi3").val('');
                $("#ecustomersendphone").val('');
            }
        });

        /*$('#icity').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getkota/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea: iarea
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
        });*/

        /*$('#isalesman').select2({
            placeholder: 'Cari Berdasarkan Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea: iarea
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
        });*/

       /* $('#iarea').select2({
            placeholder: 'Pilih Area'
        });

        $('#iretensi').select2({
            placeholder: 'Pilih Retensi'
        });

        $('#icustomergroup').select2({
            placeholder: 'Pilih Customer Group'
        });*/
    });

function area(iarea) {
    var earea = $('#iarea option:selected').text();
    if (iarea!='') {
        $("#icity").attr("disabled", false);
        $("#isalesman").attr("disabled", false);
    }else{
        $("#icity").attr("disabled", true);
        $("#isalesman").attr("disabled", true);
    }
    $('#icity').html('');
    $('#isalesman').val('');
    $('#icity').html('');
    $('#isalesman').val('');
    $('#ecustomerprovinsi1').val(earea);
    $('#ecustomerprovinsi2').val(earea);
    $('#ecustomerprovinsi3').val(earea);
}

function sales(isalesman) {
    var esales = $('#isalesman option:selected').text();
    $('#esalesmanname').val(esales);
}

function getkhusus(iproducttype) {
    if (iproducttype!='') {
        $("#icustomerspecialproduct").attr("disabled", false);
    }else{
        $("#icustomerspecialproduct").attr("disabled", true);
    }

    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getcustomerspecialproduct');?>",
        data:"iproducttype="+iproducttype,
        dataType: 'json',
        success: function(data){
            $("#icustomerspecialproduct").html(data.kop);
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}

function retensi(iretensi) {
    if (iretensi!='') {
        if(iretensi=='00') $('#nvisitperiod').val('0');
        if(iretensi=='01') $('#nvisitperiod').val('20');
        if(iretensi=='02') $('#nvisitperiod').val('7');
        if(iretensi=='03') $('#nvisitperiod').val('3');
        if(iretensi=='04') $('#nvisitperiod').val('60');
        if(iretensi=='05') $('#nvisitperiod').val('14');
        if(iretensi=='06') $('#nvisitperiod').val('21');
        if(iretensi=='07') $('#nvisitperiod').val('120');
        if(iretensi=='08') $('#nvisitperiod').val('90');
        if(iretensi=='XX') $('#nvisitperiod').val('0');
    }else{
        $('#nvisitperiod').val('');
    }
}

function copydisc(){
    document.getElementById('ncustomerdiscount1').value=document.getElementById('ncustomerdiscount').value;
    $('#ncustomerdiscount1').val($('#ncustomerdiscount').val());
}

function disable(ipricegroup){
    if (ipricegroup!='') {
        $('#addrow').attr("disabled", false);
    }else{
        $('#addrow').attr("disabled", true);
    }
    $("#tabledata tr:gt(0)").remove();       
    $("#jml").val(0);
    xx = 0;
}

function getharga(id){
    ada=false;
    var a = $('#iproduct'+id).val();
    var e = $('#motif'+id).val();
    var x = $('#jml').val();
    for(i=1;i<=x;i++){            
        if((a == $('#iproduct'+i).val()) && (i!=x)){
            alert ("kode : "+a+" sudah ada !!!!!");            
            ada=true;            
            break;        
        }else{            
            ada=false;             
        }
    }
    if(!ada){
        var iproduct    = $('#iproduct'+id).val();
        var kdharga     = $('#ipricegroup').val();
        $.ajax({
            type: "post",
            data: {
                'iproduct'  : iproduct,
                'kdharga'   : kdharga
            },
            url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
            dataType: "json",
            success: function (data) {
                $('#eproductname'+id).val(data[0].nama);
                $('#vproductretail'+id).val(formatcemua(data[0].harga));
                $('#emotifname'+id).val(data[0].namamotif);
                $('#motif'+id).val(data[0].motif);
                $('#iproductstatus'+id).val(data[0].i_product_status);
                $('#iproductgroup'+id).val(data[0].i_product_group);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }else{
        $('#iproduct'+id).html('');
        $('#iproduct'+id).val('');
    }
}

function hitungnilai(isi,jml){
    jml=document.getElementById("jml").value;
    if (isNaN(parseFloat(isi))){
        alert("Input harus numerik");
    }else{
        dtmp1=parseFloat(formatulang(document.getElementById("ncustomerdiscount1").value));
        dtmp2=parseFloat(formatulang(document.getElementById("ncustomerdiscount2").value));
        dtmp3=parseFloat(formatulang(document.getElementById("ncustomerdiscount3").value));
        vdis1=0;
        vdis2=0;
        vdis3=0;
        vtot =0;
        for(i=1;i<=jml;i++){
            vhrg=formatulang(document.getElementById("vproductretail"+i).value);
            nqty=formatulang(document.getElementById("norder"+i).value);
            vhrg=parseFloat(vhrg)*parseFloat(nqty);
            vtot=vtot+vhrg;
            document.getElementById("vtotal"+i).value=formatcemua(vhrg);
        }
        vdis1=vdis1+((vtot*dtmp1)/100);
        vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
        vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
        document.getElementById("vcustomerdiscount1").value=formatcemua(vdis1);
        document.getElementById("vcustomerdiscount2").value=formatcemua(vdis2);
        document.getElementById("vcustomerdiscount3").value=formatcemua(vdis3);
        vdis1=parseFloat(vdis1);
        vdis2=parseFloat(vdis2);
        vdis3=parseFloat(vdis3);
        vtotdis=vdis1+vdis2+vdis3;
        vtotdis=Math.round(vtotdis);
        document.getElementById("vspbdiscounttotal").value=formatcemua(vtotdis);
        document.getElementById("vspb").value=formatcemua(vtot);
        vtotbersih=parseFloat(vtot)-parseFloat(vtotdis);
        document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
    }
}

function dipales(a){
    if (document.getElementById("iarea").value=='') {
        swal("Area Harus Diisi!");
        return false;
    }
    if (document.getElementById("isalesman").value=='') {
        swal("Salesman Harus Diisi!");
        return false;
    }
    if (document.getElementById("dsurvey").value=='') {
        swal("Tanggal Survey Harus Diisi!");
        return false;
    }
    if (document.getElementById("nvisitperiod").value=='') {
        swal("Periode Kunjungan Harus Diisi!");
        return false;
    }
    if (document.getElementById("icity").value=='') {
        swal("Kota Harus Dipilih!");
        return false;
    }
    /*if ((document.getElementById("chkcriterianew").value=='' || document.getElementById("chkcriterianew").value==null) || (document.getElementById("chkcriteriaupdate").value==''||document.getElementById("chkcriteriaupdate").value==null)) {
        swal("Kriteria Pelanggan Harus Dipilih!");
        return false;
    }*/
    if (document.getElementById("ecustomername").value=='') {
        swal("Nama Pelanggan/Toko Harus Diisi!");
        return false;
    }
    if (document.getElementById("ecustomerowner").value=='') {
        swal("Nama Pemilik Harus Diisi!");
        return false;
    }
    if (document.getElementById("icustomerclass").value=='') {
        swal("Tipe Pelanggan Harus Dipilih!");
        return false;
    }
    if (document.getElementById("ipaymentmethod").value=='') {
        swal("Metode Pembayaran Harus Dipilih!");
        return false;
    }
    if (document.getElementById("ncustomertoplength").value=='') {
        swal("TOP Harus Diisi!");
        return false;
    }
    if(document.getElementById('icustomergroup').value == ''){  
        swal('Group Pelanggan Belum Dipilih');
        return false;
    }
    if(document.getElementById('icustomerproducttype').value == ''){    
        swal('Produk Tipe Belum Dipilih');
        return false;
    }
    if(document.getElementById('icustomerspecialproduct').value == ''){ 
        swal('Produk Khusus Belum Dipilih');
        return false;
    }
    if(document.getElementById('icustomerstatus').value == ''){ 
        swal('Status Pelanggan Belum Dipilih');
        return false;
    }
    if(document.getElementById('icustomergrade').value == ''){  
        swal('Tingkat Pelanggan Belum Dipilih');
        return false;
    }
    if(document.getElementById('icustomerservice').value == ''){    
        swal('Jenis Pelanggan Belum Dipilih');
        return false;
    }
    if(document.getElementById('icustomersalestype').value == ''){  
        swal('Cara Penjualan Belum Dipilih');
        return false;
    }
    if(document.getElementById('ipricegroup').value == ''){ 
        swal('Kelompok Harga Belum Dipilih');
        return false;
    }
    if((document.getElementById("dspb").value!='') && 
        (document.getElementById("ecustomername").value!='') &&
        (document.getElementById("dsurvey").value!='') &&
        (document.getElementById("isalesman").value!='') &&
        (document.getElementById("ncustomertoplength").value!='') &&
        (document.getElementById("ncustomerdiscount").value!='') &&
        (document.getElementById("nvisitperiod").value!='') &&
        (document.getElementById("iarea").value!='') &&
        (document.getElementById("icustomergroup").value!='') &&
        (document.getElementById("icustomerproducttype").value!='') &&
        (document.getElementById("icustomerstatus").value!='') &&
        (document.getElementById("icustomergrade").value!='') &&
        (document.getElementById("icustomerservice").value!='') &&
        (document.getElementById("icustomersalestype").value!='') &&
        (document.getElementById("ipricegroup").value!='') &&
        (document.getElementById("ipaymentmethod").value!='') && 
        ((document.getElementById("ecustomernpwp").value=='' &&  document.getElementById("inik").value!='') || (document.getElementById("ecustomernpwp").value!=''))) {  
        if(a==0){
            swal("Isi data item minimal 1!");
            return false;
        }else{                
            for(i=1;i<=a;i++){                    
                if((document.getElementById("iproduct"+i).value=='') || 
                    (document.getElementById("eproductname"+i).value=='') || 
                    (document.getElementById("norder"+i).value=='')){
                    swal('Data item masih ada yang salah !!!');                    
                    return false;
                    cek='false';
                }else{
                    return true;
                    cek='true'; 
                } 
            }
        }
    }else{
        swal('Data header masih ada yang salah !!!');
        return false;
    }
}

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
});

function hanyaAngka(evt) {      
    var charCode = (evt.which) ? evt.which : event.keyCode      
    if (charCode > 31 && (charCode < 48 || charCode > 57))        
        return false;    
    return true;
}
</script>