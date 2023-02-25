<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            <?php if(check_role($this->i_menu, 2)){ ?>
                <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right">
                    <i class="fa fa-list"></i> &nbsp;list <?= $title; ?>
                </a>
            <?php } ?>
        </div>            
        <div class="panel-body table-responsive">
            <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/indexx'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                    <div class="col-sm-5">
                        <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                    </div>
                    <div class="col-sm-5">
                        <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" id="submit" class="btn btn-info btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                    </div>
                </div>
            </div>
        </form>
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="3%;">No</th>
                    <th>Tgl. Dokumen</th>
                    <th>CoA</th>
                    <th>No. Dokumen</th>
                    <th>Jumlah</th>
                    <th>Sisa</th>
                    <th>Keterangan</th>
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
<script type="text/javascript">
    $(document).ready(function () {
        showCalendar('.date',null,0);
        datatableedit('#tabledata', base_url + '<?= $folder; ?>/Cform/get_data_referensi/<?= $dfrom.'/'.$dto;?>',6,7);
    });

    $( "#dfrom" ).change(function() {
        var dfrom   = splitdate($(this).val());
        var dto     = splitdate($('#dto').val());
        if (dfrom!=null&& dto!=null) {
            if (dfrom>dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $( "#dto" ).change(function() {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });
</script>
