<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-spin fa-refresh"></i> &nbsp;Refresh</a>
        </div>            
        <div class="panel-body table-responsive">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-4">
                <div class="form-group row">
                    <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                    <div class="col-sm-5">
                        <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                    </div>
                    <div class="col-sm-5">
                        <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
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
                    <th>No Schedule</th>
                    <th>No SPBB</th>
                    <th>Kode Product</th>
                    <th>Nama Product</th>
                    <th>Qty Schedule</th>
                    <th>Qty SPBB</th>
                    <th>Qty Hasil Cut</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Export</button>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            'columnDefs': [
            {
                "targets": 0,
                "className": "text-center",
                "width": "2%"
            },
            {
                "targets": [5,6,7,8],
                "className": "text-right",
            }],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
        /*datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom.'/'.$dto;?>');*/
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

    function cekcari() {
        if ($('#dfrom').val()!='' && $('#dto').val()=='') {
            swal('Tanggal Sampai Harus Dipilih!!! ');
            return false;
        }else if($('#dfrom').val()=='' && $('#dto').val()!=''){
            swal('Tanggal Mulai Harus Dipilih!!! ');
            return false;
        }else{
            return true;
        }
    }
</script>