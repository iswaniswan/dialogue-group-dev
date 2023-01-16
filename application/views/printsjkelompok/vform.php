<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/cetak'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-md-12">Date From</label>
                            <input readonly id="dfrom" name="dfrom" class="form-control date" required="" onchange="cekdfrom(this.value);" />
                        </div>
                        <div class="col-sm-6">
                            <label class="col-md-12">Date To</label>
                            <input readonly id="dto" name="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>" onchange="cekdto(this.value);"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" class="form-control select2" disabled="" onchange="cekarea(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SJ From</label>
                        <div class="col-sm-12">
                            <select name="sjfrom" id="sjfrom" class="form-control select2" disabled="" onchange="ceksjfrom(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SJ To</label>
                        <div class="col-sm-12">
                            <select name="sjto" id="sjto" class="form-control select2" disabled="" onchange="ceksjto(this.value);">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>                
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-print"></i>&nbsp;&nbsp;Cetak</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>
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
    function cekdfrom(dfrom) {
        var dto = $("#dto").val();
        if (dfrom != '' && dto != '') {
            $("#iarea").attr("disabled", false);
        }else{
            $("#iarea").attr("disabled", true);
        }
        $('#iarea').html('');
        $('#iarea').val('');
    }

    function cekdto(dto) {
        var dfrom = $("#dfrom").val();
        if (dfrom != '' && dto != '') {
            $("#iarea").attr("disabled", false);
        }else{
            $("#iarea").attr("disabled", true);
        }
        $('#iarea').html('');
        $('#iarea').val('');
    }

    function cekarea(iarea) {
        var dfrom = $("#dfrom").val();
        var dto = $("#dto").val();
        if (dfrom != '' && dto != '' && iarea != '') {
            $("#sjfrom").attr("disabled", false);
        }else{
            $("#sjfrom").attr("disabled", true);
        }
        $('#sjfrom').html('');
        $('#sjfrom').val('');
    }

    function ceksjfrom(sjfrom) {
        var dfrom = $("#dfrom").val();
        var dto = $("#dto").val();
        var iarea = $("#iarea").val();
        $.ajax({
            type: "post",
            data: {
                'dfrom' : dfrom,
                'dto': dto,
                'iarea'    : iarea
            },
            url: '<?= base_url($folder.'/cform/getsjfrom'); ?>',
            dataType: "json"
        });

        if (dfrom != '' && dto != '' && iarea != '' && sjfrom != '') {
            $("#sjto").attr("disabled", false);
        }else{
            $("#sjto").attr("disabled", true);
        }
        $('#sjto').html('');
        $('#sjto').val('');
    }


    function yyy(b,c){
    lebar =450;
    tinggi=400;
    eval('window.open("<?php echo site_url(); ?>"+"printsjkelompok/cform/cetak/"+b+"/"+c,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
  }

    $(document).ready(function () {
        showCalendar('.date');

        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getarea/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
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

        $('#sjfrom').select2({
            placeholder: 'Cari SJ From',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsjfrom/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dfrom    = $('#dfrom').val();
                    var dto    = $('#dto').val();
                    var iarea    = $('#iarea').val();
                    var query = {
                        q: params.term,
                        dfrom: dfrom,
                        dto: dto,
                        iarea:iarea
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

        $('#sjto').select2({
            placeholder: 'Cari SJ To',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsjto/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dfrom    = $('#dfrom').val();
                    var dto    = $('#dto').val();
                    var iarea    = $('#iarea').val();
                    var sjfrom    = $('#sjfrom').val();
                    var query = {
                        q: params.term,
                        dfrom: dfrom,
                        dto         : dto,
                        iarea       :iarea,
                        sjfrom     :sjfrom
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

</script>