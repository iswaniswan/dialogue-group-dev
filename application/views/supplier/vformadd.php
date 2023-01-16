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
            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Supplier</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplier" class="form-control" required="" maxlength="5" onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Group Pemasok</label>
                        <div class="col-sm-12">
                        <select name="isuppliergroup" class="form-control select2">
                            <?php foreach ($supplier_group as $isuppliergroup):?>
                                <option value="<?php echo $isuppliergroup->i_supplier_group;?>"><?php echo $isuppliergroup->e_supplier_groupname;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Kota</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliercity" class="form-control" maxlength="30" onkeyup="gede(this)" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierphone" class="form-control" maxlength="30" onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Pemilik</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierownername" class="form-control" maxlength="30"  value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">NPWP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliernpwp" class="form-control" maxlength="30" onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kontak</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliercontact" class="form-control" maxlength="30"  value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Diskon 1</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierdiscount" class="form-control" maxlength="30" onkeyup="gede(this)" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">TOP</label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliertoplength" class="form-control" maxlength="30" onkeyup="gede(this)" value="">
                        </div>
                    </div>
            </div>

            <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Nama </label>
                        <div class="col-sm-12">
                            <input type="text" name="isuppliername" class="form-control" required=""   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplieraddres" class="form-control" required="" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Pos</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierpostalcode" class="form-control" maxlength="5" onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">FAX</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierfax" class="form-control" maxlength="30" onkeyup="gede(this)" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alamat Pemilik</label>
                        <div class="col-sm-12">
                        <input type="text" name="isupplierowneraddress" class="form-control"   value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Telepon 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierphone2" class="form-control" maxlength="30" onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Email</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplieremail" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Diskon 2</label>
                        <div class="col-sm-12">
                            <input type="text" name="isupplierdiscount2" class="form-control" maxlength="30" onkeyup="gede(this)" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">PKP   <div class="form-check"><input type="checkbox" class="form-check-input" name="isupplierpkp"></div> </label>
                        <label class="col-md-3">PPN  <div class="form-check"><input type="checkbox" class="form-check-input"  name="isupplierppn"></div> </label> 

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

