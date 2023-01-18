<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
            <?php } ?>
        </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-7">
                <div class="form-group row">
                    <label class="col-md-3">Berlaku Mulai</label><label class="col-md-3">Berlaku Sampai</label><label class="col-md-3">Status</label><label class="col-md-2"></label>
                    <div class="col-sm-3">
                        <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                    </div>
                    <div class="col-sm-3">
                        <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control" readonly="" type="text" name="fstatus" id="fstatus">
                            <option value="all" <?php if ($status=='all') { echo "selected";}?>>Semua</option>
                            <option value="t" <?php if ($status=='t') { echo "selected";}?>>Aktif</option>
                            <option value="f" <?php if ($status=='f') { echo "selected";}?>>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" id="submit" class="btn btn-info" onclick="return cekcari();"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                    </div>
                </div>
            </div>
        </form>
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Berlaku Mulai</th>
                    <th>Berlaku Sampai</th>
                    <th>Status</th>
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
        /**
         *Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
         */
        showCalendar2('.date',1830,0);
        /**
         * datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data');
         * @type {[type]}
         */
        var dfrom   = $('#dfrom').val();
        var dto     = $('#dto').val();
        var status  = $('#fstatus').val();
        $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [{ 
                "targets": [2], 
                "className": "text-right",
            },
            { 
                "targets": [3,4], 
                "className": "text-center",
            }],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/"+status+"/"+dfrom+"/"+dto,
                "type": "POST"
            },
            "displayLength": 10,
        });
    });

    $( "#dfrom" ).change(function() {
        var dfrom   = splitdate($(this).val());
        var dto     = splitdate($('#dto').val());
        if (dfrom!=null&& dto!=null) {
            if (dfrom>dto) {
                swal('Tanggal Berlaku Mulai Tidak Boleh Lebih Besar Dari Tanggal Berlaku Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $( "#dto" ).change(function() {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal('Tanggal Berlaku Sampai Tidak Boleh Lebih Kecil Dari Tanggal Berlaku Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    function cekcari() {
        if ($('#dfrom').val()!='' && $('#dto').val()=='') {
            swal('Tanggal Berlaku Sampai Harus Dipilih!!! ');
            return false;
        }else if($('#dfrom').val()=='' && $('#dto').val()!=''){
            swal('Tanggal Berlaku Mulai Harus Dipilih!!! ');
            return false;
        }else{
            return true;
        }
    }
</script>