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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplier" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="<?= $data->i_supplier; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Group Pemasok</label>
                        <div class="col-sm-12">

<!--                             
                        <select name="isuppliergroup" class="form-control select2">
                            <?php foreach ($supplier_group as $isuppliergroup):?>
                                <option value="<?php echo $isuppliergroup->i_supplier_group;?>"><?php echo $isuppliergroup->e_supplier_groupname;?></option>
                            <?php endforeach; ?>
                        </select> -->

                <select name="isuppliergroup" class="form-control select2">
                   <option value="">Pilih Group Pemasok</option>
                   <?php foreach($supplier_group as $isuppliergroup): ?>
                   <option value="<?php echo $isuppliergroup->i_supplier_group;?>" <?php if($isuppliergroup->i_supplier_group==$data->i_supplier_group) { ?> selected="selected" <?php } ?>>
                    <?php echo $isuppliergroup->e_supplier_groupname;?></option>
                   <?php endforeach; ?> 
                </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliercity" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_city; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierphone" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_phone; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierownername" class="form-control" maxlength="30"  value="<?= $data->e_supplier_ownername; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">NPWP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliernpwp" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_npwp; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kontak</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliercontact" class="form-control" maxlength="30"  value="<?= $data->e_supplier_contact; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Diskon 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierdiscount" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_supplier_discount ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliertoplength" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_supplier_toplength; ?>">
                        </div>
                    </div>
            </div>

            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Nama </label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliername" class="form-control" required=""   value="<?= $data->e_supplier_name; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplieraddres" class="form-control" required="" maxlength="30" value="<?= $data->e_supplier_address; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Pos</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierpostalcode" class="form-control" maxlength="5" onkeyup="gede(this)" value="<?= $data->e_supplier_postalcode; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">FAX</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierfax" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_fax; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat Pemilik</label>
                        <div class="col-sm-12">
                        <input type="text" name="isupplierowneraddress" class="form-control"   value="<?= $data->e_supplier_owneraddress; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierphone2" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_supplier_phone2; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Email</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplieremail" class="form-control" maxlength="30" value="<?= $data->e_supplier_email; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Diskon 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierdiscount2" class="form-control" maxlength="30" onkeyup="gede(this)" value="<?= $data->n_supplier_discount2; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3">PPN 
                            <?php $check= $data->f_supplier_ppn;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked name=isupplierppn>";
                                } else {
                                   echo "<input type=checkbox name=isupplierppn>"; 
                                }
                            ?>
                        </label>
                        <label class="col-md-3">PKP
                            <?php $check= $data->f_supplier_pkp;
                                if ($check =='t'){
                                    echo "<input type=checkbox checked=checked name=isupplierpkp>";
                                } else {
                                   echo "<input type=checkbox name=isupplierpkp>"; 
                                }
                            ?>
                        </label>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
 

        </div>
    </div>
</div>

<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
 $(document).ready(function () {
    $(".select2").select2();
 });
</script>

