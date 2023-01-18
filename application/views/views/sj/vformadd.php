<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Tanggal SJ</label>
                        <div class="col-sm-12">
                            <input type="text" readonly="" name="dsj" id="dsj" class="form-control date" value="<?= date('d-m-Y');?>" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Toko</label>
                        <div class="col-sm-12">
                            <input type="text" name="ecustomer" id="ecustomer" class="form-control" readonly="" value="">
                            <input type="hidden" name="icustomer" id="icustomer" class="form-control" readonly="" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                class="fa fa-plus"></i>&nbsp;&nbsp;Proses</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-12">Area</label>
                            <div class="col-sm-12">
                                <select name="iarea" id="iarea" class="form-control select2" required="" onchange="get(this.value);">
                                    <option value="">-- Pilih Area --</option>
                                    <?php if($area){ foreach ($area->result() as $kuy):?>
                                        <option value="<?php echo $kuy->i_area;?>">
                                            <?php echo $kuy->i_area." - ".$kuy->e_area_name;?></option>
                                        <?php endforeach; 
                                    } ?>
                                </select>
                                <input type="hidden" name="istore" id="istore" class="form-control" readonly="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">SPB</label>
                            <div class="col-sm-12">
                                <select name="ispb" id="spbnya" class="form-control select2" onchange="getcus((this.value), document.getElementById('iarea').value);">
                                </select>
                                <input type="hidden" name="dspb" id="dspb" class="form-control" readonly="">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $(".select2").select2();
        $("#iarea").select2();
        showCalendar('.date', 0, 1);
    });

    function get(iarea) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getspb');?>",
            data:"iarea="+iarea,
            dataType: 'json',
            success: function(data){
                $("#spbnya").html(data.kop);
                $("#istore").val(data.sok);
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function getcus(ispb, iarea) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getcus');?>",
            data:"ispb="+ispb+"&iarea="+iarea,
            dataType: 'json',
            success: function(data){
                $("#ecustomer").val(data.tah);
                $("#icustomer").val(data.sip);
                $("#dspb").val(data.spb);
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
    }
</script>