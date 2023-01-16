<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-3">Tanggal Daftar</label>
                    <label class="col-md-3">Kode Barang</label>
                    <label class="col-md-6">Nama Barang</label>
                    <div class="col-sm-2">
                        <input class="form-control" type="text" name="dregister" id="dregister" value = "<?=$data->d_register;?>" placeholder = "Tanggal Daftar" readonly>
                        <input class="form-control" type="hidden" name="id" id="id" value = "<?=$data->id;?>" readonly>
                    </div><div class="col-sm-1"></div>  
                    <div class="col-sm-3">
                        <input type="text" name="ikodebrg" id="ikodebrg" class="form-control" onkeyup="gede(this);" value="<?=$data->i_material;?>" placeholder="Kode Barang" readonly>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="enamabrg" class="form-control" onkeyup="gede(this)" value="<?=$data->e_material_name;?>" placeholder="Nama Barang" readonly>     
                    </div>   
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Group Barang</label>     
                    <label class="col-md-3">Kategori Barang</label>
                    <label class="col-md-3">Sub Kategori Barang</label>
                    <label class="col-md-3">Divisi</label>
                    <div class="col-sm-3">
                        <select name="igroupbrg" id="igroupbrg" class="form-control select2" disabled="">
                            <option value="<?=$data->i_kode_group_barang;?>"><?=$data->e_nama_group_barang;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ikategori" id="ikategori" class="form-control select2" disabled="">
                            <option value="<?=$data->i_kode_kelompok;?>"><?=$data->e_nama_kelompok;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ijenisbrg" id="ijenisbrg" class="form-control select2" disabled="">
                            <option value="<?=$data->i_type_code;?>"><?=$data->e_type_name;?></option>
                        </select>
                    </div> 
                    <div class="col-sm-3">
                        <select name="idivisi" id="idivisi" class="form-control select2" disabled="">
                            <option value="<?=$data->i_divisi;?>"><?=$data->e_nama_divisi;?></option>
                        </select>
                    </div> 
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Satuan Barang</label>
                    <label class="col-md-1">Panjang</label>
                    <label class="col-md-1">Lebar</label>
                    <label class="col-md-2">Tinggi</label>
                    <label class="col-md-5">Berat</label>
                    <div class="col-sm-3">
                        <select name="isatuan" id="isatuan" class="form-control select2" disabled="">
                            <option value="<?=$data->i_satuan_code?>"><?=$data->e_satuan_barang;?></option>
                        </select>
                    </div>   
                    <div class="col-sm-1">
                        <input type="text" name="npanjang" class="form-control" maxlength="20"  value="<?=$data->n_panjang;?>" placeholder="0" readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="nlebar" class="form-control" maxlength="20"  value="<?=$data->n_lebar;?>" placeholder="0" readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="ntinggi" class="form-control" maxlength="20"  value="<?=$data->n_tinggi;?>" placeholder="0" readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="isatuanukuran" id="isatuanukuran" class="form-control" value = "CM" readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="nberat" class="form-control" maxlength="30"  value="<?=$data->n_berat;?>" placeholder="0" readonly>
                    </div>
                    <div class="col-sm-1">
                        <input type = "hidden" class="form-control" value = "GR" name="isatuanberat" id="isatuanberat" readonly>
                        <input type = "text" class="form-control" value = "Gram" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Status Produksi</label>
                    <label class="col-md-3">Style Barang</label>
                    <label class="col-md-3">Brand Barang</label>
                    <label class="col-md-3">Supplier Utama</label>
                    <div class="col-sm-3">
                        <select name="istatusproduksi" id="istatusproduksi" class="form-control select2" disabled="">
                            <option value="<?=$data->i_status_produksi;?>"><?=$data->e_status_produksi;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="istyle" id="istyle" class="form-control select2" disabled="">
                            <option value="<?=$data->i_style;?>"><?=$data->e_style_name;?></option>
                        </select>
                    </div>    
                    <div class="col-sm-3">
                        <select name="ibrand" id="ibrand" class="form-control select2" disabled="">
                            <option value="<?=$data->i_brand;?>"><?=$data->e_brand_name;?></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                       <select name="isupplier" id="isupplier" class="form-control select2" disabled="">
                            <option value="<?=$data->i_supplier;?>"><?=$data->e_supplier_name;?></option>
                       </select>        
                    </div>                     
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea name="edeskripsi" id="edeskripsi" class="form-control" placeholder="Keterangan" readonly><?=$data->e_remark;?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-8 col-sm-20">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                    </div>
                </div>
            </div>
            </form>
        </div>
        </div>
    </div>
</div>

