<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <table id="tabledata" class="display nowrap" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center;" rowspan="2">No</th>
                                <th style="text-align: center;" rowspan="2">Tanggal</th>
                                <th style="text-align: center;" rowspan="2">No. Bukti</th>
                                <th style="text-align: center;" rowspan="2">Uraian</th>
                                <th style="text-align: center;" rowspan="2">No. Perk</th>
                                <th style="text-align: center;" colspan="2">Penerimaan</th>
                                <th style="text-align: center;" colspan="2">Pengeluaran</th>
                                <th style="text-align: center;" colspan="2">Saldo Akhir</th>
                                <th style="text-align: center;" rowspan="2">Di Cek</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Tunai</th>
                                <th style="text-align: center;">Giro</th>
                                <th style="text-align: center;">Tunai</th>
                                <th style="text-align: center;">Giro</th>
                                <th style="text-align: center;">Tunai</th>
                                <th style="text-align: center;">Giro</th>
                            </tr>
                            <tr>
                                <th colspan="4" style="text-align: center;">Saldo Awal Per <?= date('d', strtotime($dfrom))." ".mbulan(date('m', strtotime($dfrom)))." ".date('Y', strtotime($dfrom));?></th>
                                <th colspan="4" style="text-align: left;">111.3<?= $iarea;?></th>
                                <th colspan="2" style="text-align: right;"><?= "Rp. ".number_format($saldo->saldotunai);?></th>
                                <th colspan="2" style="text-align: left;"><?= "Rp. ".number_format($saldo->saldogiro);?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <input type="hidden" name="dfrom" value="<?= $dfrom;?>">
                    <input type="hidden" name="dto" value="<?= $dto;?>">
                    <input type="hidden" name="iarea" value="<?= $iarea;?>">
                    <div class="form-group row" style="text-align: center;">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Dicek</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
</script>