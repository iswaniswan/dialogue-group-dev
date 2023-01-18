<?php 
include ("php/fungsi.php");
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Nomor KU Masuk</label>
                            <div class="col-sm-6">
                                <input type="text" size="10" id="ikum" name="ikum" class="form-control" value="<?php echo $row->i_kum; ?>" readonly>
				            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="eareaname" name="eareaname" class="form-control" value="<?php echo $row->e_area_name; ?>">
    		                <input type="hidden" hidden id="iarea" name="iarea" class="form-control" value="<?php echo $row->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="ecustomername" name="ecustomername" class="form-control" value="<?php echo $row->e_customer_name; ?>">
			                <input type="hidden" readonly id="icustomer" name="icustomer" class="form-control" value="<?php echo $row->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="vjumlah" name="vjumlah" class="form-control" value="<?php echo number_format($row->v_jumlah); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Ket Cek</label>
                        <div class="col-sm-6">
                            <input name="ecek" id="ecek" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Di Cek</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>                    
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal KU Masuk</label>
                        <div class="col-sm-3">
                        <input type="text" id="dkum" name="dkum" class="form-control" value="<?php echo $row->d_kum; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Bank</label>
                        <div class="col-sm-6">
                            <input readonly id="ebank" name="ebank" class="form-control" value="<?php echo $row->e_bank_name; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="esalesname" name="esalesname" class="form-control" value="<?php echo $row->e_salesman_name; ?>">
		                    <input type="hidden" readonly id="isalesman" name="isalesman" class="form-control" value="<?php echo $row->i_salesman; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Sisa</label>
                        <div class="col-sm-6">
                            <input type="text" readonly id="vsisa" name="vsisa" class="form-control" value="<?php echo number_format($row->v_sisa); ?>">
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
 $(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
 });
</script>
