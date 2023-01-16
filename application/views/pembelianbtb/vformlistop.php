<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> 
                <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                    class="fa fa-list"></i> &nbsp;<?= $title_list; ?></a>
                <?php } ?>
            </div>            
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/daftarop'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                            <div class="col-sm-5">
                                <input class="form-control date input-sm" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control date input-sm" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th width="2%;">No</th>
                            <th>No OP</th>
                            <th>Tgl OP</th>
                            <th>Bagian</th>                            
                            <th>Supplier</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah OP</th>
                            <th>Belum Di Terima</th>
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
        showCalendar('.date',null,0);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/dataop/<?= $dfrom.'/'.$dto;?>');
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