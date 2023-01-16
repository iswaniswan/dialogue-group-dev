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
                <div class="col-md-10">
                        <div class="form-group row">
                            <label class="col-md-2">Date From</label>
                            <label class="col-md-2">Date To</label>
                            <label class="col-md-8">Gudang</label>
                            <div class="col-sm-2">
                                <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                            </div>
                            <div class="col-sm-2">
                                <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                            </div>
                            <div class="col-sm-4">
                            <select name="gudang" id="gudang" class="form-control select2">
                                <?php foreach ($gudang as $gudang):?>
                                <option value="<?php echo $gudang->i_kode_master;?>">
                                    <?= $gudang->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select>
                             <input class="form-control" readonly="" type="hidden" name="igudang" id="igudang" value="<?= $igudang;?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No PP</th>
                            <th>Tanggal PP</th>
                            <th>No OP</th>
                            <th>Supplier</th>
                            <!-- <th>Kode Material</th>
                            <th>Nama Material</th>
                            <th>Satuan</th>
                            <th>Qty PP</th>
                            <th>Qty OP</th> -->
                            <th>% Realisasi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>                    
                    </tbody>
                </table>
                <br>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        showCalendar('.date',1830,0);
        $(".select2").select2();
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $igudang."/".$dfrom.'/'.$dto;?>');
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

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>