<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view2/'.$dfrom.'/'.$isupplier), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Berlaku</label>
                        <label class="col-md-9">Supplier</label>
                        <div class="col-sm-3">
                            <input type="text" id="dberlaku" name="dberlaku" class="form-control input-sm date"  readonly value="<?=$dfrom;?>" placeholder="Tanggal Berlaku">
                        </div>
                        <div class="col-sm-6">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                                <?php if($isupplier == 'ALL'){?>
                                    <option value="ALL"> All Supplier </option>
                                <?php }else{?>
                                    <option value="<?=$isupplier;?>" selected="true"><?=$esupplier->e_supplier_name;?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="3%">No</th>
                            <th>Supplier</th>
                            <th>Kode Barang</th>
                            <th>Kode Material Supplier</th>
                            <th>Nama Barang</th>
                            <th>Satuan Barang</th>
                            <th>Jenis Barang</th>
                            <th>Harga</th>
                            <th>Tgl Berlaku</th>
                            <th>Tgl Berakhir</th>
                            <th>Status Dokumen</th>
                            <th>Status Aktif</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        //datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?=$dfrom;?>/<?=$isupplier;?>');
        datatablemoddate('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?=$dfrom;?>/<?=$isupplier;?>');

        $(".select2").select2();
        showCalendar2('.date');

        $('#isupplier').select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/supplierlist'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        });

        var option = '<option value="ALL">All Supplier</option>';
        $("#isupplier").append(option);
    });
</script>