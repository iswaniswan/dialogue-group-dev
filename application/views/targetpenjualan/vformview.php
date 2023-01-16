<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
               <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian</label>
                        <label class="col-sm-3">Bulan</label>
                        <label class="col-sm-3">Tahun</label>
                        <label class="col-sm-3">Area</label>
                       
                        <div class="col-sm-3">
                            <input type="text" id="ibagian" name="ibagian" class="form-control input-sm" required="" disabled="disabled" value="<?= $bagian; ?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                            <input type="text" id="bulan" name="bulan" class="form-control input-sm" required="" disabled="disabled" >                                
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="tahun" name="tahun" class="form-control input-sm" required="" disabled="disabled" value="<?= $tahun; ?>">
                        </div> 
                        <div class="col-sm-3">
                            <input type="text" id="kode_area" name="kode_area" class="form-control input-sm" required="" disabled="disabled" value="<?= $area; ?>">
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Total</label>
                        <div class="col-sm-3">
                            <input type="text" id="total" name="total" class="form-control input-sm" disabled="disabled"
                                >
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2"
                                onclick="show('<?= $folder; ?>/cform/index/','#main'); return false;"> <i
                                    class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Targer Penjualan</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 30%;">Kota</th>
                        <th class="text-center" style="width: 30%;">Sales</th>
                        <th class="text-center" style="width: 34%;">Target</th>
                    </tr>
                </thead>
                <tbody>                   
                <?php 
                    $total = 0;
                    $i = 0;
                        foreach ($data as $row) {
                            ?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                                <td>
                                   <?= $row->e_city_name;?>
                                </td>
                                <td>
                                   <?= $row->e_sales;?>
                                </td>
                                <td>
                                   <div id="target<?= $i;?>"><?= $row->v_target;?></div>
                                </td>
                            </tr>
                        <?php 
                        $total = $total + $row->v_target;
                        $i++; } 
                    ?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width : '100%',
        });

        $('#bulan').datepicker({
            format: "MM",
            viewMode: "months", 
            minViewMode: "months",
            autoclose:true
        }).datepicker("update", "<?= $bulan; ?>");

        $('#tahun').datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years",
            autoclose:true
        });

        var counter = 0;
        var jml = $('#jml').val();

        for(counter;counter<jml;counter++){
            new AutoNumeric('#target' + counter, {
            aSep: '.', 
            aDec: ',',
            decimalPlaces:'0',
            aForm: true,
            unformatOnSubmit: true,
            vMax: '999999999',
            vMin: '-999999999',

            }); 
        }

        var checktotal = <?php echo $total; ?>;
        checktotal = toCommas(checktotal);
        document.getElementById("total").value = checktotal;
    });

    function toCommas(value) {
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

</script>