<div class="white-box" id="detailbis">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Ukuran Bis-Bisan Material</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledataxbis" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th valign="center" class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:8%;">Ukuran Bisbisan</th>
                        <th class="text-center" style="width:8%;">Lebar kain</th>
                        <th class="text-center" style="width:12%;">Jenis Potong</th>
                        <th class="text-center" style="width:10%;">% Hilang <br>Lebar Kain</th>
                        <th class="text-center" style="width:15%;">Lebar Kain Jadi</th>
                        <th class="text-center" style="width:10%;">Jml Roll</th>
                        <th class="text-center" style="width:12%;">% Tambah <br>Panjang Kain</th>
                        <th class="text-center" style="width:10%;">Panjang Bisbisan</th>
                        <th class="text-center" style="width:15%;">Panjang Bisbisan per 1m</th>
                    </tr>
                </thead>
                <tbody>
                     <?php $i = 0; if($bisbisan->num_rows()>0){
                        foreach($bisbisan->result() AS $key){ $i++; ?>
                            <tr>
                                <td style="text-align: center;">
                                    <spanx id="snum<?= $i;?>"><?= $i;?></spanx>
                                    <input readonly type="hidden" id="id_bisbisan'<?= $i;?>'" class="form-control text-center input-sm inputitem" autocomplete="off" name="id_bisbisan'<?= $i;?>'" value="<?= $key->id; ?>">
                                </td>

                                <td><input  readonly type="text" id="n_bisbisan<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_bisbisan<?= $i; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_bisbisan; ?>" onkeyup="angkahungkul(this);hitungbis('<?= $i;?>');"></td>
                                
                                <td><input readonly type="text" id="v_lebar_kain_awal<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_lebar_kain_awal<?= $i; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->v_lebar_kain_awal; ?>" onkeyup="angkahungkul(this);hitungbis('<?= $i;?>');"></td>

                                <td>
                                    <select disabled required data-nourut="<?= $i; ?>" id="id_jenis_potong<?= $i; ?>" class="form-control input-sm" name="id_jenis_potong<?= $i; ?>">
                                        <option value="<?= $key->id_jenis_potong; ?>" selected><?= $key->e_jenis_potong; ?></option>
                                    </select>
                                </td>

                                <td><input readonly type="text" id="n_hilang_lebar<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_hilang_lebar<?= $i; ?>" value="<?= $key->n_hilang_lebar; ?>" ></td>

                                <td><input readonly type="text" id="v_lebar_kain_akhir<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_lebar_kain_akhir<?= $i; ?>" value="<?= $key->v_lebar_kain_akhir; ?>" ></td>

                                <td><input readonly type="text" id="v_jumlah_roll<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_jumlah_roll<?= $i; ?>" value="<?= $key->v_jumlah_roll; ?>" ></td>

                                <td><input readonly type="text" id="n_tambah_panjang<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_tambah_panjang<?= $i; ?>" value="<?= $key->n_tambah_panjang; ?>" ></td>

                                <td><input readonly type="text" id="n_panjang_bis<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="n_panjang_bis<?= $i; ?>" value="<?= $key->n_panjang_bis; ?>" ></td>

                                <td><input readonly type="text" id="v_panjang_bis<?= $i; ?>" class="form-control text-center input-sm inputitem" autocomplete="off" name="v_panjang_bis<?= $i; ?>" value="<?= $key->v_panjang_bis; ?>" ></td>

                            </tr>
                        <?php }
                    }?>
                </tbody>
            </table>
            <input type="hidden" name="jmlbis" id="jmlbis" value="<?= $i;?>">
        </div>
    </div>
</div>

<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Konversi Material</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%;">No</th>
                        <th class="text-center" style="width:15%;">Satuan</th>
                        <th class="text-center" style="width:20%;">Operator</th>
                        <th class="text-center" style="width:15%;">Faktor</th>
                        <th class="text-center" style="width:20%;">Konversi</th>
                        <th class="text-center" style="width:5%;">Default</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; if($detail->num_rows()>0){
                        foreach($detail->result() AS $key){ $i++; ?>
                            <tr>
                                <td style="text-align: center;">
                                    <spanx id="snum<?= $i;?>"><?= $i;?></spanx>
                                </td>
                                <td>
                                    <input type="hidden" id="isatuanmaterial<?= $i;?>" name="isatuanmaterial<?= $i;?>" class="form-control" value="<?=$data->i_satuan_code; ?>">
                                    <span class="xspan"><?=$data->e_satuan_barang; ?></span>
                                </td>
                                <td>
                                    <select id="eperator<?= $i;?>" disabled class="form-control input-sm" name="eperator<?= $i;?>">
                                        <option value=""></option>
                                        <option value="*" <?php if($key->e_operator=='*'){echo "selected";}?>>Kali (*)</option>
                                        <option value="/" <?php if($key->e_operator=='/'){echo "selected";}?>>Bagi (/)</option>
                                        <option value="+" <?php if($key->e_operator=='+'){echo "selected";}?>>Tambah (+)</option>
                                        <option value="-" <?php if($key->e_operator=='-'){echo "selected";}?>>Kurang (-)</option>
                                    </select>
                                </td>
                                <td>
                                    <input value="<?= $key->n_faktor;?>" readonly type="text" id="faktor<?= $i;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="faktor<?= $i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);">
                                </td>
                                <td>
                                    <select data-nourut="<?= $i;?>" disabled id="isatuankonversi<?= $i;?>" class="form-control input-sm" name="isatuankonversi<?= $i;?>">
                                        <option value="<?= $key->i_satuan_code_konversi;?>"><?= $key->e_satuan_name;?></option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="hidden" id="default<?= $i;?>" name="default<?= $i;?>" value="<?php if($key->f_default=='t'){echo "t";}else{ echo "f";}?>">
                                    <input type="radio" class="cekdefault" disabled name="cekdefault" id="cekdefault<?= $i;?>" onclick="cek();" <?php if($key->f_default=='t'){echo "checked";}?>>
                                </td>
                            </tr>
                        <?php }
                    }?>
                </tbody>
            </table>
            <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date',1835,30);
    for (let i = 1; i <= $('#jml').val(); i++) {
        $('#eperator' + i).select2({
            placeholder: 'Pilih Operator Hitungan',
            width:"100%",
        });
        $('#isatuankonversi' + i).select2({
            placeholder: 'Cari Satuan Konversi',
            allowClear: true,
            width:"100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder . '/cform/get_satuan_konversi/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            /**
             * Cek Sudah Ada
             */
            var ada = true;
            var z = $(this).data('nourut');
            for (var x = 1; x <= $('#jml').val(); x++) {
                if ($(this).val() != null) {
                    if ((($(this).val()) == $('#isatuankonversi' + x).val()) && (z != x)) {
                        swal("Satuan tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {
                $(this).val('');
                $(this).html('');
            }
        });
    }
});
</script>
