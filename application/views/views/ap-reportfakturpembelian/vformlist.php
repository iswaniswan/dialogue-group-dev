<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"></a>
            </div>
            
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group row">
                    <label class="col-md-6">Periode (Bulan / Tahun)</label><label class="col-md-6">Supplier</label>
                        <div class="col-sm-3">
                            <!--- <input type="hidden" id="iperiode" name="iperiode" value = "<#?= $periode;?>" readonly> -->
                            <select name="bulan" id="bulan" class="form-control select2" required="">
                            <option value="<?=$bulan;?>"><?=$namabulan ;?></option>
                            <option value='01'>Januari</option>
							<option value='02'>Pebruari</option>
							<option value='03'>Maret</option>
							<option value='04'>April</option>
							<option value='05'>Mei</option>
							<option value='06'>Juni</option>
							<option value='07'>Juli</option>
							<option value='08'>Agustus</option>
							<option value='09'>September</option>
							<option value='10'>Oktober</option>
							<option value='11'>November</option>
							<option value='12'>Desember</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-3">
                            <select name="tahun" id="tahun" class="form-control select2" required="">
                                <option value="<?=$tahun?>"><?=$tahun;?></option>
                                <?php 
                                    $tahun1 = date('Y')-3;
                                    $tahun2 = date('Y');
                                    for($i=$tahun1;$i<=$tahun2;$i++)
                                    {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <input type="hidden" name="esuppliername" id="esuppliername" readonly>
                            <select class="form-control select2" name ="isupplier" id = "isupplier" >
                                <?php foreach ($supplier AS $row):?>
                                    <option value = ""></option>
                                    <option value = "<?php echo $row->i_supplier;?>" <?php if($row->i_supplier == $isupplier){ echo 'selected'; } ?>> 
                                        <?php echo $row->e_supplier_name;?>
                                    </option>
                                <?php endforeach; ?>  
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="href" onclick="return validasi();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                        </div>
                    </div>
                </div>
                </form>
                
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>No Faktur</th>
                                <th>Tanggal Faktur</th> 
                                <th>Gross</th>
                                <th>Discount</th>            
                                <th>DPP</th>            
                                <th>PPN</th>            
                                <th>Netto</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align:right"></th>
                                <th style="text-align:right"></th>
                                <th style="text-align:right"></th>            
                                <th style="text-align:right"></th>
                                <th style="text-align:right"></th>
                                <th style="text-align:right"></th>
                                <th style="text-align:right"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $bulan."/".$tahun."/".$isupplier;?>');
        
        $('.select2').select2();

        $('#isupplier').select2({
            placeholder: 'Cari Supplier Berdasarkan Kode / Nama',
            allowClear: true,
        }).on("change", function (e) {
            var kode = $('#isupplier option:selected').text();
            //kode = kode.split("-");
            $('#esuppliername').val(kode);
        });

        $('#bulan').select2({
            placeholder: 'Pilih Bulan',
            allowClear: true,
        });

        $('#tahun').select2({
            placeholder: 'Pilih Tahun',
            allowClear: true,
        });

        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [
            { 
                "targets": [3,4,5], 
                "className": "text-right",
            }
            ],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $bulan."/".$tahun."/".$isupplier;?>",
                "type": "POST"
            },
            "displayLength": 10,
            
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var gross = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var disc = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var dpp = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );    
            
            var ppn = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            var netto = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
				
            // Update footer by showing the total with the reference of the column index 
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 3 ).footer() ).html(formatcemua(gross));
                $( api.column( 4 ).footer() ).html(formatcemua(disc));
                $( api.column( 5 ).footer() ).html(formatcemua([dpp]));
                $( api.column( 6 ).footer() ).html(formatcemua([ppn]));
                $( api.column( 7 ).footer() ).html(formatcemua([netto]));
            },
        } );
    });

    function validasi() {
        var bulan        = $('#bulan').val();
        var tahun        = $('#tahun').val();
        var isupplier    = $('#isupplier').val();

        if(isupplier == ''){
            isupplier = 'ALL';
        }

        if ( (bulan == '' && tahun == '') ||  (bulan == '' || tahun == '') ) {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+bulan+'/'+tahun+'/'+isupplier);
            return true;
        }
    }
</script>