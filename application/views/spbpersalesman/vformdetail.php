<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title.$isalesman." - ".$esalesman; ?><a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Periode Awal</th>
                            <th>Periode Akhir</th>
                            <th>Kode Product</th>
                            <th>Nama Product</th>
                            <th>Jumlah Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <!-- <br> -->
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/rinci/<?= $dfrom."/".$dto."/".$isalesman;?>/');
    });
</script>