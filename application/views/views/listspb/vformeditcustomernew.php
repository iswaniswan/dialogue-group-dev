<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/updatespbcustomernew'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                                        <div class="panel-heading"> <i class="fa fa-plus"></i> &nbsp;
                                            <?= "Detail Pelanggan Baru"; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
                                        </div>
                                        <div class="panel-wrapper collapse in" aria-expanded="true">
                                            <div class="panel-body">
                                                <div class="form-body">
                                                    <div class="form-group row has-error">
                                                        <label class="col-md-3">Area <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <select class="form-control select2" onchange="area(this.value);" disabled="true">
                                                                <option value=""></option>
                                                                <?php if ($area) {                                   
                                                                    foreach ($area as $iarea) { ?>
                                                                        <option value="<?= $iarea->i_area;?>" <?php if ($isi->i_area==$iarea->i_area) {echo "selected";}?>>
                                                                            <?= $iarea->e_area_name;?>
                                                                        </option>
                                                                    <?php }; 
                                                                } ?>
                                                            </select>
                                                            <input type="hidden" name="iarea" id="iarea" readonly value="<?= $isi->i_area;?>">
                                                        </div>
                                                        <label class="col-md-3">Sales <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <select name="isalesman" id="isalesman" class="form-control select2" onchange="sales(this.value);">
                                                                <option value="<?= $isi->i_salesman;?>">
                                                                    <?= $isi->e_salesman_name;?>
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="esalesmanname" id="esalesmanname" readonly>
                                                        </div>
                                                    </div>
                                                    <?php if($isi->d_survey!='') {
                                                        $dsurvey = date('d-m-Y', strtotime($isi->d_survey));
                                                    }else{
                                                        $dsurvey='';
                                                    }?>
                                                    <div class="form-group row">
                                                        <label class="col-md-3">Tanggal Survey <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <input readonly id="dsurvey" name="dsurvey" class="form-control date" value="<?= $dsurvey;?>">
                                                        </div>
                                                        <label class="col-md-3">Periode Kunjungan <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-1">
                                                            <input readonly id="nvisitperiod" name="nvisitperiod" class="form-control" value="<?= $isi->n_visit_period; ?>">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <select id="iretensi" name="iretensi" class="form-control select2" onchange="retensi(this.value);">
                                                                <option value=""></option>
                                                                <?php if ($retensi) {                 
                                                                    foreach ($retensi as $ire) { ?>
                                                                        <option value="<?= $ire->i_retensi;?>" <?php if ($isi->i_retensi==$ire->i_retensi) { echo "selected";}?>>
                                                                            <?= $ire->i_retensi." - ".ucwords(strtolower($ire->e_retensi));?>
                                                                        </option>
                                                                    <?php }; 
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-md-3">Kota <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <select name="icity" id="icity" class="form-control select2">
                                                                <option value="<?= $isi->i_city;?>">
                                                                    <?= $isi->e_city_name;?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <label class="col-md-3">Kriteria Pelanggan <span style="color: #8B0000">*</span></label>
                                                        <div class="col-sm-3">
                                                            <div class="form-check has-error">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkcriterianew" name="chkcriterianew" class="custom-control-input" <?php if($isi->f_customer_new=='t') {echo "checked";}?>>
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Pelanggan Baru / New</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkcriteriaupdate" name="chkcriteriaupdate" class="custom-control-input" <?php if($isi->f_customer_new=='f') {echo "checked";}?>>
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
                                                                    <input id="ecustomername" name="ecustomername" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_name; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Alamat Toko</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomeraddress" id="ecustomeraddress" value="<?= $isi->e_customer_address; ?>" maxlength='100' onkeyup="gede(this)">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Penanda Toko</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomersign" name="ecustomersign" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_sign; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">RT / RW / Kode Pos</label>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ert1" id="ert1" placeholder="RT" maxlength='2' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_rt1; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="erw1" id="erw1" placeholder="RW" maxlength='2' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_rw1; ?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" name="epostal1" id="epostal1" placeholder="Kode Pos" maxlength='5' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_postal1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Telepon</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerphone" name="ecustomerphone" class="form-control" maxlength="20" value="<?= $isi->e_customer_phone; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Fax</label>
                                                                <div class="col-md-8">
                                                                    <input id="efax1" name="efax1" class="form-control" maxlength="20" value="<?= $isi->e_fax1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Yang Dihubungi</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomercontact" name="ecustomercontact" maxlength="30" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_contact; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jabatan</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomercontactgrade" id="ecustomercontactgrade" value="" maxlength='30' onkeyup="gede(this)" value="<?= $isi->e_customer_contactgrade; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Mulai Usaha</label>
                                                                <div class="col-md-2">
                                                                    <input id="ecustomermonth" name="ecustomermonth" placeholder="Bulan" maxlength="2" class="form-control" onkeypress="return hanyaAngka(event);" value="<?= $isi->e_customer_month; ?>">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input id="ecustomeryear" name="ecustomeryear" placeholder="Tahun" maxlength="4" class="form-control" onkeypress="return hanyaAngka(event);" value="<?= $isi->e_customer_year; ?>">
                                                                </div>
                                                                <label class="control-label col-md-3">(Bulan / Tahun)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Tahun</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomerage" maxlength="4" id="ecustomerage" onkeypress="return hanyaAngka(event);" value="<?= $isi->e_customer_age; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Status Toko</label>
                                                                <div class="col-md-8">
                                                                    <select id="ishopstatus" name="ishopstatus" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($shop) {                 
                                                                            foreach ($shop as $shop) { ?>
                                                                                <option value="<?= $shop->i_shop_status;?>" <?php if ($isi->i_shop_status==$shop->i_shop_status) { echo "selected";}?>>
                                                                                    <?= $shop->e_shop_status;?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Luas Fisik Toko</label>
                                                                <div class="col-md-4">
                                                                    <input id="nshopbroad" name="nshopbroad" maxlength="9" class="form-control" onkeypress="return hanyaAngka(event);" value="<?= $isi->n_shop_broad; ?>">
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
                                                                    <input id="ecustomerkelurahan1" name="ecustomerkelurahan1" maxlength="30" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_kelurahan1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kecamatan</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomerkecamatan1" id="ecustomerkecamatan1" value="" maxlength='30' onkeyup="gede(this)" value="<?= $isi->e_customer_kecamatan1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kabupaten / Kodya</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerkota1" name="ecustomerkota1" maxlength="30" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_kota1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Provinsi</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomerprovinsi1" id="ecustomerprovinsi1" value="" maxlength='30' onkeyup="gede(this)" value="<?= $isi->e_customer_provinsi1; ?>">
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
                                                                    <input id="ecustomerowner" name="ecustomerowner" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_owner; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">TTL / Umur</label>
                                                                <div class="col-md-6">
                                                                    <input class="form-control" name="ecustomerownerttl" id="ecustomerownerttl" value="" maxlength='50' value="<?= $isi->e_customer_ownerttl; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ecustomerownerage" id="ecustomerownerage" placeholder="Umur" maxlength='3' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_customer_ownerage; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">NIK <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <input id="inik" name="inik" maxlength="20" class="form-control" onkeypress="return hanyaAngka(event);" value="<?= $isi->i_nik; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Status</label>
                                                                <div class="col-md-8">
                                                                    <select id="imarriage" name="imarriage" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($status) {                 
                                                                            foreach ($status as $status) { ?>
                                                                                <option value="<?= $status->i_marriage;?>" <?php if ($isi->i_marriage==$status->i_marriage) { echo "selected";}?>>
                                                                                    <?= $status->e_marriage;?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jenis Kelamin</label>
                                                                <div class="col-md-8">
                                                                    <select id="ijeniskelamin" name="ijeniskelamin" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($kelamin) {                 
                                                                            foreach ($kelamin as $kelamin) { ?>
                                                                                <option value="<?= $kelamin->i_jeniskelamin;?>" <?php if ($isi->i_jeniskelamin==$kelamin->i_jeniskelamin) { echo "selected";}?>>
                                                                                    <?= $kelamin->e_jeniskelamin;?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Agama</label>
                                                                <div class="col-md-8">
                                                                    <select id="ireligion" name="ireligion" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($agama) {                 
                                                                            foreach ($agama as $agama) { ?>
                                                                                <option value="<?= $agama->i_religion;?>" <?php if ($isi->i_religion==$agama->i_religion) { echo "selected";}?>>
                                                                                    <?= $agama->e_religion;?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
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
                                                                    <input id="ecustomerowneraddress" maxlength="200" name="ecustomerowneraddress" class="form-control" onkeyup="gede(this)">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">RT / RW / Kode Pos</label>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ert2" id="ert2" placeholder="RT" maxlength='3' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_rt2; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="erw2" id="erw2" placeholder="RW" maxlength='3' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_rw2; ?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" name="epostal2" id="epostal2" placeholder="Kode Pos" maxlength='5' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_postal2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Telepon / HP</label>
                                                                <div class="col-md-4">
                                                                    <input id="ecustomerownerphone" name="ecustomerownerphone" placeholder="Telepon" class="form-control" maxlength="15" value="<?= $isi->e_customer_ownerphone; ?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input id="ecustomerownerhp" name="ecustomerownerhp" placeholder="Hp" class="form-control" maxlength="15" value="<?= $isi->e_customer_ownerhp; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Fax / E-mail</label>
                                                                <div class="col-md-3">
                                                                    <input id="ecustomerownerfax" name="ecustomerownerfax" placeholder="Fax" class="form-control" maxlength="20" value="<?= $isi->e_customer_ownerfax; ?>">
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input id="ecustomermail" name="ecustomermail" placeholder="E-mail" class="form-control" maxlength="30" value="<?= $isi->e_customer_mail; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Nama Suami / Istri</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerownerpartner" name="ecustomerownerpartner" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_ownerpartner; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">TTL / Umur</label>
                                                                <div class="col-md-6">
                                                                    <input class="form-control" name="ecustomerownerpartnerttl" id="ecustomerownerpartnerttl" value="" maxlength='50' value="<?= $isi->e_customer_ownerpartnerttl; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ecustomerownerpartnerage" id="ecustomerownerpartnerage" placeholder="Umur" maxlength='3' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_customer_ownerpartnerage; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kelurahan</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerkelurahan2" name="ecustomerkelurahan2" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_kelurahan2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kecamatan</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomerkecamatan2" id="ecustomerkecamatan2" value="" maxlength='50' onkeyup="gede(this)" value="<?= $isi->e_customer_kecamatan2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kabupaten / Kodya</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerkota2" name="ecustomerkota2" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->i_city; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Provinsi</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomerprovinsi2" id="ecustomerprovinsi2" value="" maxlength='50' onkeyup="gede(this)" value="<?= $isi->e_customer_provinsi2; ?>">
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
                                                                    <input id="ecustomersendaddress" name="ecustomersendaddress" maxlength="200" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_sendaddress; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">RT / RW / Kode Pos</label>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ert3" id="ert3" placeholder="RT" maxlength='3' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_rt3; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="erw3" id="erw3" placeholder="RW" maxlength='3' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_rw3; ?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" name="epostal3" id="epostal3" placeholder="Kode Pos" maxlength='5' onkeypress="return hanyaAngka(event);" value="<?= $isi->e_postal3; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Telepon</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomersendphone" name="ecustomersendphone" class="form-control" maxlength="20" value="<?= $isi->e_customer_sendphone; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Lokasi Bisa Dilalui Oleh</label>
                                                                <div class="col-md-8">
                                                                    <select id="itraversed" name="itraversed" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($traversed) {                 
                                                                            foreach ($traversed as $traversed) { ?>
                                                                                <option value="<?= $traversed->i_traversed;?>" <?php if ($traversed->i_traversed==$isi->i_traversed) {echo "selected";}?>>
                                                                                    <?= $traversed->e_traversed;?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="control-label col-md-2"></label>
                                                            <div class="form-check">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="fparkir" name="fparkir" class="custom-control-input" <?php if($isi->f_parkir=='t') {echo 'checked';}?>>
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Ada Biaya Retribusi Parkir</span>
                                                                </label>
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="fkuli" name="fkuli" class="custom-control-input" <?php if($isi->f_kuli=='t') {echo 'checked';}?>>
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
                                                                    <input id="eekspedisi1" name="eekspedisi1" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_ekspedisi1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Ekspedisi Toko 2</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="eekspedisi2" id="eekspedisi2" onkeyup="gede(this);" maxlength='50' value="<?= $isi->e_ekspedisi2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Kabupaten / Kodya</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerkota3" name="ecustomerkota3" maxlength="50" class="form-control" onkeyup="gede(this);" value="<?= $isi->e_customer_kota3; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Provinsi</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomerprovinsi3" id="ecustomerprovinsi3" value="" maxlength='50' onkeyup="gede(this)" value="<?= $isi->e_customer_provinsi3; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">No NPWP <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomernpwp" name="ecustomernpwp" maxlength="16" class="form-control" onkeypress="return hanyaAngka(event);" onkeyup="gede(this);" value="<?= $isi->e_customer_pkpnpwp; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Nama NPWP</label>
                                                                <div class="col-md-8">
                                                                    <input class="form-control" name="ecustomernpwpname" id="ecustomernpwpname" value="" maxlength='50' onkeyup="gede(this)" value="<?= $isi->e_customer_npwpname; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-2">Alamat NPWP</label>
                                                                <div class="col-md-10">
                                                                    <input id="ecustomernpwpaddress" name="ecustomernpwpaddress" class="form-control" maxlength="200" onkeyup="gede(this);" value="<?= $isi->e_customer_npwpaddress; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h3 class="box-title">KUALIFIKASI PELANGGAN</h3>
                                                    <hr class="m-t-0 m-b-40">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Tipe Pelanggan <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomerclass" name="icustomerclass" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($class) {                 
                                                                            foreach ($class as $class) { ?>
                                                                                <option value="<?= $class->i_customer_class;?>" <?php if ($class->i_customer_class==$isi->i_customer_class) {echo "selected";}?>>
                                                                                    <?= strtoupper($class->e_customer_classname);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Pola Pembayaran <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="ipaymentmethod" name="ipaymentmethod" class="form-control">
                                                                        <?php if ($payment) {                 
                                                                            foreach ($payment as $payment) { ?>
                                                                                <option value="<?= $payment->i_paymentmethod;?>" <?php if ($payment->i_paymentmethod==$isi->i_paymentmethod) {echo "selected";}?>>
                                                                                    <?= strtoupper($payment->e_paymentmethod);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">I. Nama Bank</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerbank1" maxlength="25" name="ecustomerbank1" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_bank1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">No. A/C</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerbankaccount1" maxlength="25" name="ecustomerbankaccount1" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_bankaccount1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Atas Nama</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerbankname1" maxlength="50" name="ecustomerbankname1" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_bankname1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">II. Nama Bank</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerbank2" maxlength="25" name="ecustomerbank2" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_bank2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">No. A/C</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerbankaccount2" maxlength="25" name="ecustomerbankaccount2" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_bankaccount2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Atas Nama</label>
                                                                <div class="col-md-8">
                                                                    <input id="ecustomerbankname2" maxlength="50" name="ecustomerbankname2" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_customer_bankname2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-6">Nama Kompetitor 1</label>
                                                                <div class="col-md-6">
                                                                    <input id="ekompetitor1" name="ekompetitor1" maxlength="20" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_kompetitor1; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-6">Nama Kompetitor 2</label>
                                                                <div class="col-md-6">
                                                                    <input id="ekompetitor2" name="ekompetitor2" maxlength="20" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_kompetitor2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-6">Nama Kompetitor 3</label>
                                                                <div class="col-md-6">
                                                                    <input id="ekompetitor3" name="ekompetitor3" maxlength="20" class="form-control" onkeyup="gede(this)" value="<?= $isi->e_kompetitor3; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-7">TOP (Hari) <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-5">
                                                                    <input id="ncustomertoplength" name="ncustomertoplength" maxlength="3" placeholder="Hari" class="form-control" onkeypress="return hanyaAngka(event);" value="<?= $isi->n_spb_toplength; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-7">Discount (%) <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-5">
                                                                    <input id="ncustomerdiscount" name="ncustomerdiscount" maxlength="3" placeholder="%" class="form-control" onkeyup='copydisc()' onkeypress="return hanyaAngka(event);" value="<?= $isi->n_customer_discount; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-6">Waktu Untuk Menghubungi</label>
                                                                <div class="col-md-6">
                                                                    <select id="icall" name="icall" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($call) {                 
                                                                            foreach ($call as $call) { ?>
                                                                                <option value="<?= $call->i_call;?>" <?php if ($call->i_call==$isi->i_call) {echo "selected";}?>>
                                                                                    <?= strtoupper($call->e_call);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label class="control-label col-md-2"></label>
                                                            <div class="form-check">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" id="chkkontrabon" name="chkkontrabon" class="custom-control-input" <?php if($isi->f_kontrabon=='t') {echo 'checked';}?>>
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Kontra Bon</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jadwal Kontra Bon</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="ekontrabonhari" id="ekontrabonhari">
                                                                        <option value="<?= $isi->e_kontrabon_hari; ?>">
                                                                            <?= $isi->e_kontrabon_hari; ?>
                                                                        </option>
                                                                        <option value="SENIN" <?php if (strtoupper($isi->e_kontrabon_hari)=="SENIN") {echo "selected";}?>>SENIN</option>
                                                                        <option value="SELASA" <?php if (strtoupper($isi->e_kontrabon_hari)=="SELASA") {echo "selected";}?>>SELASA</option>
                                                                        <option value="RABU" <?php if (strtoupper($isi->e_kontrabon_hari)=="RABU") {echo "selected";}?>>RABU</option>
                                                                        <option value="KAMIS" <?php if (strtoupper($isi->e_kontrabon_hari)=="KAMIS") {echo "selected";}?>>KAMIS</option>
                                                                        <option value="JUM'AT" <?php if (strtoupper($isi->e_kontrabon_hari)=="JUM'AT") {echo "selected";}?>>JUM'AT</option>
                                                                        <option value="SABTU" <?php if (strtoupper($isi->e_kontrabon_hari)=="SABTU") {echo "selected";}?>>SABTU</option>
                                                                        <option value="MINGGU" <?php if (strtoupper($isi->e_kontrabon_hari)=="MINGGU") {echo "selected";}?>>MINGGU</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ekontrabonjam1" id="ekontrabonjam1" placeholder="Jam" maxlength='5' value="<?= $isi->e_kontrabon_jam1; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="ekontrabonjam2" id="ekontrabonjam2" placeholder="Jam" maxlength='5' value="<?= $isi->e_kontrabon_jam2; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jadwal Tagih</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="etagihhari" id="etagihhari">
                                                                        <option value="<?= $isi->e_tagih_hari; ?>">
                                                                            <?= $isi->e_tagih_hari; ?>
                                                                        </option>
                                                                        <option value="SENIN" <?php if (strtoupper($isi->e_tagih_hari)=="SENIN") {echo "selected";}?>>SENIN</option>
                                                                        <option value="SELASA" <?php if (strtoupper($isi->e_tagih_hari)=="SELASA") {echo "selected";}?>>SELASA</option>
                                                                        <option value="RABU" <?php if (strtoupper($isi->e_tagih_hari)=="RABU") {echo "selected";}?>>RABU</option>
                                                                        <option value="KAMIS" <?php if (strtoupper($isi->e_tagih_hari)=="KAMIS") {echo "selected";}?>>KAMIS</option>
                                                                        <option value="JUM'AT" <?php if (strtoupper($isi->e_tagih_hari)=="JUM'AT") {echo "selected";}?>>JUM'AT</option>
                                                                        <option value="SABTU" <?php if (strtoupper($isi->e_tagih_hari)=="SABTU") {echo "selected";}?>>SABTU</option>
                                                                        <option value="MINGGU" <?php if (strtoupper($isi->e_tagih_hari)=="MINGGU") {echo "selected";}?>>MINGGU</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="etagihjam1" id="etagihjam1" placeholder="Jam" maxlength='5' value="<?= $isi->e_tagih_jam1; ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input class="form-control" name="etagihjam2" id="etagihjam2" placeholder="Jam" maxlength='5' value="<?= $isi->e_tagih_jam2; ?>">
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
                                                                    <select id="icustomergroup" name="icustomergroup" class="form-control select2">
                                                                        <?php if ($customergroup) {                 
                                                                            foreach ($customergroup as $icgroup) { ?>
                                                                                <option value="<?= $icgroup->i_customer_group;?>" <?php if ($isi->i_customer_group==$icgroup->i_customer_group){echo "selected";} ?>>
                                                                                    <?= strtoupper($icgroup->e_customer_groupname);?>
                                                                                </option>
                                                                            <?php } 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">PLU Group</label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomerplugroup" name="icustomerplugroup" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($plu) {                 
                                                                            foreach ($plu as $plu) { ?>
                                                                                <option value="<?= $plu->i_customer_plugroup;?>" <?php if ($isi->i_customer_plugroup==$plu->i_customer_plugroup){echo "selected";} ?>>
                                                                                    <?= strtoupper($plu->e_customer_plugroupname);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Tipe Produk <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomerproducttype" name="icustomerproducttype" class="form-control" onchange="getkhusus(this.value);">
                                                                        <?php if ($customertype) {                 
                                                                            foreach ($customertype as $type) { ?>
                                                                                <option value="<?= $type->i_customer_producttype;?>" <?php if ($isi->i_customer_producttype==$type->i_customer_producttype){echo "selected";} ?>>
                                                                                    <?= strtoupper($type->e_customer_producttypename);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Produk Khusus <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomerspecialproduct" name="icustomerspecialproduct" class="form-control">
                                                                        <option value="<?= $isi->i_customer_specialproduct; ?>">
                                                                            <?= $isi->e_customer_specialproductname; ?>
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Status Pelanggan <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomerstatus" name="icustomerstatus" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($customerstatus) {                 
                                                                            foreach ($customerstatus as $status) { ?>
                                                                                <option value="<?= $status->i_customer_status;?>" <?php if ($status->i_customer_status==$isi->i_customer_status) { echo "selected";}?>>
                                                                                    <?= strtoupper($status->e_customer_statusname);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Tingkat Pelanggan <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomergrade" name="icustomergrade" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($customergrade) {                 
                                                                            foreach ($customergrade as $grade) { ?>
                                                                                <option value="<?= $grade->i_customer_grade;?>" <?php if ($grade->i_customer_grade==$isi->i_customer_grade) { echo "selected";}?>>
                                                                                    <?= strtoupper($grade->e_customer_gradename);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Jenis Pelanggan <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomerservice" name="icustomerservice" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($customerservice) {                 
                                                                            foreach ($customerservice as $service) { ?>
                                                                                <option value="<?= $service->i_customer_service;?>" <?php if ($service->i_customer_service==$isi->i_customer_service) { echo "selected";}?>>
                                                                                    <?= strtoupper($service->e_customer_servicename);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">Cara Penjualan <span style="color: #8B0000">*</span></label>
                                                                <div class="col-md-8">
                                                                    <select id="icustomersalestype" name="icustomersalestype" class="form-control">
                                                                        <option value=""></option>
                                                                        <?php if ($customersalestype) {                 
                                                                            foreach ($customersalestype as $st) { ?>
                                                                                <option value="<?= $st->i_customer_salestype;?>" <?php if ($st->i_customer_salestype==$isi->i_customer_salestype) { echo "selected";}?>>
                                                                                    <?= strtoupper($st->e_customer_salestypename);?>
                                                                                </option>
                                                                            <?php }; 
                                                                        } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <i class="fa fa-plus"></i> &nbsp;
                                            <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
                                        </div>
                                        <div class="panel-body table-responsive">
                                            <div class="col-md-6">
                                                <?php if($isispb->d_spb!='') {
                                                    $bl=date('m', strtotime($isispb->d_spb));
                                                    $dspb=date('d-m-Y', strtotime($isispb->d_spb));

                                                } else {
                                                    $dspb=date('d-m-Y');
                                                }?>
                                                <input hidden id="bspb" name="bspb" value="<?= $bl; ?>">
                                                <div class="form-group row">
                                                    <label class="col-md-6">Nomor SPB</label>
                                                    <label class="col-md-6">Tanggal SPB</label>
                                                    <div class="col-sm-6">
                                                        <input id="ispb" name="ispb" class="form-control" value="<?= $isispb->i_spb;?>" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input id="dspb" name="dspb" class="form-control date" value="<?= $dspb;?>" readonly onchange="cektanggal();">
                                                        <input id="iperiode" name="iperiode" type="hidden" value="">
                                                        <input id="dspbsys" name="dspbsys" type="hidden" value="">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">PO</label>
                                                    <div class="col-sm-12">
                                                        <input id="ispbpo" name="ispbpo" class="form-control" maxlength="10" value="<?= $isispb->i_spb_po; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-8">SPB Lama</label>
                                                    <div class="col-sm-8">
                                                        <input class="form-control" value="<?= $isispb->i_spb_old; ?>" id="ispbold" name="ispbold">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" id="fspbstockdaerah" name="fspbstockdaerah" class="custom-control-input" <?php if($isispb->f_spb_stockdaerah=='t') {echo 'checked';}?>>
                                                                <span class="custom-control-indicator"></span>
                                                                <span class="custom-control-description">Stock Daerah</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">PKP</label>
                                                    <div class="col-sm-12">
                                                        <input readonly id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" class="form-control" maxlength="30" value="<?php echo $isispb->e_customer_pkpnpwp;?>">
                                                        <input id="fspbplusppn" name="fspbplusppn" type="hidden" value="<?php echo $isispb->f_spb_plusppn;?>">
                                                        <input id="fspbplusdiscount" name="fspbplusdiscount" type="hidden" value="<?php echo $isispb->f_spb_plusdiscount;?>">
                                                        <input id="fspbpkp" name="fspbpkp" type="hidden" value="<?php echo $isispb->f_spb_pkp;?>">
                                                        <input id="fcustomerfirst" name="fcustomerfirst" type="hidden" value="on">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Keterangan</label>
                                                    <div class="col-sm-12">
                                                        <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control" value="<?= $isispb->emark1?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Kelompok Harga <span style="color: #8B0000">*</span></label>
                                                    <div class="col-sm-12">
                                                        <select name="ipricegroup" id="ipricegroup" class="form-control" onchange="disable(this.value);">
                                                            <option value="">Pilih Kelompok Harga</option>
                                                            <?php if ($pricegroup) {
                                                                foreach ($pricegroup as $key) { ?>
                                                                    <option value="<?= $key->i_price_group;?>" <?php if ($isispb->i_price_group==$key->i_price_group) { echo "selected";}?>>
                                                                        <?= $key->i_price_group." - ".$key->e_price_groupname;?>
                                                                    </option>
                                                                <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php 
                                                if($nilaiorderspb==$isispb->v_spb){
                                                    $norderspbbefore= $isispb->v_spb;
                                                    $disc1parsing   = explode(".",$isispb->n_spb_discount1,strlen($isispb->n_spb_discount1));
                                                    $disc1      = ($norderspbbefore * $disc1parsing[0])/100;
                                                    $disc1parsing2  = explode(".",$isispb->n_spb_discount2,strlen($isispb->n_spb_discount2));
                                                    $disc2      = ($norderspbbefore * $disc1parsing2[0])/100;
                                                    $disc1parsing3  = explode(".",$isispb->n_spb_discount3,strlen($isispb->n_spb_discount3));
                                                    $disc3      = ($norderspbbefore * $disc1parsing3[0])/100;           
                                                    $norderspbafter = ($isispb->v_spb - (($disc1+$disc2+$disc3)));
                                                }elseif($isispb->v_spb_after<$nilaiorderspb){
                                                    $norderspbbefore= $nilaiorderspb;
                                                    $disc1parsing   = explode(".",$isispb->n_spb_discount1,strlen($isispb->n_spb_discount1));
                                                    $disc1      = ($norderspbbefore * $disc1parsing[0])/100;
                                                    $disc1parsing2  = explode(".",$isispb->n_spb_discount2,strlen($isispb->n_spb_discount2));
                                                    $disc2      = ($norderspbbefore * $disc1parsing2[0])/100;
                                                    $disc1parsing3  = explode(".",$isispb->n_spb_discount3,strlen($isispb->n_spb_discount3));
                                                    $disc3      = ($norderspbbefore * $disc1parsing3[0])/100;
                                                    $norderspbafter = ($nilaiorderspb - (($disc1+$disc2+$disc3)));
                                                }else{
                                                    $norderspbbefore= $nilaiorderspb;
                                                    $disc1parsing   = explode(".",$isispb->n_spb_discount1,strlen($isispb->n_spb_discount1));
                                                    $disc1      = ($norderspbbefore * $disc1parsing[0])/100;
                                                    $disc1parsing2  = explode(".",$isispb->n_spb_discount2,strlen($isispb->n_spb_discount2));
                                                    $disc2      = ($norderspbbefore * $disc1parsing2[0])/100;
                                                    $disc1parsing3  = explode(".",$isispb->n_spb_discount3,strlen($isispb->n_spb_discount3));
                                                    $disc3      = ($norderspbbefore * $disc1parsing3[0])/100;
                                                    $norderspbafter = ($nilaiorderspb - (($disc1+$disc2+$disc3)));
                                                }
                                                ?>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Nilai Kotor</label>
                                                    <div class="col-sm-12">
                                                        <input id="vspb" name="vspb" class="form-control"="" readonly value="<?= number_format($norderspbbefore); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-offset-5 col-sm-8">
                                                        <?php if(check_role($i_menu, 3)){?>
                                                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                                                            &nbsp;&nbsp;
                                                        <?php } ?>
                                                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$xarea."/".$dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                                        &nbsp;&nbsp;
                                                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-6">Discount 1</label>
                                                    <label class="col-md-6">Nilai Discount 1</label>
                                                    <div class="col-sm-6">
                                                        <input id="ncustomerdiscount1" name="ncustomerdiscount1" class="form-control"="" readonly value="<?php echo $isispb->n_spb_discount1; ?>">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input id="vcustomerdiscount1" name="vcustomerdiscount1" class="form-control"="" readonly value="<?php echo $isispb->v_spb_discount1; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-6">Discount 2</label>
                                                    <label class="col-md-6">Nilai Discount 2</label>
                                                    <div class="col-sm-6">
                                                        <input id="ncustomerdiscount2" name="ncustomerdiscount2" class="form-control"="" readonly value="<?php echo $isispb->n_spb_discount2; ?>">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input id="vcustomerdiscount2" name="vcustomerdiscount2" class="form-control"="" readonly value="<?php echo $isispb->v_spb_discount2; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-6">Discount 3</label>
                                                    <label class="col-md-6">Nilai Discount 3</label>
                                                    <div class="col-sm-6">
                                                        <input id="ncustomerdiscount3" name="ncustomerdiscount3" class="form-control"="" readonly value="<?php echo $isispb->n_spb_discount3; ?>">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input id="vcustomerdiscount3" name="vcustomerdiscount3" class="form-control"="" readonly value="<?php echo $isispb->v_spb_discount3; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Discount Total</label>
                                                    <div class="col-sm-12">
                                                        <input readonly id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control"="" value="<?php echo number_format($isispb->v_spb_discounttotal); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Nilai Bersih</label>
                                                    <div class="col-sm-12">
                                                        <input id="vspbbersih" name="vspbbersih" class="form-control"="" readonly value="<?= number_format($norderspbafter); ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Discount Total (Realisasi)</label>
                                                    <div class="col-sm-12">
                                                        <input id="vspbdiscounttotalafter" name="vspbdiscounttotalafter" class="form-control" readonly value="0">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-md-12">Nilai SPB (Realisasi)</label>
                                                    <div class="col-sm-12">
                                                        <input id="vspbafter" name="vspbafter" class="form-control"="" readonly value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <input type="hidden" name="jml" id="jml" value="0"> -->
                                            <div class="panel-body table-responsive">
                                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center; width: 4%;">No</th>
                                                            <th style="text-align: center; width: 10%;">Kode Barang</th>
                                                            <th style="text-align: center; width: 30%;">Nama Barang</th>
                                                            <th style="text-align: center;">Motif</th>
                                                            <th style="text-align: center;">Harga</th>
                                                            <th style="text-align: center;">Qty Pesan</th>
                                                            <th style="text-align: center;">Qty Omnhn</th>
                                                            <th style="text-align: center;">Total</th>
                                                            <th style="text-align: center;">Keterangan</th>
                                                            <th style="text-align: center;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php               
                                                        $i=0;
                                                        if ($isidetail) {
                                                            foreach($isidetail as $row){   
                                                                $i++;
                                                                $pangaos=number_format($row->v_unit_price,2);
                                                                $hrgnew=number_format($row->hrgnew,2);
                                                                $total=$row->v_unit_price*$row->n_order;
                                                                $total=number_format($total,2);
                                                                ?>
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        <spanx id="snum<?= $i;?>">
                                                                            <?=$i;?>
                                                                        </spanx>
                                                                        <input style="font-size: 12px;" type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                                        <input style="font-size: 12px;" class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                                                        <input style="font-size: 12px;" class="form-control" type="hidden" id="iproductstatus<?= $i;?>" name="iproductstatus<?= $i;?>" value="<?= $row->i_product_status;?>">
                                                                        <input style="font-size: 12px;" class="form-control" type="hidden" id="iproductgroup<?= $i;?>" name="iproductgroup<?= $i;?>" value="<?= $row->i_product_group;?>">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" readonly id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" readonly id="vproductretail<?= $i;?>" name="vproductretail<?= $i;?>" value="<?= $pangaos;?>">
                                                                        <input style="font-size: 12px;" class="form-control" type="hidden" id="hrgnew<?= $i;?>" name="hrgnew<?= $i;?>" value="<?= $hrgnew;?>">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>" value="<?= $row->n_order;?>" onkeyup="hitungnilai(this.value)">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_deliver;?>" onkeyup="hitungnilai(this.value)">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" readonly id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $total;?>">
                                                                    </td>
                                                                    <td>
                                                                        <input style="font-size: 12px;" class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->ket;?>">
                                                                    </td>
                                                                    <td align="center">
                                                                        <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                    </td>
                                                                </tr>
                                                            <?php }
                                                        }?>
                                                    </div>
                                                    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                                                </tbody>
                                            </table>
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

    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        count=$('#tabledata tr').length;
        if(count<=22){
            $('#jml').val(xx);
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="iproductstatus'+xx+'" name="iproductstatus'+xx+'" value=""></td>';
            cols += '<td><select  id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getharga('+xx+');"><input type="hidden" id="iproductgroup'+xx+'" name="iproductgroup'+xx+'" value=""></td>';
            cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" readonly></td>';
            cols += '<td><input id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'" readonly></td>';
            cols += '<td><input id="vproductretail'+xx+'" class="form-control" name="vproductretail'+xx+'"/ readonly></td>';
            cols += '<td><input id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event)" onkeyup="hitungnilai(this.value)" autocomplete="off"></td>';
            cols += '<td><input id="ndeliver'+xx+'" class="form-control" name="ndeliver'+xx+'" onkeypress="return hanyaAngka(event)" onkeyup="hitungnilai(this.value)" autocomplete="off"></td>';
            cols += '<td><input id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" onkeyup="cekval(this.value); reformat(this);"/ readonly></td>';
            cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#iproduct'+xx).select2({
                placeholder: 'Cari Kode / Nama',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/databrg/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var kdharga = $('#ipricegroup').val();
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
            });
        }else{
            swal("Maksimal 22 Item");
            /*$('#jml').val(xx-1);*/
        }
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        /*$('#jml').val(xx);*/
        del();
        /*ngetang();*/
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    $(document).ready(function () {
        $('.select2').select2();
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

        $('#icity').select2({
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
        });

        $('#isalesman').select2({
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
        });

        /*$('#iarea').select2({
            placeholder: 'Pilih Area'
        });*/

        $('#iretensi').select2({
            placeholder: 'Pilih Retensi'
        });

        $('#icustomergroup').select2({
            placeholder: 'Pilih Customer Group'
        });
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
        url: "<?= site_url($folder.'/Cform/getcustomerspecialproduct');?>",
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

function cektanggal() {
    var dspb = $('#dspb').val();
    var bspb = $('#bspb').val();
    dtmp=dspb.split('-');
    per=dtmp[2]+dtmp[1]+dtmp[0];
    bln = dtmp[1];
    if( (bspb!='') && (dspb!='') ){
        if(bspb != bln){
            swal("Tanggal SPB tidak boleh dalam bulan yang berbeda !!!");
            $("#dspb").val('');
        }
    }
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

function hitungnilai(isi){
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
    if (document.getElementById("chkcriterianew").value=='' || document.getElementById("chkcriteriaupdate").value=='') {
        swal("Kriteria Pelanggan Harus Dipilih!");
        return false;
    }
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