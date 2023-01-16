<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/<?= $dfrom.'/'.$dto ;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
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
                            <th>Nomor Dokumen</th>
                            <th>Tanggal Dokumen</th>
                            <th>No Schedule</th>
                            <th>Tanggal Schedule</th>
                            <th>Tujuan</th>
                            <th>Keterangan</th>
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
        showCalendar2('.date',1835,30);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom.'/'.$dto;?>');
        // var t = $('#tabledata').DataTable( {
        //             "ajax": {
        //                 "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom.'/'.$dto ;?>",
        //                 "type": "POST"
        //             },
        //             // dom: 'Bfrtip',
        //             // buttons: [
        //             //     'copy', 'csv', 'excel'
        //             // ],
        //             "columnDefs": [ {
        //                 "searchable": false,
        //                 "orderable": false,
        //                 "targets": [0], 
        //                 "className": "text-center",
        //             }],
        //             "order": [[ 1, "desc" ]]
        //         } );

        // t.on( 'order.dt search.dt', function () {
        //     t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         cell.innerHTML = i+1;
        //     } );
        // }).draw();
    });

    function hapus(folder,id,istatus) {
        $.ajax({
            type: "post",
            data: {
                'id' : id,
                'istatus' : istatus
            },
            url: folder+'/cform/hapus',
            dataType: "json",
            success: function (data) {
                if(data == 2){
                    swal({   
                        title: "Gagal!",   
                        text: "Data Gagal dihapus karena sudah selesai proses !",   
                        /*timer: 3000,   */
                        showConfirmButton: true,
                        type: "error",
                    },function(){
                        show(folder+'/cform','#main');
                    });
                }else{
                    swal({   
                        title: "Berhasil!",   
                        text: "Data Berhasil dihapus",   
                        /*timer: 3000,   */
                        showConfirmButton: true,
                        type: "success",
                    },function(){
                        show(folder+'/cform','#main');
                    });
                }
            },
            error: function () {
                show(folder+'/cform','#main');   
            }
        });
    }
</script>