<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view2'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Tgl Berlaku</label>
                        <label class="col-md-9">Jenis Makloon</label>
                        <div class="col-sm-3">
                            <input type="text" id="dberlaku" name="dberlaku" class="form-control date"  readonly value="<?=$dfrom;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="idtypemakloon" id="idtypemakloon" class="form-control select2">
                                <option value="<?=$idtypemakloon;?>"><?=$etypemakloon;?></option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Supplier</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th>Tanggal Berlaku</th>
                            <th>Tanggal Berakhir</th>
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
        $(".select2").select2();
        showCalendar2('.date');
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?=$dfrom;?>/<?=$idtypemakloon;?>');
    });

    $("#idtypemakloon").select2({
        placeholder: 'Pilih Jenis Makloon',
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder.'/cform/getmakloonlist'); ?>',
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
</script>