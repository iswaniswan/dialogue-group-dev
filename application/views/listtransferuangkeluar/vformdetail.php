<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <?php 
                if($isi->d_kuk!=''){
                    $tmp=explode('-',$isi->d_kuk);
                    $tgl=$tmp[2];
                    $bln=$tmp[1];
                    $thn=$tmp[0];
                    $isi->d_kuk=$tgl.'-'.$bln.'-'.$thn;
                }
                ?>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Bukti Transfer</label>
                        <div class="col-sm-5">
                            <input class="form-control" name="ikuk" id="ikuk" maxlength="15" value="<?= $isi->i_kuk; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control date" name="dkuk" id="dkuk" readonly="" required="" value="<?= $isi->d_kuk; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Pemasok</label>
                        <div class="col-sm-8">
                            <select name="isupplier" id="isupplier" class="form-control select2" required="">
                                <option value="<?= $isi->i_supplier;?>"><?= $isi->e_supplier_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jumlah</label>
                        <div class="col-sm-8">
                            <input name="vjumlah" id="vjumlah" class="form-control" autocomplete="off" required="" onkeypress="return hanyaAngka(event);" value="<?= number_format($isi->v_jumlah); ?>" onkeyup="reformat(this);hetang(this);">
                        </div>
                    </div>                  
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <?php if(check_role($i_menu,3) && ($isi->v_sisa==$isi->v_jumlah) && ($isi->f_kuk_cancel=='f')){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom.'/'.$dto.'/'.$isupplier;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">                    
                    <div class="form-group row">
                        <label class="col-md-3">Nama Bank</label>
                        <div class="col-sm-8">
                            <!-- <select name="ibank" id="ibank" class="form-control select2" required="">
                                <option value="<?= $isi->i_bank;?>"><?= $isi->e_bank_name;?></option>
                            </select> -->
                            <input type="text" readonly name="ebankname" id="ebankname" class="form-control" value="<?= $isi->e_bank_name;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-sm-8">
                            <input type="text" name="eremark" id="eremark" class="form-control" value="<?= $isi->e_remark; ?>">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Sisa</label>
                        <div class="col-sm-8">
                            <input name="vsisa" id="vsisa" class="form-control" value="<?= number_format($isi->v_sisa); ?>" readonly="" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);">
                        </div>
                    </div>     
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function cekarea(iarea) {
        if (iarea != '') {
            $("#icustomer").attr("disabled", false);
        }else{
            $("#icustomer").attr("disabled", true);
        }
        $('#icustomer').html('');
        $('#icustomer').val('');
    }

    function getbank() {
        var ebank = $('#ibank option:selected').text();
        $('#ebankname').val(ebank);
    }

    $(document).ready(function () {
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });

        $('#ibank').select2({
            placeholder: 'Pilih Bank'
        });

        $('#isupplier').select2({
            placeholder: 'Cari Supplier Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsupplier/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    function hetang(){
        $('#vsisa').val($('#vjumlah').val());
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    function dipales(){
        if((document.getElementById("ikum").value=='')||(document.getElementById("ibank").value=='')||(document.getElementById("dkum").value=='')){
            swal("Data Masih Ada yang Kososng !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>